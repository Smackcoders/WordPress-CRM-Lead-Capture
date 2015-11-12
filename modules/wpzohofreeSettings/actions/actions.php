<?php
/******************************
 * filename:    modules/wpsugarSettings/actions/actions.php
 * description:
 */

class WpzohoSettingsActions extends SkinnyActions {

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

	public function saveSettings( $sugarSettArray )
	{
		$fieldNames = array(
//			'url' => __('Zoho Address'),
			'username' => __('Zoho Username'),
			'password' => __('Zoho Password'),

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

		$FunctionsObj = new Functions( );
		$jsonData = $FunctionsObj->getAuthenticationKey( $config['username'] , $config['password'] );

		if($jsonData['result'] == "TRUE")
		{
		
			$successresult = "<p class='display_success' style='color: green;'> Settings Saved </p>";
			$result['error'] = 0;
			$result['success'] = $successresult;

			$config['authtoken'] = $jsonData['authToken'];

			$WPCapture_includes_helper_Obj = new WPCapture_includes_helper();
			$activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
	                update_option("wp_{$activateplugin}_settings", $config);
		}
		else
		{

			if($jsonData['cause'] == 'EXCEEDED_MAXIMUM_ALLOWED_AUTHTOKENS') {
				$zohocrmerror = "<p style='color:red; '>Please log in to <a target='_blank' href='https://accounts.zoho.com'>https://accounts.Zoho.com</a> - Click Active Authtoken - Remove unwanted Authtoken, so that you could generate new authtoken..</p>";
//				$alert = "Please log in to https://accounts.zoho.com - Click Active Authtoken - Remove unwanted Authtoken, so that you could generate new authtoken..";
			}
			else{
				$zohocrmerror = "<p class='display_failure' style='color:red;' >Please Verify Username and Password.</p>";
			}
			$result['error'] = 1;
			$result['errormsg'] = $zohocrmerror ;
			$result['success'] = 0;
		} 

		return $result; 
	}	
}

class CallZohoSettingsCrmObj extends WpzohoSettingsActions
{
	private static $_instance = null;

	public static function getInstance()
	{
		if( !is_object(self::$_instance) )  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
			self::$_instance = new WpzohoSettingsActions();
		return self::$_instance;
	}
}// CallSugarSettingsCrmObj Class Ends
