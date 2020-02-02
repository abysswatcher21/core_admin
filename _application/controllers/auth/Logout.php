<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        
         $array_items = array(
            'administrator_id',
            'administrator_group_id',
            'administrator_group_title',
            'administrator_group_type',
            'administrator_username',
            'administrator_name',
            'administrator_password',
            'administrator_email',
            'administrator_image',
            'administrator_last_login',
            'administrator_last_username',
            'administrator_last_last_login',
            'administrator_last_name',
            'administrator_logged_in',
            'filemanager'
        );
        
        $this->session->unset_userdata($array_items);
        
        if ($this->session->userdata('administrator_logged_in')) {
            $this->session->sess_destroy();
        }
        
        $redirect = _login_uri;
        redirect($redirect);
    }

}