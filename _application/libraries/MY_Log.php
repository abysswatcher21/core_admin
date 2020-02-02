<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Log extends CI_Log {

    public function __construct() {
        parent::__construct();
    }

    public function write_log($level, $msg, $php_error = FALSE) {
        $CI = & get_instance();
    }

}
