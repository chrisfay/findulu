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
		$this->table_listing_status  = $this->config->item('ulu_listing_status_type_table');	
		$this->table_location        = $this->config->item('ulu_location_table');		
		
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
		'phone'           => $listing_data['phone'],		
		'email'           => $listing_data['email'],		
		'address'         => $listing_data['address'],				
		'zip'             => $listing_data['zipcode'],
		'listing_type_id' => 1,
		'creation_date'   => $listing_data['creation_date'],		
		);
						
		$this->db->insert($this->table_listings, $this->db->escape($listing_core_data));
		if($this->db->affected_rows() > 0)
		{
			//build out data to go into listing meta table
			$insert_meta_data = array(
			'listing_id'            => $this->db->insert_id(),			
			'listing_tags'   => $listing_data['tags'],
			);
			
			$this->db->insert($this->table_listing_details, $this->db->escape($insert_meta_data));			
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
		'phone'           => $listing_data['phone'],		
		'email'           => $listing_data['email'],				
		'address'         => $listing_data['address'],				
		'zip'             => $listing_data['zipcode'],
		'listing_type_id' => 2,
		'creation_date'   => $listing_data['creation_date'],	
		);
						
		$this->db->insert($this->table_listings, $this->db->escape($listing_core_data));
		if($this->db->affected_rows() > 0)
		{
			//build out data to go into listing meta table
			$insert_meta_data = array(
			'listing_id'               => $this->db->insert_id(),
			'listing_ad_filename'      => $listing_data['ad'],
			'listing_coupon_filename'  => $listing_data['coupon'],
			'listing_description'      => $listing_data['description'],
			'listing_tags'             => $listing_data['tags'],
			'listing_url'              => $listing_data['url'],		
			'listing_payment_interval' => 1,	//need to update this to use the payment interval chosen by customer once built	
			);
			
			$this->db->insert($this->table_listing_details, $this->db->escape($insert_meta_data));			
			return TRUE;
		}
		else
			return FALSE;
	}
	
	function update_free_listing($listing_data)
	{
		$listing_core_data = array(			
		'title'           => $listing_data['title'],		
		'phone'           => $listing_data['phone'],		
		'email'           => $listing_data['email'],		
		'address'         => $listing_data['address'],				
		'zip'             => $listing_data['zipcode'],						
		);
					
		$this->db->where('user_id', $listing_data['user_id']);
		$this->db->where('listing_id', $listing_data['listing_id']);
		$this->db->where('listing_type_id', 1);	//make sure we're updating a free ad
		$this->db->update($this->table_listings, $this->db->escape($listing_core_data));		
		if($this->db->affected_rows() >= 0)
		{
			//build out data to go into listing meta table
			$update_meta_data = array(			
			'listing_tags'   => $listing_data['tags'],
			);
			
			$this->db->where('listing_id', $listing_data['listing_id']);
			$this->db->update($this->table_listing_details, $this->db->escape($update_meta_data));			
			if($this->db->affected_rows() >= 0)
				return TRUE;
		}		
		return FALSE;	
	}
	
	function update_premium_listing($listing_data)
	{
		$listing_core_data = array(				
		'title'           => $listing_data['title'],				
		'phone'           => $listing_data['phone'],		
		'email'           => $listing_data['email'],				
		'address'         => $listing_data['address'],				
		'zip'             => $listing_data['zipcode'],				
		);		
			
		$this->db->where('user_id', $listing_data['user_id']); //make sure we're updating a listing for the logged in user only
		$this->db->where('listing_id', $listing_data['listing_id']);		
		$this->db->where('listing_type_id', 2);	//make sure we're updating a premium ad
		$this->db->update($this->table_listings, $this->db->escape($listing_core_data));
		if($this->db->affected_rows() >= 0)
		{
			//build out data to go into listing meta table
			$insert_meta_data = array(						
			'listing_description'      => $listing_data['description'],
			'listing_tags'             => $listing_data['tags'],
			'listing_url'              => $listing_data['url'],					
			);
			
			//only update file names if a new file has been submitted
			if(! is_null($listing_data['ad']))
				$insert_meta_data['listing_ad_filename'] = $listing_data['ad'];
			if(! is_null($listing_data['coupon']))
				$insert_meta_data['listing_coupon_filename'] = $listing_data['coupon'];
			
			if($this->db->affected_rows() >= 0)
			{
				$this->db->where('listing_id', $listing_data['listing_id']);
				$this->db->update($this->table_listing_details, $this->db->escape($insert_meta_data));			
				return TRUE;
			}
		}

		return FALSE;
	}
	
	//validates if the listing_id is really a valid free listing and is owned by the currently logged in user
	function is_free_listing($listing_id, $user_id)
	{		
		$this->db->select('listing_id');				
		$this->db->where('user_id',$user_id);
		$this->db->where('listing_id',$listing_id);
		$this->db->where('listing_type_id',1);
		$query = $this->db->get($this->table_listings);		
		
		return $query->num_rows() > 0;				
	}
	
	//validates if the listing_id is really a valid premium listing
	function is_premium_listing($listing_id, $user_id)
	{
		$this->db->select('listing_id');				
		$this->db->where('user_id',$user_id);
		$this->db->where('listing_id',$listing_id);
		$this->db->where('listing_type_id',2);
		$query = $this->db->get($this->table_listings);		
		
		return $query->num_rows() > 0;
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
				$this->db->from($this->table_listings);
				$this->db->join($this->table_listing_details, $this->table_listings .'.listing_id = ' . $this->table_listing_details .'.listing_id');
				$this->db->join($this->table_location, $this->table_listings .'.zip = '. $this->table_location .'.zip_code');
				$this->db->where('user_id',$user_id);
				
				$query = $this->db->get();
				if($query->num_rows() > 0)				
					return $query->result();
				else
					return FALSE;				
			break;
			case 2:	
				$this->db->select('*');
				$this->db->from($this->table_listings);
				$this->db->join($this->table_listing_details, $this->table_listings .'.listing_id = ' . $this->table_listing_details .'.listing_id');
				$this->db->join($this->table_location, $this->table_listings .'.zip = '. $this->table_location .'.zip_code');
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
				$this->db->from($this->table_listings);
				$this->db->join($this->table_listing_details, $this->table_listings .'.listing_id = ' . $this->table_listing_details .'.listing_id');
				$this->db->join($this->table_location, $this->table_listings .'.zip = '. $this->table_location .'.zip_code');
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
				$this->db->join($this->table_listing_details, 'listings.listing_id = listing_details_meta.listing_id');
				$this->db->where('user_id',$user_id);
								
				$query = $this->db->get();
				if($query->num_rows() > 0)				
					return $query->result();
				else
					return FALSE;
			break;			
		}
	}	

	//return all listing ids for user listing (either free or premium)
	//All listings = 1
	//Free only = 2
	//Premium only = 3
	function get_all_listing_ids($listing_type = 1, $user_id)
	{
		$this->db->select('listing_id');
		$this->db->from($this->table_listings);		
		$this->db->where('user_id',$user_id);
		$this->db->where('status !=',2); //do not return deleted items
		if($listing_type === 2) //if you want only free listings
			$this->db->where('listing_type_id',1);
		if($listing_type === 3) //if you want only premium listings
			$this->db->where('listing_type_id',2);
		
		$query = $this->db->get();		
		if($query->num_rows() > 0)				
			return $query->result();
		else
			return FALSE;		
	}
	
	//get all listing data for a single listing, and make sure the user_id matches the session_id for security
	//returns a result set on success
	//or FALSE on failure
	function get_single_listing_details($listing_id, $user_id)
	{		
		$this->db->select('*');
		$this->db->from($this->table_listings);		
		$this->db->where('listings.listing_id', $listing_id);
		$this->db->where('user_id', $user_id);
		$this->db->join($this->table_listing_details, $this->table_listings .'.listing_id = ' . $this->table_listing_details .'.listing_id');
		$this->db->join($this->table_location, $this->table_listings .'.zip = '. $this->table_location .'.zip_code');
		
		$query = $this->db->get();			
		if($query->num_rows() > 0)				
			return $query->row();
		else
			return FALSE;		
	}
	
	//query the city records in the db for matches to $q
	//return result set on match, or FALSE otherwise
	//This funciton is used when a user types in the zipcode input field and
	//we autocomplete some results via jquery to make it easier
	function autocomplete_zipcode($q)
	{				
		$sql = "select DISTINCT zip_code from zip_code where zip_code LIKE (\"$q%\") LIMIT 10";		
		$query = $this->db->query($sql);				
		if($query->num_rows() > 0)				
			return $query->result();
		else
			return FALSE;
	}
	
	//validates a zipcode passsed as parameter
	//returns TRUE if valid or FALSE if not
	function valid_zipcode($zipcode)
	{
		$this->db->select('zip_code');
		$this->db->from($this->table_location);
		$this->db->where('zip_code', $zipcode);
		$query = $this->db->get();
		return $query->num_rows() > 0;
	}

	/*
	| create a shell listing upon a successful purchase
	| The user will be able to fill in the details when they choose too
	| until all details have been submitted (and the listing approved) it should
	| be set to unaproved status so the partial listing does not show in search results
	| RETURNS the listing_id of the new shell listing, or FALSE otherwise
	*/
	function create_shell_premium_listing($user_id, $zip_code,$payment_interval)
	{
		$listing_core_data = array(		
		'user_id'         => $user_id,		
		'zip'             => $zip_code,
		'listing_type_id' => 2,
		'creation_date'   => gmdate("Y-m-d H:i:s", time()),	
		);
						
		$this->db->insert($this->table_listings, $this->db->escape($listing_core_data));
		if($this->db->affected_rows() > 0)
		{
			//build out data to go into listing meta table
			$inserted_id = $this->db->insert_id();
			$insert_meta_data = array(
			'listing_id'               => $inserted_id,			
			'listing_payment_interval' => $payment_interval,	//need to update this to use the payment interval chosen by customer once built	
			);
			
			$this->db->insert($this->table_listing_details, $this->db->escape($insert_meta_data));			
			return $inserted_id;
		}
		else
			return FALSE;
	}
	
	/*
	| Set the status type of the listing to 'deleted' (but don't actually delete it from the system)
	| Parm: numeric listing id to delete and the user_id of the requester
	| Returns TRUE on success or FALSE on failure
	*/
	function delete_listing($listing_id, $user_id)
	{	
		$this->db->where('listing_id', $listing_id);
		$this->db->where('user_id', $user_id);		
		$this->db->update($this->table_listings, array('status' => '2'));
		
		return $this->db->affected_rows() > 0;			
	}
}