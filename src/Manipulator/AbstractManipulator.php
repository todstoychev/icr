<?php

namespace Todstoychev\Icr\Manipulator;

use Imagine\Image\AbstractImage;
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
        if (!is_numeric($width) || !is_numeric($height)) {
            throw new \LogicException('Non numeric value provide for calculating resize!');
        }

        $imageWidth = $abstractImage->getSize()->getWidth();
        $imageHeight = $abstractImage->getSize()->getHeight();

        $widthRatio = $imageWidth / (int) $width;
        $heightRatio = $imageHeight / (int) $height;
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
        if (!is_numeric($width) || !is_numeric($height)) {
            throw new \LogicException('Provided values for width and height are not numeric!');
        }

        $width = (int) $width;
        $height = (int) $height;

        $imageWidth = $abstractImage->getSize()
            ->getWidth();
        $imageHeight = $abstractImage->getSize()
            ->getHeight();

        if ($imageWidth < $width || $imageHeight < $height) {
            throw new ImageTooSmallException('Provided image is too small to be resize! Provide larger image.');
        }

        $cropWidth = ($imageWidth / 2) - ($width / 2);
        $cropHeight = ($imageHeight / 2) - ($height / 2);

        $box = clone $this->box;
        $box->setWidth(round($cropWidth))
            ->setHeight(round($cropHeight));
        $this->point->setBox($box);

        return $this->point;
    }
}