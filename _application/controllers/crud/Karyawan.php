<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class karyawan extends Backend_controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
    }

    function index() {
        $this->show();
    }

    function show() {
        if (!empty($this->menu_info)) {
            if (!empty($this->menu_info->parent_title)) {
                $data['arr_breadcrumbs'] = array(
                    $this->menu_info->parent_title => $this->menu_info->parent_link,
                    $this->menu_info->menu_title => $this->menu_info->menu_link
                );
            } else {
                $data['arr_breadcrumbs'] = array(
                    $this->menu_info->menu_title => $this->menu_info->menu_link
                );
            }
        }
           $sql = "
                SELECT *
                FROM sys_officer
            ";

        $officer = $this->db->query($sql)->result();

        $data['officer'] = $officer;


        $this->template->content("crud/karyawan_list_view",$data);
        $this->template->show('template');
    }

    

    function get_data(){
        $params = isset($_POST) ? $_POST : array();
        $params['table'] = "sys_officer";
        $result = $this->function_lib->get_query_data($params);
 
        $query = $result['data'];
        $total = $result['total'];

        header("Content-type: application/json");
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $json_data = array('page' => $page, 'total' => $total, 'rows' => array());
        foreach($query->result() as $row){
                        $detail = '<a href="javascript:;" onclick="return detailKaryawan(' . $row->karyawan_id . ')"><img src="' . base_url() . _dir_icon . 'window_image_small.png" border="0" alt="Detail" title="Detail" /></a>';
                        $edit = '<a href="javascript:;" onclick="return editKaryawan(' . $row->karyawan_id . ')"><img src="' . base_url() . _dir_icon . 'save_labled_edit.png" border="0" alt="Ubah" title="Ubah" /></a>';
                        $entry = array('id'=>$row->karyawan_id,
                        'cell' => array(
                            'karyawan_id' => $row->karyawan_id,
                            'nama_karyawan'  => $row->nama_karyawan,
                            'alamat_karyawan'=> $row->alamat_karyawan,
                            'gajih_karyawan' => $row->gajih_karyawan,
                            'detail' => $detail,
                            'edit' => $edit
                        )          
                    );         
                    $json_data['rows'][] = $entry;   
   
        }
        echo json_encode($json_data,JSON_PRETTY_PRINT);

    }

    function get_data_by_id(){
        $id =  $this->input->get('id');
        if(!empty($id) && is_numeric($id)){
            header("Content-type: application/json");

            $sql = "SELECT * FROM sys_officer WHERE karyawan_id=".$id."";
            $data = $this->db->query($sql)->row();
            echo json_encode($data);
            
            $response = array(
                'status' => 200,
                'msg' => 'done'
            );            
        }else{
            show_404();
        }
    }

    function act_add() {

            $this->db->trans_begin();
        
            header("Content-type: application/json");
            
            $this->form_validation->set_rules('nama_karyawan','Nama Karyawan','required|max_length[30]');
            $this->form_validation->set_rules('alamat_karyawan','Alamat Karyawan','required|max_length[50]');
            $this->form_validation->set_rules('gajih_karyawan','Gajih Karyawan','required|max_length[15]');
            if ($this->form_validation->run($this) == FALSE) {

                $response = array(
                    'status' => 400,
                    'msg' => validation_errors()
                );
            }else{
                $this->load->library('upload');
                $this->load->library('image_lib');
                try{
                $nama_karyawan = $this->input->post('nama_karyawan');
                $alamat_karyawan = $this->input->post('alamat_karyawan');
                $gajih_karyawan =  $this->input->post('gajih_karyawan');


                $data = array();
                $data['nama_karyawan'] = $nama_karyawan;
                $data['alamat_karyawan'] = $alamat_karyawan;
                $data['gajih_karyawan'] = $gajih_karyawan;
                
                if (!empty($_FILES['logo']['tmp_name'])) {
                 
                    if ($this->upload->fileUpload('logo', 'assets/images/crud/', 'jpg|jpeg|gif|png', 1024)) {
                        $upload = $this->upload->data();
                    
                        $size = getimagesize($upload['full_path']);
                        $width = $size[0];
                        $height = $size[1];

                        if ($width != 250 || $height != 250) {
                            $this->image_lib->resizeImage($upload['full_path'], 250, 250);
                            $this->image_lib->cropCenterImage($upload['full_path'], 250, 250);
                        }

                        $image_filename = url_title($nama_karyawan) . '-' . date("YmdHis") . strtolower($upload['file_ext']);
                        rename($upload['full_path'], $upload['file_path'] . $image_filename);
                        $data['foto_karyawan'] = $image_filename;
                    
                    
                    } else {
                        
                        $is_error_upload = TRUE;
                        $data['foto_karyawan'] = '';
                    }
                }
            
                $this->db->insert('sys_officer', $data);    
                $is_error = FALSE;
                
                }catch(exception $ex){
                    $is_error = TRUE;
                }
            

            if(!$is_error) {
                if($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    $response = array(
                        'status' => 400,
                        'msg' => 'Failed to add data! Please try again.'
                    );
                }else {
                        $this->db->trans_commit();

                        $response = array(
                            'status' => 200,
                            'msg' => 'Success to add data. ' 
                       
                        );
                       
                    }
            }else{
                show_404();
            }
            echo json_encode($response);
        }
          
    }

    function act_update(){
        $this->db->trans_begin();
        
        header("Content-type: application/json");
        $this->form_validation->set_rules('nama_karyawan','Nama Karyawan','required|max_length[30]');
            $this->form_validation->set_rules('alamat_karyawan','Alamat Karyawan','required|max_length[50]');
            $this->form_validation->set_rules('gajih_karyawan','Gajih Karyawan','required|max_length[15]');
            if ($this->form_validation->run($this) == FALSE) {

                $response = array(
                    'status' => 400,
                    'msg' => validation_errors()
                );
            }else{
                $this->load->library('upload');
                $this->load->library('image_lib');

                try{
                $karyawan_id = $this->input->post('karyawan_id');
                $nama_karyawan = $this->input->post('nama_karyawan');
                $alamat_karyawan = $this->input->post('alamat_karyawan');
                $gajih_karyawan =  $this->input->post('gajih_karyawan');

                $karyawan_old_picture = $this->input->post('old_logo_edit');

                $data = array();
                $data['nama_karyawan'] = $nama_karyawan;
                $data['alamat_karyawan'] = $alamat_karyawan;
                $data['gajih_karyawan'] = $gajih_karyawan;
                if (!empty($_FILES['logo_edit']['tmp_name'])) {
                 
                    if ($this->upload->fileUpload('logo_edit', 'assets/images/crud/', 'jpg|jpeg|gif|png', 1024)) {
                        $upload = $this->upload->data();
                    
                        $size = getimagesize($upload['full_path']);
                        $width = $size[0];
                        $height = $size[1];

                        if ($width != 250 || $height != 250) {
                            $this->image_lib->resizeImage($upload['full_path'], 250, 250);
                            $this->image_lib->cropCenterImage($upload['full_path'], 250, 250);
                        }

                        $image_filename = url_title($nama_karyawan) . '-' . date("YmdHis") . strtolower($upload['file_ext']);
                        rename($upload['full_path'], $upload['file_path'] . $image_filename);

                        if ($karyawan_old_picture != '' && file_exists('assets/images/crud/' . $karyawan_old_picture)) {
                            @unlink('assets/images/crud/' . $karyawan_old_picture);
                        }

                        $data['foto_karyawan'] = $image_filename;
                    
                    
                    } else {
                        
                        $is_error_upload = TRUE;
                        $data['foto_karyawan'] = '';
                    }
                }
                
                $this->db->where('karyawan_id',$karyawan_id);
                $this->db->update('sys_officer', $data);    
                $is_error = FALSE;
                
                }catch(exception $ex){
                    $is_error = TRUE;
                }
            

                if(!$is_error) {
                    if($this->db->trans_status() === FALSE){
                        $this->db->trans_rollback();
                        $response = array(
                            'status' => 400,
                            'msg' => 'Failed to add data! Please try again.'
                        );
                    }else {
                            $this->db->trans_commit();

                            $response = array(
                                'status' => 200,
                                'msg' => 'Success to add data. ' 
                        
                            );
                        
                        }
                }else{
                    show_404();
                }
                echo json_encode($response);
        }
    }
    
   
    function act_delete() {
        if ($_SESSION['administrator_group_type'] == 'superuser') {
            $arr_output = array();
            $arr_output['message'] = '';
            $arr_output['message_class'] = '';

            //delete
            if ($this->input->post('delete') != FALSE) {
                $arr_item = json_decode($_POST['item']);
                if (is_array($arr_item)) {
                    $success = $failed = 0;
                    foreach ($arr_item as $id) {

                        $is_error = FALSE;
                        $this->db->trans_begin();
                        
                        //delete old pic 
                        $karyawan_old_picture = $this->db->select('foto_karyawan')->get_where('sys_officer',array('karyawan_id' => $id))->row('foto_karyawan');
                     
                        if($karyawan_old_picture != ''){
                            @unlink('assets/images/crud/' .$karyawan_old_picture);
                        }


                        //hapus data
                        $this->db->where('karyawan_id', $id);
                        $this->db->delete('sys_officer');

                        if ($this->db->affected_rows() < 0) {
                            $is_error = TRUE;
                        }

                        if (!$is_error) {
                            if ($this->db->trans_status() === FALSE) {
                                $this->db->trans_rollback();
                                $failed++;
                            } else {
                                $this->db->trans_commit();
                                $success++;
                            }
                        } else {
                            $this->db->trans_rollback();
                            $failed++;
                        }
                    }

                    $str_success = ($success > 0) ? $success . ' data was successfully deleted. ' : '';
                    $str_failed = ($failed > 0) ? $failed . ' data failed to delete.' : '';

                    $arr_output['message'] = $str_success . $str_failed;
                    $arr_output['message_class'] = ($failed > 0) ? 'response_error alert alert-danger' : 'response_confirmation alert alert-success';
                } else {
                    $arr_output['message'] = 'You have not selected data.';
                    $arr_output['message_class'] = 'response_error alert alert-danger';
                }
            }

            echo json_encode($arr_output);
        } else {
            show_404();
        }
    }
    
    function export_data_karyawan() {
        $params = array();
        if (isset($_POST)) {
            foreach ($_POST as $id => $value) {
                $params[$id] = $value;
            }
        }

        $params['params']['select'] = "*";
        $params['params']['table'] = "sys_officer";


        if ($params['params']['total_data'] <= 1000) {
            unset($params['params']['rp']);
            unset($params['params']['page']);
        }

        $data = array();
        $data['title'] = 'Data Karyawan';
        $data['params'] = $params;
        $data['query'] = $this->function_lib->get_query_data($params['params'])['data'];
        $data['column'] = isset($_POST['column']) ? $_POST['column'] : array();
        $column_title = json_decode($data['column']['title']);

        $data['column']['title'] = json_encode($column_title);

        $this->function_lib->export_excel_standard($data);
    }

    function act_print(){
                $sql = "SELECT * FROM sys_officer";
                $officer = $this->db->query($sql)->result();
                $data = array();
                $data['officer'] = $officer;
            
                $html = $this->load ->view("printview/Karyawan",$data,true);
            
                $this->load->library('Pdf');
                $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor("Fadhillah");
                $pdf->SetTitle("Data Karyawan");
                $pdf->SetSubject("Data_karyawan");

                $pdf->setPrintHeader(false);
                $pdf->setPrintFooter(true);

                $pdf->AddPage();



                $pdf->SetFont("times", "", 11);
                $pdf->writeHTML($html, true, false, false, false, '');

                $pdf->lastPage();

                $pdf->Output("Fadhillah" . date("YmdHis") . ".pdf", "D");
                /* TCPDF END */
        
    }
 

}
