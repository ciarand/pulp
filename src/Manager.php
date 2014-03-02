<?php namespace Ciarand\Pulp;

use React\EventLoop\Factory as LoopFactory;
use Evenement\EventEmitter;
use Ciarand\Pulp\Stream\SourceStream;
use Ciarand\Pulp\Stream\DestinationStream;

class Manager extends EventEmitter
{
    protected $loop;

    protected $tasks;

    protected $currentTask;

    public function __construct($loop = null)
    {
        $this->loop = $loop ?: LoopFactory::create();
    }

    public function task($name, array $reqs = array(), $callback = null)
    {
        if ($callback === null) {
            throw new \Exception("No callback provided for {$name} task");
        }

        if (!is_callable($callback)) {
            throw new \Exception("Callback for {$name} task is not callable");
        }

        // Add a new task
        $this->tasks[$name] = ($task = new Task($callback, $name));
        $task->setRequirements($reqs);
    }

    public function run($taskName = "default")
    {
        $this->runTasks((array) $taskName);
        return $this->loop->run();
    }

    public function watch($glob, $reqs)
    {
        $globber = new Globber($glob);
        $emitter = new EventEmitter;

        $this->loop->addPeriodicTimer(.200, function () use ($globber, $reqs, $emitter) {
            if ($globber->hasChanged()) {
                $emitter->emit("data");
                $this->runTasks($reqs);
            }
        });

        return $emitter;
    }

    public function src($glob)
    {
        $stream = new SourceStream(new Globber($glob));

        $this->on("task_complete", function (Task $task) use ($stream) {
            if ($task !== $this->currentTask) {
                return;
            }

            $stream->begin();
        });

        return $stream;
    }

    public function dest($dest)
    {
        return new DestinationStream($dest);
    }

    public function runTasks($tasks)
    {
        foreach ($tasks as $task) {
            $this->runTask($task);
        }
    }

    protected function runTask($taskName)
    {
        if (!isset($this->tasks[$taskName])) {
            throw new \Exception("{$taskName} task is not defined");
        }

        // Get the Task object
        $task = $this->tasks[$taskName];

        // Store the current previous task, set a new one
        list($previousTask, $this->currentTask) = [$this->currentTask, $task];

        // Run the task's requirements (recursively)
        foreach ($task->getRequirements() as $req) {
            $this->runTask($req);
        }

        // Run the task
        $task->run();

        $this->emit("task_complete", [$task]);
        // Reset the current task to whatever it was before
        $this->currentTask = $previousTask;
    }

    public function log($message)
    {
        $from = "Build";
        if ($this->currentTask instanceof Task) {
            $from = $this->currentTask->getName();
        }

        printf("[%s]: %s" . PHP_EOL, $from, $message);
    }
}
