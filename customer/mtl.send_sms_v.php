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
?>
<div id="form_container">
  <p>
	<label>From :</label>
    	<?
	$QUERY = "SELECT * from cc_did where iduser='".$user_info[0]."' and id_cc_didgroup='".$_SESSION['id_didgroup']."'";
	$run = mysql_query($QUERY) or die(mysql_error());
	?>
 	<select name="from" id="from">
    	<? 

	while($row = mysql_fetch_assoc($run)) {
	?>
            <option value="<?=$row['did'];?>"><?=$row['did'];?></option>
        <? } ?>
    </select>
  </p>
  <p>
    <label>To : </label>
  <input class="required" name="to" id="to">
  </p>
  <p>
    <label>Message : </label>  
  <textarea class="required" id="message" name="message"></textarea>
  </p>



</div>
