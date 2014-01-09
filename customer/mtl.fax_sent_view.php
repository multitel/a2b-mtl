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

// we load A2B libraries
include 'lib/customer.defines.php';
include 'lib/customer.module.access.php';
//include 'lib/Class.RateEngine.php';
//include 'lib/customer.smarty.php';
		
//call the remote API
$client = new nusoap_client($api_url);
$mtl_fax_id = $_GET['mtl_fax_id'];
$parameters=array(array(
        "UserName" => $api_user,
        "Password" => $api_pass,
        "Id" => $mtl_fax_id));
$Res = array();
$Res = $client->call("GetSentFaxWs", $parameters,'urn:GetSentFaxWs');

$data = $Res['DATA'][0];
$expires = 60*60*24*14;
		   header("Content-Transfer-Encoding: binary");
		   header('Content-Type: application/pdf');
		   echo $data['PDF'];
?> 
