<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wilayah extends Public_Controller {

  function __construct(){
    parent::__construct();
    session_start();
    $this->load->model('desa_model');
    $this->load->model('wilayah_model');
    $this->load->model('kabupaten_model');
    $this->load->helper('url');
  }

  public function index($offset=0)
  {
    $this->load->helper('form');
    $list_kab = $this->kabupaten_model->list_nama();
    $kab[''] = 'Semua kabupaten';
    foreach ($list_kab as $nama_kab){
      $kab[$nama_kab['nama_kabupaten']] = $nama_kab['nama_kabupaten'];
    }
    $data['form_kab'] = form_dropdown('',$kab,'','id="kab" class="form-control"');
    $data['kab'] = $this->input->post('kab');
    $header = new stdClass();
    $header->title = "Wilayah Administratif";
    $this->load->view('header', $header);
    $this->load->view('ajax_list_wilayah',$data);
    $this->load->view('footer');
  }


  public function filter(){
    $filter = $this->input->post('filter');
    if($filter!=0)
      $_SESSION['filter']=$filter;
    else unset($_SESSION['filter']);
    redirect("/laporan");
  }

  public function ajax_list_wilayah()
  {
    $list = $this->wilayah_model->list_desa();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $desa) {
      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $desa['region_code'];
      $row[] = $desa['nama_desa'];
      $row[] = $desa['nama_kecamatan'];
      $row[] = $desa['nama_kabupaten'];
      $row[] = $desa['nama_provinsi'];
      $row[] = '';

      $data[] = $row;
    }

    $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->wilayah_model->count_all_desa(),
            "recordsFiltered" => $this->wilayah_model->count_filtered_desa(),
            "data" => $data,
        );
    //output to json format
    echo json_encode($output);
  }

}
