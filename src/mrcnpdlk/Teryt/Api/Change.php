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

class Change
{
    /**
     * Zmiany w katalogu TERC w wersji urzędowej rejestru
     *
     * @param \DateTime      $fromDate
     * @param \DateTime|null $toDate
     *
     * @return \SplFileObject
     */
    public static function PobierzZmianyTercUrzedowy(\DateTime $fromDate, \DateTime $toDate = null): \SplFileObject
    {
        $toDate  = $toDate ?? new \DateTime();
        $res     = Client::getInstance()->request(
            'PobierzZmianyTercUrzedowy',
            [
                'stanod' => $fromDate->format('Y-m-d'),
                'stando' => $toDate->format('Y-m-d'),
            ],
            false)
        ;
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Zmiany w katalogu TERC w wersji adresowej rejestru
     *
     * @param \DateTime      $fromDate
     * @param \DateTime|null $toDate
     *
     * @return \SplFileObject
     */
    public static function PobierzZmianyTercAdresowy(\DateTime $fromDate, \DateTime $toDate = null): \SplFileObject
    {
        $toDate  = $toDate ?? new \DateTime();
        $res     = Client::getInstance()->request(
            'PobierzZmianyTercAdresowy',
            [
                'stanod' => $fromDate->format('Y-m-d'),
                'stando' => $toDate->format('Y-m-d'),
            ],
            false)
        ;
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Zmiany w katalogu TERC w wersji adresowej rejestru
     *
     * @param \DateTime      $fromDate
     * @param \DateTime|null $toDate
     *
     * @return \SplFileObject
     */
    public static function PobierzZmianyNTS(\DateTime $fromDate, \DateTime $toDate = null): \SplFileObject
    {
        $toDate  = $toDate ?? new \DateTime();
        $res     = Client::getInstance()->request(
            'PobierzZmianyNTS',
            [
                'stanod' => $fromDate->format('Y-m-d'),
                'stando' => $toDate->format('Y-m-d'),
            ],
            false)
        ;
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Zmiany w katalogu SIMC w wersji urzędowej rejestru
     *
     * @param \DateTime      $fromDate
     * @param \DateTime|null $toDate
     *
     * @return \SplFileObject
     */
    public static function PobierzZmianySimcUrzedowy(\DateTime $fromDate, \DateTime $toDate = null): \SplFileObject
    {
        $toDate  = $toDate ?? new \DateTime();
        $res     = Client::getInstance()->request(
            'PobierzZmianySimcUrzedowy',
            [
                'stanod' => $fromDate->format('Y-m-d'),
                'stando' => $toDate->format('Y-m-d'),
            ],
            false)
        ;
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Zmiany w katalogu SIMC w wersji adresowej rejestru
     *
     * @param \DateTime      $fromDate
     * @param \DateTime|null $toDate
     *
     * @return \SplFileObject
     */
    public static function PobierzZmianySimcAdresowy(\DateTime $fromDate, \DateTime $toDate = null): \SplFileObject
    {
        $toDate  = $toDate ?? new \DateTime();
        $res     = Client::getInstance()->request(
            'PobierzZmianySimcAdresowy',
            [
                'stanod' => $fromDate->format('Y-m-d'),
                'stando' => $toDate->format('Y-m-d'),
            ],
            false)
        ;
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Zmiany w katalogu SIMC w wersji statystycznej rejestru
     *
     * @param \DateTime      $fromDate
     * @param \DateTime|null $toDate
     *
     * @return \SplFileObject
     */
    public static function PobierzZmianySimcStatystyczny(\DateTime $fromDate, \DateTime $toDate = null): \SplFileObject
    {
        $toDate  = $toDate ?? new \DateTime();
        $res     = Client::getInstance()->request(
            'PobierzZmianySimcStatystyczny',
            [
                'stanod' => $fromDate->format('Y-m-d'),
                'stando' => $toDate->format('Y-m-d'),
            ],
            false)
        ;
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Zmiany w katalogu ULIC w wersji urzędowej rejestru
     *
     * @param \DateTime      $fromDate
     * @param \DateTime|null $toDate
     *
     * @return \SplFileObject
     */
    public static function PobierzZmianyUlicUrzedowy(\DateTime $fromDate, \DateTime $toDate = null): \SplFileObject
    {
        $toDate  = $toDate ?? new \DateTime();
        $res     = Client::getInstance()->request(
            'PobierzZmianyUlicUrzedowy',
            [
                'stanod' => $fromDate->format('Y-m-d'),
                'stando' => $toDate->format('Y-m-d'),
            ],
            false)
        ;
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Zmiany w katalogu ULIC w wersji adresowej rejestru
     *
     * @param \DateTime      $fromDate
     * @param \DateTime|null $toDate
     *
     * @return \SplFileObject
     */
    public static function PobierzZmianyUlicAdresowy(\DateTime $fromDate, \DateTime $toDate = null): \SplFileObject
    {
        $toDate  = $toDate ?? new \DateTime();
        $res     = Client::getInstance()->request(
            'PobierzZmianyUlicAdresowy',
            [
                'stanod' => $fromDate->format('Y-m-d'),
                'stando' => $toDate->format('Y-m-d'),
            ],
            false)
        ;
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }
}
