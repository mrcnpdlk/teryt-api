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
 * @author Marcin Pudełek <marcin@pudelek.org.pl>
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
        $this->assertInstanceOf(NullLogger::class, Client::getInstance()->getLogger());
    }

    public function testEmptyCache()
    {
        $this->assertInstanceOf(NullLogger::class, Client::getInstance()->getLogger());
    }

    public function testGetProvinces()
    {
        $tList = Api\TERC::PobierzListeWojewodztw();
        $this->assertNotEmpty($tList);
        $this->assertInstanceOf(JednostkaTerytorialna::class, $tList[0]);

    }

}
