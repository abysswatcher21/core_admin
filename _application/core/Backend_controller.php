<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backend_controller extends MY_Controller{

    public $ref_action_name = array();

    public function __construct()
    {
        parent::__construct();
        //ini buat clear cache agar kalo setelah logout tidak bisa di back lewat button back di browser
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        $this->load->library('authentication');

        if(!$this->authentication->auth_user()) {
            //show_error('Shove off, this is for admins.');
//            $referer = rawurlencode('http://' . $_SERVER['HTTP_HOST'] . preg_replace('@/+$@', '', $_SERVER['REQUEST_URI']) . '/');
//            $origin = isset($_SERVER['HTTP_REFERER']) ? rawurlencode($_SERVER['HTTP_REFERER']) : $referer;
//            $redirect = _backend_login_uri . '?redirect_url=' . $origin;
            if ($this->input->is_ajax_request()) {
                die("expired#");
            }else{
                $origin = $this->uri->uri_string();
                $this->session->set_flashdata('redirect_url', $origin);
                $this->session->keep_flashdata('redirect_url');
                redirect(_login_uri);
            }

        } else if(!$this->authentication->privilege_user()) {
            //show_error('Shove off, this is for admins.');
            if ($this->input->is_ajax_request()) {
                die("Unauthorized#");
            }else{
                $this->session->set_flashdata('confirmation', '<div class="error alert alert-danger">You are not authorized.</div>');
                redirect('dashboard/dashboard1');
            }

        } else {
            $arr_ref_action_name = $this->authentication->get_ref_action_name();
            if (!empty($arr_ref_action_name)) {
                $this->ref_action_name = $arr_ref_action_name;
            }

            return TRUE;
        }

    }

}
