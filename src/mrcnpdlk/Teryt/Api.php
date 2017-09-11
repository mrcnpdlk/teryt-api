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
 * Date: 06.09.2017
 * Time: 19:47
 */

namespace mrcnpdlk\Teryt;

use mrcnpdlk\Teryt\Model\City;


/**
 * Class Api
 *
 * @package mrcnpdlk\Teryt
 */
class Api
{
    /**
     * @var NativeApi
     */
    private $oNativeApi;

    /**
     * Api constructor.
     *
     * @param Client $oClient
     */
    public function __construct(Client $oClient)
    {
        $this->oNativeApi = new NativeApi($oClient);
    }

    public function getCity(string $id)
    {
        $oCity = new City($this->oNativeApi);

        return $oCity->find($id);
    }

}
