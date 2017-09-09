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
 * Time: 14:48
 */

namespace mrcnpdlk\Teryt;


use Psr\Log\NullLogger;

class ClientTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        \mrcnpdlk\Teryt\Client::create();
    }

    public function testEmptyLogger()
    {
        $this->assertInstanceOf(NullLogger::class, Client::getInstance()->getLogger());
    }
    public function testEmptyCache()
    {
        $this->assertInstanceOf(NullLogger::class, Client::getInstance()->getLogger());
    }
}
