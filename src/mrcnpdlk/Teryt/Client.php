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

declare (strict_types=1);

namespace mrcnpdlk\Teryt;

use mrcnpdlk\Teryt\Exception\Connection;
use mrcnpdlk\Teryt\Exception\NotFound;
use mrcnpdlk\Teryt\Exception\Response;
use mrcnpdlk\Teryt\Model\CityData;
use mrcnpdlk\Teryt\Model\CommuneData;
use mrcnpdlk\Teryt\Model\DistrictData;
use mrcnpdlk\Teryt\Model\ProvinceData;
use mrcnpdlk\Teryt\Model\RegionDivisionUnitData;
use mrcnpdlk\Teryt\Model\StreetData;
use mrcnpdlk\Teryt\Model\TerritorialDivisionUnitData;
use phpFastCache\Core\Pool\ExtendedCacheItemPoolInterface;

/**
 * Class Client
 *
 * @package mrcnpdlk\Teryt
 */
class Client
{
    const CATEGORY_ALL          = '0';
    const CATEGORY_PROVINCE_ALL = '1';
    const CATEGORY_DISTRICT_ALL = '2';
    const CATEGORY_COMMUNE_ALL  = '3';

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
     * Client constructor.
     */
    protected function __construct()
    {
        $this->tTerytConfig = $this->tDefTerytConfig;
    }

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
        $this->tTerytConfig['password'] = $tConfig['password'] ?? null;;

        if (!$this->tTerytConfig['username'] || !$this->tTerytConfig['password']) {
            throw new Connection(sprintf('Username and password for TERYT WS1 is required'));
        }
        $this->getSoap(true);

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
     * @param \phpFastCache\Core\Pool\ExtendedCacheItemPoolInterface|null $oCache
     *
     * @return $this
     * @see \phpFastCache\CacheManager::setDefaultConfig();
     * @see https://packagist.org/packages/phpfastcache/phpfastcache Documentation od Phpfastcache
     */
    public function setCacheInstace(ExtendedCacheItemPoolInterface $oCache = null)
    {
        $this->oCache = $oCache;

        return $this;
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
                    if (!property_exists($res, $resultKey)) {
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
     * @param mixed    $hashKey
     * @param int|null $ttl
     *
     * @return mixed
     */
    private function useCache(\Closure $closure, $hashKey, int $ttl = null)
    {
        if (is_array($hashKey) || is_object($hashKey)) {
            $hashKey = md5(json_encode($hashKey));
        } else {
            $hashKey = strval($hashKey);
        }

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
     * Return Province data
     *
     * @param string $provinceId
     *
     * @return ProvinceData
     */
    public function getProvince(string $provinceId)
    {
        $self = $this;

        return $this->useCache(
            function () use ($self, $provinceId) {
                foreach ($self->getProvinces() as $p) {
                    if ($p->provinceId === $provinceId) {
                        return $p;
                    }
                }
                throw new NotFound(sprintf('Province [id:%s] not found', $provinceId));
            },
            [__METHOD__, $provinceId]
        );
    }

    /**
     * Provinces list
     *
     * @return ProvinceData[]
     * @throws \mrcnpdlk\Teryt\Exception
     */
    public function getProvinces()
    {
        $answer = [];
        $res    = $this->getResponse('PobierzListeWojewodztw');
        foreach (Helper::getPropertyAsArray($res, 'JednostkaTerytorialna') as $p) {
            $answer[] = ProvinceData::create($p);
        };

        return $answer;

    }

    /**
     * Get District object
     *
     * @param string $provinceId
     * @param string $districtId
     *
     * @return DistrictData
     */
    public function getDistrict(string $provinceId, string $districtId)
    {
        $self = $this;

        return $this->useCache(
            function () use ($self, $provinceId, $districtId) {
                foreach ($self->getDistricts($provinceId) as $p) {
                    if ($p->districtId === $districtId) {
                        return $p;
                    }
                }
                throw new NotFound(sprintf('District [id:%s] not found', $provinceId));
            },
            [__METHOD__, $provinceId]
        );
    }

    /**
     * Return Districts list in Province
     *
     * @param string $provinceId Province ID
     *
     * @return DistrictData[]
     */
    public function getDistricts(string $provinceId)
    {
        $answer = [];
        $res    = $this->getResponse('PobierzListePowiatow', ['Woj' => $provinceId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaTerytorialna') as $p) {
            $answer[] = DistrictData::create($p);
        };

        return $answer;

    }

    /**
     * Searching Province by name
     *
     * @param string $phrase
     *
     * @return ProvinceData[]
     */
    public function searchProvince(string $phrase)
    {
        $answer = [];
        foreach ($this->getProvinces() as $p) {
            if (strpos(mb_strtolower($p->name), mb_strtolower($phrase)) !== false) {
                $answer[] = $p;
            }
        }

        return $answer;
    }

    /**
     * Searching District by name
     *
     * @param string      $phrase
     * @param string|null $provinceId ID of Province. Slower search if not set
     *
     * @return DistrictData[]
     */
    public function searchDistrict(string $phrase, string $provinceId = null)
    {
        $answer = [];
        if (is_null($provinceId)) {
            $tProvinceIds = Helper::getKeyValues($this->getProvinces(), 'provinceId', true);
        } else {
            $tProvinceIds = [$provinceId];
        }
        foreach ($tProvinceIds as $pId) {
            foreach ($this->getDistricts($pId) as $p) {
                if (strpos(mb_strtolower($p->name), mb_strtolower($phrase)) !== false) {
                    $answer[] = $p;
                }
            }
        }


        return $answer;
    }

    /**
     * Searching Commune by name
     *
     * @param string      $phrase
     * @param string|null $provinceId ID of Province. Slower search if not set
     * @param string|null $districtId ID of District in Province. Slower search if not set
     *
     * @return CommuneData[]
     */
    public function searchCommune(string $phrase, string $provinceId = null, string $districtId = null)
    {
        /**
         * @var $tProvinceIds string[]
         * @var $tDistrictIds string[]
         */
        $answer = [];
        if (is_null($provinceId)) {
            $tProvinceIds = Helper::getKeyValues($this->getProvinces(), 'provinceId', true);
        } else {
            $tProvinceIds = [$provinceId];
        }

        foreach ($tProvinceIds as $pId) {
            if (is_null($districtId)) {
                $tDistrictIds = Helper::getKeyValues($this->getDistricts($pId), 'districtId', true);
            } else {
                $tDistrictIds = [$districtId];
            }

            foreach ($tDistrictIds as $dId) {
                foreach ($this->getCommunes($pId, $dId) as $p) {
                    if (strpos(mb_strtolower($p->name), mb_strtolower($phrase)) !== false) {
                        $answer[] = $p;
                    }
                }
            }
        }


        return $answer;
    }

    /**
     * Return Communes in District in Province
     *
     * @param string $provinceId ID województwa
     * @param string $districtId ID powiatu
     *
     * @return CommuneData[]
     */
    public function getCommunes(string $provinceId, string $districtId)
    {
        $answer = [];
        $res    = $this->getResponse('PobierzListeGmin', ['Woj' => $provinceId, 'Pow' => $districtId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaTerytorialna') as $p) {
            $answer[] = CommuneData::create($p);
        };

        return $answer;

    }

    public function __debugInfo()
    {
        return ['Top secret'];
    }

    /**
     * Get list of Regions
     *
     * @return RegionDivisionUnitData[]
     */
    public function getRegions()
    {
        $answer = [];
        $res    = $this->getResponse('PobierzListeRegionow');
        foreach (Helper::getPropertyAsArray($res, 'JednostkaNomenklaturyNTS') as $p) {
            $answer[] = RegionDivisionUnitData::create($p);
        };

        return $answer;
    }

    /**
     * Get list of Regions
     *
     * @param string $provinceId
     *
     * @return \mrcnpdlk\Teryt\Model\RegionDivisionUnitData[]
     */
    public function getSubRegions(string $provinceId)
    {
        $answer = [];
        $res    = $this->getResponse('PobierzListePodregionow', ['Woj' => $provinceId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaNomenklaturyNTS') as $p) {
            $answer[] = RegionDivisionUnitData::create($p);
        };

        return $answer;
    }

    /**
     * @param int $tercId
     *
     * @return CommuneData
     * @throws NotFound
     */
    public function getCommuneByTercId(int $tercId)
    {
        $sTercId = str_pad(strval($tercId), 7, '0', \STR_PAD_LEFT);
        foreach ($this->getCommunes(substr($sTercId, 0, 2), substr($sTercId, 2, 2)) as $c) {
            if ($c->tercId === $tercId) {
                return $c;
            }
        }

        throw new NotFound(sprintf('Commune [tercId:%s] not found', $tercId));
    }

    /**
     * Getting cities in commune
     *
     * @param string $provinceId
     * @param string $districtId
     * @param string $communeId
     * @param string $communeTypeId
     *
     * @return CityData[]
     */
    public function getCities(string $provinceId, string $districtId, string $communeId, string $communeTypeId)
    {
        $answer = [];
        $res    = $this->getResponse('PobierzListeMiejscowosciWRodzajuGminy',
            [
                'symbolWoj'  => $provinceId,
                'symbolPow'  => $districtId,
                'symbolGmi'  => $communeId,
                'symbolRodz' => $communeTypeId,
            ]);
        foreach (Helper::getPropertyAsArray($res, 'Miejscowosc') as $p) {
            $answer[] = CityData::create($p);
        };

        return $answer;

    }

    /**
     * @param string $provinceId
     * @param string $districtId
     * @param string $communeId
     * @param string $communeTypeId
     * @param string $cityId
     * @param bool   $asAddressVer
     *
     * @return array
     *
     * @todo Poprawić działanie metody - zwraca 0 wyników
     */
    public function getStreets(
        string $provinceId,
        string $districtId,
        string $communeId,
        string $communeTypeId,
        string $cityId,
        bool $asAddressVer = true
    ) {
        $answer = [];
        $conf   = [
            'Woj'               => $provinceId,
            'Pow'               => $districtId,
            'Gmi'               => $communeId,
            'Rodz'              => $communeTypeId,
            'msc'               => $cityId,
            'czyWersjaUrzedowa' => !$asAddressVer,
            'czyWersjaAdresowa' => $asAddressVer,

        ];
        $res    = $this->getResponse('PobierzListeUlicDlaMiejscowosci', $conf);

        /*        foreach (Helper::getPropertyAsArray($res, 'Miejscowosc') as $p) {
                    $answer[] = CityData::create($p);
                };*/

        return $answer;
    }

    /**
     * @param string $cityId
     *
     * @return CityData
     * @throws \mrcnpdlk\Teryt\Exception\NotFound
     */
    public function getCity(string $cityId)
    {
        $res = $this->getResponse('WyszukajMiejscowosc',
            ['identyfikatorMiejscowosci' => $cityId]
        );

        if (property_exists($res, 'Miejscowosc')) {
            return CityData::create($res->Miejscowosc);
        } else {
            throw new NotFound(sprintf('City [id:%s] not found', $cityId));
        }
    }

    /**
     * Verifing strret in city
     *
     * @param string $cityId   City ID
     * @param string $streetId StreetId
     *
     * @return StreetData
     * @throws \mrcnpdlk\Teryt\Exception\NotFound
     */
    public function verifyAdressForCity(string $cityId, string $streetId)
    {
        $res = $this->getResponse('WeryfikujAdresDlaUlic',
            [
                'symbolMsc' => $cityId,
                'SymUl'     => $streetId,
            ]
        );
        if (property_exists($res, 'ZweryfikowanyAdres')) {
            return StreetData::create($res->ZweryfikowanyAdres);
        } else {
            throw new NotFound(sprintf('Street [id:%s] not found in city [id:%s]', $streetId, $cityId));
        }
    }

    /**
     * @param string $phrase
     * @param string $category
     * ```
     * 0 - Wyszukiwanie wśród wszystkich rodzajów jednostek
     * 1 - Dla województw
     * 2 - Dla wszystkich powiatów
     * 21 - Dla powiatów ziemskich (identyfikator powiatu 01-60)
     * 22 - Dla miast na prawach powiatu (identyfikator powiatu 61-99)
     * 3 - Dla gmin ogółem
     * 31 - Dla gmin miejskich (identyfikator rodzaju gminy 1)
     * 32 - Dla dzielnic i delegatur (identyfikator rodzaju 8 i 9)
     * 33 - Dla gmin wiejskich (identyfikator rodzaju 2)
     * 34 - Dla gmin miejsko-wiejskich (3)
     * 341 - Dla miast w gminach miejsko-wiejskich(4)
     * 342 - Dla obszarów miejskich w gminach miejsko-wiejskich(5)
     * 35 - Dla miast ogółem (identyfikator 1 i 4)
     * 36 - Dla terenów wiejskich (identyfikator 2 i 5)
     * ```
     *
     * @return TerritorialDivisionUnitData
     * @throws \mrcnpdlk\Teryt\Exception\NotFound
     * @todo Poprawić
     */
    private function searchDivisionUnit(string $phrase, string $category)
    {
        $res = $this->getResponse('WyszukajJednostkeWRejestrze',
            [
                'Nazwa'      => $phrase,
                'identyfiks' => null,
                'kategoria'  => $category,
            ]
        );
        if (property_exists($res, 'JednostkaPodzialuTerytorialnego')) {
            return TerritorialDivisionUnitData::create($res->JednostkaPodzialuTerytorialnego);
        } else {
            throw new NotFound(sprintf('Street [id:%s] not found in city [id:%s]', $streetId, $cityId));
        }
    }
}
