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

class WyszukanaUlica extends Ulica
{
    /**
     * @var string
     */
    public $streetName_1;
    /**
     * @var string
     */
    public $streetName_2;

    public function __construct(\stdClass $oData = null)
    {
        if ($oData) {
            $this->streetIdentity = $oData->Cecha;
            $this->communeId      = $oData->Gmi;
            $this->communeName    = $oData->Gmina;
            $this->cityName       = $oData->Miejscowosc;
            $this->streetName     = $oData->Nazwa;
            $this->streetName_1   = $oData->Nazwa1;
            $this->streetName_2   = $oData->Nazwa2;
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

    public static function create(\stdClass $oData)
    {
        return new static($oData);
    }
}
