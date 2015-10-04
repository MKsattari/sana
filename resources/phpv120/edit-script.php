<?php
<!--##session phpmain##-->

	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

<!--## if (bTblCheckConcurrentUpdate) { ##-->
	var $HashValue; // Hash Value
<!--## } ##-->

<!--## if (bDetailEditPaging) { ##-->
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;
<!--## } ##-->

<!--## if (bMultiPage) { ##-->
	var $MultiPages; // Multi pages object
<!--## } ##-->

<!--## if (bShowMultiPageForDetails) { ##-->
	var $DetailPages; // Detail pages object
<!--## } ##-->

<!--##include captcha-script.php/phpcaptcha_var##-->

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

	<!--## if (bUseTabularFormForDesktop) { ##-->
		$this->FormClassName = "ewForm ewEditForm";
		if (ew_IsMobile())
			$this->FormClassName = ew_Concat("form-horizontal", $this->FormClassName, " ");
	<!--## } ##-->

	<!--## if (bDetailEditPaging) { ##-->
		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
	<!--## } ##-->

		// Load key from QueryString
		<!--##
			for (var i = 0, len = arKeyFlds.length; i < len; i++) {
				if (GetFldObj(arKeyFlds[i])) {
		##-->
		if (@$_GET["<!--##=gsFldParm##-->"] <> "") {
			$this-><!--##=gsFldParm##-->->setQueryStringValue($_GET["<!--##=gsFldParm##-->"]);

	<!--## if (bDetailEditPaging) { ##-->
			$this->RecKey["<!--##=gsFldParm##-->"] = $this-><!--##=gsFldParm##-->->QueryStringValue;
		} else {
			$bLoadCurrentRecord = TRUE;
	<!--## } ##-->

		}
		<!--##
				}
			} // KeyField
		##-->
		
	<!--## if (nMasterTableCount > 0) { ##-->
		// Set up master detail parameters
		$this->SetUpMasterParms();
	<!--## } ##-->

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

	<!--## if (bDetailEditPaging) { ##-->

		// Load recordset
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

	<!--## } ##-->

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code

			$this->LoadFormValues(); // Get form values
			
	<!--## if (bTblCheckConcurrentUpdate) { ##-->
			// Overwrite record, reload hash value
			if ($this->CurrentAction == "overwrite") {
				$this->LoadRowHash();
	<!--## if (bTblEditConfirm) { ##-->
				$this->CurrentAction = "F";
	<!--## } else { ##-->
				$this->CurrentAction = "U";
	<!--## } ##-->
			}
	<!--## } ##-->

	<!--## if (bDetailEdit && nDetailTableCount > 0) { ##-->
			// Set up detail parameters
			$this->SetUpDetailParms();
	<!--## } ##-->

		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

	<!--## if (!bDetailEditPaging) { ##-->
		// Check if valid key
		<!--##
			for (var i = 0, len = arKeyFlds.length; i < len; i++) {
				if (GetFldObj(arKeyFlds[i])) {
		##-->
		if ($this-><!--##=gsFldParm##-->->CurrentValue == "")
			$this->Page_Terminate("<!--##=sFnList##-->"); // Invalid key, return to list
		<!--##
				}
			} // KeyField
		##-->
	<!--## } ##-->

		<!--##include captcha-script.php/phpcaptcha_php##-->

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}

		switch ($this->CurrentAction) {
			case "I": // Get a record to display

	<!--## if (bDetailEditPaging) { ##-->

				if (!$bMatchRecord) {
					if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
					$this->Page_Terminate("<!--##=sFnList##-->"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
		<!--## if (bTblCheckConcurrentUpdate) { ##-->
					$this->HashValue = $this->GetRowHash($this->Recordset); // Get hash value for record
		<!--## } ##-->
				}

	<!--## } else { ##-->

				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("<!--##=sFnList##-->"); // No matching record, return to list
				}

	<!--## } ##-->

	<!--## if (bDetailEdit && nDetailTableCount > 0) { ##-->
				// Set up detail parameters
				$this->SetUpDetailParms();
	<!--## } ##-->

				break;

			Case "U": // Update

	<!--## if (ew_IsNotEmpty(TABLE.TblEditReturnPage)) { ##-->
				$sReturnUrl = <!--##=sEditReturnPage##-->;
	<!--## } else { ##-->
		<!--## if (bDetailEdit && nDetailTableCount > 0) { ##-->
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
			<!--## if (bDetailView) { ##-->
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
			<!--## } else { ##-->
					$sReturnUrl = "<!--##=sFnList##-->"; // Master list page
			<!--## } ##-->
				else
					$sReturnUrl = <!--##=sEditReturnPage##-->;
		<!--## } else { ##-->
				$sReturnUrl = <!--##=sEditReturnPage##-->;
		<!--## } ##-->
	<!--## } ##-->
				if (ew_GetPageName($sReturnUrl) == "<!--##=sFnList##-->")
					$sReturnUrl = $this->AddMasterUrl($this->GetListUrl()); // List page, return to list page with correct master key if necessary

				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

	<!--## if (bDetailEdit && nDetailTableCount > 0) { ##-->
					// Set up detail parameters
					$this->SetUpDetailParms();
	<!--## } ##-->

				}
		
		}
		
		// Render the record
		<!--## if (bTblEditConfirm) { ##-->
		if ($this->CurrentAction == "F") { // Confirm page
			$this->RowType = EW_ROWTYPE_VIEW; // Render as View
		} else {
			$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		}
		<!--## } else { ##-->
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		<!--## } ##-->

		$this->ResetAttrs();
		$this->RenderRow();
	}
<!--##/session##-->
?>

<!--##session htmtable##-->
<div class="ewToolbar">
<!--##include phpcommon.php/breadcrumb##-->
<!--##include phpcommon.php/language##-->
<div class="clearfix"></div>
</div>

<!--##include phpcommon.php/header-message##-->
<!--##include phpcommon.php/phpcommon-message##-->

<!--## if (bDetailEditPaging && bTopPageLink) { ##-->
<!--## if (bTblEditConfirm) { ##-->
<?php if ($<!--##=gsTblVar##-->->CurrentAction <> "F") { // Confirm page ?>
<!--## } ##-->
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<!--##include pager.php/pager##-->
<div class="clearfix"></div>
</form>
<!--## if (bTblEditConfirm) { ##-->
<?php } ?>
<!--## } ##-->
<!--## } ##-->

<form name="<!--##=sFormName##-->" id="<!--##=sFormName##-->" class="<?php echo $<!--##=sPageObj##-->->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">

<?php if ($<!--##=sPageObj##-->->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $<!--##=sPageObj##-->->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="<!--##=gsTblVar##-->">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<!--## if (bTblCheckConcurrentUpdate) { ##-->
<input type="hidden" name="k_hash" id="k_hash" value="<?php echo $<!--##=sPageObj##-->->HashValue ?>">
<!--## } ##-->
<!--## if (bTblEditConfirm) { ##-->
<?php if ($<!--##=gsTblVar##-->->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } ?>
<!--## } ##-->

<!--##
	for (var i = 0, len = arMasterTables.length; i < len; i++) {
		var MasterDetail = goAllMasDets[arMasterTables[i].index];
		MASTERTABLE = DB.Tables(MasterDetail.MasterTable);
		if (MASTERTABLE.TblGen) {
			sMasterTblVar = MASTERTABLE.TblVar;
##-->
<?php if ($<!--##=gsTblVar##-->->getCurrentMasterTable() == "<!--##=sMasterTblVar##-->") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="<!--##=sMasterTblVar##-->">
<!--##
			for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
				MASTERFIELD = MASTERTABLE.Fields(MasterDetail.Rels[j].MasterField);
				sMasterFldParm = MASTERFIELD.FldParm;
				DETAILFIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
				sDetailFldParm = DETAILFIELD.FldParm;
##-->
<input type="hidden" name="fk_<!--##=sMasterFldParm##-->" value="<?php echo $<!--##=gsTblVar##-->-><!--##=sDetailFldParm##-->->getSessionValue() ?>">
<!--##
			} // MasterDetailField
##-->
<?php } ?>
<!--##
		}
	} // MasterDetail
##-->

<!--## if (bUserTablePassword) { ##-->
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<!--## } ##-->

<!--## if (bUseCustomTemplate) { ##-->
<!--##~SYSTEMFUNCTIONS.GetCustomTemplate()##-->
<!--## } ##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
<div class="ewDesktop">
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

<!--## if (bUseTabularFormForDesktop) { ##-->
<div<!--##=sPageZeroTableStyle##-->>
<table id="<!--##=sTableId##-->"<!--##=ewCSSDesktopTableClass##-->>
<!--## } else { ##-->

<div<!--##=ewCSSTableClass##--><!--##=sPageZeroTableStyle##-->>

<!--## } ##-->

<!--##
		for (var i = 0, len = arFlds.length; i < len; i++) {
			if (GetFldObj(arFlds[i])) {
				if (glFldPageIndex == 0) {
##-->
<!--##include edit-script.php/fieldlayout##-->
<!--##
				}
			}
		} // Field
##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
</table>
</div>
<!--## } else { ##-->

</div>

<!--## } ##-->

<?php } ?>
<!--##
	}
##-->

<!--##
	// Multi page
	if (bMultiPage) {
##-->
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
<!--##
	}
##-->

<!--##
	for (var pageidx = 1; pageidx <= nPage; pageidx++) {
		var sTableId = ewTableId + ((bMultiPage) ? pageidx : "");
		if (bMultiPage) {
##-->
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
<!--##
		}
		if (!TABLE.TblMultiPageEdit || (TABLE.TblMultiPageEdit && String(pageidx) in dPages)) {
##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
<div>
<table id="<!--##=sTableId##-->"<!--##=ewCSSDesktopTableClass##--><!--##=sPageTableStyle##-->>
<!--## } else { ##-->

<div<!--##=ewCSSTableClass##--><!--##=sPageTableStyle##-->>

<!--## } ##-->

<!--##
			for (var i = 0, len = arFlds.length; i < len; i++) {
				if (GetFldObj(arFlds[i])) {
					if (!TABLE.TblMultiPageEdit || (TABLE.TblMultiPageEdit && glFldPageIndex == pageidx)) {
##-->
<!--##include edit-script.php/fieldlayout##-->
<!--##
					}
				}
			} // Field
##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
</table>
</div>
<!--## } else { ##-->

</div>

<!--## } ##-->

<!--## if (bMultiPage && bUseCustomMultiPageTemplate) { ##-->
<!--##~SYSTEMFUNCTIONS.GetCustomTemplatePage(pageidx)##-->
<!--## } ##-->

<!--##
		}
		if (bMultiPage) {
##-->
<!--## if (bUseAccordionForMultiPage) { ##-->
			</div>
		</div>
	</div>
<!--## } else { ##-->
		</div>
<!--## } ##-->
<!--##
		}
	} // Page
##-->

<!--##
	// Multi page
	if (bMultiPage) {
##-->
<!--## if (bUseAccordionForMultiPage) { ##-->
</div>
<!--## } else { ##-->
	</div>
</div>
<!--## } ##-->
</div>
<!--##
	}
##-->

<!--##
	// Generate hidden fields
	for (var i = 0, len = arFlds.length; i < len; i++) {
		if (GetFldObj(arFlds[i])) {
			if (ew_IsHiddenField(TABLE, goFld, CTRL.CtrlID)) {
##-->
<!--##=SYSTEMFUNCTIONS.FieldEdit()##-->
<!--##
			}
		}
	} // Field
##-->

<!--##
	// Generate primary key fields as hidden if not selected
	for (var i = 0, len = arKeyFlds.length; i < len; i++) {
		if (!InArray(arFlds, arKeyFlds[i])) {
			if (GetFldObj(arKeyFlds[i])) {
##-->
	<!--##=SYSTEMFUNCTIONS.FieldEditPrimaryKey()##-->
<!--##
			}
		}
	} // KeyField
##-->

<!--##include detail-script.php/detailgrid##-->

<!--##include captcha-script.php/phpcaptcha_htm##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
<div class="ewDesktopButton">
<!--## } else { ##-->

<div class="form-group">
	<div class="<!--##=ewBootstrapOffsetClass##-->">

<!--## } ##-->

<!--## if (bTblCheckConcurrentUpdate) { ##-->
<?php if ($<!--##=gsTblVar##-->->UpdateConflict == "U") { // Record already updated by other user ?>
<button class="<!--##=sSubmitButtonClass##-->" name="btnAction" id="btnAction" type="<!--##=sButtonType##-->"<!--##=sOverwriteButtonSubmit##-->><?php echo $Language->Phrase("OverwriteBtn") ?></button>
<button class="<!--##=sCancelButtonClass##-->" name="btnReload" id="btnReload" type="<!--##=sButtonType##-->"<!--##=sReloadButtonSubmit##-->><?php echo $Language->Phrase("ReloadBtn") ?></button>
<?php } else { ?>
<!--## } ##-->
<!--## if (bTblEditConfirm) { ##-->
<?php if ($<!--##=gsTblVar##-->->CurrentAction <> "F") { // Confirm page ?>
<button class="<!--##=sSubmitButtonClass##-->" name="btnAction" id="btnAction" type="<!--##=sButtonType##-->"<!--##=sConfirmButtonSubmit##-->><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="<!--##=sCancelButtonClass##-->" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $<!--##=sPageObj##-->->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="<!--##=sSubmitButtonClass##-->" name="btnAction" id="btnAction" type="<!--##=sButtonType##-->"<!--##=sButtonSubmit##-->><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="<!--##=sCancelButtonClass##-->" name="btnCancel" id="btnCancel" type="<!--##=sButtonType##-->"<!--##=sCancelButtonSubmit##-->><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
<!--## } else { ##-->
<button class="<!--##=sSubmitButtonClass##-->" name="btnAction" id="btnAction" type="<!--##=sButtonType##-->"<!--##=sButtonSubmit##-->><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="<!--##=sCancelButtonClass##-->" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $<!--##=sPageObj##-->->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
<!--## } ##-->
<!--## if (bTblCheckConcurrentUpdate) { ##-->
<?php } ?>
<!--## } ##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
</div>
<!--## } else { ##-->

	</div>
</div>

<!--## } ##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
</div>
<!--## } ##-->

<!--## if (bDetailEditPaging && bBottomPageLink) { ##-->
<!--## if (bTblEditConfirm) { ##-->
<?php if ($<!--##=gsTblVar##-->->CurrentAction <> "F") { // Confirm page ?>
<!--## } ##-->
<!--##include pager.php/pager##-->
<div class="clearfix"></div>
<!--## if (bTblEditConfirm) { ##-->
<?php } ?>
<!--## } ##-->
<!--## } ##-->

</form>

<!--## if (bUseCustomTemplate) { ##-->
<!--##~SYSTEMFUNCTIONS.RenderCustomTemplate()##-->
<!--## } ##-->

<script type="text/javascript">
<!--##=sFormName##-->.Init();
</script>

<!--##include phpcommon.php/footer-message##-->
<!--##/session##-->


<!--##session fieldlayout##-->
<!--##
	if (ew_IsEmpty(goFld.FldAutoUpdateValue)) { // Skip if auto-update
		if (!ew_IsHiddenField(TABLE, goFld, CTRL.CtrlID)) {
			sHeaderSpanId = "elh_" + gsTblVar + "_" + gsFldParm;
			sForLabel = ew_UseForLabel(goFld) ? " for=\"" + gsFldVar + "\"" : "";
##-->
<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=goFld.FldName##--> ?>

<!--## if (bUseTabularFormForDesktop) { ##-->
	<tr id="r_<!--##=gsFldParm##-->">
		<td><span id="<!--##=sHeaderSpanId##-->"><!--##=SYSTEMFUNCTIONS.FieldCaption()##--></span></td>
		<td<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldEdit()##--><?php echo $<!--##=gsFldObj##-->->CustomMsg ?></td>
	</tr>
<!--## } else { ##-->

	<div id="r_<!--##=gsFldParm##-->" class="form-group">
		<label id="<!--##=sHeaderSpanId##-->"<!--##=sForLabel##--> class="<!--##=ewBootstrapLabelClass##-->"><!--##=SYSTEMFUNCTIONS.FieldCaption()##--></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->"><div<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldEdit()##--><?php echo $<!--##=gsFldObj##-->->CustomMsg ?></div></div>
	</div>

<!--## } ##-->

<?php } ?>
<!--##
		}
	}
##-->
<!--##/session##-->


<?php
<!--##session phpevents##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Form_CustomValidate")##-->
<!--##/session##-->
?>