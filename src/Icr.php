<?php

namespace Todstoychev\Icr;

use Imagine\Gd;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Todstoychev\Icr\Entity;
use Todstoychev\Icr\Exception;
use Todstoychev\Icr\Handler;
use Todstoychev\Icr\StaticData;
use Todstoychev\Icr\Validator\ConfigurationValidator;

/**
 * Module main class
 *
 * @todo Seems like this class is doing too much. Think for refactoring.
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr
 */
class Icr
{
    /**
     * @var UplodedFile
     */
    protected $uploadedFile;

    /**
     * @var string
     */
    protected $context;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var DirectoryHandler
     */
    protected $directoryHandler;

    /**
     * @var OriginalFileHandler
     */
    protected $originalFileHandler;

    /**
     * @var CalculationsHandler
     */
    protected $calculationsHandler;

    /**
     * @var OperationsHandler
     */
    protected $operationsHandler;

    /**
     * @var Box
     */
    protected $box;

    /**
     * @var Point
     */
    protected $point;

    /**
     * @var ConfigurationValidator
     */
    protected $configurationValidator;

    /**
     * @param UploadedFile $uploadedFile
     * @param array $config
     * @param string $context
     */
    public function __construct(UploadedFile $uploadedFile, array $config, $context)
    {
        $this->setUploadedFile($uploadedFile);
        $this->setConfig($config);
        $this->setContext($context);
    }

    /**
     * @return UploadedFile
     */
    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return Icr
     */
    public function setUploadedFile($uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;

        return $this;
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
     * @return Icr
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string $context
     * @return Icr
     */
    public function setContext($context)
    {
        $this->context = $context;

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
     * @return Icr
     */
    public function setDirectoryHandler(Handler\DirectoryHandler $directoryHandler)
    {
        $this->directoryHandler = $directoryHandler;

        return $this;
    }

    /**
     * @return Handler\OriginalFileHandler
     */
    public function getOriginalFileHandler()
    {
        return $this->originalFileHandler;
    }

    /**
     * @param Handler\OriginalFileHandler $originalFileHandler
     * @return Icr
     */
    public function setOriginalFileHandler(Handler\OriginalFileHandler $originalFileHandler)
    {
        $this->originalFileHandler = $originalFileHandler;

        return $this;
    }

    /**
     * @return CalculationsHandler
     */
    public function getCalculationsHandler()
    {
        return $this->calculationsHandler;
    }

    /**
     * @param Handler\CalculationsHandler $calculationHandler
     * @return Icr
     */
    public function setCalculationsHandler(Handler\CalculationsHandler $calculationsHandler)
    {
        $this->calculationsHandler = $calculationsHandler;

        return $this;
    }

    /**
     * @return OperationsHandler
     */
    public function getOperationsHandler()
    {
        return $this->operationsHandler;
    }

    /**
     * @param Handler\OperationsHandler $operationsHandler
     * @return Icr
     */
    public function setOperationsHandler(Handler\OperationsHandler $operationsHandler)
    {
        $this->operationsHandler = $operationsHandler;

        return $this;
    }

    /**
     * @return Entity\Box
     */
    public function getBox()
    {
        return $this->box;
    }

    /**
     * @param Entity\Box $box
     * @return Icr
     */
    public function setBox(Entity\Box $box)
    {
        $this->box = $box;

        return $this;
    }

    /**
     * @return Entity\Point
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * @param Entity\Point $point
     * @return Icr
     */
    public function setPoint(Entity\Point $point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * @return ConfigurationValidator
     */
    public function getConfigurationValidator()
    {
        return $this->configurationValidator;
    }

    /**
     * @param ConfigurationValidator $configurationValidator
     * @return Icr
     */
    public function setConfigurationValidator($configurationValidator)
    {
        $this->configurationValidator = $configurationValidator;

        return $this;
    }

    /**
     * Get the image file name
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->getOriginalFileHandler()->getFileName();
    }

    /**
     * Processes the image
     *
     * @return bool|string
     */
    public function process()
    {
        try {
            // Validate configuration data
            $this->initConfigurationValidator();
            $this->getConfigurationValidator()->validate();

            // Check and create directory structure
            $this->initDirectoryHandler();
            $this->getDirectoryHandler()->checkAndCreateDirectory();

            // Handle the original file
            $this->initOriginalFileHandler();
            $this->getOriginalFileHandler()->checkFileSize()->generateFileName()->saveOriginalFile();

            // Init calculation and operation handlers
            $image = $this->getOriginalFileHandler()->openFile(new Gd\Imagine());
            $fileName = $this->getOriginalFileHandler()->getFileName();

            /*
             * Since calculation and operation handlers are dependent from Box and Point instances initialize
             * first Box and Point
             */
            $this->initBox();
            $this->initPoint();
            $this->initCalculationsHandler($image);
            $this->initOperationsHandler($image, $fileName);

            // Manipulate the image
            foreach ($this->config[$this->getContext()] as $sizeName => $values) {
                // Set Box values
                $this->getBox()
                    ->setWidth($values['width'])
                    ->setHeight($values['height'])
                    ->setName($sizeName)
                    ->setContext($this->getContext());

                // Perform Resize and crop
                if ($values['operation'] == 'resize-crop') {
                    $box = $this->getCalculationsHandler()->calculateOutputSize(true);
                    $this->getOperationsHandler()
                        ->setBox($box)
                        ->resizeCrop($values['width'], $values['height'])
                        ->saveImage();
                }

                // Perform resize
                if ($values['operation'] == 'resize') {
                    $box = $this->getCalculationsHandler()->calculateOutputSize();
                    $this->getOperationsHandler()
                        ->setBox($box)
                        ->resize()
                        ->saveImage();
                }

                // Perform proportional resize
                if ($values['operation'] == 'resize-proportional') {
                    $box = $this->getCalculationsHandler()->calculateOutputSize(true);
                    $this->getOperationsHandler()
                        ->setBox($box)
                        ->resize()
                        ->saveImage();
                }

                // Perform crop
                if ($values['operation'] == 'crop') {
                    $point = $this->getCalculationsHandler()->calculateCropPoint();
                    $this->getOperationsHandler()
                        ->crop()
                        ->saveImage();
                }
            }

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Initialize the DirectoryHandler
     *
     * @return $this
     */
    protected function initDirectoryHandler()
    {
        $directoryHandler = new Handler\DirectoryHandler(
            $this->config['uploads_path'],
            $this->config[$this->getContext()],
            $this->getContext()
        );

        $this->setDirectoryHandler($directoryHandler);

        return $this;
    }

    /**
     * Initialize OriginalImageHandler
     *
     * @return $this
     */
    protected function initOriginalFileHandler()
    {
        $originalFileHandler = new Handler\OriginalFileHandler(
            $this->getUploadedFile(),
            $this->config['uploads_path'],
            $this->getContext()
        );

        $this->setOriginalFileHandler($originalFileHandler);

        return $this;
    }

    /**
     * Initialize Box entity instance
     *
     * @param int $width
     * @param int $height
     * @param string $name
     * @param string $context
     *
     * @return $this
     */
    protected function initBox($width = 0, $height = 0, $name = '', $context = '')
    {
        $box = new Entity\Box($width, $height, $name, $context);
        $this->setBox($box);

        return $this;
    }

    /**
     * Initialize Point entity instance
     *
     * @param int $x
     * @param int $y
     *
     * @return $this
     */
    protected function initPoint($x = 0, $y = 0)
    {
        $point = new Entity\Point($x, $y);
        $this->setPoint($point);

        return $this;
    }

    /**
     * Initialize CalculationsHandler
     *
     * @param Gd\Image $image
     *
     * @return $this
     */
    protected function initCalculationsHandler(Gd\Image $image)
    {
        $calculationsHandler = new Handler\CalculationsHandler($image, $this->getBox(), $this->getPoint());
        $this->setCalculationsHandler($calculationsHandler);

        return $this;
    }

    /**
     * Initialize OperationsHandler
     *
     * @param Gd\Image $image
     * @param string $imageName
     *
     * @return $this
     */
    protected function initOperationsHandler(Gd\Image $image, $imageName)
    {
        $operationsHandler = new Handler\OperationsHandler(
            $image,
            $this->getPoint(),
            $this->getBox(),
            $this->config['uploads_path'],
            $imageName
        );

        $this->setOperationsHandler($operationsHandler);

        return $this;
    }

    /**
     * Initialize ConfigurationValidator
     *
     * @return $this
     */
    protected function initConfigurationValidator()
    {
        $configurationValidator = new ConfigurationValidator($this->getConfig(), $this->getContext());
        $this->setConfigurationValidator($configurationValidator);

        return $this;
    }
}