<?php

$HelperObj = new WPCapture_includes_helper;
$module = $HelperObj->Module;
$moduleslug = $HelperObj->ModuleSlug;
$activatedplugin = $HelperObj->ActivatedPlugin;
$activatedpluginlabel = $HelperObj->ActivatedPluginLabel;

$plugin_dir= WP_CONST_ULTIMATE_CRM_CPT_DIRECTORY;
$plugins_url = WP_CONST_ULTIMATE_CRM_CPT_DIR;

//$config = get_option('smack_wp_tiger_user_capture_settings');
//$imagepath = $siteurl.'/wp-content/plugins/wp-tiger-pro/images/';
$siteurl = site_url();
$imagepath = $plugins_url.'images/';
if( isset($_POST["smack-{$activatedplugin}-user-capture-settings-form"]) ) {
/*$fieldNames = array(
                'smack_user_capture' => __('Capture Registering User'),
                'smack_capture_duplicates' => __('Capture Duplicate users'),
        );

                foreach ($fieldNames as $field=>$value){
                        $config[$field] = $_POST[$field];
                }

        update_option('smack_wp_tiger_user_capture_settings', $config);
*/
	$skinnyObj = CallSyncWPCrmObj::getInstance();
	$skinnyObj->saveSettingArray($_POST , $HelperObj);

}
$config = get_option("smack_{$activatedplugin}_user_capture_settings");
?>
<form id="smack-<?php echo $activatedplugin;?>-user-capture-settings-form" action="" method="post">
<input type="hidden" name="smack-<?php echo $activatedplugin;?>-user-capture-settings-form" value="smack-<?php echo $activatedplugin;?>-user-capture-settings-form" />
<h1>Capture WordPress users</h1>
<table>
	<tr>
		<td><br/>
			<label><div style='float:left;padding-right: 5px;'>Sync New Registration to CRM Contacts </div> <div style='float:right;'>:</div> </label>
		</td>
		<td><br/>
			<input type='checkbox' class='smack-settings-user-capture' name='smack_user_capture' id='smack_user_capture' 
<?php
if($config['smack_user_capture']=='on')
{
	echo "checked";
}
?>
>
		</td>
	</tr>
	<tr>
		<td>
			<label><div style='float:left;padding-right: 5px;'>Duplicate handling</div><div style='float:right;'>:</div> </label>
		</td>
		<td>
			<input type='radio' class='smack-settings-capture-duplicates' name='smack_capture_duplicates' id='smack_capture_duplicates' value='skip' 
<?php
if($config['smack_capture_duplicates']=='skip')
{
	echo "checked";
}
?>
> Skip
		</td>
		<td>
			<input type='radio' class='smack-settings-capture-duplicates' name='smack_capture_duplicates' id='smack_update_records' value='update'
<?php
if($config['smack_capture_duplicates']=='update')
{
	echo "checked";
}
?>
> Update
		</td>
		<td>
			<input type='radio' class='smack-settings-capture-duplicates' name='smack_capture_duplicates' id='smack_update_records' value='none'
<?php
if(!isset($config['smack_capture_duplicates']) || ($config['smack_capture_duplicates']=='none'))
{
	echo "checked";
}
?>
> None
		</td>

	</tr>

</table>
<table>
        <tr>
                <td>
                        <input type="hidden" name="posted" value="<?php echo 'posted';?>">
                        <p class="submit">
                                <input type="submit" value="<?php _e('Save Auto Sync Settings');?>" class="button-primary"/>
                        </p>
                </td>
		<td>
		<input type="button" style="float:left;" value="<?php _e('Manual Sync (Existing users)');?>" class="button-secondary submit-add-to-menu" onclick="captureAlreadyRegisteredUsers('<?php echo $siteurl; ?>');"/>
		<img style="display:none; float:left; padding-top:5px; padding-left:5px;" id="loading-image" src="<?php echo $imagepath.'loading-indicator.gif';?>" />
		</td>

        </tr>
</table>
</form>
