<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Review extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->load->library('load_view');			 
		$this->load->library('form_validation');				
		$this->load->library('validation');
		$this->load->library('lib_review');
		$this->load->model('model_listing');		 
		$this->load->model('tag_model');
		$this->load->model('model_reviews');
		$this->validation->set_error_delimiters('<div class="error">','</div>');		
		 
		 //all default data that should be included when passed to the search results view
		$this->view_content = array(
			'username'             => $this->session->userdata('username'),
			'listing_details'      => NULL,
			'messages'             => array(),
			'errors'               => array(),			
			'search_parm'		   => NULL,
			'listing_type'		   => NULL,
			'tags'				   => NULL,
			'listing_id'   		   => NULL,			
			'rating_allowed'       => TRUE,
			'rating_value_global'  => 0,
			'rating_value_user'    => 0,
			'total_rating_count'   => NULL,
			'total_rating_sum' 	   => NULL,
			'rating_average' 	   => 0,
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
		$this->load_view->_loadDefaultTemplate($data, 'REVIEW_LISTING');		
		return;
	}
		
	//review form successfully processed
	function _show_review_success_page()
	{
		$data['content'] = $this->load->view('front_end/review_success', $this->view_content, TRUE);
		$this->load_view->_loadDefaultTemplate($data, 'REVIEW_LISTING');		
		return;				
	}
	
	//load the review form 
	function _show_review_form()
	{
		$data['content'] = $this->load->view('front_end/create_review', $this->view_content, TRUE);
		$this->load_view->_loadDefaultTemplate($data, 'REVIEW_LISTING');		
		return;				
	}
			
	//manage a new review request via 'Write review' link
	function create_review($listing_id = NULL)
	{
		//only logged in members allowed to leave a review
		if (!$this->tank_auth->is_logged_in(TRUE)) 
		{							
			redirect('/main/login/');
		}
		
		 //form rules
		$rules['textReview']   = "trim|max_length[5000]";				
		$this->validation->set_rules($rules);
		
		//define the fields we're using for validation purposes		
		$fields['textReview']    = "Text review";				
		$fields['audio']         = "Audio upload field";				
		$fields['video']         = "Video upload field";				
		$this->validation->set_fields($fields);
		
		//review link sent without an id	
		if(is_null($listing_id))		
		{	
			$this->_no_listing_found();	
			return;
		}
		
		//TODO: sanitize listing_id input received								
		//TODO: check if user has already left a review for this listing, if so, inform them somehow and pre-populate whatever review info they've created so far		
		
		
		if(! $this->view_content['listing_details'] = $this->model_listing->get_listing_details_for_review($listing_id))
		{
			$this->_no_listing_found();
			return;
		}
		
		//get ratings and compute average
		$this->view_content['total_rating_count']  = $this->model_reviews->get_total_ratings_count($listing_id);
		$this->view_content['total_rating_sum']    = $this->model_reviews->get_total_ratings_sum($listing_id);															
		$this->view_content['rating_average']      = $this->lib_review->compute_rating_average($this->view_content['total_rating_sum'],$this->view_content['total_rating_count']);
		$this->view_content['rating_value_global'] = $this->view_content['rating_average'];
						
		//check if form was submitted, if so lets process it, otherwise, show form
		if($this->input->post('create_review')) 		
			$this->_process_review_form($listing_id);					
		else
		{	
			//form was not submitted, show default review page
			if($this->model_reviews->rating_already_submitted($this->session->userdata('user_id'), $listing_id))
			{
				$this->view_content['rating_allowed']    = FALSE;				
				$this->view_content['rating_value_user'] = $this->model_reviews->get_rating($this->session->userdata('user_id'), $listing_id); //show the user's rating if he's submitted one, otherwise the global average rating												
			}
			$this->view_content['listing_id'] = $listing_id;
			$this->_show_review_form();	
			return;			
		}
	}
	
	//process the submitted review form
	function _process_review_form($listing_id)
	{		
		//TODO: Logic to process review submission form
		
				
		//run form validation
		if($this->validation->run() == FALSE)
		{	
			$this->_show_review_form();			
			return;
		}
				
		
		//process rating portion
		if($this->model_reviews->rating_already_submitted($this->session->userdata('user_id'), $listing_id))
			$this->view_content['messages']['rating_submitted'] = "Rating already submitted, ignoring";
		else
		{
			//rating is out of range
			if(! in_array($this->input->post('vote'), range(1, 5)))
			{
				$this->view_content['errors']['invalid_rating'] = "Rating must be within 1 - 5";
				$this->_show_review_form();			
				return;
			}
			
			//rating is valid, lets add the entry to the db
			if(! $this->model_reviews->insert_rating($this->session->userdata('user_id'), $listing_id, $this->input->post('vote')))
				$this->view_content['errors']['rating_failed'] = "Rating failed to insert";			
		}
		
			
		
		//TODO: Validate whether user has already left this type of review, if not, process it and show success page
		$this->_show_review_success_page();		
		return;		
		
	}
}

/* End of file create_review.php */
/* Location:  ./application/controllers/create_review.php */