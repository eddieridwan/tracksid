  <style type="text/css">
    .small-box .icon {
      font-size: 80px;
      top: 4px;
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
                <div class="col-lg-4 col-xs-4">
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
                <div class="col-lg-4 col-xs-4">
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
                <div class="col-lg-4 col-xs-4">
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
              </div>
          </div>
          <div class='box box-info'>
             <div class="box-header">
                  <h3 class="box-title">Kabupaten Pengguna</h3>
             </div>
              <div class='box-body'>
                <div class="col-lg-4 col-xs-4">
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
                <div class="col-lg-4 col-xs-4">
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
                <div class="col-lg-4 col-xs-4">
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
          </div>
        </div>
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
