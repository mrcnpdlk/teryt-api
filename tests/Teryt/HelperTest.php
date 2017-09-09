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
 * Time: 19:59
 */

namespace mrcnpdlk\Teryt;

class HelperTest extends TestCase
{
    public function testConvertToBoolean()
    {
        $this->assertEquals(true, Helper::convertToBoolean('true'));
        $this->assertEquals(true, Helper::convertToBoolean(1));
        $this->assertEquals(true, Helper::convertToBoolean(true));
        $this->assertEquals(false, Helper::convertToBoolean('false'));
        $this->assertEquals(false, Helper::convertToBoolean(0));
        $this->assertEquals(false, Helper::convertToBoolean(false));
    }
}
