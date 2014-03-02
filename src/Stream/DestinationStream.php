<?php namespace Ciarand\Pulp\Stream;

use Ciarand\Pulp\Globber;
use Ciarand\Pulp\Resource\FileResource;
use Ciarand\Pulp\Resource\FileWriter;
use React\Stream\WritableStream;

class DestinationStream extends WritableStream
{
    protected $fileWriter;

    public function __construct($folder)
    {
        $this->fileWriter = new FileWriter($folder);
    }

    public function write($resource)
    {
        $this->fileWriter->write($resource);
    }
}
