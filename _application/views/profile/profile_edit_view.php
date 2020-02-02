<div class="page-title">
    <div class="title_left">
        <h3>Change My Profile</h3>
    </div>
</div>
<div class="clearfix"></div>
<?php
if ($query->num_rows() > 0) {
    $row = $query->row();
    ?>
<div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="margin-bottom: 70px">
                <div class="x_title">
                    <h2>Form Change Profile</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?php echo form_open_multipart($form_action, array('class' => 'form-horizontal form-label-left')); ?>

                    <?php echo form_hidden('uri_string', uri_string()); ?>
                    <?php echo form_hidden('old_image', $row->administrator_image); ?>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Username <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo form_input('username', (isset($_SESSION['input_username'])) ? $_SESSION['input_username'] : $row->administrator_username, 'data-validation="required alphanumeric length" data-validation-length="6-15" data-validation-allowing="-_" data-validation-help="use a-z, 0-9, minus sign and underscore" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo form_input('name', (isset($_SESSION['input_name'])) ? $_SESSION['input_name'] : $row->administrator_name, 'data-validation="required" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobilephone">Mobile Phone <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo form_input('mobilephone', (isset($_SESSION['input_mobilephone'])) ? $_SESSION['input_mobilephone'] : $row->administrator_mobilephone, 'data-validation="required number length" data-validation-length="10-13" data-validation-help="example : 081234567890" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo form_input('email', (isset($_SESSION['input_email'])) ? $_SESSION['input_email'] : $row->administrator_email, 'data-validation="email" data-validation-optional="true" class="form-control col-md-7 col-xs-12"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image">Profile Picture
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <img id="preview-image" src="" border="0" alt="image" style="max-width: 250px; max-height: 250px; margin: auto; display: block">
                            <br>
                            <?php echo form_upload('image', '', 'id="btn-upload" data-validation="mime size" data-validation-max-size="1M" class="form-control col-md-7 col-xs-12" data-validation-allowing="jpg, jpeg, png, gif" accept=".gif, .jpg, .jpeg, .png"'); ?>
                        </div>
                    </div>

                    <div class="ln_solid"></div>

                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; Save Profile</button>
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $("#btn-upload").change(function () {
                readURL(this);
            });

            var administrator_image = '<?php echo $row->administrator_image; ?>';

            if (administrator_image !== '') {
                $('#preview-image').attr('src', '<?php echo site_url('media/' . _dir_administrator . '250/250/') ?>' + administrator_image);
            } else {
                $('#preview-image').attr('src', '<?php echo THEMES_BACKEND . '/images/no-img.jpg'; ?>');
            }
        });

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#preview-image').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <!--form validator-->
    <script src="<?php echo THEMES_BACKEND; ?>/vendor/js/form-validator/jquery.form-validator.min.js"></script>

    <script>
            $.validate({
                modules: 'file',
                // lang: 'id'
            });
    </script>
    <?php
} else {
    echo '<div class="error alert alert-danger"><p>Sorry, data is not available.</p></div>';
}
?>