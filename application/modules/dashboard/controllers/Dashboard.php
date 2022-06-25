<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends BackendController {

    public function __construct()
    {
        parent::__construct();
		if (!$this->ion_auth->logged_in()) redirect(base_url('auth/login'), 'refresh');
    }

	public function index()
	{
		$this->data['message'] = $this->_show_message();
		$this->_render_page('dashboard', $this->data);
	}

	public function logout()
	{
		$this->ion_auth->logout();
		redirect(base_url('auth/login'), 'refresh');
	}
}
