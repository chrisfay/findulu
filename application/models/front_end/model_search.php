<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_search extends Model
{	
	function __construct()
	{
		parent::__construct();
	}
	
	//get all matching listings in the database based on the array of id's
	//return from querying the sphinx index
	//$sphinx_ids_returned should be the $result['matches'] array of id's from sphinx
	//RETURNS: a database object from query or FALSE on failure
	function get_search_results($sphinx_ids_returned = array(), $limit = NULL, $offset = NULL)
	{		
		if(sizeof($sphinx_ids_returned) > 0)
		{				
			//build out query against mysql core db matching id's found
			foreach($sphinx_ids_returned as $row)
			{			
				$this->db->or_where('listings.listing_id', $row['id']);
			}
			
			$this->db->select('listings.listing_id,listings.title,listings.phone,listings.email,listings.zip,listing_details_meta.listing_ad_filename,
								listing_details_meta.listing_url,zip_code.city,zip_code.state_name');
			$this->db->join('listing_details_meta', 'listing_details_meta.listing_id = listings.listing_id');
			$this->db->join('zip_code', 'zip_code.zip_code = listings.zip');
			$this->db->limit($limit, $offset);			
			return $this->db->get('listings'); //return object query results		
		}
		
		return FALSE;
	}
	
	//get the count of how many records thes earch will return from db so we can paginate
	//RETURNS: the number of rows the search would return
	function count_search_results($sphinx_ids_returned)
	{
		if(sizeof($sphinx_ids_returned) > 0)
		{				
			//build out query against mysql core db matching id's found
			foreach($sphinx_ids_returned as $row)
			{			
				$this->db->or_where('listings.listing_id', $row['id']);
			}
			
			$this->db->select('COUNT(listings.listing_id) as search_count',FALSE);
			$this->db->join('listing_details_meta', 'listing_details_meta.listing_id = listings.listing_id');
			$this->db->join('zip_code', 'zip_code.zip_code = listings.zip');
			
			$query = $this->db->get('listings');
			$row = $query->row();							
			return $row->search_count;			
		}
		
		return 0;
	}			
}



