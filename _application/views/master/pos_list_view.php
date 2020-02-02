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
            <form id="form-pos" class="form-horizontal form-label-left" data-url="">
                <div class="modal-body" style="overflow-y: auto; height: calc(100vh - 255px);">
                    <div id="modal-response-message" class="alert alert-danger alert-dismissible fade in" role="alert" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <div></div>
                    </div>

                    <input type="hidden" name="id">

                    <ul class="nav nav-tabs bar_tabs" role="tablist">
                        <li class="active"><a data-toggle="tab" href="#pos">Store</a></li>
                        <li><a data-toggle="tab" href="#pf">Phone or Fax</a></li>
                        <li><a data-toggle="tab" href="#cp">Contact Person</a></li>
                        <li><a data-toggle="tab" href="#config">Config POS</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="pos" class="tab-pane fade in active">
                            <div class="row">
                                
                                <?php if($is_superuser): ?>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="company">Company Name <span class="required">*</span>
                                            </label>
                                            <select name="company" data-validation="required" class="form-control select2">
                                                <option value="">--Choose Company--</option>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Store Name <span class="required">*</span>
                                        </label>
                                        <input type="text" name="name" class="form-control" data-validation="required length" data-validation-length="max50">
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label" for="address">Store Address <span class="required">*</span>
                                        </label>
                                        <textarea name="address" class="form-control" data-validation="required length" data-validation-length="max50"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label" for="province">Province
                                        </label>
                                        <select name="province" data-validation="" class="form-control select2">
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
                                        <select name="subdistrict" data-validation="" class="form-control select2">
                                            <option value="">--Choose Subdistrict--</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label" for="city">City <span class="required"></span>
                                        </label>
                                        <select name="city" data-validation="" class="form-control select2">
                                            <option value="">--Choose City--</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label" for="zip_code">Postal Code
                                        </label>
                                        <input type="text" name="zip_code" class="form-control" data-validation="number" data-validation-optional="true" >
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
                                    <h5><i class="fa fa-plus-circle"></i> Form Add Phone or Fax</h5>
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
                                    <h5><i class="fa fa-plus-circle"></i> Form Add Contact Person</h5>
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
                                            <label>Phone
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
                        <div id="config" class="tab-pane fade">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label">POS Type
                                                </label>
                                                <select id="config-pos-type" name="pos_type" class="form-control">
                                                    <option value="resto">Restaurant</option>
                                                    <option value="retail">Retail</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label">Paper Size
                                                </label>
                                                <select id="config-paper-size" name="paper_size" class="form-control">
                                                    <option value="mini">Mini</option>
                                                    <option value="large">Large</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label" for="logo">Logo Struck</label>
                                                <input type="file" name="logo" id="btn-upload-logo" data-validation="mime size" data-validation-max-size="250kb" class="form-control" data-validation-allowing="jpg, jpeg, png, gif" accept=".gif, .jpg, .jpeg, .png">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group" style="margin: 5px auto; width: 50%;">
                                                <img id="preview-logo" src="" border="0" alt="logo" style="max-width: 200px; max-height: 200px; margin: auto; display: block">
                                                <br>
                                                <input type="hidden" name="old_logo" id="old-logo" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">Header Title Text 1
                                        </label>
                                        <input id="config-title-text-1" type="text" name="config_title_text_1" class="form-control input-sm">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">Header Title Text 2
                                        </label>
                                        <input id="config-title-text-2" type="text" name="config_title_text_2" class="form-control input-sm">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">Header Title Text 3
                                        </label>
                                        <input id="config-title-text-3" type="text" name="config_title_text_3" class="form-control input-sm">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">Footer Text
                                        </label>
                                        <input id="config-footer-text-1" type="text" name="config_footer_text_1" class="form-control input-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <button type="button" class="btn btn-default pull-right btn-round"><i class="fa fa-file-text-o"></i>&nbsp;Preview Struck</button>
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

                <div id="table-detail-company"></div>

                <div id="table-detail-pos"></div>

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

<?php
if($is_superuser){
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
    "use strict";
    
    class Pos {
        constructor() {
            // Globals Variable
            this.arrPrivilege = <?php echo $json_encode_action . ';'; ?>
            this.permissionDisplay();
            
            this.arrDataPhoneFax = [];
            this.arrDataContactPerson = [];
            this.cityIdGlobals = 0;
        }
        
        //-------------- phone fax --------------------------
        insertPhoneFaxToTable() {
            $('#table-phonefax tbody').html('');
            if (pos.arrDataPhoneFax.length != 0) {
                let tr = '';
                $.each(pos.arrDataPhoneFax, function (key, value) {
                    tr += `<tr>
                                <td>${key + 1}</td>
                                <td>${value.fax}</td>
                                <td>${value.phone}</td>
                                <td>${value.mobile_phone}</td>
                                <td>
                                    <a href="javascript:;" onclick="pos.editPhoneFax(${key})" class="btn btn-default btn-xs"><i class="fa fa-edit"></i> Edit</a>
                                    <a href="javascript:;" onclick="pos.deletePhoneFax(${key})" class="btn btn-default btn-xs"><i class="fa fa-trash"></i> Delete</a>
                                </td>
                            </tr>`;
                });
                $('#table-phonefax tbody').html(tr);
                $('#table-phonefax').show();
                $('#form-phonefax').hide();
            } else {
                $('#table-phonefax').hide();
                pos.resetFormPhoneFax();
                $('#submit-pf').attr('onclick', 'pos.addPhoneFax()');
                $('#submit-pf').html('<i class="fa fa-plus-circle"></i>&nbsp;Add');
                $('#cancel-pf').hide();
                $('#form-phonefax').show();
                $('input[name="fax"]').focus();
            }
        }
        
        resetFormPhoneFax() {
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

        addPhoneFax() {
            let fax = $('input[name="fax"]').val();
            let phone = $('input[name="phone"]').val();
            let mobilePhone = $('input[name="mobile_phone"]').val();

            let isError = false;
            $('#form-phonefax input').validate(function (valid, elem) {
                if (!valid) {
                    isError = true;
                }
            });

            if (!isError) {
                if (fax || phone || mobilePhone) {
                    pos.arrDataPhoneFax.push({"fax": fax, "phone": phone, "mobile_phone": mobilePhone});
                    pos.resetFormPhoneFax();
                    pos.insertPhoneFaxToTable();
                }
            }
        }

        deletePhoneFax(key) {
            if (confirm(`Are you sure you will delete phone or fax number data${key + 1} ?`)) {
                pos.arrDataPhoneFax.splice(key, 1);
                pos.insertPhoneFaxToTable();
            }
        }

        editPhoneFax(key) {
            pos.resetFormPhoneFax();
            $('input[name="fax"]').val(pos.arrDataPhoneFax[key].fax);
            $('input[name="phone"]').val(pos.arrDataPhoneFax[key].phone);
            $('input[name="mobile_phone"]').val(pos.arrDataPhoneFax[key].mobile_phone);
            $('#table-phonefax').hide();
            $('#submit-pf').attr('onclick', `pos.updatePhoneFax(${key})`);
            $('#submit-pf').html('<i class="fa fa-save"></i>&nbsp;Save Changes');
            $('#cancel-pf').show();
            $('#form-phonefax').show();
            $('input[name="fax"]').focus();
        }

        updatePhoneFax(key) {
            let fax = $('input[name="fax"]').val();
            let phone = $('input[name="phone"]').val();
            let mobilePhone = $('input[name="mobile_phone"]').val();

            let isError = false;
            $('#form-phonefax input').validate(function (valid, elem) {
                if (!valid) {
                    isError = true;
                }
            });

            if (!isError) {
                if (fax || phone || mobilePhone) {
                    pos.arrDataPhoneFax[key].fax = fax;
                    pos.arrDataPhoneFax[key].phone = phone;
                    pos.arrDataPhoneFax[key].mobile_phone = mobilePhone;
                    pos.resetFormPhoneFax();
                    pos.insertPhoneFaxToTable();
                }
            }
        }
        //------------end phone fax--------------------------
        
        //---------contact person-----------------------
        insertContactPersonToTable() {
            $('#table-contactperson tbody').html('');
            if (pos.arrDataContactPerson.length != 0) {
                let tr = '';
                $.each(pos.arrDataContactPerson, function (key, value) {
                    tr += `<tr>
                                <td>${key + 1}</td>
                                <td>${value.name}</td>
                                <td>${value.address}</td>
                                <td>${value.phone}</td>
                                <td>
                                    <a href="javascript:;" onclick="pos.editContactPerson(${key})" class="btn btn-default btn-xs"><i class="fa fa-edit"></i> Edit</a>
                                    <a href="javascript:;" onclick="pos.deleteContactPerson(${key})" class="btn btn-default btn-xs"><i class="fa fa-trash"></i> Delete</a>
                                </td>
                            </tr>`;
                });
                $('#table-contactperson tbody').html(tr);
                $('#table-contactperson').show();
                $('#form-contactperson').hide();
            } else {
                $('#table-contactperson').hide();
                pos.resetFormContactPerson();
                $('#submit-cp').attr('onclick', 'pos.addContactPerson()');
                $('#submit-cp').html('<i class="fa fa-plus-circle"></i>&nbsp;Add');
                $('#cancel-cp').hide();
                $('#form-contactperson').show();
                $('input[name="cp_name"]').focus();
            }
        }

        resetFormContactPerson() {
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

        addContactPerson() {
            let cpName = $('input[name="cp_name"]').val();
            let cpAddress = $('textarea[name="cp_address"]').val();
            let cpPhone = $('input[name="cp_phone"]').val();

            let isError = false;
            $('#form-contactperson input').validate(function (valid, elem) {
                if (!valid) {
                    isError = true;
                }
            });

            if (!isError) {
                if (cpName || cpAddress || cpPhone) {
                    pos.arrDataContactPerson.push({"name": cpName, "address": cpAddress, "phone": cpPhone});
                    pos.resetFormContactPerson();
                    pos.insertContactPersonToTable();
                }
            }
        }

        deleteContactPerson(key) {
            if (confirm(`Are you sure you will delete contact person data${key + 1} ?`)) {
                pos.arrDataContactPerson.splice(key, 1);
                pos.insertContactPersonToTable();
            }
        }

        editContactPerson(key) {
            pos.resetFormContactPerson();
            $('input[name="cp_name"]').val(pos.arrDataContactPerson[key].name);
            $('textarea[name="cp_address"]').val(pos.arrDataContactPerson[key].address);
            $('input[name="cp_phone"]').val(pos.arrDataContactPerson[key].phone);
            $('#table-contactperson').hide();
            $('#submit-cp').attr('onclick', `pos.updateContactPerson(${key})`);
            $('#submit-cp').html('<i class="fa fa-save"></i>&nbsp;Save Changes');
            $('#cancel-cp').show();
            $('#form-contactperson').show();
            $('input[name="cp_name"]').focus();
        }

        updateContactPerson(key) {
            let cpName = $('input[name="cp_name"]').val();
            let cpAddress = $('textarea[name="cp_address"]').val();
            let cpPhone = $('input[name="cp_phone"]').val();

            let isError = false;
            $('#form-contactperson input').validate(function (valid, elem) {
                if (!valid) {
                    isError = true;
                }
            });

            if (!isError) {
                if (cpName || cpAddress || cpPhone) {
                    pos.arrDataContactPerson[key].name = cpName;
                    pos.arrDataContactPerson[key].address = cpAddress;
                    pos.arrDataContactPerson[key].phone = cpPhone;
                    pos.resetFormContactPerson();
                    pos.insertContactPersonToTable();
                }
            }
        }
        //-----------------end contact person-----------------
        
        getCityByProvince(idProvince, callback) {
            $.ajax({
                url: '<?php echo site_url('master/pos/get_data_city_by_province_id'); ?>',
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

        getSubdistrictByCity(callback) {
            $.ajax({
                url: '<?php echo site_url('master/pos/get_data_subdistrict_by_city_id'); ?>',
                method: 'GET',
                data: 'id=' + pos.cityIdGlobals,
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
        
        // for get data with ajax
        getDataAjax(url, method = 'GET', data = '', callback){
            $.ajax({
                url: url,
                method: method,
                data: data,
                dataType: 'json',
                success: function (res) {
                    callback(res);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    let res = {
                        status: 400,
                        msg: textStatus
                    }
                    callback(res);
                }
            });
        }
        
        openDetail(id) {
            pos.getData('detail', id);
        }
        
        getData(type, id) {
            $.ajax({
                url: '<?php echo site_url('master/pos/get_data_by_id'); ?>',
                method: 'GET',
                data: 'id=' + id,
                dataType: 'json',
                success: function (res) {
                    if (res) {
                <?php if($is_superuser || isset($action['update'])): ?>
                        if (type === 'edit') {
                            console.log(res);
                            $('input[name="id"]').val(res.pos_id);
                            <?php if($is_superuser): ?>
                                $('select[name="company"]').parent()
                                        .removeClass('has-success')
                                        .removeClass('has-error');
                                $('select[name="company"]').next()
                                        .children('.selection')
                                        .children('.select2-selection')
                                        .removeClass('valid')
                                        .removeClass('error')
                                        .css('border-color', '');
                                $('select[name="company"]').removeClass('error')
                                        .removeAttr('style')
                                        .next()
                                        .next('span.form-error')
                                        .remove();
                                $('select[name="company"]').val(res.pos_company_id).select2();
                            <?php endif; ?>
                            $('input[name="code"]').val(res.pos_code);
                            $('input[name="name"]').val(res.pos_name);
                            $('input[name="zip_code"]').val(res.pos_zip_code);
                            $('textarea[name="address"]').val(res.pos_address);

                            if (res.pos_province_name) {

                                var idProvince = $('select[name="province"]').find('option:contains(' + res.pos_province_name + ')').val();
                                $('select[name="province"]').val(idProvince).select2().trigger('change', 'handler');
                                
                                pos.getCityByProvince(idProvince, function(res2) {
                                    var option = '<option value="">--Choose City--</option>';
                                    var isSelectCity = '';
                                    pos.cityIdGlobals = 0;
                                    $.each(res2, function (key, value) {
                                        isSelectCity = '';
                                        if (res.pos_city_name == value.city_name) {
                                            isSelectCity = 'selected="selected"';
                                            pos.cityIdGlobals = value.city_id;
                                        }
                                        option += '<option value="' + value.city_id + '" ' + isSelectCity + '>' + value.city_name + '</option>';
                                    });
                                    $('select[name="city"]').html(option).removeAttr('disabled').select2();
                                    
                                    pos.getSubdistrictByCity(function(res3) {
                                        var option = '<option value="">--Choose Subdistrict--</option>';
                                        var isSelectSubdistrict = '';
                                        $.each(res3, function (key, value) {
                                            isSelectSubdistrict = '';
                                            if (res.pos_subdistrict_name == value.subdistrict_name) {
                                                isSelectSubdistrict = 'selected="selected"';
                                            }
                                            option += '<option value="' + value.subdistrict_id + '" ' + isSelectSubdistrict + '>' + value.subdistrict_name + '</option>';
                                        });

                                        $('select[name="subdistrict"]').html(option).removeAttr('disabled').select2();
                                    });
                                });
                            } else {
                                $('select[name="province"]').val('');
                                $('select[name="province"]').change();
                            }

                            var dataPhoneFax = JSON.parse(res.pos_phone_fax).results;
                            if (dataPhoneFax.length !== 0) {
                                pos.arrDataPhoneFax = dataPhoneFax;
                            }
                            pos.insertPhoneFaxToTable();

                            var dataContactPerson = JSON.parse(res.pos_contact_person).results;
                            if (dataContactPerson.length !== 0) {
                                pos.arrDataContactPerson = dataContactPerson;
                            }
                            pos.insertContactPersonToTable();
                            
                            if(res.pos_pos_config_json != "" && res.pos_pos_config_json != null){
                                let configPOS = JSON.parse(res.pos_pos_config_json);

                                $('#config-pos-type').val(configPOS.pos_type);
                                $('#config-paper-size').val(configPOS.struck_template.paper_size);

                                if(configPOS.struck_template.header.logo_filename != '' && configPOS.struck_template.header.logo_filename != null){
                                    $('#preview-logo').attr('src', '<?php echo site_url('media/assets/images/logo_struck/250/250/') ?>' + configPOS.struck_template.header.logo_filename);
                                    $('#old-logo').val(configPOS.struck_template.header.logo_filename);
                                }else{
                                    $('#preview-logo').attr('src', '<?php echo THEMES_BACKEND . '/images/no-img.jpg'; ?>');
                                    $('#old-logo').val('');
                                }

                                $('#config-title-text-1').val(configPOS.struck_template.header.title_text_1);
                                $('#config-title-text-2').val(configPOS.struck_template.header.title_text_2);
                                $('#config-title-text-3').val(configPOS.struck_template.header.title_text_3);

                                $('#config-footer-text-1').val(configPOS.struck_template.footer.text_1);
                            }

                            $('#form-pos').attr('data-url', '<?php echo site_url('master/pos/act_update'); ?>');

                            $('#modal').modal({
                                backdrop: 'static',
                                keyboard: false
                            }, 'show');

                        }
                <?php endif; ?>

                        if (type === 'detail') {
                            console.log('detail', res);
                            $('#detail .modal-title').text('Detail <?php echo isset($this->menu_info->menu_title) ? $this->menu_info->menu_title : '' ?> ' + res.pos_name);

                            if (res.company_id) {
                                var addressCompany = (res.company_province_name) ? ', ' + res.company_subdistrict_name + ', ' + res.company_city_name + ', ' + res.company_province_name : '';
                                var tableCompany = '';
                                tableCompany += '<table class="table table-bordered">';
                                tableCompany += '<tr>';
                                tableCompany += '<td style="width: 30%"><strong>COMPANY NAME</strong></td>';
                                tableCompany += '<td>' + res.company_title + '</td>';
                                tableCompany += '</tr>';
                                tableCompany += '<tr>';
                                tableCompany += '<td><strong>COMPANY ADDRESS</strong></td>';
                                tableCompany += '<td>' + res.company_address + addressCompany + '</td>';
                                tableCompany += '</tr>';
                                tableCompany += '</table>';
                                $('#table-detail-company').html(tableCompany);
                                $('#table-detail-company').show();
                            } else {
                                $('#table-detail-company').html('');
                                $('#table-detail-company').hide();
                            }

                            $('#table-detail-pos').html('');
                            var addresspos = (res.pos_province_name) ? ', ' + res.pos_subdistrict_name + ', ' + res.pos_city_name + ', ' + res.pos_province_name : '';
                            var tablepos = '';
                            tablepos += '<table class="table table-bordered">';
                            tablepos += '<tr>';
                            tablepos += '<td style="width: 30%"><strong>STORE NAME</strong></td>';
                            tablepos += '<td>' + res.pos_name + '</td>';
                            tablepos += '</tr>';
                            tablepos += '<tr>';
                            tablepos += '<td><strong>STORE ADDRESS</strong></td>';
                            tablepos += '<td>' + res.pos_address + addresspos + '</td>';
                            tablepos += '</tr>';
                            tablepos += '</table>';
                            $('#table-detail-pos').html(tablepos);


                            var dataPhoneFax = JSON.parse(res.pos_phone_fax).results;
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

                            var dataContactPerson = JSON.parse(res.pos_contact_person).results;
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
                        alert('Data Not Found !');
                        $('#gridview').flexReload();
                        <?php if($is_superuser): ?>
                            getDataCompany('init');
                        <?php endif; ?>
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
        
        // cek permission to display
        permissionDisplay() {
            let buttons = $("button");
            $.each(buttons, function (index, element){
                let key = $(element).data("action");
                for (var property in self.arrPrivilege) {
                    if (property == key) {
                        $(element).show();
                    }
                }
            });
        }
        
        // validate action
        validate() {
            <?php if(!$is_superuser): ?>
                if (typeof this.arrPrivilege[this.nameAction] === 'undefined') {
                    console.log("you don't have permisson on that function");
                    alert("you don't have permisson on that function");
                    return false;
                }
            <?php endif; ?>
        }
    }
    
    class ActionAdd extends Pos {
        constructor() {
            super();
            // Globals Variable
            this.nameAction = "add";
        }
        
        initReady(){
            
        }
        
        openModalAdd(){
            pos.arrDataPhoneFax = [];
            pos.arrDataContactPerson = [];

            pos.insertPhoneFaxToTable();
            pos.insertContactPersonToTable();

            $('#form-pos').trigger("reset");
            $('#form-pos').attr('data-url', '<?php echo site_url('master/pos/act_add'); ?>');
            $('#modal .modal-title').text("Form Add <?php echo isset($this->menu_info->menu_title) ? $this->menu_info->menu_title : '' ?>");
            $('#preview-logo').attr('src', '<?php echo THEMES_BACKEND . '/images/no-img.jpg'; ?>');

            $("#modal-response-message").finish();

            <?php if($is_superuser): ?>
                $('select[name="company"]').val('').change().select2();
            <?php endif; ?>
            $('select[name="province"]').val('').change();
            $('select[name="city"]').val('').change();
            <?php if($is_superuser): ?>
                pos.getDataAjax('<?php echo site_url('common/get_data_company'); ?>', 'GET', '', function(res) {
                    if(res.status == 200){
                        if(res.data.length > 0){
                            $('#modal').modal({
                                backdrop: 'static',
                                keyboard: false
                            }, 'show');
                        }else{
                            $('#modal-company').modal({
                                backdrop: 'static',
                                keyboard: false
                            }, 'show');
                        }
                    }
                    if(res.status == 400){
                        alert(res.msg);
                    }
                });
            <?php else: ?>
                $('#modal').modal({
                    backdrop: 'static',
                    keyboard: false
                }, 'show');
            <?php endif; ?>
        }
    }
    
    class ActionUpdate extends Pos {
        constructor() {
            super();
            // Globals Variable
            this.nameAction = "update";
        }
        
        openEdit(id) {
            pos.arrDataPhoneFax = [];
            pos.arrDataContactPerson = [];

            pos.insertPhoneFaxToTable();
            pos.insertContactPersonToTable();

            $('#form-pos').trigger("reset");
            $('#modal .modal-title').text("Form Edit <?php echo isset($this->menu_info->menu_title) ? $this->menu_info->menu_title : '' ?>");
            $('#preview-logo').attr('src', '<?php echo THEMES_BACKEND . '/images/no-img.jpg'; ?>');

            $("#modal-response-message").finish();

            pos.getData('edit', id);

        }
    }
    
    let pos = new Pos();
    let actionAdd = new ActionAdd();
    let actionUpdate = new ActionUpdate();

    $(document).ready(function () {
    
        $('#btn-upload-logo').on('change', function (){
           readURL(this, '#preview-logo');
       });
       
        // on change tab
        $('.nav-tabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href"); // activated tab
            if (target === '#pos') {
                $(target).find('select[name="company"]').select2();
            } else{
                $(target).find('input')[0].focus();
            }
        });

        // on modal shown
        $('#modal').on('shown.bs.modal', function () {
            $('.nav-tabs a[href="#pos"]').tab('show');
            $(this).find('select[name="company"]').select2();
        });

        // init select2
        $('.select2').select2({
            dropdownParent: $("#modal")
        });

        // on submit
        $('#form-pos').on('submit', function (e) {
            $('#form-pos button[type="submit"]').attr('disabled', 'disabled');
            e.preventDefault();

            var urlForm = $('#form-pos').attr('data-url');

            var formData = new FormData(this);

            formData.append('phone_fax', JSON.stringify(pos.arrDataPhoneFax));
            formData.append('contact_person', JSON.stringify(pos.arrDataContactPerson));

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
                        $('#form-pos button[type="submit"]').removeAttr('disabled');
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
                        $('#form-pos button[type="submit"]').removeAttr('disabled');
                        $("#modal-response-message").finish();

                        $("#modal-response-message").slideDown("fast");
                        $('#modal-response-message div').html(data['msg']);
                        $("#modal-response-message").delay(10000).slideUp(1000);
                    }
                },
                error: function (err) {
                    $('#form-pos button[type="submit"]').removeAttr('disabled');
                    console.log(err);
                }
            });
        });

        $('select[name="province"]').on('change', function (e, action) {
            if(typeof action == 'undefined'){
                var idProvince = $(this).val();

                if (idProvince) {
                    $.ajax({
                        url: '<?php echo site_url('master/pos/get_data_city_by_province_id'); ?>',
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
                                alert('Terjadi Kesalahan!');
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

        $('select[name="city"]').on('change', function () {
            var idCity = $(this).val();

            if (idCity) {
                $.ajax({
                    url: '<?php echo site_url('master/pos/get_data_subdistrict_by_city_id'); ?>',
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

        $('select[name="subdistrict"]').on('change', function () {
            $(this).parent().
                    removeClass('has-success');
            $(this).next()
                    .children('.selection')
                    .children('.select2-selection')
                    .removeClass('valid')
                    .removeClass('error')
                    .css('border-color', '');
        });


        $('select[name="province"], select[name="city"], select[name="subdistrict"]').on('change', function () {
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
            $('#submit-pf').attr('onclick', 'pos.addPhoneFax()');
            $('#submit-pf').html('<i class="fa fa-plus-circle"></i>&nbsp;Add');
            pos.resetFormPhoneFax();
            $('#cancel-pf').show();
            $('#form-phonefax').show();
            $('input[name="fax"]').focus();
        });

        $('#open-form-cp').on('click', function () {
            $('#table-contactperson').hide();
            $('#submit-cp').attr('onclick', 'pos.addContactPerson()');
            $('#submit-cp').html('<i class="fa fa-plus-circle"></i>&nbsp;Add');
            pos.resetFormContactPerson();
            $('#cancel-cp').show();
            $('#form-contactperson').show();
            $('input[name="cp_name"]').focus();
        });

        $('#cancel-pf').on('click', function () {
            $('#table-phonefax').show();
            $('#form-phonefax').hide();
        });

        $('#cancel-cp').on('click', function () {
            $('#table-contactperson').show();
            $('#form-contactperson').hide();
        });

        <?php if($is_superuser): ?>
            $('select[name="company"]').on('change', function () {
                $(this).parent()
                        .removeClass('has-success')
                        .removeClass('has-error');
                $(this).next()
                        .children('.selection')
                        .children('.select2-selection')
                        .removeClass('valid')
                        .removeClass('error')
                        .css('border-color', '');
                $(this).removeClass('error')
                        .removeAttr('style')
                        .next()
                        .next('span.form-error')
                        .remove();
            });

            pos.getDataAjax('<?php echo site_url('common/get_data_company'); ?>', 'GET', '', function(res) {
                if(res.status == 200){
                    let option = '<option value="">--Choose Company--</option>';
                    $.each(res.data, function (key, value) {
                        option += '<option value="' + value.company_id + '">' + value.company_title + '</option>';
                    });

                    $('select[name="company"]').html(option).select2();
                }
                if(res.status == 400){
                    alert(res.msg);
                }
            });
        <?php endif; ?>
    });
    
    $("#gridview").flexigrid({
        url: '<?php echo site_url("master/pos/get_data"); ?>',
        dataType: 'json',
        colModel: [
            <?php if($is_superuser || isset($action['update'])):
                echo "{display: 'Edit', name: 'edit', width: 40, sortable: false, align: 'center', datasource: false},";
            endif;
                echo "{display: 'Detail', name: 'detail', width: 40, sortable: false, align: 'center', datasource: false},";
            if($is_superuser):
                echo "{display: 'Company Name', name: 'company_title', width: 200, sortable: true, align: 'left'},";
            endif;
            echo "
                {display: 'Store Code', name: 'pos_code', width: 100, sortable: true, align: 'left'},
                {display: 'Store Name', name: 'pos_name', width: 200, sortable: true, align: 'left'},
                {display: 'Address', name: 'pos_address', width: 200, sortable: true, align: 'left'},
                {display: 'Province', name: 'pos_province_name', width: 150, sortable: true, align: 'left'},
                {display: 'City', name: 'pos_city_name', width: 150, sortable: true, align: 'left'},
                {display: 'Subdistrict', name: 'pos_subdistrict_name', width: 150, sortable: true, align: 'left',hide: true},
                {display: 'Postal Code', name: 'pos_zip_code', width: 80, sortable: true, align: 'left',hide: true},
                {display: 'Phone / Fax', name: 'phone_fax', width: 180, sortable: false, align: 'left',hide: true},
                {display: 'Contact Person', name: 'contact_person', width: 180, sortable: false, align: 'left',hide: true},
                ";
            ?>
        ],
        buttons: [
            <?php if($is_superuser):
                echo "
                    {display: 'Add', name: 'add', bclass: 'add', onpress: actionAdd.openModalAdd},
                    {separator: true},
                    ";
            endif; 
            echo "
                {display: 'Select All', name: 'selectall', bclass: 'selectall', onpress: check},
                {separator: true},
                {display: 'Unselect All', name: 'selectnone', bclass: 'selectnone', onpress: check},
                ";
            if($is_superuser):
                echo "
                    {separator: true},
                    {display: 'Delete', name: 'delete', bclass: 'delete', onpress: act_show, urlaction: '" . site_url('master/pos/act_delete') . "'},
                    ";
            endif; ?>
        ],
        buttons_right: [
            <?php if($is_superuser || isset($action['export'])):
                echo "{display: 'Export Excel', name: 'excel', bclass: 'excel', onpress: export_data, urlaction: '" . site_url("master/pos/export_data_warehouse") . "'}";
            endif; ?>
        ],
        <?php
            if($user_group !== 'administrator_pos'): ?>
                searchitems: [
                    <?php if($is_superuser):
                        echo "{display: 'Company Name', name: 'company_title', type: 'text'},";
                    endif;
                    echo "
                        {display: 'Store Code', name: 'pos_code', type: 'text'},
                        {display: 'Store Name', name: 'pos_name', type: 'text'},
                        {display: 'Address', name: 'pos_address', type: 'text'},
                        {display: 'Province', name: 'pos_province_name', type: 'text'},
                        {display: 'City', name: 'pos_city_name', type: 'text'},
                        {display: 'Subdistrict', name: 'pos_subdistrict_name', type: 'text'},
                        {display: 'Postal Code', name: 'pos_zip_code', type: 'text'},
                        "; ?>
                ],
        <?php endif;?>
        sortname: "pos_id",
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

    <?php if($is_superuser || isset($action['update'])): ?>
        
    <?php endif; ?>
        
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