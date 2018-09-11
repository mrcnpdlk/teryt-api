<?php
/**
 * TERYT-API
 *
 * Copyright (c) 2018 pudelek.org.pl
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
 * Date: 11.09.2018
 * Time: 21:52
 */

namespace mrcnpdlk\Teryt\Model\ArcGIS;


class AddressCandidateModel
{
    /**
     * @var string|null
     */
    public $address;
    /**
     * @var float
     */
    public $score;
    /**
     * @var \mrcnpdlk\Teryt\Model\ArcGIS\AddressCandidateModel\LocationModel
     */
    public $location;
    /**
     * @var \mrcnpdlk\Teryt\Model\ArcGIS\AddressCandidateModel\AttributeModel
     */
    public $attributes;
}
