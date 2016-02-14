<?php

namespace Todstoychev\Icr\Manager;

use Illuminate\Filesystem\FilesystemAdapter;

/**
 * Class FileManager
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
     * @var UniqueFileNameGenerator
     */
    protected $uniqueFileNameGenerator;

    public function __construct(FilesystemAdapter $filesystemAdapter, UniqueFileNameGenerator $uniqueFileNameGenerator)
    {
        $this->filesystemAdapter = $filesystemAdapter;
        $this->uniqueFileNameGenerator = $uniqueFileNameGenerator;
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
        $hash = $this->uniqueFileNameGenerator->generate($this->filesystemAdapter, $extension, $path);

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
}