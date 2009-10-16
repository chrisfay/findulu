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
	function _main_nav($user_level = 1, $definePageName = '')
	{			
		$nav =  '<li class="home'.(($definePageName === 'HOME') ? ' active': '').'"><a href="'.base_url().'"><span>Home</span></a></li>' .
				'<li class="about'.(($definePageName === 'ABOUT') ? ' active': '').'"><a href="#"><span>About</span></a></li>' .
				'<li class="support'.(($definePageName === 'SUPPORT') ? ' active': '').'"><a href="#"><span>Suppport</span></a></li>' .
				'<li class="contact'.(($definePageName === 'CONTACT') ? ' active': '').'"><a href="#"><span>Contact Us</span></a></li>';
		
		return $nav;
	}
	
	//default template loader - helper library to build template view for main content
	//$data is the data to pass to the view
	//$define is the page name to define in the header - used to style links and pages properly
	function _loadDefaultTemplate($data = NULL, $definePageName = 'HOME')
	{			
		$data['logged_in']    		 = $this->ci->tank_auth->is_logged_in(TRUE);	
		$data['is_home']      		 = $definePageName === 'HOME';
		$data['results_page'] 		 = $definePageName === 'SEARCH_RESULTS'; 
		$data['page_defined'] 		 = $definePageName;
		$data['page_title'] 		 = 'Findulu - the better business finder';		
		$data['navList'] 			 = $this->_main_nav('', $definePageName);
		$data['define']  			 = $definePageName;			
		$data['username'] 			 = $this->ci->session->userdata('username');
		$data['single_listing_page'] = $definePageName === 'SINGLE_LISTING';
				 	
		$this->ci->load->view('front_end/default_layout', array(
			'header'    => $this->ci->load->view('front_end/header', $data, true),			
			'content'   => $this->ci->load->view('front_end/content', array(), true),
			'footer'    => $this->ci->load->view('front_end/footer', array(), true)
		));
	}	
}

/* End of file load_view.php */
/* Location: ./application/libraries/load_view.php */