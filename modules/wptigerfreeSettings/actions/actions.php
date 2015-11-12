<?php
/******************************
 * filename:    modules/vtigerSettings/actions/actions.php
 * description:
 */
class WptigerSettingsActions extends SkinnyActions {

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

	public function executeView($request)
	{
		// return an array of name value pairs to send data to the template
		$data = array();
		return $data;
	}


	public function saveSettings($sett_array)
	{

		$fieldNames = array(

			'url' => __('Vtiger Url'),
			'username' => __('Vtiger User Name'),
			'accesskey' => __('Vtiger Access Key'),

			'user_capture' => __('User Capture'),
			'contact_form' => __('Contact Form'),
			'smack_email' => __('Smack Email'),
                         'email' => __('Email id'),	
			 'debug_mode' => __('Debug Mode'),
			 

//			'smack_host_app_key' => __('Application Key'),
/*			'recaptcha_public_key' => __('Recaptcha Public Key'),
			'recaptcha_private_key' => __('Recaptcha Private Key'),
			'smack_recaptcha' => __('Recaptcha'),
*/
		);

		foreach ($fieldNames as $field=>$value){
			if(isset($sett_array[$field]))
			{
				$config[$field] = trim($sett_array[$field]);
			}
		}

		$FunctionsObj = new Functions( );
                $testlogin_result = $FunctionsObj->testLogin( $sett_array['url'] , $sett_array['username'] , $sett_array['accesskey'] );

		if($testlogin_result == 1)
		{

                        $successresult = "<p  class='display_success' style='color: green;'> Settings Saved </p>";
                        $result['error'] = 0;
                        $result['success'] = $successresult;
			$WPCapture_includes_helper_Obj = new WPCapture_includes_helper();
			$activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
			update_option("wp_{$activateplugin}_settings", $config);
		
		}
		else
		{
			$vtigercrmerror = "<p  class='display_failure' style='color:red;' >Please Verify Username and Password.</p>";

                        $result['error'] = 1;
                        $result['errormsg'] = $vtigercrmerror ;
                        $result['success'] = 0;
		}

		return $result;
	}

}
