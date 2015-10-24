<?php

namespace Todstoychev\Icr\Handler;

/**
 * Directory handler. Provides methods to handle missing directory creation adn deleting directory and files.
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\Handler
 */
class DirectoryHandler extends AbstractHandler
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $context;

    /**
     * @param string $uploadsPath
     * @param array $sizes
     */
    public function __construct(array $config = [], $context = '')
    {
        $this->setConfig($config);
        $this->setContext($context);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     *
     * @return DirectoryHandler
     */
    public function setConfig($config)
    {
        $this->config = $config;

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
     *
     * @return DirectoryHandler
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    public function checkAndCreateDirectories($context)
    {
        $this->setContext($context);

        $path = public_path($this->getUploadsPath());

        // Check and create uploads directory
        if (!is_dir($path)) {
            mkdir($path, 0777, true);

            // Create context directory structure
            mkdir($path.'/'.$this->getContext(), 0777);

            foreach ($this->config[$this->getContext()] as $sizeName => $values) {
                mkdir($path.'/'.$this->getContext().'/'.$sizeName, 0777);
            }
        }

        return $this;
    }

    public function deleteContextFilesAndDirectories()
    {
        foreach ($this->config[$this->getContext()] as $sizeName => $values) {
            $path = public_path($this->getUploadsPath() . '/' . $this->getContext() . '/' . $sizeName);
            if (is_dir($path)) {
                $files = scandir($path);
                $files = array_diff($files, ['.', '..']);

                foreach ($files as $file) {
                    unlink($path . '/' . $file);
                }

                rmdir($path);
            }
        }

        return $this;
    }
}