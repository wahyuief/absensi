<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Fpdf\Fpdf;

class Groups extends BackendController {

    public function __construct()
    {
        parent::__construct();
		if (!$this->ion_auth->logged_in()) redirect(base_url('auth/login'), 'refresh');
		if (!$this->ion_auth->is_admin()) show_error('Sorry, you do not have permission to access this page');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->lang->load('auth');
    }

	public function index()
	{
		$this->data['total'] = $this->ion_auth->like('name', input_get('q'))->like('description', input_get('q'))->groups()->num_rows();
		$this->data['pagination'] = new \yidas\data\Pagination([
			'perPageParam' => '',
			'totalCount' => $this->data['total'],
			'perPage' => 7,
		]);
		$this->data['start'] = ($this->data['total'] > 0 ? $this->data['pagination']->offset+1 : 0);
		$this->data['end'] = ($this->data['total'] > 0 ? $this->data['pagination']->offset+$this->ion_auth->limit($this->data['pagination']->limit)->offset($this->data['pagination']->offset)->groups()->num_rows() : 0);
		$this->data['groups'] = $this->ion_auth->like('name', input_get('q'))->like('description', input_get('q'))->limit($this->data['pagination']->limit)->offset($this->data['pagination']->offset)->order_by('id', 'DESC')->groups()->result();
		$this->data['message'] = $this->_show_message();
		$this->_render_page('groups/list', $this->data);
	}

	public function add()
	{
		$this->form_validation->set_rules('name', 'name', 'trim|required');
		$this->form_validation->set_rules('description', 'description', 'trim');

		if ($this->form_validation->run() === TRUE && $this->ion_auth->create_group(input_post('name'), input_post('description'))) {
			if ($this->_valid_csrf_nonce() === FALSE) show_error($this->lang->line('error_csrf'));
			$this->_set_message('success', $this->ion_auth->messages());
			redirect(base_url('administrator/groups'), 'refresh');
		} else {
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['message'] = ($this->ion_auth->errors() ? $this->_show_message('error', $this->ion_auth->errors()) : $this->_show_message('error', validation_errors()));
			$this->_render_page('groups/add', $this->data);
		}
	}

	public function edit($id)
	{
		$group = $this->ion_auth->group(wah_decode($id));
		if (!$group->num_rows()) redirect(base_url('administrator/groups'), 'refresh');

		$this->form_validation->set_rules('name', 'name', 'trim|required|alpha_dash');
		if ($this->form_validation->run() === TRUE && $this->ion_auth->update_group($group->row()->id, input_post('name'), ['description' => input_post('description')])) {
			if ($this->_valid_csrf_nonce() === FALSE || $group->row()->id != wah_decode(input_post('id'))) show_error($this->lang->line('error_csrf'));
			$this->_set_message('success', $this->ion_auth->messages());
			redirect(base_url('administrator/groups/edit/' . $id), 'refresh');
		} else {
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['message'] = ($this->ion_auth->errors() ? $this->_show_message('error', $this->ion_auth->errors()) : $this->_show_message('error', validation_errors()));
			$this->data['group'] = $group->row();
			$this->_render_page('groups/edit', $this->data);
		}
	}

	public function delete($id)
	{
		$group = $this->ion_auth->group(wah_decode($id));
		if (!$group->num_rows()) redirect(base_url('administrator/groups'), 'refresh');
		if ($group->row()->name === 'admin') redirect(base_url('administrator/groups'), 'refresh');

		if ($this->ion_auth->delete_group($group->row()->id)) $this->_set_message('success', $this->ion_auth->messages());
		redirect(base_url('administrator/groups'), 'refresh');
	}

	public function export_excel()
	{
		$title = 'Export Groups ' . date('d M Y');
		$groups = $this->ion_auth->order_by('id', 'DESC')->groups()->result();

		$spreadsheet = new Spreadsheet();
		foreach(range('A','B') as $columnID) $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'Name')
			->setCellValue('B1', 'Description');

		$i=2;
		foreach($groups as $group) {
			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A'.$i, $group->name)
				->setCellValue('B'.$i, $group->description);
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
		$title = 'Export Groups ' . date('d M Y');
		$groups = $this->ion_auth->order_by('id', 'DESC')->groups()->result();

		$pdf = new Fpdf();
		$headers = array('Name', 'Description');
		$pdf->SetFont('Arial', '', 12);
		$pdf->AddPage();

		$pdf->SetFillColor(220, 220, 220);
		$pdf->SetTextColor(0);
		$pdf->SetLineWidth(0);
		$pdf->SetFont('', 'B');
		$width = array(40, 150);
		for ($i = 0; $i < count($headers); $i++)
        	$pdf->Cell($width[$i], 7, $headers[$i], 0, 0, 'L', true);
		$pdf->Ln();
		$pdf->SetFillColor(245, 245, 245);
		$pdf->SetTextColor(0);
		$pdf->SetFont('', '', 10);
		$fill = false;
		foreach ($groups as $group) {
			$pdf->Cell($width[0], 6, $group->name, 0, 0, 'L', $fill);
			$pdf->Cell($width[1], 6, $group->description, 0, 0, 'L', $fill);
			$pdf->Ln();
			$fill = !$fill;
		}

		$pdf->Output();
	}
}