<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class track extends CI_Controller {

  public function index()
  {
    $this->load->view('welcome_message');
  }

  public function desa(){
    $this->load->model('desa_model');
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    $result = $this->desa_model->insert();
    echo $result;
  }
}
