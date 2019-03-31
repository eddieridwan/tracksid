<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akses extends CI_Controller {

  function __construct(){
    parent::__construct();
    session_start();
    $this->load->helper('url');
    if (!admin_logged_in()) redirect('login');
  }

	public function index()
	{
	}

	public function bersihkan()
	{
		$this->load->model('akses_model');
		$this->load->model('desa_model');
		$this->akses_model->bersihkan();
		$this->desa_model->hapus_nonaktif_tdkterdaftar();
		redirect('laporan/review');
	}

}
