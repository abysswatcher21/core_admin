<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('auth/login_model');
    }

    public function index() {
        $this->login();
    }

    public function login() {

        //ini buat clear cache agar kalo setelah login tidak bisa di back lewat button back di browser
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        //cek apakah masih ada session administrator
        if ($this->session->userdata('administrator_logged_in')) {
            redirect('dashboard/dashboard1');
        } else {
            $this->load->helper('form');
            if (isset($_SESSION['redirect_url']) && trim($_SESSION['redirect_url']) != '') {
                $data['redirect_url'] = $_SESSION['redirect_url'];
            } else {
                $data['redirect_url'] = '';
            }
            $this->template->content("auth/login_view", $data);
            $this->template->show('template_login');
        }
    }

    public function verify() {
        $this->load->library('form_validation');
        $this->load->library('function_lib');

        $this->form_validation->set_rules('username', '<b>Username</b>', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('password', '<b>Password</b>', 'trim|htmlspecialchars|required');
        $this->form_validation->set_rules('kode_unik', '<b>Kode unik</b>', 'callback_check_captcha');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('confirmation', validation_errors());
            $this->session->set_flashdata('username', $this->input->post('username'));
            $this->session->keep_flashdata(['confirmation', 'username']);

            $redirect_url = $this->input->post('redirect_url');
            if (trim($redirect_url) != '') {
                $this->session->set_flashdata('redirect_url', $redirect_url);
                $this->session->keep_flashdata('redirect_url');
            } else {
                $redirect = _login_uri;
            }
        } else {
            $username = addslashes($this->input->post('username'));
            $password = addslashes($this->input->post('password'));

            $redirect_url = $this->input->post('redirect_url');
            $query = $this->login_model->get_data_administrator_by_username($username);
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $pass_verify = $row->administrator_password;
                if (($row->administrator_username === $username) && password_verify($password, $pass_verify)) {
                    // if (($row->administrator_username === $username)) {
                    if ($row->administrator_group_is_active == '0') {

                        //group_inactive
                        $this->session->set_flashdata('confirmation', '<p>Grup Akun Anda tidak aktif.</p><p>Silakan hubungi Administrator Pusat untuk mengaktifkan Grup Akun Anda.</p>');
                        $this->session->set_flashdata('username', $this->input->post('username'));
                        $this->session->keep_flashdata(['confirmation', 'username']);
                        if (trim($redirect_url) != '') {
                            $this->session->set_flashdata('redirect_url', $redirect_url);
                            $this->session->keep_flashdata('redirect_url');
                        } else {
                            $redirect = _login_uri;
                        }
                    } elseif ($row->administrator_is_active == '0') {

                        //inactive
                        $this->session->set_flashdata('confirmation', '<p>Akun Anda tidak aktif.</p><p>Silakan hubungi Administrator Pusat untuk mengaktifkan Akun Anda.</p>');
                        $this->session->set_flashdata('username', $this->input->post('username'));
                        $this->session->keep_flashdata(['confirmation', 'username']);
                        if (trim($redirect_url) != '') {
                            $this->session->set_flashdata('redirect_url', $redirect_url);
                            $this->session->keep_flashdata('redirect_url');
                        } else {
                            $redirect = _login_uri;
                        }
                    } else {

                        //sukses
                        $query_last_login = $this->login_model->get_data_administrator_last_login();
                        $row_last_login = $query_last_login->row();

                        $array_items = array(
                            'administrator_id' => $row->administrator_id,
                            'administrator_group_id' => $row->administrator_group_id,
                            'administrator_group_title' => $row->administrator_group_title,
                            'administrator_group_type' => $row->administrator_group_type,
                            'administrator_group_company_id' => $row->administrator_group_company_id,
                            'administrator_group_warehouse_id' => $row->administrator_group_warehouse_id,
                            'administrator_group_pos_id' => $row->administrator_group_pos_id,
                            'administrator_username' => $row->administrator_username,
                            'administrator_name' => $row->administrator_name,
                            'administrator_password' => $row->administrator_password,
                            'administrator_email' => $row->administrator_email,
                            'administrator_image' => $row->administrator_image,
                            'administrator_last_login' => $row->administrator_last_login,
                            'administrator_logged_in' => TRUE,
                            'administrator_last_last_login' => $row_last_login->administrator_last_login,
                            'administrator_last_username' => $row_last_login->administrator_username,
                            'administrator_last_name' => $row_last_login->administrator_name,
                            'is_superuser' => ($row->administrator_group_type == 'superuser' ? 'true' : 'false'),
                            'filemanager' => TRUE
                        );

                        $this->session->set_userdata($array_items);

                        $data = array();
                        $data['administrator_last_login'] = date('Y-m-d H:i:s');
                        $this->function_lib->update_data('sys_administrator', 'administrator_id', $row->administrator_id, $data);

                    }
                } else {

                    //password salah
                    $this->session->set_flashdata('confirmation', '<p><b>Username</b> atau <b>Password</b> yang Anda masukkan salah.</p>');
                    $this->session->set_flashdata('username', $this->input->post('username'));
                    $this->session->keep_flashdata(['confirmation', 'username']);
                    if (trim($redirect_url) != '') {
                        $this->session->set_flashdata('redirect_url', $redirect_url);
                        $this->session->keep_flashdata('redirect_url');
                    } else {
                        $redirect = _login_uri;
                    }
                }
            } else {

                //data tidak ditemukan
                $this->session->set_flashdata('confirmation', '<p><b>Username</b> atau <b>Password</b> yang Anda masukkan salah.</p>');
                $this->session->set_flashdata('username', $this->input->post('username'));
                $this->session->keep_flashdata(['confirmation', 'username']);
                if (trim($redirect_url) != '') {
                    $this->session->set_flashdata('redirect_url', $redirect_url);
                    $this->session->keep_flashdata('redirect_url');
                } else {
                    $redirect = _login_uri;
                }
            }
        }
        redirect($redirect);
    }

    function check_captcha($str) {
        $this->load->library('captcha');
        if ($this->captcha->verify($str)) {
            return true;
        } else {
            $this->form_validation->set_message('check_captcha', '{field} yang anda masukkan salah.');
            return false;
        }
    }

    public function captcha() {
        $this->load->library('captcha');
        $config = array(
            'background_image' => _dir_captcha . 'captcha-login-1.png',
            'image_width' => 265,
            'image_height' => 54,
        );
        $this->captcha->generate_image($config);
    }

}
