<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
  function __construct(){
    parent::__construct();
    session_start();
    $this->load->helper('url');
    $this->load->model('desa_model');
  }

	public function index()
	{
		$this->load->view('dashboard');
	}

	public function index_baru()
	{
		$data = array();
		$data = $this->desa_model->jmlDesa();
    $data['baru'] = $this->desa_model->get_baru();
		$this->load->view('dashboard/header');
		$this->load->view('dashboard/nav');
		$this->load->view('dashboard/dashboard', $data);
		$this->load->view('dashboard/footer');
	}

}
