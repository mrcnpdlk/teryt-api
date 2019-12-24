<?php

declare(strict_types=1);
/**
 * TERYT-API
 *
 * Copyright (c) 2019 pudelek.org.pl
 *
 * @license MIT License (MIT)
 *
 * For the full copyright and license information, please view source file
 * that is bundled with this package in the file LICENSE
 * @author Marcin Pudełek <marcin@pudelek.org.pl>
 */

/**
 * Created by Marcin.
 * Date: 11.09.2017
 * Time: 23:01
 */

namespace mrcnpdlk\Teryt;

use DateTime;
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
use SplFileObject;

final class NativeApi
{
    /**
     * @var string Wyszukiwanie wśród wszystkich rodzajów jednostek
     */
    public const CATEGORY_ALL         = '0'; // Wyszukiwanie wśród wszystkich rodzajów jednostek
    public const CATEGORY_WOJ_ALL     = '1'; // Dla województw
    public const CATEGORY_POW_ALL     = '2'; // Dla wszystkich powiatów
    public const CATEGORY_POW_ZIE     = '21'; // Dla powiatów ziemskich (identyfikator powiatu 01-60)
    public const CATEGORY_POW_MIA     = '22'; // Dla miast na prawach powiatu (identyfikator powiatu 61-99)
    public const CATEGORY_GMI_ALL     = '3'; // Dla gmin ogółem
    public const CATEGORY_GMI_MIA     = '31'; // Dla gmin miejskich (identyfikator rodzaju gminy 1)
    public const CATEGORY_DELEG       = '32'; // Dla dzielnic i delegatur (identyfikator rodzaju 8 i 9)
    public const CATEGORY_GMI_WIE     = '33'; // Dla gmin wiejskich (identyfikator rodzaju 2)
    public const CATEGORY_GMI_MIE_WIE = '34'; // Dla gmin miejsko-wiejskich (3)
    public const CATEGORY_MIA         = '341'; // Dla miast w gminach miejsko-wiejskich(4)
    public const CATEGORY_MIA_OBS     = '342'; // Dla obszarów miejskich w gminach miejsko-wiejskich(5)
    public const CATEGORY_MIA_ALL     = '35'; // Dla miast ogółem (identyfikator 1 i 4)
    public const CATEGORY_WIE         = '36'; // Dla terenów wiejskich (identyfikator 2 i 5)

    /**
     * Określenie zakresu miejscowości
     */
    public const SEARCH_CITY_TYPE_ALL  = '000'; //Wszystkie
    public const SEARCH_CITY_TYPE_MAIN = '001'; //Miejscowości podstawowe
    public const SEARCH_CITY_TYPE_ADD  = '002'; //Części integralne miejscowości

    /**
     * @var \mrcnpdlk\Teryt\NativeApi|null
     */
    private static $instance = null;
    /**
     * @var \mrcnpdlk\Teryt\Config
     */
    private $oConfig;

    /**
     * NativeApi constructor.
     *
     * @param \mrcnpdlk\Teryt\Config $configuration
     */
    private function __construct(Config $configuration)
    {
        $this->oConfig = $configuration;
    }

    /**
     * @param \mrcnpdlk\Teryt\Config $configuration
     *
     * @return \mrcnpdlk\Teryt\NativeApi
     */
    public static function create(Config $configuration): NativeApi
    {
        static::$instance = new static($configuration);

        return static::$instance;
    }

    /**
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \mrcnpdlk\Teryt\NativeApi
     */
    public static function getInstance(): NativeApi
    {
        if (null === static::$instance) {
            throw new Exception(sprintf('First call CREATE method!'));
        }

        return static::$instance;
    }

    /**
     * Sprawdzenie czy użytkownik jest zalogowany
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return bool
     */
    public function CzyZalogowany(): bool
    {
        return $this->oConfig->request('CzyZalogowany');
    }

    /**
     * Data początkowa bieżącego stanu katalogu NTS
     *
     * @throws \mrcnpdlk\Teryt\Exception
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     *
     * @return string|null Data w formacie YYY-MM-DD
     */
    public function PobierzDateAktualnegoKatNTS()
    {
        $res = $this->oConfig->request('PobierzDateAktualnegoKatNTS');

        try {
            return (new DateTime($res))->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Data początkowa bieżącego stanu katalogu SIMC
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return string|null Data w formacie YYY-MM-DD
     */
    public function PobierzDateAktualnegoKatSimc()
    {
        $res = $this->oConfig->request('PobierzDateAktualnegoKatSimc');

        try {
            return (new DateTime($res))->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Data początkowa bieżącego stanu katalogu TERC
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return string|null Data w formacie YYY-MM-DD
     */
    public function PobierzDateAktualnegoKatTerc()
    {
        $res = $this->oConfig->request('PobierzDateAktualnegoKatTerc');

        try {
            return (new DateTime($res))->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Data początkowa bieżącego stanu katalogu ULIC
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return string|null Data w formacie YYY-MM-DD
     */
    public function PobierzDateAktualnegoKatUlic()
    {
        $res = $this->oConfig->request('PobierzDateAktualnegoKatUlic');

        try {
            return (new DateTime($res))->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Lista powiatów i gmin we wskazanym województwie
     *
     * @param string $provinceId
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return JednostkaTerytorialna[]
     */
    public function PobierzGminyiPowDlaWoj(string $provinceId): array
    {
        $answer = [];
        $res    = $this->oConfig->request('PobierzGminyiPowDlaWoj', ['Woj' => $provinceId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaTerytorialna') as $p) {
            $answer[] = new JednostkaTerytorialna($p);
        }

        return $answer;
    }

    /**
     * Identyfikatory i nazwy jednostek nomenklatury z wybranego stanu katalogu
     *
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogNTS(): SplFileObject
    {
        $res     = $this->oConfig->request('PobierzKatalogNTS');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Dane o miejscowościach z systemu identyfikatorów SIMC z wybranego stanu katalogu w wersji adresowej
     *
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogSIMC(): SplFileObject
    {
        $res     = $this->oConfig->request('PobierzKatalogSIMC');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Dane o miejscowościach z systemu identyfikatorów SIMC z wybranego stanu katalogu w wersji adresowej
     *
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogSIMCAdr(): SplFileObject
    {
        $res     = $this->oConfig->request('PobierzKatalogSIMCAdr');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Dane o miejscowościach z systemu identyfikatorów SIMC z wybranego stanu katalogu w wersji adresowej
     *
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogSIMCStat(): SplFileObject
    {
        $res     = $this->oConfig->request('PobierzKatalogSIMCStat');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Dane z systemu identyfikatorów TERC z wybranego stanu katalogu w wersji urzędowej
     *
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogTERC(): SplFileObject
    {
        $res     = $this->oConfig->request('PobierzKatalogTERC');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Dane z systemu identyfikatorów TERC z wybranego stanu katalogu w wersji adresowej
     *
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogTERCAdr(): SplFileObject
    {
        $res     = $this->oConfig->request('PobierzKatalogTERCAdr');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Katalog ulic dla wskazanego stanu w wersji urzędowej
     *
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogULIC(): SplFileObject
    {
        $res     = $this->oConfig->request('PobierzKatalogULIC');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Katalog ulic dla wskazanego stanu w wersji adresowej
     *
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogULICAdr(): SplFileObject
    {
        $res     = $this->oConfig->request('PobierzKatalogULICAdr');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Katalog ulic dla wskazanego stanu w wersji urzędowej zmodyfikowany dla miast posiadający delegatury
     *
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogULICBezDzielnic(): SplFileObject
    {
        $res     = $this->oConfig->request('PobierzKatalogULICBezDzielnic');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Katalog rodzajów miejscowości dla wskazanego stanu
     *
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogWMRODZ(): SplFileObject
    {
        $res     = $this->oConfig->request('PobierzKatalogWMRODZ');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Lista gmin we wskazanym powiecie
     *
     * @param string $provinceId
     * @param string $districtId
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return JednostkaTerytorialna[]
     */
    public function PobierzListeGmin(string $provinceId, string $districtId): array
    {
        $answer = [];
        $res    = $this->oConfig->request('PobierzListeGmin', ['Woj' => $provinceId, 'Pow' => $districtId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaTerytorialna') as $p) {
            $answer[] = new JednostkaTerytorialna($p);
        }

        return $answer;
    }

    /**
     * Lista gmin w powiecie
     *
     * @param string $districtId  dwuznakowy symbol powiatu
     * @param string $subregionId dwuznakowy symbol podregionu
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return JednostkaNomenklaturyNTS[]
     */
    public function PobierzListeGminPowiecie(string $districtId, string $subregionId): array
    {
        $answer = [];
        $res    = $this->oConfig->request('PobierzListeGminPowiecie', ['Pow' => $districtId, 'Podreg' => $subregionId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaNomenklaturyNTS') as $p) {
            $answer[] = new JednostkaNomenklaturyNTS($p);
        }

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
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return Miejscowosc[]
     */
    public function PobierzListeMiejscowosciWGminie(string $provinceName, string $districtName, string $communeName): array
    {
        $answer = [];
        $res    = $this->oConfig->request('PobierzListeMiejscowosciWGminie',
            [
                'Wojewodztwo' => $provinceName,
                'Powiat'      => $districtName,
                'Gmina'       => $communeName,
            ]);
        foreach (Helper::getPropertyAsArray($res, 'Miejscowosc') as $p) {
            $answer[] = new Miejscowosc($p);
        }

        return $answer;
    }

    /**
     * Lista miejscowości znajdujących się we wskazanej gminie.
     * Wyszukiwanie odbywa się z uwzględnieniem symboli
     *
     * @param int $tercId
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return Miejscowosc[]
     */
    public function PobierzListeMiejscowosciWRodzajuGminy(int $tercId): array
    {
        $answer = [];
        $oTerc  = new Terc($tercId);
        $res    = $this->oConfig->request('PobierzListeMiejscowosciWRodzajuGminy',
            [
                'symbolWoj'  => $oTerc->getProvinceId(),
                'symbolPow'  => $oTerc->getDistrictId(),
                'symbolGmi'  => $oTerc->getCommuneId(),
                'symbolRodz' => $oTerc->getCommuneTypeId(),
            ]);
        foreach (Helper::getPropertyAsArray($res, 'Miejscowosc') as $p) {
            $answer[] = new Miejscowosc($p);
        }

        return $answer;
    }

    /**
     * Lista podregionów w województwie
     *
     * @param string $provinceId Dwuznakowy symbol województwa
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return JednostkaNomenklaturyNTS[]
     */
    public function PobierzListePodregionow(string $provinceId): array
    {
        $answer = [];
        $res    = $this->oConfig->request('PobierzListePodregionow', ['Woj' => $provinceId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaNomenklaturyNTS') as $p) {
            $answer[] = new JednostkaNomenklaturyNTS($p);
        }

        return $answer;
    }

    /**
     * Pobieranie listy powiatów dla danego województwa
     *
     * @param string $provinceId
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return JednostkaTerytorialna[]
     */
    public function PobierzListePowiatow(string $provinceId): array
    {
        $answer = [];
        $res    = $this->oConfig->request('PobierzListePowiatow', ['Woj' => $provinceId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaTerytorialna') as $p) {
            $answer[] = new JednostkaTerytorialna($p);
        }

        return $answer;
    }

    /**
     * Lista powiatów w podregionie
     *
     * @param string $subregionId Dwuznakowy symbol podregionu
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return JednostkaNomenklaturyNTS[]
     */
    public function PobierzListePowiatowWPodregionie(string $subregionId): array
    {
        $answer = [];
        $res    = $this->oConfig->request('PobierzListePowiatowWPodregionie', ['Podreg' => $subregionId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaNomenklaturyNTS') as $p) {
            $answer[] = new JednostkaNomenklaturyNTS($p);
        }

        return $answer;
    }

    /**
     * Lista regionów
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return JednostkaNomenklaturyNTS[]
     */
    public function PobierzListeRegionow(): array
    {
        $answer = [];
        $res    = $this->oConfig->request('PobierzListeRegionow');
        foreach (Helper::getPropertyAsArray($res, 'JednostkaNomenklaturyNTS') as $p) {
            $answer[] = new JednostkaNomenklaturyNTS($p);
        }

        return $answer;
    }

    /**
     * Lista ulic we wskazanej miejscowości
     *
     * @param int    $tercId
     * @param string $cityId
     * @param bool   $asAddress
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return UlicaDrzewo[]
     */
    public function PobierzListeUlicDlaMiejscowosci(int $tercId, string $cityId, bool $asAddress = false): array
    {
        $answer = [];
        $oTerc  = new Terc($tercId);
        $res    = $this->oConfig->request('PobierzListeUlicDlaMiejscowosci',
            [
                'woj'               => $oTerc->getProvinceId(),
                'pow'               => $oTerc->getDistrictId(),
                'gmi'               => $oTerc->getCommuneId(),
                'rodzaj'            => $oTerc->getCommuneTypeId(),
                'msc'               => $cityId,
                'czyWersjaUrzedowa' => !$asAddress,
                'czyWersjaAdresowa' => $asAddress,
            ]
        );
        foreach (Helper::getPropertyAsArray($res, 'UlicaDrzewo') as $p) {
            $answer[] = new UlicaDrzewo($p);
        }

        return $answer;
    }

    /**
     * Lista województw
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return JednostkaTerytorialna[]
     */
    public function PobierzListeWojewodztw(): array
    {
        $answer = [];
        $res    = $this->oConfig->request('PobierzListeWojewodztw');
        foreach (Helper::getPropertyAsArray($res, 'JednostkaTerytorialna') as $p) {
            $answer[] = new JednostkaTerytorialna($p);
        }

        return $answer;
    }

    /**
     * Lista województw regionie
     *
     * @param string $regionId Jednoznakowy symbol regionu
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return JednostkaNomenklaturyNTS[]
     */
    public function PobierzListeWojewodztwWRegionie(string $regionId): array
    {
        $answer = [];
        $res    = $this->oConfig->request('PobierzListeWojewodztwWRegionie', ['Reg' => $regionId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaNomenklaturyNTS') as $p) {
            $answer[] = new JednostkaNomenklaturyNTS($p);
        }

        return $answer;
    }

    /**
     * Zwraca listę cech obiektów z katalogu ulic
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return string[]
     */
    public function PobierzSlownikCechULIC(): array
    {
        $res = $this->oConfig->request('PobierzSlownikCechULIC');

        return Helper::getPropertyAsArray($res, 'string');
    }

    /**
     * Zwraca listę rodzajów jednostek
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return string[]
     */
    public function PobierzSlownikRodzajowJednostek(): array
    {
        $res = $this->oConfig->request('PobierzSlownikRodzajowJednostek');

        return Helper::getPropertyAsArray($res, 'string');
    }

    /**
     * Zwraca listę rodzajów miejscowości
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return RodzajMiejscowosci[]
     */
    public function PobierzSlownikRodzajowSIMC(): array
    {
        $answer = [];
        $res    = $this->oConfig->request('PobierzSlownikRodzajowSIMC');
        foreach (Helper::getPropertyAsArray($res, 'RodzajMiejscowosci') as $p) {
            $answer[] = new RodzajMiejscowosci($p);
        }

        return $answer;
    }

    /**
     * Zmiany w katalogu TERC w wersji adresowej rejestru
     *
     * @param \DateTime      $fromDate
     * @param \DateTime|null $toDate
     *
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzZmianyNTS(DateTime $fromDate, DateTime $toDate = null): SplFileObject
    {
        $toDate  = $toDate ?? new DateTime();
        $res     = $this->oConfig->request(
            'PobierzZmianyNTS',
            [
                'stanod' => $fromDate->format('Y-m-d'),
                'stando' => $toDate->format('Y-m-d'),
            ],
            false);
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
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzZmianySimcAdresowy(DateTime $fromDate, DateTime $toDate = null): SplFileObject
    {
        $toDate  = $toDate ?? new DateTime();
        $res     = $this->oConfig->request(
            'PobierzZmianySimcAdresowy',
            [
                'stanod' => $fromDate->format('Y-m-d'),
                'stando' => $toDate->format('Y-m-d'),
            ],
            false);
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
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzZmianySimcStatystyczny(DateTime $fromDate, DateTime $toDate = null): SplFileObject
    {
        $toDate  = $toDate ?? new DateTime();
        $res     = $this->oConfig->request(
            'PobierzZmianySimcStatystyczny',
            [
                'stanod' => $fromDate->format('Y-m-d'),
                'stando' => $toDate->format('Y-m-d'),
            ],
            false);
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
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzZmianySimcUrzedowy(DateTime $fromDate, DateTime $toDate = null): SplFileObject
    {
        $toDate  = $toDate ?? new DateTime();
        $res     = $this->oConfig->request(
            'PobierzZmianySimcUrzedowy',
            [
                'stanod' => $fromDate->format('Y-m-d'),
                'stando' => $toDate->format('Y-m-d'),
            ],
            false);
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
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzZmianyTercAdresowy(DateTime $fromDate, DateTime $toDate = null): SplFileObject
    {
        $toDate  = $toDate ?? new DateTime();
        $res     = $this->oConfig->request(
            'PobierzZmianyTercAdresowy',
            [
                'stanod' => $fromDate->format('Y-m-d'),
                'stando' => $toDate->format('Y-m-d'),
            ],
            false);
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Zmiany w katalogu TERC w wersji urzędowej rejestru
     *
     * @param \DateTime      $fromDate
     * @param \DateTime|null $toDate
     *
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzZmianyTercUrzedowy(DateTime $fromDate, DateTime $toDate = null): SplFileObject
    {
        $toDate  = $toDate ?? new DateTime();
        $res     = $this->oConfig->request(
            'PobierzZmianyTercUrzedowy',
            [
                'stanod' => $fromDate->format('Y-m-d'),
                'stando' => $toDate->format('Y-m-d'),
            ],
            false);
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
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzZmianyUlicAdresowy(DateTime $fromDate, DateTime $toDate = null): SplFileObject
    {
        $toDate  = $toDate ?? new DateTime();
        $res     = $this->oConfig->request(
            'PobierzZmianyUlicAdresowy',
            [
                'stanod' => $fromDate->format('Y-m-d'),
                'stando' => $toDate->format('Y-m-d'),
            ],
            false);
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
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return \SplFileObject
     */
    public function PobierzZmianyUlicUrzedowy(DateTime $fromDate, DateTime $toDate = null): SplFileObject
    {
        $toDate  = $toDate ?? new DateTime();
        $res     = $this->oConfig->request(
            'PobierzZmianyUlicUrzedowy',
            [
                'stanod' => $fromDate->format('Y-m-d'),
                'stando' => $toDate->format('Y-m-d'),
            ],
            false);
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT do poziomu
     * miejscowości. Weryfikacja odbywa się za pomoca identyfikatorów
     *
     * @param string $cityId
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception\NotFound
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return ZweryfikowanyAdresBezUlic
     */
    public function WeryfikujAdresDlaMiejscowosci(string $cityId): ZweryfikowanyAdresBezUlic
    {
        $res   = $this->oConfig->request('WeryfikujAdresDlaMiejscowosci', ['symbolMsc' => $cityId]);
        $oData = Helper::getPropertyAsObject($res, 'ZweryfikowanyAdresBezUlic');

        return new ZweryfikowanyAdresBezUlic($oData);
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT,w wersji
     * adresowej rejestru do poziomu miejscowości. Weryfikacja odbywa się za pomoca identyfikatorów
     *
     * @param string $cityId
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception\NotFound
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return ZweryfikowanyAdresBezUlic
     */
    public function WeryfikujAdresDlaMiejscowosciAdresowy(string $cityId): ZweryfikowanyAdresBezUlic
    {
        $res   = $this->oConfig->request('WeryfikujAdresDlaMiejscowosciAdresowy', ['symbolMsc' => $cityId]);
        $oData = Helper::getPropertyAsObject($res, 'ZweryfikowanyAdresBezUlic');

        return new ZweryfikowanyAdresBezUlic($oData);
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie
     * TERYT do poziomu ulic.Weryfikacja odbywa się za pomoca nazw
     *
     * @param string $cityId
     * @param string $streetId
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception\NotFound
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return ZweryfikowanyAdres
     */
    public function WeryfikujAdresDlaUlic(string $cityId, string $streetId): ZweryfikowanyAdres
    {
        $res   = $this->oConfig->request('WeryfikujAdresDlaUlic', ['symbolMsc' => $cityId, 'SymUl' => $streetId]);
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
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception\NotFound
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return ZweryfikowanyAdres
     */
    public function WeryfikujAdresDlaUlicAdresowy(string $cityId, string $streetId): ZweryfikowanyAdres
    {
        $res   = $this->oConfig->request('WeryfikujAdresDlaUlicAdresowy', ['symbolMsc' => $cityId, 'SymUl' => $streetId]);
        $oData = Helper::getPropertyAsObject($res, 'ZweryfikowanyAdres');

        return new ZweryfikowanyAdres($oData);
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
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return ZweryfikowanyAdresBezUlic[]
     */
    public function WeryfikujAdresWmiejscowosci(
        string $provinceName,
        string $districtName,
        string $communeName,
        string $cityName,
        string $cityTypeName = null
    ): array {
        $answer = [];
        $res    = $this->oConfig->request('WeryfikujAdresWmiejscowosci',
            [
                'Wojewodztwo' => $provinceName,
                'Powiat'      => $districtName,
                'Gmina'       => $communeName,
                'Miejscowosc' => $cityName,
                'Rodzaj'      => $cityTypeName,
            ]);
        foreach (Helper::getPropertyAsArray($res, 'ZweryfikowanyAdresBezUlic') as $p) {
            $answer[] = new ZweryfikowanyAdresBezUlic($p);
        }

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
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return ZweryfikowanyAdresBezUlic[]
     */
    public function WeryfikujAdresWmiejscowosciAdresowy(
        string $provinceName,
        string $districtName,
        string $communeName,
        string $cityName,
        string $cityTypeName = null
    ): array {
        $answer = [];
        $res    = $this->oConfig->request('WeryfikujAdresWmiejscowosciAdresowy',
            [
                'Wojewodztwo' => $provinceName,
                'Powiat'      => $districtName,
                'Gmina'       => $communeName,
                'Miejscowosc' => $cityName,
                'Rodzaj'      => $cityTypeName,
            ]);
        foreach (Helper::getPropertyAsArray($res, 'ZweryfikowanyAdresBezUlic') as $p) {
            $answer[] = new ZweryfikowanyAdresBezUlic($p);
        }

        return $answer;
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
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return ZweryfikowanyAdres[]
     *
     * @todo empty response
     */
    public function WeryfikujNazwaAdresUlic(
        string $provinceName,
        string $districtName,
        string $communeName,
        string $cityName,
        string $cityTypeName = null,
        string $streetName
    ): array {
        $answer = [];
        $res    = $this->oConfig->request('WeryfikujNazwaAdresUlic',
            [
                'nazwaWoj'         => $provinceName,
                'nazwaPow'         => $districtName,
                'nazwaGmi'         => $communeName,
                'nazwaMiejscowosc' => $cityName,
                'rodzajMiejsc'     => $cityTypeName,
                'nazwaUlicy'       => $streetName,
            ]);

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
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return ZweryfikowanyAdres[]
     */
    public function WeryfikujNazwaAdresUlicAdresowy(
        string $provinceName,
        string $districtName,
        string $communeName,
        string $cityName,
        string $cityTypeName = null,
        string $streetName
    ): array {
        $answer = [];
        $res    = $this->oConfig->request('WeryfikujNazwaAdresUlicAdresowy',
            [
                'nazwaWoj'         => $provinceName,
                'nazwaPow'         => $districtName,
                'nazwaGmi'         => $communeName,
                'nazwaMiejscowosc' => $cityName,
                'rodzajMiejsc'     => $cityTypeName,
                'nazwaUlicy'       => $streetName,
            ]);

        $tData = Helper::getPropertyAsArray($res, 'ZweryfikowanyAdres');

        foreach ($tData as $datum) {
            $answer[] = new ZweryfikowanyAdres($datum);
        }

        return $answer;
    }

    /**
     * Zwraca listę znalezionych jednostek w katalagu TERC
     *
     * @param string $name
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return JednostkaPodzialuTerytorialnego[]
     *
     * @todo Metoda zwraca 0 wyników
     */
    public function WyszukajJPT(string $name): array
    {
        $answer = [];
        $res    = $this->oConfig->request('WyszukajJPT', ['nazwa' => $name], false);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaPodzialuTerytorialnego') as $p) {
            $answer[] = new JednostkaPodzialuTerytorialnego($p);
        }

        return $answer;
    }

    /**
     * Zwraca listę znalezionych jednostek w katalagu TERC
     *
     * @param string|null $name     zawiera nazwę wyszukiwanej jednostki
     * @param string|null $category określa kategorię wyszukiwanych jednostek
     * @param string[]    $tSimc    lista identyfikatorów SIMC (cityId)
     * @param string[]    $tTerc    lista identyfikatorów TERC (tercId)
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return JednostkaPodzialuTerytorialnego[]
     */
    public function WyszukajJednostkeWRejestrze(
        string $name = null,
        string $category = null,
        array $tSimc = [],
        array $tTerc = []
    ): array {
        $answer     = [];
        $identyfiks = [];
        foreach ($tSimc as $simc) {
            $identyfiks[] = ['simc' => $simc];
        }
        foreach ($tTerc as $terc) {
            $identyfiks[] = ['terc' => $terc];
        }
        $res = $this->oConfig->request('WyszukajJednostkeWRejestrze',
            [
                'nazwa'      => $name,
                'kategoria'  => $category ?? self::CATEGORY_ALL,
                'identyfiks' => $identyfiks,
            ]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaPodzialuTerytorialnego') as $p) {
            $answer[] = new JednostkaPodzialuTerytorialnego($p);
        }

        return $answer;
    }

    /**
     * Zwaraca listę znalezionych miejscowości w katalogu SIMC.
     *
     * @param string|null $cityName
     * @param string|null $cityId
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return Miejscowosc[]
     */
    public function WyszukajMiejscowosc(string $cityName = null, string $cityId = null): array
    {
        $answer = [];
        $res    = $this->oConfig->request('WyszukajMiejscowosc',
            ['nazwaMiejscowosci' => $cityName, 'identyfikatorMiejscowosci' => $cityId]);
        foreach (Helper::getPropertyAsArray($res, 'Miejscowosc') as $p) {
            $answer[] = new Miejscowosc($p);
        }

        return $answer;
    }

    /**
     * Zwaraca listę znalezionych miejscowości we wskazanej
     * jednostce podziału terytorialnego
     *
     * @param string $provinceName
     * @param string $districtName
     * @param string $communeName
     * @param string $cityName
     * @param string $cityId
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return Miejscowosc[]
     */
    public function WyszukajMiejscowoscWJPT(
        string $provinceName,
        string $districtName,
        string $communeName,
        string $cityName,
        string $cityId = null
    ): array {
        $answer = [];
        /**
         * @var \stdClass|null
         */
        $res = $this->oConfig->request('WyszukajMiejscowoscWJPT',
            [
                'nazwaWoj'                  => $provinceName,
                'nazwaPow'                  => $districtName,
                'nazwaGmi'                  => $communeName,
                'nazwaMiejscowosci'         => $cityName,
                'identyfikatorMiejscowosci' => $cityId,
            ]);
        foreach (Helper::getPropertyAsArray($res, 'Miejscowosc') as $p) {
            $answer[] = new Miejscowosc($p);
        }

        return $answer;
    }

    /**
     * Zwaraca listę znalezionych miejscowości we wskazanej jednostcepodziału terytorialnego
     *
     * @param string|null $name         Nazwa miejscowości
     * @param string|null $cityId       ID miejscowości
     * @param string[]    $tSimc        Lista cityId w których szukamy
     * @param string[]    $tTerc        Lista tercId w których szukamy
     * @param string      $cityTypeName Predefiniowany typ wyszukiwania ('000','001','002') stałe: SEARCH_CITY_TYPE_*
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return WyszukanaMiejscowosc[]
     */
    public function WyszukajMiejscowoscWRejestrze(
        string $name = null,
        string $cityId = null,
        array $tSimc = [],
        array $tTerc = [],
        string $cityTypeName = NativeApi::SEARCH_CITY_TYPE_ALL
    ): array {
        $answer     = [];
        $identyfiks = [];
        foreach ($tSimc as $simc) {
            $identyfiks[] = ['simc' => $simc];
        }
        foreach ($tTerc as $terc) {
            $identyfiks[] = ['terc' => $terc];
        }
        $res = $this->oConfig->request('WyszukajMiejscowoscWRejestrze',
            [
                'nazwa'              => $name,
                'rodzajMiejscowosci' => $cityTypeName,
                'symbol'             => $cityId,
                'identyfiks'         => $identyfiks,
            ]);
        foreach (Helper::getPropertyAsArray($res, 'WyszukanaMiejscowosc') as $p) {
            $answer[] = new WyszukanaMiejscowosc($p);
        }

        return $answer;
    }

    /**
     * Wyszukuje wskazaną ulicę w katalogu ULIC. Wyszukiwanie odbywa się za pomoca nazw
     *
     * @param string|null $streetName
     * @param string|null $streetIdentityName
     * @param string|null $cityName
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return Ulica[]
     */
    public function WyszukajUlice(string $streetName = null, string $streetIdentityName = null, string $cityName = null): array
    {
        $answer = [];
        $res    = $this->oConfig->request('WyszukajUlice',
            [
                'nazwaulicy'        => $streetName,
                'cecha'             => $streetIdentityName,
                'nazwamiejscowosci' => $cityName,
            ]);
        foreach (Helper::getPropertyAsArray($res, 'Ulica') as $p) {
            $answer[] = new Ulica($p);
        }

        return $answer;
    }

    /**
     * Wyszukuje wskazaną ulicę w katalogu ULIC
     *
     * @param string|null       $name         Nazwa ulicy
     * @param string            $identityName Nazwa cechy, wymagane
     * @param string|null       $streetId     ID ulicy
     * @param array<string,int> $tSimc        Lista cityId w których szukamy
     * @param array<string,int> $tTerc        Lista tercId w których szukamy
     *
     * @throws \mrcnpdlk\Teryt\Exception\Connection
     * @throws \mrcnpdlk\Teryt\Exception
     *
     * @return WyszukanaUlica[]
     */
    public function WyszukajUliceWRejestrze(
        string $name = null,
        string $identityName = 'ul.',
        string $streetId = null,
        array $tSimc = [],
        array $tTerc = []
    ): array {
        $answer     = [];
        $identyfiks = [];
        foreach ($tSimc as $simc) {
            $identyfiks[] = ['simc' => $simc];
        }
        foreach ($tTerc as $terc) {
            $identyfiks[] = ['terc' => $terc];
        }
        $res = $this->oConfig->request('WyszukajUliceWRejestrze',
            [
                'nazwa'         => $name,
                'cecha'         => $identityName,
                'identyfikator' => $streetId,
                'identyfiks'    => $identyfiks,
            ]);
        foreach (Helper::getPropertyAsArray($res, 'WyszukanaUlica') as $p) {
            $answer[] = new WyszukanaUlica($p);
        }

        return $answer;
    }
}
