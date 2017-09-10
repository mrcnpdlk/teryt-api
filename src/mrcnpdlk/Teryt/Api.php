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

use mrcnpdlk\Teryt\Model\Terc;
use mrcnpdlk\Teryt\ResponseModel\Dictionary\RodzajMiejscowosci;
use mrcnpdlk\Teryt\ResponseModel\Territory\JednostkaNomenklaturyNTS;
use mrcnpdlk\Teryt\ResponseModel\Territory\JednostkaPodzialuTerytorialnego;
use mrcnpdlk\Teryt\ResponseModel\Territory\JednostkaTerytorialna;
use mrcnpdlk\Teryt\ResponseModel\Territory\Miejscowosc;
use mrcnpdlk\Teryt\ResponseModel\Territory\Ulica;
use mrcnpdlk\Teryt\ResponseModel\Territory\UlicaDrzewo;
use mrcnpdlk\Teryt\ResponseModel\Territory\WyszukanaMiejscowosc;
use mrcnpdlk\Teryt\ResponseModel\Territory\WyszukanaUlica;
use mrcnpdlk\Teryt\ResponseModel\Territory\ZweryfikowanyAdres;
use mrcnpdlk\Teryt\ResponseModel\Territory\ZweryfikowanyAdresBezUlic;

/**
 * Class Api
 *
 * @package mrcnpdlk\Teryt
 */
class Api
{

    /**
     * @var string Wyszukiwanie wśród wszystkich rodzajów jednostek
     */
    const CATEGORY_ALL         = '0'; // Wyszukiwanie wśród wszystkich rodzajów jednostek
    const CATEGORY_WOJ_ALL     = '1'; // Dla województw
    const CATEGORY_POW_ALL     = '2'; // Dla wszystkich powiatów
    const CATEGORY_POW_ZIE     = '21'; // Dla powiatów ziemskich (identyfikator powiatu 01-60)
    const CATEGORY_POW_MIA     = '22'; // Dla miast na prawach powiatu (identyfikator powiatu 61-99)
    const CATEGORY_GMI_ALL     = '3'; // Dla gmin ogółem
    const CATEGORY_GMI_MIA     = '31'; // Dla gmin miejskich (identyfikator rodzaju gminy 1)
    const CATEGORY_DELEG       = '32'; // Dla dzielnic i delegatur (identyfikator rodzaju 8 i 9)
    const CATEGORY_GMI_WIE     = '33'; // Dla gmin wiejskich (identyfikator rodzaju 2)
    const CATEGORY_GMI_MIE_WIE = '34'; // Dla gmin miejsko-wiejskich (3)
    const CATEGORY_MIA         = '341'; // Dla miast w gminach miejsko-wiejskich(4)
    const CATEGORY_MIA_OBS     = '342'; // Dla obszarów miejskich w gminach miejsko-wiejskich(5)
    const CATEGORY_MIA_ALL     = '35'; // Dla miast ogółem (identyfikator 1 i 4)
    const CATEGORY_WIE         = '36'; // Dla terenów wiejskich (identyfikator 2 i 5)

    /**
     * Określenie zakresu miejscowości
     */

    const SEARCH_CITY_TYPE_ALL  = '000'; //Wszystkie
    const SEARCH_CITY_TYPE_MAIN = '001'; //Miejscowości podstawowe
    const SEARCH_CITY_TYPE_ADD  = '002'; //Części integralne miejscowości

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
     * Data początkowa bieżącego stanu katalogu TERC
     *
     * @return null|string Data w formacie YYY-MM-DD
     */
    public static function PobierzDateAktualnegoKatTerc()
    {
        $res = Client::getInstance()->request('PobierzDateAktualnegoKatTerc');

        try {
            return (new \DateTime($res))->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Data początkowa bieżącego stanu katalogu NTS
     *
     * @return null|string Data w formacie YYY-MM-DD
     */
    public static function PobierzDateAktualnegoKatNTS()
    {
        $res = Client::getInstance()->request('PobierzDateAktualnegoKatNTS');

        try {
            return (new \DateTime($res))->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Data początkowa bieżącego stanu katalogu SIMC
     *
     * @return null|string Data w formacie YYY-MM-DD
     */
    public static function PobierzDateAktualnegoKatSimc()
    {
        $res = Client::getInstance()->request('PobierzDateAktualnegoKatSimc');

        try {
            return (new \DateTime($res))->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Data początkowa bieżącego stanu katalogu ULIC
     *
     * @return null|string Data w formacie YYY-MM-DD
     */
    public static function PobierzDateAktualnegoKatUlic()
    {
        $res = Client::getInstance()->request('PobierzDateAktualnegoKatUlic');

        try {
            return (new \DateTime($res))->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
