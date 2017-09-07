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

/**
 * Class ZweryfikowanyAdresBezUlic
 *
 * @package mrcnpdlk\Teryt\ResponseModel\Territory
 */
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

    /**
     * ZweryfikowanyAdresBezUlic constructor.
     *
     * @param \stdClass $oData Obiekt zwrócony z TerytWS1
     */
    public function __construct(\stdClass $oData)
    {
        $this->historicalCityType = $oData->HistorycznyRodzajMiejscowosci;
        $this->communeName        = $oData->NazwaGmi;
        $this->cityName           = $oData->NazwaMiejscowosci;
        $this->districtName       = $oData->NazwaPow;
        $this->provinceName       = $oData->NazwaWoj;
        $this->communeTypeName    = $oData->RodzajGmi;
        $this->cityTypeName       = $oData->RodzajMiejscowosci;
        $this->communeId          = $oData->SymbolGmi;
        $this->cityId             = $oData->SymbolMiejscowosci;
        $this->districtId         = $oData->SymbolPow;
        $this->communeTypeId      = $oData->SymbolRodzajuGmi;
        $this->provinceId         = $oData->SymbolWoj;

        parent::__construct();
    }
}
