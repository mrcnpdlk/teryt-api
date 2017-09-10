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
 * @author  Marcin Pudełek <marcin@pudelek.org.pl>
 *
 */

/**
 * Created by Marcin.
 * Date: 10.09.2017
 * Time: 12:53
 */

namespace mrcnpdlk\Teryt\Api;


use mrcnpdlk\Teryt\Client;
use mrcnpdlk\Teryt\Helper;
use mrcnpdlk\Teryt\ResponseModel\Territory\ZweryfikowanyAdres;
use mrcnpdlk\Teryt\ResponseModel\Territory\ZweryfikowanyAdresBezUlic;

class Verification
{
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

        return new ZweryfikowanyAdresBezUlic($oData);
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

        return new ZweryfikowanyAdresBezUlic($oData);
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT do poziomu
     * miejscowości. Weryfikacja odbywa się za pomoca nazw
     *
     * Nazwa miejscowości nie musibyć pełna - nastąpi wtedy wyszkiwanie pełnokontekstowe
     * w wtórym zostanie zwrócona tablica wyników
     *
     * @param string      $provinceName Nazwa województwa
     * @param string      $districtName Nazwa powiatu
     * @param string      $communeName  Nazwa gminy
     * @param string      $cityName     Nazwa miejscowości
     * @param string|null $cityTypeName Nazwa typu miejscowości
     *
     * @return ZweryfikowanyAdresBezUlic[]
     */
    public static function WeryfikujAdresWmiejscowosci(
        string $provinceName,
        string $districtName,
        string $communeName,
        string $cityName,
        string $cityTypeName = null
    ) {
        $answer = [];
        $res    = Client::getInstance()->request('WeryfikujAdresWmiejscowosci',
            [
                'Wojewodztwo' => $provinceName,
                'Powiat'      => $districtName,
                'Gmina'       => $communeName,
                'Miejscowosc' => $cityName,
                'Rodzaj'      => $cityTypeName,
            ])
        ;
        foreach (Helper::getPropertyAsArray($res, 'ZweryfikowanyAdresBezUlic') as $p) {
            $answer[] = new ZweryfikowanyAdresBezUlic($p);
        };

        return $answer;
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT w wersji adresowej do poziomu
     * miejscowości. Weryfikacja odbywa się za pomoca nazw
     *
     * Nazwa miejscowości nie musibyć pełna - nastąpi wtedy wyszkiwanie pełnokontekstowe
     * w wtórym zostanie zwrócona tablica wyników
     *
     * @param string      $provinceName Nazwa województwa
     * @param string      $districtName Nazwa powiatu
     * @param string      $communeName  Nazwa gminy
     * @param string      $cityName     Nazwa miejscowości
     * @param string|null $cityTypeName Nazwa typu miejscowości
     *
     * @return ZweryfikowanyAdresBezUlic[]
     */
    public static function WeryfikujAdresWmiejscowosciAdresowy(
        string $provinceName,
        string $districtName,
        string $communeName,
        string $cityName,
        string $cityTypeName = null
    ) {
        $answer = [];
        $res    = Client::getInstance()->request('WeryfikujAdresWmiejscowosciAdresowy',
            [
                'Wojewodztwo' => $provinceName,
                'Powiat'      => $districtName,
                'Gmina'       => $communeName,
                'Miejscowosc' => $cityName,
                'Rodzaj'      => $cityTypeName,
            ])
        ;
        foreach (Helper::getPropertyAsArray($res, 'ZweryfikowanyAdresBezUlic') as $p) {
            $answer[] = new ZweryfikowanyAdresBezUlic($p);
        };

        return $answer;
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie
     * TERYT do poziomu ulic.Weryfikacja odbywa się za pomoca nazw
     *
     * @param string $cityId
     * @param string $streetId
     *
     * @return ZweryfikowanyAdres
     */
    public static function WeryfikujAdresDlaUlic(string $cityId, string $streetId)
    {
        $res   = Client::getInstance()->request('WeryfikujAdresDlaUlic', ['symbolMsc' => $cityId, 'SymUl' => $streetId]);
        $oData = Helper::getPropertyAsObject($res, 'ZweryfikowanyAdres');

        return new ZweryfikowanyAdres($oData);
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie
     * TERYT do poziomu ulic w wersji adresowej. Weryfikacja odbywa się za pomoca nazw
     *
     * @param string $cityId
     * @param string $streetId
     *
     * @return ZweryfikowanyAdres
     */
    public static function WeryfikujAdresDlaUlicAdresowy(string $cityId, string $streetId)
    {
        $res   = Client::getInstance()->request('WeryfikujAdresDlaUlicAdresowy', ['symbolMsc' => $cityId, 'SymUl' => $streetId]);
        $oData = Helper::getPropertyAsObject($res, 'ZweryfikowanyAdres');

        return new ZweryfikowanyAdres($oData);
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT do poziomu ulic.
     * Weryfikacja odbywa się za pomoca nazw
     *
     * @param string      $provinceName
     * @param string      $districtName
     * @param string      $communeName
     * @param string      $cityName
     * @param string|null $cityTypeName
     * @param string      $streetName
     *
     * @return ZweryfikowanyAdres[]
     */
    public static function WeryfikujNazwaAdresUlic(
        string $provinceName,
        string $districtName,
        string $communeName,
        string $cityName,
        string $cityTypeName = null,
        string $streetName
    ) {
        $answer = [];
        $res    = Client::getInstance()->request('WeryfikujNazwaAdresUlic',
            [
                'nazwaWoj'         => $provinceName,
                'nazwaPow'         => $districtName,
                'nazwaGmi'         => $communeName,
                'nazwaMiejscowosc' => $cityName,
                'rodzajMiejsc'     => $cityTypeName,
                'nazwaUlicy'       => $streetName,
            ])
        ;

        $tData = Helper::getPropertyAsArray($res, 'ZweryfikowanyAdres');

        foreach ($tData as $datum) {
            $answer[] = new ZweryfikowanyAdres($datum);
        }

        return $answer;
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT do poziomu ulic w wersji adresowej rejestru.
     * Weryfikacja odbywa się za pomoca nazw
     *
     * @param string      $provinceName
     * @param string      $districtName
     * @param string      $communeName
     * @param string      $cityName
     * @param string|null $cityTypeName
     * @param string      $streetName
     *
     * @return ZweryfikowanyAdres[]
     */
    public static function WeryfikujNazwaAdresUlicAdresowy(
        string $provinceName,
        string $districtName,
        string $communeName,
        string $cityName,
        string $cityTypeName = null,
        string $streetName
    ) {
        $answer = [];
        $res    = Client::getInstance()->request('WeryfikujNazwaAdresUlicAdresowy',
            [
                'nazwaWoj'         => $provinceName,
                'nazwaPow'         => $districtName,
                'nazwaGmi'         => $communeName,
                'nazwaMiejscowosc' => $cityName,
                'rodzajMiejsc'     => $cityTypeName,
                'nazwaUlicy'       => $streetName,
            ])
        ;

        $tData = Helper::getPropertyAsArray($res, 'ZweryfikowanyAdres');

        foreach ($tData as $datum) {
            $answer[] = new ZweryfikowanyAdres($datum);
        }

        return $answer;
    }
}
