<?php
	function admin_logged_in(){
		if (!isset($_SESSION['logged_in']) OR !isset($_SESSION['is_admin'])) return false;
		return ($_SESSION['logged_in'] AND $_SESSION['is_admin']);
	}

?>