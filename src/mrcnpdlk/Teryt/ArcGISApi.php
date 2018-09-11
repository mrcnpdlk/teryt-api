<?php
/**
 * TERYT-API
 *
 * Copyright (c) 2018 pudelek.org.pl
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
 * Date: 11.09.2018
 * Time: 21:33
 */

namespace mrcnpdlk\Teryt;


use Curl\Curl;
use mrcnpdlk\Teryt\Model\ArcGIS\FoundAddressModel;

class ArcGISApi
{
    private $url = 'http://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/';

    /**
     * @see https://developers.arcgis.com/rest/geocode/api-reference/geocoding-find-address-candidates.htm
     *
     * @param string $searchedPlace
     * @param array  $options
     *
     * @throws \ErrorException
     * @throws \JsonMapper_Exception
     * @throws \mrcnpdlk\Teryt\Exception
     */
    public function findAddressCandidates(string $searchedPlace, array $options)
    {
        $options['SingleLine']   = $searchedPlace;
        $options['f']            = 'json';
        $options['forStorage']   = false;
        $options['category']     = 'Address';
        $options['maxLocations'] = 1;
        $options['outFields']    = 'LongLabel,Addr_type,Nbrhd,District,City,Subregion,Region,Postal,Country,Distance';

        $curl = new Curl();
        $curl->get($this->url . 'findAddressCandidates', $options);

        if ($curl->error) {
            throw new Exception('Error: ' . $curl->errorCode . ': ' . $curl->errorMessage);
        }

        $oMapper = new \JsonMapper();
        $res     = $oMapper->map($curl->response, new FoundAddressModel());
        print_r($res);
    }

}
