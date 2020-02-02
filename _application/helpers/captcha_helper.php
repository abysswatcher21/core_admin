<?php

/*
 * Template Helper
 *
 * @author	Agus Heriyanto
 * @copyright	Copyright (c) 2014, Esoftdream.net
 */

// -----------------------------------------------------------------------------

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/securimage/securimage.php';

function captcha($arr_config = array()) {
    $captcha = new Securimage();
    if (is_array($arr_config)) {
        foreach ($arr_config as $key => $value) {
            if (isset($captcha->$key)) {
                $captcha->$key = $value;
            }
        }
    }

    $captcha->show(APPPATH . 'libraries/securimage/backgrounds/bg3.png');
}

