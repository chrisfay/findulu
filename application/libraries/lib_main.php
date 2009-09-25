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
		
		return $nav;
	}
}