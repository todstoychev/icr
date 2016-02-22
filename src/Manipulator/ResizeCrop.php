<?php

namespace Todstoychev\Icr\Manipulator;

use Imagine\Image\AbstractImage;

/**
 * Class ResizeCrop
 *
 * @package Todstoychev\Icr\Manipulator
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class ResizeCrop extends AbstractManipulator
{
    /**
     * {@inheritdoc}
     */
    public function manipulate(AbstractImage $image, $width, $height)
    {
        $box = $this->calculateResize($image, $width, $height);
        $image = $image->resize($box);

        $point = $this->createCropPoint($image, $width, $height);
        $this->box->setWidth($width)
            ->setHeight($height);

        return $image->crop($point, $this->box);
    }
}