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
    public static function uploadImage(UploadedFile $uploadedFile, $context)
    {
        return app('icr.processor')->upload($uploadedFile, $context);
    }

    public static function deleteImage($fileName, $context)
    {
        return app('icr.processor')->delete($fileName, $context);
    }
}