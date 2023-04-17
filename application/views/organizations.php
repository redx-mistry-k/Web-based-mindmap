<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	$this->load->view('include/header.php');
	$access_data            = $this->session->userdata('permission_info');
	$add_access             = (int)$access_data[$controller_name]->add_access;
	$edit_access            = (int)$access_data[$controller_name]->edit_access;
	$delete_access          = (int)$access_data[$controller_name]->delete_access;
	$import_access          = (int)$access_data[$controller_name]->import_access;
	$export_access          = (int)$access_data[$controller_name]->export_access;
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
			$title                  = ucwords(str_replace("_"," ","$view_name"));
			$show_feild             = (int)$inputs_rlst->show_feild;
			if($show_feild === 1){
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
					//Select Box
					if(($field_type === 5 || $field_type === 6) && $picklist_type === 2){
						if($picklist_type === 2){
							$table_column .= "{ 
												title: '$title',
												data: '$input_id',
												render:function(value){
													var pick_result = jQuery.parseJSON('".json_encode($pick_result[$super_input_setting_id])."');
													return pick_result[value];
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
					$input_fields .= "<div class='input-field col s6 m4 l4'> $label_view $form_input </div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val $len_val },";
					}
				}else
				//Decimal
				if($field_type === 2){//decimal
					$form_input    =  form_input(array('class' => 'validate decimal','id' => $input_id,'name' => $input_id,'value'=>$default_value));
					$input_fields .= "<div class='input-field col s6 m4 l4'> $label_view $form_input </div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val $len_val number: true },";
					}
				}else
				//Mobile
				if($field_type === 3){//mobile_no
					$form_input    =  form_input(array('class' => 'validate','id' => $input_id,'name' => $input_id,'value'=>$default_value));
					$input_fields .= "<div class='input-field col s6 m4 l4'> $label_view $form_input </div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val maxlength: $length, minlength: $length, number: true },";
					}
				}else
				//Email
				if($field_type === 4){//email
					$form_input    =  form_input(array('class' => 'validate','id' => $input_id,'name' => $input_id,'value'=>$default_value));
					$input_fields .= "<div class='input-field col s6 m4 l4'> $label_view $form_input </div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val email: true },";
					}
				}else
				//Select Box
				if($field_type === 5){//select
					$form_input    =  form_dropdown(array('class' => 'validate select','id' => $input_id,'name' => $input_id), $pick_result[$super_input_setting_id],$default_value);
					$input_fields .= "<div class='input-field col s6 m4 l4'> $form_input $label_view</div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val },";
					}
				}else
				//Multi Select Box
				if($field_type === 6){
					$id = $input_id."[]";
					$form_input    =  form_dropdown(array('class' => 'validate select','id' => $input_id,'name' => $id,'multiple'=>true),$pick_result[$super_input_setting_id],$default_value);
					$input_fields .= "<div class='input-field col s6 m4 l4'> $form_input $label_view</div>";
					if($mandatory === 1){
						$form_validation_rule .= "'$id' : { $req_val },";
					}
				}else
				//Date
				if($field_type === 7){
					$form_input    =  form_input(array('class' => 'datepicker','id' => $input_id,'name' => $input_id,'value'=>$default_value));
					$input_fields .= "<div class='input-field col s6 m4 l4'> $label_view $form_input </div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val },";
					}
				}else
				//Time
				if($field_type === 8){
					$form_input    =  form_input(array('class' => 'timepicker','id' => $input_id,'name' => $input_id,'value'=>$default_value));
					$input_fields .= "<div class='input-field col s6 m4 l4'> $label_view $form_input </div>";
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
					$input_fields .= "<div class='input-field col s6 m4 l4'> $label_view $hidden_input $form_input </div>";
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
					$input_fields .= "<div class='input-field col s6 m4 l4'> $label_view $form_input </div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val },";
					}
				}
				/* //Check Box
				if($field_type === 12){
					$form_input    =  form_checkbox(array('class' =>'with-gap','id' => $input_id,'name' => $input_id));
					$input_fields .= "<div class='input-field col s6 m4 l4'> $label_view $form_input </div>";
					if($mandatory === 1){
						$form_validation_rule .= "$input_id : { $req_val },";
					}
				} */
				//File Upload
				if($field_type === 12){
					$input_fields .= "<div class='input-field col s6 m4 l4'>
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
							<div class='card-content grey lighten-4'>
								$form_container
							</div>
						</div>";
	$filter_table     = "<table id='filter_table'>$filter_table</table>";  						
?>                                
<!-- BEGIN: Page Main-->
<div id="main" class="main-full">
   <div class="row">
      <div class="content-wrapper-before gradient-45deg-indigo-light-blue"></div>
   </div>
   <div class="col s12">
      <div class="container">
         <div class="section section-data-tables">
            <!-- Scroll - Vertical and Horizontal -->
            <div class="row">
               <div class="col s12">
                  <div class="card">
                     <div class="card-content">
                        <div class="row">
                           <div class="col s4">
                              <div id='filter' class='left'><a data-target="filter_inputs" class="waves-effect waves-light btn-small modal-trigger">Filter<i class="material-icons left">filter_list</i></a></div>
                              <div id='export' class='right'></div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col s12">
                              <table id="ajax_table" class="display nowrap" style='text-align:center;'>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="content-overlay"></div>
      </div>
   </div>
   <div class="col s12">
      <!-- Modal -->
      <div id="form_inputs" class="modal modal-xl" style='text-align:center;'>
         <div class="modal-content">
			<div class="modal-header">
				<h5 class="left modal-title" id="myExtraLargeModalLabel" style="margin:0;">User Information</h5>
				<button type="button" class="btn modal-close right" style="margin:0;background-color: #ff4081;" onclick="close_form()"><i class="material-icons">close</i></button>
				<div class="clear"></div>
			</div>
            <?php echo form_open(site_url($controller_name.'/form_save'),array('id' => 'form_save','class'=>''));
				  echo form_input(array('type' => 'hidden','id' => $super_name,'name' => $super_name,'value'=>'')); 
				  echo $ui_line;
			?>	
            <div class="modal-footer">
				<div class="row">
					<div class="input-field col s12">
						<button type="submit" class="btn waves-effect waves-light" id="btn_submit"><i class="material-icons right">send</i>Submit</button>
					</div>
				</div>
            </div>
            <?php echo form_close(); ?>
         </div>
      </div>
   </div>
  <div id="filter_inputs" class="modal modal-s" style='text-align:center;'>
	 <div class="modal-content">
		<?php echo $filter_table;?>	
		<div class="modal-footer">
			<div class="row">
				<div class="input-field col s12">
					<button type="submit" class="waves-effect waves-light btn-small mb-1 mr-1 modal-close right" id="filter_close">Close</button>
					<button type="submit" class="waves-effect waves-light btn-small mb-1 mr-1 modal-close left" id="filter_submit">Search</button>
				</div>
			</div>
		</div>
	 </div>
  </div>
</div>
<!-- Modal -->
<?php $this->load->view('include/footer.php'); ?>
<script>
 $(document).ready(function () {
	$('#form_inputs').modal({
		dismissible:false
	});
	$('#filter_close').click(function(){
		$('#filter_table input').val('');
		$('#filter_table select').val('');
		$('.select').formSelect();
		M.updateTextFields();
		$ajax_table.draw();
	});
	$('#filter_submit').click(function(){
		$ajax_table.draw();
	});
	update_class();
	<?php echo $onload_script;?>
	
	$ajax_table = $('#ajax_table').DataTable({
		responsive: !0,
        scrollY: true,
        scrollCollapse: !0,
		processing: true,
		serverSide: true,
		lengthMenu: [
			[10, 25, 50],
			[10, 25, 50]
		],
		language: {
			"lengthMenu": "Display _MENU_ records per page",
			"zeroRecords": "Nothing found - sorry",
			"info": "Showing page _PAGE_ of _PAGES_",
			"infoEmpty": "No records available",
			"infoFiltered": "(filtered from _MAX_ total records)"
		},
		serverMethod: 'post',
		ajax: {
			url: '<?php echo site_url()."/".$controller_name;?>/search',
			data: function(data){
				<?php echo $filter_table_val; ?>
			}
		},
		columns: [{
				title: "<label> <input type='checkbox' class='filled-in' name='select_all' /><span></span> </label>",
				data: '<?php echo "$super_name";?>',
				orderable: false,
				render: function (value) {
					return "<label> <input type='checkbox'  class='filled-in' name='select_one' value=" + value + " /><span></span></label> ";
				}
			},
			<?php 
				echo $table_column;
				if($edit_access === 1){
			?> {
				title: 'View',
				data: '<?php echo "$super_name";?>',
				render: function (value) {
					return '<a onclick="edit_view(' + value + ')" data-target="form_inputs" class="waves-effect waves-light btn-small modal-trigger"><i class="material-icons">edit</i>Edit</a>';
				}
			}
			<?php } ?>
		]
	});
	<?php if($delete_access === 1){ ?>
	$('#ajax_table_filter').prepend("<label><a onclick='delete_info()' class='mb-6 btn-floating waves-effect waves-light gradient-45deg-purple-deep-orange' id='delete_btn'><i class='material-icons'>delete</i></a></label>\n");
	<?php 	}	 
			if($export_access === 1){
	?>
	new $.fn.dataTable.Buttons($ajax_table, {
		buttons: [{
			extend: 'collection',
			text: 'Export',
			buttons: [
				{
					extend: 'excelHtml5'
				},
				{
					extend: 'print'
				},
				{
					extend: 'pdfHtml5'
				},
			]
		}]
	}).container().appendTo('#export');
	<?php } ?>
	$('select[name="ajax_table_length"]').addClass('select');
	$('.select').formSelect();
	M.updateTextFields();
	$('#delete_btn').addClass('disabled');
	$('input[name="select_all"]').change(function () {
		var count = 0;
		if ($(this).is(":checked")) {
			$('input[name="select_one"]').each(function () {
				$(this).prop('checked', true);
				count++;
			})
		} else {
			$('input[name="select_one"]').each(function () {
				$(this).prop('checked', false);
			});
			count = 0;
		}
		if (count === 0) {
			$('#delete_btn').addClass('disabled');
			$('input[name="select_all"]').prop('checked', false);
		} else {
			$('#delete_btn').removeClass('disabled');
		}
	});
	$ajax_table.on('change', 'input[name="select_one"]', function () {
		var count = 0;
		$('input[name="select_one"]').each(function () {
			if ($(this).is(":checked")) {
				count++;
			}
		});
		if (count === 0) {
			$('#delete_btn').addClass('disabled');
			$('input[name="select_all"]').prop('checked', false);
		} else {
			$('#delete_btn').removeClass('disabled');
		}
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
						$('#form_inputs').modal('close');
					} else {
						M.toast({
							html: rslt.message
						})
					}
					close_form();
					$ajax_table.draw();
				}
			});
		}
	});
	
});

//UPDATE CLASS FOR INPUT FIELDS
function update_class(){
	//ONLY NUMBERS
	$(".number").inputFilter(function (value) {
		return /^\d*$/.test(value);
	});
	//ONLY NUMBERS GREATER THAN ZERO
	$(".positive_int").inputFilter(function (value) {
		return /^\d*$/.test(value);
	});
	//INTEGER WITH . & ,
	$(".float").inputFilter(function (value) {
		return /^-?\d*[.]?\d*$/.test(value);
	});
	//INTEGER WITH TWO DECIMAL VALUE
	$(".decimal").inputFilter(function (value) {
		return /^-?\d*[.]?\d{0,2}$/.test(value);
	});
	//ONLY ALPHABETS
	$(".alpha").inputFilter(function (value) {
		return /^[a-z]*$/i.test(value);
	});
	//Mobile Number > 0 & < 10
	$(".mobile_no").inputFilter(function (value) {
		return /^\d*$/.test(value) && (value === "" || parseInt(value) <= 10);
	});
	$('.datepicker').datepicker({
		format:'dd/mm/yyyy',
		onSelect: function () {
			this.close();
		},
		autoClose: true
	});
	$('.timepicker').timepicker();
	$('.select').formSelect();
	M.updateTextFields();
}
function edit_view(super_id){
	var send_url = "<?php echo site_url($controller_name);?>/edit/";
	$.ajax({
		method: "POST",
		url: send_url,
		data: {
			super_id: super_id
		},
		success: function (data) {
			var rslt = JSON.parse(data);
			if (rslt.request_status) {
				$.each(rslt.search_result, function (key, value) {
					if (key !== 'password') {
						$("#" + key).val(value);
					}
				}); 
				$('.tabs').tabs('select','account');
				$('#employee_permission_info').html(rslt.table_info);
				$('#permission_tab_head').show();
				$('.select').formSelect();
				M.updateTextFields();
			}
		}
	});
}
function delete_info() {
	$.confirm({
		title: 'Confirm..!',
		columnClass: 'small',
		content: 'Are You Sure.?',
		type: 'red',
		typeAnimated: true,
		useBootstrap: false,
		buttons: {
			tryAgain: {
				text: 'Yes',
				btnClass: 'btn-red',
				action: function () {
					var super_id = new Array();
					$('input[name="select_one"]').each(function () {
						if ($(this).is(":checked")) {
							super_id.push($(this).val());
						}
					});
					var send_url = "<?php echo site_url($controller_name);?>/delete/";
					$.ajax({
						method: "POST",
						url: send_url,
						data: {
							super_id: super_id
						},
						success: function (data) {
							var rslt = JSON.parse(data);
							M.toast({
								html: rslt.message
							});
							$ajax_table.draw();
							$('input[name="select_all"]').prop('checked', false);
						}
					});
				}
			},
			close: function () {}
		}
	});
}
function close_form() {
	$('#<?php echo $super_name;?>').val('');
	$('#form_save')[0].reset();
	$("label[class='error']").remove();
	$('.select').formSelect();
	M.updateTextFields();
	$ajax_table.draw();
}
function Upload_file(input_id,extension_type){
	var send_url	 = '<?php echo base_url("upload_files/uploads.php?send_from=$controller_name&send_for='+input_id+'&extension_type='+extension_type+'");?>';
	var file_data    = $('#file_'+input_id+'').prop('files')[0];
	if(file_data){
		var form_data = new FormData();
		form_data.append(input_id, file_data);
		$.ajax({
			url: send_url,
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,
			type: 'post',
			success: function(result_data){
				var rslt = JSON.parse(result_data);
				if(rslt['success']){
					$('#'+input_id).val(rslt['path']);
				}else{
					M.toast({
						html: rslt['msg']
					});
					$('#'+input_id).val('');
				}
			}
		});
	}else{
		M.toast({
			html: 'Please select file size below or equal to 2mb'
		});
		$('#'+input_id).val('');
	}
}
</script>