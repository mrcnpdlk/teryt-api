<?php
/**
 * Copyright (c) 2017.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Created by Marcin Pudełek <marcin@pudelek.org.pl>
 * Date: 05.09.2017
 */

namespace mrcnpdlk\Teryt\Model;


class ProvinceData
{
    /**
     * Dwuznakowy symbol województwa
     *
     * @var string
     */
    public $id;
    /**
     * Nawa jednostki
     *
     * @var string
     */
    public $name;
    /**
     * Typ jednostki podziału terytorialnego
     *
     * @var string
     */
    public $typeName;
    /**
     * Określa datę katalogu dla wskazanego stanu
     *
     * @var string
     */
    public $statusDate;

    public static function create(\stdClass $oData)
    {
        $resData           = new static();
        $resData->id       = $oData->WOJ;
        $resData->name     = $oData->NAZWA;
        $resData->typeName = $oData->NAZWA_DOD;

        try {
            $date = $oData->STAN_NA ? (new \DateTime($oData->STAN_NA))->format('Y-m-d') : null;
        } catch (\Exception $e) {
            $date = null;
        }

        $resData->statusDate = $date;

        return $resData;
    }
}