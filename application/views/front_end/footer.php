	<div id="footer">
		The footer | 
		<?php echo (($logged_in) ? '<a href="'.base_url().'auth/logout">Log out</a> | <a href="'.base_url().
					'profile/manage">User Profile</a>' : '<a href="'.base_url().'main/login">Log in</a> | '.
					'<a href="'.base_url().'main/register">Register</a>'); ?> 
					<?php echo (($this->tank_auth->is_admin()) ? ' | <a href="'.base_url().'admin/general">Admin</a>' : '')?>
	</div>
</div><!--wrapper-->
</body>
</html>