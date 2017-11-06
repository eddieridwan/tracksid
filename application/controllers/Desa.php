<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Desa extends CI_Controller {

  function __construct(){
    parent::__construct();
    session_start();
    $this->load->helper('url');
    if (!admin_logged_in()) redirect('login');
  }

	public function index()
	{
	}

	public function hapus($id){
		$this->load->model('desa_model');
		$this->desa_model->hapus($id);
		redirect('/');
	}

}
