<?php

namespace Todstoychev\Icr;

use Illuminate\Filesystem\FilesystemAdapter;
use Todstoychev\Icr\Handler\OpenImageHandler;
use Todstoychev\Icr\Manager\FileManager;
use Todstoychev\Icr\Manipulator\ManipulatorFactory;

/**
 * Processes images.
 *
 * @package Todstoychev\Icr
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class Processor
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var ManipulatorFactory
     */
    protected $manipulatorFactory;

    /**
     * @var OpenImageHandler
     */
    protected $openImageHandler;

    /**
     * @var FileManager
     */
    protected $fileManager;

    /**
     * @param array $config
     * @param ManipulatorFactory $manipulatorFactory
     * @param OpenImageHandler $openImageHandler
     * @param FileManager $fileManager
     */
    public function __construct(
        array $config,
        ManipulatorFactory $manipulatorFactory,
        OpenImageHandler $openImageHandler,
        FileManager $fileManager
    ) {
        $this->config = $config;
        $this->manipulatorFactory = $manipulatorFactory;
        $this->openImageHandler = $openImageHandler;
        $this->openImageHandler->setImageLibrary($this->config['image_adapter']);
        $this->fileManager = $fileManager;
    }

    /**
     * Handles image upload
     *
     * @param string $context
     * @param string $file
     * @param string $extension
     * @param FilesystemAdapter $filesystemAdapter
     *
     * @return string
     */
    public function upload($context, $file, $extension, FilesystemAdapter $filesystemAdapter)
    {
        // Upload original image
        $fileName = $this->fileManager->setFileSystemAdapter($filesystemAdapter)
            ->uploadFile($file, $extension, $context);

        $this->processSizes($file, $fileName, $context, $extension, $this->fileManager);

        return $fileName;
    }

    /**
     * Delete file
     *
     * @param string $fileName File to delete
     * @param string $context Context name
     * @param FilesystemAdapter $filesystemAdapter
     *
     * @return bool
     */
    public function delete($fileName, $context, FilesystemAdapter $filesystemAdapter)
    {
        // Delete sizes
        $this->deleteSizes($fileName, $context, $filesystemAdapter);

        // Delete original file
        $filesystemAdapter->delete('/' . $context . '/' . $fileName);

        return true;
    }

    /**
     * Rebuilds image sizes
     *
     * @param string $fileName
     * @param string $context
     * @param FilesystemAdapter $filesystemAdapter
     *
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function rebuild($fileName, $context, FilesystemAdapter $filesystemAdapter)
    {
        // Open original file
        $originalImage = $filesystemAdapter->get('/' . $context . '/' . $fileName);
        $originalImage = $this->openImageHandler->loadImage($originalImage);
        $extension = $this->findExtension($fileName);

        // Rebuild sizes
        $this->deleteSizes($fileName, $context, $filesystemAdapter);
        $this->fileManager->setFileSystemAdapter($filesystemAdapter);
        $this->processSizes($originalImage, $fileName, $context, $extension, $filesystemAdapter);

        return true;
    }

    /**
     * Deletes sizes
     *
     * @param string $fileName File name
     * @param string $context Context name
     * @param FilesystemAdapter $filesystemAdapter
     *
     * @return Processor
     */
    protected function deleteSizes($fileName, $context, FilesystemAdapter $filesystemAdapter)
    {
        $this->fileManager->setFileSystemAdapter($filesystemAdapter);

        foreach ($this->config[$context] as $sizeName => $values) {
            $path = $this->fileManager->path($context, $sizeName);
            $filesystemAdapter->delete($path . $fileName);
        }

        return $this;
    }

    /**
     * Find file extension
     *
     * @param string $fileName
     *
     * @return string
     */
    protected function findExtension($fileName)
    {
        preg_match('/.[a-Z]{3,4}$/', $fileName, $matches);

        return array_shift($matches);
    }

    /**
     * Process sizes
     *
     * @param string $file File data
     * @param string $fileName File name
     * @param string $context File context
     * @param string $extension File extension
     * @param FilesystemAdapter $filesystemAdapter
     *
     * @return Processor
     */
    public function processSizes($file, $fileName, $context, $extension, FilesystemAdapter $filesystemAdapter)
    {
        foreach ($this->config[$context] as $sizeName => $values) {
            $image = $this->openImageHandler->loadImage($file);
            $operation = $this->manipulatorFactory->create(
                $values['operation']
            );
            $image = $operation->manipulate($image, $values['width'], $values['height']);

            $this->fileManager->setFileSystemAdapter($filesystemAdapter)
                ->uploadFile($image, $extension, $context, $sizeName, $fileName);
        }

        return $this;
    }
}