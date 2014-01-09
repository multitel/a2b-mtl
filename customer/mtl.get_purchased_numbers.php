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

require_once('lib/nusoap/nusoap.php');
include('mtl.config.php');

include 'lib/customer.defines.php';
include 'lib/customer.module.access.php';

// connect to db
$DBHandle  = DbConnect();
// get account service info
$QUERY = "SELECT * from mtl_services where did_group_id='".$_SESSION['id_didgroup']."'";
$res = $DBHandle -> Execute($QUERY);

if ($res) $numrow = $res->RecordCount();


if ($numrow == 0) exit();
// and we store that info into $service_info
$service_info =$res->fetchRow();

// get account info
$QUERY = "SELECT * from cc_card where username='".$_SESSION['pr_login']."'";
$res = $DBHandle -> Execute($QUERY);
if ($res) $numrow = $res->RecordCount();
if ($numrow == 0) exit();
// and we store that info into $user_info
$user_info =$res->fetchRow();
$QUERY = "SELECT * from cc_did where provider='99' and iduser='".$user_info[0]."' and id_cc_didgroup='".$_SESSION['id_didgroup']."'";
$run = mysql_query($QUERY);

//$res = $DBHandle -> Execute($QUERY);
//number, selling_rate , fixrate and creationdate

//$count =  count($Res['DATA']);

$total_data = mysql_num_rows($run);

      $iDisplayStart = $_GET['iDisplayStart'];
        $iDisplayLength = $_GET['iDisplayLength'];
        $iSortCol_0 = $_GET['iSortCol_0'];
        $iSortingCols = $_GET['iSortingCols'];
        $sSearch = $_GET['sSearch'];
        $sEcho = $_GET['sEcho'];
        
    
        // Data set length after filtering
        $iFilteredTotal = $total_data;
    
        // Total data set length
        $iTotal = $total_data;
    
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );
		/*
		if($default_number_uri = $this->customlib->getPreference($this->current_user->username,'default_number_uri'))
		{
		}
		else
		{
			$default_number_uri = $this->customlib->clientip();
		}
        */
		while($data = mysql_fetch_assoc($run))
		{

			
			 $row = array();
			 $number 	= $data['did'];
			 $row['number'] = $number;			 
//			 $row['uri'] = $data['Number'].'@'.$default_number_uri;			 
			 $row['selling_rate'] = $data['selling_rate'];			 
			 $row['fixrate'] = $data['fixrate'];
			 $row['creationdate'] = $data['creationdate'];			 			 

			 $output['aaData'][] = $row;
		}

       echo json_encode($output);
/*
// below output usable for ExtJS based pages
for ($iIndex=0;$iIndex<$count;$iIndex++) {
    if ($Res['DATA'][$iIndex]['Number']!='') { 
	// keeping the below $_SESSION values to be used in the purchase action, 
	// since the purchase action does not return cost and free minutes and other info
	// so if we don't store this in $_SESSION we would have to make a new API call to get number info before purchase action
	// therefore it is faster to store this in SESSION than it is to call the API once more

	$setup_cost 	= setupcost($Res['DATA'][$iIndex]['SetupCost'],$service_info);
	$monthly_cost	= monthlycost($Res['DATA'][$iIndex]['MonthlyCharges'],$service_info);
	$permin_cost	= perminutecost($Res['DATA'][$iIndex]['PerMinuteCharges'],$service_info);
	$freeminutes	= freeminutes($Res['DATA'][$iIndex]['FreeMinutes'],$service_info);

	$number = $Res['DATA'][$iIndex]['Number'];
	$_SESSION['MTL']['DID'][$number]['Number']		= $Res['DATA'][$iIndex]['Number'];
	$_SESSION['MTL']['DID'][$number]['SetupCost'] 		= $setup_cost;
	$_SESSION['MTL']['DID'][$number]['MonthlyCharges'] 	= $monthly_cost;
	$_SESSION['MTL']['DID'][$number]['PerMinuteCharges']	= $permin_cost;
	$_SESSION['MTL']['DID'][$number]['Country']		= $Res['DATA'][$iIndex]['Country'];
	$_SESSION['MTL']['DID'][$number]['Area']		= $Res['DATA'][$iIndex]['Area'];
	$_SESSION['MTL']['DID'][$number]['CountryCode']		= $Res['DATA'][$iIndex]['CountryCode'];
	$_SESSION['MTL']['DID'][$number]['StateCode']		= $Res['DATA'][$iIndex]['StateCode'];
	$_SESSION['MTL']['DID'][$number]['FreeMinutes']		= $freeminutes;



	// marking up what we show in customer interface
	$Res['DATA'][$iIndex]['SetupCost'] 			= $setup_cost;
	$Res['DATA'][$iIndex]['MonthlyCharges'] 		= $monthly_cost;
	$Res['DATA'][$iIndex]['PerMinuteCharges'] 		= $permin_cost;
	$Res['DATA'][$iIndex]['FreeMinutes']			= $freeminutes;
    }
}
*/
//echo json_encode($Res);

//print_r($_SESSION);
//echo "login: ".$_SESSION['pr_login']."<br>";
//echo "card_id: ".$_SESSION['card_id']."<br>";
//echo "ui_lang: ".$_SESSION['ui_language']."<br>";
//echo "did_grp: ".$_SESSION['id_didgroup']."<br>";

?>
