<?php

namespace Todstoychev\Icr\Operation;

use Imagine\Image\Box;
use Imagine\Image\Point;

/**
 * Performs crop image operation
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\Operation
 */
class CropOperation extends AbstractOperation
{

    /**
     * @inheritdoc
     */
    public function doAction()
    {
        $this->getImage()->crop($this->createCropPoint(), $this->createBox());

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function createBox()
    {
        return new Box($this->getWidth(), $this->getHeight());
    }

    /**
     * Creates point object representing the crop start point.
     *
     * @return Point
     */
    protected function createCropPoint()
    {
        $imageWidth = $this->getImage()->getSize()->getWidth();
        $imageHeight = $this->getImage()->getSize()->getHeight();

        $cropWidth = ($imageWidth / 2) - ($this->getWidth() / 2);
        $cropHeight = ($imageHeight / 2) - ($this->getHeight() / 2);

        return new Point(round($cropWidth), round($cropHeight));
    }
}