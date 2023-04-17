<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MAIN_Controller extends CI_Controller {
	public function __construct($module_name = NULL){
		parent::__construct();
	}
	public function set_session_value($result){
		$get_company_info  = $this->model->get_company_info();
		$set_session_data  = array(
			'super_employee_id' => $result['super_employee_id'],
			'organization'      => $result['organization'],
			'employee_name'     => $result['employee_name'],
			'employee_role'     => $result['employee_role'],
			'branch_name'       => $result['branch_name'],
			'company_name'      => $get_company_info['company_name'],
			'phone'             => $get_company_info['mobile_number'],
			'email'             => $get_company_info['email_id'],
			'logo'              => $get_company_info['logo'],
			'alt_email_id'      => $get_company_info['alt_email_id']
		);
		$this->session->set_userdata($set_session_data);	
		$get_emp_permission_info  = $this->model->get_emp_permission_info();
		$permission_info  = array_map(function ($check_array){
							$return_arr              = array();
							$return_arr['module_name'] = $check_array->module_name;
							$return_arr['module_info'] = $check_array;
							return $return_arr;
						},$get_emp_permission_info);
		$permission_info  = array_column($permission_info,'module_info','module_name');
		$this->session->set_userdata('permission_info',$permission_info);	
	}
}
class MAIN_Baisc_Controller extends MAIN_Controller {
	public $super_employee_id;
	public $super_role;
	public $branch;
	public $dynamic_values;
	public $controller_name;
	public $super_name;
	public $view_result   = array();
	public $inputs_result = array();
	public $pick_result   = array();
	public $module_name   = '';
	public $main_query    = '';
	public $count_query   = '';
	public $edit_query    = '';
	public $organization  = '';
	
	public function __construct($module_name = NULL){
		parent::__construct();
		if(!$this->model->signin_config()){	
			redirect('admin');
		}
		$this->super_employee_id  = $this->session->userdata('super_employee_id');
		$this->super_role         = $this->session->userdata('employee_role');
		$this->branch             = $this->session->userdata('branch_name');
		$this->organization       = $this->session->userdata('organization');
		if(!$this->model->has_access($module_name,$this->super_employee_id)){
			redirect('page_500');
		}
		$data['signin_id']        = $this->super_employee_id;
		$data['super_role']       = $this->super_role;
		$data['organization']     = $this->organization;
		$data['controller_name']  = $module_name;
		$data['super_name']       = "super_$module_name"."_id";
		$data['menus_rslt']       = $this->model->get_module_list();
		
		$this->module_name        = $module_name;
		$this->controller_name    = $module_name;
		$this->super_name         = "super_$module_name"."_id";
        $this->load->vars($data);
		$this->get_all_information();
	}
	
	public function get_all_information(){
		$module_setting_result      = $this->db->query("CALL bs_module_setting('".$this->module_name."')");
		$module_result              = $module_setting_result->results();
		$module_setting_result->next_rslt();
		$super_module_creation_id   = $module_result[0]->super_module_creation_id;
		$view_setting_qry           = $this->db->query("CALL bs_view_setting('".$super_module_creation_id."','".$this->super_role."')");
		$this->view_result          = $view_setting_qry->results();
		$view_setting_qry->next_rslt();
		$input_setting_qry          = $this->db->query("CALL bs_input_setting('".$super_module_creation_id."','".$this->super_role."')");
		$input_setting_rlst         = $input_setting_qry->results();
		$input_setting_qry->next_rslt();
		$module_name                = "bs_".$this->module_name;
		$select_query               = "";
		$join_query                 = "";
		$count                      = "A";
		foreach($input_setting_rlst as $inputs_rlst){
			$super_input_setting_id = $inputs_rlst->super_input_setting_id;
			$input_id               = $inputs_rlst->input_id;
			$field_type             = (int)$inputs_rlst->field_type;
			$table_view             = (int)$inputs_rlst->table_view;
			$mandatory              = (int)$inputs_rlst->mandatory;
			$this->edit_query      .= "$module_name.$input_id,";
			if($table_view === 1){
				if($field_type === 5 || $field_type === 6 || $field_type === 10){
					if($mandatory === 1){
						$con = "INNER";
					}else{
						$con = "LEFT";
					}
					$picklist_type      = (int)$inputs_rlst->picklist_type;
					$pick_table_value   = explode(",",$inputs_rlst->pick_table_value);
					if($picklist_type === 1){
						$select_table              = $inputs_rlst->select_table;
						$value_01                  = $pick_table_value[0];
						$value_02                  = $pick_table_value[1];
						$select_pick_query         = "SELECT $inputs_rlst->pick_table_value from $select_table where delete_status = 0";
						$input_setting_qry         = $this->db->query("CALL bs_run('".$select_pick_query."')");
						$input_rlst                = $input_setting_qry->results();
						$input_setting_qry->next_rslt();
						$return_arr = array();
						foreach($input_rlst as $value){
							$return_arr[$value->$value_01] = $value->$value_02;
						}
						$this->pick_result[$super_input_setting_id] = $return_arr;
						$select_query    .= "$count.$value_02 as $input_id,";
						$join_query      .= " $con JOIN $select_table as $count ON $count.$value_01 = $module_name.$input_id ";
					}else
					if($picklist_type === 2){
						$this->pick_result[$super_input_setting_id] = $pick_table_value;
						$select_query    .= "$module_name.$input_id,";
					}
				}else{
					$select_query    .= "$module_name.$input_id,";
				}
			}else
			if($field_type === 5 || $field_type === 6 || $field_type === 10){
				$picklist_type      = (int)$inputs_rlst->picklist_type;
				$pick_table_value   = explode(",",$inputs_rlst->pick_table_value);
				if($picklist_type === 1){
					$select_table              = $inputs_rlst->select_table;
					$value_01                  = $pick_table_value[0];
					$value_02                  = $pick_table_value[1];
					$select_pick_query         = "SELECT $inputs_rlst->pick_table_value from $select_table where delete_status = 0";
					$input_setting_qry         = $this->db->query("CALL bs_run('".$select_pick_query."')");
					$input_rlst                = $input_setting_qry->results();
					$input_setting_qry->next_rslt();
					$return_arr = array();
					foreach($input_rlst as $value){
						$return_arr[$value->$value_01] = $value->$value_02;
					}
					$this->pick_result[$super_input_setting_id]  = $return_arr;
				}else
				if($picklist_type === 2){
					$this->pick_result[$super_input_setting_id] = $pick_table_value;
				}
			}
			$this->inputs_result[$inputs_rlst->form_view][] = $inputs_rlst;
			$count++;
		}
		if($select_query){
			$select_query   = rtrim($select_query,",");
		}
		if($this->edit_query){
			$this->edit_query  = "$module_name.$this->super_name,".rtrim($this->edit_query,",");
		}
		$this->main_query = "select $module_name.$this->super_name,$select_query from $module_name $join_query where $module_name.delete_status = 0";
		$this->count_query = "select count(*) as rslt_count from $module_name $join_query where $module_name.delete_status = 0";
		/* if((int)$this->super_role <= 3){
			$check_array = array('branch','company_information','role','employee');
			if(!in_array($this->module_name, $check_array)){
				if((int)$this->super_role === 3){
					$this->main_query  .= " and $module_name.entry_branch = $this->branch";
					$this->count_query .= " and $module_name.entry_branch = $this->branch";
				}
			}else{
				$this->main_query .= " and ($module_name.entry_created_by = $this->super_employee_id or $this->super_role IN (\"1\",\"2\"))";
				$this->count_query .= " and ($module_name.entry_created_by = $this->super_employee_id or $this->super_role IN (\"1\",\"2\"))";
			}
		}else{
			$this->main_query .= " and ($module_name.entry_branch = $this->branch)";
			$this->count_query .= " and ($module_name.entry_created_by = $this->super_employee_id and $module_name.entry_branch = $this->branch)";
		} */
	}
}
class MAIN_Register_controller extends MAIN_Controller {
	public $super_employee_id;
	public $super_role;
	public $branch;
	public $dynamic_values;
	public $controller_name;
	public $super_name;
	public $view_result   = array();
	public $inputs_result = array();
	public $pick_result   = array();
	public $module_name   = '';
	public $main_query    = '';
	public $count_query   = '';
	public $edit_query    = '';
	
	public function __construct($module_name = NULL){
		parent::__construct();
		$this->super_employee_id  = 1;
		$this->super_role         = 1;
		$this->branch             = 1;
		$data['signin_id']        = $this->super_employee_id;
		$data['super_role']       = $this->super_role;
		$data['controller_name']  = $module_name;
		$data['super_name']       = "super_$module_name"."_id";
		$data['menus_rslt']       = $this->model->get_module_list();
		$this->module_name        = $module_name;
		$this->controller_name    = $module_name;
		$this->super_name         = "super_$module_name"."_id";
        $this->load->vars($data);
		$this->get_all_information();
	}
	
	public function get_all_information(){
		$module_setting_result      = $this->db->query("CALL bs_module_setting('".$this->module_name."')");
		$module_result              = $module_setting_result->results();
		$module_setting_result->next_rslt();
		$super_module_creation_id   = $module_result[0]->super_module_creation_id;
		$view_setting_qry           = $this->db->query("CALL bs_view_setting('".$super_module_creation_id."','".$this->super_role."')");
		$this->view_result          = $view_setting_qry->results();
		$view_setting_qry->next_rslt();
		$input_setting_qry          = $this->db->query("CALL bs_input_setting('".$super_module_creation_id."','".$this->super_role."')");
		$input_setting_rlst         = $input_setting_qry->results();
		$input_setting_qry->next_rslt();
		$module_name                = "bs_".$this->module_name;
		$select_query               = "";
		$join_query                 = "";
		$count                      = "A";
		foreach($input_setting_rlst as $inputs_rlst){
			$super_input_setting_id = $inputs_rlst->super_input_setting_id;
			$input_id               = $inputs_rlst->input_id;
			$field_type             = (int)$inputs_rlst->field_type;
			$table_view             = (int)$inputs_rlst->table_view;
			$mandatory              = (int)$inputs_rlst->mandatory;
			$this->edit_query      .= "$module_name.$input_id,";
			if($table_view === 1){
				if($field_type === 5 || $field_type === 6 || $field_type === 10){
					if($mandatory === 1){
						$con = "INNER";
					}else{
						$con = "LEFT";
					}
					$picklist_type      = (int)$inputs_rlst->picklist_type;
					$pick_table_value   = explode(",",$inputs_rlst->pick_table_value);
					if($picklist_type === 1){
						$select_table              = $inputs_rlst->select_table;
						$value_01                  = $pick_table_value[0];
						$value_02                  = $pick_table_value[1];
						$select_pick_query         = "SELECT $inputs_rlst->pick_table_value from $select_table where delete_status = 0";
						$input_setting_qry         = $this->db->query("CALL bs_run('".$select_pick_query."')");
						$input_rlst                = $input_setting_qry->results();
						$input_setting_qry->next_rslt();
						$return_arr = array();
						foreach($input_rlst as $value){
							$return_arr[$value->$value_01] = $value->$value_02;
						}
						$this->pick_result[$super_input_setting_id]  = $return_arr;
						if($select_table == "organizations"){
							print_r($this->pick_result);die;
						}
						$select_query    .= "$count.$value_02 as $input_id,";
						$join_query      .= " $con JOIN $select_table as $count ON $count.$value_01 = $module_name.$input_id ";
					}else
					if($picklist_type === 2){
						$this->pick_result[$super_input_setting_id] = $pick_table_value;
						$select_query    .= "$module_name.$input_id,";
					}
				}else{
					$select_query    .= "$module_name.$input_id,";
				}
			}else
			if($field_type === 5 || $field_type === 6 || $field_type === 10){
				$picklist_type      = (int)$inputs_rlst->picklist_type;
				$pick_table_value   = explode(",",$inputs_rlst->pick_table_value);
				if($picklist_type === 1){
					$select_table              = $inputs_rlst->select_table;
					$value_01                  = $pick_table_value[0];
					$value_02                  = $pick_table_value[1];
					$select_pick_query         = "SELECT $inputs_rlst->pick_table_value from $select_table where delete_status = 0";
					$input_setting_qry         = $this->db->query("CALL bs_run('".$select_pick_query."')");
					$input_rlst                = $input_setting_qry->results();
					$input_setting_qry->next_rslt();
					$return_arr = array();
					foreach($input_rlst as $value){
						$return_arr[$value->$value_01] = $value->$value_02;
					}
					$this->pick_result[$super_input_setting_id]  = $return_arr;
				}else
				if($picklist_type === 2){
					$this->pick_result[$super_input_setting_id] = $pick_table_value;
				}
			}
			$this->inputs_result[$inputs_rlst->form_view][] = $inputs_rlst;
			$count++;
		}
		if($select_query){
			$select_query   = rtrim($select_query,",");
		}
		if($this->edit_query){
			$this->edit_query  = "$module_name.$this->super_name,".rtrim($this->edit_query,",");
		}
		$this->main_query = "select $module_name.$this->super_name,$select_query from $module_name $join_query where $module_name.delete_status = 0";
		$this->count_query = "select count(*) as rslt_count from $module_name $join_query where $module_name.delete_status = 0";
		/* if((int)$this->super_role <= 3){
			$check_array = array('branch','company_information','role','employee');
			if(!in_array($this->module_name, $check_array))
			{
				if((int)$this->super_role === 3){
					$this->main_query  .= " and $module_name.entry_branch = $this->branch";
					$this->count_query .= " and $module_name.entry_branch = $this->branch";
				}
			}else{
				$this->main_query .= " and ($module_name.entry_created_by = $this->super_employee_id or $this->super_role IN (\"1\",\"2\"))";
				$this->count_query .= " and ($module_name.entry_created_by = $this->super_employee_id or $this->super_role IN (\"1\",\"2\"))";
			}
		}else{
			$this->main_query .= " and ($module_name.entry_created_by = $this->super_employee_id and $module_name.entry_branch = $this->branch)";
			$this->count_query .= " and ($module_name.entry_created_by = $this->super_employee_id and $module_name.entry_branch = $this->branch)";
		} */
	}
}
?>