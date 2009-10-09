<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| Lib_tags - Library
| Library that manages tag processing
|
*/

class Lib_tags
{
	private $errors;
	
	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->model('user_profile/tag_model');
		$this->errors = array(); //any errors durring processing we should add them here		
	}
	
	//check if tag already exists (tag text)
	function tag_exists($tag)
	{
	
	}
	
	//checks if a given tag mapping exists (can only have one identical tag_id -> listing_id)
	function tag_mapping_exists($tag_id, $listing_id)
	{
	
	}
	
	//tags comma sep list of tags and process each one, passing them to create_new_tag_and_mapp
	//used when updating a listing
	//RETURNS TRUE on success FALSE on failure
	function update_tags_bulk($tags = NULL, $listing_id = NULL)
	{	
		if(is_null($tags) || is_null($listing_id))
			return FALSE;
		
		$tags = str_replace(', ', ',', $tags);
		$tags = str_replace('_', '-', $tags);
		$tags = str_replace(' ', '-', $tags);
		$tags = explode(',', $tags);
		
		//if tags array has an element we need to first clear out any mapping entries
		if(sizeof($tags) > 0)
			$this->ci->tag_model->delete_tag_mappings($listing_id);
		
		foreach ($tags as $tag) 
		{				
			$tag = trim($tag);
			
			if (!empty($tag))
			{
				$this->create_new_tag_and_mapp($tag, $listing_id);		
			}						
		}
		
		//remove any tags that are missing mapping entries in tag_mapping
		$this->ci->tag_model->cleanup_tags();
		
		return TRUE;		
	}
	
	//both create a new tag and add the listing to tag_id mapping
	function create_new_tag_and_mapp($tag, $listing_id)
	{		
		if (!empty($tag) && !empty($listing_id))		
		{			
			return $this->ci->tag_model->create_new_tag_and_mapp($tag, $listing_id);
		}
		
		return FALSE;
	}
	
	//create new tag
	//RETURN the tag_id of the new tag
	function create_new_tag($tag)
	{
	
	}
		
	//map tag to listing	
	function add_tag_mapping($tag_id, $listing_id)
	{
	
	}
	
	//remove a tag mapping to a listing
	function delete_tag_mapping($tag_id, $listing_id)
	{
	
	}
	
	//delete a tag with matching text '$tag'
	//should only delete if there are no mappings for this tag (constraint should block as well)
	function delete_tag($tag_text)
	{
	
	}
	
	//delete a tag with matching tag_id
	//should only delete if there are no mappings for this tag (constraint should block as well)
	function delete_tag_by_id($tag_id)
	{
	
	}
		
	//get all tags for a specific listing_id
	function get_tags($listing_id)
	{
	
	}
	
	function edit_tag($tag_id, $new_tag_text)
	{
	
	}
	
	function get_tag_id($tag_text)
	{
	
	}
	
	
	
	
	
	
}