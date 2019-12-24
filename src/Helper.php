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
 * @author  Marcin Pudełek <marcin@pudelek.org.pl>
 */

namespace mrcnpdlk\Teryt;

use mrcnpdlk\Teryt\Exception\Connection;
use SoapFault;
use SplFileObject;
use stdClass;

/**
 * Class Helper
 */
class Helper
{
    /**
     * Converting value to boolean
     *
     * @param mixed $exclude
     *
     * @return bool
     */
    public static function convertToBoolean($exclude): bool
    {
        if (is_bool($exclude)) {
            return $exclude;
        }

        if (is_numeric($exclude)) {
            return 1 === $exclude;
        }

        if (is_string($exclude)) {
            return 'true' === strtolower(trim($exclude)) || '1' === trim($exclude);
        }

        return false;
    }

    /**
     * Return unique property/key values from array of object/array
     *
     * @param array<mixed> $tItems
     * @param string       $sKey
     * @param bool         $asUnique
     *
     * @throws Exception
     *
     * @return array<mixed>
     */
    public static function getKeyValues(array $tItems, string $sKey, bool $asUnique = true): array
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
                && !in_array($item[$sKey], $answer, true)
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
     * @throws Exception
     *
     * @return array<mixed>
     */
    public static function getPropertyAsArray(stdClass $oObject, string $sPropertyName): array
    {
        if (!property_exists($oObject, $sPropertyName)) {
            throw new Exception\NotFound(sprintf('%s() Property [%s] not exist in object', __METHOD__, $sPropertyName));
        }
        if (!is_array($oObject->{$sPropertyName})) {
            return [$oObject->{$sPropertyName}];
        }

        return $oObject->{$sPropertyName};
    }

    /**
     * Fixing Teryt API bug
     *
     * If only one item exists in response, returned property is not a array but object type
     *
     * @param \stdClass $oObject
     * @param string    $sPropertyName
     *
     * @throws Exception
     * @throws Exception\NotFound
     *
     * @return mixed
     */
    public static function getPropertyAsObject(stdClass $oObject, string $sPropertyName)
    {
        if (!property_exists($oObject, $sPropertyName)) {
            throw new Exception\NotFound(sprintf('%s() Property [%s] not exist in object', __METHOD__, $sPropertyName));
        }
        if (!is_object($oObject->{$sPropertyName})) {
            throw new Exception(sprintf('%s() Property [%s] is not an object type [is:%s]', __METHOD__, $sPropertyName, gettype($oObject->{$sPropertyName})));
        }

        return $oObject->{$sPropertyName};
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
        if ($e instanceof SoapFault) {
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
            }

            return new Exception('Unknown Exception', 1, $e);
        }
    }

    /**
     * Save file on disk
     *
     * @param string $sPath   Destination path
     * @param string $content File content
     *
     * @throws \RuntimeException
     * @throws \LogicException
     *
     * @return \SplFileObject
     */
    public static function saveFile(string $sPath, string $content): SplFileObject
    {
        if (!file_exists($sPath) || (md5_file($sPath) !== md5($content))) {
            $oFile = new SplFileObject($sPath, 'w+');
            $oFile->fwrite($content);
        }

        return new SplFileObject($sPath);
    }
}
