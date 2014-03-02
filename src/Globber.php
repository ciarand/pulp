<?php namespace Ciarand\Pulp;

class Globber implements \Countable
{
    protected $glob;

    protected $cache;

    protected $latestFiles;

    public function __construct($glob)
    {
        $this->glob = $glob;
        $this->cache = $this->getLastModifiedTime();
    }

    public function hasChanged()
    {
        $latest = $this->getLastModifiedTime();
        return ($latest > $this->cache) && ($this->cache = $latest);
    }

    public function getFiles()
    {
        return ($this->latestFiles = glob($this->glob, GLOB_BRACE));
    }

    protected function getLastModifiedTime()
    {
        \clearstatcache();
        $files = $this->getFiles();
        $times = array_combine($files, array_map("filemtime", $files));
        arsort($times);

        return reset($times);
    }

    public function count()
    {
        return count($this->getFiles());
    }
}
