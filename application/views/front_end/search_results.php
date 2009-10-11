<h3><?php echo $content['message'];?></h3>

<?php 
if(! is_null($content['search_results']))
{
	echo '<h3>Results:</h3>';
	foreach($content['search_results']->result() as $row)
		echo $row->title . "<br />";
}
?>


