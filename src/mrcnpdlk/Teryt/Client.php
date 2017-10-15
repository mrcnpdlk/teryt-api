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
    const SERVICE_URL_TEST      = 'https://uslugaterytws1test.stat.gov.pl/wsdl/terytws1.wsdl';
    const SERVICE_URL           = 'https://uslugaterytws1.stat.gov.pl/wsdl/terytws1.wsdl';
    const SERVICE_USER_TEST     = 'TestPubliczny';
    const SERVICE_PASSWORD_TEST = '1234abcd';
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
     * @var string
     */
    private $sServiceUrl;
    /**
     * @var string
     */
    private $sServiceUsername;
    /**
     * @var string
     */
    private $sServicePassword;

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->setConfig();
        $this->setLoggerInstance();
        $this->setCacheInstance();
    }

    /**
     * Set Teryt configuration parameters
     *
     * @param string|null $username     Service username
     * @param string|null $password     Service password
     * @param bool        $isProduction Default FALSE
     *
     * @return $this
     *
     */
    public function setConfig(string $username = null, string $password = null, bool $isProduction = false)
    {
        $this->sServiceUrl      = $isProduction ? Client::SERVICE_URL : Client::SERVICE_URL_TEST;
        $this->sServiceUsername = $username ?? Client::SERVICE_USER_TEST;
        $this->sServicePassword = $password ?? Client::SERVICE_PASSWORD_TEST;

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
            $this->soapClient = new TerytSoapClient($this->sServiceUrl, [
                'soap_version' => SOAP_1_1,
                'exceptions'   => true,
                'cache_wsdl'   => WSDL_CACHE_BOTH,
            ]);
            $this->soapClient->addUserToken($this->sServiceUsername, $this->sServicePassword);
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
     * Making request to Teryt WS1 API
     *
     * @param string  $method  Method name
     * @param array   $args    Parameters
     * @param boolean $addDate Add DataStanu to request
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
            $hashKey = $this->getHash(__METHOD__, $method, $args);
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
     * @param mixed ,... $arg
     *
     * @return string
     */
    public function getHash($arg)
    {
        $args = func_get_args();
        array_push($args, $this->sServiceUrl, $this->sServiceUsername, $this->sServicePassword);

        return md5(json_encode($args));
    }

    /**
     * Caching things
     *
     * @param \Closure $closure Function calling when cache is empty or not valid
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

    /**
     * @return array
     *
     */
    public function __debugInfo()
    {
        return ['Top secret'];
    }

}
