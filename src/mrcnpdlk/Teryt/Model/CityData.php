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
    public $cityId;
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
    /**
     * Dwuznakowy symbol województwa
     *
     * @var string
     */
    public $provinceId;
    /**
     * Dwuznakowy symbol powiatu
     *
     * @var string
     */
    public $districtId;
    /**
     * Dwuznakowy symbol gminy
     *
     * @var string
     */
    public $communeId;
    /**
     * Jednoznakowy symbol typu gminy
     *
     * @var string
     */
    public $communeTypeId;

    /**
     * @param \stdClass $oData
     *
     * @return \mrcnpdlk\Teryt\Model\CityData
     */
    public static function create(\stdClass $oData)
    {
        $resData         = new static();
        $resData->cityId = $oData->Symbol ?: null;
        $resData->name   = $oData->Nazwa ?: null;
        $resData->tercId = isset($oData->GmiSymbol) && strlen($oData->GmiSymbol) === 7 ? $oData->GmiSymbol : null;

        //gdy dane z innego obiektu
        if (!$resData->tercId && $oData->PowSymbol && $oData->GmiSymbol && $oData->GmiRodzaj) {
            $resData->tercId = sprintf('%s%s%s', $oData->PowSymbol, $oData->GmiSymbol, $oData->GmiRodzaj);
        }

        if ($resData->tercId) {
            $t                      = Helper::translateTercId($resData->tercId);
            $resData->provinceId    = $t['provinceId'];
            $resData->districtId    = $t['districtId'];
            $resData->communeId     = $t['communeId'];
            $resData->communeTypeId = $t['communeTypeId'];
        }


        return $resData;
    }

    /**
     * @todo poprawic dzialanie metody
     * @return array
     */
    public function getStreets()
    {
        return Client::getInstance()->getStreets(
            $this->provinceId,
            $this->districtId,
            $this->communeId,
            $this->communeTypeId,
            $this->cityId)
            ;
    }

    /**
     * @param string $streetId
     *
     * @return \mrcnpdlk\Teryt\Model\StreetData
     */
    public function getStreet(string $streetId)
    {
        return Client::getInstance()->verifyAdressForCity($this->cityId, $streetId);
    }

}
