<?php
 /* Put your code here */ 


function formFields( $options, $onAction, $editShortCodes , $formtype = "post" )
{


	$siteurl= site_url();
	$module =$module_options ='Leads';
	$content1='';

	if($editShortCodes=='')
		$editShortCodes = 'no'; 

	#$imagepath=$siteurl.'/wp-content/plugins/'.WP_CONST_ULTIMATE_CRM_CPT_SLUG.'/images/' ;
	$imagepath= WP_CONST_ULTIMATE_CRM_CPT_DIR . 'images/';

	$config_leads_fields = get_option($options);


$content='
	<input type="hidden" name="field-form-hidden" value="field-form" />
	<div>';
	$i = 0;
	if(!isset($config_leads_fields['fields'][0]))
	{
		$content.='<p style="text-align:center;font-size:20px;color:red;">Crm fields are not yet synchronised</p>';
	}
	else
	{
		$iscontent = true;
		$content.='<table style="background-color: #F1F1F1; border: 1px solid #dddddd;width:85%;margin-bottom:26px;margin-top:5px"><tr class="smack_highlight smack_alt" style="border-bottom: 1px solid #dddddd;"><th class="smack-field-td-middleit" style="width: 40px;" align="left"><input type="checkbox" name="selectall" id="selectall" onclick="selectAll'."('field-form','".$module."')".';"/></th><th style="width: 100px;" align="left"><h5>Field Name</h5></th><th class="smack-field-td-middleit" style="width: 100px;" align="left"><h5>Show Field</h5></th><th class="smack-field-td-middleit" style="width: 150px;" align="left"><h5>Order</h5></th></tr>';

		for($i=0;$i<count($config_leads_fields['fields']);$i++)
		{
			if( $config_leads_fields['fields'][$i]['wp_mandatory'] == 1 )
			{
				$madantory_checked = 'checked="checked"';
			}
			else
			{
				$madantory_checked = "";
			}
			if( isset($config_leads_fields['fields'][$i]['mandatory']) && $config_leads_fields['fields'][$i]['mandatory'] == 2)
			{
				if($i % 2 == 1)
				$content1.='<tr class="smack_highlight smack_alt">';
				else
				$content1.='<tr class="smack_highlight">';

				$content1.='
				<td class="smack-field-td-middleit"><input type="checkbox" name="select'.$i.'" id="select'.$i.'" disabled=disabled checked=checked ></td>
				<td>'.$config_leads_fields['fields'][$i]['label'].' *</td>
				<td class="smack-field-td-middleit">';
//				if($config_leads_fields['fields'][$i]['publish'] == 1)
				{
					$content1.='<a name="publish'.$i.'" id="publish'.$i.'" onclick="'."alert('This field is mandotory, cannot hide')".'">
					<img src="' . WP_CONST_ULTIMATE_CRM_CPT_DIR . 'images/tick_strict.png"/>
					</a>';
				}
				$content1.='</td>
				<td class="smack-field-td-middleit">';
				$content1.= "<input class='position-text-box' type='textbox' name='position{$i}' value='".($i+1)."' >";
				$content1.='</td></tr>';
			}
			else
			{
				if($i % 2 == 1)
				$content1.='<tr class="smack_highlight smack_alt">';
				else
				$content1.='<tr class="smack_highlight">';

				$content1.='<td class="smack-field-td-middleit">';
				

					$content1.= '<input type="checkbox" name="select'.$i.'" id="select'.$i.'">';
				
				
				$content1.='</td>
				<td>'.$config_leads_fields['fields'][$i]['label'].'</td>
				<td class="smack-field-td-middleit">';

				if($config_leads_fields['fields'][$i]['publish'] == 1){
					$content1.='<a name="publish'.$i.'" id="publish'.$i.'" >
					<img src="' . WP_CONST_ULTIMATE_CRM_CPT_DIR . 'images/tick.png"/>
					</a>';
				}
				else{
					$content1.='<a name="publish'.$i.'" id="publish'.$i.'" >
					<img src="' . WP_CONST_ULTIMATE_CRM_CPT_DIR . 'images/publish_x.png"/>
					</a>';
				}
				$content1.='</td>
				<td class="smack-field-td-middleit">';
				$content1.= "<input class='position-text-box' type='textbox' name='position{$i}' value='".($i+1)."' >";
				$content1.='</td></tr>';
			}
		}
	}

	$content1.="<input type='hidden' name='no_of_rows' id='no_of_rows' value={$i} />";
	$content.=$content1;

	$content.= '</table>
	</div>
	';
			//
	return array( 'iscontent' => $iscontent , 'data' => $content);
}


/*	if (isset ($_POST ['formtype'])) {
		$data = saveFormFields( $skinnyData['option'] , $skinnyData['onAction'] , $skinnyData['REQUEST']['EditShortCode'] , $skinnyData['formtype'] );

		if( isset($data['display']) )
		{
			echo $data['display'];
		}
	}
*/
?>
	<form id="field-form" name = "fieldform" action="<?php echo WP_CONST_ULTIMATE_CRM_CPT_PLUG_URL.'&__module=ManageShortcodes&__action=ManageFields&module=Leads&EditShortCode=yes&formtype='.$skinnyData['formtype']; ?>" method="post">

<script src="https://code.jquery.com/jquery-1.10.2.js"></script>


<h4>    



</h4>

<?php

global $IncludedPlugins;
$crmtype = $IncludedPlugins[$skinnyData['activatedplugin']];

?>

<span id='inneroptions' style='position:relative;left:5px;margin-left:10px;'>
<?php

echo "CRM Type: $crmtype";
echo str_repeat('&nbsp;', 8);
echo "Module Type: Leads";

?>
</span>
<br><br>



<h3 style="margin-left:0px; ">
[<?php  echo $skinnyData['activatedplugin']; ?>-web-form type='<?php echo $skinnyData['formtype'];?>'] 


<input style="float:right;" type="button" class="button-secondary submit-add-to-menu" name="sync_crm_fields" value="Fetch CRM Fields" onclick=" syncCrmFields('<?php echo $skinnyData['siteurl'] ;?>','<?php echo $skinnyData['module'] ;?>','<?php echo $skinnyData['options'] ;?>', '<?php echo $skinnyData['onAction'] ;?>');"/>

</h3>

<span  style="padding:10px;  color:#FFFFFF; background-color: #37707D; text-align:center; float:right; font-weight:bold; cursor:pointer; margin-top:-11px; position:relative; overflow:hidden;"  id ="showmore">Form Options <i class="dashicons dashicons-arrow-down"></i></span>

<span  style="padding:10px; color:#FFFFFF; background-color: #37707D; text-align:center; float:right; font-weight:bold; cursor:pointer;  margin-top:209px; margin-right:0px; position:relative; overflow:hidden;"  id ="showless">Form Options <i class="dashicons dashicons-arrow-up"></i></span>

<br><br>

<div class="wp-common-crm-content" style="background-color: white;">

        
	
	<div class="content" style="padding: 20px 0px; color:#004D40 !important; font-size:13px !important; font-weight: bold !important; position:relative;margin-top:-45px; ">
	<table>
		<tr>
	<?php
		$config_fields = get_option($skinnyData['option']);
		$formtypes = array('post' => "Post" , 'widget' => "Widget" );

		$content = "";
		$content.= "<td style='width: 25%; position:relative;'>Form Type  : </td><td style='width: 10%;'><span> {$formtypes[$skinnyData['formtype']]} </span><input type='hidden' name='formtype' value='{$skinnyData['formtype']}'>";

/*
		$select_option = "";
		foreach( $formtypes as $formtype_key => $formtype_value )
		{
			if( $formtype_key == $skinnyData['formtype'] )
			{
				$select_option.= "<option value='{$formtype_key}' selected > {$formtype_value} </option>";
			}
			else
			{
				$select_option.= "<option value='{$formtype_key}'> {$formtype_value} </option>";
			}
		}

		$content.= $select_option;

		$content.= "</select>";*/
		$content.= "</td>";
//		$content.='<input type="hidden" name="formtype" value="'.$formtype.'"/>';
		echo $content;
	?>
		<td style="width: 10%; position:relative;">
			Form Mode : 
		</td>
		<td >
			<?php
				if(isset($_REQUEST['formtype']))
				{
					echo "Form builder is in Edit mode";
				}
				else
				{
					echo "Temporary form builder mode. <span style='color: red;'> Careful on saving this field this form options will be stored under the form type chosen on 'Form Type' picklist</span>";
				}
			?>
		</td>
		</tr>
<!--
	</table>
    </div>
    <br>
    <h3> Form Extras </h3>
    <div class="wp-common-crm-content">
	<table>
-->
		<tr><td><br></td></tr>

		<tr>
		    <td>
			<label style="position:relative";>Error Message On Form Submission : </label>
		    </td>
		    <td>
			<input type="text" name="errormessage" placeholder = "Submitting Failed" value="<?php if(isset($config_fields['errormessage'])) echo $config_fields['errormessage']; ?>" />
		    </td>
		 <td style = "width:18%;">
                	<a class="tooltip"  href="#">
			<img src="<?php echo WP_CONST_ULTIMATE_CRM_CPT_DIR; ?>images/help.png">
			<span class="tooltipPostStatus">
			<img src="<?php echo WP_CONST_ULTIMATE_CRM_CPT_DIR; ?>images/callout.gif" class="callout">
			Form submission failure message display text.
			<img style="margin-top: 6px;float:right;" src="<?php echo WP_CONST_ULTIMATE_CRM_CPT_DIR; ?>images/help.png">
			</span> </a>
        	</td>

		</tr>
		<tr>
		    <td style="position:relative;">
			<label >Success Message On Form Submission : </label> 
		    </td>
		    <td>
			<input type="text" name="successmessage" placeholder = "Thankyou For Submitting" value="<?php if(isset($config_fields['successmessage'])) echo $config_fields['successmessage']; ?>" />
		   </td>
		<td>
			<a class="tooltip" href="#">
                        <img src="<?php echo WP_CONST_ULTIMATE_CRM_CPT_DIR; ?>images/help.png">
                        <span class="tooltipPostStatus">
                        <img src="<?php echo WP_CONST_ULTIMATE_CRM_CPT_DIR; ?>images/callout.gif" class="callout">
                        Form submission success message display text.
                        <img style="margin-top: 6px;float:right;" src="<?php echo WP_CONST_ULTIMATE_CRM_CPT_DIR; ?>images/help.png">
                        </span> </a>			

		</td>
		</tr>
		<tr><td><br></td></tr>
		<tr>
		    <td style="position:relative;">
			<label>Enable URL Redirection : </label>
		    
		    
			<input type="checkbox" id ="enableurlredirection" name="enableurlredirection" value="on" <?php if(isset($config_fields['enableurlredirection']) == 'on'){ echo "checked=checked"; } ?> />

		    </td>
		    <td>
			<input id="redirecturl" type="text" name="redirecturl" placeholder = "1" value="<?php if(isset($config_fields['redirecturl'])) echo $config_fields['redirecturl']; ?>" />
		    </td>
 		<td>
			<a class="tooltip" href="#">
                        <img src="<?php echo WP_CONST_ULTIMATE_CRM_CPT_DIR; ?>images/help.png">      
                        <span class="tooltipPostStatus">
                        <img src="<?php echo WP_CONST_ULTIMATE_CRM_CPT_DIR; ?>images/callout.gif" class="callout">
                        Redirect Page or Post Id.
                        <img style="margin-top: 6px;float:right;" src="<?php echo WP_CONST_ULTIMATE_CRM_CPT_DIR; ?>images/help.png">
                        </span> </a>                
        
                </td>

		</tr>
<!--
		<tr>
		    <td>
			<label>Redirect Url : </label>
		    </td>
		    <td>
			<input id="redirecturl" type="text" name="redirecturl" value="<?php if(isset($config_fields['redirecturl'])) echo $config_fields['redirecturl']; ?>" />
		    </td>
		</tr>
-->
	</table>
	</div>
        </div>
        <br>

 
	 
	<div class="wp-common-crm-content">
<h4 id="formtext" style=" margin:0px; padding: 10px 0px; "> Field Settings :</h4>

		<div class="action-buttons" style="<?php if($skinnyData['onAction'] == 'onCreate') echo 'width:720px;'; else echo 'width:650px;' ?> padding-bottom: 20px; padding-top: 20px;">

			<img src="<?php echo WP_CONST_ULTIMATE_CRM_CPT_DIR; ?>images/loading-indicator.gif" id="loading-image" style="display: none; position:relative; left:500px;padding-top: 5px; padding-left: 15px;">
			</div>
		 
<div class="wp-common-crm-content1">
<select id="bulk-action-selector-top" name="bulkaction" style="margin: 0px 0px 2px;">
<option selected="selected" value="-1">Bulk Actions</option>
<option value="enable_field">Enable Field</option>
<option value="disable_field">Disable Field</option>
<option value="update_order">Update Order</option>
</select>


	<?php
		$content = "";
//		$content.= '<input type="hidden" id="no_of_fields" value="'.$i.'"/>';
		$content.= '<input class="button-primary" type="submit" value="Save Field Settings"/>';
		echo $content;
	?>		
        </div>


		<div id="fieldtable">
		<?php
		if(isset($skinnyData['REQUEST']['EditShortCode']))
		{
			$return_data = formFields( $skinnyData['option'] , $skinnyData['onAction'] , $skinnyData['REQUEST']['EditShortCode'] , $skinnyData['formtype'] );
			echo $return_data['data'];
		}
		else
		{
			$return_data = formFields( $skinnyData['option'] , $skinnyData['onAction'] , '' , $skinnyData['formtype'] );
			echo $return_data['data'];
		}

		?>
		</div>
	</div>
	<br>
	
	<div id="crmfield"
	<?php if(!$return_data['iscontent'])
	{ 
		echo "style='display:none'"; 
	} 
	?>
	>
	       

<script>
function showAccordion( id )
{
	if(jQuery("#advance_option_display").val() == 0 )
	{
		jQuery("#advance_option").css("display", "block");
		jQuery("#advance_option_display").val(1);
		jQuery("#accordion_arrow").removeClass( "fa-chevron-right" );
		jQuery("#accordion_arrow").addClass( "fa-chevron-down" );
	}
	else
	{
                jQuery("#advance_option").css("display", "none");
                jQuery("#advance_option_display").val(0);
		jQuery("#accordion_arrow").removeClass( "fa-chevron-down" );
		jQuery("#accordion_arrow").addClass( "fa-chevron-right" );
	}
}
</script>


<script>
$(document).ready(function() {
        $( ".content" ).hide();
        $( "#showless" ).hide();

        $( "#showmore" ).click(function() {
        $( ".content" ).show( 500 );
        $( "#showless" ).show();
        $( "#showmore" ).hide();
        });

        $( "#showless" ).click(function() {
        $( ".content" ).hide( 500 );
        $( "#showless" ).hide();
        $( "#showmore" ).show();
        });
        
});
</script>



	<h3 onclick="showAccordion('advance_option');" style=" cursor: pointer;"> Advanced Options <i id="accordion_arrow" style="float: right; color:#FFFFFF;" class="fa fa-chevron-right"></i></h3>
        <div class="wp-common-crm-content" id="advance_option" style="display:none; " >
		<input type="hidden" id="advance_option_display" name="advance_option_display"  value=0 >
		<div class="version-warning">
			<span style="font-weight:bold; color:red;">Pro Version Only</span>
			<?php
				require_once(WP_CONST_ULTIMATE_CRM_CPT_DIRECTORY."modules/ManageFields/templates/Advance_Option.php");
			?>
		</div>		
        </div>
        <br>
	
</form>
