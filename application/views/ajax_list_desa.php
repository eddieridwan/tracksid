<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Desa Pengguna OpenSID</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.min.css')?>" rel="stylesheet">
   <link href="<?php echo base_url('assets/css/laporan.css')?>" rel="stylesheet">
     <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    </head>
<body>
    <?php $this->load->view('_laporan_nav'); ?>

    <div id="main" class="container">
      <!-- Use any element to open the sidenav -->
      <button type="button" class="btn btn-secondary">
        <span onclick="openNav()">Menu</span>
      </button>
        <h1 style="font-size:20pt">
            <span>Desa Pengguna OpenSID</span>
            <?php if (defined('ENVIRONMENT') AND ENVIRONMENT == 'development'): ?>
              <span style="background-color: orange;">(DEVELOPMENT)</span>
            <?php endif; ?>
        </h1>

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
                    <th>Desa</th>
                    <th>Kecamatan</th>
                    <th>Kabupaten</th>
                    <th>Provinsi</th>
                    <th>Web</th>
                    <th>Versi Offline</th>
                    <th>Versi Online</th>
                    <th>Tgl Rekam</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
                <tr>
                    <th>No</th>
                    <th>Desa</th>
                    <th>Kecamatan</th>
                    <th>Kabupaten</th>
                    <th>Provinsi</th>
                    <th>Web</th>
                    <th>Versi Offline</th>
                    <th>Versi Online</th>
                    <th>Tgl Rekam</th>
                </tr>
            </tfoot>
        </table>
    </div>

<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.min.js')?>"></script>


<script type="text/javascript">

var table;

$(document).ready(function() {

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

</script>

</body>
</html>