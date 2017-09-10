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
 * Time: 12:41
 */

namespace mrcnpdlk\Teryt\Api;


use mrcnpdlk\Teryt\Helper;
use mrcnpdlk\Teryt\Model\Terc;

class ULIC
{
    /**
     * Lista ulic we wskazanej miejscowości
     *
     * @param int    $tercId
     * @param string $cityId
     * @param bool   $asAddress
     *
     * @return UlicaDrzewo[]
     * @todo Metoda nie działa poprawnie
     */
    public static function PobierzListeUlicDlaMiejscowosci(int $tercId, string $cityId, bool $asAddress = false)
    {
        $answer = [];
        $oTerc  = new Terc($tercId);
        $res    = Client::getInstance()->request('PobierzListeUlicDlaMiejscowosci',
            [
                'Woj'               => $oTerc->getProvinceId(),
                'Pow'               => $oTerc->getDistrictId(),
                'Gmi'               => $oTerc->getCommuneId(),
                'Rodz'              => $oTerc->getCommuneTypeId(),
                'msc'               => $cityId,
                'czyWersjaUrzedowa' => !$asAddress,
                'czyWersjaAdresowa' => $asAddress,
            ]
        )
        ;

        foreach (Helper::getPropertyAsArray($res, 'UlicaDrzewo') as $p) {
            $answer[] = new UlicaDrzewo($p);
        };

        return $answer;
    }
}
