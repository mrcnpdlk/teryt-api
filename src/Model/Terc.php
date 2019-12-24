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
 * @author  Marcin Pudełek <marcin@pudelek.org.pl>
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
 */
class Terc
{
    /**
     * Dwuznakowy id wojewodztwa
     *
     * @var string|null
     */
    private $provinceId;
    /**
     * Dwuznakowy id powiatu
     *
     * @var string|null
     */
    private $districtId;
    /**
     * dwuznakowy id gminy
     *
     * @var string|null
     */
    private $communeId;
    /**
     * Jednoznakowy id rodzaju gminy
     *
     * @var string|null
     */
    private $communeTypeId;
    /**
     * Identyfikator gminy wraz z RODZ
     *
     * @var int|null
     */
    private $tercId;

    /**
     * Terc constructor.
     *
     * @param int|null $tercId
     *
     * @throws \mrcnpdlk\Teryt\Exception
     */
    public function __construct(int $tercId = null)
    {
        $this->setTercId($tercId);
    }

    /**
     * @return string|null
     */
    public function getCommuneId()
    {
        return $this->communeId;
    }

    /**
     * @return string|null
     */
    public function getCommuneTypeId()
    {
        return $this->communeTypeId;
    }

    /**
     * @return string|null
     */
    public function getDistrictId()
    {
        return $this->districtId;
    }

    /**
     * @return string|null
     */
    public function getProvinceId()
    {
        return $this->provinceId;
    }

    /**
     * @return int|null
     */
    public function getTercId()
    {
        return $this->tercId;
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
        $this->provinceId    = str_pad($provinceId, 2, '0', \STR_PAD_LEFT);
        $this->districtId    = str_pad($districtId, 2, '0', \STR_PAD_LEFT);
        $this->communeId     = str_pad($communeId, 2, '0', \STR_PAD_LEFT);
        $this->communeTypeId = $communeTypeId;
        $this->tercId        = (int)sprintf('%s%s%s%s',
            $this->provinceId,
            $this->districtId,
            $this->communeId,
            $this->communeTypeId);

        return $this;
    }

    /**
     * Ustawienie tercId
     * Pozostałe pola są generowanie w locie
     *
     * @param int|null $tercId
     *
     * @throws Exception
     *
     * @return static
     */
    public function setTercId(int $tercId = null)
    {
        if ($tercId) {
            $this->tercId = $tercId;
            $sTercId      = str_pad((string)$tercId, 7, '0', \STR_PAD_LEFT);
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
