<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profile
{
	function __construct()
	{
		$this->ci =& get_instance();	
		$this->ci->load->library('session');		
		$this->ci->load->database();	
		$this->ci->load->model('user_profile/profile_model');
	}
	
	function _mainNav($user_role = 1)
	{		
		$nav = '<li><a href="'.base_url().'">Findulu Home</a></li>'.
			   '<li><a href="'.base_url().'profile/manage">User Profile</a></li>'.
			   '<li><a href="'.base_url().'profile/manage/update_password">Chage password</a></li>'.
			   '<li><a href="'.base_url().'profile/manage/update_email">Chage email</a></li>'.			   
			   '<li><a href="'.base_url().'profile/manage/unregister">Delete account</a></li>'.			   			   
			   '<li><a href="'.base_url().'profile/manage/manage_avatar">Manage avatar</a></li>'.			   
			   '<li><a href="'.base_url().'profile/manage/view_listings">Listings</a></li>'.			   			   
			   '<li><a href="'.base_url().'profile/manage/manage_bookmarks">Bookmarks</a></li>'.	
			   '<li><a href="'.base_url().'profile/manage/earnings">Earnings</a></li>'.				   
			   
			   (($this->ci->tank_auth->is_admin()) ? '<li><a href="'.base_url().'admin/general">Admin</a></li>' : '');
		
		return $nav;
	}
	
	
	/* return the full path and file name of the avatar to use
	| Option 1 = return fully built image tag (default)
	| Option 2 = return only the image name with extension
	| Option 3 = return full path and image name
	|
	*/
	function get_avatar($option = 1)
	{	
		$upload_path = $this->ci->config->item('ulu_upload_path');
		$default_avatar = $this->ci->config->item('ulu_default_avatar');
		
		if( ! $avatar = $this->ci->profile_model->get_avatar_file_name($this->ci->session->userdata('user_id')))
			$avatar = $default_avatar;				
	
		if($option === 1)					
			return '<img src="' . base_url() . $upload_path . $avatar.'" alt="your avatar" />';					
		else if($option === 2)
			return $avatar;					
		else if($option === 3)
			return base_url() . $upload_path . $avatar;
		else
			return false;
	}
	
	//loads the data into the main user profile template
	function _loadDefaultTemplate($data = NULL)
	{
		if(is_null($data))
			$data['content'] = '';
	
		//build out nav		
		$data['navList'] = $this->_mainNav($this->ci->session->userdata('user_role'));
		
		//get avatar path/filename
		$data['avatarPath'] = $this->get_avatar(3);
	
		//load default view of the profile				
		$username = $this->ci->session->userdata('username');
		$data['page_title'] = $username. ' - user profile';
		$data['username'] = $username;
		$this->ci->load->view('user_profile/default_layout', array(
			'header'    => $this->ci->load->view('user_profile/header', $data, true),
			'nav'       => $this->ci->load->view('user_profile/nav', array(), true),
			'content'   => $this->ci->load->view('user_profile/content', array(), true),
			'footer'    => $this->ci->load->view('user_profile/footer', array(), true)
		));
	}
}