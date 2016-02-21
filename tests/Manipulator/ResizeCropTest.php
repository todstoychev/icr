<?php

namespace Todstoychev\Icr\Tests\Manipulator;

use Imagine\Gd\Imagine;
use Imagine\Image\AbstractImage;
use Todstoychev\Icr\Manipulator;

/**
 * Class ResizeCropTest
 *
 * @package Todstoychev\Icr\Tests\Manipulator
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class ResizeCropTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractImage
     */
    protected $image;

    /**
     * @var Manipulator\ResizeCrop
     */
    protected $resizeCrop;

    /**
     * @var string
     */
    protected $path;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->image = new Imagine();
        $this->path = __DIR__ . '/../fixtures/test_image.png';
        $this->resizeCrop = new Manipulator\ResizeCrop(new Manipulator\Box(), new Manipulator\Point());
    }

    /**
     * Tests manipulate with correct values
     */
    public function testManipulateWithCorrectValues()
    {
        $image = $this->resizeCrop->manipulate($this->image->open($this->path), 200, 200);
        static::assertEquals(200, $image->getSize()->getWidth());
        static::assertEquals(200, $image->getSize()->getHeight());
    }

    /**
     * Tests manipulate with float
     */
    public function testManipulateWithFloat()
    {
        $image = $this->resizeCrop->manipulate($this->image->open($this->path), 200.10, 200.10);
        static::assertEquals(200, $image->getSize()->getWidth());
        static::assertEquals(200, $image->getSize()->getHeight());
    }

    /**
     * Tests manipulate with numeric string
     */
    public function testsManipulateWithNumericString()
    {
        $image = $this->resizeCrop->manipulate($this->image->open($this->path), '200', '200');
        static::assertEquals(200, $image->getSize()->getWidth());
        static::assertEquals(200, $image->getSize()->getHeight());
    }

    /**
     * Test manipulate with string
     */
    public function testManipulateWithNonNumericString()
    {
        static::setExpectedExceptionRegExp('LogicException');
        $this->resizeCrop->manipulate($this->image->open($this->path), 'test', 'test');
    }
}
