<?php

require_once('SmackContactFormGenerator.php');
    add_action('wpcf7_submit','contact_forms_example');

 function contact_forms_example()
{

	global $wpdb,$HelperObj;
        $post_id = $_POST['_wpcf7'];


	$all_fields = $_POST;
        foreach($all_fields as $key=>$value)    {
        if(preg_match('/^_wp/',$key))
        unset($all_fields[$key]);
        }
//print_r($all_fields);
	$enable_fields = get_option('contact_enable_fields');
//print_r($enable_fields);
	foreach($enable_fields as $key=>$value)
	{
		$cont_labl = $value['label'];
		$cont_label = preg_replace('/\/| |\(|\)|\?/','_',$cont_labl);
		$cont_label = rtrim($cont_label,':');
		$cont_label = rtrim($cont_label,'_');
//	echo "<pre>";	print_r($cont_label);echo '</pre>';
		$cont_name  = $value['name'];
//print_r($cont_label);
		foreach($all_fields as $field_id=>$user_value)
		{	
		//	echo "<pre>"; print_r($field_id);echo "</pre>";
			$field_id = rtrim($field_id,':');
			$field_id = rtrim($field_id,'_');
	//		echo "<pre>"; print_r($field_id);echo "</pre>";
			if($field_id == $cont_label)
			{
				$ArraytoApi[$value['name']] = $user_value;
			}
			
		}
	}


	$activateplugin = $HelperObj->ActivatedPlugin;
	$formtype = get_option('form_type');
	$shortcode = "[{$activateplugin}-web-form type='{$formtype}']";
		
	$code['name'] = $shortcode;
/*        $newform = new CaptureData();
        $newshortcode = $newform->formfields_settings( $code['name'] );
print_r($newshortcode);die();
        $FormSettings = $newform->getFormSettings( $code['name'] );
//print_r($FormSettings);die('bye');
                $module = $FormSettings->module; //$shortcodes[$attr['name']]['module'];
//print_r($module);die;*/
        $ArraytoApi['moduleName'] = 'Leads';
        $ArraytoApi['formnumber'] = $post_id;
        //$ArraytoApi['submitcontactform'] = '';
        $ArraytoApi['submit'] = 'Submit';
     //   $activatedPlugin = $HelperObj->ActivatedPlugin;
        foreach($ArraytoApi as $key=>$value)
                {
                if($key=='')
                {
                $noe = $key;
                }

                        if(is_array($ArraytoApi[$key]))
                        {
                                switch($activateplugin)
                                {
                                        case 'wptigerfree':
                                        $ArraytoApi[$key] ='1';
                                        break;

                                        case 'wpsugarfree':
                                        $ArraytoApi[$key] ='on';
                                        break;

                                        case 'wpzohofree':
                                        $ArraytoApi[$key] ='true';
                                        break;

					case 'wpsalesforcefree':
                                        $ArraytoApi[$key] ='on';
                                        break;
                                }
                        }
                }
        unset($ArraytoApi[$noe]);

        global $_POST;
                $_POST = array();
                $_POST = $ArraytoApi;
//      $code['name'] = $smack_shortcode;
//print_r($_POST);
	smackContactFormGenerator($code);
        callCurlFREE('post');

        return true;


}

?>
