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
            throw new Exception\NonExsitingContextException("Context {$this->getContext()} does not exists!");
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
            foreach (StaticData\MandatoryConfigValues::$mandatoryConfigValueKeys as $keyName) {
                if (!array_key_exists($keyName, $values)) {
                    throw new Exception\MandatoryConfigValueMissingException(
                        "Parameter {$keyName} is not set in the configuration!"
                    );
                }

                // Validate values type
                if (!in_array($values['operation'], StaticData\Operations::$allowedOperations)) {
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