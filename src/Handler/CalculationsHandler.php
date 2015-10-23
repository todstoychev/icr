<?php

namespace Todstoychev\Icr\Handler;

use Imagine\Gd\Image;
use Todstoychev\Icr\Entity\Box;
use Todstoychev\Icr\Entity\Point;

/**
 * Handler that provides methods to calculate the amounth of resizing. Resized image width and height.
 * Calculates also the crop point position on the canvas.
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\Handler
 */
class CalculationsHandler
{
    /**
     * @var Image
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
     * @param Image $image
     * @param Box $box
     * @param Point $point
     */
    public function __construct(Image $image, Box $box, Point $point)
    {
        $this->setImage($image);
        $this->setBox($box);
        $this->setPoint($point);
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
     * @return CalculationsHandler
     */
    public function setImage(Image $image)
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
     * @param Box $size
     * @return CalculationsHandler
     */
    public function setBox($box)
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
     * @return CalculationsHandler
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Calculates output size. Can calculate width nad height to resize the image to its current proportions
     * if @param $optimalResize is set to true. Else will use the provided values.
     *
     * @param int $width
     * @param int $height
     * @param bool $optimalResize
     * @return Box
     */
    public function calculateOutputSize($optimalResize = false)
    {
        if (!$optimalResize) {
            return $this->getBox();
        }

        $widthRatio = $this->getImage()->getSize()->getWidth() / $this->getBox()->getWidth();
        $heightRatio = $this->getImage()->getSize()->getHeight() / $this->getBox()->getHeight();
        $ratio = min($widthRatio, $heightRatio);
        $calculatedWidth = round($this->getImage()->getSize()->getWidth() / $ratio);
        $calculatedHeight = round($this->getImage()->getSize()->getHeight() / $ratio);

        $this->getBox()->setWidth($calculatedWidth);
        $this->getBox()->setHeight($calculatedHeight);

        return $this->getBox();
    }

    /**
     * Calculate crop point position on the canvas
     *
     * @param int $width
     * @param int $height
     * @return Point
     */
    public function calculateCropPoint()
    {
        $x = 0;
        $y = 0;

        if ($this->getImage()->getSize()->getWidth() > $this->getBox()->getWidth()) {
            $x = ($this->getImage()->getSize()->getWidth() - $this->getBox()->getWidth()) / 2;
        }

        if ($this->getImage()->getSize()->getHeight() - $this->getBox()->getHeight()) {
            $y = ($this->getImage()->getSize()->getHeight() - $this->getBox()->getHeight()) / 2;
        }

        $this->getPoint()->setX(round($x));
        $this->getPoint()->setY(round($y));

        return $this->getPoint();
    }
}