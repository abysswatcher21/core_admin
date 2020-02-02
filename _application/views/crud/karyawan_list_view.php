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


<!-- Modal -->
<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <span class="modal-title" id="exampleModalLabel"><h4>Karyawan</h4></span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form id="form" action="" method="post">
               
                <div class="form-group">
                    <label for="Nama">Nama Karyawan</label>
                    <span class="required">*</span>
                    <input name="nama_karyawan" type="text" class="form-control" id="" placeholder="Masukan nama karyawan ex: Fadhil" data-validation="required length"  data-validation-length="max30">
                    
                </div>
                <div class="form-group">
                    <label for="Alamat">Alamat</label>
                    <span class="required">*</span>
                    <input name="alamat_karyawan" type="text" class="form-control" id="" placeholder="Masukan alamat ex:Jl.Peta No 62" data-validation="required length"  data-validation-length="max50">
                </div>
                <div class="form-group">
                    <label for="Gaji">Gajih Karyawan</label>
                    <span class="required">*</span>
                    <input name="gajih_karyawan" type="number" min="0" class="form-control" id="" placeholder="Masukan Gajih Karyawan ex :10000" data-validation="length number"  data-validation-length="max15">
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                     <div class="form-group" style="margin: 5px auto; width: 50%;">
                         <label class="control-label" for="logo">Foto Karyawan
                        </label>
                            <img id="preview-logo" src="" border="0" alt="logo" style="max-width: 200px; max-height: 200px; margin: auto; display: block">
                            <br>
                         <input type="hidden" name="old_logo" id="old-logo" value="">
                         <input type="file" name="logo" id="btn-upload-logo" data-validation="mime size" data-validation-max-size="1M" class="form-control" data-validation-allowing="jpg, jpeg, png, gif" accept=".gif, .jpg, .jpeg, .png">
                    </div>
                </div>
                          

       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary"><span class="fa fa-save"> </span> Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="Modaledit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <span class="modal-title" id="exampleModalLabel"><h4>Edit Karyawan</h4></span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form id="formedit" action="" method="post">
               
            <input name="karyawan_id" type="number" class="form-control" id="form_id" required>
                <div class="form-group">
                    <label for="Nama">Nama Karyawan</label>
                    <input name="nama_karyawan" type="text" class="form-control" id="form_nama" placeholder="Masukan nama karyawan ex: Fadhil" data-validation="required length"  data-validation-length="max30">
                </div>
                <div class="form-group">
                    <label for="Alamat">Alamat</label>
                    <input name="alamat_karyawan" type="text" class="form-control" id="form_alamat" placeholder="Masukan alamat ex:Jl.Peta No 62" data-validation="required length"  data-validation-length="max50">
                </div>
                <div class="form-group">
                    <label for="Gaji">Gajih Karyawan</label>
                    <input name="gajih_karyawan" type="number" min="0" class="form-control" id="form_gajih" placeholder="Masukan Gajih Karyawan ex :10000"  data-validation="length number"  data-validation-length="max15">
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                     <div class="form-group" style="margin: 5px auto; width: 50%;">
                         <label class="control-label" for="logo">Foto Karyawan
                        </label>
                            <img id="preview-logo-edit" src="" border="0" alt="logo" style="max-width: 200px; max-height: 200px; margin: auto; display: block">
                            <br>
                         <input type="hidden" name="old_logo_edit" id="old-logo-edit" value="">
                         <input type="file" name="logo_edit" id="btn-upload-logo-edit" data-validation="mime size" data-validation-max-size="1M" class="form-control" data-validation-allowing="jpg, jpeg, png, gif" accept=".gif, .jpg, .jpeg, .png">
                    </div>
                </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary"><span class="fa fa-save"> </span> Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>  




<div id="detail" class="modal fade" role="dialog">
    <div class="custom-loading"><span></span></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">Detail</h4>
            </div>
            <div class="modal-body">
                    <div id="nama_karyawan"> </div>
                    <div id="alamat_karyawan"> </div>
                    <div id="gajih_karyawan"> </div>
                    <div style="margin: 10px auto; width: 50%; text-align: center;">
                     <img id="karyawan_image" src="" alt="" srcset="">
                    <span><strong>Image Karyawan</strong></span>
                    </div>
                   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
  


$("#gridview").flexigrid({
        url: '<?php echo site_url("/crud/karyawan/get_data"); ?>',
        dataType: 'json',
        colModel: [
           <?php
              echo "
                {display: 'Edit', name: 'edit', width: 50, sortable: false, align: 'left'},
                {display: 'Detail', name: 'detail', width: 50, sortable: false, align: 'left'},
                {display: 'Karyawan Code', name: 'karyawan_id', width: 100, sortable: true, align: 'left'},
                {display: 'Nama Karyawan', name: 'nama_karyawan', width: 200, sortable: true, align: 'left'},
                {display: 'Alamat Karyawan', name: 'alamat_karyawan', width: 480, sortable: true, align: 'left'},
                {display: 'Gajih Karyawan', name: 'gajih_karyawan', width: 200, sortable: true, align: 'left'},
              
                ";
            ?>
          
        ],
        buttons: [
            <?Php
            echo "
                {display: 'Add', name: 'add', bclass: 'add',onpress:addModal},
                {separator: true},
                {display: 'Delete', name: 'delete', bclass: 'delete', onpress: act_show, urlaction: '" .site_url('/crud/karyawan/act_delete'). "'},
                {separator: true},
                {display: 'Select All', name: 'selectall', bclass: 'selectall', onpress: check},
                {separator: true},
                {display: 'Unselect All', name: 'selectnone', bclass: 'selectnone', onpress: check},
                ";
                ?>
            
        ], buttons_right: [
            <?Php
                echo "{display: 'Export Excel', name: 'excel', bclass: 'excel', onpress: export_data, urlaction: '" . site_url("crud/karyawan/export_data_karyawan") . "'},";
                echo "{display: 'Export PDF', name: 'PDF', bclass: 'print', onpress: export_data, urlaction: '" . site_url("crud/karyawan/act_print") . "'}";
            ?>

             
        ],
        searchitems: [
          
           
                {display: 'Karyawan Code',type: 'text', name: 'karyawan_id', width: 100, sortable: true, align: 'left'},
                {display: 'Nama Karyawan',type: 'text', name: 'nama_karyawan', width: 200, sortable: true, align: 'left'},
                {display: 'Alamat Karyawan',type: 'text', name: 'alamat_karyawan', width: 200, sortable: true, align: 'left'},
                {display: 'Gajih Karyawan',type: 'text', name: 'gajih_karyawan', width: 200, sortable: true, align: 'left'},
               
        ],

        sortname: "karyawan_id",
        sortorder: "asc",
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

  

    function addModal(){
        $('#Modal').modal('show');
        $('#preview-logo').attr('src', '<?php echo THEMES_BACKEND . '/images/no-img.jpg'; ?>');
        
    }       
  
 


    function detailKaryawan(id){
        $.ajax({
            url:'<?php echo site_url('crud/karyawan/get_data_by_id'); ?>',
            method: 'GET',
            data: 'id=' + id,
            success: function(res){
              
                    $("#detail").modal('show');
                    $("#nama_karyawan").html('<b>Nama Karyawan : </b>' + res.nama_karyawan);
                    $("#alamat_karyawan").html('<b> Alamat Karyawan : </b>'  + res.alamat_karyawan);
                    $("#gajih_karyawan").html('<b> Gajih Karyawan : </b> ' + res.gajih_karyawan);
                    $("#karyawan_image").attr('src' ,"<?php echo site_url('media/assets/images/crud/250/250/'); ?>" +res.foto_karyawan);
            }
        }); 
    }

    function tampilkanEdit(id){
        $.ajax({
            url:'<?php echo site_url('crud/karyawan/get_data_by_id'); ?>',
            method: 'GET',
            data: 'id=' + id,
            success: function(res){
           
                    
                    $("#Modaledit").modal('show');
                    $("#form_id").val(res.karyawan_id);
                    $("#form_id").hide();
                    $("#form_nama").val(res.nama_karyawan);
                    $("#form_alamat").val(res.alamat_karyawan);
                    $("#form_gajih").val(res.gajih_karyawan);

                    $("#old-logo-edit").val(res.foto_karyawan);

                     if(res.foto_karyawan != '' && res.foto_karyawan != null){
                            $('#preview-logo-edit').attr('src', '<?php echo site_url('media/assets/images/crud/250/250/') ?>' + res.foto_karyawan);
                        }else{
                            $('#preview-logo-edit').attr('src', '<?php echo THEMES_BACKEND . '/images/no-img.jpg'; ?>');
                        }
                     


            }
        }); 
    }

    function editKaryawan(id){
      tampilkanEdit(id);
   
      $("#formedit").on('submit', function(e){
         e.preventDefault();
          let formdataedit = new FormData(this);
          $.ajax({
                type: 'POST',
                url: '/core_admin/crud/karyawan/act_update',
                data: formdataedit,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    if(response.status == 200){
                        $("#Modaledit").modal('hide');
                    }else{
                        alert('gagal');
                    }
                  
                    $('#gridview').flexReload();
                },
                error: function (err) {
                    console.log(err);
                }
            });
      });
    }
  

    $(document).ready(function(){
        $('#btn-upload-logo-edit').on('change', function (){
            readURL(this, '#preview-logo-edit');
        });
        $('#btn-upload-logo').on('change', function (){
        readURL(this, '#preview-logo')
       });
        $("#form").on('submit', function(e){
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '/core_admin/crud/karyawan/act_add',
                data: formData,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    if(response.status == 200){
                        alert('Success');
                        $("#form").trigger('reset');
                    }else{
                        alert('gagal');
                    }
                  
                    $('#gridview').flexReload();
                },
                error: function (err) {
                    console.log(err);
                }
            });
        })
    });

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
<script src="<?php echo THEMES_BACKEND; ?>/vendor/js/form-validator/jquery.form-validator.min.js"></script>
<script>
    $.validate({
        modules: 'logic, file, security',
        // lang: 'id'
    });
</script>
