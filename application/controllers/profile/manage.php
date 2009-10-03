<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends Controller
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
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('tank_auth');		
		$this->load->model('user_profile/profile_model');	
		$this->lang->load('tank_auth');
		$this->load->library('validation');
		$this->validation->set_error_delimiters('<div class="error">','</div>');
		
	}
	
	//default user profile page
	function index($data = NULL)
	{				
		//show listing creation buttons on user profile home page
		$view_content['content']['stuff'] = 'Some content to load in the future for the index page';
		$data['content'] = $this->load->view('user_profile/index', $view_content, TRUE);						
		$this->profile->_loadDefaultTemplate($data);
	}	
	
	/**
	 * Send email message of given type (activate, forgot_password, etc.)
	 *
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	void
	 */
	function _send_email($type, $email, &$data)
	{
		$this->load->library('email');
		$this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->to($email);
		$this->email->subject(sprintf($this->lang->line('auth_subject_'.$type), $this->config->item('website_name', 'tank_auth')));
		$this->email->message($this->load->view('email/'.$type.'-html', $data, TRUE));
		$this->email->set_alt_message($this->load->view('email/'.$type.'-txt', $data, TRUE));
		$this->email->send();
	}
		
	function update_password()
	{	
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('');

		} else {
			$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
			$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->change_password(
						$this->form_validation->set_value('old_password'),
						$this->form_validation->set_value('new_password'))) {	// success					
					$data['content'] = $this->lang->line('auth_message_password_changed');					
					$this->profile->_loadDefaultTemplate($data);
					return;

				} else {														// fail
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			
			$data['content'] = $this->load->view('user_profile/change_password_form', $data, TRUE);						
			$this->profile->_loadDefaultTemplate($data);
		}
	}		
	
	function update_email()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('/main/login/');

		} else {
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->set_new_email(
						$this->form_validation->set_value('email'),
						$this->form_validation->set_value('password')))) {			// success

					$data['site_name'] = $this->config->item('website_name', 'tank_auth');

					// Send email with new email address and its activation link
					$this->_send_email('change_email', $data['new_email'], $data);

					$data['content'] = sprintf($this->lang->line('auth_message_new_email_sent'), $data['new_email']);
					$this->profile->_loadDefaultTemplate($data);
					return;

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$data['content'] = $this->load->view('user_profile/change_email_form', $data, TRUE);
			$this->profile->_loadDefaultTemplate($data);		
		}
	}
	
	/**
	 * Replace user email with a new one.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function reset_email()
	{
		$user_id		= $this->uri->segment(3);
		$new_email_key	= $this->uri->segment(4);

		// Reset email
		if ($this->tank_auth->activate_new_email($user_id, $new_email_key)) {	// success
			$this->tank_auth->logout();
			$data['content'] = $this->lang->line('auth_message_new_email_activated').' '.anchor(site_url('/main/login/'), 'Login');
			$this->profile->_loadDefaultTemplate($data);

		} else {																// fail
			$data['content'] = $this->lang->line('auth_message_new_email_failed');
			$this->profile->_loadDefaultTemplate($data);
		}
	}
	
	/**
	 * Delete user from the site (only when user is logged in)
	 *
	 * @return void
	 */
	function unregister()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('/main/login/');

		} else {
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->delete_user(
						$this->form_validation->set_value('password'))) {		// success
					$data['content'] = $this->lang->line('auth_message_unregistered');
					$this->profile->_loadDefaultTemplate($data);
					return;

				} else {														// fail
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$data['content'] = $this->load->view('auth/unregister_form', $data, TRUE);
			$this->profile->_loadDefaultTemplate($data);
		}
	}					
	
	//upload/update a user's avatar
	function manage_avatar()
	{		
		$config['upload_path']   = $this->config->item('ulu_upload_path');
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = '100';
		$config['max_width']     = '80';
		$config['max_height']    = '80';		
		$field_name = 'avatar';		
		
		$this->load->library('upload', $config);				
		
		//build variables that should be passed to the avatar view
		$view_content['content'] = array(
			'error'        => NULL,      //any error messages that should be displayed
			'file_details' => NULL, //file details after upload is successful
			'message'      => NULL,      //any general messages to show			
		);					
		
		if( ! $this->input->post('avatar_uploaded'))
		{			
			$data['content'] = $this->load->view('user_profile/upload_avatar', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
		}		
		else if( ! $this->upload->do_upload($field_name))
		{				
			$view_content['content']['error'] = array('error' => $this->upload->display_errors());			
			$data['content'] = $this->load->view('user_profile/upload_avatar', $view_content, TRUE);
			$this->profile->_loadDefaultTemplate($data);
		}
		else
		{			
			//build view output
			$view_content['content']['file_details'] = $this->upload->data();			
			$view_content['content']['message'] = '<h3>Success - file uploaded</h3>';						
			
			//file uploaded successfully - lets update db
			$this->profile_model->update_avatar($this->session->userdata('user_id'), $view_content['content']['file_details']['file_name']);									
			
			$data['content'] = $this->load->view('user_profile/upload_avatar', $view_content, TRUE);						
			$this->profile->_loadDefaultTemplate($data);
		}		
	}
	
	function restore_default_avatar()
	{		
		$this->profile_model->update_avatar($this->session->userdata('user_id'), $this->config->item('ulu_default_avatar'));
		redirect('user_profile/manage_avatar');		
	}

	//show any listings the user has
	function view_listings()
	{
		//build variables that should be passed to the default listing details view
		$view_content['content'] = array(						
			'message'      => NULL,      //any general messages to show			
			'listings'     => NULL,      //an array of listing information
		);
		
		$view_content['content']['message'] = 'Load some generic messages in here';
		
		//load free listings for user
		if(! $view_content['content']['free_listing_ids'] = $this->profile_model->get_all_listing_ids(2,$this->session->userdata('user_id')))
			$view_content['content']['free_listing_ids'] = NULL;
			
		//load premium listings for user
		if(! $view_content['content']['premium_listing_ids'] = $this->profile_model->get_all_listing_ids(3,$this->session->userdata('user_id')))
			$view_content['content']['premium_listing_ids'] = NULL;
		
		$data['content'] = $this->load->view('user_profile/listings', $view_content, TRUE);
		$this->profile->_loadDefaultTemplate($data);		
	}

	function view_single_listing($listing_id)
	{
		//build variables that should be passed to the view
		$view_content['content'] = array(						
			'message'      => NULL,      //any general messages to show			
			'listings'     => NULL,      //an array of listing information
		);
		
		$view_content['content']['message'] = 'Load some generic messages in here';
		if(! $view_content['content']['listing'] = $this->profile_model->get_single_listing_details($listing_id,$this->session->userdata('user_id')))
			$view_content['content']['listing'] = NULL;
		
		$data['content'] = $this->load->view('user_profile/listing_details', $view_content, TRUE);
		$this->profile->_loadDefaultTemplate($data);		
	}
	
	function upload_logo()
	{
		$data['content'] = 'test';
		$this->profile->_loadDefaultTemplate($data);
	}
	
	function create_premium_listing()
	{

	}
	
	function update_keywords()
	{
	
	}
	
	function update_listing_text()
	{
	
	}
	
	function update_tags()
	{
	
	}
	
	
}
/* End of file user_profile.php */
/*Location: /application/controllers/user_profile.php*/