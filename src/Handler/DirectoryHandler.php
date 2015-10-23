<?php

namespace Todstoychev\Icr\Handler;

/**
 * Directory handler. Provides methods to handle missing directory creation
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\Handler
 */
class DirectoryHandler
{
    /**
     * @var string
     */
    protected $uploadsPath;

    /**
     * @var array
     */
    protected $sizes;

    /**
     * @var string
     */
    protected $context;

    /**
     * @param string $uploadsPath
     * @param array $sizes
     */
    public function __construct($uploadsPath, array $sizes, $context)
    {
        $this->setUploadsPath($uploadsPath);
        $this->setSizes($sizes);
        $this->setContext($context);
    }

    /**
     * @return mixed
     */
    public function getUploadsPath()
    {
        return $this->uploadsPath;
    }

    /**
     * @param string $uploadsPath
     * @return DirectoryHandler
     */
    public function setUploadsPath($uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;

        return $this;
    }

    /**
     * @return array
     */
    public function getSizes()
    {
        return $this->sizes;
    }

    /**
     * @param array $sizes
     * @return DirectoryHandler
     */
    public function setSizes($sizes)
    {
        $this->sizes = $sizes;

        return $this;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string $context
     * @return DirectoryHandler
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }


    /**
     * Check if directory exists if not creates it. Necessary to provide the irectory structure for saving
     * the image files.
     */
    public function checkAndCreateDirectory()
    {
        $folders = explode('/', $this->uploadsPath);
        $path = public_path();

        // Create base dir structure
        foreach ($folders as $folder) {
            if (strlen($folder) > 0) {
                $path .= '/' . $folder;
                $this->createDirectory($path);
            }
        }

        // Create context directory structure
        $this->createDirectory($path . '/' . $this->getContext());

        foreach ($this->sizes as $size => $data) {
            $this->createDirectory($path . '/' . $this->getContext() . '/' . $size);
        }
    }

    /**
     * Creates directory if not exists
     *
     * @param string $path Path to directory
     */
    protected function createDirectory($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0755);
        }
    }
}