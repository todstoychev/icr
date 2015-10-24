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
     * @var string
     */
    protected $context;

    /**
     * @param array $config
     * @param string $context
     */
    public function __construct(array $config = [], $context = '')
    {
        $this->setContext($context);
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

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string $context
     *
     * @return BaseHandler
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Gets the uploads path set in the configuration
     *
     * @return string
     */
    public function getUploadsPath()
    {
        if (!array_key_exists('uploads_path', $this->getConfig())) {
            throw new Exception\NonExistingArrayKeyException('Array key "uploads_path" not set in configuration array!');
        }

        return $this->config['uploads_path'];
    }

    /**
     * Get context values array. The context values array contains data for the image sizes, width, height and etc.
     * @example
     * [
     *  'large' => [
     *      'width' => 2000,
     *      'height' => 3000,
     *      'operation' => 'resize'
     *  ],
     *  'medium' => [
     *      ...
     *  ],
     *  ...
     * ]
     *
     * @param string $context
     *
     * @return array
     * @throws NonExsitingContextException
     */
    public function getContextValues($context = '')
    {
        $context = (empty($context)) ? $this->getContext() : $context;

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
    public function getAllowedFileTypes()
    {
        return $this->config['allowed_filetypes'];
    }
}