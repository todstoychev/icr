<?php

namespace Todstoychev\Icr\Manipulator;

use Imagine\Image\BoxInterface;
use Imagine\Image\PointInterface;

/**
 * Class Point
 *
 * @package Todstoychev\Icr\Manipulator
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class Point implements PointInterface
{
    /**
     * @var BoxInterface
     */
    private $box;

    /**
     * Constructs coordinate with size instance, it needs to be relative to
     *
     * @param BoxInterface $box
     */
    public function __construct(BoxInterface $box = null)
    {
        $this->box = $box;
    }

    /**
     * @param BoxInterface $box
     *
     * @return Point
     */
    public function setBox(BoxInterface $box)
    {
        $this->box = $box;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getX()
    {
        return ceil($this->box->getWidth() / 2);
    }

    /**
     * {@inheritdoc}
     */
    public function getY()
    {
        return ceil($this->box->getHeight() / 2);
    }

    /**
     * {@inheritdoc}
     */
    public function in(BoxInterface $box)
    {
        return $this->getX() < $box->getWidth() && $this->getY() < $box->getHeight();
    }

    /**
     * {@inheritdoc}
     */
    public function move($amount)
    {
        return new Point($this->getX() + $amount, $this->getY() + $amount);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('(%d, %d)', $this->getX(), $this->getY());
    }
}