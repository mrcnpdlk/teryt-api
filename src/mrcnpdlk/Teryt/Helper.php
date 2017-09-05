<?php

namespace mrcnpdlk\Teryt;


use mrcnpdlk\Teryt\Exception\Connection;

class Helper
{
    /**
     * @param $exclude
     *
     * @return boolean
     */
    public static function convertToBoolean($exclude)
    {
        if (is_numeric($exclude) && ($exclude == 0 || $exclude == 1)) {
            settype($exclude, 'boolean');
        } else {
            if (
                $exclude === true
                || $exclude === false
                || strtolower(trim($exclude)) === 'true'
                || strtolower(trim($exclude)) === 'false'
            ) {
                settype($exclude, 'boolean');
            } else {
                $exclude = false;
            }
        }

        return $exclude;
    }

    /**
     * @param \Exception $e
     *
     * @return \mrcnpdlk\Teryt\Exception|\mrcnpdlk\Teryt\Exception\Connection|\Exception
     */
    public static function handleException(\Exception $e)
    {
        if ($e instanceof \SoapFault) {
            switch ($e->faultcode ?? null) {
                case 'a:InvalidSecurityToken':
                    return new Connection(sprintf('Invalid Security Token'), 1, $e);
                case 'WSDL':
                    return new Connection(sprintf('%s', $e->faultstring ?? 'Unknown', 1, $e));
                default:
                    return $e;
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
     * If only one item exists in response, returnet property is not a array but object type
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
            throw new Exception(sprintf('%s() Property [%s] not exist in object', __METHOD__, $sPropertyName));
        }
        if (!is_array($oObject->{$sPropertyName})) {
            return [$oObject->{$sPropertyName}];
        } else {
            return $oObject->{$sPropertyName};
        }
    }

    /**
     * @param int $tercId
     *
     * @return array
     */
    public static function translateTercId(int $tercId)
    {
        $sTercId = str_pad(strval($tercId), 7, '0', \STR_PAD_LEFT);

        return [
            'provinceId'    => substr($sTercId, 0, 2),
            'districtId'    => substr($sTercId, 2, 2),
            'communeId'     => substr($sTercId, 4, 2),
            'communeTypeId' => substr($sTercId, 6, 1),
        ];
    }

}
