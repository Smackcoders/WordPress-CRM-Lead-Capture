<?php
//error_reporting(E_ALL);
//ini_set("display_error" , "On");
	if(!session_id()) {
		session_start();
	}
	if(isset($_SESSION['generated_forms']))
	{
		unset($_SESSION['generated_forms']);
	}
	

global $HelperObj;
$HelperObj = new WPCapture_includes_helper;
$activatedplugin = $HelperObj->ActivatedPlugin;
$activatedpluginlabel = $HelperObj->ActivatedPluginLabel;


add_filter('widget_text', 'do_shortcode');
add_shortcode( $activatedplugin."-web-form" ,'smackContactFormGenerator');
/*
add_shortcode('smack-wp-tiger-leads-widget-fields','smack_wp_tiger_leads_widget_fields');
add_shortcode('smack-wp-tiger-leads','smack_wp_tiger_leads');
add_shortcode('smack-wp-tiger-contacts-widget-fields','smack_wp_tiger_contacts_widget_fields');
add_shortcode('smack-wp-tiger-contacts','smack_wp_tiger_contacts');
*/
global $plugin_dir;
$plugin_dir = WP_CONST_ULTIMATE_CRM_CPT_DIRECTORY;
$plugin_url = WP_CONST_ULTIMATE_CRM_CPT_DIR;

$onAction = 'onCreate';
$siteurl = site_url();

global $config;
global $post;

$config = get_option("wp_{$activatedplugin}_settings");

$post = array();
//global $action;
global $module_options, $module , $isWidget, $assignedto, $check_duplicate, $update_record;
//$action=trim($config['url'], "/").'/modules/Webforms/post.php';

/*
function smack_wp_tiger_leads_widget_fields($attr)
{
	return smack_wp_tiger_pro_shortcode_function(array('name' => 'smack_wp_vtiger_lead_widget_fields'));
}

function smack_wp_tiger_leads($attr)
{
	return smack_wp_tiger_pro_shortcode_function(array('name' => 'smack_wp_vtiger_lead_fields'));
}

function smack_wp_tiger_contacts_widget_fields($attr)
{
	return smack_wp_tiger_pro_shortcode_function(array('name' => 'smack_wp_vtiger_contact_widget_fields'));
}

function smack_wp_tiger_contacts($attr)
{
	return smack_wp_tiger_pro_shortcode_function(array('name' => 'smack_wp_vtiger_contact_fields'));
}
*/

function smackContactFormGenerator($attr){
	global $HelperObj;
	global $module_options, $module, $isWidget, $assignedto, $check_duplicate, $update_record;
	$module_options = 'Leads';
	$shortcodes = get_option("smack_{$HelperObj->ActivatedPlugin}_lead_{$attr['type']}_field_settings");

	if(is_array($shortcodes))
	{
//		foreach($shortcodes as $name => $values)
		{
		
//	if($attr['name'] == $name)
			{
				$config_fields = $shortcodes['fields'];
				$module = $shortcodes['module'];
				$isWidget = $shortcodes['isWidget'];
				$assignedto = $shortcodes['assignedto'];
				$module_options = $module;
				$check_duplicate = $shortcodes['check_duplicate'];
				if(isset($shortcodes['update_record']))
				{
					$update_record = $shortcodes['update_record'];
				}
			}
		}
	}

	if($attr['type'] == "post")
	{
		return normalContactForm( $module, $config_fields, $module_options , "post" );
	}
	else
	{
		return widgetContactForm($module, $config_fields, $module_options , "widget" );
	}
}

function callCurlFREE( $formtype )
{
	global $HelperObj;
	global $plugin_dir;
	global $plugin_url;
	global $config;
	global $post;

//	global $action;
	global $module_options, $module , $isWidget, $assignedto, $check_duplicate, $update_record;
	$plugin_dir=WP_CONST_ULTIMATE_CRM_CPT_DIRECTORY;
	
	$globalvariables = Array( 'plugin_dir' => $plugin_dir , 'plugin_url' => $plugin_url , 'post' => $post , 'module_options' => $module_options , 'module' => $module , 'isWidget' => $isWidget , 'assignedto' => $assignedto , 'check_duplicate' => $check_duplicate , 'update_record' => $update_record , 'HelperObj' => $HelperObj );

	$CapturingProcessClass = new CapturingProcessClass();
	$data = $CapturingProcessClass->CaptureFormFields($globalvariables); 
	
	$smacklog='';

        $HelperObj = new WPCapture_includes_helper();
        $module = $HelperObj->Module;
        $moduleslug = $HelperObj->ModuleSlug;
        $activatedplugin = $HelperObj->ActivatedPlugin;
        $activatedpluginlabel = $HelperObj->ActivatedPluginLabel;

	$config_fields = get_option("smack_{$activatedplugin}_lead_{$formtype}_field_settings");

/* new code for check duplicates before posting 26-11-2012 ENDS */
	if(isset($data) && $data) {

		if(isset($_REQUEST['submitcontactform']))
		{
			$submitcontactform = "smackLogMsg{$_REQUEST['formnumber']}";
		}
		if(isset($_REQUEST['submitcontactformwidget']))
		{
			$submitcontactform = "widgetSmackLogMsg{$_REQUEST['formnumber']}";
		}

/*	$to = "{$config['email']}";
        $subject = 'wp_mail function test';
        $message = 'This is a test of the wp_mail function: wp_mail is working';
      //  $current_user = wp_get_current_user();
       // $admin_email = $current_user->user_email;
        $headers = "From: JEYARAJ <jeyarajj@smackcoders.com>" . "\r\n\\"; */

		$successfulAttemptsOption = get_option( "wp-{$activatedplugin}-contact-{$formtype}-form-attempts" );
			$total=0;
			$success=0;               
		if(!isset($successfulAttemptsOption['total']) && ($successfulAttemptsOption['success'] ))
		{
			$successfulAttemptsOption['total'] = 0;
                        $successfulAttemptsOption['success'] = 0;

		}
		else{       
		 	$total= $successfulAttemptsOption['total'];
                        $success= $successfulAttemptsOption['success'];
	      	}
		$total++;
//		$content.= $data;

	$contenttype = "\n";

	foreach($config_fields['fields'] as $key => $value)
	{
		$config_field_label[$value['name']] = $value['display_label'];
	}

	foreach( $post as $key => $value )
	{
		if(($key != 'formnumber') && ($key != 'submitcontactformwidget') && ($key != 'moduleName') && ($key != "submit" ) && ( $key != "") &&($key != 'submitcontactform'))
		if(isset($config_field_label[$key]))
		{
			$contenttype.= "{$config_field_label[$key]} : $value"."\n";
		}
		else
		{
			$contenttype.= "$key : $value"."\n";
		}
	}

	$config = get_option("wp_{$activatedplugin}_settings");

	if(preg_match("/{$config_fields['module']} entry is added./",$data)) {

			$success++;
		
			$successfulAttemptsOption['total'] = $total;
			$successfulAttemptsOption['success'] = $success;
			$sendmail = mailsend( $config,$activatedplugin,$formtype,$plugin_url, "Success" , $contenttype );
			update_option( "wp-{$activatedplugin}-contact-{$formtype}-form-attempts",$successfulAttemptsOption );
			if( isset($config_fields['enableurlredirection']) && ($config_fields['enableurlredirection'] == "on") && isset($config_fields['redirecturl']) && ( $config_fields['redirecturl'] !== "" ) && is_numeric($config_fields['redirecturl']) )
			{
				wp_redirect(get_permalink($config_fields['redirecturl']));
			}
		//	return("successful");
        
			$smacklog.="<script>";
			if(isset( $config_fields['successmessage'] ) && ($config_fields['successmessage'] != "") )
			{
                        	$smacklog.="document.getElementById('{$submitcontactform}').innerHTML=\"<p class='smack_logmsg' style='color:green;'>{$config_fields['successmessage']}</p>\"";
			}
			else
			{
                        	$smacklog.="document.getElementById('{$submitcontactform}').innerHTML=\"<p class='smack_logmsg' style='color:green;'>Thank you for submitting</p>\"";

			}
	                $smacklog.="</script>";

			return $smacklog;
	}
	else
	{
		$sendmail =  mailsend( $config,$activatedplugin,$formtype,$plugin_url, "Failure" ,$contenttype );
		update_option( "wp-{$activatedplugin}-contact-{$formtype}-form-attempts",$successfulAttemptsOption );
	//	return("failed");
		$smacklog.="<script>";
		if( isset( $config_fields['errormessage'] ) && ($config_fields['errormessage'] != "") )
		{
			$smacklog.="document.getElementById('{$submitcontactform}').innerHTML=\"<p class='smack_logmsg' style='color:red;'>{$config_fields['errormessage']}</p>\"";
		}
		else
		{
			$smacklog.="document.getElementById('{$submitcontactform}').innerHTML=\"<p class='smack_logmsg' style='color:red;'>Submitting Failed</p>\"";
		}
		$smacklog.="</script>";
		$successfulAttemptsOption['total'] = $total;
		$successfulAttemptsOption['success'] = $success;		

		return $smacklog;
	}
	}	
}

function normalContactForm($module, $config_fields, $module_options , $formtype)
{
	global $plugin_dir;
	global $plugin_url;
	$siteurl=site_url();
	global $config;
	global $post;
//	global $action;
	$script='';
	$post=$_POST;
	if( !isset( $_SESSION["generated_forms"] ) )
	{
		$_SESSION["generated_forms"] = 1;
	}
	else
	{
		$_SESSION["generated_forms"]++;
	}

if(isset($_POST['submitcontactform']) && ($_POST['formnumber'] == $_SESSION['generated_forms']) )
	{

		$count_error=0;
		for($i=0; $i<count($config_fields); $i++)
		{
			if(array_key_exists($config_fields[$i]['name'],$_POST))
			{
				if($config_fields[$i]['wp_mandatory'] == 1 && $_POST[$config_fields[$i]['name']] == "" )
				{
//echo "{$config_fields[$i]['name']}	- mandatory++";
					$count_error++;
				}
				elseif($config_fields[$i]['type']['name'] == 'integer' && !preg_match('/^[\d]*$/', $_POST[$config_fields[$i]['name']]) && ($_POST[$config_fields[$i]['name']] != ""))
				{
//echo "{$config_fields[$i]['name']}	- integer++";
					$count_error++;
				}
				elseif($config_fields[$i]['type']['name'] == 'double'  && !preg_match('/^([\d]{1,8}.?[\d]{1,2})?$/', $_POST[$config_fields[$i]['name']]) && ($_POST[$config_fields[$i]['name']] != ""))
				{
//echo "{$config_fields[$i]['name']}	- double++";
					$count_error++;
				}
				elseif($config_fields[$i]['type']['name'] == 'currency'  && !preg_match('/^([\d]{1,8}.?[\d]{1,2})?$/', $_POST[$config_fields[$i]['name']]) && ($_POST[$config_fields[$i]['name']] != ""))
				{
//echo "{$config_fields[$i]['name']}	- currency++";
					$count_error++;
				}
				elseif($config_fields[$i]['type']['name'] == 'email' && (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',$_POST[$config_fields[$i]['name']]) && ($_POST[$config_fields[$i]['name']] != "")))
				{
//echo "{$config_fields[$i]['name']}      - email++";
					$count_error++;
				}
				elseif($config_fields[$i]['type']['name'] == 'url' && (!preg_match('/^((http:|ftp:|https:)\/\/[a-z0-9A-Z]+\.[a-z0-9-]+\.[a-z0-9-]{2,4})/',$_POST[$config_fields[$i]['name']]) && ($_POST[$config_fields[$i]['name']] != "")))
				{
					if($_POST[$config_fields[$i]['name']] == "")
					{
					}
					else
					{
//echo "{$config_fields[$i]['name']}    -  url++";
					$count_error++;
					}
					
				}
				elseif($config_fields[$i]['type']['name'] == 'multipicklist' )
				{
					$concat = "";
					for( $index=0; $index<count($_POST[$config_fields[$i]['name']]); $index++)
					{
					$concat.=$_POST[$config_fields[$i]['name']][$index]." |##| ";

					}
					$concat=substr($concat,0,-6);
					$post[$config_fields[$i]['name']]=$concat;

				}
				elseif($config_fields[$i]['type']['name'] == 'phone' && !preg_match('/^[2-9]{1}[0-9]{2}-[0-9]{3}-[0-9]{4}$/', $_POST[$config_fields[$i]['name']]))
				{
					
				}
			}
		}

		if(isset($_POST['recaptcha_response_field']))
		{
			$privatekey = $config['smack_private_key'];

			# the response from reCAPTCHA
			$resp = null;
			# the error code from reCAPTCHA, if any
			$error = null;
			$resp = recaptcha_check_answer ($privatekey,
						       	$_SERVER["REMOTE_ADDR"],
						      	$_POST["recaptcha_challenge_field"],
						      	$_POST["recaptcha_response_field"]);
			//print($_SERVER["REMOTE_ADDR"]."<br/>".$_POST["recaptcha_challenge_field"]."<br/>".$_POST["recaptcha_response_field"]);
			if (!$resp->is_valid) {
				$count_error++;
			}
		}

	}

	$content = "<form id='contactform{$_SESSION["generated_forms"]}' name='contactform{$_SESSION["generated_forms"]}' method='post'>";
	$content.= "<table>";
	$content.= "<div id='smackLogMsg{$_SESSION["generated_forms"]}'></div>";
	$content1="";
	$count_selected=0;

		for($i=0; $i<count($config_fields);$i++) {

			$content2 = "";
			$fieldtype = $config_fields[$i]['type']['name'];
			if( $config_fields[$i]['publish']==1)
			{

				if($config_fields[$i]['wp_mandatory']==1)
				{
					$content1.="<tr><td>".$config_fields[$i]['display_label']." *</td>";
					
					$M=' mandatory';
				}
				else
				{
					$content1.="<tr><td>".$config_fields[$i]['display_label']."</td>";
					$M='';
				}
				if($fieldtype == "string")
				{
					$content1.="<td><input type='text' class='string{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}' value='";
					if(isset($_POST[$config_fields[$i]['name']]) && ($_POST['formnumber'] == $_SESSION['generated_forms']) && $count_error!=0) 
						$content1 .= $_POST[$config_fields[$i]['name']];
					else
						$content1 .= '';
$content1 .= "'/><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'>";
if(isset($_POST['submitcontactform']) && ($_POST['formnumber'] == $_SESSION['generated_forms']) )
{
	if($config_fields[$i]['wp_mandatory'] == 1 && $_POST[$config_fields[$i]['name']] == "" )
	{
		$content1 .="This field is mandatory";
	}
}
	$content1 .="</span></td></tr>";
					$count_selected++;
				}
				elseif($fieldtype == "text")
				{
					$content1.="<td><textarea class='textarea{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}'></textarea><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'></span></td></tr>";
					$count_selected++;
				}

                                elseif($fieldtype == 'radioenum')
                                {
                                        $content1 .= "<td>";
                                        $picklist_count = count($config_fields[$i]['type']['picklistValues']);
                                        for($j=0 ; $j<$picklist_count ; $j++)
                                        {
                                                $content2.="<input type='radio' name='{$config_fields[$i]['name']}' value='{$config_fields[$i]['type']['picklistValues'][$j]['label']}'>{$config_fields[$i]['type']['picklistValues'][$j]['value']}";
                                        }
                                        $content1.=$content2;
                                        $content1.="<script>document.getElementById('{$config_fields[$i]['name']}').value='{$_POST[$config_fields[$i]['name']]}'</script>";
                                        $content1 .= "<br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'></span>";
                                        $content1 .= "</td>";
                                        $count_selected++;
                                }
				elseif($fieldtype == 'multipicklist')
				{
					$picklist_count = count($config_fields[$i]['type']['picklistValues']);
					$content1.="<td><select class='multipicklist{$M}' name='{$config_fields[$i]['name']}[]' multiple='multiple' id='{$module_options}_{$config_fields[$i]['name']}' >";
					for($j=0 ; $j<$picklist_count ; $j++)
					{
						$content2.="<option id='{$config_fields[$i]['name']}' value='{$config_fields[$i]['type']['picklistValues'][$j]['value']}'>{$config_fields[$i]['type']['picklistValues'][$j]['label']}</option>";
					}
					$content1.=$content2;
					$content1.="</select><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'></span></td></tr>";
//					$content1.="<script>document.getElementById({$config_fields[$i]['name']}).value={$_POST[$config_fields[$i]['name']]}</script>";
					$count_selected++;
				}
				elseif($fieldtype == 'picklist')
				{
					$picklist_count = count($config_fields[$i]['type']['picklistValues']);
					$content1.="<td><select class='picklist{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}'  value='";
					if(isset($_POST[$config_fields[$i]['name']]) && ($_POST['formnumber'] == $_SESSION['generated_forms']) && $count_error!=0)
                                                $content1 .= $_POST[$config_fields[$i]['name']];
                                        else
                                                $content1 .= '';

$content1.="'>";
					for($j=0 ; $j<$picklist_count ; $j++)
					{
						$content2.="<option id='{$config_fields[$i]['name']}' value='{$config_fields[$i]['type']['picklistValues'][$j]['value']}'>{$config_fields[$i]['type']['picklistValues'][$j]['label']}</option>";
					}
					$content1.=$content2;
					$content1.="</select><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'></span></tr>";

					$count_selected++;
				}
				elseif($fieldtype == 'integer')
				{
					$content1.="<td><input type='text' class='integer{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}' value='";
					if(isset($_POST[$config_fields[$i]['name']]) && ($_POST['formnumber'] == $_SESSION['generated_forms']) && $count_error!=0)
                                                $content1 .= $_POST[$config_fields[$i]['name']];
                                        else
                                                $content1 .= '';
$content1.="'/><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'>";
if($config_fields[$i]['wp_mandatory'] == 1 && $_POST[$config_fields[$i]['name']] == "" )
{
	$content1 .="This field is mandatory";
}
elseif( isset($_POST[$config_fields[$i]['name']]) && $config_fields[$i]['type']['name'] == 'integer' && !preg_match('/^[\d]*$/', $_POST[$config_fields[$i]['name']]) && ($_POST[$config_fields[$i]['name']] != ""))
{
	$content1 .="This field is integer";
}
	$content1 .= "</span></td></tr>";
					$count_selected++;
				}
				elseif($fieldtype == 'double')
				{
					$content1.="<td><input type='text' class='double{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}' value='{$_POST[$config_fields[$i]['name']]}'/><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'></span></td></tr>";
					$count_selected++;
				}
				elseif($fieldtype == 'currency')
				{
					$content1.="<td><input type='text' class='currency{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}' value='";
					if(isset($_POST[$config_fields[$i]['name']]) && ($_POST['formnumber'] == $_SESSION['generated_forms']) && $count_error!=0)
                                                $content1 .= $_POST[$config_fields[$i]['name']];
                                        else
                                                $content1 .= '';
$content1.="'/><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'>";
if($config_fields[$i]['wp_mandatory'] == 1 && $_POST[$config_fields[$i]['name']] == "" )
{
	$content1 .="This field is mandatory";
}
elseif(  isset($_POST[$config_fields[$i]['name']]) && $config_fields[$i]['type']['name'] == 'currency'  && !preg_match('/^([\d]{1,8}.?[\d]{1,2})?$/', $_POST[$config_fields[$i]['name']])&& ($_POST[$config_fields[$i]['name']] != ""))
{
	$content1 .="This field is integer";
}
	$content1 .= "</span></td></tr>";
					$count_selected++;
				}
				elseif($fieldtype == 'email')
				{
					$content1.="<td><input type='text' class='email{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}' value='";
					if(isset($_POST[$config_fields[$i]['name']]) && ($_POST['formnumber'] == $_SESSION['generated_forms']) && $count_error!=0)
                                                $content1 .= $_POST[$config_fields[$i]['name']];
                                        else
                                                $content1 .= '';

$content1.="'/><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'>";

if($config_fields[$i]['wp_mandatory'] == 1 && $_POST[$config_fields[$i]['name']] == "" )
{
	$content1 .="This field is mandatory";
}
elseif( isset($_POST[$config_fields[$i]['name']]) && $config_fields[$i]['type']['name'] == 'email' && (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',$_POST[$config_fields[$i]['name']]) && ($_POST[$config_fields[$i]['name']] != "")))
{	
	$content1 .="Invalid Email";
}

	$content1 .="</span></td></tr>";
					$count_selected++;
				}
				elseif($fieldtype == 'date')
				{
?>
				<script> 
					jQuery(function() {
						jQuery( "#<?php echo $module_options.'_'.$config_fields[$i]['name'].'_'.$_SESSION['generated_forms'];?>" ).datepicker({
							dateFormat: "yy-mm-dd",
							changeMonth: true,
							changeYear: true,
							showOn: "button",
							buttonImage: "<?php echo $plugin_url; ?>/images/calendar.gif",
							buttonImageOnly: true,
							yearRange: '1900:2050'
						});
					});
				</script>
<?php
					$content1.='<td><input type="text" class="date'.$M.'" name='.$config_fields[$i]['name'].' id="'.$module_options.'_'.$config_fields[$i]['name'].'_'.$_SESSION['generated_forms'].'" value="';
					if(isset($_POST[$config_fields[$i]['name']]) && ($_POST['formnumber'] == $_SESSION['generated_forms']) && $count_error!=0)
                                                $content1 .= $_POST[$config_fields[$i]['name']];
                                        else
                                                $content1 .= '';

$content1.='" readonly="readonly" /> <span class="smack_field_error" id="'.$config_fields[$i]['name'].'error'.$_SESSION["generated_forms"].'"></span></td></tr>';

					$count_selected++;
				}
				elseif($fieldtype == 'boolean')
				{
					$content1.='<td><input type="checkbox'.$M.'" class="boolean" name='.$config_fields[$i]['name'].' id="'.$module_options.'_'.$config_fields[$i]['name'].'" value="on"/><br/><span class="smack_field_error" id="'.$config_fields[$i]['name'].'error'.$_SESSION["generated_forms"].'"></span></td></tr>';
					$count_selected++;
				}
				elseif($fieldtype == 'url')
				{
					$content1.="<td><input type='text' class='url{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}' value='";
					if(isset($_POST[$config_fields[$i]['name']]) && ($_POST['formnumber'] == $_SESSION['generated_forms']) && $count_error!=0)
                                                $content1 .= $_POST[$config_fields[$i]['name']];
                                        else
                                                $content1 .= '';
$content1.="'/><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'>";
if(isset($_POST['submitcontactform']) && ($_POST['formnumber'] == $_SESSION['generated_forms']) )
{
	if($config_fields[$i]['wp_mandatory'] == 1 && $_POST[$config_fields[$i]['name']] == "" )
	{
		$content1 .="This field is mandatory";
	}
	elseif(  isset($_POST[$config_fields[$i]['name']]) && $config_fields[$i]['type']['name'] == 'url' && (!preg_match('/^((http:|ftp:|https:)\/\/[a-z0-9A-Z]+\.[a-z0-9-]+\.[a-z0-9-]{2,4})/',$_POST[$config_fields[$i]['name']]) && ($_POST[$config_fields[$i]['name']] != "")))
	{
		$content1 .="Invalid URL";
	}
}
		$content1 .="</span></td></tr>";	
				$count_selected++;
				}
				elseif($fieldtype == 'phone')
				{
					$content1.="<td><input type='text' class='phone{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}' value='";
				if(isset($_POST[$config_fields[$i]['name']]) && ($_POST['formnumber'] == $_SESSION['generated_forms']) && $count_error!=0)
                                                $content1 .= $_POST[$config_fields[$i]['name']];
                                        else
                                                $content1 .= '';
$content1.="'/><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'>";
if(isset($_POST['submitcontactform']) && ($_POST['formnumber'] == $_SESSION['generated_forms']) )
{
	if($config_fields[$i]['wp_mandatory'] == 1 && $_POST[$config_fields[$i]['name']] == "" )
	{
		$content1 .="This field is mandatory";
	}
/*
elseif($config_fields[$i]['type']['name'] == 'phone' && !preg_match('/^[2-9]{1}[0-9]{2}-[0-9]{3}-[0-9]{4}$/', $_POST[$config_fields[$i]['name']]) && ($_POST[$config_fields[$i]['name']] != ""))
	{
		$content1 .="Invalid no";
	}
*/
}
		$content1 .="</span></td></tr>";
				$count_selected++;
				}
				else
				{
					$content1.="<td><input type='text' class='others{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}' value='{$_POST[$config_fields[$i]['name']]}'/><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'></span></td></tr>";
					$count_selected++;
				}
			}
		}

	if($count_selected==0)
	{
		$content.="<h3>You have selected no fields</h3>";
	}
	else
	{
		$content.=$content1;
	}
	$content.="<tr><td></td><td>";
	if($count_selected==0)
	{
	}
	else
	{
		$captha_config = get_option("wp_captcha_settings");
		if($captha_config['smack_recaptcha']=='yes')
		{
			require_once(WP_CONST_ULTIMATE_CRM_CPT_DIRECTORY."captcha/recaptchalib.php");
			$publickey = $config['smack_public_key']; 
			$content.="<div style='color:red' id='recaptcha_response_field_error{$_SESSION["generated_forms"]}'></div>";
			$content.=recaptcha_get_html($publickey);
		}
		$content.="<p class='contact-form-comment'>
		<p class='form-submit'>";
		$content.="<input type='hidden' name='formnumber' value='{$_SESSION['generated_forms']}'>";
		$content.="<input type='hidden' name='submitcontactform' value='submitcontactform{$_SESSION['generated_forms']}'/>";
		$content.='<input type="submit" value="Submit" id="submit" name="submit"></p>';
	}


	$content.="</td></tr></table>";
	$content.="<input type='hidden' value='".$module."' name='moduleName' /></p></form>";
	if(isset($_POST['submitcontactform']) && ($_POST['formnumber'] == $_SESSION['generated_forms']) )
	{
		if($count_error==0)
		{
			$content.= callCurlFREE( $formtype );
		}
	}
	return $content;
}

function widgetContactForm($module, $config_fields, $module_options , $formtype)
{
global $plugin_dir;
global $plugin_url;
$siteurl=site_url();
global $config;
//global $action;
global $post;
$post=array();
$post=$_POST;

        if( !isset( $_SESSION["generated_forms"] ) )
        {

                $_SESSION["generated_forms"] = 1;
        }
        else
        {
                $_SESSION["generated_forms"]++;
        }

if(isset($_POST['submitcontactformwidget']) && ($_POST['submitcontactformwidget'] == 'submitwidgetcontactform'.$_SESSION['generated_forms'])  && ($_POST['formnumber'] == $_SESSION['generated_forms']) )
	
{
		$content = "";
		$script = "";
		$count_error=0;
		for($i=0; $i<count($config_fields); $i++)
		{
			if(array_key_exists($config_fields[$i]['name'],$_POST))
			{
				if($config_fields[$i]['wp_mandatory'] == 1 && $_POST[$config_fields[$i]['name']] == "")
				{
					$script="<script> oFormObject = document.forms['contactform{$_SESSION["generated_forms"]}']; oformElement = oFormObject.elements['".$config_fields[$i]['name']."']; document.getElementById('".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}').innerHTML= '<div style=\'color:red;\'>This field is mandatory</div>'; </script>";
					$content .= $script;
					$script="";
					$count_error++;
				}
				elseif(  isset($_POST[$config_fields[$i]['name']]) && $config_fields[$i]['type']['name'] == 'integer' && !preg_match('/^[\d]*$/', $_POST[$config_fields[$i]['name']]))
				{
					$script="<script>oFormObject = document.forms['contactform{$_SESSION["generated_forms"]}'];oformElement = oFormObject.elements['".$config_fields[$i]['name']."']; document.getElementById('".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}').innerHTML='enter valid ".$config_fields[$i]['name']."'; </script>";
					$content .= $script;
					$script="";
					$count_error++;
				}
				elseif($config_fields[$i]['type']['name'] == 'double'  && !preg_match('/^([\d]{1,8}.?[\d]{1,2})?$/', $_POST[$config_fields[$i]['name']]) )
				{
					$script="<script>oFormObject = document.forms['contactform{$_SESSION["generated_forms"]}'];oformElement = oFormObject.elements['".$config_fields[$i]['name']."']; document.getElementById('".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}').innerHTML='enter valid ".$config_fields[$i]['name']."';</script>";
					$content .= $script;
					$count_error++;
				}
				elseif($config_fields[$i]['type']['name'] == 'currency'  && !preg_match('/^([\d]{1,8}.?[\d]{1,2})?$/', $_POST[$config_fields[$i]['name']]) )
				{
					$script="<script>oFormObject = document.forms['contactform{$_SESSION["generated_forms"]}'];oformElement = oFormObject.elements['".$config_fields[$i]['name']."']; document.getElementById('".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}').innerHTML='enter valid ".$config_fields[$i]['name']."';</script>";
					$content .= $script;
					$count_error++;
				}
				elseif($config_fields[$i]['type']['name'] == 'email' && (!preg_match('/^([a-z0-9_\+-]+(\.[a-z0-9_\+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]{2,4}))?$/',$_POST[$config_fields[$i]['name']])))
				{
					$script="<script>oFormObject = document.forms['contactform{$_SESSION["generated_forms"]}'];oformElement = oFormObject.elements['".$config_fields[$i]['name']."']; document.getElementById('".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}').innerHTML='<font color=\'red\'>Enter valid ".$config_fields[$i]['name']."</font>';</script>";
					$content .= $script;
					$count_error++;
				}
				elseif($config_fields[$i]['type']['name'] == 'phone' && !preg_match('/^[2-9]{1}[0-9]{2}-[0-9]{3}-[0-9]{4}$/', $_POST[$config_fields[$i]['name']]))
				{
					/*if(preg_match('/^\([2-9]{1}[0-9]{2}\)[0-9]{3}-[0-9]{4}$/', $_POST[$config_fields[$i]['name']]))
					{
					}
					elseif(preg_match('/^[2-9]{1}[0-9]{2}[0-9]{3}[0-9]{4}$/', $_POST[$config_fields[$i]['name']]))
					{
					}
					else
					{
					$script="<script>oFormObject = document.forms['contactform{$_SESSION["generated_forms"]}'];oformElement = oFormObject.elements['".$config_fields[$i]['name']."']; document.getElementById('".$config_fields[$i]['name']."error').innerHTML='enter valid ".$config_fields[$i]['name']." number'; </script>";
					$content .= $script;
					$script="";
					$count_error++;
					}*/
				}
				elseif($config_fields[$i]['type']['name'] == 'multipicklist' )
				{
$concat ="";
for( $index=0; $index<count($_POST[$config_fields[$i]['name']]); $index++)
{
$concat.=$_POST[$config_fields[$i]['name']][$index]." |##| ";

}
$concat=substr($concat,0,-6);
$post[$config_fields[$i]['name']]=$concat;

				}
				elseif($config_fields[$i]['type']['name'] == 'url' && (!preg_match('/^((http:|ftp:|https:)\/\/[a-z0-9A-Z]+\.[a-z0-9-]+\.[a-z0-9-]{2,4})/',$_POST[$config_fields[$i]['name']])))
				{
					if($_POST[$config_fields[$i]['name']] == "")
					{
					}
					else
					{
						$script="<script>oFormObject = document.forms['contactform{$_SESSION["generated_forms"]}'];oformElement = oFormObject.elements['".$config_fields[$i]['name']."']; document.getElementById('".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}').innerHTML='enter valid ".$config_fields[$i]['name']."'</script>";
					$count_error++;
					}
					$content .= $script;
				}
			}
		}
		if(isset($_POST['recaptcha_response_field']))
		{
			$privatekey = $config['smack_private_key'];

			# the response from reCAPTCHA
			$resp = null;
			# the error code from reCAPTCHA, if any
			$error = null;
			$resp = recaptcha_check_answer ($privatekey,
						       	$_SERVER["REMOTE_ADDR"],
						      	$_POST["recaptcha_challenge_field"],
						      	$_POST["recaptcha_response_field"]);
			//print($_SERVER["REMOTE_ADDR"]."<br/>".$_POST["recaptcha_challenge_field"]."<br/>".$_POST["recaptcha_response_field"]);
			if (!$resp->is_valid) {
			  // What happens when the CAPTCHA was entered incorrectly
			  //die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .      "(reCAPTCHA said: " . $resp->error . ")");
				$script="<script>oFormObject = document.forms['contactform{$_SESSION["generated_forms"]}'];oformElement = oFormObject.elements['recaptcha_response_field']; document.getElementById('recaptcha_response_field_error{$_SESSION["generated_forms"]}').innerHTML='enter valid captcha value' </script>";
				$content .= $script;
				$count_error++;
			}
		}
	}

	$content = "<form id='contactform{$_SESSION["generated_forms"]}' name='contactform{$_SESSION["generated_forms"]}' method='post'>";
	$content.= "<div id='widgetSmackLogMsg{$_SESSION["generated_forms"]}'></div>";
	$content1="";
	$count_selected=0;

		for($i=0; $i<count($config_fields);$i++) {

			if(isset( $_POST[$config_fields[$i]['name']] ) && ($_POST['formnumber'] == $_SESSION['generated_forms']) )
			{
				$field_value = $_POST[$config_fields[$i]['name']]; 
			}
			else
			{
				$field_value = "";
			}

			$content2 = "";
			$fieldtype = $config_fields[$i]['type']['name'];
			if($config_fields[$i]['publish']==1)
			{
				if($config_fields[$i]['wp_mandatory']==1)
				{
					$content1.=$config_fields[$i]['display_label']." *";
					$M=' mandatory';
				}
				else
				{
					$content1.="<label for='".$config_fields[$i]['display_label']."'>".$config_fields[$i]['display_label']."</label>";
					$M='';
				}
				if($fieldtype == "string")
				{
					$content1.='<div class="div_texbox">'."<input class='smack_widget_textbox' type='text' class='string{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}' value='";
					if(isset($_POST[$config_fields[$i]['name']]) && ($_POST['formnumber'] == $_SESSION['generated_forms']) && $count_error!=0) 
					$content1 .= $field_value;
					else
						$content1 .= '';
$content1 .= "'/><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'>";
if(isset($_POST['submitcontactformwidget']) && ($_POST['submitcontactformwidget'] == 'submitwidgetcontactform'.$_SESSION['generated_forms'])  && ($_POST['formnumber'] == $_SESSION['generated_forms']) )
{
	if($config_fields[$i]['wp_mandatory'] == 1 && $_POST[$config_fields[$i]['name']] == "")
	{
		$content1 .="This field is mandatory";
	}
}
		$content1 .="</span></div>";
					$count_selected++;
				}
				elseif($fieldtype == "text")
				{
					$content1.='<div class="div_texbox">'."<textarea class='textarea{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}'></textarea><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'></span></div>";
					$count_selected++;
				}
                                elseif($fieldtype == 'radioenum')
                                {
                                        $content1 .= '<div class="div_texbox">';
                                        $picklist_count = count($config_leads_fields[$i]['type']['picklistValues']);
                                        for($j=0 ; $j<$picklist_count ; $j++)
                                        {
                                                $content2.="<input type='radio' name='{$config_leads_fields[$i]['name']}' value='{$config_leads_fields[$i]['type']['picklistValues'][$j]['label']}'>{$config_leads_fields[$i]['type']['picklistValues'][$j]['value']}<br/>";
                                        }
                                        $content1.=$content2;
                                        $content1 .= "<br/><span class='smack-field_error' id='".$config_leads_fields[$i]['name']."error{$_SESSION["generated_forms"]}'></span>";
                                        $content1 .= "</div>";
//                                        $content1.="<script>document.getElementById('{$config_leads_fields[$i]['name']}').value='{$field_value}'</script>";
                                        $count_selected++;
                                }
				elseif($fieldtype == 'multipicklist')
				{

					$picklist_count = count($config_fields[$i]['type']['picklistValues']);
					$content1.='<div class="div_texbox">'."<select class='multipicklist{$M}' name='{$config_fields[$i]['name']}[]' multiple='multiple' id='{$module_options}_{$config_fields[$i]['name']}'  value='{$field_value}'>";
					for($j=0 ; $j<$picklist_count ; $j++)
					{
						$content2.="<option id='{$config_fields[$i]['name']}' value='{$config_fields[$i]['type']['picklistValues'][$j]['value']}'>{$config_fields[$i]['type']['picklistValues'][$j]['label']}</option>";
					}
					$content1.=$content2;
					$content1.="</select><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'></span></div>";
					//$content1.="<script>document.getElementById({$config_fields[$i]['name']}).value={$field_value}</script>";
					$count_selected++;
				}
				elseif($fieldtype == 'picklist')
				{
					$picklist_count = count($config_fields[$i]['type']['picklistValues']);
					$content1.='<div class="div_texbox">'."<select class='picklist{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}'  value='";
					if(isset($_POST[$config_fields[$i]['name']]) && ($_POST['formnumber'] == $_SESSION['generated_forms']) && $count_error!=0)
                                                $content1 .= $field_value;
                                        else
                                                $content1 .= '';

$content1.="'>";
					for($j=0 ; $j<$picklist_count ; $j++)
					{
						$content2.="<option id='{$config_fields[$i]['name']}' value='{$config_fields[$i]['type']['picklistValues'][$j]['value']}'>{$config_fields[$i]['type']['picklistValues'][$j]['label']}</option>";
					}
					$content1.=$content2;
					$content1.="</select><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'></span></div>";
/*
					$content1.="<script>document.getElementById('{$config_fields[$i]['name']}').value='";
					if(isset($_POST[$config_fields[$i]['name']]) && ($_POST['formnumber'] == $_SESSION['generated_forms']) && $count_error!=0)
                                                $content1 .= $field_value;
                                        else
                                                $content1 .= '';
					$content1.="'</script>";
*/
					$count_selected++;
				}
				elseif($fieldtype == 'integer')
				{
					$content1.='<div class="div_texbox">'."<input class='smack_widget_textbox' type='text' class='integer{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}' value='";
				if(isset($_POST[$config_fields[$i]['name']]) && ($_POST['formnumber'] == $_SESSION['generated_forms']) && $count_error!=0)
                                                $content1 .= $field_value;
                                        else
                                                $content1 .= '';
$content1 .="'/><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'>";
if($config_fields[$i]['wp_mandatory'] == 1 && $_POST[$config_fields[$i]['name']] == "")
	{
		$content1 .="This field is mandatory";
	}

elseif( isset($_POST[$config_fields[$i]['name']]) && $config_fields[$i]['type']['name'] == 'integer' && !preg_match('/^[\d]*$/', $_POST[$config_fields[$i]['name']]))
{
	$content1 .="This field is integer";
}
	$content1 .="</span></div>";
					$count_selected++;
				}
				elseif($fieldtype == 'double')
				{
					$content1.='<div class="div_texbox">'."<input class='smack_widget_textbox' type='text' class='double{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}' value='{$field_value}'/><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'></span></div>";
					$count_selected++;
				}
				elseif($fieldtype == 'currency')	
				{
					$content1.='<div class="div_texbox">'."<input class='smack_widget_textbox' type='text' class='currency{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}' value='";
				if(isset($_POST[$config_fields[$i]['name']]) && ($_POST['formnumber'] == $_SESSION['generated_forms']) && $count_error!=0)
                                                $content1 .= $field_value;
                                        else
                                                $content1 .= '';
$content1 .="'/><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'>";
if($config_fields[$i]['wp_mandatory'] == 1 && $_POST[$config_fields[$i]['name']] == "")
	{
		$content1 .="This field is mandatory";
	}
elseif( isset($_POST[$config_fields[$i]['name']]) && $config_fields[$i]['type']['name'] == 'currency'  && !preg_match('/^([\d]{1,8}.?[\d]{1,2})?$/', $_POST[$config_fields[$i]['name']]) )
{
	$content1 .="This field is integer";
}
		$content1 .="</span></div>";
					$count_selected++;
				}
				elseif($fieldtype == 'email')
				{
					$content1.='<div class="div_texbox">'."<input class='smack_widget_textbox' type='text' class='email{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}' value='";
				if(isset($_POST[$config_fields[$i]['name']]) && ($_POST['formnumber'] == $_SESSION['generated_forms']) && $count_error!=0)
                                                $content1 .= $field_value;
                                        else
                                                $content1 .= '';
$content1 .="'/><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'>";
if($config_fields[$i]['wp_mandatory'] == 1 && $_POST[$config_fields[$i]['name']] == "")
	{
		$content1 .="This field is mandatory";
	}
elseif(  isset($_POST[$config_fields[$i]['name']]) && $config_fields[$i]['type']['name'] == 'email' && (!preg_match('/^([a-z0-9_\+-]+(\.[a-z0-9_\+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]{2,4}))?$/',$_POST[$config_fields[$i]['name']]) && ($_POST[$config_fields[$i]['name']] != "") ))
{	
	$content1 .="Invalid Email";
}
	$content1 .="</span></div>";		
			$count_selected++;
				}
				elseif($fieldtype == 'date')
				{
?>
				<script> 
					jQuery(function() {
						jQuery( "#<?php echo $module_options.'_'.$config_fields[$i]['name'].'_'.$_SESSION['generated_forms'];?>" ).datepicker({
							dateFormat: "yy-mm-dd",
							changeMonth: true,
							changeYear: true,
							showOn: "button",
							buttonImage: "<?php echo $plugin_url; ?>/images/calendar.gif",
							buttonImageOnly: true
						});
					});
				</script>
<?php
					$content1.='<div class="div_texbox">'.'<input type="text" class="date'.$M.' smack_widget_textbox_date_picker" name='.$config_fields[$i]['name'].' id="'.$module_options.'_'.$config_fields[$i]['name'].'_'.$_SESSION['generated_forms'].'" value="';
					 if(isset($_POST[$config_fields[$i]['name']]) && ($_POST['formnumber'] == $_SESSION['generated_forms']) && $count_error!=0)
                                                $content1 .= $_POST[$config_fields[$i]['name']];
                                        else
                                                $content1 .= '';
$content1 .='" readonly="readonly" /> <span class="smack_field_error" id="'.$config_fields[$i]['name'].'error'.$_SESSION["generated_forms"].'"></span></div>';
					$count_selected++;
				}
				elseif($fieldtype == 'boolean')
				{
					$content1.='<div class="div_texbox">'.'<input type="checkbox'.$M.'" class="boolean" name='.$config_fields[$i]['name'].' id="'.$module_options.'_'.$config_fields[$i]['name'].'" value="on"/><br/><span class="smack_field_error" id="'.$config_fields[$i]['name'].'error'.$_SESSION["generated_forms"].'"></span><div>';
					$count_selected++;
				}
				elseif($fieldtype == 'url')
				{
					$content1.='<div class="div_texbox">'."<input class='smack_widget_textbox' type='text' class='url{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}' value='";
				if(isset($_POST[$config_fields[$i]['name']]) && ($_POST['formnumber'] == $_SESSION['generated_forms']) && $count_error!=0)
                                                $content1 .= $field_value;
                                        else
                                                $content1 .= '';
$content1 .="'/><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'>";
if(isset($_POST['submitcontactformwidget']) && ($_POST['submitcontactformwidget'] == 'submitwidgetcontactform'.$_SESSION['generated_forms'])  && ($_POST['formnumber'] == $_SESSION['generated_forms']) )
{
	if($config_fields[$i]['wp_mandatory'] == 1 && $_POST[$config_fields[$i]['name']] == "")
	{
		$content1 .="This field is mandatory";
	}

	elseif( isset($_POST[$config_fields[$i]['name']]) && $config_fields[$i]['type']['name'] == 'url' && (!preg_match('/^((http:|ftp:|https:)\/\/[a-z0-9A-Z]+\.[a-z0-9-]+\.[a-z0-9-]{2,4})/',$_POST[$config_fields[$i]['name']]))  && ($_POST[$config_fields[$i]['name']] != "") )
	{
		$content1 .="Invalid URL";
	}
}
		$content1 .="</span></div>";
				$count_selected++;
				}
				elseif($fieldtype == 'phone')
				{
					$content1.='<div class="div_texbox">'."<input class='smack_widget_textbox' type='text' class='phone{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}' value='";
				if(isset($_POST[$config_fields[$i]['name']]) && ($_POST['formnumber'] == $_SESSION['generated_forms']) && $count_error!=0)
                                                $content1 .= $field_value;
                                        else
                                                $content1 .= '';
$content1 .="'/><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'>";
if(isset($_POST['submitcontactformwidget']) && ($_POST['submitcontactformwidget'] == 'submitwidgetcontactform'.$_SESSION['generated_forms'])  && ($_POST['formnumber'] == $_SESSION['generated_forms']) )
{
	if($config_fields[$i]['wp_mandatory'] == 1 && $_POST[$config_fields[$i]['name']] == "")
	{
		$content1 .="This field is mandatory";
	}
}
		$content1 .="</span></div>";
					$count_selected++;
				}
				else
				{
					$content1.='<div class="div_texbox">'."<input class='smack_widget_textbox' type='text' class='others{$M}' name='{$config_fields[$i]['name']}' id='{$module_options}_{$config_fields[$i]['name']}' value='{$field_value}'/><br/><span class='smack_field_error' id='".$config_fields[$i]['name']."error{$_SESSION["generated_forms"]}'></span></div>";
					$count_selected++;
				}
			}
		}

	if($count_selected==0)
	{
		$content.="<h3>You have selected no fields</h3>";
	}
	else
	{
		$content.=$content1;
	}
	if($count_selected==0)
	{
	}
	else
	{
                $captha_config = get_option("wp_captcha_settings");
                if($captha_config['smack_recaptcha']=='yes')
		{
			require_once(WP_CONST_ULTIMATE_CRM_CPT_DIRECTORY."captcha/recaptchalib.php");
			$publickey = $config['smack_public_key']; 
			$content.="<div id='recaptcha_response_field_error{$_SESSION["generated_forms"]}'></div>";
			$content.=recaptcha_get_html($publickey);
		}
		$content.="<p class='contact-form-comment'>
		<p class='form-submit'>";
		$content.="<input type='hidden' name='formnumber' value='{$_SESSION['generated_forms']}'>";
		$content.="<input type='hidden' name='submitcontactformwidget' value='submitwidgetcontactform{$_SESSION["generated_forms"]}'/>";
		$content.='<input class="smack_widget_buttons" type="submit" value="Submit" id="submit" name="submit"></p>';
	}

	if(isset($_POST['submitcontactformwidget']) && ($_POST['submitcontactformwidget'] == 'submitwidgetcontactform'.$_SESSION['generated_forms'])  && ($_POST['formnumber'] == $_SESSION['generated_forms']) )
	{

		if($count_error==0)
		{
			$content .= callCurlFREE( $formtype );
		}
	}
	$content.="<input type='hidden' value='".$module."' name='moduleName' /></p></form>";

	
return $content;
}

function getip()
                {
                $ip = $_SERVER['REMOTE_ADDR'];
                return $ip;
                }

function mailsend( $config,$activatedplugin,$formtype,$plugin_url,$data,$contenttype )
{

        $to = "{$config['email']}";
        $subject = 'Form Details';
        $message = "Shortcode : " . "[$activatedplugin-web-form type='$formtype']" ."\n" . "URL: " . $plugin_url ."\n" . "Type:".$formtype ."\n". "Form Status:".$data . "\n" . "FormFields and Values:"."\n".$contenttype ."\n"."User IP:".getip();
	$current_user = wp_get_current_user();
       	$admin_email = $current_user->user_email;
        $headers = "From: Administrator <$admin_email>" . "\r\n\\";
        if(isset($config['smack_email']) && $config['smack_email'] == 'on')
        {
	      wp_mail( $to, $subject, $message,$headers );
        }


}


?>
