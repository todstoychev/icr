<?php

namespace Todstoychev\Icr\Handler;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Todstoychev\Icr\Exception;

class UploadedFileHandler extends AbstractHandler
{
    /**
     * @var UploadedFile
     */
    protected $uploadedFile;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @return UploadedFile
     */
    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    /**
     * @param UploadedFile $uploadedFile
     *
     * @return UploadedFileHandler
     */
    public function setUploadedFile(UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;

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
     *
     * @return UploadedFileHandler
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getUploadedFileWidth()
    {

    }

    public function getUploadedFileHeight()
    {

    }

    public function getUploadedFileDimensions()
    {
        list($width, $height) = getimagesize($this->getUploadedFile());

        var_dump($width);
    }

    public function handle(UploadedFile $uploadedFile, $context)
    {
        $this->setUploadedFile($uploadedFile);
        $this->validateUploadedFile();
        $this->checkFileSize();
        $this->generateFileName($context);
        $this->saveOriginalFile($context);
    }

    protected function validateUploadedFile()
    {
        $allowedFileTypes = $this->getAllowedFileTypes();
        $mimeType = $this->getUploadedFile()->getClientMimeType();
        $extension = $this->getUploadedFile()->getClientOriginalExtension();

        if (!array_key_exists($mimeType, $allowedFileTypes)) {
            throw new Exception\NonAllowedMimeTypeException('Mime type not allowed!');
        }

        if (!in_array($extension, $allowedFileTypes[$mimeType])) {
            throw new Exception\NonAllowedFileExtensionException('File extension did not match the file mime type or it is not allowed!');
        }

        return $this;
    }

    protected function checkFileSize()
    {
        $phpIni = ini_get('upload_max_filesize');
        $mb = str_replace('M', '', $phpIni);
        $bytes = $mb * 1048576;
        if ($this->getUploadedFile()->getSize() > $bytes) {
            throw new Exception\FileLimitExeededException('File too large');
        }
        return $this;
    }

    protected function generateFileName($context)
    {
        $extension = $this->getUploadedFile()->getClientOriginalExtension();
        $fileName = md5(microtime()) . '.' . $extension;
        $path = public_path($this->getUploadsPath() . '/' . $context . '/' . $fileName);
        if (is_file($path)) {
            $this->generateFileName($context);
        }
        $this->setFileName($fileName);
        return $this;
    }

    protected function saveOriginalFile($context)
    {
        $path = public_path($this->getUploadsPath() . '/' . $context);
        $this->uploadedFile->move($path, $this->getFileName());
        return $this;
    }
}