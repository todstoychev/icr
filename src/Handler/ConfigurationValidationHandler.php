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
    public function validate($context)
    {
        $this->setContext($context)->validateContext()->validateConfigValues();
    }

    protected function validateContext()
    {
        // Check is context set
        if (!array_key_exists($this->getContext(), $this->getConfig())) {
            throw new Exception\NonExistingContextException("Context {$this->getContext()} does not exists!");
        }

        return $this;
    }

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
                    throw new Exception\InvalidConfigurationValueException(
                        'Invalid cofiguration value provided for operation!'
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

        return $this;
    }
}