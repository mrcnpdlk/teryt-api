<?php
/**
 * Copyright (c) 2017.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Created by Marcin.
 * Date: 05.09.2017
 * Time: 23:23
 */

namespace mrcnpdlk\Teryt\Model;


use mrcnpdlk\Teryt\Client;

class ProvinceData extends TerritorialDivisionUnitData
{
    /**
     * @see Client::getDistrict()
     *
     * @param string $districtId
     *
     * @return DistrictData
     */
    public function getDistrict(string $districtId)
    {
        return Client::getInstance()->getDistrict($this->provinceId, $districtId);
    }

    /**
     * @see Client::getDistricts()
     * @return DistrictData[]
     */
    public function getDistricts()
    {
        return Client::getInstance()->getDistricts($this->provinceId);
    }

    /**
     * @see Client::searchDistrict()
     *
     * @param string $phrase
     *
     * @return DistrictData[]
     */
    public function searchDistricts(string $phrase)
    {
        return Client::getInstance()->searchDistrict($phrase, $this->provinceId);
    }
}
