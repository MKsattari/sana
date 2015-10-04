<!--##session content##-->
<!--##include phpcommon.php/phpcommon-config##-->
<!--##include custom.php/phpconfig##-->

<!--## if (bIncludeFiles) { ##-->
<!--##include phpcommon.php/phpcommon-directive##-->
<!--##=SYSTEMFUNCTIONS.IncludeFile("ewconfig","")##-->
<?php $EW_ROOT_RELATIVE_PATH = "<!--##=sAppRootRelativePath##-->"; ?>
<!--##=SYSTEMFUNCTIONS.IncludeFile("_adodb","")##-->
<!--##=SYSTEMFUNCTIONS.IncludeFile("phpmkrfn","")##-->
<!--##include phpcommon-table.php/phpcommon-table-include##-->
<!--##=SYSTEMFUNCTIONS.IncludeFile("userfn","")##-->
<!--##include phpcommon-scripts.php/phppageclassbegin##-->
<!--##include custom.php/phpmain##-->
<!--##include custom.php/phpfunction##-->
<!--##include phpcommon-scripts.php/phpevents##-->
<!--##include phpcommon-scripts.php/phppageclassend##-->
<!--##include phpcommon-scripts.php/phpload##-->
<!--##=SYSTEMFUNCTIONS.IncludeFile("header","")##-->
<!--## } ##-->

<!--## if (bIncludeFiles) { ##-->
<?php if (!@$gbSkipHeaderFooter) { ?>
<div class="ewToolbar">
<!--##include phpcommon.php/breadcrumb##-->
<!--##include phpcommon.php/language##-->
<div class="clearfix"></div>
</div>
<?php } ?>
<!--## } ##-->

<!--##~SYSTEMFUNCTIONS.GetCustomTemplate()##-->

<!--## if (bIncludeFiles) { ##-->
<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<!--##=SYSTEMFUNCTIONS.IncludeFile("footer","")##-->
<!--##include phpcommon-scripts.php/phpunload##-->
<!--## } ##-->
<!--##/session##-->


<!--##session phpconfig##-->
<!--##
	// Set up table var
	gsTblVar = TABLE.TblVar;

	// Include other table class
	dIncludeTable = {};

	// Set up source table
	if (ew_IsNotEmpty(TABLE.TblRptSrc)) {
		SRCTABLE = DB.Tables(TABLE.TblRptSrc);
		if (SRCTABLE) {
			sSrcTblVar = SRCTABLE.TblVar;
			sTblObj = sSrcTblVar;
			CURRENTTABLE = TABLE; // Save current table
			TABLE = SRCTABLE; // Set table object
			dIncludeTable[sSrcTblVar] = ew_GetFileNameByCtrlID("info");
			TABLE = CURRENTTABLE; // Restore current table
		}
	}

	// Set up user table
	if (bUserTable) {
		if (!(sSecTblVar in dIncludeTable)) {
			CURRENTTABLE = TABLE; // Save current table
			TABLE = SECTABLE; // Set table object
			dIncludeTable[sSecTblVar] = ew_GetFileNameByCtrlID("info");
			TABLE = CURRENTTABLE; // Restore current table
		}
	}

	var sFn = TABLE.TblName;
	var bIncludeFiles = (TABLE.IncludeFiles && sFn.toLowerCase().substr(sFn.length-4) == ".php");

	// Custom file relative path
	sRelativePath = "";
	sRelativePathPrefix = "";
	sAppRootRelativePath = "";
	sRelativePath = ew_DestRelPath(TABLE.OutputFolder);
	if (sRelativePath != "") {
		sRelativePathPrefix = "$EW_RELATIVE_PATH . ";
		sAppRootRelativePath = ew_AppRootRelPath(TABLE.OutputFolder);
	}
##-->
<!--##/session##-->


<?php
<!--##session phpmain##-->

	//
	// Page main
	//
	function Page_Main() {
		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}
<!--##/session##-->
?>


<?php
<!--##session phpfunction##-->
	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("<!--##=CTRL.CtrlID##-->", "<!--##=gsTblVar##-->", $url, "", "<!--##=gsTblVar##-->", TRUE);
	}
<!--##/session##-->
?>