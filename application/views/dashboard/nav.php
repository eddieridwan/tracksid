<!-- Perubahan script coding untuk bisa menampilkan menu dan sub menu bootstrap (AdminLTE) berdasarkan daftar modul dan sub modul yang aktif  -->
<!-- Nantinya seluruh file nav.php (sub modul) yang ada di masing-masing folder modul utama akan dihapus (sudah tidak digunakan lagi)  -->
<aside class="main-sidebar">
	<section class="sidebar">
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?= base_url().'assets/images/opensid_logo.png'?>" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				STATUS OPENSID
			</div>
		</div>
		<ul class="sidebar-menu" data-widget="tree">
			<li class="header">MENU UTAMA</li>
      <li><a href="<?= site_url()?>"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
      <li class="treeview">
        <a href="#"><i class="fa fa-file-text"></i> <span>Laporan</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="<?= site_url('laporan')?>">Desa OpenSID</a></li>
          <li><a href="<?= site_url('laporan/profil_kabupaten')?>">Kabupaten OpenSID</a></li>
          <li><a href="<?= site_url('laporan/profil_versi')?>">Versi OpenSID</a></li>
        </ul>
      </li>
      <li id="wilayah"><a href="<?= site_url('wilayah')?>"><i class="fa fa-list"></i> <span>Wilayah Administratif</span></a></li>
  	</ul>
	</section>
 </aside>

