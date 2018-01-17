<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller{
	
	public function __construct()
	{
		parent::__construct();
		$this->cek_login();
		$this->load->model('model_users');
	}
	
	public function index()
	{
		if ($this->input->post('submit-information')){
			//jika avatar diganti
			if (!empty($_FILES['avatar']['name'])){
				//konf library upload CI
				$config['upload_path'] = './assets/images/';
				$config['allowed_type'] = 'gif|jpg|png';
				$config['max_size'] = 2000;
				$config['file_name'] = $this->session->userdata('username'). '_'.date('YmdHis');
				
				//load library upload
				$this->load->library('upload',$config);
				
				//jika error upload
				if (!$this->upload->do_upload('avatar')){
					exit($this->upload->display_errors());
				}
				$data['avatar'] = $this->upload->data()['file_name'];
			}
			
			$this->form_validation->set_rules('nama','Nama','required');
			$this->form_validation->set_rules('alamat','Alamat','required');
			$this->form_validation->set_rules('telp','Telepon','required');
			$this->form_validation->set_message('required','%s tidak boleh kosong!');
			
			if($this->form_validation->run() === TRUE){
				
				$data['nama'] = $this->input->post('nama');
				$data['alamat'] = $this->input->post('alamat');
				$data['telp'] = $this->input->post('telp');
				
				$userID = $this->session->userdata('id');
				$query = $this->model_users->update($userID, $data);
				
				//cek query
				if ($query) {
					$message = array('status' => true, 'message' => 'Berhasil memperbarui profile');
					$this->session->set_userdata($data);
				}else{
					$message = array('status' => false, 'message' => 'Gagal memperbarui profile');
				}
				
				$this->session->set_flashdata('message_profile', $message);
				redirect('admin/profile','refresh');
			}
		}
		
		//form password
		if ($this->input->post('submit-password')){
			$this->form_validation->set_rules('password_lama','Password Lama','required|callback_cekPasswordLama');
			$this->form_validation->set_rules('password_baru','Password Baru','required|min_length[5]');
			$this->form_validation->set_rules('konfirmasi_password','Konfirmasi Password','required|matches[password_baru]');
			
			//pesan error
			$this->form_validation->set_message('required', '%s tidak boleh kosong!');
			$this->form_validation->set_message('min_length', '{field} minimal {param} karakter.');
			$this->form_validation->set_message('matches', '{field} tidak sama dengan {param}.');
			
			if ($this->form_validation->run() === TRUE){
				
				$data = array(
				'password' => password_hash($this->input->post('konfirmasi_password'),PASSWORD_DEFAULT)
				);
				
				//ambil user ID
				$userID = $this->session->userdata('id');
				
				//jalankan function update pada model_users
				$query = $this->model_users->update($userID, $data);
				
				if ($query){
					redirect('admin/auth/logout');
				} else {
					$message = array('status' => false, 'message' => 'Gagal memperbarui profile');
				}
				$this->session->set_flashdata('message_profile', $message);
				redirect('admin/profile','refresh');
			}
		}
		//data untuk page profile
		$data['pageTitle'] = 'Profile';
		$data['pageContent'] = $this->load->view('admin/profile/profile',$data, TRUE);
		
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/template/sidebar',$data);
		$this->load->view('admin/template/content',$data);
		$this->load->view('admin/template/footer',$data);
	}
	
	public function cekPasswordLama()
	{
		//ambil userid dari session-
		$userID = $this->session->userdata('id');
		
		$password = $this->input->post('password_lama');
		$query = $this->model_users->cekPasswordLama($userID, $password);
		
		if ($query) {
			$this->form_validation->set_message('cekPasswordLama','Password lama tidak sesuai!');
			return false;
		}
		return true;
	}
	
}