<?php namespace Ciarand\Pulp\Stream;

use Ciarand\Pulp\Globber;
use Ciarand\Pulp\Resource\ResourceFactory;
use React\Stream\ReadableStream;

class SourceStream extends ReadableStream
{
    protected $globber;

    public function __construct(Globber $globber)
    {
        $this->globber = $globber;
    }

    public function begin()
    {
        $factory = new ResourceFactory;

        foreach ($this->globber->getFiles() as $resource) {
            $this->emit("data", [$factory->makeReadableFileResource($resource)]);
        }

        $this->emit("end");
    }
}
