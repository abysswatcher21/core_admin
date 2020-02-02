<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function generate_code($table_name = '', $fieldname = '', $extra = '', $digit = 5) {
        $sql = "
            SELECT
            IFNULL(LPAD(MAX(CAST(RIGHT(" . $fieldname . ", " . $digit . ") AS SIGNED) + 1), " . $digit . ", '0'), '" . sprintf('%0' . $digit . 'd', 1) . "') AS code 
            FROM " . $table_name . "
            " . $extra . "
          ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->code;
        } else {
            return '';
        }
    }

    function generate_stock_code($type = 'so', $warehouse_id = 0, $store_id = 0, $timestamp = "", $start = 1, $end = 1) {
        $datetime = date("Y-m-d H:i:s");
        $prefix = "";
        if (empty($timestamp)) {
            $timestamp = date("Ymd");
        }

        $is_error = FALSE;
        $msg = "Success";

        try {
            if ($type == 'so') {

                if ($warehouse_id > 0) {
                    //get format number so based on warehouse_id
                    $format_number_so = $this->db->select('so_trans_format_number_so')
                            ->get_where('sys_so_trans_format_number', array('so_trans_format_number_warehouse_id' => $warehouse_id), 1)
                            ->row('so_trans_format_number_so');
                } else {
                    //get format number so based on store_id
                    $format_number_so = $this->db->select('so_trans_format_number_so')
                            ->get_where('sys_so_trans_format_number', array('so_trans_format_number_store_id' => $store_id), 1)
                            ->row('so_trans_format_number_so');
                }

                if (!empty($format_number_so)) {
                    $prefix = $format_number_so . "-";
                }

                $data = array();
                for ($i = $start; $i <= $end; $i++) {
                    $data[$i]['so_stock_code_id'] = $prefix . $timestamp . "-" . str_pad($i, 5, "0", STR_PAD_LEFT);
                    if ($warehouse_id > 0) {
                        $data[$i]['so_stock_code_warehouse_id'] = $warehouse_id;
                    } else {
                        $data[$i]['so_stock_code_store_id'] = $store_id;
                    }
                    $data[$i]['so_stock_code_datetime'] = $datetime;
                }

                if (!$this->db->insert_batch('sys_so_stock_code', $data)) {
                    if ($warehouse_id > 0) {
                        throw new Exception("Failed insert sys_so_stock_code Warehouse = " . $warehouse_id . ", Looping = " . $i);
                    } else {
                        throw new Exception("Failed insert sys_so_stock_code Store = " . $store_id . ", Looping = " . $i);
                    }
                }
            }

            if ($type == 'trans') {

                if ($warehouse_id > 0) {
                    //get format number trans based on warehouse_id
                    $format_number_trans = $this->db->select('so_trans_format_number_trans')
                            ->get_where('sys_so_trans_format_number', array('so_trans_format_number_warehouse_id' => $warehouse_id), 1)
                            ->row('so_trans_format_number_trans');
                } else {
                    //get format number trans based on store_id
                    $format_number_trans = $this->db->select('so_trans_format_number_trans')
                            ->get_where('sys_so_trans_format_number', array('so_trans_format_number_store_id' => $store_id), 1)
                            ->row('so_trans_format_number_trans');
                }

                if (!empty($format_number_trans)) {
                    $prefix = $format_number_trans . "-";
                }

                $data = array();
                for ($i = $start; $i <= $end; $i++) {
                    $data[$i]['trans_stock_code_id'] = $prefix . $timestamp . "-" . str_pad($i, 5, "0", STR_PAD_LEFT);
                    if ($warehouse_id > 0) {
                        $data[$i]['trans_stock_code_warehouse_id'] = $warehouse_id;
                    } else {
                        $data[$i]['trans_stock_code_store_id'] = $store_id;
                    }
                    $data[$i]['trans_stock_code_datetime'] = $datetime;
                }

                if (!$this->db->insert_batch('sys_trans_stock_code', $data)) {
                    if ($warehouse_id > 0) {
                        throw new Exception("Failed insert sys_trans_stock_code Warehouse = " . $warehouse_id . ", Looping = " . $i);
                    } else {
                        throw new Exception("Failed insert sys_trans_stock_code Store = " . $store_id . ", Looping = " . $i);
                    }
                }
            }
        } catch (Exception $exc) {
            $is_error = TRUE;
            $msg = $exc->getMessage();
        }

        $arr_result = array(
            'is_error' => $is_error,
            'msg' => $msg
        );

        return $arr_result;
    }

    function generate_sku($product_variant_name) {
        $arr = explode(' ', $product_variant_name);
        $product_name = '';

        foreach ($arr as $row) {

            if (!is_numeric($row)) {
                if (strlen($row) >= 3) {
                    $product_name .= substr($row, 0, 3) . '-';
                } else {
                    $product_name .= substr($row, 0, 1) . '-';
                }
            } else {
                $product_name .= $row . '-';
            }
        }

        $product_name = rtrim($product_name, '-');

//        $sql = "
//            SELECT COUNT(product_variant_sku_code) AS total
//            FROM sys_product_variant 
//            WHERE product_variant_sku_code = '".$product_name."'
//        ";
//        $query = $this->db->query($sql);
//        if ($query->num_rows() > 0) {
//            $row = $query->row();
//            
//            $total = $row->total;
//            
//            if ($total > 0) {
//                $product_name .= '-'. ($total + 1);
//            }
//        }
//        return $product_name;
        return $this->check_recursiv($product_name);
    }

    function check_recursiv($product_name, $suffix = 1) {
        $sql = "
            SELECT product_variant_id
            FROM sys_product_variant 
            WHERE product_variant_sku_code = '" . $product_name . "-" . $suffix . "'
        ";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $product_name = $this->check_recursiv($product_name, $suffix + 1);
        } else {
            $product_name = $product_name . "-" . $suffix;
        }
        return $product_name;
    }

    function get_one_stock_from_stock_code($type = 'so', $warehouse_id = 0, $store_id = 0) {
        $minimal_code = 20;
        $code = "";
        $timestamp = date("Ymd");
        $is_error = FALSE;

        if ($type == 'so') {

            $str_where = "";
            if ($warehouse_id > 0) {
                $str_where = "so_stock_code_warehouse_id = '$warehouse_id'";
            } else {
                $str_where = "so_stock_code_store_id = '$store_id'";
            }

            // check if so_stock_code_id < 100
            $sql = "
                SELECT COUNT(so_stock_code_id) AS total_code
                FROM sys_so_stock_code
                WHERE $str_where
            ";

            $total_code = $this->db->query($sql)->row('total_code');

            if ($total_code < $minimal_code) {
                $sql = "
                    SELECT so_stock_code_id,
                    IFNULL(MAX(CAST(RIGHT(so_stock_code_id, 5) AS SIGNED)), 0) AS no_urut
                    FROM sys_so_stock_code
                    WHERE $str_where
                    ORDER BY so_stock_code_id DESC
                    LIMIT 1
                    ";
                $last_so_stock_code_id = $this->db->query($sql)->row('no_urut');
                $arr_result = $this->generate_stock_code('so', $warehouse_id, $store_id, $timestamp, ($last_so_stock_code_id + 1), ($last_so_stock_code_id + 1000));

                if ($arr_result['is_error']) {
                    $is_error = TRUE;
                }
            }

            // get one so_stock_code_id
            $sql = "
                SELECT so_stock_code_id
                FROM sys_so_stock_code
                WHERE $str_where
                ORDER BY so_stock_code_id ASC
                LIMIT 1
            ";

            $stock_code = $this->db->query($sql)->row('so_stock_code_id');

            // delete so_stock_code_id after get
            $sql = "
                DELETE FROM sys_so_stock_code
                WHERE so_stock_code_id = '$stock_code'
                AND $str_where
                ";
            $this->db->query($sql);

            if ($this->db->affected_rows() < 0) {
                $is_error = TRUE;
            }

            $code = $stock_code;
        } else {
            $str_where = "";
            if ($warehouse_id > 0) {
                $str_where = "trans_stock_code_warehouse_id = '$warehouse_id'";
            } else {
                $str_where = "trans_stock_code_store_id = '$store_id'";
            }
            
            // check if trans_stock_code_id < 100
            $sql = "
                SELECT COUNT(trans_stock_code_id) AS total_code
                FROM sys_trans_stock_code
                WHERE $str_where
            ";

            $total_code = $this->db->query($sql)->row('total_code');

            if ($total_code < $minimal_code) {
                $sql = "
                    SELECT trans_stock_code_id,
                    IFNULL(MAX(CAST(RIGHT(trans_stock_code_id, 5) AS SIGNED)), 0) AS no_urut 
                    FROM sys_trans_stock_code
                    WHERE $str_where
                    ORDER BY trans_stock_code_id DESC
                    LIMIT 1
                    ";
                $last_trans_stock_code_id = $this->db->query($sql)->row('no_urut');
                $arr_result = $this->generate_stock_code('trans', $warehouse_id, $store_id, $timestamp, ($last_trans_stock_code_id + 1), ($last_trans_stock_code_id + 1000));

                if ($arr_result['is_error']) {
                    $is_error = TRUE;
                }
            }

            // get one trans_stock_code_id
            $sql = "
                SELECT trans_stock_code_id
                FROM sys_trans_stock_code
                WHERE $str_where
                ORDER BY trans_stock_code_id ASC
                LIMIT 1
            ";

            $stock_code = $this->db->query($sql)->row('trans_stock_code_id');

            // delete sys_trans_stock_code after get
            $sql = "
                DELETE FROM sys_trans_stock_code
                WHERE trans_stock_code_id = '$stock_code'
                AND $str_where
                ";
            $this->db->query($sql);

            if ($this->db->affected_rows() < 0) {
                $is_error = TRUE;
            }

            $code = $stock_code;
        }

        $results = array(
            'is_error' => $is_error,
            'code' => $code
        );
        return $results;
    }
    
    function process_debt($company_id = 0, $code = 0, $date = null, $nominal = 0, $type = "add") {
        $is_error = FALSE;
        
        if(empty($date) && !validate_date($date)){
            $date = date('Y-m-d');
        }
        
        $month_year = strtoupper(convert_month(date("m",strtotime($date)), 'en')) . " " . date("Y",strtotime($date));
        
        if($type == "add"){
            $sql = "
                SELECT rep_hutang_piutang_id
                FROM rep_hutang_piutang
                WHERE rep_hutang_piutang_code = '" . $code . "'
                AND rep_hutang_piutang_month_year = '" . $month_year . "'
                AND rep_hutang_piutang_company_id = '" . $company_id . "'
                ";
            $query = $this->db->query($sql);
                
            if($query->num_rows() > 0){
                $id = $query->row('rep_hutang_piutang_id');
                
                $this->db->set('rep_hutang_piutang_hutang_value', 'rep_hutang_piutang_hutang_value + ' . $nominal, FALSE);
                $update_po = array(
                    'rep_hutang_piutang_last_updated' => date('Y-m-d'),
                );
                $this->db->update('rep_hutang_piutang', $update_po, array('rep_hutang_piutang_id' => $id));

                if ($this->db->affected_rows() < 0) {
                    $is_error = TRUE;
                }
            }else{
                $data = array();
                $data['rep_hutang_piutang_company_id'] = $company_id;
                $data['rep_hutang_piutang_code'] = $code;
                $data['rep_hutang_piutang_hutang_value'] = $nominal;
                $data['rep_hutang_piutang_month_year'] = $month_year;
                $data['rep_hutang_piutang_last_updated'] = date('Y-m-d');
                $data['rep_hutang_piutang_kolektibilitas'] = "Lunas";
                
                $this->db->insert('rep_hutang_piutang', $data);
                
                if ($this->db->affected_rows() < 0) {
                    $is_error = TRUE;
                }
            }
        }
        
        return $is_error;
    }
    
    function process_receivable(){
        
    }

}
