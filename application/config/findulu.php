<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Findulu website details
|
| These config parms are used specific to the findulu site
| 
| ulu_upload_path = the upload path that all files (ie logos, avatars, etc.. etc..) will go
|
|--------------------------------------------------------------------------
*/

//tables used
$config['ulu_users_table']                   = 'users';
$config['ulu_users_banned_table']            = 'users_banned';
$config['ulu_profile_table']                 = 'user_profiles';
$config['ulu_roles_table']                   = 'user_roles';
$config['ulu_listings_table']                = 'listings';                    //core listing info (geo info and whatnot)
$config['ulu_listing_details_table']         = 'listing_details_meta';        //stores additional listing info like description,tags,logo, etc..
$config['ulu_listing_type_table']            = 'listing_type';                //stores the types of listings that can be created
$config['ulu_location_table']                  = 'zip_code';                    //the table with all locale data (zipcode, city,state, etc...)

//general
$config['ulu_upload_path']                   = 'uploads/';
$config['ulu_default_avatar']                = 'defaultAvatar.png';
$config['ulu_default_listing_ad_image']      = 'defaultListingAd.png';
$config['ulu_default_listing_coupon_image']  = 'defaultListingAd.png';
$config['ulu_free_site_enabled']             = TRUE;                          //whether the site is in free mode or pay mode (FALSE)
$config['ulu_max_tags']                      = 10;                    //maximum number of tags allowed for premuim uploads

/* End of file findulu.php */
/* Location: ./application/config/findulu.php */
