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
		if (! isset($_POST[$field]))		
			return form_prep($default);		
		
		if($this->form_update_success) //if the form was successfully updated			
			return form_prep($default);						
		
		return form_prep($_POST[$field]);
	}	
	
	function set_form_update_status($status = FALSE)
	{
		$this->form_update_success = $status;
	}
}