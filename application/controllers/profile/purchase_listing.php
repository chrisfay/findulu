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

		//TODO: create new controller called profile/edit_listing and pass $listing_id to it so the user can begin populating it		
		echo anchor('profile/manage/view_single_listing/'.$listing_id, 'Populate purchased listing #'. $listing_id);
	}
	
	//process a listing purchase form request
	//returns TRUE if purchase is successfull, or FALSE otherwise
	function _process_order_form()
	{
		//process/validate input fields (ie)
		//$credit_card_num = $this->input->post('credit_card');
		//$credit_card_num = $this->input->post('credit_card');
		
		//set the zipcode to use when creating the shell template
		$this->zip_code          = '85742'; //should use the form data when that has been coded, not this static zipcode
		$this->payment_interval = '1';     //should use the form data when that has been coded, not this static interval
		return TRUE; //purchase went through
	}
	
	
	
}