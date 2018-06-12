
<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Kabupaten OpenSID
        <small>(Kabupaten dengan desa OpenSID)</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= site_url()?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Laporan</li>
        <li class="active">Kabupaten OpenSID</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid" id="main">

        <input type="hidden" name="arg_id_local" value="<?php echo $is_local?>">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title" >Filter</h3>
            </div>
            <div class="panel-body">
                <form id="form-filter" class="form-horizontal">
                    <div class="form-group">
                        <label for="is_local" class="col-sm-2 control-label">Jenis Server</label>
                        <div class="col-sm-4">
                            <?php echo $form_server; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="LastName" class="col-sm-2 control-label"></label>
                        <div class="col-sm-4">
                            <button type="button" id="btn-filter" class="btn btn-primary">Filter</button>
                            <button type="button" id="btn-reset" class="btn btn-default">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kabupaten</th>
                    <th>Provinsi</th>
                    <th>Server Offline</th>
                    <th>Server Online</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
                <tr>
                    <th>No</th>
                    <th>Kabupaten</th>
                    <th>Provinsi</th>
                    <th>Server Offline</th>
                    <th>Server Online</th>
                </tr>
            </tfoot>
        </table>

    <form action="<?php echo site_url("laporan")?>" method="POST" id="show_desa">
        <input name='is_local' type="hidden">
        <input name='kab' type="hidden">
    </form>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php $adminlte = 'vendor/almasaeed2010/adminlte/'; ?>
<script src="<?= base_url($adminlte.'bower_components/jquery/dist/jquery.min.js')?>"></script>
<script src="<?= base_url($adminlte.'bower_components/bootstrap/dist/js/bootstrap.min.js')?>"></script>
<script src="<?= base_url($adminlte.'dist/js/adminlte.min.js')?>"></script>

<!-- Ambil confirmation dialog dari https://ethaizone.github.io/Bootstrap-Confirmation/#install
-->
<script src="<?php echo base_url('assets/js/popper.js')?>"></script> <!-- diperlukan bootstrap -->
<script src="<?php echo base_url('assets/js/bootstrap-tooltip.js') ?>"></script> <!-- diperlukan bootstrap-confirmation -->
<script src="<?php echo base_url('assets/js/bootstrap-confirmation.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/js/script.js') ?>"></script>


<script type="text/javascript">

var table;

$(document).ready(function() {

    $('#is_local').val($('input[name=arg_id_local').val());

    //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('laporan/ajax_profil_kabupaten')?>",
            "type": "POST",
            "data": function ( data ) {
                data.is_local = $('#is_local').val();
            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });

    // https://stackoverflow.com/questions/14619498/datatables-global-search-on-keypress-of-enter-key-instead-of-any-key-keypress
    $('#table_filter input').unbind();
    $('#table_filter input').bind('keyup', function(e) {
        if(e.keyCode == 13) {
            table.search( this.value ).draw();
        }
    });

    $('#btn-filter').click(function(){ //button filter event click
        table.ajax.reload();  //just reload table
    });
    $('#btn-reset').click(function(){ //button reset event click
        $('#form-filter')[0].reset();
        table.ajax.reload();  //just reload table
    });

});

function goto_desa(kab, jenis_server) {
    $('input[name=is_local').val(jenis_server);
    $('input[name=kab').val(kab);
    $('form#show_desa').submit();
}

</script>
