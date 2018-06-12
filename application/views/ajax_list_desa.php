<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
    <style type="text/css">
        tr.highlight { background-color: orange !important; }
        tr.nonaktif { background-color: #F9E79F !important; }
    </style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Desa OpenSID
        <small>(Desa yang memasang OpenSID)</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= site_url()?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Laporan</li>
        <li class="active">Desa OpenSID</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid" id="main">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->

        <div>
            <input type="hidden" name="arg_id_local" value="<?php echo $is_local?>">
            <input type="hidden" name="arg_kab" value="<?php echo $kab?>">
        </div>

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
                        <label for="kab" class="col-sm-2 control-label">Kabupaten</label>
                        <div class="col-sm-4">
                            <?php echo $form_kab; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="akses" class="col-sm-2 control-label">Akses Terakhir</label>
                        <div class="col-sm-4">
                            <?php echo $form_akses; ?>
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
                    <th>Aksi</th>
                    <th>Desa</th>
                    <th>Kecamatan</th>
                    <th>Kabupaten</th>
                    <th>Provinsi</th>
                    <th>Web</th>
                    <th>Versi Offline</th>
                    <th>Versi Online</th>
                    <th>Akses Terakhir</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
                <tr>
                    <th>No</th>
                    <th>Aksi</th>
                    <th>Desa</th>
                    <th>Kecamatan</th>
                    <th>Kabupaten</th>
                    <th>Provinsi</th>
                    <th>Web</th>
                    <th>Versi Offline</th>
                    <th>Versi Online</th>
                    <th>Akses Terakhir</th>
                </tr>
            </tfoot>
        </table>


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
    $('#kab').val($('input[name=arg_kab').val());

    //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('laporan/ajax_list_desa')?>",
            "type": "POST",
            "data": function ( data ) {
                data.is_local = $('#is_local').val();
                data.kab = $('#kab').val();
                data.akses = $('#akses').val();
            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            {
                "targets": [ 0,1 ], //first column / numbering column
                "orderable": false, //set not orderable
            },
            {
                "targets": [ <?php echo admin_logged_in() ? '10' : '1,10';?> ], // kolom aksi, jenis tidak ditampilkan
                "visible": false
            }
        ],

        "createdRow": function ( row, data, index ) {
            if ( data[10] == '2' ) {
                $(row).addClass('highlight');
            } else {
                var d = new Date();
                tgl_nonaktif = d.setMonth(d.getMonth()-2);
                if ( Date.parse(data[9]) < tgl_nonaktif ) {
                    $(row).addClass('nonaktif')
                }
            }
        },

        "initComplete": function(settings, json) {
            // Aktifkan bootstrap-confirmation setelah data selesai dimuat;
            $('[data-toggle="confirmation"]').confirmation();
        }
    });

    // https://datatables.net/reference/event/draw
    table.on( 'draw', function () {
        // Aktifkan bootstrap-confirmation setelah data selesai ditampilkan;
        $('[data-toggle="confirmation"]').confirmation();
    } );

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

</script>
