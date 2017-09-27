<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

// You can find dbforge usage examples here: http://ellislab.com/codeigniter/user-guide/database/forge.html


class Migration_Create_provinsi_table extends CI_Migration
{
    public function __construct()
  	{
	    parent::__construct();
        $this->load->dbforge();
        $this->load->model('provinsi_model');
  	}

  	public function up()
  	{
	    $fields = array(
        'kode' => array(
            'type'=>'tinyint',
            'constraint'=>2,
            'unsigned'=>TRUE),
        'nama' => array(
            'type'=>'varchar',
            'constraint'=>100)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('kode', TRUE);
        $this->dbforge->create_table('provinsi', TRUE);
        $this->_isi_tabel_provinsi();
        $this->_ubah_provinsi_desa();
    }

  	public function down()
  	{
	    $this->dbforge->drop_table('provinsi', TRUE);
        $this->db->update('desa', array('jenis' => 1));
    }

    private function _isi_tabel_provinsi()
    {
      $query = "
        INSERT INTO `provinsi` (`kode`, `nama`) VALUES
        (11,  'Aceh'),
        (12,  'Sumatera Utara'),
        (13,  'Sumatera Barat'),
        (14,  'Riau'),
        (15,  'Jambi'),
        (16,  'Sumatera Selatan'),
        (17,  'Bengkulu'),
        (18,  'Lampung'),
        (19,  'Kepulauan Bangka Belitung'),
        (21,  'Kepulauan Riau'),
        (31,  'DKI Jakarta'),
        (32,  'Jawa Barat'),
        (33,  'Jawa Tengah'),
        (34,  'DI Yogyakarta'),
        (35,  'Jawa Timur'),
        (36,  'Banten'),
        (51,  'Bali'),
        (52,  'Nusa Tenggara Barat'),
        (53,  'Nusa Tenggara Timur'),
        (61,  'Kalimantan Barat'),
        (62,  'Kalimantan Tengah'),
        (63,  'Kalimantan Selatan'),
        (64,  'Kalimantan Timur'),
        (65,  'Kalimantan Utara'),
        (71,  'Sulawesi Utara'),
        (72,  'Sulawesi Tengah'),
        (73,  'Sulawesi Selatan'),
        (74,  'Sulawesi Tenggara'),
        (75,  'Gorontalo'),
        (76,  'Sulawesi Barat'),
        (81,  'Maluku'),
        (82,  'Maluku Utara'),
        (91,  'Papua'),
        (92,  'Papua Barat')
      ";
      $this->db->query($query);
    }

    private function _ubah_provinsi_desa()
    {
        // Konversi nama provinsi tersimpan di tabel desa
        $konversi = $this->provinsi_model->konversi;
        // Dapat daftar nama provinsi tersimpan yg unik
        // Konversi setiap nama unik
        $list_provinsi = $this->db->distinct()->select('nama_provinsi')->get('desa')->result_array();
        foreach ($list_provinsi as $provinsi)
        {
          $nama_provinsi = strtolower($provinsi['nama_provinsi']);
          if(isset($konversi[$nama_provinsi]))
          {
            $this->db->where('nama_provinsi', $provinsi['nama_provinsi'])->update('desa', array('nama_provinsi' => $konversi[$nama_provinsi]));
          } else
          {
            // Cek setiap desa, apakah nama provinsi di daftar baku
            // Jika tidak, ubah jenis menjadi 2
            if ($this->provinsi_model->cek_baku($nama_provinsi) === FALSE)
            {
              $this->db->where('nama_provinsi', $provinsi['nama_provinsi'])->update('desa', array('jenis' => 2));
            }
          }

        }
    }
}
/* End of file '20170926074831_create_provinsi_table' */
/* Location: .//Users/eddie/projects/personal/vagrant-php-box/sites/html/tracksid/application/migrations/20170926074831_create_provinsi_table.php */
