<!--##session detailclass##-->
<!--##
	if ((CTRL.CtrlID == "list" ||
		CTRL.CtrlID == "view" ||
		((CTRL.CtrlID == "edit" || CTRL.CtrlID == "update") && bDetailEdit) ||
		(CTRL.CtrlID == "add" && bDetailAdd)) && (nDetailTableCount > 0)) {
		for (var i = 0, len = arDetailTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arDetailTables[i].index];
			OLDTABLE = TABLE; // Save master table
			MASTERTABLE = DB.Tables(TABLE.TblName);
			TABLE = DB.Tables(MasterDetail.DetailTable);
			if (TABLE.TblGen && TABLE.TblType != "REPORT") {
				sDetailTblVar = TABLE.TblVar;
				sDetailGridClassFn = ew_GetFileNameByCtrlID("gridcls");
##-->
<?php include_once <!--##=sRelativePathPrefix##-->"<!--##=sDetailGridClassFn##-->" ?>
<!--##
			}
			TABLE = OLDTABLE; // Restore master table
		}
	}
##-->
<!--##/session##-->

<!--##session detailgrid##-->
<!--##
	if (bShowMultiPageForDetails) {
##-->
<?php if ($<!--##=gsTblVar##-->->getCurrentDetailTable() <> "") { ?>
<?php
	$FirstActiveDetailTable = $<!--##=sPageObj##-->->DetailPages->ActivePageIndex();
?>
<div class="ewDetailPages">
<!--##
		if (bUseAccordionForMultiPage) {
##-->
<div class="panel-group" id="<!--##=sPageObj##-->_details">
<!--##
		} else {
##-->
<div class="tabbable" id="<!--##=sPageObj##-->_details">
	<ul class="nav<?php echo $<!--##=sPageObj##-->->DetailPages->NavStyle() ?>">
<!--##
		if (CTRL.CtrlID == "view") {
			sDetailPrp = "DetailView";
		} else if (CTRL.CtrlID == "edit") {
			sDetailPrp = "DetailEdit";
		} else if (CTRL.CtrlID == "add") {
			sDetailPrp = "DetailAdd";
		}
		for (var i = 0, len = arDetailTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arDetailTables[i].index];
			OLDTABLE = TABLE; // Save master table
			MASTERTABLE = DB.Tables(TABLE.TblName);
			TABLE = DB.Tables(MasterDetail.DetailTable);
			if (TABLE.TblGen && TABLE.TblType != "REPORT") {
				sDetailTblVar = TABLE.TblVar;
##-->
<?php
	if (in_array("<!--##=sDetailTblVar##-->", explode(",", $<!--##=gsTblVar##-->->getCurrentDetailTable())) && $<!--##=sDetailTblVar##-->-><!--##=sDetailPrp##-->) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "<!--##=sDetailTblVar##-->") {
			$FirstActiveDetailTable = "<!--##=sDetailTblVar##-->";
		}
?>
		<li<?php echo $<!--##=sPageObj##-->->DetailPages->TabStyle("<!--##=sDetailTblVar##-->") ?>><a href="#tab_<!--##=sDetailTblVar##-->" data-toggle="tab"><?php echo $Language->TablePhrase("<!--##=ew_Quote(sDetailTblVar)##-->", "TblCaption") ?></a></li>
<?php
	}
?>
<!--##
			}
			TABLE = OLDTABLE; // Restore master table
		}
##-->
	</ul>
	<div class="tab-content">
<!--##
		}
	}

	if (((CTRL.CtrlID == "view" && bDetailView) || (CTRL.CtrlID == "edit" && bDetailEdit) ||
		(CTRL.CtrlID == "add" && bDetailAdd)) && (nDetailTableCount > 0)) {
		if (CTRL.CtrlID == "view") {
			sDetailPrp = "DetailView";
		} else if (CTRL.CtrlID == "edit") {
			sDetailPrp = "DetailEdit";
		} else if (CTRL.CtrlID == "add") {
			sDetailPrp = "DetailAdd";
		}
		for (var i = 0, len = arDetailTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arDetailTables[i].index];
			OLDTABLE = TABLE; // Save master table
			MASTERTABLE = DB.Tables(TABLE.TblName);
			TABLE = DB.Tables(MasterDetail.DetailTable);
			if (TABLE.TblGen && TABLE.TblType != "REPORT") {
				sDetailTblVar = TABLE.TblVar;
				sDetailGridFn = ew_GetFileNameByCtrlID("grid");
##-->
<?php
	if (in_array("<!--##=sDetailTblVar##-->", explode(",", $<!--##=gsTblVar##-->->getCurrentDetailTable())) && $<!--##=sDetailTblVar##-->-><!--##=sDetailPrp##-->) {
<!--## if (bShowMultiPageForDetails) { ##-->
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "<!--##=sDetailTblVar##-->") {
			$FirstActiveDetailTable = "<!--##=sDetailTblVar##-->";
		}
<!--## } ##-->
?>
<!--## if (!bShowMultiPageForDetails) { ##-->
<?php if ($<!--##=gsTblVar##-->->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("<!--##=ew_Quote(sDetailTblVar)##-->", "TblCaption") ?></h4>
<?php } ?>
<!--## } ##-->
<!--##
	if (bShowMultiPageForDetails) {
		if (bUseAccordionForMultiPage)
			var sDivClass = "panel-collapse collapse";
		else
			var sDivClass = "tab-pane";
		if (bUseAccordionForMultiPage) {
##-->
	<div class="panel panel-default<?php echo $<!--##=sPageObj##-->->DetailPages->PageStyle("<!--##=sDetailTblVar##-->") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#<!--##=sPageObj##-->_details" href="#tab_<!--##=sDetailTblVar##-->"><?php echo $Language->TablePhrase("<!--##=ew_Quote(sDetailTblVar)##-->", "TblCaption") ?></a>
			</h4>
		</div>
		<div class="<!--##=sDivClass##--><?php echo $<!--##=sPageObj##-->->DetailPages->PageStyle("<!--##=sDetailTblVar##-->") ?>" id="tab_<!--##=sDetailTblVar##-->">
			<div class="panel-body">
<!--##
		} else {
##-->
		<div class="<!--##=sDivClass##--><?php echo $<!--##=sPageObj##-->->DetailPages->PageStyle("<!--##=sDetailTblVar##-->") ?>" id="tab_<!--##=sDetailTblVar##-->">
<!--##
		}
	}
##-->
<?php include_once "<!--##=sDetailGridFn##-->" ?>
<!--##
	if (bShowMultiPageForDetails) {
		if (bUseAccordionForMultiPage) {
##-->
			</div>
		</div>
	</div>
<!--##
		} else {
##-->
		</div>
<!--##
		}
	}
##-->
<?php } ?>
<!--##
			}
			TABLE = OLDTABLE; // Restore master table
		}
	}

	if (bShowMultiPageForDetails) {
##-->
<!--## if (bUseAccordionForMultiPage) { ##-->
</div>
<!--## } else { ##-->
	</div>
</div>
<!--## } ##-->
</div>
<?php } ?>
<!--##
	}
##-->
<!--##/session##-->