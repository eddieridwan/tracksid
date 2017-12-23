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

    function cek_baku($nama)
  {
    // Kode kabupaten berbentuk XX.XX, seperti 11.01
    $hasil = $this->db->where('region_name',$nama)->where('char_length(region_code) = 5')->get('tbl_regions');
    return $hasil->num_rows() > 0;
  }

}