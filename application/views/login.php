<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
   <!-- BEGIN: Head-->
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta name="author" content="Hi-Mat Developers">
      <title>FMSPW</title>
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>dist/images/logo/favicon.ico">	
		<link rel="apple-touch-icon" href="<?php echo base_url();?>dist/images/logo/favicon.ico">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/vendors/vendors.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/materialize.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/style.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/custom.css">
		<style>
		 .login-page {
			margin: 11rem auto;
			width: 30%;
			height: 50%;
			background-color: #fff;
			border: none;
			padding: 10.5rem 2.5rem 2.5rem !important;
			position: relative;
		}

		.jumbotron {
			border: none;
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
			left: 21px;
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
		.card .card-content {
			padding: 0px;
		}
		</style>
   </head>
   <body style='background-image: url("<?php echo base_url();?>dist/images/gallery/login.jpg");height: 100%;background-position: center; background-repeat: no-repeat; background-size: cover;'>
      <section>
        <div class="container ">
			<div class='jumbotron login-page'>
               <div class="row text-center">
                  <div class="col-md-8">
                  </div>
               </div>
				<div class='row '>
					<div id="login" class="col s12">
					   <div class="row">
						  <?php echo form_open('login/emp_sign_in',array('class' => 'login-form', 'id' => 'signin_form'));?>
						  <div class="input-field col-md-12">
							 <i class="material-icons prefix pt-2">person_outline</i>		  
							 <?php
								echo form_input(array('class' => 'validate','id' => 'user_name','name' => 'user_name','autocomplete' => 'off'));
								?>
							 <label for="user_name" class="center-align">Username</label>
						  </div>
					   </div>
					   <div class="row">
						  <div class="input-field col-md-12">
							 <i class="material-icons prefix pt-2">lock_outline</i>		  
							 <?php
								echo form_input(array('class' => 'validate','id' => 'password','name' => 'password','type' =>"password",'autocomplete' => 'off'));
								?>
							 <label for="password">Password</label>
						  </div>
					   </div>
					   <div class="row text-center">
						  <div class="col-md-12">		
							 <button type="submit" class="waves-effect waves-light text-white btn gradient-45deg-light-blue-cyan z-depth-4 mr-1 mb-2 white">Login</button>
							 <p class="margin right-align medium-small"><a href="<?php echo site_url();?>/" style="cursor: pointer;">Home</a></p>
						  </div>
						  <div class="input-field col s12 m12 l12">
							</div>
					   </div>
					 <?php echo form_close();?>
					 <div class="row">
					  </div>
					</div>
				</div>
               <div class="hedding-login" style="background: rgba(0, 0, 0, 0) linear-gradient(60deg, #d302027a, #ff050500) repeat scroll 0 0;">
					<h5 class="login-text">
					<img class="responsive-img" src="<?php echo base_url();?>dist/images/logo/683_logo.png" alt="FSOS logo" width='135'/><br/></h5>
					<h7 class="login-text">Admin Login</h7>
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
		$("#forgot_pass_submit").submit(function(e){e.preventDefault();}).validate({
			rules: {
				email: {
				  required: true,
				  email: true
				}
			},
			submitHandler: function(form) {
				$(form).ajaxSubmit({
					success:function(data) { 
						var rslt = JSON.parse(data);
						if(rslt.request_status){
							M.toast({html: rslt.message})
						}else{
							$(form)[0].reset();
							M.toast({html: rslt.message})
						}
					} 
				});
			}
		});
	});
	function forgot_password(){
		$('#forgot_pass_submit')[0].reset();
		$('#signin_form')[0].reset();
		$("label[class='error']").remove();
		$('#login').hide();
		$('#forgot_password').show();
	}
	function back_login(){
		$('#forgot_pass_submit')[0].reset();
		$('#signin_form')[0].reset();
		$("label[class='error']").remove();
		$('#forgot_password').hide();
		$('#login').show();
	}
	</script>
</html>