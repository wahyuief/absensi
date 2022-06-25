<?php defined('BASEPATH') or exit('No direct script access allowed');

class FrontendController extends MY_Controller
{
    public $CI;
    protected $data = array();
    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
    }

    protected function _get_csrf_nonce() {
		$key = unique_id('uuid');
		$value = unique_id('symbol', 64);
		$this->session->set_flashdata('e10dc27f-c1c0-0275-7420-9954e5f2ade4', $key);
		$this->session->set_flashdata('bfb3b245-7dcb-b810-6e68-71b7ec497677', $value);
		return [$key => $value];
	}

	protected function _valid_csrf_nonce() {
		$csrfkey = $this->input->post($this->session->flashdata('e10dc27f-c1c0-0275-7420-9954e5f2ade4'));
		if ($csrfkey && $csrfkey === $this->session->flashdata('bfb3b245-7dcb-b810-6e68-71b7ec497677')) return TRUE;
		return FALSE;
	}

    protected function _render_page($view, $data = NULL, $returnhtml = FALSE) {
		$data['title'] = $this->config->item('site_title', 'ion_auth') . ($this->uri->segment(2) ? ' | ' . ucwords($this->uri->segment(2)) : '') . ($this->uri->segment(3) ?  ' - ' .ucwords($this->uri->segment(3)) : '');
		$viewdata = (empty($data)) ? $this->data : $data;
		$view_html = $this->load->view($view, $viewdata, $returnhtml);
		if ($returnhtml) return $view_html;
	}
}