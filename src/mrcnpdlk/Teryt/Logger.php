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


class Logger
{
    protected static $_instance;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $oLogger;

    protected function __construct(\Psr\Log\LoggerInterface $oLogger = null)
    {
        $this->oLogger = $oLogger;
    }

    public static function create(\Psr\Log\LoggerInterface $oLogger = null)
    {
        if (!static::$_instance) {
            static::$_instance = new static($oLogger);
        }

        return static::$_instance;
    }

    public static function debug($message, array $context = [])
    {
        if ($oLogger = static::getInstance()->getLogger()) {
            $oLogger->debug($message, $context);
        }
    }

    public function getLogger()
    {
        return $this->oLogger;
    }

    /**
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
