<?php

class Desa_model extends CI_Model{

  var $table = 'desa';
  var $column_order = array(null, 'nama_desa','nama_kecamatan','nama_kabupaten','nama_provinsi','url_referrer','opensid_version','tgl'); //set column field database for datatable orderable
  var $column_order_kabupaten = array(null, 'nama_kabupaten','nama_provinsi','is_local','jumlah'); //set column field database for datatable orderable
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
    $version = $data['version'];
    $external_ip = $data['external_ip'];
    unset($data['version']);
    unset($data['external_ip']);

    // Masalah dengan auto_increment meloncat. Paksa supaya berurutan.
    // https://ubuntuforums.org/showthread.php?t=2086550
    $sql = "ALTER TABLE desa AUTO_INCREMENT = 1";
    $this->db->query($sql);
    $data['nama_provinsi'] = $this->_normalkan_spasi($data['nama_provinsi']);
    $data['nama_kabupaten'] = $this->_normalkan_spasi($data['nama_kabupaten']);
    $data['nama_kecamatan'] = $this->_normalkan_spasi($data['nama_kecamatan']);
    $data['nama_desa'] = $this->_normalkan_spasi($data['nama_desa']);
    $data['id'] = $this->_desa_baru($data);
    if (empty($data['id'])){
      $data['is_local'] = is_local($data['url']) ? '1' : '0';
      $out = $this->db->insert('desa', $data);
      $data['id'] = $this->db->insert_id();
      $this->email_github($data);
      $hasil = "<br>Desa baru: ".$data['id'];
    } else {
      $out = $this->db->where('id',$data['id'])->update('desa',$data);
      $hasil = "<br>Desa lama: ".$data['id'];
    }
    $data['version'] = $version;
    $data['external_ip'] = $external_ip;
   return $hasil." ".$out;
  }

  private function _normalkan_spasi($str){
    return trim(preg_replace('/\s+/', ' ', $str));
  }

  private function _desa_baru($data){
    /*
      Dibuat entri di tabel desa untuk setiap kombinasi:
        1. Nama desa, nama kec, nama kab, nama prov, ip_address jika online (is_local = FALSE)
        2. Nama desa, nama kec, nama kab, nama prov, is_local = TRUE
      Cara ini tidak menjamin setiap entri mewakili desa yang sebenarnya, karena bisa saja:
      a. Database contoh/pelatihan/demo menggunakan nama desa sebenarnya di localhost, sehingga akan tergabung dengan entri database offline untuk desa tersebut
      b. Database contoh/pelatihan/demo menggunakan nama desa sebenarnya di online, sehingga akan mempunyai entri terpisah, dan akan tampak seperti ada lebih dari satu database online untuk desa tersebut
      c. Database contoh/pelatihan/demo menggunakan nama desa yang sebenarnya tidak ada, sehingga akan tampak adanya desa yang tidak benar
      d. Nama desa/kec/kab/prov diubah (mis karena salah ejaan) akan tersimpan sebagai desa terpisah, sehingga akan tampak beberapa entri untuk desa tersebut dengan ejaan yang berbeda
      e. Database desa pindah hosting dengan ip_address yang berbeda, sehingga akan tampak lebih dari satu database online untuk desa tersebut.

      Untuk kasus (d) dan (e), entri yang salah/kadaluarsa akan terfilter dari laporan jika tidak diakses lagi dalam masa 2 bulan.

      TODO: Untuk kasus (b) dan (c), nanti akan difilter dari laporan dengan mengidentifikasi ip_address atau url (jika online) yang merupakan server contoh/pelatihan/demo.

      TODO: Jangka panjang, akan digunakan daftar desa baku, sehingga tidak akan ada entri untuk desa yang sebenarnya tidak ada atau tertulis salah.
    */
    $cek_desa = array(
      "nama_desa" => strtolower($data['nama_desa']),
      "nama_kecamatan" => strtolower($data['nama_kecamatan']),
      "nama_kabupaten" => strtolower($data['nama_kabupaten']),
      "nama_provinsi" => strtolower($data['nama_provinsi'])
      );
    if (is_local($data['url'])) {
      $cek_desa = array_merge($cek_desa, array("is_local" => '1'));
    } else
      $cek_desa = array_merge($cek_desa, array("ip_address" => $data['ip_address'], "is_local" => '0'));
    $id = $this->db->select('id')->where($cek_desa)->get('desa')->row()->id;
    return $id;
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
    - ada kolom wilayah yang masih merupakan contoh (berisi karakter non-alpha)
  */
  public function abaikan($data){
    $abaikan = false;
    $desa = trim($data['nama_desa']);
    $kec = trim($data['nama_kecamatan']);
    $kab = trim($data['nama_kabupaten']);
    $prov = trim($data['nama_provinsi']);
    if ( empty($desa) OR empty($kec) OR empty($kab) OR empty($prov) ) {
      $abaikan = true;
    } elseif (preg_match('/[^a-zA-Z\s:]/', $desa) OR
        preg_match('/[^a-zA-Z\s:]/', $kec) OR
        preg_match('/[^a-zA-Z\s:]/', $kab) OR
        preg_match('/[^a-zA-Z\s:]/', $prov)
       ) {
      $abaikan = true;
    }
    if (preg_match('/sid.bangundesa.info|demosid.opensid.info/', $data['url']))
      $abaikan = true;
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
    $filtered_query = $this->_get_main_query();
    if($this->input->post('is_local') !== null AND $this->input->post('is_local') !== '')
    {
      $filtered_query .= " AND is_local = ".$this->input->post('is_local');
    }
    $sSearch = $_POST['search']['value'];
    $filtered_query .= " AND (nama_desa LIKE '%".$sSearch."%' or nama_kecamatan LIKE '%".$sSearch."%' or nama_kabupaten LIKE '%".$sSearch."%' or nama_provinsi LIKE '%".$sSearch."%') ";
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

  private function _filtered_kabupaten_query(){
    $filtered_query = "1";
    if($this->input->post('is_local') !== null AND $this->input->post('is_local') !== '')
    {
      $filtered_query .= " AND is_local = ".$this->input->post('is_local');
    }
    $sSearch = $_POST['search']['value'];
    $filtered_query .= " AND (nama_kabupaten LIKE '%".$sSearch."%' OR nama_provinsi LIKE '%".$sSearch."%')";
    return $filtered_query;
  }

  function count_filtered_kabupaten()
  {
    $jumlah = $this->db->select('count(DISTINCT nama_kabupaten, nama_provinsi, is_local) as jumlah')->from('desa')->where($this->_filtered_kabupaten_query())->get()->row()->jumlah;
    return $jumlah;
  }

  public function count_all_kabupaten()
  {
    $jumlah = $this->db->select('count(DISTINCT nama_kabupaten, nama_provinsi, is_local) as jumlah')->from('desa')->get()->row()->jumlah;
    return $jumlah;
  }

  function profil_kabupaten(){
    $this->db->select('nama_provinsi, nama_kabupaten, is_local, count(*) as jumlah')->from('desa')->where($this->_filtered_kabupaten_query());

    if(isset($_POST['order'])) // here order processing
    {
      $sort_by = $this->column_order_kabupaten[$_POST['order']['0']['column']];
      $sort_type = $_POST['order']['0']['dir'];
      $this->db->order_by($sort_by." ".$sort_type);
    }
    if($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);

    $data = $this->db->group_by(array('nama_provinsi', 'nama_kabupaten', 'is_local'))->get()->result_array();
    return $data;
  }

  private function email($subject, $message, $to="eddie.ridwan@gmail.com"){
    $this->load->library('email'); // Note: no $config param needed
    $this->email->from('opensid.server@gmail.com', 'OpenSID Tracker');
    $this->email->to($to);
    $this->email->subject($subject);
    $this->email->message($message);
    if ($this->email->send())
      echo "<br>Email desa baru: ".$message;
    else show_error($this->email->print_debugger());
  }

  private function email_github($data){
    $message =
      "Desa: ".$data['nama_desa']."\r\n".
      "Kecamatan: ".$data['nama_kecamatan']."\r\n".
      "Kabupaten: ".$data['nama_kabupaten']."\r\n".
      "Provinsi: ".$data['nama_provinsi']."\r\n".
      "Website: "."http://".$data['url']."\r\n";
    $this->load->library('email'); // Note: no $config param needed
    $this->email->from('opensid.server@gmail.com', 'Desa OpenSID');
    $this->email->to("reply+0003cedb28a15af7509fdc8d2eea2ad81330dadac78af6e492cf0000000115b3c7f892a169ce0f03d3ca@reply.github.com");
    $this->email->subject("Desa Pengguna OpenSID");
    $this->email->message($message);
    if ($this->email->send())
      echo "<br>Email desa baru ke Github: ".$message;
    else show_error($this->email->print_debugger());
  }
}
?>