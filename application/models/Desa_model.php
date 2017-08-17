<?php

class Desa_model extends CI_Model{

  var $table = 'customers';
  var $column_order = array(null, 'nama_desa','nama_kecamatan','nama_kabupaten','nama_provinsi','url_referrer','opensid_version','tgl'); //set column field database for datatable orderable
  var $column_search = array('nama_desa','nama_kecamatan','nama_kabupaten','nama_provinsi'); //set column field database for datatable searchable
  var $order = array('id' => 'asc'); // default order

  public function __construct(){
    parent::__construct();
    $this->load->database();
    $this->load->library('user_agent');
  }

  public function insert(&$data){
    $url = $data['url'];
    $data['url'] = parse_url($url, PHP_URL_HOST);
    unset($data['version']);

    // Masalah dengan auto_increment meloncat. Paksa supaya berurutan.
    // https://ubuntuforums.org/showthread.php?t=2086550
    // $sql = "ALTER TABLE desa AUTO_INCREMENT = 1";
    // $this->db->query($sql);

    $cek_desa = array(
      "nama_desa" => $data['nama_desa'],
      "nama_kecamatan" => $data['nama_kecamatan'],
      "nama_kabupaten" => $data['nama_kabupaten'],
      "nama_provinsi" => $data['nama_provinsi']
      );
    $data['id'] = $this->db->select('id')->where($cek_desa)->get('desa')->row()->id;
    if (empty($data['id'])){
      $out = $this->db->insert('desa', $data);
      $data['id'] = $this->db->insert_id();
      $this->email("Desa baru", json_encode($data));
      $hasil = "<br>Desa baru: ".$data['id'];
    } else {
      $out = $this->db->where('id',$data['id'])->update('desa',$data);
      $hasil = "<br>Desa lama: ".$data['id'];
    }
    return $hasil." ".$out;
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

  function paging($offset=0,$main_sql){

    $sql      = "SELECT COUNT(id) AS jml ".$main_sql;
    $query    = $this->db->query($sql);
    $row      = $query->row_array();
    $jml_data = $row['jml'];

    $this->load->library('pagination');
    $cfg["base_url"] = base_url() . "index.php/laporan/index";
    $cfg['page']     = $offset;
    $cfg['per_page'] = 20;
    // $cfg['per_page'] = $_SESSION['per_page'];
    $cfg['total_rows'] = $jml_data;
    $this->pagination->initialize($cfg);
    return $this->pagination;
  }

  public function list_desa($offset=0){
    $main_sql = $this->_get_main_query();
    $main_sql .= $this->filter_sql();
    $this->paging($offset, $main_sql);
    $paging_sql = ' LIMIT ' .$offset. ',' .$this->pagination->per_page;
    $sql = "SELECT * ".$main_sql;
    $sql .= $paging_sql;

    $query = $this->db->query($sql);
    $data['list_desa'] = $query->result_array();
    $data['links'] = $this->pagination->create_links();
    return $data;
  }

  /*
    Jangan rekam, jika:
    - ada kolom nama wilayah kosong
    - ada kolom wilayah yang masih merupakan contoh
  */
  public function abaikan($data){
    $abaikan = false;
    if ( empty(preg_replace("/[^a-zA-Z]/", "", $data['nama_desa']))
      OR empty(preg_replace("/[^a-zA-Z]/", "", $data['nama_kecamatan']))
      OR empty(preg_replace("/[^a-zA-Z]/", "", $data['nama_kabupaten']))
      OR empty(preg_replace("/[^a-zA-Z]/", "", $data['nama_provinsi'])) ) {
      $abaikan = true;
    }
    if (strpos($data['nama_desa'], 'Senggig1') !== FALSE OR
        strpos($data['nama_kecamatan'], 'Batulay4r') !== FALSE OR
        strpos($data['nama_kabupaten'], 'Bar4t') !== FALSE OR
        strpos($data['nama_provinsi'], 'NT13') !== FALSE
       ) {
      $abaikan = true;
    }
    return $abaikan;
  }

// ===============================

  private function _get_main_query()
  {
    $main_sql = "FROM
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
    $main_sql .= $this->_akses_query();
    return $main_sql;
  }

  private function _get_filtered_query()
  {
    $sSearch = $_POST['search']['value'];
    $filtered_query = $this->_get_main_query()."AND (nama_desa LIKE '%".$sSearch."%' or nama_kecamatan LIKE '%".$sSearch."%' or nama_kabupaten LIKE '%".$sSearch."%' or nama_provinsi LIKE '%".$sSearch."%') ";
    return $filtered_query;
  }

  // Hanya laporkan desa yang situsnya diakses dalam 3 bulan terakhir
  private function _akses_query()
  {
    $sql = " AND TIMESTAMPDIFF(MONTH, tgl_ubah, NOW()) <= 2 ";
    return $sql;
  }

  function get_datatables()
  {
      $qry = "SELECT * ".$this->_get_filtered_query();
      if(isset($_POST['order'])) // here order processing
      {
        $sort_by = $this->column_order[$_POST['order']['0']['column']];
        $sort_type = $_POST['order']['0']['dir'];
        $qry .= " ORDER BY ".$sort_by." ".$sort_type;
      }
     if($_POST['length'] != -1)
       $qry .= " LIMIT ".$_POST['start'].", ".$_POST['length'];
     $query = $this->db->query($qry);
     return $query->result_array();

  }

  function count_filtered()
  {
    $sql = "SELECT COUNT(id) AS jml ".$this->_get_filtered_query();
    $query    = $this->db->query($sql);
    $row      = $query->row_array();
    return $row['jml'];
  }

  public function count_all()
  {
    $sql = "SELECT COUNT(id) AS jml ".$this->_get_main_query();
    $query    = $this->db->query($sql);
    $row      = $query->row_array();
    return $row['jml'];
  }

  private function email($subject, $message){
    $this->load->library('email'); // Note: no $config param needed
    $this->email->from('opensid.server@gmail.com', 'OpenSID Tracker');
    $this->email->to('eddie.ridwan@gmail.com');
    $this->email->subject($subject);
    $this->email->message($message);
    // if ($this->email->send())
      echo "<br>Email desa baru: ".$message;
    // else show_error($this->email->print_debugger());
  }

}
?>