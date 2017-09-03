<?php
declare (strict_types=1);

namespace mrcnpdlk\Teryt;

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
    public function getVoivodships()
    {
        return $this->getResponse('PobierzListeWojewodztw');
    }

    /**
     * Lista powiatów we wskazanym województwie
     *
     * @param string $voivodshipId
     *
     * @return mixed
     */
    public function getPoviats(string $voivodshipId)
    {
        return $this->getResponse('PobierzListePowiatow');
    }

    /**
     * Lista gmin we wskazanym powiecie
     *
     * @param string $voivodshipId
     * @param string $poviatId
     *
     * @return mixed
     */
    public function getCommunes(string $voivodshipId, string $poviatId)
    {
        return $this->getResponse('PobierzListeGmin');
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
            $res       = $this->soapClient->__soapCall($method, $args);
            $resultKey = $method . 'Result';

            return $res->{$resultKey};
        } catch (\SoapFault $e) {
            throw new Exception($e->faultcode);
        }
    }

}
