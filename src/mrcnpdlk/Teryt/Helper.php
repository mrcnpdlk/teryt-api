<?php

namespace mrcnpdlk\Teryt;


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
        } else if (
            $exclude === true
            || $exclude === false
            || strtolower(trim($exclude)) === 'true'
            || strtolower(trim($exclude)) === 'false'
        ) {
            settype($exclude, 'boolean');
        } else {
            $exclude = false;
        }

        return $exclude;
    }
}
