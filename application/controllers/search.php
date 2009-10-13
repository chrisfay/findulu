<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Search extends Controller
{
	private $errors  = array();
	private $results = array();
	private $view_content = array();

	function __construct()
	{
		parent::__construct();
		
		$this->load->library('lib_main');
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
		//load default search page - no search was submitted
		$this->_no_listing_results('Please enter a search');
		return;
	}
	
	//search against listings database via sphinx and output results
	function listings()
	{	
		//search was not submitted, lets show default search page
		if(! $search_term = $this->input->post('search_term'))
		{
			$this->_no_listing_results(NULL);
			return;	
		}			
				
		//form rules
		$rules['search_term']         = "trim|required|max_length[255]";		
		$rules['search_location']     = "trim|max_length[255]|callback_is_valid_location";		
		$this->validation->set_rules($rules);
		
		//define the fields we're using for validation purposes
		$fields['search_term']         = "Search term";		
		$fields['search_location']     = "Search location";		
		$this->validation->set_fields($fields);
		
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
		$this->load_view->_loadDefaultTemplate($data);		
		return;
	}
	
	//display listings - should only be 
	//pass the data to be displayed on the search results page
	//returns VOID
	function _display_listing_results($search_term = '', $search_location = '')
	{			
		//search was submitted and is sane, lets process it
		$this->sphinx->SetArrayResult(TRUE);
		if(! $result = $this->sphinx->Query($search_term . $this->build_search_location_string($search_location)))
		{						
			$this->_no_listing_results("Search failed for some reason");
			return;			
		}
		
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
		$this->load_view->_loadDefaultTemplate($data);		
		return;
	}
		
	//callback function to verify location search parameter is a valid location
	function is_valid_location($str)
	{
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
	| For example, could return ' -e @zip 85002' if its determined that we're only searching on zip
	| OR could return '-e @city phoenix @state_prefix' if determined that search is city, state
	*/ 
	function build_search_location_string($str)
	{
		return $str;
	}
}