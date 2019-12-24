<?php

declare(strict_types=1);
/**
 * TERYT-API
 *
 * Copyright (c) 2019 pudelek.org.pl
 *
 * @license MIT License (MIT)
 *
 * For the full copyright and license information, please view source file
 * that is bundled with this package in the file LICENSE
 * @author  Marcin Pudełek <marcin@pudelek.org.pl>
 */

/**
 * Created by Marcin Pudełek <marcin@pudelek.org.pl>
 * Date: 24.12.2019
 * Time: 12:29
 */

namespace mrcnpdlk\Teryt;

use DateTime;
use Mrcnpdlk\Lib\ConfigurationOptionsAbstract;
use mrcnpdlk\Psr16Cache\Adapter;
use mrcnpdlk\Teryt\Exception\Connection;
use mrcnpdlk\Teryt\Exception\Response;
use Psr\SimpleCache\CacheInterface;

class Config extends ConfigurationOptionsAbstract
{
    public const SERVICE_URL_TEST      = 'https://uslugaterytws1test.stat.gov.pl/wsdl/terytws1.wsdl';
    public const SERVICE_URL           = 'https://uslugaterytws1.stat.gov.pl/wsdl/terytws1.wsdl';
    public const SERVICE_USER_TEST     = 'TestPubliczny';
    public const SERVICE_PASSWORD_TEST = '1234abcd';

    /**
     * @var string
     */
    protected $username = self::SERVICE_USER_TEST;
    /**
     * @var string
     */
    protected $password = self::SERVICE_PASSWORD_TEST;
    /**
     * @var bool
     */
    protected $isProduction = false;
    /**
     * @var \mrcnpdlk\Psr16Cache\Adapter
     */
    private $oCacheAdapter;
    /**
     * SoapClient handler
     *
     * @var \mrcnpdlk\Teryt\TerytSoapClient|null
     */
    private $soapClient;
    /**
     * @var \Psr\SimpleCache\CacheInterface|null
     */
    protected $cache;
    /**
     * @var string
     */
    private $serviceUrl;

    /**
     * Config constructor.
     *
     * @param array<string,mixed> $config
     *
     * @throws \Mrcnpdlk\Lib\ConfigurationException
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->oCacheAdapter = new Adapter($this->cache, $this->getLogger());
        $this->serviceUrl    = $this->isProduction ? self::SERVICE_URL : self::SERVICE_URL_TEST;
    }

    /**
     * Making request to Teryt WS1 API
     *
     * @param string               $method  Method name
     * @param array<string, mixed> $args    Parameters
     * @param bool                 $addDate Add DataStanu to request
     *
     * @throws \mrcnpdlk\Teryt\Exception
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     *
     * @return mixed
     */
    public function request(string $method, array $args = [], bool $addDate = true)
    {
        try {
            if (!array_key_exists('DataStanu', $args) && $addDate) {
                $args['DataStanu'] = (new DateTime())->format('Y-m-d');
            }
            $self = $this;
            $this->getLogger()->debug(sprintf('REQ: %s', $method), $args);

            $resp = $this->oCacheAdapter->useCache(
                static function () use ($self, $method, $args) {
                    $res       = $self->getSoap()->__soapCall($method, [$args]);
                    $resultKey = $method . 'Result';
                    if (!property_exists($res, $resultKey)) {
                        throw new Response(sprintf('%s doesnt exist in response', $resultKey));
                    }

                    return $res->{$resultKey};
                },
                [__METHOD__, $method, $args]
            );

            $this->getLogger()->debug(sprintf('RESP: %s, type is %s', $method, gettype($resp)));

            return $resp;
        } catch (\Exception $e) {
            throw Helper::handleException($e);
        }
    }

    /**
     * @param \Psr\SimpleCache\CacheInterface|null $cache
     *
     * @return Config
     */
    public function setCache(?CacheInterface $cache): Config
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @param bool $isProduction
     *
     * @return Config
     */
    public function setIsProduction(bool $isProduction): Config
    {
        $this->isProduction = $isProduction;

        return $this;
    }

    /**
     * @param string $password
     *
     * @return Config
     */
    public function setPassword(string $password): Config
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param string $username
     *
     * @return Config
     */
    public function setUsername(string $username): Config
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get SoapClient
     *
     * @return \mrcnpdlk\Teryt\TerytSoapClient
     */
    private function getSoap(): TerytSoapClient
    {
        try {
            if (null === $this->soapClient) {
                $this->reinitSoap();
            }
        } catch (\Exception $e) {
            Helper::handleException($e);
        }

        return $this->soapClient;
    }

    /**
     * Reinit Soap Client
     *
     * @throws \Exception
     * @throws Connection
     * @throws Exception
     *
     * @return $this
     */
    private function reinitSoap(): self
    {
        try {
            $this->soapClient = new TerytSoapClient($this->serviceUrl, [
                'soap_version' => SOAP_1_1,
                'exceptions'   => true,
                'cache_wsdl'   => WSDL_CACHE_BOTH,
            ]);
            $this->soapClient->addUserToken($this->username, $this->password);
        } catch (\Exception $e) {
            throw Helper::handleException($e);
        }

        return $this;
    }
}
