<?php	
$avatar = array(
	'name'  => 'avatar',
	'id'    => 'avatar',
	'value' => '',
	'class' => 'input',
);
$submit = array(	
	'class' => 'input',
	'value' => 'upload',
);
?>

<?php 
//show errors
if( ! is_null($content['error']))
{	
	foreach($content['error'] as $error)
		echo $error;
}
?>
	
<?php if(! is_null($content['message'])) echo $content['message']; ?>

<h2>Upload a new avatar</h2>

<?php
	echo form_open_multipart($this->uri->uri_string());	
	echo form_upload($avatar);
	echo form_hidden('avatar_uploaded','1');
?>

<?php echo form_submit($submit); ?>
<?php echo form_close(); ?>

<a href="<?php echo base_url() ?>user_profile/restore_default_avatar">Restore default avatar</a>




