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

<div id="content_dialog"></div>  

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

<script language="javascript" type="text/javascript">

$(document).ready(function() {
	
	$('#new_fax_btn').click(function () {
 $("#myDialog")
      .dialog({
      	title : "New Fax",
        autoOpen: false,
		resizables: false,
		 modal: true,
        width: 800,
		height: 500,
       })
     .draftmodaldialog({
	  show_header : true,
	  show_footer : true,
	  content_source_url: 'mtl.new_fax.php',
	  	 submit_button:true,
	  submit_url:'mtl.new_fax_process.php',
	  submit_label:'Send Fax',    
	  cancel_label:'Cancel',  
	  show_noty_on_submit:true,	  
	  noty_message:'Success Send Fax',
	  noty_type:'success',	  	  
	  noty_layout:'top',
	});

	});
	
});
</script>

<style>
tfoot input,tfoot select
{
	width:90% !important;
}
</style>

<div class="widget widget-tabs widget-tabs-gray">
		
			<!-- Tabs Heading -->
			<div class="widget-head">
				<ul>
                    <li><a data-toggle="tab" href="#tab-3" class="glyphicons share_alt"><i></i>Fax Messages</a></li>
                    <li><a data-toggle="tab" href="#tab-4" class="glyphicons share_alt"><i></i>Fax Sent</a></li>                    
				</ul>
			</div>
			<!-- // Tabs Heading END -->
			
			<div class="widget-body">
				<div class="tab-content">


		<div id="tab-3" class="tab-pane active">



<div class="row-fluid">
    <div class="span12">

        <p><button id="new_fax_btn" class="btn btn-primary">New Fax</button></p>
		<table id="dt_fax_messages" class="table table-striped table-bordered dTableR">
            <thead>
                <tr>
                   
                    <th>Number</th>
                    <th>From</th>
                    <th>Duration</th>
                    <th>Pages</th>                    
                    <th>Cost</th>                    
                    <th>Bit Rate</th>  
                     <th>Action</th>                                                           
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="dataTables_empty" colspan="6">Loading data from server</td>
                </tr>
            </tbody>
        
        </table>
    </div>
</div>



<script language="javascript" type="text/javascript">

$(document).ready(function() {
	
	$('.fax_view').live('click',function () {
			var url = $(this).attr('rel');
				$('#dialog').remove();		
	$('#content_dialog').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="'+url+'" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: 'Fax VIew',
		close: function (event, ui) {
			//alert("a");
		},	
		bgiframe: false,
		width: 800,
		height: 550,
		resizable: false,
		modal: false
	});
	

	return false;
	});
		
	
	$('#dt_fax_messages').dataTable( {
		"bProcessing": true,
		"bServerSide": true,
		  "sDom": "<'row-fluid'<'span4'l><'span4'T><'span4'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
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
			"sAjaxSource": "mtl.getfax_messages.php",
						"aoColumns": [
						{ "mDataProp": 'fax_nr' },
						{ "mDataProp": 'src_nr' },
						{ "mDataProp": 'duration' },
						{ "mDataProp": 'pages' },
						{ "mDataProp": 'cost' },												
						{ "mDataProp": 'bitrate' },	
						{
							"mDataProp": null, 
							"sClass": "center",
							"sDefaultContent": '<a href="" class="editor_edit">Edit</a>',
							"bSortable": false,
							"bSearchable": false,
							"fnRender": function (oObj)                              
							{
								return "<a href='#' rel='mtl.fax_view.php?mtl_fax_id="+oObj.aData['mtl_fax_id'] +"' class='fax_view splashy-contact_blue_edit' alt='View' title='View'></a>";
							}
						}					
					
					],
					"aaSorting": [[0, 'desc']]
	} );
		
		 // Create the form
   						var editor = new $.fn.dataTable.Editor( {
					"ajaxUrl": "mtl.fax_messages_action.php",
					"domTable": "#dt_fax_messages",
				
        "events": {
            "onPreSubmit": function ( o ) {
				/*
                if ( o.data.number === "" ) {
                    this.error('pattern', 'A number must be given');
                    return false;
                }
                else if ( o.data.did_provider  === "" ) {
                    this.error('comment', 'A did provider must be given');
                    return false;
                }
				*/

            }
        } 
				} );

				editor.add( [ 
					{
						"label": "Address:",
						"name": "address"
						}, 																				
				]
				
				
				);

				// New record
				$('button.editor_create').on('click', function (e) {
					e.preventDefault();

					editor.create(
						'Create new record',
						{
							"label": "Add",
							"fn": function () {
								editor.submit()
							}
						}
					);
				} );

				// Edit record
				$('#dt_fax_messages').on('click', 'a.editor_edit', function (e) {
					e.preventDefault();

					editor.edit(
						$(this).parents('tr')[0],
						'Edit record',
						{
							"label": "Update",
							"fn": function () { editor.submit(); }
						}
					);
				} );

				// Delete a record
				$('#dt_fax_messages').on('click', 'a.editor_remove', function (e) {
					e.preventDefault();

					editor.message( "Are you sure you want to remove this row?" );
					editor.remove(
						$(this).parents('tr')[0],
						'Delete row', 
						{
							"label": "Delete",
							"fn": function () {
								editor.submit()
							}
						}
					);
				} );
} );


</script>

</div>

		<div id="tab-4" class="tab-pane">



<div class="row-fluid">
    <div class="span12">


		<table id="dt_fax_sent" class="table table-striped table-bordered dTableR">
            <thead>
                <tr>
                   
                      <th>From Number</th>
                    <th>To Number</th>
                    <th>Pages</th>                    
                    <th>Duration</th>                    
                    <th>Status</th>    
                    <th>Action</th>                                                               
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="dataTables_empty" colspan="6">Loading data from server</td>
                </tr>
            </tbody>
        
        </table>
    </div>
</div>



<script language="javascript" type="text/javascript">

$(document).ready(function() {
	$('#dt_fax_sent').dataTable( {
		"bProcessing": true,
		"bServerSide": true,
		  "sDom": "<'row-fluid'<'span4'l><'span4'T><'span4'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
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
			"sAjaxSource": "mtl.getfax_sent.php",
						"aoColumns": [
						{ "mDataProp": 'src_nr' },
						{ "mDataProp": 'dst_nr' },
						{ "mDataProp": 'pages' },						
						{ "mDataProp": 'duration' },						
						{ "mDataProp": 'status' },	
						{
							"mDataProp": null, 
							"sClass": "center",
							"sDefaultContent": '<a href="" class="editor_edit">Edit</a>',
							"bSortable": false,
							"bSearchable": false,
							"fnRender": function (oObj)                              
							{
								return "<a href='#' rel='mtl.fax_sent_view.php?mtl_fax_id="+oObj.aData['mtl_fax_id'] +"' class='fax_view splashy-contact_blue_edit' alt='View' title='View'></a>";
							}
						}					
					
					],
					"aaSorting": [[0, 'desc']]
	} );
		
		 // Create the form
   						var editor = new $.fn.dataTable.Editor( {
					"ajaxUrl": "mtl.fax_sent_action.php",
					"domTable": "#dt_fax_sent",
				
        "events": {
            "onPreSubmit": function ( o ) {
				/*
                if ( o.data.number === "" ) {
                    this.error('pattern', 'A number must be given');
                    return false;
                }
                else if ( o.data.did_provider  === "" ) {
                    this.error('comment', 'A did provider must be given');
                    return false;
                }
				*/

            }
        } 
				} );

				editor.add( [ 
					{
						"label": "Address:",
						"name": "address"
						}, 																				
				]
				
				
				);

				// New record
				$('button.editor_create').on('click', function (e) {
					e.preventDefault();

					editor.create(
						'Create new record',
						{
							"label": "Add",
							"fn": function () {
								editor.submit()
							}
						}
					);
				} );

				// Edit record
				$('#dt_fax_sent').on('click', 'a.editor_edit', function (e) {
					e.preventDefault();

					editor.edit(
						$(this).parents('tr')[0],
						'Edit record',
						{
							"label": "Update",
							"fn": function () { editor.submit(); }
						}
					);
				} );

				// Delete a record
				$('#dt_fax_sent').on('click', 'a.editor_remove', function (e) {
					e.preventDefault();

					editor.message( "Are you sure you want to remove this row?" );
					editor.remove(
						$(this).parents('tr')[0],
						'Delete row', 
						{
							"label": "Delete",
							"fn": function () {
								editor.submit()
							}
						}
					);
				} );
} );


</script>

</div>

</div>
</div>
</div>
