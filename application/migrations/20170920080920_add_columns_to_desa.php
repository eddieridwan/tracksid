<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

// You can find dbforge usage examples here: http://ellislab.com/codeigniter/user-guide/database/forge.html


class Migration_Add_columns_to_desa extends CI_Migration
{
  public function __construct()
	{
    parent::__construct();
		$this->load->dbforge();
    $this->load->database();
	}

	public function up()
	{
    $fields = array(
      'tgl_rekam TIMESTAMP NOT NULL',
      'jenis' => array(
        'type'=>'tinyint',
        'constraint' => 3,
        'default' => 1
      )
    );
    $this->dbforge->add_column('desa',$fields);
    $this->_isi_tgl_rekam();
  }

	public function down()
	{
    $this->dbforge->drop_column('desa', 'tgl_rekam');
    $this->dbforge->drop_column('desa', 'jenis');
  }

  private function _isi_tgl_rekam()
  {
    $list_desa = $this->db->select('id')->get('desa')->result_array();
    foreach ($list_desa as $desa)
    {
      $tgl_rekam = $this->db->select('tgl')->where('desa_id', $desa['id'])->order_by('tgl ASC')->limit(1)->get('akses')->row()->tgl;
      $this->db->where('id', $desa['id'])->update('desa', array('tgl_rekam' => $tgl_rekam));
    }
  }
}
/* End of file '20170920080920_create_users_table' */
/* Location: .//Users/eddie/projects/personal/vagrant-php-box/sites/html/tracksid/application/migrations/20170920080920_create_users_table.php */
