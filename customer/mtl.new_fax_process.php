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
include 'lib/customer.defines.php';
include 'lib/customer.module.access.php';
include 'mtl.config.php';

if ($_POST['sendtofaxnr']) { 
	//call the sendFax api
	$FromNumber = $_POST['sendfromnumber'];
	$ToNumber = $_POST['sendtofaxnr'];
	$client = new nusoap_client($api_url);									 
	$parameters=array(
		array(
			"UserName" => $api_user,
			"Password" => $api_pass,
			"FromNumber" => $FromNumber,
			"ToNumber"=>$ToNumber,
			"Message"=>'',
			"DateDue"=>'',	  				  				  			  
		)
	);
        $Res = array();
        $Res = $client->call("SendFaxWs", $parameters,'urn:SendFaxWs');
				
				
	$return_arr = $Res['DATA'][0];
	$return = $return_arr['RETURN']; // 1 success
				
	if($return > 0) {
		$status = "success";
                $message = "Success create new fax";
	} else {
		$status = "error";
                $message = "Failed create new fax";						
	}
					
	echo json_encode(
			array(
				'status' => $status,
				'message' => $message,					
			)
	);
}
		 
	 
?>
