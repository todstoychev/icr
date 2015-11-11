<?php

namespace Todstoychev\Icr\Reader;

/**
 * Directory tree reader. Used to get directory and file names.
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\Reader
 */
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

    /**
     * @param string $path
     */
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
     * @return DirectoryTreeReader
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

    /**
     * Read directory names and existing file names. Ignores duplicated. Directory names can be called with
     * @example $this->getDirectoryNames()
     * File names can be called with
     * @example $this->getFileNames()
     *
     * @return DirectoryTreeReader
     */
    public function read()
    {
        $directoryIterator = new \RecursiveDirectoryIterator($this->getPath());
        $objects = new \RecursiveIteratorIterator($directoryIterator, \RecursiveIteratorIterator::CHILD_FIRST);

        $array = [];

        foreach ($objects as $splFileInfo) {
            if ($splFileInfo->isDir() && !preg_match('/\.$/', $splFileInfo->getFileName())) {
                $this->directoryNames[] = $splFileInfo->getFileName();
                continue;
            }

            if (
                !in_array($splFileInfo->getFileName(), $this->fileNames) &&
                !preg_match('/\.$/', $splFileInfo->getFileName())
            ) {
                $this->fileNames[] = $splFileInfo->getFileName();
            }
        }

        return $this;
    }
}