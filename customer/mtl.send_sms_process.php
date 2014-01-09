<?php
/*

Copyright (c) 2014 Multitel LLC , Multitel Inc ( http://www.multitel.net ) under the MIT License (MIT)

The MIT License (MIT):

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

// 
include('lib/nusoap/nusoap.php');
include 'lib/customer.defines.php';
include 'lib/customer.module.access.php';
include 'mtl.config.php';

$QUERY = "SELECT * from cc_card where username='".$_SESSION['pr_login']."'";
$res = $DBHandle -> Execute($QUERY);
if ($res) $numrow = $res->RecordCount();
if ($numrow == 0) exit();
// and we store that info into $user_info
$user_info =$res->fetchRow();
$cc_card_id = $user_info[0];
// connect to db
$DBHandle  = DbConnect();
$from = $_POST['from'];
$to = $_POST['to'];
$text = $_POST['message'];	
			 					
$client = new nusoap_client($api_url);									 
$parameters=array(
	array(
		"UserName" => $api_user,
		"Password" => $api_pass,
		"From" => $from,
		"To" => $to,
		"Text" => $text				  				  				  			  
	)
);
$Res = array();
$Res = $client->call("SendSmsWs", $parameters,'urn:SendSmsWs');
				
				
$return_arr = $Res['DATA'][0];
$cause_code = $return_arr['CAUSECODE']; //0 success
$status_send_sms = $return_arr['STATUS']; 
$message_send_sms = $return_arr['MESSAGE']; 
				
if($cause_code == '0') {
	//get sms price
	$parameters=array(
		array(
			"UserName" => $api_user,
			"Password" => $api_pass,
			"Number" => $to,
		)
	);
        $Res = array();
        $Res = $client->call("GetSmsPriceWs", $parameters,'urn:GetSmsPriceWs');
				
				
	$return_arr = $Res['DATA'][0];
	$payment = $return_arr['COST'];
	if($user_info['typepaid'] == 0) {
		$QUERY = "update cc_card set credit=credit-$payment where username='".$_SESSION['pr_login']."'";
	} else {
		$QUERY = "update cc_card set credit=credit+$payment where username='".$_SESSION['pr_login']."'";
	}
	mysql_query($QUERY);
	//insert to local db
	mysql_query("insert into mtl_sent_sms(cc_card_id,src_nr,dst_nr,message,status) values('$cc_card_id','$from','$to','$text','$status_send_sms')");
	$message = 'Success Send SMS';
	$status = 'success';
} else {
	$message = $message_send_sms;
	$status = 'error';
}
				
				
$json = array(
	'status' => $status,
	'message' => $message,
);
echo json_encode($json);
return;
			
?>
