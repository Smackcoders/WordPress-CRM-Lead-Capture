<?php
$siteurl= site_url();
if( isset($_POST["smack-captcha-form"]))
{
	$skinnyObj = CallCaptchaSettingsCrmObj::getInstance();
	$skinnyObj->saveSettingArray($_POST);
}

$WPCapture_includes_helper_Obj = new WPCapture_includes_helper();
$activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;

$config = get_option("wp_captcha_settings");
?>

	<form id="smack-captcha-form" action="" method="post">
	<input type="hidden" name="smack-captcha-form" value="smack-captcha-form" />
		<h1>Google reCAPTCHA Settings</h1>
		<table>
			<tr>
				<td>
					<label>Do you need captcha to visible : </label>
				</td>
				<td>
					<input type='radio' class='smack-vtiger-settings-radio-captcha' name='smack_recaptcha' id='smack_recaptcha_no'  value="no"
		<?php
		if($config['smack_recaptcha']=='no' || !isset($config['smack_recaptcha']))
		{
			echo "checked";
		}
		?>
		 onclick="showOrHideRecaptcha('no');"> No
					<input type='radio' class='smack-vtiger-settings-radio-captcha' name='smack_recaptcha' id='smack_recaptcha_yes'  value="yes" 
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
				<td>
					<label>Recaptcha Public Key  : </label>
				</td>
				<td>
					<input type='text' class='smack-vtiger-settings-text' placeholder='Enter your recaptcha public key here' name='recaptcha_public_key' id='smack_public_key' value="<?php echo $config['recaptcha_public_key'] ?>"/>
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
				<td >
					<label>Recaptcha Private Key : </label>
				</td>
				<td>
					<input type='text' class='smack-vtiger-settings-text' placeholder='Enter your recaptcha private key here' name='recaptcha_private_key' id='smack_private_key' value="<?php echo $config['recaptcha_private_key'] ?>"/>
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td>
					<input type="hidden" name="posted" value="<?php echo 'posted';?>">
					<p class="submit">
						<input type="submit" value="<?php _e('Save Settings');?>" class="button-primary"/>
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
