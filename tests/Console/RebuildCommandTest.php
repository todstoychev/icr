<?php

namespace Todstoychev\Icr\Tests;

use Todstoychev\Icr\Console\RebuildCommand;
use Todstoychev\Icr\Processor;

/**
 * Class RebuildCommandTest
 *
 * @package Todstoychev\Icr\Tests
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class RebuildCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RebuildCommand
     */
    protected $rebuildCommand;

    /**
     * @var Processor
     */
    protected $processor;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->processor = static::getMockBuilder('Todstoychev\Icr\Processor')
            ->disableOriginalConstructor()
            ->getMock();
        $this->rebuildCommand = new RebuildCommand($this->processor);
    }

    /**
     * Test construction
     */
    public function testConstruct()
    {
        static::assertAttributeInstanceOf('Todstoychev\Icr\Processor', 'processor', $this->rebuildCommand);
    }
}
