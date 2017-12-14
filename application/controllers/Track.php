<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Track extends CI_Controller {

  function __construct(){
    parent::__construct();
    $this->load->helper('url');
    session_start();
  }

  public function desa(){
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    $this->load->model('desa_model');
    $data = $_POST;
    $this->desa_model->normalkan_nama($data);
    if($this->desa_model->abaikan($data)) return;
    $this->load->model('akses_model');
    $data['tgl_ubah'] = date('Y-m-d G:i:s');
    $result1 = $this->desa_model->insert($data);
    $result2 = $this->akses_model->insert($data);
    echo "<br>Result: ".$result1." ".$result2;
  }
}
