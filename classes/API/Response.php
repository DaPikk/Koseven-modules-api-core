<?php

class API_Response {

    public static function send($data, $format = 'json', $status = 200)
    {
        $response = Response::factory()->status($status);

        switch (strtolower($format)) {
            case 'xml':
                $response->headers('Content-Type', 'application/xml');
                $response->body(self::to_xml($data));
                break;

            case 'jsonp':
                $callback = Request::current()->query('callback');
                if (!$callback) {
                    $callback = 'callback';
                }
                $response->headers('Content-Type', 'application/javascript');
                $response->body($callback . '(' . json_encode($data) . ');');
                break;

            case 'txt':
                $response->headers('Content-Type', 'text/plain');
                $response->body(print_r($data, true));
                break;

            case 'json':
            default:
                $response->headers('Content-Type', 'application/json');
                $response->body(json_encode($data));
                break;
        }

        echo $response->send_headers()->body();
        exit;
    }

    protected static function to_xml($data, $xml = null)
    {
        if ($xml === null) {
            $xml = new SimpleXMLElement('<?xml version="1.0"?><response></response>');
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                self::to_xml($value, $xml->addChild(is_numeric($key) ? "item$key" : $key));
            } else {
                $xml->addChild(is_numeric($key) ? "item$key" : $key, htmlspecialchars((string)$value));
            }
        }

        return $xml->asXML();
    }
}
