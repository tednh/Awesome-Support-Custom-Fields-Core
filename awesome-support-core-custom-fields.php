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
/**
 * Register all custom fields after the plugin is safely loaded.
 */
add_action( 'plugins_loaded', 'wpas_user_custom_fields' );

function wpas_user_custom_fields() {

	/* You can start adding your custom fields safely after this line */
	if ( function_exists( 'wpas_add_custom_field' ) ) {

		wpas_add_custom_field( 'manufacturer', 
				array(
					'title' => __( 'My Custom Manufacturer', 'wpas' ), //(mandatory) (string): The “human readable” name of your custom field. That’s the name that’ll be shown in the submission form.
					'label' => __( 'Manufacturer', 'wpas' ), 
					'field_type' => 'text', //(mandatory) (string): The type of field that you want to register.
					'required' => false, //(boolean): true or false Whether or not this field is required for submission. If set to false a ticket can be submitted even if this field is not filled-in
					'order' => 16, //The order in which the field should appear on the screen
					'placeholder' => '', //An optional placeholder to use with input fields
					'default' => '', // The (optional) default value for your custom field
					'log' => '', //(boolean): true or false Whether or not to log the changes of values to this field. The log is shown in the ticket history in the back-end (seen by admins and agents)
					)
				);
		
		/*
		//Checkbox, Radio and Select-Only Parameters		
		wpas_add_custom_field( 'device_in_use', 
				array(
					'title' => __( 'Device', 'wpas' ),
					'field_type' => 'select',
					'required' => true, 
					'options' => array( 'iphone' => 'Iphone', 'mac' => 'Macbook', 'ipad' => 'Ipad' ),
					'order' => 2 
					)
				);	

		
		
	    /**
		 * Add custom upload field for check image upload.
		 *
		 * This field allows the user to upload
		 * a single image attachment.
		 *
		 * Requirements:
		 * - User must have 'edit_ticket' capability.
		 * - Only one file allowed.
		 * - Upload activity will be logged.
		 */
	    /**
	     * Upload field configuration.
	     */
	   /* $image_array_args = array(

	        // Field label/title shown to users.
	        'title' => __( 'Front Of Check', 'wpas' ),

	        // Field type.
	        'field_type' => 'upload',

	        // Required user capability.
	        'capability' => 'edit_ticket',

	        // Allow multiple uploads or not.
	        'multiple' => false,

	        // Help text displayed below the field.
	        'label' => __(
	            'Upload an image of the front of your check',
	            'wpas'
	        ),

	        // Save upload actions to ticket log.
	        'log' => true,

	        // Display order.
	        'order' => 1,
	    );*/

	    /**
	     * Register the custom upload field.
	     */
	   /* wpas_add_custom_field(
	        'front_of_check',
	        $image_array_args
	    );  */ 


		
	}

	if ( function_exists( 'wpas_add_custom_taxonomy' ) ) {
		
		wpas_add_custom_taxonomy( 
			'my_custom_services', 
			array( 
				'title' => 'My Custom Services', 
				'label' => 'Custom Service', 
				'label_plural' => 'Custom Services', 
				'order' => 1 
			) 
		);

	}

	//An Example Using A Custom Upload Field
	/**
	 * Register custom upload field on the ticket submission form.
	 *
	 * Hook:
	 * - wpas_submission_form_inside_after_subject
	 *   Displays the field right after the ticket subject field.
	 */
	add_action( 'wpas_submission_form_inside_after_subject', 'my_upload_field_frontend', 1 ); 

	/**
	 * Add custom upload field for check image upload.
	 *
	 * This field allows the user to upload
	 * a single image attachment.
	 *
	 * Requirements:
	 * - User must have 'edit_ticket' capability.
	 * - Only one file allowed.
	 * - Upload activity will be logged.
	 */
	function my_upload_field_frontend() {

		//echo "my_upload_field_frontend<pre>";var_dump( $xxxxxxx );echo "</pre>";
		// Make sure Awesome Support function exists.
		if ( ! function_exists( 'wpas_add_custom_field' ) ) {
		    return;
		}

		//echo "wpas_add_custom_field<pre>";var_dump( $image_array_args );echo "</pre>";
	}

/* Do NOT write anything after this line */
//load main class if not exists
if ( ! class_exists( 'WPAS_File_Upload' ) ) {
    if ( defined( 'WPAS_PATH' ) && file_exists( WPAS_PATH . 'includes/file-uploader/class-file-uploader.php' ) ) {
        require( WPAS_PATH . 'includes/file-uploader/class-file-uploader.php' );
    }
    if ( ! class_exists( 'ASCF_My_File_Upload' ) ) {
	    class ASCF_My_File_Upload extends WPAS_File_Upload {
	        protected $index;

	        public function __construct( $field_name ) {
	            $this->setup_index( $field_name );
	            if ( is_admin() ) {
	                add_action( 'wpas_add_reply_admin_after', array( $this, 'new_reply_backend_attachment' ), 10, 2 );
	            } else {
	                add_action( 'wpas_open_ticket_after', array( $this, 'new_ticket_attachment' ), 10, 2 );
	            }
	        }

	        protected function setup_index( $field_name ) {
	            $this->index = $field_name;
	        }
	    }
	    new ASCF_My_File_Upload( 'front_of_check' );
	}

	
}

}



