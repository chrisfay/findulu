<?php if(! is_null($content['message'])) : ?>
	<h3><?php echo $content['message'];?></h3>
<? endif; ?>

<?php echo $this->validation->error_string; ?>

<?php 
if(! is_null($content['search_results']))
{
	echo '<h3>Results:</h3>';
	foreach($content['search_results']->result() as $row)
		echo $row->title . "<br />";
}
?>

<?php
$search_term = array(
	'id'    => 'search_term',
	'name'  => 'search_term',
	'class' => 'input',
	'value' => '',
);

$search_location = array(
	'id'    => 'search_location',
	'name'  => 'search_location',
	'class' => 'input',
	'value' => '',
);

$submit = array(
	'class' => 'submit',
	'value' => 'Go!',
);
?>
<h3>Search listings.</h3>

<?php
echo form_open($this->uri->uri_string());
echo form_label('Search listings: ', $search_term['id']);
echo form_input($search_term);
echo form_label('City, state or zip: ', $search_location['id']);
echo form_input($search_location);
echo form_submit($submit);
echo form_hidden('listing_search_submitted','1');
echo form_close();
?>

