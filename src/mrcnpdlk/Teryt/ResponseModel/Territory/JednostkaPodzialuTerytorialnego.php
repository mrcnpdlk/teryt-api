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


class JednostkaPodzialuTerytorialnego extends AbstractResponseModel
{
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

    public static function create(\stdClass $oData)
    {
        return new static($oData);

    }
}
