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
 * @author Marcin Pudełek <marcin@pudelek.org.pl>
 */

/**
 * Created by Marcin.
 * Date: 06.09.2017
 * Time: 21:56
 */

namespace mrcnpdlk\Teryt\ResponseModel\Dictionary;

/**
 * Class RodzajMiejscowosci
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
     * RodzajMiejscowosci constructor.
     *
     * @param \stdClass $oData Obiekt zwrócony z TerytWS1
     */
    public function __construct(\stdClass $oData)
    {
        $this->id   = $oData->Symbol;
        $this->name = $oData->Nazwa;
        $this->desc = $oData->Opis;
    }
}
