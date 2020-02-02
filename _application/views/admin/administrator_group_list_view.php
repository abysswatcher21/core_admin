<div class="page-title">
    <div class="title_left">
        <h3><?php echo isset($this->menu_info->menu_title) ? $this->menu_info->menu_title : '' ?></h3>
    </div>
</div>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div id="response_message" style="display:none;"></div>
        <table id="gridview" style="display:none;"></table>
    </div>
</div>

<?php 
if($is_superuser || isset($action['add']) || isset($action['update'])){
?>

<!-- Modal-->
<div id="modal" class="modal fade" role="dialog" style="overflow: hidden">
    <div class="custom-loading"><span></span></div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <form id="form" class="form-horizontal form-label-left" data-url="">
                
                <input type="hidden" name="administrator_group_id">
                
                <div class="modal-body" style="overflow-y: auto; max-height: calc(100vh - 200px);">
                    <div id="modal-response-message" class="alert alert-danger alert-dismissible fade in" role="alert" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <div></div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="title">Group Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo form_input('title', '', 'data-validation="required length" data-validation-length="max20" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>
                    
                    <?php if($is_superuser || $_SESSION['administrator_group_type'] == 'administrator_company'): ?>
                        <div id="block_superuser">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="type">Group Type <span class="required">*</span>
                                </label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <select id="input-group-type" name="type" data-validation="required" class="form-control my-select2">
                                        <option value="">--Choose Group--</option>
                                        <?php if($is_superuser): ?>
                                            <option value="administrator_company">Company Administrator</option>
                                        <?php endif; ?>
                                        <option value="administrator_warehouse">Warehouse Administrator</option>
                                        <option value="administrator_pos">Store Administrator</option>
                                        <?php if($is_superuser): ?>
                                            <option value="administrator_cashier">Cashier Administrator</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <?php if($is_superuser): ?>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="company">Company Name <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select name="company" data-validation="required" class="form-control my-select2">
                                            <option value="">--Choose Company--</option>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div id="container-warehouse" style="display: none;">
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="warehouse">Warehouse Name <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select name="warehouse" data-validation="required" class="form-control my-select2">
                                            <option value="">--Choose Warehouse--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" id="add-warehouse">
                                    <div class="col-md-4 col-sm-4 col-xs-12"></div>
                                    <div class="col-md-6 col-sm-6 col-xl-12">
                                        <span>This company warehouse does not exist. </strong></span><a href="<?php echo site_url('master/warehouse/show'); ?>"><strong><u>Add Warehouse?</u></strong></a>
                                    </div>
                                </div>
                            </div>

                            <div id="container-pos" style="display: none;">
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="pos">Store Name <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select name="pos" data-validation="required" class="form-control my-select2">
                                            <option value="">--Choose POS--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" id="add-pos">
                                    <div class="col-md-4 col-sm-4 col-xs-12"></div>
                                    <div class="col-md-6 col-sm-6 col-xl-12">
                                        <span>This company pos does not exist. </strong></span><a href="<?php echo site_url('master/pos/show'); ?>"><strong><u>Add POS?</u></strong></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div id="privilege_menu" class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="allitem">Privilege
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <div class="checkbox">
                                <label>
                                    <?php echo form_checkbox(array('name' => 'allmenu', 'checked' => false, 'value' => true, 'id' => 'allmenu')); ?> <strong>Select All</strong>
                                </label>
                            </div>
                            <?php
                            // cari root menu
                            if (array_key_exists('0', $arr_menu_privilege)) {
                                echo '<div id="block_menu">';

                                // urutkan root menu berdasarkan menu_order_by
                                ksort($arr_menu_privilege[0]);

                                // ekstrak root menu
                                foreach ($arr_menu_privilege[0] as $rootmenu_sort => $rootmenu_value) {

                                    $rootmenu_checkbox_data = array();
                                    $rootmenu_checkbox_data['name'] = 'menu[]';
                                    $rootmenu_checkbox_data['id'] = 'menu_' . $rootmenu_value->administrator_menu_id ;
                                    $rootmenu_checkbox_data['value'] = $rootmenu_value->administrator_menu_id;
                                    $rootmenu_checkbox_data['checked'] = FALSE;
                                    $rootmenu_checkbox_data['class'] = 'menu_item menu-lev-1';

                                    echo '<div class="checkbox" style="margin:10px 0 10px 25px;"><label>' . form_checkbox($rootmenu_checkbox_data) . '&nbsp;<strong style="color: #26b99a;">' . $rootmenu_value->administrator_menu_title . '</strong></label></div>';

                                    // cari submenu 1
                                    if (array_key_exists($rootmenu_value->administrator_menu_id, $arr_menu_privilege)) {
                                        echo '<div id="block_menu_' . $rootmenu_value->administrator_menu_id . '">';

                                        // urutkan submenu 1 berdasarkan menu_order_by
                                        ksort($arr_menu_privilege[$rootmenu_value->administrator_menu_id]);

                                        // ekstrak submenu 1 yang par_id adalah menu_id dari root menu
                                        foreach ($arr_menu_privilege[$rootmenu_value->administrator_menu_id] as $submenu_1_sort => $submenu_1_value) {

                                            $submenu_1_checkbox_data = array();
                                            $submenu_1_checkbox_data['name'] = 'menu[]';
                                            $submenu_1_checkbox_data['id'] = 'menu_' . $submenu_1_value->administrator_menu_id;
                                            $submenu_1_checkbox_data['value'] = $submenu_1_value->administrator_menu_id;
                                            $submenu_1_checkbox_data['checked'] = FALSE;
                                            $submenu_1_checkbox_data['class'] = 'menu_item menu-lev-2';

                                            echo '<div class="checkbox" style="margin:0 0 5px 50px;"><label>' . form_checkbox($submenu_1_checkbox_data) . '&nbsp;<strong style="color: #26b99a;">' . $submenu_1_value->administrator_menu_title . '</strong></label></div>';
                                            
                                            // cari submenu 2
                                            if (array_key_exists($submenu_1_value->administrator_menu_id, $arr_menu_privilege)) {
                                                echo '<div id="block_menu_' . $submenu_1_value->administrator_menu_id . '">';

                                                // urutkan submenu 2 berdasarkan menu_order_by
                                                ksort($arr_menu_privilege[$submenu_1_value->administrator_menu_id]);

                                                // ekstrak submenu 2 yang par_id adalah menu_id dari sub menu 1
                                                foreach ($arr_menu_privilege[$submenu_1_value->administrator_menu_id] as $submenu_2_sort => $submenu_2_value) {

                                                    $submenu_2_checkbox_data = array();
                                                    $submenu_2_checkbox_data['name'] = 'menu[]';
                                                    $submenu_2_checkbox_data['id'] = 'menu_' . $submenu_2_value->administrator_menu_id;
                                                    $submenu_2_checkbox_data['value'] = $submenu_2_value->administrator_menu_id;
                                                    $submenu_2_checkbox_data['checked'] = FALSE;
                                                    $submenu_2_checkbox_data['class'] = 'menu_item';

                                                    echo '<div class="checkbox" style="margin:0 0 5px 75px;"><label>' . form_checkbox($submenu_2_checkbox_data) . '&nbsp;<strong style="color: #26b99a;">' .  $submenu_2_value->administrator_menu_title . '</strong></label></div>';

                                                    // cari submenu 3
                                                    if (array_key_exists($submenu_2_value->administrator_menu_id, $arr_menu_privilege)) {
                                                        echo '<div id="block_menu_' . $submenu_2_value->administrator_menu_id . '">';

                                                        // urutkan submenu 3 berdasarkan menu_order_by
                                                        ksort($arr_menu_privilege[$submenu_2_value->administrator_menu_id]);

                                                        // ekstrak submenu 3 yang par_id adalah menu_id dari sub menu 2
                                                        foreach ($arr_menu_privilege[$submenu_2_value->administrator_menu_id] as $submenu_3_sort => $submenu_3_value) {
                                                            $submenu_3_checkbox_data = array();
                                                            $submenu_3_checkbox_data['name'] = 'menu[]';
                                                            $submenu_3_checkbox_data['id'] = 'menu_' . $submenu_3_value->administrator_menu_id;
                                                            $submenu_3_checkbox_data['value'] = $submenu_3_value->administrator_menu_id;
                                                            $submenu_3_checkbox_data['checked'] = FALSE;
                                                            $submenu_3_checkbox_data['class'] = 'menu_item';

                                                            echo '<div class="checkbox" style="margin:0 0 5px 100px;"><label>' . form_checkbox($submenu_3_checkbox_data) . '&nbsp;<strong style="color: #26b99a;">' .  $submenu_3_value->administrator_menu_title . '</strong></label></div>';
                                                            
                                                            $arr_act = array();
                                                            $arr_act = json_decode($submenu_3_value->results);

                                                            $str_html_act = '';
                                                            if (!empty($arr_act)) {
                                                                $str_html_act .= '<div id="block_act_menu_' . $submenu_3_value->administrator_menu_id . '" class="act_block"><div class="checkbox" style="margin:0 0 5px 125px;">';

                                                                $tag_br = '';
                                                                $no = 0;
                                                                foreach ($arr_act as $key => $value) {
                                                                    $checkbox_act = array();
                                                                    $checkbox_act['name'] = 'action[' . $submenu_3_value->administrator_menu_id . '][]';
                                                                    $checkbox_act['id'] = 'action-' . $submenu_3_value->administrator_menu_id . '-' . $no;
                                                                    $checkbox_act['value'] = $value->name;
                                                                    $checkbox_act['checked'] = FALSE;
                                                                    $checkbox_act['class'] = 'act_menu_item menu-lev-3';

                                                                    $str_html_act .= $tag_br . '<label>' . form_checkbox($checkbox_act) . '&nbsp;' . $value->title . '</label>';
                                                                    $tag_br = '<br>';
                                                                    $no++;
                                                                }
                                                                $str_html_act .= '</div></div>';
                                                            }
                                                            echo $str_html_act;
                                                        }
                                                        echo '</div>';
                                                    } else {
                                                        $arr_act = array();
                                                        $arr_act = json_decode($submenu_2_value->results);

                                                        $str_html_act = '';
                                                        if (!empty($arr_act)) {
                                                            $str_html_act .= '<div id="block_act_menu_' . $submenu_2_value->administrator_menu_id . '" class="act_block"><div class="checkbox" style="margin:0 0 5px 100px;">';

                                                            $tag_br = '';
                                                            $no = 0;
                                                            foreach ($arr_act as $key => $value) {
                                                                $checkbox_act = array();
                                                                $checkbox_act['name'] = 'action[' . $submenu_2_value->administrator_menu_id . '][]';
                                                                $checkbox_act['id'] = 'action-' . $submenu_2_value->administrator_menu_id . '-' . $no;
                                                                $checkbox_act['value'] = $value->name;
                                                                $checkbox_act['checked'] = FALSE;
                                                                $checkbox_act['class'] = 'act_menu_item menu-lev-3';

                                                                $str_html_act .= $tag_br . '<label>' . form_checkbox($checkbox_act) . '&nbsp;' . $value->title . '</label>';
                                                                $tag_br = '<br>';
                                                                $no++;
                                                            }
                                                            $str_html_act .= '</div></div>';
                                                        }
                                                        echo $str_html_act;
                                                    }
                                                }
                                                echo '</div>';
                                            }else{
                                                $arr_act = array();
                                                $arr_act = json_decode($submenu_1_value->results);

                                                $str_html_act = '';
                                                if (!empty($arr_act)) {
                                                    $str_html_act .= '<div id="block_act_menu_' . $submenu_1_value->administrator_menu_id . '" class="act_block"><div class="checkbox" style="margin:0 0 5px 75px;">';

                                                    $tag_br = '';
                                                    $no = 0;
                                                    foreach ($arr_act as $key => $value) {
                                                        $checkbox_act = array();
                                                        $checkbox_act['name'] = 'action[' . $submenu_1_value->administrator_menu_id . '][]';
                                                        $checkbox_act['id'] = 'action-' . $submenu_1_value->administrator_menu_id . '-' . $no;
                                                        $checkbox_act['value'] = $value->name;
                                                        $checkbox_act['checked'] = FALSE;
                                                        $checkbox_act['class'] = 'act_menu_item menu-lev-3';

                                                        $str_html_act .= $tag_br . '<label>' . form_checkbox($checkbox_act) . '&nbsp;' . $value->title . '</label>';
                                                        $tag_br = '<br>';
                                                        $no++;
                                                    }
                                                    $str_html_act .= '</div></div>';
                                                }
                                                echo $str_html_act;
                                            }
                                        }
                                        echo '</div>';
                                    }else{
                                        $arr_act = array();
                                        $arr_act = json_decode($rootmenu_value->results);

                                        $str_html_act = '';
                                        if (!empty($arr_act)) {
                                            $str_html_act .= '<div id="block_act_menu_' . $rootmenu_value->administrator_menu_id . '" class="act_block"><div class="checkbox" style="margin:0 0 5px 50px;">';

                                            $tag_br = '';
                                            $no = 0;
                                            foreach ($arr_act as $key => $value) {
                                                $checkbox_act = array();
                                                $checkbox_act['name'] = 'action[' . $rootmenu_value->administrator_menu_id . '][]';
                                                $checkbox_act['id'] = 'action-' . $rootmenu_value->administrator_menu_id . '-' . $no;
                                                $checkbox_act['value'] = $value->name;
                                                $checkbox_act['checked'] = FALSE;
                                                $checkbox_act['class'] = 'act_menu_item menu-lev-3';

                                                $str_html_act .= $tag_br . '<label>' . form_checkbox($checkbox_act) . '&nbsp;' . $value->title . '</label>';
                                                $tag_br = '<br>';
                                                $no++;
                                            }
                                            $str_html_act .= '</div></div>';
                                        }
                                        echo $str_html_act;
                                    }
                                }
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; Save <?php echo isset($this->menu_info->menu_title) ? $this->menu_info->menu_title : '' ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end modal-->

<?php
}
?>

<?php 
if($is_superuser || isset($action['add']) ){
?>
<!--modal company-->
<div id="modal-company" class="modal fade" role="dialog">
    <div class="custom-loading"><span></span></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Form Add <?php echo isset($this->menu_info->menu_title) ? $this->menu_info->menu_title : '' ?></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <strong>Data Company Not Available!</strong> Please add data company first.
                </div>
            </div>
            <div class="modal-footer">
                <a href="<?php echo site_url('master/company/show'); ?>" class="btn btn-dark"><i class="fa fa-plus-circle"></i>&nbsp; Add Data Company</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal company-->
<?php
}
?>

<script>

    $("#gridview").flexigrid({
        url: '<?php echo site_url("admin/administrator_group/get_data"); ?>',
        dataType: 'json',
        colModel: [
            <?php if ($is_superuser || isset($action['update'])):
                echo "
                    { display: 'Edit', name: 'edit', width: 40, sortable: false,datasource: false , align: 'center' },
                    ";
            endif; ?>
            { display: 'Status', name: 'administrator_group_is_active', width: 40, sortable: true, align: 'center' },
            { display: 'Group Name', name: 'administrator_group_title', width: 250, sortable: true, align: 'left' },
            <?php if($is_superuser):
                echo "{ display: 'Company Name', name: 'company_title', width: 200, sortable: true, align: 'left' },";
            endif; ?>
            <?php if($is_superuser || $_SESSION['administrator_group_type'] == 'administrator_company'):
                echo "{ display: 'Warehouse Name', name: 'warehouse_name', width: 200, sortable: true, align: 'left' },";
            endif; ?>
            <?php if($is_superuser || $_SESSION['administrator_group_type'] == 'administrator_company'):
                echo "{ display: 'POS Name', name: 'pos_name', width: 200, sortable: true, align: 'left' },";
            endif; ?>
            <?php if ($is_superuser):
                echo "
                    { display: 'Group Type', name: 'administrator_group_type', width: 150, sortable: true, align: 'center' },
                    ";
            endif; ?>
        ],
        buttons: [
            <?php if ($is_superuser || isset($action['add'])):
                echo "
                    { display: 'Add', name: 'add', bclass: 'add', onpress: addAdministratorGroup },
                    { separator: true },
                    ";
            endif; ?>
            { display: 'Select All', name: 'selectall', bclass: 'selectall', onpress: check },
            { separator: true },
            { display: 'Unselect All', name: 'selectnone', bclass: 'selectnone', onpress: check },
            <?php if ($is_superuser || isset($action['activate'])) :
                echo "
                    { separator: true },
                    { display: 'Activate', name: 'publish', bclass: 'publish', onpress: act_show, urlaction: '" . site_url("admin/administrator_group/act_activate") . "' },
                    ";
            endif;
            if ($is_superuser || isset($action['deactivate'])):
                echo "
                    { separator: true },
                    { display: 'Deactivate', name: 'unpublish', bclass: 'unpublish', onpress: act_show, urlaction: '" . site_url("admin/administrator_group/act_deactivate") . "' },
                    ";
            endif;
            if ($is_superuser || isset($action['delete'])):
                echo "
                    { separator: true },
                    { display: 'Delete', name: 'delete', bclass: 'delete', onpress: act_show, urlaction: '" . site_url("admin/administrator_group/act_delete") . "' },
                        ";
            endif; ?>
        ],
        searchitems: [
            { display: 'Group Name', name: 'administrator_group_title', type: 'text', isdefault: true },
            <?php if($is_superuser):
                echo "{ display: 'Company Name', name: 'company_title', type: 'text' },";
            endif; ?>
            <?php if($is_superuser || $_SESSION['administrator_group_type'] == 'administrator_company'):
                echo "{ display: 'Warehouse Name', name: 'warehouse_name', type: 'text' },";
            endif; ?>
            <?php if($is_superuser || $_SESSION['administrator_group_type'] == 'administrator_company'):
                echo "{ display: 'POS Name', name: 'pos_name', type: 'text' },";
            endif; ?>
            <?php if ($is_superuser):
                echo "
                    { display: 'Group Type', name: 'administrator_group_type', type: 'select', option: 'superuser:Superuser|administrator_company:Company Administrator|administrator_warehouse:Warehouse Administrator|administrator_pos:POS Administrator|administrator_cashier:Cashier Administrator'},
                    ";
            endif; ?>
            { display: 'Status', name: 'administrator_group_is_active', type: 'select', option: '1:Active|0:Inactive'},
        ],
        sortname: "administrator_group_id",
        sortorder: "desc",
        usepager: true,
        title: '',
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        showToggleBtn: true,
        width: 'auto',
        height: '300',
        resizable: false,
        singleSelect: false
    });
    
    $(document).ready(function () {
        $('.my-select2').select2();
    
        <?php if ($is_superuser || isset($action['add']) || isset($action['update']) ) : ?>
            $('#modal').on('shown.bs.modal', function () {
                $('input[name="title"]').focus();
            });
        
            $("#privilege_menu .menu_item").on('change',function () {
                var id = $(this).attr('id');
                if ($("#" + id).is(':checked')) {
                    var parents = $(this).parent().parent().parent();

                    while (parents.attr('id') != 'block_menu' && id != 'block_menu') {
                        var parent_id = parents.attr('id');
                        $("#menu_" + parent_id.replace('block_menu_', '')).prop('checked', true);
                        parents = parents.parent();
                    }
                    //change child all item checked
                    $("#block_" + id + " .menu_item").prop('checked', true).change();
                    $('#block_act_'+id+' input[value="show"]').prop('checked', true).attr('disabled', 'disabled');
                    $('#block_act_'+id).show();
                } else {
                    var parents = $(this).parent().parent().parent();
                    if(parents.attr('id') != 'block_menu' && id != 'block_menu'){
                        var parent_id = parents.attr('id');
                        var countChild = $('#' + parent_id + ' .menu_item:checked').length;
                        if(countChild == 0){
                            $("#menu_" + parent_id.replace('block_menu_', '')).prop('checked', false);
                        }
                    }
                    //change child all item unchecked
                    $("#block_" + id + " .menu_item").prop('checked', false).change();
                    $('#block_act_'+id+' input').prop('checked', false).removeAttr('disabled');
                    $('#block_act_'+id).hide();
                }
            });
        
            $("#privilege_menu .act_menu_item").on('change',function () {
                var id = $(this).attr('id');
                var splitId = id.split('-');
                if ($("#" + id).is(':checked')) {
                        $("#menu_"+splitId[1]).prop('checked', true).change();
                        if($('#'+id).val() === 'show'){
                            $('#'+id).attr('disabled', 'disabled');
                        }

                        var parents = $("#menu_"+splitId[1]).parent().parent().parent();
                        while (parents.attr('id') != 'block_menu' && id != 'block_menu') {
                            var parent_id = parents.attr('id');
                            $("#menu_" + parent_id.replace('block_menu_', '')).prop('checked', true);
                            parents = parents.parent();
                        }

                } else {
                    $('#'+id).removeAttr('disabled');
                }
            });
        
            $("#allmenu").on('click',function () {
                $statusChecked =  $(this).is(':checked') ? true : false;
                $('#block_menu .menu_item').prop('checked', $statusChecked).change();
                $('#block_menu .act_menu_item').prop('checked', $statusChecked).change();
            });

            $("#privilege_menu .menu-lev-1").on('click', (event) => {
                $statusChecked =  $(event.currentTarget).is(':checked') ? true : false;
                $(event.currentTarget).closest('div').next().find('.menu-lev-2').prop('checked', $statusChecked).change();
                $(event.currentTarget).closest('div').next().find('.menu-lev-3').prop('checked', $statusChecked).change();
            });

            $("#privilege_menu .menu-lev-2").on('click', (event) => {
                $statusChecked =  $(event.currentTarget).is(':checked') ? true : false;
                $(event.currentTarget).closest('div').next().find('.menu-lev-3').prop('checked', $statusChecked).change();
            });
            
            $('#input-group-type').on('change', function() {
                $('#container-pos, #container-warehouse').hide();
                let value = $(this).val();
                if(value){
                    if(value == 'administrator_company'){
//                        $('#container-warehouse, #container-pos').show();
                    }else if(value == 'administrator_pos' || value == 'administrator_cashier'){
                        <?php if($_SESSION['administrator_group_type'] == 'administrator_company'): ?>
                            getDataPos('init', <?php echo $_SESSION['administrator_group_company_id']; ?>);
                        <?php endif; ?>
                        $('select[name="warehouse"]').val('').change();
                        $('#container-pos').show();
                    }else if(value == 'administrator_warehouse'){
                        <?php if($_SESSION['administrator_group_type'] == 'administrator_company'): ?>
                            getDataWarehouse('init', <?php echo $_SESSION['administrator_group_company_id']; ?>);
                        <?php endif; ?>
                        $('select[name="pos"]').val('').change();
                        $('#container-warehouse').show();
                    }
                }
            });
        <?php endif;

        if ($is_superuser || $_SESSION['administrator_group_type'] == 'administrator_company') : ?>
            $('select[name="type"]').on('change',function () {
                var typeVal = $(this).val();
                if(typeVal){
                     $(this).next()
                        .children('.selection')
                        .children('.select2-selection')
                        .removeClass('valid')
                        .removeClass('error')
                        .css('border-color', '');
                    $(this).next().next().remove();
                }
            });
        
            $('select[name="company"]').on('change',function () {
                var idCompany = $(this).val();
                $('#add-warehouse').hide();
                if(idCompany){
                    $('select[name="company"]')
                        .next()
                        .children('.selection')
                        .children('.select2-selection')
                        .removeClass('valid')
                        .removeClass('error')
                        .css('border-color', '');
                    $('select[name="company"]')
                        .next().next().remove();

                    $('select[name="warehouse"]')
                        .next()
                        .children('.selection')
                        .children('.select2-selection')
                        .removeClass('valid')
                        .removeClass('error')
                        .css('border-color', '');
                    $('select[name="warehouse"]')
                        .next().next().remove();
                
                    $('select[name="pos"]')
                        .next()
                        .children('.selection')
                        .children('.select2-selection')
                        .removeClass('valid')
                        .removeClass('error')
                        .css('border-color', '');
                    $('select[name="pos"]')
                        .next().next().remove();

                    getDataWarehouse('init', idCompany);
                    getDataPos('init', idCompany);
                }else{
                    $('select[name="warehouse"]').val('').attr('disabled', 'disabled').select2();

                     $('select[name="warehouse"]')
                        .next()
                        .children('.selection')
                        .children('.select2-selection')
                        .removeClass('valid')
                        .removeClass('error')
                        .css('border-color', '');
                    $('select[name="warehouse"]')
                        .next().next().remove();
                
                    $('select[name="pos"]').val('').attr('disabled', 'disabled').select2();

                     $('select[name="pos"]')
                        .next()
                        .children('.selection')
                        .children('.select2-selection')
                        .removeClass('valid')
                        .removeClass('error')
                        .css('border-color', '');
                    $('select[name="pos"]')
                        .next().next().remove();
                }
            });
        
            $('select[name="warehouse"]').on('change',function () {
                var idWarehouse = $(this).val();
                $('#add-warehouse').hide();
                if(idWarehouse){
                    $('select[name="warehouse"]')
                        .next()
                        .children('.selection')
                        .children('.select2-selection')
                        .removeClass('valid')
                        .removeClass('error')
                        .css('border-color', '');
                    $('select[name="warehouse"]')
                        .next().next().remove();
                }
            });
            
            $('select[name="pos"]').on('change',function () {
                var idPos = $(this).val();
                $('#add-pos').hide();
                if(idPos){
                    $('select[name="pos"]')
                        .next()
                        .children('.selection')
                        .children('.select2-selection')
                        .removeClass('valid')
                        .removeClass('error')
                        .css('border-color', '');
                    $('select[name="pos"]')
                        .next().next().remove();
                }
            });    
        <?php endif;

        if ($is_superuser || isset($action['add']) || isset($action['update']) ) : ?>
        
            $('#form').on('submit', function (e) {
                $('#form button[type="submit"]').attr('disabled', 'disabled');
                e.preventDefault();
                var urlForm = $('#form').attr('data-url');
                $.ajax({
                    type: 'POST',
                    url: urlForm,
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (res) {
                        console.log(res);
                        if (res.status == 200) {
                            $('#modal').modal('hide');
                            $('#form button[type="submit"]').removeAttr('disabled');
                            $('#gridview').flexReload();
                            var message_class = 'response_confirmation alert alert-success';

                            $("#response_message").finish();

                            $("#response_message").addClass(message_class);
                            $("#response_message").slideDown("fast");
                            $("#response_message").html(res.msg);
                            $("#response_message").delay(10000).slideUp(1000, function () {
                                $("#response_message").removeClass(message_class);
                            });
                        } else {
                            $('#modal').scrollTop('0px');
                            $('#form button[type="submit"]').removeAttr('disabled');
                            $("#modal-response-message").finish();

                            $("#modal-response-message").slideDown("fast");
                            $('#modal-response-message div').html(res.msg);
                            $("#modal-response-message").delay(10000).slideUp(1000);
                        }
                    },
                    error: function (err) {
                        $('#form button[type="submit"]').removeAttr('disabled');
                        console.log(err);
                    }
                });
            });   
        <?php endif; ?>
    });
    
<?php if ($is_superuser || isset($action['add'])) : ?>
    function addAdministratorGroup() {
        $('#form').trigger("reset");
        $('input:checkbox').removeAttr('checked');
        $('input.act_menu_item').prop('checked', false).removeAttr('disabled');
        $('#modal .modal-title').text('Form Add <?php echo isset($this->menu_info->menu_title) ? $this->menu_info->menu_title : '' ?>');
        $("#response_message").finish();
        $('#add-warehouse').hide();
        $('#add-pos').hide();
        $('.act_block').hide();
        $('#form').attr('data-url', '<?php echo site_url('admin/administrator_group/act_add'); ?>');
        $('#privilege_menu').show();
        $('#modal .modal-body').animate({scrollTop: '0px'}, 300);
        <?php if($is_superuser): ?>
            $('select[name="type"]').val('').change().select2();
            $('select[name="company"]').val('');
            $('select[name="warehouse"]').val('');
            $('select[name="pos"]').val('');
            $('#block_superuser').show();
            getDataCompany('init');
            getDataCompany('add');
        <?php else: ?>
            $('#modal').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
        <?php endif; ?>
    }
<?php endif;

if ($is_superuser || isset($action['update'])) : ?>

    function editAdministratorGroup(id) {
        $('#form').trigger("reset");
        $('input:checkbox').removeAttr('checked');
        $('input.act_menu_item').prop('checked', false).removeAttr('disabled');
        $('#modal .modal-title').text('Form Edit <?php echo isset($this->menu_info->menu_title) ? $this->menu_info->menu_title : '' ?>');
        $('#form input[name="administrator_group_id"]').val(id);
        $('#form').attr('data-url', '<?php echo site_url('admin/administrator_group/act_update'); ?>');
        $("#response_message").finish();
        $('.act_block').hide();
        <?php if($is_superuser) : ?>
            $('select[name="type"]').val('').change().select2();
            $('select[name="company"]').val('');
            $('select[name="warehouse"]').val('');
            $('select[name="pos"]').val('');
            $('#block_superuser').show();
        <?php endif; ?>
        getDataEdit(id);
    }
    
    function getDataEdit(id) {
        $.ajax({
            url: '<?php echo site_url('admin/administrator_group/get_data_by_id'); ?>',
            method: 'GET',
            data: 'id=' + id,
            dataType: 'json',
            success: function (res) {
//                console.log(res);
                if (res.data) {
                    $('#modal input[name="title"]').val(res.data.administrator_group_title);
                    <?php if($is_superuser): ?>
                        if(res.data.administrator_group_type !== 'superuser'){
                            $('#block_superuser').show();
                            $('select[name="type"]').val(res.data.administrator_group_type).select2();
                            getDataCompany('edit', res.data.administrator_group_company_id);
                            if(res.data.administrator_group_type == "administrator_company"){
//                                getDataWarehouse('edit', res.data.administrator_group_company_id, res.data.administrator_group_warehouse_id);
//                                getDataPos('edit', res.data.administrator_group_company_id, res.data.administrator_group_pos_id);
                            }else if(res.data.administrator_group_type == "administrator_warehouse"){
                                getDataWarehouse('edit', res.data.administrator_group_company_id, res.data.administrator_group_warehouse_id);
                            }else if(res.data.administrator_group_type == "administrator_pos" || res.data.administrator_group_type == "administrator_cashier"){
                                getDataPos('edit', res.data.administrator_group_company_id, res.data.administrator_group_pos_id);
                            }
                        }else{
                            $('#block_superuser').hide();
                        }
                    <?php else: 
                        if($_SESSION['administrator_group_type'] == 'administrator_company'): ?>
                            if(res.data.administrator_group_type !== 'superuser'){
                                $('#block_superuser').show();
                                $('select[name="type"]').val(res.data.administrator_group_type).select2();
                                if(res.data.administrator_group_type == "administrator_warehouse"){
                                    getDataWarehouse('edit', res.data.administrator_group_company_id, res.data.administrator_group_warehouse_id);
                                }else if(res.data.administrator_group_type == "administrator_pos" || res.data.administrator_group_type == "administrator_cashier"){
                                    getDataPos('edit', res.data.administrator_group_company_id, res.data.administrator_group_pos_id);
                                }
                            }else{
                                $('#block_superuser').hide();
                            }
                        <?php endif; ?>
                    <?php endif; ?>
                                        
                    if(res.data.administrator_group_id == <?php echo $this->session->userdata('administrator_group_id'); ?>){
                        $('#privilege_menu').hide();
                    }else{
                        if(res.arr_checked_menu.length > 0){
                            $.each(res.arr_checked_menu, function (key, value) {
                                $('#menu_' + value.id).prop('checked', true);
                                if(value.act){
                                    $.each(value.act, function (k, v) {
                                        if(v === 'show'){
                                            $('#block_act_menu_' + value.id + ' input[type="checkbox"][value="' + v + '"]').prop('checked', true).attr('disabled', 'disabled');
                                        }else{
                                            $('#block_act_menu_' + value.id + ' input[type="checkbox"][value="' + v + '"]').prop('checked', true);
                                        }
                                    });
                                    $('#block_act_menu_' + value.id).show();
                                }
                            });
                        }
                        
                        $('#privilege_menu').show();
                    }
                    $('#modal .modal-body').animate({scrollTop: '0px'}, 300);
                    
                    $('#modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    }, 'show');
                } else {
                    alert('Data not found !');
                    $('#gridview').flexReload();
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    }
<?php endif;

if ($is_superuser) : ?>

    function getDataCompany(type, idCompany) {
        $.ajax({
            url: '<?php echo site_url('admin/administrator_group/get_data_company'); ?>',
            method: 'GET',
            data: '',
            dataType: 'json',
            success: function (res) {
                if (type === 'add') {
                    if (res.length !== 0) {
                        
                        $('select[name="warehouse"]').attr('disabled', 'disabled').select2();
                        $('select[name="pos"]').attr('disabled', 'disabled').select2();
                        
                        $('#modal').modal({
                            backdrop: 'static',
                            keyboard: false
                        }, 'show');

                    } else {
                        $('#modal-company').modal({
                            backdrop: 'static',
                            keyboard: false
                        }, 'show');
                    }
                }
                
                if(type === 'edit'){
                    var option = '<option value="">--Choose Company--</option>';
                    var isSelected = '';
                    $.each(res, function (key, value) {
                        isSelected = '';
                        if(value.company_id == idCompany){
                            isSelected = 'selected="selected"';
                        }
                        option += '<option value="' + value.company_id + '" ' + isSelected + '>' + value.company_title + '</option>';
                    });

                    $('select[name="company"]').html(option).select2();
                }
                

                if (type === 'init') {
                    var option = '<option value="">--Choose Company--</option>';
                    $.each(res, function (key, value) {
                        option += '<option value="' + value.company_id + '">' + value.company_title + '</option>';
                    });

                    $('select[name="company"]').html(option).select2();
                }

            },
            error: function (err) {
                console.log(err);
            }
        });
    }
<?php endif; ?>

<?php if($is_superuser || $_SESSION['administrator_group_type'] == 'administrator_company'): ?>
    function getDataWarehouse(type, idCompany, idWarehouse) {
        $.ajax({
            url: '<?php echo site_url('admin/administrator_group/get_data_warehouse'); ?>',
            method: 'GET',
            data: 'id='+idCompany,
            dataType: 'json',
            success: function (res) {
                console.log(res);
                
                if (type === 'init') {
                    
                    var option = '<option value="">--Choose Warehouse--</option>';
                    
                    $.each(res, function (key, value) {
                        option += '<option value="' + value.warehouse_id + '">' + value.warehouse_name + '</option>';
                    });
                    
                }
                
                if(type === 'edit'){
                    $('#container-warehouse').show();
                    var option = '<option value="">--Choose Warehouse--</option>';
                    var isSelect = '';
                    $.each(res, function (key, value) {
                        isSelect = '';
                        if(value.warehouse_id == idWarehouse){
                            isSelect = 'selected="selected"';
                        }
                        option += '<option value="' + value.warehouse_id + '" ' + isSelect + '>' + value.warehouse_name + '</option>';
                    });
                    
                }
                
                $('select[name="warehouse"]')
                            .html(option)
                            .removeAttr('disabled')
                            .select2();
                $('select[name="warehouse"]')
                    .parent()
                    .removeClass('has-success');
                $('select[name="warehouse"]')
                            .next()
                            .children('.selection')
                            .children('.select2-selection')
                            .removeClass('valid')
                            .removeClass('error')
                            .css('border-color', '');
                    
                if(res.length > 0){
                        $('#add-warehouse').hide();
                        
                    }else{
                        $('#add-warehouse').show();
                    }

            },
            error: function (err) {
                console.log(err);
            }
        });
    }
    
    function getDataPos(type, idCompany, idPos) {
        $.ajax({
            url: '<?php echo site_url('admin/administrator_group/get_data_pos'); ?>',
            method: 'GET',
            data: 'id='+idCompany,
            dataType: 'json',
            success: function (res) {
                
                if (type === 'init') {
                    
                    var option = '<option value="">--Choose POS--</option>';
                    
                    $.each(res, function (key, value) {
                        option += '<option value="' + value.pos_id + '">' + value.pos_name + '</option>';
                    });
                    
                }
                
                if(type === 'edit'){
                    $('#container-pos').show();
                    var option = '<option value="">--Choose POS--</option>';
                    var isSelect = '';
                    $.each(res, function (key, value) {
                        isSelect = '';
                        if(value.pos_id == idPos){
                            isSelect = 'selected="selected"';
                        }
                        option += '<option value="' + value.pos_id + '" ' + isSelect + '>' + value.pos_name + '</option>';
                    });
                    
                }
                
                $('select[name="pos"]')
                            .html(option)
                            .removeAttr('disabled')
                            .select2();
                $('select[name="pos"]')
                    .parent()
                    .removeClass('has-success');
                $('select[name="pos"]')
                            .next()
                            .children('.selection')
                            .children('.select2-selection')
                            .removeClass('valid')
                            .removeClass('error')
                            .css('border-color', '');
                    
                if(res.length > 0){
                        $('#add-pos').hide();
                    }else{
                        $('#add-pos').show();
                    }

            },
            error: function (err) {
                console.log(err);
            }
        });
    }
<?php endif; ?>
</script>

<!--form validator-->
<script src="<?php echo THEMES_BACKEND; ?>/vendor/js/form-validator/jquery.form-validator.min.js"></script>

<script>
     $.validate({
         onError: function(){
            $('#modal .modal-body').animate({scrollTop: '0px'}, 300);
        }
//         lang: 'id'
     });
</script>