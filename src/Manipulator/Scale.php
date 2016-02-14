<?php

namespace Todstoychev\Icr\Manipulator;

use Imagine\Image\AbstractImage;

/**
 * Performs scale operation
 *
 * @package Todstoychev\Icr\Manipulator
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class Scale extends AbstractManipulator
{
    /**
     * {@inheritdoc}
     *
     * @return AbstractImage;
     */
    public function manipulate()
    {
        $this->checkImage();
        $this->box->setHeight($this->height)
            ->setWidth($this->width);

        return $this->image->resize($this->box);
    }
}