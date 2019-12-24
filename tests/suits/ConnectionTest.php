<?php
/**
 * TERYT-API
 *
 * Copyright (c) 2019 pudelek.org.pl
 *
 * @license MIT License (MIT)
 *
 * For the full copyright and license information, please view source file
 * that is bundled with this package in the file LICENSE
 * @author Marcin Pudełek <marcin@pudelek.org.pl>
 */

/**
 * Created by Marcin.
 * Date: 09.09.2017
 * Time: 14:42
 */

namespace Tests\mrcnpdlk\Teryt;

use mrcnpdlk\Teryt\Client;
use mrcnpdlk\Teryt\Config;
use mrcnpdlk\Teryt\Exception\Connection;
use mrcnpdlk\Teryt\Exception\NotFound;
use mrcnpdlk\Teryt\NativeApi;

class ConnectionTest extends TestCase
{
    public function testConnect(): void
    {
        $oConfig    = new Config();
        $oNativeApi = NativeApi::create($oConfig);
        $this->assertEquals(true, $oNativeApi->CzyZalogowany());
    }

    /**
     * @throws \Mrcnpdlk\Lib\ConfigurationException
     * @throws \mrcnpdlk\Teryt\Exception
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     */
    public function testInvalidAuth(): void
    {
        $this->expectException(Connection::class);
        $oConfig = new Config([
            'username'     => 'invaliduser',
            'password'     => 'invalidpassword',
            'isProduction' => false,
        ]);
        $oNativeApi = NativeApi::create($oConfig);
        $oNativeApi->PobierzListeWojewodztw();
    }
}
