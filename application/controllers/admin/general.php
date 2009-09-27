<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class General extends Controller
{
	function __construct()
	{
		parent::__construct();		
				
		//only logged in admins allowed here
		if (!$this->tank_auth->is_admin()) {							
			redirect('');
		}		
		
		$this->load->library('admin_lib');
		$this->load->library('profile');
		$this->load->model('admin/admin_model');
		$this->load->library('validation');
		$this->validation->set_error_delimiters('<div class="error">','</div>');							
		
		$errors   = array();
		$messages = array();
	}
	
	function index()
	{			
		$this->admin_lib->_loadDefaultTemplate();
	}
			
	//delete a listing
	function delete_listing()
	{	
		//form fules
		$rules['delete_listing']   = "required|numeric";
		$this->validation->set_rules($rules);
		
		//run validation
		if($this->validation->run() == FALSE)	
		{		
			$this->admin_lib->_loadDefaultTemplate();
			return false;
		}
			
		$listing_id = $this->input->post('delete_listing');
			
		if($this->admin_model->delete_listing($listing_id))
			$data['messages'] = $this->messages = array('delete_listing' => "Successfully deleted listing $listing_id"); //include function name for output
		else
			$data['errors'] = $this->errors = array('delete_listing' => "Failed to delete listing id $listing_id");//include function name for output
			
		$this->admin_lib->_loadDefaultTemplate($data);		
	}
	
	//delete a findulu user via the admin panel
	function delete_user()
	{	
		//form fules
		$rules['delete_user']   = "required|numeric";
		$this->validation->set_rules($rules);
		
		//run validation
		if($this->validation->run() == FALSE)	
		{		
			$this->admin_lib->_loadDefaultTemplate();
			return false;
		}
			
		$user_id = $this->input->post('delete_user');
				
		if($this->admin_model->delete_user($user_id))
			$data['messages'] = $this->messages = array('delete_user' => "Successfully deleted user with id $user_id");
		else					
			$data['errors'] = $this->errors = array('delete_user' => "Failed to delete user with id $user_id");
		
			
		$this->admin_lib->_loadDefaultTemplate($data);		
	}
	
	function delete_all_listings()
	{
		if($this->admin_model->delete_all_listings())
			$data['messages'] = $this->messages = array('delete_all_listings' => "Successfully deleted all listings");
		else					
			$data['errors'] = $this->errors = array('delete_user' => "Failed to delete all listings for some reason");
		
			
		$this->admin_lib->_loadDefaultTemplate($data);
	}
}