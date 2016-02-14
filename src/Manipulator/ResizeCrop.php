<?php

namespace Todstoychev\Icr\Manipulator;

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
     *
     * @return mixed
     */
    public function manipulate()
    {
        $this->checkImage();
        $this->calculateResize($this->width, $this->height);
        $this->image->resize($this->box);

        $this->createCropPoint($this->width, $this->height);
        $this->box->setWidth($this->width)
            ->setHeight($this->height);

        return $this->image->crop($this->point, $this->box);
    }
}