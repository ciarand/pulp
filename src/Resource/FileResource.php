<?php namespace Ciarand\Pulp\Resource;

use Evenement\EventEmitter;
use React\Stream\Util;
use React\Stream\ReadableStreamInterface;
use React\Stream\WritableStreamInterface;

class FileResource extends EventEmitter implements
    ReadableStreamInterface,
    WritableStreamInterface
{
    protected $stream;

    protected $readable;

    protected $writable;

    protected $closed;

    protected $name;

    public function __construct($file, $mode)
    {
        list($this->name, $this->stream) = [$file, fopen($file, $mode)];
    }

    public function pipe(WritableStreamInterface $dest, array $options = array())
    {
        Util::pipe($this, $dest, $options);

        return $dest;
    }

    public function isReadable()
    {
        return $this->readable;
    }

    public function isWritable()
    {
        return $this->isWritable();
    }

    public function write($data)
    {
        fwrite($this->stream, $data);
    }

    public function pause()
    {

    }

    public function resume()
    {

    }

    public function end($data = null)
    {

    }

    public function close()
    {

    }

    public function getName()
    {
        return $this->name;
    }

    public function begin()
    {
        while (!feof($this->stream)) {
            $this->emit("data", [fread($this->stream, 4096)]);
        }

        $this->emit("end");
    }
}
