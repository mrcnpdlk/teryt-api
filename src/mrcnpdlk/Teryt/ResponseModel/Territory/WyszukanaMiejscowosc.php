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

class WyszukanaMiejscowosc extends Miejscowosc
{
    /**
     * Identyfikator miejscowości podstawowej
     *
     * @var string
     */
    public $cityParentId;
    /**
     * Symbol rodzaju miejscowości
     *
     * @var string
     */
    public $rmId;
    /**
     * Nazwa rodzaju miejscowości
     *
     * @var string
     */
    public $rmName;

    public function __construct(\stdClass $oData)
    {
        $this->communeId     = $oData->Gmi;
        $this->communeName   = $oData->Gmina;
        $this->cityName      = $oData->Nazwa;
        $this->districtId    = $oData->Pow;
        $this->districtName  = $oData->Powiat;
        $this->rmId          = $oData->Rm;
        $this->rmName        = $oData->RodzajMiejscowosci;
        $this->communeTypeId = $oData->RodzajGminy;
        $this->cityId        = $oData->Symbol;
        $this->cityParentId  = $oData->SymbolPodst;
        $this->provinceId    = $oData->Woj;
        $this->provinceName  = $oData->Wojewodztwo;

        try {
            $this->statusDate = $oData->DataStanu ? (new \DateTime($oData->DataStanu))->format('Y-m-d') : null;
        } catch (\Exception $e) {
            $this->statusDate = null;
        }

        parent::__construct();
    }

    public static function create(\stdClass $oData)
    {
        return new static($oData);
    }

}
