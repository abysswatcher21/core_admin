<?php

/*
 * Media Image Controller
 *
 * @author	Agus Heriyanto
 * @copyright	Copyright (c) 2014, Esoftdream.net
 */

// -----------------------------------------------------------------------------
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Images extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        redirect();
    }

    function get_image() {
//        echo $this->uri->segment(7);
        if($this->uri->segment(7) != '') {
            $data['data_doc_root'] = _doc_root;
            $data['data_dir_cache'] = _dir_cache;
            $data['data_dir'] = '/' . $this->uri->segment(2) . '/' . $this->uri->segment(3) . '/' . $this->uri->segment(4) . '/';
            $data['data_width'] = $this->uri->segment(5);
            $data['data_height'] = $this->uri->segment(6);
            $data['filename'] = $this->uri->segment(7);
            $data['data_images'] = $data['data_dir'] . $data['filename'];
            $data['data_color'] = 'ffffff';
            $data['data_cropratio'] = '';
            $data['data_quality'] = 90;
            $data['data_nocache'] = '';
            $this->load->view('media/images_view', $data);
        } else {
            redirect();
        }
    }

}
