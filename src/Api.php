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

namespace mrcnpdlk\Teryt;

use mrcnpdlk\Teryt\Model\City;

/**
 * Class Api
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
     * @param \mrcnpdlk\Teryt\Config $configuration
     */
    public function __construct(Config $configuration)
    {
        $this->oNativeApi = NativeApi::create($configuration);
    }

    /**
     * Get information about City
     *
     * @param string $id
     *
     * @throws \mrcnpdlk\Teryt\Exception
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception\InvalidArgument
     * @throws \mrcnpdlk\Teryt\Exception\NotFound
     *
     * @return City
     */
    public function getCity(string $id): City
    {
        $oCity = new City();

        return $oCity->find($id);
    }
}
