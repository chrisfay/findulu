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
		
	//edit a free listing
	function free($listing_id = NULL)
	{
		if(is_null($listing_id) || !$this->profile_model->is_free_listing($listing_id, $this->session->userdata('user_id')))
		{
			redirect('profile/manage/view_listings');
			return;
		}
		
		//check if listing is valid and if its a free listing, otherwise re-route to listings page
		
			
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
		if(is_null($listing_id) || !$this->profile_model->is_premium_listing($listing_id, $this->session->userdata('user_id')))
		{
			redirect('profile/manage/view_listings');
			return;
		}
	
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
			$data['content'] = $this->load->view('user_profile/edit_premium_listing', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return false;
		}				
				
		//the form was not submitted, display default view
		if( ! $this->input->post('create_listing')) 
		{			
			$data['content'] = $this->load->view('user_profile/edit_premium_listing', $view_content, TRUE);
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
				$data['content'] = $this->load->view('user_profile/edit_premium_listing', $view_content, TRUE);
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
				$data['content'] = $this->load->view('user_profile/edit_premium_listing', $view_content, TRUE);
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
		'listing_id'       => $listing_id,
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
		);
		
		//lets update db with listing information
		if(! $this->profile_model->update_premium_listing($listing_data)) //failed to update db for some reason
		{
			$view_content['content']['message'] = '<h3>Failed to edit listing</h3>';														
			$data['content'] = $this->load->view('user_profile/edit_premium_listing', $view_content, TRUE);						
			$this->profile->_loadDefaultTemplate($data);
			return;
		}
		
		//succssfully created listing
		$view_content['content']['message'] = '<h3>Successfully edited listing</h3>';														
		$data['content'] = $this->load->view('user_profile/edit_premium_listing', $view_content, TRUE);								
		$this->profile->_loadDefaultTemplate($data);
	}
}