<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page_500 extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		if($this->model->signin_config()){
			$this->load->view($this->router->fetch_class());
		}else{
			redirect('login');
		}
	}
}
?>