<?php

class API_CORS {

    public static function apply(Response $response)
    {
        $config = Kohana::$config->load('api-core');

        $allowed_origins = $config->get('allowed_origins', ['']);
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

        if (in_array('*', $allowed_origins) || in_array($origin, $allowed_origins)) {
            $allow_origin = in_array('*', $allowed_origins, true) ? '*' : $origin;
            $response->headers([
                'Access-Control-Allow-Origin' => $allow_origin,
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Authorization, Content-Type, X-Requested-With',
            ]);
        }else {
            // Log and respond with structured CORS error
            $message = sprintf(
                "CORS Error: Unauthorized origin '%s' attempted API access.",
                $origin
            );

            API_Log::log_access($message, Log::ERROR);

            $error = [
                'error'  => 'CORS Unauthorized',
                'origin' => $origin,
                'status' => 401,
                'message' => 'The request origin is not allowed by the CORS policy.',
            ];

            API_Response::send($error, 'json', 401);
        }
    }
}
