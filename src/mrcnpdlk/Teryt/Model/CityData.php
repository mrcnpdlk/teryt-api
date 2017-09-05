<?php
/**
 * Copyright (c) 2017.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Created by Marcin.
 * Date: 05.09.2017
 * Time: 23:51
 */

namespace mrcnpdlk\Teryt\Model;


class CityData
{
    /**
     * 7 znakowy identyfikator miejscowości
     *
     * @var string
     */
    public $id;
    /**
     * Nazwa miejscowości
     *
     * @var string
     */
    public $name;
    /**
     * @var integer
     */
    public $tercId;

    public static function create(\stdClass $oData)
    {
        $resData         = new static();
        $resData->id     = $oData->Symbol ?: null;
        $resData->name   = $oData->Nazwa ?: null;
        $resData->tercId = $oData->GmiSymbol ?: null;


        return $resData;
    }

}
