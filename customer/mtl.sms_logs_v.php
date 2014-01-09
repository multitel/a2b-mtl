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


<script language="javascript" type="text/javascript">

$(document).ready(function() {
	
	$('#new_sms_btn').click(function () {
 $("#myDialog")
      .dialog({
      	title : "Send SMS",
        autoOpen: false,
		resizables: false,
		 modal: true,
        width: 800,
		height: 500,
       })
     .draftmodaldialog({
	  show_header : true,
	  show_footer : true,
	  content_source_url: 'mtl.send_sms_v.php',
	  	 submit_button:true,
	  submit_url:'mtl.send_sms_process.php',
	  submit_label:'Send SMS',    
	  cancel_label:'Cancel',  
	  show_noty_on_submit:true,	  
	  noty_message:'Success Send SMS',
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

<div class="row-fluid">
    <div class="span12">
<p><a id="new_sms_btn" class="btn btn-primary">New SMS</a><p>
		<table id="dt_default" class="table table-striped table-bordered dTableR">
            <thead>
                <tr>
                   
                    <th>Number From</th>
                    <th>Number To</th>
                    <th>Message</th>
                    <th>Date</th>                    
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="dataTables_empty" colspan="6">Loading data from server</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                   
                    <th>Number From</th>
                    <th>Number To</th>
                    <th>Message</th>
                    <th>Date</th>  

                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script language="javascript" type="text/javascript">

$(document).ready(function() {
	$('#dt_default').dataTable( {
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
			"sAjaxSource": "mtl.get_sms_logs.php",
						"aoColumns": [
						{ "mDataProp": 'src_nr' },
						{ "mDataProp": 'dst_nr' },
						{ "mDataProp": 'message' },
						{ "mDataProp": 'date_created' },						
					
					],
					"aaSorting": [[3, 'desc']]
	} );
} );


</script>
