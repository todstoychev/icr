<?php

namespace Todstoychev\Icr;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Module main class
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr
 */
class Icr
{
    /**
     * Handles upload image
     *
     * @param UploadedFile $uploadedFile
     * @param string $context
     * @return mixed
     */
    public static function uploadImage(UploadedFile $uploadedFile, $context)
    {
        return app('icr.processor')->upload($uploadedFile, $context);
    }

    /**
     * Handles delete image
     *
     * @param string $fileName
     * @param string $context
     * @return mixed
     */
    public static function deleteImage($fileName, $context)
    {
        return app('icr.processor')->delete($fileName, $context);
    }
}