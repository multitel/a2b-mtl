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

<link href="http://assets.multitel.net/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="http://assets.multitel.net/bootstrap/css/responsive.css" rel="stylesheet" type="text/css">
<link href="http://assets.multitel.net/fonts/glyphicons/css/glyphicons.css" rel="stylesheet" type="text/css">
<link href="http://assets.multitel.net/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<!--[if IE 7]><link href="http://assets.multitel.net/fonts/font-awesome/css/font-awesome-ie7.min.css" rel="stylesheet" type="text/css"><![endif]-->
<link href="http://assets.multitel.net/scripts/plugins/forms/pixelmatrix-uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<link href="http://assets.multitel.net/bootstrap/extend/bootstrap-select/bootstrap-select.css" rel="stylesheet" type="text/css">
<!-- JQueryUI -->
<link href="http://assets.multitel.net/scripts/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css">
<!-- MiniColors ColorPicker Plugin -->
<link href="http://assets.multitel.net/scripts/plugins/color/jquery-miniColors/jquery.miniColors.css" rel="stylesheet" type="text/css">
<!-- Google Code Prettify Plugin -->
<link href="http://assets.multitel.net/scripts/plugins/other/google-code-prettify/prettify.css" rel="stylesheet" type="text/css">
<!-- Bootstrap Image Gallery -->
<link href="http://assets.multitel.net/bootstrap/extend/bootstrap-image-gallery/css/bootstrap-image-gallery.min.css" rel="stylesheet" type="text/css">
<!-- Select2 Plugin -->
<link href="http://assets.multitel.net/scripts/plugins/forms/select2/select2.css" rel="stylesheet" type="text/css">
<!-- Main Theme Stylesheet :: CSS -->
<link href="http://assets.multitel.net/css/style-default-menus-dark.css?1374506533" rel="stylesheet" type="text/css">
<link href="http://assets.multitel.net/css/general.css" rel="stylesheet" type="text/css">
<!-- LESS.js Library -->
<script src="http://assets.multitel.net/scripts/plugins/system/less.min.js"></script>
<!-- JQuery -->
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

<!-- Code Beautify -->
<script src="http://assets.multitel.net/scripts/plugins/other/js-beautify/beautify.js"></script>
<script src="http://assets.multitel.net/scripts/plugins/other/js-beautify/beautify-html.js"></script>

<!-- Global -->
<script>
var basePath = '',
    commonPath = 'http://assets.multitel.net/';
</script>

<script src="http://assets.multitel.net/scripts/plugins/forms/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>

<!--noty -->
<script src="http://assets.multitel.net/scripts/plugins/noty/jquery.noty.js" type="text/javascript"></script>
<!-- layouts -->
<script type="text/javascript" src="http://assets.multitel.net/scripts/plugins/noty/layouts/bottom.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/scripts/plugins/noty/layouts/bottomCenter.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/scripts/plugins/noty/layouts/bottomLeft.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/scripts/plugins/noty/layouts/bottomRight.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/scripts/plugins/noty/layouts/center.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/scripts/plugins/noty/layouts/centerLeft.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/scripts/plugins/noty/layouts/centerRight.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/scripts/plugins/noty/layouts/inline.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/scripts/plugins/noty/layouts/top.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/scripts/plugins/noty/layouts/topCenter.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/scripts/plugins/noty/layouts/topLeft.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/scripts/plugins/noty/layouts/topRight.js"></script>
<script type="text/javascript" src="http://assets.multitel.net/scripts/plugins/noty/themes/default.js"></script>

<script language="javascript" type="text/javascript">
  function generate_noty(text,type,layout) {
    var n = noty({
                text: text,
                type: type,
                dismissQueue: true,
                layout: layout,
                theme: 'defaultTheme',
                timeout:5000
    });
  }
</script>
<!-- JQueryUI -->
<script src="http://assets.multitel.net/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>

<!-- JQueryUI Touch Punch -->
<!-- small hack that enables the use of touch events on sites using the jQuery UI user interface library -->
<script src="http://assets.multitel.net/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

<script src="http://assets.multitel.net/scripts/plugins/jquery_draftmodaldialog/jquery.draftmodaldialog.js"></script>
<link href="http://assets.multitel.net/scripts/plugins/jquery_draftmodaldialog/jquery.draftmodaldialog.css" rel="stylesheet" />
<link href="http://assets.multitel.net/scripts/plugins/jquery_ui/jquery-ui-1.10.0.custom.css" rel="stylesheet" />
<!-- Modernizr -->
<script src="http://assets.multitel.net/scripts/plugins/system/modernizr.js"></script>

<!-- Bootstrap -->
<script src="http://assets.multitel.net/bootstrap/js/bootstrap.min.js"></script>

<!-- SlimScroll Plugin -->
<script src="http://assets.multitel.net/scripts/plugins/other/jquery-slimScroll/jquery.slimscroll.js"></script>

<!-- MegaMenu -->
<script src="http://assets.multitel.net/scripts/demo/megamenu.js?1374506533"></script>

<!-- jQuery ScrollTo Plugin -->
<!--[if gt IE 8]><!--><script src="http://balupton.github.io/jquery-scrollto/lib/jquery-scrollto.js"></script><!--<![endif]-->

<!-- History.js -->
<!--[if gt IE 8]><!--><script src="http://browserstate.github.io/history.js/scripts/bundled/html4+html5/jquery.history.js"></script><!--<![endif]-->




<!-- Holder Plugin -->
<script src="http://assets.multitel.net/scripts/plugins/other/holder/holder.js?1374506533"></script>

<!-- Uniform Forms Plugin -->
<script src="http://assets.multitel.net/scripts/plugins/forms/pixelmatrix-uniform/jquery.uniform.min.js"></script>

<!-- Bootstrap Extended -->
<script src="http://assets.multitel.net/bootstrap/extend/bootstrap-select/bootstrap-select.js"></script>
<script src="http://assets.multitel.net/bootstrap/extend/bootbox.js"></script>

<!-- Google Code Prettify -->
<script src="http://assets.multitel.net/scripts/plugins/other/google-code-prettify/prettify.js"></script>

<!-- MiniColors Plugin -->
<script src="http://assets.multitel.net/scripts/plugins/color/jquery-miniColors/jquery.miniColors.js"></script>

<!-- Cookie Plugin -->
<script src="http://assets.multitel.net/scripts/plugins/system/jquery.cookie.js"></script>

<!-- Colors -->
<script>
var primaryColor = '#e5412d',
    dangerColor = '#b55151',
    successColor = '#609450',
    warningColor = '#ab7a4b',
    inverseColor = '#45484d';
</script>

<!-- Themer -->
<script>
var themerPrimaryColor = primaryColor;
</script>
<script src="http://assets.multitel.net/scripts/demo/themer.js"></script>

<!-- Bootstrap Image Gallery -->
<script src="http://assets.multitel.net/scripts/plugins/gallery/load-image/js/load-image.min.js"></script>
<script src="http://assets.multitel.net/bootstrap/extend/bootstrap-image-gallery/js/bootstrap-image-gallery.min.js" type="text/javascript"></script>

<!-- Common Demo Script -->
<script src="http://assets.multitel.net/scripts/demo/common.js?1374506533"></script>

<script src="http://assets.multitel.net/js/jquery.form.js" type="text/javascript"></script>
<script src="http://assets.multitel.net/js/ajax_form.js" type="text/javascript"></script>

<link href="http://assets.multitel.net/img/splashy/splashy.css" rel="stylesheet" />
<!-- DataTables Plugin -->


<link href="http://assets.multitel.net/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />
<link href="http://assets.multitel.net/scripts/plugins/tables/DataTables/extras/TableTools/media/css/TableTools.css" rel="stylesheet" />
<link href="http://assets.multitel.net/scripts/plugins/tables/DataTables/extras/ColVis/media/css/ColVis.css" rel="stylesheet" />
<!-- DataTables Tables Plugin -->
<script src="http://assets.multitel.net/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="http://assets.multitel.net/scripts/plugins/tables/DataTables/extras/TableTools/media/js/TableTools.min.js"></script>
<script src="http://assets.multitel.net/scripts/plugins/tables/DataTables/extras/ColVis/media/js/ColVis.min.js"></script>
<script src="http://assets.multitel.net/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>

<script src="http://assets.multitel.net/scripts/plugins/tables/DataTables/extras/Editor/dataTables.editor.js"></script>
<link href="http://assets.multitel.net/scripts/plugins/tables/DataTables/extras/Editor/dataTables.editor.css" rel="stylesheet" />
	



<!-- Select2 Plugin -->
<script src="http://assets.multitel.net/scripts/plugins/forms/select2/select2.js"></script>

<script>
$(document).ready(function()
{
        $(".select_search").select2({
                allowClear: true
        });
});
</script>
