	<style type="text/css">
		.small-box .icon {
			font-size: 80px;
			top: 4px;
		}
		td.break {
			word-break: break-all;
		}
	</style>
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				Dashboard
				<small>Status Penggunaan OpenSID</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Dashboard</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content container-fluid">

			<!--------------------------
				| Your Page Content Here |
				-------------------------->

			<div class='row'>
				<div class='col-md-9'>
					<div class='box box-info'>
						<div class="box-header">
							<h3 class="box-title">Desa Pengguna</h3>
						</div>
						<div class='box-body'>
							<div class="col-md-4">
								<div class="small-box bg-blue">
									<div class="inner">
										<h3><?= $desa_total ?></h3>
										<p>Total Desa</p>
									</div>
									<div class="icon">
										<i class="ion ion-location"></i>
									</div>
									<a href="<?=site_url('laporan')?>" class="small-box-footer">Lihat Detail  <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
							<div class="col-md-4">
								<div class="small-box bg-teal">
									<div class="inner">
										<h3><?= $desa_online ?></h3>
										<p>Desa Online</p>
									</div>
									<div class="icon">
										<i class="ion ion-android-cloud-done"></i>
									</div>
									<a href="<?=site_url('laporan/index/0')?>" class="small-box-footer">Lihat Detail  <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
							<div class="col-md-4">
								<div class="small-box bg-aqua">
									<div class="inner">
										<h3><?= $desa_offline ?></h3>
										<p>Desa Offline</p>
									</div>
									<div class="icon">
										<i class="ion ion-android-cloud-outline"></i>
									</div>
									<a href="<?=site_url('laporan/index/1')?>" class="small-box-footer">Lihat Detail  <i class="fa fa-arrow-circle-right"></i></a>
								 </div>
							</div>

							<div class="col-md-4">
								<div class="info-box info-box-sm">
									<span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
									<div class="info-box-content">
										<span class="info-box-text"><strong><?= $aktif ?></strong></span>
											Aktif 7 hari terakhir
									</div><!-- /.info-box-content -->
								</div><!-- /.info-box -->
							</div>
							<div class="col-md-4">
								<div class="info-box info-box-sm">
									<span class="info-box-icon bg-red"><i class="fa fa-times"></i></span>
									<div class="info-box-content">
										<span class="info-box-text"><strong><?= $tidak_aktif ?></strong></span>
											Tidak aktif 4 bulan
									</div><!-- /.info-box-content -->
								</div><!-- /.info-box -->
							</div>
							<div class="col-md-4">
								<div class="info-box info-box-sm">
									<span class="info-box-icon bg-yellow"><i class="fa fa-question"></i></span>
									<div class="info-box-content">
										<span class="info-box-text"><strong><?= $bukan_desa ?></strong></span>
											Bukan desa terdaftar
									</div><!-- /.info-box-content -->
								</div><!-- /.info-box -->
							</div>
						</div>

	          <div class="box box-primary collapsed-box">
	            <div class="box-header with-border">
	              <h3 class="box-title">Desa baru dalam 7 hari terakhir</h3>

	              <div class="box-tools pull-right">
	                <span data-toggle="tooltip" title="<?= count($baru) ?> Desa Baru" class="badge bg-light-blue"><?= count($baru) ?></span>
	                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                </button>
	              </div>
	              <!-- /.box-tools -->
	            </div>
	            <!-- /.box-header -->
	            <div class="box-body">
	              <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Desa</th>
                      <th>Kecamatan</th>
                      <th>Kabupaten</th>
                      <th>Provinsi</th>
                      <th>Web</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($baru as $data): ?>
                      <tr>
                        <td></td>
                        <td><?= $data->nama_desa ?></td>
                        <td><?= $data->nama_kecamatan ?></td>
                        <td><?= $data->nama_kabupaten ?></td>
                        <td><?= $data->nama_provinsi ?></td>
                        <td class="break"><a href="http://<?= $data->url_hosting ?>" target='_blank'><?= $data->url_hosting ?></a></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
	              </table>
	            </div>
	            <!-- /.box-body -->
	          </div>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-9'>
					<div class='box box-info'>
						<div class="box-header">
							<h3 class="box-title">Kabupaten Pengguna</h3>
						</div>
						<div class='box-body'>
							<div class="col-md-4">
								<div class="small-box bg-red">
									<div class="inner">
										<h3><?= $kabupaten_total ?></h3>
										<p>Total Kabupaten</p>
									</div>
									<div class="icon">
										<i class="ion ion-map"></i>
									</div>
									<a href="<?=site_url('laporan/profil_kabupaten')?>" class="small-box-footer">Lihat Detail  <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
							<div class="col-md-4">
								<div class="small-box bg-orange">
									<div class="inner">
										<h3><?= $kabupaten_online ?></h3>
										<p>Kabupaten Online</p>
									</div>
									<div class="icon">
										<i class="fa ion-android-cloud-done"></i>
									</div>
									<a href="<?=site_url('laporan/profil_kabupaten/0')?>" class="small-box-footer">Lihat Detail  <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
							<div class="col-md-4">
								<div class="small-box bg-yellow">
									<div class="inner">
										<h3><?= $kabupaten_offline ?></h3>
										<p>Kabupaten Offline</p>
									</div>
									<div class="icon">
										<i class="ion ion-android-cloud-outline"></i>
									</div>
									<a href="<?=site_url('laporan/profil_kabupaten/1')?>" class="small-box-footer">Lihat Detail  <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
						</div>
	          <div class="box box-primary collapsed-box">
	            <div class="box-header with-border">
	              <h3 class="box-title">Kabupaten yang belum ada desa OpenSID</h3>

	              <div class="box-tools pull-right">
	                <span data-toggle="tooltip" title="<?= count($kabupaten_kosong) ?> Kabupaten belum ada OpenSID" class="badge bg-light-blue"><?= count($kabupaten_kosong) ?></span>
	                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                </button>
	              </div>
	              <!-- /.box-tools -->
	            </div>
	            <!-- /.box-header -->
	            <div class="box-body">
	              <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Kode Kabupaten
                      <th>Nama Kabupaten</th>
                      <th>Nama Provinsi</th>
                      <th>Jumlah Desa</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($kabupaten_kosong as $data): ?>
                      <tr>
                        <td></td>
                        <td><?= $data->region_code ?></td>
                        <td><?= $data->nama_kabupaten ?></td>
                        <td><?= $data->nama_provinsi ?></td>
                        <td><?= $data->jml_desa ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
	              </table>
	            </div>
	            <!-- /.box-body -->
	          </div>


					</div>
				</div>
			</div>

		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->


