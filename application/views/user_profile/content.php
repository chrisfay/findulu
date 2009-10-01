<div id="content">
	<div class="userInfo">
		<img src="<?php echo $avatarPath?>" class="userAvatar" alt="user avatar" />
		<h1><?php echo $username ?> - User profile</h1>
		<div class="clear"></div>
		<?
			//TODO - add create listings button for home page
			//listing_buttons
		
		?>
	</div>
	<?php echo ((isset($content)) ? $content : '' ); ?>
</div>