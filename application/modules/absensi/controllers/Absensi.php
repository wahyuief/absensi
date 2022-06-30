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
    }

	public function index($id)
	{
		$id = wah_decode($id);
		$matkul = $this->km_model->get(['kelas_matkul.id_matkul' => $id])->row();
		if ($this->ion_auth->in_group('mahasiswa')) $this->data['data'] = $this->kmm_model->get(['kelas_matkul_mahasiswa.id_km' => $matkul->id_km, 'kelas_matkul_mahasiswa.id_mahasiswa' => $this->session->userdata('user_id')])->row();
		if ($this->ion_auth->in_group('dosen')) $this->data['data'] = $this->km_model->get(['kelas_matkul.id_matkul' => $id, 'mata_kuliah.id_dosen' => $this->session->userdata('user_id')])->row();
		$this->data['message'] = $this->_show_message();
		$this->data['user'] = $this->ion_auth->user()->row();

		$this->_render_page('absensi/absen', $this->data);
	}

	public function upload()
	{
		$user = $this->ion_auth->where('id', $id_user)->users()->row();
		$absensi = $this->absensi_model->get(['absensi.id_matkul' => wah_decode(input_post('matkul')), 'absensi.id_user' => $this->session->userdata('user_id')]);

		$data = [
			'id_matkul' => wah_decode(input_post('matkul')),
			'id_user' => $this->session->userdata('user_id'),
			'tanggal_absen' => date('Y-m-d H:i:s'),
			'keterangan' => 'Masuk'
		];
		
		if ($absensi->num_rows() > 0) {
			return false;
		}

		if ($this->absensi_model->add($data)) {
			echo json_encode(array(
				'success' => true
			));
		}
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
