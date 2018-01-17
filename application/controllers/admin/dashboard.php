<?php defined("BASEPATH")OR exit("No direct script access allowed");

class Dashboard extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->cek_login();
		
		//load model
		$this->load->model('model_events');
		$this->load->model('model_loker');
	}
	
	public function index()
	{
		//ambil data event
		$event = $this->model_events->get_where(array('tanggal_berakhir >=' => date('Y-m-d')
		))->row();
		
		//ambil data loker
		$loker = $this->model_loker->get_where(array('tanggal_berakhir >=' =>date('Y-m-d')
		))->row();
		
		//data untuk page index
		$data['event'] = $event;
		$data['loker'] = $loker;
		$data["pageTitle"]= "Dashboard";
		$data["pageContent"]= $this->load->view('dashboard/content', $data, TRUE);
		
		$this->load->view('admin/template/header',$data);
		$this->load->view('admin/template/sidebar',$data);
		$this->load->view('admin/template/content',$data);
		$this->load->view('admin/template/footer',$data);
	}
	
	public function routing_ka_ayah()
	{
		echo 'Hahaha';
	}
}