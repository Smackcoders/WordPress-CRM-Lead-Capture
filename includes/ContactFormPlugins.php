<?php
require_once(plugin_dir_path(__FILE__).'/../ConfigureIncludedPlugins.php');
class ContactFormPlugins
{
	public function getActivePlugin()
	{
		return get_option('ActivatedPlugin');
	}

	public function getAllPlugins()
	{

	}

	public function getInactivePlugins()
	{

	}

	public function getPluginActivationHtml( )
	{
		global $IncludedPlugins;
		global $crmdetails;
		$html = "";
		$select_option = "";
		$html .= '<span style ="position:relative;left:63%;"><select name = "pluginselect" id ="pluginselect" onchange="selectedPlug( this )">';

		foreach($IncludedPlugins as $pluginslug => $pluginlabel)
		{

			if($this->getActivePlugin() == $pluginslug )
			{
				
				$select_option .= "<option value='{$pluginslug}' selected=selected > {$pluginlabel} </option>";

			}
			else
			{
				$select_option .= "<option value='{$pluginslug}' > {$pluginlabel} </option>" ;
			}

		}
		$html .= $select_option;
		$html .= "</select></span>";
		return $html;
	}
}
?>
