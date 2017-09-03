<?php

class Akses_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->load->database();
    $this->load->library('user_agent');
  }

  public function insert($data){
    // $data['id'] adalah desa_id, diambil di Desa_model->insert
    $desa_id = $data['id'];

    // Masalah dengan auto_increment meloncat. Paksa supaya berurutan.
    // https://ubuntuforums.org/showthread.php?t=2086550
    // PERHATIAN: insert ke tabel akses memasukkan dua baris duplikat, sehingga
    // perlu dibuatkan index unik (perlu dicari sebabnya -- mungkin bug di mysql atau codeigniter)
    $sql = "ALTER TABLE akses AUTO_INCREMENT = 1";
    $this->db->query($sql);
    $akses = array();
    $akses['desa_id'] = $desa_id;
    $akses['url_referrer'] = $data['url'];
    $akses['request_uri'] = $_SERVER['REQUEST_URI'];
    $akses['client_ip'] = get_client_ip_server();
    $akses['external_ip'] = $data['external_ip'];
    $akses['opensid_version'] = (isset($data['version']) ? $data['version'] : '1.9');
    $akses['tgl'] = $data['tgl_ubah'];
    $out2 = $this->db->insert('akses',$akses);
    return "akses: ".$out2;
  }

}
?>