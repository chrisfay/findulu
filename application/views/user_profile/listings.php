<?php
//show listings view - displays any listings for the user they have 
?>

<?php echo anchor('profile/create_listing/free','Create free listing', array('title' => 'Create new listing')); //create new listing link ?><br />
<?php echo anchor('profile/purchase_listing','Purchase premium listing', array('title' => 'Create new listing')); //create new listing link ?>
<br /><br />

<?php
	//show urls to any listings
	if(isset($content['free_listing_ids']))
	{	
		?>
		<h2>Below are your current FREE listings</h2>				
		<?
		foreach($content['free_listing_ids'] as $data)
		{
			echo anchor('profile/manage/view_single_listing/'.$data->listing_id, 'Listing '.$data->listing_id) . ' ' . anchor('profile/edit_listing/free/'.$data->listing_id, '(Edit)') . "<br />"; "<br />"; 
			
			//echo '<a href="profile/manage/view_single_listing/'.$data.'">Listing'.$data.'</a>';			
		}		
	}
	else
		echo '<h3>No free listings created</h3>';
		
	//show urls to any listings
	if(isset($content['premium_listing_ids']))
	{	
		?>
		<h2>Below are your current PREMIUM listings</h2>				
		<?
		foreach($content['premium_listing_ids'] as $data)
		{
			echo anchor('profile/manage/view_single_listing/'.$data->listing_id, 'Listing '.$data->listing_id) . ' ' . anchor('profile/edit_listing/premium/'.$data->listing_id, '(Edit)') . "<br />"; 
			
			//echo '<a href="profile/manage/view_single_listing/'.$data.'">Listing'.$data.'</a>';			
		}		
	}
	else
		echo '<h3>No premium listings created</h3>';
?>



