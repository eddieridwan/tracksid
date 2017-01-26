<?php

class Desa_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->load->database();
    $this->load->library('user_agent');
  }

  public function insert(){
    $data = $_POST;
    $data['tgl_ubah'] = date('Y-m-d H:i:s',time());
    (isset($data['version'] ? $version = $data['version'] : $version = '1.9');
    unset($data['version']);

    // Masalah dengan auto_increment meloncat. Paksa supaya berurutan.
    // https://ubuntuforums.org/showthread.php?t=2086550
    $sql = "ALTER TABLE desa AUTO_INCREMENT = 1";
    $this->db->query($sql);

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
    $desa_id = $this->db->insert_id();
    // Kalau update, query lagi untuk mencari desa_id
    if ($desa_id == 0) {
      $this->db->where(array(
        "nama_desa" => $data["nama_desa"],
        "nama_kecamatan" => $data["nama_kecamatan"],
        "nama_kabupaten" => $data["nama_kabupaten"],
        "nama_provinsi" => $data["nama_provinsi"],
        "ip_address" => $data["ip_address"]
      ));
      $query = $this->db->get("desa");
      $desa = $query->row_array();
      $desa_id = $desa['id'];
    }

    // Masalah dengan auto_increment meloncat. Paksa supaya berurutan.
    // https://ubuntuforums.org/showthread.php?t=2086550
    // PERHATIAN: insert ke tabel akses memasukkan dua baris duplikat, sehingga
    // perlu dibuatkan index unik (perlu dicari sebabnya -- mungkin bug di mysql atau codeigniter)
    $sql = "ALTER TABLE akses AUTO_INCREMENT = 1";
    $this->db->query($sql);
    $akses = [];
    $akses['desa_id'] = $desa_id;
    $akses['url_referrer'] = $this->agent->referrer();
    $akses['client_ip'] = get_client_ip_server();
    $akses['opensid_version'] = $version;
    $akses['tgl'] = $data['tgl_ubah'];
    $sql = $this->db->set($akses)->get_compiled_insert('akses');

    $out2 = $this->db->query($sql);
    return "desa: ".$out." akses: ".$out2;
  }

}
?>