<?php
	$siteurl = site_url();
	$config = get_option("wp_{$skinnyData['activatedplugin']}_settings");

?>
</span>
<form id="smack-zoho-settings-form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
	<h3>Zoho CRM Settings</h3>
 <img src="<?php echo WP_CONST_ULTIMATE_CRM_CPT_DIR; ?>images/loading-indicator.gif" id="loading-image" style="display: none; position:relative; left:500px;padding-top: 5px; padding-left: 15px;">
	<input type="hidden" name="smack-zoho-settings-form" value="smack-zoho-settings-form" />
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
                <tr><td><br></td></tr>
		<tr>
			<td style='width:160px;'>
				<label id="innertext"> Zoho Username </label><div style='float:right;'> : </div></label>
			</td>
			<td>
				<input type='text' class='smack-sugar-pro-settings-text' name='username' id='smack_host_username' value="<?php echo $config['username'] ?>"/>
			</td>
		</tr>
		<tr>
			<td style='width:160px;'>
				<label id="innertext"> Zoho Password </label><div style='float:right;'> : </div>
			</td>
			<td>
				<input type='password' class='smack-sugar-pro-settings-text' name='password' id='smack_host_access_key' value="<?php echo $config['password'] ?>"/>
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
		<h5 id="inneroptions"  style="font-weight:bold;">Debug Mode</h5>
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






<!--
	<h3>Google reCAPTCHA Settings</h3>
	<table>
		<tr style="display:block;">
			<td>
				<label>Do you need captcha to visible : </label>
			</td>
			<td>
				<input type='radio' class='smack-zoho-settings-radio-captcha' name='smack_recaptcha' id='smack_recaptcha_no'  value="no"
	<?php
	if($config['smack_recaptcha']=='no' || !isset($config['smack_recaptcha']))
	{
		echo "checked";
	}
	?>
	 onclick="showOrHideRecaptcha('no');"> No
				<input type='radio' class='smack-zoho-settings-radio-captcha' name='smack_recaptcha' id='smack_recaptcha_yes'  value="yes" 
	<?php
	if($config['smack_recaptcha']=='yes')
	{
		echo "checked";
	}
	?>
	 onclick="showOrHideRecaptcha('yes');"> Yes
			</td>
		</tr>

		<tr id="recaptcha_public_key"
	<?php
	if($config['smack_recaptcha']=='no' || !isset($config['smack_recaptcha']))
	{
		echo 'style="display:none"';
	}
	else
	{
		echo 'style="display:block"';
	}
	?>
	>
			<td style='width:100px;'>
				<label> Recaptcha Public Key  : </label>
			</td>
			<td>
				<input type='text' class='smack-sugar-pro-settings-text' name='recaptcha_public_key' id='smack_public_key' value="<?php echo $config['recaptcha_public_key'] ?>" placeholder="Enter recaptcha public key here"/>
			</td>
		</tr>
		<tr id="recaptcha_private_key" <?php
	if($config['smack_recaptcha']=='no' || !isset($config['smack_recaptcha']))
	{
		echo 'style="display:none"';
	}
	else
	{
		echo 'style="display:block;"';
	}
	?>
	>
			<td style='width:100px;'>
				<label> Recaptcha Private Key : </label>
			</td>
			<td>
				<input type='text' class='smack-sugar-pro-settings-text' name='recaptcha_private_key' id='smack_private_key' value="<?php echo $config['recaptcha_private_key'] ?>" placeholder="Enter recaptcha private key here"/>
			</td>
		</tr>
	</table>
-->
	<table>
		<tr>
			<td>
				<input type="hidden" name="posted" value="<?php echo 'posted';?>">

				<input class="smack_settings_input_text" type="hidden" id="authkey" name="authkey" value="" />
				<p class="submit">
					<input type="submit" value="<?php _e('Save Settings');?>" class="button-primary" onclick="document.getElementById('loading-image').style.display = 'block'"/>

				</p>
			</td>
		</tr>
	</table>
	</div>
	<!--
	<div id="wp-sugar-pro-video">
		<span style="padding-top: 10px; font-size: 14px; padding-bottom: 20px; font-weight: bold;">How to configure WP Sugar Pro</span>
		<iframe width="560" height="315" src="http://www.youtube.com/embed/AE6MvSQuubg?list=PL2k3Ck1bFtbQnYh2ak-jM7fYyo0kzMlv-" frameborder="0" allowfullscreen></iframe>
	</div>
	-->
</form>
