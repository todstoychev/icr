<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.02.16
 * Time: 22:39
 */

namespace Todstoychev\Icr\Tests;

use Todstoychev\Icr\Handler\ConfigurationValidationHandler;


/**
 * Class ConfigurationValidationHandlerTest
 *
 * @package Todstoychev\Icr\Tests
 * @author Todor Todorov <todstoychev@gmail.com>
 */
class ConfigurationValidationHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConfigurationValidationHandler
     */
    protected $configurationValidationHandler;

    /**
     * @var array
     */
    protected $config;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->config = [
            'context' => [
                'test' => [
                    'width' => 100,
                    'height' => 100,
                    'operation' => 'resize',
                ],
                'small_test' => [
                    'width' => 200,
                    'height' => 200,
                    'operation' => 'resize-crop',
                ],
                'medium_test' => [
                    'width' => 300,
                    'height' => 300,
                    'operation' => 'scale',
                ],
                'big_test' => [
                    'width' => 400,
                    'height' => 400,
                    'operation' => 'crop',
                ],
            ],
        ];

        $this->configurationValidationHandler = new ConfigurationValidationHandler($this->config);
    }

    /**
     * Test validate context
     *
     * @throws \Todstoychev\Icr\Exception\NonExistingContextException
     */
    public function testValidateContext()
    {
        foreach (array_keys($this->config) as $context) {
            $result = $this->configurationValidationHandler->validateContext($context);

            static::assertSame($result, $this->configurationValidationHandler);
        }
        static::setExpectedException(
            'Todstoychev\Icr\Exception\NonExistingContextException'
        );

        $this->configurationValidationHandler->validateContext('wrong');
    }

    /**
     * Test validate config values
     */
    public function testValidateConfigValues()
    {
        foreach (array_keys($this->config) as $context) {
            $result = $this->configurationValidationHandler->validateConfigValues($context);

            static::assertSame($result, $this->configurationValidationHandler);
        }

        static::setExpectedException(
            'Todstoychev\Icr\Exception\MandatoryConfigValueMissingException'
        );

        $config = [
            'context' => [
                'test' => [
                ],
            ],
        ];

        $this->configurationValidationHandler->setConfig($config);
        $this->configurationValidationHandler->validateConfigValues('context');

        $config = [
            'context' => [
                'test' => [
                    'width' => 'sss',
                    'height' => 'sss',
                    'operation' => 'scale',
                ],
            ],
        ];

        static::setExpectedException(
            'Todstoychev\Icr\Exception\InvalidConfigurationValueException'
        );

        $this->configurationValidationHandler->setConfig($config);
        $this->configurationValidationHandler->validateConfigValues('context');
    }

    /**
     * Tests validate driver
     *
     * @throws \Todstoychev\Icr\Exception\InvalidConfigurationValueException
     */
    public function testValidateDriver()
    {
        $result = $this->configurationValidationHandler->setConfig(['driver' => 'gd']);
        $this->configurationValidationHandler->validateDriver();

        static::assertSame($result, $this->configurationValidationHandler);

        static::setExpectedException('Todstoychev\Icr\Exception\InvalidConfigurationValueException');

        $this->configurationValidationHandler->setConfig(['driver' => '']);
        $this->configurationValidationHandler->validateDriver();
        $this->configurationValidationHandler->setConfig(['wrong' => '']);
        $this->configurationValidationHandler->validateDriver();
    }

}
