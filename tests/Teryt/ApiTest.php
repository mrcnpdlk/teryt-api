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

class ApiTest extends TestCase
{
    public function testCatalog()
    {
        $oFile = Api\Catalog::PobierzKatalogWMRODZ();

        $this->assertInstanceOf(\SplFileObject::class,$oFile);
        $this->assertEquals(true,file_exists($oFile->getPath()));
        $this->assertEquals(true,is_readable($oFile->getPath()));
    }
}
