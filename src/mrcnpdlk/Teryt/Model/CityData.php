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


use mrcnpdlk\Teryt\Client;
use mrcnpdlk\Teryt\Helper;

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
     * Typu string, określa identyfikator jednostki. Identyfikator posiada 7
     * znaków i jest złączeniem symboli jednostki określających województwo
     * (2 znaki), powiat (2 znaki), gminę (2 znaki) i rodzaj jednostki (1 znak)
     *
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

    public function getStreets()
    {
        $tIds = Helper::translateTercId($this->tercId);

        return Client::getInstance()->getStreets($tIds['provinceId'], $tIds['districtId'], $tIds['communeId'], $tIds['communeTypeId'], $this->id);
    }

}
