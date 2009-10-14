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
		//echo print_r($row);?>
		<ul class="listing_result">
			<li><a href="<?php echo base_url() . 'view_single_listing/' .$row->listing_id?>"><?php echo $row->title ?></a></li>
			<li>Phone: <?php echo $row->phone ?></li>
			<li>Email: <?php echo $row->email ?></li>
			<li>zip: <?php echo $row->zip ?></li>
			<li>listing_ad_filename: <?php echo $row->listing_ad_filename ?></li>
			<li>listing_url: <?php echo $row->listing_url ?></li>
			<li>city: <?php echo $row->city ?></li>
			<li>state: <?php echo $row->state_name ?></li>
		</ul>		
<?
	}
}
?>
		



