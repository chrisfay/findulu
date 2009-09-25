<?php
//show listings view - displays any listings for the user they have 
?>

<?php echo anchor('profile/create_listing/free','Create free listing', array('title' => 'Create new listing')); //create new listing link ?><br />
<?php echo anchor('profile/create_listing/premium','Create premium listing', array('title' => 'Create new listing')); //create new listing link ?>
<br /></br />

<h2>Below are your current listings</h2>
<?php
	if(isset($content['listings']))
	{
		$count = 0;
		
		foreach($content['listings'] as $data)
		{
			echo'<ul class="listing">'.
					'<li><strong>Listing #'.$count.'</strong></li>'.
					'<li>listing_id: '.$data->listing_id.'</li>'.
					'<li>user_id: '.$data->user_id.'</li>'.
					'<li>title: '.$data->title.'</li>'.
					'<li>city: '.$data->city.'</li>'.
					'<li>state: '.$data->state.'</li>'.
					'<li>zip: '.$data->zip.'</li>'.
					'<li>listing_type_id: '.$data->listing_type_id.'</li>'.
					'<li>status: '.$data->status.'</li>'.
					'<li>listing_id: '.$data->listing_id.'</li>'.
					'<li>listing_description: '.$data->listing_description.'</li>'.
					'<li>listing_tags: '.$data->listing_tags.'</li>'.
					'<li>logo_filename: '.$data->logo_filename.'</li>' .
				'</ul>';
		}		
	}
?>


