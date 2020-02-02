<?php

/*
 * Upload Extended Libraries
 *
 * @author	Agus Heriyanto
 * @copyright	Copyright (c) 2014, Esoftdream.net
 */

// -----------------------------------------------------------------------------

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Upload extends CI_Upload {

    function __construct() {
        parent::__construct();
    }

    //use this to uploading file
    //fileUpload(field_name, file_destination, file_types, file_max_size in kilobytes (optional), file_max_width in pixel (optional), file_max_height in pixel (optional));
    function fileUpload($field, $path, $types, $max_size = '0', $max_width = '0', $max_height = '0') {
        $config['upload_path'] = $path;
        $config['allowed_types'] = $types;
        $config['max_size'] = $max_size;
        $config['max_width'] = $max_width;
        $config['max_height'] = $max_height;
        $config['overwrite'] = false;

        $this->initialize($config);

        return $this->do_upload($field);
    }

    /**
     * Verify that the filetype is allowed
     *
     * @access	public
     * @return	bool
     */
//    function is_allowed_filetype($ignore_mime = false) {
//        if (count($this->allowed_types) == 0 OR !is_array($this->allowed_types)) {
//            $this->set_error('upload_no_file_types');
//            return FALSE;
//        }
//
//        $image_types = array('gif', 'jpg', 'jpeg', 'png', 'jpe');
//
//        foreach ($this->allowed_types as $val) {
//            $mime = $this->mimes_types(strtolower($val));
//
//            // Images get some additional checks
//            if (in_array($val, $image_types) && $this->is_image()) {
//                if (getimagesize($this->file_temp) === FALSE) {
//                    return FALSE;
//                }
//            }
//
//            if (is_array($mime)) {
//                if (in_array($this->file_type, $mime, TRUE)) {
//                    return TRUE;
//                }
//            } else {
//                if ($mime == $this->file_type) {
//                    return TRUE;
//                }
//            }
//        }
//
//        return FALSE;
//    }

    // --------------------------------------------------------------------
}

?>
