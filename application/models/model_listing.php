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
	function get_single_listing_details_free($listing_id)
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
	
	//get all listing data for a single listing	
	//returns a result set on success
	//or FALSE on failure
	function get_single_listing_details_premium($listing_id)
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
}

/* End of file model_listing.php */
/* Location:  ./application/models/model_listing.php */