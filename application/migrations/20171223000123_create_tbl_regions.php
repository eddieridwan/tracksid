<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

// You can find dbforge usage examples here: http://ellislab.com/codeigniter/user-guide/database/forge.html


class Migration_Create_tbl_regions extends CI_Migration
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
    $sql = file_get_contents(FCPATH."application/migrations/tbl_regions.sql");

    /*
    https://stackoverflow.com/questions/25147099/how-i-can-execute-a-sql-script-with-codeigniter-and-pass-data-to-it
    CI migration only allows you to run one statement at a time.
    */

    $sqls = explode(';', $sql);
    array_pop($sqls);

    foreach($sqls as $statement){
      $statment = $statement . ";";
      $this->db->query($statement);
    }
    $this->db->close();
    $this->load->database();

    /*
    Cek setiap desa, dan ubah jenis menjadi '2' jika nama wilayahnya
    tidak ada di tbl_regions
    */
    $list_desa = $this->db->get('desa')->result_array();
    foreach ($list_desa as $desa){
      $betul_desa = $this->wilayah_model->cek_baku($desa);
      if (!$betul_desa){
        $this->db->where('id',$desa['id'])->update('desa',array('jenis'=>2));
      }
    }
  }

	public function down()
	{
    $this->dbforge->drop_table('tbl_regions',TRUE);
  }

}
/* End of file '20171223000123_create_tbl_regions' */
/* Location: .//Users/eddie/projects/personal/vagrant-php-box/sites/html/tracksid/application/migrations/20171223000123_create_tbl_regions.php */
