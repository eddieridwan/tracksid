<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

// You can find dbforge usage examples here: http://ellislab.com/codeigniter/user-guide/database/forge.html


class Migration_Update_kode_desa extends CI_Migration
{
  public function __construct()
	{
    parent::__construct();
		$this->load->dbforge();
    $this->load->database();
    $this->load->model('wilayah_model');
	}

	public function up()
	{
    /*
      Update tbl_regions menggunakan daftar desa dari Permendagri No. ?? Tahun ??
      Baca daftar desa dari master-desa-permendagri-99-2099.csv
    */

    /*
      Buka file csv. Baris pertama judul kolom.
      Satu baris satu desa.

      Kolom:
      ID,KODE-PROV,KODE-KAB,KODE-KEC,KODE-DESA,PROVINSI,KABUPATEN/KOTA,KECAMATAN,DESA-KELURAHAN,STATUS ADM
      1,11,01,01,2001,ACEH,ACEH SELATAN,BAKONGAN,KEUDE BAKONGAN,DESA


      Proses setiap desa
      kalau KODE-PROV.KODE-KAB.KODE-KEC.KODE-DESA ada di kolom region_code
        - update region_name
      else
        - kalau KODE-PROV tidak ada, buat provinsi baru
        - kalau KODE-PROV.KODE-KAB tidak ada, buat kabupaten baru
            + else update nama kabupaten
        - kalau KODE-PROV.KODE-KAB.KODE-KEC tidak ada, buat kecamatan baru
            + else update nama kecamatan
        - buat desa baru KODE-PROV.KODE-KAB.KODE-KEC.KODE-DESA
    */
    $list_desa = $this->db->get('desa')->result_array();
    for ($i=0; $i < count($daftar_desa); $i++){
      $tbl_region_id = $this->wilayah_model->cek_baku($desa);
      if (!empty($tbl_region_id)) {
        $this->db->where('id',$tbl_region_id)->update('tbl_regions',array('desa_id'=>$desa['id']));
      }
    }
  }

	public function down()
	{
    // Tidak bisa mundur; bisa restore deari backup;
  }

}
/* End of file */
