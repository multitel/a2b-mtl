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
// get account service info
$QUERY = "SELECT * from mtl_services where did_group_id='".$_SESSION['id_didgroup']."'";
$res = $DBHandle -> Execute($QUERY);

if ($res) $numrow = $res->RecordCount();
if ($numrow == 0) exit();
// and we store that info into $service_info
$service_info =$res->fetchRow();
$fax_price = $service_info['fax_price'];
$e911_price = $service_info['e911_price'];
// get account info
$QUERY = "SELECT * from cc_card where username='".$_SESSION['pr_login']."'";
$res = $DBHandle -> Execute($QUERY);
if ($res) $numrow = $res->RecordCount();
if ($numrow == 0) exit();
// and we store that info into $user_info
$user_info =$res->fetchRow();
$firstname = $user_info['firstname'];
$lastname = $user_info['lastname'];
$number = $_GET['number'];
$us_number = false; if(substr($_GET['number'],0,1) == 1) { $us_number = true; }
$setup = $_GET['setup'];
$monthly = $_GET['monthly'];
$perminute = $_GET['perminute'];
$free_minutes = $_GET['free_minutes'];
$forward_destination = 1;
$uri = $number.'@'.getmyip();	
$record = 0;
$screening = 0;
$atcd =0;
$ring_type =1;

if (isset($_POST['number'])) {	
	$error = false;
	$errors = '';
	$e911 = $_POST['e911'];
	if($e911 == 1) { 
		if(empty($_POST['callername'])) { 
	   		$error = true;
		   	$errors .= '<p>Caller Name Is Required</p>';
	   	}
	  	if(empty($_POST['address'])) {
			$error = true;
		   	$errors .= '<p>Address Is Required</p>';
	   	}
	  	if(empty($_POST['city'])) {
			$error = true;
		   	$errors .= '<p>City Is Required</p>';
	  	}
	  	if(empty($_POST['state'])) {
			$error = true;
		   	$errors .= '<p>State Is Required</p>';
	  	}
	  	if(empty($_POST['zip'])) {
		   	$error = true;
		   	$errors .= '<p>Zip Is Required</p>';
	   	}
	}
	  
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
	$payment = $setup + $monthly;

	$selling_rate = $monthly;

	if($e911 == 1) {
		$selling_rate += $e911_price;
		$payment += $e911_price;
	}

	if($FaxDetect == 1) {
		$selling_rate += $fax_price;
		$payment += $fax_price;	
	}

	$payment = (($payment>0)?$payment:10000000000000);

	//echo "Remote account BALANCE: ".$balance."<br>";
	//echo "To Pay for this number: ".$payment."<br>";

	if ($balance<$payment) { 
		//die("ERROR: MTL - BTL.99");
 		$error = true;
		$errors .= '<p>Balance Not Enough, current did balance :'.$balance.', total payment :'.$payment.'</p>';
	}

	// if user type is prepaid and the amount to pay is larger than available balance
	if (($user_info[29]==0) && ($payment>$user_info[9])) { 
		//die("ERROR: LOC - BTL.99 [".$user_info[9]."]" ); // balance too low
 		$error = true;
		$errors .= '<p>Balance Too Low, current balance :'.$user_info[9].', total payment :'.$payment.'</p>';
	}
	  	 
	
	if (!$error) {
		$description = $_POST['description'];
		$number = $_POST['number'];
		$FaxDetect = $_POST['faxdetect'];
		$FaxEmail = $_POST['faxemail'];
		$FaxRing = $_POST['faxring'];
		$Vm = '';
		$Vmring = $_POST['vmring'];
		$Vms2t = $_POST['vms2t'];
		$Vmemail = $_POST['vmemail'];
		$Cnam = $_POST['cnam'];			
		$URI2 = '';		
		$URI3 = '';		
		$RingType = $ring_type;
		$ringstyle = $_POST['ringstyle'];																				    $type = $forward_destination;			 //type id 
		$client = new nusoap_client($api_url);
		$data_param =  array(
			 	"UserName" => $api_user,
		        	"Password" => $api_pass,
			   	"Number"=>$number,
			   	"URI"=> $uri,
			   	"URI2"=> $URI2,
			   	"URI3"=> $URI3,
			   	"RingType"=> $RingType,			   			   			   
			   	"FaxDetect"=>$FaxDetect,
			   	"FaxEmail"=>$FaxEmail,
			   	"FaxRing"=>$FaxRing,
			   	"Vm"=>$Vm,
			   	"Vmring"=>$Vmring,
			   	"Vms2t"=>$Vms2t,
			   	"Vmemail"=>$Vmemail,
			   	"Cnam"=>$Cnam,
			   	"Type"=>$type,
			   	"Description" =>$description,	
			   	"Record"=>$record,	
			   	"Screening"=>$screening,	
			   	"Ringstyle"=>$ringstyle,				   			   			   		   			   			   );
		$parameters=array(
				$data_param
		);
		// print_r($parameters);exit;
                $Res = array();
                $Res = $client->call("BuyNumbersWs", $parameters,'urn:BuyNumbersWs');
		//echo json_encode($Res);
				
		$return_arr = $Res['DATA'][0];
		$return = $return_arr['RETURN']; 
				
		if($return == $number) {

		//insert to cc destination
		$QUERY = "INSERT into cc_did set did='".$number."',";
		$QUERY.= "selling_rate='".$selling_rate."',";
		$QUERY.= "id_cc_didgroup='".$_SESSION['id_didgroup']."',";
		$QUERY.= "aleg_carrier_cost_min='".$perminute."',";
		$QUERY.= "iduser='".$user_info[0]."', activated=1,reserved=1,billingtype=1,provider=99,";
		$QUERY.= "fixrate='".$perminute."'";
		$res = $DBHandle -> Execute($QUERY);

		$QUERY = "SELECT * from cc_did where did='".$number."' limit 1";
		$res = $DBHandle -> Execute($QUERY);
		if ($res) $numrow = $res->RecordCount();
		//if ($numrow == 0) exit();
		// and we store that info into $did_info
		$did_info =$res->fetchRow();
		//echo $QUERY;

		$QUERY = "INSERT into cc_did_destination set destination='".$uri."', priority=1,id_cc_card='".$user_info[0]."',activated=1,voip_call=1,validated=1,id_cc_did='".$did_info[0]."'";
		$res = $DBHandle -> Execute($QUERY);
		//echo $QUERY;
		if($user_info['typepaid'] == 0) {
			$QUERY = "update cc_card set credit=credit-$payment where username='".$_SESSION['pr_login']."'";
		} else {
			$QUERY = "update cc_card set credit=credit+$payment where username='".$_SESSION['pr_login']."'";
		}
		$res = $DBHandle -> Execute($QUERY);
		//close insert
		if($e911 == 0) {
			$status ='success';
			$message ='Success Purchase Number #'.$number;
		} else {
			$address = $_POST['address'];
			$address2 = $_POST['address2'];
			$city = $_POST['city'];
			$state = $_POST['state'];
			$zip = $_POST['zip'];
			$callername = $_POST['callername'];			 			 
			$client = new nusoap_client($api_url);									 
			$parameters=array(
				array(
					"UserName" => $api_user,
					"Password" => $api_pass,
					"UniqueId" => '',
					"Address"=>$address,
					"Address2"=>$address2,
					"City"=>$city,
					"State"=>$state,
					"Zip"=>$zip,
					"Number"=>$number,
					"Callername"=>$callername			  				  				  			  
				)
			);
			$Res = array();
			$Res = $client->call("ProvisionAddressWs", $parameters,'urn:ProvisionAddressWs');
			$return_arr = $Res['DATA'][0];
			$return_provision = $return_arr['RETURN']; // 1 success
							
			if($return_provision == '1') {
				$status ='success';
				$message ='Success Purchase Number and Porivison Address #'.$number;
			} else {
				$status ='success';
				$message ='Success Purchase Number but Failed Porivison Address #'.$number;
			}
							
		}
	} else if($return == '99') {  //not enough balance or not allowed to purchased
			$status ='error';
			$message ='Failed Purchase Number #'.$number.',Not Enough Balance or Not Allowed to Do Transaction';
		} else { //should be 100 authentication failed
			$status ='error';
			$message ='Failed Purchase Number #'.$number.', Return :'.$return;
		}
		echo json_encode(
			array(
				'status'	=> $status,
				'message'	=> $message,	
			)
		);
		exit;
	} else {
		echo json_encode(
			array(
				'message'	=> $errors,
				'status'	=> 'error'
			)
		);
		exit;
	}
}
		 	 		
$description = $firstname." ".$lastname;		 
		
?>     

<script type="text/javascript">
$(function() {
	$('#tabs').tabs();
	
	$('.e911_select').change(function()
	{
		var e911_val = $(this).val();
		if(e911_val == 1)
		{
			$('.e911_link').show();
			//$('.e911').show();			
		}
		else
		{
			$('.e911_link').hide();
			//$('.e911').hide();						
		}
	});
	
$("#forward_destination").change(function()
{
	var forward_destination_id = $(this).val();
	if(forward_destination_id =='')
	{
			$(".uri").hide();
		$(".voicemail").hide();	
	}
	else if(forward_destination_id == 5 || forward_destination_id== 11)
	{
	$(".uri").hide();
	$(".voicemail").show();			
	}
	else
	{
	$(".uri").show();
	$(".voicemail").hide();	
	}
	
	
});

$("#forward_destination").trigger('change');

})
</script>


<div class="tabs" id="tabs">
<ul>
  <li><a href="#tabs-1">Number Info</a></li> 
 
  <li><a href="#tabs-2">Fax Settings</a></li>
  <li style="display:none" class="e911_link"><a href="#e911">E911</a></li>  
</ul>
<fieldset>
<div id="tabs-1">
 <div class="row-fluid">
  <div class="span4">
  <input type="hidden" name="number" value="<?=$number;?>">
  
  
  <div class="control-group">
		<label class="control-label">Name</label>
		<div class="controls">
			<input type="text" name="description" value="<?=$description;?>">
		</div>
	</div>
    
  
    
    
    
    <div class="control-group">
		<label class="control-label">Cnam</label>
		<div class="controls">
         	<select name="cnam">
             <option value="0" selected>No</option>
             <option value="1">Yes</option>             
            </select>
		</div>
	</div>
    
    
    <? if($us_number) { ?>
    <div class="control-group">
		<label class="control-label">E911</label>
		<div class="controls">
         	<select name="e911" class="e911_select">
            <option value="0">No</option>
            <option value="1">Yes</option>

            </select>
		</div>
	</div>
    <? } else { ?>
        <input type="hidden" name="e911" value="0" />
    <? } ?>
    
    
    </div>
  
   <div class="span4">

	 
    
    
    
    <div class="control-group">
		<label class="control-label">Ring Style</label>
		<div class="controls">
         	<select name="ringstyle">
            <option value="1" selected>Ring</option>
            <option value="2">Music</option>
            </select>
		</div>
	</div>
       
    
    
    
    
    
    
    
    

    
    
    
                                            
	
  </div>
  <div class="span4">
                                      
	<div class="control-group">
		<label class="control-label">Setup Fee</label>
		<div class="controls">
			<?=$setup;?>
		</div>
	</div>    
                     
	                     
        
         <div class="control-group">
		<label class="control-label">Monthly Fee</label>
		<div class="controls">
			<?=$monthly;?>
		</div>
		</div>
        
        <div class="control-group">
		<label class="control-label">Perminutes</label>
		<div class="controls">
			<?=$perminute;?>
		</div>
		</div>
        
    
  </div>  
	
        
	    
    </div>  
             
</div>
<div id="tabs-2">
  <div class="row-fluid">	
  
  <div class="control-group">
		<label class="control-label">Fax Detect(<?=$fax_price;?>/month)</label>
		<div class="controls">
        <select name="faxdetect">
             <option value="0">No</option>
             <option value="1">Yes</option>             
            </select>
		</div>
	</div>
  
	<div class="control-group">
		<label class="control-label">Fax Ring</label>
		<div class="controls">
			<input type="text" class="span5" name="faxring" value="">
		</div>
	</div>  
                                            
	<div class="control-group">
		<label class="control-label">Fax Email</label>
		<div class="controls">
			<input type="text" class="span5" name="faxemail" value="">
		</div>
	</div>  
	
                               
                                            
	     
    </div>                                                                                                                                                                                                                                                                                                 
</div>

<div id="e911" class="e911" style="display:none">

<div class="control-group">
		<label class="control-label">Caller Name</label>
		<div class="controls">
			<input class="required" id="callername" name="callername" value="<?=$user_info["firstname"].' '.$user_info["lastname"];?>">
		</div>
	</div>
    
    <div class="control-group">
		<label class="control-label">Address</label>
		<div class="controls">
			<input class="required" name="address" id="address" value="<?=$user_info['address'];?>">
		</div>
	</div>
    
    <div class="control-group">
		<label class="control-label">Address2</label>
		<div class="controls">
			 <input class="required" name="address2" id="address2">
		</div>
	</div>
    
    
    <div class="control-group">
		<label class="control-label">City</label>
		<div class="controls">
		 <input class="required" id="city" name="city" value="<?=$user_info['city'];?>">
		</div>
	</div>
    
    
    <div class="control-group">
		<label class="control-label">State</label>
		<div class="controls">
			 <input class="required" id="state" name="state" value="<?=$user_info['state'];?>">
		</div>
	</div>
    
    <div class="control-group">
		<label class="control-label">Zip</label>
		<div class="controls">
			  <input class="required" id="zip" name="zip" value="<?=$user_info['zipcode'];?>">
		</div>
	</div>

</div>
</fieldset>
</div>
 


<?
   function getmyip() {
            $myip = `ifconfig | grep  inet | grep -v '127.0.0' | grep -v '::' | awk {'print $2'} | grep -m 1 '.' | cut -d':' -f2`;
            $myip = str_replace('addr','',$myip);  # strip extra junk
            $myip = str_replace(':','',$myip);     #
            $myip = str_replace(' ','',$myip);     #
            $myip = str_replace('\n','',$myip);     #
            $myip = str_replace('\r','',$myip);     #
            $myip = str_replace('\r\n','',$myip);     #
            $myip = str_replace('inet','',$myip);  #
            $myip = substr($myip,0,14);
            $myip = trim($myip);
            return $myip;
        }
?>
