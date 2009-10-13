<?php if($this->tank_auth->is_logged_in()): ?>
	<h1>Welcome <?php echo $view_content['username']; ?> - you are logged in</h1>
<?php else: ?>
	<h1>Welcome - you are NOT logged in</h1>		
<? endif; ?>

<?php echo anchor('profile/create_listing/free','Create free listing', array('title' => 'Create new listing')); //create new listing link ?><br />
<?php echo anchor('profile/purchase_listing','Purchase premium listing', array('title' => 'Create new listing')); //create new listing link ?>