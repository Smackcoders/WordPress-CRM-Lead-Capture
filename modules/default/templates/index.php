<script>
	alert("<?php  echo site_url(); ?>"+'/wp-admin/admin.php?page='+ <?php echo WP_CONST_ULTIMATE_CRM_CPT_SLUG; ?>+'/index.php&__module=Settings&__action=view');
	window.href.location = "<?php  echo site_url(); ?>"+'/wp-admin/admin.php?page='+ <?php echo WP_CONST_ULTIMATE_CRM_CPT_SLUG; ?>+'/index.php&__module=Settings&__action=view';
</script>

<?php

	echo site_url().'/wp-admin/admin.php?page='. WP_CONST_ULTIMATE_CRM_CPT_SLUG.'/index.php&__module=Settings&__action=view';

header('Location : '.site_url().'/wp-admin/admin.php?page='. WP_CONST_ULTIMATE_CRM_CPT_SLUG.'/index.php&__module=Settings&__action=view');

