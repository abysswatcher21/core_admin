<?php

/*
 * Captcha Libraries
 *
 * @author	Agus Heriyanto
 * @copyright	Copyright (c) 2014, Esoftdream.net
 */

// -----------------------------------------------------------------------------

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once _doc_root . 'addons/securimage/securimage.php';

class Captcha extends Securimage {
    
    function __construct() {
        parent::__construct();
    }
    
    public function generate_image($arr_config = array()) {
        $captcha = new Securimage();
        $background_image = '';
        if (is_array($arr_config)) {
            foreach ($arr_config as $key => $value) {
                if (isset($captcha->$key)) {
                    $captcha->$key = $value;
                }
            }
            if(array_key_exists('background_image', $arr_config)) {
                $background_image = $arr_config['background_image'];
            }
        }
        $captcha->ttf_file = _doc_root . 'addons/securimage/fonts/mangalb.ttf';
        $captcha->charset = 'abcdefghkmnprstuvwyz';
        $captcha->code_length = rand(5, 6);
        $captcha->use_transparent_text = true;
        $captcha->text_transparency_percentage = 40;
        $captcha->text_angle_minimum = 0;
        $captcha->text_angle_maximum = 10;
        $captcha->perturbation = 0;
        $captcha->iscale = 10;
        $captcha->num_lines = 0;
        $captcha->use_multi_text = true;
        if(!isset($arr_config['multi_text_color'])) {
            $captcha->multi_text_color = array(
                new Securimage_Color("#3776c3"),
                new Securimage_Color("#56bebe"),
                new Securimage_Color("#e15a5a"),
                new Securimage_Color("#c4a137"),
                new Securimage_Color("#4cc843"),
            );
        }
        
        $captcha->show($background_image);
    }
    
    public function verify($string = '') {
        $captcha = new Securimage();
        return $captcha->check($string);
    }

}

?>
