<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head> 
<title>Findulu - Home page.</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<?php if($logged_in): ?>
<h1>Test home view - you are logged in</h1>
<a href="auth/logout">Log out</a> <a href="user_profile/">User Profile</a>
<?php else: ?>
<h1>Test home view - you are NOT logged in</h1>
<a href="/main/login">Log in</a>
<? endif; ?>
</body>
</html>