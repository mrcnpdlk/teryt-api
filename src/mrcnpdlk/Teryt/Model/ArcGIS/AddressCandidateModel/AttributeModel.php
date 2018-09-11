<?php
/**
 * TERYT-API
 *
 * Copyright (c) 2018 pudelek.org.pl
 *
 * @license MIT License (MIT)
 *
 * For the full copyright and license information, please view source file
 * that is bundled with this package in the file LICENSE
 *
 * @author  Marcin Pudełek <marcin@pudelek.org.pl>
 *
 */

/**
 * Created by Marcin.
 * Date: 11.09.2018
 * Time: 21:54
 */

namespace mrcnpdlk\Teryt\Model\ArcGIS\AddressCandidateModel;


class AttributeModel
{
    /**
     * @var string
     */
    public $longLabel;
    /**
     * @var string
     */
    public $addrType;
    /**
     * @var string
     */
    public $nbrhd;
    /**
     * @var string
     */
    public $district;
    /**
     * @var string
     */
    public $city;
    /**
     * @var string
     */
    public $subregion;
    /**
     * @var string
     */
    public $region;
    /**
     * @var string
     */
    public $postal;
    /**
     * @var string
     */
    public $country;
    /**
     * @var string
     */
    public $distance;

    /**
     * @param string $addrType
     *
     * @return AttributeModel
     */
    public function setAddrType(string $addrType)
    {
        $this->addrType = $addrType;

        return $this;
    }
}
