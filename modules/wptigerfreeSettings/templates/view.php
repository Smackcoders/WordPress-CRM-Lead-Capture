<?php
$siteurl = site_url();
$config = get_option("wp_{$skinnyData['activatedplugin']}_settings");

?>

<div><h3>VTiger CRM Settings</h3></div>

	<img src="<?php echo WP_CONST_ULTIMATE_CRM_CPT_DIR; ?>images/loading-indicator.gif" id="loading-image" style="display: none; position:relative; left:500px;padding-top: 5px; padding-left: 15px;">
<div>
<!--  Start -->
	<form id="smack-vtiger-settings-form" action="" method="post">
		<input type="hidden" name="smack-vtiger-settings-form" value="smack-vtiger-settings-form" />
		<input type="hidden" id="plug_URL" value="<?php echo WP_CONST_ULTIMATE_CRM_CPT_PLUG_URL;?>" />
		<div class="wp-common-crm-content" style="width: 600px;float: left;">
		<table>
			<tr>
				<td><label id="inneroptions" style="font-weight:bold;">Select Plugin</label></td>
				<td>
			<?php
				$ContactFormPluginsObj = new ContactFormPlugins();
				echo $ContactFormPluginsObj->getPluginActivationHtml();
			?>
				</td>
			</tr>
		</table>
		<table class="settings-table">
			<tr><td colspan=2 ><h5 id="inneroptions">VtigerCRM settings</h5></td></tr>
			<tr>
				<td style='width:160px;'>
					<label id="innertext"> Vtiger Url </label><div style='float:right;'> : </div>
				</td>
				<td>
					<input type='text' class='smack-vtiger-settings-text' name='url' id='smack_host_address' value="<?php echo $config['url'] ?>"/>

				</td>
			</tr>
			<tr>
				<td style='width:160px;'>
					<label id="innertext"> Vtiger Username </label><div style='float:right;'> : </div></label>
				</td>
				<td>
					<input type='text' class='smack-vtiger-settings-text' name='username' id='smack_host_username' value="<?php echo $config['username'] ?>"/>

				</td>
			</tr>
			<tr>
				<td style='width:160px;'>
					<label id="innertext"> Vtiger Access Key </label><div style='float:right;'> : </div>
				</td>
				<td>
					<input type='text' class='smack-vtiger-settings-text' name='accesskey' id='smack_host_access_key' value="<?php echo $config['accesskey'] ?>"/>
				</td>
			</tr>
			<tr>
				<td>
				</td>
			</tr>
			<tr>
				<td style='width:160px;'>
					<label id="innertext"><div style='float:left;'> Capture Registering User  </div></label>
				</td>
				<td>
					<input type='checkbox' class='smack-vtiger-settings-text' name='user_capture' id='user_capture' value="on" <?php if(isset($config['user_capture']) && $config['user_capture'] == 'on') { echo "checked=checked"; } ?>/>
				</td>
			</tr>

		</table>
		<br/>

	<table>
                <h5 id="inneroptions" style="font-weight:bold;">Contact Form 7</h5>
                <tr>
                                <td style='width:160px;'>
                                        <label id="innertext"><div style='float:left;'> Enable Contact Form 7  </div></label>
                                </td>
                                <td>
                                        <input type='checkbox' class='smack-vtiger-settings-text' name='contact_form' id='contact_form' value="on" <?php if(isset($config['contact_form']) && $config['contact_form'] == 'on') { echo "checked=checked"; } ?>/>
                                </td>
                </tr>



        </table>
	<tr><td><br></td></tr>

<h5 id="inneroptions" style="font-weight:bold;">Email Notification</h5>
<table>
                <tr>
                        <td style='width:160px;'>
                                <label id="innertext"><div style='float:left;'> Email All Captured Data</div></label>
                        </td>
                        <td>
                                <input type='checkbox' class='smack-vtiger-settings-text' name='smack_email' id='smack_email' value="on" <?php if(isset($config['smack_email']) && $config['smack_email'] == 'on') { echo "checked=checked"; } ?>/>
                        </td>
                </tr>
		<tr><td><br></td></tr>
                <tr>
                        <td style='width:160px;'>
                                <label id="innertext"><div style='float:left;'> Email Id </div></label>
                        </td>
                        <td>
                     <input type='text' class='smack-sugar-pro-settings-text' name='email' id='email' value="<?php echo $config['email'] ?>"/>
                        </td>
                </tr> 
</table>
 <tr><td><br></td></tr>
	<table> 
		<h5 id="inneroptions" style="font-weight:bold;">Debug Mode</h5>
		<tr>
                                <td style='width:160px;'>
                                        <label id="innertext"><div style='float:left;'> Enable Debug Mode  </div></label>
                                </td>
                                <td>
                                        <input type='checkbox' class='smack-vtiger-settings-text' name='debug_mode' id='debug_mode' value="on" <?php if(isset($config['debug_mode']) && $config['debug_mode'] == 'on') { echo "checked=checked"; } ?>/>
                                </td>
                </tr>



        </table>
        <br/>

		<table>
			<tr>
				<td>
					<input type="hidden" name="posted" value="<?php echo 'posted';?>">
                        
					<p class="submit">
						<input type="submit" value="<?php _e('Save Settings');?>" class="button-primary" onclick="document.getElementById('loading-image').style.display = 'block'"/>
					</p>
				</td>
			</tr>
		</table>
		</div>
		<!--<div id="wp-tiger-pro-video">
			<span style="padding-top: 10px; font-size: 14px; padding-bottom: 20px; font-weight: bold;">How to configure WP Tiger Pro</span>
			<iframe width="560" height="315" src="http://www.youtube.com/embed/AE6MvSQuubg?list=PL2k3Ck1bFtbQnYh2ak-jM7fYyo0kzMlv-" frameborder="0" allowfullscreen></iframe>
		</div>-->
	</form>
<!-- End-->
</div>
