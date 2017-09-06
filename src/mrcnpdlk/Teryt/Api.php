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
 * Date: 06.09.2017
 * Time: 19:47
 */

namespace mrcnpdlk\Teryt;


use mrcnpdlk\Teryt\Exception\NotImplemented;
use mrcnpdlk\Teryt\Model\Terc;
use mrcnpdlk\Teryt\ResponseModel\Dictionary\RodzajMiejscowosci;
use mrcnpdlk\Teryt\ResponseModel\Territory\JednostkaTerytorialna;
use mrcnpdlk\Teryt\ResponseModel\Territory\Miejscowosc;
use mrcnpdlk\Teryt\ResponseModel\Territory\UlicaDrzewo;
use mrcnpdlk\Teryt\ResponseModel\Territory\ZweryfikowanyAdres;
use mrcnpdlk\Teryt\ResponseModel\Territory\ZweryfikowanyAdresBezUlic;

class Api
{
    /**
     * Sprawdzenie czy użytkownik jest zalogowany
     *
     * @return bool
     */
    public static function CzyZalogowany()
    {
        $res = Client::getInstance()->request('CzyZalogowany');

        return $res;
    }

    /**
     * @return JednostkaTerytorialna[]
     */
    public static function PobierzListeWojewodztw()
    {
        $answer = [];
        $res    = Client::getInstance()->request('PobierzListeWojewodztw');
        foreach (Helper::getPropertyAsArray($res, 'JednostkaTerytorialna') as $p) {
            $answer[] = JednostkaTerytorialna::create($p);
        };

        return $answer;
    }

    /**
     * @param string $provinceId
     *
     * @return JednostkaTerytorialna[]
     */
    public static function PobierzListePowiatow(string $provinceId)
    {
        $answer = [];
        $res    = Client::getInstance()->request('PobierzListePowiatow', ['Woj' => $provinceId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaTerytorialna') as $p) {
            $answer[] = JednostkaTerytorialna::create($p);
        };

        return $answer;
    }

    /**
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
            $answer[] = JednostkaTerytorialna::create($p);
        };

        return $answer;
    }

    /**
     * @param string $provinceId
     *
     * @return JednostkaTerytorialna[]
     */
    public static function PobierzGminyiPowDlaWoj(string $provinceId)
    {
        $answer = [];
        $res    = Client::getInstance()->request('PobierzGminyiPowDlaWoj', ['Woj' => $provinceId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaTerytorialna') as $p) {
            $answer[] = JednostkaTerytorialna::create($p);
        };

        return $answer;
    }

    /**
     * Lista ulic we wskazanej miejscowości
     *
     * @param int    $tercId
     * @param string $cityId
     * @param bool   $asAddres
     *
     * @return UlicaDrzewo[]
     * @todo Metoda nie działa poprawnie
     */
    public static function PobierzListeUlicDlaMiejscowosci(int $tercId, string $cityId, bool $asAddres = true)
    {
        $answer = [];
        $oTerc  = Terc::setTercId($tercId);
        $res    = Client::getInstance()->request('PobierzListeUlicDlaMiejscowosci',
            [
                'Woj'               => $oTerc->provinceId,
                'Pow'               => $oTerc->districtId,
                'Gmi'               => $oTerc->communeId,
                'Rodz'              => $oTerc->communeTypeId,
                'msc'               => $cityId,
                'czyWersjaUrzedowa' => !$asAddres,
                'czyWersjaAdresowa' => $asAddres,
            ]
        )
        ;

        foreach (Helper::getPropertyAsArray($res, 'UlicaDrzewo') as $p) {
            $answer[] = UlicaDrzewo::create($p);
        };

        return $answer;
    }

    /**
     * Lista miejscowości znajdujących się we wskazanej gminie.
     * Wyszukiwanie odbywa się z uwzględnieniem nazw
     *
     * @param string $provinceName
     * @param string $districtName
     * @param string $communeName
     *
     * @return Miejscowosc[]
     * @todo Metoda nie działa
     */
    public static function PobierzListeMiejscowosciWGminie(string $provinceName, string $districtName, string $communeName)
    {
        $answer = [];
        $res    = Client::getInstance()->request('PobierzListeMiejscowosciWGminie',
            [
                'wojewodztwo' => $provinceName,
                'Powiat'      => $districtName,
                'Gmina'       => $communeName,
            ])
        ;
        foreach (Helper::getPropertyAsArray($res, 'Miejscowosc') as $p) {
            $answer[] = Miejscowosc::create($p);
        };

        return $answer;
    }

    /**
     * Lista miejscowości znajdujących się we wskazanej gminie.
     * Wyszukiwanie odbywa się z uwzględnieniem symboli
     *
     * @param int $tercId
     *
     * @return Miejscowosc[]
     * @todo Zwraca niezgodne typy - połatane
     */
    public static function PobierzListeMiejscowosciWRodzajuGminy(int $tercId)
    {
        $answer = [];
        $oTerc  = Terc::setTercId($tercId);
        $res    = Client::getInstance()->request('PobierzListeMiejscowosciWRodzajuGminy',
            [
                'symbolWoj'  => $oTerc->provinceId,
                'symbolPow'  => $oTerc->districtId,
                'symbolGmi'  => $oTerc->communeId,
                'symbolRodz' => $oTerc->communeTypeId,
            ])
        ;
        foreach (Helper::getPropertyAsArray($res, 'Miejscowosc') as $p) {
            $answer[] = Miejscowosc::create($p);
        };

        return $answer;
    }

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
            $answer[] = RodzajMiejscowosci::create($p);
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

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT do poziomu
     * miejscowości. Weryfikacja odbywa się za pomoca identyfikatorów
     *
     * @param string $cityId
     *
     * @return ZweryfikowanyAdresBezUlic
     */
    public static function WeryfikujAdresDlaMiejscowosci(string $cityId)
    {
        $res   = Client::getInstance()->request('WeryfikujAdresDlaMiejscowosci', ['symbolMsc' => $cityId]);
        $oData = Helper::getPropertyAsObject($res, 'ZweryfikowanyAdresBezUlic');

        return ZweryfikowanyAdresBezUlic::create($oData);
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT,w wersji
     * adresowej rejestru do poziomu miejscowości. Weryfikacja odbywa się za pomoca identyfikatorów
     *
     * @param string $cityId
     *
     * @return ZweryfikowanyAdresBezUlic
     */
    public static function WeryfikujAdresDlaMiejscowosciAdresowy(string $cityId)
    {
        $res   = Client::getInstance()->request('WeryfikujAdresDlaMiejscowosciAdresowy', ['symbolMsc' => $cityId]);
        $oData = Helper::getPropertyAsObject($res, 'ZweryfikowanyAdresBezUlic');

        return ZweryfikowanyAdresBezUlic::create($oData);
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT do poziomu
     * miejscowości. Weryfikacja odbywa się za pomoca nazw
     *
     * @param string      $provinceName
     * @param string      $districtName
     * @param string      $communeName
     * @param string      $cityName
     * @param string|null $cityTypeName
     *
     * @throws NotImplemented
     */
    public static function WeryfikujAdresWmiejscowosci(
        string $provinceName,
        string $districtName,
        string $communeName,
        string $cityName,
        string $cityTypeName = null
    ) {
        throw new NotImplemented(sprintf('%s() Method not implemented', __METHOD__));
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT w wersji adresowej do poziomu
     * miejscowości. Weryfikacja odbywa się za pomoca nazw
     *
     * @param string      $provinceName
     * @param string      $districtName
     * @param string      $communeName
     * @param string      $cityName
     * @param string|null $cityTypeName
     *
     * @throws NotImplemented
     */
    public static function WeryfikujAdresWmiejscowosciAdresowy(
        string $provinceName,
        string $districtName,
        string $communeName,
        string $cityName,
        string $cityTypeName = null
    ) {
        throw new NotImplemented(sprintf('%s() Method not implemented', __METHOD__));
    }

    public static function WeryfikujAdresDlaUlic(string $cityId, string $streetId)
    {
        $res = Client::getInstance()->request('WeryfikujAdresDlaUlic', ['symbolMsc' => $cityId, 'SymUl' => $streetId]);
        $oData = Helper::getPropertyAsObject($res, 'ZweryfikowanyAdres');

        return ZweryfikowanyAdres::create($oData);
    }


}
