<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	
	public function index(){
		if($this->model->signin_config()){
			redirect('organizations');
		}else{
			$this->load->view('login');
		}
	}
	
	public function emp_sign_in(){
		$user_name = $this->input->post('user_name'); 
		$password  = md5($this->input->post('password'));
		$query     = $this->db->get_where('employee',array('user_name' => $user_name,'password'=>$password,'delete_status' => 0)); 
		$query     = "SELECT super_employee_id,employee_name,employee_role,branch_name,organization from bs_employee where delete_status = 0 and user_name = \"$user_name\" and password = \"$password\"";
		$query     = $this->db->query("CALL bs_run('$query')");
		$result    = $query->rows();
		$query->next_rslt();
		if((int)$query->num_rows() === 1){
			$this->set_session_value($result);
			if($this->model->signin_config()){
				echo json_encode(array('request_status'=>true,'message'=>'Login Success..'));
			}else{
				echo json_encode(array('request_status'=>false,'message'=>'Something went wrong,please contact admin'));					
			}
		}else{
			echo json_encode(array('request_status'=>false,'message'=>'Invalid Username or Password'));
		}
	}
	
	public function logout(){
		if(isset($_SESSION['organization'])){
			$this->session->sess_destroy();
			echo json_encode(array('request_status'=>true,'message'=>'Logout Success..!','redirect'=>'charity_register'));
		}else{
			$this->session->sess_destroy();
			echo json_encode(array('request_status'=>true,'message'=>'Logout Success..!','redirect'=>'admin'));
		}
    }  
	
	public function set_session_value($result){
		$get_company_info  = $this->model->get_company_info();
		$query       = "SELECT name,phone_no,organization_type,email_id from bs_organizations where super_organizations_id = \'".$result['organization']."\'";
		$query       = $this->db->query("CALL bs_run('$query')");
		$org_result  = $query->rows();
		$query->next_rslt();
		$set_session_data  = array(
			'super_employee_id'     => $result['super_employee_id'],
			'organization'          => $result['organization'],
			'organization_name'     => $org_result['name'],
			'organization_phone_no' => $org_result['phone_no'],
			'organization_email_id' => $org_result['email_id'],
			'organization_type'     => $org_result['organization_type'],
			'employee_name'         => $result['employee_name'],
			'employee_role'         => $result['employee_role'],
			'branch_name'           => $result['branch_name'],
			'company_name'          => $get_company_info['company_name'],
			'phone'                 => $get_company_info['mobile_number'],
			'email'                 => $get_company_info['email_id'],
			'logo'                  => $get_company_info['logo'],
			'alt_email_id'          => $get_company_info['alt_email_id']
		);
		$this->session->set_userdata($set_session_data);	
		$get_emp_permission_info  = $this->model->get_emp_permission_info();
		$permission_info          = array_map(function ($check_array){
										$return_arr              = array();
										$return_arr['module_name'] = $check_array->module_name;
										$return_arr['module_info'] = $check_array;
										return $return_arr;
									},$get_emp_permission_info);
		$permission_info          = array_column($permission_info,'module_info','module_name');
		$this->session->set_userdata('permission_info',$permission_info);	
	}
	
	public function forgot_password(){
		$email        = $this->input->post('email');
		if($this->model->check_mail_exists($email)){
			$to       = $email;
			$base_url = base_url();
			$subject  = 'Forgot Password';
			$headers  = "From: admin@himat.in". "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			$message  = "<html><body><p>To change your password, <a href=\"$base_url"."change_password/change_password.php?frm=".$this->model->get_click($email)."\" >click here</a></p><p>Note: Please Kindly delete this mail after reading <\p></body></html>";
			if(mail($to,$subject,$message,$headers)){
				echo json_encode(array('request_status'=>true,'message'=>"The email sent successfully"));
			}else{
				echo json_encode(array('request_status'=>false,'message'=>"Something Went wrong..!",'responseText'=>"Something Went wrong..!"));
			}
		}else{
			echo json_encode(array('request_status'=>false,'message'=>"Email id not exists"));
		}
	}
	//pending
	public function change_password(){
		$frm          = $this->input->post('frm');
		$new_password = $this->input->post('new_password');
		$con_password = $this->input->post('con_password');
		if($this->model->check_username($frm)){
			if($new_password == $con_password){
				$created_on     = date('Y-m-d H:i:s');
				$con_password   = md5($con_password);
				$update_values  = "`password` = \"$con_password\",`entry_deleted_by` =\"1\",`entry_deleted_date` = \"$created_on\"";
				$frm          = $this->model->decrypt_key($frm);
				$update_query = "UPDATE bs_employee SET $update_values WHERE user_name = \"$frm\"";
				if($this->db->query("CALL bs_run('$update_query')")){
					echo json_encode(array('request_status'=>true,'message'=>"Password Changed Successfully"));
				}else{
					echo json_encode(array('request_status'=>false,'errorMessage'=>"Something Went wrong..!",'responseText'=>"Something Went wrong..!"));
				}
			}else{
				echo json_encode(array('request_status'=>false,'errorMessage'=>"Something Went wrong..!",'responseText'=>"Something Went wrong..!"));
			}
		}else{
			echo json_encode(array('request_status'=>false,'errorMessage'=>"Something Went wrong..!",'responseText'=>"Something Went wrong..!"));
		}
	}
}
