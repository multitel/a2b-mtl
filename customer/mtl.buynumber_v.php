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

?>

<div id="myDialog"></div>
<link rel="stylesheet" type="text/css" href="http://extjs.cachefly.net/ext-3.4.0/resources/css/ext-all.css"/>
<link rel="stylesheet" type="text/css" href="http://extjs.cachefly.net/ext-3.4.0/resources/css/xtheme-gray.css"/>
<script type="text/javascript" src="http://extjs.cachefly.net/ext-3.4.0/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="http://extjs.cachefly.net/ext-3.4.0/ext-all.js"></script>
<link href="http://assets.multitel.net/css/general.css" rel="stylesheet" type="text/css">
<link href="http://assets.multitel.net/js/datatables/extras/TableTools/media/css/TableTools.css" rel="stylesheet" type="text/css">
<link href="http://assets.multitel.net/js/datatables/dataTables.editor.css" rel="stylesheet" type="text/css">
<link href="http://assets.multitel.net/js/datatables/extras/ColumnFilterWidget/css/ColumnFilterWidgets.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="http://assets.multitel.net/js/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/js/datatables/extras/Scroller/media/js/dataTables.scroller.min.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/js/datatables/extras/TableTools/media/js/TableTools.min.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/js/datatables/extras/TableTools/media/js/ZeroClipboard.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/js/datatables/dataTables.editor.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/js/datatables/extras/ColumnFilter/jquery.dataTables.columnFilter.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/js/datatables/jquery.dataTables.bootstrap.min.js"></script>



<style>
tfoot input,tfoot select
{
        width:90% !important;
}

.DTE_Field_Name_voicemail
{
        display:none;
}
html.front #gmap
{
        top:-13px !important;
}

</style>


<div id="gmap" style="width:1100px; height:400px;"> </div>
<script src="http://maps.google.com/maps?file=api&v=2&sensor=false&key=AIzaSyAymHLJQWfnU4FjJr5vYkhynvVQ_HGonyQ" type="text/javascript"></script>

        
<div class="container-960 innerTB">
	<div class="relativeWrap">
        	<div class="widget widget-tabs border-bottom-none">
                        <!-- Tabs Heading -->
                        <div class="widget-head">
                                <ul>
                                        <li class="active"><a class="glyphicons list" href="#tab1" data-toggle="tab"><i></i>Buy Numbers</a></li>
                                        <li><a class="glyphicons cogwheel" href="#tab2" data-toggle="tab"><i></i>Portability</a></li>
			               				 <li><a class="glyphicons cogwheel" href="#tab3" data-toggle="tab"><i></i>Purchased Numbers</a></li>
                                </ul>
                        </div>
                        <!-- // Tabs Heading END -->

                        <div class="widget-body">
                                <div class="tab-content">
                                <div id="tab1" class="tab-pane active widget-body-regular">
					<h3 class="glyphicons circle_info margin-none">
						<i>Buy Numbers</i>
					</h3>
					<label for="country">Country</label>
					<select name="country" id="country" class="select_search" style="width: 280px; padding-left: 10px;"></select>
					<label for="area">Area</label>
					<select name="area" id="area" class="select_search" style="width: 280px; padding-left: 10px;"></select>
					<div class="row-fluid">
						<div class="span12">
					                <table id="dt_default" class="table table-striped table-bordered dTableR">
							        <thead>
							                <tr>
										<th>Number</th>
										<th>Setup</th>
										<th>Monthly</th>
										<th>Perminute</th>
								             
								                <th>Country</th>
								                <th>Action</th>
								       </tr>
								</thead>
								<tbody>
							        	<tr>
								                <td class="dataTables_empty" colspan="6">Loading data from server</td>
							                </tr>
								</tbody>
							        <tfoot>
							                <tr>
								                <th>Number</th>
									        <th>Setup</th>
								                <th>Monthly</th>
								                <th>Perminute</th>
								                <th>Country</th>
								                <th></th>
									</tr>
							        </tfoot>
						        </table>
						</div>
					</div>



<script type="text/javascript" charset="UTF-8">
	function createMarker(point, desc,area_code) {
        	var marker = new google.maps.Marker(point, { title: desc });
                marker.value = desc;
                GEvent.addListener(marker, "click", function() {
                        $("#area").val(area_code);
                        $("#area").trigger('change');
                });
                return marker;
	}
        function createCountryMarker(point, title, country ) {
        	var marker = new GMarker(point, { title: title });  marker.value = title;
                GEvent.addListener(marker, "click", function() {
	                $("#country").select2("val", country); //set the value
                        getAreas(country);
                });
                return marker;
        }

        function getAreas(c) {
		Ext.getBody().mask('Loading...');
                var x = document.getElementById('country').selectedIndex;
                var y = document.getElementById('country').options;
                $.getJSON(
                        'mtl.getareas.php?c=' + c,
                         function(data) {
                         	$('#area').children().remove().end();
	                        $('#area').append('<option selected>&nbsp;</option>');
        	                $('#area').removeAttr('disabled');
                 	        document.getElementById('gmap').innerHTML='';
                        	var map = new GMap2(document.getElementById("gmap"));
	                        map.addControl(new GSmallMapControl(), new GControlPosition(G_ANCHOR_BOTTOM_LEFT, new GSize(10,10)));
        	                var check = 0
                	        $.each(data.DATA, function (key,val) {
                       	 		if (check == 0) {
	                               		map.setCenter(new GLatLng(val.CLATITUDE,val.CLONGITUDE),5);
	                               		check = check + 1;
        		        	}
                        		latlng = new google.maps.LatLng(val.ALATITUDE, val.ALONGITUDE);
		                        map.addOverlay(createMarker(latlng,val.DESCRIPTION,val.AREACODE));//modif by erwindraft
        		                $('#area').append('<option value="' + val.AREACODE + '" + style="padding-left: 10px;"> ' + val.DESCRIPTION + ' [+' + val.AREACODE + ']' + '</option');
                	        });
		                Ext.getBody().unmask();
                	});
                Ext.getBody().unmask();
        }
</script>

<script type="text/javascript" src="http://assets.multitel.net/js/extjs/RowEditor.js"></script>


<script type="text/javascript">
 if (GBrowserIsCompatible()) {
                var map = new GMap2(document.getElementById("gmap"));
                map.setCenter(new GLatLng(43.907787,-79.359741),1);
                map.addControl(new GSmallMapControl(), new GControlPosition(G_ANCHOR_BOTTOM_LEFT, new GSize(10,10)));
            }
$(document).ready(function() {
        $('.edit_purchased_number_btn').live('click',function () {
                var url= $(this).attr('rel');
                var title= $(this).attr('title');

	$("#myDialog")
      		.dialog({
		        title : title,
		        autoOpen: false,
	                resizables: false,
        	        modal: true,
		        width: 1100,
	                height: 550,
	       	})
		.draftmodaldialog({
			show_header : true,
			show_footer : true,
		        content_source_url: url,
		        submit_button:true,
		        submit_url:url,
		        submit_label:'Submit',
		        cancel_label:'Cancel',
		        // click_success_id:'#refresh',
		        show_noty_on_submit:true,
		        noty_message:'Success Edit Purchased Number',
		        noty_type:'success',
		        noty_layout:'top',
			// ajax_form:true,
			// ajax_form_id:'#form_modal'
	        });
	});

        $('.purchase_number_btn').live('click',function () {
                var url= $(this).attr('rel');
                var title= $(this).attr('title');

		$("#myDialog")
			.dialog({
		        	title : title,
	        		autoOpen: false,
		                resizables: false,
	        	        modal: true,
				width: 1100,
		                height: 550,
			})
			.draftmodaldialog({
				show_header : true,
				show_footer : true,
			        content_source_url: url,
			        submit_button:true,
			        submit_url:url,
			        submit_label:'Submit',
			        cancel_label:'Cancel',
			        click_success_id:'#refresh_after_buy',
			        show_noty_on_submit:true,
			        noty_message:'Success Purchase Number',
			        noty_type:'success',
			        noty_layout:'top',
				// ajax_form:true,
				// ajax_form_id:'#form_modal'
		        });
	});


        Ext.getBody().mask('Loading...');
	$.getJSON(
        	'mtl.getcountries.php',
	        function(data) {
        		$('#country').append('<option selected>&nbsp;</option>');
		        $.each(data.DATA, function(key, val) {
			        $('#country').append('<option value="' + val.COUNTRYCODE + ':' + val.COUNTRYID + '" style="padding-left: 10px;"> ' + val.DESCRIPTION + '    [+' + val.COUNTRYCODE + ']' + '</option>');
                		var latlng = new google.maps.LatLng(val.LATITUDE, val.LONGITUDE);
			        map.addOverlay(createCountryMarker(latlng,val.DESCRIPTION,val.COUNTRYCODE + ':' + val.COUNTRYID));
	        	});
		        Ext.getBody().unmask();
	        }
	);

	$("#country").change(function() {
        $("#area").select2('val', 'All');

	var c = $(this).val();
	prefix = '';
	oTable.fnReloadAjax("mtl.getnumbers.php?c="+c);

	Ext.getBody().mask('Loading...');
	$.getJSON(
        		'mtl.getareas.php?c=' + c,
		        function(data) {
        			//$('#area').children().remove().end();
			          $('#area').find('option').remove().end();
		        	  $('#area').append('<option selected>&nbsp;</option>');
			          $('#area').removeAttr('disabled');
        	                  document.getElementById('gmap').innerHTML='';
	                          var map = new GMap2(document.getElementById("gmap"));
                        	  map.addControl(new GSmallMapControl(), new GControlPosition(G_ANCHOR_BOTTOM_LEFT, new GSize(10,10)));
                	          var check = 0
			          $.each(data.DATA, function (key,val) {
				          if (check == 0) {
                        			  map.setCenter(new google.maps.LatLng(val.CLATITUDE,val.CLONGITUDE),5);
		                	          check = check + 1;
                		          }
                	          	  latlng = new google.maps.LatLng(val.ALATITUDE, val.ALONGITUDE);
		                          map.addOverlay(createMarker(latlng,val.DESCRIPTION,val.AREACODE));
				          $('#area').append('<option value="' + val.AREACODE + '" + style="padding-left: 10px;"> ' + val.DESCRIPTION + ' [+' + val.AREACODE + ']' + '</option');
			          });
		        	  Ext.getBody().unmask();
        		}
		);
	});

        $.fn.dataTableExt.oApi.fnReloadAjax = function ( oSettings, sNewSource, fnCallback, bStandingRedraw ) {
		if ( sNewSource !== undefined && sNewSource !== null ) {
		        oSettings.sAjaxSource = sNewSource;
		}
		// Server-side processing should just call fnDraw
		if ( oSettings.oFeatures.bServerSide ) {
		        this.fnDraw();
		        return;
		}

		this.oApi._fnProcessingDisplay( oSettings, true );
		var that = this;
		var iStart = oSettings._iDisplayStart;
		var aData = [];

		this.oApi._fnServerParams( oSettings, aData );

		oSettings.fnServerData.call( oSettings.oInstance, oSettings.sAjaxSource, aData, function(json) {
		        /* Clear the old information from the table */
		        that.oApi._fnClearTable( oSettings );

		        /* Got the data - add it to the table */
        		var aData =  (oSettings.sAjaxDataProp !== "") ?
		        that.oApi._fnGetObjectDataFn( oSettings.sAjaxDataProp )( json ) : json;

        		for ( var i=0 ; i<aData.length ; i++ ) {
			        that.oApi._fnAddData( oSettings, aData[i] );
	        	}

		        oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();

	        	that.fnDraw();

	        	if ( bStandingRedraw === true ) {
			        oSettings._iDisplayStart = iStart;
			        that.oApi._fnCalculateEnd( oSettings );
		        	that.fnDraw( false );
		        }

        		that.oApi._fnProcessingDisplay( oSettings, true ); //set to true to display preloader

		        /* Callback user function - for event handlers etc */
        		if ( typeof fnCallback == 'function' && fnCallback !== null ) {
	        		fnCallback( oSettings );
	        	}
		}, oSettings );
	};
	var prefix='';

	$("#area").change(function() {
	        prefix = $(this).val();
        	oTable.fnReloadAjax("mtl.getnumbers.php?prefix="+prefix);
	});

	$("#DTE_Field_forward_destination").live("change",function() {
	        var forward_destination_id = $(this).val();
        	if(forward_destination_id =='') {
	                $(".DTE_Field_Name_uri").hide();
        	        $(".DTE_Field_Name_voicemail").hide();
        	} else if(forward_destination_id == 5 || forward_destination_id== 11) {
		        $(".DTE_Field_Name_uri").hide();
		        $(".DTE_Field_Name_voicemail").show();
	        } else {
		        $(".DTE_Field_Name_uri").show();
		        $(".DTE_Field_Name_voicemail").hide();
        	}
	});


	var oTable = $('#dt_default').dataTable( {
                "bInfo": false,
                "bProcessing": true,
                "bServerSide": true,
                "sDom": "<'row-fluid'<'span4'l><'span4'T><'span4'f>r>t<'row'<'span6'i><'span6'p>>",
                "oTableTools": {
        	        "aButtons": [
                	        "copy",
                        	"print", {
                	                "sExtends":    "collection",
                        	        "sButtonText": 'Export <span class="caret" />',
	                                "aButtons":    [ "csv", "xls", "pdf" ]
        	                }
                        ],
                        "sSwfPath": "http://assets.multitel.net/js/datatables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
                },
           	"sPaginationType": "bootstrap",
	        "bScrollCollapse": true,
                        "sAjaxSource": "mtl.getnumbers.php?prefix="+prefix,
                        "aoColumns": [
          			{ "mDataProp": 'number' },
                                { "mDataProp": 'setup' },
                                { "mDataProp": 'monthly' },
                                { "mDataProp": 'perminute' },
                              //  { "mDataProp": 'free_minutes' },
                                { "mDataProp": 'country' },
                                {
                                  "mDataProp": null,
                                  "sClass": "center",
                                  "sDefaultContent": '<a href="" class="editor_edit">Edit</a>',
	                                  "bSortable": false,
                                          "bSearchable": false,
                                          "fnRender": function (oObj) {
	                                          return "<a href='#' rel='mtl.purchase_number_modal.php?number="+oObj.aData['id'] +"&setup="+oObj.aData['setup'] +"&monthly="+oObj.aData['monthly'] +"&perminute="+oObj.aData['perminute'] +"&free_minutes="+oObj.aData['free_minutes'] +"' class='purchase_number_btn splashy-contact_blue_edit' alt='Purchase Number' title='Purchase Number "+oObj.aData['id']+"'></a>";
                                          }
                                }],
                                "aaSorting": [[1, 'asc']]
		        } ).columnFilter({
                	        aoColumns: [
                                     { type: "text" },
                                     { type: "text" },
                                     { type: "text" },
                                     { type: "text" },
                                     { type: "text" },
                                     { type: "text" }
                                ]
	                });
                //oTable


                function forward_destination_loader()
                {
                        var data_obj= new Array({});
                        data_obj.splice(0,1);
                        $.ajax({
                          url: 'ajax/get_forward_destination_loader',
                          async: false,
                          dataType: 'json',
                          success: function (json) {
                                  for(var a=0;a<json.length;a++){
                                        obj= { "label" : json[a][0], "value" : json[a][1]};
                                        data_obj.push(obj);
                                  }
                                }
                        });
                        return data_obj;
                }

                function voicemail_loader()
                {
                        var data_obj= new Array({});
                        data_obj.splice(0,1);
                        $.ajax({
                          url: '/ajax/get_voicemail_loader',
                          async: false,
                          dataType: 'json',
                          success: function (json) {
                                  for(var a=0;a<json.length;a++){
                                        obj= { "label" : json[a][0], "value" : json[a][1]};
                                        data_obj.push(obj);
                                  }
                                }
                        });
                        return data_obj;
                }



                 // Create the form
                        var editor = new $.fn.dataTable.Editor( {
                                        "ajaxUrl": "/ajax/dt_purchase_number",
                                        "domTable": "#dt_default",

        "events": {
            "onPreSubmit": function ( o ) {
                if ( o.data.uri === "" ) {
                    this.error('uri', 'A uri must be given');
                    return false;
                }
                else if ( o.data.forward_destination  === "" ) {
                    this.error('forward_destination', 'A forward destination must be given');
                    return false;
                }
            }
        }
                                } );

                                editor.add( [
                                        {
                                                "label": "Forward Destination:",
                                                "name": "forward_destination",
                                                "default" : '1',
                                                "type": "select",
                                                 //"ipOpts":forward_destination_loader()
                                        }, {
                                                "label": "URI:",
                                                "name": "uri",
                                        }, {
                                                "label": "URI:",
                                                "name": "voicemail",
                                                "type": "select",
                                                // "ipOpts":voicemail_loader()
                                        }, {
                                                "label": "Fax Detect:",
                                                "name": "faxdetect",
                                        }, {
                                                "label": "Fax Ring:",
                                                "name": "faxring",
                                        }, {
                                                "label": "Fax Email:",
                                                "name": "faxemail",
                                        },
                                                                                {
                                                "label": "Cnam:",
                                                "name": "cnam",
                                        }
                                        ,
                                        {
                                                "label": "Vm:",
                                                "name": "vm",
                                        }
                                        ,
                                        {
                                                "label": "Vmring:",
                                                "name": "vmring",
                                        }
                                        ,
                                        {
                                                "label": "VmS2T:",
                                                "name": "vms2t",
                                        }
                                        ,
                                        {
                                                "label": "Vmemail:",
                                                "name": "vmemail",
                                        }
                                ]


                                );

                                                $('#dt_default').on('click', 'a.editor_edit', function (e) {
                                        e.preventDefault();

                                        editor.edit(
                                                $(this).parents('tr')[0],
                                                'Purchase Number',
                                                {
                                                        "label": "Purchase",
                                                        "fn": function ()
                                                        {
                                                        editor.submit();
                                                        }
                                                }
                                        );
                                } );

});



</script>


</div>


                <div id="tab2" class="tab-pane widget-body-regular">

                <form name="" method="post" action="ajax/portNumber" class="ajax_form" enctype="multipart/form-data">
                 <div class="formSep">
                                                <p><span class="label label-inverse">Portability</span></p>
                                                <div class="row-fluid">
                        <div class="span3">
                                                                <label for="company">Number</label>
                                                                <input type="text" id="Number" name="Number" class="span11" value="">

                                                        </div>
                                <div class="span3">
                                                                <label for="company">Name</label>
                                                                <input type="text" id="Name" name="Name" class="span11" value="">

                                                        </div>



                                                        <div class="span3">
                                                                <label for="company">Address</label>
                                                                <input type="text" id="Address" name="Address" class="span11" value="">

                                                        </div>

                              <div class="span3">
                                                                <label for="mask_date">City</label>
                                                                  <input type="text" id="City" name="City" class="span11" value="">

                                                        </div>


                            </div>

                            <div class="row-fluid">



                                                        <div class="span3">
                                                                <label for="mask_product">State</label>
                                                                <input type="text" id="State" name="State" class="span11" value="">

                                                        </div>




                            <div class="span3">
                                                                <label for="mask_product">Zip Code</label>
                                                                <input type="text" id="ZipCode" name="ZipCode" class="span11" value="">

                                                        </div>

                            <div class="span3">
                                                                <label for="mask_product">Country</label>
                                                                <select name="Country">
                                <option value="United States" selected="selected">United States</option>
                                 <option value="Canada">Canada</option>
                                 <option value="France">France</option>
                                 <option value="United Kingdom">United Kingdom</option>

                                </select>

                                                        </div>
                                                        <div class="span3">
                                                        </div>

                            </div>

                            <div class="row-fluid">
                            <div class="span3">
                                                                <label for="mask_product">Contact Number</label>
                                                                <input type="text" id="ContactNumber" name="ContactNumber" class="span11" value="">

                                                        </div>
                                                        <div class="span3">
                                                                <label for="mask_product">Current Carrier/Provider</label>
                                                                <input type="text" id="FromCarrier" name="FromCarrier" class="span11" value="">

                                                        </div>
                            <div class="span3">
                                                                <label for="mask_product">Authorized Name</label>
                                                                <input type="text" id="AuthorizedUsername" name="AuthorizedUsername" class="span11" value="">

                                                        </div>

                            <div class="span3">
                                                                <label for="company">Comments</label>
                                                                <input type="text" id="Comments" name="Comments" class="span11" value="">

                                                        </div>




                            </div>

                                                        <div class="row-fluid">
                                                        <div class="span6">
                                                                <label for="mask_product">Last Month Bill Image</label>
                                                                <input type="file" id="LastMonthBillImage" name="LastMonthBillImage" class="span11" value="">

                                                        </div>
                            <div class="span6">
                                                                <label for="mask_product">Other Supported Doc Image</label>
                                                                <input type="file" id="OtherSupportedDocImage" name="OtherSupportedDocImage" class="span11" value="">

                                                        </div>

                            </div>
                            <br />
                                                        <br />

                            <div class="widget-body">
                                        <table class="table table-bordered table-vertical-center table-pricing table-pricing-2">

                                                <!-- Table heading -->
                                                <thead>
                                                        <tr>
                                                                                        <th class="center">United States</th>
                                                                                                <th class="center">Canada</th>
                                                                                                <th class="center">France</th>
                                                                                                <th class="center">United Kingdom</th>
                                                                                        </tr>
                                                </thead>
                                                <!-- // Table heading END -->

                                                <!-- Table body -->
                                                <tbody>

                                                        <!-- Table pricing row -->
                                                        <tr class="pricing">
                                                                                        <td class="center">
                                                                        <span class="price">$35</span>
                                                                        <span>per number</span>
                                                                </td>
                                                                                                <td class="center">
                                                                        <span class="price">$40</span>
                                                                        <span>per number</span>
                                                                </td>
                                <td class="center">
                                                                        <span class="price">$170</span>
                                                                        <span>per number</span>
                                                                </td>
                                                                <td class="center">
                                                                        <span class="price">$40</span>
                                                                        <span>per number</span>
                                                                </td>

                                                        </tr>
                                                        <!-- // Table pricing row END -->

                                                        <!-- Table row -->
                                                        <tr>
                                                                                        <td class="center">
                                                                <ul>
                                          <li> <strong>5 weeks</strong> time for porting</li>

                                </ul>
                                                                        <div class="separator bottom"></div>


                                                                </td>
                                                                                                <td class="center">
                                                                <ul>
                                        <li> <strong>4 weeks</strong> time for porting</li>
                                </ul>
                                                                        <div class="separator bottom"></div>


                                                                </td>
                                                                                                <td class="center">
                                                                <ul>
                                        <li> <strong>8 weeks</strong> time for porting</li>
                                </ul>
                                                                        <div class="separator bottom"></div>


                                                                </td>

                                                        <td class="center">
                                                                <ul>
                                        <li> <strong>4 weeks</strong> time for porting</li>
                                </ul>
                                                                        <div class="separator bottom"></div>


                                                                </td>

                                                        </tr>
                                                        <!-- // Table row END -->

                                                </tbody>
                                                <!-- // Table body END -->

                                        </table>
                                </div>
                            <div class="form-action">

                             <div id="preloader" style="display:none;">
                            Submitting request..
                            </div>
                            <div id="notif_container">
                            </div>
                            <br />

                            <input type="submit" name="submit" value="Submit" class="btn btn-primary" />

                           </div>



                            </div>
                 </form>
                </div>

                <div id="tab3" class="tab-pane widget-body-regular">


                <div class="row-fluid">
    <div class="span12">
<button id="refresh_purchased_numbers" class="btn btn-primary">Refresh</button>
                <table id="dt_purchased_numbers" class="table table-striped table-bordered dTableR">
            <thead>
                <tr>

                    <th>Number</th>
                    <th>Selling Rate</th>
                    <th>Fix Rate</th>
                    <th>Creation Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="dataTables_empty" colspan="6">Loading data from server</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>

                    <th>Number</th>
                    <th>Selling Rate</th>
                    <th>Fix Rate</th>
                    <th>Creation Date</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>



<script language="javascript" type="text/javascript">

$(document).ready(function() {

$("#DTE_Field_forward_destination").live("change",function()
{

        var forward_destination_id = $(this).val();
        var current_uri = $("#DTE_Field_uri").val();
        //alert(current_uri);
        $("#DTE_Field_voicemail").val(current_uri);
        if(forward_destination_id =='')
        {
                        $(".DTE_Field_Name_uri").hide();
                $(".DTE_Field_Name_voicemail").hide();
        }
        else if(forward_destination_id == 5 || forward_destination_id== 11)
        {
        $(".DTE_Field_Name_uri").hide();
        $(".DTE_Field_Name_voicemail").show();
        }
        else
        {
        $(".DTE_Field_Name_uri").show();
        $(".DTE_Field_Name_voicemail").hide();
        }


});

        var p_dt = $('#dt_purchased_numbers').dataTable( {
                //"bPaginate": false,
                "bInfo": false,
                "bProcessing": true,
                "bServerSide": true,
                  "sDom": "<'row-fluid'<'span4'l><'span4'T><'span4'f>r>t<'row'<'span6'i><'span6'p>>",
                   "oTableTools": {
                        "aButtons": [
                            "copy",
                            "print",
                            {
                                "sExtends":    "collection",
                                "sButtonText": 'Export <span class="caret" />',
                                "aButtons":    [ "csv", "xls", "pdf" ]
                            }
                        ],

                    },
                                   "sPaginationType": "bootstrap",
                                   "bScrollCollapse": true,
                                        "sAjaxSource": "mtl.get_purchased_numbers.php",
                                                "aoColumns": [
                                                { "mDataProp": 'number' },
                                                { "mDataProp": 'selling_rate' },
                                                { "mDataProp": 'fixrate' },
                                                { "mDataProp": 'creationdate' },
                                                {
                                                        "mDataProp": null,
                                                        "sClass": "center",
                                                        "sDefaultContent": '<a href="" class="editor_edit">Edit</a>',
                                                        "bSortable": false,
                                                        "bSearchable": false,
                                                        "fnRender": function (oObj)
                                                        {
                                                                return "<a href='#' rel='mtl.edit_purchased_number_modal.php?number="+oObj.aData['number']+"' class='edit_purchased_number_btn splashy-contact_blue_edit' alt='Edit Purchased Number' title='Edit Purchased Number "+oObj.aData['number']+"'></a><a href='' class='editor_remove splashy-tag_remove'></a>";
                                                        }
                                                }

                                        ],
                                        "aaSorting": [[0, 'desc']]
        } ).columnFilter({
                        aoColumns: [
                                     { type: "text" },
                                     { type: "text" },
                                     { type: "text" },
                                     { type: "text" },
                                     { type: "text" },

                                ]

                });



                function forward_destination_loader()
                {
                        var data_obj= new Array({});
                        data_obj.splice(0,1);
                        $.ajax({
                          url: '/ajax/get_forward_destination_loader',

                          async: false,
                          dataType: 'json',
                          success: function (json) {
                                  for(var a=0;a<json.length;a++){
                                        obj= { "label" : json[a][0], "value" : json[a][1]};
                                        data_obj.push(obj);
                                  }
                                }
                        });
                        return data_obj;
                }

                function voicemail_loader()
                {
                        var data_obj= new Array({});
                        data_obj.splice(0,1);
                        $.ajax({
                          url: '/ajax/get_voicemail_loader',
                          async: false,
                          dataType: 'json',
                          success: function (json) {
                                  for(var a=0;a<json.length;a++){
                                        obj= { "label" : json[a][0], "value" : json[a][1]};
                                        data_obj.push(obj);
                                  }
                                }
                        });
                        return data_obj;
                }

                 // Create the form
                        var editor = new $.fn.dataTable.Editor( {
                "ajaxUrl": "/ajax/dt_edit_number",
                "domTable": "#dt_purchased_numbers",

        "events": {
            "onPreSubmit": function ( o ) {
                if ( o.data.uri === "" ) {
                    this.error('uri', 'A uri must be given');
                    return false;
                }
                else if ( o.data.forward_destination  === "" ) {
                    this.error('forward_destination', 'A forward destination must be given');
                    return false;
                }
            }
        }
                                } );

                         // When the editor is opened, bind a key listener to the window
                                editor.on('onOpen', function () {
                                        $("#DTE_Field_forward_destination").trigger('change');
                                } );

                                editor.add( [
                                        {
                                                "label": "Forward Destination:",
                                                "name": "forward_destination",
                                                "type": "select",
                                                // "ipOpts":forward_destination_loader()
                                        }, {
                                                "label": "URI:",
                                                "name": "uri",
                                        }, {
                                                "label": "URI:",
                                                "name": "voicemail",
                                                "type": "select",
                                                "default": "uri",
                                               //  "ipOpts":voicemail_loader()
                                        }, {
                                                "label": "Fax Detect:",
                                                "name": "faxdetect",
                                        }, {
                                                "label": "Fax Ring:",
                                                "name": "faxring",
                                        }, {
                                                "label": "Fax Email:",
                                                "name": "faxemail",
                                        },
                                        {
                                                "label": "Cnam:",
                                                "name": "cnam",
                                        }
                                        ,
                                        {
                                                "label": "Vm:",
                                                "name": "vm",
                                        }
                                        ,
                                        {
                                                "label": "Vmring:",
                                                "name": "vmring",
                                        }
                                        ,
                                        {
                                                "label": "VmS2T:",
                                                "name": "vms2t",
                                        }
                                        ,
                                        {
                                                "label": "Vmemail:",
                                                "name": "vmemail",
                                        }
                                ]


                                );

                                // Edit record

                                                $('#dt_purchased_numbers').on('click', 'a.editor_edit', function (e) {



                                        e.preventDefault();

                                        editor.edit(
                                                $(this).parents('tr')[0],
                                                'Update Purchased Number',
                                                {
                                                        "label": "Update",
                                                        "fn": function ()
                                                        {
                                                        editor.submit();
                                                        }
                                                }
                                        );
                                } );


                                // Delete a record
                                $('#dt_purchased_numbers').on('click', 'a.editor_remove', function (e) {
                                        e.preventDefault();

                                        editor.message( "Are you sure you want to remove this number?" );
                                        editor.remove(
                                                $(this).parents('tr')[0],
                                                'Delete Number',
                                                {
                                                        "label": "Delete",
                                                        "fn": function () {
                                                                editor.submit()
                                                        }
                                                }
                                        );
                                } );


                                $('#refresh_purchased_numbers').live("click",function () {
        p_dt.fnReloadAjax();
                //oTable.fnDraw();
                  } );
				  
				  		  $('#refresh_after_buy').live("click",function () {
      $("#area").trigger('change');
		 p_dt.fnReloadAjax();
  		  } );
		  
} );


</script>


                </div>

</div>

</div>

</div>
</div>
</div>

<button id="refresh_after_buy" class="btn btn-primary"></button>
