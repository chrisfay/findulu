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
									
		//TODO: load listing details from db
		if(! $listing_type_id = $this->model_listing->get_listing_type_id($listing_id))
		{	
			$this->_no_listing_found();
			return;
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
}

/* End of file view_single_listing.php */
/* Location:  ./application/controllers/view_single_listing.php */