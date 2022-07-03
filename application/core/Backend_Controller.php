<?php defined('BASEPATH') or exit('No direct script access allowed');

class BackendController extends MY_Controller
{
    public $CI;
    protected $data = array();
    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
		if (!pendaftaran_wajah_check() && $this->uri->segment(1) !== 'profile' && !$this->ion_auth->is_admin() && $this->uri->segment(1) !== 'logout') redirect(base_url('profile'));
    }

    protected function _get_csrf_nonce() {
		$key = unique_id('uuid');
		$value = unique_id('uuid');
		$this->session->set_flashdata('duwaicnhduawidwakl', $key);
		$this->session->set_flashdata('owcqppjwqchdlwapjd', $value);
		return [$key => $value];
	}

	protected function _valid_csrf_nonce() {
		$csrfkey = $this->input->post($this->session->flashdata('duwaicnhduawidwakl'));
		if ($csrfkey && $csrfkey === $this->session->flashdata('owcqppjwqchdlwapjd')) return TRUE;
		return FALSE;
	}

    protected function _render_page($view, $data) {
		$data['title'] = $this->config->item('site_title', 'ion_auth') . ($this->uri->segment(1) ? ' | ' . ucwords($this->uri->segment(1)) : '') . ($this->uri->segment(2) ?  ' - ' .ucwords($this->uri->segment(2)) : '');
		$data['user_sess'] = $this->ion_auth->user()->row();
		foreach ($this->ion_auth->get_users_groups($data['user_sess']->id)->result() as $group) {
			$group_name[] = $group->name;
		}
		$data['group_user_sess'] = $group_name;
		$this->load->view('administrator/header', $data);
		$this->load->view('administrator/navbar', $data);
		$this->load->view('administrator/sidebar', $data);
		$this->load->view('administrator/breadcrumb', $data);
		$this->load->view($view, $data);
		$this->load->view('administrator/footer', $data);
	}
}