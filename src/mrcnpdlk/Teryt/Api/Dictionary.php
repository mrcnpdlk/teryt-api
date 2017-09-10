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
 * @author Marcin Pudełek <marcin@pudelek.org.pl>
 *
 */

/**
 * Created by Marcin.
 * Date: 10.09.2017
 * Time: 12:47
 */

namespace mrcnpdlk\Teryt\Api;


use mrcnpdlk\Teryt\Client;
use mrcnpdlk\Teryt\Helper;
use mrcnpdlk\Teryt\ResponseModel\Dictionary\RodzajMiejscowosci;

class Dictionary
{
    /**
     * Zwraca listę rodzajów jednostek
     *
     * @return string[]
     */
    public static function PobierzSlownikRodzajowJednostek()
    {
        $res = Client::getInstance()->request('PobierzSlownikRodzajowJednostek');

        return Helper::getPropertyAsArray($res, 'string');
    }

    /**
     * Zwraca listę rodzajów miejscowości
     *
     * @return RodzajMiejscowosci[]
     */
    public static function PobierzSlownikRodzajowSIMC()
    {
        $answer = [];
        $res    = Client::getInstance()->request('PobierzSlownikRodzajowSIMC');
        foreach (Helper::getPropertyAsArray($res, 'RodzajMiejscowosci') as $p) {
            $answer[] = new RodzajMiejscowosci($p);
        };

        return $answer;
    }

    /**
     * Zwraca listę cech obiektów z katalogu ulic
     *
     * @return string[]
     */
    public static function PobierzSlownikCechULIC()
    {
        $res = Client::getInstance()->request('PobierzSlownikCechULIC');

        return Helper::getPropertyAsArray($res, 'string');
    }
}
