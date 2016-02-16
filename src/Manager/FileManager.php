<?php

namespace Todstoychev\Icr\Manager;

use Illuminate\Filesystem\FilesystemAdapter;

/**
 * File manager class
 *
 * @package Todstoychev\Icr
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class FileManager
{
    /**
     * @var FilesystemAdapter
     */
    protected $filesystemAdapter;

    /**
     * @param FilesystemAdapter $filesystemAdapter
     *
     * @return FileManager
     */
    public function setFileSystemAdapter(FilesystemAdapter $filesystemAdapter)
    {
        $this->filesystemAdapter = $filesystemAdapter;

        return $this;
    }

    /**
     * Uploads a file
     *
     * @param string $file File contents
     * @param string $extension File extension
     * @param null|string $context File context
     * @param null|string $sizeName Size directory name
     * @param null|string $fileName Predefined filename
     *
     * @return string
     */
    public function uploadFile($file, $extension, $context = null, $sizeName = null, $fileName = null)
    {
        $path = $this->path($context, $sizeName);
        $hash = $this->generate($this->filesystemAdapter, $extension, $path);

        if (null === $fileName) {
            $fileName = $hash . '.' . $extension;
        }

        $path = $path . $fileName;

        $this->filesystemAdapter->put($path, $file);

        return $fileName;
    }

    /**
     * Define path
     *
     * @param null|string $context File context
     * @param null|string $sizeName Size name
     *
     * @return string
     */
    public function path($context = null, $sizeName = null)
    {
        $path = '/';
        $path .= (null !== $context) ? $context . '/' : null;
        $path .= (null !== $sizeName) ? $sizeName . '/' : null;

        return $path;
    }

    /**
     * Generates unique filename
     *
     * @param FilesystemAdapter $filesystemAdapter
     * @param string $extension File extension
     * @param null|string $path Directory path
     *
     * @return string
     */
    public function generate(FilesystemAdapter $filesystemAdapter, $extension, $path = null)
    {
        $hash = sha1(time() + microtime());

        $exists = $filesystemAdapter->exists($path . '/' . $hash . '.' . $extension);

        if ($exists) {
            $this->generate($filesystemAdapter, $extension, $path);
        }

        return $hash;
    }
}