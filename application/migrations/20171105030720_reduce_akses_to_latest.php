<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

// You can find dbforge usage examples here: http://ellislab.com/codeigniter/user-guide/database/forge.html


class Migration_Reduce_akses_to_latest extends CI_Migration
{
  public function __construct()
	{
    parent::__construct();
		$this->load->dbforge();
    $this->load->database();
	}

	public function up()
	{
    $list_desa = $this->db->select('id')->get('desa')->result_array();
    foreach ($list_desa as $desa)
    {
      // Hapus semua akses kecuali yang terakhir
      $tgl_terakhir = $this->db->select('tgl')->where('desa_id', $desa['id'])->order_by('tgl DESC')->limit(1)->get('akses')->row()->tgl;
      $this->db->where('desa_id', $desa['id'])->where("tgl <> '$tgl_terakhir'")->delete('akses');
    }
  }

	public function down()
	{
    // Cannot be reverted
  }

}
/* End of file '20171105030720_reduce_akses_to_latest' */
/* Location: .//Users/eddie/projects/personal/vagrant-php-box/sites/html/tracksid/application/migrations/20171105030720_reduce_akses_to_latest.php */
