<?php
/**
 * The main plugin file
 *
 * @package WordPress_Plugins
 * @subpackage WP_Core_Updates_Permission
 */

/*
Plugin Name: Core Updates Permission 
Description: Forked from `Disable All WordPress Updates` pluggin. The one significant difference is that this plugin also allows you to pick and choose administrators that *can* have the ability to make updates. 
Plugin URI:  http://wordpress.org/plugins/disable-wordpress-updates/
Version:     1.4.0.1
Author:      Mike Auteri (`Disable All WordPress Updates` by Oliver Schlöbe and Tanja Preu&szlig;e)
Author URI:  http://www.mikeauteri.com/

Copyright 2013 Mike Auteri (email : mauteri@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Define the plugin version
 */
define( 'WPCUPVERSION', '1.4.0.1' );

/**
 * The WP_Core_Updates_Permission class
 *
 * @package 	WordPress_Plugins
 * @subpackage 	WP_Core_Updates_Permission
 * @since 		1.3
 * @author 		scripts@schloebe.de
 */
class WP_Core_Updates_Permission {
	/**
	 * The WP_Core_Updates_Permission class constructor
	 * initializing required stuff for the plugin
	 *
	 * PHP 4 Compatible Constructor
	 *
	 * @since 		1.3
	 * @author 		scripts@schloebe.de
	 */
	function WP_Core_Updates_Permission() {
		$this->__construct();
	}
	
	
	/**
	 * The WP_Core_Updates_Permission class constructor
	 * initializing required stuff for the plugin
	 *
	 * PHP 5 Constructor
	 *
	 * @since 		1.3
	 * @author 		scripts@schloebe.de
	 */
	function __construct() {
		/*
		 * Add Core Updates Field To Profile
		 */
		add_action( 'show_user_profile', array( &$this, 'core_updates_permission_field' ) );
		add_action( 'edit_user_profile', array( &$this, 'core_updates_permission_field' ) );
		add_action( 'personal_options_update', array( &$this, 'save_core_updates_permission_field' ) );
		add_action( 'edit_user_profile_update', array( &$this, 'save_core_updates_permission_field' ) );
		
		/*
		 * Check if current user has permission to make core updates
		 */
		add_action('widgets_init', array($this, 'can_user_make_core_updates'));
	}

	/*
	 * Check to see if user can make updates
	 * 
	 * @since		1.4
	 * @author	mauteri@gmail.com
	 */
	function can_user_make_core_updates() {
		$current_user = wp_get_current_user();
		
		if( !(bool) get_the_author_meta( 'allow_core_updates', $current_user->ID ) ) {
			add_action( 'admin_head', array(&$this, 'remove_nag'));
			add_action('admin_init', array(&$this, 'admin_init'));
			
			/*
			 * Disable Theme Updates
			 * 2.8 to 3.0
			 */
			add_filter( 'pre_transient_update_themes', create_function( '$a', "return null;" ) );
			/*
			 * 3.0
			 */
			add_filter( 'pre_site_transient_update_themes', create_function( '$a', "return null;" ) );
			
			/*
			 * Disable Plugin Updates
			 * 2.8 to 3.0
			 */
			add_action( 'pre_transient_update_plugins', array(&$this, create_function( '$a', "return null;" )) );
			/*
			 * 3.0
			 */
			add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );
			
			/*
			 * Disable Core Updates
			 * 2.8 to 3.0
			 */
			add_filter( 'pre_transient_update_core', create_function( '$a', "return null;" ) );
			/*
			 * 3.0
			 */
			add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );
		}
		
	}

	/*
	 * Hide notifications to update 
	 * 
	 * @since		1.4.0.1
	 * @author	mauteri@gmail.com
	 */
	function remove_nag() {
		 echo '<style type="text/css">
						 #update-nag, .update-nag { display: none }
					 </style>';
	}
	
	/**
	 * Initialize and load the plugin stuff
	 *
	 * @since 		1.3
	 * @author 		scripts@schloebe.de
	 */
	function admin_init() {
		if ( !function_exists( 'remove_action' ) ) 
			return;
	
		/*
		 * Disable Theme Updates
		 * 2.8 to 3.0
		 */
		remove_action( 'load-themes.php', 'wp_update_themes' );
		remove_action( 'load-update.php', 'wp_update_themes' );
		remove_action( 'admin_init', '_maybe_update_themes' );
		remove_action( 'wp_update_themes', 'wp_update_themes' );
		wp_clear_scheduled_hook( 'wp_update_themes' );
		
		/*
		 * 3.0
		 */
		remove_action( 'load-update-core.php', 'wp_update_themes' );
		wp_clear_scheduled_hook( 'wp_update_themes' );
		
		
		/*
		 * Disable Plugin Updates
		 * 2.8 to 3.0
		 */
		remove_action( 'load-plugins.php', 'wp_update_plugins' );
		remove_action( 'load-update.php', 'wp_update_plugins' );
		remove_action( 'admin_init', '_maybe_update_plugins' );
		remove_action( 'wp_update_plugins', 'wp_update_plugins' );
		wp_clear_scheduled_hook( 'wp_update_plugins' );
		
		/*
		 * 3.0
		 */
		remove_action( 'load-update-core.php', 'wp_update_plugins' );
		wp_clear_scheduled_hook( 'wp_update_plugins' );
		
		
		/*
		 * Disable Core Updates
		 * 2.8 to 3.0
		 */
		remove_action( 'wp_version_check', 'wp_version_check' );
		remove_action( 'admin_init', '_maybe_update_core' );
		wp_clear_scheduled_hook( 'wp_version_check' );
		
		
		/*
		 * 3.0
		 */
		wp_clear_scheduled_hook( 'wp_version_check' );
	}

	/*
	 * Save core updates field
	 *
	 * @since			1.4
	 * @author		mauteri@gmail.com
	 */	
	function save_core_updates_permission_field( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) )
			return false;

		update_user_meta( $user_id, 'allow_core_updates', $_POST['allow_core_updates'] );
	}

	/*
	 * Add Core Updates fields to profile page
	 *
	 * @since			1.4
	 * @author		mauteri@gmail.com
	 */	
	function core_updates_permission_field( $user ) { 
		if( $this->get_current_user_role( $user ) ) {
			ob_start(); ?>
			<h3><?php _e( 'Core Updates Permission', 'blank' ); ?></h3>

			<table class="form-table">
				<tr>
					<th><label for="allow-core-updates"><?php _e( 'Allow this User to make Core Updates?' ); ?></label></th>
					<td>
						<span class="button-wrapper">
							<input type="radio" name="allow_core_updates" id="allow-core-updates-y" value="1"<?php checked( (bool) get_the_author_meta( 'allow_core_updates', $user->ID ) ); ?> /> <label for="allow-core-updates-y">Yes</label></span>&nbsp;&nbsp; 
						<span class="button-wrapper">
							<input type="radio" name="allow_core_updates" id="allow-core-updates-n" value="0"<?php checked( !(bool) get_the_author_meta( 'allow_core_updates', $user->ID ) ); ?> /> <label for="allow-core-updates-n">No</label>
						</span>
						<p class="description"><?php _e( '&ldquo;With Great Power Comes Great Responsibility&rdquo; &mdash;Voltaire' ); ?></p>
					</td>
				</tr>
			</table>
			<?php
			echo ob_get_clean();
		}
	}

	/*
	 * User role check
	 *
	 * @since			1.4
	 * @author		mauteri@gmail.com
	 */	
	function get_current_user_role( $user ) {
    $user_roles = $user->roles;
    $user_role = array_shift($user_roles);
		if( $user_role == 'administrator' )
			return true;
		else
			return false;
	}
}

if ( class_exists('WP_Core_Updates_Permission') && is_admin() ) {
	$WP_Core_Updates_Permission = new WP_Core_Updates_Permission();
}
