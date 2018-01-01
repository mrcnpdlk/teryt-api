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
 * Class WyszukanaUlica
 *
 * @package mrcnpdlk\Teryt\ResponseModel\Territory
 */
class WyszukanaUlica extends Ulica
{
    /**
     * Nazwa uliczy cz. 1
     *
     * @var string
     */
    public $streetName1;
    /**
     * Nazwa ulicy cz. 2
     *
     * @var string
     */
    public $streetName2;

    /**
     * WyszukanaUlica constructor.
     *
     * @param \stdClass|null $oData Obiekt zwrócony z TerytWS1
     *
     * @throws \mrcnpdlk\Teryt\Exception
     */
    public function __construct(\stdClass $oData = null)
    {
        if ($oData) {
            $this->streetIdentity = $oData->Cecha;
            $this->communeId      = $oData->Gmi;
            $this->communeName    = $oData->Gmina;
            $this->cityName       = $oData->Miejscowosc;
            $this->streetName     = $oData->Nazwa;
            $this->streetName1    = $oData->Nazwa1;
            $this->streetName2    = $oData->Nazwa2;
            $this->districtId     = $oData->Pow;
            $this->districtName   = $oData->Powiat;
            $this->communeTypeId  = $oData->RodzajGminy;
            $this->streetId       = $oData->Symbol;
            $this->cityId         = $oData->SymbolSimc;
            $this->provinceId     = $oData->Woj;
            $this->provinceName   = $oData->Wojewodztwo;

            try {
                $this->statusDate = $oData->DataStanu ? (new \DateTime($oData->DataStanu))->format('Y-m-d') : null;
            } catch (\Exception $e) {
                $this->statusDate = null;
            }
        }

        parent::__construct();
    }
}
