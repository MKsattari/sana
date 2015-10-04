<?php
<!--##session phpmain##-->

	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
<!--## if (bDetailViewPaging) { ##-->
	var $Pager;
<!--## } ##-->
	var $RecCnt;
	var $RecKey = array();

<!--##
	if (bDetailShowCount) {
		for (var i = 0, len = arDetailTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arDetailTables[i].index];
			DETAILTABLE = DB.Tables(MasterDetail.DetailTable);
			if (nDetailTableCount > 0 && DETAILTABLE.TblGen && DETAILTABLE.TblType != "REPORT") {
				sDetailTblVar = DETAILTABLE.TblVar;
##-->
	var $<!--##=sDetailTblVar##-->_Count;
<!--##
			}
		} // MasterDetail
	}
##-->

	var $Recordset;

<!--## if (bMultiPage) { ##-->
	var $MultiPages; // Multi pages object
<!--## } ##-->

<!--## if (bShowMultiPageForDetails) { ##-->
	var $DetailPages; // Detail pages object
<!--## } ##-->

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		<!--## if (bDetailViewPaging || (bViewExport || (bExportCsv || bExportWord || bExportExcel || bExportXml))) { ##-->
		// Load current record
		$bLoadCurrentRecord = FALSE;
		<!--## } ##-->
		
		$sReturnUrl = "";

		$bMatchRecord = FALSE;

	<!--## if (nMasterTableCount > 0) { ##-->
		// Set up master/detail parameters
		$this->SetUpMasterParms();
	<!--## } ##-->

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		if ($this->IsPageRequest()) { // Validate request
		
			<!--##
				for (var i = 0; i < nKeyCount; i++) {
					if (GetFldObj(arKeyFlds[i])) {
			##-->
			if (@$_GET["<!--##=gsFldParm##-->"] <> "") {
				$this-><!--##=gsFldParm##-->->setQueryStringValue($_GET["<!--##=gsFldParm##-->"]);
				$this->RecKey["<!--##=gsFldParm##-->"] = $this-><!--##=gsFldParm##-->->QueryStringValue;
			} elseif (@$_POST["<!--##=gsFldParm##-->"] <> "") {
				$this-><!--##=gsFldParm##-->->setFormValue($_POST["<!--##=gsFldParm##-->"]);
				$this->RecKey["<!--##=gsFldParm##-->"] = $this-><!--##=gsFldParm##-->->FormValue;
			} else {
				<!--## if (bDetailViewPaging) { ##-->
				$bLoadCurrentRecord = TRUE;
				<!--## } else { ##-->
				$sReturnUrl = "<!--##=sFnList##-->"; // Return to list
				<!--## } ##-->
			}
			<!--##
					}
				} // Field
			##-->
		
			// Get action
			$this->CurrentAction = "I"; // Display form
			
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
			
			<!--## if (bDetailViewPaging) { ##-->

					$this->StartRec = 1; // Initialize start position
					if ($this->Recordset = $this->LoadRecordset()) // Load records
						$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
					if ($this->TotalRecs <= 0) { // No record found
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$this->Page_Terminate("<!--##=sFnList##-->"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position
						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
				<!--##
					sMatchCond = "";
					for (var i = 0; i < nKeyCount; i++) {
						if (GetFldObj(arKeyFlds[i])) {
							if (ew_IsNotEmpty(sMatchCond)) sMatchCond += " && ";
							sMatchCond += "strval($this->" + gsFldParm + "->CurrentValue) == strval($this->Recordset->fields('" + SQuote(gsFldName) + "'))";
						}
					} // Field
				##-->
							if (<!--##=sMatchCond##-->) {
								$this->setStartRecordNumber($this->StartRec); // Save record position
								$bMatchRecord = TRUE;
								break;
							} else {
								$this->StartRec++;
								$this->Recordset->MoveNext();
							}
						}
					}
					if (!$bMatchRecord) {
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "<!--##=sFnList##-->"; // No matching record, return to list
					} else {
						$this->LoadRowValues($this->Recordset); // Load row values
					}

			<!--## } else { ##-->

					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "<!--##=sFnList##-->"; // No matching record, return to list
					}

			<!--## } ##-->
			
			}
			
			<!--##
				if (bViewExport) {
					if (bExportHtml || bExportEmail || bExportCsv || bExportWord || bExportExcel || bExportXml || bExportPDF) {
			##-->
			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
			<!--##
					}
				}
			##-->
		
		} else {
			$sReturnUrl = "<!--##=sFnList##-->"; // Not page request, return to list
		}

		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();

	<!--## if (bDetailView && nDetailTableCount > 0) { ##-->
		// Set up detail parameters
		$this->SetUpDetailParms();
	<!--## } ##-->

	}
<!--##/session##-->
?>

<!--##session htmtable##-->

<!--##=sExpStart##-->
<div class="ewToolbar">
<!--##include phpcommon.php/breadcrumb##-->

<?php $<!--##=sPageObj##-->->ExportOptions->Render("body") ?>
<?php
	foreach ($<!--##=sPageObj##-->->OtherOptions as &$option)
		$option->Render("body");
?>

<!--##include phpcommon.php/language##-->
<div class="clearfix"></div>
</div>
<!--##=sExpEnd##-->

<!--##include phpcommon.php/header-message##-->
<!--##include phpcommon.php/phpcommon-message##-->

<!--## if (bDetailViewPaging && bTopPageLink) { ##-->
<!--##=sExpStart##-->
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<!--##include pager.php/pager##-->
<div class="clearfix"></div>
</form>
<!--##=sExpEnd##-->
<!--## } ##-->

<form name="<!--##=sFormName##-->" id="<!--##=sFormName##-->" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($<!--##=sPageObj##-->->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $<!--##=sPageObj##-->->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="<!--##=gsTblVar##-->">

<!--## if (bUseCustomTemplate) { ##-->
<!--##~SYSTEMFUNCTIONS.GetCustomTemplate()##-->
<!--## } ##-->

<!--##
	var sMainTableStyle = "";
	var sPageTableStyle = "";
	var sPageZeroTableStyle = "";
	if (bUseCustomTemplate) {
		sPageZeroTableStyle = " style=\"display: none\"";
		if (!bUseCustomMultiPageTemplate)
			sMainTableStyle = " style=\"display: none\"";
		sPageTableStyle = " style=\"display: none\"";
	}

	// Field with page index = 0
	if (bMultiPage && bMultiPageZero) {
		var sTableId = ewTableId + "0";
##-->
<?php if ($<!--##=sPageObj##-->->MultiPages->Items[0]->Visible) { ?>
<table<!--##=ewCSSTableClass##--><!--##=sPageZeroTableStyle##-->>

<!--##
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				if (glFldPageIndex == 0) {
##-->
<!--##include view-script.php/fieldlayout##-->
<!--##
				}
			}
		} // Field
##-->

</table>
<?php } ?>
<!--##
	}
##-->

<!--##
	// Multi page
	if (bMultiPage) {
##-->
<?php if ($<!--##=gsTblVar##-->->Export == "") { ?>
<div class="ewMultiPage"<!--##=sMainTableStyle##-->>
<!--## if (bUseAccordionForMultiPage) { ##-->
<div class="panel-group" id="<!--##=sPageObj##-->">
<!--## } else { ##-->
<div class="tabbable" id="<!--##=sPageObj##-->">
	<ul class="nav<?php echo $<!--##=sPageObj##-->->MultiPages->NavStyle() ?>">
<!--##
		for (var i = 1; i <= nPage; i++) {
			if (String(i) in dPages) {
##-->
		<li<?php echo $<!--##=sPageObj##-->->MultiPages->TabStyle("<!--##=i##-->") ?>><a href="#tab_<!--##=gsTblVar##--><!--##=i##-->" data-toggle="tab"><?php echo $<!--##=gsTblVar##-->->PageCaption(<!--##=i##-->) ?></a></li>
<!--##
			} else {
##-->
		<li style="display: none"><a href="#tab_<!--##=gsTblVar##--><!--##=i##-->" data-toggle="tab"></a></li>
<!--##
			}
		} // Page
##-->
	</ul>
	<div class="tab-content">
<!--## } ##-->
<?php } ?>
<!--##
	}
##-->

<!--##
	for (var pageidx = 1; pageidx <= nPage; pageidx++) {
		var sTableId = ewTableId + ((bMultiPage) ? pageidx : "");
		if (bMultiPage) {
##-->
<?php if ($<!--##=gsTblVar##-->->Export == "") { ?>
<!--## if (bUseAccordionForMultiPage) { ##-->
	<div class="panel panel-default<?php echo $<!--##=sPageObj##-->->MultiPages->PageStyle("<!--##=pageidx##-->") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#<!--##=sPageObj##-->" href="#tab_<!--##=gsTblVar##--><!--##=pageidx##-->"><?php echo $<!--##=gsTblVar##-->->PageCaption(<!--##=pageidx##-->) ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $<!--##=sPageObj##-->->MultiPages->PageStyle("<!--##=pageidx##-->") ?>" id="tab_<!--##=gsTblVar##--><!--##=pageidx##-->">
			<div class="panel-body">
<!--## } else { ##-->
		<div class="tab-pane<?php echo $<!--##=sPageObj##-->->MultiPages->PageStyle("<!--##=pageidx##-->") ?>" id="tab_<!--##=gsTblVar##--><!--##=pageidx##-->">
<!--## } ##-->
<?php } ?>
<!--##
		}
		if (!TABLE.TblMultiPageView || (TABLE.TblMultiPageView && String(pageidx) in dPages)) {
##-->

<table<!--##=ewCSSTableClass##--><!--##=sPageTableStyle##-->>

<!--##
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				if (!TABLE.TblMultiPageView || (TABLE.TblMultiPageView && glFldPageIndex == pageidx)) {
##-->
<!--##include view-script.php/fieldlayout##-->
<!--##
				}
			}
		} // Field
##-->

</table>
<!--## if (bMultiPage && bUseCustomMultiPageTemplate) { ##-->
<!--##~SYSTEMFUNCTIONS.GetCustomTemplatePage(pageidx)##-->
<!--## } ##-->

<!--##
		}
		if (bMultiPage) {
##-->
<?php if ($<!--##=gsTblVar##-->->Export == "") { ?>
<!--## if (bUseAccordionForMultiPage) { ##-->
			</div>
		</div>
	</div>
<!--## } else { ##-->
		</div>
<!--## } ##-->
<?php } ?>
<!--##
		}
	} // Page
##-->

<!--##
	// Multi page
	if (bMultiPage) {
##-->
<?php if ($<!--##=gsTblVar##-->->Export == "") { ?>
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

<!--## if (bDetailViewPaging && bBottomPageLink) { ##-->
<!--##=sExpStart##-->
<!--##include pager.php/pager##-->
<div class="clearfix"></div>
<!--##=sExpEnd##-->
<!--## } ##-->

<!--##include detail-script.php/detailgrid##-->

</form>

<!--## if (bUseCustomTemplate) { ##-->
<!--##~SYSTEMFUNCTIONS.RenderCustomTemplate()##-->
<!--## } ##-->

<!--##=sExpStart##-->
<script type="text/javascript">
<!--##=sFormName##-->.Init();
</script>
<!--##=sExpEnd##-->

<!--##include phpcommon.php/footer-message##-->
<!--##/session##-->


<!--##session fieldlayout##-->
<!--##
	sHeaderSpanId = "elh_" + gsTblVar + "_" + gsFldParm;
##-->
<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=goFld.FldName##--> ?>
	<tr id="r_<!--##=gsFldParm##-->">
		<td><span id="<!--##=sHeaderSpanId##-->"><!--##=SYSTEMFUNCTIONS.FieldCaption()##--></span></td>
		<td data-name="<!--##=gsFldParm##-->"<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldView()##--></td>
	</tr>
<?php } ?>
<!--##/session##-->


<?php
<!--##session phpfunction##-->

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;

		$options = &$this->OtherOptions;
		$option = &$options["action"];
<!--##
	// Set up link visibility
	sMasterViewVisible = ew_SecurityCheck("View", bSecurityEnabled, bUserTable);
	sMasterEditVisible = ew_SecurityCheck("Edit", bSecurityEnabled, bUserTable);
	sMasterCopyVisible = ew_SecurityCheck("Add", bSecurityEnabled, bUserTable);
	sDetailLink = sViewPageDetailLinkCaption;

	// Set up edit check
	sEditSecChkWrk = ew_SecurityCheck("Edit", bSecurityEnabled, bUserTable);
	sDetailEditSecChkWrk = sEditSecChkWrk;
	if (ew_IsEmpty(sDetailEditSecChkWrk)) sDetailEditSecChkWrk = "TRUE";
	if (ew_IsNotEmpty(sEditSecChkWrk))
		sEditSecChkWrk = " && " + sEditSecChkWrk;

	// Set up add check
	sAddSecChkWrk = ew_SecurityCheck("Add", bSecurityEnabled, bUserTable);
	sDetailCopySecChkWrk = sAddSecChkWrk;
	if (ew_IsEmpty(sDetailCopySecChkWrk)) sDetailCopySecChkWrk = "TRUE";
	if (ew_IsNotEmpty(sAddSecChkWrk))
		sAddSecChkWrk = " && " + sAddSecChkWrk;

	// Set up delete check
	sDeleteSecChkWrk = ew_SecurityCheck("Delete", bSecurityEnabled, bUserTable);
	if (ew_IsNotEmpty(sDeleteSecChkWrk))
		sDeleteSecChkWrk = " && " + sDeleteSecChkWrk;

	// Show Option Link
	if (bTableHasUserIDFld)
		sOptionChkWrk = "$this->ShowOptionLink()";
	else
		sOptionChkWrk = "";

	if (TABLE.TblAdd) {
##-->
		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> ""<!--##=sAddSecChkWrk##-->);
<!--##
	}

	if (TABLE.TblEdit) {
		if (bTableHasUserIDFld)
			sOptionLinkChkWrk = "&& $this->ShowOptionLink('edit')";
		else
			sOptionLinkChkWrk = "";
##-->
		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> ""<!--##=sEditSecChkWrk##--><!--##=sOptionLinkChkWrk##-->);
<!--##
	}

	if (TABLE.TblCopy && TABLE.TblAdd) {
		if (bTableHasUserIDFld)
			sOptionLinkChkWrk = " && $this->ShowOptionLink('add')";
		else
			sOptionLinkChkWrk = "";
##-->
		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> ""<!--##=sAddSecChkWrk##--><!--##=sOptionLinkChkWrk##-->);
<!--##
	}

	if (TABLE.TblDelete) {
		if (bTableHasUserIDFld)
			sOptionLinkChkWrk = " && $this->ShowOptionLink('delete')";
		else
			sOptionLinkChkWrk = "";
		if (bInlineDelete)
			sDeleteConfirm = " onclick=\\\"return ew_ConfirmDelete(this);\\\"";
		else
			sDeleteConfirm = "";
##-->
		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a<!--##=sDeleteConfirm##--> class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> ""<!--##=sDeleteSecChkWrk##--><!--##=sOptionLinkChkWrk##-->);
<!--##
	}
##-->

<!--##
	// Detail links
	if (nDetailTableCount > 0) {
##-->
		$option = &$options["detail"];
		$DetailTableLink = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";
<!--##
		for (var i = 0, len = arDetailTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arDetailTables[i].index];
			OLDTABLE = TABLE; // Save master table
			MASTERTABLE = DB.Tables(TABLE.TblName);
			TABLE = DB.Tables(MasterDetail.DetailTable);
			if (TABLE.TblGen) {
				sDetailTblVar = TABLE.TblVar;
				sDetailPageObj = ew_GetPageObjByCtrlId("grid");
				if (TABLE.TblType == "REPORT")
					sDetailUrl = ew_GetFileNameByCtrlID("report"); // Detail list url
				else
					sDetailUrl = ew_GetFileNameByCtrlID("list"); // Detail list url
				sDetailUrl += "?\" . EW_TABLE_SHOW_MASTER . \"=" + gsTblVar;
				sQry = "";
				for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
					FIELD = MASTERTABLE.Fields(MasterDetail.Rels[j].MasterField);
					sFldParm = FIELD.FldParm;
					sFldObj = "$this->" + sFldParm;
					sQry += "&fk_" + sFldParm + "=";
					cv = sFldObj + "->CurrentValue";
					if (ew_GetFieldType(FIELD.FldType) == 2)
						sQry += "\" . urlencode(ew_UnFormatDateTime(" + cv + ", 0)) . \"";
					else
						sQry += "\" . urlencode(strval(" + cv + ")) . \"";
				} // MasterDetailField
				sDetailUrl += sQry;
				sDetailVisible = ew_SecurityCheck("Detail", bSecurityEnabled, bUserTable);
				if (sDetailVisible == "") sDetailVisible = "TRUE";

				// Set up detail view link visibility
				sDetailViewVisible = ew_SecurityCheck("DetailView", bSecurityEnabled, bUserTable);
				sDetailViewVisible = BuildCond(sMasterViewVisible, "&&", sDetailViewVisible);
				if (ew_IsNotEmpty(sDetailViewVisible)) sDetailViewVisible = " && " + sDetailViewVisible;

				// Set up detail edit link visibility
				sDetailEditVisible = ew_SecurityCheck("DetailEdit", bSecurityEnabled, bUserTable);
				sDetailEditVisible = BuildCond(sMasterEditVisible, "&&", sDetailEditVisible);
				if (ew_IsNotEmpty(sDetailEditVisible)) sDetailEditVisible = " && " + sDetailEditVisible;

				// Set up detail copy link visibility
				sDetailCopyVisible = ew_SecurityCheck("DetailAdd", bSecurityEnabled, bUserTable);
				sDetailCopyVisible = BuildCond(sMasterCopyVisible, "&&", sDetailCopyVisible);
				if (ew_IsNotEmpty(sDetailCopyVisible)) sDetailCopyVisible = " && " + sDetailCopyVisible;
				bDetailCopy = (bDetailAdd && (MASTERTABLE.TblAdd && MASTERTABLE.TblCopy));

				sDetailPrefix = (TABLE.TblType == "REPORT") ? "detailreport" : "detail";
##-->
		// "<!--##=sDetailPrefix##-->_<!--##=sDetailTblVar##-->"
		$item = &$option->Add("<!--##=sDetailPrefix##-->_<!--##=sDetailTblVar##-->");
		$body = <!--##=sDetailLink##--> . $Language->TablePhrase("<!--##=sDetailTblVar##-->", "TblCaption");
	<!--## if (bDetailShowCount && TABLE.TblType != "REPORT") { ##-->
		$body .= str_replace("%c", $this-><!--##=sDetailTblVar##-->_Count, $Language->Phrase("DetailCount"));
	<!--## } ##-->
	<!--## if (TABLE.TblType == "REPORT") { ##-->
		$body = "<a class=\"btn btn-default btn-sm ewRowLink\" href=\"" . ew_HtmlEncode("<!--##=sDetailUrl##-->") . "\">" . $body . "</a>";
	<!--## } else { ##-->
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("<!--##=sDetailUrl##-->") . "\">" . $body . "</a>";
		$links = "";
		<!--## if (MASTERTABLE.TblView && bDetailView) { ##-->
		if ($GLOBALS["<!--##=sDetailPageObj##-->"] && $GLOBALS["<!--##=sDetailPageObj##-->"]->DetailView<!--##=sDetailViewVisible##-->) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle(<!--##=sMasterDetailViewLinkCaption##-->) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=<!--##=sDetailTblVar##-->")) . "\">" . ew_HtmlImageAndText(<!--##=sMasterDetailViewLinkCaption##-->) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "<!--##=sDetailTblVar##-->";
		}
		<!--## } ##-->
		<!--## if (MASTERTABLE.TblEdit && bDetailEdit) { ##-->
		if ($GLOBALS["<!--##=sDetailPageObj##-->"] && $GLOBALS["<!--##=sDetailPageObj##-->"]->DetailEdit<!--##=sDetailEditVisible##-->) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle(<!--##=sMasterDetailEditLinkCaption##-->) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=<!--##=sDetailTblVar##-->")) . "\">" . ew_HtmlImageAndText(<!--##=sMasterDetailEditLinkCaption##-->) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "<!--##=sDetailTblVar##-->";
		}
		<!--## } ##-->
		<!--## if (bDetailCopy) { ##-->
		if ($GLOBALS["<!--##=sDetailPageObj##-->"] && $GLOBALS["<!--##=sDetailPageObj##-->"]->DetailAdd<!--##=sDetailCopyVisible##-->) {
			$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle(<!--##=sMasterDetailCopyLinkCaption##-->) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=<!--##=sDetailTblVar##-->")) . "\">" . ew_HtmlImageAndText(<!--##=sMasterDetailCopyLinkCaption##-->) . "</a></li>";
			if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
			$DetailCopyTblVar .= "<!--##=sDetailTblVar##-->";
		}
		<!--## } ##-->
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
	<!--## } ##-->
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = <!--##=sDetailVisible##-->;
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "<!--##=sDetailTblVar##-->";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;
<!--##
			}
			// Restore Current Table
			TABLE = MASTERTABLE;
		} // MasterDetail
##-->
		// Multiple details
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";
			// Multiple details
			$oListOpt = &$option->Add("details");
			$oListOpt->Body = $body;
		}

		// Set up detail default
		$option = &$options["detail"];
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$option->UseImageAndText = TRUE;
		$ar = explode(",", $DetailTableLink);
		$cnt = count($ar);
		$option->UseDropDownButton = ($cnt > 1);
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
<!--##
	}
##-->

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = <!--##=ew_Val(bUseDropDownForListOptions)##-->;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

	}

<!--##/session##-->
?>


<?php
<!--##session phpevents##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Page_Exporting")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Row_Export")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Page_Exported")##-->
<!--##/session##-->
?>