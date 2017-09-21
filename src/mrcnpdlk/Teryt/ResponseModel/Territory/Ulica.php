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
 * @author  Marcin Pudełek <marcin@pudelek.org.pl>
 *
 */

/**
 * Created by Marcin Pudełek <marcin@pudelek.org.pl>
 * Date: 06.09.2017
 */

namespace mrcnpdlk\Teryt\ResponseModel\Territory;

/**
 * Class Ulica
 *
 * @package mrcnpdlk\Teryt\ResponseModel\Territory
 */
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

    /**
     * Ulica constructor.
     *
     * @param \stdClass|null $oData Obiekt zwrócony z TerytWS1
     */
    public function __construct(\stdClass $oData = null)
    {
        if ($oData) {
            $this->streetIdentity = $oData->Cecha;
            $this->communeTypeId  = $oData->GmiRodzaj ?? $oData->RodzGmi ?? null;
            $this->communeId      = $oData->GmiSymbol ?? $oData->Gmi ?? null;
            $this->communeName    = $oData->Gmina ?? null;
            $this->cityId         = $oData->IdentyfikatorMiejscowosci;
            $this->streetId       = $oData->IdentyfikatorUlicy ?? $oData->SymbolUlicy ?? null;
            $this->streetName     = $oData->Nazwa ?? null;
            $this->cityName       = $oData->NazwaMiejscowosci ?? null;
            $this->districtId     = $oData->PowSymbol ?? $oData->Pow ?? null;
            $this->districtName   = $oData->Powiat ?? null;
            $this->provinceId     = $oData->WojSymbol ?? $oData->Woj ?? null;
            $this->provinceName   = $oData->Wojewodztwo ?? null;

            $dataStanu = $oData->DataStanu ?? $oData->StanNa ?? null;
            try {
                $this->statusDate = $dataStanu ? (new \DateTime($dataStanu))->format('Y-m-d') : null;
            } catch (\Exception $e) {
                $this->statusDate = null;
            }
        }

        parent::__construct();
    }
}
