<?php

namespace Todstoychev\Icr\Handler;

/**
 * Handles delete image operations
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\Handler
 */
class DeleteImageHandler extends AbstractHandler
{
    /**
     * Delete only reized images and keeps the original image
     *
     * @param string $context
     * @param string $fileName
     *
     * @throws \Todstoychev\Icr\Exception\NonExistingArrayKeyException
     * @throws \Todstoychev\Icr\Exception\NonExistingContextException
     */
    public function deleteImageSizes($context, $fileName)
    {
        $contextPath = public_path($this->getUploadsPath() . '/' . $context);

        foreach ($this->getContextValues($context) as $key => $value) {
            $image = $contextPath . '/' . $key . '/' . $fileName;

            is_file($image) ? unlink($image) : null;
        }
    }

    /**
     * Completely deletes an image
     *
     * @param string $context
     * @param string $fileName
     *
     * @throws \Todstoychev\Icr\Exception\NonExistingArrayKeyException
     */
    public function deleteImage($context, $fileName)
    {
        $this->deleteImageSizes($context, $fileName);

        $fileName = preg_replace('/\.[a-z]{3,4}/', '', $fileName);

        $path = public_path($this->getUploadsPath() . '/' . $context);

        foreach (glob($path . '/' . $fileName . '*') as $file) {
            unlink($file);
        }
    }
}