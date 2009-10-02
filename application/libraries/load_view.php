<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Load_view
{
	function __construct()
	{
		$this->ci =& get_instance();
		
		$this->ci->load->library('tank_auth');
		$this->ci->lang->load('tank_auth');
		$this->ci->load->library('lib_main');
		$this->ci->load->library('session');
	}
	
	//default template loader - helper library to build template view for main content
	function _loadDefaultTemplate($data = NULL)
	{		
		$data['logged_in'] = FALSE;		
		if ($this->ci->tank_auth->is_logged_in(TRUE)) {							// logged in and activated
			$data['logged_in'] = TRUE;
		}		
	
		//build out nav
		$data['navList'] = $this->ci->lib_main->_mainNav();
	
		//load default view of the profile				
		$username = $this->ci->session->userdata('username');
		$data['page_title'] = 'Findulu - the better business finder';
		$data['username'] = $username;
		$this->ci->load->view('front_end/default_layout', array(
			'header'    => $this->ci->load->view('front_end/header', $data, true),
			'nav'       => $this->ci->load->view('front_end/nav', array(), true),
			'content'   => $this->ci->load->view('front_end/content', array(), true),
			'footer'    => $this->ci->load->view('front_end/footer', array(), true)
		));
	}	
}

/* End of file load_view.php */
/* Location: ./application/libraries/load_view.php */