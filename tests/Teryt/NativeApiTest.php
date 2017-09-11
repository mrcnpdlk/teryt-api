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
 * Time: 20:12
 */

namespace mrcnpdlk\Teryt;

class NativeApiTest extends TestCase
{
    public function testCatalog()
    {
        $oClient    = new \mrcnpdlk\Teryt\Client();
        $oNativeApi = new NativeApi($oClient);
        $oFile      = $oNativeApi->PobierzKatalogWMRODZ();

        $this->assertInstanceOf(\SplFileObject::class, $oFile);
        $this->assertEquals(true, file_exists($oFile->getPath()));
        $this->assertEquals(true, is_readable($oFile->getPath()));
    }

    public function testChange()
    {
        $oClient    = new \mrcnpdlk\Teryt\Client();
        $oNativeApi = new NativeApi($oClient);
        $fromDate   = new \DateTime();
        $toDate     = new \DateTime();
        $oFile      = $oNativeApi->PobierzZmianyTercAdresowy($fromDate->modify('-14 day'), $toDate);

        $this->assertInstanceOf(\SplFileObject::class, $oFile);
        $this->assertEquals(true, file_exists($oFile->getPath()));
        $this->assertEquals(true, is_readable($oFile->getPath()));
    }
}
