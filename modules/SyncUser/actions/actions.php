<?php
/******************************
 * filename:    modules/wptigerSyncUser/actions/actions.php
 * description:
 */

class SyncUserActions extends SkinnyActions {

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

	public function saveSettingArray($sett_array , $HelperObj)
	{
		$module = $HelperObj->Module;
		$moduleslug = $HelperObj->ModuleSlug;
		$activatedplugin = $HelperObj->ActivatedPlugin;
		$activatedpluginlabel = $HelperObj->ActivatedPluginLabel;

		$fieldNames = array(
			'smack_user_capture' => __('Capture Registering User'),
			'smack_capture_duplicates' => __('Capture Duplicate users'),
        	);

                foreach ($fieldNames as $field=>$value){
			if(isset($sett_array[$field]))
			{
                        	$config[$field] = $sett_array[$field];
			}
			else
			{
				$config[$field] = "";
			}
                }

	        update_option("smack_{$activatedplugin}_user_capture_settings", $config);
	}

}
/*
class CallSyncWPCrmObj extends SyncUserActions
{
	private static $_instance = null;

	public static function getInstance()
	{
		if( !is_object(self::$_instance) )  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
			self::$_instance = new SyncUserActions();
		return self::$_instance;
	}
}// CallSyncWPCrmObj Class Ends  
*/
?>
