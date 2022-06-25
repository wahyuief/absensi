<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Fpdf\Fpdf;

class Notification extends BackendController {

    public function __construct()
    {
        parent::__construct();
		if (!$this->ion_auth->logged_in()) redirect(base_url('auth/login'), 'refresh');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->lang->load('auth');
    }

	public function index()
	{
		$user_id = $this->ion_auth->user()->row()->id;
		$this->data['total'] = notif_list($user_id, null, null, input_get('q'))->num_rows();
		$this->data['pagination'] = new \yidas\data\Pagination([
			'perPageParam' => '',
			'totalCount' => $this->data['total'],
			'perPage' => 7,
		]);
		$this->data['start'] = ($this->data['total'] > 0 ? $this->data['pagination']->offset+1 : 0);
		$this->data['end'] = ($this->data['total'] > 0 ? $this->data['pagination']->offset+notif_list($user_id, $this->data['pagination']->limit, $this->data['pagination']->offset, input_get('q'))->num_rows() : 0);
		$this->data['notification'] = notif_list($user_id, $this->data['pagination']->limit, $this->data['pagination']->offset, input_get('q'))->result();
		$this->data['message'] = $this->_show_message();
		$this->_render_page('notification/list', $this->data);
	}

	public function read($id)
	{
		$user_id = $this->ion_auth->user()->row()->id;
		$notif = notif_list($user_id, null, null, false, ['notification.id' => wah_decode($id)]);
		if (!$notif->num_rows()) redirect(base_url('notification'), 'refresh');
		
		notif_read(wah_decode($id), $user_id);
		$this->data['notif'] = $notif->row();
		$this->_render_page('notification/read', $this->data);
	}

	public function delete($id)
	{
		$user_id = $this->ion_auth->user()->row()->id;
		$notif = notif_list($user_id, null, null, false, ['notification.id' => wah_decode($id)]);
		if (!$notif->num_rows()) redirect(base_url('notification'), 'refresh');

		if (notif_delete($notif->row()->id)) $this->_set_message('success', 'Deleted Successfully');
		redirect(base_url('notification'), 'refresh');
	}

	public function export_excel()
	{
		$title = 'Export Notification ' . date('d M Y');
		$user_id = $this->ion_auth->user()->row()->id;
		$notification = notif_list($user_id)->result();

		$spreadsheet = new Spreadsheet();
		foreach(range('A','D') as $columnID) $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'From')
			->setCellValue('B1', 'Title')
			->setCellValue('C1', 'Date')
			->setCellValue('D1', 'Status');

		$i=2;
		foreach($notification as $notif) {
			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A'.$i, $notif->sender_name)
				->setCellValue('B'.$i, $notif->title)
				->setCellValue('C'.$i, $notif->sent_on)
				->setCellValue('D'.$i, ($notif->read_on) ? 'Read' : 'Unread');
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
		$title = 'Export Notification ' . date('d M Y');
		$user_id = $this->ion_auth->user()->row()->id;
		$notification = notif_list($user_id)->result();

		$pdf = new Fpdf();
		$headers = array('From', 'Title', 'Date', 'Status');
		$pdf->SetFont('Arial', '', 12);
		$pdf->AddPage();

		$pdf->SetFillColor(220, 220, 220);
		$pdf->SetTextColor(0);
		$pdf->SetLineWidth(0);
		$pdf->SetFont('', 'B');
		$width = array(40, 80, 40, 30);
		for ($i = 0; $i < count($headers); $i++)
        	$pdf->Cell($width[$i], 7, $headers[$i], 0, 0, 'L', true);
		$pdf->Ln();
		$pdf->SetFillColor(245, 245, 245);
		$pdf->SetTextColor(0);
		$pdf->SetFont('', '', 10);
		$fill = false;
		foreach ($notification as $notif) {
			$pdf->Cell($width[0], 6, $notif->sender_name, 0, 0, 'L', $fill);
			$pdf->Cell($width[1], 6, $notif->title, 0, 0, 'L', $fill);
			$pdf->Cell($width[2], 6, $notif->sent_on, 0, 0, 'L', $fill);
			$pdf->Cell($width[3], 6, ($notif->read_on) ? 'Read' : 'Unread', 0, 0, 'L', $fill);
			$pdf->Ln();
			$fill = !$fill;
		}

		$pdf->Output();
	}
}