<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lib_Main
{
	function __construct()
	{
		$this->ci =& get_instance();		
	}
	
	function _mainNav($user_level = 1)
	{
		$nav = '<li><a href="'.base_url().'">Findulu Home</a></li>'.		   
			   (($user_level === 9 ) ? '<li><a href="#">Admin</a></li>' : '');
			   
		$nav =  '<li class="home"><a href="'.base_url().'" class="active"><span>Home</span></a></li>' .
				'<li class="about"><a href="#"><span>About</span></a></li>' .
				'<li class="support"><a href="#"><span>Suppport</span></a></li>' .
				'<li class="contact"><a href="#"><span>Contact Us</span></a></li>';
		
		return $nav;
	}
}