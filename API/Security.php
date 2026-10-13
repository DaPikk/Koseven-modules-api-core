<?php

class API_Security {

    public static function enforce_https()
    {
        if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
            API_Response::send(['error' => 'HTTPS required'], 'json', 403);
        }
    }
}
