<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Fpdf\Fpdf;

class Dosen extends BackendController {

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
		$this->data['total'] = $this->ion_auth->like('username', input_get('q'))->like('fullname', input_get('q'))->like('email', input_get('q'))->users('dosen')->num_rows();
		$this->data['pagination'] = new \yidas\data\Pagination([
			'perPageParam' => '',
			'totalCount' => $this->data['total'],
			'perPage' => 10,
		]);
		$this->data['start'] = ($this->data['total'] > 0 ? $this->data['pagination']->offset+1 : 0);
		$this->data['end'] = ($this->data['total'] > 0 ? $this->data['pagination']->offset+$this->ion_auth->limit($this->data['pagination']->limit)->offset($this->data['pagination']->offset)->users('dosen')->num_rows() : 0);
		$this->data['users'] = $this->ion_auth->like('username', input_get('q'))->like('fullname', input_get('q'))->like('email', input_get('q'))->limit($this->data['pagination']->limit)->offset($this->data['pagination']->offset)->order_by('id', 'DESC')->users('dosen')->result();
		$this->data['message'] = $this->_show_message();

		$this->_render_page('dosen/list', $this->data);
	}

	public function add()
	{
		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		$this->form_validation->set_rules('fullname', 'full name', 'trim|required');
		$this->form_validation->set_rules('username', 'nidn', 'trim|required|is_unique[' . $tables['users'] . '.username]');
		$this->form_validation->set_rules('email', 'email address', 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
		$this->form_validation->set_rules('phone', 'phone', 'trim');
		$this->form_validation->set_rules('company', 'company', 'trim');
		$this->form_validation->set_rules('password', 'password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']');
		$this->form_validation->set_rules('password_confirm', 'confirm password', 'required|matches[password]');

		if ($this->form_validation->run() === TRUE) {
			$password = input_post('password');
			$email = strtolower(input_post('email'));

			$additional_data = [
				'username' => input_post('username'),
				'fullname' => input_post('fullname'),
				'phone' => input_post('phone'),
				'uuid' => unique_id('uuid')
			];
		}
		
		if ($this->form_validation->run() === TRUE && $id = $this->ion_auth->register($email, $password, $email, $additional_data)) {
			$this->ion_auth->add_to_group(3, $id);
			$this->_set_message('success', $this->ion_auth->messages());
			redirect(base_url('dosen'), 'refresh');
		} else {
			$this->data['message'] = ($this->ion_auth->errors() ? $this->_show_message('error', $this->ion_auth->errors()) : $this->_show_message('error', validation_errors()));
			$this->_render_page('dosen/add', $this->data);
		}
	}

	public function edit($id)
	{
		$user = $this->ion_auth->user(wah_decode($id));
		if (!$user->num_rows()) redirect(base_url('dosen'), 'refresh');

		$user = $user->row();
		$groups = $this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($user->id)->result_array();
			
		$this->form_validation->set_rules('username', 'nidn', 'trim|required');
		$this->form_validation->set_rules('fullname', 'full name', 'trim|required');
		$this->form_validation->set_rules('phone', 'phone', 'trim');
		$this->form_validation->set_rules('company', 'company', 'trim');

		if (isset($_POST) && !empty($_POST)) {
			if ($user->id != wah_decode(input_post('id'))) show_error($this->lang->line('error_csrf'));

			if (input_post('password')) {
				$this->form_validation->set_rules('password', 'password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']');
				$this->form_validation->set_rules('password_confirm', 'confirm password', 'required|matches[password]');
			}

			if ($this->form_validation->run() === TRUE) {
				$data = [
					'fullname' => input_post('fullname'),
					'username' => input_post('username'),
					'email' => input_post('email'),
					'phone' => input_post('phone'),
					'active' => input_post('status')
				];

				if (input_post('password')) $data['password'] = input_post('password');

				if ($this->ion_auth->update($user->id, $data)) {
					$this->_set_message('success', $this->ion_auth->messages());
					redirect(base_url('dosen/edit/' . wah_encode($user->id)), 'refresh');
				} else {
					$this->_set_message('error', $this->ion_auth->errors());
					redirect(base_url('dosen/edit/' . wah_encode($user->id)), 'refresh');
				}
			}
		}

		$this->data['message'] = ($this->ion_auth->errors() ? $this->_show_message('error', $this->ion_auth->errors()) : $this->_show_message('error', validation_errors()));
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;

		$this->_render_page('dosen/edit', $this->data);
	}

	public function delete($id)
	{
		$user = $this->ion_auth->user(wah_decode($id));
		if (!$user->num_rows()) redirect(base_url('dosen'), 'refresh');
		if ($user->row()->id === '1') redirect(base_url('dosen'), 'refresh');

		if ($this->ion_auth->delete_user($user->row()->id)) $this->_set_message('success', $this->ion_auth->messages());
		redirect(base_url('dosen'), 'refresh');
	}

	public function export_excel()
	{
		$title = 'Export Users ' . date('d M Y');
		$users = $this->ion_auth->order_by('id', 'DESC')->users('dosen')->result();
		foreach ($users as $k => $user) $users[$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
		foreach ($user->groups as $group) $group_name[] = $group->name;

		$spreadsheet = new Spreadsheet();
		foreach(range('A','F') as $columnID) $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'NIDN')
			->setCellValue('B1', 'Full Name')
			->setCellValue('C1', 'Email Address')
			->setCellValue('D1', 'Groups')
			->setCellValue('E1', 'Status')
			->setCellValue('F1', 'Created');

		$i=2;
		foreach($users as $user) {
			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A'.$i, $user->username)
				->setCellValue('B'.$i, $user->fullname)
				->setCellValue('C'.$i, $user->email)
				->setCellValue('D'.$i, implode(',', $group_name))
				->setCellValue('E'.$i, ($user->active) ? 'Active' : 'Inactive')
				->setCellValue('F'.$i, date('d M Y', $user->created_on));
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
		$title = 'Export Users ' . date('d M Y');
		$users = $this->ion_auth->order_by('id', 'DESC')->users('dosen')->result();
		foreach ($users as $k => $user) $users[$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
		foreach ($user->groups as $group) $group_name[] = $group->name;

		$pdf = new Fpdf();
		$headers = array('NIDN', 'Full Name', 'Email', 'Groups', 'Status');
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
		foreach ($users as $user) {
			$pdf->Cell($width[0], 6, $user->username, 0, 0, 'L', $fill);
			$pdf->Cell($width[1], 6, $user->fullname, 0, 0, 'L', $fill);
			$pdf->Cell($width[2], 6, $user->email, 0, 0, 'L', $fill);
			$pdf->Cell($width[3], 6, implode(',', $group_name), 0, 0, 'L', $fill);
			$pdf->Cell($width[4], 6, ($user->active) ? 'Active' : 'Inactive', 0, 0, 'L', $fill);
			$pdf->Ln();
			$fill = !$fill;
		}

		$pdf->Output();
	}
}
