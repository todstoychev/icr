<?php

namespace Todstoychev\Icr\Handler;

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
     * @param array $config
     */
    public function __construct(array $config = [])
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
     * @param array $config
     *
     * @return BaseHandler
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    public function getUploadsPath()
    {
        if (!array_key_exists('uploads_path', $this->getConfig())) {
            throw new Exception\NonExistingArrayKeyException('Array key "uploads_path" not set in configuration array!');
        }

        return $this->config['uploads_path'];
    }

    public function getContextValues($context)
    {
        if (!array_key_exists($context, $this->config)) {
            throw new Exception\NonExsitingContextException("No context values found for '{$context}'");
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