<?php

class Login_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_data_administrator_by_username($username) {
        $sql = "
            SELECT * 
            FROM sys_administrator 
            INNER JOIN sys_administrator_group ON administrator_group_id = administrator_administrator_group_id 
            WHERE administrator_username = '" . $username . "' 
        ";
//        echo $sql;
//        die();
        return $this->db->query($sql);
    }
    
    function get_data_administrator_last_login() {
        $sql_last_login = "
            SELECT * 
            FROM sys_administrator 
            ORDER BY administrator_last_login DESC 
            LIMIT 1
        ";
        return $this->db->query($sql_last_login);
    }

}