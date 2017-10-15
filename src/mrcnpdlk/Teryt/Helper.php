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

namespace mrcnpdlk\Teryt;


use mrcnpdlk\Teryt\Exception\Connection;

/**
 * Class Helper
 *
 * @package mrcnpdlk\Teryt
 */
class Helper
{
    /**
     * Converting value to boolean
     *
     * @param $exclude
     *
     * @return boolean
     */
    public static function convertToBoolean($exclude)
    {
        if (is_bool($exclude)) {
            return $exclude;
        } else {
            if (is_numeric($exclude)) {
                return $exclude === 1;
            } else {
                if (is_string($exclude)) {
                    return strtolower(trim($exclude)) === 'true' || trim($exclude) === '1';
                } else {
                    return false;
                }
            }
        }
    }


    /**
     * Catching and managment of exceptions
     *
     * @param \Exception $e
     *
     * @return \Exception|Exception|Connection
     */
    public static function handleException(\Exception $e)
    {
        if ($e instanceof \SoapFault) {
            switch ($e->faultcode ?? null) {
                case 'a:InvalidSecurityToken':
                    return new Connection(sprintf('Invalid Security Token'), 1, $e);
                case 'WSDL':
                    return new Connection(sprintf('%s', $e->faultstring ?? 'Unknown'), 2, $e);
                case 'Client':
                    return new Connection(sprintf('%s', $e->faultstring ?? 'Unknown'), 3, $e);
                default:
                    return new Connection(sprintf('%s', 'Unknown'), 99, $e);
            }
        } else {
            if ($e instanceof Exception) {
                return $e;
            } else {
                return new Exception('Unknown Exception', 1, $e);
            }
        }
    }

    /**
     * Return unique property/key values from array of object/array
     *
     * @param array  $tItems
     * @param string $sKey
     * @param bool   $asUnique
     *
     * @return array
     * @throws Exception
     */
    public static function getKeyValues(array $tItems, string $sKey, bool $asUnique = true)
    {
        $answer = [];
        foreach ($tItems as $item) {
            if (!is_object($item) && !is_array($item)) {
                throw new Exception(sprintf('%s() elem of tInput is not an array|object, is %s', __METHOD__, gettype($item)));
            }
            $item = (array)$item;
            if (
                array_key_exists($sKey, $item)
                && (is_string($item[$sKey]) || is_numeric($item[$sKey]))
                && !in_array($item[$sKey], $answer)
            ) {
                $answer[] = $item[$sKey];
            }
        }

        return $asUnique ? array_unique($answer) : $answer;
    }

    /**
     * Fixing Teryt API bug
     *
     * If only one item exists in response, returned property is not a array but object type
     *
     * @param \stdClass $oObject
     * @param string    $sPropertyName
     *
     * @return array
     * @throws Exception
     */
    public static function getPropertyAsArray(\stdClass $oObject, string $sPropertyName)
    {
        if (!property_exists($oObject, $sPropertyName)) {
            throw new Exception\NotFound(sprintf('%s() Property [%s] not exist in object', __METHOD__, $sPropertyName));
        }
        if (!is_array($oObject->{$sPropertyName})) {
            return [$oObject->{$sPropertyName}];
        } else {
            return $oObject->{$sPropertyName};
        }
    }

    /**
     * Fixing Teryt API bug
     *
     * If only one item exists in response, returned property is not a array but object type
     *
     * @param \stdClass $oObject
     * @param string    $sPropertyName
     *
     * @return mixed
     * @throws Exception
     * @throws Exception\NotFound
     */
    public static function getPropertyAsObject(\stdClass $oObject, string $sPropertyName)
    {
        if (!property_exists($oObject, $sPropertyName)) {
            throw new Exception\NotFound(sprintf('%s() Property [%s] not exist in object', __METHOD__, $sPropertyName));
        }
        if (!is_object($oObject->{$sPropertyName})) {
            throw new Exception(sprintf('%s() Property [%s] is not an object type [is:%s]', __METHOD__, $sPropertyName,
                gettype($oObject->{$sPropertyName})));
        } else {
            return $oObject->{$sPropertyName};
        }
    }

    /**
     * Save file on disk
     *
     * @param string $sPath   Destination path
     * @param string $content File content
     *
     * @return \SplFileObject
     */
    public static function saveFile(string $sPath, string $content)
    {
        if (!file_exists($sPath) || (md5_file($sPath) !== md5($content))) {
            $oFile = new \SplFileObject($sPath, 'w+');
            $oFile->fwrite($content);
        }

        return new \SplFileObject($sPath);
    }
}
