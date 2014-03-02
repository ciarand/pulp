<?php namespace Ciarand\Pulp\Resource;

class FileWriter
{
    protected $folder;

    protected $factory;

    public function __construct($folder)
    {
        $this->folder = $folder;
        $this->factory = new ResourceFactory;
    }

    public function write(FileResource $source)
    {
        $path        = sprintf("%s/%s", $this->folder, $source->getName());
        $destination = $this->factory->makeWriteableFileResource($path);

        $source->pipe($destination);
        $source->begin();
    }
}
