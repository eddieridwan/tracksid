<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

  function __construct(){
    parent::__construct();
    $this->load->model('desa_model');
    $this->load->helper('url');
    session_start();
  }

  public function index($offset=0)
  {
    if(isset($_SESSION['filter']))
      $data['filter'] = $_SESSION['filter'];
    else $data['filter'] = '';

    $data_desa = $this->desa_model->list_desa($offset);
    $data['list_desa'] = $data_desa['list_desa'];
    $data['links'] = $data_desa['links'];
    $data['offset'] = $offset;
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
