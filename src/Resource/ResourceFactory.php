<?php namespace Ciarand\Pulp\Resource;

class ResourceFactory
{
    public function makeReadableFileResource($file)
    {
        if (!is_readable($file)) {
            throw new \Exception("{$file} is not readable");
        }

        return $this->makeFileResource($file, "r");
    }

    public function makeWriteableFileResource($file)
    {
        if (!is_writeable($file) && !$this->makeDirStructure($file)) {
            throw new \Exception("{$file} is not writable");
        }

        return $this->makeFileResource($file, "w");
    }

    protected function makeDirStructure($file)
    {
        $directory = pathinfo($file, PATHINFO_DIRNAME);

        return is_dir($directory) || mkdir($directory, 0755, true);
    }

    protected function makeFileResource($file, $mode)
    {
        return new FileResource($file, $mode);
    }
}
