<?php
//create listing view - provides functions to create a new listing

$ad = array(
	'id'    => 'listing_ad',
	'name'  => 'ad',
	'class' => 'input',
	'value' => '',
);

$coupon = array(
	'id'    => 'listing_coupon',
	'name'  => 'coupon',
	'class' => 'input',
	'value' => '',
);

$submit = array(	
	'class' => 'input',
	'value' => 'Create listing',
);

//show errors
if( ! is_null($content['error']))
{	echo '<div class="error">';
	foreach($content['error'] as $error)
		echo $error;
	echo '</div>';
}
?>

<?php if(! is_null($content['message'])) echo $content['message']; ?>

<h2>Create a new premium listing</h2>

<?php //echo $this->validation->error_string; //output any validation errors?>

<?php
	echo form_open_multipart($this->uri->uri_string());
	
	echo form_label('Ad image (336 X 280 px):', $ad['id']);
	echo $this->validation->ad_error;	
	echo form_upload($ad);
		
	echo form_label('Coupon image (336 X 280 px):', $coupon['id']);
	echo $this->validation->coupon_error;	
	echo form_upload($coupon);
?>

	<label>Listing title (ie Business name, Organization name, etc...):</label>
	<?php echo $this->validation->title_error; ?>
	<input type="text" name="title" id="listing_title" value="<?php echo $this->validation->title; ?>" />
	<label>Listing description:</label>
	<?php echo $this->validation->description_error; ?>
	<textarea name="description" id="listing_description"><?php echo $this->validation->description; ?></textarea>
	<label>Phone (ie. 555-555-5555):</label>
	<?php echo $this->validation->phone_error; ?>
	<input type="text" name="phone" id="listing_phone" value="<?php echo $this->validation->phone; ?>" />
	<label>Email:</label>
	<?php echo $this->validation->email_error; ?>
	<input type="text" name="email" id="listing_email" value="<?php echo $this->validation->email; ?>" />
	<label>Website:</label>
	<?php echo $this->validation->url_error; ?>
	<input type="text" name="url" id="listing_url" value="<?php echo $this->validation->url; ?>" />
	<label>Address:</label>
	<?php echo $this->validation->address_error; ?>
	<input type="text" name="address" id="listing_address" value="<?php echo $this->validation->address; ?>" />
	<label>Zipcode:</label>
	<?php echo $this->validation->zipcode_error; ?>
	<input type="text" name="zipcode" id="listing_zipcode" value="<?php echo $this->validation->zipcode; ?>" />
	<label>Tags (separate tags with commas ie. plumbing,repair):</label>
	<?php echo $this->validation->tags_error; ?>
	<input type="text" name="tags" id="listing_tags" value="<?php echo $this->validation->tags; ?>" />

<?			
	echo form_hidden('create_listing','1');
?>

<?php echo form_submit($submit); ?>
<?php echo form_close(); ?>
