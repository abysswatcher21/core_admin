<style>
    #detail 
    .table>tbody>tr>td, 
    .table>tbody>tr>th, 
    .table>tfoot>tr>td, 
    .table>tfoot>tr>th, 
    .table>thead>tr>td, 
    .table>thead>tr>th{
        padding: 4px;
    }
</style>
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
if($is_superuser || isset($action['update'])){
?>
<!-- Modal -->
<div id="modal" class="modal fade" role="dialog">
    <div class="custom-loading"><span></span></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <form id="form-company" class="form-horizontal form-label-left" data-url="">
                <div class="modal-body">
                    <div id="modal-response-message" class="alert alert-danger alert-dismissible fade in" role="alert" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <div></div>
                    </div>

                    <input type="hidden" name="id">

                    <ul class="nav nav-tabs bar_tabs" role="tablist">
                        <li class="active"><a data-toggle="tab" href="#company">Company</a></li>
                        <li><a data-toggle="tab" href="#pf">Phone or Fax</a></li>
                        <li><a data-toggle="tab" href="#cp">Contact Person</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="company" class="tab-pane fade in active">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label" for="title">Company Name <span class="required">*</span>
                                        </label>
                                        <input type="text" name="title" class="form-control" data-validation="required length" data-validation-length="max50">
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label" for="address">Company Address <span class="required">*</span>
                                        </label>
                                        <textarea name="address" class="form-control" data-validation="required length" data-validation-length="max50"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label" for="province">Province
                                        </label>
                                        <select name="province" data-validation="" class="form-control">
                                            <option value="">--Choose Province--</option>
                                            <?php
                                            foreach ($province as $value) {
                                                ?>
                                                <option value="<?php echo $value->province_id; ?>"><?php echo $value->province_name ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label" for="subdistrict">Subdistrict <span class="required"></span>
                                        </label>
                                        <select name="subdistrict" data-validation="" class="form-control">
                                            <option value="">--Choose Subdistrict--</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label" for="city">City <span class="required"></span>
                                        </label>
                                        <select name="city" data-validation="" class="form-control">
                                            <option value="">--Choose City--</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label" for="zip_code">Postal code
                                        </label>
                                        <input type="text" name="zip_code" class="form-control" data-validation="number" data-validation-optional="true">
                                    </div>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group" style="margin: 5px auto; width: 50%;">
                                        <label class="control-label" for="logo">Company Logo
                                        </label>
                                        <img id="preview-logo" src="" border="0" alt="logo" style="max-width: 200px; max-height: 200px; margin: auto; display: block">
                                        <br>
                                        <input type="hidden" name="old_logo" id="old-logo" value="">
                                        <input type="file" name="logo" id="btn-upload-logo" data-validation="mime size" data-validation-max-size="1M" class="form-control" data-validation-allowing="jpg, jpeg, png, gif" accept=".gif, .jpg, .jpeg, .png">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div id="pf" class="tab-pane fade">
                            <div id="table-phonefax">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h4 style="color: #26b99a; display: inline">List Phone or Fax</h4>
                                        <a href="javascript:;" id="open-form-phonefax" class="btn btn-dark btn-sm pull-right"><i class="fa fa-plus-circle"></i> &nbsp; Add</a>
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="20%">Fax</th>
                                                    <th width="25%">Phone</th>
                                                    <th width="25%">Mobilephone</th>
                                                    <th width="25%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="x_panel tile x_panel_form" id="form-phonefax">
                                <div class="x_title">
                                    <h5><i class="fa fa-plus-circle"></i>Form Add Phone or Fax</h5>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <div class="row">
                                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                            <label>Fax Number</label>
                                            <input type="text" name="fax" class="form-control" data-validation="length number" data-validation-optional="true" data-validation-length="max15">
                                        </div>
                                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                            <label>Phone Number</label>
                                            <input type="text" name="phone" class="form-control" data-validation="length number" data-validation-optional="true" data-validation-length="max15">
                                        </div>
                                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                            <label>Mobilephone Number
                                            </label>
                                            <input type="text" name="mobile_phone" class="form-control" data-validation="length number" data-validation-optional="true" data-validation-length="10-13">
                                        </div>
                                    </div>
                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <a href="javascript:;" id="submit-pf" class="btn btn-sm btn-dark"><i class="fa fa-plus-circle"></i>&nbsp;Add</a>
                                        <a href="javascript:;" id="cancel-pf" class="btn btn-sm btn-default">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="cp" class="tab-pane fade">
                            <div id="table-contactperson">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h4 style="color: #26b99a; display: inline">List Contact Person</h4>
                                        <a href="javascript:;" id="open-form-cp" class="btn btn-dark btn-sm pull-right"><i class="fa fa-plus-circle"></i>&nbsp; Add</a>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="20%">Name</th>
                                                    <th width="30%">Address</th>
                                                    <th width="20%">Phone</th>
                                                    <th width="25%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="x_panel tile x_panel_form" id="form-contactperson">
                                <div class="x_title">
                                    <h5><i class="fa fa-plus-circle"></i>Form Add Contact Person</h5>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <div class="row">
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label>Name</label>
                                            <input type="text" name="cp_name" class="form-control" data-validation="length" data-validation-optional="true" data-validation-length="max50">
                                        </div>
                                        <div class="form-group col-md-9 col-sm-9 col-xs-12">
                                            <label>Address</label>
                                            <textarea name="cp_address" class="form-control" data-validation="length" data-validation-optional="true" data-validation-length="max50"></textarea>
                                        </div>
                                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                            <label>Phone Number
                                            </label>
                                            <input type="text" name="cp_phone" class="form-control" data-validation="length number" data-validation-optional="true" data-validation-length="10-13"">
                                        </div>
                                    </div>
                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <a href="javascript:;" id="submit-cp" class="btn btn-sm btn-dark"><i class="fa fa-plus-circle"></i>&nbsp;Add</a>
                                        <a href="javascript:;" id="cancel-cp" class="btn btn-sm btn-default">Cancel</a>
                                    </div>
                                </div>
                            </div>
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
<!-- end modal -->
<?php
}
?>

<!--modal detail-->
<div id="detail" class="modal fade" role="dialog">
    <div class="custom-loading"><span></span></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                
                <div style="margin: 10px auto; width: 50%; text-align: center;">
                    <img src="" id="logo-detail" style="width: 150px; height: 150px;"><br>
                    <span><strong>COMPANY LOGO</strong></span>
                </div>
                
                <div id="table-detail-company"></div>

                <div class="row">
                    <div id="table-detail-pf" class="col-md-6 col-sm-6 col-xs-12">
                        <h5>Phone or Fax</h5>
                    </div>

                    <div id="table-detail-cp" class="col-md-6 col-sm-6 col-xs-12">
                        <h5>Contact Person</h5>
                    </div>
                    
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal detail-->

<script>
<?php
if($is_superuser ||  isset($action['update'])){
?>
     //-------GLOBALS-----------------
    var arrDataPhoneFax = [];
    var arrDataContactPerson = [];
    //------------------------------
<?php
}
?>
    
    $("#gridview").flexigrid({
        url: '<?php echo site_url("master/company/get_data"); ?>',
        dataType: 'json',
        colModel: [
        <?php if($is_superuser ||  isset($action['update'])):
            echo "{display: 'Edit', name: 'edit', width: 40, sortable: false, align: 'center', datasource: false},";
        endif; ?>
            {display: 'Detail', name: 'detail', width: 40, sortable: false, align: 'center', datasource: false},
            {display: 'Company Name', name: 'company_title', width: 200, sortable: true, align: 'left'},
            {display: 'Company Address', name: 'company_address', width: 200, sortable: true, align: 'left'},
            {display: 'Province', name: 'company_province_name', width: 150, sortable: true, align: 'left'},
            {display: 'City', name: 'company_city_name', width: 180, sortable: true, align: 'left'},
            {display: 'Subdistrict', name: 'company_subdistrict_name', width: 150, sortable: true, align: 'left'},
            {display: 'Postal Code', name: 'company_zip_code', width: 120, sortable: true, align: 'center'},
            {display: 'Phone / Fax', name: 'phone_fax', width: 180, sortable: false, align: 'left', hide: true},
            {display: 'Contact Person', name: 'contact_person', width: 180, sortable: false, align: 'left', hide: true},
        ],
        buttons: [
        <?php if($is_superuser):
         echo "{display: 'Add', name: 'add', bclass: 'add', onpress: addCompany},
               {separator: true},";
        endif; ?>
         {display: 'Select All', name: 'selectall', bclass: 'selectall', onpress: check},
         {separator: true},
         {display: 'Unselect All', name: 'selectnone', bclass: 'selectnone', onpress: check},
        <?php if($is_superuser):
         echo "
                {separator: true},
                {display: 'Delete', name: 'delete', bclass: 'delete', onpress: act_show, urlaction: '" . site_url('master/company/act_delete') . "'},
                ";
        endif; ?>
        ],
        <?php if($is_superuser):
        echo "
            buttons_right: [
                {display: 'Export Excel', name: 'excel', bclass: 'excel', onpress: export_data, urlaction: '" . site_url("master/company/export_data_company") . "'}
            ],";
        endif; ?>
        <?php if($is_superuser): ?>
        searchitems: [
            {display: 'Company Name', name: 'company_title', type: 'text', isdefault: true},
            {display: 'Company Address', name: 'company_address', type: 'text'},
            {display: 'Province', name: 'company_province_name', type: 'text'},
            {display: 'City', name: 'company_city_name', type: 'text'},
            {display: 'Subdistrict', name: 'company_subdistrict_name', type: 'text'},
            {display: 'Postal Code', name: 'company_zip_code', type: 'text'},
        ],
        <?php endif; ?>
        sortname: "company_id",
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
       
       $('#btn-upload-logo').on('change', function (){
            readURL(this, '#preview-logo');
       });
        
<?php
if($is_superuser ||  isset($action['update'])){
?>
         $('ul.nav-tabs li a').on('click', function (e){
            if($(this).parent().attr('class') == ''){
                var elementId = $(this).attr('href');
                setTimeout(function (){
                    $(elementId).find('input')[0].focus();
                }, 200);
            }
        });
        
        $('#modal').on('shown.bs.modal', function () {
            $('input[name="title"]').focus();
        });

        $('select[name="province"], select[name="city"], select[name="subdistrict"]').select2();

        $('#form-company').on('submit', function (e) {
            $('#form-company button[type="submit"]').attr('disabled', 'disabled');
            e.preventDefault();

            var urlForm = $('#form-company').attr('data-url');

            var formData = new FormData(this);

            formData.append('phone_fax', JSON.stringify(arrDataPhoneFax));
            formData.append('contact_person', JSON.stringify(arrDataContactPerson));

            var valueProvince = $('select[name="province"]').val();
            var valueCity = $('select[name="city"]').val();
            var valueSubdistrict = $('select[name="subdistrict"]').val();

            formData.set('province', '');
            if (valueProvince) {
                formData.set('province', $('select[name="province"] option[value="' + valueProvince + '"]').text());
            }

            formData.set('city', '');
            if (valueCity) {
                formData.set('city', $('select[name="city"] option[value="' + valueCity + '"]').text());
            }

            formData.set('subdistrict', '');
            if (valueSubdistrict) {
                formData.set('subdistrict', $('select[name="subdistrict"] option[value="' + valueSubdistrict + '"]').text());
            }

            formData.delete('fax');
            formData.delete('phone');
            formData.delete('mobile_phone');
            formData.delete('cp_name');
            formData.delete('cp_address');
            formData.delete('cp_phone');

//            for (var pair of formData.entries()) {
//                console.log(pair[0] + ', ' + pair[1]);
//            }

            $.ajax({
                type: 'POST',
                url: urlForm,
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    if (data['status'] == 200) {
                        $('#modal').modal('hide');
                        $('#form-company button[type="submit"]').removeAttr('disabled');
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
                        $('#form-company button[type="submit"]').removeAttr('disabled');
                        $("#modal-response-message").finish();

                        $("#modal-response-message").slideDown("fast");
                        $('#modal-response-message div').html(data['msg']);
                        $("#modal-response-message").delay(10000).slideUp(1000);
                    }
                },
                error: function (err) {
                    $('#form-company button[type="submit"]').removeAttr('disabled');
                    console.log(err);
                }
            });
        });

        $('select[name="province"]').on('change', function (e, action) {
            if(typeof action == 'undefined'){
                var idProvince = $(this).val();

                if (idProvince) {
                    $.ajax({
                        url: '<?php echo site_url('master/company/get_data_city_by_province_id'); ?>',
                        method: 'GET',
                        data: 'id=' + idProvince,
                        dataType: 'json',
                        success: function (res) {
                            if (res) {
                                var option = '<option value="">--Choose City--</option>';

                                $.each(res, function (key, value) {
                                    option += '<option value="' + value.city_id + '">' + value.city_name + '</option>';
                                });

                                $('select[name="city"]')
                                        .html(option)
                                        .removeAttr('disabled')
                                        .attr('data-validation', 'required').select2();
                                $('select[name="city"]')
                                        .parent()
                                        .removeClass('has-success');
                                $('select[name="city"]')
                                        .next()
                                        .children('.selection')
                                        .children('.select2-selection')
                                        .removeClass('valid')
                                        .removeClass('error')
                                        .css('border-color', '');
                                $('label[for="city"] span.required').text('*');


                                $('select[name="subdistrict"]')
                                        .html('<option value="">--Choose Subdistrict--</option>')
                                        .attr('disabled', 'disabled')
                                        .attr('data-validation', '').select2();
                                $('select[name="subdistrict"]')
                                        .parent()
                                        .removeClass('has-success');
                                $('select[name="subdistrict"]')
                                        .next()
                                        .children('.selection')
                                        .children('.select2-selection')
                                        .removeClass('valid')
                                        .removeClass('error')
                                        .css('border-color', '');
                                $('label[for="subdistrict"] span.required').text('*');
                            } else {
                                alert('There is an error! Please try again.');
                            }
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });
                } else {

                    $('select[name="city"]')
                            .html('<option value="">--Choose City--</option>')
                            .attr('disabled', 'disabled')
                            .attr('data-validation', '').select2();
                    $('select[name="city"]')
                            .parent()
                            .removeClass('has-success');
                    $('select[name="city"]')
                            .next()
                            .children('.selection')
                            .children('.select2-selection')
                            .removeClass('valid')
                            .removeClass('error')
                            .css('border-color', '');
                    $('label[for="city"] span.required').text('');

                    $('select[name="subdistrict"]')
                            .html('<option value="">--Choose Subdistrict--</option>')
                            .attr('disabled', 'disabled')
                            .attr('data-validation', '').select2();
                    $('select[name="subdistrict"]')
                            .parent()
                            .removeClass('has-success');
                    $('select[name="subdistrict"]')
                            .next()
                            .children('.selection')
                            .children('.select2-selection')
                            .removeClass('valid')
                            .removeClass('error')
                            .css('border-color', '');
                    $('label[for="subdistrict"] span.required').text('');
                }
            }
        });

        $('select[name="city"]').on('change', function (e) {
            var idCity = $(this).val();

            if (idCity) {
                $.ajax({
                    url: '<?php echo site_url('master/company/get_data_subdistrict_by_city_id'); ?>',
                    method: 'GET',
                    data: 'id=' + idCity,
                    dataType: 'json',
                    success: function (res) {
                        if (res) {
                            var option = '<option value="">--Choose Subdistrict--</option>';

                            $.each(res, function (key, value) {
                                option += '<option value="' + value.subdistrict_id + '">' + value.subdistrict_name + '</option>';
                            });

                            $('select[name="city"]')
                                    .next()
                                    .children('.selection')
                                    .children('.select2-selection')
                                    .removeClass('valid')
                                    .removeClass('error')
                                    .css('border-color', '');

                            $('select[name="subdistrict"]')
                                    .html(option)
                                    .removeAttr('disabled')
                                    .attr('data-validation', 'required').select2();
                            $('select[name="subdistrict"]')
                                    .parent()
                                    .removeClass('has-success');
                            $('select[name="subdistrict"]')
                                    .next()
                                    .children('.selection')
                                    .children('.select2-selection')
                                    .removeClass('valid')
                                    .removeClass('error')
                                    .css('border-color', '');
                        } else {
                            alert('There is an error! Please try again.');
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            } else {
                $('select[name="subdistrict"]')
                        .html('<option value="">--Choose Subdistrict--</option>')
                        .attr('disabled', 'disabled')
                        .attr('data-validation', '').select2();
                $('select[name="subdistrict"]')
                        .parent().
                        removeClass('has-success');
                $('select[name="subdistrict"]')
                        .next()
                        .children('.selection')
                        .children('.select2-selection')
                        .removeClass('valid')
                        .removeClass('error')
                        .css('border-color', '');
            }
        });

        $('select[name="subdistrict"]').on('change', function (e) {
            $('select[name="subdistrict"]')
                    .parent().
                    removeClass('has-success');
            $('select[name="subdistrict"]')
                    .next()
                    .children('.selection')
                    .children('.select2-selection')
                    .removeClass('valid')
                    .removeClass('error')
                    .css('border-color', '');
        });

        $('select[name="province"], select[name="city"], select[name="subdistrict"]').on('change', function (e) {
            $('select[name="city"]')
                    .removeClass('error')
                    .removeAttr('style')
                    .next()
                    .next('span.form-error')
                    .remove();
            $('select[name="city"]').parent().removeClass('has-error');

            $('select[name="subdistrict"]')
                    .removeClass('error')
                    .removeAttr('style')
                    .next()
                    .next('span.form-error')
                    .remove();
            $('select[name="subdistrict"]').parent().removeClass('has-error');
        });

        $('#open-form-phonefax').on('click', function (e) {
            $('#table-phonefax').hide();
            $('#submit-pf').attr('onclick', 'return addPhoneFax()');
            $('#submit-pf').html('<i class="fa fa-plus-circle"></i>&nbsp;Add');
            resetFormPhoneFax();
            $('#cancel-pf').show();
            $('#form-phonefax').show();
            $('input[name="fax"]').focus();
        });

        $('#open-form-cp').on('click', function (e) {
            $('#table-contactperson').hide();
            $('#submit-cp').attr('onclick', 'return addContactPerson()');
            $('#submit-cp').html('<i class="fa fa-plus-circle"></i>&nbsp;Add');
            resetFormContactPerson();
            $('#cancel-cp').show();
            $('#form-contactperson').show();
            $('input[name="cp_name"]').focus();
        });

        $('#cancel-pf').on('click', function (e) {
            $('#table-phonefax').show();
            $('#form-phonefax').hide();
        });

        $('#cancel-cp').on('click', function (e) {
            $('#table-contactperson').show();
            $('#form-contactperson').hide();
        });
<?php
}
?>

    });
<?php
if($is_superuser ||  isset($action['update'])){
?>
    //-------------- phone fax --------------------------
    function insertPhoneFaxToTable(arrDataPhoneFax) {
        $('#table-phonefax tbody').html('');
        if (arrDataPhoneFax.length != 0) {
            var tr = '';
            $.each(arrDataPhoneFax, function (key, value) {
                tr += '<tr><td>' + (key + 1) + '</td><td>' + value.fax + '</td><td>' + value.phone + '</td><td>' + value.mobile_phone + '</td><td><a href="javascript:;" onclick="return editPhoneFax(' + key + ')" class="btn btn-default btn-xs"><i class="fa fa-edit"></i> Edit</a><a href="javascript:;" onclick="return deletePhoneFax(' + key + ')" class="btn btn-default btn-xs"><i class="fa fa-trash"></i> Delete</a></td></tr>';
            });
            $('#table-phonefax tbody').html(tr);
            $('#table-phonefax').show();
            $('#form-phonefax').hide();
        } else {
            $('#table-phonefax').hide();
            resetFormPhoneFax();
            $('#submit-pf').attr('onclick', 'return addPhoneFax()');
            $('#submit-pf').html('<i class="fa fa-plus-circle"></i>&nbsp;Add');
            $('#cancel-pf').hide();
            $('#form-phonefax').show();
            $('input[name="fax"]').focus();
        }
    }

    function resetFormPhoneFax() {
        $('input[name="fax"]').val('')
                .removeClass('error')
                .removeAttr('style')
                .next('span.form-error')
                .remove();
        $('input[name="fax"]').parent().removeClass('has-error');
        $('input[name="phone"]').val('')
                .removeClass('error')
                .removeAttr('style')
                .next('span.form-error')
                .remove();
        $('input[name="phone"]').parent().removeClass('has-error');
        $('input[name="mobile_phone"]').val('')
                .removeClass('error')
                .removeAttr('style')
                .next('span.form-error')
                .remove();
        $('input[name="mobile_phone"]').parent().removeClass('has-error');
    }

    function addPhoneFax() {
        var fax = $('input[name="fax"]').val();
        var phone = $('input[name="phone"]').val();
        var mobilePhone = $('input[name="mobile_phone"]').val();

        var isError = false;
        $('#form-phonefax input').validate(function (valid, elem) {
            if (valid) {
                isError = false;
            } else {
                isError = true;
            }
        });

        if (!isError) {
            if (fax || phone || mobilePhone) {
                arrDataPhoneFax.push({"fax": fax, "phone": phone, "mobile_phone": mobilePhone});
                resetFormPhoneFax();
                insertPhoneFaxToTable(arrDataPhoneFax);
            }
        }
    }

    function deletePhoneFax(key) {
        if (confirm('Are you sure you will delete phone or fax number data ' + (key + 1) + ' ?')) {
            arrDataPhoneFax.splice(key, 1);
            insertPhoneFaxToTable(arrDataPhoneFax);
        }
    }

    function editPhoneFax(key) {
        resetFormPhoneFax();
        $('input[name="fax"]').val(arrDataPhoneFax[key].fax);
        $('input[name="phone"]').val(arrDataPhoneFax[key].phone);
        $('input[name="mobile_phone"]').val(arrDataPhoneFax[key].mobile_phone);
        $('#table-phonefax').hide();
        $('#submit-pf').attr('onclick', 'return updatePhoneFax(' + key + ')');
        $('#submit-pf').html('<i class="fa fa-save"></i>&nbsp;Save Changes');
        $('#cancel-pf').show();
        $('#form-phonefax').show();
        $('input[name="fax"]').focus();
    }

    function updatePhoneFax(key) {
        var fax = $('input[name="fax"]').val();
        var phone = $('input[name="phone"]').val();
        var mobilePhone = $('input[name="mobile_phone"]').val();

        var isError = false;
        $('#form-phonefax input').validate(function (valid, elem) {
            if (valid) {
                isError = false;
            } else {
                isError = true;
            }
        });

        if (!isError) {
            if (fax || phone || mobilePhone) {
                arrDataPhoneFax[key].fax = fax;
                arrDataPhoneFax[key].phone = phone;
                arrDataPhoneFax[key].mobile_phone = mobilePhone;
                resetFormPhoneFax();
                insertPhoneFaxToTable(arrDataPhoneFax);
            }
        }
    }
    //------------end phone fax--------------------------

    //---------contact person-----------------------
    function insertContactPersonToTable(arrDataContactPerson) {
        $('#table-contactperson tbody').html('');
        if (arrDataContactPerson.length != 0) {
            var tr = '';
            $.each(arrDataContactPerson, function (key, value) {
                tr += '<tr><td>' + (key + 1) + '</td><td>' + value.name + '</td><td>' + value.address + '</td><td>' + value.phone + '</td><td><a href="javascript:;" onclick="return editContactPerson(' + key + ')" class="btn btn-default btn-xs"><i class="fa fa-edit"></i> Edit</a><a href="javascript:;" onclick="return deleteContactPerson(' + key + ')" class="btn btn-default btn-xs"><i class="fa fa-trash"></i> Delete</a></td></tr>';
            });
            $('#table-contactperson tbody').html(tr);
            $('#table-contactperson').show();
            $('#form-contactperson').hide();
        } else {
            $('#table-contactperson').hide();
            resetFormContactPerson();
            $('#submit-cp').attr('onclick', 'return addContactPerson()');
            $('#submit-cp').html('<i class="fa fa-plus-circle"></i>&nbsp;Add');
            $('#cancel-cp').hide();
            $('#form-contactperson').show();
            $('input[name="cp_name"]').focus();
        }
    }

    function resetFormContactPerson() {
        $('input[name="cp_name"]').val('')
                .removeClass('error')
                .removeAttr('style')
                .next('span.form-error')
                .remove();
        $('input[name="cp_name"]').parent().removeClass('has-error');
        $('textarea[name="cp_address"]').val('')
                .removeClass('error')
                .removeAttr('style')
                .next('span.form-error')
                .remove();
        $('textarea[name="cp_address"]').parent().removeClass('has-error');
        $('input[name="cp_phone"]').val('')
                .removeClass('error')
                .removeAttr('style')
                .next('span.form-error')
                .remove();
        $('input[name="cp_phone"]').parent().removeClass('has-error');
    }

    function addContactPerson() {
        var cpName = $('input[name="cp_name"]').val();
        var cpAddress = $('textarea[name="cp_address"]').val();
        var cpPhone = $('input[name="cp_phone"]').val();

        var isError = false;
        $('#form-contactperson input').validate(function (valid, elem) {
            if (valid) {
                isError = false;
            } else {
                isError = true;
            }
        });

        if (!isError) {
            if (cpName || cpAddress || cpPhone) {
                arrDataContactPerson.push({"name": cpName, "address": cpAddress, "phone": cpPhone});
                resetFormContactPerson();
                insertContactPersonToTable(arrDataContactPerson);
            }
        }
    }

    function deleteContactPerson(key) {
        if (confirm('Are you sure you will delete contact person data ' + (key + 1) + ' ?')) {
            arrDataContactPerson.splice(key, 1);
            insertContactPersonToTable(arrDataContactPerson);
        }
    }

    function editContactPerson(key) {
        resetFormContactPerson();
        $('input[name="cp_name"]').val(arrDataContactPerson[key].name);
        $('textarea[name="cp_address"]').val(arrDataContactPerson[key].address);
        $('input[name="cp_phone"]').val(arrDataContactPerson[key].phone);
        $('#table-contactperson').hide();
        $('#submit-cp').attr('onclick', 'return updateContactPerson(' + key + ')');
        $('#submit-cp').html('<i class="fa fa-save"></i>&nbsp;Save Changes');
        $('#cancel-cp').show();
        $('#form-contactperson').show();
        $('input[name="cp_name"]').focus();
    }

    function updateContactPerson(key) {
        var cpName = $('input[name="cp_name"]').val();
        var cpAddress = $('textarea[name="cp_address"]').val();
        var cpPhone = $('input[name="cp_phone"]').val();

        var isError = false;
        $('#form-contactperson input').validate(function (valid, elem) {
            if (valid) {
                isError = false;
            } else {
                isError = true;
            }
        });

        if (!isError) {
            if (cpName || cpAddress || cpPhone) {
                arrDataContactPerson[key].name = cpName;
                arrDataContactPerson[key].address = cpAddress;
                arrDataContactPerson[key].phone = cpPhone;
                resetFormContactPerson();
                insertContactPersonToTable(arrDataContactPerson);
            }
        }
    }
    //-----------------end contact person-----------------
<?php
}

if ($is_superuser) {
?>
        function addCompany() {
            arrDataPhoneFax = [];
            arrDataContactPerson = [];

            insertPhoneFaxToTable(arrDataPhoneFax);
            insertContactPersonToTable(arrDataContactPerson);

            $('#form-company').trigger("reset");
            $('#modal .modal-title').text("Form Add <?php echo isset($this->menu_info->menu_title) ? $this->menu_info->menu_title : '' ?>");
            $('#preview-logo').attr('src', '<?php echo THEMES_BACKEND . '/images/no-img.jpg'; ?>');
            $("#modal-response-message").finish();
            
            $('select[name="province"]').val('');
            $('select[name="province"]').change();
            $('select[name="city"]').val('');
            $('select[name="city"]').change();

            $('ul.bar_tabs > li').removeClass();
            $('ul.bar_tabs li:first-child').addClass('active');
            $('div.tab-content > div').removeClass('in active');
            $('div.tab-content div:first-child').addClass('in active');

            $('#form-company').attr('data-url', '<?php echo site_url('master/company/act_add'); ?>');

            $('#modal').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
        }
<?php
}
?>
    function detailCompany(id) {
        getData('detail', id);
    }

<?php
if($is_superuser ||  isset($action['update'])){
?>
    function editCompany(id) {
        arrDataPhoneFax = [];
        arrDataContactPerson = [];

        insertPhoneFaxToTable(arrDataPhoneFax);
        insertContactPersonToTable(arrDataContactPerson);

        $('#form-company').trigger("reset");
        $('#modal .modal-title').text("Form Edit <?php echo isset($this->menu_info->menu_title) ? $this->menu_info->menu_title : '' ?>");

        $("#modal-response-message").finish();

        $('ul.bar_tabs > li').removeClass();
        $('ul.bar_tabs li:first-child').addClass('active');
        $('div.tab-content > div').removeClass('in active');
        $('div.tab-content div:first-child').addClass('in active');

        getData('edit', id);

    }
<?php
}
?>

    function getData(type, id) {
        $.ajax({
            url: '<?php echo site_url('master/company/get_data_by_id'); ?>',
            method: 'GET',
            data: 'id=' + id,
            dataType: 'json',
            success: function (res) {
                if (res) {
                    <?php
                    if($is_superuser ||  isset($action['update'])){
                    ?>
                    if (type === 'edit') {
//                        console.log(res);
                        $('#old-logo').val(res.company_image);
                        $('input[name="id"]').val(res.company_id);
                        $('input[name="title"]').val(res.company_title);
                        $('textarea[name="address"]').val(res.company_address);
                        $('input[name="zip_code"]').val(res.company_zip_code);

                        if (res.company_province_name) {

                            var idProvince = $('select[name="province"]').find('option:contains(' + res.company_province_name + ')').val();
                            $('select[name="province"]').val(idProvince).select2().trigger('change', 'handler');

                            getCityByProvince(idProvince, function (resCity){
                                var option = '<option value="">--Choose City--</option>';
                                var isSelectCity = '';
                                let cityId = 0;
                                $.each(resCity, function (key, value) {
                                    isSelectCity = '';
                                    if (res.company_city_name == value.city_name) {
                                        isSelectCity = 'selected="selected"';
                                        cityId = value.city_id;
                                    }
                                    option += '<option value="' + value.city_id + '" ' + isSelectCity + '>' + value.city_name + '</option>';
                                });
                                getSubdistrictByCity(cityId, function (resSubdistrict){
                                    var option = '<option value="">----Choose Subdistrict--</option>';
                                    var isSelectSubdistrict = '';
                                    $.each(resSubdistrict, function (key, value) {
                                        isSelectSubdistrict = '';
                                        if (res.company_subdistrict_name == value.subdistrict_name) {
                                            isSelectSubdistrict = 'selected="selected"';
                                        }
                                        option += '<option value="' + value.subdistrict_id + '" ' + isSelectSubdistrict + '>' + value.subdistrict_name + '</option>';
                                    });

                                    $('select[name="subdistrict"]').html(option).removeAttr('disabled').select2();
                                });
                                $('select[name="city"]').html(option).removeAttr('disabled').select2();
                            });
                        } else {
                            $('select[name="province"]').val('');
                            $('select[name="province"]').change();
                        }
                        
                        if(res.company_image != '' && res.company_image != null){
                            $('#preview-logo').attr('src', '<?php echo site_url('media/assets/images/company/250/250/') ?>' + res.company_image);
                        }else{
                            $('#preview-logo').attr('src', '<?php echo THEMES_BACKEND . '/images/no-img.jpg'; ?>');
                        }

                        var dataPhoneFax = JSON.parse(res.company_phone_fax).results;
                        if (dataPhoneFax.length !== 0) {
                            arrDataPhoneFax = dataPhoneFax;
                        }
                        insertPhoneFaxToTable(arrDataPhoneFax);

                        var dataContactPerson = JSON.parse(res.company_contact_person).results;
                        if (dataContactPerson.length !== 0) {
                            arrDataContactPerson = dataContactPerson;
                        }
                        insertContactPersonToTable(arrDataContactPerson);

                        $('#form-company').attr('data-url', '<?php echo site_url('master/company/act_update'); ?>');

                        $('#modal').modal({
                            backdrop: 'static',
                            keyboard: false
                        }, 'show');

                    }
                    <?php
                    }
                    ?>

                    if (type === 'detail') {
                        console.log('detail', res);
                        $('#detail .modal-title').text('Detail <?php echo isset($this->menu_info->menu_title) ? $this->menu_info->menu_title : '' ?> ' + res.company_title);
                        
                        if(res.company_image != '' && res.company_image != null){
                            $('#logo-detail').attr('src', '<?php echo site_url('media/assets/images/company/150/150/') ?>' + res.company_image);
                        }else{
                            $('#logo-detail').attr('src', '<?php echo THEMES_BACKEND . '/images/no-img.jpg'; ?>');
                        }
                        
                        var address = (res.company_province_name) ? ', ' + res.company_subdistrict_name + ', ' + res.company_city_name + ', ' + res.company_province_name : '';
                        var tableCompany = '';
                        tableCompany += '<table class="table table-bordered">';
                        tableCompany += '<tr>';
                        tableCompany += '<td style="width: 30%"><strong>COMPANY NAME</strong></td>';
                        tableCompany += '<td>' + res.company_title + '</td>';
                        tableCompany += '</tr>';
                        tableCompany += '<tr>';
                        tableCompany += '<td><strong>COMPANY ADDRESS</strong></td>';
                        tableCompany += '<td>' + res.company_address + address + '</td>';
                        tableCompany += '</tr>';
                        tableCompany += '</table>';
                        $('#table-detail-company').html(tableCompany);

                        var dataPhoneFax = JSON.parse(res.company_phone_fax).results;
                        if (dataPhoneFax.length !== 0) {
                            var isMain = '';
                            var clMain = '';
                            var tablePhoneFax = '';
                            $.each(dataPhoneFax, function (key, value) {
                                isMain = 'ALTERNATIVE ' + key;
                                clMain = 'bg-orange';
                                if (key === 0) {
                                    isMain = 'MAIN';
                                    clMain = 'bg-blue';
                                }
                                tablePhoneFax += '<table class="table table-bordered">';
                                tablePhoneFax += '<tr class="' + clMain + '">';
                                tablePhoneFax += '<td colspan="2" class="text-center"><strong>' + isMain + '</strong></td>';
                                tablePhoneFax += '</tr>';
                                tablePhoneFax += '<tr>';
                                tablePhoneFax += '<td style="width: 30%">Fax</td>';
                                tablePhoneFax += '<td>' + value.fax + '</td>';
                                tablePhoneFax += '</tr>';
                                tablePhoneFax += '<tr>';
                                tablePhoneFax += '<td>Phone</td>';
                                tablePhoneFax += '<td>' + value.phone + '</td>';
                                tablePhoneFax += '</tr>';
                                tablePhoneFax += '<tr>';
                                tablePhoneFax += '<td>Mobilephone</td>';
                                tablePhoneFax += '<td>' + value.mobile_phone + '</td>';
                                tablePhoneFax += '</tr>';
                                tablePhoneFax += '</table>';
                            });
                            $('#table-detail-pf > table').remove();
                            $('#table-detail-pf h5').after(tablePhoneFax);
                            $('#table-detail-pf').show();
                        } else {
                            $('#table-detail-pf > table').remove();
                            $('#table-detail-pf').hide();
                        }

                        var dataContactPerson = JSON.parse(res.company_contact_person).results;
                        if (dataContactPerson.length !== 0) {
                            var isMain = '';
                            var clMain = '';
                            var tableContactPerson = '';
                            $.each(dataContactPerson, function (key, value) {
                                isMain = 'ALTERNATIVE ' + key;
                                clMain = 'bg-orange';
                                if (key === 0) {
                                    isMain = 'MAIN';
                                    clMain = 'bg-blue';
                                }
                                tableContactPerson += '<table class="table table-bordered">';
                                tableContactPerson += '<tr class="' + clMain + '">';
                                tableContactPerson += '<td colspan="2" class="text-center"><strong>' + isMain + '</strong></td>';
                                tableContactPerson += '</tr>';
                                tableContactPerson += '<tr>';
                                tableContactPerson += '<td style="width: 30%">Name</td>';
                                tableContactPerson += '<td>' + value.name + '</td>';
                                tableContactPerson += '</tr>';
                                tableContactPerson += '<tr>';
                                tableContactPerson += '<td>Phone</td>';
                                tableContactPerson += '<td>' + value.phone + '</td>';
                                tableContactPerson += '</tr>';
                                tableContactPerson += '<tr>';
                                tableContactPerson += '<td>Address</td>';
                                tableContactPerson += '<td>' + value.address + '</td>';
                                tableContactPerson += '</tr>';
                                tableContactPerson += '</table>';
                            });
                            $('#table-detail-cp > table').remove();
                            $('#table-detail-cp h5').after(tableContactPerson);
                            $('#table-detail-cp').show();
                        } else {
                            $('#table-detail-cp > table').remove();
                            $('#table-detail-cp').hide();
                        }
                        
                        $('#detail').modal('show');

                    }
                } else {
                    alert('Data not found! Please try again.');
                    $('#gridview').flexReload();
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    }
<?php
if($is_superuser ||  isset($action['update'])){
?>
    function getCityByProvince(idProvince, callback) {
        $.ajax({
            url: '<?php echo site_url('master/company/get_data_city_by_province_id'); ?>',
            method: 'GET',
            data: 'id=' + idProvince,
            dataType: 'json',
            success: function (res) {
                if (res) {
                    callback(res);
                } else {
                    alert('There is an error! Please try again.');
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    }

    function getSubdistrictByCity(cityId, callback) {
        $.ajax({
            url: '<?php echo site_url('master/company/get_data_subdistrict_by_city_id'); ?>',
            method: 'GET',
            data: 'id=' + cityId,
            dataType: 'json',
            success: function (res) {
                if (res) {
                    callback(res);
                } else {
                    alert('There is an error! Please try again.');
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
    function readURL(input, element) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(element).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<!--form validator-->
<script src="<?php echo THEMES_BACKEND; ?>/vendor/js/form-validator/jquery.form-validator.min.js"></script>
<script>
    $.validate({
        modules: 'logic, file, security',
        // lang: 'id'
    });
</script>