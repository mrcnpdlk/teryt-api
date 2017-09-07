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
 * Created by Marcin.
 * Date: 06.09.2017
 * Time: 20:51
 */

namespace mrcnpdlk\Teryt\Model;


use mrcnpdlk\Teryt\Exception;

/**
 * Class Terc
 *
 * @package mrcnpdlk\Teryt\Model
 */
class Terc
{
    /**
     * Dwuznakowy id wojewodztwa
     *
     * @var string
     */
    public $provinceId;
    /**
     * Dwuznakowy id powiatu
     *
     * @var string
     */
    public $districtId;
    /**
     * dwuznakowy id gminy
     *
     * @var string
     */
    public $communeId;
    /**
     * Jednoznakowy id rodzaju gminy
     *
     * @var string
     */
    public $communeTypeId;
    /**
     * Identyfikator gminy wraz z RODZ
     *
     * @var int
     */
    public $tercId;

    /**
     * Ustawienie tercId
     * Pozostałe pola są generowanie w locie
     *
     * @param int|null $tercId
     *
     * @return static
     * @throws Exception
     */
    public static function setTercId(int $tercId = null)
    {
        $o = new static();
        if ($tercId) {
            $o->tercId = $tercId;
            $sTercId   = str_pad(strval($tercId), 7, '0', \STR_PAD_LEFT);
            if (strlen($sTercId) > 7) {
                throw new Exception(sprintf('TercId [%s] malformed', $sTercId));
            }
            $o->provinceId    = substr($sTercId, 0, 2);
            $o->districtId    = substr($sTercId, 2, 2);
            $o->communeId     = substr($sTercId, 4, 2);
            $o->communeTypeId = substr($sTercId, 6, 1);
        }

        return $o;
    }

    /**
     * Ustawienie idk-ów
     * tercId jest generowany w locie
     *
     * @param string $provinceId
     * @param string $districtId
     * @param string $communeId
     * @param string $communeTypeId
     *
     * @return static
     */
    public static function setIds(string $provinceId, string $districtId, string $communeId, string $communeTypeId)
    {
        $o                = new static();
        $o->provinceId    = $provinceId;
        $o->districtId    = $districtId;
        $o->communeId     = $communeId;
        $o->communeTypeId = $communeTypeId;
        $o->tercId        = intval(sprintf('%s%s%s%s', $provinceId, $districtId, $communeId, $communeTypeId));

        return $o;
    }
}
