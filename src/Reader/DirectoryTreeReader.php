<?php

namespace Todstoychev\Icr\Reader;

class DirectoryTreeReader
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $fileNames = [];

    /**
     * @var array
     */
    protected $directoryNames = [];

    public function __construct($path = '')
    {
        $this->setPath($path);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return FilenamesReader
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return array
     */
    public function getFileNames()
    {
        return $this->fileNames;
    }

    /**
     * @return array
     */
    public function getDirectoryNames()
    {
        return $this->directoryNames;
    }

    public function read()
    {
        $directoryIterator = new \RecursiveDirectoryIterator($this->getPath());
        $objects = new \RecursiveIteratorIterator($directoryIterator, \RecursiveIteratorIterator::CHILD_FIRST);

        $array = [];

        foreach ($objects as $splFileInfo) {
            if ($splFileInfo->isDir() && !preg_match('/\./', $splFileInfo->getFileName())) {
                $this->directoryNames[] = $splFileInfo->getFileName();
                continue;
            }

            if (!in_array($splFileInfo->getFileName(), $this->fileNames)) {
                $this->fileNames[] = $splFileInfo->getFileName();
            }
        }

        return $this;
    }
}