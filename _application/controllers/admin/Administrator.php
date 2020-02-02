<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Administrator extends Backend_controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('admin/administrator_model');
        $this->load->helper('form');

        $this->file_dir = _dir_administrator;
        $this->allowed_file_type = 'jpg|jpeg|gif|png';
        $this->image_width = 250;
        $this->image_height = 250;
        $this->max_size = 1024;
    }

    public function index() {
        $this->show();
    }

    function show() {
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

        $administrator_group_grid_options = '';
        $administrator_group_options = array();

        $query_administrator_group = $this->administrator_model->get_group_list($this->session->userdata('administrator_group_type'));
        if ($query_administrator_group->num_rows() > 0) {
            foreach ($query_administrator_group->result() as $row_administrator_group) {
                $administrator_group_grid_options .= $row_administrator_group->administrator_group_id . ':' . $row_administrator_group->administrator_group_title . '|';
            }
            $administrator_group_grid_options = rtrim($administrator_group_grid_options, '|');
        }

        $data['administrator_group_grid_options'] = $administrator_group_grid_options;

        $data['is_superuser'] = ($_SESSION['administrator_group_type'] == 'superuser') ? TRUE : FALSE;

        $data['action'] = array_flip($this->ref_action_name);

        $this->template->content("admin/administrator_list_view", $data);
        $this->template->show('template');
    }

    function get_data() {
        $params = isset($_POST) ? $_POST : array();
        $params['table'] = "sys_administrator";
        $params['join'] = "INNER JOIN sys_administrator_group ON administrator_group_id = administrator_administrator_group_id";

        if ($this->session->userdata('administrator_group_type') != 'superuser') {
            $params['where'] = "administrator_group_type != 'superuser' AND administrator_group_company_id = " . $this->session->userdata('administrator_group_company_id');
        }

        $data = $this->function_lib->get_query_data($params);


        header("Content-type: application/json");
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $json_data = array('page' => $page, 'total' => $data['total'], 'rows' => array());
        foreach ($data['data']->result() as $row) {

            //image
            if ($row->administrator_image != '' && file_exists($this->file_dir . $row->administrator_image)) {
                $image = $row->administrator_image;
            } else {
                $image = '_default.png';
            }
            $image = '<img src="' . base_url() . $this->file_dir . $image . '" border="0" width="110" align="absmiddle" alt="' . $image . '" />';

            //is_active
            if ($row->administrator_is_active == '1') {
                $stat = 'Active';
                $image_stat = 'bulb_on.png';
            } else {
                $stat = 'Inactive';
                $image_stat = 'bulb_off.png';
            }
            $is_active = '<img src="' . base_url() . _dir_icon . $image_stat . '" alt="' . $stat . '" title="' . $stat . '" border="0" />';

            //edit
            $edit = '<a href="javascript:;" onclick="return editAdministrator(' . $row->administrator_id . ')"><img src="' . base_url() . _dir_icon . 'save_labled_edit.png" border="0" alt="Edit" title="Edit" /></a>';

            //edit password
            $edit_password = '<a href="javascript:;" onclick="return editPassword(' . $row->administrator_id . ')"><img src="' . base_url() . _dir_icon . 'lock_edit.png" border="0" alt="Edit Password" title="Edit Password" /></a>';

            $entry = array('id' => $row->administrator_id,
                'cell' => array(
                    'administrator_id' => $row->administrator_id,
                    'administrator_group_title' => $row->administrator_group_title,
                    'administrator_username' => $row->administrator_username,
                    'administrator_name' => $row->administrator_name,
                    'administrator_mobilephone' => $row->administrator_mobilephone,
                    'administrator_email' => $row->administrator_email,
                    'administrator_last_login' => convert_datetime($row->administrator_last_login, 'en'),
                    'administrator_image' => $image,
                    'administrator_is_active' => $is_active,
                    'edit' => $edit,
                    'edit_password' => $edit_password
                ),
            );
            $json_data['rows'][] = $entry;
        }

        echo json_encode($json_data);
    }

    function act_add() {
        if (!empty($_POST)) {
            header("Content-type: application/json");
            $this->load->library('form_validation');

            $this->form_validation->set_rules('administrator_group_id', '<b>Group</b>', 'required');
            $this->form_validation->set_rules('username', '<b>Username</b>', 'required|min_length[6]|max_length[15]|unique[sys_administrator.administrator_username]');
            $this->form_validation->set_rules('password', '<b>Password</b>', 'required|min_length[6]|max_length[12]|matches[password_conf]');
            $this->form_validation->set_rules('password_conf', '<b>Repeat Password</b>', 'required');
            $this->form_validation->set_rules('name', '<b>Name</b>', 'required');
            $this->form_validation->set_rules('email', '<b>Email</b>', 'valid_email');
            $this->form_validation->set_rules('mobilephone', '<b>mobilephone Number</b>', 'required|numeric');

            if ($this->form_validation->run($this) == FALSE) {

                $response = array(
                    'status' => 400,
                    'msg' => validation_errors()
                );
            } else {

                $this->load->library('upload');
                $this->load->library('image_lib');

                $is_error = FALSE;
                $is_error_upload = FALSE;

                $this->db->trans_begin();

                try {

                    $administrator_group_id = $this->input->post('administrator_group_id');
                    $administrator_username = $this->input->post('username');
                    $administrator_password = $this->input->post('password');
                    $administrator_name = $this->input->post('name');
                    $administrator_email = $this->input->post('email');
                    $administrator_mobilephone = $this->input->post('mobilephone');

                    $data = array();
                    $data['administrator_administrator_group_id'] = $administrator_group_id;
                    $data['administrator_username'] = $administrator_username;
                    $data['administrator_password'] = password_hash($administrator_password, PASSWORD_DEFAULT);
                    $data['administrator_name'] = $administrator_name;
                    $data['administrator_email'] = $administrator_email;
                    $data['administrator_mobilephone'] = $administrator_mobilephone;
                    $data['administrator_is_active'] = 1;

                    if (!empty($_FILES['image']['tmp_name'])) {
                        if ($this->upload->fileUpload('image', $this->file_dir, $this->allowed_file_type, $this->max_size)) {
                            $upload = $this->upload->data();

                            $size = getimagesize($upload['full_path']);
                            $width = $size[0];
                            $height = $size[1];

                            if ($width != $this->image_width || $height != $this->image_height) {
                                $this->image_lib->resizeImage($upload['full_path'], $this->image_width, $this->image_height);
                                $this->image_lib->cropCenterImage($upload['full_path'], $this->image_width, $this->image_height);
                            }

                            $image_filename = url_title($administrator_name) . '-' . date("YmdHis") . strtolower($upload['file_ext']);
                            rename($upload['full_path'], $upload['file_path'] . $image_filename);
                            $data['administrator_image'] = $image_filename;
                        } else {
                            $is_error_upload = TRUE;
                            $data['administrator_image'] = '';
                        }
                    }

                    $this->db->insert('sys_administrator', $data);
                    $administrator_id = $this->db->insert_id();

                    if ($this->db->affected_rows() < 0) {
                        $is_error = TRUE;
                    }

                    $sql = "
                            SELECT administrator_group_warehouse_id
                            FROM sys_administrator_group
                            WHERE administrator_group_id = " . $administrator_group_id . "
                        ";
                    $row = $this->db->query($sql)->row();

                    $data = array();
                    $data['administrator_warehouse_administrator_id'] = $administrator_id;
                    $data['administrator_warehouse_warehouse_id'] = $row->administrator_group_warehouse_id;

                    $this->db->insert('sys_administrator_warehouse', $data);

                    if ($this->db->affected_rows() < 0) {
                        $is_error = TRUE;
                    }
                } catch (Exception $ex) {
                    $is_error = TRUE;
                }

                $error_upload = ($is_error_upload) ? $this->upload->display_errors() : '';

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
                            'msg' => 'Success to add data. ' . $error_upload . '',
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

    public function act_update() {
        if (!empty($_POST)) {
            header("Content-type: application/json");
            $this->load->library('form_validation');

            if ($this->session->userdata('administrator_id') != $this->input->post('id')) {
                $this->form_validation->set_rules('administrator_group_id', '<b>Group</b>', 'required');
            }
            $this->form_validation->set_rules('username', '<b>Username</b>', 'required|min_length[6]|max_length[15]|unique[sys_administrator.administrator_username.administrator_id.' . $this->input->post('id') . ']');
            $this->form_validation->set_rules('name', '<b>Name</b>', 'required');
            $this->form_validation->set_rules('email', '<b>Email</b>', 'valid_email');
            $this->form_validation->set_rules('mobilephone', '<b>Mobilephone Number</b>', 'required|numeric');

            if ($this->form_validation->run($this) == FALSE) {

                $response = array(
                    'status' => 400,
                    'msg' => validation_errors()
                );
            } else {

                $this->load->library('upload');
                $this->load->library('image_lib');

                $is_error = FALSE;
                $is_error_upload = FALSE;

                $this->db->trans_begin();

                try {

                    $administrator_id = $this->input->post('id');
                    $administrator_group_id = $this->input->post('administrator_group_id');
                    $administrator_username = $this->input->post('username');
                    $administrator_name = $this->input->post('name');
                    $administrator_email = $this->input->post('email');
                    $administrator_mobilephone = $this->input->post('mobilephone');
                    $administrator_old_image = $this->input->post('old_image');

                    $data = array();
                    if ($this->session->userdata('administrator_id') != $this->input->post('id')) {
                        $data['administrator_administrator_group_id'] = $administrator_group_id;
                    }

                    $data['administrator_username'] = $administrator_username;
                    $data['administrator_name'] = $administrator_name;
                    $data['administrator_email'] = $administrator_email;
                    $data['administrator_mobilephone'] = $administrator_mobilephone;

                    if (!empty($_FILES['image']['tmp_name'])) {
                        if ($this->upload->fileUpload('image', $this->file_dir, $this->allowed_file_type, $this->max_size)) {
                            $upload = $this->upload->data();

                            $size = getimagesize($upload['full_path']);
                            $width = $size[0];
                            $height = $size[1];

                            if ($width != $this->image_width || $height != $this->image_height) {
                                $this->image_lib->resizeImage($upload['full_path'], $this->image_width, $this->image_height);
                                $this->image_lib->cropCenterImage($upload['full_path'], $this->image_width, $this->image_height);
                            }

                            $image_filename = url_title($administrator_name) . '-' . date("YmdHis") . strtolower($upload['file_ext']);
                            rename($upload['full_path'], $upload['file_path'] . $image_filename);

                            //delete old file
                            if ($administrator_old_image != '' && file_exists($this->file_dir . $administrator_old_image)) {
                                @unlink($this->file_dir . $administrator_old_image);
                            }

                            $data['administrator_image'] = $image_filename;
                        } else {
                            $is_error_upload = TRUE;
                        }
                    }

                    $this->db->where('administrator_id', $administrator_id);
                    $this->db->update('sys_administrator', $data);

                    if ($this->db->affected_rows() < 0) {
                        $is_error = TRUE;
                    }

                    if (isset($data['administrator_administrator_group_id'])) {
                        $sql = "
                            SELECT administrator_group_warehouse_id
                            FROM sys_administrator_group
                            WHERE administrator_group_id = " . $administrator_group_id . "
                        ";

                        $row = $this->db->query($sql)->row();

                        $data_warehouse = array();
                        $data_warehouse['administrator_warehouse_warehouse_id'] = $row->administrator_group_warehouse_id;

                        $this->db->where('administrator_warehouse_administrator_id', $administrator_id);
                        $this->db->update('sys_administrator_warehouse', $data_warehouse);

                        if ($this->db->affected_rows() < 0) {
                            $is_error = TRUE;
                        }
                    }
                } catch (Exception $ex) {
                    $is_error = TRUE;
                }

                $error_upload = ($is_error_upload) ? $this->upload->display_errors() : '';

                if (!$is_error) {

                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();

                        $response = array(
                            'status' => 400,
                            'msg' => 'Failed to change data! Please try again.',
                        );
                    } else {

                        if ($this->session->userdata('administrator_id') == $this->input->post('id')) {
                            $this->session->set_userdata($data);
                        }

                        $this->db->trans_commit();

                        $response = array(
                            'status' => 200,
                            'msg' => 'Success to change data. ' . $error_upload . '',
                        );
                    }
                } else {
                    $this->db->trans_rollback();

                    $response = array(
                        'status' => 400,
                        'msg' => 'Failed to change data! Please try again.',
                    );
                }
            }

            echo json_encode($response);
        } else {
            show_404();
        }
    }

    function act_update_password() {
        if (!empty($_POST)) {
            header("Content-type: application/json");
            $this->load->library('form_validation');

            $this->form_validation->set_rules('password', '<b>Password Baru</b>', 'required|min_length[6]|max_length[12]|matches[password_conf]');
            $this->form_validation->set_rules('password_conf', '<b>Ulangi Password Baru</b>', 'required');

            if ($this->form_validation->run($this) == FALSE) {

                $response = array(
                    'status' => 400,
                    'msg' => validation_errors()
                );
            } else {

                $is_error = FALSE;
                $this->db->trans_begin();

                try {
                    $administrator_id = $this->input->post('id');
                    $administrator_password = $this->input->post('password');

                    $data = array();
                    $pass_hash = password_hash($administrator_password, PASSWORD_DEFAULT);
                    $data['administrator_password'] = $pass_hash;

                    $this->db->where('administrator_id', $administrator_id);
                    $this->db->update('sys_administrator', $data);

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
                            'msg' => 'Failed to change data! Please try again.'
                        );
                    } else {
                        $this->db->trans_commit();

                        $response = array(
                            'status' => 200,
                            'msg' => 'Success to change data.',
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

    function act_delete() {
        $arr_output = array();
        $arr_output['message'] = '';
        $arr_output['message_class'] = '';

        //delete
        if ($this->input->post('delete') != FALSE) {
            $arr_item = json_decode($_POST['item']);
            if (is_array($arr_item)) {
                $success = $failed = 0;
                $str_my_id = '';

                foreach ($arr_item as $id) {
                    if ($this->session->userdata('administrator_id') != $id) {
                        $is_error = FALSE;
                        $this->db->trans_begin();

                        //hapus file gambar
                        $filename = $this->function_lib->get_one('sys_administrator', 'administrator_image', array('administrator_id' => $id));
                        if ($filename != '' && file_exists($this->file_dir . $filename)) {
                            @unlink($this->file_dir . $filename);
                        }

                        //hapus data administrator
                        $this->db->where('administrator_id', $id);
                        $this->db->delete('sys_administrator');

                        //hapus data administrator warehouse
                        $this->db->where('administrator_warehouse_administrator_id', $id);
                        $this->db->delete('sys_administrator_warehouse');

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
                        $str_my_id = 'Cannot delete the account that is being used.';
                        $failed++;
                    }
                }

                $str_success = ($success > 0) ? $success . ' data was successfully deleted. ' : '';
                $str_failed = ($failed > 0) ? $failed . ' data failed to delete. ' . $str_my_id : '';

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
                $str_my_id = '';
                foreach ($arr_item as $id) {

                    if ($this->session->userdata('administrator_id') != $id) {
                        $is_error = FALSE;
                        $is_change = TRUE;

                        $this->db->trans_begin();

                        $data = array();
                        $data['administrator_is_active'] = '1';
                        $this->db->where('administrator_id', $id);
                        $this->db->update('sys_administrator', $data);

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
                        $str_my_id = 'Cannot activate the account that is being used.';
                        $failed++;
                    }
                }

                $str_success = ($success > 0) ? $success . ' data was successfully activated. ' : '';
                $str_no_change = ($no_change > 0) ? $no_change . ' data doesn&apos;t change. ' : '';
                $str_failed = ($failed > 0) ? $failed . ' data failed to activated. ' . $str_my_id : '';

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
                $str_my_id = '';
                foreach ($arr_item as $id) {

                    if ($this->session->userdata('administrator_id') != $id) {
                        $is_error = FALSE;
                        $is_change = TRUE;

                        $this->db->trans_begin();

                        $data = array();
                        $data['administrator_is_active'] = '0';
                        $this->db->where('administrator_id', $id);
                        $this->db->update('sys_administrator', $data);

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
                        $str_my_id = 'Cannot disable the account that is being used.';
                        $failed++;
                    }
                }
                $str_success = ($success > 0) ? $success . ' data was successfully disabled. ' : '';
                $str_no_change = ($no_change > 0) ? $no_change . ' data doesn&apos;t change. ' : '';
                $str_failed = ($failed > 0) ? $failed . ' data failed to disable. ' . $str_my_id : '';

                $arr_output['message'] = $str_success . $str_no_change . $str_failed;
                $arr_output['message_class'] = ($failed > 0) ? 'response_confirmation alert alert-danger' : (($no_change > 0) ? 'response_confirmation alert alert-info' : 'response_confirmation alert alert-success');
            } else {
                $arr_output['message'] = 'You have not selected data.';
                $arr_output['message_class'] = 'response_error alert alert-danger';
            }
        }

        echo json_encode($arr_output);
    }

    function get_data_by_id() {
        $id = $this->input->get('id');
        if (!empty($id) && is_numeric($id)) {
            header("Content-type: application/json");

            $sql = "
                SELECT *
                FROM sys_administrator
                INNER JOIN sys_administrator_group ON administrator_group_id = administrator_administrator_group_id
                WHERE administrator_id = " . $id . "
            ";

            $data = $this->db->query($sql)->row();

            echo json_encode($data);
        } else {
            show_404();
        }
    }

    function get_data_group_admin() {
        header("Content-type: application/json");
        $str_where = '';
        if ($this->session->userdata('administrator_group_type') != 'superuser') {
            if($this->session->userdata('administrator_group_type') == 'administrator_company'){
                $str_where = ' AND administrator_group_company_id = ' . $this->session->userdata('administrator_group_company_id');
            }else{
                if($this->session->userdata('administrator_group_type') == 'administrator_warehouse'){
                    $str_where = " AND administrator_group_warehouse_id = " . $this->session->userdata('administrator_group_warehouse_id') . " AND administrator_group_type = 'administrator_warehouse'";
                }else{
                    $str_where = " AND administrator_group_pos_id = " . $this->session->userdata('administrator_group_pos_id') . " AND administrator_group_type = 'administrator_pos'";
                }
            }
        }

        $sql = "
                SELECT administrator_group_id, administrator_group_title
                FROM sys_administrator_group
                WHERE administrator_group_type != 'superuser' AND administrator_group_is_active = '1'" . $str_where . "
                ORDER BY administrator_group_title ASC
            ";

        $data = $this->db->query($sql)->result();

        echo json_encode($data);
    }

}
