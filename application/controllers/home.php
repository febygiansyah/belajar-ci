<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}
	
	public function index()
	{
		$data['judul'] = "Halaman Depan";
		$this->load->view('template/header',$data);
		$this->load->view('template/menu',$data);
		$this->load->view('template/banner',$data);
		$this->load->view('template/content',$data);
		$this->load->view('template/footer',$data);
		
	}
	
	public function about()
	{
		$data['judul'] = "Halama About";
		$this->load->view('about/header_about',$data);
		$this->load->view('template/menu',$data);
		$this->load->view('about/content',$data);
		$this->load->view('template/footer',$data);
	}

}