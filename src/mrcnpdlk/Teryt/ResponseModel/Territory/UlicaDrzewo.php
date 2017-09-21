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
 * Created by Marcin Pudełek <marcin@pudelek.org.pl>
 * Date: 06.09.2017
 */

namespace mrcnpdlk\Teryt\ResponseModel\Territory;

/**
 * Class UlicaDrzewo
 *
 * @package mrcnpdlk\Teryt\ResponseModel\Territory
 */
class UlicaDrzewo extends Ulica
{
    /**
     * 7 znakowy identyfikator miejscowości podstawowej
     *
     * @var string
     */
    public $cityParentId;
    /**
     * Zwiera nazwę ulicy w podziale na pola z uwzględnieniem słów kluczowych
     *
     * @var string
     */
    public $streetName1;
    /**
     * Zwiera nazwę ulicy w podziale na pola z uwzględnieniem słów kluczowych
     *
     * @var string
     */
    public $streetName2;
    /**
     * wyznacza miejsce podziału pełnej nazwy ulicy na nazwa1 i nazwa2
     *
     * @var integer
     */
    public $indexKey;

    /**
     * UlicaDrzewo constructor.
     *
     * @param \stdClass $oData Obiekt zwrócony z TerytWS1
     */
    public function __construct(\stdClass $oData)
    {
        parent::__construct($oData);

        $this->cityParentId = $oData->IdentyfikatorMiejscowosciPodstawowej;
        $this->streetName1  = $oData->Nazwa1;
        $this->streetName2  = $oData->Nazwa2;
        $this->indexKey     = $oData->IndeksKlucza;

        return $oData;
    }
}
