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
		//TODO: create empty premium listing
	}
	
	//process a listing purchase form request
	//returns TRUE if purchase is successfull, or FALSE otherwise
	function _process_order_form()
	{
		//process/validate input fields (ie)
		//$credit_card_num = $this->input->post('credit_card');
		//$credit_card_num = $this->input->post('credit_card');
		return TRUE; //purchase went through
	}
}