<?php

namespace Todstoychev\Icr\Handler;

use Illuminate\Support\Facades\Config;
use Todstoychev\Icr\Exception;

/**
 * Abstract handler class necessary to create handlers
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package Todstoychev\Icr\Handler
 */
class AbstractHandler
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param string $config
     */
    public function __construct($config = '')
    {
        $this->setConfig($config);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param string $config
     *
     * @return BaseHandler
     */
    public function setConfig($config)
    {
        $this->config = Config::get($config);

        return $this;
    }

    /**
     * Gets the uploads path parameter from configuration
     *
     * @return string
     * @throws Exception\NonExistingArrayKeyException
     */
    public function getUploadsPath()
    {
        if (!array_key_exists('uploads_path', $this->getConfig())) {
            throw new Exception\NonExistingArrayKeyException('Array key "uploads_path" not set in configuration array!');
        }

        return $this->config['uploads_path'];
    }

    /**
     * Gets the context values array
     *
     * @param $context
     *
     * @return array
     * @throws Exception\NonExistingContextException
     */
    public function getContextValues($context)
    {
        if (!array_key_exists($context, $this->config)) {
            throw new Exception\NonExistingContextException("No context values found for '{$context}'");
        }

        return $this->config[$context];
    }

    /**
     * Gets array of allowed mime types and extensions
     *
     * @example
     * [
     *  'image/jpeg' => [
     *      'jpeg', 'jpg'
     *  ],
     * ...
     * ]
     *
     * @return array
     */
    public function getAllowedFileTypes($context)
    {
        return $this->config['allowed_filetypes'][$context];
    }
}