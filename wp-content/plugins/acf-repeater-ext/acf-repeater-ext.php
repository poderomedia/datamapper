<?php
/*
Plugin Name: Advanced Custom Fields: Repeater Field Extended
Plugin URI: http://www.lixsys.cl
Description: Adds the repeater2 field
Version: 1.0
Author: Alexis Nilo
Author URI: http://www.lixsys.cl
License: GPL
Copyright: Alexis Nilo
*/


class acf_repeater_plugin2
{
	var $settings;
	
	
	/*
	*  Constructor
	*
	*  @description: 
	*  @since 1.0.0
	*  @created: 23/06/12
	*/
	
	function __construct()
	{
		// vars
		$settings = array(
			'version' => '1.0',
			//'remote' => '--',
			'basename' => plugin_basename(__FILE__),
		);
		
		
		/*/ create remote update
		if( is_admin() )
		{
			if( !class_exists('acf_remote_update') )
			{
				include_once('acf-remote-update.php');
			}
			
			new acf_remote_update( $settings );
		}*/
		
		
		// actions
		add_action('acf/register_fields', array($this, 'register_fields'));
	}
	
	
	/*
	*  register_fields
	*
	*  @description: 
	*  @since: 3.6
	*  @created: 31/01/13
	*/
	
	function register_fields()
	{
		include_once('repeater-ext.php');
	}
		
}

new acf_repeater_plugin2();

?>
