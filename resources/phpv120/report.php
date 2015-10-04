<!--##session phpconfig##-->
<!--##
	// Set source table
	if (ew_IsNotEmpty(TABLE.TblRptSrc)) {
		SRCTABLE = DB.Tables(TABLE.TblRptSrc);
		sSrcTblVar = SRCTABLE.TblVar;
	}

	var bShowSummary = false; // Show Summary

	var arrSmry = new Array();
	for (i = 0; i <= 3; i++) {
		arrSmry[i] = new Array(2);
	}
	arrSmry[0][0] = "<?php echo $Language->Phrase(\"RptSum\") ?>";
	arrSmry[1][0] = "<?php echo $Language->Phrase(\"RptAvg\") ?>";
	arrSmry[2][0] = "<?php echo $Language->Phrase(\"RptMin\") ?>";
	arrSmry[3][0] = "<?php echo $Language->Phrase(\"RptMax\") ?>";
	arrSmry[0][1] = false;
	arrSmry[1][1] = false;
	arrSmry[2][1] = false;
	arrSmry[3][1] = false;

	for (var i = 0; i < nFldCount; i++) {
		if (GetFldObj(arFlds[i])) {
			if (goFld.FldGroupByShowSummary != 0) {
				bShowSummary = true;
				break;
			}
		}
	} // Field

	if (bShowSummary) {
		for (j = 0; j < nFldCount; j++) {
			if (GetFldObj(arFlds[j])) {
				if (goFld.FldGroupBy <= 0 && goFld.FldHtmlTag != "FILE") {
					if (goFld.FldRptAggSum) arrSmry[0][1] = true;
					if (goFld.FldRptAggAvg) arrSmry[1][1] = true;
					if (goFld.FldRptAggMin) arrSmry[2][1] = true;
					if (goFld.FldRptAggMax) arrSmry[3][1] = true;
				}
			}
		} // Field
	}
	bAggregate = (arrSmry[0][1] || arrSmry[1][1] || arrSmry[2][1] || arrSmry[3][1]);

	sIndent = "<td><div" + ewCSSGroupIndent + "></div></td>";

	nGrps = nGroups;

	arrGrpFlds = sGroupFldNames.split(",");

	nFlds = 0; // No. Of Selected Fields
	var arrDtlFlds = new Array();
	for (i = 0; i < nFldCount; i++) {
		if (GetFldObj(arFlds[i])) {
			if (goFld.FldGroupBy <= 0) {
				arrDtlFlds[nFlds] = goFld.FldName;
				nFlds += 1;
			}
		}
	} // Field
##-->
<!--##/session##-->


<?php
<!--##session phpmain##-->

	var $ExportOptions; // Export options
	var $RecCnt = 0;
	var $RowCnt = 0; // For custom view tag
	var $ReportSql = "";
	var $ReportFilter = "";
	var $DefaultFilter = "";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $MasterRecordExists;
	var $Command;
	var $DtlRecordCount;
	var $ReportGroups;
	var $ReportCounts;
	var $LevelBreak;
	var $ReportTotals;
	var $ReportMaxs;
	var $ReportMins;

	var $Recordset;
	var $DetailRecordset;
	var $RecordExists;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		
		$this->ReportGroups = &ew_InitArray(<!--##=nGrps+1##-->, NULL);
		$this->ReportCounts = &ew_InitArray(<!--##=nGrps+1##-->, 0);
		$this->LevelBreak = &ew_InitArray(<!--##=nGrps+1##-->, FALSE);
		$this->ReportTotals = &ew_Init2DArray(<!--##=nGrps+1##-->, <!--##=nFlds+1##-->, 0);
		$this->ReportMaxs = &ew_Init2DArray(<!--##=nGrps+1##-->, <!--##=nFlds+1##-->, 0);
		$this->ReportMins = &ew_Init2DArray(<!--##=nGrps+1##-->, <!--##=nFlds+1##-->, 0);

		<!--##
			if (nMasterTableCount > 0) {
		##-->
		// Get reset command
		if (@$_GET["cmd"] <> "") {
			$this->Command = strtolower($_GET["cmd"]);
			// Reset master/detail
			if ($this->Command == "resetall") {
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
		<!--##
			for (var i = 0, len = arMasterTables.length; i < len; i++) {
				var MasterDetail = goAllMasDets[arMasterTables[i].index];
				MASTERTABLE = DB.Tables(MasterDetail.MasterTable);
				if (MASTERTABLE.TblGen) {
					for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
						DETAILFIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
						sDetailFldParm = DETAILFIELD.FldParm;
		##-->
				$this-><!--##=sDetailFldParm##-->->setSessionValue("");
		<!--##
					} // MasterDetailField
				}
			} // MasterDetail
		##-->
			}
		}
		<!--##
			}
		##-->

		<!--## if (nMasterTableCount > 0) { ##-->
		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		<!--## } ##-->

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		<!--##
			if (nMasterTableCount > 0) {
		##-->
		<!--##
				for (var i = 0, len = arMasterTables.length; i < len; i++) {
					var MasterDetail = goAllMasDets[arMasterTables[i].index];
					OLDTABLE = TABLE; // Save detail table
					DETAILTABLE = DB.Tables(TABLE.TblName);
					TABLE = DB.Tables(MasterDetail.MasterTable);
					if (TABLE.TblGen) {
						sMasterTblVar = TABLE.TblVar;
						sMasterListFn = ew_GetFileNameByCtrlID("list");
		##-->
		// Build master record SQL
		global $<!--##=sMasterTblVar##-->;
		if ($this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "<!--##=sMasterTblVar##-->") {
			$rsmaster = $<!--##=sMasterTblVar##-->->LoadRs($this->GetMasterFilter());
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
				$this->Page_Terminate("<!--##=sMasterListFn##-->"); // Return to master page
			} else {
				$<!--##=sMasterTblVar##-->->LoadListRowValues($rsmaster);
				$<!--##=sMasterTblVar##-->->RowType = EW_ROWTYPE_MASTER; // Master row
				$<!--##=sMasterTblVar##-->->RenderListRow();
				$rsmaster->Close();
			}
		}
		<!--##
					}
					TABLE = OLDTABLE; // Restore detail table
				} // MasterDetail
			}
		##-->
	}
<!--##/session##-->
?>


<!--##session reportcontent##-->
<div class="ewToolbar">
<!--##include phpcommon.php/breadcrumb##-->
<!--##include phpcommon.php/language##-->
<div class="clearfix"></div>
</div>

<?php

<!--##
	if (nGrps == 0) {
##-->
$<!--##=sPageObj##-->->RecCnt = 1; // No grouping
<!--##
	} else {
		// Process Group Level
		sTblDefaultFilter = SRCTABLE.TblFilter.trim();
		if (sTblDefaultFilter == "") sTblDefaultFilter = "\"\"";
##-->
$<!--##=sPageObj##-->->DefaultFilter = <!--##=sTblDefaultFilter##-->;
$<!--##=sPageObj##-->->ReportFilter = $<!--##=sPageObj##-->->DefaultFilter;
	<!--## if (bUserTable) { ##-->
if (!$Security->CanReport()) {
	if ($<!--##=sPageObj##-->->ReportFilter <> "") $<!--##=sPageObj##-->->ReportFilter .= " AND ";
	$<!--##=sPageObj##-->->ReportFilter .= "(0=1)";
}
	<!--## } ##-->
<!--##
	}
##-->

if ($<!--##=sPageObj##-->->DbDetailFilter <> "") {
	if ($<!--##=sPageObj##-->->ReportFilter <> "") $<!--##=sPageObj##-->->ReportFilter .= " AND ";
	$<!--##=sPageObj##-->->ReportFilter .= "(" . $<!--##=sPageObj##-->->DbDetailFilter . ")";
}

$ReportConn = &$<!--##=sPageObj##-->->Connection();
<!--##
	if (nGrps == 0) {
##-->
// Set up detail SQL
$<!--##=gsTblVar##-->->CurrentFilter = $<!--##=sPageObj##-->->ReportFilter;
$<!--##=sPageObj##-->->ReportSql = $<!--##=gsTblVar##-->->DetailSQL();

// Load recordset
$<!--##=sPageObj##-->->Recordset = $ReportConn->Execute($<!--##=sPageObj##-->->ReportSql);
$<!--##=sPageObj##-->->RecordExists = !$<!--##=sPageObj##-->->Recordset->EOF;
<!--##
	} else {
##-->
// Set up filter and load Group level sql
$<!--##=gsTblVar##-->->CurrentFilter = $<!--##=sPageObj##-->->ReportFilter;
$<!--##=sPageObj##-->->ReportSql = $<!--##=gsTblVar##-->->GroupSQL();

// Load recordset
$<!--##=sPageObj##-->->Recordset = $ReportConn->Execute($<!--##=sPageObj##-->->ReportSql);
$<!--##=sPageObj##-->->RecordExists = !$<!--##=sPageObj##-->->Recordset->EOF;
<!--##
	}
##-->

?>

<!--##=sExpStart##-->
<?php if ($<!--##=sPageObj##-->->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $<!--##=sPageObj##-->->ExportOptions->Render("body") ?></div>
<?php } ?>
<!--##=sExpEnd##-->

<!--##include phpcommon.php/header-message##-->

<table class="ewReportTable">
<?php

<!--##
	if (nGrps > 0) {
##-->

// Get First Row
if ($<!--##=sPageObj##-->->RecordExists) {
	<!--##
		for (var i = 0; i < nGrps; i++) {
			if (GetFldObj(arrGrpFlds[i])) {
				sFld = "$" + sPageObj + "->Recordset->fields('" + SQuote(gsFldName) + "')";
	##-->
	$<!--##=gsFldObj##-->->setDbValue(<!--##=GetFldVal(TABLE, sFld, goFld.FldType)##-->);
	$<!--##=sPageObj##-->->ReportGroups[<!--##=i##-->] = $<!--##=gsFldObj##-->->DbValue;
	<!--##
			}
		} // Groups
	##-->
}
$<!--##=sPageObj##-->->RecCnt = 0;
$<!--##=sPageObj##-->->ReportCounts[0] = 0;
$<!--##=sPageObj##-->->ChkLvlBreak();
while (!$<!--##=sPageObj##-->->Recordset->EOF) {

	// Render for view
	$<!--##=gsTblVar##-->->RowType = EW_ROWTYPE_VIEW;
	$<!--##=gsTblVar##-->->ResetAttrs();
	$<!--##=sPageObj##-->->RenderRow();

	// Show group headers
	<!--##
		var nColSpan, nFldSpan;
		for (var i = 0; i < nGrps; i++) {
			grpID = i + 1;
			sTDIndent = "";
			if (ew_IsNotEmpty(sIndent)) {
				for (j = 1; j <= i; j++)
					sTDIndent += sIndent;
				nColSpan = nGrps - i;
				nFldSpan = nFlds;
			} else {
				nColspan = 1;
				if (bAggregate)
					nFldSpan = nFlds;
				else
					nFldSpan = nFlds - 1;
			}
			if (nColSpan > 1)
				sTDSpan = " colspan=" + nColSpan;
			else
				sTDSpan = "";
			if (GetFldObj(arrGrpFlds[i])) {
	##-->
	if ($<!--##=sPageObj##-->->LevelBreak[<!--##=grpID##-->]) { // Reset counter and aggregation
?>
	<tr><!--##=sTDIndent##--><td<!--##=sTDSpan##--><!--##=ewCSSGroupField##-->><?php echo $<!--##=gsFldObj##-->->FldCaption() ?></td>
	<td colspan=<!--##=nFldSpan##--><!--##=ewCSSGroupName##-->><!--##=SYSTEMFUNCTIONS.FieldView()##--></td></tr>
<?php
	}
	<!--##
			}
		} // Group
	##-->
<!--##
	}
##-->

<!--##
	// Process detail level
	sDetailWhere = "";
//	sOrderBy = sOrderFldSrcs;
##-->

	// Get detail records
	$<!--##=sPageObj##-->->ReportFilter = $<!--##=sPageObj##-->->DefaultFilter;
<!--##
	if (ew_IsNotEmpty(sGroupFldNames)) {
		for (i = 0; i < nGrps; i++) {
			if (GetFldObj(arrGrpFlds[i])) {
				sFldTypeName = GetFieldTypeName(goFld.FldType);
				if (ew_GetFieldType(goFld.FldType) == 2 &&
					goFld.FldViewTag == "FORMATTED TEXT" && goFld.FldFmtType == "Date/Time")
					sFilterFld = "ew_UnFormatDateTime(ew_FormatDateTime($" + gsFldObj + "->CurrentValue," + goFld.FldDtFormat + ")," + goFld.FldDtFormat + ")";
				else
					sFilterFld = "$" + gsFldObj + "->CurrentValue";
##-->
	if ($<!--##=sPageObj##-->->ReportFilter <> "") $<!--##=sPageObj##-->->ReportFilter .= " AND ";
	if (is_null(<!--##=sFilterFld##-->)) {
		$<!--##=sPageObj##-->->ReportFilter .= "(<!--##=ew_Quote(gsFld)##--> IS NULL)";
	} else {
		$<!--##=sPageObj##-->->ReportFilter .= "(<!--##=ew_Quote(gsFld)##--> = " . ew_QuotedValue(<!--##=sFilterFld##-->, <!--##=sFldTypeName##-->, $<!--##=sPageObj##-->->DBID) . ")";
	}
<!--##
			}
		} // Group
	}
##-->
	if ($<!--##=sPageObj##-->->DbDetailFilter <> "") {
		if ($<!--##=sPageObj##-->->ReportFilter <> "")
			$<!--##=sPageObj##-->->ReportFilter .= " AND ";
		$<!--##=sPageObj##-->->ReportFilter .= "(" . $<!--##=sPageObj##-->->DbDetailFilter . ")";
	}

<!--## if (bUserTable) { ##-->
	if (!$Security->CanReport()) {
		if ($sFilter <> "") $sFilter .= " AND ";
		$sFilter .= "(0=1)";
	}
<!--## } ##-->

	// Set up detail SQL
	$<!--##=gsTblVar##-->->CurrentFilter = $<!--##=sPageObj##-->->ReportFilter;
	$<!--##=sPageObj##-->->ReportSql = $<!--##=gsTblVar##-->->DetailSQL();

	// Load detail records
	$<!--##=sPageObj##-->->DetailRecordset = $ReportConn->Execute($<!--##=sPageObj##-->->ReportSql);
	$<!--##=sPageObj##-->->DtlRecordCount = $<!--##=sPageObj##-->->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$<!--##=sPageObj##-->->DetailRecordset->EOF) {
		$<!--##=sPageObj##-->->RecCnt++;
<!--##
	for (var i = 0; i < nFlds; i++) {
		if (GetFldObj(arrDtlFlds[i])) {
			if (goFld.FldRptAggSum || goFld.FldRptAggAvg || goFld.FldRptAggMin || goFld.FldRptAggMax) {
				sFld = "$" + sPageObj + "->DetailRecordset->fields('" + SQuote(gsFldName) + "')";
##-->
		$<!--##=gsFldObj##-->->setDbValue(<!--##=GetFldVal(TABLE, sFld, goFld.FldType)##-->);
<!--##
			}
		}
	} // Field
##-->
	}
	if ($<!--##=sPageObj##-->->RecCnt == 1) {
		$<!--##=sPageObj##-->->ReportCounts[0] = 0;
<!--##
	for (var i = 0; i < nFlds; i++) {
		if (GetFldObj(arrDtlFlds[i])) {
			if (goFld.FldRptAggSum || goFld.FldRptAggAvg) {
##-->
		$<!--##=sPageObj##-->->ReportTotals[0][<!--##=i##-->] = 0;
	<!--##
			}
			if (goFld.FldRptAggMin) {
	##-->
		if (!$<!--##=sPageObj##-->->DetailRecordset->EOF) {
			$<!--##=sPageObj##-->->ReportMins[0][<!--##=i##-->] = $<!--##=gsFldObj##-->->DbValue;
		} else {
			$<!--##=sPageObj##-->->ReportMins[0][<!--##=i##-->] = 0;
		}
	<!--##
			}
			if (goFld.FldRptAggMax) {
	##-->
		if (!$<!--##=sPageObj##-->->DetailRecordset->EOF) {
			$<!--##=sPageObj##-->->ReportMaxs[0][<!--##=i##-->] = $<!--##=gsFldObj##-->->DbValue;
		} else {
			$<!--##=sPageObj##-->->ReportMaxs[0][<!--##=i##-->] = 0;
		}
<!--##
			}
		}
	} // Field
##-->
	}
<!--##
	if (nGrps > 0) {
##-->
	for ($i = 1; $i <= <!--##=nGrps##-->; $i++) {
		if ($<!--##=sPageObj##-->->LevelBreak[$i]) { // Reset counter and aggregation
			$<!--##=sPageObj##-->->ReportCounts[$i] = 0;
	<!--##
		for (i = 0; i < nFlds; i++) {
			if (GetFldObj(arrDtlFlds[i])) {
				if (goFld.FldRptAggSum || goFld.FldRptAggAvg) {
	##-->
			$<!--##=sPageObj##-->->ReportTotals[$i][<!--##=i##-->] = 0;
	<!--##
				}
				if (goFld.FldRptAggMin) {
	##-->
			if (!$<!--##=sPageObj##-->->DetailRecordset->EOF) {
				$<!--##=sPageObj##-->->ReportMins[$i][<!--##=i##-->] = $<!--##=gsFldObj##-->->CurrentValue;
			} else {
				$<!--##=sPageObj##-->->ReportMins[$i][<!--##=i##-->] = 0;
			}
	<!--##
				}
				if (goFld.FldRptAggMax) {
	##-->
			if (!$<!--##=sPageObj##-->->DetailRecordset->EOF) {
				$<!--##=sPageObj##-->->ReportMaxs[$i][<!--##=i##-->] = $<!--##=gsFldObj##-->->CurrentValue;
			} else {
				$<!--##=sPageObj##-->->ReportMaxs[$i][<!--##=i##-->] = 0;
			}
	<!--##
				}
			}
		} // Field
	##-->
		}
	}
<!--##
	}
##-->

<!--##
	for (i = 0; i <= nGrps; i++) {
##-->
	$<!--##=sPageObj##-->->ReportCounts[<!--##=i##-->] += $<!--##=sPageObj##-->->DtlRecordCount;
<!--##
	} // Group
##-->
	if ($<!--##=sPageObj##-->->RecordExists) {
?>
	<tr>
<!--##
	if (ew_IsNotEmpty(sIndent)) {
		for (i = 1; i <= nGrps; i++) {
##-->
		<!--##=sIndent##-->
<!--##
		}
	} else {
		if (bAggregate) {
##-->
		<td></td>
<!--##
		}
	}
	for (i = 0; i < nFlds; i++) {
		if (GetFldObj(arrDtlFlds[i])) {
##-->
		<td<!--##=ewCSSGroupHeader##-->><?php echo $<!--##=gsFldObj##-->->FldCaption() ?></td>
<!--##
		}
	} // Field
##-->
	</tr>
<?php
	}
	while (!$<!--##=sPageObj##-->->DetailRecordset->EOF) {
		$<!--##=sPageObj##-->->RowCnt++;
<!--##
	for (var i = 0; i < nFlds; i++) {
		if (GetFldObj(arrDtlFlds[i])) {
			sFld = "$" + sPageObj + "->DetailRecordset->fields('" + SQuote(gsFldName) + "')";
			if (goFld.FldHtmlTag == "FILE") {
##-->
		$<!--##=gsFldObj##-->->Upload->DbValue = <!--##=sFld##-->;
<!--##
			} else {
##-->
		$<!--##=gsFldObj##-->->setDbValue(<!--##=GetFldVal(TABLE, sFld, goFld.FldType)##-->);
	<!--##
			}
			for (var j = 0; j <= nGrps; j++) {
				if (goFld.FldRptAggSum || goFld.FldRptAggAvg) {
	##-->
		$<!--##=sPageObj##-->->ReportTotals[<!--##=j##-->][<!--##=i##-->] += $<!--##=gsFldObj##-->->CurrentValue;
	<!--##
				}
				if (goFld.FldRptAggMin) {
	##-->
		if ($<!--##=sPageObj##-->->ReportMins[<!--##=j##-->][<!--##=i##-->] > $<!--##=gsFldObj##-->->CurrentValue)
			$<!--##=sPageObj##-->->ReportMins[<!--##=j##-->][<!--##=i##-->] = $<!--##=gsFldObj##-->->CurrentValue;
	<!--##
				}
				if (goFld.FldRptAggMax) {
	##-->
		if ($<!--##=sPageObj##-->->ReportMaxs[<!--##=j##-->][<!--##=i##-->] < $<!--##=gsFldObj##-->->CurrentValue)
			$<!--##=sPageObj##-->->ReportMaxs[<!--##=j##-->][<!--##=i##-->] = $<!--##=gsFldObj##-->->CurrentValue;
<!--##
				}
			} // Group
		}
	} // Field
##-->
		// Render for view
		$<!--##=gsTblVar##-->->RowType = EW_ROWTYPE_VIEW;
		$<!--##=gsTblVar##-->->ResetAttrs();
		$<!--##=sPageObj##-->->RenderRow();
?>
<!--##
	if (TABLE.TblRptShowDetails) {
##-->
	<tr>
	<!--##
		if (ew_IsNotEmpty(sIndent)) {
			for (var i = 1; i <= nGrps; i++) {
	##-->
		<!--##=sIndent##-->
	<!--##
			}
		} else {
			if (bAggregate) {
	##-->
		<td></td>
	<!--##
			}
		}
		for (var i = 0; i < nFlds; i++) {
			if (GetFldObj(arrDtlFlds[i])) {
	##-->
		<td<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldView()##--></td>
	<!--##
			}
		}
	##-->
	</tr>
<!--##
	}
##-->
<?php
		$<!--##=sPageObj##-->->DetailRecordset->MoveNext();
	}
	$<!--##=sPageObj##-->->DetailRecordset->Close();

<!--##
	if (nGrps > 0) {
##-->
	// Save old group data
	<!--##
		for (var i = 0; i < nGrps; i++) {
			if (GetFldObj(arrGrpFlds[i])) {
	##-->
	$<!--##=sPageObj##-->->ReportGroups[<!--##=i##-->] = $<!--##=gsFldObj##-->->CurrentValue;
	<!--##
			}
		}
	##-->

	// Get next record
	$<!--##=sPageObj##-->->Recordset->MoveNext();
	if ($<!--##=sPageObj##-->->Recordset->EOF) {
		$<!--##=sPageObj##-->->RecCnt = 0; // EOF, force all level breaks
	} else {
	<!--##
		for (var i = 0; i < nGrps; i++) {
			if (GetFldObj(arrGrpFlds[i])) {
				sFld = "$" + sPageObj + "->Recordset->fields('" + SQuote(gsFldName) + "')";
	##-->
		$<!--##=gsFldObj##-->->setDbValue(<!--##=GetFldVal(TABLE, sFld, goFld.FldType)##-->);
	<!--##
			}
		}
	##-->
	}
	$<!--##=sPageObj##-->->ChkLvlBreak();

	// Show footers
	<!--##
		for (var i = nGrps-1; i >= 0; i--) {
			grpID = i + 1;
			sTDIndent = "";
			if (ew_IsNotEmpty(sIndent)) {
				for (j = 1; j <= i; j++)
					sTDIndent += sIndent;
				nColSpan = nGrps - i;
				nColSpanSum = nColSpan + nFlds;
			} else {
				nColSpan = 1;
				if (bAggregate)
					nColSpanSum = nFlds + 1;
				else
					nColSpanSum = nFlds;
			}
			if (nColSpan > 1)
				sTDSpan = " colspan=" + nColSpan;
			else
				sTDSpan = "";
			if (nColSpanSum > 1)
				sTDSpanSum = " colspan=" + nColSpanSum;
			else
				sTDSpanSum = "";
			if (GetFldObj(arrGrpFlds[i])) {
	##-->
	if ($<!--##=sPageObj##-->->LevelBreak[<!--##=grpID##-->]) {
		$<!--##=gsFldObj##-->->CurrentValue = $<!--##=sPageObj##-->->ReportGroups[<!--##=i##-->];
		// Render row for view
		$<!--##=gsTblVar##-->->RowType = EW_ROWTYPE_VIEW;
		$<!--##=gsTblVar##-->->ResetAttrs();
		$<!--##=sPageObj##-->->RenderRow();
		$<!--##=gsFldObj##-->->CurrentValue = $<!--##=gsFldObj##-->->DbValue;
?>
<!--##
	//if (bShowSummary) {
	if (goFld.FldGroupByShowSummary) {
##-->
	<tr><!--##=sTDIndent##--><td<!--##=sTDSpanSum##--><!--##=ewCSSGroupSummary##-->><!--##@RptSumHead##-->&nbsp;<?php echo $<!--##=gsFldObj##-->->FldCaption() ?>:&nbsp;<?php echo $<!--##=gsFldObj##-->->ViewValue ?> (<?php echo ew_FormatNumber($<!--##=sPageObj##-->->ReportCounts[<!--##=grpID##-->],0) ?> <!--##@RptDtlRec##-->)</td></tr>
	<!--##
		for (j = 0; j <= 3; j++) {
			if (arrSmry[j][1]) {
	##-->
<?php
	<!--##
					for (k = 0; k < nFlds; k++) {
						if (GetFldObj(arrDtlFlds[k])) {
							if (j == 0 && goFld.FldRptAggSum) {
	##-->
	$<!--##=gsFldObj##-->->CurrentValue = $<!--##=sPageObj##-->->ReportTotals[<!--##=grpID##-->][<!--##=k##-->];
	<!--##
							} else if (j == 1 && goFld.FldRptAggAvg) {
	##-->
	if ($<!--##=sPageObj##-->->ReportCounts[<!--##=grpID##-->] > 0) {
		$<!--##=gsFldObj##-->->CurrentValue = $<!--##=sPageObj##-->->ReportTotals[<!--##=grpID##-->][<!--##=k##-->] / $<!--##=sPageObj##-->->ReportCounts[<!--##=grpID##-->];
	} else {
		$<!--##=gsFldObj##-->->CurrentValue = 0;
	}
	<!--##
							} else if (j == 2 && goFld.FldRptAggMin) {
	##-->
	$<!--##=gsFldObj##-->->CurrentValue = $<!--##=sPageObj##-->->ReportMins[<!--##=grpID##-->][<!--##=k##-->];
	<!--##
							} else if (j == 3 && goFld.FldRptAggMax) {
	##-->
	$<!--##=gsFldObj##-->->CurrentValue = $<!--##=sPageObj##-->->ReportMaxs[<!--##=grpID##-->][<!--##=k##-->];
	<!--##
							}
						}
					} // Field
	##-->
	// Render row for view
	$<!--##=gsTblVar##-->->RowType = EW_ROWTYPE_VIEW;
	$<!--##=gsTblVar##-->->ResetAttrs();
	$<!--##=sPageObj##-->->RenderRow();
?>
	<tr><!--##=sTDIndent##-->
		<td<!--##=sTDSpan##--><!--##=ewCSSGroupAggregate##-->><!--##=arrSmry[j][0]##--></td>
	<!--##
				for (k = 0; k < nFlds; k++) {
					if (GetFldObj(arrDtlFlds[k])) {
						if ((j == 0 && goFld.FldRptAggSum) ||
							(j == 1 && goFld.FldRptAggAvg) ||
							(j == 2 && goFld.FldRptAggMin) ||
							(j == 3 && goFld.FldRptAggMax)) {
	##-->
		<td<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldView()##--></td>
	<!--##
						} else {
	##-->
		<td>&nbsp;</td>
	<!--##
						}
					}
				} // Field
	##-->
	</tr>
	<!--##
			}
		} // Summary
	##-->
	<tr><td colspan=<!--##=nFlds+nGrps##-->>&nbsp;<br></td></tr>
<!--##
	}
##-->
<?php
}
	<!--##
		}
	} // Group
	##-->
}

// Close recordset
$<!--##=sPageObj##-->->Recordset->Close();
<!--##
	}
##-->
?>
<!--##
	var nDtlSpan = 0;
	if (TABLE.TblRptShowGrandTotal) {
##-->
<?php if ($<!--##=sPageObj##-->->RecordExists) { ?>
	<!--##
		if (nGrps > 0 && ew_IsNotEmpty(sIndent)) {
			nDtlSpan = nGrps;
		} else {
			if (bAggregate)
				nDtlSpan = 1;
			else
				nDtlSpan = 0;
		}
		if (nDtlSpan > 1)
			sTDSpan = " colspan=" + nDtlSpan;
		else
			sTDSpan = "";
	##-->
	<tr><td colspan=<!--##=nFlds+nDtlSpan##-->>&nbsp;<br></td></tr>
	<tr><td colspan=<!--##=nFlds+nDtlSpan##--><!--##=ewCSSGrandSummary##-->><!--##@RptGrandTotal##-->&nbsp;(<?php echo ew_FormatNumber($<!--##=sPageObj##-->->ReportCounts[0], 0) ?>&nbsp;<!--##@RptDtlRec##-->)</td></tr>
	<!--##
		for (var j = 0; j <= 3; j++) {
			if (arrSmry[j][1]) {
	##-->
<?php
	<!--##
					for (k = 0; k < nFlds; k++) {
						if (GetFldObj(arrDtlFlds[k])) {
							if (j == 0 && goFld.FldRptAggSum) {
	##-->
	$<!--##=gsFldObj##-->->CurrentValue = $<!--##=sPageObj##-->->ReportTotals[0][<!--##=k##-->];
	<!--##
							} else if (j == 1 && goFld.FldRptAggAvg) {
	##-->
	if ($<!--##=sPageObj##-->->ReportCounts[<!--##=grpID##-->] > 0) {
		$<!--##=gsFldObj##-->->CurrentValue = $<!--##=sPageObj##-->->ReportTotals[0][<!--##=k##-->] / $<!--##=sPageObj##-->->ReportCounts[0];
	} else {
		$<!--##=gsFldObj##-->->CurrentValue = 0;
	}
	<!--##
							} else if (j == 2 && goFld.FldRptAggMin) {
	##-->
	$<!--##=gsFldObj##-->->CurrentValue = $<!--##=sPageObj##-->->ReportMins[0][<!--##=k##-->];
	<!--##
							} else if (j == 3 && goFld.FldRptAggMax) {
	##-->
	$<!--##=gsFldObj##-->->CurrentValue = $<!--##=sPageObj##-->->ReportMaxs[0][<!--##=k##-->];
	<!--##
							}
						}
					} // Field
	##-->
	// Render row for view
	$<!--##=gsTblVar##-->->RowType = EW_ROWTYPE_VIEW;
	$<!--##=gsTblVar##-->->ResetAttrs();
	$<!--##=sPageObj##-->->RenderRow();
?>
	<tr>
		<td<!--##=sTDSpan##--><!--##=ewCSSGroupAggregate##-->><!--##=arrSmry[j][0]##--></td>
	<!--##
				for (k = 0; k < nFlds; k++) {
					if (GetFldObj(arrDtlFlds[k])) {
						if ((j == 0 && goFld.FldRptAggSum) ||
							(j == 1 && goFld.FldRptAggAvg) ||
							(j == 2 && goFld.FldRptAggMin) ||
							(j == 3 && goFld.FldRptAggMax)) {
	##-->
		<td<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldView()##--></td>
	<!--##
						} else {
	##-->
		<td>&nbsp;</td>
	<!--##
						}
					}
				} // Field
	##-->
	</tr>
	<!--##
			}
		} // Summary
	##-->
<?php } ?>
<!--##
	}
##-->
<?php if ($<!--##=sPageObj##-->->RecordExists) { ?>
	<tr><td colspan=<!--##=nFlds+nDtlSpan##-->>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>

<!--##include phpcommon.php/footer-message##-->
<!--##/session##-->


<?php
<!--##session phpfunction##-->
<!--##
	if (nGrps > 0) {
##-->
	// Check level break
	function ChkLvlBreak() {
		<!--##
			for (i = 1; i <= nGrps; i++) {
		##-->
		$this->LevelBreak[<!--##=i##-->] = FALSE;
		<!--##
			}
		##-->
		if ($this->RecCnt == 0) { // Start Or End of Recordset
		<!--##
			for (i = 1; i <= nGrps; i++) {
		##-->
			$this->LevelBreak[<!--##=i##-->] = TRUE;
		<!--##
			}
		##-->
		} else {
		<!--##
			for (i = 0; i < nGrps; i++) {
				if (GetFldObj(arrGrpFlds[i])) {
		##-->
			if (!ew_CompareValue($this-><!--##=gsFldParm##-->->CurrentValue, $this->ReportGroups[<!--##=i##-->])) {
		<!--##
				for (j = i+1; j <= nGrps; j++) {
		##-->
				$this->LevelBreak[<!--##=j##-->] = TRUE;
		<!--##
				}
		##-->
			}
		<!--##
				}
			} // Group
		##-->
		}
	}
<!--##
	}
##-->
<!--##/session##-->
?>