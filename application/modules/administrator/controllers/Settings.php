<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends BackendController {

    public function __construct()
    {
        parent::__construct();
		if (!$this->ion_auth->logged_in()) redirect(base_url('auth/login'), 'refresh');
		if (!$this->ion_auth->is_admin()) show_error('Sorry you do not have permission to access this page');
    }

	public function index()
	{
		redirect(base_url('administrator/settings/general'));
	}

	public function general()
	{
		$user_sess = $this->ion_auth->user()->row();
		$this->form_validation->set_rules('site_name', 'full name', 'trim|required');
		$this->form_validation->set_rules('site_description', 'site_description', 'trim|required');
		$this->form_validation->set_rules('author', 'author', 'trim|required');
		$this->form_validation->set_rules('email', 'email', 'trim|required');
		$this->form_validation->set_rules('timezone', 'timezone', 'trim|required');
		$this->form_validation->set_rules('accent_color', 'accent_color', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			$this->data['message'] = $this->_show_message('error', $this->ion_auth->errors());
			$this->_render_page('settings/general', $this->data);
		} else {
			set_option('site_name', input_post('site_name'));
			set_option('site_description', input_post('site_description'));
			set_option('author', input_post('author'));
			set_option('email', input_post('email'));
			set_option('timezone', input_post('timezone'));
			set_option('accent_color', input_post('accent_color'));
			redirect(base_url('administrator/settings/general'), 'refresh');
		}
	}

	public function security()
	{
		$user_sess = $this->ion_auth->user()->row();
		$this->form_validation->set_rules('fullname', 'full name', 'trim|required');
		$this->form_validation->set_rules('phone', 'phone', 'trim|required');
		$this->form_validation->set_rules('company', 'company', 'trim');

		if ($this->form_validation->run() === FALSE) {
			$this->data['message'] = $this->_show_message('error', $this->ion_auth->errors());
			$this->_render_page('settings/security', $this->data);
		} else {
			$data = [
				'username' => $user_sess->username,
				'email' => $user_sess->email,
				'fullname' => input_post('fullname'),
				'company' => input_post('company'),
				'phone' => input_post('phone')
			];
			if ($this->ion_auth->update($user_sess->id, $data)) {
				$this->_set_message('success', $this->ion_auth->messages());
			} else {
				$this->_set_message('error', $this->ion_auth->errors());
			}
			redirect(base_url('administrator/settings/security'), 'refresh');
		}
	}
}
