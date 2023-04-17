<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Default_model extends CI_Model{
	
	protected $super_employee_id = '';
	protected $company_name      = '';
	
	public function __construct()
	{
		parent::__construct('Default_model');
		$this->logged_in();
	}
	
	public function logged_in(){
		$this->super_employee_id = $this->session->userdata('super_employee_id');
		$this->company_name      = $this->session->userdata('company_name');
	}
	
	public function get_company_info(){
		$company_query     = $this->db->query("CALL bs_run('select company_name,mobile_number,email_id,alt_email_id, logo from bs_company_information where delete_status = 0 ')");
		$company_info    = $company_query->rows();
		$company_query->next_rslt();
		return $company_info;
	}
	
	public function get_emp_permission_info(){
		$this->logged_in();
		$permission_query     = $this->db->query("CALL bs_run('select * from bs_permission where super_employee_id = \"$this->super_employee_id\"')");
		$permission_info    = $permission_query->results();
		$permission_query->next_rslt();
		return $permission_info;
	}
	
	public function has_access($module_id,$login_id){
		$permission_query     = $this->db->query("CALL bs_run('select count(*) as rslt_count from bs_permission where module_name = \"$module_id\" and super_employee_id = \"$login_id\" and module_access = 1')");
		$results    = $permission_query->rows();
		$permission_query->next_rslt();
		$rslt_count = (int)$results['rslt_count'];
		if($rslt_count === 1){
			return true;
		}else{
			return false;
		}
	}
	
	public function signin_config(){
		$this->logged_in();
		if(isset($this->super_employee_id) && isset($this->company_name)){
			return true;
		}
		return false;
	}
	
	public function get_module_list(){
		$this->logged_in();
		$get_menu_list            = $this->db->query("CALL bs_get_menu_list('".$this->super_employee_id."')");
		$get_menu_rslt            = $get_menu_list->results();
		$get_menu_list->next_rslt();
		return $get_menu_rslt;
	}
	
	public function check_mail_exists($mail){
		$emp_query     = $this->db->query("CALL bs_run('select count(*) as rslt_count from bs_employee where email_id = \"$mail\"')");
		$results    = $emp_query->rows();
		$emp_query->next_rslt();
		$rslt_count = (int)$results['rslt_count'];
		if($rslt_count === 1){
			return true;
		}else{
			return false;
		}
	}
	
	public function get_click($mail){
		$emp_query     = $this->db->query("CALL bs_run('select user_name from bs_employee where email_id = \"$mail\"')");
		$results    = $emp_query->rows();
		$emp_query->next_rslt();
		$user_name  = $results['user_name'];
		return $this->encrypt_key($user_name);
	}
	
	public function check_username($user_name){
		$user_name     = $this->decrypt_key($user_name);
		$emp_query     = $this->db->query("CALL bs_run('select count(*) as rslt_count from bs_employee where user_name = \"$user_name\"')");
		$results    = $emp_query->rows();
		$emp_query->next_rslt();
		$rslt_count = (int)$results['rslt_count'];
		if($rslt_count === 1){
			return true;
		}else{
			return false;
		}
	}
	
	public function encrypt_key($data)
	{
		// Store the cipher method 
		$ciphering = "AES-128-CTR"; 
		  
		// Use OpenSSl Encryption method 
		$iv_length = openssl_cipher_iv_length($ciphering); 
		$options = 0; 
		  
		// Non-NULL Initialization Vector for encryption 
		$encryption_iv = '1234567891011121'; 
		  
		// Store the encryption key 
		$encryption_key = "HIMAT_DEVELOPERS"; 
		  
		// Use openssl_encrypt() function to encrypt the data 
		return openssl_encrypt($data, $ciphering, 
					$encryption_key, $options, $encryption_iv); 
	}
	public function decrypt_key($data)
	{
		// Store the cipher method 
		$ciphering = "AES-128-CTR"; 
		$options = 0; 
		
		// Non-NULL Initialization Vector for decryption 
		$decryption_iv = '1234567891011121'; 
		  
		// Store the decryption key 
		$decryption_key = "HIMAT_DEVELOPERS"; 
		  
		// Use openssl_decrypt() function to decrypt the data 
		return openssl_decrypt ($data, $ciphering,  
				$decryption_key, $options, $decryption_iv); 
	}
}
?>