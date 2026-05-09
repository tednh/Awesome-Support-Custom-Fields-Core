<?php
/**
 * @package   Awesome Support Core Custom Fields
 * @author    Awesome Support Team <contact@getawesomesupport.com>
 * @license   GPL-2.0+
 * @link      https://getawesomesupport.com
 * @copyright 2014-2026 AwesomeSupport
 *
 * @wordpress-plugin
 * Plugin Name:       Awesome Support: Core Custom Fields
 * Plugin URI:        http://getawesomesupport.com
 * Description:       Adds custom fields to the Awesome Support ticket submission form.
 * Version:           0.1.0
 * Author:            Awesome Support Team
 * Author URI:        https://getawesomesupport.com
 * Text Domain:       wpas
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'plugins_loaded', 'wpas_user_custom_fields' );
/**
 * Register all custom fields after the plugin is safely loaded.
 */
function wpas_user_custom_fields() {

	/* You can start adding your custom fields safely after this line */
	if ( function_exists( 'wpas_add_custom_field' ) ) {

		wpas_add_custom_field( 'phone_name', 
				array(
					'title' => __( 'Proizvođač', 'wpas' ),
					'field_type' => 'text',
					'required' => true
					)
				);
				
		wpas_add_custom_field( 'issue_name', 
				array(
					'title' => __( 'Uređaj', 'wpas' ),
					'field_type' => 'text',
					'required' => true
					)
				);	

		wpas_add_custom_field( 'custom_file', 
				array(
					'title' => __( 'VIDEO OR IMAGE', 'wpas' ),
					'field_type' => 'upload',
					'required' => false,
					'capability' => 'edit_ticket',
					'multiple' => false,
					'label' => 'Upload an image of the front of your checkxxxxxxxxxxxx',		
					'log' => true,
					'order' => 0
				)
			);	
	}

/* Do NOT write anything after this line */
}