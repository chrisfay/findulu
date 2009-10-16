<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//view single listing (View)
echo '<h3>Listing Details</h3>';

if(! is_null($listing_details) && $listing_type === 'FREE') : ?>
	
<ul class="listingDetails">	
	<li><span>title:</span> <?php echo $listing_details->title ?></li>
	<li><span>phone:</span> <?php echo $listing_details->phone ?></li>
	<li><span>email:</span> <?php echo $listing_details->email ?></li>
	<li><span>address:</span> <?php echo $listing_details->address ?></li>
	<li><span>city:</span> <?php echo $listing_details->city ?></li>	
	<li><span>state abbrev:</span> <?php echo $listing_details->state_prefix ?></li>	
	<li><span>zip:</span> <?php echo $listing_details->zip ?></li>					
	<li><span>listing_ad_filename:</span><br><img src="<?php echo base_url() . 'uploads/' .$this->config->item('ulu_default_listing_ad_image')?>" alt="ad image" /></li>	
</ul>

<?php elseif(! is_null($listing_details) && $listing_type === 'PREMIUM') : ?>
	
<ul class="listingDetails">	
	<li><span>title:</span> <?php echo $listing_details->title ?></li>
	<li><span>url:</span> <a href="<?php echo $listing_details->listing_url?>"><?php echo $listing_details->listing_url?></a></li>
	<li><span>phone:</span> <?php echo $listing_details->phone ?></li>
	<li><span>email:</span> <?php echo $listing_details->email ?></li>
	<li><span>address:</span> <?php echo $listing_details->address ?></li>
	<li><span>city:</span> <?php echo $listing_details->city ?></li>	
	<li><span>state abbrev:</span> <?php echo $listing_details->state_prefix ?></li>	
	<li><span>zip:</span> <?php echo $listing_details->zip ?></li>			
	<li><span>listing_description:</span> <?php echo $listing_details->listing_description ?></li>	
	<li><span>listing_ad_filename:</span><br><img src="<?php echo base_url() ?>uploads/<?php echo ((is_null($listing_details->listing_ad_filename)) ? $this->config->item('ulu_default_listing_ad_image') : $listing_details->listing_ad_filename)?>" alt="ad image" /></li>
	<li><span>listing_coupon_filename:</span><br><img src="<?php echo base_url()?>uploads/<?php echo ((is_null($listing_details->listing_coupon_filename)) ? $this->config->item('ulu_default_listing_coupon_image') : $listing_details->listing_coupon_filename)?>" alt="coupon image" /></li>
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