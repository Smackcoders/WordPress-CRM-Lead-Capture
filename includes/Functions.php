<?php
/*

Cases : 
1) CreateNewFieldShortcode		Will create new field shortcode
2) FetchCrmFields			Will Fetch crm fields from the the crm
3) FieldSwitch				Enable/Disable single field
4) DuplicateSwitch			Change Duplicate handling settings 
5) MoveFields				Change the order of the fields
6) MandatorySwitch			Make Mandatory or Remove Mandatory
7) SaveDisplayLabel			Save Display Label
8) SwitchMultipleFields			Enable/Disable multiple fields
9) SwitchWidget				Enable/Disable widget  form
10) SaveAssignedTo			Save Assignee of the form leads 
11) CaptureAllWpUsers			Capture All wp users
*/

class OverallFunctions {

	public function doFieldAjaxAction()
	{
		$module = $_REQUEST['module'];
		$module_options = $module;
		$options = $_REQUEST['option'];
		$onAction = $_REQUEST['onAction'];
		$siteurl = site_url();
		$HelperObj = new WPCapture_includes_helper;
		//$module = $HelperObj->Module;
		$moduleslug = $HelperObj->ModuleSlug;
		$activatedplugin = $HelperObj->ActivatedPlugin;
		$activatedpluginlabel = $HelperObj->ActivatedPluginLabel;

		$FunctionsObj = new Functions();

		$tmp_option = "smack_{$activatedplugin}_{$moduleslug}_fields-tmp";

		if($onAction == 'onEditShortCode');
		{
			$original_options = "smack_{$activatedplugin}_fields_shortcodes";
			$original_config_fields = get_option($original_options);
		//	$shortcode = $_REQUEST['shortcode'];
		}
		//ii&option="+option+"&onAction="+onAction

		$SettingsConfig = get_option("wp_{$activatedplugin}_settings");

		if($onAction == 'onCreate')
		{
			$config_fields = get_option($options);
		}
		else
		{
			$config_fields = get_option($options);
		//	$config_fields = $config_fields[$shortcode];
		}
		//die;
		$FieldCount = 0;
		if(isset($config_fields['fields']))
		{
			$FieldCount =count($config_fields['fields']);
		}

		if(isset($config_fields)){
			$error[0] = 'no fields';
		}

//		if($FieldCount > 0)
		switch($_REQUEST['doaction'])
		{
			case "FetchCrmFields":

				$config_fields = $FunctionsObj->getCrmFields( $module );
       
				if($options != 'getSelectedModuleFields')
				{
					include(WP_CONST_ULTIMATE_CRM_CPT_DIRECTORY.'templates/crm-fields-form.php');
				}

                                if($onAction == 'onCreate')
                                {
                                        update_option($options, $config_fields);
                                }
                                else
                                {
					update_option($options, $config_fields);
					update_option("smack_{$activatedplugin}_{$moduleslug}_fields-tmp", $config_fields);
                                }

				break;
			default:
				break;
		}
	}
}

class AjaxActionsClass
{
	public static function adminAllActions()
	{
	//        require_once("modules/wptigerContactField/actions/allActions.php");
		$OverallFunctionObj = new OverallFunctions();
		if( isset($_REQUEST['operation']) && ($_REQUEST['operation'] == "NoFieldOperation") )
		{
			$OverallFunctionObj->doNoFieldAjaxAction( );
		}
		else
		{
			$OverallFunctionObj->doFieldAjaxAction(  );
		}
		die;
	}
}

add_action('wp_ajax_adminAllActions', array( "AjaxActionsClass" , 'adminAllActions' ));

class CapturingProcessClass
{
	function CaptureFormFields( $globalvariables )
	{
		$HelperObj = new WPCapture_includes_helper;
		$module = $HelperObj->Module;
		$moduleslug = $HelperObj->ModuleSlug;
		$activatedplugin = $HelperObj->ActivatedPlugin;
		$activatedpluginlabel = $HelperObj->ActivatedPluginLabel;
		$duplicate_inserted = 0;

		$module = $globalvariables['module'];
		$post = $globalvariables['post'];

		$FunctionsObj = new Functions();


		if(is_array($post))
		{
			foreach($post as $key => $value)
			{
				if(($key != 'moduleName') && ($key != 'submitcontactform') && ($key != 'submitcontactformwidget') && ($key != '') && ($key != 'submit'))
				{
					$module_fields[$key] = $value;
				}
			}
		}


//		$module_fields[$FunctionsObj->assignedToFieldId()] = $globalvariables['assignedto'];  
		unset($module_fields['formnumber']);
		unset($module_fields['IsUnreadByOwner']);

		$record = $FunctionsObj->createRecord( $module , $module_fields);
		$data = "";
		if($record['result'] == "success")
		{
			$duplicate_inserted++;
			$data = "/$module entry is added./";
		}

		return $data;
	}

	/*
	Capture wordpress user on registration or creating a user from Wordpress Users
	*/

	function capture_registering_users($user_id)
	{
		$HelperObj = new WPCapture_includes_helper;
		$module = $HelperObj->Module;
		$moduleslug = $HelperObj->ModuleSlug;
		$activatedplugin = $HelperObj->ActivatedPlugin;
		$activatedpluginlabel = $HelperObj->ActivatedPluginLabel;

		$SettingsConfig = get_option("wp_{$activatedplugin}_settings");

		if( isset($SettingsConfig['user_capture']) && ($SettingsConfig['user_capture'] == "on") )
		{
			$module = "Contacts";
			$duplicate_cancelled = 0;
			$duplicate_inserted = 0;
			$duplicate_updated = 0;
			$successful = 0;
			$failed = 0;

			$url = $SettingsConfig['url'];
			$username = $SettingsConfig['username'];
			$accesskey = $SettingsConfig['accesskey'];
			$FunctionsObj = new Functions();

			$config_user_capture = get_option("smack_{$activatedplugin}_user_capture_settings");

			$user_data = get_userdata( $user_id );
			$user_email = $user_data->data->user_email;

			$user_lastname = get_user_meta( $user_id, 'last_name', 'true' );
			$user_firstname = get_user_meta( $user_id, 'first_name', 'true' );
			if(empty($user_lastname))
			{
				$user_lastname = $user_data->data->display_name;
			}

			$post = $FunctionsObj->mapUserCaptureFields( $user_firstname , $user_lastname , $user_email );

			$config_fields = get_option("smack_{$activatedplugin}_lead_fields-tmp");

			$post[$FunctionsObj->assignedToFieldId()] = $config_fields['assignedto'];

			$record = $FunctionsObj->createRecordOnUserCapture( $module , $post );
			if($record)
			{
				$data = "/$module entry is added./";
			}
		}
	}
}
?>
