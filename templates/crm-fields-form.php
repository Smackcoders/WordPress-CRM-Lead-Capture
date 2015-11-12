<?php
$content1='';
$content='
	<input type="hidden" name="field-form-hidden" value="field-form" />
	<div>';
	$i=0;
	if(!isset($config_fields['fields'][0]))
	{
		$content.='<p style="text-align:center;font-size:20px;color:red;">Crm fields are not yet synchronised</p>';
	}
	else
	{
	//	$content .= '<tr><td>';
		$content .='<div id="fieldtable">';
		$content.='<table style="background-color: #F1F1F1; border: 1px solid #dddddd;width:85%;margin-bottom:26px;margin-top:5px"><tr class="smack_highlight smack_alt" style="border-bottom: 1px solid #dddddd;"><th class="smack-field-td-middleit" align="left" style="width: 40px;"><input type="checkbox" name="selectall" id="selectall" onclick="selectAll'."('field-form','".$module."')".';"/></th><th align="left" style="width: 100px;"><h5>Field Name</h5></th><th class="smack-field-td-middleit" align="left" style="width: 100px;"><h5>Show Field</h5></th><th class="smack-field-td-middleit" align="left" style="width: 150px;"><h5>Order</h5></th></tr>';

	//	$content.='<table class="smack-table-populate"><tr class="smack_alt"><th style="width: 50px;"><input type="checkbox" name="selectall" id="selectall" onclick="selectAll'."('field-form','".$module."')".';"/></th><th style="width: 200px;"><h5>Field Name</h5></th><th style="width: 100px;"><h5>Show Field</h5></th><th style="width: 100px;"><h5>Order</h5></th><th style="width: 120px;"><h5>Mandatory Fields</h5></th><th style="width: 200px;"><h5>Field Label Display</h5></th></tr>';
		$imagepath=WP_CONST_ULTIMATE_CRM_CPT_DIR.'images/';

		for($i=0;$i<count($config_fields['fields']);$i++)
		{
			if( isset( $config_fields['fields'][$i]['wp_mandatory'] ) && ( $config_fields['fields'][$i]['wp_mandatory']==1 ))
			{
				$madantory_checked='checked="checked"';
			}
			else
			{
				$madantory_checked="";
			}
			if(isset( $config_fields['fields'][$i]['mandatory'] ) && ($config_fields['fields'][$i]['mandatory'] == 2 ))
			{
				if($i % 2 == 1)
				$content1.='<tr class="smack_highlight smack_alt">';
				else
				$content1.='<tr class="smack_highlight">';

				$content1.='
				<td class="smack-field-td-middleit"><input type="checkbox" name="select'.$i.'" id="select'.$i.'" disabled="disabled" ></td>
				<td>'.$config_fields['fields'][$i]['label'].' *</td>
				<td class="smack-field-td-middleit">';
				if($config_fields['fields'][$i]['publish'] == 1){
					$content1.='<a class="smack_pointer" name="publish'.$i.'" id="publish'.$i.'" onclick="'."alert('This field is mandotory, cannot hide')".'">
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
                                

				//<input type="checkbox" name="select'.$i.'" id="select'.$i.'">
				$content1.= '</td>
				<td>'.$config_fields['fields'][$i]['label'].'</td>
				<td class="smack-field-td-middleit">';
				if($config_fields['fields'][$i]['publish'] == 1){
					$content1.='<a class="smack_pointer" name="publish'.$i.'" id="publish'.$i.'" onclick="published('.$i.',0,'."'$siteurl'".','."'$module'".','."'$options'".','."'$onAction'".');">
					<img src="' . WP_CONST_ULTIMATE_CRM_CPT_DIR . 'images/tick.png"/>
					</a>';
				}
				else{
					$content1.='<a class="smack_pointer" name="publish'.$i.'" id="publish'.$i.'" onclick="published('.$i.',1,'."'$siteurl'".','."'$module'".','."'$options'".','."'$onAction'".');">
					<img src="' . WP_CONST_ULTIMATE_CRM_CPT_DIR . 'images/publish_x.png"/>
					</a>';
				}
				$content1.='</td>
				<td class="smack-field-td-middleit">';
				$content1.= "<input class='position-text-box' type='textbox' name='position{$i}' value='".($i+1)."' >";
				$content1.='</td></tr>';
			}
		}
		$content1.="<input type='hidden' name='no_of_rows' id='no_of_rows' value={$i} />";

		$content1.= "</table></div>";
	}

		$content.=$content1;

$content .='</div>';
echo $content;

?>
