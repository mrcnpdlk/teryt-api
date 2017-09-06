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


abstract class AbstractResponseModel
{
    /**
     * Dwuznakowy symbol województwa
     *
     * @var string
     */
    public $provinceId;
    /**
     * Dwuznakowy symbol powiatu
     *
     * @var string|null
     */
    public $districtId;
    /**
     * Dwuznakowy symbol gminy
     *
     * @var string|null
     */
    public $communeId;
    /**
     * Jednoznakowy symbol gminy
     *
     * @var string|null
     */
    public $communeTypeId;
    /**
     * Nazwa województwa
     *
     * @var string
     */
    public $provinceName;
    /**
     * Nazwa powiatu
     *
     * @var string
     */
    public $districtName;
    /**
     * Nazwa gminy
     *
     * @var string
     */
    public $communeName;
    /**
     * Nazwa rodzaju gminy
     *
     * @var string|null
     */
    public $communeTypeName;
    /**
     * Określa datę katalogu dla wskazanego stanu w formacie YYYY-MM-DD
     *
     * @var string
     */
    public $statusDate;
}