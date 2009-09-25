<?php
//create listing view - provides functions to create a new listing

$logo = array(
	'id'    => 'listing_logo',
	'name'  => 'logo',
	'class' => 'input',
	'value' => '',
);

$title = array(
	'id'    => 'listing_title',
	'name'  => 'title',
	'class' => 'input',	
);

$description = array(
	'id'    => 'listing_description',
	'name'  => 'description',
	'class' => 'textarea',	
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

<h2>Create a new premium listing</h2>

<?php
	echo form_open_multipart($this->uri->uri_string());		
	
	echo form_label('Logo image:', $logo['id']);
	echo form_upload($logo);
	echo form_label('Title:', $title['id']);
	echo form_input($title);
	echo form_label('Description:', $description['id']);
	echo form_textarea($description);
	echo form_label('City:', $city['id']);
	echo form_input($city);
	echo form_label('State:', $state['id']);
	echo form_input($state);
	echo form_label('Zipcode:', $zipcode['id']);
	echo form_input($zipcode);
	echo form_hidden('create_listing','1');
?>

<?php echo form_submit($submit); ?>
<?php echo form_close(); ?>
