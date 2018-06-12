<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Login
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= site_url()?>""><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Login</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content container-fluid">

		<div class="row">
			<?php if (validation_errors()) : ?>
				<div class="col-md-6">
					<div class="alert alert-danger" role="alert">
						<?= validation_errors() ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if (isset($error)) : ?>
				<div class="col-md-6">
					<div class="alert alert-danger" role="alert">
						<?= $error ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="col-md-6">
				<?= form_open() ?>
					<div class="form-group">
						<label for="username">Username</label>
						<input type="text" class="form-control" id="username" name="username" placeholder="Your username">
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" class="form-control" id="password" name="password" placeholder="Your password">
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-default" value="Login">
					</div>
				<?= form_close() ?>
			</div>
		</div><!-- .row -->

	</section>
</div><!-- .content-wrapper -->