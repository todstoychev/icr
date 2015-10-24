<?php

namespace Todstoychev\Icr\Handler;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Todstoychev\Icr\Exception\FileLimitExeededException;
use Todstoychev\Icr\Exception\NonAllowedFileExtensionException;
use Todstoychev\Icr\Exception\NonAllowedMimeTypeException;

class OriginalImageHandler extends AbstractHandler
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
     * @return OriginalImageHandler
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
     * @return OriginalImageHandler
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function handle(UploadedFile $uploadedFile)
    {
        $this->setUploadedFile($uploadedFile);
        $this->validateUploadedFile();
        $this->checkFileSize();
        $this->generateFileName();
        $this->saveOriginalFile();
    }

    protected function validateUploadedFile()
    {
        $allowedFileTypes = $this->getAllowedFileTypes();
        $mimeType = $this->getUploadedFile()->getClientMimeType();
        $extension = $this->getUploadedFile()->getClientOriginalExtension();

        if (!array_key_exists($mimeType, $allowedFileTypes)) {
            throw new NonAllowedMimeTypeException('Mime type not allowed!');
        }

        if (!in_array($extension, $allowedFileTypes[$mimeType])) {
            throw new NonAllowedFileExtensionException('File extension did not match the file mime type or it is not allowed!');
        }

        return $this;
    }

    protected function checkFileSize()
    {
        $phpIni = ini_get('upload_max_filesize');
        $mb = str_replace('M', '', $phpIni);
        $bytes = $mb * 1048576;
        if ($this->getUploadedFile()->getSize() > $bytes) {
            throw new FileLimitExeededException('File too large');
        }
        return $this;
    }

    protected function generateFileName()
    {
        $extension = $this->getUploadedFile()->getClientOriginalExtension();
        $fileName = md5(microtime()) . '.' . $extension;
        $path = public_path($this->getUploadsPath() . '/' . $this->getContext() . '/' . $fileName);
        if (is_file($path)) {
            $this->generateFileName();
        }
        $this->setFileName($fileName);
        return $this;
    }

    protected function saveOriginalFile()
    {
        $path = public_path($this->getUploadsPath() . '/' . $this->getContext());
        $this->uploadedFile->move($path, $this->getFileName());
        return $this;
    }

}