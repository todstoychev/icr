<?php

namespace Todstoychev\Icr\Manipulator;

use Imagine\Image\AbstractImage;
use Imagine\Image\Point;
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
     * @var Box
     */
    protected $box;

    /**
     * @var Point
     */
    protected $point;

    /**
     * @param Box $box
     * @param Point $point
     */
    public function __construct(Box $box, Point $point)
    {
        $this->box = $box;
        $this->point = $point;
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
     * Main manipulate method
     *
     * @param AbstractImage $abstractImage
     * @param int $width
     * @param int $height
     *
     * @return mixed
     */
    abstract public function manipulate(AbstractImage $abstractImage, $width, $height);

    /**
     * Calculates image resize
     *
     * @param AbstractImage $abstractImage
     * @param int $width
     * @param int $height
     *
     * @return Box
     */
    protected function calculateResize(AbstractImage $abstractImage, $width, $height)
    {
        $imageWidth = $abstractImage->getSize()->getWidth();
        $imageHeight = $abstractImage->getSize()->getHeight();

        $widthRatio = $imageWidth / $width;
        $heightRatio = $imageHeight / $height;
        $ratio = min($widthRatio, $heightRatio);

        if ($ratio < 1) {
            throw new ImageTooSmallException('Provided image is too small to be resize! Provide larger image.');
        }

        $calcWidth = $imageWidth / $ratio;
        $calcHeight = $imageHeight / $ratio;

        $box = clone $this->box;

        $box->setWidth(round($calcWidth))
            ->setHeight(round($calcHeight));

        return $box;
    }

    /**
     * Creates crop point
     *
     * @param AbstractImage $abstractImage
     * @param int $width
     * @param int $height
     *
     * @return Point
     */
    protected function createCropPoint(AbstractImage $abstractImage, $width, $height)
    {
        $imageWidth = $abstractImage->getSize()
            ->getWidth();
        $imageHeight = $abstractImage->getSize()
            ->getHeight();
        $cropWidth = ($imageWidth / 2) - ($width / 2);
        $cropHeight = ($imageHeight / 2) - ($height / 2);

        $box = clone $this->box;
        $box->setWidth(round($cropWidth))
            ->setHeight(round($cropHeight));
        $point = clone $this->point;
        $point->setBox($box);

        return $point;
    }
}