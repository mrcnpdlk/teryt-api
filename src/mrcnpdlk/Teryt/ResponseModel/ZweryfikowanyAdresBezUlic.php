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
 * Created by Marcin Pudełek <marcin@pudelek.org.pl>
 * Date: 06.09.2017
 */

namespace mrcnpdlk\Teryt\ResponseModel;


class ZweryfikowanyAdresBezUlic extends AbstractResponseModel
{
    /**
     * Nazwa miejscowości
     *
     * @var string
     */
    public $cityName;
    /**
     * Rodzaj miejscowości
     *
     * @var string
     */
    public $cityType;
    /**
     * Poprzedni rodzaj miejscowości
     *
     * @var string
     */
    public $historicalCityType;
    /**
     * 7 znakowy identyfikator miejscowości
     *
     * @var string
     */
    public $cityId;

}