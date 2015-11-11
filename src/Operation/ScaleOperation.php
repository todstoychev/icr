<?php

namespace Todstoychev\Icr\Operation;

use Imagine\Image\Box;

/**
 * Performs scale operation
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\Operation
 */
class ScaleOperation extends AbstractOperation
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
     * @inheritdoc
     */
    protected function createBox()
    {
        return new Box($this->getWidth(), $this->getHeight());
    }
}