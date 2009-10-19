<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Search extends Controller
{
	private $errors  = array();
	private $results = array();
	private $view_content = array();

	function __construct()
	{
		parent::__construct();
				
		$this->load->library('form_validation');				
		$this->load->library('validation');					
		$this->load->library('load_view');
		$this->load->library('lib_review');		
		$this->load->library('sphinx');
		$this->load->library('lib_zipcode');
		$this->load->model('front_end/model_search');		
		$this->load->model('model_reviews');	
		$this->load->model('model_listing');		
		$this->validation->set_error_delimiters('<div class="error">','</div>');		
		
		//all default data that should be included when passed to the search results view
		$this->view_content = array(
			'username'          			=> $this->session->userdata('username'),
			'search_results'    			=> NULL,
			'search_results_review_data'	=> array(), //multi-d array of review details for each search results
			'message'           			=> NULL,
			'error'            				=> NULL,
			'search_type'       			=> 'listings',	
			'pagination_markup'				=> NULL,		
		);		
	}
	
	//run a search against the database
	//RETURNS: the
	function index()
	{
		//no search was submitted - send them to listings search by default
		$this->listings();
		return;
	}
	
	//search against listings database via sphinx and output results
	//search can be submitted via input fields or via url with parms
	function listings($search_term_parm = NULL, $search_location_parm = NULL)
	{							
		//form rules
		$rules['search_term']         = "trim|required|max_length[255]";		
		$rules['search_location']     = "trim|max_length[255]|callback__is_valid_location";		
		$this->validation->set_rules($rules);
		
		//define the fields we're using for validation purposes
		$fields['search_term']         = "Search term";		
		$fields['search_location']     = "Search location";		
		$this->validation->set_fields($fields);
				
		//search was not submitted, lets show default search page		
		//echo ((! $this->input->post('search_term')) ? 'not_populated' : 'populated');		
		if(! $this->input->post('search_term') && ! $this->input->post('search_location') && is_null($search_term_parm) && is_null($search_location_parm))
		{				
			$this->_no_listing_results(NULL);
			return;	
		}
		
		//check whether the search is from input fields or url
		if($this->input->post('search_term') || $this->input->post('search_location'))
		{			
			$search_term     = $this->sanitize_input($this->input->post('search_term'));
			$search_location = $this->sanitize_input($this->input->post('search_location'));
			$search_from_url = FALSE;						
		}
		else
		{
			$search_term      = $this->sanitize_input($search_term_parm);			
			$search_location = (($search_location_parm == 'empty') ? '' : $this->sanitize_input($search_location_parm));				
			$search_from_url  = TRUE;			
		}				
		
		//set the session userdata			
		$this->session->set_userdata(array(
			'search_term' 	  => $search_term,
			'search_location' => $search_location,
		));		 		
		
		//if search_term is empty we should throw an error message
		if(strlen($search_term) <= 0 || is_null($search_term) || $search_term === 'Search for business or service here...')
		{
			$this->_no_listing_results('Please enter a search term so we can find a listing.');
				return;
		}
		
		//only run validation if search was sent via form
		if(! $search_from_url)
		{
			//run validation		
			if($this->validation->run() == FALSE)	
			{				
				$this->_no_listing_results(NULL);
				return;
			}
		}
				
		if($search_location === 'City, State or Zip' || strlen($search_location) <= 0 || is_null($search_location)) //location wasn't submitted
			$search_location = '';
		if($search_term === 'Search for business or service here...' || strlen($search_term) <= 0) //term wasn't submitted
			$search_term = '';
				
		$this->_display_listing_results($search_term, $search_location); //run the search and display results
	}

	//helper function to load search results view when a search has failed or is empty
	function _no_listing_results($msg)
	{		
		//load default search page - no search was submitted
		$this->view_content['message'] = $msg;
		$data['content'] = $this->load->view('front_end/search_results', $this->view_content, TRUE);
		$this->load_view->_loadDefaultTemplate($data, 'SEARCH_RESULTS');		
		return;
	}
			
	//callback function to verify location search parameter is a valid location
	function _is_valid_location($str)
	{		
		//TODO: Decide/complete the valid location callback for location search input on home page/results page
		$valid_location = TRUE;
		
		if( ! $valid_location)
		{		
			$this->validation->set_message('is_valid_location','%s is not a valid location.');		
		}
		
		return TRUE;
	}
		
	//cleanup the input parms
	function sanitize_input($str)
	{
		//TODO: finnish search input sanitization function		
		//make sure a search term was submitted (if by url, since we don't use validation on urls)
		return trim($str);
	}
	
	/* build_search_location_string
	| takes the search location submitted by user and tries to decide which method to use. After we figure it out,
	| we build out the second half of the query parameter for Sphinx and pass it back to complete the search	
	| Methods/Possibilities:
	| zipcode search
	| city search ONLY (default)	| 
	| city AND state_prefix
	| city AND state_name
	| RETURNS: the formatted second part of the query to append to the search_term part
	| For example, could return '-e @zip 85002' if its determined that we're only searching on zip
	| OR could return '-e @city phoenix @state_prefix' if determined that search is city, state
	*/ 
	function build_search_location_string($str)
	{				
		//empty or default location submitted - search based only on primary field and don't build location string
		if(strlen($str) <= 0)			
			return $str;		
		
		//Zip ONLY
		//check if the location is a zip code ONLY
		if (preg_match('/^[0-9]{5}([- ]?[0-9]{4})?$/', $str)) 
		{	
			//get all zips within a certain mile range and build out the location parm based on that								
			$range = $this->config->item('ulu_zip_range');
			
			if(! $zips_in_range = $this->lib_zipcode->get_zips_in_range($str, $range, _ZIPS_SORT_BY_DISTANCE_ASC, true))
				return '@zip ' . $str;
				
			$local_output = '';				
			
			//build final local output (ie @zip 66205 | @zip 66203)
			foreach($zips_in_range as $zip_in_range => $distance)
			{
				$local_output .= '@zip ' . $zip_in_range . ' | ';	
			}				
							
			return $local_output;		
		}
		
		//State prefix only (ie KS)
		//match examples:
		//ks
		//KS		
		else if(eregi('(^[a-zA-Z]{2}$)',$str))
		{										
			$str = trim($str);
			return '@state_prefix  '. $str;																
		}
				
		//City, State_Prefix
		//match examples:
		//mission, ks
		//mission,ks		
		else if(eregi('(^[a-zA-Z ]+,[a-zA-Z]{2}$)|(^[a-zA-Z ]+, [a-zA-Z]{2}$)',$str))
		{				
			//remove spaces						
			$local_pair = explode(",", $str);
			$local_pair[0] = trim($local_pair[0]);
			$local_pair[1] = trim($local_pair[1]);
						
			if(sizeof($local_pair) == 2)							
			{
				//get a zipcode for this city
				//If we can get a zip for this city, we build out a list of zips in range
				//otherwise we just run the search with the original, city, state_prefix format
				if(! $result = $this->model_zipcode->get_zip_from_city_state_prefix($local_pair[0],$local_pair[1] ))						
					return '@city '. $local_pair[0] . ' @state_prefix  '. $local_pair[1];											
					
				//get all zips within a certain mile range and build out the location parm based on that								
				$range = $this->config->item('ulu_zip_range');
				
				if(! $zips_in_range = $this->lib_zipcode->get_zips_in_range($result->zip_code, $range, _ZIPS_SORT_BY_DISTANCE_ASC, true))
					return '@city '. $local_pair[0] . ' @state_prefix  '. $local_pair[1];											
					
				$local_output = '';				
				
				//build final local output (ie @zip 66205 | @zip 66203)
				foreach($zips_in_range as $zip_in_range => $distance)
				{
					$local_output .= '@zip ' . $zip_in_range . ' | ';	
				}				
								
				return $local_output;				
			}
		}	
		//City, StateName
		//match examples:
		//mission, kansas
		//mission,kansas
		else if(eregi('(^[a-zA-Z ]+,[a-zA-Z]{1,}$)|(^[a-zA-Z ]+, [a-zA-Z]{1,}$)',$str))
		{			
			//remove spaces						
			$local_pair = explode(",", $str);
			$local_pair[0] = trim($local_pair[0]);
			$local_pair[1] = trim($local_pair[1]);
						
			if(sizeof($local_pair) == 2)							
			{
				//get a zipcode for this city
				//If we can get a zip for this city, we build out a list of zips in range
				//otherwise we just run the search with the original, city, state_prefix format
				if(! $result = $this->model_zipcode->get_zip_from_city_state_name($local_pair[0],$local_pair[1] ))						
					return '@city '. $local_pair[0] . ' @state_name  '. $local_pair[1];											
					
				//get all zips within a certain mile range and build out the location parm based on that								
				$range = $this->config->item('ulu_zip_range');
				
				if(! $zips_in_range = $this->lib_zipcode->get_zips_in_range($result->zip_code, $range, _ZIPS_SORT_BY_DISTANCE_ASC, true))
					return '@city '. $local_pair[0] . ' @state_name  '. $local_pair[1];											
					
				$local_output = '';				
				
				//build final local output (ie @zip 66205 | @zip 66203)
				foreach($zips_in_range as $zip_in_range => $distance)
				{
					$local_output .= '@zip ' . $zip_in_range . ' | ';	
				}				
								
				return $local_output;				
			}
		}

		//could be junk data, lets just run a city search as the default
		else 
		{
			return '@city ' . $str;			
		}
	}
	
	//takes a db object of search results and builds out the search_results_review_data for each record
	function _build_search_review_data($search_results_obj)
	{
		foreach($search_results_obj->result() as $row) 
		{				
			//get ratings and compute average			
			$rating_count   = $this->view_content['search_results_review_data'][$row->listing_id]['total_rating_count']  = $this->model_reviews->get_total_ratings_count($row->listing_id);
			$rating_sum     = $this->view_content['search_results_review_data'][$row->listing_id]['total_rating_sum']    = $this->model_reviews->get_total_ratings_sum($row->listing_id);															
			$rating_average = $this->view_content['search_results_review_data'][$row->listing_id]['rating_average']      = $this->lib_review->compute_rating_average($rating_sum,$rating_count);
			$this->view_content['search_results_review_data'][$row->listing_id]['rating_value_global'] = $rating_average;
		}
	}
	
	//display listings
	//pass the data to be displayed on the search results page
	//returns VOID
	function _display_listing_results($search_term = '', $search_location = '')
	{			
		//search was submitted and is sane, lets process it
		$this->sphinx->SetArrayResult(TRUE);
		$this->sphinx->SetMatchMode(SPH_MATCH_EXTENDED); //so we can do field searches
		if(! $result = $this->sphinx->Query($search_term . ' ' .$this->build_search_location_string($search_location)))		
		{				
			$this->_no_listing_results("Search failed for some reason");
			return;			
		}	
				
		//echo print_r($result);
		//make sure we have some results
		//echo sizeof($result['status']);
		if($result['total_found'] == 0)
		{		
			//try one last ditch effort (search by state_name)						
			if(! $result = $this->sphinx->Query($search_term . ' @state_name  '. $search_location))		
			{				
				$this->_no_listing_results("Search failed for some reason");
				return;			
			}
			
			if($result['total_found'] == 0)
			{		
				$this->_no_listing_results("No results found");
				return;
			}
		}		
			
		//pagination stuff	
		$search_location = ((strlen($search_location) <= 0) ? 'empty' : $search_location); //set empty location to a string so url still works			
		$this->load->library('pagination');
		$config['base_url']    = base_url() .'search/listings/'.$search_term.'/'.$search_location.'/';
		$config['total_rows']  = $this->model_search->count_search_results($result['matches']);
		$per_page              = $config['per_page'] = $this->config->item('ulu_max_search_results');	
		$config['uri_segment'] = '5'; 			
		$this->pagination->initialize($config);	
		$this->view_content['pagination_markup'] = $this->pagination->create_links();			
								
		$this->view_content['search_results'] = $this->model_search->get_search_results($result['matches'],$per_page, $this->uri->segment(5)); //get listing_id's from our db				
		$this->_build_search_review_data($this->view_content['search_results']);
				
		$data['content'] = $this->load->view('front_end/search_results', $this->view_content, TRUE);
		$this->load_view->_loadDefaultTemplate($data, 'SEARCH_RESULTS');		
		return;
	}	
}