<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Fpdf\Fpdf;

class Jadwal extends BackendController {

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

	public function index()
	{
		// echo strtotime('-7 days', time());
		$id = $this->session->userdata('user_id');
		$search = (input_get('nama_kelas') ? ['nama_kelas' => input_get('nama_kelas')] : false);
		if ($this->ion_auth->in_group('mahasiswa')) $this->data['total'] = $this->kmm_model->get(['kelas_matkul_mahasiswa.id_mahasiswa' => $id, 'DAYNAME(FROM_UNIXTIME(jadwal_mulai))' => date('l')], $search)->num_rows();
		if ($this->ion_auth->in_group('dosen')) $this->data['total'] = $this->km_model->get(['mata_kuliah.id_dosen' => $id, 'DAYNAME(FROM_UNIXTIME(jadwal_mulai))' => date('l')], $search)->num_rows();
		if ($this->ion_auth->is_admin()) $this->data['total'] = $this->km_model->get(['DAYNAME(FROM_UNIXTIME(jadwal_mulai))' => date('l')], $search)->num_rows();
		$this->data['pagination'] = new \yidas\data\Pagination([
			'perPageParam' => '',
			'totalCount' => $this->data['total'],
			'perPage' => 10,
		]);
		$this->data['start'] = ($this->data['total'] > 0 ? $this->data['pagination']->offset+1 : 0);
		if ($this->ion_auth->in_group('mahasiswa')) $this->data['end'] = ($this->data['total'] > 0 ? $this->kmm_model->get(['kelas_matkul_mahasiswa.id_mahasiswa' => $id, 'DAYNAME(FROM_UNIXTIME(jadwal_mulai))' => date('l')], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->num_rows() : 0);
		if ($this->ion_auth->in_group('dosen')) $this->data['end'] = ($this->data['total'] > 0 ? $this->km_model->get(['mata_kuliah.id_dosen' => $id, 'DAYNAME(FROM_UNIXTIME(jadwal_mulai))' => date('l')], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->num_rows() : 0);
		if ($this->ion_auth->is_admin()) $this->data['end'] = ($this->data['total'] > 0 ? $this->km_model->get(['DAYNAME(FROM_UNIXTIME(jadwal_mulai))' => date('l')], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->num_rows() : 0);
		if ($this->ion_auth->in_group('mahasiswa')) $this->data['datas'] = $this->kmm_model->get(['kelas_matkul_mahasiswa.id_mahasiswa' => $id, 'DAYNAME(FROM_UNIXTIME(jadwal_mulai))' => date('l')], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->result();
		if ($this->ion_auth->in_group('dosen')) $this->data['datas'] = $this->km_model->get(['mata_kuliah.id_dosen' => $id, 'DAYNAME(FROM_UNIXTIME(jadwal_mulai))' => date('l')], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->result();
		if ($this->ion_auth->is_admin()) $this->data['datas'] = $this->km_model->get(['DAYNAME(FROM_UNIXTIME(jadwal_mulai))' => date('l')], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->result();
		$this->data['user'] = $this->ion_auth->where('id', $id)->users()->row();
		$this->data['message'] = $this->_show_message();

		$this->_render_page('jadwal/list', $this->data);
	}

	public function add($id)
	{
		$this->form_validation->set_rules('matkul', 'matkul', 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			$data = [
				'id_km' => input_post('matkul'),
				'id_mahasiswa' => wah_decode($id),
			];
		}
		
		if ($this->form_validation->run() === TRUE && $this->kmm_model->add($data)) {
			$this->_set_message('success', 'Data berhasil disimpan');
			redirect(base_url('jadwal/' . $id), 'refresh');
		} else {
			$this->data['message'] = $this->_show_message('error', validation_errors());
			$this->data['matkul'] = $this->km_model->get()->result();
			$this->_render_page('jadwal/add', $this->data);
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
