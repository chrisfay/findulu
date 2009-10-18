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
		$this->load->library('lib_review');	
		$this->load->model('model_listing');		 
		$this->load->model('tag_model');
		$this->load->model('model_reviews');
		 
		 //all default data that should be included when passed to the search results view
		$this->view_content = array(
			'username'             => $this->session->userdata('username'),
			'listing_details'      => NULL,
			'message'              => NULL,
			'error'                => NULL,			
			'search_parm'		   => NULL,
			'listing_type'		   => NULL,
			'tags'				   => NULL,
			'listing_id'   		   => NULL,			
			'rating_value_global'  => 0,			
			'total_rating_count'   => NULL,
			'total_rating_sum' 	   => NULL,
			'rating_average' 	   => 0,
			'is_owner'			   => FALSE,
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
				return;
				break;
		}		
		
		//TODO: load view_single_listing view with details info		
		//get ratings and compute average
		$this->view_content['total_rating_count']  = $this->model_reviews->get_total_ratings_count($listing_id);
		$this->view_content['total_rating_sum']    = $this->model_reviews->get_total_ratings_sum($listing_id);															
		$this->view_content['rating_average']      = $this->lib_review->compute_rating_average($this->view_content['total_rating_sum'],$this->view_content['total_rating_count']);
		$this->view_content['rating_value_global'] = $this->view_content['rating_average'];		 
		$this->view_content['is_owner']			   = $this->model_listing->is_listing_owner($listing_id, $this->session->userdata('user_id'));
		$this->_listing_found();			
	}
	
	//no listing was found for the parms (or lack of)
	function _no_listing_found()
	{		
		$data['content'] = $this->load->view('front_end/no_listing_found', $this->view_content, TRUE);
		$this->load_view->_loadDefaultTemplate($data, 'SINGLE_LISTING');		
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