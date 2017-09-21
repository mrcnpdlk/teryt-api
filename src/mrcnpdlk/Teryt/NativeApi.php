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
 * Date: 11.09.2017
 * Time: 23:01
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


final class NativeApi
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
     * @var \mrcnpdlk\Teryt\NativeApi|null
     */
    protected static $instance = null;
    /**
     * @var Client
     */
    protected $oClient;

    /**
     * NativeApi constructor.
     *
     * @param \mrcnpdlk\Teryt\Client $oClient
     */
    protected function __construct(Client $oClient)
    {
        $this->oClient = $oClient;
    }

    /**
     * @param \mrcnpdlk\Teryt\Client $oClient
     *
     * @return \mrcnpdlk\Teryt\NativeApi
     */
    public static function create(Client $oClient)
    {
        static::$instance = new static($oClient);

        return static::$instance;
    }

    /**
     * @return \mrcnpdlk\Teryt\NativeApi
     * @throws \mrcnpdlk\Teryt\Exception
     */
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            throw new Exception(sprintf('First call CREATE method!'));
        }

        return static::$instance;
    }

    /**
     * Sprawdzenie czy użytkownik jest zalogowany
     *
     * @return bool
     */
    public function CzyZalogowany()
    {
        $res = $this->oClient->request('CzyZalogowany');

        return $res;
    }

    /**
     * Data początkowa bieżącego stanu katalogu TERC
     *
     * @return null|string Data w formacie YYY-MM-DD
     */
    public function PobierzDateAktualnegoKatTerc()
    {
        $res = $this->oClient->request('PobierzDateAktualnegoKatTerc');

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
    public function PobierzDateAktualnegoKatNTS()
    {
        $res = $this->oClient->request('PobierzDateAktualnegoKatNTS');

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
    public function PobierzDateAktualnegoKatSimc()
    {
        $res = $this->oClient->request('PobierzDateAktualnegoKatSimc');

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
    public function PobierzDateAktualnegoKatUlic()
    {
        $res = $this->oClient->request('PobierzDateAktualnegoKatUlic');

        try {
            return (new \DateTime($res))->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Dane z systemu identyfikatorów TERC z wybranego stanu katalogu w wersji adresowej
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogTERCAdr(): \SplFileObject
    {
        $res     = $this->oClient->request('PobierzKatalogTERCAdr');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Dane z systemu identyfikatorów TERC z wybranego stanu katalogu w wersji urzędowej
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogTERC(): \SplFileObject
    {
        $res     = $this->oClient->request('PobierzKatalogTERC');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Identyfikatory i nazwy jednostek nomenklatury z wybranego stanu katalogu
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogNTS(): \SplFileObject
    {
        $res     = $this->oClient->request('PobierzKatalogNTS');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Dane o miejscowościach z systemu identyfikatorów SIMC z wybranego stanu katalogu w wersji adresowej
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogSIMCAdr(): \SplFileObject
    {
        $res     = $this->oClient->request('PobierzKatalogSIMCAdr');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Dane o miejscowościach z systemu identyfikatorów SIMC z wybranego stanu katalogu w wersji adresowej
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogSIMC(): \SplFileObject
    {
        $res     = $this->oClient->request('PobierzKatalogSIMC');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Dane o miejscowościach z systemu identyfikatorów SIMC z wybranego stanu katalogu w wersji adresowej
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogSIMCStat(): \SplFileObject
    {
        $res     = $this->oClient->request('PobierzKatalogSIMCStat');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Katalog ulic dla wskazanego stanu w wersji urzędowej
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogULIC(): \SplFileObject
    {
        $res     = $this->oClient->request('PobierzKatalogULIC');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Katalog ulic dla wskazanego stanu w wersji adresowej
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogULICAdr(): \SplFileObject
    {
        $res     = $this->oClient->request('PobierzKatalogULICAdr');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Katalog ulic dla wskazanego stanu w wersji urzędowej zmodyfikowany dla miast posiadający delegatury
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogULICBezDzielnic(): \SplFileObject
    {
        $res     = $this->oClient->request('PobierzKatalogULICBezDzielnic');
        $sPath   = sprintf('%s/%s.zip', sys_get_temp_dir(), $res->nazwa_pliku);
        $content = base64_decode($res->plik_zawartosc);

        return Helper::saveFile($sPath, $content);
    }

    /**
     * Katalog rodzajów miejscowości dla wskazanego stanu
     *
     * @return \SplFileObject
     */
    public function PobierzKatalogWMRODZ(): \SplFileObject
    {
        $res     = $this->oClient->request('PobierzKatalogWMRODZ');
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
     * @return \SplFileObject
     */
    public function PobierzZmianyTercUrzedowy(\DateTime $fromDate, \DateTime $toDate = null): \SplFileObject
    {
        $toDate  = $toDate ?? new \DateTime();
        $res     = $this->oClient->request(
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
     * Zmiany w katalogu TERC w wersji adresowej rejestru
     *
     * @param \DateTime      $fromDate
     * @param \DateTime|null $toDate
     *
     * @return \SplFileObject
     */
    public function PobierzZmianyTercAdresowy(\DateTime $fromDate, \DateTime $toDate = null): \SplFileObject
    {
        $toDate  = $toDate ?? new \DateTime();
        $res     = $this->oClient->request(
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
     * Zmiany w katalogu TERC w wersji adresowej rejestru
     *
     * @param \DateTime      $fromDate
     * @param \DateTime|null $toDate
     *
     * @return \SplFileObject
     */
    public function PobierzZmianyNTS(\DateTime $fromDate, \DateTime $toDate = null): \SplFileObject
    {
        $toDate  = $toDate ?? new \DateTime();
        $res     = $this->oClient->request(
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
     * Zmiany w katalogu SIMC w wersji urzędowej rejestru
     *
     * @param \DateTime      $fromDate
     * @param \DateTime|null $toDate
     *
     * @return \SplFileObject
     */
    public function PobierzZmianySimcUrzedowy(\DateTime $fromDate, \DateTime $toDate = null): \SplFileObject
    {
        $toDate  = $toDate ?? new \DateTime();
        $res     = $this->oClient->request(
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
     * Zmiany w katalogu SIMC w wersji adresowej rejestru
     *
     * @param \DateTime      $fromDate
     * @param \DateTime|null $toDate
     *
     * @return \SplFileObject
     */
    public function PobierzZmianySimcAdresowy(\DateTime $fromDate, \DateTime $toDate = null): \SplFileObject
    {
        $toDate  = $toDate ?? new \DateTime();
        $res     = $this->oClient->request(
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
     * @return \SplFileObject
     */
    public function PobierzZmianySimcStatystyczny(\DateTime $fromDate, \DateTime $toDate = null): \SplFileObject
    {
        $toDate  = $toDate ?? new \DateTime();
        $res     = $this->oClient->request(
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
     * Zmiany w katalogu ULIC w wersji urzędowej rejestru
     *
     * @param \DateTime      $fromDate
     * @param \DateTime|null $toDate
     *
     * @return \SplFileObject
     */
    public function PobierzZmianyUlicUrzedowy(\DateTime $fromDate, \DateTime $toDate = null): \SplFileObject
    {
        $toDate  = $toDate ?? new \DateTime();
        $res     = $this->oClient->request(
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
     * Zmiany w katalogu ULIC w wersji adresowej rejestru
     *
     * @param \DateTime      $fromDate
     * @param \DateTime|null $toDate
     *
     * @return \SplFileObject
     */
    public function PobierzZmianyUlicAdresowy(\DateTime $fromDate, \DateTime $toDate = null): \SplFileObject
    {
        $toDate  = $toDate ?? new \DateTime();
        $res     = $this->oClient->request(
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
     * Zwraca listę rodzajów jednostek
     *
     * @return string[]
     */
    public function PobierzSlownikRodzajowJednostek()
    {
        $res = $this->oClient->request('PobierzSlownikRodzajowJednostek');

        return Helper::getPropertyAsArray($res, 'string');
    }

    /**
     * Zwraca listę rodzajów miejscowości
     *
     * @return RodzajMiejscowosci[]
     */
    public function PobierzSlownikRodzajowSIMC()
    {
        $answer = [];
        $res    = $this->oClient->request('PobierzSlownikRodzajowSIMC');
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
    public function PobierzSlownikCechULIC()
    {
        $res = $this->oClient->request('PobierzSlownikCechULIC');

        return Helper::getPropertyAsArray($res, 'string');
    }

    /**
     * Lista regionów
     *
     * @return JednostkaNomenklaturyNTS[]
     */
    public function PobierzListeRegionow()
    {
        $answer = [];
        $res    = $this->oClient->request('PobierzListeRegionow');
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
    public function PobierzListeWojewodztwWRegionie(string $regionId)
    {
        $answer = [];
        $res    = $this->oClient->request('PobierzListeWojewodztwWRegionie', ['Reg' => $regionId]);
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
    public function PobierzListePodregionow(string $provinceId)
    {
        $answer = [];
        $res    = $this->oClient->request('PobierzListePodregionow', ['Woj' => $provinceId]);
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
    public function PobierzListePowiatowWPodregionie(string $subregionId)
    {
        $answer = [];
        $res    = $this->oClient->request('PobierzListePowiatowWPodregionie', ['Podreg' => $subregionId]);
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
    public function PobierzListeGminPowiecie(string $districtId, string $subregionId)
    {
        $answer = [];
        $res    = $this->oClient->request('PobierzListeGminPowiecie', ['Pow' => $districtId, 'Podreg' => $subregionId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaNomenklaturyNTS') as $p) {
            $answer[] = new JednostkaNomenklaturyNTS($p);
        };

        return $answer;
    }

    /**
     * Zwraca listę znalezionych jednostek w katalagu TERC
     *
     * @param string $name
     *
     * @return JednostkaPodzialuTerytorialnego[]
     * @todo Metoda zwraca 0 wyników
     */
    public function WyszukajJPT(string $name)
    {
        $answer = [];
        $res    = $this->oClient->request('WyszukajJPT', ['nazwa' => $name], false);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaPodzialuTerytorialnego') as $p) {
            $answer[] = new JednostkaPodzialuTerytorialnego($p);
        };

        return $answer;
    }

    /**
     * Zwaraca listę znalezionych miejscowości w katalogu SIMC.
     *
     * @param string|null $cityName
     * @param string|null $cityId
     *
     * @return Miejscowosc[]
     */
    public function WyszukajMiejscowosc(string $cityName = null, string $cityId = null)
    {
        $answer = [];
        $res    = $this->oClient->request('WyszukajMiejscowosc', ['nazwaMiejscowosci' => $cityName, 'identyfikatorMiejscowosci' => $cityId]);
        foreach (Helper::getPropertyAsArray($res, 'Miejscowosc') as $p) {
            $answer[] = new Miejscowosc($p);
        };

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
     * @return Miejscowosc[]
     */
    public function WyszukajMiejscowoscWJPT(
        string $provinceName,
        string $districtName,
        string $communeName,
        string $cityName,
        string $cityId = null
    ) {
        $answer = [];
        /**
         * @var \stdClass|null $res
         */
        $res = $this->oClient->request('WyszukajMiejscowoscWJPT',
            [
                'nazwaWoj'                  => $provinceName,
                'nazwaPow'                  => $districtName,
                'nazwaGmi'                  => $communeName,
                'nazwaMiejscowosci'         => $cityName,
                'identyfikatorMiejscowosci' => $cityId,
            ]);
        foreach (Helper::getPropertyAsArray($res, 'Miejscowosc') as $p) {
            $answer[] = new Miejscowosc($p);
        };

        return $answer;
    }

    /**
     * Wyszukuje wskazaną ulicę w katalogu ULIC. Wyszukiwanie
     * odbywa się za pomoca nazw
     *
     * @param string|null $streetName
     * @param string|null $streetIdentityName
     * @param string|null $cityName
     *
     * @return Ulica[]
     */
    public function WyszukajUlice(string $streetName = null, string $streetIdentityName = null, string $cityName = null)
    {
        $answer = [];
        $res    = $this->oClient->request('WyszukajUlice',
            [
                'nazwaulicy'        => $streetName,
                'cecha'             => $streetIdentityName,
                'nazwamiejscowosci' => $cityName,
            ]);
        foreach (Helper::getPropertyAsArray($res, 'Ulica') as $p) {
            $answer[] = new Ulica($p);
        };

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
     * @return JednostkaPodzialuTerytorialnego[]
     */
    public function WyszukajJednostkeWRejestrze(
        string $name = null,
        string $category = null,
        array $tSimc = [],
        array $tTerc = []
    ) {
        $answer     = [];
        $identyfiks = [];
        foreach ($tSimc as $simc) {
            $identyfiks[] = ['simc' => $simc];
        }
        foreach ($tTerc as $terc) {
            $identyfiks[] = ['terc' => $terc];
        }
        $res = $this->oClient->request('WyszukajJednostkeWRejestrze',
            [
                'nazwa'      => $name,
                'kategoria'  => $category ?? NativeApi::CATEGORY_ALL,
                'identyfiks' => $identyfiks,
            ]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaPodzialuTerytorialnego') as $p) {
            $answer[] = new JednostkaPodzialuTerytorialnego($p);
        };

        return $answer;
    }

    /**
     * Zwaraca listę znalezionych miejscowości we wskazanej
     * jednostcepodziału terytorialnego
     *
     * @param string|null $name         Nazwa miejscowości
     * @param string|null $cityId       ID miejscowości
     * @param array       $tSimc        Lista cityId w których szukamy
     * @param array       $tTerc        Lista tercId w których szukamy
     * @param string      $cityTypeName Predefinowany typ wyszukiwania ('000','001','002')
     *
     * @return WyszukanaMiejscowosc[]
     */
    public function WyszukajMiejscowoscWRejestrze(
        string $name = null,
        string $cityId = null,
        array $tSimc = [],
        array $tTerc = [],
        string $cityTypeName = NativeApi::SEARCH_CITY_TYPE_ALL
    ) {
        $answer     = [];
        $identyfiks = [];
        foreach ($tSimc as $simc) {
            $identyfiks[] = ['simc' => $simc];
        }
        foreach ($tTerc as $terc) {
            $identyfiks[] = ['terc' => $terc];
        }
        $res = $this->oClient->request('WyszukajMiejscowoscWRejestrze',
            [
                'nazwa'              => $name,
                'rodzajMiejscowosci' => $cityTypeName,
                'symbol'             => $cityId,
                'identyfiks'         => $identyfiks,
            ]);
        foreach (Helper::getPropertyAsArray($res, 'WyszukanaMiejscowosc') as $p) {
            $answer[] = new WyszukanaMiejscowosc($p);
        };

        return $answer;
    }

    /**
     * Wyszukuje wskazaną ulicę w katalogu ULIC
     *
     * @param string|null $name         Nazwa ulicy
     * @param string      $identityName Nazwa cechy, wymagane
     * @param string|null $streetId     ID ulicy
     * @param array       $tSimc        Lista cityId w których szukamy
     * @param array       $tTerc        Lista tercId w których szukamy
     *
     * @return WyszukanaUlica[]
     */
    public function WyszukajUliceWRejestrze(
        string $name = null,
        string $identityName = 'ul.',
        string $streetId = null,
        array $tSimc = [],
        array $tTerc = []
    ) {
        $answer     = [];
        $identyfiks = [];
        foreach ($tSimc as $simc) {
            $identyfiks[] = ['simc' => $simc];
        }
        foreach ($tTerc as $terc) {
            $identyfiks[] = ['terc' => $terc];
        }
        $res = $this->oClient->request('WyszukajUliceWRejestrze',
            [
                'nazwa'         => $name,
                'cecha'         => $identityName,
                'identyfikator' => $streetId,
                'identyfiks'    => $identyfiks,
            ]);
        foreach (Helper::getPropertyAsArray($res, 'WyszukanaUlica') as $p) {
            $answer[] = new WyszukanaUlica($p);
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
    public function PobierzListeMiejscowosciWGminie(string $provinceName, string $districtName, string $communeName)
    {
        $answer = [];
        $res    = $this->oClient->request('PobierzListeMiejscowosciWGminie',
            [
                'Wojewodztwo' => $provinceName,
                'Powiat'      => $districtName,
                'Gmina'       => $communeName,
            ]);
        foreach (Helper::getPropertyAsArray($res, 'Miejscowosc') as $p) {
            $answer[] = new Miejscowosc($p);
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
    public function PobierzListeMiejscowosciWRodzajuGminy(int $tercId)
    {
        $answer = [];
        $oTerc  = new Terc($tercId);
        $res    = $this->oClient->request('PobierzListeMiejscowosciWRodzajuGminy',
            [
                'symbolWoj'  => $oTerc->getProvinceId(),
                'symbolPow'  => $oTerc->getDistrictId(),
                'symbolGmi'  => $oTerc->getCommuneId(),
                'symbolRodz' => $oTerc->getCommuneTypeId(),
            ]);
        foreach (Helper::getPropertyAsArray($res, 'Miejscowosc') as $p) {
            $answer[] = new Miejscowosc($p);
        };

        return $answer;
    }

    /**
     * Lista województw
     *
     * @return JednostkaTerytorialna[]
     */
    public function PobierzListeWojewodztw()
    {
        $answer = [];
        $res    = $this->oClient->request('PobierzListeWojewodztw');
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
    public function PobierzListePowiatow(string $provinceId)
    {
        $answer = [];
        $res    = $this->oClient->request('PobierzListePowiatow', ['Woj' => $provinceId]);
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
    public function PobierzListeGmin(string $provinceId, string $districtId)
    {
        $answer = [];
        $res    = $this->oClient->request('PobierzListeGmin', ['Woj' => $provinceId, 'Pow' => $districtId]);
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
    public function PobierzGminyiPowDlaWoj(string $provinceId)
    {
        $answer = [];
        $res    = $this->oClient->request('PobierzGminyiPowDlaWoj', ['Woj' => $provinceId]);
        foreach (Helper::getPropertyAsArray($res, 'JednostkaTerytorialna') as $p) {
            $answer[] = new JednostkaTerytorialna($p);
        };

        return $answer;
    }

    /**
     * Lista ulic we wskazanej miejscowości
     *
     * @param int    $tercId
     * @param string $cityId
     * @param bool   $asAddress
     *
     * @return UlicaDrzewo[]
     */
    public function PobierzListeUlicDlaMiejscowosci(int $tercId, string $cityId, bool $asAddress = false)
    {
        $answer = [];
        $oTerc  = new Terc($tercId);
        $res    = $this->oClient->request('PobierzListeUlicDlaMiejscowosci',
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
        };

        return $answer;
    }

    /**
     * Weryfikuje istnienie wskazanego obiektu w bazie TERYT do poziomu
     * miejscowości. Weryfikacja odbywa się za pomoca identyfikatorów
     *
     * @param string $cityId
     *
     * @return ZweryfikowanyAdresBezUlic
     */
    public function WeryfikujAdresDlaMiejscowosci(string $cityId)
    {
        $res   = $this->oClient->request('WeryfikujAdresDlaMiejscowosci', ['symbolMsc' => $cityId]);
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
    public function WeryfikujAdresDlaMiejscowosciAdresowy(string $cityId)
    {
        $res   = $this->oClient->request('WeryfikujAdresDlaMiejscowosciAdresowy', ['symbolMsc' => $cityId]);
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
    public function WeryfikujAdresWmiejscowosci(
        string $provinceName,
        string $districtName,
        string $communeName,
        string $cityName,
        string $cityTypeName = null
    ) {
        $answer = [];
        $res    = $this->oClient->request('WeryfikujAdresWmiejscowosci',
            [
                'Wojewodztwo' => $provinceName,
                'Powiat'      => $districtName,
                'Gmina'       => $communeName,
                'Miejscowosc' => $cityName,
                'Rodzaj'      => $cityTypeName,
            ]);
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
    public function WeryfikujAdresWmiejscowosciAdresowy(
        string $provinceName,
        string $districtName,
        string $communeName,
        string $cityName,
        string $cityTypeName = null
    ) {
        $answer = [];
        $res    = $this->oClient->request('WeryfikujAdresWmiejscowosciAdresowy',
            [
                'Wojewodztwo' => $provinceName,
                'Powiat'      => $districtName,
                'Gmina'       => $communeName,
                'Miejscowosc' => $cityName,
                'Rodzaj'      => $cityTypeName,
            ]);
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
    public function WeryfikujAdresDlaUlic(string $cityId, string $streetId)
    {
        $res   = $this->oClient->request('WeryfikujAdresDlaUlic', ['symbolMsc' => $cityId, 'SymUl' => $streetId]);
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
    public function WeryfikujAdresDlaUlicAdresowy(string $cityId, string $streetId)
    {
        $res   = $this->oClient->request('WeryfikujAdresDlaUlicAdresowy', ['symbolMsc' => $cityId, 'SymUl' => $streetId]);
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
    public function WeryfikujNazwaAdresUlic(
        string $provinceName,
        string $districtName,
        string $communeName,
        string $cityName,
        string $cityTypeName = null,
        string $streetName
    ) {
        $answer = [];
        $res    = $this->oClient->request('WeryfikujNazwaAdresUlic',
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
     * @return ZweryfikowanyAdres[]
     */
    public function WeryfikujNazwaAdresUlicAdresowy(
        string $provinceName,
        string $districtName,
        string $communeName,
        string $cityName,
        string $cityTypeName = null,
        string $streetName
    ) {
        $answer = [];
        $res    = $this->oClient->request('WeryfikujNazwaAdresUlicAdresowy',
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
}
