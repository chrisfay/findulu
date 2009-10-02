<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head> 
<title>Findulu - Home page.</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<?php if($this->tank_auth->is_logged_in()): ?>
	<h1>Welcome <?php echo $view_content['username']; ?> - you are logged in</h1>
<?php else: ?>
	<h1>Welcome - you are NOT logged in</h1>		
<? endif; ?>
</body>
</html>