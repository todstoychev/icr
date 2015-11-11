<?php

namespace Todstoychev\Icr\Operation;

use Imagine\Image\Box;
use Imagine\Image\Point;

class CropOperation extends AbstractOperation
{

    public function doAction()
    {
        $this->getImage()->crop($this->createCropPoint(), $this->createBox());

        return $this;
    }

    protected function createBox()
    {
        return new Box($this->getWidth(), $this->getHeight());
    }

    protected function createCropPoint()
    {
        $imageWidth = $this->getImage()->getSize()->getWidth();
        $imageHeight = $this->getImage()->getSize()->getHeight();

        $cropWidth = ($imageWidth / 2) - ($this->getWidth() / 2);
        $cropHeight = ($imageHeight / 2) - ($this->getHeight() / 2);

        return new Point(round($cropWidth), round($cropHeight));
    }
}