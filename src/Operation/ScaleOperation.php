<?php

namespace Todstoychev\Icr\Operation;

use Imagine\Image\Box;

class ScaleOperation extends AbstractOperation
{

    public function doAction()
    {
        $this->getImage()->resize($this->createBox());

        return $this;
    }

    protected function createBox()
    {
        return new Box($this->getWidth(), $this->getHeight());
    }
}