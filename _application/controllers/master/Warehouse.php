<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse extends Backend_controller {

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
            } else {
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

        $this->template->content("master/warehouse_list_view", $data);
        $this->template->show('template');
    }

    function get_data() {
        $params = isset($_POST) ? $_POST : array();
        $params['select'] = "
                *,
                IFNULL(json_extract(warehouse_phone_fax, '$.results'), '') AS results_phonefax,
                IFNULL(json_extract(warehouse_contact_person, '$.results'), '') AS results_contactperson
            ";
        $params['table'] = "sys_warehouse";
        $params['join'] = "JOIN sys_company ON company_id = warehouse_company_id";

        if ($this->session->userdata('administrator_group_type') == 'administrator_company') {
            $params['where'] = "warehouse_company_id = " . $this->session->userdata('administrator_group_company_id');
        }

        if ($this->session->userdata('administrator_group_type') == 'administrator_warehouse') {
            $params['where'] = "warehouse_id = " . $this->session->userdata('administrator_group_warehouse_id');
        }

        $result = $this->function_lib->get_query_data($params);
        $query = $result['data'];
        $total = $result['total'];

        header("Content-type: application/json");
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $json_data = array('page' => $page, 'total' => $total, 'rows' => array());
        foreach ($query->result() as $row) {

            $detail = '<a href="javascript:;" onclick="return detailWarehouse(' . $row->warehouse_id . ')"><img src="' . base_url() . _dir_icon . 'window_image_small.png" border="0" alt="Detail" title="Detail" /></a>';
            $edit = '<a href="javascript:;" onclick="return editWarehouse(' . $row->warehouse_id . ')"><img src="' . base_url() . _dir_icon . 'save_labled_edit.png" border="0" alt="Ubah" title="Ubah" /></a>';

            $entry = array('id' => $row->warehouse_id,
                'cell' => array(
                    'warehouse_code' => $row->warehouse_code,
                    'warehouse_name' => $row->warehouse_name,
                    'warehouse_address' => $row->warehouse_address,
                    'warehouse_province_name' => $row->warehouse_province_name,
                    'warehouse_city_name' => $row->warehouse_city_name,
                    'warehouse_subdistrict_name' => $row->warehouse_subdistrict_name,
                    'warehouse_zip_code' => $row->warehouse_zip_code,
                    'company_title' => $row->company_title,
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

            $str_where = '';
            if ($this->session->userdata('administrator_group_type') != 'superuser') {
                $str_where = "AND warehouse_company_id =  " . $this->session->userdata('administrator_group_company_id');
            }

            $sql = "
                SELECT *
                FROM sys_warehouse
                JOIN sys_company ON company_id = warehouse_company_id
                WHERE warehouse_id = " . $id . " " . $str_where . "
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

    function get_data_company() {
        if ($this->session->userdata('administrator_group_type') == 'superuser') {
            header("Content-type: application/json");

            $sql = "
                SELECT company_id, company_title
                FROM sys_company
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

            if ($this->session->userdata('administrator_group_type') == 'superuser') {
                $this->form_validation->set_rules('company', '<b>Company Name</b>', 'required');
            }

            $warehouse_company_id = ($this->session->userdata('administrator_group_type') == 'superuser') ? $this->input->post('company') : $this->session->userdata('administrator_group_company_id');

            $this->form_validation->set_rules('code', '<b>Warehouse Code</b>', 'required|max_length[5]|callback_code_check[' . $warehouse_company_id . ']');
            $this->form_validation->set_rules('name', '<b>Warehouse Name</b>', 'required|max_length[50]');
            $this->form_validation->set_rules('address', '<b>Warehouse Address</b>', 'required|max_length[50]');
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

                    $warehouse_logo = "";

                    $warehouse_code = $this->input->post('code');
                    $warehouse_name = $this->input->post('name');
                    $warehouse_address = $this->input->post('address');
                    $warehouse_province_name = $this->input->post('province');
                    $warehouse_city_name = $this->input->post('city');
                    $warehouse_subdistrict_name = $this->input->post('subdistrict');
                    $warehouse_zip_code = $this->input->post('zip_code');
                    $warehouse_phone_fax = $this->input->post('phone_fax');
                    $warehouse_contact_person = $this->input->post('contact_person');

                    $warehouse_phone_fax = '{"results": ' . $warehouse_phone_fax . '}';
                    $warehouse_contact_person = '{"results": ' . $warehouse_contact_person . '}';

                    $pos_type = $this->input->post('pos_type');
                    $paper_size = $this->input->post('paper_size');

                    $config_title_text_1 = htmlspecialchars($this->input->post('config_title_text_1'));
                    $config_title_text_2 = htmlspecialchars($this->input->post('config_title_text_2'));
                    $config_title_text_3 = htmlspecialchars($this->input->post('config_title_text_3'));

                    $config_footer_text_1 = htmlspecialchars($this->input->post('config_footer_text_1'));

                    $data = array();
                    $data['warehouse_company_id'] = $warehouse_company_id;
                    $data['warehouse_code'] = $warehouse_code;
                    $data['warehouse_name'] = $warehouse_name;
                    $data['warehouse_address'] = $warehouse_address;
                    $data['warehouse_province_name'] = $warehouse_province_name;
                    $data['warehouse_city_name'] = $warehouse_city_name;
                    $data['warehouse_subdistrict_name'] = $warehouse_subdistrict_name;
                    $data['warehouse_zip_code'] = $warehouse_zip_code;
                    $data['warehouse_phone_fax'] = $warehouse_phone_fax;
                    $data['warehouse_contact_person'] = $warehouse_contact_person;

                    if (!empty($_FILES['logo']['tmp_name'])) {
                        if ($this->upload->fileUpload('logo', 'assets/images/logo_struck/', 'jpg|jpeg|gif|png', 250)) {
                            $upload = $this->upload->data();

                            $size = getimagesize($upload['full_path']);
                            $width = $size[0];
                            $height = $size[1];

                            $image_filename = url_title($warehouse_name) . '-' . date("YmdHis") . strtolower($upload['file_ext']);
                            rename($upload['full_path'], $upload['file_path'] . $image_filename);

                            $warehouse_logo = $image_filename;
                        } else {
                            $is_error_upload = TRUE;
                            $warehouse_logo = '';
                        }
                    }

                    $array_config = array(
                        "pos_type" => $pos_type,
                        "struck_template" => array(
                            "footer" => array(
                                "text_1" => $config_footer_text_1,
                            ),
                            "header" => array(
                                "title_text_1" => $config_title_text_1,
                                "title_text_2" => $config_title_text_2,
                                "title_text_3" => $config_title_text_3,
                                "logo_filename" => $warehouse_logo,
                            ),
                            "paper_size" => $paper_size,
                        )
                    );

                    $data['warehouse_pos_config_json'] = json_encode($array_config);

                    $this->db->insert('sys_warehouse', $data);
                    $warehouse_id = $this->db->insert_id();

                    if ($this->db->affected_rows() < 0) {
                        $is_error = TRUE;
                    }

                    //get all product variant in this company
                    $product_variant = $this->db->select('product_variant_id')->get_where('sys_product_variant', array('product_variant_company_id' => $warehouse_company_id))->result();

                    //insert all product in this company to sys_stock
                    foreach ($product_variant as $row_product_variant) {
                        $stock = array();
                        $stock['stock_warehouse_id'] = $warehouse_id;
                        $stock['stock_product_id'] = $row_product_variant->product_variant_id;
                        $this->db->insert('sys_stock', $stock);

                        if ($this->db->affected_rows() < 0) {
                            $is_error = TRUE;
                        }
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
                        'msg' => 'Failed to add data! Please try again. ' . $error_upload
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

            if ($this->session->userdata('administrator_group_type') == 'superuser') {
                $this->form_validation->set_rules('company', '<b>Company Name</b>', 'required');
            }

            $warehouse_company_id = ($this->session->userdata('administrator_group_type') == 'superuser') ? $this->input->post('company') : $this->session->userdata('administrator_group_company_id');
            $warehouse_id = ($this->session->userdata('administrator_group_type') == 'superuser' || $this->session->userdata('administrator_group_type') == 'administrator_company') ? $this->input->post('id') : $this->session->userdata('administrator_group_warehouse_id');

            $this->form_validation->set_rules('code', '<b>Warehouse Code</b>', 'required|max_length[5]|callback_code_check[' . $warehouse_company_id . '.' . $warehouse_id . ']');
            $this->form_validation->set_rules('name', '<b>Warehouse Name</b>', 'required|max_length[50]');
            $this->form_validation->set_rules('address', '<b>Warehouse Address</b>', 'required|max_length[50]');
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

                    $warehouse_old_logo = !empty($this->input->post('old_logo')) ? $this->input->post('old_logo') : '';

                    $warehouse_logo = $warehouse_old_logo;

                    $warehouse_code = $this->input->post('code');
                    $warehouse_name = $this->input->post('name');
                    $warehouse_address = $this->input->post('address');
                    $warehouse_province_name = $this->input->post('province');
                    $warehouse_city_name = $this->input->post('city');
                    $warehouse_subdistrict_name = $this->input->post('subdistrict');
                    $warehouse_zip_code = $this->input->post('zip_code');
                    $warehouse_phone_fax = $this->input->post('phone_fax');
                    $warehouse_contact_person = $this->input->post('contact_person');

                    $warehouse_phone_fax = '{"results": ' . $warehouse_phone_fax . '}';
                    $warehouse_contact_person = '{"results": ' . $warehouse_contact_person . '}';

                    $pos_type = $this->input->post('pos_type');
                    $paper_size = $this->input->post('paper_size');

                    $config_title_text_1 = htmlspecialchars($this->input->post('config_title_text_1'));
                    $config_title_text_2 = htmlspecialchars($this->input->post('config_title_text_2'));
                    $config_title_text_3 = htmlspecialchars($this->input->post('config_title_text_3'));

                    $config_footer_text_1 = htmlspecialchars($this->input->post('config_footer_text_1'));

                    $data = array();
                    $data['warehouse_company_id'] = $warehouse_company_id;
                    $data['warehouse_code'] = $warehouse_code;
                    $data['warehouse_name'] = $warehouse_name;
                    $data['warehouse_address'] = $warehouse_address;
                    $data['warehouse_province_name'] = $warehouse_province_name;
                    $data['warehouse_city_name'] = $warehouse_city_name;
                    $data['warehouse_subdistrict_name'] = $warehouse_subdistrict_name;
                    $data['warehouse_zip_code'] = $warehouse_zip_code;
                    $data['warehouse_phone_fax'] = $warehouse_phone_fax;
                    $data['warehouse_contact_person'] = $warehouse_contact_person;


                    if (!empty($_FILES['logo']['tmp_name'])) {
                        if ($this->upload->fileUpload('logo', 'assets/images/logo_struck/', 'jpg|jpeg|gif|png', 250)) {
                            $upload = $this->upload->data();

                            $size = getimagesize($upload['full_path']);
                            $width = $size[0];
                            $height = $size[1];

                            $image_filename = url_title($warehouse_name) . '-' . date("YmdHis") . strtolower($upload['file_ext']);
                            rename($upload['full_path'], $upload['file_path'] . $image_filename);

                            //delete old file
                            if ($warehouse_old_logo != '' && @file_exists('assets/images/logo_struck/' . $warehouse_old_logo)) {
                                @unlink('assets/images/logo_struck/' . $warehouse_old_logo);
                            }

                            $warehouse_logo = $image_filename;
                        } else {
                            $is_error_upload = TRUE;
                            $warehouse_logo = '';
                        }
                    }

                    $array_config = array(
                        "pos_type" => $pos_type,
                        "struck_template" => array(
                            "footer" => array(
                                "text_1" => $config_footer_text_1,
                            ),
                            "header" => array(
                                "title_text_1" => $config_title_text_1,
                                "title_text_2" => $config_title_text_2,
                                "title_text_3" => $config_title_text_3,
                                "logo_filename" => $warehouse_logo,
                            ),
                            "paper_size" => $paper_size,
                        )
                    );

                    $data['warehouse_pos_config_json'] = json_encode($array_config);

                    $this->db->where('warehouse_id', $warehouse_id);
                    $this->db->update('sys_warehouse', $data);

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
                        'msg' => 'Failed to change data! Please try again.' . $error_upload,
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

                        $warehouse_pos_config_json = $this->db->select('warehouse_pos_config_json')->get_where('sys_warehouse', array('warehouse_id' => $id))->row('warehouse_pos_config_json');

                        if (!empty($warehouse_pos_config_json)) {
                            $data_config = json_decode($warehouse_pos_config_json);

                            //delete old file
                            if ($data_config->struck_template->header->logo_filename != '' && @file_exists('assets/images/logo_struck/' . $data_config->struck_template->header->logo_filename)) {
                                @unlink('assets/images/logo_struck/' . $data_config->struck_template->header->logo_filename);
                            }
                        }

                        //hapus data
                        $this->db->where('warehouse_id', $id);
                        $this->db->delete('sys_warehouse');

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

    function get_code() {

        $id = $this->input->get('id');
        if (!empty($id) && is_numeric($id)) {
            header("Content-type: application/json");

            $str_where = "WHERE warehouse_company_id = " . $id;
            $code = $this->common_model->generate_code('sys_warehouse', 'warehouse_code', $str_where);

            if (!empty($code)) {
                $data = array(
                    'status' => 200,
                    'data' => $code
                );
            } else {
                $data = array(
                    'status' => 400,
                    'msg' => 'Failed to get Warehouse Code!'
                );
            }

            echo json_encode($data);
        } else {
            show_404();
        }
    }

    function code_check($str, $params) {

        $arr_params = explode('.', $params, 2);
        $params_count = count($arr_params);

        if ($params_count == 2) {
            list($company_id, $warehouse_id) = $arr_params;
            $sql = "
                SELECT COUNT(*) AS rows_count 
                FROM sys_warehouse 
                WHERE warehouse_code = '" . $str . "' 
                AND warehouse_id != '" . $warehouse_id . "'
                AND warehouse_company_id = '" . $company_id . "'
            ";
        } else {
            list($company_id) = $arr_params;
            $sql = "
                SELECT COUNT(*) AS rows_count 
                FROM sys_warehouse 
                WHERE warehouse_company_id = '" . $company_id . "' AND warehouse_code = '" . $str . "'
            ";
        }

        $this->form_validation->set_message('code_check', '{field} has been used.');
        $query = $this->db->query($sql);
        $row = $query->row();
        return ($row->rows_count > 0) ? FALSE : TRUE;
    }

    function export_data_warehouse() {
        $params = array();
        if (isset($_POST)) {
            foreach ($_POST as $id => $value) {
                $params[$id] = $value;
            }
        }

        $params['params']['select'] = "
                *,
                IFNULL(json_extract(warehouse_phone_fax, '$.results'), '') AS results_phonefax,
                IFNULL(json_extract(warehouse_contact_person, '$.results'), '') AS results_contactperson
            ";
        $params['params']['table'] = "sys_warehouse";
        $params['params']['join'] = "JOIN sys_company ON company_id = warehouse_company_id";

        if ($this->session->userdata('administrator_group_type') == 'administrator_company') {
            $params['params']['where'] = "warehouse_company_id = " . $this->session->userdata('administrator_group_company_id');
        }

        if ($this->session->userdata('administrator_group_type') == 'administrator_warehouse') {
            $params['params']['where'] = "warehouse_id = " . $this->session->userdata('administrator_group_warehouse_id');
        }

        if ($params['params']['total_data'] <= 1000) {
            unset($params['params']['rp']);
            unset($params['params']['page']);
        }

        $data = array();
        $data['title'] = 'Data Warehouse';
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
            $excel->getActiveSheet()->setCellValue($cell_column . $cell_row, 'Export Date: ' . convert_datetime(date("Y-m-d H:i:s"), 'en'));
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
                                if ($id == 11) {
                                    if (isset($phone_fax[0])) {
                                        $str_phone_fax = "Fax : " . $phone_fax[0]->fax . "\nPhone : " . $phone_fax[0]->phone . "\nMobilephone : " . $phone_fax[0]->mobile_phone;
                                    }
                                    $data = $str_phone_fax;
                                } else if ($id == 12) {
                                    if (isset($contact_person[0])) {
                                        $str_contact_person = "Name : " . $contact_person[0]->name . "\nAddress : " . $contact_person[0]->address . "\nMobilephone : " . $contact_person[0]->phone;
                                    }
                                    $data = $str_contact_person;
                                } else {
                                    $data = '';
                                }
                            } else {
                                $data = $row->$value;
                            }

                            if ($id == 11 || $id == 12) {
                                $arr_column_max_width[$id] = 40;
                            } else {
                                $column_width = ceil(strlen(trim($data)));
                                if ($column_width > $arr_column_max_width[$id]) {
                                    $arr_column_max_width[$id] = $column_width;
                                }
                            }

                            if ($id == 11 || $id == 12) {
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
