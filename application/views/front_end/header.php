<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head> 
<title><?php echo $page_title ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?php echo base_url() ?>css/reset.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>css/simple_model.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>css/default.css" />

<!--[if IE 6]>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/ie6.css" />
	<script type="text/javascript" src="<?php echo base_url() ?>js/DD_belatedPNG_0.0.7a.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>js/transparency.js"></script>
<![endif]--> 

<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/ie7.css" />		
<![endif]--> 

<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
	'class' => 'input required',
);

$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
	'class' => 'input required',
);

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
<body id="<?php echo (($define === 'SEARCH_RESULTS') ? 'shortPage' : 'page'); ?>">
<div id="headerWraper">
	<div id="header">
		<div class="user">
			<div id="basic-modal">			
				<?php echo ((! $logged_in) ? '<a href="main/login" class="login">Login</a>' : 'Welcome <a href="profile/" class="profile">'. $username . '</a> <a href="'.base_url().'auth/logout" class="logout">Logout</a>'); ?> | <a href="#" class="register">Register</a>
					
			</div>
		</div>
		
		<!-- [START] modal signin form (uses simple dialog framework) -->
		<div id="basic-modal-content">
			<h1>Login.</h1>
			<p>Fill in the details below to log into Findulu.</p>					
			<form action="<?php echo base_url() ?>main/login" method="post" id="loginForm">
				<label for="username">Username</label>
				<?php echo form_input($login); ?>
				<label for="password" class="tall">Password</label>
				<?php echo form_password($password); ?>
				<input type="submit" value="SUBMIT" class="submit" />
			</form>							
		</div>
		<!-- [END] modal signin form (uses simple dialog framework) -->
	
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
