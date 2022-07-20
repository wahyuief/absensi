<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends BackendController {

    public function __construct()
    {
        parent::__construct();
		if (!$this->ion_auth->logged_in()) redirect(base_url('auth/login'), 'refresh');
		$this->load->model('kmm_model');
    }

	public function index()
	{
		$this->data['message'] = $this->_show_message();
		if ($this->ion_auth->in_group('mahasiswa')) $id = $this->session->userdata('user_id');
		$search = (input_get('nama_kelas') ? ['nama_kelas' => input_get('nama_kelas')] : false);
		$this->data['total'] = $this->kmm_model->get(['kelas_matkul_mahasiswa.id_mahasiswa' => $id], $search)->num_rows();
		$this->data['pagination'] = new \yidas\data\Pagination([
			'perPageParam' => '',
			'totalCount' => $this->data['total'],
			'perPage' => 10,
		]);
		$this->data['start'] = ($this->data['total'] > 0 ? $this->data['pagination']->offset+1 : 0);
		$this->data['end'] = ($this->data['total'] > 0 ? $this->kmm_model->get(['kelas_matkul_mahasiswa.id_mahasiswa' => $id], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->num_rows() : 0);
		$this->data['datas'] = $this->kmm_model->get(['kelas_matkul_mahasiswa.id_mahasiswa' => $id], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->result();
		$this->data['user'] = $this->ion_auth->where('id', $id)->users()->row();
		$this->_render_page('dashboard', $this->data);
	}

	public function logout()
	{
		$this->ion_auth->logout();
		redirect(base_url('auth/login'), 'refresh');
	}
}
