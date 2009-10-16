<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); //TODO: Complete form elements for review form?>

<div class="review">
	<!--listing details -->
	<ul>
		<li><?php echo $listing_details->title ?></li>
		<li><span>phone:</span> <?php echo $listing_details->phone ?></li>
		<li><span>email:</span> <?php echo $listing_details->email ?></li>
	</ul>

	<form class="uniForm" action="<?php echo $this->uri->uri_string() ?>" method="post">
	
		<fieldset class="inlineLabels">								
			<div class="clear"></div>
			<div class="ctrlHolder">		
				<div class="multiField" id="starify">
					<label for="vote1" class="blockLabel"><input type="radio" name="vote" id="vote1" value="1" /> Poor</label>
					<label for="vote2" class="blockLabel"><input type="radio" name="vote" id="vote2" value="2" /> Fair</label>
					<label for="vote3" class="blockLabel"><input type="radio" name="vote" id="vote3" value="3" checked="checked" /> Average</label>
					<label for="vote4" class="blockLabel"><input type="radio" name="vote" id="vote4" value="4" /> Good</label>
					<label for="vote5" class="blockLabel"><input type="radio" name="vote" id="vote5" value="5" /> Excellent</label>
				</div>
				<div class="clear"></div>
			</div>
			
			<label>Leave a review</label>
			<textarea class="reviewText" rows="5" cols="8"></textarea>
	
			<div class="buttonHolder">
				<button type="reset" class="resetButton">Reset</button>
				<button type="submit" class="primaryAction">Submit</button>
			</div>
	
		</fieldset>
		<input type="hidden" name="create_review" value="1" />
		
	</form>
</div><!--[END] .review-->