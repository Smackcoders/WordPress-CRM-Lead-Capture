<?php //echo 'Layout php file text';
	$captObj=CallWPCaptureObj::getInstance();
	$captObj->renderMenu();
?>

<div class="wp-common-crmwrapper" id="innerhead">
<?php
	echo $skinny_content;
?>
</div>
