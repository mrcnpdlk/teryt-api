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

    /**
     * @param string $username
     * @param string $password
     * @param bool   $digest
     */
    public function addUserToken(string $username, string $password, bool $digest = false)
    {
        $this->username = $username;
        $this->password = $password;
        $this->digest   = $digest;
    }

    /**
     * @param string $request
     * @param string $location
     * @param string $action
     * @param int    $version
     * @param int    $oneWay
     *
     * @return string
     */
    public function __doRequest($request, $location, $action, $version, $oneWay = 0)
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

        return parent::__doRequest($request, $location, $action, $version, $oneWay);
    }
}
