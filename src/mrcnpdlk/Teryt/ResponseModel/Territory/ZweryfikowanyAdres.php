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

/**
 * Created by Marcin Pudełek <marcin@pudelek.org.pl>
 * Date: 06.09.2017
 */

namespace mrcnpdlk\Teryt\ResponseModel\Territory;

class ZweryfikowanyAdres extends ZweryfikowanyAdresBezUlic
{
    /**
     * Nazwa ulicy w pełnym brzemieniu
     *
     * @var string
     */
    public $streetName;
    /**
     * Część nazwy ulicy począwszy od słowa, które
     * decyduje o pozycji ulicy w układzie alfabetycznym, aż do końca nazwy
     *
     * @var string
     */
    public $streetName_1;
    /**
     * Pozostała część nazwy ulicy
     *
     * @var string
     */
    public $streetName_2;
    /**
     * Identyfikator ulicy
     *
     * @var string
     */
    public $streetId;
    /**
     * Nazwa cechy ulicy
     *
     * @var static
     */
    public $streetIdentityName;

    public function __construct(\stdClass $oData)
    {
        parent::__construct($oData);
        $this->streetId           = $oData->SymUl;
        $this->streetName_1       = $oData->Nazwa_1;
        $this->streetName_2       = $oData->Nazwa_2;
        $this->streetName         = $oData->NazwaUlicyWPelnymBrzmieniu;
        $this->streetIdentityName = $oData->NazwaCechy;
    }

    public static function create(\stdClass $oData)
    {
        return new static($oData);

    }

}
