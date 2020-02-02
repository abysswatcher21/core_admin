<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler(true);
        $this->site_configuration = $this->function_lib->get_site_configuration();
        // $this->arr_flashdata = $this->custom_session->all_flashdata();
        define('THEMES_BACKEND' , site_url('themes/backend/'. $this->site_configuration['backend_themes']));
        
        $this->menu_info = $this->function_lib->get_info_menu();
//        print_r($this->menu_info);
        
    }

    public function createRespon($code = 200, $msg = 'OK', $data){
      $pesan = array(
          'status_code' => $code,
          'message' => $msg
      );

      $pesan = array(
        'status_code' => $code,
        'message' => $msg,
        'data' => $data
      );
      return json_encode($pesan);
    }

  function pagination($total, $pagenum , $end  ){
    $total_page = ceil($total / $end);

    //------------- Prev page
    $prev = $pagenum - 1;
    if ($prev < 1 ) {
        $prev = 0;
    }
    //------------------------

    //------------- Next page
    $next       = $pagenum + 1 ;
    if ($next > $total_page ) {
        $next = 0;
    }
    //----------------------

    $from       = 1;
    $to         = $total_page;

    $to_page    =  $pagenum - 2 ;
    if ($to_page > 0) {
      $from = $to_page;
    }

    if ($total_page >= 5) {
      if ($total_page > 0 ) {
        $to = 5 + $to_page;
        if ($to > $total_page ) {
          $to = $total_page;
        }
      } else {
        $to   = 5;
      }
    }

    #looping kotak pagination
    $firstpage_istrue = FALSE;
    $lastpage_istrue = FALSE;
    if ($total_page <= 1) {
      $detail = [];
    }
    else {
      for ($i=$from;$i<=$to;$i++) {
        $detail[]= $i;
      }
      if ($from != 1) {
        $firstpage_istrue = TRUE;
      }
      if ($to != $total_page) {
        $lastpage_istrue = TRUE;
      }
    }

    $pagination = array (
      'total_item'   => $total,
      'total_page'   => $total_page,
      'first_page'   => $firstpage_istrue,
      'last_page'    => $lastpage_istrue,
      'prev'         => $prev,
      'current'      => $pagenum,
      'next'         => $next,
      'detail'       => json_encode($detail)
    );

    return $pagination;
  }
}

/* End of file ControllerName.php */
/* Location: ./application/controllers/ControllerName.php */
