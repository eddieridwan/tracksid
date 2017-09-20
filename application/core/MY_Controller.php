<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
  public $data = array();
  function __construct() {
    parent::__construct();
  }
}

class Admin_Controller extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    echo 'This is from admin controller';
  }
}

class Public_Controller extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
  }
}
