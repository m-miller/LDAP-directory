<?php
/*
Plugin Name: LDAP Directory Shortcode
Plugin URI: http://nursing.mayo.edu
Description: Shortcode for displaying a directory list by single or multiple mangers and job titles, or  by department number. Links photos, names and pagers to the Quarterly. 
Author: <a href="http://quarterly.mayo.edu/directory/person/person.htm?per_id=15469921" target="_blank">Martin Miller</a>, Mayo Clinic Department of Nursing
Version: 2.5
Author URI: http://nursing.mayo.edu
*/
 
/* TODO: clean up class

*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


require_once( plugin_dir_path( __FILE__ ) . 'ldap_dir.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 *
 * - replace Plugin_Name with the name of the class defined in
 *   `class-plugin-name.php`
 */
//register_activation_hook( __FILE__, array( 'ldaipDir', 'activate' ) );
//register_deactivation_hook( __FILE__, array( 'ldaipDir', 'deactivate' ) );


add_action( 'plugins_loaded', array( 'ldapDir', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 *
 * - replace `class-plugin-admin.php` with the name of the plugin's admin file
 * - replace Plugin_Name_Admin with the name of the class defined in
 *   `class-plugin-name-admin.php`
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
//if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	//require_once( plugin_dir_path( __FILE__ ) . 'ldap_dir.php' );
	//add_action( 'plugins_loaded', array( 'Plugin_Name_Admin', 'get_instance' ) );

//}
