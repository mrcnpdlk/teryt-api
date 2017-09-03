<?php

namespace mrcnpdlk\Teryt;


use RobRichards\WsePhp\WSSESoap;

class TerytSoapClient extends \SoapClient
{
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    /**
     * @var bool
     */
    private $digest;

    public function addUserToken(string $username, string $password, bool $digest = false)
    {
        $this->username = $username;
        $this->password = $password;
        $this->digest   = $digest;
    }

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $doc = new \DOMDocument('1.0');
        $doc->loadXML($request);
        $wsa = new WSASoap($doc);
        $wsa->addAction($action);
        $doc                  = $wsa->getDoc();
        $wsse                 = new WSSESoap($doc, false);
        $wsse->signAllHeaders = false;
        $wsse->addUserToken($this->username, $this->password, $this->digest);
        $request = $wsse->saveXML();

        return parent::__doRequest($request, $location, $action, $version, $one_way);
    }
}
