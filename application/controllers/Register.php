<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Register extends MAIN_Register_controller {
	public function __construct()
	{
		parent::__construct('organizations');
	}
	//DEFAULT INDEX CALL
	public function index(){
		if($this->model->signin_config()){
			redirect('index');
		}else{
			$data['view_result']     = $this->view_result;
			$data['inputs_result']   = $this->inputs_result;
			$data['pick_result']     = $this->pick_result;
			$data['controller_name'] = "register";
			$this->load->view("register",$data);
		}
	}
	//FORM SAVE FUNACTIONALITY
	public function form_save(){
		if($this->input->post($this->super_name)){
			$super_id = (int)$this->input->post($this->super_name);
		}else{
			$super_id = 0;
		}
		$insert_column   = '';
		$insert_values   = '';
		$update_values   = '';
		foreach($this->view_result as $view_rlst){
			$super_view_id      = $view_rlst->super_view_id;  
			foreach($this->inputs_result[$super_view_id] as $inputs_rlst){
				$field_type             = (int)$inputs_rlst->field_type;
				$form_view              = $inputs_rlst->form_view;
				$column                 = $inputs_rlst->input_id;			
				//Multi Select Box
				if($field_type === 6){
					$column_value  = implode(",",$this->input->post($column."[]"));
				}else
				//Date
				if($field_type === 7){
					$column_value  = date('Y-m-d',strtotime($this->input->post($column)));
				}else
				//Time
				if($field_type === 8){
					$column_value  = date('H:i:s',strtotime($this->input->post($column)));
				}else
				//Auto Complete
				if($field_type === 10){
					$column_value  = trim($this->input->post('hidden_'.$column));
				}else
				//Check Box
				if($field_type === 13){
					$column_value  = (trim($this->input->post($column)) == 'on') ? 1 : 0;  
				}else{
					$column_value  = trim($this->input->post($column));
				}
				if($column === 'name'){
					$name = trim($this->input->post($column));
				}
				$insert_column   .= "`$column`,";
				$insert_values   .= "\"$column_value\",";
				$update_values   .= "`$column` = \"$column_value\",";
			}
		}
		$created_on      = date('Y-m-d H:i:s');
		if($super_id === 0){
			if($this->check_unique($name)){
				$insert_column   .= "`entry_created_by`,`entry_created_date`";
				$insert_values    .= "\"$this->super_employee_id\",\"$created_on\"";
				$insert_query    = "INSERT INTO bs_$this->controller_name ($insert_column) VALUES ($insert_values)";
				if($this->db->query("CALL bs_run('$insert_query')")){
					$insert_id = $this->db->query("CALL bs_run('SELECT LAST_INSERT_ID() as insert_id')");
					$result    = $insert_id->results();
					$insert_id->next_rslt();
					$insert_id              = $result[0]->insert_id;
					$name                   = $this->input->post("name");
					$organization_type      = (int)$this->input->post("organization_type");
					$phone_no               = $this->input->post("phone_no");
					$email_id               = $this->input->post("email_id");
					$pas                    = $this->input->post("password");
					if($organization_type === 0){
						$employee_role      = 6;
					}else{
						$employee_role      = 5;
					}
					if(isset($pas) && $pas != ''){
						$password  = md5($pas);
					}else{
						$password  = md5($phone_no);
					}
					$insert_query    = 'INSERT INTO bs_employee (`employee_name`,`employee_role`,`phone_no`,`email_id`,`branch_name`,`user_name`,`password`,`entry_created_by`,`entry_created_date`,`organization`) VALUES ("'.$name.'","'.$employee_role.'","'.$phone_no.'","'.$email_id.'","1","'.$email_id.'","'.$password.'","'.$this->super_employee_id.'","'.$created_on.'","'.$insert_id.'")';
					$this->db->query("CALL bs_run('$insert_query')");
					$insert_id = $this->db->query("CALL bs_run('SELECT LAST_INSERT_ID() as insert_id')");
					$result    = $insert_id->results();
					$insert_id->next_rslt();
					$insert_id              = $result[0]->insert_id;
					if($organization_type === 0){
						$employee_role      = 6;
						$insert_values      = "(\"donee\",\"$insert_id\",\"1\",\"1\",\"1\",\"1\",\"1\",\"1\"),(\"donate_food\",\"$insert_id\",\"1\",\"0\",\"0\",\"0\",\"0\",\"0\"),";
					}else{
						$insert_values      = "(\"donate\",\"$insert_id\",\"1\",\"0\",\"1\",\"0\",\"0\",\"0\"),(\"donate_food\",\"$insert_id\",\"1\",\"1\",\"1\",\"0\",\"0\",\"0\"),(\"donee\",\"$insert_id\",\"1\",\"0\",\"0\",\"0\",\"0\",\"0\"),";
					}
					$this->db->query("CALL bs_run('DELETE FROM bs_permission WHERE super_employee_id = \"$insert_id\"')");
					$insert_values = rtrim($insert_values,",");
					$insert_query  = "INSERT INTO bs_permission (`module_name`, `super_employee_id`, `module_access`, `add_access`, `edit_access`, `delete_access`, `import_access`, `export_access`) VALUES $insert_values";
					$this->db->query("CALL bs_run('$insert_query')");
					echo json_encode(array("request_status" => true,"message" =>'Successfully Added'));
				}else{
					echo json_encode(array("request_status" => false,"message" =>'Something went wrong,please contact admin'));
				}
			}else{
				echo json_encode(array("request_status" => false,"message" =>'Organization Name Already Exists!!'));
			}
		}else{
			if($this->check_unique($brand,$super_id)){
				$update_values    .= "`entry_updated_by` = \"$this->super_employee_id\",`entry_updated_date` = \"$created_on\"";
				$update_query = "UPDATE bs_$this->controller_name SET $update_values WHERE $this->super_name = $super_id";
				if($this->db->query("CALL bs_run('$update_query')")){
					echo json_encode(array("request_status" => true,"message" =>'Successfully Updated'));
				}else{
					echo json_encode(array("request_status" => false,"message" =>'Something went wrong,please contact admin'));
				}
			}else{
				echo json_encode(array("request_status" => false,"message" =>'Brand Name Already Exists!!'));
			}
		}		
	}
	//EDIT FORM FUNCATIONALITY
	public function edit(){
		$super_id = -1;
		if($this->input->post('super_id')){
			$super_id = (int)$this->input->post('super_id');
		}
		$base_query     = $this->db->query("CALL bs_run('select $this->edit_query from bs_$this->controller_name where bs_$this->controller_name.delete_status = 0 and super_$this->controller_name".'_id'." = $super_id')");
		$search_result    = $base_query->rows();
		$base_query->next_rslt();
		if(empty($search_result)){
			echo json_encode(array("request_status" => false));
		}else{
			echo json_encode(array("request_status" => true,"search_result" => $search_result));
		}
	}
	//DELETE FUNACTIONALITY
	public function delete(){
		$super_id       = $this->input->post('super_id');
		if(!empty($super_id)){
			$super_id       = '"'.implode('","', $super_id).'"';
			$created_on     = date('Y-m-d H:i:s');
			$update_values  = "`delete_status` = 1,`entry_deleted_by` = \"$this->super_employee_id\",`entry_deleted_date` = \"$created_on\"";
			$update_query = "UPDATE bs_$this->controller_name SET $update_values WHERE $this->super_name IN ($super_id)";
			if($this->db->query("CALL bs_run('$update_query')")){
				echo json_encode(array("request_status" => true,"message" =>'Deleted Successfully'));
			}else{
				echo json_encode(array("request_status" => false,"message" =>'Something went wrong,please contact admin'));
			}
		}else{
			echo json_encode(array("request_status" => false,"message" =>'Something went wrong,please contact admin'));
		}
	}
	//AUTO COMPLETE FUNACTIONALITY
	public function get_auto_complete(){
		$select_table        = $this->input->post('select_table');
		$pick_table_value    = $this->input->post('pick_table_value');
		$auto_complete_query = $this->db->query("CALL bs_run('select $pick_table_value from $select_table where $select_table.delete_status = 0')");
		$auto_complete_rlst  = $auto_complete_query->results();
		$auto_complete_query->next_rslt();
		$pick_table_value   = explode(",",$pick_table_value);
		$value_01           = $pick_table_value[0];
		$value_02           = $pick_table_value[1];
		$rslt               = array();
		$rslt_data          = array();
		foreach($auto_complete_rlst as $result){
			$rslt[$result->$value_02]      = null;
			$rslt_data[$result->$value_02] = $result->$value_01;
		}
		echo json_encode(array("request_status" => true,"rslt" =>$rslt,'rslt_data'=>$rslt_data));
	}
	// CHECK UNIQUE BRAND VALUE
	function check_unique($name,$super_organizations_id = -1){
		if($name){
			$add_query    = '';
			if($super_organizations_id > 0){
				$add_query = " and super_organizations_id != $super_organizations_id";
			}
			$check_qry     = "SELECT count(*) as rslt_count from bs_organizations where delete_status = 0 and name = \"$name\" $add_query";
			$check_qry     = $this->db->query("CALL bs_run('$check_qry')");
			$column_result = $check_qry->rows();
			$check_qry->next_rslt();
			$rslt_count    = (int)$column_result['rslt_count'];
			if($rslt_count === 0){
				return true;
			}else{
				return false;
			}
		}
	}
}
?>