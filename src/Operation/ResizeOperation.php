<?php

namespace Todstoychev\Icr\Operation;

use Imagine\Image\Box;
use Todstoychev\Icr\Exception\ResizeRatioException;

/**
 * ResizeOperation
 *
 * @author Todor Todorov <todstoychev@gmail.com> 
 * @package Todstoychev\Icr\Operation;
 */
class ResizeOperation extends AbstractOperation
{

    public function doAction()
    {
        $this->getImage()->resize($this->createBox());

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
}
