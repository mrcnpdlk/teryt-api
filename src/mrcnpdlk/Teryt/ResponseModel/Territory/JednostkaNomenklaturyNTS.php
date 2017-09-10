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
 * Class JednostkaNomenklaturyNTS
 *
 * @package mrcnpdlk\Teryt\ResponseModel\Territory
 */
class JednostkaNomenklaturyNTS extends AbstractResponseModel
{
    /**
     * Jednoznakowy symbol poziomu
     *
     * @var string
     */
    public $level;
    /**
     * Jednoznakowy symbol regionu
     *
     * @var string
     */
    public $regionId;
    /**
     * Dwuznakowy symbol podregionu
     *
     * @var string
     */
    public $subregionId;
    /**
     * Nazwa jednostki
     *
     * @var string
     */
    public $name;
    /**
     * Typ jednostki podziału terytorialnego
     *
     * @var string
     */
    public $typeName;

    public function __construct(\stdClass $oData = null)
    {
        parent::__construct($oData);
        $this->communeId     = $oData->GMI;
        $this->name          = $oData->NAZWA;
        $this->typeName      = $oData->NAZWA_DOD;
        $this->subregionId   = $oData->PODREG;
        $this->districtId    = $oData->POW;
        $this->level         = $oData->POZIOM;
        $this->regionId      = $oData->REGION;
        $this->communeTypeId = $oData->RODZ;
        $this->provinceId    = $oData->WOJ;

        try {
            $this->statusDate = $oData->STAN_NA ? (new \DateTime($oData->STAN_NA))->format('Y-m-d') : null;
        } catch (\Exception $e) {
            $this->statusDate = null;
        }

        $this->expandData();
    }
}
