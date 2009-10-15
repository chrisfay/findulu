<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_zipcode extends Model
{
	private $last_error = "";            // last error message set by this class
	
	function __construct()
	{
		parent::__construct();
		
		 //$this->load->library('');					
	}
	
	function index()
	{	
		 //code here...	
	}
	// This function pulls just the lattitude and longitude from the
  	// database for a given zip code.
	function get_zip_point($zip) {	
      
    	$this->db->select('lat,lon,zip_code');
		$this->db->where('zip_code', $zip);
		$result = $this->db->get('zip_code');
		
		if($result->num_rows() > 0) 
		{
			return $result->row();
		}
		
		$this->last_error = mysql_error();
		return FALSE;      		    
   }
   
   //return all zip details for the given zip_code
   function get_zip_details($zip)
   {
	  $this->db->select('lat AS lattitude, lon AS longitude, city, county, state_prefix,state_name, area_code, time_zone');
	  $this->db->where('zip_code', $zip);
	  $query = $this->db->get('zip_code');
	  
	  if($query->nun_rows() > 0)
		  	return $query->row();
	  
	  return FALSE;   	
   }
   
   //return the zipcode associated with a given city & state
   function get_zip_from_city_state_prefix($city, $state_prefix)
   {
		$this->db->select('zip_code');
		$this->db->where('UPPER(city)', strtoupper($city));
		$this->db->where('UPPER(state_prefix)', strtoupper($state_prefix));
		$result = $this->db->get('zip_code');
				
		if($result->num_rows() > 0)
			return $result->row();
		
		return FALSE;
   }
   
   //return the zipcode associated with a given city & state
   function get_zip_from_city_state_name($city, $state_name)
   {
		$this->db->select('zip_code');
		$this->db->where('UPPER(city)', strtoupper($city));
		$this->db->where('UPPER(state_name)', strtoupper($state_name));
		$result = $this->db->get('zip_code');
				
		if($result->num_rows() > 0)
			return $result->row();
		
		return FALSE;
   }
   
   //get a list of all zipcode details within range and return resulting array
   function get_zips_in_range($zip, $min_lat, $max_lat, $min_lon, $max_lon, $include_base) 
   {
   		$sql = "SELECT zip_code, lat, lon FROM zip_code ";
		if (!$include_base) $sql .= "WHERE zip_code <> '$zip' AND ";
		else $sql .= "WHERE "; 
		$sql .= "lat BETWEEN '$min_lat' AND '$max_lat' 
		   AND lon BETWEEN '$min_lon' AND '$max_lon'"; 	
   	
   		$query = $this->db->query($sql);
   		//echo $this->db->last_query();
   		
   		if($query->num_rows() > 0)
	   		return $query->result();
	   		
	   	return FALSE;  	
   }
}

/* End of file model_zipcode.php */
/* Location:  ./application/controllers/model_zipcode.php */