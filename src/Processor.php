<?php

namespace Todstoychev\Icr;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Todstoychev\Icr;
use Todstoychev\Icr\Handler;
use Todstoychev\Icr\Reader\DirectoryTreeReader;

class Processor
{
    /**
     * @var Handler\ConfigurationValidationHandler
     */
    protected $configurationValidationHandler;

    /**
     * @var Handler\DirectoryHandler
     */
    protected $directoryHandler;

    /**
     * @var Handler\UploadedFileHandler
     */
    protected $uploadedFileHandler;

    /**
     * @var Handler\OpenImageHandler
     */
    protected $openImageHandler;

    /**
     * @var Handler\DeleteImageHandler
     */
    protected $deleteImageHandler;

    /**
     * @var DirectoryTreeReader
     */
    protected $directoryTreeReader;

    public function __construct(
        Handler\ConfigurationValidationHandler $configurationValidationHandler,
        Handler\DirectoryHandler $directoryHandler,
        Handler\UploadedFileHandler $uploadedFileHandler,
        Handler\OpenImageHandler $openImageHandler,
        Handler\DeleteImageHandler $deleteImageHandler,
        DirectoryTreeReader $directoryTreeReader
    ) {
        $this->setConfigurationValidationHandler($configurationValidationHandler);
        $this->setDirectoryHandler($directoryHandler);
        $this->setUploadedFileHandler($uploadedFileHandler);
        $this->setOpenImageHandler($openImageHandler);
        $this->setDeleteImageHandler($deleteImageHandler);
        $this->setDirectoryTreeReader($directoryTreeReader);
    }

    /**
     * @return Handler\ConfigurationValidationHandler
     */
    public function getConfigurationValidationHandler()
    {
        return $this->configurationValidationHandler;
    }

    /**
     * @param Handler\ConfigurationValidationHandler $configurationValidationHandler
     *
     * @return Processor
     */
    public function setConfigurationValidationHandler(
        Handler\ConfigurationValidationHandler $configurationValidationHandler
    ) {
        $this->configurationValidationHandler = $configurationValidationHandler;

        return $this;
    }

    /**
     * @return Handler\DirectoryHandler
     */
    public function getDirectoryHandler()
    {
        return $this->directoryHandler;
    }

    /**
     * @param Handler\DirectoryHandler $directoryHandler
     *
     * @return Processor
     */
    public function setDirectoryHandler(Handler\DirectoryHandler $directoryHandler)
    {
        $this->directoryHandler = $directoryHandler;

        return $this;
    }

    /**
     * @return Handler\UploadedFileHandler
     */
    public function getUploadedFileHandler()
    {
        return $this->uploadedFileHandler;
    }

    /**
     * @param Handler\UploadedFileHandler $uploadedFileHandler
     *
     * @return Processor
     */
    public function setUploadedFileHandler(Handler\UploadedFileHandler $uploadedFileHandler)
    {
        $this->uploadedFileHandler = $uploadedFileHandler;

        return $this;
    }

    /**
     * @return Handler\OpenImageHandler
     */
    public function getOpenImageHandler()
    {
        return $this->openImageHandler;
    }

    /**
     * @param Handler\OpenImageHandler $openImageHandler
     * @return Processor
     */
    public function setOpenImageHandler(Handler\OpenImageHandler $openImageHandler)
    {
        $this->openImageHandler = $openImageHandler;

        return $this;
    }

    /**
     * @return Handler\DeleteImageHandler
     */
    public function getDeleteImageHandler()
    {
        return $this->deleteImageHandler;
    }

    /**
     * @param Handler\DeleteImageHandler $deleteImageHandler
     *
     * @return Processor
     */
    public function setDeleteImageHandler(Handler\DeleteImageHandler $deleteImageHandler)
    {
        $this->deleteImageHandler = $deleteImageHandler;

        return $this;
    }

    /**
     * @return DirectoryTreeReader
     */
    public function getDirectoryTreeReader()
    {
        return $this->directoryTreeReader;
    }

    /**
     * @param DirectoryTreeReader $directoryTreeReader
     *
     * @return Processor
     */
    public function setDirectoryTreeReader(DirectoryTreeReader $directoryTreeReader)
    {
        $this->directoryTreeReader = $directoryTreeReader;

        return $this;
    }

    public function upload(UploadedFile $uploadedFile, $context)
    {
        try {
            $fileName = $this->uploadImage($uploadedFile, $context);

            $this->processImage($context, $fileName);
        } catch (\Exception $e) {
            $this->getDeleteImageHandler()->deleteImage($context, $fileName);

            return $e;
        }
    }

    public function delete($fileName, $context)
    {
        $this->getConfigurationValidationHandler()->validateContext($context);
        $this->getDeleteImageHandler()->deleteImage($context, $fileName);
    }

    public function rebuild($context)
    {
        $this->getDirectoryHandler()->deleteContextFilesAndDirectories($context)->checkAndCreateDirectories($context);

        $path = public_path($this->getDirectoryHandler()->getUploadsPath() . '/' . $context);

        $this->getDirectoryTreeReader()->setPath($path)->read();

        $fileNames = $this->getDirectoryTreeReader()->getFileNames();

        foreach ($fileNames as $fileName) {
            $this->processImage($context, $fileName);
        }
    }

    protected function uploadImage(UploadedFile $uploadedFile, $context)
    {
        // Validate configuration data
        $this->getConfigurationValidationHandler()
            ->validateConfigValues($context)
            ->validateContext($context)
            ->validateDriver();

        // Check the directories structure
        $this->getDirectoryHandler()->checkAndCreateDirectories($context);

        // Generate name and save the original image file
        $this->getUploadedFileHandler()->handle($uploadedFile, $context);

        $fileName = $this->getUploadedFileHandler()->getFileName();

        return $fileName;
    }

    protected function processImage($context, $fileName)
    {
        $config = $this->getOpenImageHandler()->getConfig();

        $image = $this->getOpenImageHandler()->openImage($context, $fileName);

        foreach ($config[$context] as $sizeName => $values) {
            $imageCopy = $image->copy();

            // Form operation name name
            $operationClassName = $this->createOperationClassName($values['operation']);

            $operationClassName = '\Todstoychev\Icr\Operation\\' . $operationClassName;

            // Create class instance
            $operation = new $operationClassName($imageCopy, $values['width'], $values['height']);

            // Perform operation and save the image
            $operation->doAction();
            $path = public_path($config['uploads_path'] . '/' . $context . '/' . $sizeName . '/' . $fileName);
            $path = preg_replace('/\.[a-z]{3,4}$/', $values['format'], $path);
            $imageCopy->save($path);

            // Delete existing instance
            unset($imageCopy);
        }
    }

    protected function createOperationClassName($operation)
    {
        $array = explode('-', $operation);

        foreach ($array as $key => $value) {
            $array[$key] = ucfirst($value);
        }

        $state = implode('', $array);

        return $state . 'Operation';
    }
}