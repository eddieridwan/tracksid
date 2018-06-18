<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Api_desa extends CI_Controller
{
    public function __construct()
    {
      parent::__construct();
			session_start();
			$this->load->model('desa_model');
      $this->load->helper('url');
      if (!admin_logged_in())
        exit("Anda tidak mempunyai akses ke menu ini.");
    }
   public function delete($id)
    {
      json_encode($this->desa_model->hapus($id));
      redirect('laporan/review');

    }
}