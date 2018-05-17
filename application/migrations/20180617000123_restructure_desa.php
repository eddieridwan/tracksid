<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

// You can find dbforge usage examples here: http://ellislab.com/codeigniter/user-guide/database/forge.html


class Migration_Restructure_desa extends CI_Migration
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
      Tata ulang tabel desa:
      1. Tambahkan kolom2 record desa supaya data lokal dan hosting tersimpan semua dalam satu record desa:
          - ip
          - versi
          - tgl_rekam
          _ tgl_akses
          - url
      2. Cari semua record akses untuk desa lokal dan hosting
      2.1 Hapus semua record akses kecuali yang terkini untuk masing2 lokal dan hosting.
      2.2 Pindahkan data dari record akses terkini untuk masing2 lokal dan hosting ke record desa:
          - ip dari client_ip
          - versi dari opensid_version
          - tgl_akses dari tgl
          - url dari url_referrer
      3. Kalau ada record lokal dan hosting untuk desa yg sama:
      3.1. Untuk setiap desa, gabung data dari record lokal ke record hosting
          - tgl_rekam_lokal dari tgl_rekam
      3.2. Hapus record lokal.
      4. Kalau hanya ada record lokal untuk suatu desa, salin data:
          - tgl_rekam_lokal dari tgl_rekam
      5. Kalau ada record hosting, salin data:
          - tgl_rekam_hosting dari tgl_rekam
      6. Hapus kolom record desa:
          - ip_address
          - url
          - tgl_ubah
          - is_local
          - tgl_rekam
    */
    // Tambah kolom desa
    $this->tambahKolomDesa();

    $list_desa = $this->db->order_by('nama_provinsi, nama_kabupaten, nama_kecamatan, nama_desa, is_local DESC')->get('desa')->result_array();
    for ($i=0; $i < count($list_desa); $i++){
      $nama = $this->nama($list_desa[$i]);
      $j = $i;
      $desa_lokal = NULL;
      $desa_hosting = NULL;
      while ($nama == $this->nama($list_desa[$j])) {
        if ($list_desa[$j]['is_local']) {
          if (isset($desa_lokal))
            // Hapus duplikat
            $this->db->where('id', $list_desa[$j]['id'])->delete('desa');
          else
            $desa_lokal = $list_desa[$j];
        } else {
          if (isset($desa_hosting))
            // Hapus duplikat
            $this->db->where('id', $list_desa[$j]['id'])->delete('desa');
          else
            $desa_hosting = $list_desa[$j];
        }
        $j++;
      }
      $i = $j - 1;
      $this->salinAkses($desa_hosting, $desa_lokal);
      // $this->hapusKolomDesa();
    }
  }

  private function tambahKolomDesa()
  {
    // Tambah kolom
    $fields = array();
    if (!$this->db->field_exists('ip_lokal', 'desa')) {
      $fields['ip_lokal'] = array(
          'type' => 'varchar',
          'constraint' => 20
      );
    }
    if (!$this->db->field_exists('ip_hosting', 'desa')) {
      $fields['ip_hosting'] = array(
          'type' => 'varchar',
          'constraint' => 20
      );
    }
    if (!$this->db->field_exists('versi_lokal', 'desa')) {
      $fields['versi_lokal'] = array(
          'type' => 'varchar',
          'constraint' => 20
      );
    }
    if (!$this->db->field_exists('versi_hosting', 'desa')) {
      $fields['versi_hosting'] = array(
          'type' => 'varchar',
          'constraint' => 20
      );
    }
    if (!$this->db->field_exists('tgl_rekam_lokal', 'desa')) {
      $fields['tgl_rekam_lokal'] = array(
          'type' => 'timestamp'
      );
    }
    if (!$this->db->field_exists('tgl_rekam_hosting', 'desa')) {
      $fields['tgl_rekam_hosting'] = array(
          'type' => 'timestamp'
      );
    }
    if (!$this->db->field_exists('tgl_akses_lokal', 'desa')) {
      $fields['tgl_akses_lokal'] = array(
          'type' => 'timestamp'
      );
    }
    if (!$this->db->field_exists('tgl_akses_hosting', 'desa')) {
      $fields['tgl_akses_hosting'] = array(
          'type' => 'timestamp'
      );
    }
    if (!$this->db->field_exists('url_lokal', 'desa')) {
      $fields['url_lokal'] = array(
          'type' => 'varchar',
          'constraint' => 200
      );
    }
    if (!$this->db->field_exists('url_hosting', 'desa')) {
      $fields['url_hosting'] = array(
          'type' => 'varchar',
          'constraint' => 200
      );
    }
    $this->dbforge->add_column('desa', $fields);
  }

  private function hapusKolomDesa()
  {
    $kolom_hapus = array(
      "ip_address",
      "url",
      "tgl_ubah",
      "is_local",
      "tgl_rekam"
    );
    foreach ($kolom_hapus as $kolom) {
      if ($this->db->field_exists($kolom, 'desa'))
        $this->dbforge->drop_column('desa', $kolom);
    }
  }

  private function salinAkses($desa_hosting, $desa_lokal)
  {
    $kolom_salin = array(
      'client_ip' => 'ip',
      'opensid_version' => 'versi',
      'tgl' => 'tgl_akses',
      'url_referrer' => 'url'
    );
    if (!empty($desa_hosting)) {
      if (!empty($desa_lokal)) {
        // Salin data akses terkini lokal ke lokal di hosting
        $akses_terkini = $this->aksesTerkini($desa_lokal['id']);
        foreach ($kolom_salin as $key => $value) {
          $desa_hosting[$value.'_lokal'] = $akses_terkini[$key];
        }
        $desa_hosting['tgl_rekam_lokal'] = $desa_lokal['tgl_rekam'];
        // Hapus desa lokal
        $this->db->where('id', $desa_lokal['id'])->delete('desa');
      }
      // Salin data akses terkini hosting ke hosting di hosting
      $akses_terkini = $this->aksesTerkini($desa_hosting['id']);
      foreach ($kolom_salin as $key => $value) {
        $desa_hosting[$value.'_hosting'] = $akses_terkini[$key];
      }
      // Hapus akses lama
      $this->hapusAksesLama($akses_terkini);
      $desa_hosting['tgl_rekam_hosting'] = $desa_hosting['tgl_rekam'];
      // Simpan desa hosting
      $this->db->where('id', $desa_hosting['id'])->update('desa', $desa_hosting);

    } elseif (!empty($desa_lokal)) {
      // Hanya lokal
      // Salin data akses terkini lokal ke lokal di lokal
      $akses_terkini = $this->aksesTerkini($desa_lokal['id']);
      foreach ($kolom_salin as $key => $value) {
        $desa_lokal[$value.'_lokal'] = $akses_terkini[$key];
      }
      // Hapus akses lama
      $this->hapusAksesLama($akses_terkini);
      $desa_lokal['tgl_rekam_lokal'] = $desa_lokal['tgl_rekam'];
      // Simpan desa lokal
      $this->db->where('id', $desa_lokal['id'])->update('desa', $desa_lokal);
    }
  }

  /*
    Cari record akses terkini
  */
  private function aksesTerkini($desa_id)
  {
    $akses_terkini = $this->db
      ->where('desa_id', $desa_id)
      ->order_by('tgl DESC')
      ->limit(1)
      ->get('akses')->row_array();
    return $akses_terkini;
  }

  /*
    Hapus semua akses untuk desa, kecuali terkini
  */
  private function hapusAksesLama($akses_terkini)
  {
    $this->db
      ->where('desa_id', $akses_terkini['desa_id'])
      ->where("id <> '$akses_terkini[id]'")
      ->delete('akses');
  }

  private function nama($desa)
  {
    return $desa['nama_provinsi'].$desa['nama_kabupaten'].$desa['nama_kecamatan'].$desa['nama_desa'];
  }

	public function down()
	{
    // Tidak bisa mundur; bisa restore deari backup;
  }

}
/* End of file */
