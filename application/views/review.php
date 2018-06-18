<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
    <style type="text/css">
        .highlight { background-color: orange !important; }
        .nonaktif { background-color: #F9E79F !important; }
    </style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Desa Untuk Diperiksa
        <small>(Desa non-aktif dengan nama tidak terdaftar)</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= site_url()?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Laporan</li>
        <li class="active">Review Desa</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid" id="main">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->

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
              <?php foreach($main as $data): ?>
                <tr>

                  <td></td>
                  <td class="<?= $data->jenis == '2' ? 'highlight' : ''?>">
										<button href="" onclick="deleteItem(<?php echo $data->id; ?>)" title="Hapus Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-trash"></i></button>
                  </td>
                  <td><?= $data->nama_desa ?></td>
                  <td><?= $data->nama_kecamatan ?></td>
                  <td><?= $data->nama_kabupaten ?></td>
                  <td><?= $data->nama_provinsi ?></td>
                  <td><a href="http://<?= $data->url_hosting ?>" target='_blank'><?= $data->url_hosting ?></a></td>
                  <td><?= $data->versi_lokal ?></td>
                  <td><?= $data->versi_hosting ?></td>
                  <td><?= $data->tgl_akses ?></td>
                </tr>
              <?php endforeach; ?>
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
	  <script src="<?= base_url('assets/js/popper.js')?>"></script> <!-- diperlukan bootstrap -->
    <script src="<?= base_url('assets/js/bootstrap-tooltip.js') ?>"></script> <!-- diperlukan bootstrap-confi=-->
    <script src="<?= base_url('assets/js/bootstrap-confirmation.js') ?>"></script>
    <script src="<?= base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
    <script src="<?= base_url('assets/datatables/js/dataTables.bootstrap.min.js')?>"></script>
		<script src="<?= base_url('assets/js/sweetalert.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/script.js') ?>"></script>



<script type="text/javascript">
function deleteItem($id){
	swal({
			title: "Apakah Anda Yakin?",
			text: "Setelah dihapus, data tidak dapat dipulihkan!!",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) {
				swal("Data berhasil dihapus!", {
				icon: "success",
				});

				window.location = "<?= site_url('api_desa/delete')?>/" + $id;
			} else {
				swal("Data tidak berhasil dihapus!");
			}
		});
}

var table;

$(document).ready(function() {

		var t = $('#table').DataTable( {
			scrollY					: '100vh',
			scrollCollapse	: true,
			autoWidth				: true,
    	"columnDefs": [
      	{
          	"targets": [ 0, 1],
          	"searchable": false,
          	"orderable": false,
      	}
    	],
    	"order": [[9, 'desc']]
  	} );
		t.on( 'order.dt search.dt', function () {
			t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();

});

</script>
