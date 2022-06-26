<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Foto extends BackendController {

    public function __construct()
    {
        parent::__construct();
		if (!$this->ion_auth->logged_in()) redirect(base_url('auth/login'), 'refresh');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->lang->load('auth');
		$this->load->model('foto_model');
    }

	public function index($id)
	{
		$id = wah_decode($id);
		$search = (input_get('nama_kelas') ? ['nama_kelas' => input_get('nama_kelas')] : false);
		$this->data['total'] = $this->foto_model->get(['users_photos.id_user' => $id], $search)->num_rows();
		$this->data['pagination'] = new \yidas\data\Pagination([
			'perPageParam' => '',
			'totalCount' => $this->data['total'],
			'perPage' => 10,
		]);
		$this->data['start'] = ($this->data['total'] > 0 ? $this->data['pagination']->offset+1 : 0);
		$this->data['end'] = ($this->data['total'] > 0 ? $this->foto_model->get(['users_photos.id_user' => $id], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->num_rows() : 0);
		$this->data['datas'] = $this->foto_model->get(['users_photos.id_user' => $id], $search, $this->data['pagination']->limit, $this->data['pagination']->offset)->result();
		$this->data['user'] = $this->ion_auth->where('id', $id)->users()->row();
		$this->data['message'] = $this->_show_message();

		$this->_render_page('mahasiswa/foto/list', $this->data);
	}

	public function add($id)
	{
		$this->data['message'] = $this->_show_message('error', validation_errors());
		$this->data['foto'] = $this->foto_model->get(['id_user' => wah_decode($id)])->result();
		$this->data['user'] = $this->ion_auth->where('id', wah_decode($id))->users()->row();
		$this->_render_page('mahasiswa/foto/add', $this->data);
	}

	public function upload()
	{
		$id_user = wah_decode(input_post('user'));
		$user = $this->ion_auth->where('id', $id_user)->users()->row();
		$foto = $this->foto_model->get();

		$data = [
			'id_user' => $id_user,
			'photo' => $this->base64_to_jpeg($this->input->post('foto'), $user->fullname, ($foto->num_rows()+1) . '.jpeg')
		];
		
		if ($foto->num_rows() >= 50) {
			return false;
		}

		if ($this->foto_model->add($data)) {
			echo json_encode(array(
				'success' => true,
				'user' => $user->fullname,
				'jumlah' => $foto->num_rows()
			));
		}
	}

	function base64_to_jpeg($base64_string, $fullname, $output_file) {
		$filename = 'assets/facedetection/images/'.$fullname.'/'.$output_file;
		$dirname = dirname($filename);
		if (!is_dir($dirname)) mkdir($dirname, 0755, true);
		$ifp = fopen( $filename, 'wb' ); 
		$data = explode( ',', $base64_string );
		fwrite( $ifp, base64_decode( $data[ 1 ] ) );
		fclose( $ifp ); 
		return $output_file; 
	}
}
