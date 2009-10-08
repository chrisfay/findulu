<?php
//create listing view - provides functions to create a new listing

$submit = array(	
	'class' => 'input',
	'value' => 'Edit listing',
);

//show errors
if( ! is_null($content['error']))
{	
	foreach($content['error'] as $error)
		echo $error;
}
?>

<?php if(! is_null($content['message'])) echo '<h3>'.$content['message'] . '</h3>'; ?>

<h2>Edit listing</h2>

<?php
	//build comma sep list of tags	
	$tags = $content['tags'];	
	foreach($tags as $tag)
		$tagOutput = $tag->tag_text;
?>

<?php
	echo form_open_multipart($this->uri->uri_string());		
?>
	<label>Listing title (ie Business name, Organization name, etc...):</label>
	<?php echo $this->validation->title_error; ?>
	<input type="text" name="title" id="listing_title" value="<?php echo set_value('title', $content['existing_data']->title); ?>" />
	<label>Phone (ie. 555-555-5555):</label>
	<?php echo $this->validation->phone_error; ?>
	<input type="text" name="phone" id="listing_phone" value="<?php echo set_value('phone', $content['existing_data']->phone); ?>" />
	<label>Email:</label>
	<?php echo $this->validation->email_error; ?>
	<input type="text" name="email" id="listing_email" value="<?php echo set_value('email', $content['existing_data']->email); ?>" />
	<label>Address:</label>
	<?php echo $this->validation->address_error; ?>
	<input type="text" name="address" id="listing_address" value="<?php echo set_value('address', $content['existing_data']->address); ?>" />
	<label>Zipcode:</label>
	<?php echo $this->validation->zipcode_error; ?>
	<input type="text" name="zipcode" id="listing_zipcode" value="<?php echo set_value('zipcode', $content['existing_data']->zip); ?>" />
	<label>Tag (1 word allowed for free listing):</label>
	<?php echo $this->validation->tags_error; ?>
	<input type="text" name="tags" id="listing_tags" value="<?php echo set_value('tags', $tagOutput); ?>" />
<?
	echo form_hidden('edit_listing','1');		
?>

<?php echo form_submit($submit); ?>
<?php echo form_close(); ?>
