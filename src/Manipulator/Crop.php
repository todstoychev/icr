<?php

namespace Todstoychev\Icr\Manipulator;

use Imagine\Image\Point;

/**
 * Handles crop operation
 *
 * @package Todstoychev\Icr\Manipulator
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class Crop extends AbstractManipulator
{
    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function manipulate()
    {
        $this->createCropPoint($this->width, $this->height);
        $this->box->setHeight($this->height)
            ->setWidth($this->width);

        return $this->image->crop($this->point, $this->box);
    }
}