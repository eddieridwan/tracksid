<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	$config['useragent'] = 'CodeIgniter';
	$config['protocol'] = 'smtp';
	//$config['mailpath'] = '/usr/sbin/sendmail';
	$config['smtp_host'] = 'ssl://smtp.googlemail.com';
	$config['smtp_port'] = 465;
	$config['smtp_timeout'] = 5;
	$config['wordwrap'] = TRUE;
	$config['wrapchars'] = 76;
	$config['mailtype'] = 'html';
	$config['charset'] = 'utf-8';
	$config['validate'] = FALSE;
	$config['priority'] = 3;
	$config['crlf'] = "\r\n";
	$config['newline'] = "\r\n";
	$config['bcc_batch_mode'] = FALSE;
	$config['bcc_batch_size'] = 200;

	if (file_exists($file_path = FCPATH.'config/email.php'))
	{
		include($file_path);
	}
