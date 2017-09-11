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
 * Date: 11.09.2017
 * Time: 23:34
 */

namespace mrcnpdlk\Teryt\Model;


use mrcnpdlk\Teryt\NativeApi;

class EntityAbstract
{
    /**
     * @var NativeApi
     */
    protected $oNativeApi;

    public function __construct(NativeApi $oNativeApi)
    {
        $this->oNativeApi = $oNativeApi;
    }
}
