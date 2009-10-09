<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Create_listing extends Controller
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
		$this->load->library('lib_tags');
		$this->validation->set_error_delimiters('<div class="error">','</div>');		
	}
	
	function index($data = NULL)
	{	
		$data['content'] = $this->load->view('user_profile/create_listing_index', array(), TRUE);
		$this->profile->_loadDefaultTemplate($data);
	}		
	
	//take user input via form and create free listing 
	function free()
	{
		//build variables that should be passed to the avatar view
		$view_content['content'] = array(
			'error'        => NULL,      //any error messages that should be displayed
			'file_details' => NULL,      //file details after upload is successful
			'message'      => NULL,      //any general messages to show			
		);
	
		//form rules
		$rules['title']     = "trim|required|min_length[2]|max_length[255]";		
		$rules['phone']     = "trim|min_length[12]|max_length[12]|callback_is_valid_phone_number";
		$rules['email']     = "trim|required|valid_email|max_length[255]";
		$rules['address']   = "trim|min_length[2]|max_length[255]";		
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
			$data['content'] = $this->load->view('user_profile/create_free_listing', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return false;
		}		
				
		//the form was not submitted, display default view
		if( ! $this->input->post('create_listing')) 
		{			
			$data['content'] = $this->load->view('user_profile/create_free_listing', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return;
		}
		
		//process form data and insert into db
		$listing_data = array(
		'user_id'         => $this->session->userdata('user_id'),		
		'title'           => $this->input->post('title'),
		'phone'           => $this->input->post('phone'),
		'email'           => $this->input->post('email'),
		'address'         => $this->input->post('address'),			
		'zipcode'         => $this->input->post('zipcode'),		
		'creation_date'   => gmdate("Y-m-d H:i:s", time()),
		);
		
		$tag = trim($this->input->post('tags')); //single tag
		
		//lets update db
		if(! $listing_id = $this->profile_model->create_free_listing($listing_data)) //failed to update db for some reason
		{
			$view_content['content']['message'] = '<h3>Failed to create listing</h3>';														
			$data['content'] = $this->load->view('user_profile/create_free_listing', $view_content, TRUE);						
			$this->profile->_loadDefaultTemplate($data);
			return;
		}
		
		//insert single tag into database
		if(! $this->lib_tags->create_new_tag_and_mapp($tag, $listing_id))
		{
			//tag creation failed - we need to delete the listing info out of listings and listimg_meta
			$this->profile_model->delete_listing_data_perm($listing_id);
		
			$view_content['content']['message'] = '<h3>Failed to add tag to database</h3>';														
			$data['content'] = $this->load->view('user_profile/create_free_listing', $view_content, TRUE);						
			$this->profile->_loadDefaultTemplate($data);
			return;
		}		
		
		//succssfully created listing
		$view_content['content']['message'] = '<h3>Successfully created listing</h3>';														
		$data['content'] = $this->load->view('user_profile/create_free_listing', $view_content, TRUE);								
		$this->profile->_loadDefaultTemplate($data);
	}			
	
	//----------- CALLBACKS (for input fields) ----------------//
	
	//checks if the tag field has spaces or words separated by commas
	//RETURNS: FALSE on failure, or TRUE on success	
	function tag_word_count_check($str)
	{		
		if(sizeof(preg_split('/[;, \n]+/', $str)) > 1)
		{
			$this->validation->set_message('tag_word_count_check','The %s field can not be more than one word.');		
			return FALSE;
		}	
		
		return TRUE;
	}
	
	//checks if number of tags is greater than allowed number and if they are in the correct form
	//returns TRUE on success, or FALSE otherwise
	function valid_tags($str)
	{
		if(sizeof(preg_split('/[, \n]+/', $str)) > $this->config->item('ulu_max_tags'))
		{
			$this->validation->set_message('tag_count_premium','The %s field can not have more than ' . $this->config->item('ulu_max_tags') . ' words');		
			return FALSE;
		}	
		
		return TRUE;
	}
	
	//validations phone number field to format 555-555-5555
	function is_valid_phone_number($str)
	{
		if(ereg("^[0-9]{3}-[0-9]{3}-[0-9]{4}$", $str))					
			return TRUE;		
		else
		{			
			$this->validation->set_message('is_valid_phone_number','The %s field must be a valid format (ie. 3334445555, 333.444.5555, 333-444-5555, 333 444 5555, (333) 444 5555 and all combinations thereof.)');		
			return FALSE;
		}
	}
	
	//verify the zip entered is valid
	//return TRUE on success or FALSE otherwise
	function valid_zipcode($zipcode)
	{
		if(! $this->profile_model->valid_zipcode($zipcode))
		{
			$this->validation->set_message('valid_zipcode', 'Invalid zipcode');
			return FALSE;
		}
		
		return TRUE;
	}
	
	//----------- [END] CALLBACKS ----------------//
	
	//this function is called when a user types in the city input when creating a listing
	//it returns the result set to show during autocompletion
	//returns FALSE on failure, or the key/value array on success
	function autocomplete_zipcode()
	{
		if(! $q = $this->input->post('q'))
			return FALSE;
		
		//connect to db and get records that match the user's keywords
		if(! $results = $this->profile_model->autocomplete_zipcode($q))
			return FALSE;
				
		foreach ($results as $row) 
		{
			if (strpos(strtolower($row->zip_code), $q) !== false) 
			{
				echo $row->zip_code ."|".$row->zip_code."\n";				
			}
			//echo $row->city;
		}
	}
	
	function upload_logo()
	{
		$data['content'] = 'test';
		$this->profile->_loadDefaultTemplate($data);
	}
	
	function update_keywords()
	{
	
	}
	
	function update_listing_text()
	{
	
	}
	
	function update_tags()
	{
	
	}
	
	
}
/* End of file user_profile.php */
/*Location: /application/controllers/user_profile.php*/