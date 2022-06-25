<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Fpdf\Fpdf;

class Semester extends BackendController {

    public function __construct()
    {
        parent::__construct();
		if (!$this->ion_auth->logged_in()) redirect(base_url('auth/login'), 'refresh');
		if ($this->ion_auth->in_group('mahasiswa')) show_error('Sorry, you do not have permission to access this page');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->lang->load('auth');
		$this->load->model('semester_model');
    }

	public function index()
	{
		$search = (input_get('tahun') || input_get('keterangan') ? ['tahun' => input_get('tahun'), 'keterangan' => input_get('keterangan')] : false);
		$this->data['total'] = $this->semester_model->get(false, $search)->num_rows();
		$this->data['pagination'] = new \yidas\data\Pagination([
			'perPageParam' => '',
			'totalCount' => $this->data['total'],
			'perPage' => 10,
		]);
		$this->data['start'] = ($this->data['total'] > 0 ? $this->data['pagination']->offset+1 : 0);
		$this->data['end'] = ($this->data['total'] > 0 ? $this->semester_model->get(false, $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->num_rows() : 0);
		$this->data['datas'] = $this->semester_model->get(false, $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->result();
		$this->data['message'] = $this->_show_message();

		$this->_render_page('semester/list', $this->data);
	}

	public function add()
	{
		$this->form_validation->set_rules('tahun', 'tahun', 'trim|required');
		$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			$data = [
				'tahun' => input_post('tahun'),
				'keterangan' => input_post('keterangan'),
			];
		}
		
		if ($this->form_validation->run() === TRUE && $id = $this->semester_model->add($data)) {
			$this->_set_message('success', 'Data berhasil disimpan');
			redirect(base_url('semester'), 'refresh');
		} else {
			$this->data['message'] = $this->_show_message('error', validation_errors());
			$this->_render_page('semester/add', $this->data);
		}
	}

	public function edit($id)
	{
		$data = $this->semester_model->get(['id_semester' => wah_decode($id)]);
		if (!$data->num_rows()) redirect(base_url('semester'), 'refresh');

		$data = $data->row();
			
		$this->form_validation->set_rules('tahun', 'tahun', 'trim|required');
		$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			if ($data->id_semester != wah_decode(input_post('id'))) show_error($this->lang->line('error_csrf'));
			$input = [
				'tahun' => input_post('tahun'),
				'keterangan' => input_post('keterangan')
			];

			if ($this->semester_model->set($input, ['id_semester' => $data->id_semester])) {
				$this->_set_message('success', 'Data berhasil disimpan');
				redirect(base_url('semester/edit/' . wah_encode($data->id_semester)), 'refresh');
			} else {
				$this->_set_message('error', 'Data gagal disimpan');
				redirect(base_url('semester/edit/' . wah_encode($data->id_semester)), 'refresh');
			}
		} else {
			$this->data['message'] = $this->_show_message('error', validation_errors());
			$this->data['data'] = $data;
	
			$this->_render_page('semester/edit', $this->data);
		}

	}

	public function delete($id)
	{
		$data = $this->semester_model->get(['id_semester' => wah_decode($id)]);
		if (!$data->num_rows()) redirect(base_url('semester'), 'refresh');

		if ($this->semester_model->unset(['id_semester' => $data->row()->id_semester])) $this->_set_message('success', 'Data berhasil dihapus');
		redirect(base_url('semester'), 'refresh');
	}

	public function export_excel()
	{
		$title = 'Export Semester ' . date('d M Y');
		$datas = $this->semester_model->get()->result();

		$spreadsheet = new Spreadsheet();
		foreach(range('A','F') as $columnID) $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'Tahun')
			->setCellValue('B1', 'Keterangan');

		$i=2;
		foreach($datas as $data) {
			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A'.$i, $data->tahun)
				->setCellValue('B'.$i, $data->keterangan);
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
		$title = 'Export Semester ' . date('d M Y');
		$datas = $this->semester_model->get()->result();

		$pdf = new Fpdf();
		$headers = array('Tahun', 'Keterangan');
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
			$pdf->Cell($width[0], 6, $data->tahun, 0, 0, 'L', $fill);
			$pdf->Cell($width[1], 6, $data->keterangan, 0, 0, 'L', $fill);
			$pdf->Ln();
			$fill = !$fill;
		}

		$pdf->Output();
	}
}
