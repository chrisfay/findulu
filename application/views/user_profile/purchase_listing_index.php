<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$zipcode = array(
	'id'    => 'listing_zipcode',
	'name'  => 'zipcode',
	'class' => 'input',
);

$interval_options = array(
	'1'   => 'Monthly',
	'2'   => 'Quarterly',
	'3'   => 'Every 6 months',
	'4'   => 'Annually',
);

$submit = array(
	'class' => 'submit_purchase',
	'value' => 'Purchase listing',
);

//output some general error
echo ((isset($view_content['message'])) ? $view_content['message'] : '');
?>

<h2>Purchase premium listings</h2>

<?
echo form_open($this->uri->uri_string());
echo form_label('Zipcode:', $zipcode['id']);
echo form_input($zipcode);
echo form_label('Payment interval:');
echo form_dropdown('payment_interval',$interval_options, 'annual');
echo form_submit($submit);
echo form_hidden('purchase_listing','1');
echo form_close();
?>


