<?php
//create listing view - provides functions to create a new listing

$title = array(
	'id'    => 'listing_title',
	'name'  => 'title',
	'class' => 'input',	
);

$phone = array(
	'id'    => 'listing_phone',
	'name'  => 'phone',
	'class' => 'input',	
);

$email = array(
	'id'    => 'listing_email',
	'name'  => 'email',
	'class' => 'input',	
);

$address = array(
	'id'    => 'listing_address',
	'name'  => 'address',
	'class' => 'input',	
);

$city = array(
	'id'    => 'listing_city',
	'name'  => 'city',
	'class' => 'input',	
);

$state = array(
	'id'    => 'listing_state',
	'name'  => 'state',
	'class' => 'input',	
);

$zipcode = array(
	'id'    => 'listing_zipcode',
	'name'  => 'zipcode',
	'class' => 'input',	
);

$tags = array(
	'id'    => 'listing_tags',
	'name'  => 'tags',
	'class' => 'input',	
);

$submit = array(	
	'class' => 'input',
	'value' => 'Create listing',
);

//show errors
if( ! is_null($content['error']))
{	
	foreach($content['error'] as $error)
		echo $error;
}
?>

<?php if(! is_null($content['message'])) echo $content['message']; ?>

<h2>Create a new free listing</h2>

<?php echo $this->validation->error_string; //output any validation errors?>

<?php
	echo form_open_multipart($this->uri->uri_string());		


	echo form_label('Title:', $title['id']);
	echo form_input($title);
	echo form_label('Phone:', $phone['id']);
	echo form_input($phone);
	echo form_label('Email:', $email['id']);
	echo form_input($email);
	echo form_label('Address:', $address['id']);
	echo form_input($address);
	echo form_label('City:', $city['id']);
	echo form_input($city);
	echo form_label('State Abbreviation:', $state['id']);
	echo form_input($state);
	echo form_label('Zipcode:', $zipcode['id']);
	echo form_input($zipcode);
	echo form_label('Tag (1 word allowed for free listing):', $tags['id']);
	echo form_input($tags);
	echo form_hidden('create_listing','1');
?>

<?php echo form_submit($submit); ?>
<?php echo form_close(); ?>
