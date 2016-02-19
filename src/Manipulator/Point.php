<?php

namespace Todstoychev\Icr\Manipulator;

use Imagine\Image\BoxInterface;
use Imagine\Image\PointInterface;
use Todstoychev\Icr\Exception\IcrRuntimeException;

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
    protected $box;

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
        $this->checkBoxInstance();

        return $this->box->getWidth();
    }

    /**
     * {@inheritdoc}
     */
    public function getY()
    {
        $this->checkBoxInstance();

        return $this->box->getHeight();
    }

    /**
     * {@inheritdoc}
     */
    public function in(BoxInterface $box)
    {
        $this->checkBoxInstance();

        return $this->getX() < $box->getWidth() && $this->getY() < $box->getHeight();
    }

    /**
     * {@inheritdoc}
     */
    public function move($amount)
    {
        $x = $this->getX() + $amount;
        $y = $this->getY() + $amount;

        if ($x < 0 || $y < 0) {
            throw new \LogicException('Can not move with negative result!');
        }

        return new Point(new Box($x, $y));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('(%d, %d)', $this->getX(), $this->getY());
    }

    /**
     * Checks is box instance set
     */
    protected function checkBoxInstance()
    {
        if (!$this->box instanceof Box) {
            throw new IcrRuntimeException('Box instance not set!');
        }
    }
}