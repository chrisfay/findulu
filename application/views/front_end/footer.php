<div class="clear"></div>
<div id="footerWrapper">
	<div id="footer">
		&copy; Copyright 2009 Findulu, LLC. All Rights Reserved.
		<?php echo (($this->tank_auth->is_admin()) ? ' | <a href="'.base_url().'admin/general">Admin</a>' : '')?>
	</div><!--[END] #footer -->
</div><!--[END] #footerWraper -->

<!-- JS scripts -->
<script type="text/javascript" src="<?php echo base_url() ?>js/functions.js"></script>
<?php if(! $logged_in) : ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/basic.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.simplemodal.js"></script>
<?php endif; ?>


<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.uni-form.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/ui.core.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/ui.stars.js"></script>	

<?php if($review_page): ?>
<script type="text/javascript">
	$(function(){
		$(".starify").children().not(":input").hide();
		
		// Create stars from :radio boxes
		$(".starify").stars({
			<?php echo ((! $rating_allowed) ? 'disabled: true,' : '') ?>
			cancelShow: false			
		});
		
		<?php echo ((! $rating_allowed) ? '$(".starify").stars("select", '.$rating_value_user.');' : '$(".starify").stars("select", '.$rating_value_global.');') ?>
	});
</script>
<?php elseif($single_listing_page) : ?>
<script type="text/javascript">
	$(function(){
		$(".starify").children().not(":input").hide();
		
		// Create stars from :radio boxes
		$(".starify").stars({
			cancelShow: false,
			disabled: true
		});
		
		<?php echo '$(".starify").stars("select", '.$rating_value_global.');'; ?>
	});
</script>
<?php endif; ?>
</body>
</html>