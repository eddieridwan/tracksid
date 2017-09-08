<div id="laporan_nav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <a href="<?php echo (uri_string() != 'laporan') ? site_url('laporan') : '#'?>">Daftar Desa</a>
  <a href="<?php echo (strpos(uri_string(),'profil_kabupaten') === FALSE) ? site_url('laporan/profil_kabupaten') : '#'?>">Profil Kabupaten</a>
</div>

<script src="<?php echo base_url('assets/jquery/jquery-2.2.3.min.js')?>"></script>
<script type="text/javascript">
	/* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
	function openNav() {
	    document.getElementById("laporan_nav").style.width = "250px";
	    document.getElementById("main").style.marginLeft = "250px";
	    document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
	}

	/* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
	function closeNav() {
	    document.getElementById("laporan_nav").style.width = "0";
	    document.getElementById("main").style.marginLeft = "0";
	    document.body.style.backgroundColor = "white";
	};

	$(document).ready(function() {
		$('#laporan_nav > a').click(function(){
			closeNav();
		});
	});
</script>
