<?php
/**
 * Copyright (c) 2017.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Created by PhpStorm.
 * User: Marcin
 * Date: 05.09.2017
 * Time: 20:53
 */

namespace mrcnpdlk\Teryt\Model;


class RegionDivisionUnitData
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
     * Dwuznakowy symbol województwa
     *
     * @var string
     */
    public $provinceId;
    /**
     * Dwuznakowy symbol podregionu
     *
     * @var string
     */
    public $subRegionId;
    /**
     * Dwuznakowy symbol powiatu
     *
     * @var string
     */
    public $districtId;
    /**
     * Dwuznakowy symbol gminy
     *
     * @var string
     */
    public $communeId;
    /**
     * Jednoznakowy symbol typu gminy
     *
     * @var string
     */
    public $communeTypeId;
    /**
     * Nazwa
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
    /**
     * Określa datę katalogu dla wskazanego stanu
     *
     * @var string
     */
    public $statusDate;
    /**
     * @var integer
     */
    public $tercId;


    public static function create(\stdClass $oData)
    {
        $resData                = new static();
        $resData->provinceId    = $oData->WOJ ?: null;
        $resData->districtId    = $oData->POW ?: null;
        $resData->communeId     = $oData->GMI ?: null;
        $resData->communeTypeId = $oData->RODZ ?: null;
        $resData->name          = $oData->NAZWA ?: null;
        $resData->typeName      = $oData->NAZWA_DOD ?: null;
        $resData->regionId      = $oData->REGION ?: null;
        $resData->subRegionId   = $oData->PODREG ?: null;
        $resData->level         = $oData->POZIOM ?: null;

        try {
            $date = $oData->STAN_NA ? (new \DateTime($oData->STAN_NA))->format('Y-m-d') : null;
        } catch (\Exception $e) {
            $date = null;
        }

        $resData->statusDate = $date;
        if ($resData->provinceId && $resData->districtId && $resData->communeId && $resData->communeTypeId) {
            $resData->tercId = intval(sprintf("%s%s%s%s",
                $resData->provinceId,
                $resData->districtId,
                $resData->communeId,
                $resData->communeTypeId));
        }

        return $resData;
    }
}
