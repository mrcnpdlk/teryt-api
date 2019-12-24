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
 * @author  Marcin Pudełek <marcin@pudelek.org.pl>
 */

namespace mrcnpdlk\Teryt;

use DOMDocument;
use DOMXPath;

/**
 * @see https://github.com/robrichards/wse-php/issues/31
 */
class WSASoap
{
    const WSANS  = 'http://www.w3.org/2005/08/addressing';
    const WSAPFX = 'wsa';
    /**
     * @var string|null
     */
    private $soapNS;
    /**
     * @var string|null
     */
    private $soapPFX;
    /**
     * @var \DOMDocument|null
     */
    private $soapDoc   = null;
    /**
     * @var \DOMElement|null
     */
    private $envelope  = null;
    /**
     * @var \DOMXPath|null
     */
    private $SOAPXPath = null;
    /**
     * @var \DOMElement|\DOMNode|null
     */
    private $header    = null;

    public function __construct(DOMDocument $doc)
    {
        $this->soapDoc   = $doc;
        $this->envelope  = $doc->documentElement;
        $this->soapNS    = $this->envelope->namespaceURI;
        $this->soapPFX   = $this->envelope->prefix;
        $this->SOAPXPath = new DOMXPath($doc);
        $this->SOAPXPath->registerNamespace('wssoap', $this->soapNS);
        $this->SOAPXPath->registerNamespace('wswsa', static::WSANS);
        $this->envelope->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:' . self::WSAPFX, static::WSANS);
        $this->locateHeader();
    }

    /**
     * @param string $action
     */
    public function addAction($action): void
    {
        /* Add the WSA Action */
        $header     = $this->locateHeader();
        $nodeAction = $this->soapDoc->createElementNS(static::WSANS, self::WSAPFX . ':Action', $action);
        $header->appendChild($nodeAction);
    }

    /**
     * @return \DOMDocument
     */
    public function getDoc(): DOMDocument
    {
        return $this->soapDoc;
    }

    /**
     * @return \DOMElement|\DOMNode
     */
    private function locateHeader()
    {
        if (null === $this->header) {
            $headers = $this->SOAPXPath->query('//wssoap:Envelope/wssoap:Header');
            $header  = $headers->item(0);
            if (!$header) {
                $header = $this->soapDoc->createElementNS($this->soapNS, $this->soapPFX . ':Header');
                $this->envelope->insertBefore($header, $this->envelope->firstChild);
            }
            $this->header = $header;
        }

        return $this->header;
    }
}
