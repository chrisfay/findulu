<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Review extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->load->library('load_view');			 
		$this->load->library('form_validation');				
		$this->load->library('validation');
		$this->load->model('model_listing');		 
		$this->load->model('tag_model');
		$this->validation->set_error_delimiters('<div class="error">','</div>');		
		 
		 //all default data that should be included when passed to the search results view
		$this->view_content = array(
			'username'           => $this->session->userdata('username'),
			'listing_details'    => NULL,
			'message'            => NULL,
			'error'              => NULL,			
			'search_parm'		 => NULL,
			'listing_type'		 => NULL,
			'tags'				 => NULL,
			'listing_id'   		 => NULL,			
		);
	}
	
	function index()
	{
		 $this->create_review();
		 return;
	}
	
	//no listing was found for the parms (or lack of)
	function _no_listing_found()
	{		
		$data['content'] = $this->load->view('front_end/no_listing_found', $this->view_content, TRUE);
		$this->load_view->_loadDefaultTemplate($data, 'NO_LISTING_FOUND');		
		return;
	}
	
	//load the review form 
	function _show_review_page()
	{		
		$data['content'] = $this->load->view('front_end/create_review', $this->view_content, TRUE);
		$this->load_view->_loadDefaultTemplate($data, 'REVIEW_LISTING');		
		return;		
	}
	
	//review form successfully processed
	function _show_review_success_page()
	{
		$data['content'] = $this->load->view('front_end/review_success', $this->view_content, TRUE);
		$this->load_view->_loadDefaultTemplate($data, 'REVIEW_SUCCESS');		
		return;				
	}
	
	function create_review($listing_id = NULL)
	{
		//only logged in members allowed to leave a review
		if (!$this->tank_auth->is_logged_in(TRUE)) 
		{							
			redirect('/main/login/');
		}
		
		//check if form was submitted, if so lets process it		
		if($this->input->post('create_review')) 
		{		
			$this->_process_review_form();
			return;
		}
				
		//review link sent without an id	
		if(is_null($listing_id))		
		{	
			$this->_no_listing_found();	
			return;
		}
		
		//TODO: sanitize listing_id input received	
		
		//TODO: check if user has already left a review for this listing, if so, inform them somehow		
		
		
		if(! $this->view_content['listing_details'] = $this->model_listing->get_listing_details_for_review($listing_id))
		{
			$this->_no_listing_found();
			return;
		}									
			
		$this->view_content['listing_id'] = $listing_id;
		$this->_show_review_page();	
		return;			
	}
	
	//process the submitted review form
	function _process_review_form()
	{
		//TODO: Logic to process review submission form
		/*
		 //form rules
		$rules['title']     = "trim|required|min_length[2]|max_length[255]";				
		$this->validation->set_rules($rules);
		
		//define the fields we're using for validation purposes
		$fields['title']     = "Title";				
		$this->validation->set_fields($fields);
		
		//run validation
		if($this->validation->run() == FALSE)	
		{		
			$data['content'] = $this->load->view('user_profile/create_free_listing', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return false;
		}*/		
				
		$this->_show_review_success_page();
	}
}

/* End of file create_review.php */
/* Location:  ./application/controllers/create_review.php */