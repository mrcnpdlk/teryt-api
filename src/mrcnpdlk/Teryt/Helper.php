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
}
