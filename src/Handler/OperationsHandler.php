<?php

namespace Todstoychev\Icr\Handler;

use Imagine\Gd\Image;
use Todstoychev\Icr\Entity\Box;
use Todstoychev\Icr\Entity\Point;

/**
 * Handler to provide methods for the three basic operation crop, resize and resize-crop
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\Handler
 */
class OperationsHandler
{
    /**
     * @var Image
     */
    protected $image;

    /**
     * @var Point
     */
    protected $point;

    /**
     * @var Box
     */
    protected $box;

    /**
     * @var UploadedFile
     */
    protected $uploadsPath;

    /**
     * @var string
     */
    protected $imageName;

    /**
     * @var Image
     */
    protected $imageCopy;

    /**
     * @param Image $image
     * @param Point $point
     * @param Box $box
     * @param string $uploadsPath
     * @param string $imageName
     */
    public function __construct(Image $image, Point $point, Box $box, $uploadsPath, $imageName)
    {
        $this->setImage($image);
        $this->setPoint($point);
        $this->setBox($box);
        $this->setUploadsPath($uploadsPath);
        $this->setImageName($imageName);
    }

    /**
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Image $image
     * @return OperationsHandler
     */
    public function setImage(Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Point
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * @param Point $point
     * @return OperationsHandler
     */
    public function setPoint(Point $point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * @return Box
     */
    public function getBox()
    {
        return $this->box;
    }

    /**
     * @param Box $box
     * @return OperationsHandler
     */
    public function setBox(Box $box)
    {
        $this->box = $box;

        return $this;
    }

    /**
     * @return string
     */
    public function getUploadsPath()
    {
        return $this->uploadsPath;
    }

    /**
     * @param string $uploadsPath
     * @return OperationsHandler
     */
    public function setUploadsPath($uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param string $imageName
     * @return OperationsHandler
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return Image
     */
    public function getImageCopy()
    {
        return $this->imageCopy;
    }

    /**
     * @param Image $imageCopy
     *
     * @return $this
     */
    public function setImageCopy(Image $imageCopy)
    {
        $this->imageCopy = $imageCopy;

        return $this;
    }

    /**
     * Provides the crop manipulation method
     *
     * @return $this
     */
    public function crop()
    {
        $this->setImageCopy($this->getImage()->copy());
        $this->getImageCopy()->crop($this->getPoint(), $this->getBox());

        return $this;
    }

    /**
     * Resizes the image
     *
     * @return $this
     */
    public function resize()
    {
        $this->setImageCopy($this->getImage()->copy());
        $this->getImageCopy()->resize($this->getBox());

        return $this;
    }

    /**
     * Provides resize-crop manipulation
     *
     * @param int $width
     * @param int $height
     *
     * @return $this
     */
    public function resizeCrop($width, $height)
    {
        $this->setImageCopy($this->getImage()->copy());
        $this->getImageCopy()->resize($this->getBox());
        $this->getBox()->setWidth($width)->setHeight($height);
        $this->getImageCopy()->crop($this->getPoint(), $this->getBox());

        return $this;
    }

    /**
     * Saves the image
     */
    public function saveImage()
    {
        $path = $this->getUploadsPath() . '/' . $this->getBox()->getContext() . '/' . $this->getBox()->getName() . '/' . $this->getImageName();

        $this->getImageCopy()->save($path);
    }
}