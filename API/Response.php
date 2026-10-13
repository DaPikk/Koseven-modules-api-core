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
                if (!preg_match('/^[A-Za-z_$][A-Za-z0-9_$]*(?:\.[A-Za-z_$][A-Za-z0-9_$]*)*$/', $callback)) {
                    self::send(array('error' => 'Invalid JSONP callback'), 'json', 400);
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
            $element_name = self::xml_element_name($key);
            if (is_array($value)) {
                self::to_xml($value, $xml->addChild($element_name));
            } else {
                $xml->addChild($element_name, htmlspecialchars((string)$value));
            }
        }

        return $xml->asXML();
    }

    /**
     * Return a safe XML element name while preserving the existing itemN
     * convention for numeric array keys.
     */
    protected static function xml_element_name($key)
    {
        $key = (string) $key;
        if (is_numeric($key)) {
            return 'item'.$key;
        }

        return preg_match('/^[A-Za-z_][A-Za-z0-9._-]*$/D', $key) ? $key : 'item';
    }
}
