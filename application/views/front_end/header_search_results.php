<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head> 
<title><?php echo $page_title ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?php echo base_url() ?>css/reset.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>css/default.css" />

<script type="text/javascript" src="<?php echo base_url() ?>js/jquery-1.3.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/functions.js"></script>
<!--[if IE 6]>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/ie6.css" />
	<script type="text/javascript" src="<?php echo base_url() ?>js/DD_belatedPNG_0.0.7a.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>js/transparency.js"></script>
<![endif]--> 

<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/ie7.css" />		
<![endif]--> 

<?php
$search_term = array(
	'id'    => 'search_term',
	'name'  => 'search_term',
	'class' => 'input',
	'value' => 'Search for business or service here...',
);

$search_location = array(
	'id'    => 'search_location',
	'name'  => 'search_location',
	'class' => 'input',
	'value' => 'City, State or Zip',
);

$submit = array(
	'class' => 'submit',
	'value' => '',
);
?>

</head>
<body id="page">
<div id="headerWraper">
	<div id="header">
		<div class="user">
			<a href="#" class="login">Login</a> | <a href="#" class="register">Register</a>
		</div>
	
		<a href="<?php echo base_url(); ?>" class="logo"><span>findulu - the better business finder</span></a>
				
		<?php echo form_open('search/listings', array('id'=>'searchForm'));?>
			<div class="inputs">
				<?php echo form_input($search_term); ?>
				<?php echo form_input($search_location); ?>
			</div>
			<?php echo form_submit($submit); ?>
			<?php echo form_hidden('listing_search_submitted','1'); ?>
		<?php echo form_close(); ?>

		<div class="mainNavigation">
			<ul class="nav">
				<?php echo $navList ?>
			</ul>
		</div>
	</div>
</div><!--[END] #headerWraper -->
