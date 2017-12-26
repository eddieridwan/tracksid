<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Untuk tabel tbl_regions yang berisi daftar semua wilayah
 */
class Wilayah_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function cek_baku($data)
  {
    /*
     * Cek nama desa dan telusuri sampai nama provinsi
     */
    // Kode desa berbentuk XX.XX.XX.XXXX , seperti 11.01.05.2019
    $hasil = $this->db->where('region_name',$data['nama_desa'])->where('char_length(region_code) = 13')->get('tbl_regions');
    if($hasil->num_rows() == 0) return false;
    // Periksa kec/kab/provinsi untuk setiap kandidat desa, karena mungkin ada lebih
    // dari satu desa dengan nama yang sama
    $kandidat_desa = $hasil->result_array();
    foreach ($kandidat_desa as $kandidat){
      // Cek kecamatan untuk desa ini
      $hasil = $this->db->where('region_code',$kandidat['parent_code'])->where('region_name',$data['nama_kecamatan'])->get('tbl_regions');
      if ($hasil->num_rows() == 0) continue;
      // Cek kabupaten untuk kecamatan ini
      $wilayah = $hasil->row_array();
      $hasil = $this->db->where('region_code',$wilayah['parent_code'])->where('region_name',$data['nama_kabupaten'])->get('tbl_regions');
      if ($hasil->num_rows() == 0) continue;
      // Cek provinsi untuk kabupaten ini
      $wilayah = $hasil->row_array();
      $hasil = $this->db->where('region_code',$wilayah['parent_code'])->where('region_name',$data['nama_provinsi'])->get('tbl_regions');
      if ($hasil->num_rows() == 0) continue;
      // Semua data wilayah cocok
      return true;
    }
    // Tidak ada yg cocok
    return false;
  }

}