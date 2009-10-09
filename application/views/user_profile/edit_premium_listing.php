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
	'value' => 'Edit listing',
);

//show errors
if( ! is_null($content['error']))
{	echo '<div class="error">';
	foreach($content['error'] as $error)
		echo $error;
	echo '</div>';
}
?>

<?php
	//build comma sep list of tags	
	if(sizeof($content['tags']) > 0)
	{				
		$tags = $content['tags'];	
		$tagCount = sizeof($tags);
		$tagOutput = '';
		$i = 1;
		foreach($tags as $tag)
		{
			$tagOutput .= (($tagCount == $i) ? $tag->tag_text : $tag->tag_text . ',');
			$i++;
		}
	}
	else
		$tagOutput = ''; //no tags created yet(shell listing so far)
?>

<?php if(! is_null($content['message'])) echo $content['message']; ?>

<h2>Edit premium listing</h2>

<?php //echo $this->validation->error_string; //output any validation errors?>

<?php
	echo form_open_multipart($this->uri->uri_string());

	echo form_label('Ad image (336 X 280 px):', $ad['id']);
	echo $this->validation->ad_error;	
	echo form_upload($ad);
	echo '<img src="'.base_url().'uploads/'.((is_null($content['existing_data']->listing_ad_filename)) ? $this->config->item('ulu_default_listing_ad_image') : $content['existing_data']->listing_ad_filename).'" alt="ad image" /><br />';

	echo form_label('Coupon image (336 X 280 px):', $coupon['id']);
	echo $this->validation->coupon_error;	
	echo form_upload($coupon);
	echo '<img src="'.base_url().'uploads/'.((is_null($content['existing_data']->listing_coupon_filename)) ? $this->config->item('ulu_default_listing_coupon_image') : $content['existing_data']->listing_coupon_filename).'" alt="coupon image" /><br />';	
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
	<label>Website:</label>
	<?php echo $this->validation->url_error; ?>
	<input type="text" name="url" id="listing_url" value="<?php echo set_value('url', $content['existing_data']->listing_url); ?>" />
	<label>Address:</label>
	<?php echo $this->validation->address_error; ?>
	<input type="text" name="address" id="listing_address" value="<?php echo set_value('address', $content['existing_data']->address); ?>" />
	<label>Zipcode:</label>
	<?php echo $this->validation->zipcode_error; ?>
	<input type="text" name="zipcode" id="listing_zipcode" value="<?php echo set_value('zipcode', $content['existing_data']->zip); ?>" />
	<label>Tags (separate tags with commas ie. plumbing,repair):</label>
	<?php echo $this->validation->tags_error; ?>
	<input type="text" name="tags" id="listing_tags" value="<?php echo set_value('tags', $tagOutput); ?>" />
	<label>Listing description:</label>
	<div class="clear" style="padding-bottom:25px"></div>
	<?php echo $this->validation->description_error; ?>
	<textarea name="description" id="listing_description"><?php echo set_value('description', $content['existing_data']->listing_description);?></textarea>

<?			
	echo form_hidden('edit_listing','1');
?>

<?php echo form_submit($submit); ?>
<?php echo form_close(); ?>

<!--show description preview -->
<div class="wmd-preview"></div>
