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
*/

$number = stripslashes($_GET['number']);
$url    = stripslashes($_GET['url']);
if (!$number) { die(0); }
if (!$url) { die(0); }


include('lib/nusoap/nusoap.php');
include('mtl.config.php');

include 'lib/customer.defines.php';
include 'lib/customer.module.access.php';
$DBHandle  = DbConnect();
$QUERY = "SELECT * from mtl_services where did_group_id='".$_SESSION['id_didgroup']."'";
$res = $DBHandle -> Execute($QUERY);

if ($res) $numrow = $res->RecordCount();
if ($numrow == 0) exit();
$service_info =$res->fetchRow();


$QUERY = "SELECT * from cc_card where username='".$_SESSION['pr_login']."'";
$res = $DBHandle -> Execute($QUERY);
if ($res) $numrow = $res->RecordCount();
if ($numrow == 0) exit();
$user_info =$res->fetchRow();

 


//call the remote API

// get balance and check if balance amount allows us to buy the number
$client = new nusoap_client($api_url,'wsdl');
$parameters=array(
      array(
      "UserName" => $api_user,
      "Password" => $api_pass
      )
      );
      $Res = array();
      $Res = $client->call("GetBalanceWs", $parameters,'urn:GetBalanceWs');

$count =  count($Res['DATA']);
$balance = $Res['DATA'][0]['RETURN'];
$payment = $_SESSION['MTL']['DID'][$number]['SetupCost'] + $_SESSION['MTL']['DID'][$number]['MonthlyCharges'];
$payment = (($payment>0)?$payment:10000000000000);

echo "Remote account BALANCE: ".$balance."<br>";
echo "To Pay for this number: ".$payment."<br>";

if ($balance<$payment) { 
die("ERROR: MTL - BTL.99");
}

// if user type is prepaid and the amount to pay is larger than available balance
if (($user_info[29]==0) && ($payment>$user_info[9])) { 
die("ERROR: LOC - BTL.99 [".$user_info[9]."]" ); // balance too low
}

#die('number purchased');

// if we didn't die in previous step, go ahead and order the number
$client = new nusoap_client($api_url);
$parameters=array(
        array( "UserName" => $api_user,
                "Password" => $api_pass,
                "Number" => $number,
                "URI" => $url,
                "Type" => "1"
        )
);
#$Res = array();
$Res = $client->call("BuyNumbersWs", $parameters,'urn:BuyNumbersWs');
$return = $Res['DATA'][0]['RETURN'];
if ($return != $number) { 
// process error codes


switch ($return1) { 
	case "-55" : die("ERROR: MTL - IUN.55"); 	// Inexistant user name	
	case "-110": die("ERROR: MTL - UPI.110");	// User password incorrect
	case "-165": die("ERROR: MTL - NIS.165");	// Number is already sold
	case "-220": die("ERROR: MTL - NIR.220");	// Number is reserved
	case "-275": die("ERROR: MTL - NNE.275");	// Number not existant
	case "-330": die("ERROR: MTL - CCNE.330");	// Country code does not exist
#	default: die("ERROR: ".$return);
   }
} 

function print_rr($content, $return=false) 
{
    $output = '<div style="border: 1px solid; height: 100px; resize: both; overflow: auto;"><pre>' 
        . print_r($content, true) . '</pre></div>';
 
    if ($return) {
        return $output;
    } else {
        echo $output;
    }
} 

print_rr($_SESSION['MTL']['DID'])."<br>";
$QUERY = "INSERT into cc_did set did='".$_SESSION['MTL']['DID'][$number]['Number']."',";
$QUERY.= "selling_rate='".$_SESSION['MTL']['DID'][$number]['PerMinuteChargess']."',";
$QUERY.= "aleg_carrier_cost_min='".$_SESSION['MTL']['DID'][$number]['PerMinuteCharges']."',";
$QUERY.= "iduser='".$user_info[0]."', activated=1,reserved=1,billingtype=1,";
$QUERY.= "fixrate='".$_SESSION['MTL']['DID'][$number]['PerMinuteCharges']."'";
$res = $DBHandle -> Execute($QUERY);

$QUERY = "SELECT * from cc_did where did='".$_SESSION['MTL']['DID'][$number]['Number']."' limit 1";
$res = $DBHandle -> Execute($QUERY);
if ($res) $numrow = $res->RecordCount();
//if ($numrow == 0) exit();
// and we store that info into $did_info
$did_info =$res->fetchRow();
echo $QUERY;

$QUERY = "INSERT into cc_did_destination set destination='".$url."', priority=1,id_cc_card='".$user_info[0]."',activated=1,voip_call=1,validated=1,id_cc_did='".$did_info[0]."'";
$res = $DBHandle -> Execute($QUERY);
echo $QUERY;

?>
