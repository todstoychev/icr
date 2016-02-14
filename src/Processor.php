<?php

namespace Todstoychev\Icr;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Todstoychev\Icr\Handler\OpenImage;
use Todstoychev\Icr\Manager\FileManager;
use Todstoychev\Icr\Manager\UniqueFileNameGenerator;
use Todstoychev\Icr\Manipulator\Box;
use Todstoychev\Icr\Manipulator\ManipulatorFactory;
use Todstoychev\Icr\Manipulator\Point;

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
     * @var OpenImage
     */
    protected $openImageHandler;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->manipulatorFactory = new ManipulatorFactory(new Box, new Point());
        $this->openImageHandler = new OpenImage($this->config['image_adapter']);
    }

    /**
     * Handles image upload
     *
     * @param string $context
     * @param UploadedFile $uploadedFile
     * @param FilesystemAdapter $filesystemAdapter
     *
     * @return string
     */
    public function upload($context, UploadedFile $uploadedFile, FilesystemAdapter $filesystemAdapter)
    {
        $file = File::get($uploadedFile);
        $extension = $uploadedFile->getClientOriginalExtension();
        $fileManager = new FileManager($filesystemAdapter, new UniqueFileNameGenerator());

        // Upload original image
        $fileName = $fileManager->uploadFile($file, $extension, $context);

        $this->processSizes($file, $fileName, $context, $extension, $fileManager);

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
        $fileManager = new FileManager($filesystemAdapter, new UniqueFileNameGenerator());
        $this->processSizes($originalImage, $fileName, $context, $extension, $fileManager);

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
        $fileManager = new FileManager($filesystemAdapter, new UniqueFileNameGenerator());

        foreach ($this->config[$context] as $sizeName => $values) {
            $path = $fileManager->path($context, $sizeName);
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
     * @param FileManager $fileManager
     *
     * @return Processor
     */
    public function processSizes($file, $fileName, $context, $extension, FileManager $fileManager)
    {
        foreach ($this->config[$context] as $sizeName => $values) {
            $image = $this->openImageHandler->loadImage($file);
            $operation = $this->manipulatorFactory->create(
                $image,
                $values['operation'],
                $values['width'],
                $values['height']
            );
            $image = $operation->manipulate();

            $fileManager->uploadImage($image, $extension, $context, $sizeName, $fileName);
        }

        return $this;
    }
}