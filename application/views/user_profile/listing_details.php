<?php echo anchor('profile/create_listing/free','Create free listing', array('title' => 'Create new listing')); //create new listing link ?><br />
<?php echo anchor('profile/purchase_listing','Purchase premium listing', array('title' => 'Create new listing')); //create new listing link ?>
<br></br>

<?php
	if(isset($content['listing'])) :	
		
		$data = $content['listing'];
		
		echo'<ul class="listing">'.					
				'<li><span>listing_id:</span> '               .$data->listing_id.'</li>'.
				'<li><span>creation_date:</span>'             .$data->creation_date.' (GMT)</li>' .
				'<li><span>user_id:</span>'                   .$data->user_id.'</li>'.
				'<li><span>title:</span>'                     .$data->title.'</li>'.
				'<li><span>phone:</span>'                     .$data->phone.'</li>'.
				'<li><span>email:</span>'                     .$data->email.'</li>'.
				'<li><span>address:</span>'                   .$data->address.'</li>'.
				'<li><span>city:</span>'                      .$data->city.'</li>'.
				'<li><span>county:</span>'                    .$data->county.'</li>'.
				'<li><span>state_name:</span> '               .$data->state_name.'</li>'.
				'<li><span>state abbrev:</span> '             .$data->state_prefix.'</li>'.
				'<li><span>area_code:</span> '                .$data->area_code.'</li>'.
				'<li><span>zip:</span> '                      .$data->zip.'</li>'.					
				'<li><span>time_zone:</span> '                .$data->time_zone.'</li>'.					
				'<li><span>listing_type_id:</span> '          .(($data->listing_type_id == 1) ? 'Free listing' : 'Premium listing').'</li>'.
				'<li><span>status:</span> '                   .(($data->status == 0) ? 'Not yet approved' : 'Approved/Active').'</li>'.					
				'<li><span>listing_description:</span> '      .$data->listing_description.'</li>'.
				'<li><span>listing_tags:</span> '             .$data->listing_tags.'</li>'.
				'<li><span>listing_payment_interval:</span> ' .$data->listing_payment_interval.'</li>'.
				'<li><span>listing_ad_filename:</span> '      .'<br><img src="'.base_url().'uploads/'.((is_null($data->listing_ad_filename)) ? $this->config->item('ulu_default_listing_ad_image') : $data->listing_ad_filename).'" alt="ad image" /></li>' .
				'<li><span>listing_coupon_filename:</span> '  .'<br><img src="'.base_url().'uploads/'.((is_null($data->listing_coupon_filename)) ? $this->config->item('ulu_default_listing_coupon_image') : $data->listing_coupon_filename).'" alt="coupon image" /></li>' .					
			'</ul>';			
		
	
	else:		
?>
	<h3>Invalid listing id</h3>	

<?php endif;?>