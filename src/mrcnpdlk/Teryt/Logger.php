<?php
/**
 * TERYT-API
 *
 * Copyright (c) 2017 pudelek.org.pl
 *
 * For the full copyright and license information, please view source file
 * that is bundled with this package in the file LICENSE
 *
 * Author Marcin Pudełek <marcin@pudelek.org.pl>
 *
 */

/**
 * Created by Marcin Pudełek <marcin@pudelek.org.pl>
 * Date: 07.09.2017
 */

namespace mrcnpdlk\Teryt;

/**
 * Class Logger
 *
 * @package mrcnpdlk\Teryt
 */
class Logger
{
    /**
     * Instance of Logger
     *
     * @var Logger
     */
    protected static $_instance;
    /**
     * External logger handler
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $oLogger;

    /**
     * Logger constructor.
     *
     * @param \Psr\Log\LoggerInterface|null $oLogger
     */
    protected function __construct(\Psr\Log\LoggerInterface $oLogger = null)
    {
        $this->oLogger = $oLogger;
    }

    /**
     * Retur Logger class instance
     *
     * If not exists create it
     *
     * @param \Psr\Log\LoggerInterface|null $oLogger
     *
     * @return Logger
     */
    public static function create(\Psr\Log\LoggerInterface $oLogger = null)
    {
        if (!static::$_instance) {
            static::$_instance = new static($oLogger);
        }

        return static::$_instance;
    }

    /**
     * Debug item
     *
     * @param       $message
     * @param array $context
     */
    public static function debug($message, array $context = [])
    {
        if ($oLogger = static::getInstance()->getLogger()) {
            $oLogger->debug($message, $context);
        }
    }

    /**
     * Get external logger handler
     *
     * @return null|\Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->oLogger;
    }

    /**
     * Retur Logger class instance
     *
     * @return \mrcnpdlk\Teryt\Logger
     * @throws \Exception
     */
    public static function getInstance()
    {
        if (!static::$_instance) {
            throw new \Exception('First create');
        }

        return static::$_instance;
    }
}
