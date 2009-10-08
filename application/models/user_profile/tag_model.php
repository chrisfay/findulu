<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tag_model extends Model
{
	function __construct()
	{
		parent::__construct();				
	}
	
	//return an object of all tags for that listing id
	//RETURNS array on success or FALSE on failure	
	function get_tags($listing_id)
	{
		$this->db->select('tags.tag_text');
		$this->db->from('tags');
		$this->db->join('tag_mapping', 'tags.tag_id = tag_mapping.tag_id');
		$this->db->where('tag_mapping.listing_id', $listing_id);
		
		$query = $this->db->get();			
		//echo $this->db->last_query();
		if($query->num_rows() > 0)				
			return $query->result();
		else
			return FALSE;				
	}		

	//create a new tag in tags and a mapping entry in tag_mapping
	//if tag already exists, simply add mapping enter to it for the listing_id
	//return TRUE on success or FALSE on failure
	function create_new_tag_and_mapp($tag, $listing_id)
	{	
		$tag_id = NULL;		
		
		if(! $this->tag_exists($tag))		
		{
			if(! $tag_id = $this->create_new_tag($tag))
				return FALSE; //could not create new tag for some reason			
		}
		else
		{
			//get the id of the existing tag
			$tag_id = $this->get_tag_id($tag);
		}					
		
		//add entry in mapping table
		if($this->tag_mapping_exists($tag_id, $listing_id))
		{
			return TRUE;
		}
		else
		{
			return $this->create_new_tag_mapp_entry($tag_id, $listing_id);
		}		
	}
	
	//remove any tags without a corresponding tag_mapping entry
	function cleanup_tags()
	{
		$q =   'DELETE FROM tags WHERE NOT EXISTS (
					SELECT *
					FROM tag_mapping
					WHERE tags.tag_id = tag_mapping.tag_id
				)';
		
		$query = $this->db->query($q);
	}
	
	//add a new tag to tags table
	//return generated tag_id on success, or FALSE on failure
	function create_new_tag($tag)
	{
		$this->db->insert('tags', array('tag_text' => $tag));
		if($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	//ad a new tag mapping entry into tag_mapping table
	function create_new_tag_mapp_entry($tag_id, $listing_id)
	{
		$this->db->insert('tag_mapping', array('tag_id' => $tag_id, 'listing_id' => $listing_id));
		return $this->db->affected_rows() > 0;		
	}
		
	//check if tag text already exists in db
	//returns TRUE if it does or FALSE if it does not
	function tag_exists($tag)
	{
		$this->db->where('tag_text', $tag);
		$query = $this->db->get('tags',1);
	
		if ( $query->num_rows() == 1 )		
			return TRUE;
		else
			return FALSE;
	}
	
	//get the id of the tag from the tag text
	//returns tag_id on success or FALSE on failure
	function get_tag_id($tag)
	{
		$query = $this->db->get_where('tags',array('tag_text' => $tag), 1);
		if( $query->num_rows() == 1 )
		{
			$row = $query->row();
			return $row->tag_id;
		}
		
		return FALSE;
	}
	
	//check if a tag mapping already exists in tag_mapping table
	function tag_mapping_exists($tag_id, $listing_id)
	{
		$this->db->where('tag_id', $tag_id);
		$this->db->where('listing_id', $listing_id);
		$query = $this->db->get('tag_mapping',1);
	
		if ( $query->num_rows() == 1 )		
			return TRUE;
		else
			return FALSE;
	}
	
	//clear out tag mapping entries for listing via listing_id
	function delete_tag_mappings($listing_id)
	{		
		$this->db->where('listing_id', $listing_id);
		$this->db->delete('tag_mapping');
		if($this->db->affected_rows() > 0)
			return TRUE;
			
		return FALSE;
	}	
	
}