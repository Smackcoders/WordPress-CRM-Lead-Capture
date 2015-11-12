<?php
	$selectedPlugin= $_REQUEST['postdata'];
//	update_option('selectedPlug',$selectedPlugin);
	update_option('ActivatedPlugin' , $selectedPlugin );
?>
