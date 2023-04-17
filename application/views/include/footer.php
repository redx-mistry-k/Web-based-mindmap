		<!-- BEGIN: Footer
		<footer class="page-footer footer footer-static footer-dark gradient-45deg-indigo-light-blue gradient-shadow navbar-border navbar-shadow">
		  <div class="footer-copyright">
			<div class="container"><span>Copyright &copy;<?php echo date('Y');?> <a href="https://himat.in/" target="_blank">HiMat</a> All rights reserved.</span></div>
		  </div>
		</footer>-->
		<script src="<?php echo base_url();?>dist/js/vendors.min.js"></script>
		<script src="<?php echo base_url();?>dist/js/plugins.min.js"></script>
		<script src="<?php echo base_url();?>dist/js/search.min.js"></script>
		<script src="<?php echo base_url();?>dist/js/custom/custom-script.min.js"></script>
		<script src="<?php echo base_url();?>dist/js/scripts/customizer.min.js"></script>
		<script src="<?php echo base_url();?>dist/js/validator.min.js"></script>
		<script src="<?php echo base_url();?>dist/js/jquery.form.js"></script>
		<script src="<?php echo base_url();?>dist/vendors/data-tables/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>dist/vendors/data-tables/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo base_url();?>dist/vendors/data-tables/js/dataTables.select.min.js"></script>
		<script src="<?php echo base_url();?>dist/vendors/data-tables/js/buttons.flash.min.js"></script>
		<script src="<?php echo base_url();?>dist/vendors/data-tables/js/buttons.html5.min.js"></script>
		<script src="<?php echo base_url();?>dist/vendors/data-tables/js/buttons.print.min.js"></script>
		<script src="<?php echo base_url();?>dist/vendors/data-tables/js/jszip.min.js"></script>
		<script src="<?php echo base_url();?>dist/vendors/data-tables/js/pdfmake.min.js"></script>
		<script src="<?php echo base_url();?>dist/vendors/data-tables/js/vfs_fonts.js"></script>
		<script src="<?php echo base_url();?>dist/js/jquery-confirm.js"></script>
		<script src="<?php echo base_url();?>dist/js/select2.min.js"></script>
		<script src="<?php echo base_url();?>dist/js/form-select2.min.js"></script>
		<script src="<?php echo base_url();?>dist/js/moment.min.js"></script>
		<style>
			#ajax_table_filter label:nth-child(2){
				display:none;
			}
		</style>
		<script>
		$(document).ready(function() {
			$('#logout').click(function(){
				$.ajax({
					url: "<?php echo site_url();?>/login/logout",
					success: function(data){
						var rslt = JSON.parse(data);
						M.toast({html: rslt.message})
						window.location.replace(rslt.redirect);
					}
				});
			});
		});
			
		</script>
	</body>
</html>