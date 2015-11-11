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
     * Checks if directory exists. If directory is missing, creates it.
     *
     * @param string $context
     *
     * @return DirectoryHandler
     * @throws \Todstoychev\Icr\Exception\NonExistingArrayKeyException
     */
    public function checkAndCreateDirectories($context)
    {
        $path = public_path($this->getUploadsPath());

        // Check and create uploads directory
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        if (!is_dir($path . '/' . $context)) {
            // Create context directory structure
            mkdir($path . '/' . $context, 0777);
        }

        foreach ($this->config[$context] as $sizeName => $values) {
            if (!is_dir($path . '/' . $context . '/' . $sizeName)) {
                mkdir($path . '/' . $context . '/' . $sizeName, 0777);
            }
        }

        return $this;
    }

    /**
     * Delete all context files and directories in the directory tree
     *
     * @param string $context
     *
     * @return DirectoryHandler
     * @throws \Todstoychev\Icr\Exception\NonExistingArrayKeyException
     */
    public function deleteContextFilesAndDirectories($context)
    {
        foreach ($this->config[$context] as $sizeName => $values) {
            $path = public_path($this->getUploadsPath() . '/' . $context . '/' . $sizeName);
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