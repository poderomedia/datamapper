=== ACF Single Relationship Add New ===
Contributors: fancyfiber
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=J2UT8BCPQGA3S
Tags: acf, advanced custom fields, relationship, add-on, admin
Requires at least: 3.0.0
Tested up to: 3.5
Version: 1.2
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a new related item while editing a post, without leaving the current post.

== Description ==

**Requires the plug-in [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/ "Awesome plug-in to manage custom fields").**

This is a very basic add-on to allow the ability for relationship fields that are associated with a single related item, to be able to add a new
item to the related item without leaving the post/page you are currently editing. Uses a colorbox pop-up to open the add new post window with very
limited options.

Usage:

* Install the plug-in (standard WordPress installation)
* Set up a relationship field with only one related item (standard Advanced Custom Field configuration)
* Add or Edit a post with the relationship and the new Add New button will show up on the relationship field
* Click the Add New button to open Colorbox add new post for the related item
* Click the publish button and the new item will be added (or click the X button to cancel) and the Colorbox will close
* The newly added item will be available for selection in the relationship list

This plug-in was developed for [Tanabicom, LLC](http://www.tanabi.com) and [5D Spectrum, Inc](http://www.5dspectrum.com) by [Fancy Fiber Designs](http://hosting.fancyfiber.com/design/)

== Installation ==

1. Be sure to have **Advanced Custom Fields** installed and activated first
1. Upload `acf-single-relationship-add-new` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Start using the feature in your admin posts and pages

== Frequently Asked Questions ==

= Why do I need this plug-in? =

Simply, it provides a method to add new relationship items while editing a post or page, so you don't have to leave your current screen to get a new item to show up.
Even if you were to add in a new window, the new item may not show up in the window you are working on. It is just a time saver, plain and simple.

= Why did you use Colorbox instead of Thickbox built into the WordPress Admin? =

The desire was to use Thickbox initially, but WordPress administration does some controlling of the size of the thickbox for the media upload and overriding these defaults
would be problematic for site I was working on at the time and presumably other sites as well. 

== Screenshots ==

1. Shows the new Add New button on a relationship field
2. Clicking the Add New button opens the Colorbox with the limited features. Clicking the publish button will post the new item and close the Colorbox.

== Changelog ==

= 1.2 =
Fixed post type not updating afer new version of ACF installed. New ACF version split files out and changed the manner of the javascript calls.
This version should work for both old and new releases of ACF.

= 1.1 =
Fix bug when WordPress is installed in a subdirectory rather than the root. Thanks to Scott Nath for the fix.

= 1.0 =
Initial Release
