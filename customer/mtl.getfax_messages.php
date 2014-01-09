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

// we load A2B libraries
include 'lib/customer.defines.php';
include 'lib/customer.module.access.php';
//include 'lib/Class.RateEngine.php';
//include 'lib/customer.smarty.php';

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
$cc_card_id = $user_info[0];
$cc_didgroup_id = $_SESSION['id_didgroup'];
$QUERY = "SELECT * from cc_did where iduser='".$user_info[0]."' and id_cc_didgroup='".$_SESSION['id_didgroup']."'";

$run = mysql_query($QUERY);
$aa_data = array();
while($row = mysql_fetch_assoc($run))
		{
			$cc_did_id = $row['id'];
			$number = $row['did'];
			
//call the remote API
$client = new nusoap_client($api_url);

$parameters=array(array(
        "UserName" => $api_user,
        "Password" => $api_pass,
        "Number" => $number));
$Res = array();
$Res = $client->call("GetReceivedFaxesWs", $parameters,'urn:GetReceivedFaxesWs');

//$count =  count($Res['DATA']);
$datas = $Res['DATA'];

foreach($datas as $data) {
	$fax_nr = $data['FAX_NR'];			 		 
	$src_nr = $data['SRC_NR'];			 
	$duration = $data['DURATION'];
	$pages = $data['PAGES'];
	$cost = $data['COST'];	
	$bitrate = $data['BITRATE'];				 		 
	$received = $data['RCV_TIME'];
	$mtl_fax_id = $data['ID'];			 
	$run_check = mysql_query("select * from mtl_rcvd_fax where mtl_fax_id='$mtl_fax_id'");
	if(mysql_num_rows($run_check) < 1) {
		$query_ins = "insert into mtl_rcvd_fax (cc_card_id,cc_did_id,cc_didgroup_id,fax_nr,src_nr,duration,pages,cost,bitrate,pdf,received,mtl_fax_id) values('$cc_card_id', '$cc_did_id','$cc_didgroup_id','$fax_nr','$src_nr','$duration','$pages','$cost','$bitrate','$fax_nr','$received','$mtl_fax_id')";
		mysql_query($query_ins) or die(mysql_error());
	}
}

		}
//$total_data = count($aa_data);

$run = mysql_query("select * from mtl_rcvd_fax where cc_card_id='$cc_card_id'");
$total_data = mysql_num_rows($run);

$iDisplayStart = $_GET['iDisplayStart'];
$iDisplayLength = $_GET['iDisplayLength'];
$iSortCol_0 = $_GET['iSortCol_0'];
$iSortingCols = $_GET['iSortingCols'];
$sSearch = $_GET['sSearch'];
$sEcho = $_GET['sEcho'];
		
$limit_str='';
// Paging
if(isset($iDisplayStart) && $iDisplayLength != '-1') {
	//$this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
	$limit_str = 'limit '.$iDisplayStart.','.$iDisplayLength;
}

$run = mysql_query("select * from mtl_rcvd_fax where cc_card_id='$cc_card_id' ".$limit_str);
while($row_data = mysql_fetch_assoc($run)) {
	$row = array();
	$row['fax_nr'] = $row_data['fax_nr'];			 		 
	$row['src_nr'] = $row_data['src_nr'];			 
	$row['duration'] = $row_data['duration'];
	$row['pages'] = $row_data['pages'];
	$row['cost'] = $row_data['cost'];	
	$row['bitrate'] = $row_data['bitrate'];				 		 
	$row['mtl_fax_id'] = $row_data['mtl_fax_id'];				 		 			 
	$row['DT_RowId'] = $row_data['id'];			 
        $aa_data[] = $row;
}
    
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
$output['aaData'] = $aa_data;
echo json_encode($output);
?>
