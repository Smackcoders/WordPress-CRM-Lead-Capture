//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------

function enableCaptcha(siteurl,module, option, onAction)
{
        var selected = true;
	document.getElementById('loading-image').style.display = "block";

	if(jQuery('#isWidget').prop('checked'))
        {
                var checked = true;
                selected = false;
        }
        else
        {
                var checked = false;
        }
	var shortcode = '';
	if(onAction == 'onEditShortCode')
	{
		shortcode = jQuery('#shortcode').val();
	}
	var  data_array = {
	    'action'	    : 'adminAllActions',
	    'doaction'	    : 'SwitchWidget',
	    'adminaction'   : 'isWidget',
	    'module'	    : module,
	    'option'	    : option,
	    'onAction'	    : onAction,
	    'shortcode'	    : shortcode,
	    'checked'	    : checked, 
	    'selected'	    : selected,
	};

        jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: data_array,
                success:function(data) {
			//alert(data);
                        if(data.indexOf("true") != -1)
                        {
                                jQuery("#isWidget_status").html('Saved');
                                jQuery("#isWidget_status").css('display','inline').fadeOut(3000);
                        }
                        else
                        {
                                jQuery("#isWidget_status").html('Not Saved');
                                jQuery('#isWidget').attr("checked", selected);
                                jQuery("#isWidget_status").css('display','inline').fadeOut(3000);
                        }
			document.getElementById('loading-image').style.display = "none";
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
        });
}

function selectedPlug( thiselement )
{
	// var pluginselect = document.getElementsByName('pluginselect');
var pluginselect = document.getElementById('pluginselect');
var select = pluginselect.options[pluginselect.selectedIndex].value;

if(confirm("If Plugin Settings changed the Previous Shortcodes not work!") == true)
{
		document.getElementById('loading-image').style.display = "block";

	var pluginselect_value;

	for(var i = 0; i < pluginselect.length; i++){
	    if(pluginselect[i].selected == true){
		pluginselect_value = pluginselect[i].value;
	    }
	}

	var redirectURL=document.getElementById('plug_URL').value;
	var postdata = pluginselect_value;

	jQuery.ajax({
		type: 'POST',
		url: ajaxurl,
		data: {
		    'action'   : 'selectplug',
		    'postdata' : postdata,
		},
		success:function(data) { 
			location.href=redirectURL+'&__module=Settings&__action=view';	//	Redirect to Plugin Settings page
		},
		error: function(errorThrown){
			console.log(errorThrown);
		}
	});
}
}

function goToTop()
{
	jQuery(window).scrollTop(0);
}

function selectAll(formid,module)
{
var i;
var data="";
var form =document.getElementById(formid);
var chkall = form.elements['selectall'];
var chkBx_count = form.elements['no_of_rows'].value;
	if(chkall.checked == true){
		for (i=0;i<chkBx_count;i++){
			if(document.getElementById('select'+i).disabled == false)
				document.getElementById('select'+i).checked = true;
		}
	}else{
		for (i=0;i<chkBx_count;i++){
			if(document.getElementById('select'+i).disabled == false)
				document.getElementById('select'+i).checked = false;
		}
	}
}

function syncCrmFields(siteurl, module, option, onAction)
{
	document.getElementById('loading-image').style.display = "block";

	var shortcode = '';
	if(onAction == 'onEditShortCode')
	{
		shortcode = jQuery('#shortcode').val();
	}

        jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action'	 : 'adminAllActions',
		    'doaction' 	 : 'FetchCrmFields',
                    'siteurl'	 : siteurl,
		    'module'	 : module,
		    'option'	 : option,
		    'onAction'	 : onAction,
		    'shortcode'	 : shortcode,
                },
                success:function(data) {
			jQuery("#fieldtable").html(data);
			document.getElementById('loading-image').style.display = "none";
			document.getElementById('crmfield').style.display = 'block';
			//location.reload();
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
        });
}

/*validate Redirect*/

function getvalidate(formid)
{
	var redirecturl = document.fieldform.redirecturl.value;
	var form = document.getElementById(formid);
	var enable = form.elements['enableurlredirection'];
	var tomatch = /(http(s)?:|http:|ftp:\\)?([\w-]+\.)+[\w-]+[.com|.in|.org]+(\[\?%&=]*)?/
	if (enable.checked == true)
	{
		if(tomatch.test(redirecturl))
		{
		//window.alert("URL ok");
			return true;
		}
		else
		{
			window.alert("Invalid URL Tryagain");
			return false;
		}
	}
}


//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
