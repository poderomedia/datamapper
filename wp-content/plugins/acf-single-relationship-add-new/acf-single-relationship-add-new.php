<?php
/*
Plugin Name: ACF Single Relationship Add New
Plugin URI: http://hosting.fancyfiber.com/acf-single-relationship-add-new/
Description: With Advanced Custom Fields Installed, relationship fields with a single post type attached will get an "Add New" button. Which will open in a colorbox to add a new item, with out leaving the current item.
Version: 1.2
Author: Fancy Fiber Designs
Author URI: http://hosting.fancyfiber.com/design/
License: GPLv2
*/

function asran_admin_scripts($hook) {
   $asran_custom_admin_url = array( 'admin_url' => admin_url() );

   switch($hook) {
      case 'post.php':
         if(!isset($_GET['asran-colorbox'])) {
            wp_enqueue_script('colorbox', plugins_url('/js/jquery.colorbox-min.js', __FILE__), array('jquery'));
            wp_enqueue_script('asran-post', plugins_url('/js/asran-post.js', __FILE__), array('jquery', 'colorbox'));
            wp_enqueue_style('colorbox-css', plugins_url('/css/colorbox.css', __FILE__));
            wp_localize_script( 'asran-post', 'asran_custom', $asran_custom_admin_url );
         } else {
            wp_enqueue_style('asran-admin-css', plugins_url('/css/asran-admin.css', __FILE__));
         }
         break;
      case 'post-new.php':   
         if(isset($_GET['asran-colorbox'])) {
            wp_enqueue_style('asran-admin-css', plugins_url('/css/asran-admin.css', __FILE__));
         } else {
            wp_enqueue_script('colorbox', plugins_url('/js/jquery.colorbox-min.js', __FILE__), array('jquery'));
            wp_enqueue_script('asran-post', plugins_url('/js/asran-post.js', __FILE__), array('jquery', 'colorbox'));
            wp_enqueue_style('colorbox-css', plugins_url('/css/colorbox.css', __FILE__));
            wp_localize_script( 'asran-post', 'asran_custom', $asran_custom_admin_url );
         }
         break;
      default:
         return;
   }
}
add_action( 'admin_enqueue_scripts', 'asran_admin_scripts' );

