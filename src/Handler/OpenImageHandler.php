<?php

namespace Todstoychev\Icr\Handler;

use Todstoychev\Icr\Exception\ExtensionNotFoundException;

class OpenImageHandler extends AbstractHandler
{
    /**
     * Open image
     *
     * @param string $context
     * @param string $fileName
     *
     * @return ImageInterface
     */
    public function openImage($context, $fileName)
    {
        $imageDriver = $this->config['driver'];
        $imageDriver = ucfirst($imageDriver);
        $path = public_path($this->getUploadsPath() . '/' . $context . '/' . $fileName);
        $functionName = 'openWith' . $imageDriver;

        return call_user_func([$this, $functionName], $path);
    }

    /**
     * Open image with GD implementation
     *
     * @param string $path
     *
     * @return \Imagine\Gd\Image
     * @throws ExtensionNotFoundException
     */
    protected function openWithGd($path)
    {
        $matches = [];

        preg_match('/\.[a-z]{3,4}$/', $path, $matches);

        $extension = array_shift($matches);

        if (empty($extension)) {
            throw new ExtensionNotFoundException('Extension not found in path!');
        }

        $extension = str_replace('.', '', $extension);

        if ($extension == 'jpg') {
            $extension = 'jpeg';
        }

        // Create function name to open the image as GD resource
        $functionName = 'imagecreatefrom' . $extension;

        $resource = call_user_func($functionName, $path);

        return new \Imagine\Gd\Image($resource);
    }

    /**
     * Open image with Imagick
     *
     * @param string $path
     *
     * @return \Imagine\Imagick\Image
     */
    protected function openWithImagick($path)
    {
        $imagick = new \Imagick($path);

        return new \Imagine\Imagick\Image($imagick);
    }

    /**
     * Open image with Gmagick
     *
     * @param string $path
     *
     * @return \Imagine\Gmagick\Image
     */
    protected function openWithGmagick($path)
    {
        $gmagick = new \Gmagick($path);

        return new \Imagine\Gmagick\Image($gmagick);
    }
}