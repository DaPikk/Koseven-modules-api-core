<?php

class Controller_Api_V1 extends Controller {

    public function before()
    {
        parent::before();
        API_Auth::check();
    }

    public function action_index()
    {
        $resource = ucfirst($this->request->param('resource'));
        $id       = $this->request->param('id');
        $method   = strtolower($this->request->method());
        $format   = $this->request->param('format', 'json');

        $controller_class = "Controller_Api_{$resource}";

        if (!class_exists($controller_class)) {
            API_Response::send(['error' => "Resource '$resource' not found."], $format, 404);
        }

        try {
            $controller = new $controller_class($this->request, $this->response);
            $controller->before();
            if (method_exists($controller, $method)) {
                $controller->$method();
                $controller->after();
            } else {
                API_Response::send(['error' => "Method '$method' not allowed."], $format, 405);
            }
        } catch (Exception $e) {
            API_Response::send(['error' => $e->getMessage()], $format, 500);
        }
    }
}
