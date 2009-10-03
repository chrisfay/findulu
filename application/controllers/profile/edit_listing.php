<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Edit_listing extends Controller
{
	function __construct()
	{
		parent::__construct();
		
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
		//user must call one of the edit functions, not the root of the controller
		redirect('profile/manage/view_listings');
	}
	
	//TODO: code the edit listing controller function logic below (both free and premium)
	
	//edit a free listing
	function free($listing_id = NULL)
	{
		if(is_null($listing_id))
		{
			redirect('profile/manage/view_listings');
			return;
		}
			
		//build variables that should be passed to the view
		$view_content['content'] = array(
			'error'        => NULL,      //any error messages that should be displayed
			'file_details' => NULL,      //file details after upload is successful
			'message'      => NULL,      //any general messages to show			
		);
		
		//form rules
		$rules['title']     = "trim|required|min_length[2]|max_length[255]";		
		$rules['phone']     = "trim|required|min_length[12]|max_length[12]|callback_is_valid_phone_number";
		$rules['email']     = "trim|required|valid_email|max_length[255]";
		$rules['address']   = "trim|required|min_length[2]|max_length[255]";		
		$rules['zipcode']   = "trim|required|min_length[5]|max_length[5]|numeric|callback_valid_zipcode";
		$rules['tags']      = "trim|required|min_length[2]|max_length[255]|callback_tag_word_count_check";
		$this->validation->set_rules($rules);
		
		//define the fields we're using for validation purposes
		$fields['title']     = "Title";		
		$fields['phone']     = "Phone";
		$fields['email']     = "Email";
		$fields['address']   = "Address";		
		$fields['zipcode']   = "Zipcode";
		$fields['tags']      = "Tags";
		$this->validation->set_fields($fields);
		
		//run validation
		if($this->validation->run() == FALSE)	
		{		
			$data['content'] = $this->load->view('user_profile/edit_free_listing', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return false;
		}		
				
		//the form was not submitted, display default view
		if( ! $this->input->post('create_listing')) 
		{			
			$data['content'] = $this->load->view('user_profile/edit_free_listing', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return;
		}
		
		//process form data and insert into db
		$listing_data = array(
		'user_id'         => $this->session->userdata('user_id'),
		'listing_id'      => $listing_id,
		'title'           => $this->input->post('title'),
		'phone'           => $this->input->post('phone'),
		'email'           => $this->input->post('email'),
		'address'         => $this->input->post('address'),			
		'zipcode'         => $this->input->post('zipcode'),
		'tags'            => $this->input->post('tags'),		
		);
		
		//lets update db
		if(! $this->profile_model->update_free_listing($listing_data,$this->session->userdata('user_id'))) //failed to update db for some reason
		{
			$view_content['content']['message'] = '<h3>Failed to update listing</h3>';														
			$data['content'] = $this->load->view('user_profile/edit_free_listing', $view_content, TRUE);						
			$this->profile->_loadDefaultTemplate($data);
			return;
		}
		
		//succssfully created listing
		$view_content['content']['message'] = '<h3>Successfully updated listing</h3>';														
		$data['content'] = $this->load->view('user_profile/edit_free_listing', $view_content, TRUE);								
		$this->profile->_loadDefaultTemplate($data);
	}
	
	//edit premium listing
	function premium($listing_id = NULL)
	{
		if(is_null($listing_id))
		{
			redirect('profile/manage/view_listings');
			return;
		}
	
		//build variables that should be passed to the view
		$view_content['content'] = array(
			'error'        => NULL,      //any error messages that should be displayed
			'file_details' => NULL,      //file details after upload is successful
			'message'      => NULL,      //any general messages to show			
		);
	}
}