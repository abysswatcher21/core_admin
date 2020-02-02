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
if($is_superuser || isset($action['add'])){
?>
<!-- Modal Add -->
<div id="modal-add" class="modal fade" role="dialog" style="overflow: hidden">
    <div class="custom-loading"><span></span></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Form Add <?php echo isset($this->menu_info->menu_title) ? $this->menu_info->menu_title : '' ?></h4>
            </div>
            <form id="form-add" class="form-horizontal form-label-left" data-url="">
                <div class="modal-body" style="overflow-y: auto; max-height: calc(100vh - 200px);">
                    <div id="modal-response-message-add" class="alert alert-danger alert-dismissible fade in" role="alert" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <div></div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="administrator_group_id">Group <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo form_dropdown('administrator_group_id', '', '', 'data-validation="required" class="form-control"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="username">Username <span class="required">*</span>
                        </label>
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <?php echo form_input('username', '', 'data-validation="required alphanumeric length" data-validation-length="6-15" data-validation-allowing="-_" data-validation-help="use a-z, 0-9, minus sign and underscore" class="form-control"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="password">Password <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo form_password('password', '', 'data-validation="required length" data-validation-length="6-12" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="password_conf">Repeat Password <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo form_password('password_conf', '', 'data-validation="required confirmation" data-validation-confirm="password" data-validation-error-msg="must be same as password column" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name">Name <span class="required">*</span>
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <?php echo form_input('name', '', 'data-validation="required" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="mobilephone">Mobilephone Number <span class="required">*</span>
                        </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <?php echo form_input('mobilephone', '', 'data-validation="required number length" data-validation-length="10-13" data-validation-help="example : 081234567890" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="email">Email
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <?php echo form_input('email', '', 'data-validation="email" data-validation-optional="true" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="image">Profile Picture
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <img id="preview-image-add" src="" border="0" alt="image" style="max-width: 250px; max-height: 250px; margin: auto; display: block">
                            <br>
                            <?php echo form_upload('image', '', 'id="btn-upload-add" data-validation="mime size" data-validation-max-size="1M" class="form-control col-md-7 col-xs-12" data-validation-allowing="jpg, jpeg, png, gif" accept=".gif, .jpg, .jpeg, .png"'); ?>
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
<!--end modal add-->
<?php 
} 

if($is_superuser || isset($action['update'])){
?>
<!-- Modal edit -->
<div id="modal-edit" class="modal fade" role="dialog" style="overflow: hidden">
    <div class="custom-loading"><span></span></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Form Edit <?php echo isset($this->menu_info->menu_title) ? $this->menu_info->menu_title : '' ?></h4>
            </div>
            <form id="form-edit" class="form-horizontal form-label-left" data-url="">
                <div class="modal-body" style="overflow-y: auto; max-height: calc(100vh - 200px);">
                    <div id="modal-response-message-edit" class="alert alert-danger alert-dismissible fade in" role="alert" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <div></div>
                    </div>

                    <?php echo form_hidden('id', ''); ?>
                    <?php echo form_hidden('old_image', ''); ?>

                    <div class="form-group" id="input-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="administrator_group_id">Grup <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo form_dropdown('administrator_group_id', '', '', 'data-validation="required" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="username">Username <span class="required">*</span>
                        </label>
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <?php echo form_input('username', '', 'data-validation="required alphanumeric length" data-validation-length="6-15" data-validation-allowing="-_" data-validation-help="use a-z, 0-9, minus sign and underscore" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name">Name <span class="required">*</span>
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <?php echo form_input('name', '', 'data-validation="required" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="mobilephone">Mobilephone Number <span class="required">*</span>
                        </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <?php echo form_input('mobilephone', '', 'data-validation="required number length" data-validation-length="10-13" data-validation-help="example : 081234567890" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="email">Email
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <?php echo form_input('email', '', 'data-validation="email" data-validation-optional="true" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="image">Profile Picture
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <img id="preview-image-edit" src="" border="0" alt="image" style="max-width: 250px; max-height: 250px; margin: auto; display: block">
                            <br>
                            <?php echo form_upload('image', '', 'id="btn-upload-edit" data-validation="mime size" data-validation-max-size="1M" class="form-control col-md-7 col-xs-12" data-validation-allowing="jpg, jpeg, png, gif" accept=".gif, .jpg, .jpeg, .png"'); ?>
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
<!--end modal edit-->
<?php 
}

if($is_superuser || isset($action['update_password'])){
?>
<!-- Modal password -->
<div id="modal-password" class="modal fade" role="dialog">
    <div class="custom-loading"><span></span></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Form Edit Password <?php echo isset($this->menu_info->menu_title) ? $this->menu_info->menu_title : '' ?></h4>
            </div>
            <form id="form-password" class="form-horizontal form-label-left" data-url="">
                <div class="modal-body">
                    <div id="modal-response-message-password" class="alert alert-danger alert-dismissible fade in" role="alert" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <div></div>
                    </div>

                    <?php echo form_hidden('id', ''); ?>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="username">Username
                        </label>
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <?php echo form_input('username', '', 'class="form-control col-md-7 col-xs-12" readonly="readonly"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name">Name
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <?php echo form_input('name', '', 'class="form-control col-md-7 col-xs-12" readonly="readonly"'); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="password">Password <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo form_password('password', '', 'data-validation="required length" data-validation-length="6-12" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="password_conf">Repeat Password <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo form_password('password_conf', '', 'data-validation="required confirmation" data-validation-confirm="password" data-validation-error-msg="must be same as password column" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; Save Password <?php echo isset($this->menu_info->menu_title) ? $this->menu_info->menu_title : '' ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end modal password-->
<?php
}

if($is_superuser){
?>
<!--modal group-->
<div id="modal-group" class="modal fade" role="dialog">
    <div class="custom-loading"><span></span></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Form Add <?php echo isset($this->menu_info->menu_title) ? $this->menu_info->menu_title : '' ?></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <strong>There is no Data Administrator Group!</strong> Please add Data Administrator Group first.
                </div>
            </div>
            <div class="modal-footer">
                <a href="<?php echo site_url('admin/administrator_group'); ?>" class="btn btn-dark"><i class="fa fa-plus-circle"></i>&nbsp; Add Data Administrator Group</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal group-->
<?php
} 
?>


<script>
    $("#gridview").flexigrid({
        url: '<?php echo site_url("admin/administrator/get_data"); ?>',
        dataType: 'json',
        colModel: [
<?php
if ($is_superuser || isset($action['update'])) {
    echo "{display: 'Edit', name: 'edit', width: 40, sortable: false, align: 'center', datasource: false},";
}

if($is_superuser || isset($action['update_password'])){
    echo "{display: 'Password', name: 'edit_password', width: 60, sortable: false, align: 'center', datasource: false},";
}
?>
            {display: 'Status', name: 'administrator_is_active', width: 40, sortable: true, align: 'center'},
            {display: 'Username', name: 'administrator_username', width: 120, sortable: true, align: 'left'},
            {display: 'Name', name: 'administrator_name', width: 200, sortable: true, align: 'left'},
            {display: 'Phone Number', name: 'administrator_mobilephone', width: 120, sortable: true, align: 'left'},
            {display: 'E-Mail', name: 'administrator_email', width: 150, sortable: true, align: 'left'},
            {display: 'Group', name: 'administrator_group_title', width: 150, sortable: true, align: 'left'},
            {display: 'Last Login', name: 'administrator_last_login', width: 180, sortable: true, align: 'center'},
            
            
        ],
        buttons: [
<?php
if($is_superuser || isset($action['add'])){
    echo "{display: 'Add', name: 'add', bclass: 'add', onpress: addAdministrator},
            {separator: true},";
}
?>
            {display: 'Select All', name: 'selectall', bclass: 'selectall', onpress: check},
            {separator: true},
            {display: 'Unselect All', name: 'selectnone', bclass: 'selectnone', onpress: check},
<?php
if($is_superuser || isset($action['activate'])){
    echo "{separator: true},
            {display: 'Activate', name: 'publish', bclass: 'publish', onpress: act_show, urlaction: '" . site_url("admin/administrator/act_activate") . "'},";
}

if($is_superuser || isset($action['deactivate'])){
    echo "{separator: true},
            {display: 'Deactivate', name: 'unpublish', bclass: 'unpublish', onpress: act_show, urlaction: '" . site_url("admin/administrator/act_deactivate") . "'},";
}

if($is_superuser || isset($action['delete'])){
    echo "{separator: true},
            {display: 'Delete', name: 'delete', bclass: 'delete', onpress: act_show, urlaction: '" . site_url("admin/administrator/act_delete") . "'},";
}
?>
        ],
        searchitems: [
            {display: 'Username', name: 'administrator_username', type: 'text', isdefault: true},
            {display: 'Name', name: 'administrator_name', type: 'text'},
<?php
if($is_superuser){
    echo "{display: 'Grup', name: 'administrator_group_id', type: 'select', option: '" . $administrator_group_grid_options . "'},";
}
?>
            {display: 'Last Login', name: 'administrator_last_login', type: 'date'},
            {display: 'Status', name: 'administrator_is_active', type: 'select', option: '1:Active|0:Inactive'},
        ],
        sortname: "administrator_id",
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
<?php
if($is_superuser || isset($action['add']) || isset($action['update'])){
?>
        
        $('#modal-add').on('shown.bs.modal', function () {
            $('#modal-add select[name="administrator_group_id"]').select2('open');
        });
        
        $('#modal-edit').on('shown.bs.modal', function () {
            var userId = $('#modal-edit input[name="id"]').val();
            if(userId != <?php echo $this->session->userdata('administrator_id'); ?>){
                $('#modal-edit select[name="administrator_group_id"]').select2('open');
            }else{
                $('#modal-edit input[name="username"]').focus();
            }
        });
        
        
        $('#modal-add select[name="administrator_group_id"], #modal-edit select[name="administrator_group_id"]').on('change',function () {
            var value = $(this).val();
            if(value){
                $(this)
                    .next()
                    .children('.selection')
                    .children('.select2-selection')
                    .removeClass('valid')
                    .removeClass('error')
                    .css('border-color', '');
                $(this)
                    .next().next().remove();
            }
        });
<?php
}

if($is_superuser || isset($action['add'])){
?>
        $("#btn-upload-add").change(function () {
            readURL(this, 'add');
        });
        
        $('#form-add').on('submit', function (e) {
            $('#form-add button[type="submit"]').attr('disabled', 'disabled');
            e.preventDefault();

            var urlForm = $('#form-add').attr('data-url');
            $.ajax({
                type: 'POST',
                url: urlForm,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
//                    console.log(data);
                    if (data['status'] == 200) {
                        $('#modal-add').modal('hide');
                        $('#form-add button[type="submit"]').removeAttr('disabled');
                        $('#gridview').flexReload();
                        var message_class = 'response_confirmation alert alert-success';

                        $("#response_message").finish();

                        $("#response_message").addClass(message_class);
                        $("#response_message").slideDown("fast");
                        $("#response_message").html(data['msg']);
                        $("#response_message").delay(10000).slideUp(1000, function () {
                            $("#response_message").removeClass(message_class);
                        });
                    } else {
                        $('#form-add button[type="submit"]').removeAttr('disabled');
                        $("#modal-response-message-add").finish();

                        $("#modal-response-message-add").slideDown("fast");
                        $('#modal-response-message-add div').html(data['msg']);
                        $("#modal-response-message-add").delay(10000).slideUp(1000);
                    }
                },
                error: function (err) {
                    $('#form-add button[type="submit"]').removeAttr('disabled');
                    console.log(err);
                }
            });
        });
<?php
}

if($is_superuser || isset($action['update'])){
?>
         $("#btn-upload-edit").change(function () {
            readURL(this, 'edit');
        });

        $('#form-edit').on('submit', function (e) {
            $('#form-edit button[type="submit"]').attr('disabled', 'disabled');
            e.preventDefault();

            var urlForm = $('#form-edit').attr('data-url');
            $.ajax({
                type: 'POST',
                url: urlForm,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
//                    console.log(data);
                    if (data['status'] == 200) {
                        $('#modal-edit').modal('hide');
                        $('#form-edit button[type="submit"]').removeAttr('disabled');
                        $('#gridview').flexReload();
                        var message_class = 'response_confirmation alert alert-success';

                        $("#response_message").finish();

                        $("#response_message").addClass(message_class);
                        $("#response_message").slideDown("fast");
                        $("#response_message").html(data['msg']);
                        $("#response_message").delay(10000).slideUp(1000, function () {
                            $("#response_message").removeClass(message_class);
                        });
                    } else {
                        $('#form-edit button[type="submit"]').removeAttr('disabled');
                        $("#modal-response-message-edit").finish();

                        $("#modal-response-message-edit").slideDown("fast");
                        $('#modal-response-message-edit div').html(data['msg']);
                        $("#modal-response-message-edit").delay(10000).slideUp(1000);
                    }
                },
                error: function (err) {
                    $('#form-edit button[type="submit"]').removeAttr('disabled');
                    console.log(err);
                }
            });
        });
<?php
}

if($is_superuser || isset($action['update_password'])){
?>
        $('#modal-password').on('shown.bs.modal', function () {
            $('#modal-password input[name="password"]').focus();
        });
        
        $('#form-password').on('submit', function (e) {
            $('#form-password button[type="submit"]').attr('disabled', 'disabled');
            e.preventDefault();

            var urlForm = $('#form-password').attr('data-url');
            $.ajax({
                type: 'POST',
                url: urlForm,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    if (data['status'] == 200) {
                        $('#modal-password').modal('hide');
                        $('#form-password button[type="submit"]').removeAttr('disabled');
                        $('#gridview').flexReload();
                        var message_class = 'response_confirmation alert alert-success';

                        $("#response_message").finish();

                        $("#response_message").addClass(message_class);
                        $("#response_message").slideDown("fast");
                        $("#response_message").html(data['msg']);
                        $("#response_message").delay(10000).slideUp(1000, function () {
                            $("#response_message").removeClass(message_class);
                        });
                    } else {
                        $('#form-password button[type="submit"]').removeAttr('disabled');
                        $("#modal-response-message-password").finish();

                        $("#modal-response-message-password").slideDown("fast");
                        $('#modal-response-message-password div').html(data['msg']);
                        $("#modal-response-message-password").delay(10000).slideUp(1000);
                    }
                },
                error: function (err) {
                    $('#form-password button[type="submit"]').removeAttr('disabled');
                    console.log(err);
                }
            });
        });
<?php
}
?>
    });

<?php
if($is_superuser || isset($action['add']) || isset($action['update'])){
?>
     function readURL(input, id) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#preview-image-' + id).attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function getDataGroupAdmin(type, idGroup) {
        $.ajax({
            url: '<?php echo site_url('admin/administrator/get_data_group_admin'); ?>',
            method: 'GET',
            data: '',
            dataType: 'json',
            success: function (res) {
                if (type === 'add') {
                    if (res.length !== 0) {
//                        console.log(res);
                        
                        $('#modal-add').modal({
                            backdrop: 'static',
                            keyboard: false
                        }, 'show');

                    } else {
                        $('#modal-group').modal({
                            backdrop: 'static',
                            keyboard: false
                        }, 'show');
                    }
                }
                
                if(type === 'edit'){
                    var option = '<option value="">--Choose Group--</option>';
                    var isSelected = '';
                    $.each(res, function (key, value) {
                        isSelected = '';
                        if(value.administrator_group_id == idGroup){
                            isSelected = 'selected="selected"';
                        }
                        option += '<option value="' + value.administrator_group_id + '" ' + isSelected + '>' + value.administrator_group_title + '</option>';
                    });

                    $('#modal-edit select[name="administrator_group_id"]').html(option).select2();
                }
                

                if (type === 'init') {
                    var option = '<option value="">--Choose Group--</option>';
                    $.each(res, function (key, value) {
                        option += '<option value="' + value.administrator_group_id + '">' + value.administrator_group_title + '</option>';
                    });

                    $('#modal-add select[name="administrator_group_id"]')
                            .html(option).select2();
                }

            },
            error: function (err) {
                console.log(err);
            }
        });
    }
    
<?php
}

if($is_superuser || isset($action['add'])){
?>
    function addAdministrator() {
        $('#form-add').trigger("reset");
        $('#form-add').attr('data-url', '<?php echo site_url('admin/administrator/act_add'); ?>');
        $('#preview-image-add').attr('src', '<?php echo THEMES_BACKEND . '/images/no-img.jpg'; ?>');
        getDataGroupAdmin('init');
        getDataGroupAdmin('add');
        $("#modal-response-message-add").finish();
        $('#modal-add .modal-body').animate({scrollTop: '0px'}, 300);
    }
<?php
}

if($is_superuser || isset($action['update'])){
?>
    function editAdministrator(id) {
        $('#form-edit').trigger("reset");
        $('#form-edit').attr('data-url', '<?php echo site_url('admin/administrator/act_update'); ?>');
        getDataEdit(id, 'edit');
        $('#input-group').show();
        $("#modal-response-message-edit").finish();
        $('#modal-edit .modal-body').animate({scrollTop: '0px'}, 300);
        $('#modal-edit').modal({
            backdrop: 'static',
            keyboard: false
        }, 'show');
    }
<?php
}

if($is_superuser || isset($action['update_password'])){
?>
    function editPassword(id) {
        $('#form-password').trigger("reset");
        $('#form-password').attr('data-url', '<?php echo site_url('admin/administrator/act_update_password'); ?>');
        getDataEdit(id, 'password');
        $('#input-group').show();
        $("#modal-response-message-password").finish();
        $('#modal-password').modal({
            backdrop: 'static',
            keyboard: false
        }, 'show');
    }
<?php
}

if($is_superuser || isset($action['update']) || isset($action['update_password'])){
?>
    function getDataEdit(id, type) {
        $.ajax({
            url: '<?php echo site_url('admin/administrator/get_data_by_id'); ?>',
            method: 'GET',
            data: 'id='+id,
            dataType: 'json',
            success: function (res) {
                if(res){
                    if (type === 'edit') {
                        $('#modal-edit input[name="id"]').val(res.administrator_id);
                        $('#modal-edit input[name="old_image"]').val(res.administrator_image);
                        $('#modal-edit input[name="username"]').val(res.administrator_username);
                        $('#modal-edit input[name="name"]').val(res.administrator_name);
                        $('#modal-edit input[name="mobilephone"]').val(res.administrator_mobilephone);
                        $('#modal-edit input[name="email"]').val(res.administrator_email);
                        
                        if(res.administrator_id == <?php echo $this->session->userdata('administrator_id'); ?>){
                            $('#modal-edit input[name="administrator_group_id"]').val(res.administrator_group_id);
                            $('#input-group').hide();
                        }else{
                            $('#modal-edit input[name="administrator_group_id"]').val(res.administrator_group_id);
                            $('#input-group').show();
                            getDataGroupAdmin('edit', res.administrator_group_id);
                        }
                        
                        if (res.administrator_image) {
                            $('#preview-image-edit').attr('src', '<?php echo site_url('media/' . _dir_administrator . '250/250/') ?>' + res.administrator_image);
                        } else {
                            $('#preview-image-edit').attr('src', '<?php echo THEMES_BACKEND . '/images/no-img.jpg'; ?>');
                        }
                    }

                    if(type === 'password'){
                        $('#modal-password input[name="id"]').val(res.administrator_id);
                        $('#modal-password input[name="username"]').val(res.administrator_username);
                        $('#modal-password input[name="name"]').val(res.administrator_name);
                    }
                }else{
                    alert('Data tidak ditemukan !');
                    $('#gridview').flexReload();
                }


            },
            error: function (err) {
                console.log(err);
            }
        });
    }
<?php
}
?>
    
</script>

<!--form validator-->
<script src="<?php echo THEMES_BACKEND; ?>/vendor/js/form-validator/jquery.form-validator.min.js"></script>

<script>
    $.validate({
        modules: 'file, security',
        onError: function(){
            $('#modal-add .modal-body').animate({scrollTop: '0px'}, 300);
            $('#modal-edit .modal-body').animate({scrollTop: '0px'}, 300);
        }
        // lang: 'id'
    });
</script>
