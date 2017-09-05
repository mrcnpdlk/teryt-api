<?php
/**
 * Copyright (c) 2017.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Created by Marcin.
 * Date: 05.09.2017
 * Time: 23:17
 */

namespace mrcnpdlk\Teryt\Model;


use mrcnpdlk\Teryt\Client;

class CommuneData extends TerritorialDivisionUnitData
{
    public function getCities()
    {
        return Client::getInstance()->getCities($this->provinceId, $this->districtId, $this->communeId, $this->communeTypeId);
    }
}
