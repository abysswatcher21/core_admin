<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('appConfig')) {

    function app_config($type) {
        $CI = & get_instance();
        $CI->load->database();
        $sql = 'SELECT configuration_value
            FROM app_configuration WHERE configuration_type = \'' . $type . '\'';

        $query = $CI->db->query($sql);
        $config = $query->row()->configuration_value;

        $arr_config = json_decode($config, true);
        if (is_array($arr_config)) {
            return json_decode($config);
        } else {
            return $config;
        }
    }

}