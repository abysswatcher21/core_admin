<?php

/*
 * TinyMCE Helper
 *
 * @author	Agus Heriyanto
 * @copyright	Copyright (c) 2014, Esoftdream.net
 */

// -----------------------------------------------------------------------------

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * TinyMCE Textarea field
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if (!function_exists('form_textarea_tinymce')) {

    function form_textarea_tinymce($fieldname = '', $value = '', $toolbar = 'Basic', $extra = '', $area_width = '100%', $area_height = '500') {
        $CI = & get_instance();
        
        if(!isset($_SESSION['filemanager']) && $CI->session->userdata('filemanager')) {
            $_SESSION['filemanager'] = $CI->session->userdata('filemanager');
        }
        
        $path = 'addons/tinymce';
        $theme = 'modern';
        
        switch($toolbar) {
            
            case "PageGenerator":
                $plugins_string = '
                    "advlist autolink link image lists charmap preview hr pagebreak",
                    "searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking",
                    "table contextmenu directionality emoticons paste textcolor fullscreen responsivefilemanager youtube mcegmaps"
                ';
                
                $toolbar_string = '
                    toolbar1: "code preview | cut copy paste undo redo | bullist numlist outdent indent alignleft aligncenter alignright alignjustify | image media youtube mcegmaps | table hr smiley charmap",
                    toolbar2: "bold italic underline strikethrough subscript superscript removeformat | link | styleselect | fontselect | fontsizeselect | forecolor backcolor | visualblocks",
                ';
                
                //menubar: "file edit insert view format table tools",
                $config_string = '
                    menubar: true,
                    image_advtab: true,
                    external_filemanager_path:"' . base_url() . $path . '/filemanager/",
                    filemanager_title:"File Manager" ,
                    external_plugins: { "filemanager" : "' . base_url() . $path . '/filemanager/plugin.min.js"}
                ';
                break;
            
            case "Standard":
                $plugins_string = '
                    "advlist autolink link image lists charmap preview hr",
                    "wordcount visualblocks visualchars code nonbreaking",
                    "table contextmenu directionality paste fullscreen responsivefilemanager"
                ';
                
                $toolbar_string = '
                    toolbar: "code preview | bold italic underline strikethrough | cut copy paste undo redo | link | image table hr charmap | styleselect | visualblocks",
                ';
                
                //menubar: "file edit insert view format table tools",
                $config_string = '
                    menubar: false,
                    image_advtab: true,
                    external_filemanager_path:"' . base_url() . $path . '/filemanager/",
                    filemanager_title:"File Manager" ,
                    external_plugins: { "filemanager" : "' . base_url() . $path . '/filemanager/plugin.min.js"}
                ';
                break;
            
            default:
                $plugins_string = '
                    "preview hr wordcount paste"
                ';
                
                $toolbar_string = '
                    toolbar: "bold italic underline strikethrough | cut copy paste",
                ';
                
                //menubar: "file edit insert view format table tools",
                //menubar: false,
                $config_string = '
                    menubar: false
                ';
                break;
        }
        
        $content = '';
        $content .= tinymce_initialize($path);
        $content .= '
        <script type="text/javascript">
            tinymce.init({
                selector: "textarea#' . $fieldname . '",
                theme: "' . $theme . '",
                width: "' . $area_width . '",
                height: "' . $area_height . '",
                plugins: [' . $plugins_string . '],
                ' . $toolbar_string . '
                ' . $config_string . '
             });
        </script>
        ';
        $content .= '<textarea id="' . $fieldname . '" name="' . $fieldname . '" ' . $extra . ' style="width:' . $area_width . '; height:' . $area_height . 'px;">' . $value . '</textarea>';

        return $content;
    }

}

/**
 * This function adds once the TinyMCE's config vars
 * @access private
 * @param array $data (default: array())
 * @return string
 */
function tinymce_initialize($path) {
    
    $return = '';
    if (!defined('CI_TINYMCE_HELPER_LOADED')) {
        define('CI_TINYMCE_HELPER_LOADED', TRUE);
        $return = '<script type="text/javascript" src="' . base_url() . $path . '/tinymce.min.js"></script>';
    }

    return $return;
}
