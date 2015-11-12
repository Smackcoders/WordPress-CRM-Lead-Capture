<?php
//echo 'Check menu php';

	$HelperObj = new WPCapture_includes_helper;

	$plugin_url= WP_CONST_ULTIMATE_CRM_CPT_DIRECTORY;
	$onAction= 'onCreate';
	$siteurl= site_url();
	$module = $HelperObj->Module;
	$moduleslug = $HelperObj->ModuleSlug;
?>
<nav class='navbar navbar-default' role='navigation'>
   <div>
      <ul class='nav navbar-nav'>
	<li class="<?php if( ($_REQUEST['__module']=='ManageShortcodes' ) &&( ( $_REQUEST['__action']=='view' ) ||( $_REQUEST['__action']=='ManageFields' )) ){ echo 'activate'; }else { echo 'deactivate'; }?>" >
		<a href='admin.php?page=<?php echo WP_CONST_ULTIMATE_CRM_CPT_SLUG;?>/index.php&__module=ManageShortcodes&__action=view'><span id='shortcodetab'> Manage ShortCodes</span></a>
	</li>
	
	<li class="<?php if( ($_REQUEST['__module']=='Settings' ) && ( $_REQUEST['__action']=='view' ) ){ echo 'activate'; }else{ echo 'deactivate'; }?>">
		<a href='admin.php?page=<?php echo WP_CONST_ULTIMATE_CRM_CPT_SLUG;?>/index.php&__module=Settings&__action=view'><span id='settingstab'> Settings </span></a>
	</li>
      </ul>
   </div>
</nav>
<?php if( !is_plugin_active('wp-leads-builder-any-crm/index.php')) { ?>
<p style="text-align:center;font-size:15px;color:red;"> Alert: Old plugin is still active, deactivate and delete it now.</p>
<?php } ?>
