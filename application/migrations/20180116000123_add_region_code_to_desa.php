<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

// You can find dbforge usage examples here: http://ellislab.com/codeigniter/user-guide/database/forge.html


class Migration_Add_region_code_to_desa extends CI_Migration
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
      Tambah kolom desa_id di tabel tbl_regions untuk link ke desa_id
    */
    $fields = array(
      'desa_id' => array(
        'type'=> 'int',
        'constraint' => 10
      )
    );
    $this->dbforge->add_column('tbl_regions',$fields);
    /*
      Cek setiap desa; kalau baku simpan id dari table desa di tbl_region
    */
    $list_desa = $this->db->get('desa')->result_array();
    foreach ($list_desa as $desa){
      $tbl_region_id = $this->wilayah_model->cek_baku($desa);
      if (!empty($tbl_region_id)) {
        $this->db->where('id',$tbl_region_id)->update('tbl_regions',array('desa_id'=>$desa['id']));
      }
    }
  }

	public function down()
	{
    $this->dbforge->drop_column('tbl_regions','desa_id');
  }

}
/* End of file */
