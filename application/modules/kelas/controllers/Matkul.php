<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Fpdf\Fpdf;

class Matkul extends BackendController {

    public function __construct()
    {
        parent::__construct();
		if (!$this->ion_auth->logged_in()) redirect(base_url('auth/login'), 'refresh');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->lang->load('auth');
		$this->load->model('km_model');
		$this->load->model('kelas_model');
		$this->load->model('semester_model');
		$this->load->model('matkul_model');
    }

	public function index($id_kelas)
	{
		$id_kelas = wah_decode($id_kelas);
		$search = (input_get('nama_kelas') ? ['nama_kelas' => input_get('nama_kelas')] : false);
		$this->data['total'] = $this->km_model->get(['kelas_matkul.id_kelas' => $id_kelas], $search)->num_rows();
		$this->data['pagination'] = new \yidas\data\Pagination([
			'perPageParam' => '',
			'totalCount' => $this->data['total'],
			'perPage' => 10,
		]);
		$this->data['start'] = ($this->data['total'] > 0 ? $this->data['pagination']->offset+1 : 0);
		$this->data['end'] = ($this->data['total'] > 0 ? $this->km_model->get(['kelas_matkul.id_kelas' => $id_kelas], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->num_rows() : 0);
		$this->data['datas'] = $this->km_model->get(['kelas_matkul.id_kelas' => $id_kelas], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->result();
		$this->data['kelas'] = $this->kelas_model->get(['id_kelas' => $id_kelas])->row();
		$this->data['message'] = $this->_show_message();

		$this->_render_page('kelas/matkul/list', $this->data);
	}

	public function add($id)
	{
		$this->form_validation->set_rules('matkul', 'matkul', 'trim|required');
		$this->form_validation->set_rules('hari', 'hari', 'trim|required');
		$this->form_validation->set_rules('waktu_mulai', 'waktu_mulai', 'trim|required');
		$this->form_validation->set_rules('waktu_selesai', 'waktu_selesai', 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			switch (input_post('hari')) {
				case 'Senin':
					$tanggal = '20-06-2022 ';
					break;
				case 'Selasa':
					$tanggal = '21-06-2022 ';
					break;
				case 'Rabu':
					$tanggal = '22-06-2022 ';
					break;
				case 'Kamis':
					$tanggal = '23-06-2022 ';
					break;
				case 'Jumat':
					$tanggal = '24-06-2022 ';
					break;
				case 'Sabtu':
					$tanggal = '25-06-2022 ';
					break;
				default://minggu
					$tanggal = '26-06-2022 ';
					break;
			}
			$jadwal_mulai = $tanggal . input_post('waktu_mulai');
			$jadwal_selesai = $tanggal . input_post('waktu_selesai');
			$data = [
				'id_matkul' => input_post('matkul'),
				'id_kelas' => wah_decode($id),
				'jadwal_mulai' => strtotime($jadwal_mulai),
				'jadwal_selesai' => strtotime($jadwal_selesai)
			];
		}
		
		if ($this->form_validation->run() === TRUE && $this->km_model->add($data)) {
			$this->_set_message('success', 'Data berhasil disimpan');
			redirect(base_url('kelas/matkul/' . $id), 'refresh');
		} else {
			$this->data['message'] = $this->_show_message('error', validation_errors());
			$this->data['matkul'] = $this->matkul_model->get()->result();
			$this->_render_page('kelas/matkul/add', $this->data);
		}
	}

	public function edit($id)
	{
		$data = $this->km_model->get(['id_km' => wah_decode($id)]);
		if (!$data->num_rows()) redirect(base_url('kelas/matkul/' . $id), 'refresh');

		$data = $data->row();
		
		$this->form_validation->set_rules('matkul', 'matkul', 'trim|required');
		$this->form_validation->set_rules('hari', 'hari', 'trim|required');
		$this->form_validation->set_rules('waktu_mulai', 'waktu_mulai', 'trim|required');
		$this->form_validation->set_rules('waktu_selesai', 'waktu_selesai', 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			if ($data->id_km != wah_decode(input_post('id'))) show_error($this->lang->line('error_csrf'));
			switch (input_post('hari')) {
				case 'Senin':
					$tanggal = '20-06-2022 ';
					break;
				case 'Selasa':
					$tanggal = '21-06-2022 ';
					break;
				case 'Rabu':
					$tanggal = '22-06-2022 ';
					break;
				case 'Kamis':
					$tanggal = '23-06-2022 ';
					break;
				case 'Jumat':
					$tanggal = '24-06-2022 ';
					break;
				case 'Sabtu':
					$tanggal = '25-06-2022 ';
					break;
				default://minggu
					$tanggal = '26-06-2022 ';
					break;
			}
			$jadwal_mulai = $tanggal . input_post('waktu_mulai');
			$jadwal_selesai = $tanggal . input_post('waktu_selesai');
			$input = [
				'id_matkul' => input_post('matkul'),
				'jadwal_mulai' => strtotime($jadwal_mulai),
				'jadwal_selesai' => strtotime($jadwal_selesai)
			];

			if ($this->km_model->set($input, ['id_km' => $data->id_km])) {
				$this->_set_message('success', 'Data berhasil disimpan');
			} else {
				$this->_set_message('error', 'Data gagal disimpan');
			}
			redirect(base_url('kelas/matkul/edit/' . wah_encode($data->id_km)), 'refresh');
		} else {
			$this->data['message'] = $this->_show_message('error', validation_errors());
			$this->data['matkul'] = $this->matkul_model->get()->result();
			$this->data['data'] = $data;
	
			$this->_render_page('kelas/matkul/edit', $this->data);
		}

	}

	public function delete($id)
	{
		$data = $this->km_model->get(['id_km' => wah_decode($id)]);
		if (!$data->num_rows()) redirect(base_url('kelas'), 'refresh');

		if ($this->km_model->unset(['id_km' => $data->row()->id_km])) $this->_set_message('success', 'Data berhasil dihapus');
		redirect(base_url('kelas/matkul'), 'refresh');
	}

	public function absensi($id_matkul)
	{
		$id_matkul = wah_decode($id_matkul);
		$user_id = ($this->ion_auth->in_group('mahasiswa') ? $this->session->userdata('user_id') : false);
		$this->data['total'] = $this->absensi_model->get(['absensi.id_matkul' => $id_matkul, 'absensi.id_user !=' => $this->session->userdata('user_id')], $search)->num_rows();
		if ($user_id) $this->data['total'] = $this->absensi_model->get(['absensi.id_user' => $user_id, 'absensi.id_matkul' => $id_matkul], $search)->num_rows();
		$this->data['pagination'] = new \yidas\data\Pagination([
			'perPageParam' => '',
			'totalCount' => $this->data['total'],
			'perPage' => 10,
		]);
		$this->data['message'] = $this->_show_message('error', validation_errors());
		$this->data['matkul'] = $this->matkul_model->get(['id_matkul' => $id_matkul])->row();
		$this->data['start'] = ($this->data['total'] > 0 ? $this->data['pagination']->offset+1 : 0);
		$this->data['end'] = ($this->data['total'] > 0 ? $this->absensi_model->get(['absensi.id_matkul' => $id_matkul, 'absensi.id_user !=' => $this->session->userdata('user_id')], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->num_rows() : 0);
		if ($user_id) $this->data['end'] = ($this->data['total'] > 0 ? $this->absensi_model->get(['absensi.id_user' => $user_id, 'absensi.id_matkul' => $id_matkul], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->num_rows() : 0);
		$this->data['datas'] = $this->absensi_model->get(['absensi.id_matkul' => $id_matkul, 'absensi.id_user !=' => $this->session->userdata('user_id')])->result();
		if ($user_id) $this->data['datas'] = $this->absensi_model->get(['absensi.id_user' => $user_id, 'absensi.id_matkul' => $id_matkul])->result();
		$this->_render_page('kelas/rekap/list', $this->data);
	}

	public function export_excel($id_matkul)
	{
		$id_matkul = wah_decode($id_matkul);
		$user_id = ($this->ion_auth->in_group('mahasiswa') ? $this->session->userdata('user_id') : false);
		$datas = $this->absensi_model->get(['absensi.id_matkul' => $id_matkul], $search)->result();
		if ($user_id) $datas = $this->absensi_model->get(['absensi.id_user' => $user_id, 'absensi.id_matkul' => $id_matkul], $search)->result();
		$title = 'Export Rekap ' . date('d M Y');

		$spreadsheet = new Spreadsheet();
		foreach(range('A','C') as $columnID) $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'Nama Mahasiswa')
			->setCellValue('B1', 'Tanggal Absen')
			->setCellValue('C1', 'Keterangan');

		$i=2;
		foreach($datas as $data) {
			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A'.$i, $data->fullname)
				->setCellValue('B'.$i, $data->tanggal_absen)
				->setCellValue('C'.$i, $data->keterangan);
			$i++;
		}

		$spreadsheet->getActiveSheet()->setTitle($title);
		$spreadsheet->setActiveSheetIndex(0);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

	public function export_pdf($id_matkul)
	{
		$title = 'Export Rekap ' . date('d M Y');
		$id_matkul = wah_decode($id_matkul);
		$user_id = ($this->ion_auth->in_group('mahasiswa') ? $this->session->userdata('user_id') : false);
		$datas = $this->absensi_model->get(['absensi.id_matkul' => $id_matkul], $search)->result();
		if ($user_id) $datas = $this->absensi_model->get(['absensi.id_user' => $user_id, 'absensi.id_matkul' => $id_matkul], $search)->result();

		$pdf = new Fpdf();
		$headers = array('Nama Mahasiswa', 'Tanggal Absen', 'Keterangan');
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
			$pdf->Cell($width[0], 6, $data->fullname, 0, 0, 'L', $fill);
			$pdf->Cell($width[1], 6, $data->tanggal_absen, 0, 0, 'L', $fill);
			$pdf->Cell($width[2], 6, $data->keterangan, 0, 0, 'L', $fill);
			$pdf->Ln();
			$fill = !$fill;
		}

		$pdf->Output();
	}
}
