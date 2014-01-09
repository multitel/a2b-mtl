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
?>
	<script type="text/javascript">
		function checkform() {
	//		if (document.getElementById('faxheader').value=='') { 
	//			alert('Please set a fax header');
	//			document.getElementById('faxheader').focus();
	//			return false;
	//		}
			return true;
		}
	</script>
<?
$run = mysql_query("select * from cc_did where iduser='".$user_info[0]."'");
?>    
 
 <div class="row-fluid">
				 <div class="span6">
                 
						<!-- Group -->
						<div class="control-group">
							<label for="" class="control-label">From number</label>
							<div class="controls">
                           <select class="span10" id="sendfromnumber" name="sendfromnumber">
				<?php while ($purchased_number = mysql_fetch_assoc($run)) { ?>
					<option value="<?=$purchased_number['did'];?>"><?=$purchased_number['did'];?></option>
				<?php } ?>
					</select>
                    
                    
                            </div>
						</div>
						                      
                        
						<div class="control-group">
							<label for="" class="control-label">From name</label>
							<div class="controls">
							<input class="span10 fiwIcon" type="text" role="textbox" name="sendfromname" id="sendfromname">
							</div>
						</div>   
                        
                              <div class="control-group">
							<label for="" class="control-label">From company</label>
							<div class="controls">
							<input class="span10 fiwIcon" type="text" role="textbox" name="sendfromcompany" id="sendfromcompany">
							</div>
						</div>
                        
                        <div class="control-group">
							<label for="" class="control-label">Callback number</label>
							<div class="controls">
                             <input type="text" name="callbacknumber" id="callbacknumber" />
							</div>
						</div>
                        
                        <div class="control-group">
							<label for="" class="control-label">ATTN to</label>
							<div class="controls">
							<input class="span10 fiwIcon" type="text" role="textbox" name="sendtoname" id="sendtoname">
							</div>
						</div>   
                 </div>
					<!-- Column -->
					<div class="span6">
					            
                        
                       <div class="control-group">
							<label for="" class="control-label">To company</label>
							<div class="controls">
							 <input class="span10 fiwIcon" type="text" role="textbox" name="sendtocompany" id="sendtocompany">
							</div>
						</div>
                        
                        <div class="control-group">
							<label for="" class="control-label">To fax number</label>
							<div class="controls">
							 <input class="span10 fiwIcon" type="text" role="textbox" name="sendtofaxnr" id="sendtofaxnr">
							</div>
						</div>
                        
                        <div class="control-group">
							<label for="" class="control-label">Send coverpage</label>
							<div class="controls">
							<input class="span10" type="checkbox" required="" name="sendcoverpage" id="sendcoverpage">
							</div>
						</div>
                        
                        <div class="control-group">
							<label for="" class="control-label">Subject</label>
							<div class="controls">
							<input class="span10 fiwIcon" type="text" role="textbox" name="subject" id="subject">
							</div>
						</div>
                        
                        <div class="control-group">
							<label for="" class="control-label">Attachement</label>
							<div class="controls">
                          
			       <input type="file" name="attachement" id="attachement">
							</div>
						</div>
                        
						
					</div>
					<!-- // Column END -->
					
					
				</div>
