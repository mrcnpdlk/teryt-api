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
    private $provinceId;
    /**
     * Dwuznakowy id powiatu
     *
     * @var string
     */
    private $districtId;
    /**
     * dwuznakowy id gminy
     *
     * @var string
     */
    private $communeId;
    /**
     * Jednoznakowy id rodzaju gminy
     *
     * @var string
     */
    private $communeTypeId;
    /**
     * Identyfikator gminy wraz z RODZ
     *
     * @var int
     */
    private $tercId;

    /**
     * Terc constructor.
     *
     * @param int|null $tercId
     */
    public function __construct(int $tercId = null)
    {
        $this->setTercId($tercId);
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
    public function setIds(string $provinceId, string $districtId, string $communeId, string $communeTypeId)
    {
        $this->provinceId = str_pad($provinceId, 2, '0', \STR_PAD_LEFT);
        $this->districtId = str_pad($districtId, 2, '0', \STR_PAD_LEFT);;
        $this->communeId = str_pad($communeId, 2, '0', \STR_PAD_LEFT);;
        $this->communeTypeId = $communeTypeId;
        $this->tercId        = intval(sprintf('%s%s%s%s',
            $this->provinceId,
            $this->districtId,
            $this->communeId,
            $this->communeTypeId));

        return $this;
    }

    /**
     * @return string
     */
    public function getProvinceId()
    {
        return $this->provinceId;
    }

    /**
     * @return string
     */
    public function getDistrictId()
    {
        return $this->districtId;
    }

    /**
     * @return string
     */
    public function getCommuneId()
    {
        return $this->communeId;
    }

    /**
     * @return string
     */
    public function getCommuneTypeId()
    {
        return $this->communeTypeId;
    }

    /**
     * @return int
     */
    public function getTercId()
    {
        return $this->tercId;
    }

    /**
     * Ustawienie tercId
     * Pozostałe pola są generowanie w locie
     *
     * @param int|null $tercId
     *
     * @return static
     * @throws Exception
     */
    public function setTercId(int $tercId = null)
    {
        if ($tercId) {
            $this->tercId = $tercId;
            $sTercId      = str_pad(strval($tercId), 7, '0', \STR_PAD_LEFT);
            if (strlen($sTercId) > 7) {
                throw new Exception(sprintf('TercId [%s] malformed', $sTercId));
            }
            $this->provinceId    = substr($sTercId, 0, 2);
            $this->districtId    = substr($sTercId, 2, 2);
            $this->communeId     = substr($sTercId, 4, 2);
            $this->communeTypeId = substr($sTercId, 6, 1);
        }

        return $this;
    }
}
