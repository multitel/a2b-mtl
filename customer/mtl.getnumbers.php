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

include('lib/nusoap/nusoap.php');
include('mtl.config.php');
include 'lib/customer.defines.php';
include 'lib/customer.module.access.php';
$DBHandle  = DbConnect();
// get account service info
$QUERY = "SELECT * from mtl_services where did_group_id='".$_SESSION['id_didgroup']."'";
$res = $DBHandle -> Execute($QUERY);

if ($res) $numrow = $res->RecordCount();


if ($numrow == 0) exit();
// and we store that info into $service_info
$service_info =$res->fetchRow();

//call the remote API

$client = new nusoap_client($api_url);
$prefix = stripslashes($_GET['prefix']);
$parameters=array(array(
        "UserName" => $api_user,
        "Password" => $api_pass,
        "CountryCode" => $prefix));
$Res = array();
$Res = $client->call("GetDidsWs", $parameters,'urn:GetDidsWs');

//$count =  count($Res['DATA']);
$datas = $Res['DATA'];
$total_data = count($datas);
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
		foreach($datas as $data)
		{
			if ($data['Number']!='-100') {
			 $row = array();
			 $setup_cost 	= setupcost($data['SetupCost'],$service_info);
			 $monthly_cost	= monthlycost($data['MonthlyCharges'],$service_info);
			 $permin_cost	= perminutecost($data['PerMinuteCharges'],$service_info);
			 $freeminutes	= freeminutes($data['FreeMinutes'],$service_info);
			 $row['id'] = $data['Number'];			 
//			 $row['uri'] = $data['Number'].'@'.$default_number_uri;			 
			 $row['uri'] = $data['Number'];			 
			 $row['number'] = $data['Number'];
			 $row['setup'] = $setup_cost;
			 $row['monthly'] = $monthly_cost;
			 $row['perminute'] = $permin_cost;
			 $row['free_minutes'] = $freeminutes;
			 $row['country'] = $data['Country'];
			 $row['DT_RowId'] = $data['Number'];			 			 
			 $output['aaData'][] = $row;
			}
		}

       echo json_encode($output);
/*
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


function setupcost($cost,$service_info) {
	$cost = $cost + ($cost * $service_info[3]/100);	// add the markup
        if ($cost < $service_info[4]) {       		// if marked up setup cost is still less than minimum setup fee
		return $service_info[4];      		// then return the minimum setup fee
	} else {                              		// otherwise...
		return $cost;                 		// return the marked up setup fee
	}
}

function monthlycost($cost,$service_info) {

 
	$cost = $cost + ($cost * $service_info[5]/100);	// add the markup
	if ($cost < $service_info[6]) { 		// if marked up monthly cost is still less than minimum monthly cost
		return $service_info[6];		// then return the minimum monthly cost
	} else { 					// otherwise...
		return $cost;				// return the marked up monthly fee
	}
}

function perminutecost($cost,$service_info) { 
	$cost = $cost + ($cost * $service_info[7]/100); // add the markup
	if ($cost < $service_info[8]) { 		// if marked up per minute cost is still less than minimum per minute cost
		return $service_info[8];		// then return the minimum per minute cost
	} else { 					// otherwise...
		return $cost;				// return the marked up per minute cost
	}
}

function freeminutes($minutes,$service_info) { 
	if ($minutes>$service_info[9]) { 		// if result from API (number of free minutes/month) is greater than what the service has set for this user
		return $service_info[9];		// then return the maximum number of free minutes we are willing to provide for this user
	} else { 					// otherwise..
		return $minutes;			// return the number of minutes provided by the API
	}
}


?>
