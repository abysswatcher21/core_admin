<?php

/*
 * Backend Systems Controller
 *
 * @author	Agus Heriyanto
 * @copyright	Copyright (c) 2014, Esoftdream.net
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Systems extends Backend_controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('profile/systems_model');
        $this->load->helper('form');

        $this->image_width = 250;
        $this->image_height = 250;
        $this->max_size = 1024;
        $this->allowed_file_type = 'jpg|jpeg|gif|png';
        $this->file_dir = _dir_administrator;
    }

    public function index() {
        $this->profile();
    }


    function profile() {
        $data['arr_breadcrumbs'] = array(
            'My Profile' => 'profile/systems/profile',
        );

        $data['query'] = $this->function_lib->get_detail_data('sys_administrator', 'administrator_id', $this->session->userdata('administrator_id'));
        $data['image_width'] = $this->image_width;
        $data['image_height'] = $this->image_height;
        $data['form_action'] = 'profile/systems/act_profile';

        $this->template->content("profile/profile_edit_view", $data);
        $this->template->show('template');
    }

    function act_profile() {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('username', '<b>Username</b>', 'required|alpha_dash|min_length[6]|max_length[15]|unique[sys_administrator.administrator_username.administrator_id.' . $this->session->userdata('administrator_id') . ']');
        $this->form_validation->set_rules('name', '<b>Name</b>', 'required');
        $this->form_validation->set_rules('email', '<b>Email</b>', 'valid_email');
        $this->form_validation->set_rules('mobilephone', '<b>Mobilephone Number</b>', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('confirmation', '<div class="error alert alert-danger">' . validation_errors() . '</div>');
            $this->session->keep_flashdata('confirmation');
            redirect('profile/systems/profile');
        } else {
            $this->load->library('upload');
            $this->load->library('image_lib');

            $is_error = FALSE;
            $is_error_upload = FALSE;

            $this->db->trans_begin();

            try {

                $administrator_id = $this->session->userdata('administrator_id');
                $administrator_username = $this->input->post('username');
                $administrator_name = $this->input->post('name');
                $administrator_email = $this->input->post('email');
                $administrator_old_image = $this->input->post('old_image');
                $administrator_mobilephone = $this->input->post('mobilephone');

                $data = array();
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
                        if ($administrator_old_image != '' && file_exists(_dir_administrator . $administrator_old_image)) {
                            @unlink(_dir_administrator . $administrator_old_image);
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

                $this->session->set_userdata($data);
            } catch (Exception $ex) {
                $is_error = TRUE;
            }

            $error_upload = ($is_error_upload) ? $this->upload->display_errors() : '';

            if (!$is_error) {

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();

                    $this->session->set_flashdata('confirmation', '<div class="confirmation alert alert-danger">Failed to change profile. Please try again.</div>');
                    $this->session->keep_flashdata('confirmation');
                    redirect('profile/systems/profile');
                } else {
                    $this->db->trans_commit();

                    $this->session->set_flashdata('confirmation', '<div class="confirmation alert alert-success">Success to change profile. ' . $error_upload . '</div>');
                    $this->session->keep_flashdata('confirmation');
                    redirect('profile/systems/profile');
                }
            } else {
                $this->db->trans_rollback();

                $this->session->set_flashdata('confirmation', '<div class="confirmation alert alert-danger">Failed to change profile. Please try again.</div>');
                $this->session->keep_flashdata('confirmation');
                redirect('profile/systems/profile');
            }
        }
    }

    function password() {
        $data['arr_breadcrumbs'] = array(
            'My Password' => 'profile/systems/password',
        );

        $data['query'] = $this->function_lib->get_detail_data('sys_administrator', 'administrator_id', $this->session->userdata('administrator_id'));
        $data['form_action'] = 'profile/systems/act_password';

        $this->template->content("profile/password_edit_view", $data);
        $this->template->show('template');
    }

    function act_password() {
        $this->load->library('form_validation');

        if ($this->session->userdata('administrator_group_type') != 'superuser') {
            $this->form_validation->set_rules('old_password', '<b>Old Password</b>', 'required|callback_password_check');
        }
        $this->form_validation->set_rules('password', '<b>New Password</b>', 'required|min_length[6]|max_length[12]|matches[password_conf]');
        $this->form_validation->set_rules('password_conf', '<b>Repeat New Password</b>', 'required');

        if ($this->form_validation->run($this) == FALSE) {
            $this->session->set_flashdata('confirmation', '<div class="error alert alert-danger">' . validation_errors() . '</div>');
            $this->session->keep_flashdata('confirmation');
            redirect('profile/systems/password');
        } else {

            $is_error = FALSE;

            $this->db->trans_begin();

            try {

                $administrator_id = $this->session->userdata('administrator_id');
                $administrator_password = $this->input->post('password');
                $pass_hash = password_hash($administrator_password, PASSWORD_DEFAULT);
                $data = array();
                $data['administrator_password'] = $pass_hash;

                $this->db->where('administrator_id', $administrator_id);
                $this->db->update('sys_administrator', $data);

                if ($this->db->affected_rows() < 0) {
                    $is_error = TRUE;
                }

                $this->session->set_userdata($data);
            } catch (Exception $ex) {
                $is_error = TRUE;
            }

            if (!$is_error) {

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();

                    $this->session->set_flashdata('confirmation', '<div class="confirmation alert alert-danger">Failed to change password. Please try again.</div>');
                    $this->session->keep_flashdata('confirmation');
                    redirect('profile/systems/password');
                } else {
                    $this->db->trans_commit();

                    $this->session->set_flashdata('confirmation', '<div class="confirmation alert alert-success">Success to change password.</div>');
                    $this->session->keep_flashdata('confirmation');
                    redirect('profile/systems/password');
                }
            } else {
                $this->db->trans_rollback();

                $this->session->set_flashdata('confirmation', '<div class="confirmation alert alert-danger">Failed to change password. Please try again.</div>');
                $this->session->keep_flashdata('confirmation');
                redirect('profile/systems/password');
            }
        }
    }

    function password_check($str) {
        $password = $this->session->userdata('administrator_password');
        if (password_verify($str, $password)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('password_check', '%s is wrong. Please try again.');
            return FALSE;
        }
    }

}
