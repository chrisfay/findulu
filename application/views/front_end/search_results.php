<?php if(! is_null($content['message'])) : ?>
	<h3><?php echo $content['message'];?></h3>
<? endif; ?>

<?php echo $this->validation->error_string; ?>

<?php 
if(! is_null($content['search_results']))
{
	echo '<h3>Results:</h3>';
	foreach($content['search_results']->result() as $row) 
	{
		echo '<strong>Title</strong>: '. $row->title . "<br />";
		echo print_r($row) . "<br /><br />";		
	}
}
		



