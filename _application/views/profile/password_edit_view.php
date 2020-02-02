<div class="page-title">
    <div class="title_left">
        <h3>Change My Password</h3>
    </div>
</div>
<div class="clearfix"></div>
<?php
if ($query->num_rows() > 0) {
    ?>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Form Change Password</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?php echo form_open($form_action, array('class' => 'form-horizontal form-label-left')); ?>
                    <?php echo form_hidden('uri_string', uri_string()); ?>

                    <?php
                    if ($this->session->userdata('administrator_group_type') != 'superuser') {
                        ?>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="old_password">Old Password <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo form_password('old_password', '', 'data-validation="required length" data-validation-length="6-12" class="form-control col-md-7 col-xs-12"'); ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">New Password <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo form_password('password', '', 'data-validation="required length" data-validation-length="6-12" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password_conf">Repeat New Password <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo form_password('password_conf', '', 'data-validation="required confirmation" data-validation-confirm="password" data-validation-error-msg="must be match with new password column" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                    <div class="ln_solid"></div>

                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; Save Password</button>
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>

    <!--form validator-->
    <script src="<?php echo THEMES_BACKEND; ?>/vendor/js/form-validator/jquery.form-validator.min.js"></script>

    <script>
        $.validate({
            modules: 'security',
            // lang: 'id'
        });
    </script>
    <?php
} else {
    echo '<div class="error alert alert-danger"><p>Sorry, data is not available.</p></div>';
}