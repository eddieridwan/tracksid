<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $title?></title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="">

	<!-- css -->
	<link href="<?php echo base_url('assets/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.min.css')?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/css/laporan.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/style.css') ?>" rel="stylesheet">

	<!-- js -->
	<!-- Ambil confirmation dialog dari https://ethaizone.github.io/Bootstrap-Confirmation/#install
	-->
	<script src="<?php echo base_url('assets/jquery/jquery-3.2.1.js')?>"></script>
  <script src="<?php echo base_url('assets/js/popper.js')?>"></script> <!-- diperlukan bootstrap -->
	<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/bootstrap-tooltip.js') ?>"></script> <!-- diperlukan bootstrap-confirmation -->
	<script src="<?php echo base_url('assets/js/bootstrap-confirmation.js') ?>"></script>
	<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
	<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.min.js')?>"></script>
	<script src="<?php echo base_url('assets/js/script.js') ?>"></script>

	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script>
		jQuery(function($) {
			$('.navbar .dropdown').hover(function() {
				$(this).find('.dropdown-menu').first().stop(true, true).delay(250).slideDown();
			}, function() {
				$(this).find('.dropdown-menu').first().stop(true, true).delay(100).slideUp();
			});
			$('.navbar .dropdown > a').click(function(){
				location.href = this.href;
			});
		});
	</script>

</head>
<body>

	<header id="site-header">
		<nav class="navbar navbar-default" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="<?php echo base_url() ?>">
						Pengguna OpenSID
	          <?php if (defined('ENVIRONMENT') AND ENVIRONMENT == 'development'): ?>
	            <span style="background-color: orange;">(DEVELOPMENT)</span>
	          <?php endif; ?>
	        </a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown">Laporan OpenSID<span class='caret'></span></a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo site_url('laporan');?>">Daftar Desa</a></li>
								<li><a href="<?php echo site_url('laporan/profil_kabupaten ');?>">Profil Kabupaten</a></li>
								<li><a href="<?php echo site_url('laporan/profil_versi');?>">Profil Versi</a></li>
							</ul>
						</li>

						<?php if (isset($_SESSION['username']) && $_SESSION['logged_in'] === true) : ?>
							<li><a href="<?= site_url('logout') ?>">Logout</a></li>
						<?php else : ?>
							<li><a href="<?= site_url('register') ?>">Register</a></li>
							<li><a href="<?= site_url('login') ?>">Login</a></li>
						<?php endif; ?>
					</ul>
				</div><!-- .navbar-collapse -->
			</div><!-- .container-fluid -->
		</nav><!-- .navbar -->
	</header><!-- #site-header -->

	<main id="site-content" role="main">

		<?php if (!empty($_SESSION) AND (defined('ENVIRONMENT') AND ENVIRONMENT == 'development')) : ?>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<?php var_dump($_SESSION); ?>
					</div>
				</div><!-- .row -->
			</div><!-- .container -->
		<?php endif; ?>

