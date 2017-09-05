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
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    /**
     * @var \SoapClient
     */
    private $soapClient;
    /**
     * @var \phpFastCache\Core\Pool\ExtendedCacheItemPoolInterface
     */
    private $oCache;

    /**
     * Client constructor.
     *
     * @param string $url
     * @param string $username
     * @param string $password
     */
    public function __construct(string $url, string $username, string $password, ExtendedCacheItemPoolInterface $oCache = null)
    {
        $this->url      = $url;
        $this->username = $username;
        $this->password = $password;
        $this->oCache   = $oCache;
        $this->initClient();
    }

    /**
     * @return $this
     */
    private function initClient()
    {
        try {
            $this->soapClient = new TerytSoapClient($this->url, [
                'soap_version' => SOAP_1_1,
                'exceptions'   => true,
                'cache_wsdl'   => WSDL_CACHE_BOTH,
            ]);
            $this->soapClient->addUserToken($this->username, $this->password);
        } catch (\Exception $e) {
            static::handleException($e);
        }

        return $this;
    }

    /**
     * @param \Exception $e
     *
     * @return \mrcnpdlk\Teryt\Exception|\mrcnpdlk\Teryt\Exception\Connection|\Exception
     */
    private static function handleException(\Exception $e)
    {
        if ($e instanceof \SoapFault) {
            switch ($e->faultcode ?? null) {
                case 'a:InvalidSecurityToken':
                    return new Connection(sprintf('Invalid Security Token'), 1, $e);
                case 'WSDL':
                    return new Connection(sprintf('%s', $e->faultstring ?? 'Unknown', 1, $e));
                default:
                    return $e;
            }
        } else {
            if ($e instanceof Exception) {
                return $e;
            } else {
                return new Exception('Unknown Exception', 1, $e);
            }
        }
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
    private function getResponse(string $method, array $args = [])
    {
        try {
            if (!array_key_exists('DataStanu', $args)) {
                $args['DataStanu'] = (new \DateTime())->format('Y-m-d');
            }
            $hashKey = md5(json_encode([__METHOD__, $method, $args]));

            if ($this->oCache && $this->oCache->hasItem($hashKey)) {
                var_dump('FOM CACHE');

                return $this->oCache->getItem($hashKey)->get();
            } else {
                $res       = $this->soapClient->__soapCall($method, [$args]);
                $resultKey = $method . 'Result';

                if (!isset($res->{$resultKey})) {
                    throw new Response(sprintf('%s doesnt exist in response', $resultKey));
                }
                $answer = $res->{$resultKey};

                if ($this->oCache) {
                    $CachedString = $this->oCache
                        ->getItem($hashKey)
                        ->set($answer)
                        ->expiresAfter(5)
                    ;
                    $this->oCache->save($CachedString); // Save the cache item just like you do with doctrine and entities
                    var_dump('ADD CACHE');
                }

                return $answer;
            }
        } catch (\Exception $e) {
            throw static::handleException($e);
        }


    }

    /**
     * Lista województw
     *
     * @return mixed
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

}
