<?php

namespace Todstoychev\Icr\Tests\Manipulator;

use Todstoychev\Icr\Manipulator\Box;
use Todstoychev\Icr\Manipulator\ManipulatorFactory;
use Todstoychev\Icr\Manipulator\Point;

/**
 * Class ManipulatorFactoryTest
 *
 * @package Todstoychev\Icr\Tests\Manipulator
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class ManipulatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ManipulatorFactory
     */
    protected $factory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->factory = new ManipulatorFactory(new Box(), new Point());
    }

    /**
     * Tests create with correct values
     */
    public function testCreateWithCorrectValues()
    {
        $manipulation = $this->factory->create('crop');
        static::assertInstanceOf('Todstoychev\Icr\Manipulator\Crop', $manipulation);

        $manipulation = $this->factory->create('Crop');
        static::assertInstanceOf('Todstoychev\Icr\Manipulator\Crop', $manipulation);

        $manipulation = $this->factory->create('CROP');
        static::assertInstanceOf('Todstoychev\Icr\Manipulator\Crop', $manipulation);

        $manipulation = $this->factory->create('crOp');
        static::assertInstanceOf('Todstoychev\Icr\Manipulator\Crop', $manipulation);
    }

    /**
     * Test with non existing operation name
     */
    public function testCreateWithNonExistingOperation()
    {
        static::setExpectedExceptionRegExp('Todstoychev\Icr\Exception\IcrRuntimeException');
        $this->factory->create('operation');
    }

    /**
     * Tests with wrong operation value type
     */
    public function testWithWrongOperationNameValueType()
    {
        static::setExpectedExceptionRegExp('LogicException');
        $this->factory->create(1000);
    }
}
