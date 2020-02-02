<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends Backend_controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->show();
    }

    function show() {
        if (!empty($this->menu_info)) {
            if (!empty($this->menu_info->parent_title)) {
                $data['arr_breadcrumbs'] = array(
                    $this->menu_info->parent_title => $this->menu_info->parent_link,
                    $this->menu_info->menu_title => $this->menu_info->menu_link
                );
            }else{
                 $data['arr_breadcrumbs'] = array(
                    $this->menu_info->menu_title => $this->menu_info->menu_link
                );
            }
        }

        $sql = "
                SELECT *
                FROM ref_province
            ";

        $province = $this->db->query($sql)->result();

        $data['province'] = $province;

        $data['is_superuser'] = ($_SESSION['administrator_group_type'] == 'superuser') ? TRUE : FALSE;

        $data['action'] = array_flip($this->ref_action_name);

        $this->template->content("master/company_list_view", $data);
        $this->template->show('template');
    }

    function get_data() {
        $params = isset($_POST) ? $_POST : array();
        $params['select'] = "
                *,
                IFNULL(json_extract(company_phone_fax, '$.results'), '') AS results_phonefax,
                IFNULL(json_extract(company_contact_person, '$.results'), '') AS results_contactperson
            ";
        $params['table'] = "sys_company";

        if ($this->session->userdata('administrator_group_type') != 'superuser') {
            $params['where'] = "company_id = " . $this->session->userdata('administrator_group_company_id');
        }

        $result = $this->function_lib->get_query_data($params);
        $query = $result['data'];
        $total = $result['total'];

        header("Content-type: application/json");
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $json_data = array('page' => $page, 'total' => $total, 'rows' => array());
        foreach ($query->result() as $row) {

            $detail = '<a href="javascript:;" onclick="return detailCompany(' . $row->company_id . ')"><img src="' . base_url() . _dir_icon . 'window_image_small.png" border="0" alt="Detail" title="Detail" /></a>';
            $edit = '<a href="javascript:;" onclick="return editCompany(' . $row->company_id . ')"><img src="' . base_url() . _dir_icon . 'save_labled_edit.png" border="0" alt="Ubah" title="Ubah" /></a>';

            $entry = array('id' => $row->company_id,
                'cell' => array(
                    'company_title' => $row->company_title,
                    'company_address' => $row->company_address,
                    'company_province_name' => $row->company_province_name,
                    'company_city_name' => $row->company_city_name,
                    'company_subdistrict_name' => $row->company_subdistrict_name,
                    'company_zip_code' => $row->company_zip_code,
                    'detail' => $detail,
                    'edit' => $edit
                ),
            );

            $phonefax = json_decode($row->results_phonefax);
            $contactperson = json_decode($row->results_contactperson);

            $str_phone_fax = '';
            $str_contact_person = '';
            
            if (isset($phonefax[0])) {
                $str_phone_fax = '
                    <ul style="padding-left: 20px;">
                        <li><strong>Fax</strong> : ' . $phonefax[0]->fax . '</li>
                        <li><strong>Phone</strong> : ' . $phonefax[0]->phone . '</li>
                        <li><strong>Mobilephone</strong> : ' . $phonefax[0]->mobile_phone . '</li>
                    </ul>
                ';
            }
            
            if (isset($contactperson[0])) {
                $str_contact_person = '
                    <ul style="padding-left: 20px;">
                        <li><strong>Name</strong> : ' . $contactperson[0]->name . '</li>
                        <li><strong>Address</strong> : ' . $contactperson[0]->address . '</li>
                        <li><strong>Phone</strong> : ' . $contactperson[0]->phone . '</li>
                    </ul>
                ';
            }

            $entry['cell']['phone_fax'] = $str_phone_fax;
            $entry['cell']['contact_person'] = $str_contact_person;

            $json_data['rows'][] = $entry;
        }

        echo json_encode($json_data);
    }

    function get_data_by_id() {
        $id = $this->input->get('id');
        if (!empty($id) && is_numeric($id)) {
            header("Content-type: application/json");

            $sql = "
                SELECT *
                FROM sys_company
                WHERE company_id = " . $id . "
            ";

            $data = $this->db->query($sql)->row();

            echo json_encode($data);
        } else {
            show_404();
        }
    }

    function get_data_city_by_province_id() {
        $id = $this->input->get('id');
        if (!empty($id) && is_numeric($id)) {
            header("Content-type: application/json");

            $sql = "
                SELECT city_id, city_name
                FROM ref_city
                WHERE city_province_id = " . $id . "
            ";

            $data = $this->db->query($sql)->result();

            echo json_encode($data);
        } else {
            show_404();
        }
    }

    function get_data_subdistrict_by_city_id() {
        $id = $this->input->get('id');
        if (!empty($id) && is_numeric($id)) {
            header("Content-type: application/json");

            $sql = "
                SELECT subdistrict_id, subdistrict_name
                FROM ref_subdistrict
                WHERE subdistrict_city_id = " . $id . "
            ";

            $data = $this->db->query($sql)->result();

            echo json_encode($data);
        } else {
            show_404();
        }
    }

    public function act_add() {
        if (!empty($_POST) && $_SESSION['administrator_group_type'] == 'superuser') {
            header("Content-type: application/json");
            $this->load->library('form_validation');

            $this->form_validation->set_rules('title', '<b>Company Name</b>', 'required|max_length[50]');
            $this->form_validation->set_rules('address', '<b>Company Address</b>', 'required|max_length[50]');
            $this->form_validation->set_rules('zip_code', '<b>Postal Code</b>', 'numeric');

            if (!empty($this->input->post('province'))) {
                $this->form_validation->set_rules('city', '<b>City</b>', 'required');
                $this->form_validation->set_rules('subdistrict', '<b>Subdistrict</b>', 'required');
            }

            if ($this->form_validation->run($this) == FALSE) {

                $response = array(
                    'status' => 400,
                    'msg' => validation_errors()
                );
            } else {
                
                $this->load->library('upload');
                $this->load->library('image_lib');

                $is_error = FALSE;
                $is_error_upload = FALSE;

                $this->db->trans_begin();

                try {

                    $company_title = $this->input->post('title');
                    $company_address = $this->input->post('address');
                    $company_province_name = $this->input->post('province');
                    $company_city_name = $this->input->post('city');
                    $company_subdistrict_name = $this->input->post('subdistrict');
                    $company_zip_code = $this->input->post('zip_code');
                    $company_phone_fax = $this->input->post('phone_fax');
                    $company_contact_person = $this->input->post('contact_person');

                    $company_phone_fax = '{"results": ' . $company_phone_fax . '}';
                    $company_contact_person = '{"results": ' . $company_contact_person . '}';

                    $data = array();
                    $data['company_title'] = $company_title;
                    $data['company_address'] = $company_address;
                    $data['company_province_name'] = $company_province_name;
                    $data['company_city_name'] = $company_city_name;
                    $data['company_subdistrict_name'] = $company_subdistrict_name;
                    $data['company_zip_code'] = $company_zip_code;
                    $data['company_phone_fax'] = $company_phone_fax;
                    $data['company_contact_person'] = $company_contact_person;
                    
                    if (!empty($_FILES['logo']['tmp_name'])) {
                        if ($this->upload->fileUpload('logo', 'assets/images/company/', 'jpg|jpeg|gif|png', 1024)) {
                            $upload = $this->upload->data();

                            $size = getimagesize($upload['full_path']);
                            $width = $size[0];
                            $height = $size[1];

                            if ($width != 250 || $height != 250) {
                                $this->image_lib->resizeImage($upload['full_path'], 250, 250);
                                $this->image_lib->cropCenterImage($upload['full_path'], 250, 250);
                            }

                            $image_filename = url_title($company_title) . '-' . date("YmdHis") . strtolower($upload['file_ext']);
                            rename($upload['full_path'], $upload['file_path'] . $image_filename);
                            $data['company_image'] = $image_filename;
                        } else {
                            $is_error_upload = TRUE;
                            $data['company_image'] = '';
                        }
                    }

                    $this->db->insert('sys_company', $data);

                    if ($this->db->affected_rows() < 0) {
                        $is_error = TRUE;
                    }
                } catch (Exception $ex) {
                    $is_error = TRUE;
                }
                
                $error_upload = ($is_error_upload) ? $this->upload->display_errors() : '';

                if (!$is_error) {

                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();

                        $response = array(
                            'status' => 400,
                            'msg' => 'Failed to add data! Please try again.'
                        );
                    } else {
                        $this->db->trans_commit();

                        $response = array(
                            'status' => 200,
                            'msg' => 'Success to add data. ' . $error_upload,
                        );
                    }
                } else {
                    $this->db->trans_rollback();

                    $response = array(
                        'status' => 400,
                        'msg' => 'Failed to add data! Please try again.'
                    );
                }
            }

            echo json_encode($response);
        } else {
            show_404();
        }
    }

    public function act_update() {
        if (!empty($_POST)) {
            header("Content-type: application/json");
            $this->load->library('form_validation');

            $this->form_validation->set_rules('title', '<b>Company Name</b>', 'required|max_length[50]');
            $this->form_validation->set_rules('address', '<b>Company Address</b>', 'required|max_length[50]');
            $this->form_validation->set_rules('zip_code', '<b>Postal Code</b>', 'numeric');

            if (!empty($this->input->post('province'))) {
                $this->form_validation->set_rules('city', '<b>City</b>', 'required');
                $this->form_validation->set_rules('subdistrict', '<b>Subdistrict</b>', 'required');
            }

            if ($this->form_validation->run() == FALSE) {

                $response = array(
                    'status' => 400,
                    'msg' => validation_errors()
                );
            } else {

                $this->load->library('upload');
                $this->load->library('image_lib');

                $is_error = FALSE;
                $is_error_upload = FALSE;

                $this->db->trans_begin();

                try {

                    $company_id = $this->input->post('id');
                    $company_title = $this->input->post('title');
                    $company_address = $this->input->post('address');
                    $company_province_name = $this->input->post('province');
                    $company_city_name = $this->input->post('city');
                    $company_subdistrict_name = $this->input->post('subdistrict');
                    $company_zip_code = $this->input->post('zip_code');
                    $company_phone_fax = $this->input->post('phone_fax');
                    $company_contact_person = $this->input->post('contact_person');
                    
                    $company_old_logo = $this->input->post('old_logo');

                    $company_phone_fax = '{"results": ' . $company_phone_fax . '}';
                    $company_contact_person = '{"results": ' . $company_contact_person . '}';

                    $data = array();
                    $data['company_title'] = $company_title;
                    $data['company_address'] = $company_address;
                    $data['company_province_name'] = $company_province_name;
                    $data['company_city_name'] = $company_city_name;
                    $data['company_subdistrict_name'] = $company_subdistrict_name;
                    $data['company_zip_code'] = $company_zip_code;
                    $data['company_phone_fax'] = $company_phone_fax;
                    $data['company_contact_person'] = $company_contact_person;
                    
                    if (!empty($_FILES['logo']['tmp_name'])) {
                        if ($this->upload->fileUpload('logo', 'assets/images/company/', 'jpg|jpeg|gif|png', 1024)) {
                            $upload = $this->upload->data();

                            $size = getimagesize($upload['full_path']);
                            $width = $size[0];
                            $height = $size[1];

                            if ($width != 250 || $height != 250) {
                                $this->image_lib->resizeImage($upload['full_path'], 250, 250);
                                $this->image_lib->cropCenterImage($upload['full_path'], 250, 250);
                            }

                            $image_filename = url_title($company_title) . '-' . date("YmdHis") . strtolower($upload['file_ext']);
                            rename($upload['full_path'], $upload['file_path'] . $image_filename);
                            
                            //delete old file
                            if ($company_old_logo != '' && file_exists('assets/images/company/' . $company_old_logo)) {
                                @unlink('assets/images/company/' . $company_old_logo);
                            }
                            
                            $data['company_image'] = $image_filename;
                        } else {
                            $is_error_upload = TRUE;
                            $data['company_image'] = '';
                        }
                    }

                    $this->db->where('company_id', $company_id);
                    $this->db->update('sys_company', $data);

                    if ($this->db->affected_rows() < 0) {
                        $is_error = TRUE;
                    }
                } catch (Exception $ex) {
                    $is_error = TRUE;
                }

                $error_upload = ($is_error_upload) ? $this->upload->display_errors() : '';
                
                if (!$is_error) {

                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();

                        $response = array(
                            'status' => 400,
                            'msg' => 'Failed to change data! Please try again.',
                        );
                    } else {
                        $this->db->trans_commit();

                        $response = array(
                            'status' => 200,
                            'msg' => 'Success to change data. ' . $error_upload,
                        );
                    }
                } else {
                    $this->db->trans_rollback();

                    $response = array(
                        'status' => 400,
                        'msg' => 'Failed to change data! Please try again.',
                    );
                }
            }

            echo json_encode($response);
        } else {
            show_404();
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

                        //hapus data
                        $this->db->where('company_id', $id);
                        $this->db->delete('sys_company');

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

    function export_data_company() {
        $params = array();
        if (isset($_POST)) {
            foreach ($_POST as $id => $value) {
                $params[$id] = $value;
            }
        }
        
        $params['params']['select'] = "
                *,
                IFNULL(json_extract(company_phone_fax, '$.results'), '') AS results_phonefax,
                IFNULL(json_extract(company_contact_person, '$.results'), '') AS results_contactperson
            ";
        $params['params']['table'] = "sys_company";
        if ($this->session->userdata('administrator_group_type') != 'superuser') {
            $params['params']['where'] = "company_id = " . $this->session->userdata('administrator_group_company_id');
        }

        if ($params['params']['total_data'] <= 1000) {
            unset($params['params']['rp']);
            unset($params['params']['page']);
        }

        $data = array();
        $data['title'] = 'Data Company';
        $data['params'] = $params;
        $data['query'] = $this->function_lib->get_query_data($params['params'])['data'];
        $data['column'] = isset($_POST['column']) ? $_POST['column'] : array();
        $column_title = json_decode($data['column']['title']);

        $data['column']['title'] = json_encode($column_title);

        $this->export_excel($data);
    }
    
    function export_excel($data = false) {
        if ($data) {
            //$this->output->enable_profiler(TRUE);
            ini_set('memory_limit', -1);
            set_time_limit(0);
            $this->load->library('Excel');

            // Initiate cache
            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
            $cacheSettings = array('memoryCacheSize' => '32MB');
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

            $arr_style_title = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'EEEEEE')
                ),
                'alignment' => array(
                    'wrap' => true,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                ),
            );

            $arr_style_content = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'alignment' => array(
                    'wrap' => true,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP
                ),
            );

            extract($data);
            $filename = url_title($title) . '-' . date("YmdHis");
            $arr_column_name = json_decode($column['name']);
            $arr_column_show = json_decode($column['show']);
            $arr_column_align = json_decode($column['align']);
            $arr_column_title = json_decode($column['title']);
            $arr_column_max_width = array();

            //menyisipkan sort number
            array_unshift($arr_column_name, "sort");
            array_unshift($arr_column_show, true);
            array_unshift($arr_column_align, "center");
            array_unshift($arr_column_title, "No");

            $first_column = $cell_column = 'A';
            $cell_row = $first_row = 1;
            $excel = new PHPExcel();
            $excel->getProperties()->setTitle($title)->setSubject($title);
            $excel->getActiveSheet()->setTitle(substr($title, 0, 31));
            $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
            $excel->getDefaultStyle()->getFont()->setName('Calibri');
            $excel->getDefaultStyle()->getFont()->setSize(10);

            //title
            $excel->getActiveSheet()->getRowDimension($cell_row)->setRowHeight(20);
            $excel->getActiveSheet()->getStyle($cell_column . $cell_row)->getFont()->setBold(true);
            $excel->getActiveSheet()->getStyle($cell_column . $cell_row)->getFont()->setSize(13);
            $excel->getActiveSheet()->setCellValue($cell_column . $cell_row, strtoupper($title));
            $cell_row++;
            $excel->getActiveSheet()->setCellValue($cell_column . $cell_row, 'Export Date : ' . convert_datetime(date("Y-m-d H:i:s"), 'en'));
            $cell_row++;
            $cell_row++;

            //cari jumlah kolom
            $total_column = 0;
            $last_column = $first_column;
            foreach ($arr_column_show as $id => $is_show) {
                if ($is_show == true) {
                    $total_column++;
                    if ($total_column > 1) {
                        $last_column++;
                    }
                }
            }

            if (is_array($arr_column_title)) {
                $cell_column = $first_column;
                $excel->getActiveSheet()->getRowDimension($cell_row)->setRowHeight(20);
                $excel->getActiveSheet()->getStyle($cell_column . $cell_row . ':' . $last_column . $cell_row)->applyFromArray($arr_style_title);
                $excel->getActiveSheet()->getStyle($cell_column . $cell_row . ':' . $last_column . $cell_row)->getFont()->setBold(true)->setSize(11);
                $excel->getActiveSheet()->getStyle($cell_column . $cell_row . ':' . $last_column . $cell_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER_CONTINUOUS);
                foreach ($arr_column_title as $id => $value) {
                    if ($arr_column_show[$id] == true) {
                        $arr_column_max_width[$id] = ceil(1.5 * strlen($value) + 0.6);
                        $excel->getActiveSheet()->getColumnDimension($cell_column)->setWidth($arr_column_max_width[$id]);
                        $excel->getActiveSheet()->setCellValue($cell_column . $cell_row, strtoupper($value));
                        $cell_column++;
                    }
                }
                $cell_row++;
            }
            $first_content_row = $cell_row;

            if ($query->num_rows() > 0) {
                $sort = 1;
                foreach ($query->result() as $row) {
                    $row->sort = $sort;
                    $excel->getActiveSheet()->getRowDimension($cell_row)->setRowHeight(17);
                    $cell_column = $first_column;
                    foreach ($arr_column_name as $id => $value) {
                        if ($arr_column_show[$id] == true) {

                            $str_phone_fax = '';
                            $phone_fax = json_decode($row->results_phonefax);

                            $str_contact_person = '';
                            $contact_person = json_decode($row->results_contactperson);

                            if (!isset($row->$value)) {
                                if ($id == 9) {
                                    if (isset($phone_fax[0])) {
                                        $str_phone_fax = "Fax : " . $phone_fax[0]->fax . "\nPhone : " . $phone_fax[0]->phone . "\nMobilephone : " . $phone_fax[0]->mobile_phone;
                                    }
                                    $data = $str_phone_fax;
                                } else if ($id == 10) {
                                    if (isset($contact_person[0])) {
                                        $str_contact_person = "Name : " . $contact_person[0]->name . "\nAddress : " . $contact_person[0]->address . "\nPhone : " . $contact_person[0]->phone;
                                    }
                                    $data = $str_contact_person;
                                } else {
                                    $data = '';
                                }
                            } else {
                                $data = $row->$value;
                            }

                            if ($id == 9 || $id == 10) {
                                $arr_column_max_width[$id] = 40;
                            } else {
                                $column_width = ceil(strlen(trim($data)));
                                if ($column_width > $arr_column_max_width[$id]) {
                                    $arr_column_max_width[$id] = $column_width;
                                }
                            }

                            if ($id == 9 || $id == 10) {
                                if (isset($phone_fax[0]) || isset($contact_person[0])) {
                                    $excel->getActiveSheet()->getRowDimension($cell_row)->setRowHeight(40);
                                }
                            }

                            if (is_nominal($data)) {
                                $excel->getActiveSheet()->setCellValueExplicit($cell_column . $cell_row, $data, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $excel->getActiveSheet()->getStyle($cell_column . $cell_row)->getNumberFormat()->setFormatCode("#,##0");
                            } else {
                                $excel->getActiveSheet()->setCellValueExplicit($cell_column . $cell_row, $data, PHPExcel_Cell_DataType::TYPE_STRING);
                            }
                            $cell_column++;
                        }
                    }
                    $cell_row++;
                    $sort++;
                }
            }
            $last_content_row = $cell_row;
            $last_content_row--;

            $cell_column = $first_column;
            foreach ($arr_column_title as $id => $value) {
                if ($arr_column_show[$id] == true) {
                    $excel->getActiveSheet()->getStyle($cell_column . $first_content_row . ':' . $cell_column . $last_content_row)->getAlignment()->setHorizontal($arr_column_align[$id]);
                    $excel->getActiveSheet()->getStyle($cell_column . $first_content_row . ':' . $cell_column . $last_content_row)->applyFromArray($arr_style_content);
                    $column_width = ($arr_column_max_width[$id] > 50) ? 50 : $arr_column_max_width[$id]; //max_width = 50
                    $excel->getActiveSheet()->getColumnDimension($cell_column)->setWidth($column_width);

                    $cell_column++;
                }
            }

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
            header('Cache-Control: max-age=0');

            $write = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
            $write->save('php://output');
            exit;
        }
    }

}
