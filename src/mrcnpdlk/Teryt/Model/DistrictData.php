<?php
/**
 * Copyright (c) 2017.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Created by Marcin.
 * Date: 05.09.2017
 * Time: 23:24
 */

namespace mrcnpdlk\Teryt\Model;


use mrcnpdlk\Teryt\Client;

class DistrictData extends TerritorialDivisionUnitData
{
    /**
     * @see Client::getCommunes();
     * @return CommuneData[]
     */
    public function getCommunes()
    {
        return Client::getInstance()->getCommunes($this->provinceId, $this->districtId);
    }

    /**
     * @param string $phrase
     *
     * @see Client::searchCommune()
     * @return CommuneData[]
     */
    public function searchCommunes(string $phrase)
    {
        return Client::getInstance()->searchCommune($phrase, $this->provinceId, $this->districtId);
    }
}
