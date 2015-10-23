<?php

namespace Todstoychev\Icr\Handler;

use Imagine\Gd\Imagine;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Todstoychev\Icr\Exception\FileLimitExeededException;

/**
 * Handler used to perform some basic operations on the original file
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\Handler
 */
class OriginalFileHandler
{
    /**
     * @var string
     */
    protected $uploadsPath;

    /**
     * @var UploadedFile
     */
    protected $uploadedFile;

    /**
     * @var string
     */
    protected $context;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @param UploadedFile $uploadedFile
     * @param string $uploadsPath
     * @param string $context
     */
    public function __construct(UploadedFile $uploadedFile, $uploadsPath, $context)
    {
        $this->setUploadedFile($uploadedFile);
        $this->setUploadsPath($uploadsPath);
        $this->setContext($context);
    }

    /**
     * @return string
     */
    public function getUploadsPath()
    {
        return $this->uploadsPath;
    }

    /**
     * @param string $uploadsPath
     * @return OriginalFileHandler
     */
    public function setUploadsPath($uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;

        return $this;
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
     * @return OriginalFileHandler
     */
    public function setUploadedFile(UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;

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
     * @return OriginalFileHandler
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return OriginalFileHandler
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Generates file name for the saved image
     *
     * @return $this
     */
    public function generateFileName()
    {
        $extension = $this->getUploadedFile()->getClientOriginalExtension();
        $fileName = md5(microtime()) . '.' . $extension;
        $path = public_path($this->uploadsPath . '/' . $this->getContext() . '/' . $fileName);

        if (is_file($path)) {
            $this->generateFileName();
        }

        $this->setFileName($fileName);

        return $this;
    }

    /**
     * Saves the original image file
     *
     * @return $this
     */
    public function saveOriginalFile()
    {
        $path = public_path($this->uploadsPath . '/' . $this->getContext());
        $this->uploadedFile->move($path, $this->getFileName());

        return $this;
    }

    /**
     * Checks if the image exeeds the file limit.
     *
     * @return $this
     * @throws FileLimitExeededException
     */
    public function checkFileSize()
    {
        $phpIni = ini_get('upload_max_filesize');
        $mb = str_replace('M', '', $phpIni);
        $bytes = $mb * 1048576;

        if ($this->getUploadedFile()->getSize() > $bytes) {
            throw new FileLimitExeededException('File too large');
        }

        return $this;
    }

    /**
     * Opens the original file as imagine object
     *
     * @param Imagine $imagine
     *
     * @return \Imagine\Gd\Image|\Imagine\Image\ImageInterface
     */
    public function openFile(Imagine $imagine)
    {
        $filePath = $this->getUploadsPath() . '/' . $this->getContext() . '/' . $this->getFileName();
        $image = $imagine->open($filePath);

        return $image;
    }
}