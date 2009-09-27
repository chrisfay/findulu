<?php
$delete_user = array(
	'id'     => 'delete_user',
	'name'   => 'delete_user',
	'class'  => 'input',
	'value'  => '',
);

$delete_listing = array(
	'id'     => 'delete_listing',
	'name'   => 'delete_listing',
	'class'  => 'input',
	'value'  => '',
);

$submit = array(
	'id'     => 'submit',
	'class'  => 'submit',
    'value'  => 'Submit',	
);
?>

<div id="content">
	<div class="userInfo">
		<img src="<?php echo $avatarPath?>" class="userAvatar" alt="user avatar" />
		<h1><?php echo $username ?> - admin pannel</h1>
		<div class="clear"></div>
	</div>
	
	<div id="adminOptions">
		
		<h2>Admin options</h2>
		
		<?php 
		//show any error messages
		if(isset($errors))
		{
			foreach($errors as $key => $err)
				echo '<div class="error">Function: '.$key.'<br /> Error: '.$err."</div>";
		}				
		
		//show any regular messages
		if(isset($messages))
		{
			foreach($messages as $key => $mess)
				echo '<div class="messages">Function: '.$key.'<br /> Message: '.$mess."</div>";
		}				
		?>
		
		<?php echo $this->validation->error_string; //output any validation errors?>
				
		<!--[START] user deletion form -->		
		<?php 	echo form_open('admin/general/delete_user');
				echo form_label('Delete user by user_id:',$delete_user);
				echo form_input($delete_user);
				echo form_submit($submit);
				echo form_close();
		?>
		<!--[END] user deletion form -->
		
		<!--[START] listing deletion form -->		
		<?php 	echo form_open('admin/general/delete_listing');
				echo form_label('Delete listing by listing_id:',$delete_listing);
				echo form_input($delete_listing);
				echo form_submit($submit);
				echo form_close();
		?>
		<!--[END] listing deletion form -->
				
		<!--[START] listing deletion form -->		
		<br>
		<?php 	echo form_open('admin/general/delete_all_listings');
				echo form_label('Delete all listings:',$submit);				
				echo form_submit($submit);
				echo form_close();
		?>
		<br>
		<!--[END] listing deletion form -->
		
		
		<!--[START] show default site data -->
		<div class="defaultData">
			<h3>All users</h3>
			<?php 
			if(isset($user_info))
			{
				foreach($user_info as $user)
					echo $user->id.":".$user->username."<br />";				
			}			
			?>
		</div>
		<!--[END] show default site data -->
	</div>
</div>
