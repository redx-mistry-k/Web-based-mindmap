<?php
	$super_employee_id = $this->session->userdata('super_employee_id');
	$employee_role     = $this->session->userdata('employee_role');
	$company_name      = $this->session->userdata('company_name');
	$employee_name     = $this->session->userdata('employee_name');
	$phone             = $this->session->userdata('phone');
	$email             = $this->session->userdata('email');
	$logo             = $this->session->userdata('logo');
	if($menus_rslt){
		foreach($menus_rslt as $result){
			$menu_list[$result->menu_name][] = $result;
			$menu_name[]                     = $result->menu_name;
		}
		$menu_name = array_unique($menu_name);
	}
?>
<html class="loading" lang="en" data-textdirection="ltr">
	<!-- BEGIN: Head-->
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
		<meta name="author" content="Hi-Mat Developers">
		<title><?php echo $company_name;?></title>	
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>dist/images/logo/favicon.ico">	
		<link rel="apple-touch-icon" href="<?php echo base_url();?>dist/images/logo/favicon.ico">	
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/vendors/data-tables/css/buttons.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/vendors/data-tables/css/jquery.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/vendors/data-tables/css/select.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/vendors/vendors.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/materialize.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/style.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/custom.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/jquery-confirm.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/select2.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/form-select2.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/select2-materialize.css">
		<!-- END: Custom CSS-->
	</head>
	<!-- END: Head-->
	<body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns" data-open="click" data-menu="vertical-modern-menu" data-col="2-columns" >

	<!-- BEGIN: Header-->
	<header class="page-topbar" id="header">
		<div class="navbar navbar-fixed"> 
			<nav class="navbar-main navbar-color nav-collapsible navbar-dark gradient-45deg-indigo-light-blue no-shadow nav-collapsed">
				<div class="nav-wrapper">
					<div class="header-search-wrapper hide-on-med-and-down">
						<?php 
							if($controller_name == "organizations"){
								$display_name = "Registered Users";
							}else
							if($controller_name == "donate_food"){
								if(in_array($_SESSION['employee_role'],array(1,2,6))){
									$display_name = "Request Food";
								}else{
									$display_name = "Post Food Availablity";
								}
							}else{
								$display_name = ucwords(str_replace("_"," ","$controller_name"));
							}
						?>
						<h4 class="breadcrumbs-title mt-0 mb-0" style="color: rgba(255,255,255,.85);"><span><?php echo $display_name;?> </	span></h4>
					</div>
					<ul class="navbar-list right">
						<li class="hide-on-med-and-down"><a class="waves-effect waves-block waves-light toggle-fullscreen" href="javascript:void(0);"><i class="material-icons">settings_overscan</i></a></li>
						<li class="hide-on-large-only search-input-wrapper"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search</i></a></li>
						<li><a class="waves-effect waves-block waves-light notification-button" href="javascript:void(0);" data-target="profile-dropdown"><i class='material-icons'>account_circle</i></a></li>
					</ul>
					<ul class="dropdown-content mb-2" id="profile-dropdown">
						<li><a class="grey-text text-darken-1 " style='display: flex;' href="#"><i class="material-icons">perm_identity</i><?php echo $employee_name;?></a></li>
						<li><a class="grey-text text-darken-1" href="#" style='display: flex;'><i class="material-icons">contacts</i><?php if((int)$_SESSION['organization'] != ''){ echo $_SESSION['organization_phone_no'];}else{echo $phone; };?></a></li>
						<li class="divider"></li>
						<li><a class="grey-text text-darken-1"id='logout'><i class="material-icons">keyboard_tab</i> Logout</a></li>
					</ul>
				</div>
				<nav class="display-none search-sm">
					<div class="nav-wrapper">
						<form id="navbarForm">
							<div class="input-field search-input-sm">
								<input class="search-box-sm mb-0" type="search" required="" id="search" placeholder="Explore Materialize" data-search="template-list">
								<label class="label-icon" for="search"><i class="material-icons search-sm-icon">search</i></label><i class="material-icons search-sm-close">close</i>
								<ul class="search-list collection search-list-sm display-none"></ul>
							</div>
						</form>
					</div>
				</nav>
			</nav>
		</div>
	</header>
	<!-- BEGIN: SideNav-->
<aside class="sidenav-main nav-expanded nav-collapsible sidenav-light sidenav-active-square nav-collapsed">
    <div class="brand-sidebar">
      <h1 class="logo-wrapper">
         <a class="brand-logo darken-1" href="#">
			 <img class="hide-on-med-and-down" src="<?php echo base_url();?><?php echo $logo;?>" alt="<?php echo $company_name;?> logo" style='height: 34px;margin-top: -20px;'>
			 <img class="show-on-medium-and-down hide-on-med-and-up" src="<?php echo base_url();?><?php echo $logo;?>" alt="<?php echo $company_name;?> logo" style='height: 48px; margin: -10px 10px 10px -50px;'>
			 <span class="logo-text hide-on-med-and-down" style='font-size: 18px;margin: -10px 0px 0px -10px;'><?php echo $company_name;?></span>
			 <a class="navbar-toggler" href="#">
				<i class="material-icons">radio_button_unchecked</i>
			 </a>
         </a>
      </h1>
    </div>
    <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="menu-accordion">
	  <?php 
		if($controller_name === "Index"){
			$status_main = 'close';
			$status = 'active';
		}else{
			$status_main = '';
			$status = '';
		}
	  ?>
	  <li class="navigation-header"><a class="navigation-header-text">Applications</a><i class="navigation-header-icon material-icons">more_horiz</i>
	  </li>
	  <?php 
			$menu_data = '';
			if(isset($menu_name) && isset($menus_rslt)){
				foreach($menu_name as $menus){
					if(in_array("donee",array_column($menu_list[$menus],'module_name')) && (int)$employee_role === 5 && $menus == "Organizations"){
						continue;
					}
					if (in_array("$controller_name", array_column($menu_list[$menus], 'module_name'))){
						$status_main = 'close';
					}else{
						$status_main = '';
					}
					if($menus == "Organizations"){
							
							$menu_data .= '<li class="'.$status_main.' bold">
									<a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)">
										<i class="material-icons">'.$menu_list[$menus][0]->icon.'</i>
										<span class="menu-title" data-i18n="'.$menus.'">Menu</span>
										<span class="badge badge pill orange float-right mr-10">'.count($menu_list[$menus]).'</span>
									</a>';
					}else{
						$menu_data .= '<li class="'.$status_main.' bold">
									<a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)">
										<i class="material-icons">'.$menu_list[$menus][0]->icon.'</i>
										<span class="menu-title" data-i18n="'.$menus.'">'.$menus.'</span>
										<span class="badge badge pill orange float-right mr-10">'.count($menu_list[$menus]).'</span>
									</a>';
					}
					
					
					if($menu_list[$menus]){	
						$menu_data .= '<div class="collapsible-body">
										 <ul class="collapsible collapsible-sub" data-collapsible="accordion">';
						foreach($menu_list[$menus] as $menu){
							$name        = $menu->menu_name;
							$icon        = $menu->icon;
							$module_name = $menu->module_name;
							if($controller_name === "$module_name"){
								$status = 'active';
							}else{
								$status = '';
							}
							if($module_name == "organizations"){
									$view_name = "Registered Users";
							}else
							if($module_name == "donate_food"){
								if(in_array($_SESSION['employee_role'],array(1,2,6))){
									$view_name = "Request Food";
								}else{
									$view_name = "Post Food Availablity";
								}
							}else{
								$view_name = $module_name;
							}
							$menu_data .= '<li class="'.$status.'">
											  <a class="'.$status.'" href='.site_url("/$module_name").'><i class="material-icons">chevron_right</i><span data-i18n="Modern">'.ucwords(str_replace("_"," ","$view_name")).'</span></a>
											</li>';
						}
						$menu_data .= '</ul></div>';
					}
					$menu_data .= '</li>';
				}
			}
			echo $menu_data;
	  ?>
	  <?php 
		if((int)$employee_role === 1){
			if($controller_name === "module_creation"){
				$status_main = 'close';
				$status = 'active';
			}else{
				$status_main = '';
				$status = '';
			}
	  ?>
		<li class="<?php echo $status_main; ?> bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)" tabindex="0"><i class="material-icons">lock_open</i><span class="menu-title" data-i18n="Authentication">Authentication</span></a>
          <div class="collapsible-body" style="">
            <ul class="collapsible collapsible-sub" data-collapsible="accordion">
              <li class="<?php echo $status; ?>"><a class="<?php echo $status; ?>" href="<?php echo site_url();?>/module_creation"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Module Setting">Module Setting</span></a>
              </li>
            </ul>
          </div>
        </li>
	<?php }?>
   </ul>
   <div class="navigation-background"></div>
   <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
</aside>
<!-- END: SideNav-->
	