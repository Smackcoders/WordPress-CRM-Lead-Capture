<?php
/******************************
 * filename:    modules/wpsugarShortcode/actions/actions.php
 * description:
 */

class ManageShortcodesActions extends SkinnyActions {

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

	public function getUsersListHtml()
	{
	
	}


	public function executeManageFields($request)
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


if (isset ($_POST['formtype'])) {
		$SaveFields = new SaveFields();
		$formFields = $SaveFields->saveFormFields( $data['option'] , $data['onAction'] , $data['REQUEST']['EditShortCode'] , $data , $data['formtype'] );



	if( isset($formFields['display']) )
		{
			echo $formFields['display'];
		}
	}


//		if($thirdPartyPlugin == 'contactform' && !empty($request['POST']))        {
  //                                      $obj->formatContactFields($request['GET']['EditShortcode']);
  //                              }

                return $data;
	}


}
class SaveFields
{
function saveFormFields( $options , $onAction , $editShortCodes ,  $request , $formtype = "post" )
{

	$HelperObj = new WPCapture_includes_helper();
	$module = $HelperObj->Module;
	$moduleslug = $HelperObj->ModuleSlug;
	$activatedplugin = $HelperObj->ActivatedPlugin;
	$activatedpluginlabel = $HelperObj->ActivatedPluginLabel;

	$save_field_config = array();


if( isset($request['REQUEST']['bulkaction']))
{
$action = $request['REQUEST']['bulkaction'];
$SaveFields = new SaveFields();
switch($action)
{
case 'enable_field':
	$save_field_config = $SaveFields->enableField( $request );
	break;

case 'disable_field':
	$save_field_config = $SaveFields->disableField($request);
	break;


case 'update_order':
	$save_field_config = $SaveFields->updateOrder($request);
	break;
}

//echo "<pre>";print_r($save_field_config);echo "</pre>";
$i =0;
foreach($save_field_config as $key=>$val)
{

	foreach($val as $key=>$value)
	{
	$i++;
	if($value['publish'] == 1)
		{
		$enable_fields[$i]['label'] = $value['label'];
		$enable_fields[$i]['name'] = $value['name'];
		$enable_fields[$i]['wp_mandatory'] = $value['wp_mandatory'];
		foreach($value['type'] as $key=>$val)
			{
				if($key == 'name')
				{
				$enable_fields[$i]['type'] = $val;
				}
			}	
		foreach($value['type']['picklistValues'] as $key=>$valuee )
			{
				
				$enable_fields[$i]['pickvalue'] = $value['type']['picklistValues'];//print_r($enable_field[$i][$j]['pickvalue']);
			}
 		}

//print_r($enable_fields[$i]['pickvalue']);
	} 
}

$WPCapture_includes_helper_Obj = new WPCapture_includes_helper();
$activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;

$contact = get_option("wp_{$activateplugin}_settings");

	if($contact['contact_form'] == 'on')
	{
	$obj = new SaveFields();
	$obj->formatContactFields($enable_fields,$activateplugin,$formtype);
	}
}	
	$data['display'] = "<p class='display_success'> Field Settings Saved </p>";
	return $data;
}

public function formatContactFields($enable_fields,$activateplugin,$formtype)
{
//echo "<pre>";print_r($enable_fields);echo "</pre>";
	update_option('contact_enable_fields',$enable_fields);
	foreach($enable_fields as $key=>$value)
	{
//echo "<pre>";	print_r($value);
	$type = $value['type'];
	$labl = $value['label'];
	$label = preg_replace('/\/| |\(|\)|\?/','_',$labl);

	$mandatory =$value['wp_mandatory'];
	//		$cont_array = array();
                        $cont_array = $value['pickvalue'];
                        $string ="";
	//		print_r($cont_array);
                        foreach($cont_array as $val) {
				
                                $string .= "\"{$val['label']}\" ";
                        }
                        $str = rtrim($string,',');
		if($mandatory == 0)
                                {
                                        $man ="";
                                }
                         else
                                {
                                        $man ="*";
                                }
		switch($type)
                {
                        case 'phone':
                        case 'currency':
                        case 'text':
                        case 'integer':
                        case 'string':
                                $contact_array .= "<p>".  $label ."".$man. "<br />
                                                 [text".$man." ".  $label."] </p>" ;
                                break;

                        case 'email':
                                $contact_array .= "<p>".  $label ."".$man. "<br /> 
                                                [email".$man." ". $label."] </p>" ;
                                break;
                        case 'url':
                                $contact_array .= "<p>".  $label ."".$man. "<br />
                                                [url".$man." ". $label."] </p>" ;
                                break;
                        case 'picklist':
                                $contact_array .= "<p>".  $label ."".$man. "<br />
                                                [select".$man." ". $label." " .$str."] </p>" ;
                                $str ="";
                                break;
                        case 'boolean':
                                $contact_array .= "<p>
                                                [checkbox".$man." ". $label." "."label_first "."\" $label\""."] </p>" ;                
                                break;
                        case 'date':
                                $contact_array .= "<p>".  $label ."".$man. "<br />
                                            [date".$man." ". $label." min:1950-01-01 max:2050-12-31 placeholder \"YYYY-MM-DD\"] </p>" ;
			          break;
                        case '':
                                $contact_array .= "<p>".  $label ."".$man. "<br />
                                                 [text".$man." ".  $label."] </p>" ;
                               break;

                        default:

                                break;

                }

	}
//print_r($contact_array);
	$contact_array .= "<p><br /> [submit "." \"Submit\""."]</p>";
        $meta = $contact_array;
	
	$shortcode = "[{$activateplugin}-web-form type='{$formtype}']";
	$title = "{$activateplugin}-web-form type='{$formtype}'";
	$checkid = get_option($shortcode);
	if(empty($checkid))
	{
		$contform = array (
                        'post_title'  => $shortcode,
                        'post_content'=> $contact_array,
                        'post_type'   => 'wpcf7_contact_form',
                        'post_status' => 'publish',
                        'post_name'   => $shortcode
                                  );
        	$id = wp_insert_post($contform);
        	$content2 = "[contact-form-7 id=\"$id\" title=\"$title\"]";
       		 $contform2 = array (
                'post_title'  => $id,
                'post_content'=> $content2,
                'post_type'   => 'post',
                'post_status' => 'publish',
                'post_name'   => $id
                );
        wp_insert_post($contform2);

        $post_id = $id;
        $meta_key ='_form';
        $meta_value = $meta;
        update_post_meta($post_id,$meta_key,$meta_value);
	update_option($shortcode,$id);
	}
	else
	{

	global $wpdb;
	$wpdb->query("update $wpdb->posts set post_content='{$contact_array}' where ID={$checkid}");
        $wpdb->query("update $wpdb->postmeta set meta_value='{$meta}' where post_id={$checkid} and meta_key='_form'");	
	}
}


public function enableField($data)
{


$config_fields = get_option( "smack_{$data['activatedplugin']}_lead_{$data['REQUEST']['formtype']}_field_settings" );

	if( !is_array( $config_fields['fields'] ) )
	{
		$config_fields = get_option("smack_{$data['activatedplugin']}_{$data['moduleslug']}_fields-tmp");

	}
	foreach( $config_fields as $shortcode_attributes => $fields )
	{
		if($shortcode_attributes == "fields")
		{
			foreach( $fields as $key => $field )
			{
				$save_field_config["fields"][$key] = $field;
				if( !isset($field['mandatory']) || $field['mandatory'] != 2 )
				{
					if(isset($_POST['select'.$key]))
					{
						$save_field_config['fields'][$key]['publish'] = 1;
					}
					
				}
				else
				{
					$save_field_config['fields'][$key]['publish'] = 1;
				}
			}
		}
		else
		{
			$save_field_config[$shortcode_attributes] = $fields;
		}

	}

update_option("smack_{$data['activatedplugin']}_lead_{$data['REQUEST']['formtype']}_field_settings", $save_field_config);
	update_option("smack_{$data['activatedplugin']}_{$data['moduleslug']}_fields-tmp" , $save_field_config);
	return $save_field_config;
}

public function disableField($data)
{

$config_fields = get_option( "smack_{$data['activatedplugin']}_lead_{$data['REQUEST']['formtype']}_field_settings" );

	if( !is_array( $config_fields ) )
	{
		$config_fields = get_option("smack_{$data['activatedplugin']}_{$data['moduleslug']}_fields-tmp");	
	}


	foreach( $config_fields as $shortcode_attributes => $fields )
	{
		if($shortcode_attributes == "fields")
		{
			foreach( $fields as $key => $field )
			{
				$save_field_config["fields"][$key] = $field;
				if( !isset($field['mandatory']) || $field['mandatory'] != 2 )
				{
					if(isset($data['REQUEST']['select'.$key]))
					{

						$save_field_config['fields'][$key]['publish'] = 0;
					}
					
				}
				else
				{

					$save_field_config['fields'][$key]['publish'] = 1;
				}
			}
		}
		else
		{
			$save_field_config[$shortcode_attributes] = $fields;
		}
	}
update_option("smack_{$data['activatedplugin']}_lead_{$data['REQUEST']['formtype']}_field_settings", $save_field_config);
	update_option("smack_{$data['activatedplugin']}_{$data['moduleslug']}_fields-tmp" , $save_field_config);
	return $save_field_config;
}


public function updateOrder($data)
{

$config_fields = get_option( "smack_{$data['activatedplugin']}_lead_{$data['REQUEST']['formtype']}_field_settings" );

	if( !is_array( $config_fields ) )
	{
		$config_fields = get_option("smack_{$data['activatedplugin']}_{$data['moduleslug']}_fields-tmp");	
	}


	foreach( $config_fields as $shortcode_attributes => $fields )
        {
                if($shortcode_attributes == "fields")
                {
                        foreach( $fields as $key => $field )
                        {
                                $save_field_config["fields"][$key] = $field;
			}
		}
	}

$extra_fields = array( "enableurlredirection" , "redirecturl" , "errormessage" , "successmessage");


	foreach( $extra_fields as $extra_field )
	{

		if(isset( $_POST[$extra_field]))
		{
			$save_field_config[$extra_field] = $_POST[$extra_field];

		} 
		else
		{
			unset($save_field_config[$extra_field]);
		}
	}

	for( $i = 0; $i < $data['REQUEST']['no_of_rows']; $i++ )
	{
		$REQUEST_DATA[$i] = $data['REQUEST']['position'.$i];
	}

	asort($REQUEST_DATA);
	$i = 0;
	foreach( $REQUEST_DATA as $key => $value )
	{
		$Ordered_field_config['fields'][$i] = $save_field_config['fields'][$key];
		$i++;
	}

	$save_field_config['fields'] = $Ordered_field_config['fields']; 
	update_option("smack_{$data['activatedplugin']}_lead_{$data['REQUEST']['formtype']}_field_settings", $save_field_config);
	update_option("smack_{$data['activatedplugin']}_{$data['moduleslug']}_fields-tmp" , $save_field_config);
	return $save_field_config;
}

}



class CallManageShortcodesCrmObj extends ManageShortcodesActions
{
	private static $_instance = null;
	public static function getInstance()
	{
		if( !is_object(self::$_instance) )  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
			self::$_instance = new CallManageShortcodesCrmObj();
		return self::$_instance;
	}
}// CallSugarShortcodeCrmObj Class Ends


/*	public function formatContactFields($shortcode)
	{



	} */
