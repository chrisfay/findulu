<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
	private $form_update_success = FALSE; //track the state of form update

	function __construct()
	{
		parent::CI_Form_validation();
	}	
	
	/**
	 * Get the value from a form - this method overrides the default Codeigniter set_value function
	 *
	 * Permits you to repopulate a form field with the value it was submitted
	 * with, or, if that value doesn't exist, with the default
	 *
	 * @access	public
	 * @param	string	the field name
	 * @param	string
	 * @return	void
	 */
	function set_value($field = '', $default = '')
	{			
		if (! isset($_POST[$field]))	//form data not submitted	
			return form_prep($default);		
		
		if($this->form_update_success) //if the form was successfully updated we should show default data, not outdated post data
			return form_prep($default);						
		
		return form_prep($_POST[$field]); //errors were found, show the original submitted post data
	}	
	
	//sets the success/failure of the form udpate - used above to re-populate the correct
	//date in the input fields
	function set_form_update_status($status = FALSE)
	{
		$this->form_update_success = $status;
	}
}