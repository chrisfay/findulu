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
		$this->load->library('lib_tags');
		$this->load->library('HTMLPurifier');
		$this->load->model('tag_model');
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
		//listing id is invalid (or empty) or not owned by the logged in user - route to listing page
		if(is_null($listing_id) || !$this->profile_model->is_free_listing($listing_id, $this->session->userdata('user_id')))
		{
			redirect('profile/manage/view_listings');
			return;
		}		
			
		//build variables that should be passed to the view
		$view_content['content'] = array(
			'error'          => NULL,      //any error messages that should be displayed
			'file_details'   => NULL,      //file details after upload is successful
			'message'        => NULL,      //any general messages to show						
			'existing_data'  => $this->profile_model->get_single_listing_details($listing_id, $this->session->userdata('user_id')),
			'tags'           => $this->tag_model->get_tags($listing_id),
		);
				
		//form rules
		$rules['title']     = "trim|required|min_length[2]|max_length[255]";		
		$rules['phone']     = "trim|required|min_length[12]|max_length[12]|callback_is_valid_phone_number";
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
		
		//the form was not submitted, display default view
		if( ! $this->input->post('edit_listing')) 
		{						
			$data['content'] = $this->load->view('user_profile/edit_free_listing', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return;
		}		
		
		//run validation
		if($this->validation->run() == FALSE)	
		{		
			$data['content'] = $this->load->view('user_profile/edit_free_listing', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return false;
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
		);
		
		$newTags = $this->input->post('tags');
				
		//lets update db
		if(! $this->profile_model->update_free_listing($listing_data,$this->session->userdata('user_id'))) //failed to update db for some reason
		{
			$view_content['content']['message'] = 'Nothing Updated';														
			$data['content'] = $this->load->view('user_profile/edit_free_listing', $view_content, TRUE);						
			$this->profile->_loadDefaultTemplate($data);
			return;
		}
		
		//lets update tags with new ones					
		if( ! $this->lib_tags->update_tags_bulk($newTags, $listing_id))
		{
			$view_content['content']['message'] = 'Nothing Updated';														
			$data['content'] = $this->load->view('user_profile/edit_free_listing', $view_content, TRUE);						
			$this->profile->_loadDefaultTemplate($data);
			return;
		}

		//refresh data for input fields
		$view_content['content']['existing_data']  = $this->profile_model->get_single_listing_details($listing_id, $this->session->userdata('user_id'));
		$view_content['content']['tags']           = $this->tag_model->get_tags($listing_id);		
		
		//set form validation library to update form success so fields re-populate properly
		$this->form_validation->set_form_update_status($success = TRUE);		
		
		//succssfully created listing
		$view_content['content']['message'] = 'Successfully updated listing';														
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
	
		//build variables that should be passed to the listing edit view
		$view_content['content'] = array(
			'error'          => NULL,      //any error messages that should be displayed
			'file_details'   => NULL,      //file details after upload is successful
			'message'        => NULL,      //any general messages to show			
			'existing_data'  => $this->profile_model->get_single_listing_details($listing_id, $this->session->userdata('user_id')),		
			'tags'           => (($this->tag_model->get_tags($listing_id)) ? $this->tag_model->get_tags($listing_id) : array() ),
		);
			
		//form rules
		$rules['title']            = "trim|required|min_length[2]|max_length[255]";		
		$rules['description']      = "trim|max_length[5000]";		
		$rules['phone']            = "trim|min_length[12]|max_length[12]|callback_is_valid_phone_number";
		$rules['email']            = "trim|required|valid_email|max_length[255]";
		$rules['url']              = "trim|max_length[255]";
		$rules['address']          = "trim|min_length[2]|max_length[255]";	
		$rules['zipcode']          = "trim|required|min_length[5]|max_length[5]|numeric|callback_valid_zipcode";
		$rules['tags']             = "trim|required|min_length[2]|max_length[255]|callback_tag_count_premium";
		$rules['payment_interval'] = "trim|required|callback_payment_interval";
		$this->validation->set_rules($rules);
		
		//define the fields we're using for validation purposes
		$fields['ad']               = "Ad";		
		$fields['coupon']           = "Coupon";		
		$fields['title']            = "Title";		
		$fields['description']      = "Description";		
		$fields['phone']            = "Phone";
		$fields['email']            = "Email";
		$fields['url']              = "Url";
		$fields['address']          = "Address";		
		$fields['zipcode']          = "Zipcode";
		$fields['tags']             = "Tags";
		$fields['payment_interval'] = "Payment interval";
		$this->validation->set_fields($fields);
		
		//the form was not submitted, display default view
		if( ! $this->input->post('edit_listing')) 
		{			
			$data['content'] = $this->load->view('user_profile/edit_premium_listing', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return;
		}
		
		//run validation
		if($this->validation->run() == FALSE)	
		{		
			$data['content'] = $this->load->view('user_profile/edit_premium_listing', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return false;
		}											
		
		/////////////////////////////// Ad upload stuff ///////////////////////////////
		$config['upload_path']   = $this->config->item('ulu_upload_path');
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = '1000';
		$config['max_width']     = '720';
		$config['max_height']    = '300';		
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
		else //file not uploaded						
			$uploadedFileName = NULL;	
		/////////////////////////////// Ad upload stuff END///////////////////////////////
		
		/////////////////////////////// Coupon upload stuff ///////////////////////////////
		$config['upload_path']   = $this->config->item('ulu_upload_path');
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = '1000';
		$config['max_width']     = '720';
		$config['max_height']    = '300';		
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
		else //file was not uploaded					
			$uploadedCouponFileName = NULL;
		/////////////////////////////// Ad upload stuff END///////////////////////////////
		
		
		//process form data and insert into db
		$listing_data = array(
		'listing_id'       => $listing_id,
		'user_id'          => $this->session->userdata('user_id'),
		'ad'               => $uploadedFileName,
		'coupon'           => $uploadedCouponFileName,
		'title'            => $this->input->post('title'),
		'description'      => $this->cleanDescription($this->input->post('description')), //run description text through html filter
		'phone'            => $this->input->post('phone'),
		'email'            => $this->input->post('email'),
		'url'              => $this->input->post('url'),
		'address'          => $this->input->post('address'),			
		'zipcode'          => $this->input->post('zipcode'),	
		'payment_interval' => $this->input->post('payment_interval'),			
		);
		
		
		
		$newTags = $this->input->post('tags'); //tags the user has submitted via input field
										
		//lets update db with listing information
		if(! $this->profile_model->update_premium_listing($listing_data)) //failed to update db for some reason
		{
			$view_content['content']['message'] = '<h3>Failed to edit listing</h3>';														
			$data['content'] = $this->load->view('user_profile/edit_premium_listing', $view_content, TRUE);						
			$this->profile->_loadDefaultTemplate($data);
			return;
		}
		
		//lets update tags with new ones					
		if( ! $this->lib_tags->update_tags_bulk($newTags, $listing_id))
		{
			$view_content['content']['message'] = 'Nothing Updated';														
			$data['content'] = $this->load->view('user_profile/edit_free_listing', $view_content, TRUE);						
			$this->profile->_loadDefaultTemplate($data);
			return;
		}
		
		//succssfully created listing
		$view_content['content']['message'] = '<h3>Successfully edited listing</h3>';														
		
		//refresh file data		
		$view_content['content']['existing_data'] = $this->profile_model->get_single_listing_details($listing_id, $this->session->userdata('user_id'));
		$view_content['content']['tags']          = (($this->tag_model->get_tags($listing_id)) ? $this->tag_model->get_tags($listing_id) : array() );
		
		//set form validation library to update form success so fields re-populate properly
		$this->form_validation->set_form_update_status($success = TRUE);
		
		//display view
		$data['content'] = $this->load->view('user_profile/edit_premium_listing', $view_content, TRUE);								
		$this->profile->_loadDefaultTemplate($data);
	}
	
	//HTML FILTERING FOR DESCRIPTION FIELD
	//filters html in description field using htmlpurifier and only allows whitelisted tags
	//http://rdjs.co.uk/web/HTML-Purifier-a-solution-for-allowing-html-in-website-form-input/1
	//http://htmlpurifier.org
	function cleanDescription($dirtyHtml)
	{
	  // load the config and overide defaults as necessary
	  $config = HTMLPurifier_Config::createDefault();
	  $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
	  $config->set('HTML.AllowedElements', 'a,em,blockquote,p,strong,pre,code,img');
	  $config->set('HTML.AllowedAttributes', 'a.href,a.title,img.alt,img.src');
	  $config->set('HTML.TidyLevel', 'light'); 

	  // run the escaped html code through the purifier 
	  $cleanHtml = $this->htmlpurifier->purify($dirtyHtml, $config);
	  return $cleanHtml;
	}

	
	//----------- CALLBACKS (for input fields) ----------------//
	
	//verify the user has updated with a valid payment interval
	function payment_interval($str)
	{		
		if($str !== '1' && $str !== '2' && $str !== '3' && $str !== '4')
		{
			$this->validation->set_message('payment_interval','The %s field must be a valid interval.');		
			return FALSE;
		}
		
		return TRUE;
	}
	
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
}