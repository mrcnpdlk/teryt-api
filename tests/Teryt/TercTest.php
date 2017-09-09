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
 * Date: 09.09.2017
 * Time: 20:12
 */

namespace mrcnpdlk\Teryt;

use mrcnpdlk\Teryt\Model\Terc;

class TercTest extends TestCase
{
    public function testTerc()
    {
        $oTerc = new Terc(1234567);
        $this->assertEquals('12', $oTerc->getProvinceId());
        $this->assertEquals('34', $oTerc->getDistrictId());
        $this->assertEquals('56', $oTerc->getCommuneId());
        $this->assertEquals('7', $oTerc->getCommuneTypeId());
        $this->assertEquals(1234567, $oTerc->getTercId());

        $oTerc = new Terc(123456);
        $this->assertEquals('01', $oTerc->getProvinceId());
        $this->assertEquals('23', $oTerc->getDistrictId());
        $this->assertEquals('45', $oTerc->getCommuneId());
        $this->assertEquals('6', $oTerc->getCommuneTypeId());
        $this->assertEquals(123456, $oTerc->getTercId());

        $oTerc = new Terc(null);
        $this->assertEquals(null, $oTerc->getProvinceId());
        $this->assertEquals(null, $oTerc->getDistrictId());
        $this->assertEquals(null, $oTerc->getCommuneId());
        $this->assertEquals(null, $oTerc->getCommuneTypeId());
        $this->assertEquals(null, $oTerc->getTercId());

        $oTerc = new Terc();
        $oTerc->setIds('1','2','3','4');
        $this->assertEquals('01', $oTerc->getProvinceId());
        $this->assertEquals('02', $oTerc->getDistrictId());
        $this->assertEquals('03', $oTerc->getCommuneId());
        $this->assertEquals('4', $oTerc->getCommuneTypeId());
        $this->assertEquals(102034, $oTerc->getTercId());
    }
}
