<?php
   $super_employee_id = $this->session->userdata('super_employee_id');
   $company_name      = $this->session->userdata('company_name');
   $employee_name     = $this->session->userdata('employee_name');
   $phone             = $this->session->userdata('phone');
   $email             = $this->session->userdata('email');
   ?>
<html class="loading" lang="en" data-textdirection="ltr">
   <!-- BEGIN: Head-->
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta name="description" content="Materialize is a Material Design Admin Template,It's modern, responsive and based on Material Design by Google.">
      <meta name="keywords" content="materialize, admin template, dashboard template, flat admin template, responsive admin template, eCommerce dashboard, analytic dashboard">
      <meta name="author" content="Hi-Mat Developers">
      <title><?php echo $company_name;?> Store</title>
      <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>dist/images/logo/logo.png">
      <link rel="apple-touch-icon" href="<?php echo base_url();?>dist/images/logo/logo.png">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/vendors/vendors.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/materialize.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/style.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/custom.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>dist/css/page-500.min.css">
   </head>
   <!-- END: Head-->
   <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 1-column    blank-page blank-page" data-open="click" data-menu="vertical-modern-menu" data-col="1-column">
      <div class="row">
         <div class="col s12">
            <div class="container">
               <div class="section p-0 m-0 height-100vh section-500">
                  <div class="row">
                     <!-- 404 -->
                     <div class="col s12 center-align white">
                        <img src="<?php echo base_url();?>dist/images/gallery/error-2.png" alt="" class="bg-image-500">
                        <h1 class="error-code m-0">500</h1>
                        <h6 class="mb-2">BAD REQUEST</h6>
                        <a class="btn waves-effect waves-light gradient-45deg-deep-purple-blue gradient-shadow mb-4" href="<?php echo site_url();?>/index">Back
                        TO Home</a>
                     </div>
                  </div>
               </div>
            </div>
            <div class="content-overlay"></div>
         </div>
      </div>
      <script src="<?php echo base_url();?>dist/js/vendors.min.js"></script>
      <script src="<?php echo base_url();?>dist/js/plugins.min.js"></script>
      <script src="<?php echo base_url();?>dist/js/search.min.js"></script>
      <script src="<?php echo base_url();?>dist/js/custom/custom-script.min.js"></script>
   </body>
</html>