<div class="clear"></div>
<div id="footerWrapper">
	<div id="footer">
		&copy; Copyright 2009 Findulu, LLC. All Rights Reserved.
		<?php echo (($this->tank_auth->is_admin()) ? ' | <a href="'.base_url().'admin/general">Admin</a>' : '')?>
	</div><!--[END] #footer -->
</div><!--[END] #footerWraper -->

<!-- JS scripts -->
<script type="text/javascript" src="<?php echo base_url() ?>js/jquery-1.3.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/functions.js"></script>
<?php if(! $logged_in) : ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/basic.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.simplemodal.js"></script>
<?php endif; ?>
</body>
</html>