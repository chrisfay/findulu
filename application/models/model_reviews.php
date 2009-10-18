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
}

/* End of file model_reviews.php */
/* Location:  ./application/controllers/model_reviews.php */