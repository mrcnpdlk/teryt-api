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

class Ulica extends AbstractResponseModel
{
    /**
     * Zawiera cechę ulicy
     *
     * @var string
     */
    public $streetIdentity;
    /**
     * Nazwa ulicy
     *
     * @var string
     */
    public $streetName;
    /**
     * Identyfikator ulicy
     *
     * @var string
     */
    public $streetId;
    /**
     * 7 znakowy identyfikator miejscowości
     *
     * @var string
     */
    public $cityId;
    /**
     * Nazwa ulicy
     *
     * @var string
     */
    public $cityName;

    public function __construct(\stdClass $oData)
    {
        $this->streetIdentity = $oData->Cecha;
        $this->communeTypeId  = $oData->GmiRodzaj;
        $this->communeId      = $oData->GmiSymbol;
        $this->communeName    = $oData->Gmina;
        $this->cityId         = $oData->IdentyfikatorMiejscowosci;
        $this->streetId       = $oData->IdentyfikatorUlicy;
        $this->streetName     = $oData->Nazwa;
        $this->cityName      = $oData->NazwaMiejscowosci;
        $this->districtId     = $oData->PowSymbol;
        $this->districtName   = $oData->Powiat;
        $this->provinceId     = $oData->WojSymbol;
        $this->provinceName   = $oData->Wojewodztwo;

        parent::__construct();
    }

    public static function create(\stdClass $oData)
    {
        return new static($oData);
    }
}
