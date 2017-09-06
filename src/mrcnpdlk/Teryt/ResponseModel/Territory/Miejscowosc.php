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

class Miejscowosc extends AbstractResponseModel
{
    /**
     * Nazwa miejscowości
     *
     * @var string
     */
    public $name;
    /**
     * 7 znakowy identyfikator miejscowości
     *
     * @var string
     */
    public $cityId;

    /**
     * @param \stdClass $oData
     *
     * @return static
     * @todo Błąd w dokumentacji, zwracana niezgodna ilosc znaków dla PowSymbol i GmiSymbol. Narazie połatałem
     */
    public static function create(\stdClass $oData)
    {
        $o                = new static();
        $o->name          = $oData->Nazwa;
        $o->cityId        = $oData->Symbol;
        $o->provinceId    = $oData->WojSymbol;
        $o->provinceName  = $oData->Wojewodztwo;
        $o->districtName  = $oData->Powiat;
        $o->districtId    = strlen($oData->PowSymbol) === 4 ? substr($oData->PowSymbol, 2, 2) : $oData->PowSymbol;
        $o->communeName   = $oData->Gmina;
        $o->communeId     = strlen($oData->GmiSymbol) === 7 ? substr($oData->GmiSymbol, 4, 2) : $oData->GmiSymbol;
        $o->communeTypeId = $oData->GmiRodzaj;

        $o->expandData();

        return $o;
    }

}
