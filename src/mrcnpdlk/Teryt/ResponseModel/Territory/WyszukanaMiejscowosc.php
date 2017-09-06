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

namespace mrcnpdlk\Teryt\ResponseModel\Territory;

class WyszukanaMiejscowosc extends Miejscowosc
{
    /**
     * Identyfikator miejscowości podstawowej
     *
     * @var string
     */
    public $cityParentId;
    /**
     * Symbol rodzaju miejscowości
     *
     * @var string
     */
    public $rmId;
    /**
     * Nazwa rodzaju miejscowości
     *
     * @var string
     */
    public $rmName;

}
