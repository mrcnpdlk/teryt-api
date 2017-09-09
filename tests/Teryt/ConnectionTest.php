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
 * Time: 14:42
 */

namespace Teryt;


use mrcnpdlk\Teryt\TestCase;

class ConnectionTest extends TestCase
{
    public function testConnect()
    {
        $client = \mrcnpdlk\Teryt\Client::getInstance();
        $this->assertEquals(true, \mrcnpdlk\Teryt\Api::CzyZalogowany());
    }

    /**
     * @expectedException \mrcnpdlk\Teryt\Exception\Connection
     */
    public function testInvalidAuth()
    {
        \mrcnpdlk\Teryt\Client::getInstance()
                              ->setTerytConfig(['url' => 'http://foo.bar'])
        ;
    }
}
