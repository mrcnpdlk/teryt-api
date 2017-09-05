<?php
/**
 * Copyright (c) 2017.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types=1);

namespace mrcnpdlk\Teryt;

use mrcnpdlk\Teryt\Exception\Connection;
use mrcnpdlk\Teryt\Exception\Response;
use mrcnpdlk\Teryt\Model\Province;
use mrcnpdlk\Teryt\Model\ProvinceData;
use phpFastCache\Core\Pool\ExtendedCacheItemPoolInterface;

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
    protected static $_instance;
    /**
     * @var \mrcnpdlk\Teryt\TerytSoapClient
     */
    private $soapClient;
    /**
     * @var \phpFastCache\Core\Pool\ExtendedCacheItemPoolInterface
     */
    private $oCache;
    /**
     * @var array
     */
    private $tTerytConfig = [];
    /**
     * @var array
     */
    private $tDefTerytConfig
        = [
            'url'      => 'https://uslugaterytws1test.stat.gov.pl/wsdl/terytws1.wsdl',
            'username' => 'TestPubliczny',
            'password' => '1234abcd',
        ];

    /**
     * @return \mrcnpdlk\Teryt\Client
     */
    public static function create()
    {
        if (!static::$_instance) {
            static::$_instance = new static;
        }

        return static::$_instance;
    }

    /**
     * Get class instance
     *
     * @return \mrcnpdlk\Teryt\Client Instancja klasy
     * @throws \mrcnpdlk\Teryt\Exception
     */
    public static function getInstance()
    {
        if (!static::$_instance) {
            throw new Exception(sprintf('First use Client::create() method to instanciate class'));
        }

        return static::$_instance;
    }

    /**
     * Client constructor.
     */
    protected function __construct()
    {
        $this->tTerytConfig = $this->tDefTerytConfig;
    }


    /**
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
        $this->tTerytConfig['url']      = $tConfig['url'] ?: 'https://uslugaterytws1.stat.gov.pl/terytws1.svc';
        $this->tTerytConfig['username'] = $tConfig['username'] ?: null;
        $this->tTerytConfig['password'] = $tConfig['password'] ?: null;;

        if (!$this->tTerytConfig['username'] || !$this->tTerytConfig['password']) {
            throw new Connection(sprintf('Username and password for TERYT WS1 is required'));
        }
        $this->getSoap(true);

        return $this;
    }

    /**
     * @param \phpFastCache\Core\Pool\ExtendedCacheItemPoolInterface|null $oCache
     *
     * @return $this
     * @see \phpFastCache\CacheManager::setDefaultConfig();
     */
    public function setCacheInstace(ExtendedCacheItemPoolInterface $oCache = null)
    {
        $this->oCache = $oCache;

        return $this;
    }

    /**
     * @param bool $bReinit
     *
     * @return \mrcnpdlk\Teryt\TerytSoapClient
     */
    private function getSoap(bool $bReinit = false)
    {
        try {
            if (!$this->soapClient || $bReinit) {
                $this->soapClient = new TerytSoapClient($this->tTerytConfig['url'], [
                    'soap_version' => SOAP_1_1,
                    'exceptions'   => true,
                    'cache_wsdl'   => WSDL_CACHE_BOTH,
                ]);
                $this->soapClient->addUserToken($this->tTerytConfig['username'], $this->tTerytConfig['password']);
            }

        } catch (\Exception $e) {
            Helper::handleException($e);
        }

        return $this->soapClient;
    }

    /**
     * Czy zalogowany
     *
     * @return bool
     */
    public function isLogged()
    {
        return Helper::convertToBoolean($this->getResponse('CzyZalogowany'));
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     * @throws \Throwable
     * @throws \mrcnpdlk\Teryt\Exception
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     */
    public function getResponse(string $method, array $args = [])
    {
        try {
            if (!array_key_exists('DataStanu', $args)) {
                $args['DataStanu'] = (new \DateTime())->format('Y-m-d');
            }
            $hashKey = md5(json_encode([__METHOD__, $method, $args]));
            $self    = $this;

            return $this->useCache(
                function () use ($self, $method, $args) {
                    $res       = $self->getSoap()->__soapCall($method, [$args]);
                    $resultKey = $method . 'Result';

                    if (!isset($res->{$resultKey})) {
                        throw new Response(sprintf('%s doesnt exist in response', $resultKey));
                    }

                    return $res->{$resultKey};
                },
                $hashKey);

        } catch (\Exception $e) {
            throw Helper::handleException($e);
        }
    }

    /**
     * Cache management
     *
     * @param \Closure $closure
     * @param string   $hashKey
     * @param int|null $ttl
     *
     * @return mixed
     */
    public function useCache(\Closure $closure, string $hashKey, int $ttl = null)
    {
        if ($this->oCache && $this->oCache->hasItem($hashKey)) {
            return $this->oCache->getItem($hashKey)->get();
        } else {
            $answer = $closure();

            if ($this->oCache) {
                $CachedString = $this->oCache
                    ->getItem($hashKey)
                    ->set($answer)
                ;
                if ($ttl) {
                    $CachedString->expiresAfter($ttl);
                }
                $this->oCache->save($CachedString); // Save the cache item just like you do with doctrine and entities
            }

            return $answer;
        }
    }

    /**
     * Get province object with ID
     *
     * @param string $provinceId
     *
     * @return Province
     */
    public function getProvince(string $provinceId)
    {
        return Province::create($provinceId);
    }

    /**
     * Lista województw
     *
     * @return ProvinceData[]
     * @throws \mrcnpdlk\Teryt\Exception
     */
    public function getProvinces()
    {
        $answer = [];
        $res    = $this->getResponse('PobierzListeWojewodztw');
        if (isset($res->JednostkaTerytorialna)) {
            foreach ($res->JednostkaTerytorialna as $p) {
                $answer[] = ProvinceData::create($p);
            };

            return $answer;
        } else {
            throw new Exception(sprintf('%s Empty response', __METHOD__));
        }
    }

    /**
     * Lista powiatów we wskazanym województwie
     *
     * @param string $provinceId ID województwa
     *
     * @return mixed
     */
    public function getDistricts(string $provinceId)
    {
        return $this->getResponse('PobierzListePowiatow', ['Woj' => $provinceId]);
    }

    /**
     * Lista gmin we wskazanym powiecie
     *
     * @param string $provinceId ID województwa
     * @param string $districtId ID powiatu
     *
     * @return mixed
     */
    public function getCommunes(string $provinceId, string $districtId)
    {
        return $this->getResponse('PobierzListeGmin', ['Woj' => $provinceId, 'Pow' => $districtId]);
    }

    public function __debugInfo()
    {
        return ['Top secret'];
    }
}
