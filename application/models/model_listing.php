<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_listing extends Model
{
	function __construct()
	{
		parent::__construct();
		
		 //$this->load->library('');					
	}
	//get the listing_type_id based on a listing id	
	function get_listing_type_id($listing_id) 
	{
		$this->db->select('listing_type_id');
		$this->db->from('listings');		
		$this->db->where('listing_id', $listing_id);				
		$query = $this->db->get();			
		
		if($query->num_rows() > 0) 
		{
			$row = $query->row();			
			return $row->listing_type_id;
		}				
		
		return FALSE;
	}	
		
	//get all listing data for a single listing	
	//returns a result set on success
	//or FALSE on failure
	//title,phone,email,address,zip,tag
	function get_single_listing_details_free($listing_id)
	{		
		$this->db->select('listings.title,listings.phone,listings.email,listings.address,listings.zip,zip_code.city,zip_code.state_prefix');
		$this->db->from('listings');		
		$this->db->where('listings.listing_id', $listing_id);
		$this->db->join('zip_code','zip_code.zip_code = listings.zip');		
		$query = $this->db->get();			
		
		if($query->num_rows() > 0)				
			return $query->row();
		else
			return FALSE;		
	}
	
	//get all listing data for a single listing	
	//returns a result set on success
	//or FALSE on failure
	function get_single_listing_details_premium($listing_id)
	{		
		$this->db->select('listings.title,listings.phone,listings.email,listings.address,
							listing_details_meta.listing_description,listing_details_meta.listing_ad_filename,
							listing_details_meta.listing_coupon_filename,listing_details_meta.listing_url,
							listings.zip,zip_code.city,zip_code.state_prefix');
		$this->db->from('listings');		
		$this->db->where('listings.listing_id', $listing_id);
		$this->db->join('listing_details_meta','listing_details_meta.listing_id = listings.listing_id');		
		$this->db->join('zip_code','zip_code.zip_code = listings.zip');				
		$query = $this->db->get();			
		
		if($query->num_rows() > 0)				
			return $query->row();
		else
			return FALSE;								
	}
	
	//return a subset of listing info in order to do a review
	function get_listing_details_for_review($listing_id)
	{
		$this->db->select('listings.title,listings.phone,listings.email,listing_details_meta.listing_url');
		$this->db->from('listings');		
		$this->db->where('listings.listing_id', $listing_id);		
		$this->db->join('listing_details_meta','listing_details_meta.listing_id = listings.listing_id');		
		$query = $this->db->get();			
		
		if($query->num_rows() > 0)				
			return $query->row();
		else
			return FALSE;		
	}
	
	//checks whether a certain user is the owner of the listing
	//RETURNS: TRUE if so, FALSE otherwise
	function is_listing_owner($listing_id, $user_id)
	{
		$this->db->where('listing_id', $listing_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('listings',1);
	
		return $query->num_rows() == 1;
	}
}

/* End of file model_listing.php */
/* Location:  ./application/models/model_listing.php */