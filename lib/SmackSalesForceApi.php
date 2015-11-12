<?php
if(!function_exists("Getaccess_token"))
{

function Getaccess_token( $config , $code ) {

	$token_url = "https://login.salesforce.com/services/oauth2/token";
//	$code = $_GET['code'];
//	$config = get_option ( 'smack_zoho_crm_settings' );
	if (!isset($code) || $code == "") {
	    die("Error - code parameter missing from request!");
	}
	$params = "code=" . $code
	    . "&grant_type=authorization_code"
	    . "&client_id=" . $config['key']
	    . "&client_secret=" . $config['secret']
	    . "&redirect_uri=" . urlencode($config['callback']);
	$curl = curl_init($token_url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
	$json_response = curl_exec($curl);

	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if ( $status != 200 ) {
	    die("Error: call to token URL $token_url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
	}
	curl_close($curl);

	$response = json_decode($json_response, true);
	
return $response;
}


function refresh_token( $config ) {

        $token_url = "https://login.salesforce.com/services/oauth2/token";
//$host ="https://login.salesforce.com/grant_type=refresh_token&client_id={$config['key']}&client_secret= {$config['secret']}&refresh_token={$config['access_token']}";

        $params = "grant_type=refresh_token"
            . "&client_id=" . $config['key']
            . "&client_secret=" . $config['secret']
            . "&refresh_token=" . $config['access_token'];

        $curl = curl_init($token_url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ( $status != 200 ) {
            die("Error: call to token URL $token_url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        curl_close($curl);

        $response = json_decode($json_response, true);
        return $response;
}


function GetCrmModuleFields( $instance_url, $access_token , $module = "Lead" )
{
	$url = "$instance_url/services/data/v20.0/sobjects/{$module}/describe/";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));

    $json_response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($json_response, true);
/*
echo "<pre>";
print_r($access_token);
echo "</pre>";    
*/
return $response;
}

function Getuser( $instance_url, $access_token , $module = "Lead" )
{
    $url = "$instance_url/services/data/v20.0/sobjects/user/";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));

    $json_response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($json_response, true);


    
return $response;
}

function create_record( $data_array, $instance_url, $access_token , $module = "Lead" ) {

    $url = "$instance_url/services/data/v20.0/sobjects/{$module}/";

    $content = json_encode($data_array);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token",
                "Content-type: application/json"));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

    $json_response = curl_exec($curl);
/*
echo '<pre>';
echo "access_token = {$access_token}<br>";
echo "instance_ur = {$instance_url}";
print_r($json_response);
echo '</pre>'; */

    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ( $status != 201 ) {
        die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
    }
    
  //  echo "HTTP status $status creating account<br/><br/>";

    curl_close($curl);

    $response = json_decode($json_response, true);
return $response;
/*	if(isset($response['id']))
	{
		return "success";
	}
	else
	{
		return "not success";
	}  */
}

}

?>
