<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Track extends CI_Controller {

  function __construct(){
    parent::__construct();
    session_start();
  }

  public function index()
  {
    $this->load->view('welcome_message');
  }

  public function desa(){
    $_SESSION['seq'] = 1;
    $this->load->model('desa_model');
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    $result = $this->desa_model->insert();
    echo "Result: ".$result;
  }
}
