<?php

/**
 *
 * @author Alex Gabriel Castañeda
 */
class ObjectArrayUtil {

    /**
     * Convert an object into an associative array
     *
     * This function converts an object into an associative array by iterating
     * over its public properties. Because this function uses the foreach
     * construct, Iterators are respected. It also works on arrays of objects.
     *
     * @return array
     */
    public static function convertToArray($object) {
        if (is_array($object))
            return $object;
        if (!is_object($object))
            return false;
        $serial = serialize($object);
        $serial = preg_replace('/O:\d+:".+?"/', 'a', $serial);
        if (preg_match_all('/s:\d+:"\\0.+?\\0(.+?)"/', $serial, $ms, PREG_SET_ORDER)) {
            foreach ($ms as $m) {
                $serial = str_replace($m[0], 's:' . strlen($m[1]) . ':"' . $m[1] . '"', $serial);
            }
        }
        return @unserialize($serial);
    }

}

?>
