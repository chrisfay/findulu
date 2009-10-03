<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Edit_listing extends Controller
{
	function __construct()
	{
		parent::construct();
		
		//only logged in members allowed here
		if (!$this->tank_auth->is_logged_in(TRUE)) {							
			redirect('/main/login/');
		}
		
		//load libraries, helpers, etc...
		$this->load->library('profile');
		$this->load->config('tank_auth', TRUE);		
		$this->load->library('form_validation');		
		$this->load->model('user_profile/profile_model');			
		$this->load->library('validation');
		$this->validation->set_error_delimiters('<div class="error">','</div>');
	}
	
	function index($data = NULL)
	{
		$data['content'] = $this->load->view('user_profile/create_listing_index', array(), TRUE);
		$this->profile->_loadDefaultTemplate($data);
	}
	
	//TODO: code the edit listing controller function logic below (both free and premium)
	
	//edit a free listing
	function free($listing_id)
	{
		//build variables that should be passed to the view
		$view_content['content'] = array(
			'error'        => NULL,      //any error messages that should be displayed
			'file_details' => NULL,      //file details after upload is successful
			'message'      => NULL,      //any general messages to show			
		);
	}
	
	//edit premium listing
	function premium($listing_id)
	{
		//build variables that should be passed to the view
		$view_content['content'] = array(
			'error'        => NULL,      //any error messages that should be displayed
			'file_details' => NULL,      //file details after upload is successful
			'message'      => NULL,      //any general messages to show			
		);
	}
}