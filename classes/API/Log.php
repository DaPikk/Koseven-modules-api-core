<?php

class API_Log {

    public static function log_access($message, $level = Log::INFO)
    {
        $log = Log::instance();
        $log->add($level, $message);
    }
}
