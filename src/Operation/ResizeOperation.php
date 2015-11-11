<?php

namespace Todstoychev\Icr\Operation;

use Imagine\Image\Box;
use Todstoychev\Icr\Exception\ResizeRatioException;

/**
 * Performs resize operation
 *
 * @author Todor Todorov <todstoychev@gmail.com> 
 * @package Todstoychev\Icr\Operation;
 */
class ResizeOperation extends AbstractOperation
{

    /**
     * @inheritdoc
     */
    public function doAction()
    {
        $this->getImage()->resize($this->createBox());

        return $this;
    }

    /**
     * Calculates resize ratio
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
}
