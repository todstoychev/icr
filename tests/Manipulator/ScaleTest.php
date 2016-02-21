<?php

namespace Todstoychev\Icr\Tests\Manipulator;

use Imagine\Gd\Imagine;
use Imagine\Image\AbstractImage;
use Todstoychev\Icr\Manipulator;

/**
 * Class ScaleTest
 *
 * @package Todstoychev\Icr\Tests\Manipulator
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class ScaleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractImage
     */
    protected $image;

    /**
     * @var Manipulator\Scale
     */
    protected $scale;

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
        $this->scale = new Manipulator\Scale(new Manipulator\Box(), new Manipulator\Point());
    }

    /**
     * Tests manipulate with correct values
     */
    public function testManipulateWithCorrectValues()
    {
        $image = $this->scale->manipulate($this->image->open($this->path), 200, 200);
        static::assertEquals(200, $image->getSize()->getWidth());
        static::assertEquals(200, $image->getSize()->getHeight());
    }

    /**
     * Tests manipulate with float
     */
    public function testManipulateWithFloat()
    {
        $image = $this->scale->manipulate($this->image->open($this->path), 200.10, 200.10);
        static::assertEquals(200, $image->getSize()->getWidth());
        static::assertEquals(200, $image->getSize()->getHeight());
    }

    /**
     * Tests manipulate with numeric string
     */
    public function testsManipulateWithNumericString()
    {
        $image = $this->scale->manipulate($this->image->open($this->path), '200', '200');
        static::assertEquals(200, $image->getSize()->getWidth());
        static::assertEquals(200, $image->getSize()->getHeight());
    }

    /**
     * Test manipulate with string
     */
    public function testManipulateWithNonNumericString()
    {
        static::setExpectedExceptionRegExp('LogicException');
        $this->scale->manipulate($this->image->open($this->path), 'test', 'test');
    }
}
