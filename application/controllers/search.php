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
		$this->load->library('load_view');
		$this->load->library('sphinx');
		
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
		$this->view_content['content']['message'] = 'Please enter a search';					
		$data['content'] = $this->load->view('front_end/search_results', $this->view_content, TRUE);
		$this->load_view->_loadDefaultTemplate($data);
	}
	
	//search against listings database via sphinx and output results
	function listings()
	{			
		if(! $search_term = $this->input->post('search_term'))
		{
			//load default search page - no search was submitted
			$this->_no_listing_results("Please enter a search");
			return;						
		}
		
		//search was submitted, lets process it
		$this->sphinx->SetArrayResult(TRUE);
		if(! $result = $this->sphinx->Query($search_term))
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
		
		$this->view_content['content']['search_results'] = $this->db->get('listings');
		$data['content'] = $this->load->view('front_end/search_results', $this->view_content, TRUE);
		$this->load_view->_loadDefaultTemplate($data);		
		return FALSE;		
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
}