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
		$this->load->library('sphinx');
		$this->validation->set_error_delimiters('<div class="error">','</div>');
		
		//all default data that should be included when passed to the search results view
		$this->view_content['content'] = array(
			'username'          => $this->session->userdata('username'),
			'search_results'    => NULL,
			'message'           => '',
			'error'            =>  '',
			'search_type'       => 'listings',
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
	function listings()
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
		if(! $search_term = $this->input->post('search_term'))
		{
			$this->_no_listing_results(NULL);
			return;	
		}
		
		//run validation		
		if($this->validation->run() == FALSE)	
		{				
			$this->_no_listing_results("Form input errors");
			return;
		}
		
		//one last sanatization of any field input data
		$search_term     = $this->sanitize_input($search_term);
		$search_location = $this->sanitize_input($this->input->post('search_location'));
		
		if($search_location === 'City, State or Zip') //location wasn't submitted
			$search_location = '';
				
		$this->_display_listing_results($search_term, $search_location); //run the search and display results
	}

	//helper function to load search results view when a search has failed or is empty
	function _no_listing_results($msg)
	{		
		//load default search page - no search was submitted
		$this->view_content['content']['message'] = $msg;
		$data['content'] = $this->load->view('front_end/search_results', $this->view_content, TRUE);
		$this->load_view->_loadDefaultTemplate($data, 'SEARCH_RESULTS');		
		return;
	}
	
	//display listings - should only be 
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

		//echo $search_term . ' ' .$this->build_search_location_string($search_location);		
		
		//echo print_r($result);
		//make sure we have some results
		//echo sizeof($result['status']);
		if($result['total_found'] == 0)
		{
			$this->_no_listing_results("No results found");
			return;
		}		
		
		//build out where clause with all matching id's found
		foreach($result['matches'] as $row)
		{			
			$this->db->or_where('listing_id', $row['id']);
		}
		
		$this->view_content['content']['search_results'] = $this->db->get('listings'); //get listing_id's from our db		
		
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
		return $str;
	}
	
	/* build_search_location_string
	| takes the search location submitted by user and tries to decide which method to use. After we figure it out,
	| we build out the second half of the query parameter for Sphinx and pass it back to complete the search	
	| Methods/Possibilities:
	| zipcode search
	| city search ONLY
	| state search only
	| city AND state only	
	| RETURNS: the formatted second part of the query to append to the search_term part
	| For example, could return '-e @zip 85002' if its determined that we're only searching on zip
	| OR could return '-e @city phoenix @state_prefix' if determined that search is city, state
	*/ 
	function build_search_location_string($str)
	{
		//TODO: complete the build_search_location_string function for deciding the location search method used
		
		//check if the location is a zip code ONLY
		if (preg_match('/^[0-9]{5}([- ]?[0-9]{4})?$/', $str)) 
		{
			return '@zip ' . $str;
		}		
	}
}