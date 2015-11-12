<?php
include_once(WP_CONST_ULTIMATE_CRM_CPT_DIRECTORY.'lib/vtwsclib/Vtiger/WSClient.php');
class Functions{

	public $username;
	public $accesskey;
	public $url;
/*
	public $dbuser;
        public $dbpass;
        public $dbhost;
        public $dbname;
*/

	public $result_emails;
	public $result_ids;

	public function __construct()
	{
		$WPCapture_includes_helper_Obj = new WPCapture_includes_helper();
		$activateplugin = $WPCapture_includes_helper_Obj->ActivatedPlugin;
		$SettingsConfig = get_option("wp_{$activateplugin}_settings");

		$this->username = $SettingsConfig['username'];
		$this->accesskey = $SettingsConfig['accesskey'];
		$this->url = $SettingsConfig['url'];
/*
		$this->dbuser = $SettingsConfig['dbuser'];
		$this->dbhost = $SettingsConfig['hostname'];
		$this->dbpass = $SettingsConfig['dbpass'];
		$this->dbname = $SettingsConfig['dbname'];
*/
 	}

	public function login()
	{
		$client = new Vtiger_WSClient($this->url);
		$login = $client->doLogin($this->username, $this->accesskey);

		return $client;
	}

	public function testLogin( $url , $username , $accesskey )
	{
                $client = new Vtiger_WSClient($url);
                $login = $client->doLogin($username, $accesskey);
		return $login;
	}

	public function getCrmFields( $module )
	{
                $client = $this->login();
                $recordInfo = $client->doDescribe($module);

                $config_fields = array();
                if($recordInfo)
                {
                        $j=0;
                        for($i=0;$i<count($recordInfo['fields']);$i++)
                        {
                                if($recordInfo['fields'][$i]['nullable']=="" && $recordInfo['fields'][$i]['editable']=="" ){
                                }
                                elseif($recordInfo['fields'][$i]['type']['name'] == 'reference'){
                                }
                                elseif($recordInfo['fields'][$i]['name'] == 'modifiedby' || $recordInfo['fields'][$i]['name'] == 'assigned_user_id' ){
                                }
                                else{
                                        $config_fields['fields'][$j] = $recordInfo['fields'][$i];
                                        $config_fields['fields'][$j]['order'] = $j;
                                        $config_fields['fields'][$j]['publish'] = 1;
                                        $config_fields['fields'][$j]['display_label'] = $recordInfo['fields'][$i]['label'];
                                        if($recordInfo['fields'][$i]['mandatory']==1)
                                        {
                                                $config_fields['fields'][$j]['wp_mandatory'] = 1;
                                                $config_fields['fields'][$j]['mandatory'] = 2;
                                        }
                                        else
                                        {
                                                $config_fields['fields'][$j]['wp_mandatory'] = 0;
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
 

                }
                return $config_fields;
	}

	public function getUsersList()
	{
                $query = "select user_name, id, first_name, last_name  from Users";
                $client = $this->login();
                $records = $client->doQuery($query);
                if($records) {
                        $columns = $client->getResultColumns($records);
                        foreach($records as $record) {
                                $user_details['user_name'][] = $record['user_name'];
                                $user_details['id'][] = $record['id'];
                                $user_details['first_name'][] = $record['first_name'];
                                $user_details['last_name'][] = $record['last_name'];
                        }
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
			$content_option.="<option id='{$users_list['id'][$i]}' value='{$users_list['id'][$i]}'";
			if($users_list['id'][$i] == $config_fields["assignedto"])
			{
				$content_option.=" selected";
			}
			$content_option.=">{$users_list['first_name'][$i]} {$users_list['last_name'][$i]}</option>";
		}
		$html .= $content_option;
		$html .= "</select> <span style='padding-left:15px; color:red;' id='assignedto_status'></span>";
		return $html;
	}

        public function assignedToFieldId()
        {
                return "assigned_user_id";
        }

        public function mapUserCaptureFields( $user_firstname , $user_lastname , $user_email )
        {
                $post = array();
                $post['firstname'] = $user_firstname;
                $post['lastname'] = $user_lastname;
                $post[$this->duplicateCheckEmailField()] = $user_email;
                return $post;
        }

        public function createRecordOnUserCapture( $module , $module_fields )
        {
		return $this->createRecord( $module , $module_fields );
	}

	public function createRecord( $module , $module_fields )
	{
		$module = "Leads";
		$client = $this->login();
		$record = $client->docreate( $module , $module_fields );
		if($record)
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

		$selected_module = $client->doRetrieve( "$ids_present" );
		$update_client = $module_fields;
		foreach($update_client as $key => $value){
			$selected_module[$key] = $value;
		}
		$record = $client->doUpdate($selected_module);

		if($record)
		{
			$data['result'] = "success";
			$data['failure'] = 0;
		}
		else
		{
			$data['result'] = "failure";
			$data['failure'] = 1;
			$data['reason'] = "failed updating entry";
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
                $count_query = "SELECT count(*) FROM $module";
                $records = $client->doQuery($count_query);
                $total = $records[0]['count'];
                for($i=0;$i<=$total;$i=$i+100)
                {
                          $query = "SELECT lastname, email FROM $module LIMIT $i , 100";
                          $records = $client->doQuery($query);
                          if($records) {
				  $columns = $client->getResultColumns($records);
				  if(is_array($records))
				  {
					  foreach($records as $record) {
						$result_lastnames[] = $record['lastname'];
						$result_emails[] = $record['email'];
						$result_ids[] = $record['id'];

						if($email == $record['email'])
						{
							$code = $record['id'];
							$email_present = "yes";
	//                                              break;
						}
					  }
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

	function duplicateCheckEmailField()
	{
		return "email";
	}
}
