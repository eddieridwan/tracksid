<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
	File berisi setting sensitif ada di config/sandi.php yang tidak disimpan di git
*/

if (file_exists($file_path = FCPATH.'config/sandi.php'))
	{
		include($file_path);
	}
$config['nothing'] = '';