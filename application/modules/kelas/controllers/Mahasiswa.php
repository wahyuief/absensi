<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Fpdf\Fpdf;

class Mahasiswa extends BackendController {

    public function __construct()
    {
        parent::__construct();
		if (!$this->ion_auth->logged_in()) redirect(base_url('auth/login'), 'refresh');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->lang->load('auth');
		$this->load->model('km_model');
		$this->load->model('kelas_model');
		$this->load->model('semester_model');
    }

	public function index($id_kelas)
	{
		$id_kelas = wah_decode($id_kelas);
		$search = (input_get('nama_kelas') ? ['nama_kelas' => input_get('nama_kelas')] : false);
		$this->data['total'] = $this->km_model->get(['kelas_mahasiswa.id_kelas' => $id_kelas], $search)->num_rows();
		$this->data['pagination'] = new \yidas\data\Pagination([
			'perPageParam' => '',
			'totalCount' => $this->data['total'],
			'perPage' => 10,
		]);
		$this->data['start'] = ($this->data['total'] > 0 ? $this->data['pagination']->offset+1 : 0);
		$this->data['end'] = ($this->data['total'] > 0 ? $this->km_model->get(['kelas_mahasiswa.id_kelas' => $id_kelas], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->num_rows() : 0);
		$this->data['datas'] = $this->km_model->get(['kelas_mahasiswa.id_kelas' => $id_kelas], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->result();
		$this->data['kelas'] = $this->kelas_model->get(['id_kelas' => $id_kelas])->row();
		$this->data['message'] = $this->_show_message();

		$this->_render_page('kelas/mahasiswa/list', $this->data);
	}

	public function add($id)
	{
		$this->form_validation->set_rules('mahasiswa', 'mahasiswa', 'trim|required');
		$this->form_validation->set_rules('semester', 'semester', 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			$data = [
				'id_mahasiswa' => input_post('mahasiswa'),
				'id_kelas' => wah_decode($id),
				'id_semester' => input_post('semester'),
			];
		}
		
		if ($this->form_validation->run() === TRUE && $this->km_model->add($data)) {
			$this->_set_message('success', 'Data berhasil disimpan');
			redirect(base_url('kelas/mahasiswa/' . $id), 'refresh');
		} else {
			$mahasiswa = $this->ion_auth->order_by('id', 'DESC')->users('mahasiswa')->result();
			$mhsw = array();
			foreach ($mahasiswa as $mah) {
				if (!$this->km_model->get(['id_mahasiswa' => $mah->id])->num_rows()) {
					$mhsw[] = array(
						'id' => $mah->id,
						'fullname' => $mah->fullname
					);
				}
			}
			$this->data['message'] = $this->_show_message('error', validation_errors());
			$this->data['mahasiswa'] = $mhsw;
			$this->data['semester'] = $this->semester_model->get(false, ['tahun' => date('Y')])->result();
			$this->_render_page('kelas/mahasiswa/add', $this->data);
		}
	}

	public function edit($id)
	{
		$data = $this->km_model->get(['id_km' => wah_decode($id)]);
		if (!$data->num_rows()) redirect(base_url('kelas/mahasiswa/' . $id), 'refresh');

		$data = $data->row();
		
		$this->form_validation->set_rules('mahasiswa', 'mahasiswa', 'trim|required');
		$this->form_validation->set_rules('kelas', 'kelas', 'trim|required');
		$this->form_validation->set_rules('semester', 'semester', 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			if ($data->id_km != wah_decode(input_post('id'))) show_error($this->lang->line('error_csrf'));
			$input = [
				'id_mahasiswa' => input_post('mahasiswa'),
				'id_kelas' => input_post('kelas'),
				'id_semester' => input_post('semester'),
			];

			if ($this->km_model->set($input, ['id_km' => $data->id_km])) {
				$this->_set_message('success', 'Data berhasil disimpan');
				redirect(base_url('kelas/mahasiswa/edit/' . wah_encode($data->id_km)), 'refresh');
			} else {
				$this->_set_message('error', 'Data gagal disimpan');
				redirect(base_url('kelas/mahasiswa/edit/' . wah_encode($data->id_km)), 'refresh');
			}
		} else {
			$this->data['message'] = $this->_show_message('error', validation_errors());
			$mahasiswa = $this->ion_auth->order_by('id', 'DESC')->users('mahasiswa')->result();
			$mhsw = array();
			foreach ($mahasiswa as $mah) {
				$mhsw[] = array(
					'id' => $mah->id,
					'fullname' => $mah->fullname
				);
			}
			$this->data['message'] = $this->_show_message('error', validation_errors());
			$this->data['mahasiswa'] = $mhsw;
			$this->data['semester'] = $this->semester_model->get(false, ['tahun' => date('Y')])->result();
			$this->data['kelas'] = $this->kelas_model->get()->result();
			$this->data['data'] = $data;
	
			$this->_render_page('kelas/mahasiswa/edit', $this->data);
		}

	}

	public function delete($id)
	{
		$data = $this->km_model->get(['id_km' => wah_decode($id)]);
		if (!$data->num_rows()) redirect(base_url('kelas'), 'refresh');

		if ($this->km_model->unset(['id_km' => $data->row()->id_km])) $this->_set_message('success', 'Data berhasil dihapus');
		redirect(base_url('kelas/mahasiswa'), 'refresh');
	}

	public function export_excel()
	{
		$title = 'Export Kelas ' . date('d M Y');
		$datas = $this->km_model->get()->result();

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
		$datas = $this->km_model->get()->result();

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
