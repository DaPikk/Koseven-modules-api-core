<?php defined('SYSPATH') OR die('No direct access.');

class API_Log {

    public static function log_access($message, $level = Log::INFO)
    {
        /*
         * @param string $msg_type
	 * @param string $msg
	 * @param string $object
	 * @param string $object_id
	 * @param string $module
	 * @param int $user_id
	 * @param string $username
         */
        $mod = 'api-core';
        $log = Syslogm::instance($mod);
        $log->log($level, $message, $mod);
    }
}
