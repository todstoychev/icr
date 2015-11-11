<?php

namespace Todstoychev\Icr\Operation;

use Imagine\Image\Box;
use Imagine\Image\Point;

class ResizeCropOperation extends AbstractOperation
{

    public function doAction()
    {
        $this->getImage()->resize($this->createBox());

        $box = new Box($this->getWidth(), $this->getHeight());

        $this->getImage()->crop($this->createCropPoint(), $box);

        return $this;
    }

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

    protected function calculateWidth()
    {
        return $this->getImage()->getSize()->getWidth() / $this->calculateRatio();
    }

    protected function calculateHeight()
    {
        return $this->getImage()->getSize()->getHeight() / $this->calculateRatio();
    }

    protected function createBox()
    {
        return new Box($this->calculateWidth(), $this->calculateHeight());
    }

    protected function createCropPoint()
    {
        $cropWidth = $this->calculateCropWidth();
        $cropHeight = $this->calculateCropHeight();

        return new Point(round($cropWidth), round($cropHeight));
    }

    protected function calculateCropWidth()
    {
        $imageWidth = $this->getImage()->getSize()->getWidth();

        if ($imageWidth == $this->getWidth()) {
            return 0;
        }

        $cropWidth = ($imageWidth / 2) - ($this->getWidth() / 2);

        return $cropWidth;
    }

    protected function calculateCropHeight()
    {
        $imageHeight = $this->getImage()->getSize()->getHeight();

        if ($imageHeight == $this->getHeight())
        {
            return 0;
        }

        $cropHeight = ($imageHeight / 2) - ($this->getHeight() / 2);

        return $cropHeight;
    }
}