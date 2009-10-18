<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); //TODO: Complete form elements for review form

$audio = array(
	'id'    => 'audio',
	'name'  => 'audio',
	'class' => 'input',
	'value' => '',
);

$video = array(
	'id'    => 'video',
	'name'  => 'video',
	'class' => 'input',
	'value' => '',
);

?>

<div class="review">
	<!--listing details -->
	<ul>
		<li><?php echo $listing_details->title ?></li>
		<li><span>phone:</span> <?php echo $listing_details->phone ?></li>
		<li><span>email:</span> <?php echo $listing_details->email ?></li>
		<li>Total ratings: <?php echo $total_rating_count ?></li>
		<li>Rating average: <?php echo (($rating_average == 0) ? 'Unrated' : $rating_average); ?></li>
	</ul>
	<!-- display any messages -->
	<?php if((sizeof($messages) > 0)) :	?>
		<ul class="messages">
		<?php foreach($messages as $message) : ?>			
			<li><?php echo $message ?></li>			
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	
	<!-- display any errors -->
	<?php if((sizeof($errors) > 0)) :	?>
		<ul class="errors">
		<?php foreach($errors as $error) : ?>			
			<li><?php echo $error ?></li>			
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	
	<?php echo form_open_multipart($this->uri->uri_string(),array('class'=>'uniForm')); ?>
	
		<?php
		echo form_label('Audio clip (15 pts):', $audio['id']);
		echo $this->validation->audio_error;	
		echo form_upload($audio);
		echo "<br />";
			
		echo form_label('Video clip (30 pts):', $video['id']);
		echo $this->validation->video_error;	
		echo form_upload($video);
		?>
	
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
			<?php echo $this->validation->textReview_error;	?>
			<textarea class="reviewText" name="textReview" rows="5" cols="8"></textarea>
	
			<div class="buttonHolder">
				<!--<button type="reset" class="resetButton">Reset</button>-->
				<button type="submit" class="primaryAction">Submit</button>
			</div>
	
		</fieldset>
		<input type="hidden" name="create_review" value="1" />
		
	</form>
</div><!--[END] .review-->