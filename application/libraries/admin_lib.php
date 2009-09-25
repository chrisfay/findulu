<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_lib
{
	function __construct()
	{
		$this->ci =& get_instance();

		$this->ci->load->library('session');
		$this->ci->load->database();
		$this->ci->load->model('admin/admin_model');
		$this->ci->load->library('profile');
		$this->ci->load->library('validation');
		$this->ci->validation->set_error_delimiters('<div class="error">','</div>');
	}
	
	function _mainNav()
	{		
		$nav = '<li><a href="'.base_url().'">Findulu Home</a></li>'.
			   '<li><a href="'.base_url().'profile/manage">User Profile</a></li>';
		return $nav;
	}	
	
	//loads the data into the main admin template
	function _loadDefaultTemplate($data = NULL)
	{	
		//TODO: Load up all information to populate any forms on page and pass to function below
		$default_data = array(
			'user_info' => $this->ci->admin_model->get_all_users(),
			'listings'  => NULL,
		);
				
					
		//build out nav		
		$data['navList'] = $this->_mainNav();
		
		//get avatar path/filename
		$data['avatarPath'] = $this->ci->profile->get_avatar(3);
		
		//get all users and their id's
		if( ! $data['content']['users'] = $this->ci->admin_model->get_all_users())
			$data['content']['users'] = FALSE;
	
		//load default view of the profile				
		$username = $this->ci->session->userdata('username');
		$data['page_title'] = $username. ' - admin panel';
		$data['username'] = $username;
		$this->ci->load->view('admin/default_layout', array(
			'header'    => $this->ci->load->view('admin/header', $data, true),
			'nav'       => $this->ci->load->view('admin/nav', array(), true),
			'content'   => $this->ci->load->view('admin/content', $default_data, true),
			'footer'    => $this->ci->load->view('admin/footer', array(), true)
		));
	}
}