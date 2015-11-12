<?php
	if(isset($skinnyData['display']))
	{
		echo $skinnyData['display'];
	}
	include( WP_CONST_ULTIMATE_CRM_CPT_DIRECTORY . 'modules/'.$skinnyData['action'].'/templates/view.php');
?>
