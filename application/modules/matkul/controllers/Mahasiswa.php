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
		$this->load->model('mm_model');
		$this->load->model('matkul_model');
		$this->load->model('km_model');
    }

	public function index($id_matkul)
	{
		$id_matkul = wah_decode($id_matkul);
		$search = (input_get('nama_matkul') ? ['nama_matkul' => input_get('nama_matkul')] : false);
		$this->data['total'] = $this->mm_model->get(['matkul_mahasiswa.id_matkul' => $id_matkul], $search)->num_rows();
		$this->data['pagination'] = new \yidas\data\Pagination([
			'perPageParam' => '',
			'totalCount' => $this->data['total'],
			'perPage' => 10,
		]);
		$this->data['start'] = ($this->data['total'] > 0 ? $this->data['pagination']->offset+1 : 0);
		$this->data['end'] = ($this->data['total'] > 0 ? $this->mm_model->get(['matkul_mahasiswa.id_matkul' => $id_matkul], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->num_rows() : 0);
		$this->data['datas'] = $this->mm_model->get(['matkul_mahasiswa.id_matkul' => $id_matkul], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->result();
		$this->data['matkul'] = $this->matkul_model->get(['id_matkul' => $id_matkul])->row();
		$this->data['message'] = $this->_show_message();

		$this->_render_page('matkul/mahasiswa/list', $this->data);
	}

	public function add($id)
	{
		$this->form_validation->set_rules('mahasiswa', 'mahasiswa', 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			$data = [
				'id_mahasiswa' => input_post('mahasiswa'),
				'id_matkul' => wah_decode($id),
			];
		}
		
		if ($this->form_validation->run() === TRUE && $this->mm_model->add($data)) {
			$this->_set_message('success', 'Data berhasil disimpan');
			redirect(base_url('matkul/mahasiswa/' . $id), 'refresh');
		} else {
			$mahasiswa = $this->ion_auth->order_by('id', 'DESC')->users('mahasiswa')->result();
			$mhsw = array();
			foreach ($mahasiswa as $mah) {
				if (!$this->mm_model->get(['matkul_mahasiswa.id_matkul' => wah_decode($id), 'id_mahasiswa' => $mah->id])->num_rows()) {
					if ($this->matkul_model->get(['mata_kuliah.id_matkul' => wah_decode($id), 'mata_kuliah.id_semester' => $this->km_model->get(['id_mahasiswa' => $mah->id])->row()->id_semester])->num_rows()) {
						$mhsw[] = array(
							'id' => $mah->id,
							'fullname' => $mah->fullname
						);
					}
				}
			}
			$this->data['message'] = $this->_show_message('error', validation_errors());
			$this->data['mahasiswa'] = $mhsw;
			$this->_render_page('matkul/mahasiswa/add', $this->data);
		}
	}

	public function edit($id)
	{
		$data = $this->mm_model->get(['id_mm' => wah_decode($id)]);
		if (!$data->num_rows()) redirect(base_url('matkul/mahasiswa/' . $id), 'refresh');

		$data = $data->row();
		
		$this->form_validation->set_rules('mahasiswa', 'mahasiswa', 'trim|required');
		$this->form_validation->set_rules('matkul', 'mata kuliah', 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			$input = [
				'id_mahasiswa' => input_post('mahasiswa'),
				'id_matkul' => input_post('matkul')
			];

			if ($this->mm_model->set($input, ['id_mm' => $data->id_mm])) {
				$this->_set_message('success', 'Data berhasil disimpan');
			} else {
				$this->_set_message('error', 'Data gagal disimpan');
			}
			redirect(base_url('matkul/mahasiswa/edit/' . wah_encode($data->id_mm)), 'refresh');
		} else {
			$this->data['message'] = $this->_show_message('error', validation_errors());
			$mahasiswa = $this->ion_auth->order_by('id', 'DESC')->users('mahasiswa')->result();
			$mhsw = array();
			foreach ($mahasiswa as $mah) {
				if ($this->matkul_model->get(['mata_kuliah.id_matkul' => $this->mm_model->get(['matkul_mahasiswa.id_mm' => wah_decode($id)])->row()->id_matkul, 'mata_kuliah.id_semester' => $this->km_model->get(['id_mahasiswa' => $mah->id])->row()->id_semester])->num_rows()) {
					$mhsw[] = array(
						'id' => $mah->id,
						'fullname' => $mah->fullname
					);
				}
			}
			$matkul = $this->matkul_model->get(['mata_kuliah.id_semester' => $data->id_semester])->result();
			$mtkl = array();
			foreach ($matkul as $mk) {
				$mtkl[] = array(
					'id_matkul' => $mk->id_matkul,
					'nama_matkul' => $mk->nama_matkul
				);
			}
			$this->data['message'] = $this->_show_message('error', validation_errors());
			$this->data['mahasiswa'] = $mhsw;
			$this->data['matkul'] = $mtkl;
			$this->data['data'] = $data;
	
			$this->_render_page('matkul/mahasiswa/edit', $this->data);
		}

	}

	public function delete($id)
	{
		$data = $this->mm_model->get(['id_mm' => wah_decode($id)]);
		if (!$data->num_rows()) redirect(base_url('matkul'), 'refresh');

		if ($this->mm_model->unset(['id_mm' => $data->row()->id_mm])) $this->_set_message('success', 'Data berhasil dihapus');
		redirect(base_url('matkul/mahasiswa'), 'refresh');
	}

	public function export_excel()
	{
		$title = 'Export Kelas ' . date('d M Y');
		$datas = $this->mm_model->get()->result();

		$spreadsheet = new Spreadsheet();
		foreach(range('A','F') as $columnID) $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'Nama Kelas');

		$i=2;
		foreach($datas as $data) {
			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A'.$i, $data->nama_matkul);
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
		$datas = $this->mm_model->get()->result();

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
			$pdf->Cell($width[0], 6, $data->nama_matkul, 0, 0, 'L', $fill);
			$pdf->Ln();
			$fill = !$fill;
		}

		$pdf->Output();
	}
}
