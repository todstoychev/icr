<?php

namespace Todstoychev\Icr;

use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Facade class
 *
 * @package Todstoychev\Icr
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class Icr
{
    /**
     * Upload image
     *
     * @param UploadedFile $uploadedFile
     * @param string $context Context name
     * @param string $filesystemAdapterName
     *
     * @return string
     */
    public static function uploadImage(UploadedFile $uploadedFile, $context, $filesystemAdapterName = 'local')
    {
        $processor = new Processor(Config::get('icr'));
        $filesystemAdapter = Storage::disk($filesystemAdapterName);
        $fileName = $processor->upload($context, $uploadedFile, $filesystemAdapter);

        return $fileName;
    }

    /**
     * Delete image
     *
     * @param string $fileName File name
     * @param string $context Context name
     * @param string $filesystemAdapterName
     *
     * @return bool
     */
    public static function deleteImage($fileName, $context, $filesystemAdapterName = 'local')
    {
        $processor = new Processor(Config::get('icr'));
        $filesystemAdapter = Storage::disk($filesystemAdapterName);
        $processor->delete($fileName, $context, $filesystemAdapter);

        return true;
    }
}