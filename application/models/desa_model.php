<?php

class Desa_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->load->database();
    $this->load->library('user_agent');
  }

  public function insert($data){
    $url = $data['url'];
    $data['url'] = parse_url($url, PHP_URL_HOST);
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
    return "desa: ".$out;
  }

  private function filter_sql(){
    if(isset($_SESSION['filter'])){
      $filter = $_SESSION['filter'];
      if ($filter == 1)
        $filter_sql = " AND NOT url_referrer LIKE '%localhost%' AND NOT url_referrer LIKE '%192.168%' AND NOT url_referrer LIKE '%127.0.0.1%' AND NOT url_referrer LIKE '%/10.%'";
      else
        $filter_sql = " AND (url_referrer LIKE '%localhost%' OR url_referrer LIKE '%192.168%' OR url_referrer LIKE '%127.0.0.1%' OR url_referrer LIKE '%/10.%')";
    return $filter_sql;
    }
  }


  public function list_desa(){
    $sql = "SELECT * FROM
      (SELECT d.*,
        (SELECT url_referrer FROM akses WHERE d.id = desa_id AND url_referrer IS NOT NULL ORDER BY tgl DESC LIMIT 1) as url_referrer,
        (SELECT tgl FROM akses WHERE d.id = desa_id AND url_referrer IS NOT NULL ORDER BY tgl DESC LIMIT 1) as tgl,
        (SELECT opensid_version FROM akses WHERE d.id = desa_id AND url_referrer IS NOT NULL ORDER BY tgl DESC LIMIT 1) as opensid_version,
        (SELECT client_ip FROM akses WHERE d.id = desa_id AND url_referrer IS NOT NULL ORDER BY tgl DESC LIMIT 1) as client_ip
        FROM desa d
        WHERE NOT d.nama_provinsi = '' AND d.nama_provinsi NOT LIKE '%NT13%' AND d.nama_kabupaten NOT LIKE '%Bar4t%'
        ORDER BY d.nama_provinsi, d.nama_kabupaten, d.nama_kecamatan
      ) x
      WHERE NOT url_referrer ='' ";

    $sql .= $this->filter_sql();
    $query = $this->db->query($sql);
    $data = $query->result_array();
    return $data;
  }
}
?>