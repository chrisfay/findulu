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

<?php echo anchor('profile/create_listing/free','Create free listing', array('title' => 'Create new listing')); //create new listing link ?><br />
<?php echo anchor('profile/purchase_listing','Purchase premium listing', array('title' => 'Create new listing')); //create new listing link ?>

<?php
$search_term = array(
	'id'    => 'search_term',
	'name'  => 'search_term',
	'class' => 'input',
	'value' => '',
);

$submit = array(
	'class' => 'submit',
	'value' => 'Go!',
);
?>

<br />
<br />
<h3>Search listings.</h3>

<?php
echo form_open('search/listings');
echo form_label('Search listings: ', $search_term['id']);
echo form_input($search_term);
echo form_submit($submit);
echo form_hidden('listing_search_submitted','1');
echo form_close();
?>


</body>
</html>