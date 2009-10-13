<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Load_view
{
	function __construct()
	{
		$this->ci =& get_instance();
		
		$this->ci->load->library('tank_auth');
		$this->ci->lang->load('tank_auth');
		//$this->ci->load->library('lib_main'); //not being used right now
		$this->ci->load->library('session');
	}
	
	//return the default nav links	
	function _main_nav($user_level = 1, $activeClass = '')
	{			
		$nav =  '<li class="home'.(($activeClass === 'home') ? ' active': '').'"><a href="'.base_url().'" class="active"><span>Home</span></a></li>' .
				'<li class="about"><a href="#"><span>About</span></a></li>' .
				'<li class="support"><a href="#"><span>Suppport</span></a></li>' .
				'<li class="contact"><a href="#"><span>Contact Us</span></a></li>';
		
		return $nav;
	}
	
	//default template loader - helper library to build template view for main content
	//$data is the data to pass to the view
	//$definePageName is the name to define in the header - used to include different css styles for diff pages
	function _loadDefaultTemplate($data = NULL, $define = 'HOME', $activeClass = '')
	{			
		$data['logged_in']    = $this->ci->tank_auth->is_logged_in(TRUE);	
		$data['is_home']      = $define === 'HOME';
		$data['results_page'] = $define === 'SEARCH_RESULTS'; 
		$data['page_defined'] = $define;
		
	
		//build out nav - pass the nav class to activate as well!
		$data['navList'] = $this->_main_nav('', $activeClass);
		$data['define']  = $define;
	
		//load default view of the profile				
		$username = $this->ci->session->userdata('username');
		$data['page_title'] = 'Findulu - the better business finder';
		$data['username'] = $username;		
		$this->ci->load->view('front_end/default_layout', array(
			'header'    => $this->ci->load->view('front_end/header', $data, true),			
			'content'   => $this->ci->load->view('front_end/content', array(), true),
			'footer'    => $this->ci->load->view('front_end/footer', array(), true)
		));
	}
}

/* End of file load_view.php */
/* Location: ./application/libraries/load_view.php */