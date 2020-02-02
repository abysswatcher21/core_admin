<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (ENVIRONMENT != 'development') {
            if (php_sapi_name() !== "cli") {
                exit('No direct script access allowed');
            }
        }
    }

}
