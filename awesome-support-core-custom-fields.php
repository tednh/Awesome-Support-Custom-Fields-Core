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

//Coder remove the  comment ( symbols '//' ) if he wants to add custom "upload" field for Front-end submit ticket form ONLY
//add_action( 'wpas_submission_form_pre_render_fields', 'my_upload_field_frontend', 1 ); 

/**
 * Register custom upload field.
 *
 * @return void
 */
function my_upload_field_frontend() {

	// Stop if Awesome Support function does not exist.
	if ( ! function_exists( 'wpas_add_custom_field' ) ) {
		return;
	}

	$field_args = array(
		'title'      => __( 'Front Of Check', 'textdomain' ),
		'field_type' => 'upload',
		'capability' => 'edit_ticket',
		'multiple'   => false,
		'label'      => __( 'Upload an image of the front of your check', 'textdomain' ),
		'log'        => true,
		'order'      => 1,
	);
	if ( function_exists( 'wpas_add_custom_field' ) ) {

		wpas_add_custom_field( 'front_of_check', $field_args );
	}	
}

//Coder remove the comment ( symbols '//' )  if he wants to add custom "upload" field for Back-end ONLY
//add_action( 'wpas_mb_details_before_custom_fields', 'my_upload_field_backend' );

/**
 * Add backend upload field: Imagem do Registro
 *
 * @return void
 */
function my_upload_field_backend() {

	// Ensure function exists before using it
	if ( ! function_exists( 'wpas_add_custom_field' ) ) {
		return;
	}

	$field_args = array(
		'title'      => __( 'Imagem do Registro', 'textdomain' ),
		'field_type' => 'upload',
		'capability' => 'edit_ticket',
		'multiple'   => false,
		'label'      => __( 'Insira aqui a imagem do protocolo', 'textdomain' ),
		'log'        => true,
		'order'      => 1,
	);
	if ( function_exists( 'wpas_add_custom_field' ) ) {

		wpas_add_custom_field( 'imagem_do_registro', $field_args );
	}
}

/**
 * Register all custom fields after the plugin is safely loaded.
 */
add_action( 'plugins_loaded', 'wpas_user_custom_fields' );

function wpas_user_custom_fields() {

	//load main class if not exists
	if ( ! class_exists( 'WPAS_File_Upload' ) ) {
	    if ( defined( 'WPAS_PATH' ) && file_exists( WPAS_PATH . 'includes/file-uploader/class-file-uploader.php' ) ) {
	        require( WPAS_PATH . 'includes/file-uploader/class-file-uploader.php' );

		} else {
	        exit();
	    }
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
	}	

	// Instantiate only if class exists
	if ( class_exists( 'ASCF_My_File_Upload' ) ) {
		// Coder will replace with name of field he wants to use
		new ASCF_My_File_Upload( 'my_upload_field_frontend' );
		//new ASCF_My_File_Upload( 'front_of_check' );
		//new ASCF_My_File_Upload( 'imagem_do_registro' );
	}

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
					'log' => true, //(boolean): true or false Whether or not to log the changes of values to this field. The log is shown in the ticket history in the back-end (seen by admins and agents)
					'show_column' => false	
				)
		);

		//Checkbox, Radio and Select-Only Parameters		
		wpas_add_custom_field( 'my_checkbox_field', 
			array(
					'title' => __( 'My Checkbox Field', 'wpas' ),
					'label' => __( 'My Checkbox Field', 'wpas' ), 
					'field_type' => 'checkbox',
					'required' => true, 
					'options' => array( 'iphone' => 'Iphone', 'mac' => 'Macbook', 'ipad' => 'Ipad' ),
					'order' => 2 
			)
		);	
		//WYSIWYG-The WordPress TinyMCE text editor.
		wpas_add_custom_field( 'wysuwyg_only', 
			array(
				'title' => __( 'My WYSIWYG field ', 'wpas' ),
				'label' => __( 'My WYSIWYG field', 'wpas' ), 
				'field_type' => 'wysiwyg',
				'required' => true, 					
				'order' => 2 
			)
		);

		wpas_add_custom_field( 'submit_date', 			
				array(
				'title'             => __( 'Submit Date', 'awesome-support' ),
				'field_type' => 'date-field',
				'required' => true, 		
				'column_attributes' => array(
					'head' => array( 'type' => 'numeric', 'sort-initial' => 'descending' ),
					'body' => array( 'value' => 'wpas_get_the_time_timestamp' ),
				),
			),
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
	   	$image_array_args = array(

	        // Field label/title shown to users.
	        'title' => __( 'Upload File for Front Of Check', 'wpas' ),
	        // Field type.
	        'field_type' => 'upload',
	        // Required user capability.
	        'capability' => 'edit_ticket',
	        // Allow multiple uploads or not.
	        'multiple' => false,
	        // Help text displayed below the field.
	        'label' => __(
	            'Upload an image of the front of your check', 'wpas' ),
	        // Save upload actions to ticket log.
	        'log' => true,
	        // Display order.
	        'order' => 1,
	    );

	    /**
	     * Register the custom upload field for both FRONT-END submit ticket form and in back-end
	     */
	    wpas_add_custom_field( 'my_upload_field_frontend', $image_array_args ); 

	}			
	
	//Dynamic Dropdown (AKA Taxonomy)
	if ( function_exists( 'wpas_add_custom_taxonomy' ) ) {		
		wpas_add_custom_taxonomy( 
			'my_custom_services', 
			array( 
				'title' => 'My Custom Services', 
				'label' => 'Custom Service', 
				'label_plural' => 'Custom Services', 
				'order' => 1,
				'show_column' => true,
				'column_attributes' => 
					array(
						'head' => array( 'type' => 'text', 'sort-initial' => 'descending' ),					
					),			
			) 
		);	
	}	
}

/* Do NOT write anything after this line */

	



