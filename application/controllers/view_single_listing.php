<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class View_single_listing extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		 //$this->load->library('libraryName');					
		 //$this->load->model('modelName');
		 //$this->load->view('viewName');
	}
	
	function index()
	{
		 $this->locate(); //shouldn't hit the root - redirect to locate function
	}
	
	function locate($id = NULL, $title = NULL)
	{
		if(is_null($id) || is_null($title))
			echo 'Page missing';//TODO: route to 404 page
		
		//TODO: sanitize input received
					
		//TODO: load listing details from db
		
		//TODO: load view_single_listing view with details info				 
			
		echo $id . $title;
	}
}

/* End of file view_single_listing.php */
/* Location:  ./application/controllers/view_single_listing.php */