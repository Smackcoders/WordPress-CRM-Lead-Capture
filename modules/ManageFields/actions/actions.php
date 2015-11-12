<?php
/******************************
 * filename:    modules/wptigerLeadField/actions/actions.php
 * description:
 */

class ManageFieldsActions extends SkinnyActions {

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

	public function executeview($request)
	{

		$data = array();

		foreach( $request as $key => $REQUESTS )
		{
			foreach( $REQUESTS as $REQUESTS_KEY => $REQUESTS_VALUE )
			{
				$data['REQUEST'][$REQUESTS_KEY] = $REQUESTS_VALUE;
			}
		}

		$data['HelperObj'] = new WPCapture_includes_helper;
		$data['module'] = $data["HelperObj"]->Module;
		$data['moduleslug'] = $data['HelperObj']->ModuleSlug;
		$data['activatedplugin'] = $data["HelperObj"]->ActivatedPlugin;
		$data['activatedpluginlabel'] = $data["HelperObj"]->ActivatedPluginLabel;

		$data['plugin_url']= WP_CONST_ULTIMATE_CRM_CPT_DIRECTORY;
		$data['onAction'] = 'onCreate';
		$data['siteurl'] = site_url();

		if(isset($data['REQUEST']['formtype']))
		{
			$data['formtype'] = $data['REQUEST']['formtype'];
		}
		else
		{
			$data['formtype'] = "post";
		}

		if(isset($data['REQUEST']['EditShortCode']) && ( $data['REQUEST']['EditShortCode'] == "yes" ) )
		{
			$data['option'] = $data['options'] = "smack_{$data['activatedplugin']}_lead_{$data['formtype']}_field_settings"; // final output sample 'smack_wptigerfree_lead_post_field_settings //'smack_wp_vtiger_fields_shortcodes';
		}
		else
		{
			$data['option'] = $data['options'] = "smack_{$data['activatedplugin']}_{$data['moduleslug']}_fields-tmp"; // final output sample 'smack_wptigerpro_vtiger_fields-tmp' //'smack_wp_vtiger_lead_fields-tmp';
		}


		if(isset($data['REQUEST']['EditShortCode']) && ( $data['REQUEST']['EditShortCode'] == "yes" ) )//&& $_REQUEST['EditShortCode']=='' && is_null($_REQUEST['EditShortCode']))
		{
			$data['onAction'] = 'onEditShortCode';
		}
		else
		{
			$data['onAction'] = 'onCreate';
		}

                return $data;
	}

}
