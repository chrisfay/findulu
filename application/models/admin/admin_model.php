<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Admin_model extends Model
{
	private  $table_users, $table_users_banned, $table_profile, $table_roles, $table_listings, $table_listing_details, $table_listing_types;

	function __construct()
	{
		parent::__construct();
		
		//set table variables based on config parms
		$this->table_users           = $this->config->item('ulu_users_table');
		$this->table_users_banned    = $this->config->item('ulu_users_banned_table');
		$this->table_profile         = $this->config->item('ulu_profile_table');
		$this->table_roles           = $this->config->item('ulu_roles_table');
		$this->table_listings        = $this->config->item('ulu_listings_table');
		$this->table_listing_details = $this->config->item('ulu_listing_details_table');
		$this->table_listing_types   = $this->config->item('ulu_listing_type_table');
	}
	
	/*
	| Delete contents from table_listings & table_listing_details
	| Parm: numeric listing id to delete
	| Returns TRUE on success or FALSE on failure
	*/
	function delete_listing($listing_id)
	{
		$this->db->where('listing_id', $listing_id);
		$this->db->delete($this->table_listing_details);
		if($this->db->affected_rows() > 0)
		{
			$this->db->where('listing_id', $listing_id);
			$this->db->delete($this->table_listings);
		}
		if($this->db->affected_rows() > 0)
			return TRUE;
		
		return FALSE;
	}

	function get_all_listings()
	{
	
	}
	
	//returns all users and their associated id's
	//return FALSE if query fails
	function get_all_users()
	{
		$this->db->select('id,username');
		$query = $this->db->get($this->table_users);			
		if($query->num_rows() > 0)
			return $query->result();
		else
			return FALSE;
	}
	
	/*
	| Delete user from table_users & table_users_banned & table_profile
	| Parm: numeric user id to delete
	| Returns TRUE on success or FALSE on failure
	*/
	function delete_user($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->delete($this->table_profile);
		if($this->db->affected_rows() > 0)
		{
			//delete users from banned table if they are there
			$this->db->where('id', $user_id);
			$this->db->delete($this->table_users_banned);			
		}		
		
		$this->db->where('id', $user_id);
		$this->db->delete($this->table_users);
		if($this->db->affected_rows() > 0)
			return TRUE;

		return FALSE;		
	}
	
	/*
	| Ban user in table_users_banned
	| Parm: numeric user id to ban
	| Returns TRUE on success or FALSE on failure
	*/
	function ban_user($user_id)
	{
	
	}
}