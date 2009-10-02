<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

//content for just the user profile home page
	
echo anchor('profile/create_listing/free','Create free listing', array('title' => 'Create new listing')); //create new listing link
echo "<br>";
echo anchor('profile/purchase_listing','Purchase premium listing', array('title' => 'Create new listing')); //create new listing link
	
?>