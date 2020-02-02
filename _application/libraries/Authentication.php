<?php

/*
 * Authentication Libraries
 *
 * @author	Agus Heriyanto
 * @copyright	Copyright (c) 2014, Esoftdream.net
 */

// -----------------------------------------------------------------------------

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Authentication {

    var $CI = null;

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->library(array('session'));
    }

    function auth_user() {
        if (!$this->CI->session->userdata('administrator_logged_in')) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function privilege_user() {

        $actor = '';
        $controller = '';
        $action = '';
        $action_split = '';
        $uri_string = rtrim(uri_string(), "/");
        $arr_uri = explode('/', $uri_string);

        if (isset($arr_uri[0])) {
            $actor = $arr_uri[0];
        }
        if (isset($arr_uri[1])) {
            $controller = $arr_uri[1];
        }
        // cek apabila url ada action nya
        if (isset($arr_uri[2])) {
            $action = $arr_uri[2];

            // cek action berawalan act_
            if (startsWith($arr_uri[2], 'act_')) {
//                $action_split = substr($arr_uri[2], 4);
                $action_split = explode('_', $arr_uri[2]);
            }
        }

        $arr_uri_string_true = array(
            'profile',
            'profile/systems',
            'profile/systems/profile',
            'profile/systems/act_profile',
            'profile/systems/password',
            'dashboard/dashboard1',
            'sales_order/pos/act_print_order',
            'sales_order/pos/act_print_bill',
            'sales_order/pos/act_print_struct',
            'sales_order/pos/act_print_dot_matrix',
            'sales_order/pos/act_add',
            'sales_order/pos/act_add_hold',
            'sales_order/pos/act_add_customer',
            'sales_order/pos/act_add_register_close',
            'sales_order/pos/act_delete_hold',
            'sales_order/pos_central/act_print_order',
            'sales_order/pos_central/act_print_bill',
            'sales_order/pos_central/act_print_struct',
            'sales_order/pos_central/act_print_dot_matrix',
            'sales_order/pos_central/act_add',
            'sales_order/pos_central/act_add_hold',
            'sales_order/pos_central/act_add_customer',
            'sales_order/pos_central/act_add_register_close',
            'sales_order/pos_central/act_delete_hold',
            'sales_order/pos_merchant/act_print_order',
            'sales_order/pos_merchant/act_print_bill',
            'sales_order/pos_merchant/act_print_struct',
            'sales_order/pos_merchant/act_print_dot_matrix',
            'sales_order/pos_merchant/act_add',
            'sales_order/pos_merchant/act_add_hold',
            'sales_order/pos_merchant/act_add_customer',
            'sales_order/pos_merchant/act_add_register_close',
            'sales_order/pos_merchant/act_delete_hold',
            'sales_order/pos_new/act_print_order',
            'sales_order/pos_new/act_print_bill',
            'sales_order/pos_new/act_print_struct',
            'sales_order/pos_new/act_print_dot_matrix',
            'sales_order/pos_new/act_add',
            'sales_order/pos_new/act_add_hold',
            'sales_order/pos_new/act_add_customer',
            'sales_order/pos_new/act_add_register_close',
            'sales_order/pos_new/act_delete_hold',
            'sales_order/pos_new/act_add_pic'
        );

        $is_true = FALSE;
        if ($this->CI->session->userdata('administrator_group_type') == 'superuser') {
            $is_true = TRUE;
        } else {
            foreach ($arr_uri_string_true as $uri_string_true) {
                // if (preg_match('/' . str_replace('/', '\/', rtrim($uri_string_true, "/")) . '$/', $uri_string)) {
                //     $is_true = TRUE;
                // }
                if (strpos($uri_string, $uri_string_true) !== false) {
                    $is_true = TRUE;
                }
            }

            if (!$is_true) {
                $sql_administrator_privilege = "
                    SELECT sys_administrator_privilege.*, administrator_menu_id, administrator_menu_link, 
                    json_extract(administrator_privilege_action, '$.results') AS results 
                    FROM sys_administrator_privilege 
                    LEFT JOIN sys_administrator_menu ON administrator_menu_id = administrator_privilege_administrator_menu_id 
                    WHERE administrator_privilege_administrator_group_id = '" . $this->CI->session->userdata('administrator_group_id') . "' 
                ";
                $query_administrator_privilege = $this->CI->db->query($sql_administrator_privilege);
                if ($query_administrator_privilege->num_rows() > 0) {
                    $arr_menu[0] = array(
                        'menu_link' => 'backend/dashboard/show',
                        'action_name' => array('show'),
                    );
                    foreach ($query_administrator_privilege->result() as $row_administrator_privilege) {
                        if ($row_administrator_privilege->administrator_menu_link != '#') {
                            $arr_menu[$row_administrator_privilege->administrator_menu_id] = array(
                                'menu_link' => $row_administrator_privilege->administrator_menu_link,
                                'action_name' => (!empty($row_administrator_privilege->results) ? json_decode($row_administrator_privilege->results, TRUE) : array()),
                            );
                        }
                    }
                }

                foreach ($arr_menu as $menu_id => $menu) {
                    
                    $url_link = $actor . '/' . $controller;

                    if (strtolower($url_link) . '/show' == strtolower($menu['menu_link']) || strtolower($url_link) . '/show/add' == strtolower($menu['menu_link'])) {
                        // cek jika url ada tambahan action, kecuali action show atau berawalan get_ atau export_
                        if ($action == 'show' || startsWith($action, 'get_') || startsWith($action, 'export_')) {
                            $is_true = TRUE;
                            break;
                        } else if (startsWith($action, 'act_')) { // cek apakah action berbentuk array hasil explode act_
                            if (isset($action_split)) {
                                // cek jika index array action name url ada di index array action name privilege
                                $menu['action_name'] = array_flip($menu['action_name']);
                                if (!empty($menu['action_name']) && isset($menu['action_name'][$action_split[1]])) {
                                    $is_true = TRUE;
                                    break;
                                } else {
                                    $is_true = FALSE;
                                }
                            }
                        } else {
                            $is_true = FALSE;
                        }
                    } else {
                        $is_true = FALSE;
                    }
                }
            }
        }
        return $is_true;
    }

    function get_ref_action_name() {
        $arr_ref_action_name = array();
        $actor = '';
        $controller = '';
        $action = '';
        $uri_string = rtrim(uri_string(), "/");
        $arr_uri = explode('/', $uri_string);

        if (isset($arr_uri[0])) {
            $actor = $arr_uri[0];
        }
        if (isset($arr_uri[1])) {
            $controller = $arr_uri[1];
        }
        if (isset($arr_uri[2])) {
            $action = $arr_uri[2];
        }

        if ($this->CI->session->userdata('administrator_group_type') !== 'superuser') {
            $sql = "
                SELECT json_extract(administrator_privilege_action, '$.results') AS results 
                FROM sys_administrator_privilege 
                JOIN sys_administrator_menu ON administrator_privilege_administrator_menu_id = administrator_menu_id
                WHERE administrator_menu_link = '" . uri_string() . "' AND administrator_privilege_administrator_group_id = " . $this->CI->session->userdata('administrator_group_id') . "
            ";
            $query = $this->CI->db->query($sql);

            if ($query->num_rows() > 0) {
                $row = $query->row();
                $arr_ref_action_name = json_decode($row->results, true);
            }
        }

        return $arr_ref_action_name;
    }

    function auth_member() {
        if (!isset($_SESSION['member_logged_in'])) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
