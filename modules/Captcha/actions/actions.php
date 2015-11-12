<?php
/******************************
 * filename:    modules/vtigerSettings/actions/actions.php
 * description:
 */

class CaptchaSettingsActions extends SkinnyActions {

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

	public function saveSettingArray($sett_array)
	{

		$fieldNames = array(
			'recaptcha_public_key' => __('Recaptcha Public Key'),
			'recaptcha_private_key' => __('Recaptcha Private Key'),
			'smack_recaptcha' => __('Recaptcha'),
		);

		foreach ($fieldNames as $field=>$value){
			$config[$field] = $sett_array[$field];
		}

                $WPCapture_includes_helper_Obj = new WPCapture_includes_helper();
                $activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
		update_option("wp_captcha_settings", $config);
	}

}

class CallCaptchaSettingsCrmObj extends CaptchaSettingsActions
{
	private static $_instance = null;

	public static function getInstance()
	{
		if( !is_object(self::$_instance) )  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
			self::$_instance = new CaptchaSettingsActions();
		return self::$_instance;
	}
}	// CallSettingsCrmObj Class Ends   
