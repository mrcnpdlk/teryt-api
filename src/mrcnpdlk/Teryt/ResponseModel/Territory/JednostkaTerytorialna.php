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


class JednostkaTerytorialna extends AbstractResponseModel
{
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

    public static function create(\stdClass $oData)
    {
        $o = new static();

        $o->provinceId    = $oData->WOJ;
        $o->districtId    = $oData->POW;
        $o->communeId     = $oData->GMI;
        $o->communeTypeId = $oData->RODZ;
        $o->name          = $oData->NAZWA;
        $o->typeName      = $oData->NAZWA_DOD;
        $o->statusDate    = $oData->STAN_NA;

        try {
            $date = $oData->STAN_NA ? (new \DateTime($oData->STAN_NA))->format('Y-m-d') : null;
        } catch (\Exception $e) {
            $date = null;
        }

        $o->statusDate = $date;

        if ($o->provinceId && $o->districtId && $o->communeId && $o->communeTypeId) {
            $o->tercId = intval(sprintf("%s%s%s%s",
                $o->provinceId,
                $o->districtId,
                $o->communeId,
                $o->communeTypeId));
        }

        return $o;
    }
}
