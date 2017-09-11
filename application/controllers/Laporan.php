<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

  function __construct(){
    parent::__construct();
    $this->load->model('desa_model');
    $this->load->helper('url');
    session_start();
  }

  public function index($offset=0)
  {
    // if(isset($_SESSION['filter']))
    //   $data['filter'] = $_SESSION['filter'];
    // else $data['filter'] = '';

    // $data_desa = $this->desa_model->list_desa($offset);
    // $data['list_desa'] = $data_desa['list_desa'];
    // $data['links'] = $data_desa['links'];
    // $data['offset'] = $offset;
    // $this->load->view('list_desa', $data);

    $this->load->helper('url');
    $this->load->helper('form');
    $opt = array('' => 'Semua',
      '1' => 'Offline',
      '0' => 'Online'
    );
    $data['form_server'] = form_dropdown('',$opt,'','id="is_local" class="form-control"');
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
      $row[] = ($desa['is_local'] == 0) ? '<i class="fa fa-external-link"></i> Online' : 'Offline';
      $row[] = $desa['jumlah'];
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
      $row[] = $this->_show_url($desa['url']);
      $row[] = $desa['opensid_version'];
      $row[] = $desa['tgl_ubah'];

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
