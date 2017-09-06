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
 * Time: 13:46
 */

namespace mrcnpdlk\Teryt\Model;


class StreetData
{
    /**
     * Identyfikator ulicy
     *
     * @var string
     */
    public $streetId;
    /**
     * Cecha
     *
     * @var string
     */
    public $identity;
    /**
     * Część nazwy ulicy począwszy od słowa, które decyduje o pozycji ulicy
     * w układzie alfabetycznym, aż do końca nazwy
     *
     * @var string
     */
    public $name_1;
    /**
     * Pozostała część nazwy ulicy
     *
     * @var string
     */
    public $name_2;
    /**
     * Pełna nazwa ulicy, złożona z cechy, nazwy_1 i nazwy_2
     *
     * @var string
     */
    public $name;

    /**
     * @param \stdClass $oData
     *
     * @return \mrcnpdlk\Teryt\Model\StreetData
     */
    public static function create(\stdClass $oData)
    {
        $resData           = new static();
        $resData->streetId = $oData->SymUl ?: null;
        $resData->identity = $oData->NazwaCechy ?: null;
        $resData->name_1   = $oData->Nazwa_1 ?: null;
        $resData->name_2   = $oData->Nazwa_2 ?: null;
        $resData->name     = $oData->NazwaUlicyWPelnymBrzmieniu ?: null;


        return $resData;
    }
}