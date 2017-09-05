<?php

declare (strict_types=1);

namespace mrcnpdlk\Teryt;

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
     * Client constructor.
     *
     * @param string $url
     * @param string $username
     * @param string $password
     */
    public function __construct(string $url, string $username, string $password)
    {
        $this->url      = $url;
        $this->username = $username;
        $this->password = $password;
        $this->initClient();
    }

    /**
     * @return $this
     */
    private function initClient()
    {
        $this->soapClient = new TerytSoapClient($this->url, [
            'soap_version' => SOAP_1_1,
            'exceptions'   => true,
            'cache_wsdl'   => WSDL_CACHE_BOTH,
        ]);
        $this->soapClient->addUserToken($this->username, $this->password);

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
     * Lista województw
     *
     * @return mixed
     */
    public function getProvinces()
    {
        $res = $this->getResponse('PobierzListeWojewodztw');
        if (isset($res->JednostkaTerytorialna)) {

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

    /**
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     * @throws Exception
     */
    private function getResponse(string $method, array $args = [])
    {
        try {
            if (!array_key_exists('DataStanu', $args)) {
                $args['DataStanu'] = (new \DateTime())->format('Y-m-d');
            }
            $res       = $this->soapClient->__soapCall($method, [$args]);
            $resultKey = $method . 'Result';

            return $res->{$resultKey};
        } catch (\SoapFault $e) {
            throw new Exception($e->faultcode);
        }
    }

}
