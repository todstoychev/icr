<?php

namespace Todstoychev\Icr\Manipulator;

use Imagine\Image\AbstractImage;

/**
 * Performs resize operation. Resize image while keeps the ratio.
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
        $box = $this->calculateResize($image, $width, $height);

        return $image->resize($box);
    }
}