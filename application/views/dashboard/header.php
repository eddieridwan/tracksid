<!-- Perubahan script coding untuk bisa menampilkan header dalam bentuk tampilan bootstrap (AdminLTE)  -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
	  <?php $adminlte = 'vendor/almasaeed2010/adminlte/'; ?>
		<title>
			Status OpenSID
		</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	  <!-- Tell the browser to be responsive to screen width -->
	  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?= base_url()?>rss.xml" />

		<link rel="stylesheet" href="<?= base_url().$adminlte?>bower_components/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?= base_url().$adminlte?>bower_components/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?= base_url().$adminlte?>bower_components/ionicons/css/ionicons.min.css">
		<link rel="stylesheet" href="<?= base_url().$adminlte?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
		<link rel="stylesheet" href="<?= base_url().$adminlte?>dist/css/AdminLTE.min.css">
		<!-- AdminLTE Skins. -->
		<link rel="stylesheet" href="<?= base_url().$adminlte?>dist/css/skins/_all-skins.min.css">
		<link rel="stylesheet" href="<?= base_url().$adminlte?>plugins/pace/pace.min.css">
		<link href="<?php echo base_url('assets/css/style.css') ?>" rel="stylesheet">
		<style type="text/css">
			.navbar-nav>.user-menu .user-image {
				width: 25px;
			}
		</style>
	</head>
	<body class="skin-purple sidebar-mini ">
		<div class="wrapper">
			<header class="main-header">
				<a href="<?= site_url()?>first"  target="_blank" class="logo">
					<span class="logo-mini"><b>SID</b></span>
					<span class="logo-lg"><b>TrackSID</b></span>
				</a>
				<nav class="navbar navbar-static-top">
					<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<?php if (isset($_SESSION['username']) && $_SESSION['logged_in'] === true) : ?>
								<li class="dropdown user user-menu">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
	                  <img src="<?= base_url($adminlte.'dist/img/user2-160x160.jpg')?>" class="img-circle user-image" alt="User Image">
										<span class="hidden-xs">NAMA </span>
									</a>
									<ul class="dropdown-menu">
										<li class="user-header">
		                  <img src="<?= base_url($adminlte.'dist/img/user2-160x160.jpg')?>" class="img-circle" alt="User Image">
											<p>Anda Login Sebagai</p>
											<p><strong>NAMA</strong></p>
										</li>
										<li class="user-footer">
											<div class="pull-left">
												<a href="<?= site_url()?>user_setting/"
													data-toggle="modal" data-target="#modalBox">
													<button  data-toggle="modal"  class="btn bg-maroon btn-flat btn-sm" >Profile</button>
												</a>
											</div>
											<div class="pull-right">
												<a href="<?= site_url('logout')?>" class="btn bg-maroon btn-flat btn-sm">Logout</a>
											</div>
										</li>
									</ul>
								</li>
							<?php else : ?>
								<li><a href="<?= site_url('login') ?>">Login</a></li>
							<?php endif; ?>
						</ul>
					</div>
				</nav>
			</header>
			<input id="success-code" type="hidden" value="$_SESSION['success']">
			<!-- Untuk menampilkan modal bootstrap info pengguna login  -->
			<div  class="modal fade" id="modalBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
							<h4 class='modal-title' id='myModalLabel'><i class='fa fa-text-width text-yellow'></i> Ubah Password</h4>
						</div>
						<div class="fetched-data"></div>
					</div>
				</div>
			</div>
			<!-- Untuk menampilkan modal / pemberitahuan perubahan password default  -->
			<div  class="modal fade" id="massageBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header btn-info'>
							<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
							<h4 class='modal-title' id='myModalLabel'><i class='fa fa-text-width text-white'></i> $_SESSION['admin_warning'][0];</h4>
						</div>
						<div class='modal-body'>
							$_SESSION['admin_warning'][1];
						</div>
						<div class='modal-footer'>
							<button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-arrow-circle-o-left'></i> Lain Kali</button>
							<a href="<?= site_url()?>user_setting/" data-toggle="modal" data-target="#modalBox" id="ok">
								<button type="button" class="btn btn-social btn-flat btn-success btn-sm"><i class='fa fa-edit'></i> Ubah</button>
							</a>
						</div>
					</div>
				</div>
			</div>




