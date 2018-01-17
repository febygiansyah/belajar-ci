<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function cek_akun()
	{
		//memanggil model users
		$this->load->model('model_users');
		
		//mengambil data dari form login dengan method POST
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		
		//jalankan function cek_akun pada model_users
		$query = $this->model_users->cek_akun($username, $password);
		
		//jika query gagal maka return false
		if (!$query)
		{
			//mengatur pesan error validasi data
			$this->form_validation->set_message('cek_akun','Username atau Password salah');
			return FALSE;
			
			//jika berhasil maka set user session dan return true
		}else{
			
			//data user dalam bentuk array
			$user_data = array(
			'id' => $query->id,
			'username' => $query->username,
			'level' => $query->level,
			'avatar' => $query->avatar,
			'nama' => $query->nama,
			'alamat' => $query->alamat,
			'telp' => $query->telp,
			'logged_in' => TRUE);
			
			//set session untuk user
			$this->session->set_userdata($user_data);
			return TRUE;
		}
	}
	
	public function login()
	{
		//jika user telah login, redirect ke base_url
		if($this->session->userdata('logged_id')) redirect(base_url());
		
		//jika form di submit jalankan blok kode ini_get
		if ($this->input->post('submit'))
		{
			//mengatur validasi data username,
			//required = tidak boleh kosong
			$this->form_validation->set_rules('username','Username','required');
			
			//mengatur validasi data password,
			//required = tida boleh kosong
			//callback_cek_akun = menjalankan function cek_akun()
			$this->form_validation->set_rules('password','Password','required|callback_cek_akun');
			
			//jalankan validasi jika semuanya benar maka redirect ke controller dashboard
			if ($this->form_validation->run() === TRUE)
			{
				redirect('admin/dashboard');
			}
		}
		//jalankan view auth/login.php
		$this->load->view('auth/login');
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('admin/auth/login');
	}
}