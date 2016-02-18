<?php

namespace Todstoychev\Icr\Manipulator;

use Imagine\Image\AbstractImage;

/**
 * Performs resize operation
 *
 * @package Todstoychev\Icr\Manipulator
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class Resize extends AbstractManipulator
{
    /**
     * {@inheritdoc}
     *
     * @return AbstractImage
     */
    public function manipulate(AbstractImage $image, $width, $height)
    {
        $this->calculateResize($image, $width, $height);

        return $image->resize($this->box);
    }
}