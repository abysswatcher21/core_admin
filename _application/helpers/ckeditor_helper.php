<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * CKEditor helper for CodeIgniter
 * 
 * @author Samuel Sanchez <samuel.sanchez.work@gmail.com> - http://kromack.com/
 * @package CodeIgniter
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/us/
 * @tutorial http://kromack.com/developpement-php/codeigniter/ckeditor-helper-for-codeigniter/
 * @see http://codeigniter.com/forums/viewthread/127374/
 * @version 2010-08-28
 * 
 */


/**
 * CKEditor Textarea field
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if (!function_exists('form_textarea_ckeditor')) {

    function form_textarea_ckeditor($fieldname = '', $value = '', $toolbar = 'Basic', $extra = '', $area_width = "100%", $area_height = '500') {
        $CI = & get_instance();
        
        if(!isset($_SESSION['filemanager']) && $CI->session->userdata('filemanager')) {
            $_SESSION['filemanager'] = $CI->session->userdata('filemanager');
        }
        
        //CKEditor's configuration
        $ckeditor = array(
            'id' => $fieldname,
            'path' => 'addons/ckeditor',
            'config' => array(
                'toolbar' => $toolbar, //options: PageGenerator, Standard, Basic
                'width' => $area_width,
                'height' => $area_height,
                'filebrowserBrowseUrl' => base_url() . 'addons/ckeditor/kcfinder/browse.php?opener=ckeditor&type=files',
                'filebrowserImageBrowseUrl' => base_url() . 'addons/ckeditor/kcfinder/browse.php?opener=ckeditor&type=images',
                'filebrowserFlashBrowseUrl' => base_url() . 'addons/ckeditor/kcfinder/browse.php?opener=ckeditor&type=flash',
                'filebrowserUploadUrl' => base_url() . 'addons/ckeditor/kcfinder/upload.php?opener=ckeditor&type=files',
                'filebrowserImageUploadUrl' => base_url() . 'addons/ckeditor/kcfinder/upload.php?opener=ckeditor&type=images',
                'filebrowserFlashUploadUrl' => base_url() . 'addons/ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash',
            ),
        );

        $content = '';
        //$content .= '<textarea name="' . $fieldname . '" id="' . $fieldname . '" >' . $value . '</textarea>';
        $content .= '<textarea id="' . $fieldname . '" name="' . $fieldname . '" ' . $extra . ' style="width:' . $area_width . '; height:' . $area_height . 'px;">' . $value . '</textarea>';
        $content .= display_ckeditor($ckeditor);

        return $content;
    }

}

/**
 * This function adds once the CKEditor's config vars
 * @author Samuel Sanchez 
 * @access private
 * @param array $data (default: array())
 * @return string
 */
function cke_initialize($data = array()) {

    $return = '';

    if (!defined('CI_CKEDITOR_HELPER_LOADED')) {

        define('CI_CKEDITOR_HELPER_LOADED', TRUE);
        $return = '<script type="text/javascript" src="' . base_url() . $data['path'] . '/ckeditor.js"></script>';
        $return .= "<script type=\"text/javascript\">CKEDITOR_BASEPATH = '" . base_url() . $data['path'] . "/';</script>";
    }

    return $return;
}

/**
 * This function create JavaScript instances of CKEditor
 * @author Samuel Sanchez 
 * @access private
 * @param array $data (default: array())
 * @return string
 */
function cke_create_instance($data = array()) {

    $return = "<script type=\"text/javascript\">
     	CKEDITOR.replace('" . $data['id'] . "', {";

    //Adding config values
    if (isset($data['config'])) {
        foreach ($data['config'] as $k => $v) {

            // Support for extra config parameters
            if (is_array($v)) {
                $return .= $k . " : [";
                $return .= config_data($v);
                $return .= "]";
            } else {
                $return .= $k . " : '" . $v . "'";
            }

            if ($k !== end(array_keys($data['config']))) {
                $return .= ",";
            }
        }
    }

    $return .= '});';
    $return .= '</script>';

    return $return;
}

/**
 * This function displays an instance of CKEditor inside a view
 * @author Samuel Sanchez 
 * @access public
 * @param array $data (default: array())
 * @return string
 */
function display_ckeditor($data = array()) {
    // Initialization
    $return = cke_initialize($data);

    // Creating a Ckeditor instance
    $return .= cke_create_instance($data);


    // Adding styles values
    if (isset($data['styles'])) {

        $return .= "<script type=\"text/javascript\">CKEDITOR.addStylesSet( 'my_styles_" . $data['id'] . "', [";


        foreach ($data['styles'] as $k => $v) {

            $return .= "{ name : '" . $k . "', element : '" . $v['element'] . "', styles : { ";

            if (isset($v['styles'])) {
                foreach ($v['styles'] as $k2 => $v2) {

                    $return .= "'" . $k2 . "' : '" . $v2 . "'";

                    if ($k2 !== end(array_keys($v['styles']))) {
                        $return .= ",";
                    }
                }
            }

            $return .= '} }';

            if ($k !== end(array_keys($data['styles']))) {
                $return .= ',';
            }
        }

        $return .= ']);';

        $return .= "CKEDITOR.instances['" . $data['id'] . "'].config.stylesCombo_stylesSet = 'my_styles_" . $data['id'] . "';";
	$return .= "</script>";
    }

    return $return;
}

/**
 * config_data function.
 * This function look for extra config data
 *
 * @author ronan
 * @link http://kromack.com/developpement-php/codeigniter/ckeditor-helper-for-codeigniter/comment-page-5/#comment-545
 * @access public
 * @param array $data. (default: array())
 * @return String
 */
function config_data($data = array()) {
    $return = '';
    foreach ($data as $key) {
        if (is_array($key)) {
            $return .= "[";
            foreach ($key as $string) {
                $return .= "'" . $string . "'";
                if ($string != end(array_values($key)))
                    $return .= ",";
            }
            $return .= "]";
        }
        else {
            $return .= "'" . $key . "'";
        }
        if ($key != end(array_values($data)))
            $return .= ",";
    }
    return $return;
}