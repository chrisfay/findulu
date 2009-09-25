<div id="content">
	<?php if($logged_in): ?>
	<h1>Welcome <?php echo $username ?> - you are logged in</h1>	
	<?php else: ?>
	<h1>Findulu home</h1>	
	<? endif; ?>
	
	<?php echo $content; ?>
</div>