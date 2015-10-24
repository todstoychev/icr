<?php

namespace Todstoychev\Icr;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Todstoychev\Icr\Handler\ConfigurationValidationHandler;
use Todstoychev\Icr\Handler\DirectoryHandler;
use Todstoychev\Icr\Handler\OriginalImageHandler;

class Processor
{
    /**
     * @var ConfigurationValidationHandler
     */
    protected $configurationValidationHandler;

    /**
     * @var DirectoryHandler
     */
    protected $directoryHandler;

    /**
     * @var OriginalImageHandler
     */
    protected $originalImageHandler;

    /**
     * @var string
     */
    protected $context;

    public function __construct(
        ConfigurationValidationHandler $configurationValidationHandler,
        DirectoryHandler $directoryHandler,
        OriginalImageHandler $originalImageHandler
    ) {
        $this->setConfigurationValidationHandler($configurationValidationHandler);
        $this->setDirectoryHandler($directoryHandler);
        $this->setOriginalImageHandler($originalImageHandler);
    }

    /**
     * @return ConfigurationValidationHandler
     */
    public function getConfigurationValidationHandler()
    {
        return $this->configurationValidationHandler;
    }

    /**
     * @param ConfigurationValidationHandler $configurationValidationHandler
     *
     * @return Processor
     */
    public function setConfigurationValidationHandler(ConfigurationValidationHandler $configurationValidationHandler)
    {
        $this->configurationValidationHandler = $configurationValidationHandler;

        return $this;
    }

    /**
     * @return DirectoryHandler
     */
    public function getDirectoryHandler()
    {
        return $this->directoryHandler;
    }

    /**
     * @param DirectoryHandler $directoryHandler
     *
     * @return Processor
     */
    public function setDirectoryHandler(DirectoryHandler $directoryHandler)
    {
        $this->directoryHandler = $directoryHandler;

        return $this;
    }

    /**
     * @return OriginalImageHandler
     */
    public function getOriginalImageHandler()
    {
        return $this->originalImageHandler;
    }

    /**
     * @param OriginalImageHandler $originalImageHandler
     *
     * @return Processor
     */
    public function setOriginalImageHandler(OriginalImageHandler $originalImageHandler)
    {
        $this->originalImageHandler = $originalImageHandler;

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
     * @return Processor
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    public function uploadImage(UploadedFile $uploadedFile, $context)
    {
        $this->setContext($context);

        try {
            // Validate configuration data
            $this->getConfigurationValidationHandler()->setContext($this->getContext())->validate($this->getContext());

            // Check the directories structure
            $this->getDirectoryHandler()->checkAndCreateDirectories($this->getContext());

            // Generate name and save the original image file
            $this->getOriginalImageHandler()->handle($uploadedFile);

        } catch (\Exception $e) {
            return $e->getMessage();
        }

        // Perform operations
    }
}