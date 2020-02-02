<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function pagination($url, $rowscount, $per_page, $segment = 5){
	$CI =& get_instance();
	$CI->load->library('pagination');

	$config = array();
	$config["base_url"] = base_url($url);
	$config["total_rows"] = $rowscount;
	$config["per_page"] = $per_page;
	$config["uri_segment"] = $segment;
	$config['use_page_numbers'] = TRUE;
	$config['full_tag_open'] = '<nav><ul class="pagination">';
	$config['full_tag_close'] = '</ul></nav>';
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';
	$config['cur_tag_open'] = '<li class="active"><a>';
	$config['cur_tag_close'] = '</a></li>';
	$config['next_tag_open'] = '<li>';
	$config['next_tag_close'] = '</li>';
	$config['prev_tag_open'] = '<li>';
	$config['prev_tag_close'] = '</li>';
	$config['first_link'] = 'First';
	$config['first_tag_open'] = '<li>';
	$config['first_tag_close'] = '</li>';
	$config['last_link'] = 'Last';
	$config['last_tag_open'] = '<li>';
	$config['last_tag_close'] = '</li>';
	$CI->pagination->initialize($config);
	return $CI->pagination->create_links();
}

?>
