<?php
/******************************
 * filename:    modules/wpsugarSettings/actions/actions.php
 * description:
 */

class WpsalesforceSettingsActions extends SkinnyActions {

	public function __construct()
	{
	}

	/**
	* The actions index method
	* @param array $request
	* @return array
	*/
	public function executeIndex($request)
	{
		// return an array of name value pairs to send data to the template
		$data = array();
		return $data;
	}

	public function saveSettings($sugarSettArray)
	{
		$fieldNames = array(
			'key' => __('Consumer Key'),
			'secret' => __('Consumer Secret'),
			'callback' => __('Callback URL'),

			'user_capture' => __('User Capture'),
			'contact_form' => __('Contact Form'),
			'smack_email' => __('Smack Email'),
                         'email' => __('Email id'),
			 'debug_mode' => __('Debug Mode'),



		//	'smack_host_app_key' => __('Application Key'),
/*
			'recaptcha_public_key' => __('Recaptcha Public Key'),
			'recaptcha_private_key' => __('Recaptcha Private Key'),
			'smack_recaptcha' => __('Recaptcha'),
*/
		);

	foreach ($fieldNames as $field=>$value){

	if(isset($sugarSettArray[$field]))
		{
			$config[$field] = $sugarSettArray[$field];
		}
		}	

                $WPCapture_includes_helper_Obj = new WPCapture_includes_helper();
                $activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;

                update_option("wp_{$activateplugin}_settings", $config);
	}	
}

class CallSalesforceSettingsCrmObj extends WpsalesforceSettingsActions
{
	private static $_instance = null;

	public static function getInstance()
	{
		if( !is_object(self::$_instance) )  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
			self::$_instance = new WpsalesforceSettingsActions();
		return self::$_instance;
	}
}// CallSugarSettingsCrmObj Class Endssssssss
