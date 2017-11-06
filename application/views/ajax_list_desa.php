<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
    <style type="text/css">
        tr.highlight { background-color: orange !important; }
        tr.nonaktif { background-color: #F9E79F !important; }
    </style>

    <?php $this->load->view('_laporan_nav'); ?>

    <div id="main" class="container">

      <!-- Use any element to open the sidenav -->
      <button type="button" class="btn btn-secondary">
        <span onclick="openNav()">Menu</span>
      </button>
        <h1 style="font-size:20pt">
            <span>Desa Pengguna OpenSID</span>
        </h1>

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
    </div>

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
            };
            var d = new Date();
            tgl_nonaktif = d.setMonth(d.getMonth()-2);
            if ( Date.parse(data[9]) < tgl_nonaktif ) {
                $(row).addClass('nonaktif');
            }
        }
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

</script>
