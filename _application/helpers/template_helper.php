<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  --------------------------------------------------------------------------------
  --------------------------------------------------------------------------------
  template_helper.php
  CodeIgniter Template Library
  Hubert Ursua
  https://github.com/hyubs/CodeIgniter-Template-Library
  --------------------------------------------------------------------------------
  --------------------------------------------------------------------------------
 */

/*
  Echoes the content of the template parameter based on the specified position.
  Prints blank ("") if template parameter has no content.
 */

function template_echo($position) {
    $CI = & get_instance();
    echo $CI->template->get($position);
}

/*
  Retrieves the content of the template parameter based on the specified position.
  Returns blank ("") if template parameter has no content.
 */

function template_get($position) {
    $CI = & get_instance();
    return $CI->template->get($position);
}
