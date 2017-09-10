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
 * Time: 14:42
 */

namespace mrcnpdlk\Teryt\Api;


use mrcnpdlk\Teryt\Client;
use mrcnpdlk\Teryt\Helper;

class Catalog
{
    /**
     * Dane z systemu identyfikatorów TERC z wybranego stanu katalogu w wersji adresowej
     *
     * @return \SplFileObject
     */
    public static function PobierzKatalogTERCAdr(): \SplFileObject
    {
        $res     = Client::getInstance()->request('PobierzKatalogTERCAdr');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Dane z systemu identyfikatorów TERC z wybranego stanu katalogu w wersji urzędowej
     *
     * @return \SplFileObject
     */
    public static function PobierzKatalogTERC(): \SplFileObject
    {
        $res     = Client::getInstance()->request('PobierzKatalogTERC');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Identyfikatory i nazwy jednostek nomenklatury z wybranego stanu katalogu
     *
     * @return \SplFileObject
     */
    public static function PobierzKatalogNTS(): \SplFileObject
    {
        $res     = Client::getInstance()->request('PobierzKatalogNTS');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Dane o miejscowościach z systemu identyfikatorów SIMC z wybranego stanu katalogu w wersji adresowej
     *
     * @return \SplFileObject
     */
    public static function PobierzKatalogSIMCAdr(): \SplFileObject
    {
        $res     = Client::getInstance()->request('PobierzKatalogSIMCAdr');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Dane o miejscowościach z systemu identyfikatorów SIMC z wybranego stanu katalogu w wersji adresowej
     *
     * @return \SplFileObject
     */
    public static function PobierzKatalogSIMC(): \SplFileObject
    {
        $res     = Client::getInstance()->request('PobierzKatalogSIMC');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Dane o miejscowościach z systemu identyfikatorów SIMC z wybranego stanu katalogu w wersji adresowej
     *
     * @return \SplFileObject
     */
    public static function PobierzKatalogSIMCStat(): \SplFileObject
    {
        $res     = Client::getInstance()->request('PobierzKatalogSIMCStat');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Katalog ulic dla wskazanego stanu w wersji urzędowej
     *
     * @return \SplFileObject
     */
    public static function PobierzKatalogULIC(): \SplFileObject
    {
        $res     = Client::getInstance()->request('PobierzKatalogULIC');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Katalog ulic dla wskazanego stanu w wersji adresowej
     *
     * @return \SplFileObject
     */
    public static function PobierzKatalogULICAdr(): \SplFileObject
    {
        $res     = Client::getInstance()->request('PobierzKatalogULICAdr');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Katalog ulic dla wskazanego stanu w wersji urzędowej zmodyfikowany dla miast posiadający delegatury
     *
     * @return \SplFileObject
     */
    public static function PobierzKatalogULICBezDzielnic(): \SplFileObject
    {
        $res     = Client::getInstance()->request('PobierzKatalogULICBezDzielnic');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Katalog rodzajów miejscowości dla wskazanego stanu
     *
     * @return \SplFileObject
     */
    public static function PobierzKatalogWMRODZ(): \SplFileObject
    {
        $res     = Client::getInstance()->request('PobierzKatalogWMRODZ');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }
}
