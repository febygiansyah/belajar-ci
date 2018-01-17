<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public function cek_login()
	{
		if(!$this->session->userdata('username'))
		{
			redirect('admin/auth/login');
		}
	}
	
	public function getUserData()
	{
		//ambil semua data session-
		$userData = $this->session->userData();
		
		return $userData;
	}
	
	public function isAdmin()
	{
		$userData = $this->getUserData();
		
		//jika level bukan administrator
		//direct ke halaman 404
		if($userData['level'] !== 'superadmin') show_404();
	}
	
}