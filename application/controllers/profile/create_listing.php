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
		'tags'            => $this->input->post('tags'),
		'creation_date'   => gmdate("Y-m-d H:i:s", time()),
		);
		
		//lets update db
		if(! $this->profile_model->create_free_listing($listing_data)) //failed to update db for some reason
		{
			$view_content['content']['message'] = '<h3>Failed to create listing</h3>';														
			$data['content'] = $this->load->view('user_profile/create_free_listing', $view_content, TRUE);						
			$this->profile->_loadDefaultTemplate($data);
			return;
		}
		
		//succssfully created listing
		$view_content['content']['message'] = '<h3>Successfully created listing</h3>';														
		$data['content'] = $this->load->view('user_profile/create_free_listing', $view_content, TRUE);								
		$this->profile->_loadDefaultTemplate($data);
	}		
	
	
	//create a premium listing
	function premium()
	{		
		//build variables that should be passed to the avatar view
		$view_content['content'] = array(
			'error'        => NULL,      //any error messages that should be displayed
			'file_details' => NULL,      //file details after upload is successful
			'message'      => NULL,      //any general messages to show			
		);
	
		//form rules
		$rules['title']         = "trim|required|min_length[2]|max_length[255]";		
		$rules['description']   = "trim|max_length[5000]";		
		$rules['phone']         = "trim|required|min_length[12]|max_length[12]|callback_is_valid_phone_number";
		$rules['email']         = "trim|required|valid_email|max_length[255]";
		$rules['url']           = "trim|max_length[255]";
		$rules['address']       = "trim|required|min_length[2]|max_length[255]";	
		$rules['zipcode']       = "trim|required|min_length[5]|max_length[5]|numeric|callback_valid_zipcode";
		$rules['tags']          = "trim|required|min_length[2]|max_length[255]|callback_tag_count_premium";
		$this->validation->set_rules($rules);
		
		//define the fields we're using for validation purposes
		$fields['ad']           = "Ad";		
		$fields['coupon']       = "Coupon";		
		$fields['title']        = "Title";		
		$fields['description']  = "Description";		
		$fields['phone']        = "Phone";
		$fields['email']        = "Email";
		$fields['url']          = "Url";
		$fields['address']      = "Address";		
		$fields['zipcode']      = "Zipcode";
		$fields['tags']         = "Tags";
		$this->validation->set_fields($fields);
		
		//run validation
		if($this->validation->run() == FALSE)	
		{		
			$data['content'] = $this->load->view('user_profile/create_premium_listing', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return false;
		}				
				
		//the form was not submitted, display default view
		if( ! $this->input->post('create_listing')) 
		{			
			$data['content'] = $this->load->view('user_profile/create_premium_listing', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return;
		}
		
		/////////////////////////////// Ad upload stuff ///////////////////////////////
		$config['upload_path']   = $this->config->item('ulu_upload_path');
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = '1000';
		$config['max_width']     = '400';
		$config['max_height']    = '400';		
		$field_name              = 'ad';		
		
		$this->load->library('upload', $config);	
		
		//check if a ad was inlcuded, if so, we need to verify and upload. If a ad was not included,
		//we should use the default ad for all customers and move on to uploading form
		$uploadedFileName = '';
		if(isset($_FILES['ad']['name']) && strlen($_FILES['ad']['name']) > 0)
		{			
			if( ! $this->upload->do_upload($field_name))
			{				
				$view_content['content']['error'] = array('error' => $this->upload->display_errors());			
				$data['content'] = $this->load->view('user_profile/create_premium_listing', $view_content, TRUE);
				$this->profile->_loadDefaultTemplate($data);
				return;
			}
			else			
			{
				$view_content['content']['file_details'] = $this->upload->data();	//build file upload view output
				$uploadedFileName = $view_content['content']['file_details']['file_name'];
			}			
		}
		else //assign default ad for upload		
			//$uploadedFileName = $this->config->item('ulu_default_listing_ad_image');			
			$uploadedFileName = NULL;	
		/////////////////////////////// Ad upload stuff END///////////////////////////////
		
		/////////////////////////////// Coupon upload stuff ///////////////////////////////
		$config['upload_path']   = $this->config->item('ulu_upload_path');
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = '1000';
		$config['max_width']     = '400';
		$config['max_height']    = '400';		
		$field_name              = 'coupon';		
		
		$this->load->library('upload', $config);	
		
		//check if a ad was inlcuded, if so, we need to verify and upload. If a ad was not included,
		//we should use the default ad for all customers and move on to uploading form
		$uploadedCouponFileName = '';
		if(isset($_FILES['coupon']['name']) && strlen($_FILES['coupon']['name']) > 0)
		{			
			if( ! $this->upload->do_upload($field_name))
			{				
				$view_content['content']['error'] = array('error' => $this->upload->display_errors());			
				$data['content'] = $this->load->view('user_profile/create_premium_listing', $view_content, TRUE);
				$this->profile->_loadDefaultTemplate($data);
				return;
			}
			else			
			{
				$view_content['content']['file_details'] = $this->upload->data();	//build file upload view output
				$uploadedCouponFileName = $view_content['content']['file_details']['file_name'];
			}			
		}
		else //assign default logo for upload		
			//$uploadedCouponFileName = $this->config->item('ulu_default_listing_coupon_image');			
			$uploadedCouponFileName = NULL;
		/////////////////////////////// Ad upload stuff END///////////////////////////////
		
		
		//process form data and insert into db
		$listing_data = array(
		'user_id'         => $this->session->userdata('user_id'),
		'ad'              => $uploadedFileName,
		'coupon'          => $uploadedCouponFileName,
		'title'           => $this->input->post('title'),
		'description'     => $this->input->post('description'),
		'phone'           => $this->input->post('phone'),
		'email'           => $this->input->post('email'),
		'url'             => $this->input->post('url'),
		'address'         => $this->input->post('address'),			
		'zipcode'         => $this->input->post('zipcode'),
		'tags'            => $this->input->post('tags'),
		'creation_date'   => gmdate("Y-m-d H:i:s", time()),
		);
		
		//lets update db with listing information
		if(! $this->profile_model->create_premium_listing($listing_data)) //failed to update db for some reason
		{
			$view_content['content']['message'] = '<h3>Failed to create listing</h3>';														
			$data['content'] = $this->load->view('user_profile/create_premium_listing', $view_content, TRUE);						
			$this->profile->_loadDefaultTemplate($data);
			return;
		}
		
		//succssfully created listing
		$view_content['content']['message'] = '<h3>Successfully created listing</h3>';														
		$data['content'] = $this->load->view('user_profile/create_premium_listing', $view_content, TRUE);								
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
	
	//checks if number of tags is greater than allowed number
	//returns TRUE if less than max, or FALSE otherwise
	function tag_count_premium($str)
	{
		if(sizeof(preg_split('/[;, \n]+/', $str)) > $this->config->item('ulu_max_tags'))
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