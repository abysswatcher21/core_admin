<?php

/*
 * Backend Systems Model
 *
 * @author	Agus Heriyanto
 * @copyright	Copyright (c) 2014, Esoftdream.net
 */

// -----------------------------------------------------------------------------

class Systems_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function check_username($username, $id = 0) {
        $sql = "SELECT 1 FROM site_administrator WHERE administrator_username = '" . $username . "' AND administrator_id != " . $id . "";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function get_administrator_password($id) {
        $sql = "SELECT administrator_password FROM site_administrator WHERE administrator_id = " . $id . "";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->administrator_password;
        } else {
            return '';
        }
    }

}

?>