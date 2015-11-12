<?php

	$skinnyObj = CallManageShortcodesCrmObj::getInstance();

	$HelperObj = new WPCapture_includes_helper;
	$module = $HelperObj->Module;
	$moduleslug = $HelperObj->ModuleSlug;
	$activatedplugin = $HelperObj->ActivatedPlugin;
	$activatedpluginlabel = $HelperObj->ActivatedPluginLabel;

	$plugin_url= WP_CONST_ULTIMATE_CRM_CPT_DIRECTORY;
	$onAction= 'onCreate';
	$siteurl= site_url();

	$options = "smack_{$activatedplugin}_fields_shortcodes";
	$url = site_url();
/*
	$FunctionsObj = new Functions();
	$users_list = $FunctionsObj->getUsersList();

	if(isset($users_list['user_name']))
	for($user_index = 0; $user_index < count($users_list['user_name']); $user_index++)
	{
		$users[$users_list['id'][$user_index]] = $users_list['first_name'][$user_index].' '.$users_list['last_name'][$user_index];//$users_list['user_name'][$user_index];
	}
*/

	$module = "Leads";

	$content="";
	$content1="";
	$content .= "

	<h3 id='innerheader'>List of Shortcodes</h3>
	<div class='wp-common-crm-content'>
	<table style='margin-right:20px;margin-bottom:20px;border: 1px solid #dddddd;'>
		<tr style='border-top: 1px solid #dddddd;'>
		</tr>
		<tr class='smack-crm-pro-highlight smack-crm-pro-alt' style='border-top: 1px solid #dddddd;'>
			<th class='smack-crm-free-list-view-th' style='width: 50px;'>SL</th>
			<th class='smack-crm-free-list-view-th' style='width: 300px;'>Shortcodes</th>
			<th class='smack-crm-free-list-view-th' style='width: 90px;'>Type</th>
			<th class='smack-crm-free-list-view-th' style='width: 90px;'>Assignee</th>
			<th class='smack-crm-free-list-view-th' style='width: 200px;'>Module</th>
			<th class='smack-crm-free-list-view-th' style='width: 150px;'>Submits</th>
			<th class='smack-crm-free-list-view-th' style='width: 150px;'>Success</th>
			<th class='smack-crm-free-list-view-th' style='width: 150px;'>Failure</th>
			<th class='smack-crm-free-list-view-th' style='width: 90px;'>Action</th>

		</tr>";
	$shortcodes = get_option($options);
	$number = 1;
	$site_url = site_url();
	$admin_url = get_admin_url();

	$formtypes = array('post' => "Post" , 'widget' => "Widget");

	foreach( $formtypes as $formtype_key => $formtype_value )
	{

		if($number % 2 == 1)
		{
			$content1 .= "<tr class='smack-crm-pro-highlight'>";
		}
		else
		{
			$content1 .= "<tr class='smack-crm-pro-highlight smack-crm-pro-alt'>";
		}

		$content1 .= "<td style='text-align:center; border-top: 1px solid #dddddd;'>{$number}</td>";
		$content1 .= "<td style='text-align:center; border-top: 1px solid #dddddd;'>[{$activatedplugin}-web-form type='{$formtype_key}']</td>";
		$content1 .= "<td style='text-align:center; border-top: 1px solid #dddddd;'>{$formtype_key}</td>";

	/*	if( $formtype_key == 'widget' )
		{
			$content1 .= "<td style='text-align:center;'>Yes</td>";
		}
		else
		{
			$content1 .= "<td style='text-align:center;'>No</td>";
		} */
		$content1 .= "<td style='text-align:center; border-top: 1px solid #dddddd;'>Admin</td>";
		$content1 .= "<td style='text-align:center; border-top: 1px solid #dddddd;'>Leads</td>";
			$successfulAttemptsOption = get_option( "wp-{$activatedplugin}-contact-{$formtype_key}-form-attempts" );
			$total = $successfulAttemptsOption['total'];
			$success = $successfulAttemptsOption['success'];			
			$failure = $total - $success;			
if(isset($total));
{
$content1 .= "<td style='text-align:center; border-top: 1px solid #dddddd;'><span style='color:#000;'>$total</span></td>";
$content1 .= "<td style='text-align:center; border-top: 1px solid #dddddd;'><span style='color:green;'>$success</span></td>";
$content1 .= "<td style='text-align:center; border-top: 1px solid #dddddd;'><span style='color:red;'>$failure</span></td>";

}
 		$content1 .= "<td style='text-align:center; border-top: 1px solid #dddddd;'>";
                $content1 .= "<a href='".WP_CONST_ULTIMATE_CRM_CPT_PLUG_URL."&__module=ManageShortcodes&__action=ManageFields&module=Leads&EditShortCode=yes&formtype={$formtype_key}' > Edit </a>";
                $content1 .= "</td>";

		$content1 .= "</tr>";
		$number++;
	}

	$content .= $content1;
	$content .= "</table>";
	$content .= "<input type='hidden' id='ShortCodeaction' name='ShortCodeaction'></div>";

	echo $content;
?>
	<br>
	<table style = "position:relative;left:450px;">
	<tr>
	<td> <input type = "button" size = "50" class = "button-primary" value ="Create More Forms" id ="advance_option" onclick="jQuery('#mandatory-upgrade').show(); jQuery('#mandatory-upgrade').css('display','inline')"> <span id="mandatory-upgrade" style="color:red; display:none;"> <a href ="https://www.smackcoders.com/wp-leads-builder-any-crm-pro.html"> Upgrade To Pro </a> </span>
	 </td>
	<tr>
	</table> 
