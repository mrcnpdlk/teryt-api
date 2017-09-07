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
 * Time: 21:56
 */

namespace mrcnpdlk\Teryt\ResponseModel\Dictionary;

/**
 * Class RodzajMiejscowosci
 *
 * @package mrcnpdlk\Teryt\ResponseModel\Dictionary
 */
class RodzajMiejscowosci
{
    /**
     * Identyfikator typu miejscowości
     *
     * @var string
     */
    public $id;
    /**
     * Nazwa typu
     *
     * @var string
     */
    public $name;
    /**
     * Opis
     *
     * @var string
     */
    public $desc;

    /**
     * Utworzenie instancji klasy RodzajMiejscowosci
     *
     * @param \stdClass $oData Obiekt zwrócony z TerytWS1
     *
     * @return static
     */
    public static function create(\stdClass $oData)
    {
        $o       = new static();
        $o->id   = $oData->Symbol;
        $o->name = $oData->Nazwa;
        $o->desc = $oData->Opis;

        return $o;
    }
}
