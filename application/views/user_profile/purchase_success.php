<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

<h3>Purchase successful!</h3>
<p>A new premium listing has been created for you. Click the link below to populate your listing.</p>

<?echo anchor('profile/edit_listing/premium/'.$view_content['listing_id'], 'Populate listing');?>