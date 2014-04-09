<?php

class phpGraph_Utilities {
    /**
     * Searches the array for a given value and returns the corresponding key if successful
     * @param $needle mixed The searched value
     * @param $haystack array The array
     * 
     * @author buddel (see comments on php man array_search function page)
     */
    static public function recursive_array_search($needle, $haystack) {
        foreach ($haystack as $key => $value) {
            $current_key = $key;
            if ($needle === $value OR (is_array($value) && self::recursive_array_search($needle, $value) !== false)) {
                return $current_key;
            }
        }
        return false;
    }
}
