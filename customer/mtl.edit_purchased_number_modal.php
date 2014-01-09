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
include('lib/customer.defines.php');
include('lib/customer.module.access.php');
include('mtl.config.php');

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
// get account info
$QUERY = "SELECT * from cc_card where username='".$_SESSION['pr_login']."'";
$res = $DBHandle -> Execute($QUERY);
if ($res) $numrow = $res->RecordCount();
if ($numrow == 0) exit();
// and we store that info into $user_info
$user_info =$res->fetchRow();
$firstname = $user_info['firstname'];
$lastname = $user_info['lastname'];
$description = $firstname." ".$lastname;

$forward_destination = 1;
		 $uri = $number.'@'.getenv("REMOTE_ADDR");	
		 $record = 0;
		 $screening = 0;
		 $atcd =0;
		 $ring_type =1;
		 
		 $number = $_GET['number'];
			 $us_number = false;		 
		 if(substr($_GET['number'],0,1) == 1)
		 {
			 $us_number = true;
		 }
		 
		 
		 		
	
	  if (isset($_POST['number'])) {	
	  $error = false;
	  $errors = '';
	  $e911 = $_POST['e911'];
	  if($e911 == 1)
	  { 
	   if(empty($_POST['callername']))
	   { 
	   	   $error = true;
		   $errors .= '<p>Caller Name Is Required</p>';
	   }
	  	   if(empty($_POST['address']))
	   {
		   $error = true;
		   $errors .= '<p>Address Is Required</p>';
	   }
	  	   if(empty($_POST['city']))
	   {
		   $error = true;
		   $errors .= '<p>City Is Required</p>';
	   }
	  	   if(empty($_POST['state']))
	   {
		   $error = true;
		   $errors .= '<p>State Is Required</p>';
	   }
	  	   if(empty($_POST['zip']))
	   {
		   $error = true;
		   $errors .= '<p>Zip Is Required</p>';
	   }
	  }
	  
	 
	
		if (!$error)
		{
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
			$ringstyle = $_POST['ringstyle'];																			
			
			
			 $type = $forward_destination;			 //type id 
			 
			  $client = new nusoap_client($api_url);
			  $data_param =  array(
			   "UserName" => $api_user,
		           "Password" => $api_pass,
			   "Number"=>$number,		   			   			   
			   "FaxDetect"=>$FaxDetect,
			   "FaxEmail"=>$FaxEmail,
			   "FaxRing"=>$FaxRing,
			   "Vm"=>$Vm,
			   "Vmring"=>$Vmring,
			   "Vms2t"=>$Vms2t,
			   "Vmemail"=>$Vmemail,
			   "Uri"=> $uri,
			   "Uri2"=> $URI2,
			   "Uri3"=> $URI3,			   			   
			   "Cnam"=>$Cnam,
			   "Description" =>$description,	
			   "RingType" =>$RingType,				   			   
			   "Record"=>$record,	
			   "Screening"=>$screening,				   			   
			   "Ringstyle"=>$ringstyle,				   			   			   		   			   			   		   			   			   			   			   			   
			   );
			   //print_r($data_param);exit;
			    $parameters=array(
			  $data_param
			   );
			  // print_r($parameters);exit;
                $Res = array();
                $Res = $client->call("ModifyNumberWs", $parameters,'urn:ModifyNumberWs');
				//echo json_encode($Res);
				
				$return_arr = $Res['DATA'][0];
				$return = $return_arr['RETURN']; 
				
				 $client = new nusoap_client($api_url);
			  $data_param =  array(
			    "UserName" => $api_user,
		        "Password" => $api_pass,
			   "Number"=>$number,			   			   			   		   			   			   			      			   			   			   			   
			   );
			   //print_r($data_param);exit;
			    $parameters=array(
			  $data_param
			   );
			  // print_r($parameters);exit;
                $Res = array();
                $Res = $client->call("GetDidInfoWs", $parameters,'urn:GetDidInfoWs');
				$return_arr = $Res['DATA'][0];
				$monthly = $return_arr['MonthlyCharges'];
				
				$selling_rate = $monthly;
					
					if($e911 == 1)
					{
						$selling_rate += $e911_price;
					}
					if($FaxDetect == 1)
					{
						$selling_rate += $fax_price;
					}
					//insert to cc destination
					$QUERY = "update cc_did set selling_rate ='".$selling_rate."' where did='".$number."',";
					$res = $DBHandle -> Execute($QUERY);
				
				if($e911 == 1)
					{
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
							
							if($return_provision == '1')
							{
								$status ='success';
								$message ='Success Edit Number and Provison Address #'.$number;
							}
							else
							{
								$status ='success';
								$message ='Success Edit Number but Failed Provison Address #'.$number;
							}
				}
				else
				{
					//check e911
					  $client = new nusoap_client($api_url);
					  $data_param =  array(
						"UserName" => $api_user,
					        "Password" => $api_pass,
			   			"Number"=>$number,	   			   			   			   			   			   		   			       );
			   //print_r($data_param);exit;
			    $parameters=array(
			  $data_param
			   );
			  // print_r($parameters);exit;
                $Res = array();
                $Res = $client->call("CheckE911ProvisionWs", $parameters,'urn:CheckE911ProvisionWs');
				//echo json_encode($Res);
				
				$return_arr = $Res['DATA'][0];
				$return = $return_arr['RETURN']; 	
				$is_e911 = false;
				if($return == 1)
				{
				$is_e911 = true;					
				}
					if($is_e911) //delete the provision
					{
						$client = new nusoap_client($api_url);
			
						$parameters=array(
						  array(
							//"UserName" => $this->session->userdata('username'),
					   		//"Password" => $this->session->userdata('ori_password'),
							"UserName" => $api_user,
							"Password" => $api_pass,
					   		"Number"=>$number				  
						  )
						 );
						$Res = array();
						$Res = $client->call("removeRecordWs", $parameters,'urn:removeRecordWs');
						
						$status ='success';
					$message ='Success Edit Number and remove E911 #'.$number;
					}
					else
					{
					$status ='success';
					$message ='Success Edit Number #'.$number;
					}
				}
			echo json_encode(
				array(
				'status'	=> $status,
				'message'	=> $message,
				
				)
				);
				exit;
		}
		else
		{
			
				
				echo json_encode(
				array(
					'message'	=> $errors,
					'status'	=> 'error'
				)
				);
				exit;
			
			
		}
	  }
		 	 		
		 
		    
		$query = "select * from cc_did where did='$number'";
		 $run = mysql_query($query);
		 $purchased_number = mysql_fetch_assoc($run);
		 
		 $client = new nusoap_client($api_url);
			  $data_param =  array(
			    	"UserName" => $api_user,
		        	"Password" => $api_pass,
			   	"Number"=>$number,			   			   			   		   			   			   			   			);
			   //print_r($data_param);exit;
			    $parameters=array(
			  $data_param
			   );
			  // print_r($parameters);exit;
                $Res = array();
                $Res = $client->call("GetDidInfoWs", $parameters,'urn:GetDidInfoWs');
				$return_arr = $Res['DATA'][0];
				
				$description = $return_arr['Description'];
				$cnam = $return_arr['Cnam'];
				$ring_style = $return_arr['RingStyle'];	
				$setup = $return_arr['SetupCost'];
				$monthly = $return_arr['MonthlyCharges'];
				$perminute = $return_arr['PerMinuteCharges'];
				$free_minutes = $return_arr['FreeMinutes'];	
				$faxring = $return_arr['FaxRing'];	
				$faxemail = $return_arr['FaxEmail'];									
				$faxdetect = $return_arr['FaxDetect'];						
		
			// $client = new nusoap_client($api_url);
			  $data_param =  array(
			    "UserName" => $api_user,
		            "Password" => $api_pass,
			    "Number"=>$number,	   			   			   			   			   			   			   		   			   
			   );
			   //print_r($data_param);exit;
			    $parameters=array(
			  $data_param
			   );
			  // print_r($parameters);exit;
                $Res = array();
                $Res = $client->call("CheckE911ProvisionWs", $parameters,'urn:CheckE911ProvisionWs');
				//echo json_encode($Res);
				
				$return_arr = $Res['DATA'][0];
				$return = $return_arr['RETURN']; 	
				$is_e911 = false;
				if($return == 1)
				{
				$is_e911 = true;
				//get info		
				  $data_param =  array(
			   		"UserName" => $api_user,
		        		"Password" => $api_pass,
			   		"Number"=>$number,	   			   			   			   			   			   			   			   );
			   //print_r($data_param);exit;
			    $parameters=array(
			  $data_param
			   );
			  // print_r($parameters);exit;
                $Res = array();
                $Res = $client->call("ProvisionDetailsWs", $parameters,'urn:ProvisionDetailsWs');
				//echo json_encode($Res);
				
				$return_arr_e911 = $Res['DATA'][0];
				$callername = $return_arr_e911['callername'];
				$address = $return_arr_e911['address'];
				$address2 = $return_arr_e911['address2'];
				$state = $return_arr_e911['state'];
				$city = $return_arr_e911['city'];
				$zip = $return_arr_e911['zip'];
				}
		
		
		 
	 
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
		<label class="control-label">Description</label>
		<div class="controls">
			<input type="text" name="description" value="<?=$description;?>">
		</div>
	</div>
    
  
    
    
    
    <div class="control-group">
		<label class="control-label">Cnam</label>
		<div class="controls">
         	<select name="cnam">
             <option value="0" <? if($cnam == 0){?>selected<? }?>>No</option>
             <option value="1" <? if($cnam == 1){?>selected<? }?>>Yes</option>             
            </select>
		</div>
	</div>
    
    
    <? if($us_number)
	{
		?>
    <div class="control-group">
		<label class="control-label">E911</label>
		<div class="controls">
         	<select name="e911" class="e911_select">
            <option value="0" <? if($is_e911){?>selected<? }?>>No</option>
            <option value="1" <? if($is_e911){?>selected<? }?>>Yes</option>

            </select>
		</div>
	</div>
    <?
	}
	else
	{
		?>
        <input type="hidden" name="e911" value="0" />
        <?
	}
	?>
    
    
    </div>
  
   <div class="span4">

	 
    
    
    
    <div class="control-group">
		<label class="control-label">Ring Style</label>
		<div class="controls">
         	<select name="ringstyle">
            <option value="1" <? if($ring_style == 1){?>selected<? }?>>Ring</option>
            <option value="2" <? if($ring_style == 2){?>selected<? }?>>Music</option>
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
        
        <div class="control-group">
		<label class="control-label">Free Minutes</label>
		<div class="controls">
			<?=$free_minutes;?>
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
             <option value="0" <? if($faxdetect == 1){?>selected<? }?>>No</option>
             <option value="1" <? if($faxdetect == 1){?>selected<? }?>>Yes</option>             
            </select>
		</div>
	</div>
  
	<div class="control-group">
		<label class="control-label">Fax Ring</label>
		<div class="controls">
			<input type="text" class="span5" name="faxring" value="<?=$faxring;?>">
		</div>
	</div>  
                                            
	<div class="control-group">
		<label class="control-label">Fax Email</label>
		<div class="controls">
			<input type="text" class="span5" name="faxemail" value="<?=$faxemail;?>">
		</div>
	</div>  
	
                               
                                            
	     
    </div>                                                                                                                                                                                                                                                                                                 
</div>

<div id="e911" class="e911" style="display:none">

<div class="control-group">
		<label class="control-label">Caller Name</label>
		<div class="controls">
			<input class="required" id="callername" name="callername" value="<?=$callername;?>">
		</div>
	</div>
    
    <div class="control-group">
		<label class="control-label">Address</label>
		<div class="controls">
			<input class="required" name="address" id="address" value="<?=$address;?>">
		</div>
	</div>
    
    <div class="control-group">
		<label class="control-label">Address2</label>
		<div class="controls">
			 <input class="required" name="address2" id="address2" value="<?=$address2;?>">
		</div>
	</div>
    
    
    <div class="control-group">
		<label class="control-label">City</label>
		<div class="controls">
		 <input class="required" id="city" name="city" value="<?=$city;?>">
		</div>
	</div>
    
    
    <div class="control-group">
		<label class="control-label">State</label>
		<div class="controls">
			 <input class="required" id="state" name="state" value="<?=$state;?>">
		</div>
	</div>
    
    <div class="control-group">
		<label class="control-label">Zip</label>
		<div class="controls">
			  <input class="required" id="zip" name="zip" value="<?=$zip;?>">
		</div>
	</div>

</div>
</fieldset>
</div>
