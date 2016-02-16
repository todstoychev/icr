<?php

namespace Todstoychev\Icr\Handler;

use Imagine\Gd\Imagine as GdImage;
use Imagine\Gmagick\Imagine as GmagickImage;
use Imagine\Image\AbstractImage;
use Imagine\Imagick\Imagine as ImagickImage;

/**
 * Opens image with Imagine
 *
 * @package Todstoychev\Icr\Handler
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class OpenImageHandler
{
    /**
     * @var string
     */
    protected $imageLibrary;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var string
     */
    protected $path;

    /**
     * @param string $imageLibrary Image library name
     */
    public function __construct($imageLibrary = 'gd')
    {
        $this->imageLibrary = $imageLibrary;
    }

    /**
     * @return string
     */
    public function getImageLibrary()
    {
        return $this->imageLibrary;
    }

    /**
     * @param string $imageLibrary
     *
     * @return OpenImageHandler
     */
    public function setImageLibrary($imageLibrary)
    {
        $this->imageLibrary = $imageLibrary;

        return $this;
    }

    /**
     * Loads image from data string with imagine based on the driver name
     *
     * @param string $image Image as string
     *
     * @return AbstractImage
     */
    public function loadImage($image)
    {
        $imageLibrary = ucfirst($this->imageLibrary);
        $functionName = 'loadWith' . $imageLibrary;
        $this->image = $image;

        return call_user_func([$this, $functionName]);
    }

    /**
     * Open image from path with imagine library
     *
     * @param string $path
     *
     * @return AbstractImage
     */
    public function openImage($path)
    {
        $imageLibrary = ucfirst($this->imageLibrary);
        $functionName = 'openWith' . $imageLibrary;
        $this->path = $path;

        return call_user_func([$this, $functionName]);
    }

    /**
     * Opens image with Imagine Gd
     *
     * @return AbstractImage
     */
    protected function loadWithGd()
    {
        $imagine = new GdImage();

        return $imagine->load($this->image);
    }

    /**
     * Opens image with Imagine Imagick
     *
     * @return AbstractImage
     */
    protected function loadWithImagick()
    {
        $imagine = new ImagickImage();

        return $imagine->load($this->image);
    }

    /**
     * Opens image with Imagine Gmagick
     *
     * @return AbstractImage
     */
    protected function loadWithGmagick()
    {
        $imagine = new GmagickImage();

        return $imagine->load($this->image);
    }

    /**
     * Opens image with GD
     *
     * @return AbstractImage
     */
    protected function openWithGd()
    {
        $imagine = new GdImage();

        return $imagine->open($this->path);
    }

    /**
     * Opens image with Imagick
     *
     * @return AbstractImage
     */
    protected function openWithImagick()
    {
        $imagine = new ImagickImage();

        return $imagine->open($this->path);
    }

    /**
     * Opens image with Gmagick
     *
     * @return AbstractImage
     */
    protected function openWithGmagick()
    {
        $imagine = new GmagickImage();

        return $imagine->open($this->path);
    }
}