<?php

namespace Todstoychev\Icr;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Module main facade class
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr
 */
class Icr
{
    /**
     * Handles upload image. Returns exception instance on error or file name on success.
     *
     * @param UploadedFile $uploadedFile
     * @param string $context
     * @param string $storage
     *
     * @return \Exception|string
     */
    public static function uploadImage(UploadedFile $uploadedFile, $context, $storage = 'local')
    {
        $file = File::get($uploadedFile);
        return app('icr.processor')->upload(
            $context,
            $file,
            $uploadedFile->getClientOriginalExtension(),
            Storage::disk($storage)
        );
    }

    /**
     * Handles delete image. Returns exception instance on error.
     *
     * @param string $fileName
     * @param string $context
     * @param string $storage
     *
     * @return mixed
     */
    public static function deleteImage($fileName, $context, $storage = 'local')
    {
        return app('icr.processor')->delete($fileName, $context, Storage::disk($storage));
    }
}