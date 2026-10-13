<?php defined('SYSPATH') OR die('No direct access.');

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

        $x = array('ref' => $referer, 'ip' => $ip, 'host' => $host);       
        if (!in_array($ip, $allowed_ips, true) && !in_array($host, $allowed_referers, true)) {
            $message = 'Error: API access tried from: <br /><pre>'.Debug::pr($x, TRUE).'</pre>';
            API_Log::log_access($message, LOG::ERROR);
            API_Response::send(array('error' => 'Forbidden'), 'json', 403);
            
        }

        $auth_header = isset($_SERVER['HTTP_AUTHORIZATION'])
            ? $_SERVER['HTTP_AUTHORIZATION']
            : (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) ? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] : '');

        $token = null;

        if ($auth_header && stripos($auth_header, 'Bearer ') === 0) {
            $token = substr($auth_header, 7);
        } elseif (isset($_SERVER['HTTP_X_API_TOKEN'])) {
            // Support for WordPress or clients using X-Api-Token
            $token = $_SERVER['HTTP_X_API_TOKEN'];
        }
        
        if (!in_array($token, $valid_tokens, true)) {
            $message = 'Error: No-Auth-token - API access tried from: <br /><pre>'.Debug::pr($x, TRUE).'</pre>';
            API_Log::log_access($message, LOG::ERROR);
            API_Response::send(array('error' => 'Unauthorized'), 'json', 401);        
        }
        //Die('Suxxxxxazzzz');
    }
}
