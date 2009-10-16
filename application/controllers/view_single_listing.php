<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class View_single_listing extends Controller
{
	private $errors  = array();
	private $results = array();
	private $view_content = array();
	
	function __construct()
	{
		parent::__construct();
		
		 $this->load->library('load_view');			 
		 $this->load->model('model_listing');		 
		 $this->load->model('tag_model');
		 
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
		 $this->locate(); //shouldn't hit the root - redirect to locate function
	}
	
	//display single listing - parameters are passed via url
	function locate($listing_id = NULL, $title = NULL)
	{
		if(is_null($listing_id) || is_null($title))			
			$this->_no_listing_found();	
					
		//TODO: sanitize input received
									
		//get listing type (free or premium)
		if(! $listing_type_id = $this->model_listing->get_listing_type_id($listing_id))
		{	
			$this->_no_listing_found();
			return;
		}
		
		$this->view_content['listing_id'] = $listing_id;
		
		//get listing details based on listing type
		switch($listing_type_id)
		{
			case 1: //free
				$this->view_content['listing_type'] = 'FREE';
				$this->view_content['listing_details'] = $this->model_listing->get_single_listing_details_free($listing_id);
				$this->view_content['tags'] = $this->tag_model->get_tags($listing_id);
				break;				
			case 2: //premium
				$this->view_content['listing_type'] = 'PREMIUM';
				$this->view_content['listing_details'] = $this->model_listing->get_single_listing_details_premium($listing_id);
				$this->view_content['tags'] = $this->tag_model->get_tags($listing_id);
				break;
			default: //something else - send to _no_listing_found
				$this->_no_listing_found();
				break;
		}		
		
		//TODO: load view_single_listing view with details info				 
		$this->_listing_found();			
	}
	
	//no listing was found for the parms (or lack of)
	function _no_listing_found()
	{		
		$data['content'] = $this->load->view('front_end/no_listing_found', $this->view_content, TRUE);
		$this->load_view->_loadDefaultTemplate($data, 'NO_LISTING_FOUND');		
		return;
	}
	
	function _listing_found()
	{						
		$data['content'] = $this->load->view('front_end/view_single_listing', $this->view_content, TRUE);
		$this->load_view->_loadDefaultTemplate($data, 'SINGLE_LISTING');		
		return;
	}
	
	function _show_review_page()
	{		
		$data['content'] = $this->load->view('front_end/review_listing', $this->view_content, TRUE);
		$this->load_view->_loadDefaultTemplate($data, 'REVIEW_LISTING');		
		return;		
	}
	
	//review a listing
	function review($listing_id	= NULL)
	{		
		if(is_null($listing_id))		
		{	
			$this->_no_listing_found();	
			return;
		}
		
		//TODO: sanitize listing_id input received	
		
		if(! $this->model_listing->get_listing_details_for_review($listing_id))
		{
			$this->_no_listing_found();
			return;
		}									
			
		$this->view_content['listing_id'] = $listing_id;
		$this->_show_review_page();	
		return;	
	}
}

/* End of file view_single_listing.php */
/* Location:  ./application/controllers/view_single_listing.php */