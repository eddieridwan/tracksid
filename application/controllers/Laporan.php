<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

  function __construct(){
    parent::__construct();
    $this->load->model('desa_model');
    $this->load->helper('url');
    session_start();
  }

  public function index()
  {
    if(isset($_SESSION['filter']))
      $data['filter'] = $_SESSION['filter'];
    else $data['filter'] = '';

    $data['list_desa'] = $this->desa_model->list_desa();
    $this->load->view('list_desa', $data);
  }

  public function filter(){
    $filter = $this->input->post('filter');
    if($filter!=0)
      $_SESSION['filter']=$filter;
    else unset($_SESSION['filter']);
    redirect("/laporan");
  }

}
