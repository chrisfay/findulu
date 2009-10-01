<div id="content">
	<div class="userInfo">
		<img src="<?php echo $avatarPath?>" class="userAvatar" alt="user avatar" />
		<h1><?php echo $username ?> - User profile</h1>
		<div class="clear"></div>		
	</div>
	<?php echo ((isset($content)) ? $content : '' ); ?>
</div>