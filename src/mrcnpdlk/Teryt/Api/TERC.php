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
 * Time: 12:38
 */

namespace mrcnpdlk\Teryt\Api;


use mrcnpdlk\Teryt\Client;
use mrcnpdlk\Teryt\Helper;
use mrcnpdlk\Teryt\ResponseModel\Territory\JednostkaTerytorialna;

class TERC
{
    /**
     * Lista województw
     *
     * @return JednostkaTerytorialna[]
     */
    public static function PobierzListeWojewodztw()
    {
        $answer = [];
        $res    = Client::getInstance()->request('PobierzListeWojewodztw');
        foreach (Helper::getPropertyAsArray($res, 'JednostkaTerytorialna') as $p) {
            $answer[] = new JednostkaTerytorialna($p);
        };

        return $answer;
    }

    /**
     * Pobieranie listy powiatów dla danego województwa
     *
     * @param string $provinceId
     *
     * @return JednostkaTerytorialna[]
     */
    public static function PobierzListePowiatow(string $provinceId)
    {
        $answer = [];
        $res    = Client::getInstance()->request('PobierzListePowiatow', ['Woj' => $provinceId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaTerytorialna') as $p) {
            $answer[] = new JednostkaTerytorialna($p);
        };

        return $answer;
    }

    /**
     * Lista gmin we wskazanym powiecie
     *
     * @param string $provinceId
     * @param string $districtId
     *
     * @return JednostkaTerytorialna[]
     */
    public static function PobierzListeGmin(string $provinceId, string $districtId)
    {
        $answer = [];
        $res    = Client::getInstance()->request('PobierzListeGmin', ['Woj' => $provinceId, 'Pow' => $districtId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaTerytorialna') as $p) {
            $answer[] = new JednostkaTerytorialna($p);
        };

        return $answer;
    }

    /**
     * Lista powiatów i gmin we wskazanym województwie
     *
     * @param string $provinceId
     *
     * @return JednostkaTerytorialna[]
     */
    public static function PobierzGminyiPowDlaWoj(string $provinceId)
    {
        $answer = [];
        $res    = Client::getInstance()->request('PobierzGminyiPowDlaWoj', ['Woj' => $provinceId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaTerytorialna') as $p) {
            $answer[] = new JednostkaTerytorialna($p);
        };

        return $answer;
    }
}
