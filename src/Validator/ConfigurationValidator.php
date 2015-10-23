<?php

namespace Todstoychev\Icr\Validator;

use Todstoychev\Icr\Exception;
use Todstoychev\Icr\StaticData\MandatoryConfigValues;
use Todstoychev\Icr\StaticData\Operations;

/**
 * Validator class used to validate the configuration
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\Validator
 */
class ConfigurationValidator
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $context;

    /**
     * @param array $config
     * @param string $context
     */
    public function __construct(array $config, $context = null)
    {
        $this->setConfig($config);
        $this->setContext($context);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     * @return ConfigurationValidator
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string $context
     * @return ConfigurationValidator
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Validates the configuration
     *
     * @throws Exception\ContextDoesNotExistsException
     * @throws Exception\MandatoryConfigValueMissingException
     */
    public function validate()
    {
        $this->validateContext();
        $this->validateConfigValues();
    }

    /**
     * Validates the context
     *
     * @throws Exception\ContextDoesNotExistsException
     */
    protected function validateContext()
    {
        // Check is context set
        if (!array_key_exists($this->getContext(), $this->getConfig())) {
            throw new Exception\ContextDoesNotExistsException("Context {$this->getContext()} does not exists!");
        }
    }

    /**
     * Validates the configuration values
     *
     * @todo Improve this method. Too much nested statements.
     *
     * @throws Exception\MandatoryConfigValueMissingException
     */
    protected function validateConfigValues()
    {
        foreach ($this->config[$this->getContext()] as $sizeName => $values) {
            foreach (MandatoryConfigValues::$mandatoryConfigValueKeys as $keyName) {
                if (!array_key_exists($keyName, $values)) {
                    throw new Exception\MandatoryConfigValueMissingException(
                        "Parameter {$keyName} is not set in the configuration!"
                    );
                }

                // Validate values type
                if (!in_array($values['operation'], Operations::$allowedOperations)) {
                    throw new Exception\InvalidConfigValueException(
                        'Invalid cofiguration value provided for operation!'
                    );
                }

                if (!is_int($values['width'])) {
                    throw new Exception\InvalidConfigValueException(
                        'Invalid configuration value provided for width!'
                    );
                }

                if (!is_int($values['height'])) {
                    throw new Exception\InvalidConfigValueException(
                        'Invalid configuration value provided for height!'
                    );
                }
            }
        }
    }
}