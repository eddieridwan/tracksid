<?php

class Desa_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->load->database();
  }

  public function insert(){
    $data = $_POST;
    $data['tgl_ubah'] = date('Y-m-d H:i:s',time());

    $sql = $this->db->set($data)->get_compiled_insert('desa');
    $sql .= "
      ON DUPLICATE KEY UPDATE
        kode_desa = VALUES(kode_desa),
        kode_pos = VALUES(kode_pos),
        kode_kecamatan = VALUES(kode_kecamatan),
        kode_kabupaten = VALUES(kode_kabupaten),
        kode_provinsi = VALUES(kode_provinsi),
        url = VALUES(url),
        lat = VALUES(lat),
        lng = VALUES(lng),
        alamat_kantor = VALUES(alamat_kantor),
        tgl_ubah = VALUES(tgl_ubah);
      ";

    $out = $this->db->query($sql);
    return $out;
  }

}
?>