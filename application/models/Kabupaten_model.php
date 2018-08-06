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

  /*
    Contoh hasil:
    region_code   nama_kabupaten  nama_provinsi jml_desa
    11.08         ACEH UTARA      Aceh          852
    11.09         SIMEULUE        Aceh          138
  */
  public function belum_ada_desa()
  {
    $sql = "
      SELECT a.region_code, a.region_name as nama_kabupaten, c.region_name as nama_provinsi, b.jml_desa from

        (SELECT region_code, region_name FROM `tbl_regions` t
          left join desa d on t.region_name = d.nama_kabupaten
          where length(region_code) = 5 and region_name not like 'kota %'
          and d.id is null) a

        left join

        (SELECT left(region_code, 5) as kabupaten_code, left(region_code, 2) as provinsi_code, count(*) as jml_desa from tbl_regions
          where char_length(region_code) = 13
          group by kabupaten_code) b

        on a.region_code = b.kabupaten_code

        left join

        tbl_regions c on c.region_code = b.provinsi_code

      order by a.region_code
    ";
    $data = $this->db->query($sql)->result();
    return $data;
  }

}