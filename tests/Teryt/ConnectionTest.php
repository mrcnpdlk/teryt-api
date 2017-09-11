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
 * @author  Marcin Pudełek <marcin@pudelek.org.pl>
 *
 */

/**
 * Created by Marcin.
 * Date: 09.09.2017
 * Time: 14:42
 */

namespace mrcnpdlk\Teryt;

class ConnectionTest extends TestCase
{
    public function testConnect()
    {
        $oClient    = new \mrcnpdlk\Teryt\Client();
        $oNativeApi = new NativeApi($oClient);
        $this->assertEquals(true, $oNativeApi->CzyZalogowany());
    }

    /**
     * @expectedException \mrcnpdlk\Teryt\Exception\Connection
     */
    public function testInvalidAuth()
    {
        $oClient = new \mrcnpdlk\Teryt\Client();
        $oClient->setConfig(['url' => 'http://foo.bar']);
    }
}
