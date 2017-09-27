<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

  define("PROVINSI", serialize(array(
        'Aceh',
        'Sumatera Utara',
        'Sumatera Barat',
        'Riau',
        'Jambi',
        'Sumatera Selatan',
        'Bengkulu',
        'Lampung',
        'Kepulauan Bangka Belitung',
        'Kepulauan Riau',
        'DKI Jakarta',
        'Jawa Barat',
        'Jawa Tengah',
        'DI Yogyakarta',
        'Jawa Timur',
        'Banten',
        'Bali',
        'Nusa Tenggara Barat',
        'Nusa Tenggara Timur',
        'Kalimantan Barat',
        'Kalimantan Tengah',
        'Kalimantan Selatan',
        'Kalimantan Timur',
        'Kalimantan Utara',
        'Sulawesi Utara',
        'Sulawesi Tengah',
        'Sulawesi Selatan',
        'Sulawesi Tenggara',
        'Gorontalo',
        'Sulawesi Barat',
        'Maluku',
        'Maluku Utara',
        'Papua',
        'Papua Barat'
      )));

class Provinsi_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
    // Konversi nama provinsi tersimpan di tabel desa
    $this->konversi = array(
      "ntb" => "Nusa Tenggara Barat",
      "ntt" => "Nusa Tenggara Timur",
      "daerah istimewa yogyakarta" => "DI Yogyakarta",
      "diy" => "DI Yogyakarta",
      "yogyakarta" => "DI Yogyakarta",
      "jabar" => "Jawa Barat",
      "jawabarat" => "Jawa Barat",
      "jateng" => "Jawa Tengah",
      "jatim" => "Jawa Timur",
      "jatimi" => "Jawa Timur",
      "jawa timu" => "Jawa Timur",
      "nad" => "Aceh",
      "kalimatnan barat" => "Kalimantan Barat",
      "sulawesi teanggara" => "Sulawesi Tenggara"
    );
    $this->provinsi_baku = unserialize(PROVINSI);
  }

  function nama_baku($nama)
  {
    $nama_provinsi = strtolower($nama);
    if(isset($this->konversi[$nama_provinsi]))
      return $this->konversi[$nama_provinsi];
    else
      return $nama;
  }

  function cek_baku($nama)
  {
    return in_array(strtolower($nama), array_map('strtolower', $this->provinsi_baku));
  }

  function list_data()
  {
    $data = $this->db->select('*')->from('provinsi')
      ->order_by('nama')->get()->result_array();
    return $data;
  }
}