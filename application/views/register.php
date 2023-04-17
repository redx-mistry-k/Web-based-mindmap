<?php
	$access_data            = $this->session->userdata('permission_info');
	$form_container         = '';
	$li_line                = ''; 
	$onload_script          = '';
	$i                      = 1;
	$form_validation_rule   = '';
	$table_column           = '';
	$filter_table_val       = '';
	$filter_table           = '';
	foreach($view_result as $view_rlst){
		$super_view_id      = $view_rlst->super_view_id;  
		$form_view_name     = $view_rlst->form_view_name;  
		$view_type          = $view_rlst->view_type;  
		$add_form_name      = $view_rlst->add_form_name;  
		$input_fields       = '';
		/* echo "<pre>";
			print_r($inputs_result[$super_view_id]);die; */
		foreach($inputs_result[$super_view_id] as $inputs_rlst){
			$super_input_setting_id = $inputs_rlst->super_input_setting_id;
			$module_id              = $inputs_rlst->module_id;
			$field_type             = (int)$inputs_rlst->field_type;
			$form_view              = $inputs_rlst->form_view;
			$input_id               = $inputs_rlst->input_id;
			$view_name              = $inputs_rlst->view_name;
			$length                 = $inputs_rlst->length;
			$decimal_value          = $inputs_rlst->decimal_value;
			$picklist_type          = (int)$inputs_rlst->picklist_type;
			$select_table           = $inputs_rlst->select_table;
			$extension_type         = $inputs_rlst->extension_type;
			$pick_table_value       = $inputs_rlst->pick_table_value;
			$default_value          = $inputs_rlst->default_value;
			$mandatory              = (int)$inputs_rlst->mandatory;
			$table_view             = (int)$inputs_rlst->table_view;
			$filter_view            = (int)$inputs_rlst->filter_view;
			$show_feild             = (int)$inputs_rlst->show_feild;
			if($show_feild === 1){
				$title                  = ucwords(str_replace("_"," ","$view_name"));
				if($filter_view === 1 && $field_type != 12){
					$con_id                 = "con_".$input_id;
					$filetr_id              = "filter_".$input_id;
					if($field_type === 5 || $field_type === 6  || $field_type === 10){
						$filter_con_array  = array(''=>'select','IN'=>'IN','NOT IN'=>'NOT IN');
						$filter_input      =  form_dropdown(array('class' => 'validate select','id' => $filetr_id,'name' => $filetr_id."[]",'multiple'=>true,'placeholder' => $view_name), $pick_result[$super_input_setting_id]);
					}else
					if($field_type === 7){
						$filter_con_array   = array('='=>'=');
						$filter_input       =  form_input(array('class' => 'datepicker','id' => $filetr_id,'name' => $filetr_id,'placeholder' => $view_name));
					}else
					if($field_type === 8){
						$filter_con_array   = array('='=>'=');
						$filter_input    =  form_input(array('class' => 'timepicker','id' => $filetr_id,'name' => $filetr_id,'placeholder' => $view_name));
					}else{
						$filter_con_array  = array(''=>'select','LIKE'=>'LIKE','='=>'=');
						$filter_input      =  form_input(array('class' => 'validate alpha_numeric','id' => $filetr_id,'name' => $filetr_id,'placeholder' => $view_name));
					}
					$con_input              =  form_dropdown(array('class' => 'validate select','id' => $con_id,'name' => $con_id), $filter_con_array);
					$filter_table       .= "<tr><td>$view_name</td><td>$con_input</td><td>$filter_input</td></tr>";
					$filter_table_val   .= "var $con_id = $('#$con_id').val(); \n data.$con_id = $con_id; \n var $filetr_id = $('#$filetr_id').val(); \n data.$filetr_id = $filetr_id; \n";
				}
				if($table_view === 1){
					//Date
					if($field_type === 7){
						$table_column .= "{
											title: '$title',
											data: '$input_id',
											render:function(value){
												if (value === null) return '';
												return  moment(value).format('DD/MM/YYYY');
											}
										},";
					}else
					//Time
					if($field_type === 8){
						$table_column .= "{
											title: '$title',
											data: '$input_id',
											render:function(value){
												if (value === null) return '';
												return  moment(value).format('HH:mm A');
											}
										},";
					}else
					//FILE UPLOAD
					if($field_type === 12){
						$url = base_url();
						$table_column .= "{
											title: '$title',
											data: '$input_id',
											render:function(value){
												if (value === null) return '';
												return '<img src=\'".$url."'+value+'\' style=\'height: 48px;\' />';
											}
										},";
					}else{
						$table_column .= "{ 
											title: '$title',
											data: '$input_id',
											render:function(value){
												return value;
											}
										},";
					}
				}
				$label_view             = form_label($view_name,$input_id);
				$req_val                = '';
				$len_val                = '';
				if($mandatory === 1){
					$req_val = 'required: true,';
				}
				if((int)$length){
					$len_val = "maxlength: $length,";
				}
				if(!$default_value){
					$default_value = '';
				}
				//Text
				if($field_type === 1){//alpha_numeric
					$form_input    =  form_input(array('class' => 'validate alpha_numeric','id' => $input_id,'name' => $input_id,'value'=>$default_value));
					$input_fields .= "<div class='input-field col s6 m6 '> $label_view $form_input </div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val $len_val },";
					}
				}else
				//Decimal
				if($field_type === 2){//decimal
					$form_input    =  form_input(array('class' => 'validate decimal','id' => $input_id,'name' => $input_id,'value'=>$default_value));
					$input_fields .= "<div class='input-field col s6 m6 '> $label_view $form_input </div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val $len_val number: true },";
					}
				}else
				//Mobile
				if($field_type === 3){//mobile_no
					$form_input    =  form_input(array('class' => 'validate','id' => $input_id,'name' => $input_id,'value'=>$default_value));
					$input_fields .= "<div class='input-field col s6 m6 '> $label_view $form_input </div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val maxlength: $length, minlength: $length, number: true },";
					}
				}else
				//Email
				if($field_type === 4){//email
					$form_input    =  form_input(array('class' => 'validate','id' => $input_id,'name' => $input_id,'value'=>$default_value));
					$input_fields .= "<div class='input-field col s6 m6 '> $label_view $form_input </div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val email: true },";
					}
				}else
				//Select Box
				if($field_type === 5){//select
					$form_input    =  form_dropdown(array('class' => 'validate select','id' => $input_id,'name' => $input_id), $pick_result[$super_input_setting_id],$default_value);
					$input_fields .= "<div class='input-field col s6 m6 '> $form_input $label_view</div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val },";
					}
				}else
				//Multi Select Box
				if($field_type === 6){
					$id = $input_id."[]";
					$form_input    =  form_dropdown(array('class' => 'validate select','id' => $input_id,'name' => $id,'multiple'=>true),$pick_result[$super_input_setting_id],$default_value);
					$input_fields .= "<div class='input-field col s6 m6 '> $form_input $label_view</div>";
					if($mandatory === 1){
						$form_validation_rule .= "'$id' : { $req_val },";
					}
				}else
				//Date
				if($field_type === 7){
					$form_input    =  form_input(array('class' => 'datepicker','id' => $input_id,'name' => $input_id,'value'=>$default_value));
					$input_fields .= "<div class='input-field col s6 m6 '> $label_view $form_input </div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val },";
					}
				}else
				//Time
				if($field_type === 8){
					$form_input    =  form_input(array('class' => 'timepicker','id' => $input_id,'name' => $input_id,'value'=>$default_value));
					$input_fields .= "<div class='input-field col s6 m6 '> $label_view $form_input </div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val },";
					}
				}else
				//Summary Box
				if($field_type === 9){
					$form_input    =  form_textarea(array('class' => 'materialize-textarea','id' => $input_id,'name' => $input_id,'rows'=>"4",'value'=>$default_value));
					$input_fields .= "<div class='input-field col s12 m12 l12'> $label_view $form_input </div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val },";
					}
				}else
				//Auto Complete
				if($field_type === 10){
					$hidden_input  =  form_input(array('type' => 'hidden','id' => 'hidden_'.$input_id,'name' => 'hidden_'.$input_id));
					$form_input    =  form_input(array('class' => 'autocomplete','id' => $input_id,'name' => $input_id,'value'=>$default_value));
					$input_fields .= "<div class='input-field col s6 m6 '> $label_view $hidden_input $form_input </div>";
					if($mandatory === 1){
						$form_validation_rule .= "hidden_$input_id : { $req_val },";
						$form_validation_rule .= "$input_id : { $req_val },";
					}
					if($picklist_type === 1){
						$send_url       = site_url($controller_name.'/get_auto_complete/');
						$onload_script .= " var select_table     = '$select_table';
											var pick_table_value = '$pick_table_value';
											$.ajax({
												  type: 'POST',
												  url: '$send_url',
												  data: {select_table:select_table,pick_table_value:pick_table_value},
												  success: function(data) {
													var rslt = JSON.parse(data);
													if (rslt.request_status) {
														$('#$input_id').autocomplete({
															data: rslt.rslt,
															limit: 5,
															onAutocomplete: function(result){
																var id = rslt.rslt_data[result];
																$('#hidden_$input_id').val(id);
															}
														});
													}
												  }
											});";
					}else{
						$onload_script .= " var pick_table_value = '<?php echo $pick_table_value;?>';
											var arr = jQuery.map( rslt, function( value, key ) {
											  return arr[value] = null;
											});
											$('#$input_id').autocomplete({
												data: arr,
												limit: 5,
												onAutocomplete: function(result){
													$('#hidden_$input_id').val(value);
												}
											});
											";
					}
				}else
				//Read Only
				if($field_type === 11){
					$form_input    =  form_input(array('class' => 'decimal','id' => $input_id,'name' => $input_id,'readonly'=>true,'value'=>$default_value));
					$input_fields .= "<div class='input-field col s6 m6 '> $label_view $form_input </div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val },";
					}
				}
				/* //Check Box
				if($field_type === 12){
					$form_input    =  form_checkbox(array('class' =>'with-gap','id' => $input_id,'name' => $input_id));
					$input_fields .= "<div class='input-field col s6 m6 '> $label_view $form_input </div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val },";
					}
				} */
				//File Upload
				if($field_type === 12){
					$input_fields .= "<div class='input-field col s6 m6 '>
											<div class = 'row' id='div_$input_id'>
												<div class = 'file-field input-field' >
												  <div class = 'btn'>
													 <span>$view_name</span>
													 <input type = 'file' id='file_$input_id' name='file_$input_id' />
												  </div>
												  <div class = 'file-path-wrapper' >
													 <input class = 'file-path validate' id='$input_id' name='$input_id' type = 'text' placeholder = '$view_name' onchange='Upload_file(\"$input_id\",\"$extension_type\")' />
												  </div>
											   </div>
											</div>
										  </div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val },";
					}
				}	
			}
			
		}
		$label_view    = form_label("password","password");
		$form_input    = form_input(array('class' => 'validate','id' => "password",'name' => "password",'type'=>"password"));
		$input_fields .= "<div class='input-field col s6 m6 '> $label_view $form_input </div>";
		$form_validation_rule .= "'password':{minlength: 6, maxlength: 30, required: true}";	
		$style = 'display: none;';
		$class = 'in active';	
		if((int)$i === 1){
			$style ='display: block;';
			$class ='active';
		}
		$form_id         = str_replace(' ','_',strtolower($form_view_name));
		$form_name       = ucwords($form_view_name);
		$li_line        .= "<li class='tab'><a href='#$form_id' class='$class'>$form_name</a></li>";
		$form_container .= "<div class='tab-content $class' id='$form_id'  style='$style'>
								<h4 class='indigo-text'>$form_name</h4>
								<div class='row'>$input_fields</div>
							</div>";
		$i++;
		
	}
	$ui_line        = "<div class='card'>
							<div class='card-tabs'>
								<ul class='tabs tabs-fixed-width'>
									$li_line
								</ul>
							</div>
							<div class='card-content'>
								$form_container
							</div>
						</div>";
	$filter_table     = "<table id='filter_table'>$filter_table</table>";  						
?>                                
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
   <!-- BEGIN: Head-->
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta name="author" content="Hi-Mat Developers">
      <title>FSOS</title>
      <link rel="apple-touch-icon" href="<?php echo base_url();?>dist/images/logo/logo.png">
      <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>dist/images/logo/logo.png">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/vendors/vendors.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/materialize.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/style.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/custom.css">
		<style>
		 .login-page {
			margin: 11rem auto;
			width: 50%;
			height: 50%;
			background-color: #fff;
			border: none;    padding: 5.5rem 2.5rem 0.5rem !important;
			position: relative;
		}

		.jumbotron {    border-radius: 10px;
			box-shadow: 0 2px 5px 0 rgb(0 0 0), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
			margin-bottom: auto;
			margin-top: 65px;
		}

		.btn-primary {
			background-color: #A03894;
		}
		.validate {
			-moz-border-bottom-colors: none;
			-moz-border-left-colors: none;
			-moz-border-right-colors: none;
			-moz-border-top-colors: none;
			background-color: rgba(0, 0, 0, 0);
			border-color: -moz-use-text-color -moz-use-text-color #cccccc;
			border-image: none;
			border-radius: 0;
			border-style: none none solid;
			border-width: medium medium 1px;
			box-shadow: none;
			box-sizing: content-box;
			font-size: 1rem;
			height: 2.1rem;
			outline: 0 none;
			transition: all 0.3s ease 0s;
			width: 100%;
		}

		.btn {
			border: 0 none;
			border-radius: 2px;
			color: #9333af !important;
			margin: 35px 0 -20px;
			white-space: normal !important;
			text-transform: uppercase;
			background: transparent;
		}

		.btn:hover {
			background: transparent;
		}

		.validate:focus {
			border-bottom: 1px solid #4285f4;
			box-shadow: 0 1px 0 0 #4285f4;
			transition: all 0.3s linear;
		}

		label {
			display: inline-block;
			font-weight: 400;
			margin-bottom: 5px;
			max-width: 100%;
			font-size: 0.9rem;
		}

		input[type="date"]:focus:not([readonly])+label,
		input[type="datetime-local"]:focus:not([readonly])+label,
		input[type="email"]:focus:not([readonly])+label,
		input[type="number"]:focus:not([readonly])+label,
		input[type="password"]:focus:not([readonly])+label,
		input[type="search-md"]:focus:not([readonly])+label,
		input[type="search"]:focus:not([readonly])+label,
		input[type="tel"]:focus:not([readonly])+label,
		input[type="text"]:focus:not([readonly])+label,
		input[type="time"]:focus:not([readonly])+label,
		input[type="url"]:focus:not([readonly])+label,
		textarea.md-textarea:focus:not([readonly])+label {
			color: #4285f4;
			transition: all 0.3s linear;
		}

		.hedding-login {
			background: rgba(0, 0, 0, 0) linear-gradient(60deg, #028cd3, #05f3ff) repeat scroll 0 0;
			position: absolute;
			text-align: center;
			top: -40px;
			width: 88%;
			box-shadow:0 7px 60px -15px #00000069;
			border-radius: 5px;
		}

		.facebook-icon {
			margin-top: 15px;
			display: inline-block;
			margin-right: 15px;
			vertical-align: middle;
		}

		.login-text {
			color: #ffffff;
			font-size: 20px;
			font-weight: 500;
		}

		.main-h4 {
			font-size: 18px;
			font-weight: 700;
			text-align: center;
		}

		@media screen and (min-width:481px) and (max-width:600px) {
			.login-page {
				width: 100%;
			}
		}

		@media screen and (max-width: 480px) {
			.login-page {
				width: 100%;
			}
		}

		.prefix {
			color: #039ad9 !important;
		}

		.login-bg {
			background-repeat: no-repeat;
			background-size: cover;
		}


		/* fallback */

		@font-face {
			font-family: 'Material Icons';
			font-style: normal;
			font-weight: 400;
			src: url(https://fonts.gstatic.com/s/materialicons/v50/flUhRq6tzZclQEJ-Vdg-IuiaDsNc.woff2) format('woff2');
		}

		.material-icons {
			font-family: 'Material Icons';
			font-weight: normal;
			font-style: normal;
			font-size: 24px;
			line-height: 1;
			letter-spacing: normal;
			text-transform: none;
			display: inline-block;
			white-space: nowrap;
			word-wrap: normal;
			direction: ltr;
			-webkit-font-feature-settings: 'liga';
			-webkit-font-smoothing: antialiased;
		}
		.white{
			color: white !important;
		}
		.input-field {
			position: relative;
			margin-top: 2rem;
		}
		.card .card-content {
			padding: 0px;
		}
		</style>
   </head>
   <body style='background-image: url("<?php echo base_url();?>dist/images/gallery/login.jpg");height: 100%;background-position: center; background-size: cover;'>
      <section>
        <div class=" ">
			<div class='jumbotron login-page'>
				<div class='row '>
					<div id="login" class="col s12">
						<div class="row">
						  <?php echo form_open('login/emp_sign_in',array('class' => 'login-form', 'id' => 'signin_form'));?>
						<div class="input-field col s12">
						  <h5 class="ml-4">Login</h5>
						</div>
						  <div class="input-field col-md-12">
							 <i class="material-icons prefix pt-2">person_outline</i>		  
							 <?php
								echo form_input(array('class' => 'validate','id' => 'user_name','name' => 'user_name'));
								?>
							 <label for="user_name" class="center-align">Username</label>
						  </div>
					   </div>
					   <div class="row">
						  <div class="input-field col-md-12">
							 <i class="material-icons prefix pt-2">lock_outline</i>		  
							 <?php
								echo form_input(array('class' => 'validate','id'=>'password','name' => 'password','type' =>"password"));
							 ?>
							 <label for="password">Password </label>
						  </div>
					   </div>
					   <div class="row text-center">
						  <div class="col-md-12">	
							 <button type="submit" class="waves-effect waves-light text-white btn gradient-45deg-light-blue-cyan z-depth-4 mr-1 mb-2 white" id = "forgot_submit"><i class="material-icons left">lock_open</i> Login</button>
								<p class="margin left-align medium-small"><a href="<?php echo site_url();?>/" style="cursor: pointer;">Home</a></p>	
							 <p class="margin right-align medium-small"><a onclick="register()" style="cursor: pointer;">Register/Signin ?</a></p>
						  </div>
						  <?php echo form_close();?>
						  <div class="input-field col s12 m12 l12"></div>
					   </div>
					</div>
				    <div id="register" class="col s12" style='display:none;'>
						<div class="input-field col s12">
						  <h5 class="ml-4">Register</h5>
						</div>
						<?php echo form_open(site_url($controller_name.'/form_save'),array('id' => 'form_save','class'=>''));
							  echo form_input(array('type' => 'hidden','id' => $super_name,'name' => $super_name,'value'=>'')); 
							  echo $input_fields;
						?>	
						<div class="row">
						  <div class="col-md-12" style="text-align: center;">	
								<button type="submit" class="waves-effect waves-light text-white btn gradient-45deg-light-blue-cyan z-depth-4 mr-1 mb-2 white" id = "forgot_submit"><i class="material-icons left">perm_identity</i>Register</button>
								<p class="margin right-align medium-small"><a onclick="back_login()" style="cursor: pointer;">Back To Login</a></p>
							</div>
						</div>
						<?php echo form_close(); ?>
				    </div>
				</div>
               <div class="hedding-login" style="background: rgba(0, 0, 0, 0) linear-gradient(60deg, #d302027a, #ff050500) repeat scroll 0 0;">
					<h5 class="login-text">
					<img class="responsive-img" src="<?php echo base_url();?>dist/images/logo/683_logo.png" alt="FSOS logo" width='135'/><br/></h5>
                </div>
			</div>
        </div>
      </section>
   </body>
   <script src="<?php echo base_url();?>dist/js/vendors.min.js"></script>
   <script src="<?php echo base_url();?>dist/js/plugins.min.js"></script>
   <script src="<?php echo base_url();?>dist/js/search.min.js"></script>
   <script src="<?php echo base_url();?>dist/js/custom/custom-script.min.js"></script>
   <script src="<?php echo base_url();?>dist/js/validator.min.js"></script>
   <script src="<?php echo base_url();?>dist/js/jquery.form.js"></script>
   <script>
	$( document ).ready(function() {
		$.validator.addMethod("checklower", function(value) {
		  return /[a-z]/.test(value);
		});
		$.validator.addMethod("checkupper", function(value) {
		  return /[A-Z]/.test(value);
		});
		$.validator.addMethod("checkdigit", function(value) {
		  return /[0-9]/.test(value);
		});
		$.validator.addMethod("pwcheck",function(value) {
			 return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) && /[a-z]/.test(value) && /\d/.test(value) && /[A-Z]/.test(value);
		});
		$("#form_save").submit(function (e){e.preventDefault();}).validate({
			ignore: ".ignore",
			invalidHandler: function(e, validator){
				if(validator.errorList.length)
				$('.tabs').tabs('select', jQuery(validator.errorList[0].element).closest(".tab-content").attr('id'));
			},
			rules: {
				<?php echo $form_validation_rule;?>
			},
			submitHandler: function (form) {
				$("#btn_submit").html("<i class='fa fa-spinner fa-spin'></i> Processing...");
				$('#btn_submit').attr('disabled','disabled');
				$(form).ajaxSubmit({
					success: function (data) {
						$('#btn_submit').attr('disabled',false);
						$("#btn_submit").html("Submit");
						var rslt = JSON.parse(data);
						if (rslt.request_status) {
							M.toast({
								html: rslt.message
							})
							$("#form_save")[0].reset();back_login();
						} else {
							M.toast({
								html: rslt.message
							})
						}
					}
				});
			}
		});
		$("#signin_form").submit(function(e){e.preventDefault();}).validate({
			rules: {
				user_name: "required",
				password: "required"
			},
			submitHandler: function(form) {
				$(form).ajaxSubmit({
					success:function(data) { 
						var rslt = JSON.parse(data);
						if(rslt.request_status){
							M.toast({html: rslt.message})
							location.reload();
						}else{
							$(form)[0].reset();
							M.toast({html: rslt.message})
						}
					} 
				});
			}
		});
	});
	function register(){
		$('#form_save')[0].reset();
		//$('#signin_form')[0].reset();
		$("label[class='error']").remove();
		$('#login').hide();
		$('#register').show();
	}
	function back_login(){
		$('#form_save')[0].reset();
		//$('#signin_form')[0].reset();
		$("label[class='error']").remove();
		$('#register').hide();
		$('#login').show();
	}
	</script>
</html>