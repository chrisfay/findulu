	<div id="footer">
		The footer | <a href="<?php echo base_url() ?>auth/logout">log out</a> <?php echo (($this->tank_auth->is_admin()) ? ' | <a href="'.base_url().'admin/general">Admin</a>' : '')?>
	</div>
</div><!--wrapper-->
</body>
</html>