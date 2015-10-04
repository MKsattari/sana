<!--##session menu##-->
<!--##
	bGenReportMenu = false;
	sMenuIdPrefix = "mi_";
	sReportMenuIdPrefix = "mri_";
	sCustomMenuIdPrefix = "mci_";
##-->

<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<!--##include phpcommon.php/render-menu##-->
<!-- End Main Menu -->

<!--##/session##-->


<!--##session mobilemenu##-->
<!--##
	bGenReportMenu = false;
	sMenuIdPrefix = "mmi_";
	sReportMenuIdPrefix = "mmri_";
	sCustomMenuIdPrefix = "mmci_";
##-->

<!-- Begin Main Menu -->
<!--##include phpcommon.php/render-menu##-->
<!-- End Main Menu -->

<!--##/session##-->


<!--##session reportmenu##-->
<!--##
	bGenReportMenu = true;
	sMenuIdPrefix = "";
	sReportMenuIdPrefix = "";
	sCustomMenuIdPrefix = "";
##-->

<!-- Begin Main Menu -->
<?php
include_once "<!--##=ew_GetFileNameByCtrlID("ewconfig")##-->";
include_once "<!--##=ew_GetFileNameByCtrlID("ewshared")##-->";
$Language = new cLanguage();
?>
<!--##include phpcommon.php/render-menu##-->
<!-- End Main Menu -->

<!--##/session##-->

