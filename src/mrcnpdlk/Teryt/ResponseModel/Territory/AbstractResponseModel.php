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


use mrcnpdlk\Teryt\Model\Terc;

/**
 * Class AbstractResponseModel
 *
 * @package mrcnpdlk\Teryt\ResponseModel\Territory
 */
abstract class AbstractResponseModel
{
    /**
     * Dwuznakowy symbol województwa
     *
     * @var string
     */
    public $provinceId;
    /**
     * Dwuznakowy symbol powiatu
     *
     * @var string|null
     */
    public $districtId;
    /**
     * Dwuznakowy symbol gminy
     *
     * @var string|null
     */
    public $communeId;
    /**
     * Jednoznakowy symbol gminy
     *
     * @var string|null
     */
    public $communeTypeId;
    /**
     * Nazwa województwa
     *
     * @var string
     */
    public $provinceName;
    /**
     * Nazwa powiatu
     *
     * @var string
     */
    public $districtName;
    /**
     * Nazwa gminy
     *
     * @var string
     */
    public $communeName;
    /**
     * Nazwa rodzaju gminy
     *
     * @var string|null
     */
    public $communeTypeName;
    /**
     * Określa datę katalogu dla wskazanego stanu w formacie YYYY-MM-DD
     *
     * @var string
     */
    public $statusDate;
    /**
     * Identyfikator gminy wraz z RODZ
     *
     * @var integer
     */
    public $tercId;

    /**
     * AbstractResponseModel constructor.
     *
     * @param \stdClass|null $oData
     */
    public function __construct(\stdClass $oData = null)
    {
        $this->expandData();
    }

    /**
     * Dociągnięcie informacji na pozostałe pola obiektu
     *
     * @return $this
     */
    public function expandData()
    {
        if ($this->tercId) {
            $oTerc               = new Terc($this->tercId);
            $this->provinceId    = $oTerc->getProvinceId();
            $this->districtId    = $oTerc->getDistrictId();
            $this->communeId     = $oTerc->getCommuneId();
            $this->communeTypeId = $oTerc->getCommuneTypeId();
        } else {
            if ($this->provinceId && $this->districtId && $this->communeId && $this->communeTypeId) {
                $oTerc        = (new Terc())->setIds($this->provinceId, $this->districtId, $this->communeId, $this->communeTypeId);
                $this->tercId = $oTerc->getTercId();
            }
        }

        $this->clearData();

        return $this;
    }

    /**
     * Clearing empty value
     *
     * @return $this
     */
    public function clearData()
    {
        //clearing data
        foreach (get_object_vars($this) as $prop => $value) {
            if ($value === '') {
                $this->{$prop} = null;
            }
        }

        return $this;
    }
}
