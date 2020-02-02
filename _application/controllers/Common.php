<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->is_superuser = $this->session->userdata('administrator_group_type') == 'superuser' ? TRUE : FALSE;
        $this->user_group = $this->session->userdata('administrator_group_type');
    }
    
    public function get_data_company() {
        $this->output->set_content_type('application/json');
        $response = array(
            'status' => 400,
            'msg' => 'Data Not Found',
            'data' => []
        );
        if ($this->is_superuser) {
            $data = $this->db->select('company_id, company_title')
                    ->get('sys_company')->result();
            $response = array(
                'status' => 200,
                'msg' => 'Success',
                'data' => $data
            );
        }
        echo json_encode($response);
    }
    
    public function get_data_warehouse() {
        $this->output->set_content_type('application/json');
        $company_id = $this->input->get('company_id');
        $response = array(
            'status' => 400,
            'msg' => 'Data Not Found',
            'data' => []
        );
        if ($company_id) {
            if ($this->is_superuser OR $this->user_group == 'administrator_company') {
                if ($this->user_group == 'administrator_company') {
                    $company_id = $this->session->userdata('administrator_group_company_id');
                }
                $data = $this->db->select('warehouse_id, warehouse_name')
                        ->get_where('sys_warehouse', array('warehouse_company_id' => $company_id))->result();
                $response = array(
                    'status' => 200,
                    'msg' => 'Success',
                    'data' => $data
                );
            }
        }
        echo json_encode($response);
    }
    
    public function get_data_warehouse_and_pos() {
        $this->output->set_content_type('application/json');
        $company_id = $this->input->get('company_id');
        $response = array(
            'status' => 400,
            'msg' => 'Data Not Found',
            'data' => []
        );
        if ($company_id) {
//            if ($this->is_superuser OR $this->user_group == 'administrator_company') {
                if ($this->user_group == 'administrator_company') {
                    $company_id = $this->session->userdata('administrator_group_company_id');
                }
                
                $data_warehouse = $this->db->select('warehouse_id, warehouse_name')
                        ->get_where('sys_warehouse', array('warehouse_company_id' => $company_id))->result();
                $data_pos = $this->db->select('pos_id, pos_name')
                        ->get_where('sys_pos', array('pos_company_id' => $company_id))->result();
                
                $data = array(
                    'warehouse' => $data_warehouse,
                    'pos' => $data_pos
                );
                $response = array(
                    'status' => 200,
                    'msg' => 'Success',
                    'data' => $data
                );
//            }
        }
        echo json_encode($response);
    }
}