<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends Public_Controller {

  function __construct(){
    parent::__construct();
    session_start();
    $this->load->model('desa_model');
    $this->load->model('kabupaten_model');
    $this->load->helper('url');
  }

  public function index($is_local='')
  {
    $this->load->helper('form');
    $opt = array('' => 'Semua',
      '1' => 'Offline',
      '0' => 'Online'
    );
    $data['form_server'] = form_dropdown('',$opt,'','id="is_local" class="form-control"');
    $list_kab = $this->kabupaten_model->list_nama();
    $kab[''] = 'Semua kabupaten';
    foreach ($list_kab as $nama_kab){
      $kab[$nama_kab['nama_kabupaten']] = $nama_kab['nama_kabupaten'];
    }
    $data['form_kab'] = form_dropdown('',$kab,'','id="kab" class="form-control"');
    $akses = array('' => 'Semua',
      '2' => 'Sejak dua bulan yang lalu',
      '1' => 'Sebelum dua bulan yang lalu',
      '3' => 'Sebelum empat bulan yang lalu'
    );
    $data['form_akses'] = form_dropdown('',$akses,'','id="akses" class="form-control"');

    $data['is_local'] = $is_local;
    $data['kab'] = $this->input->post('kab');

    $header = new stdClass();
    $header->title = "Desa Pengguna OpenSID";
    $this->load->view('dashboard/header', $header);
    $this->load->view('dashboard/nav');
    $this->load->view('ajax_list_desa', $data);
    $this->load->view('footer');
  }

  function profil_kabupaten($is_local=''){
    $this->load->helper('form');
    $opt = array('' => 'Semua',
      '1' => 'Offline',
      '0' => 'Online'
    );
    $data['form_server'] = form_dropdown('',$opt,'','id="is_local" class="form-control"');
    $data['is_local'] = $is_local;
    $header = new stdClass();
    $header->title = 'Kabupaten Pengguna OpenSID';
    $this->load->view('dashboard/header', $header);
    $this->load->view('dashboard/nav');
    $this->load->view('profil_kabupaten', $data);
    $this->load->view('footer');
 }

  public function ajax_profil_kabupaten()
  {
    $list = $this->desa_model->profil_kabupaten();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $desa) {
      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $desa['nama_kabupaten'];
      $row[] = $desa['nama_provinsi'];
      $row[] = $this->_show_desa_kabupaten($desa['nama_kabupaten'], 1, $desa['offline']);
      $row[] = $this->_show_desa_kabupaten($desa['nama_kabupaten'], 0, $desa['online']);
      $data[] = $row;
    }
    $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->desa_model->count_all_kabupaten(),
            "recordsFiltered" => $this->desa_model->count_filtered_kabupaten(),
            "data" => $data,
        );
    //output to json format
    echo json_encode($output);
  }

  function _show_desa_kabupaten($kab, $jenis_server, $jml){
    $html = '<a href="javascript:;" onclick="goto_desa(' . "'$kab',$jenis_server);" . '">' . $jml . '</a>';
    return $html;
  }

  function profil_versi(){
    $this->load->helper('form');
    $opt = array('' => 'Semua',
      '1' => 'Offline',
      '0' => 'Online'
    );
    $data['form_server'] = form_dropdown('',$opt,'','id="is_local" class="form-control"');
    $header = new stdClass();
    $header->title = 'Versi OpenSID';
    $this->load->view('dashboard/header', $header);
    $this->load->view('dashboard/nav');
    $this->load->view('profil_versi', $data);
    $this->load->view('footer');
  }

  public function ajax_profil_versi()
  {
    $list = $this->desa_model->profil_versi();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $desa) {
      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $desa['versi'];
      $row[] = $desa['offline'];
      $row[] = $desa['online'];
      $data[] = $row;
    }

    $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->desa_model->count_all_versi(),
            "recordsFiltered" => $this->desa_model->count_filtered_versi(),
            "data" => $data,
        );
    //output to json format
    echo json_encode($output);
  }

  public function test_email(){
    $this->load->library('email'); // Note: no $config param needed
    $this->email->from('opensid.server@gmail.com', 'OpenSID Tracker');
    $this->email->to('eddie.ridwan@gmail.com');
    $this->email->subject('Test email from CI and Gmail');
    $this->email->message('This is a test.');
    echo "before send ".config_item('smtp_user')." <br>";
    if ($this->email->send())
      echo "success ======<br>";
    else show_error($this->email->print_debugger());
    echo "after send";
  }

  public function filter(){
    $filter = $this->input->post('filter');
    if($filter!=0)
      $_SESSION['filter']=$filter;
    else unset($_SESSION['filter']);
    redirect("/laporan");
  }

  public function ajax_list_desa()
  {
    $list = $this->desa_model->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $desa) {
      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $this->_aksi($desa['id']);
      $row[] = $desa['nama_desa'];
      $row[] = $desa['nama_kecamatan'];
      $row[] = $desa['nama_kabupaten'];
      $row[] = $desa['nama_provinsi'];
      $row[] = empty($desa['url_hosting']) ? 'localhost' : $this->_show_url($desa['url_hosting']);
      $row[] = $desa['versi_lokal'];
      $row[] = $desa['versi_hosting'];
      $row[] = $desa['tgl_akses'];
      $row[] = $desa['jenis']; // jenis tidak ditampilkan

      $data[] = $row;
    }

    $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->desa_model->count_all(),
            "recordsFiltered" => $this->desa_model->count_filtered(),
            "data" => $data,
        );
    //output to json format
    echo json_encode($output);
  }

  private function _show_url($url) {
    if (empty($url)) return '';
    elseif (is_local($url)) return $url;
    else {
      $html = "<a href='http://$url' target='_blank'>$url</a>";
      return $html;
    }
  }

  private function _aksi($desa_id){
    $str = '
      <a class="btn btn-default" data-toggle="confirmation" data-href="'.site_url("desa/hapus/$desa_id").'"><span class="fa fa-trash"></span></a>
    ';
    return $str;
  }
}
