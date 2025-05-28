<?php

class API_CORS {

    public static function apply(Response $response)
    {
        $config = Kohana::$config->load('api-core');

        $allowed_origins = $config->get('allowed_origins', ['']);
        $origin = $_SERVER['HTTP_ORIGIN'] ? $_SERVER['HTTP_ORIGIN'] : '';

        if (in_array('*', $allowed_origins) || in_array($origin, $allowed_origins)) {
            $response->headers([
                'Access-Control-Allow-Origin' => $origin,
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Authorization, Content-Type',
            ]);
        }
    }
}
