<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Fpdf\Fpdf;

class Absensi extends BackendController {

    public function __construct()
    {
        parent::__construct();
		if (!$this->ion_auth->logged_in()) redirect(base_url('auth/login'), 'refresh');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->lang->load('auth');
		$this->load->model('km_model');
		$this->load->model('kmm_model');
		$this->load->model('kelas_model');
		$this->load->model('semester_model');
		$this->load->model('matkul_model');
		$this->load->model('foto_model');
    }

	public function index($id_kelas, $id_matkul)
	{
		$id_kelas = wah_decode($id_kelas);
		$id_matkul = wah_decode($id_matkul);
		$matkul = $this->km_model->get(['kelas_matkul.id_kelas' => $id_kelas, 'kelas_matkul.id_matkul' => $id_matkul])->row();
		if ($this->ion_auth->in_group('mahasiswa')) $this->data['data'] = $this->kmm_model->get(['kelas_matkul_mahasiswa.id_km' => $matkul->id_km, 'kelas_matkul_mahasiswa.id_mahasiswa' => $this->session->userdata('user_id')])->row();
		if ($this->ion_auth->in_group('dosen')) $this->data['data'] = $this->km_model->get(['kelas_matkul.id_kelas' => $id_kelas, 'kelas_matkul.id_matkul' => $id_matkul, 'mata_kuliah.id_dosen' => $this->session->userdata('user_id')])->row();
		$this->data['message'] = $this->_show_message();
		$this->data['user'] = $this->ion_auth->user()->row();
		$this->data['photos'] = $this->foto_model->get(['id_user' => $this->session->userdata('user_id')])->num_rows();

		$this->_render_page('absensi/absen', $this->data);
	}

	public function upload()
	{
		$user = $this->ion_auth->where('fullname', input_post('name'))->user()->row();
		$absensi = $this->absensi_model->get(['absensi.id_matkul' => wah_decode(input_post('matkul')), 'DAYNAME(tanggal_absen)' => date('l'), 'DATE(tanggal_absen)' => date('Y-m-d'), 'absensi.id_user' => $this->session->userdata('user_id')]);

		$data = [
			'id_matkul' => wah_decode(input_post('matkul')),
			'id_user' => $this->session->userdata('user_id'),
			'tanggal_absen' => date('Y-m-d H:i:s'),
			'latitude' => input_post('latitude'),
			'longitude' => input_post('longitude'),
			'keterangan' => 'Masuk'
		];
		
		if ($absensi->num_rows() > 0) {
			echo json_encode(array('id_absen' => wah_encode($absensi->row()->id_absensi)));
			return false;
		}

		if ($id = $this->absensi_model->add($data)) {
			echo json_encode(array(
				'id_absen' => wah_encode($id),
				'success' => true
			));
		} else {
			echo json_encode(array());
		}
	}

	public function upload_foto()
	{
		$id_user = $this->session->userdata('user_id');
		$user = $this->ion_auth->where('id', $id_user)->users()->row();
		$id_matkul = wah_decode(input_post('matkul'));

		$absensi = $this->absensi_model->get(['absensi.id_matkul' => $id_matkul, 'DAYNAME(tanggal_absen)' => date('l'), 'DATE(tanggal_absen)' => date('Y-m-d'), 'absensi.id_user' => $this->session->userdata('user_id')]);
		$data_matkul = $this->matkul_model->get(['id_matkul' => $id_matkul])->row();
		$id_absen = $absensi->row()->id_absensi;

		$nama_file = strtolower($user->username . $id_absen . date('dmY'));

		if (!empty($absensi->row()->foto)) {
			return false;
		}

		$data = [
			'foto' => $this->base64_to_jpeg($this->input->post('foto'), $nama_file . '.jpg')
		];

		if ($this->absensi_model->set($data, ['absensi.id_absensi' => $id_absen, 'absensi.id_matkul' => $id_matkul, 'absensi.id_user' => $id_user])) {
			echo json_encode(array(
				'success' => true,
				'foto' => $data['foto'],
			));
		} else {
			echo json_encode(array());
		}
	}

	function base64_to_jpeg($base64_string, $output_file) {
		$filename = 'assets/absensi/'.$output_file;
		$dirname = dirname($filename);
		if (!is_dir($dirname)) mkdir($dirname, 0755, true);
		$ifp = fopen( $filename, 'wb' ); 
		$data = explode( ',', $base64_string );
		fwrite( $ifp, base64_decode( $data[ 1 ] ) );
		fclose( $ifp ); 
		return $output_file; 
	}

	public function detail_absensi()
	{
		$id_matkul = wah_decode(input_post('id_matkul'));
		$data = $this->absensi_model->get(['absensi.id_matkul' => $id_matkul, 'DAYNAME(tanggal_absen)' => date('l'), 'DATE(tanggal_absen)' => date('Y-m-d'), 'absensi.id_user' => $this->session->userdata('user_id')])->row();
		$output = array(
			'tanggal_absen' => $data->tanggal_absen,
			'latitude' => $data->latitude,
			'longitude' => $data->longitude,
			'lokasi_absen' => find_location($data->latitude, $data->longitude),
			'foto' => $data->foto
		);
		echo json_encode($output);
	}
	
	public function export_excel()
	{
		$title = 'Export Kelas ' . date('d M Y');
		$datas = $this->kmm_model->get()->result();

		$spreadsheet = new Spreadsheet();
		foreach(range('A','F') as $columnID) $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'Nama Kelas');

		$i=2;
		foreach($datas as $data) {
			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A'.$i, $data->nama_kelas);
			$i++;
		}

		$spreadsheet->getActiveSheet()->setTitle($title);
		$spreadsheet->setActiveSheetIndex(0);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

	public function export_pdf()
	{
		$title = 'Export Kelas ' . date('d M Y');
		$datas = $this->kmm_model->get()->result();

		$pdf = new Fpdf();
		$headers = array('Nama Kelas');
		$pdf->SetFont('Arial', '', 12);
		$pdf->AddPage();

		$pdf->SetFillColor(220, 220, 220);
		$pdf->SetTextColor(0);
		$pdf->SetLineWidth(0);
		$pdf->SetFont('', 'B');
		$width = array(40, 40, 45, 30, 30);
		for ($i = 0; $i < count($headers); $i++)
        	$pdf->Cell($width[$i], 7, $headers[$i], 0, 0, 'L', true);
		$pdf->Ln();
		$pdf->SetFillColor(245, 245, 245);
		$pdf->SetTextColor(0);
		$pdf->SetFont('', '', 10);
		$fill = false;
		foreach ($datas as $data) {
			$pdf->Cell($width[0], 6, $data->nama_kelas, 0, 0, 'L', $fill);
			$pdf->Ln();
			$fill = !$fill;
		}

		$pdf->Output();
	}
}
