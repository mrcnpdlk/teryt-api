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
 * Created by Marcin Pudełek <marcin@pudelek.org.pl>
 * Date: 07.09.2017
 */

namespace mrcnpdlk\Teryt\Model;


use mrcnpdlk\Teryt\Api;
use mrcnpdlk\Teryt\Exception;

/**
 * Class Province
 *
 * @package mrcnpdlk\Teryt\Model
 */
class Province
{
    /**
     * Dwuznakowy symbol województwa
     *
     * @var string
     */
    public $id;
    /**
     * Nazwa województwa
     *
     * @var static
     */
    public $name;

    /**
     * Province constructor.
     *
     * @param string $id Dwuznakowy symbol województwa
     *
     * @throws Exception\NotFound
     */
    public function __construct(string $id)
    {
        foreach (Api::PobierzListeWojewodztw() as $w) {
            if ($w->provinceId === $id) {
                $this->id   = $w->provinceId;
                $this->name = $w->name;
            }
        }
        if (!$this->id) {
            throw new Exception\NotFound(sprintf('Province [id:%s] not exists', $id));
        }
    }

    /**
     * Utworzenie instancko klasy Province
     *
     * @param string $id Dwuznakowy symbol województwa
     *
     * @return Province
     */
    public static function find(string $id)
    {
        return new static($id);
    }

    /**
     * Pełnokontekstowe wyszukiwanie powiatów w województwie
     *
     * @param string|null $phrase Szukana fraza, gdy NULL zwraca wszystkie powiaty w województwie
     *
     * @return \mrcnpdlk\Teryt\Model\District[]
     * @throws \Exception
     */
    public function searchDistricts(string $phrase = null)
    {
        try {
            $answer = [];
            if ($phrase) {
                $tList = Api::WyszukajJednostkeWRejestrze($phrase, Api::CATEGORY_POW_ALL);
                foreach ($tList as $p) {
                    if ($p->provinceId === $this->id) {
                        $answer[] = District::find($p->provinceId . $p->districtId);
                    }
                }
            } else {
                $tList = Api::PobierzListePowiatow($this->id);
                foreach ($tList as $p) {
                    $answer[] = District::find($p->provinceId . $p->districtId);
                }
            }

            return $answer;

        } catch (Exception\NotFound $e) {
            return [];
        } catch (\Exception $e) {
            throw $e;
        }
    }
}


