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
 * Created by Marcin.
 * Date: 10.09.2017
 * Time: 12:40
 */

namespace mrcnpdlk\Teryt\Api;


use mrcnpdlk\Teryt\Client;
use mrcnpdlk\Teryt\Helper;
use mrcnpdlk\Teryt\ResponseModel\Territory\JednostkaNomenklaturyNTS;

class NTS
{
    /**
     * Lista regionów
     *
     * @return JednostkaNomenklaturyNTS[]
     */
    public static function PobierzListeRegionow()
    {
        $answer = [];
        $res    = Client::getInstance()->request('PobierzListeRegionow');
        foreach (Helper::getPropertyAsArray($res, 'JednostkaNomenklaturyNTS') as $p) {
            $answer[] = new JednostkaNomenklaturyNTS($p);
        };

        return $answer;
    }

    /**
     * Lista województw regionie
     *
     * @param string $regionId Jednoznakowy symbol regionu
     *
     * @return JednostkaNomenklaturyNTS[]
     */
    public static function PobierzListeWojewodztwWRegionie(string $regionId)
    {
        $answer = [];
        $res    = Client::getInstance()->request('PobierzListeWojewodztwWRegionie', ['Reg' => $regionId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaNomenklaturyNTS') as $p) {
            $answer[] = new JednostkaNomenklaturyNTS($p);
        };

        return $answer;
    }

    /**
     * Lista podregionów w województwie
     *
     * @param string $provinceId Dwuznakowy symbol województwa
     *
     * @return JednostkaNomenklaturyNTS[]
     */
    public static function PobierzListePodregionow(string $provinceId)
    {
        $answer = [];
        $res    = Client::getInstance()->request('PobierzListePodregionow', ['Woj' => $provinceId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaNomenklaturyNTS') as $p) {
            $answer[] = new JednostkaNomenklaturyNTS($p);
        };

        return $answer;
    }

    /**
     * Lista powiatów w podregionie
     *
     * @param string $subregionId Dwuznakowy symbol podregionu
     *
     * @return JednostkaNomenklaturyNTS[]
     */
    public static function PobierzListePowiatowWPodregionie(string $subregionId)
    {
        $answer = [];
        $res    = Client::getInstance()->request('PobierzListePowiatowWPodregionie', ['Podreg' => $subregionId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaNomenklaturyNTS') as $p) {
            $answer[] = new JednostkaNomenklaturyNTS($p);
        };

        return $answer;
    }

    /**
     * Lista gmin w powiecie
     *
     * @param string $districtId  dwuznakowy symbol powiatu
     * @param string $subregionId dwuznakowy symbol podregionu
     *
     * @return JednostkaNomenklaturyNTS[]
     */
    public static function PobierzListeGminPowiecie(string $districtId, string $subregionId)
    {
        $answer = [];
        $res    = Client::getInstance()->request('PobierzListeGminPowiecie', ['Pow' => $districtId, 'Podreg' => $subregionId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaNomenklaturyNTS') as $p) {
            $answer[] = new JednostkaNomenklaturyNTS($p);
        };

        return $answer;
    }
}
