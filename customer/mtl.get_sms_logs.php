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

// get account info
$QUERY = "SELECT * from cc_card where username='".$_SESSION['pr_login']."'";
$res = $DBHandle -> Execute($QUERY);
if ($res) $numrow = $res->RecordCount();
if ($numrow == 0) exit();
// and we store that info into $user_info
$user_info =$res->fetchRow();
$cc_card_id = $user_info[0];
$cc_didgroup_id = $_SESSION['id_didgroup'];

 	 $run = mysql_query("select * from mtl_sent_sms where cc_card_id='$cc_card_id'") or die(mysql_error());
	 $total_data = mysql_num_rows($run);

      $iDisplayStart = $_GET['iDisplayStart'];
        $iDisplayLength = $_GET['iDisplayLength'];
        $iSortCol_0 = $_GET['iSortCol_0'];
        $iSortingCols = $_GET['iSortingCols'];
        $sSearch = $_GET['sSearch'];
        $sEcho = $_GET['sEcho'];
		
		$limit_str='';
		 // Paging
        if(isset($iDisplayStart) && $iDisplayLength != '-1')
        {
            //$this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
			$limit_str = 'limit '.$iDisplayStart.','.$iDisplayLength;
        }
		//echo "select * from mtl_sent_sms where cc_card_id='$cc_card_id' ".$limit_str;exit;
		$run = mysql_query("select * from mtl_sent_sms where cc_card_id='$cc_card_id' ".$limit_str) or die(mysql_error());
        while($row_data = mysql_fetch_assoc($run))
		{
			 $row = array();
			 $row['src_nr'] = $row_data['src_nr'];			 		 
			 $row['dst_nr'] = $row_data['dst_nr'];			 
			 $row['message'] = $row_data['message'];
			 $row['date_created'] = $row_data['date_sent'];			 		 		 			 
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
		/*
		if($default_number_uri = $this->customlib->getPreference($this->current_user->username,'default_number_uri'))
		{
		}
		else
		{
			$default_number_uri = $this->customlib->clientip();
		}
        */
		
			 $output['aaData'] = $aa_data;
		

       echo json_encode($output);
?>
