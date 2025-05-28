<?php

class API_Auth {

    protected static $config;

    public static function check()
    {
        self::$config = Kohana::$config->load('api-core');

        $allowed_ips = self::$config->get('allowed_ips', array());
        $allowed_referers = self::$config->get('allowed_referers', array());
        $valid_tokens = self::$config->get('tokens', array());

        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $host = parse_url($referer, PHP_URL_HOST);

        if (!in_array($ip, $allowed_ips) && !in_array($host, $allowed_referers)) {
            API_Response::send(array('error' => 'Forbidden'), 'json', 403);
        }

        $auth_header = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) ? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] : '');

        if ($auth_header && stripos($auth_header, 'Bearer ') === 0) {
            $token = substr($auth_header, 7);
            if (!in_array($token, $valid_tokens)) {
                API_Response::send(array('error' => 'Unauthorized'), 'json', 401);
            }
        }
    }
}
