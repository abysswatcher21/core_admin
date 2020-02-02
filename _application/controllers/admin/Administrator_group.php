<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Administrator_group extends Backend_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/administrator_model');
        $this->load->helper('form');
    }

    function index() {
        $this->show();
    }

    public function show() {
        if (!empty($this->menu_info)) {
            if (!empty($this->menu_info->parent_title)) {
                $data['arr_breadcrumbs'] = array(
                    $this->menu_info->parent_title => $this->menu_info->parent_link,
                    $this->menu_info->menu_title => $this->menu_info->menu_link
                );
            } else {
                $data['arr_breadcrumbs'] = array(
                    $this->menu_info->menu_title => $this->menu_info->menu_link
                );
            }
        }

        $arr_menu_privilege = array();
        if ($this->session->userdata('administrator_group_type') == 'superuser') {
            $query_menu = $this->function_lib->get_superuser_menu();
        } else {
            $query_menu = $this->function_lib->get_administrator_menu($this->session->userdata('administrator_group_id'));
        }

        if ($query_menu->num_rows() > 0) {
            foreach ($query_menu->result() as $row_menu) {
                $arr_menu_privilege[$row_menu->administrator_menu_par_id][$row_menu->administrator_menu_order_by] = $row_menu;
            }
        }

        $data['is_superuser'] = ($_SESSION['administrator_group_type'] == 'superuser') ? TRUE : FALSE;

        $data['arr_menu_privilege'] = $arr_menu_privilege;

        $data['action'] = array_flip($this->ref_action_name);

        $this->template->content("admin/administrator_group_list_view", $data);
        $this->template->show('template');
    }

    function get_data() {
        $params = isset($_POST) ? $_POST : array();
        $params['table'] = "sys_administrator_group";
        $params['join'] = "
            LEFT JOIN sys_company ON company_id = administrator_group_company_id
            LEFT JOIN sys_warehouse ON warehouse_id = administrator_group_warehouse_id
            LEFT JOIN sys_pos ON pos_id = administrator_group_pos_id
            ";
        $str_where = "";
        if ($this->session->userdata('administrator_group_type') != 'superuser') {
            if ($this->session->userdata('administrator_group_type') == 'administrator_company') {
                $str_where = ' AND administrator_group_company_id = ' . $this->session->userdata('administrator_group_company_id');
            } else {
                if ($this->session->userdata('administrator_group_type') == 'administrator_warehouse') {
                    $str_where = " AND administrator_group_warehouse_id = " . $this->session->userdata('administrator_group_warehouse_id') . " AND administrator_group_type = 'administrator_warehouse'";
                } else {
                    $str_where = " AND administrator_group_pos_id = " . $this->session->userdata('administrator_group_pos_id') . " AND administrator_group_type = 'administrator_pos'";
                }
            }
        }

        $params['where_detail'] = "administrator_group_type != 'superuser'" . $str_where;

        $result = $this->function_lib->get_query_data($params);
        $query = $result['data'];
        $total = $result['total'];

        header("Content-type: application/json");
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $json_data = array('page' => $page, 'total' => $total, 'rows' => array());
        foreach ($query->result() as $row) {

            //is_active
            if ($row->administrator_group_is_active == '1') {
                $stat = 'Active';
                $image_stat = 'bulb_on.png';
            } else {
                $stat = 'Inactive';
                $image_stat = 'bulb_off.png';
            }
            $is_active = '<img src="' . base_url() . _dir_icon . $image_stat . '" alt="' . $stat . '" title="' . $stat . '" border="0" />';
            
            //edit
            $edit = "";
            if(!$this->session->userdata('administrator_group_type') != 'superuser' && $row->administrator_group_id != $this->session->userdata('administrator_group_id')){
                $edit = '<a href="javascript:;" onclick="return editAdministratorGroup(' . $row->administrator_group_id . ')"><img src="' . base_url() . _dir_icon . 'save_labled_edit.png" border="0" alt="Edit" title="Edit" /></a>';
            }

            $group_title = '';
            if ($row->administrator_group_type == 'administrator_company') {
                $group_title = 'Company Administrator';
            } else if ($row->administrator_group_type == 'administrator_warehouse') {
                $group_title = 'Warehouse Administrator';
            } else if ($row->administrator_group_type == 'administrator_pos') {
                $group_title = 'POS Administrator';
            } else if ($row->administrator_group_type == 'administrator_cashier') {
                $group_title = 'Cashier Administrator';
            }else{
                $group_title = $row->administrator_group_type;
            }

            $entry = array('id' => $row->administrator_group_id,
                'cell' => array(
                    'administrator_group_title' => $row->administrator_group_title,
                    'company_title' => $row->company_title,
                    'warehouse_name' => $row->warehouse_name,
                    'pos_name' => $row->pos_name,
                    'administrator_group_is_active' => $is_active,
                    'edit' => $edit
                ),
            );

            if ($this->session->userdata('administrator_group_type') == 'superuser') {
                $entry['cell']['administrator_group_type'] = ucfirst($group_title);
            }
            $json_data['rows'][] = $entry;
        }

        echo json_encode($json_data);
    }

    function get_data_by_id() {
        $id = $this->input->get('id');
        if (!empty($id) && is_numeric($id)) {
            header("Content-type: application/json");

            $arr_checked_menu = array();
            $query_privilege = $this->administrator_model->get_group_list_privilege($id);
            if ($query_privilege->num_rows() > 0) {
                foreach ($query_privilege->result() as $row_privilege) {
                    $arr_checked_menu[] = array(
                        'id' => $row_privilege->administrator_menu_id,
                        'act' => json_decode($row_privilege->results)
                    );
                }
            }

            $data['arr_checked_menu'] = $arr_checked_menu;

            $query = $this->administrator_model->get_group_detail($id, $this->session->userdata('administrator_group_type'));

            if ($query->num_rows() > 0) {
                $data['data'] = $query->row();
            }

            echo json_encode($data);
        } else {
            show_404();
        }
    }

    function act_add() {
        if (!empty($_POST)) {
            header("Content-type: application/json");
            $this->load->library('form_validation');

            $this->form_validation->set_rules('title', '<b>Group Name</b>', 'required|max_length[20]');

            if ($_SESSION['administrator_group_type'] == 'superuser') {
                $this->form_validation->set_rules('company', '<b>Company Name</b>', 'required');
                $this->form_validation->set_rules('type', '<b>Group Type</b>', 'required');

                switch ($this->input->post('type')) {
                    case 'administrator_company':
//                        $this->form_validation->set_rules('warehouse', '<b>Warehouse Name</b>', 'required');
//                        $this->form_validation->set_rules('pos', '<b>POS Name</b>', 'required');
                        break;
                    case 'administrator_warehouse':
                        $this->form_validation->set_rules('warehouse', '<b>Warehouse Name</b>', 'required');
                        break;
                    case 'administrator_pos':
                        $this->form_validation->set_rules('pos', '<b>POS Name</b>', 'required');
                        break;
                    case 'administrator_cashier':
                        $this->form_validation->set_rules('pos', '<b>POS Name</b>', 'required');
                        break;
                    default:
                        $this->form_validation->set_rules('warehouse', '<b>Warehouse Name</b>', 'required');
                        $this->form_validation->set_rules('pos', '<b>POS Name</b>', 'required');
                        break;
                }
            }

            if ($_SESSION['administrator_group_type'] == 'administrator_company') {
                $this->form_validation->set_rules('type', '<b>Group Type</b>', 'required');

                switch ($this->input->post('type')) {
                    case 'administrator_warehouse':
                        $this->form_validation->set_rules('warehouse', '<b>Warehouse Name</b>', 'required');
                        break;
                    case 'administrator_pos':
                        $this->form_validation->set_rules('pos', '<b>POS Name</b>', 'required');
                        break;
                    case 'administrator_cashier':
                        return true;
                        break;
                    default:
                        $this->form_validation->set_rules('warehouse', '<b>Warehouse Name</b>', 'required');
                        $this->form_validation->set_rules('pos', '<b>POS Name</b>', 'required');
                        break;
                }
            }

            if ($this->form_validation->run($this) == FALSE) {

                $response = array(
                    'status' => 400,
                    'msg' => validation_errors()
                );
            } else {

                if ($_SESSION['administrator_group_type'] == 'superuser' || $_SESSION['administrator_group_type'] == 'administrator_company') {
                    $administrator_group_warehouse_id = $this->input->post('warehouse');
                    $administrator_group_pos_id = $this->input->post('pos');
                    $administrator_group_type = $this->input->post('type');
                } else {
                    if ($_SESSION['administrator_group_type'] == 'administrator_warehouse') {
                        $administrator_group_warehouse_id = $_SESSION['administrator_group_warehouse_id'];
                        $administrator_group_pos_id = 0;
                        $administrator_group_type = 'administrator_warehouse';
                    } else {
                        $administrator_group_warehouse_id = 0;
                        $administrator_group_pos_id = $_SESSION['administrator_group_pos_id'];
                        $administrator_group_type = 'administrator_pos';
                    }
                }

                if ($administrator_group_type == 'administrator_company') {
                    $administrator_group_warehouse_id = 0;
                    $administrator_group_pos_id = 0;
                }

                $is_error = FALSE;

                $this->db->trans_begin();

                try {

                    $administrator_group_title = $this->input->post('title');
                    $administrator_group_company_id = ($_SESSION['administrator_group_type'] == 'superuser') ? $this->input->post('company') : $_SESSION['administrator_group_company_id'];

                    $menu = $this->input->post('menu');
                    $action = $this->input->post('action');

                    $data = array();
                    $data['administrator_group_company_id'] = $administrator_group_company_id;
                    $data['administrator_group_warehouse_id'] = $administrator_group_warehouse_id;
                    $data['administrator_group_pos_id'] = $administrator_group_pos_id;
                    $data['administrator_group_type'] = $administrator_group_type;
                    $data['administrator_group_title'] = $administrator_group_title;
                    $data['administrator_group_is_active'] = 1;

                    $administrator_group_id = $this->function_lib->insert_data('sys_administrator_group', $data);

                    if ($this->db->affected_rows() < 0) {
                        $is_error = TRUE;
                    }

                    //add privilege
                    if (isset($menu)) {
                        foreach ($menu as $menu_id) {
                            $arr_action = array('show');
                            if (isset($action[$menu_id])) {
                                foreach ($action[$menu_id] as $action_name) {
                                    array_push($arr_action, $action_name);
                                }
                            }
                            $data = array();
                            $data['administrator_privilege_administrator_group_id'] = $administrator_group_id;
                            $data['administrator_privilege_administrator_menu_id'] = $menu_id;
                            $data['administrator_privilege_action'] = '{"results": ' . json_encode($arr_action) . '}';
                            $this->db->insert('sys_administrator_privilege', $data);

                            if ($this->db->affected_rows() < 0) {
                                $is_error = TRUE;
                            }
                        }
                    }
                } catch (Exception $ex) {
                    $is_error = TRUE;
                }

                if (!$is_error) {

                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();

                        $response = array(
                            'status' => 400,
                            'msg' => 'Failed to add data! Please try again.'
                        );
                    } else {
                        $this->db->trans_commit();

                        $response = array(
                            'status' => 200,
                            'msg' => 'Success to add data.',
                        );
                    }
                } else {
                    $this->db->trans_rollback();

                    $response = array(
                        'status' => 400,
                        'msg' => 'Failed to add data! Please try again.'
                    );
                }
            }

            echo json_encode($response);
        } else {
            show_404();
        }
    }

    function act_update() {
        if (!empty($_POST)) {
            header("Content-type: application/json");
            $this->load->library('form_validation');

            $this->form_validation->set_rules('title', '<b>Group Name</b>', 'required|max_length[20]');

            if ($this->input->post('administrator_group_id') != $_SESSION['administrator_group_id']) {
                if ($_SESSION['administrator_group_type'] == 'superuser') {
                    $this->form_validation->set_rules('company', '<b>Company Name</b>', 'required');
                    $this->form_validation->set_rules('type', '<b>Group Type</b>', 'required');

                    switch ($this->input->post('type')) {
                        case 'administrator_company':
//                            $this->form_validation->set_rules('warehouse', '<b>Warehouse Name</b>', 'required');
//                            $this->form_validation->set_rules('pos', '<b>POS Name</b>', 'required');
                            break;
                        case 'administrator_warehouse':
                            $this->form_validation->set_rules('warehouse', '<b>Warehouse Name</b>', 'required');
                            break;
                        case 'administrator_pos':
                            $this->form_validation->set_rules('pos', '<b>POS Name</b>', 'required');
                            break;
                        case 'administrator_cashier':
                            $this->form_validation->set_rules('pos', '<b>POS Name</b>', 'required');
                            break;
                        default:
                            $this->form_validation->set_rules('warehouse', '<b>Warehouse Name</b>', 'required');
                            $this->form_validation->set_rules('pos', '<b>POS Name</b>', 'required');
                            break;
                    }
                }

                if ($_SESSION['administrator_group_type'] == 'administrator_company') {
                    $this->form_validation->set_rules('type', '<b>Group Type</b>', 'required');

                    switch ($this->input->post('type')) {
                        case 'administrator_warehouse':
                            $this->form_validation->set_rules('warehouse', '<b>Warehouse Name</b>', 'required');
                            break;
                        case 'administrator_pos':
                            $this->form_validation->set_rules('pos', '<b>POS Name</b>', 'required');
                            break;
                        default:
                            $this->form_validation->set_rules('warehouse', '<b>Warehouse Name</b>', 'required');
                            $this->form_validation->set_rules('pos', '<b>POS Name</b>', 'required');
                            break;
                    }
                }
            }

            if ($this->form_validation->run($this) == FALSE) {

                $response = array(
                    'status' => 400,
                    'msg' => validation_errors()
                );
            } else {
                $is_error = false;

                $this->db->trans_begin();

                try {

                    $administrator_group_id = $this->input->post('administrator_group_id');
                    $administrator_group_title = $this->input->post('title');

                    $data = array();
                    if ($administrator_group_id != $_SESSION['administrator_group_id']) {
                        $administrator_group_company_id = ($_SESSION['administrator_group_type'] == 'superuser') ? $this->input->post('company') : $_SESSION['administrator_group_company_id'];

                        if ($_SESSION['administrator_group_type'] == 'superuser' || $_SESSION['administrator_group_type'] == 'administrator_company') {
                            $administrator_group_warehouse_id = $this->input->post('warehouse');
                            $administrator_group_pos_id = $this->input->post('pos');
                            $administrator_group_type = $this->input->post('type');
                        } else {
                            if ($_SESSION['administrator_group_type'] == 'administrator_warehouse') {
                                $administrator_group_warehouse_id = $_SESSION['administrator_group_warehouse_id'];
                                $administrator_group_pos_id = 0;
                                $administrator_group_type = 'administrator_warehouse';
                            } else {
                                $administrator_group_warehouse_id = 0;
                                $administrator_group_pos_id = $_SESSION['administrator_group_pos_id'];
                                $administrator_group_type = 'administrator_pos';
                            }
                        }

                        if ($administrator_group_type == 'administrator_company') {
                            $administrator_group_warehouse_id = 0;
                            $administrator_group_pos_id = 0;
                        }

                        $menu = $this->input->post('menu');
                        $action = $this->input->post('action');

                        $data['administrator_group_company_id'] = $administrator_group_company_id;
                        $data['administrator_group_warehouse_id'] = $administrator_group_warehouse_id;
                        $data['administrator_group_pos_id'] = $administrator_group_pos_id;
                        $data['administrator_group_type'] = $administrator_group_type;
                    }

                    $data['administrator_group_title'] = $administrator_group_title;

                    $this->db->where('administrator_group_id', $administrator_group_id);
                    $this->db->update('sys_administrator_group', $data);

                    if ($this->db->affected_rows() < 0) {
                        $is_error = true;
                    }

                    if ($administrator_group_id != $_SESSION['administrator_group_id']) {
                        //delete privilege
                        $this->db->delete('sys_administrator_privilege', array('administrator_privilege_administrator_group_id' => $administrator_group_id));

                        if ($this->db->affected_rows() < 0) {
                            $is_error = true;
                        }

                        //add privilege
                        if (isset($menu)) {
                            foreach ($menu as $menu_id) {
                                $arr_action = array('show');
                                if (isset($action[$menu_id])) {
                                    foreach ($action[$menu_id] as $action_name) {
                                        array_push($arr_action, $action_name);
                                    }
                                }
                                $data = array();
                                $data['administrator_privilege_administrator_group_id'] = $administrator_group_id;
                                $data['administrator_privilege_administrator_menu_id'] = $menu_id;
                                $data['administrator_privilege_action'] = '{"results": ' . json_encode($arr_action) . '}';
                                $this->db->insert('sys_administrator_privilege', $data);

                                if ($this->db->affected_rows() < 0) {
                                    $is_error = TRUE;
                                }
                            }
                        }
                    }
                } catch (Exception $ex) {
                    $is_error = true;
                }

                if (!$is_error) {

                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();

                        $response = array(
                            'status' => 400,
                            'msg' => 'Failed to change data! Please try again.'
                        );
                    } else {
                        $this->db->trans_commit();

                        $response = array(
                            'status' => 200,
                            'msg' => 'Success to change data.',
                            'dump' => $_POST
                        );
                    }
                } else {

                    $this->db->trans_rollback();
                    $response = array(
                        'status' => 400,
                        'msg' => 'Failed to change data! Please try again.'
                    );
                }
            }

            echo json_encode($response);
        } else {
            show_404();
        }
    }

    function act_activate() {
        $arr_output = array();
        $arr_output['message'] = '';
        $arr_output['message_class'] = '';

        //publish
        if ($this->input->post('publish') != FALSE) {
            $arr_item = json_decode($_POST['item']);
            if (is_array($arr_item)) {
                $success = $failed = $no_change = 0;
                $str_my_group = '';

                foreach ($arr_item as $id) {

                    if ($this->session->userdata('administrator_group_id') != $id) {
                        $is_error = FALSE;
                        $is_change = TRUE;

                        $this->db->trans_begin();

                        $data = array();
                        $data['administrator_group_is_active'] = '1';
                        $this->db->where('administrator_group_id', $id);
                        $this->db->update('sys_administrator_group', $data);

                        if ($this->db->affected_rows() < 0) {
                            $is_error = TRUE;
                        }

                        if ($this->db->affected_rows() == 0) {
                            $is_change = FALSE;
                        }

                        if (!$is_error) {
                            if ($is_change) {
                                if ($this->db->trans_status() === FALSE) {
                                    $this->db->trans_rollback();
                                    $failed++;
                                } else {
                                    $this->db->trans_commit();
                                    $success++;
                                }
                            } else {
                                if ($this->db->trans_status() === FALSE) {
                                    $this->db->trans_rollback();
                                    $failed++;
                                } else {
                                    $this->db->trans_commit();
                                    $no_change++;
                                }
                            }
                        } else {
                            $this->db->trans_rollback();
                            $failed++;
                        }
                    } else {
                        $str_my_group = 'Cannot activate the group that is being used.';
                        $failed++;
                    }
                }

                $str_success = ($success > 0) ? $success . ' data was successfully activated. ' : '';
                $str_no_change = ($no_change > 0) ? $no_change . ' data doesn&apos;t change. ' : '';
                $str_failed = ($failed > 0) ? $failed . ' data failed to activated. ' . $str_my_group : '';

                $arr_output['message'] = $str_success . $str_no_change . $str_failed;
                $arr_output['message_class'] = ($failed > 0) ? 'response_confirmation alert alert-danger' : (($no_change > 0) ? 'response_confirmation alert alert-info' : 'response_confirmation alert alert-success');
            } else {
                $arr_output['message'] = 'You have not selected data.';
                $arr_output['message_class'] = 'response_error alert alert-danger';
            }
        }
        echo json_encode($arr_output);
    }

    function act_deactivate() {

        $arr_output = array();
        $arr_output['message'] = '';
        $arr_output['message_class'] = '';

        //unpublish
        if ($this->input->post('unpublish') != FALSE) {
            $arr_item = json_decode($_POST['item']);
            if (is_array($arr_item)) {
                $success = $failed = $no_change = 0;
                $str_my_group = '';
                foreach ($arr_item as $id) {

                    if ($this->session->userdata('administrator_group_id') != $id) {
                        $is_error = FALSE;
                        $is_change = TRUE;

                        $this->db->trans_begin();

                        $data = array();
                        $data['administrator_group_is_active'] = '0';
                        $this->db->where('administrator_group_id', $id);
                        $this->db->update('sys_administrator_group', $data);

                        if ($this->db->affected_rows() < 0) {
                            $is_error = TRUE;
                        }

                        if ($this->db->affected_rows() == 0) {
                            $is_change = FALSE;
                        }

                        if (!$is_error) {
                            if ($is_change) {
                                if ($this->db->trans_status() === FALSE) {
                                    $this->db->trans_rollback();
                                    $failed++;
                                } else {
                                    $this->db->trans_commit();
                                    $success++;
                                }
                            } else {
                                if ($this->db->trans_status() === FALSE) {
                                    $this->db->trans_rollback();
                                    $failed++;
                                } else {
                                    $this->db->trans_commit();
                                    $no_change++;
                                }
                            }
                        } else {
                            $this->db->trans_rollback();
                            $failed++;
                        }
                    } else {
                        $str_my_group = ' Cannot deactivate the group that is being used.';
                        $failed++;
                    }
                }

                $str_success = ($success > 0) ? $success . ' data was successfully disabled. ' : '';
                $str_no_change = ($no_change > 0) ? $no_change . ' data doesn&apos;t change. ' : '';
                $str_failed = ($failed > 0) ? $failed . ' data failed to disable. ' . $str_my_group : '';

                $arr_output['message'] = $str_success . $str_no_change . $str_failed;
                $arr_output['message_class'] = ($failed > 0) ? 'response_confirmation alert alert-danger' : (($no_change > 0) ? 'response_confirmation alert alert-info' : 'response_confirmation alert alert-success');
            } else {
                $arr_output['message'] = 'You have not selected data.';
                $arr_output['message_class'] = 'response_error alert alert-danger';
            }
        }

        echo json_encode($arr_output);
    }

    function act_delete() {
        $arr_output = array();
        $arr_output['message'] = '';
        $arr_output['message_class'] = '';

        //delete
        if ($this->input->post('delete') != FALSE) {
            $arr_item = json_decode($_POST['item']);
            if (is_array($arr_item)) {

                $success = $failed = 0;

                $str_my_group = '';

                foreach ($arr_item as $id) {

                    if ($this->session->userdata('administrator_group_id') != $id) {
                        $is_error = FALSE;
                        $this->db->trans_begin();

                        //hapus privilege
                        $this->db->where('administrator_privilege_administrator_group_id', $id);
                        $this->db->delete('sys_administrator_privilege');

                        if ($this->db->affected_rows() < 0) {
                            $is_error = TRUE;
                        }

                        //hapus data
                        $this->db->where('administrator_group_id', $id);
                        $this->db->delete('sys_administrator_group');

                        if ($this->db->affected_rows() < 0) {
                            $is_error = TRUE;
                        }

                        if (!$is_error) {
                            if ($this->db->trans_status() === FALSE) {
                                $this->db->trans_rollback();
                                $failed++;
                            } else {
                                $this->db->trans_commit();
                                $success++;
                            }
                        } else {
                            $this->db->trans_rollback();
                            $failed++;
                        }
                    } else {
                        $str_my_group = ' Cannot delete the group that is being used.';
                        $failed++;
                    }
                }

                $str_success = ($success > 0) ? $success . ' data was successfully deleted. ' : '';
                $str_failed = ($failed > 0) ? $failed . ' data failed to delete. ' . $str_my_group : '';

                $arr_output['message'] = $str_success . $str_failed;
                $arr_output['message_class'] = ($failed > 0) ? 'response_error alert alert-danger' : 'response_confirmation alert alert-success';
            } else {
                $arr_output['message'] = 'You have not selected data.';
                $arr_output['message_class'] = 'response_error alert alert-danger';
            }
        }
        echo json_encode($arr_output);
    }

    function get_data_company() {

        if ($this->session->userdata('administrator_group_type') == 'superuser') {
            header("Content-type: application/json");

            $sql = "
                SELECT company_id, company_title
                FROM sys_company
            ";

            $data = $this->db->query($sql)->result();

            echo json_encode($data);
        } else {
            show_404();
        }
    }

    function get_data_warehouse() {
        $id = $this->input->get('id');
        if (!empty($id) && is_numeric($id)) {
            header("Content-type: application/json");

            $sql = "
                SELECT *
                FROM sys_warehouse
                WHERE warehouse_company_id = " . $id . "
                ";

            $data = $this->db->query($sql)->result();

            echo json_encode($data);
        } else {
            show_404();
        }
    }

    function get_data_pos() {
        $id = $this->input->get('id');
        if (!empty($id) && is_numeric($id)) {
            header("Content-type: application/json");

            $sql = "
                SELECT *
                FROM sys_pos
                WHERE pos_company_id = " . $id . "
                ";

            $data = $this->db->query($sql)->result();

            echo json_encode($data);
        } else {
            show_404();
        }
    }

}
