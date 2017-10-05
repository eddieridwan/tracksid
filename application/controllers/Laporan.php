<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends Public_Controller {

  function __construct(){
    parent::__construct();
    $this->load->model('desa_model');
    $this->load->model('kabupaten_model');
    $this->load->helper('url');
    session_start();
  }

  public function index($offset=0)
  {
    $this->load->helper('url');
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

    $data['is_local'] = $this->input->post('is_local');
    $data['kab'] = $this->input->post('kab');
    $this->load->view('ajax_list_desa', $data);
  }

  function profil_kabupaten(){
    $this->load->helper('url');
    $this->load->helper('form');
    $opt = array('' => 'Semua',
      '1' => 'Offline',
      '0' => 'Online'
    );
    $data['form_server'] = form_dropdown('',$opt,'','id="is_local" class="form-control"');
    $this->load->view('profil_kabupaten', $data);
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
    $this->load->helper('url');
    $this->load->helper('form');
    $opt = array('' => 'Semua',
      '1' => 'Offline',
      '0' => 'Online'
    );
    $data['form_server'] = form_dropdown('',$opt,'','id="is_local" class="form-control"');
    $this->load->view('profil_versi', $data);
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
      $row[] = $desa['opensid_version'];
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
      $row[] = $desa['nama_desa'];
      $row[] = $desa['nama_kecamatan'];
      $row[] = $desa['nama_kabupaten'];
      $row[] = $desa['nama_provinsi'];
      $row[] = empty($desa['web']) ? 'localhost' : $this->_show_url($desa['web']);
      $row[] = $desa['offline'];
      $row[] = $desa['online'];
      $row[] = $desa['tgl_rekam'];
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


}
