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
 * Date: 06.09.2017
 */

namespace mrcnpdlk\Teryt\ResponseModel;


class UlicaDrzewo extends Ulica
{
    /**
     * 7 znakowy identyfikator miejscowości podstawowej
     *
     * @var string
     */
    public $cityParentId;

    /**
     * Zawiera nazwę cechy ulicy
     *
     * @var string
     */
    public $identityName;
    /**
     * Zwiera nazwę ulicy w podziale na pola z uwzględnieniem słów kluczowych
     *
     * @var string
     */
    public $streetName_1;
    /**
     * Zwiera nazwę ulicy w podziale na pola z uwzględnieniem słów kluczowych
     *
     * @var string
     */
    public $streetName_2;
    /**
     * wyznacza miejsce podziału pełnej nazwy ulicy na nazwa1 i nazwa2
     *
     * @var integer
     */
    public $indexKey;
    /**
     * Określa datę katalogu dla wskazanego stanu w formacie YYYY-MM-DD
     *
     * @var string
     */
    public $statusDate;
}