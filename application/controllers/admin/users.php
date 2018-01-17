<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		// cek login
		$this->cek_login();
		
		//cek sebagai superadmin
		$this->isAdmin();
		
		$this->load->model('model_users');
	}
	
	public function index()
	{
		//Data page index
		$data['pageTitle'] = 'Users';
		$data['users'] = $this->model_users->get()->result();
		$data['pageContent'] = $this->load->view('admin/users/userList', $data, TRUE);
		
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/template/sidebar',$data);
		$this->load->view('admin/template/content',$data);
		$this->load->view('admin/template/footer',$data);
	}
	
	public function add()
	{
		//jika form disubmit jalankan blok ini
		if($this->input->post('submit'))
		{
			$this->form_validation->set_rules('username','Username','required|min_length[5]|is_unique[users.username]');
			$this->form_validation->set_rules('password','Password','required|min_length[5]');
			$this->form_validation->set_rules('level','Level','required|in_list[superadmin,admin]');
			$this->form_validation->set_rules('active','Active','required|in_list[0,1]');
			
			$this->form_validation->set_message('required','%s tidak boleh kosong!');
			$this->form_validation->set_message('min_length','%s minimal %d karakter!');
			
			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
				'username' => $this->input->post('username'),
				'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
				'level' => $this->input->post('level'),
				'active' => $this->input->post('active')
				);
				
				$query = $this->model_users->insert($data);
				
				//cek query
				if($query) $message = array('status' => true, 'message' => 'Berhasil menambahkan user');
				else $message = array('status' => true, 'message' => 'Gagal menambahkan user');
				
				//save message to session
				$this->session->set_flashdata('message', $message);
				
				//refresh page
				redirect('admin/users/add','refresh');
			}
		}
		
		//data untuk page users/add
		$data['pageTitle'] = 'Tambah Data User';
		$data['pageContent'] = $this->load->view('admin/users/userAdd', $data, TRUE);
		
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/template/sidebar',$data);
		$this->load->view('admin/template/content',$data);
		$this->load->view('admin/template/footer',$data);
		
	}
	
	public function edit($id = null)
	{
		if ($this->input->post('submit'))
		{
			$this->form_validation->set_rules('password','Password','required|min_length[5]');
			$this->form_validation->set_rules('level','Level','required|in_list[superadmin,admin]');
			$this->form_validation->set_rules('active','Active','required|in_list[0,1]');
			
			$this->form_validation->set_message('required','%s tidak boleh kosong!');
			$this->form_validation->set_message('min_length','%s minimal %d karakter!');
			
			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
				'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
				'level' => $this->input->post('level'),
				'active' => $this->input->post('active')
				);
				
				$query = $this->model_users->update($id, $data);
				
				if ($query) $message = array('status' => true, 'message' => 'Berhasil memperbaharui user');
				else $message = array('status' => true, 'message' => 'Gagal memperbaharui user');
				
				$this->session->set_flashdata('message',$message);
				
				redirect('admin/users/edit/'.$id, 'refresh');
			}
		}
		$user = $this->model_users->get_where(array('id' => $id))->row();
		if (!$user) show_404();
		
		$data['pageTitle'] = 'Edit Data User';
		$data['user'] = $user;
		$data['pageContent'] = $this->load->view('admin/users/userEdit', $data, TRUE);
		
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/template/sidebar',$data);
		$this->load->view('admin/template/content',$data);
		$this->load->view('admin/template/footer',$data);
	}
	
	public function delete($id)
	{
		$user = $this->model_users->get_where(array('id' => $id))->row();
		
		if (!$user) show_404();
		
		$query = $this->model_users->delete($id);
		
		if ($query) $message = array('status' => true, 'message' => 'Berhasil Menghapus user');
				else $message = array('status' => true, 'message' => 'Gagal Menghapus user');
				
				$this->session->set_flashdata('message',$message);
				
				redirect('admin/users', 'refresh');
	}
}