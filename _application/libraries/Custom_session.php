<?php

class Custom_session extends CI_Session {

    var $CI;
    
    function __construct() {
        $this->CI =& get_instance();
    }

    /**
     * Gets all the flashdata stored in CI session
     *
     * @author Mirko Mariotti
     * @return Array The flashdata array
     */
    function all_flashdata() {
        $all_flashdata = $res = array();
        foreach ($_SESSION as $k => $v) {
            if (preg_match('/^flash:old:(.+)/', $k, $res)) {
                $all_flashdata[$res[1]] = $this->CI->userdata($this->flashdata_key . ':old:' . $res[1]);
            }
        }
        return $all_flashdata;
    }

}
