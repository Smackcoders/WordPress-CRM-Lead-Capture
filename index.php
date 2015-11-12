<?php
/******************************
Plugin Name: WP Leads Builder For Any CRM 
Description: A plugin that helps to capture leads.
Version: 1.2.1
Author: smackcoders.com
Plugin URI: http://www.smackcoders.com
Author URI: http://www.smackcoders.com
 * filename: index.php
 */
ob_start();
define('WP_CONST_ULTIMATE_CRM_CPT_URL', 'http://www.smackcoders.com');
define('WP_CONST_ULTIMATE_CRM_CPT_NAME', 'WP Leads Builder For Any CRM');
define('WP_CONST_ULTIMATE_CRM_CPT_SLUG', 'wp-leads-builder-any-crm');
define('WP_CONST_ULTIMATE_CRM_CPT_SETTINGS', 'WP Leads Builder For Any CRM');
define('WP_CONST_ULTIMATE_CRM_CPT_VERSION', '1.2.1');
define('WP_CONST_ULTIMATE_CRM_CPT_DIR', WP_PLUGIN_URL . '/' . WP_CONST_ULTIMATE_CRM_CPT_SLUG . '/');
define('WP_CONST_ULTIMATE_CRM_CPT_DIRECTORY', plugin_dir_path( __FILE__ ));
define('WP_CONST_ULTIMATE_CRM_CPT_PLUG_URL',site_url().'/wp-admin/admin.php?page='.WP_CONST_ULTIMATE_CRM_CPT_SLUG.'/index.php');

if (!class_exists('SkinnyControllerCommonCrmFree')) {
        require_once('lib/skinnymvc/controller/SkinnyController.php');
}


$active_plugins = get_option("active_plugins");

	require_once("includes/ContactFormPlugins.php");
	$ContactFormPlugins = new ContactFormPlugins();
	$ActivePlugin = $ContactFormPlugins->getActivePlugin();
	$get_debug_mode = get_option("wp_{$ActivePlugin}_settings");

	if(!isset($get_debug_mode['debug_mode'])) {
	        error_reporting(0);
       		 ini_set('display_errors', 'Off');
	}

	require_once("includes/{$ActivePlugin}Functions.php");
	require_once('lib/skinnymvc/controller/SkinnyController.php');
	require_once('includes/WPCapture_includes_helper.php');
	require_once("templates/SmackContactFormGenerator.php");
	require_once('includes/Functions.php');

	# Activation & Deactivation 
	register_activation_hook(__FILE__, array('WPCapture_includes_helper', 'activate') );
	register_deactivation_hook(__FILE__, array('WPCapture_includes_helper', 'deactivate') );

	function action_admin_menu_crm()
	{
		add_menu_page(WP_CONST_ULTIMATE_CRM_CPT_SETTINGS, WP_CONST_ULTIMATE_CRM_CPT_NAME, 'manage_options',  __FILE__, array('WPCapture_includes_helper','output_fd_page'), WP_CONST_ULTIMATE_CRM_CPT_DIR . "/images/leadsIcon24.png");
	}
	add_action ( "admin_menu", "action_admin_menu_crm" );

	function action_crm_init()
	{
		if (isset($_REQUEST['page']) && ($_REQUEST['page'] == WP_CONST_ULTIMATE_CRM_CPT_SLUG.'/index.php' || $_REQUEST['page'] == 'page')) {
			wp_enqueue_style('main-style', plugins_url('css/mainstyle.css', __FILE__));
			wp_enqueue_style('common-crm-free-bootstrap-css', plugins_url('css/bootstrap.css', __FILE__));
			wp_enqueue_style('common-crm-free-font-awesome-css', plugins_url('css/font-awesome/css/font-awesome.css', __FILE__));
			wp_register_script('common-crm-free-bootstrap-min-js', plugins_url('js/bootstrap.min.js', __FILE__));
			wp_enqueue_script('common-crm-free-bootstrap-min-js');
			wp_register_script('basic-action-js', plugins_url('js/basicaction.js', __FILE__));
			wp_enqueue_script('basic-action-js');
		}
	}


	function frontend_init()
	{
		if(!is_admin())
		{
			wp_enqueue_style('front-end-styles' , plugins_url('css/frontendstyles.css', __FILE__) );
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_style('jquery-datepicker-css', plugins_url('css/datepicker.css', __FILE__) );
		}
	}

	add_action('init' , 'frontend_init');
	add_action('admin_init', 'action_crm_init');
	add_action( 'profile_update', array( 'CapturingProcessClass' , 'capture_registering_users' ) );
	add_action( 'user_register', array( 'CapturingProcessClass' , 'capture_registering_users' ) );

	$WPCapture_includes_helper_Obj = new WPCapture_includes_helper();
	$activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;

	$contact = get_option("wp_{$activateplugin}_settings");
        if($contact['contact_form'] == 'on')
        {
		require_once('templates/contact_form_field_handling.php');
	} 


	function StartSession() {
	    if(!session_id()) {
		session_start();
	    }
	}

	function EndSession() {
	    session_destroy ();
	}

	function selectplug()
	{
		require_once("templates/plugin-select.php");
		die;
	}   

	add_action('admin_init', 'action_crm_init');
// Move Pages above Media
	function smack_free_builder_change_menu_order( $menu_order ) {
	   return array(
	       'index.php',
	       'edit.php',
	       'edit.php?post_type=page',
	       'upload.php',
	       'wp-leads-builder-any-crm/index.php',
	   );
	}
	add_filter( 'custom_menu_order', '__return_true' );
	add_filter( 'menu_order', 'smack_free_builder_change_menu_order' );

	add_action('wp_ajax_selectplug', 'selectplug');
?>
