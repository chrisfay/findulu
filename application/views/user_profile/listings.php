<?php
//show listings view - displays any listings for the user they have 
?>

<?php echo anchor('profile/create_listing/free','Create free listing', array('title' => 'Create new listing')); //create new listing link ?><br />
<?php echo anchor('profile/create_listing/premium','Create premium listing', array('title' => 'Create new listing')); //create new listing link ?>
<br></br>

<h2>Below are your current listings</h2>
<?php
	if(isset($content['listings']))
	{
		$count = 1;
		
		foreach($content['listings'] as $data)
		{
			echo'<ul class="listing">'.
					'<li><strong>Listing #'.$count.'</strong></li>'.
					'<li>listing_id: '               .$data->listing_id.'</li>'.
					'<li>user_id: '                  .$data->user_id.'</li>'.
					'<li>title: '                    .$data->title.'</li>'.
					'<li>phone: '                    .$data->phone.'</li>'.
					'<li>email: '                    .$data->email.'</li>'.
					'<li>address: '                  .$data->address.'</li>'.
					'<li>city: '                     .$data->city.'</li>'.
					'<li>county: '                   .$data->county.'</li>'.
					'<li>state_name: '               .$data->state_name.'</li>'.
					'<li>state abbrev: '             .$data->state_prefix.'</li>'.
					'<li>area_code: '                .$data->area_code.'</li>'.
					'<li>zip: '                      .$data->zip.'</li>'.					
					'<li>time_zone: '                .$data->time_zone.'</li>'.					
					'<li>listing_type_id: '          .$data->listing_type_id.'</li>'.
					'<li>status: '                   .(($data->status == 0) ? 'Not yet approved' : 'Approved/Active').'</li>'.					
					'<li>listing_description: '      .$data->listing_description.'</li>'.
					'<li>listing_tags: '             .$data->listing_tags.'</li>'.
					'<li>listing_ad_filename: '      .'<br><img src="'.base_url().'uploads/'.((is_null($data->listing_ad_filename)) ? $this->config->item('ulu_default_listing_ad_image') : $data->listing_ad_filename).'" alt="ad image" /></li>' .
					'<li>listing_coupon_filename: '  .'<br><img src="'.base_url().'uploads/'.((is_null($data->listing_coupon_filename)) ? $this->config->item('ulu_default_listing_coupon_image') : $data->listing_coupon_filename).'" alt="coupon image" /></li>' .
					'<li>creation_date: '            .$data->creation_date.' (GMT)</li>' .
				'</ul>';
			$count++;
		}		
	}
?>



