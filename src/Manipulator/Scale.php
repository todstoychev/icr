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
     */
    public function manipulate(AbstractImage $image, $width, $height)
    {
        $this->box->setHeight($height)
            ->setWidth($width);

        return $image->resize($this->box);
    }
}