<?php

namespace Todstoychev\Icr\Handler;

use Todstoychev\Icr\Exception;
use Todstoychev\Icr\StaticData;

/**
 * Validator class used to validate the configuration
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\Validator
 */
class ConfigurationValidationHandler extends AbstractHandler
{

    public function validateContext($context)
    {
        // Check is context set
        if (!array_key_exists($context, $this->getConfig())) {
            throw new Exception\NonExistingContextException("Context {$context} does not exists!");
        }

        return $this;
    }

    public function validateConfigValues($context)
    {
        foreach ($this->config[$context] as $values) {
            foreach (StaticData\MandatoryContextValues::$mandatoryConfigValueKeys as $keyName) {
                $this->validateKeyValues($keyName, $values);
                $this->validateValuesType($values);
            }
        }

        return $this;
    }

    public function validateDriver()
    {
        if (
            !array_key_exists('driver', $this->getConfig()) ||
            !in_array($this->config['driver'], StaticData\Drivers::$allowedDrivers)
        ) {
            throw new Exception\InvalidConfigurationValueException('Invalid or missing driver configuration value!');
        }

        return $this;
    }

    protected function validateKeyValues($keyName, array $values)
    {
        if (!array_key_exists($keyName, $values)) {
            throw new Exception\MandatoryConfigValueMissingException(
                "Parameter {$keyName} is not set in the configuration!"
            );
        }
    }

    protected function validateValuesType(array $values)
    {
        if (!in_array($values['operation'], StaticData\Operations::$allowedOperations)) {
            throw new Exception\InvalidConfigurationValueException(
                'Invalid configuration value provided for operation!'
            );
        }

        if (!is_int($values['width'])) {
            throw new Exception\InvalidConfigurationValueException(
                'Invalid configuration value provided for width!'
            );
        }

        if (!is_int($values['height'])) {
            throw new Exception\InvalidConfigurationValueException(
                'Invalid configuration value provided for height!'
            );
        }
    }
}