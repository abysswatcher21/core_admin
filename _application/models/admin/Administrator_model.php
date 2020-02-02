<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Administrator_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        //Codeigniter : Write Less Do More
    }

    function get_group_list($type = 'superuser') {
        $where = "WHERE 1";
        if ($type != 'superuser') {
            $where .= " AND administrator_group_type != 'superuser'";
        }
        $sql = "
        SELECT *
        FROM sys_administrator_group
        " . $where . " AND administrator_group_is_active = '1'
        ORDER BY administrator_group_title ASC
        ";
        return $this->db->query($sql);
    }

    function get_group_detail($id, $type = 'administrator') {
        $where = "";
        if ($type != 'superuser') {
            $where = "AND administrator_group_type != 'superuser'";
        }
        $sql = "SELECT * FROM sys_administrator_group WHERE administrator_group_id = '" . $id . "' " . $where;
        return $this->db->query($sql);
    }

    function get_group_list_privilege($id) {
        $sql = "
        SELECT administrator_menu_id,
        administrator_privilege_action,
        json_extract(administrator_privilege_action, '$.results') AS results 
        FROM sys_administrator_privilege
        INNER JOIN sys_administrator_menu ON administrator_menu_id = administrator_privilege_administrator_menu_id
        WHERE administrator_privilege_administrator_group_id = '" . $id . "'
        ";
        return $this->db->query($sql);
    }

    function check_group_delete($id) {
        $sql = "
        SELECT SUM(item) AS item
        FROM
        (
            SELECT COUNT(*) AS item FROM sys_administrator WHERE administrator_administrator_group_id = '" . $id . "'
            ) result
            ";
        $query = $this->db->query($sql);
        $row = $query->row();
        $item = $row->item;

        if ($item > 0) {
            return false;
        } else {
            return true;
        }
    }

    function get_administator_data($params) {
        $params['table'] = "sys_administrator";
        $params['join'] = "INNER JOIN sys_administrator_group ON administrator_group_id = administrator_administrator_group_id";
        if ($this->session->userdata('administrator_group_type') != 'superuser') {
            $params['where'] = "administrator_group_type != 'superuser'";
        }
        $query = $this->function_lib->get_query_data($params);
        return $query;
    }

    function get_administrator_detail($administrator_id) {
        $where = "WHERE 1";

        $sql = "
            SELECT *
            FROM sys_administrator
            INNER JOIN sys_administrator_group ON administrator_group_id = administrator_administrator_group_id
            " . $where . " AND administrator_id = " . $administrator_id . "
            ";
        return $this->db->query($sql);
    }

    function check_administrator_delete($administrator_id) {
        $sql = "
            SELECT SUM(item) AS item
            FROM
            (
                SELECT COUNT(*) AS item FROM sys_bonus_transfer_status WHERE bonus_transfer_status_administrator_id = " . $administrator_id . "
                UNION
                SELECT COUNT(*) AS item FROM sys_serial_activation WHERE serial_activation_administrator_id = " . $administrator_id . "
                ) result
                ";
        $query = $this->db->query($sql);
        $row = $query->row();
        $item = $row->item;

        if ($item > 0) {
            return false;
        } else {
            return true;
        }
    }

    function get_list_menu($par_id = 0) {
        $sql = "SELECT * FROM sys_administrator_menu WHERE administrator_menu_par_id = '" . $par_id . "' ORDER BY administrator_menu_order_by ASC";
        return $this->db->query($sql);
    }

    function get_menu_title($id = 0) {
        $sql = "SELECT administrator_menu_title FROM sys_administrator_menu WHERE administrator_menu_id = '" . $id . "' LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->administrator_menu_title;
        } else {
            return '';
        }
    }

    function get_menu_par_id($id = 0) {
        $sql = "SELECT administrator_menu_par_id FROM sys_administrator_menu WHERE administrator_menu_id = '" . $id . "' LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->administrator_menu_par_id;
        } else {
            return 0;
        }
    }

    function update_menu_order_by($id, $sort) {

        $arr_error['is_error'] = FALSE;
        $arr_error['is_over'] = FALSE;

        $sql_current = "
                    SELECT administrator_menu_par_id, 
                    administrator_menu_order_by 
                    FROM sys_administrator_menu 
                    WHERE administrator_menu_id = '" . $id . "'
                ";

        $query_current = $this->db->query($sql_current);
        $row_current = $query_current->row();

        $par_id = $row_current->administrator_menu_par_id;
        $order_by = $row_current->administrator_menu_order_by;

        $sql_total = "
                    SELECT COUNT(*) AS row_count 
                    FROM sys_administrator_menu 
                    WHERE administrator_menu_par_id = '" . $par_id . "'
                ";
        $query_total = $this->db->query($sql_total);
        $row_total = $query_total->row();
        $total = $row_total->row_count;
        
        if (($sort == 'up' && $order_by > 1) || ($sort == 'down' && $order_by < $total)) {
            if ($sort == 'up') {
                $sql = "
                    SELECT administrator_menu_id, 
                    administrator_menu_order_by 
                    FROM sys_administrator_menu 
                    WHERE administrator_menu_par_id = '" . $par_id . "' 
                    AND administrator_menu_order_by <= " . $order_by . " 
                    AND administrator_menu_id <> '" . $id . "' 
                    ORDER BY administrator_menu_order_by DESC 
                    LIMIT 1
                ";
            } else if ($sort == 'down') {
                $sql = "
                    SELECT administrator_menu_id, 
                    administrator_menu_order_by 
                    FROM sys_administrator_menu 
                    WHERE administrator_menu_par_id = '" . $par_id . "' 
                    AND administrator_menu_order_by >= " . $order_by . " 
                    AND administrator_menu_id <> '" . $id . "' 
                    ORDER BY administrator_menu_order_by ASC 
                ";
            }
            $query = $this->db->query($sql);
            $row = $query->row();

            $sql_update = "
                UPDATE sys_administrator_menu 
                SET administrator_menu_order_by = '" . $order_by . "' 
                WHERE administrator_menu_id = '" . $row->administrator_menu_id . "'
            ";
            $this->db->query($sql_update);

            if ($this->db->affected_rows() < 0) {
                $arr_error['is_error'] = TRUE;
            }

            $sql_update = "
                UPDATE sys_administrator_menu 
                SET administrator_menu_order_by = '" . $row->administrator_menu_order_by . "' 
                WHERE administrator_menu_id = '" . $id . "'
            ";
            $this->db->query($sql_update);

            if ($this->db->affected_rows() < 0) {
                $arr_error['is_error'] = TRUE;
            }

        } else {
            $arr_error['is_over'] = TRUE;
        }
        
        return $arr_error;
    }

}
