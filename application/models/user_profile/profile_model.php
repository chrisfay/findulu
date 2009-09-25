<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profile_model extends Model
{	
	private $profile_table; //the user profile table
	
	function __construct()
	{
		parent::__construct();					
	
		//set table variables based on config parms
		$this->table_users           = $this->config->item('ulu_users_table');
		$this->table_users_banned    = $this->config->item('ulu_users_banned_table');
		$this->profile_table         = $this->config->item('ulu_profile_table');
		$this->table_roles           = $this->config->item('ulu_roles_table');
		$this->table_listings        = $this->config->item('ulu_listings_table');
		$this->table_listing_details = $this->config->item('ulu_listing_details_table');
		$this->table_listing_types   = $this->config->item('ulu_listing_type_table');		
	}
	
	//update currently logged in users's avatar
	function update_avatar($user_id, $new_file_name)
	{				
		$this->db->set('avatar', $new_file_name);
		$this->db->where('user_id', $user_id);
		$this->db->update($this->profile_table);
		return $this->db->affected_rows() > 0;
		
	}
	
	//get the currently logged in user's avatar from the user profile
	function get_avatar_file_name($user_id)
	{
		$this->db->select('avatar');		
		$this->db->where('user_id',$user_id);
		$query = $this->db->get($this->profile_table);
		if($query->num_rows() == 1)
		{			
			$row = $query->row();
			return $row->avatar;
		}
		else
			return FALSE;		
	}
	
	//inserts a new free listing (must insert into main listing table as well as listing descripton table if needed)
	//parms: expects an associative array of fields/values
	//returns TRUE on success or FALSE on failure
	function create_free_listing($listing_data)
	{		
		$listing_core_data = array(		
		'user_id'         => $listing_data['user_id'],
		'title'           => $listing_data['title'],		
		'city'            => $listing_data['city'],
		'state'           => $listing_data['state'],
		'zip'             => $listing_data['zipcode'],
		'listing_type_id' => 1,
		);
						
		$this->db->insert($this->table_listings, $listing_core_data);
		if($this->db->affected_rows() > 0)
		{
			//build out data to go into listing meta table
			$insert_meta_data = array(
			'listing_id'            => $this->db->insert_id(),
			'logo_filename'         => $listing_data['logo'],
			'listing_description'   => $listing_data['description'],
			);
			
			$this->db->insert($this->table_listing_details, $insert_meta_data);			
			return TRUE;
		}
		else
			return FALSE;
	}
	
	function create_premium_listing($listing_data)
	{
		$listing_core_data = array(		
		'user_id'         => $listing_data['user_id'],
		'title'           => $listing_data['title'],		
		'city'            => $listing_data['city'],
		'state'           => $listing_data['state'],
		'zip'             => $listing_data['zipcode'],
		'listing_type_id' => 2, //2 = premium listing
		);
						
		$this->db->insert($this->table_listings, $listing_core_data);
		if($this->db->affected_rows() > 0)
		{
			//build out data to go into listing meta table
			$insert_meta_data = array(
			'listing_id'            => $this->db->insert_id(),
			'logo_filename'         => $listing_data['logo'],
			'listing_description'   => $listing_data['description'],
			);
			
			$this->db->insert($this->table_listing_details, $insert_meta_data);			
			return TRUE;
		}
		else
			return FALSE;
	}
	
	/*
	| get all listing information from main and meta listing tables
	| Return :array of all data on success or FALSE on failure
	| Parm: listing status integer
	| Status types: 
	| 1 = all status types
	| 2 = active
	| 3 = inactive
	*/
	function get_listings_by_userid($listing_status = 1,$user_id)
	{					
		switch($listing_status)
		{			
			case 1:
				$this->db->select('*');
				$this->db->from('listings');
				$this->db->join('listing_details_meta', 'listings.listing_id = listing_details_meta.listing_id');
				$this->db->where('user_id',$user_id);
				
				$query = $this->db->get();
				if($query->num_rows() > 0)				
					return $query->result();
				else
					return FALSE;				
			break;
			case 2:	
				$this->db->select('*');
				$this->db->from('listings');
				$this->db->join('listing_details_meta', 'listings.listing_id = listing_details_meta.listing_id');
				$this->db->where('user_id',$user_id);
				$this->db->where('status',1);
				
				$query = $this->db->get();
				if($query->num_rows() > 0)				
					return $query->result();
				else
					return FALSE;
			break;
			case 3:
				$this->db->select('*');
				$this->db->from('listings');
				$this->db->join('listing_details_meta', 'listings.listing_id = listing_details_meta.listing_id');
				$this->db->where('user_id',$user_id);
				$this->db->where('status',0);
				
				$query = $this->db->get();
				if($query->num_rows() > 0)				
					return $query->result();
				else
					return FALSE;
			default:
				$this->db->select('*');
				$this->db->from('listings');
				$this->db->join('listing_details_meta', 'listings.listing_id = listing_details_meta.listing_id');
				$this->db->where('user_id',$user_id);
								
				$query = $this->db->get();
				if($query->num_rows() > 0)				
					return $query->result();
				else
					return FALSE;
			break;			
		}
	}
}