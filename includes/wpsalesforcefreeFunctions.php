<?php
include_once(WP_CONST_ULTIMATE_CRM_CPT_DIRECTORY.'lib/SmackSalesForceApi.php');
class Functions{
	public $consumerkey;
	public $consumersecret;
	public $callback;

	public $instanceurl;
 	public $accesstoken;

	
	public $result_emails;
	public $result_ids;

	public function __construct()
	{
		$WPCapture_includes_helper_Obj = new WPCapture_includes_helper();
		$activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
		$SettingsConfig = get_option("wp_{$activateplugin}_settings");

		$this->consumerkey = $SettingsConfig['key'];
		$this->consumersecret = $SettingsConfig['secret'];
		$this->url = "";//$SettingsConfig['url'];
		$this->callback = $SettingsConfig['callback'];
		$this->instanceurl = $SettingsConfig['instance_url'];
 		$this->accesstoken = $SettingsConfig['access_token'];

	}

/*	public function login()
	{
		$client = new SmackZohoApi();
		return $client;
	} 

	public function getAuthenticationKey( $username , $password )
	{
//		$username = $this->username;
//		$password = $this->accesskey;
		$client = $this->login();
		$return_array = $client->getAuthenticationToken( $username , $password  );
		return $return_array;
	} */

	public function getCrmFields( $module )
	{

	$recordInfo = GetCrmModuleFields( $this->instanceurl, $this->accesstoken );
		
	$config_fields = array();

		$AcceptedFields = Array( 'textarea' => 'text' , 'string' => 'string' , 'email' => 'email' , 'boolean' => 'boolean', 'picklist' => 'picklist' , 'varchar' => 'string' , 'url' => 'url' , 'phone' => 'phone' , 'multipicklist' => 'multipicklist',  'radioenum' => 'radioenum', 'currency' => 'currency' , 'date' => 'date' , 'datetime' => 'date' , 'int' => 'string' );

                if($recordInfo)
                {
                        $j=0;
                        for($i=0;$i<count($recordInfo['fields']);$i++)
                        {
//                                        $config_fields['fields'][$j] = $recordInfo['fields'][$i];
if(( $recordInfo['fields'][$i]['type'] != 'id' ) && ( $recordInfo['fields'][$i]['updateable'] == 1 ) && ( $recordInfo['fields'][$i]['type'] != 'reference' ) && ( $recordInfo['fields'][$i]['name'] != 'EmailBouncedReason' ) && ( $recordInfo['fields'][$i]['type'] != 'datetime' ) )
{


					$config_fields['fields'][$j]['name'] = $recordInfo['fields'][$i]['name'];
					$config_fields['fields'][$j]['label'] = $recordInfo['fields'][$i]['label'];
                                        $config_fields['fields'][$j]['order'] = $j;
                                        $config_fields['fields'][$j]['publish'] = 1;
                                        $config_fields['fields'][$j]['display_label'] = $recordInfo['fields'][$i]['label'];
                                       	if( ($recordInfo['fields'][$i]['nillable'] != 1 ) && ( $recordInfo['fields'][$i]['type'] != 'boolean' ))
                                        {
                                                $config_fields['fields'][$j]['wp_mandatory'] = 1;
                                                $config_fields['fields'][$j]['mandatory'] = 2;
                                        }
                                        else
                                        {
                                                $config_fields['fields'][$j]['wp_mandatory'] = 0;
                                        }
					if($recordInfo['fields'][$i]['type'] == 'picklist' || $recordInfo['fields'][$i]['type'] == 'multipicklist' )
					{
						foreach( $recordInfo['fields'][$i]['picklistValues'] as $picklistkey => $picklistvalue )
						{
							$config_fields['fields'][$j]['type']['picklistValues'][$picklistkey] = $picklistvalue;
						}
						$config_fields['fields'][$j]['type']['defaultValue'] = "";
						$config_fields['fields'][$j]['type']['name'] = $AcceptedFields[$recordInfo['fields'][$i]['type']];
					}
					else
					{
						$config_fields['fields'][$j]['type']['name'] = $AcceptedFields[$recordInfo['fields'][$i]['type']];
					}

                                       $j++;
}
                        }

                        $config_fields['check_duplicate'] = 0;
                        $config_fields['isWidget'] = 0;
                        $config_fields['update_record'] = 0;

                        $users_list = $this->getUsersList();

                        $config_fields['assignedto'] = $users_list['id'][0];
                        $config_fields['module'] = $module;

			return $config_fields;

                }

	}
	public function getUsersList()
	{
	//https://crm.zoho.com/crm/private/xml/Users/getUsers?authtoken=Auth Token&scope=crmapi&type=The Type of User
//                $query = "select user_name, id, first_name, last_name  from Users";
		$records = Getuser( $this->instanceurl, $this->accesstoken );


	       foreach($records['recentItems'] as $record) {
	       //         $user_details['user_name'][] = $record['Name'];
			$Name = explode(" ",$record['Name']);
			$user_details['first_name'][]= $Name[0];
			$user_details['last_name'][] = $Name[1];
			$user_details['id'][] = $record['Id'];
		       // $user_details['first_name'][] = $record['@attributes']['email']; //$record['@attributes']['first_name'];
		      //  $user_details['last_name'][] = ""; //$record['@attributes']['email'];
		}
     
           return $user_details;

	}

	
	public function getUsersListHtml( )
	{
		$HelperObj = new WPCapture_includes_helper;
		$module = $HelperObj->Module;
		$moduleslug = $HelperObj->ModuleSlug;
		$activatedplugin = $HelperObj->ActivatedPlugin;
		$activatedpluginlabel = $HelperObj->ActivatedPluginLabel;

		$option = "smack_{$activatedplugin}_{$moduleslug}_fields-tmp";
		$config_fields = get_option($option);

		$users_list = $this->getUsersList();

		$html = "";
		$html = '<select name="assignedto" id="assignedto" onchange="saveAssignedTo(\''.site_url().'\',\''.$module.'\',\''.$option.'\',\'onCreate\');">';
                $content_option = "";
                if(isset($users_list['user_name']))
                for($i = 0; $i < count($users_list['user_name']) ; $i++)
                {
			$content_option.="<option id='{$users_list['user_name'][$i]}' value='{$users_list['user_name'][$i]}'";
			if($users_list['user_name'][$i] == $config_fields["assignedto"])
			{
				$content_option.=" selected";
			}
			$content_option.=">{$users_list['user_name'][$i]}</option>";
		}
		$html .= $content_option;
		$html .= "</select> <span style='padding-left:15px; color:red;' id='assignedto_status'></span>";
		return $html;
	}
	
	public function mapUserCaptureFields( $user_firstname , $user_lastname , $user_email )
	{
		$post = array();
		$post['FirstName'] = $user_firstname;
		$post['LastName'] = $user_lastname;
		$post[$this->duplicateCheckEmailField()] = $user_email;
		return $post;
	}


        public function assignedToFieldId()
        {
                return "OwnerId";
        }


/*	public function refreshToken( )
	{
		$response = refresh_token( $config ); 
		$this->instanceurl = $config['instance_url'] = $response['instance_url'];
		$config['signature'] = $response['signature'];
		$this->accesstoken = $config['access_token'] = $response['access_token'];
		update_option( "wp_{$activatedplugin}_settings" , $config );

	}

*/
	public function createRecordOnUserCapture( $module , $module_fields )
	{

//		$this->refreshToken($config );

                $record = create_record( $module_fields , $this->instanceurl, $this->accesstoken , "Contact" );


		if( isset($record['result']['message']) && ( $record['result']['message'] == "Record(s) added successfully" ) )
		{
			$data['result'] = "success";
			$data['failure'] = 0;
		}
		else
		{
			$data['result'] = "failure";
			$data['failure'] = 1;
			$data['reason'] = "failed adding entry";
		}
		return $data;
	}


	public function createRecord( $module , $module_fields )
	{


		global $HelperObj;
                $WPCapture_includes_helper_Obj = new WPCapture_includes_helper();
                $activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
		$moduleslug = $this->ModuleSlug = rtrim( strtolower($module) , "s");

//		$this->refreshToken($config );

                $record = create_record( $module_fields , $this->instanceurl, $this->accesstoken );


		if( isset($record['id']))
		{
			$data['result'] = "success";
			$data['failure'] = 0;
		}
		else
		{
			$data['result'] = "failure";
			$data['failure'] = 1;
			$data['reason'] = "failed adding entry";
		}
		return $data;
	}
	
	public function updateRecord( $module , $module_fields , $ids_present )
	{
		$client = $this->login();
//		$client->docreate( $module , $module_fields );

		global $HelperObj;
                $WPCapture_includes_helper_Obj = new WPCapture_includes_helper();
                $activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
		$moduleslug = $this->ModuleSlug = rtrim( strtolower($module) , "s");

		$config_fields = get_option("smack_{$activateplugin}_{$moduleslug}_fields-tmp");

		foreach($config_fields['fields'] as $key => $fields)  //      To add _ for field with spaces to capture the REQUEST
		{
			if( count($exploded_fields = explode(' ', $fields['fieldname'] )) > 1 )
			{
				foreach( $exploded_fields as $exploded_field )
				{
					$underscored_field .= $exploded_field."_";
				}
				$underscored_field = rtrim($underscored_field, "_");
			}
			else
			{
				$underscored_field = $fields['fieldname'];
			}
			$config_underscored_fields[$underscored_field] = $fields['fieldname'];
			$underscored_field = "";
		}

                foreach($module_fields as $field => $value)
                {
                        if( array_key_exists($field , $config_underscored_fields) )
                        {
                                $post_fields[$config_underscored_fields[$field]]=$value;//urlencode($value);
                        }
                }

                $postfields = "<{$module}>\n<row no=\"1\">\n";
		if(isset($post_fields))
		{
			foreach($post_fields as $key => $value)
			{
				$postfields .= "<FL val=\"".$key."\">".$value."</FL>\n";
			}
		}
		else
		{
			foreach($module_fields as $key => $value)
			{
				$postfields .= "<FL val=\"".$key."\">".$value."</FL>\n";
			}
		}
                $postfields .= "</row>\n</$module>";

		$config_fields = get_option("smack_{$HelperObj->ActivatedPlugin}_fields_shortcodes");

		$extraparams = "&id={$ids_present}";

		$record = $client->insertRecord( $module , "updateRecords" , $this->authtoken ,  $postfields , $extraparams );

                if( isset($record['result']['message']) && ( $record['result']['message'] == "Record(s) updated successfully" ) )
                {
                        $data['result'] = "success";
                        $data['failure'] = 0;
                }
                else
                {
                        $data['result'] = "failure";
                        $data['failure'] = 1;
                        $data['reason'] = "failed adding entry";
                }

                return $data;

	}

	public function checkEmailPresent( $module , $email )
	{
		$WPCapture_includes_helper_Obj = new WPCapture_includes_helper();
		$activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;

		$result_emails = array();
		$result_ids = array();

		$client = $this->login();
	        $email_present = "no";

		$extraparams = "&searchCondition=(Email|=|{$email})";

                $records = $client->getRecords( $module , "getSearchRecords" , $this->authtoken , "Id , Email" , "" , $extraparams );

		if(isset( $records['result'][$module]['row']['@attributes'] ))
		{
                        $result_lastnames[] = "Last Name";
                        $result_emails[] = $email; 
                        $result_ids[] = $records['result'][$module]['row']['FL'];
                        $email_present = "yes";
		}
		else
		{
			if(is_array($records['result'][$module]['row']))
			{
				foreach( $records['result'][$module]['row'] as $key => $record )
				{
					$result_lastnames[] = "Last Name";
					$result_emails[] = $email; 
					$result_ids[] = $record['FL'];
					$email_present = "yes";
				}
			}
		}

		$this->result_emails = $result_emails;
		$this->result_ids = $result_ids;
		if($email_present == 'yes')
			return true;
		else
			return false;

	}

	public function duplicateCheckEmailField()
	{
		return "Email";
	}
}
