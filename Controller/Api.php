<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api extends Controller {

    public function action_index()
    {
        $version = strtolower($this->request->param('version', 'v1'));
        $resource = $this->request->param('resource');
        $id = $this->request->param('id');

        $controller_class = 'Controller_Api_' . ucfirst($version);

        if (!class_exists($controller_class)) {
            throw new HTTP_Exception_404("API version ':version' not found", [':version' => $version]);
        }
        
        // Dynamically call the target controller's logic
        $controller = new $controller_class($this->request, $this->response);
        $controller->before();
        $controller->action_index(); // or map $this->request->method() to action
        $controller->after();
    }
}
