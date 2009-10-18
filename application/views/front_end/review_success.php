<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
echo "<h3>Review successfully processed</h3>";

if(! empty($messages))
{
	foreach($messages as $message) 
	{
		echo '<div class="message">'.$message.'</div>';	
	}	
}
?>