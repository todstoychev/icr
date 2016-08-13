<?php

namespace Todstoychev\Icr;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades;
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
     * @param null|string $fileName
     *
     * @return \Exception|string
     * @throws \Exception
     */
    public static function uploadImage(UploadedFile $uploadedFile, $context, $storage = 'local', $fileName = null)
    {
        $file = Facades\File::get($uploadedFile);
        /** @var Processor $processor */
        $processor = app('icr.processor');
        return $processor->upload(
            $context,
            $file,
            $uploadedFile->getClientOriginExtension(),
            Facades\Storage::disk($storage),
            $fileName
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
        /** @var Processor $processor */
        $processor = app('icr.processor');

        return $processor->delete($fileName, $context, Facades\Storage::disk($storage));
    }

    /**
     * Renames existing image
     *
     * @param string $oldFileName
     * @param string $newFileName
     * @param string $context
     * @param string $storage
     *
     * @return boolean
     */
    public static function renameImage($oldFileName, $newFileName, $context, $storage = 'local')
    {
        /** @var FilesystemAdapter $filesystemAdapter */
        $filesystemAdapter = Facades\Storage::disk($storage);
        /** @var Processor $processor */
        $processor = app('icr.processor');

        return $processor->rename($oldFileName, $newFileName, $context, $filesystemAdapter);
    }
}