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


use mrcnpdlk\Teryt\Model\Terc;

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
     * @var integer
     */
    public $tercId;

    public function __construct()
    {
        $this->expandData();
    }

    public function expandData()
    {
        if ($this->tercId) {
            $oTerc               = Terc::setTercId($this->tercId);
            $this->provinceId    = $oTerc->provinceId;
            $this->districtId    = $oTerc->districtId;
            $this->communeId     = $oTerc->communeId;
            $this->communeTypeId = $oTerc->communeTypeId;
        } else {
            if ($this->provinceId && $this->districtId && $this->communeId && $this->communeTypeId) {
                $oTerc        = Terc::setIds($this->provinceId, $this->districtId, $this->communeId, $this->communeTypeId);
                $this->tercId = $oTerc->tercId;
            }
        }

        //clearing data
        foreach (get_object_vars($this) as $prop => $value) {
            if ($value === '') {
                $this->{$prop} = null;
            }
        }

        return $this;
    }
}
