<?php
/**
 * Created by PhpStorm.
 * User: Marcin
 * Date: 03.09.2017
 * Time: 22:00
 */

namespace mrcnpdlk\Teryt;


/**
 * https://github.com/robrichards/wse-php/issues/31
 */
class WSASoap
{
    const WSANS = 'http://www.w3.org/2005/08/addressing';
    const WSAPFX = 'wsa';
    private $soapNS, $soapPFX;
    private $soapDoc = null;
    private $envelope = null;
    private $SOAPXPath = null;
    private $header = null;
    public function __construct(\DOMDocument $doc)
    {
        $this->soapDoc = $doc;
        $this->envelope = $doc->documentElement;
        $this->soapNS = $this->envelope->namespaceURI;
        $this->soapPFX = $this->envelope->prefix;
        $this->SOAPXPath = new \DOMXPath($doc);
        $this->SOAPXPath->registerNamespace('wssoap', $this->soapNS);
        $this->SOAPXPath->registerNamespace('wswsa', static::WSANS);
        $this->envelope->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:'.self::WSAPFX, static::WSANS);
        $this->locateHeader();
    }
    public function addAction($action)
    {
        /* Add the WSA Action */
        $header = $this->locateHeader();
        $nodeAction = $this->soapDoc->createElementNS(static::WSANS, self::WSAPFX.':Action', $action);
        $header->appendChild($nodeAction);
    }
    public function getDoc()
    {
        return $this->soapDoc;
    }
    private function locateHeader()
    {
        if ($this->header == null) {
            $headers = $this->SOAPXPath->query('//wssoap:Envelope/wssoap:Header');
            $header = $headers->item(0);
            if (!$header) {
                $header = $this->soapDoc->createElementNS($this->soapNS, $this->soapPFX.':Header');
                $this->envelope->insertBefore($header, $this->envelope->firstChild);
            }
            $this->header = $header;
        }
        return $this->header;
    }
}
