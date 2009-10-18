<?php echo $this->validation->error_string; ?>

<?php 
if(! is_null($search_results))
{
	echo '<h3>Results:</h3>';	
	$loop = 0;	
	
	foreach($search_results->result() as $row) 
	{
		//echo print_r($search_results_review_data);?>		
								
		<script type="text/javascript">
			$(function(){
				$("#<?php echo 'v'.$loop.'-starify'?>").children().not(":input").hide();
				
				// Create stars from :radio boxes
				$("#<?php echo 'v'.$loop.'-starify'?>").stars({
					cancelShow: false,
					disabled: true
				});
				
				<?php echo '$("#v'.$loop.'-starify").stars("select", '.$search_results_review_data[$row->listing_id]['rating_average'].');'; ?>
			});
		</script>								
		
		<div class="starify multiField" id="<?php echo 'v'.$loop.'-starify'?>">
			<label for="v<?php echo $loop ?>-vote1" class="blockLabel"><input type="radio" name="vote" id="v<?php echo $loop ?>-vote1" value="1" /> Poor</label>
			<label for="v<?php echo $loop ?>-vote2" class="blockLabel"><input type="radio" name="vote" id="v<?php echo $loop ?>-vote2" value="2" /> Fair</label>
			<label for="v<?php echo $loop ?>-vote3" class="blockLabel"><input type="radio" name="vote" id="v<?php echo $loop ?>-vote3" value="3" checked="checked" /> Average</label>
			<label for="v<?php echo $loop ?>-vote4" class="blockLabel"><input type="radio" name="vote" id="v<?php echo $loop ?>-vote4" value="4" /> Good</label>
			<label for="v<?php echo $loop ?>-vote5" class="blockLabel"><input type="radio" name="vote" id="v<?php echo $loop ?>-vote5" value="5" /> Excellent</label>
		</div>
		<div class="clear"></div>		
		
		<ul class="listing_result">
			<li><a href="<?php echo base_url() . 'view_single_listing/locate/'.$row->listing_id.'/'.str_replace(' ', '-', $row->title)?>"><?php echo $row->title ?></a></li>
			<li>Phone: <?php echo $row->phone ?></li>
			<li>Email: <?php echo $row->email ?></li>
			<li>zip: <?php echo $row->zip ?></li>
			<li>listing_ad_filename: <?php echo $row->listing_ad_filename ?></li>
			<li>listing_url: <?php echo $row->listing_url ?></li>
			<li>city: <?php echo $row->city ?></li>
			<li>state: <?php echo $row->state_name ?></li>
			<li>Total ratings:<?php echo $search_results_review_data[$row->listing_id]['total_rating_count'];?></li>
			<li>Rating average:<?php echo $search_results_review_data[$row->listing_id]['rating_average'];?></li>			
		</ul>
		
<?
	$loop++;
	}
}
?>
		



