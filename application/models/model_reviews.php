<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_reviews extends Model
{
	function __construct()
	{
		parent::__construct();
		
		 //$this->load->library('');					
	}		
	
	//checks whether a star rating has already been entered by the user for the given listing
	//returns TRUE if one is found, or FALSE if not
	function rating_already_submitted($user_id, $listing_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('listing_id', $listing_id);
		$query = $this->db->get('ratings'); 
		
		return $query->num_rows() > 0;				
	}
	
	//checks whether a review has already been entered by the user for the given listing and review_type
	//returns TRUE if one is found, or FALSE if not
	function review_already_submitted($review_type, $user_id, $listing_id)
	{
		 //$this->db->get_where()
	}
	
	//add a new rating
	function insert_rating($user_id, $listing_id, $rating_value)
	{
		$this->db->insert('ratings', array('user_id' => $user_id, 'listing_id' => $listing_id, 'rating'=> $rating_value, 'created' => gmdate("Y-m-d H:i:s", time())));
		return $this->db->affected_rows() > 0;
	}
	
	//return the star rating value for the give listing based on the user
	function get_rating($user_id, $listing_id)
	{
		$this->db->select('rating');		
		$this->db->where('user_id', $user_id);
		$this->db->where('listing_id', $listing_id);
		$query = $this->db->get('ratings'); 
		
		$row = $query->row();				
		return $row->rating;
	}
	
	//return an query object ($row->rating) of all ratings for a given listings or FALSE if empty
	function get_all_ratings_for_listing($listing_id)
	{
		$this->db->select('rating');		
		$this->db->where('listing_id', $listing_id);
		$query = $this->db->get('ratings'); 
		
		if($query->num_rows() > 0)
			$result = $query->result();	
		else
			return FALSE;					
	}
	
	//return the total number of ratings submitted for a listing
	function get_total_ratings_count($listing_id)
	{		
		$this->db->select('COUNT(ratings.rating) AS total_ratings_count', FALSE); //FALSE turns off the backticks so COUNT will work
		$this->db->from('ratings');
		$this->db->where('listing_id', $listing_id);
		$query = $this->db->get(); 
						
		$row = $query->row();				
		return $row->total_ratings_count;
	}
	
	//return the sum of al ratings submitted for a listing
	function get_total_ratings_sum($listing_id)
	{		
		$this->db->select('SUM(ratings.rating) AS total_ratings_sum', FALSE); //FALSE turns off the backticks so COUNT will work
		$this->db->from('ratings');
		$this->db->where('listing_id', $listing_id);
		$query = $this->db->get(); 
		
		$row = $query->row();				
		return $row->total_ratings_sum;
	}
}

/* End of file model_reviews.php */
/* Location:  ./application/controllers/model_reviews.php */