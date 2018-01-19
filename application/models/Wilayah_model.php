<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Untuk tabel tbl_regions yang berisi daftar semua wilayah
 */
class Wilayah_model extends CI_Model
{

  var $column_order = array(null, 'region_code','nama_desa','nama_kecamatan','nama_kabupaten','nama_provinsi', 'desa_id'); //set column field database for datatable orderable
  var $column_search = array('nama_desa','nama_kecamatan','nama_kabupaten','nama_provinsi'); //set column field database for datatable searchable

  function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  /*
    @return false atau tbl_regions.id
  */
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
      // Kembalikan id tbl_regions
      return $kandidat['id'];
    }
    // Tidak ada yg cocok
    return false;
  }

  private function _get_main_query_desa()
  {

    $main_sql = "FROM
      (select t.region_code as region_code,t.region_name as nama_desa,
        (select r.region_name from tbl_regions r where r.region_code = substr(t.region_code,1,8)) as nama_kecamatan,
        (select r.region_name from tbl_regions r where r.region_code = substr(t.region_code,1,5)) as nama_kabupaten,
        (select r.region_name from tbl_regions r where r.region_code = substr(t.region_code,1,2)) as nama_provinsi,
        t.desa_id
      FROM
      (select x.* from tbl_regions x
        where char_length(x.region_code) = 13) t) z
        WHERE 1=1
    ";
    return $main_sql;
  }

  private function _get_filtered_query_desa()
  {
    $filtered_query = $this->_get_main_query_desa();
    $kab = $this->input->post('kab');
    if(!empty($kab)) {
        $filtered_query .= " AND nama_kabupaten = '{$kab}'";
    }
    $sSearch = $_POST['search']['value'];
    $filtered_query .= " AND (nama_desa LIKE '%".$sSearch."%' or nama_kecamatan LIKE '%".$sSearch."%' or nama_kabupaten LIKE '%".$sSearch."%' or nama_provinsi LIKE '%".$sSearch."%') ";
    return $filtered_query;
  }

  function list_desa()
  {
    $select = "SELECT * ";
    $qry = $select.$this->_get_filtered_query_desa();
    if(isset($_POST['order'])) // here order processing
    {
      $sort_by = $this->column_order[$_POST['order']['0']['column']];
      $sort_type = $_POST['order']['0']['dir'];
      $qry .= " ORDER BY ".$sort_by." ".$sort_type;
    } else {
      $qry .= " ORDER BY region_code";
    }
    if($_POST['length'] != -1)
      $qry .= " LIMIT ".$_POST['start'].", ".$_POST['length'];
    $query = $this->db->query($qry);
    return $query->result_array();
  }

  function count_filtered_desa()
  {
    $sql = "SELECT COUNT(*) AS jml ".$this->_get_filtered_query_desa();
    $jml = $this->db->query($sql)->row()->jml;
    return $jml;
  }

  public function count_all_desa()
  {
    $sql = "SELECT COUNT(*) AS jml ".$this->_get_main_query_desa();
    $jml = $this->db->query($sql)->row()->jml;
    return $jml;
  }


}