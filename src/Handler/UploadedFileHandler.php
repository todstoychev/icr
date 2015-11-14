<?php

namespace Todstoychev\Icr\Handler;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Todstoychev\Icr\Exception;

/**
 * Handles the uploaded file
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\Handler
 */
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

    /**
     * Main handler method. Validates the uploaded file and saves it.
     *
     * @param UploadedFile $uploadedFile
     * @param string $context
     *
     * @throws Exception\FileLimitExceededException
     * @throws Exception\NonAllowedFileExtensionException
     * @throws Exception\NonAllowedMimeTypeException
     */
    public function handle(UploadedFile $uploadedFile, $context)
    {
        $this->setUploadedFile($uploadedFile);
        $this->validateUploadedFile($context);
        $this->checkFileSize();
        $this->generateFileName($context);
        $this->saveOriginalFile($context);
    }

    /**
     * Validates the uploaded file
     *
     * @param string $context
     *
     * @return UploadedFileHandler
     * @throws Exception\NonAllowedFileExtensionException
     * @throws Exception\NonAllowedMimeTypeException
     */
    protected function validateUploadedFile($context)
    {
        $allowedFileTypes = $this->getAllowedFileTypes($context);
        $mimeType = $this->getUploadedFile()->getClientMimeType();
        $extension = $this->getUploadedFile()->getClientOriginalExtension();

        if (!array_key_exists($mimeType, $allowedFileTypes)) {
            throw new Exception\NonAllowedMimeTypeException(trans('icr::exceptions.mimetype_not_allowed'));
        }

        if (!in_array($extension, $allowedFileTypes[$mimeType])) {
            throw new Exception\NonAllowedFileExtensionException(trans('icr::exceptions.file_extension_did_not_match'));
        }

        return $this;
    }

    /**
     * Checks file size. If exceeded throws exception.
     *
     * @return UploadedFileHandler
     * @throws Exception\FileLimitExceededException
     */
    protected function checkFileSize()
    {
        $phpIni = ini_get('upload_max_filesize');
        $mb = str_replace('M', '', $phpIni);
        $bytes = $mb * 1048576;
        if ($this->getUploadedFile()->getSize() > $bytes) {
            throw new Exception\FileLimitExceededException(trans('icr::exceptions.file_limit_exceeded'));
        }
        return $this;
    }

    /**
     * Generates unique file name
     *
     * @param string $context
     *
     * @return UploadedFileHandler
     * @throws Exception\NonExistingArrayKeyException
     */
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

    /**
     * Saves the original file
     *
     * @param string $context
     *
     * @return UploadedFileHandler
     * @throws Exception\NonExistingArrayKeyException
     */
    protected function saveOriginalFile($context)
    {
        $path = public_path($this->getUploadsPath() . '/' . $context);
        $this->uploadedFile->move($path, $this->getFileName());
        return $this;
    }
}