<?php

class Desa_model extends CI_Model {

	var $table = 'desa';
	var $column_order = array(null, null, 'nama_desa','nama_kecamatan','nama_kabupaten','nama_provinsi','url_hosting','versi_lokal','versi_hosting','tgl_akses'); //set column field database for datatable orderable
	var $column_order_kabupaten = array(null, 'nama_kabupaten','nama_provinsi','offline','online'); //set column field database for datatable orderable
	var $column_order_versi = array(null, 'versi','offline','online'); //set column field database for datatable orderable
	var $column_search = array('nama_desa','nama_kecamatan','nama_kabupaten','nama_provinsi'); //set column field database for datatable searchable
	var $order = array('id' => 'asc'); // default order

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('user_agent');
		$this->load->model('provinsi_model');
		$this->load->model('wilayah_model');
	}

		/**
		 * @param 	array 	$data
		 * @return 	string 	$hasil
		 * @return 	array   $data
		 */
		public function insert(&$data)
		{
				$desa = $this->siapkanData($data);
				$desa_id = $this->isDesaBaru($desa);
				if (empty($desa_id))
				{
						$desa_id = $this->insertDesa($desa);
						$hasil = "<br>Desa baru: ".$desa_id;
				}
				else
				{
						$this->db->where('id', $desa_id)->update('desa', $desa);
						$hasil = "<br>Desa lama: ".$desa_id;
				}
				$data['id'] = $desa_id; // Kembalikan untuk tabel akses
				return $hasil;
		}

		private function insertDesa($data)
		{
				// Cek apakah nama desa ada di Permen
				$tbl_region_id = $this->wilayah_model->cek_baku($data);
				if (empty($tbl_region_id)) {
					$data['jenis'] = 2; // jenis = 2 jika nama desa tidak baku
				}
				// Masalah dengan auto_increment meloncat. Paksa supaya berurutan.
				// https://ubuntuforums.org/showthread.php?t=2086550
				$sql = "ALTER TABLE desa AUTO_INCREMENT = 1";
				$this->db->query($sql);
				// Desa baru, hanya satu tgl_akses terisi
				if (isset($data['tgl_akses_lokal']))
					$data['tgl_rekam_lokal'] = $data['tgl_akses_lokal'];
				else
					$data['tgl_rekam_hosting'] = $data['tgl_akses_hosting'];
				$this->db->insert('desa', $data);
				$desa_id = $this->db->insert_id();
				$this->notifikasi($data);
				// Kalau desa baku simpan id dari tabel desa di tbl_region
				if (!empty($tbl_region_id)) {
					$this->db->where('id',$tbl_region_id)->update('tbl_regions', array('desa_id' => $desa_id));
				}
				return $desa_id;
		}

		private function siapkanData($data)
		{
				$desa = array();
				$desa['nama_desa']      = $data['nama_desa'];
				$desa['kode_desa']      = $data['kode_desa'];
				$desa['kode_pos']       = $data['kode_pos'];
				$desa['nama_kecamatan'] = $data['nama_kecamatan'];
				$desa['kode_kecamatan'] = $data['kode_kecamatan'];
				$desa['nama_kabupaten'] = $data['nama_kabupaten'];
				$desa['kode_kabupaten'] = $data['kode_kabupaten'];
				$desa['nama_provinsi']  = $data['nama_provinsi'];
				$desa['kode_provinsi']  = $data['kode_provinsi'];
				$desa['lat']            = $data['lat'];
				$desa['lng']            = $data['lng'];
				$desa['alamat_kantor']  = $data['alamat_kantor'];
				// Cek lokal
				$is_local = (is_local($data['url']) or is_local($data['ip_address']));
				$jenis = ($is_local) ? '_lokal' : '_hosting';
				$desa['url'.$jenis]         = $data['url'];
				$desa['ip'.$jenis]          = $data['ip_address'];
				$desa['versi'.$jenis]       = $data['version'];
				$desa['tgl_akses'.$jenis]   = $data['tgl_ubah'];

				return $desa;
		}

		/**
		 * Desa dianggap mempunyai nama unik (desa, kecamatan, kabupaten, provinsi).
		 * Dengan demikian bisa ada beberapa record desa untuk suatu desa sebenarnya
		 * karena perbaikan nama yang mungkin dilakukan berkali-kali.
		 * Record desa dengan nama lama tidak akan diupdate lagi dan bisa dihapus kalau
		 * sesudah tidak diakses dalam tenggang waktu yang ditentukan
		 */
		private function isDesaBaru($data)
		{
				$cek_desa = array(
					"nama_desa"       => strtolower($data['nama_desa']),
					"nama_kecamatan"  => strtolower($data['nama_kecamatan']),
					"nama_kabupaten"  => strtolower($data['nama_kabupaten']),
					"nama_provinsi"   => strtolower($data['nama_provinsi'])
					);
				$query = $this->db->select('id')->where($cek_desa)->get('desa');
				return ($query->num_rows() > 0) ? $query->row()->id : NULL;
		}

	/*
	 * Normalkan nama wilayah. Hilangkan sebutan wilayah.
	 */
	public function normalkanData($data)
	{
		$data['nama_provinsi'] = $this->_normalkan_spasi($data['nama_provinsi']);
		$data['nama_provinsi'] = $this->provinsi_model->nama_baku($data['nama_provinsi']);
		$data['nama_kabupaten'] = $this->_normalkan_spasi($data['nama_kabupaten']);
		$data['nama_kecamatan'] = $this->_normalkan_spasi($data['nama_kecamatan']);
		$data['nama_desa'] = $this->_normalkan_spasi($data['nama_desa']);
    $data['url'] = parse_url($data['url'], PHP_URL_HOST) . parse_url($data['url'], PHP_URL_PATH);
    $data['tgl_ubah'] = date('Y-m-d G:i:s');
    return $data;
	}

	private function _normalkan_spasi($str)
	{
		return trim(preg_replace('/^desa\s+|^nagari\s+|^kecamatan\s+|^kabupaten\s+|\s+desa\s+|\s+nagari\s+|\s+kecamatan\s+|\s+kabupaten\s+|\s+/i', ' ', $str));
	}

	/*
		Jangan rekam, jika:
		- ada kolom nama wilayah kurang dari 4 karakter
		- ada kolom wilayah yang masih merupakan contoh (berisi karakter non-alpha atau tulisan 'contoh', 'demo' atau 'sampel')
	*/
	public function abaikan($data)
	{
		$regex = '/[^a-zA-Z\s:]|contoh|demo\s+|sampel\s+/i';
		$abaikan = false;
		$desa = trim($data['nama_desa']);
		$kec = trim($data['nama_kecamatan']);
		$kab = trim($data['nama_kabupaten']);
		$prov = trim($data['nama_provinsi']);
		if ( strlen($desa)<4 OR strlen($kec)<4 OR strlen($kab)<4 OR strlen($prov)<4 ) {
			$abaikan = true;
		} elseif (preg_match($regex, $desa) OR
				preg_match($regex, $kec) OR
				preg_match($regex, $kab) OR
				preg_match($regex, $prov)
			 ) {
			$abaikan = true;
		}
		// Abaikan situs demo
		$abaikan_situs = '/'.$this->config->item('abaikan').'/';
		if (preg_match($abaikan_situs, $data['url']))
			$abaikan = true;
		return $abaikan;
	}

// ===============================

	private function _get_main_query()
	{
		$main_sql = "FROM
			desa
			WHERE 1=1
		";
		return $main_sql;
	}

	private function _get_filtered_query()
	{
		$filtered_query = $this->_get_main_query();
		if($this->input->post('is_local') !== null) {
			switch ($this->input->post('is_local')) {
				case '0':
					$filtered_query .= " AND versi_hosting <> '' ";
					break;
				case '1':
					$filtered_query .= " AND versi_lokal <> '' ";
					break;
			}
		}
		$kab = $this->input->post('kab');
		if(!empty($kab)) {
				$filtered_query .= " AND nama_kabupaten = '{$kab}'";
		}
		$akses = $this->input->post('akses');
		if(!empty($akses)) {
			$filtered_query .= $this->_akses_query($akses);
		}
		$sSearch = $_POST['search']['value'];
		$filtered_query .= " AND (nama_desa LIKE '%".$sSearch."%' or nama_kecamatan LIKE '%".$sSearch."%' or nama_kabupaten LIKE '%".$sSearch."%' or nama_provinsi LIKE '%".$sSearch."%') ";
		return $filtered_query;
	}

	/* Filter menurut tanggal akses terkahir.
	 * 1 = sebelum dua bulan yang lalu
	 * 2 = sejak dua bulan yang lalu
	 * 3 = sebelum embat bulan yang lalu
	*/
	private function _akses_query($akses)
	{
		switch ($akses) {
			case '1':
				$sql = " AND TIMESTAMPDIFF(MONTH, GREATEST(tgl_akses_lokal, tgl_akses_hosting), NOW()) > 1 ";
				break;
			case '2':
				$sql = " AND TIMESTAMPDIFF(MONTH, GREATEST(tgl_akses_lokal, tgl_akses_hosting), NOW()) <= 1 ";
				break;
			case '3':
				$sql = " AND TIMESTAMPDIFF(MONTH, GREATEST(tgl_akses_lokal, tgl_akses_hosting), NOW()) > 3 ";
				break;
			default:
				$sql = "";
				break;
		}
		return $sql;
	}

	function get_datatables()
	{
		$qry = "SELECT *, GREATEST(tgl_akses_lokal, tgl_akses_hosting) AS tgl_akses ".$this->_get_filtered_query();
		if(isset($_POST['order'])) // here order processing
		{
			$sort_by = $this->column_order[$_POST['order']['0']['column']];
			$sort_type = $_POST['order']['0']['dir'];
			$qry .= " ORDER BY ".$sort_by." ".$sort_type;
		} else {
			$qry .= " ORDER BY nama_provinsi, nama_kabupaten, nama_kecamatan, nama_desa";
		}
		if($_POST['length'] != -1)
		 $qry .= " LIMIT ".$_POST['start'].", ".$_POST['length'];
		$query = $this->db->query($qry);
		return $query->result_array();
	}

	function count_filtered()
	{
		$sql = "SELECT COUNT(*) AS jml ".$this->_get_filtered_query();
		$jml = $this->db->query($sql)->row()->jml;
		return $jml;
	}

	public function count_all()
	{
		$sql = "SELECT COUNT(*) AS jml ".$this->_get_main_query();
		$jml = $this->db->query($sql)->row()->jml;
		return $jml;
	}

	private function _filtered_kabupaten_query()
	{
		$filtered_query = $this->_main_kabupaten_query();
		if($this->input->post('is_local') !== null) {
			switch ($this->input->post('is_local')) {
				case '0':
					$filtered_query .= " AND versi_hosting <> '' ";
					break;
				case '1':
					$filtered_query .= " AND versi_lokal <> '' ";
					break;
			}
		}
		$sSearch = $_POST['search']['value'];
		$filtered_query .= " AND (nama_kabupaten LIKE '%".$sSearch."%' OR nama_provinsi LIKE '%".$sSearch."%')";
		$filtered_query .= ' GROUP BY nama_kabupaten ';
		return $filtered_query;
	}

	function count_filtered_kabupaten()
	{
		$sql = "SELECT COUNT(*) AS jml FROM (SELECT * ".$this->_filtered_kabupaten_query().") k";
		$jml = $this->db->query($sql)->row()->jml;
		return $jml;
	}

	public function count_all_kabupaten()
	{
		$jumlah = $this->db->select('count(DISTINCT nama_kabupaten) as jumlah')->from('desa')->get()->row()->jumlah;
		return $jumlah;
	}

	function _main_kabupaten_query()
	{
		$query = " FROM
			(SELECT DISTINCT nama_kabupaten, nama_provinsi, versi_lokal, versi_hosting,
				(SELECT count(*)
				FROM desa x where x.nama_provinsi = d.nama_provinsi and x.nama_kabupaten = d.nama_kabupaten and x.versi_lokal <> '') offline,
				(SELECT count(*)
				FROM desa x where x.nama_provinsi = d.nama_provinsi and x.nama_kabupaten = d.nama_kabupaten and x.versi_hosting <> '') online
				from desa d
			) z
			WHERE 1
		";
		return $query;
	}

	function profil_kabupaten()
	{
		$qry = "SELECT * ".$this->_filtered_kabupaten_query();

		if(isset($_POST['order'])) // here order processing
		{
			$sort_by = $this->column_order_kabupaten[$_POST['order']['0']['column']];
			$sort_type = $_POST['order']['0']['dir'];
			$qry .= " ORDER BY ".$sort_by." ".$sort_type;
		} else {
			$qry .= " ORDER BY nama_provinsi, nama_kabupaten";
		}
		if($_POST['length'] != -1)
			$qry .= " LIMIT ".$_POST['start'].", ".$_POST['length'];

		$data = $this->db->query($qry)->result_array();
		return $data;
	}

	function hapus($id)
	{
		$this->db->where('id',$id)->delete('desa');
	}

	private function _main_versi_query()
	{
		$query = " FROM
			(SELECT versi,
				SUM(CASE WHEN jenis='offline' THEN 1 ELSE 0 END) AS offline,
				SUM(CASE WHEN jenis='online' THEN 1 ELSE 0 END) AS online
			FROM
			(SELECT versi_lokal AS versi, 'offline' AS jenis FROM desa
			WHERE versi_lokal <> ''
			UNION ALL
			SELECT versi_hosting as versi, 'online' AS jenis FROM desa
			WHERE versi_hosting <> '') t
			GROUP BY versi) x
			WHERE 1
		";
		return $query;
	}

	private function _filtered_versi_query()
	{
		$filtered_query = $this->_main_versi_query();
		if($this->input->post('is_local') !== null) {
			switch ($this->input->post('is_local')) {
				case '0':
					$filtered_query .= " AND online > 0 ";
					break;
				case '1':
					$filtered_query .= " AND offline > 0 ";
					break;
			}
		}
		$sSearch = $_POST['search']['value'];
		$filtered_query .= " AND versi LIKE '%".$sSearch."%'";
		return $filtered_query;
	}

	function count_filtered_versi()
	{
		$sql = "SELECT COUNT(*) AS jml ".$this->_filtered_versi_query();
		$jml = $this->db->query($sql)->row()->jml;
		return $jml;
	}

	public function count_all_versi()
	{
		$sql = "SELECT COUNT(*) AS jml ".$this->_main_versi_query();
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		return $row['jml'];
	}

	function profil_versi()
	{
		$qry = "SELECT * ".$this->_filtered_versi_query();

		if(isset($_POST['order'])) // here order processing
		{
			$sort_by = $this->column_order_versi[$_POST['order']['0']['column']];
			$sort_type = $_POST['order']['0']['dir'];
			$qry .= " ORDER BY ".$sort_by." ".$sort_type;
		} else
			$qry .= "ORDER BY versi DESC";
		if($_POST['length'] != -1)
		 $qry .= " LIMIT ".$_POST['start'].", ".$_POST['length'];
		$query = $this->db->query($qry);
		return $query->result_array();
	}

	private function email($subject, $message, $to="eddie.ridwan@gmail.com")
	{
		$this->load->library('email'); // Note: no $config param needed
		$this->email->from('opensid.server@gmail.com', 'OpenSID Tracker');
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);
		if ($this->email->send())
			echo "<br>Email desa baru: ".$message;
		else show_error($this->email->print_debugger());
	}

	private function notifikasi($data)
	{
		$url = (!empty($data['url_hosting'])) ? $data['url_hosting'] : $data['url_lokal'];
		$message =
			"Desa: ".$data['nama_desa']."<br>\r\n".
			"Kecamatan: ".$data['nama_kecamatan']."<br>\r\n".
			"Kabupaten: ".$data['nama_kabupaten']."<br>\r\n".
			"Provinsi: ".$data['nama_provinsi']."<br>\r\n".
			"Website: "."http://".$url."<br>\r\n";
		$this->load->library('email'); // Note: no $config param needed
		$this->email->from('opensid.server@gmail.com', 'Desa OpenSID');
		$this->email->to("h2b7q6h0p6v3b3v8@opensid.slack.com");
		$this->email->subject("Desa Pengguna OpenSID");
		$this->email->message($message);
		if ($this->email->send())
			echo "<br>Notifikasi desa baru : ".$message;
		else show_error($this->email->print_debugger());
	}

	public function  jmlDesa()
	{
		$this->db->select("count(*) as desa_total");
		$this->db->select("(select count(*) from desa x where x.versi_lokal <> '') desa_offline");
		$this->db->select("(select count(*) from desa x where x.versi_hosting <> '') desa_online");
		$this->db->select("count(distinct nama_kabupaten) as kabupaten_total");
		$this->db->select("(select count(distinct nama_kabupaten) from desa x where x.versi_lokal <> '') kabupaten_offline");
		$this->db->select("(select count(distinct nama_kabupaten) from desa x where x.versi_hosting <> '') kabupaten_online");
		$data = $this->db->get('desa')->row_array();
		return $data;
	}
}
?>