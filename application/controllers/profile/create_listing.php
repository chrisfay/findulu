<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Create_listing extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		//only logged in members allowed here
		if (!$this->tank_auth->is_logged_in(TRUE)) {							
			redirect('/auth/login/');
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
		$rules['title']   = "trim|required|min_length[2]|max_length[255]";
		$rules['description']   = "trim|required|min_length[2]|max_length[50000]";
		$rules['city']   = "trim|required|min_length[2]|max_length[255]|alpha";
		$rules['state']   = "trim|required|min_length[2]|max_length[255]|alpha";
		$rules['zipcode']   = "trim|required|min_length[5]|max_length[5]|numeric";
		$this->validation->set_rules($rules);
		
		//run validation
		if($this->validation->run() == FALSE)	
		{		
			$data['content'] = $this->load->view('user_profile/create_free_listing', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return false;
		}
					
		$config['upload_path']   = $this->config->item('ulu_upload_path');
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = '1000';
		$config['max_width']     = '400';
		$config['max_height']    = '400';		
		$field_name = 'logo';		
		
		$this->load->library('upload', $config);	
				
		//the form was not submitted, display default view
		if( ! $this->input->post('create_listing')) 
		{			
			$data['content'] = $this->load->view('user_profile/create_free_listing', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return;
		}
		
		//check if a logo was inlcuded, if so, we need to verify and upload. If a logo was not included,
		//we should use the default logo for all customers and move on to uploading form
		$uploadedFileName = '';
		if(isset($_FILES['logo']['name']) && strlen($_FILES['logo']['name']) > 0)
		{			
			if( ! $this->upload->do_upload($field_name))
			{				
				$view_content['content']['error'] = array('error' => $this->upload->display_errors());			
				$data['content'] = $this->load->view('user_profile/create_free_listing', $view_content, TRUE);
				$this->profile->_loadDefaultTemplate($data);
				return;
			}
			else			
			{
				$view_content['content']['file_details'] = $this->upload->data();	//build file upload view output
				$uploadedFileName = $view_content['content']['file_details']['file_name'];
			}			
		}
		else //assign default logo for upload		
			$uploadedFileName = $this->config->item('ulu_default_listing_logo_image');			
		
		
		//process form data and insert into db
		$listing_data = array(
		'user_id'     => $this->session->userdata('user_id'),
		'logo'        => $uploadedFileName,
		'title'       => $this->input->post('title'),
		'description' => $this->input->post('description'),
		'city'        => $this->input->post('city'),
		'state'       => $this->input->post('state'),
		'zipcode'     => $this->input->post('zipcode'),
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
	
	function premium()
	{
		//build variables that should be passed to the avatar view
		$view_content['content'] = array(
			'error'        => NULL,      //any error messages that should be displayed
			'file_details' => NULL,      //file details after upload is successful
			'message'      => NULL,      //any general messages to show			
		);
	
		//form rules
		$rules['title']   = "trim|required|min_length[2]|max_length[255]";
		$rules['description']   = "trim|required|min_length[2]|max_length[50000]";
		$rules['city']   = "trim|required|min_length[2]|max_length[255]|alpha";
		$rules['state']   = "trim|required|min_length[2]|max_length[255]|alpha";
		$rules['zipcode']   = "trim|required|min_length[5]|max_length[5]|numeric";
		$this->validation->set_rules($rules);
		
		//run validation
		if($this->validation->run() == FALSE)	
		{		
			$data['content'] = $this->load->view('user_profile/create_premium_listing', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return false;
		}
					
		$config['upload_path']   = $this->config->item('ulu_upload_path');
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = '1000';
		$config['max_width']     = '400';
		$config['max_height']    = '400';		
		$field_name = 'logo';		
		
		$this->load->library('upload', $config);	
				
		//the form was not submitted, display default view
		if( ! $this->input->post('create_listing')) 
		{			
			$data['content'] = $this->load->view('user_profile/create_premium_listing', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return;
		}
		
		//check if a logo was inlcuded, if so, we need to verify and upload. If a logo was not included,
		//we should use the default logo for all customers and move on to uploading form
		$uploadedFileName = '';
		if(isset($_FILES['logo']['name']) && strlen($_FILES['logo']['name']) > 0)
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
		else //assign default logo for upload		
			$uploadedFileName = $this->config->item('ulu_default_listing_logo_image');			
		
		
		//process form data and insert into db
		$listing_data = array(
		'user_id'     => $this->session->userdata('user_id'),
		'logo'        => $uploadedFileName,
		'title'       => $this->input->post('title'),
		'description' => $this->input->post('description'),
		'city'        => $this->input->post('city'),
		'state'       => $this->input->post('state'),
		'zipcode'     => $this->input->post('zipcode'),
		);
		
		//lets update db
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