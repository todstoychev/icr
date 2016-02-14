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
    public function manipulate()
    {
        $this->checkImage();
        $this->calculateResize($this->width, $this->height);

        return $this->image->resize($this->box);
    }
}