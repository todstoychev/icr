<?php

namespace Todstoychev\Icr\Manipulator;

use Imagine\Image\AbstractImage;

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
     */
    public function manipulate(AbstractImage $image, $width, $height)
    {
        $this->createCropPoint($image, $width, $height);
        $this->box->setHeight($height)
            ->setWidth($width);

        return $image->crop($this->point, $this->box);
    }
}