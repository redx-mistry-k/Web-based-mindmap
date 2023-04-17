<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index(){
		$data['controller_name']  = "Home";
		$this->load->view('index',$data);
	}
}
?>