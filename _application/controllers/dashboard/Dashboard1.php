<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard1 extends Backend_controller {

    public function __construct() {
        parent::__construct();
    }

    function index() {
        $this->show();
    }

    function show() {

        $this->template->content("dashboard/dashboard1");
        $this->template->show('template');
    }

}
