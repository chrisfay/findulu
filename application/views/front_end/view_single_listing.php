<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//view single listing (View)
echo '<h3>Listing Details</h3>';
?>

<div class="listingStats">
	<ul>
		<li>Total ratings: <?php echo $total_rating_count ?></li>
		<li>Rating average: <?php echo (($rating_average == 0) ? 'Unrated' : $rating_average); ?></li>
	</ul>
	<div class="multiField" id="starify">
		<label for="vote1" class="blockLabel"><input type="radio" name="vote" id="vote1" value="1" /> Poor</label>
		<label for="vote2" class="blockLabel"><input type="radio" name="vote" id="vote2" value="2" /> Fair</label>
		<label for="vote3" class="blockLabel"><input type="radio" name="vote" id="vote3" value="3" checked="checked" /> Average</label>
		<label for="vote4" class="blockLabel"><input type="radio" name="vote" id="vote4" value="4" /> Good</label>
		<label for="vote5" class="blockLabel"><input type="radio" name="vote" id="vote5" value="5" /> Excellent</label>
	</div>
	<div class="clear"></div>
</div>

<?php
if(! is_null($listing_details) && $listing_type === 'FREE') : 
	$listing_type_short = 'free';
?>
	
<ul class="listingDetails">	
	<li><span>title:</span> <?php echo $listing_details->title ?></li>
	<li><span>phone:</span> <?php echo $listing_details->phone ?></li>
	<li><span>email:</span> <?php echo $listing_details->email ?></li>
	<li><span>address:</span> <?php echo $listing_details->address ?></li>
	<li><span>city:</span> <?php echo $listing_details->city ?></li>	
	<li><span>state abbrev:</span> <?php echo $listing_details->state_prefix ?></li>	
	<li><span>zip:</span> <?php echo $listing_details->zip ?></li>					
	<li><img src="<?php echo base_url() . 'uploads/' .$this->config->item('ulu_default_listing_ad_image')?>" alt="ad image" /></li>	
	<li><img src="<?php echo base_url() . 'uploads/' .$this->config->item('ulu_default_listing_coupon_image')?>" alt="coupon image" /></li>	
</ul>

<?php elseif(! is_null($listing_details) && $listing_type === 'PREMIUM') : 
	$listing_type_short = 'premium';
?>
	
<ul class="listingDetails">	
	<li><span>title:</span> <?php echo $listing_details->title ?></li>
	<li><span>url:</span> <a href="<?php echo prep_url($listing_details->listing_url)?>"><?php echo $listing_details->listing_url?></a></li>
	<li><span>phone:</span> <?php echo $listing_details->phone ?></li>
	<li><span>email:</span> <?php echo $listing_details->email ?></li>
	<li><span>address:</span> <?php echo $listing_details->address ?></li>
	<li><span>city:</span> <?php echo $listing_details->city ?></li>	
	<li><span>state abbrev:</span> <?php echo $listing_details->state_prefix ?></li>	
	<li><span>zip:</span> <?php echo $listing_details->zip ?></li>			
	<li><span>listing_description:</span> <?php echo $listing_details->listing_description ?></li>	
	<li><span>listing_ad_filename:</span><br /><img src="<?php echo base_url() ?>uploads/<?php echo ((is_null($listing_details->listing_ad_filename)) ? $this->config->item('ulu_default_listing_ad_image') : $listing_details->listing_ad_filename)?>" alt="ad image" /></li>
	<li><span>listing_coupon_filename:</span><br /><img src="<?php echo base_url()?>uploads/<?php echo ((is_null($listing_details->listing_coupon_filename)) ? $this->config->item('ulu_default_listing_coupon_image') : $listing_details->listing_coupon_filename)?>" alt="coupon image" /></li>
</ul>

<?php endif; ?>


Tags:
<ul class="tags">
	<?php foreach($tags as $tag) 
	{	
		echo "<li>$tag->tag_text</li>";	
	}
	?>
</ul>

<?php echo anchor('review/create_review/'.$listing_id.'/'.str_replace(' ', '-', $listing_details->title), 'Write a review!'); ?><br />
<?php echo (($is_owner) ? anchor('profile/edit_listing/'.$listing_type_short.'/'.$listing_id.'/'.str_replace(' ', '-', $listing_details->title), '[Edit listing]') : '');?>

