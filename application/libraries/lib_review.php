<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lib_review
{
	function __construct()
	{				
		 $this->ci =& get_instance();
		 //$this->ci->load->library('libraryName');					
		 //$this->ci->load->model('modelName');		
	}	
	
	//compute the average rating for the listing
	//RETURN: the average rating (ie 3.5)
	//formula: ratings_sum / total_ratings_count
	function compute_rating_average($ratings_sum = 0, $total_ratings_count = 0)
	{
		if(sizeof($ratings_sum) <=0)
			return 0;
		
		if($total_ratings_count <= 0)
			return 0;	
				
		return round($ratings_sum / $total_ratings_count);
	}
}		


/* End of file lib_review.php */
/* Location:  ./application/libraries/lib_review.php */