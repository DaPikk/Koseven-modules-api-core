<?php

class API_Sanitize {

    public static function clean_input($data)
    {
        $cleaned = array();

        foreach ($data as $key => $value) {
            $cleaned[$key] = is_array($value)
                ? self::clean_input($value)
                : htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        return $cleaned;
    }
}
