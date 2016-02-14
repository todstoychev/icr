<?php

namespace Todstoychev\Icr\Manipulator;

use Imagine\Image\ImageInterface;
use Todstoychev\Icr\Exception\ImageNotSetException;
use Todstoychev\Icr\Exception\ImageTooSmallException;

/**
 * Class AbstractManipulator
 *
 * @package Todstoychev\Icr\Manipulator
 * @author Todor Todorov <todstoychev@gmail.com>
 */
abstract class AbstractManipulator
{
    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var ImageInterface
     */
    protected $image;

    /**
     * @var Box
     */
    protected $box;

    /**
     * @var Point
     */
    protected $point;

    /**
     * @param ImageInterface $imagine
     * @param null|int $width Width to resize to
     * @param null|int $height Height to resize to
     * @param Box $box
     * @param Point $point
     */
    public function __construct(
        ImageInterface $imagine = null,
        $width = null,
        $height = null,
        Box $box = null,
        Point $point = null
    ) {
        $this->image = $imagine;
        $this->width = $width;
        $this->height = $height;
        $this->box = $box;
        $this->point = $point;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     *
     * @return AbstractManipulator
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     *
     * @return AbstractManipulator
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return ImageInterface
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param ImageInterface $image
     *
     * @return AbstractManipulator
     */
    public function setImage(ImageInterface $image)
    {
        $this->image = $image;

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
     *
     * @return AbstractManipulator
     */
    public function setBox(Box $box)
    {
        $this->box = $box;

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
     *
     * @return AbstractManipulator
     */
    public function setPoint(Point $point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     *
     * @return mixed
     */
    abstract public function manipulate();

    /**
     * Checks image instance
     *
     * @return AbstractManipulator
     */
    protected function checkImage()
    {
        if (!$this->image instanceof ImageInterface) {
            throw new ImageNotSetException('Imagine instance not found!');
        }

        return $this;
    }

    /**
     * Calculates image resize
     *
     * @param int $width
     * @param int $height
     *
     * @return AbstractManipulator
     */
    protected function calculateResize($width, $height)
    {
        $imageWidth = $this->image->getSize()->getWidth();
        $imageHeight = $this->image->getSize()->getHeight();

        $widthRatio = $imageWidth / $width;
        $heightRatio = $imageHeight / $height;
        $ratio = min($widthRatio, $heightRatio);

        if ($ratio < 1) {
            throw new ImageTooSmallException('Provided image is too small to be resize! Provide larger image.');
        }

        $calcWidth = $imageWidth / $ratio;
        $calcHeight = $imageHeight / $ratio;

        $this->box->setWidth(round($calcWidth))
            ->setHeight(round($calcHeight));

        return $this;
    }

    /**
     * Creates crop point
     *
     * @param int $width
     * @param int $height
     *
     * @return AbstractManipulator
     */
    protected function createCropPoint($width, $height)
    {
        $imageWidth = $this->image->getSize()
            ->getWidth();
        $imageHeight = $this->image->getSize()
            ->getHeight();
        $cropWidth = ($imageWidth / 2) - ($width / 2);
        $cropHeight = ($imageHeight / 2) - ($height / 2);

        $box = clone $this->box;
        $box->setWidth(round($cropWidth))
            ->setHeight(round($cropHeight));
        $this->point->setBox($box);

        return $this;
    }
}