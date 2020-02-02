<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Administrator_menu extends Backend_controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->show();
    }

    public function show() {
        if (!empty($this->menu_info)) {
            if (!empty($this->menu_info->parent_title)) {
                $data['arr_breadcrumbs'] = array(
                    $this->menu_info->parent_title => $this->menu_info->parent_link,
                    $this->menu_info->menu_title => $this->menu_info->menu_link
                );
            }else{
                 $data['arr_breadcrumbs'] = array(
                    $this->menu_info->menu_title => $this->menu_info->menu_link
                );
            }
        }

        $sql = "
                SELECT administrator_menu_ref_action_name, 
                administrator_menu_ref_action_title
                FROM sys_administrator_menu_ref_action
            ";

        $query_action = $this->db->query($sql)->result();

        $data['query_action'] = $query_action;

        $data['is_superuser'] = ($_SESSION['administrator_group_type'] == 'superuser') ? TRUE : FALSE;

        $data['action'] = array_flip($this->ref_action_name);

        $this->template->content("admin/administrator_menu_list_view", $data);
        $this->template->show('template');
    }

    function get_ref_class_icon() {
        $this->template->content("admin/administrator_menu_ref_class_icon_view");
        $this->template->show('template');
    }

    function get_data($menu_par_id = 0) {
        $params = isset($_POST) ? $_POST : array();
        $params['table'] = "sys_administrator_menu";
        $params['where_detail'] = "administrator_menu_par_id = '" . $menu_par_id . "'";

        $result = $this->function_lib->get_query_data($params);

        $query = $result['data'];
        $total = $result['total'];

        header("Content-type: application/json");
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $json_data = array('page' => $page, 'total' => $total, 'rows' => array());
        foreach ($query->result() as $row) {

            //is_active
            if ($row->administrator_menu_is_active == '1') {
                $stat = 'Active';
                $image_stat = 'bulb_on.png';
            } else {
                $stat = 'Inactive';
                $image_stat = 'bulb_off.png';
            }
            $is_active = '<img src="' . base_url() . _dir_icon . $image_stat . '" alt="' . $stat . '" title="' . $stat . '" border="0" />';

            //edit
            $edit = '<a href="javascript:;" onclick="return editMenu(' . $row->administrator_menu_id . ')"><img src="' . base_url() . _dir_icon . 'save_labled_edit.png" border="0" alt="Edit" title="Edit" /></a>';

            //submenu
//            $submenu = '<a href="' . base_url() . 'admin/administrator_menu/show/' . $row->administrator_menu_id . '"><img src="' . base_url() . _dir_icon . 'node-tree.png" border="0" alt="Sub Menu" title="Sub Menu" style="width:16px" /></a>';
            $submenu = '<a href="javascript:;" onclick="getMenu(' . $row->administrator_menu_id . ', \'' . $row->administrator_menu_title . '\')"><img src="' . base_url() . _dir_icon . 'node-tree.png" border="0" alt="Sub Menu" title="Sub Menu" style="width:16px" /></a>';

            //class
            $menu_class = '<div style="padding:0"><i class="' . $row->administrator_menu_class . '" style="font-size:16px"></i></div>';

            $entry = array('id' => $row->administrator_menu_id,
                'cell' => array(
                    'administrator_menu_id' => $row->administrator_menu_id,
                    'administrator_menu_title' => $row->administrator_menu_title,
                    'administrator_menu_link' => $row->administrator_menu_link,
                    'administrator_menu_class' => $menu_class,
                    'administrator_menu_is_active' => $is_active,
                    'edit' => $edit,
                    'submenu' => $submenu,
                ),
            );
            $json_data['rows'][] = $entry;
        }

        echo json_encode($json_data);
    }

    function get_data_by_id() {
        $id = $this->input->get('id');
        if (!empty($id) && is_numeric($id)) {
            header("Content-type: application/json");

            $sql = "
                SELECT *,
                IFNULL(json_extract(administrator_menu_action, '$.results'), '') AS results
                FROM sys_administrator_menu
                WHERE administrator_menu_id = " . $id . "
            ";

            $data = $this->db->query($sql)->row();

            echo json_encode($data);
        } else {
            show_404();
        }
    }

    function get_data_parent() {
        header("Content-type: application/json");

        $sql = "
                SELECT administrator_menu_id, administrator_menu_title
                FROM sys_administrator_menu
                WHERE administrator_menu_par_id = 0
            ";

        $data = $this->db->query($sql)->result();

        echo json_encode($data);
    }

    function act_add() {
        if (!empty($_POST)) {
            header("Content-type: application/json");
            $this->load->library('form_validation');

            $this->form_validation->set_rules('title', '<b>Menu Title</b>', 'required|max_length[50]');
            $this->form_validation->set_rules('link', '<b>Menu Link</b>', 'required|max_length[255]');
            $this->form_validation->set_rules('class', '<b>Icon Class</b>', 'max_length[50]');

            if ($this->input->post('par_id') > 0) {
                $this->form_validation->set_rules('parent', '<b>Menu Parent</b>', 'required');
            }

            if ($this->form_validation->run($this) == FALSE) {

                $response = array(
                    'status' => 400,
                    'msg' => validation_errors()
                );
            } else {

                $is_error = FALSE;

                $this->db->trans_begin();

                try {

                    $action = $this->input->post('action');

                    $administrator_menu_par_id = ($this->input->post('par_id') > 0) ? $this->input->post('parent') : $this->input->post('par_id');
                    $administrator_menu_title = $this->input->post('title');
                    $administrator_menu_description = $this->input->post('description');
                    $administrator_menu_link = $this->input->post('link');
                    $administrator_menu_class = $this->input->post('class');
                    $administrator_menu_order_by = $this->function_lib->get_max('sys_administrator_menu', 'administrator_menu_order_by', array('administrator_menu_par_id' => $administrator_menu_par_id)) + 1;

                    $administrator_menu_action = '{"name": "show","title": "Show Data"}';
                    if (isset($action)) {
                        foreach ($action as $value) {
                            $administrator_menu_action .= ',' . $value;
                        }
                    }

                    $title = ($administrator_menu_par_id == 0) ? 'menu' : 'sub menu';

                    $data = array();
                    $data['administrator_menu_par_id'] = $administrator_menu_par_id;
                    $data['administrator_menu_title'] = $administrator_menu_title;
                    $data['administrator_menu_description'] = $administrator_menu_description;
                    $data['administrator_menu_link'] = $administrator_menu_link;
                    $data['administrator_menu_class'] = $administrator_menu_class;
                    $data['administrator_menu_action'] = '{"results":[' . $administrator_menu_action . ']}';
                    $data['administrator_menu_order_by'] = $administrator_menu_order_by;

                    $this->db->insert('sys_administrator_menu', $data);

                    if ($this->db->affected_rows() < 0) {
                        $is_error = TRUE;
                    }
                } catch (Exception $ex) {
                    $is_error = TRUE;
                }

                if (!$is_error) {

                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();

                        $response = array(
                            'status' => 400,
                            'msg' => 'Failed to add data ' . $title . '! Please try again.'
                        );
                    } else {
                        $this->db->trans_commit();

                        $response = array(
                            'status' => 200,
                            'msg' => 'Success to add data ' . $title . '.',
                        );
                    }
                } else {
                    $this->db->trans_rollback();

                    $response = array(
                        'status' => 400,
                        'msg' => 'Failed to add data ' . $title . '! Please try again.'
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

            $this->form_validation->set_rules('title', '<b>Menu Title</b>', 'required|max_length[50]');
            $this->form_validation->set_rules('link', '<b>Menu Link</b>', 'required|max_length[255]');
            $this->form_validation->set_rules('class', '<b>Icon Class</b>', 'max_length[50]');

            if ($this->input->post('par_id') > 0) {
                $this->form_validation->set_rules('parent', '<b>Menu Parent</b>', 'required');
            }


            if ($this->form_validation->run($this) == FALSE) {

                $response = array(
                    'status' => 400,
                    'msg' => validation_errors()
                );
            } else {

                $is_error = FALSE;

                $this->db->trans_begin();

                try {

                    $action = $this->input->post('action');

                    $administrator_menu_id = $this->input->post('id');
                    $administrator_menu_par_id = ($this->input->post('par_id') > 0) ? $this->input->post('parent') : $this->input->post('par_id');
                    $administrator_menu_title = $this->input->post('title');
                    $administrator_menu_description = $this->input->post('description');
                    $administrator_menu_link = $this->input->post('link');
                    $administrator_menu_class = $this->input->post('class');

                    $administrator_menu_action = '{"name": "show","title": "Show Data"}';
                    if (isset($action)) {
                        foreach ($action as $value) {
                            $administrator_menu_action .= ',' . $value;
                        }
                    }

                    $title = ($administrator_menu_par_id == 0) ? 'menu' : 'sub menu';

                    $data = array();
                    $data['administrator_menu_par_id'] = $administrator_menu_par_id;
                    $data['administrator_menu_title'] = $administrator_menu_title;
                    $data['administrator_menu_description'] = $administrator_menu_description;
                    $data['administrator_menu_link'] = $administrator_menu_link;
                    $data['administrator_menu_class'] = $administrator_menu_class;
                    if ($this->input->post('par_id') != $this->input->post('parent') && $this->input->post('par_id') > 0) {
                        $administrator_menu_order_by = $this->function_lib->get_max('sys_administrator_menu', 'administrator_menu_order_by', array('administrator_menu_par_id' => $administrator_menu_par_id)) + 1;
                        $data['administrator_menu_order_by'] = $administrator_menu_order_by;
                    }
                    $data['administrator_menu_action'] = '{"results":[' . $administrator_menu_action . ']}';

                    $this->db->where('administrator_menu_id', $administrator_menu_id);
                    $this->db->update('sys_administrator_menu', $data);

                    if ($this->db->affected_rows() < 0) {
                        $is_error = TRUE;
                    }
                } catch (Exception $ex) {
                    $is_error = TRUE;
                }

                if (!$is_error) {

                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();

                        $response = array(
                            'status' => 400,
                            'msg' => 'Failed to change data ' . $title . '! Please try again.'
                        );
                    } else {
                        $this->db->trans_commit();

                        $response = array(
                            'status' => 200,
                            'msg' => 'Success to change data ' . $title . '.',
                        );
                    }
                } else {
                    $this->db->trans_rollback();

                    $response = array(
                        'status' => 400,
                        'msg' => 'Failed to change data ' . $title . '! Please try again.'
                    );
                }
            }

            echo json_encode($response);
        } else {
            show_404();
        }
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

                foreach ($arr_item as $id) {
                    $is_error = FALSE;
                    $this->db->trans_begin();

                    //hapus sub
                    $this->db->where('administrator_menu_par_id', $id);
                    $this->db->delete('sys_administrator_menu');

                    if ($this->db->affected_rows() < 0) {
                        $is_error = TRUE;
                    }

                    //hapus data
                    $this->db->where('administrator_menu_id', $id);
                    $this->db->delete('sys_administrator_menu');

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
                }

                $str_success = ($success > 0) ? $success . ' data was successfully deleted. ' : '';
                $str_failed = ($failed > 0) ? $failed . ' data failed to delete.' : '';

                $arr_output['message'] = $str_success . $str_failed;
                $arr_output['message_class'] = ($failed > 0) ? 'response_error alert alert-danger' : 'response_confirmation alert alert-success';
            } else {
                $arr_output['message'] = 'You have not selected data.';
                $arr_output['message_class'] = 'response_error alert alert-danger';
            }
        }
        echo json_encode($arr_output);
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
                foreach ($arr_item as $id) {

                    $is_error = FALSE;
                    $is_change = TRUE;

                    $this->db->trans_begin();

                    $data = array();
                    $data['administrator_menu_is_active'] = '1';
                    $this->db->where('administrator_menu_id', $id);
                    $this->db->update('sys_administrator_menu', $data);

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
                }

                $str_success = ($success > 0) ? $success . ' data was successfully activated. ' : '';
                $str_no_change = ($no_change > 0) ? $no_change . ' data doesn&apos;t change. ' : '';
                $str_failed = ($failed > 0) ? $failed . ' data failed to activated.' : '';

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
                foreach ($arr_item as $id) {

                    $is_error = FALSE;
                    $is_change = TRUE;

                    $this->db->trans_begin();

                    $data = array();
                    $data['administrator_menu_is_active'] = '0';
                    $this->db->where('administrator_menu_id', $id);
                    $this->db->update('sys_administrator_menu', $data);

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
                }

                $str_success = ($success > 0) ? $success . ' data was successfully disabled. ' : '';
                $str_no_change = ($no_change > 0) ? $no_change . ' data doesn&apos;t change. ' : '';
                $str_failed = ($failed > 0) ? $failed . ' data failed to disable.' : '';

                $arr_output['message'] = $str_success . $str_no_change . $str_failed;
                $arr_output['message_class'] = ($failed > 0) ? 'response_confirmation alert alert-danger' : (($no_change > 0) ? 'response_confirmation alert alert-info' : 'response_confirmation alert alert-success');
            } else {
                $arr_output['message'] = 'You have not selected data.';
                $arr_output['message_class'] = 'response_error alert alert-danger';
            }
        }

        echo json_encode($arr_output);
    }

    function act_show() {

        $this->load->model('admin/administrator_model');

        $arr_output = array();
        $arr_output['message'] = '';
        $arr_output['message_class'] = '';

        //up
        if ($this->input->post('up') != FALSE) {
            $arr_item = json_decode($_POST['item']);
            if (is_array($arr_item)) {
                $success = $failed = $over = 0;
                foreach ($arr_item as $id) {

                    $this->db->trans_begin();

                    $up = $this->administrator_model->update_menu_order_by($id, 'up');

                    if (!$up['is_error']) {
                        if (!$up['is_over']) {
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
                                $this->db->trans_rollback();
                                $over++;
                            }
                        }
                    } else {
                        $this->db->trans_rollback();
                        $failed++;
                    }
                }

                $str_success = ($success > 0) ? $success . ' data was successfully moved up. ' : '';
                $str_over = ($over > 0) ? $over . ' data is not moved. ' : '';
                $str_failed = ($failed > 0) ? $failed . ' data failed to move up.' : '';

                $arr_output['message'] = $str_success . $str_over . $str_failed;
                $arr_output['message_class'] = ($failed > 0) ? 'response_confirmation alert alert-danger' : (($over > 0) ? 'response_confirmation alert alert-info' : 'response_confirmation alert alert-success');
            } else {
                $arr_output['message'] = 'You have not selected data.';
                $arr_output['message_class'] = 'response_error alert alert-danger';
            }
        }

        //down
        if ($this->input->post('down') != FALSE) {
            $arr_item = json_decode($_POST['item']);
            if (is_array($arr_item)) {
                krsort($arr_item);
                $success = $failed = $over = 0;
                foreach ($arr_item as $id) {

                    $this->db->trans_begin();

                    $down = $this->administrator_model->update_menu_order_by($id, 'down');

                    if (!$down['is_error']) {
                        if (!$down['is_over']) {
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
                                $this->db->trans_rollback();
                                $over++;
                            }
                        }
                    } else {
                        $this->db->trans_rollback();
                        $failed++;
                    }
                }

                $str_success = ($success > 0) ? $success . ' data was successfully moved down. ' : '';
                $str_over = ($over > 0) ? $over . ' data is not moved. ' : '';
                $str_failed = ($failed > 0) ? $failed . ' data failed to move down.' : '';

                $arr_output['message'] = $str_success . $str_over . $str_failed;
                $arr_output['message_class'] = ($failed > 0) ? 'response_confirmation alert alert-danger' : (($over > 0) ? 'response_confirmation alert alert-info' : 'response_confirmation alert alert-success');
            } else {
                $arr_output['message'] = 'You have not selected data.';
                $arr_output['message_class'] = 'response_error alert alert-danger';
            }
        }

        echo json_encode($arr_output);
    }

}
