			<footer class="main-footer">
				<div class="pull-right hidden-xs">
				  	<b><a href="http://opensid.info">www.opensid.info</a></b>
				</div>
		    <!-- Default to the left -->
		    <strong>Copyright &copy; 2018 <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>.</strong> All rights reserved.
			</footer>
		</div>


	  <?php $adminlte = 'vendor/almasaeed2010/adminlte/'; ?>
		<script src="<?= base_url($adminlte.'bower_components/jquery/dist/jquery.min.js')?>"></script>
		<script src="<?= base_url($adminlte.'bower_components/bootstrap/dist/js/bootstrap.min.js')?>"></script>
		<script src="<?= base_url($adminlte.'dist/js/adminlte.min.js')?>"></script>

		<script type="text/javascript">

			$(document).ready(function() {
				$('.sidebar-menu li').removeClass("active");
		    //Enable sidebar dinamic menu
		    dinamicMenu();
		  });
	    /* DinamicMenu()
	     * dinamic activate menu
	     */
	    function dinamicMenu() {
	        var url = window.location;
	        // Will only work if string in href matches with location
	        $('.sidebar-menu li a[href="' + url + '"]').parent().addClass('active');
	        $('.treeview-menu li a[href="' + url + '"]').parent().addClass('active');
	        // Will also work for relative and absolute hrefs
	        $('.treeview-menu li a').filter(function() {
	            return this.href == url;
	        }).parent().parent().parent().addClass('active');
	    };

	  </script>



	</body>
</html>

