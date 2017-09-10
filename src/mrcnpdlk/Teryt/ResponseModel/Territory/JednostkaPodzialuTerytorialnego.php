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
 *
 * @author Marcin Pudełek <marcin@pudelek.org.pl>
 *
 */

/**
 * Created by Marcin Pudełek <marcin@pudelek.org.pl>
 * Date: 06.09.2017
 */

namespace mrcnpdlk\Teryt\ResponseModel\Territory;

/**
 * Class JednostkaPodzialuTerytorialnego
 *
 * @package mrcnpdlk\Teryt\ResponseModel\Territory
 */
class JednostkaPodzialuTerytorialnego extends AbstractResponseModel
{
    /**
     * JednostkaPodzialuTerytorialnego constructor.
     *
     * @param \stdClass $oData Obiekt zwrócony z TerytWS1
     */
    public function __construct(\stdClass $oData)
    {
        $this->communeName     = $oData->GmiNazwa;
        $this->communeTypeName = $oData->GmiNazwaDodatkowa;
        $this->communeTypeId   = $oData->GmiRodzaj;
        $this->communeId       = $oData->GmiSymbol;
        $this->districtId      = $oData->PowSymbol;
        $this->districtName    = $oData->Powiat;
        $this->provinceId      = $oData->WojSymbol;
        $this->provinceName    = $oData->Wojewodztwo;

        parent::__construct();
    }
}
