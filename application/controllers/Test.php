<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

  function __construct(){
    parent::__construct();
    session_start();
    $this->load->helper('url');
    if (!admin_logged_in()) redirect('login');
  }

	public function index()
	{
    $header = new stdClass();
    $header->title = "Desa Pengguna OpenSID";
    $this->load->view('header', $header);
    $data['test_data'] = $this->test_data();
    $this->load->view('test/desa_form', $data);
    $this->load->view('footer');
	}

	private function test_data()
	{
		$data = array();
		$data[] = array("nama" => "nama_desa", "nilai" => "Senggigitest");
		$data[] = array("nama" => "kode_desa", "nilai" => "10");
		$data[] = array("nama" => "kode_pos", "nilai" => "112300");
		$data[] = array("nama" => "nama_kecamatan", "nilai" => "Batulayar");
		$data[] = array("nama" => "kode_kecamatan", "nilai" => "20");
		$data[] = array("nama" => "nama_kabupaten", "nilai" => "Lombok Barat");
		$data[] = array("nama" => "kode_kabupaten", "nilai" => "30");
		$data[] = array("nama" => "nama_provinsi", "nilai" => "Nusa Tenggara Barat");
		$data[] = array("nama" => "kode_provinsi", "nilai" => "40");
		$data[] = array("nama" => "lat", "nilai" => "-8.488005310891758");
		$data[] = array("nama" => "lng", "nilai" => "116.0406072534065");
		$data[] = array("nama" => "alamat_kantor", "nilai" => "Jl Sudirman Raya");
		$data[] = array("nama" => "url", "nilai" => "http://localhost:8080/OpenSID/index.php/siteman");
		$data[] = array("nama" => "ip_address", "nilai" => "10.0.2.15");
		$data[] = array("nama" => "external_ip", "nilai" => "121.45.244.95");
		$data[] = array("nama" => "version", "nilai" => "pasca-2.12");
		return $data;
	}
}
