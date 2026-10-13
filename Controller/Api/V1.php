<?php defined('SYSPATH') OR die('No direct access.');

class Controller_Api_V1 extends Controller {

    public function before()
    {
        parent::before();
        API_Auth::check();
    }

    public function action_index()
    {
        
        //die('API works ;)');
        
        $resource = ucfirst($this->request->param('resource'));
        $id       = $this->request->param('id');
        $sysdom   = $this->request->param('sysdomain');
        $method   = strtolower($this->request->method());
        $format   = $this->request->param('format', 'json');
        
        //var_dump($this->request->param());
        $x = $this->request->param();

        $controller_class = "Controller_Api_{$resource}";
        if (!isset($sysdom) || empty($sysdom)) {
            $message = 'Error: No Sysdomain defined for<br /><pre>'.Debug::pr($x,TRUE).'<pre>';
            API_Log::log_access($message, LOG::ERROR);
            API_Response::send(['error' => "Sysdomain not found."], $format, 404);
            
        }

        if (!class_exists($controller_class)) {
            $message = 'Error: No resource/class found for<br /><pre>'.Debug::pr($x,TRUE).'<pre>';
            API_Log::log_access($message, LOG::ERROR);
            API_Response::send(['error' => "Resource not found."], $format, 404);
            
        }

        //echo 'TERE';
        try {
            $controller = new $controller_class($this->request, $this->response);
            $controller->before();
            //Debug::pr($method); 
            if (method_exists($controller, $method)) {
                $controller->$method();
                $controller->after();
            } else {
                $message = 'Error: Method "'.$method.'" not allowed for<br /><pre>'.Debug::pr($x, TRUE).'<pre>';
                API_Log::log_access($message, LOG::ERROR);
                API_Response::send(['error' => "Method not allowed."], $format, 405);
            }
        } catch (Throwable $e) {
            $message = 'Error: '.$e->getMessage().' for<br /><pre>'.Debug::pr($x, TRUE).'<pre>';
            API_Log::log_access($message, LOG::ERROR);
            API_Response::send(['error' => 'Internal server error.'], $format, 500);
        }
    }
}
