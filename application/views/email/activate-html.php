<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>Welcome to <?php echo $site_name; ?>!</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Welcome to <?php echo $site_name; ?>!</h2>
Thanks for joining <?php echo $site_name; ?> - <i>the better business finder.</i> Please click the link below to verify your email address:<br />
<br />
<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/main/activate/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;">Finish your registration...</a></b></big><br />
<br />
Having trouble clicking the link above? Copy the url below into your browser address bar:<br /><br />
<nobr><a href="<?php echo site_url('/main/activate/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;"><?php echo site_url('/main/activate/'.$user_id.'/'.$new_email_key); ?></a></nobr><br />
<br />
Please note, this activation email will expire in <?php echo $activation_period; ?> hours, your registration will become invalid, and you will have to register again.<br />
<br />
<br />
<h3>Upon Activating:</h3>
Your <?php echo $site_name; ?> account has been successfully created.<br /><br />

You <a href="http://findulu.com">can log</a> in with the following information:<br />
<?php if (strlen($username) > 0) { ?>Username: <?php echo $username; ?><br /><?php } ?>
Email address: <?php echo $email; ?><br />
<?php if (isset($password)) { ?>Password: <?php echo $password; ?><br /><?php } ?>
<br />
<br />

<h3>Some useful links:</h3>
<a href="#">Post a new ad</a><br />
<a href="#">Edit/Update your user profile</a><br />
<a href="#">View your earnings</a><br />
<a href="http://ww.findulu.com/tags/">See what others are saying about businesses in your area</a>
<br />
<br />

<h3>Findulu Support</h3>
If you're just getting started, there are tons of helpful resources available and easily searched for on our support site.<br /> 
<a href="#">Getting started</a> | <a href="#">More Frequently Asked Questions</a>
<br />
<br />
<br />

We hope you enjoy participating in <?php echo $site_name; ?>. If you have any questions or comments, please let us know.<br />
Have fun!<br />
The <?php echo $site_name; ?> Team
</td>
</tr>
</table>
</div>
</body>
</html>