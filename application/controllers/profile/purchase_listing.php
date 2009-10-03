<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_listing extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		//only logged in members allowed here
		if (!$this->tank_auth->is_logged_in(TRUE)) {							
			redirect('/main/login/');
		}
		
		//load libraries, helpers, etc...
		$this->load->library('profile');
		$this->load->config('tank_auth', TRUE);		
		$this->load->library('form_validation');		
		$this->load->model('user_profile/profile_model');			
		$this->load->library('validation');
		$this->validation->set_error_delimiters('<div class="error">','</div>');
		
		$zip_code;         //this should be populated in the future by the purchase form
		$payment_interval; //this should be populated in the future by the purchase form
		
	}
	
	function index()
	{
		//purchase form was not submitted, so route to default index view
		if(! $this->input->post('purchase_listing'))
		{
			//load default purchase listing view		
			$data['content'] = $this->load->view('user_profile/purchase_listing_index', array(), TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return;
		}
		
		//purchase failed, lets redirect to the purchase page with an error message
		if(! $this->_process_order_form())
		{			
			$view_content['view_content']['message'] = 'Failed to complete your purchase, please try again'; //error message
			
			$data['content'] = $this->load->view('user_profile/purchase_listing_index', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
			return;
		}
		
		//purchase was successful
		//create empty premium listing
		$listing_id = $this->profile_model->create_shell_premium_listing($this->session->userdata('user_id'), $this->zip_code, $this->payment_interval);

		//load purchase success view
		$view_content['view_content']['listing_id']       = $listing_id;		
		$data['content'] = $this->load->view('user_profile/purchase_success', $view_content, TRUE);
		$this->profile->_loadDefaultTemplate($data);		
	}
	
	//process a listing purchase form request
	//returns TRUE if purchase is successfull, or FALSE otherwise
	function _process_order_form()
	{
		//process/validate input fields (ie)		
		$this->zip_code          = $this->input->post('zipcode');
		$this->payment_interval  = $this->input->post('payment_interval');
		
		//TODO: Add better validation once we know the form process for sure!!				
		if($this->profile_model->valid_zipcode($this->zip_code) && ($this->payment_interval == 1 || $this->payment_interval == 2 || $this->payment_interval == 3 || $this->payment_interval == 4))
			return TRUE; //purchase went through
	}	
}