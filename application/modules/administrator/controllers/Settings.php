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

	public function absensi()
	{
		$user_sess = $this->ion_auth->user()->row();
		$this->form_validation->set_rules('longitude', 'longitude', 'trim|required');
		$this->form_validation->set_rules('latitude', 'latitude', 'trim|required');

		if ($this->form_validation->run() === FALSE) {
			$this->data['message'] = $this->_show_message('error', $this->ion_auth->errors());
			$this->_render_page('settings/absensi', $this->data);
		} else {
			set_option('latitude', input_post('latitude'));
			set_option('longitude', input_post('longitude'));
			redirect(base_url('administrator/settings/absensi'), 'refresh');
		}
	}

	public function check_location()
	{
		$latitude = $this->input->post('latitude');
		$longitude = $this->input->post('longitude');
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://photon.komoot.io/reverse?lat='.$latitude.'&lon='.$longitude,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_SSL_VERIFYPEER => false
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$data = json_decode($response);
		$nama_tempat = $data->features[0]->properties->name;
		$city = $data->features[0]->properties->city;
		$street = $data->features[0]->properties->street;
		$district = $data->features[0]->properties->district;
		$postcode = $data->features[0]->properties->postcode;
		echo $nama_tempat . '. ' . $street . ', ' . $district . ', ' . $city . ' ' . $postcode;
	}
}
