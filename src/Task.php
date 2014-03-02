<?php namespace Ciarand\Pulp;

class Task
{
    protected $callback;

    protected $name;

    protected $requirements = array();

    public function __construct($callback, $name)
    {
        $this->callback = $callback;
        $this->name = $name;
    }

    public function run()
    {
        return call_user_func($this->callback);
    }

    public function setRequirements(array $requirements)
    {
        $this->requirements = $requirements;
    }

    public function getRequirements()
    {
        return $this->requirements;
    }

    public function getName()
    {
        return $this->name;
    }
}
