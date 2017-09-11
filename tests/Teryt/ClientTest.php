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
 * Time: 14:48
 */

namespace mrcnpdlk\Teryt;


use mrcnpdlk\Teryt\ResponseModel\Territory\JednostkaTerytorialna;
use Psr\Log\NullLogger;

class ClientTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testEmptyLogger()
    {
        $oClient = new \mrcnpdlk\Teryt\Client();
        $this->assertInstanceOf(NullLogger::class, $oClient->getLogger());
    }

    public function testEmptyCache()
    {
        $oClient = new \mrcnpdlk\Teryt\Client();
        $this->assertInstanceOf(NullLogger::class, $oClient->getLogger());
    }

    public function testGetProvinces()
    {
        $oClient    = new \mrcnpdlk\Teryt\Client();
        $oNativeApi = new NativeApi($oClient);
        $tList      = $oNativeApi->PobierzListeWojewodztw();
        $this->assertNotEmpty($tList);
        $this->assertInstanceOf(JednostkaTerytorialna::class, $tList[0]);

    }

}
