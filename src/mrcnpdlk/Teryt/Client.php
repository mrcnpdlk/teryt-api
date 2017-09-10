<?php
/**
 * TERYT-API
 *
 * Copyright (c) 2017 pudelek.org.pl
 *
 * @license MIT License (MIT)
 *
 * For the full copyright and license information, please view source file
 * that is bundled with this package in the file LICENSE
 *
 * @author  Marcin Pudełek <marcin@pudelek.org.pl>
 *
 */

declare (strict_types=1);

namespace mrcnpdlk\Teryt;

use mrcnpdlk\Teryt\Exception\Connection;
use mrcnpdlk\Teryt\Exception\Response;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\SimpleCache\CacheInterface;

/**
 * Class Client
 *
 * @package mrcnpdlk\Teryt
 */
class Client
{
    /**
     * Client instance
     *
     * @var \mrcnpdlk\Teryt\Client
     */
    protected static $classInstance;
    /**
     * SoapClient handler
     *
     * @var \mrcnpdlk\Teryt\TerytSoapClient
     */
    private $soapClient;
    /**
     * Cache handler
     *
     * @var CacheInterface
     */
    private $oCache;
    /**
     * Logger handler
     *
     * @var LoggerInterface
     */
    private $oLogger;
    /**
     * Teryt auth configuration
     *
     * @var array
     */
    private $tTerytConfig = [];
    /**
     * Default Teryt auth configuration
     *
     * @var array
     */
    private $tDefTerytConfig
        = [
            'url'      => 'https://uslugaterytws1test.stat.gov.pl/wsdl/terytws1.wsdl',
            'username' => 'TestPubliczny',
            'password' => '1234abcd',
        ];

    /**
     * Client constructor.
     */
    protected function __construct()
    {
        $this->setTerytConfig();
        $this->setLoggerInstance();
        $this->setCacheInstance();
    }

    /**
     * Set Teryt configuration parameters
     *
     * @param array $tConfig
     *
     * @return $this
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     */
    public function setTerytConfig(array $tConfig = [])
    {
        if (empty($tConfig)) {
            $tConfig = $this->tDefTerytConfig;
        }
        $this->tTerytConfig['url']      = $tConfig['url'] ?? 'https://uslugaterytws1.stat.gov.pl/wsdl/terytws1.wsdl';
        $this->tTerytConfig['username'] = $tConfig['username'] ?? null;
        $this->tTerytConfig['password'] = $tConfig['password'] ?? null;

        if (!$this->tTerytConfig['username'] || !$this->tTerytConfig['password']) {
            throw new Connection(sprintf('Username and password for TERYT WS1 is required'));
        }
        $this->reinitSoap();

        return $this;
    }

    /**
     * Reinit Soap Client
     *
     * @return $this
     * @throws Connection
     * @throws Exception
     */
    private function reinitSoap()
    {
        try {
            $this->soapClient = new TerytSoapClient($this->tTerytConfig['url'], [
                'soap_version' => SOAP_1_1,
                'exceptions'   => true,
                'cache_wsdl'   => WSDL_CACHE_BOTH,
            ]);
            $this->soapClient->addUserToken($this->tTerytConfig['username'], $this->tTerytConfig['password']);
        } catch (\Exception $e) {
            throw Helper::handleException($e);
        }

        return $this;
    }

    /**
     * Set Logger handler (PSR-3)
     *
     * @param LoggerInterface|null $oLogger
     *
     * @return $this
     */
    public function setLoggerInstance(LoggerInterface $oLogger = null)
    {
        $this->oLogger = $oLogger ?: new NullLogger();

        return $this;
    }

    /**
     * Set Cache handler (PSR-16)
     *
     * @param CacheInterface|null $oCache
     *
     * @return \mrcnpdlk\Teryt\Client
     * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-16-simple-cache.md PSR-16
     */
    public function setCacheInstance(CacheInterface $oCache = null)
    {
        $this->oCache = $oCache;

        return $this;
    }

    /**
     * Get Client class instance
     *
     * @return \mrcnpdlk\Teryt\Client Instancja klasy
     * @throws \mrcnpdlk\Teryt\Exception
     */
    public static function getInstance()
    {
        if (!static::$classInstance) {
            static::$classInstance = new static;
        }

        return static::$classInstance;
    }

    /**
     * Making request to Teryt WS1 API
     *
     * @param string  $method  Methid name
     * @param array   $args    Parameters
     * @param boolean $addDate Add DataSTanu to request
     *
     * @return mixed
     * @throws \mrcnpdlk\Teryt\Exception
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     */
    public function request(string $method, array $args = [], bool $addDate = true)
    {
        try {
            if (!array_key_exists('DataStanu', $args) && $addDate) {
                $args['DataStanu'] = (new \DateTime())->format('Y-m-d');
            }
            $hashKey = md5(json_encode([__METHOD__, $method, $args]));
            $self    = $this;
            $this->oLogger->debug(sprintf('REQ: %s, hash: %s', $method, $hashKey), $args);

            $resp = $this->useCache(
                function () use ($self, $method, $args) {
                    $res       = $self->getSoap()->__soapCall($method, [$args]);
                    $resultKey = $method . 'Result';
                    if (!property_exists($res, $resultKey)) {
                        throw new Response(sprintf('%s doesnt exist in response', $resultKey));
                    }

                    return $res->{$resultKey};
                },
                $hashKey);

            $this->oLogger->debug(sprintf('RESP: %s, type is %s', $method, gettype($resp)));

            return $resp;

        } catch (\Exception $e) {
            throw Helper::handleException($e);
        }
    }

    /**
     * Caching things
     *
     * @param \Closure $closure Function calling wheen cache is empty or not valid
     * @param mixed    $hashKey Cache key of item
     * @param int|null $ttl     Time to live for item
     *
     * @return mixed
     */
    private function useCache(\Closure $closure, string $hashKey, int $ttl = null)
    {
        if ($this->oCache) {
            if ($this->oCache->has($hashKey)) {
                $answer = $this->oCache->get($hashKey);
                $this->oLogger->debug(sprintf('CACHE [%s]: geting from cache', $hashKey));
            } else {
                $answer = $closure();
                $this->oCache->set($hashKey, $answer, $ttl);
                $this->oLogger->debug(sprintf('CACHE [%s]: old, reset', $hashKey));
            }
        } else {
            $this->oLogger->debug(sprintf('CACHE [%s]: no implemented', $hashKey));
            $answer = $closure();
        }

        return $answer;
    }

    /**
     * Get SoapClient
     *
     * @return \mrcnpdlk\Teryt\TerytSoapClient
     */
    private function getSoap()
    {
        try {
            if (!$this->soapClient) {
                $this->reinitSoap();
            }

        } catch (\Exception $e) {
            Helper::handleException($e);
        }

        return $this->soapClient;
    }

    /**
     * Get logger instance
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->oLogger;
    }

    public function __debugInfo()
    {
        return ['Top secret'];
    }
}
