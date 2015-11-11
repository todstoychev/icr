<?php

namespace Todstoychev\Icr\Operation;

use Imagine\Image\Box;
use Imagine\Image\Point;
use Todstoychev\Icr\Exception\ResizeRatioException;

/**
 * Performs resize crop operation
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\Operation
 */
class ResizeCropOperation extends AbstractOperation
{

    /**
     * @inheritdoc
     */
    public function doAction()
    {
        $this->getImage()->resize($this->createBox());

        $box = new Box($this->getWidth(), $this->getHeight());

        $this->getImage()->crop($this->createCropPoint(), $box);

        return $this;
    }

    /**
     * Calculates resize ration. If ratio is less then 1 throws exception. This means the uploaded image
     * is less then the output dimensions.
     *
     * @return float
     * @throws ResizeRatioException
     */
    protected function calculateRatio()
    {
        $widthRatio = $this->getImage()->getSize()->getWidth() / $this->getWidth();
        $heightRatio = $this->getImage()->getSize()->getHeight() / $this->getHeight();

        $ratio = min($widthRatio, $heightRatio);

        if ($ratio < 1) {
            throw new ResizeRatioException('Your image is too small. Try with larger image.');
        }

        return $ratio;
    }

    /**
     * Calculates output image width
     *
     * @return float
     * @throws ResizeRatioException
     */
    protected function calculateWidth()
    {
        return $this->getImage()->getSize()->getWidth() / $this->calculateRatio();
    }

    /**
     * Calculates output image height
     *
     * @return float
     * @throws ResizeRatioException
     */
    protected function calculateHeight()
    {
        return $this->getImage()->getSize()->getHeight() / $this->calculateRatio();
    }

    /**
     * @inheritdoc
     */
    protected function createBox()
    {
        return new Box($this->calculateWidth(), $this->calculateHeight());
    }

    /**
     * Creates crop point
     *
     * @return Point
     */
    protected function createCropPoint()
    {
        $cropWidth = $this->calculateCropWidth();
        $cropHeight = $this->calculateCropHeight();

        return new Point(round($cropWidth), round($cropHeight));
    }

    /**
     * Calculates crop point start width
     *
     * @return int
     */
    protected function calculateCropWidth()
    {
        $imageWidth = $this->getImage()->getSize()->getWidth();

        if ($imageWidth == $this->getWidth()) {
            return 0;
        }

        $cropWidth = ($imageWidth / 2) - ($this->getWidth() / 2);

        return (int) $cropWidth;
    }

    /**
     * Calculates crop point start height
     *
     * @return int
     */
    protected function calculateCropHeight()
    {
        $imageHeight = $this->getImage()->getSize()->getHeight();

        if ($imageHeight == $this->getHeight())
        {
            return 0;
        }

        $cropHeight = ($imageHeight / 2) - ($this->getHeight() / 2);

        return (int) $cropHeight;
    }
}