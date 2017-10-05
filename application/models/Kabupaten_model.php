<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kabupaten_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }

  function list_nama()
  {
    $data = $this->db->distinct()->select('nama_kabupaten')->from('desa')
      ->order_by('nama_kabupaten')->get()->result_array();
    return $data;
  }
}