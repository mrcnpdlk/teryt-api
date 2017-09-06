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

class ZweryfikowanyAdresBezUlic extends AbstractResponseModel
{
    /**
     * Nazwa miejscowości
     *
     * @var string
     */
    public $cityName;
    /**
     * Nazwa rodzaju miejscowości
     *
     * @var string
     */
    public $cityTypeName;
    /**
     * Poprzedni rodzaj miejscowości
     *
     * @var string
     */
    public $historicalCityType;
    /**
     * 7 znakowy identyfikator miejscowości
     *
     * @var string
     */
    public $cityId;


    public static function create(\stdClass $oData)
    {
        $o                     = new self();
        $o->historicalCityType = $oData->HistorycznyRodzajMiejscowosci;
        $o->communeName        = $oData->NazwaGmi;
        $o->cityName           = $oData->NazwaMiejscowosci;
        $o->districtName       = $oData->NazwaPow;
        $o->provinceName       = $oData->NazwaWoj;
        $o->communeTypeName    = $oData->RodzajGmi;
        $o->cityTypeName       = $oData->RodzajMiejscowosci;
        $o->communeId          = $oData->SymbolGmi;
        $o->cityId             = $oData->SymbolMiejscowosci;
        $o->districtId         = $oData->SymbolPow;
        $o->communeTypeId      = $oData->SymbolRodzajuGmi;
        $o->provinceId         = $oData->SymbolWoj;

        $o->expandData();

        return $o;
    }
}
