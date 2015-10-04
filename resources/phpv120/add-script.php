<?php
<!--##session phpmain##-->

	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;

	var $OldRecordset;
	var $CopyRecord;

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
		$this->FormClassName = "ewForm ewAddForm";
		if (ew_IsMobile())
			$this->FormClassName = ew_Concat("form-horizontal", $this->FormClassName, " ");
	<!--## } ##-->

	<!--## if (nMasterTableCount > 0) { ##-->
		// Set up master/detail parameters
		$this->SetUpMasterParms();
	<!--## } ##-->

		// Process form if post back
		if (@$_POST["a_add"] <> "") {

			$this->CurrentAction = $_POST["a_add"]; // Get form action

			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset

			$this->LoadFormValues(); // Load form values

	<!--## if (bDynamicUserLevel && TABLE.TblName == DB.UserLevelTbl) { ##-->
			// Load values for user privileges
			$AllowAdd = @$_POST["x__AllowAdd"];
			if ($AllowAdd == "") $AllowAdd = 0;
			$AllowEdit = @$_POST["x__AllowEdit"];
			if ($AllowEdit == "") $AllowEdit = 0;
			$AllowDelete = @$_POST["x__AllowDelete"];
			if ($AllowDelete == "") $AllowDelete = 0;
			$AllowList = @$_POST["x__AllowList"];
			if ($AllowList == "") $AllowList = 0;
			if (defined("EW_USER_LEVEL_COMPAT")) {
				$this->Priv = intval($AllowAdd) + intval($AllowEdit) +
					intval($AllowDelete) + intval($AllowList);
			} else {
				$AllowView = @$_POST["x__AllowView"];
				if ($AllowView == "") $AllowView = 0;
				$AllowSearch = @$_POST["x__AllowSearch"];
				if ($AllowSearch == "") $AllowSearch = 0;
				$this->Priv = intval($AllowAdd) + intval($AllowEdit) +
					intval($AllowDelete) + intval($AllowList) +
					intval($AllowView) + intval($AllowSearch);
			}
	<!--## } ##-->

		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
	<!--##
		for (var i = 0, len = arKeyFlds.length; i < len; i++) {
			if (GetFldObj(arKeyFlds[i])) {
	##-->
			if (@$_GET["<!--##=gsFldParm##-->"] != "") {
				$this-><!--##=gsFldParm##-->->setQueryStringValue($_GET["<!--##=gsFldParm##-->"]);
				$this->setKey("<!--##=gsFldParm##-->", $this-><!--##=gsFldParm##-->->CurrentValue); // Set up key
			} else {
				$this->setKey("<!--##=gsFldParm##-->", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
	<!--##
			}
		} // KeyField
	##-->

			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}

		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		<!--##include captcha-script.php/phpcaptcha_php##-->

	<!--## if (bDetailAdd && nDetailTableCount > 0) { ##-->
		// Set up detail parameters
		$this->SetUpDetailParms();
	<!--## } ##-->

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {

			case "I": // Blank record, no action required
				break;
				
			case "C": // Copy an existing record

				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("<!--##=sFnList##-->"); // No matching record, return to list
				}

	<!--## if (bDetailAdd && nDetailTableCount > 0) { ##-->
				// Set up detail parameters
				$this->SetUpDetailParms();
	<!--## } ##-->

				break;

			case "A": // Add new record

				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message

	<!--## if (ew_IsNotEmpty(TABLE.TblAddReturnPage)) { ##-->
					$sReturnUrl = <!--##=sAddReturnPage##-->;
	<!--## } else { ##-->
		<!--## if (bDetailAdd && nDetailTableCount > 0) { ##-->
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = <!--##=sAddReturnPage##-->;
		<!--## } else { ##-->
					$sReturnUrl = <!--##=sAddReturnPage##-->;
		<!--## } ##-->
	<!--## } ##-->

					if (ew_GetPageName($sReturnUrl) == "<!--##=sFnList##-->")
						$sReturnUrl = $this->AddMasterUrl($this->GetListUrl()); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "<!--##=sFnView##-->")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

	<!--## if (bDetailAdd && nDetailTableCount > 0) { ##-->
					// Set up detail parameters
					$this->SetUpDetailParms();
	<!--## } ##-->

				}

		}

		// Render row based on row type
	<!--## if (bTblAddConfirm) { ##-->
		if ($this->CurrentAction == "F") { // Confirm page
			$this->RowType = EW_ROWTYPE_VIEW; // Render view type
		} else {
			$this->RowType = EW_ROWTYPE_ADD; // Render add type
		}
	<!--## } else { ##-->
		$this->RowType = EW_ROWTYPE_ADD; // Render add type
	<!--## } ##-->
		
		// Render row
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

<form name="<!--##=sFormName##-->" id="<!--##=sFormName##-->" class="<?php echo $<!--##=sPageObj##-->->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">

<?php if ($<!--##=sPageObj##-->->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $<!--##=sPageObj##-->->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="<!--##=gsTblVar##-->">
<input type="hidden" name="a_add" id="a_add" value="A">
<!--## if (bTblAddConfirm) { ##-->
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
<!--##include add-script.php/fieldlayout##-->
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
		if (!TABLE.TblMultiPageAdd || (TABLE.TblMultiPageAdd && String(pageidx) in dPages)) {
##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
<div>
<table id="<!--##=sTableId##-->"<!--##=ewCSSDesktopTableClass##--><!--##=sPageTableStyle##-->>
<!--## } else { ##-->

<div<!--##=ewCSSTableClass##--><!--##=sPageTableStyle##-->>

<!--## } ##-->

<!--##
		rowcnt = 0;
		for (var i = 0, len = arFlds.length; i < len; i++) {
			if (GetFldObj(arFlds[i])) {
				if (!TABLE.TblMultiPageAdd || (TABLE.TblMultiPageAdd && glFldPageIndex == pageidx)) {
##-->
<!--##include add-script.php/fieldlayout##-->
<!--##
				}
			}
		} // Field

		if (bDynamicUserLevel && TABLE.TblName == DB.UserLevelTbl) {
##-->
	<!-- row for permission values -->

<!--## if (bUseTabularFormForDesktop) { ##-->
	<tr id="rp_permission">
		<td><?php echo ew_HtmlTitle($Language->Phrase("Permission")) ?></td>
		<td>
<label class="checkbox-inline"><input type="checkbox" name="x__AllowAdd" id="Add" value="<?php echo EW_ALLOW_ADD ?>" /><!--##@PermissionAddCopy##--></label>
<label class="checkbox-inline"><input type="checkbox" name="x__AllowDelete" id="Delete" value="<?php echo EW_ALLOW_DELETE ?>" /><!--##@PermissionDelete##--></label>
<label class="checkbox-inline"><input type="checkbox" name="x__AllowEdit" id="Edit" value="<?php echo EW_ALLOW_EDIT ?>" /><!--##@PermissionEdit##--></label>
<?php if (defined("EW_USER_LEVEL_COMPAT")) { ?>
<label class="checkbox-inline"><input type="checkbox" name="x__AllowList" id="List" value="<?php echo EW_ALLOW_LIST ?>" /><!--##@PermissionListSearchView##--></label>
<?php } else { ?>
<label class="checkbox-inline"><input type="checkbox" name="x__AllowList" id="List" value="<?php echo EW_ALLOW_LIST ?>" /><!--##@PermissionList##--></label>
<label class="checkbox-inline"><input type="checkbox" name="x__AllowView" id="View" value="<?php echo EW_ALLOW_VIEW ?>" /><!--##@PermissionView##--></label>
<label class="checkbox-inline"><input type="checkbox" name="x__AllowSearch" id="Search" value="<?php echo EW_ALLOW_SEARCH ?>" /><!--##@PermissionSearch##--></label>
<?php } ?>
		</td>
	</tr>
<!--## } else { ##-->

	<div id="rp_permission" class="form-group">
		<label class="<!--##=ewBootstrapLabelClass##-->"><?php echo ew_HtmlTitle($Language->Phrase("Permission")) ?></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->">
<label class="checkbox-inline"><input type="checkbox" name="x__AllowAdd" id="Add" value="<?php echo EW_ALLOW_ADD ?>"><!--##@PermissionAddCopy##--></label>
<label class="checkbox-inline"><input type="checkbox" name="x__AllowDelete" id="Delete" value="<?php echo EW_ALLOW_DELETE ?>"><!--##@PermissionDelete##--></label>
<label class="checkbox-inline"><input type="checkbox" name="x__AllowEdit" id="Edit" value="<?php echo EW_ALLOW_EDIT ?>"><!--##@PermissionEdit##--></label>
<?php if (defined("EW_USER_LEVEL_COMPAT")) { ?>
<label class="checkbox-inline"><input type="checkbox" name="x__AllowList" id="List" value="<?php echo EW_ALLOW_LIST ?>"><!--##@PermissionListSearchView##--></label>
<?php } else { ?>
<label class="checkbox-inline"><input type="checkbox" name="x__AllowList" id="List" value="<?php echo EW_ALLOW_LIST ?>"><!--##@PermissionList##--></label>
<label class="checkbox-inline"><input type="checkbox" name="x__AllowView" id="View" value="<?php echo EW_ALLOW_VIEW ?>"><!--##@PermissionView##--></label>
<label class="checkbox-inline"><input type="checkbox" name="x__AllowSearch" id="Search" value="<?php echo EW_ALLOW_SEARCH ?>"><!--##@PermissionSearch##--></label>
<?php } ?>
		</div>
	</div>

<!--## } ##-->

<!--##
		}
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
	// Generate detail key fields as hidden if not selected
	for (var i = 0, len = arAllFlds.length; i < len; i++) {
		if (!InArray(arFlds, arAllFlds[i])) {
			if (GetFldObj(arAllFlds[i])) {
				if (ew_IsDetailKeyFld(TABLE, goFld)) {
##-->
	<!--##=SYSTEMFUNCTIONS.FieldEditDetailKey()##-->
<!--##
				}
			}
		}
	} // AllField
##-->

<!--##include detail-script.php/detailgrid##-->

<!--##include captcha-script.php/phpcaptcha_htm##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
<div class="ewDesktopButton">
<!--## } else { ##-->

<div class="form-group">
	<div class="<!--##=ewBootstrapOffsetClass##-->">

<!--## } ##-->
	
<!--## if (bTblAddConfirm) { ##-->
<?php if ($<!--##=gsTblVar##-->->CurrentAction <> "F") { // Confirm page ?>
<button class="<!--##=sSubmitButtonClass##-->" name="btnAction" id="btnAction" type="<!--##=sButtonType##-->"<!--##=sConfirmButtonSubmit##-->><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="<!--##=sCancelButtonClass##-->" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $<!--##=sPageObj##-->->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="<!--##=sSubmitButtonClass##-->" name="btnAction" id="btnAction" type="<!--##=sButtonType##-->"<!--##=sButtonSubmit##-->><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="<!--##=sCancelButtonClass##-->" name="btnCancel" id="btnCancel" type="<!--##=sButtonType##-->"<!--##=sCancelButtonSubmit##-->><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
<!--## } else { ##-->
<button class="<!--##=sSubmitButtonClass##-->" name="btnAction" id="btnAction" type="<!--##=sButtonType##-->"<!--##=sButtonSubmit##-->><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="<!--##=sCancelButtonClass##-->" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $<!--##=sPageObj##-->->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
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
	if (ew_IsEmpty(goFld.FldAutoUpdateValue) && !ew_IsHiddenField(TABLE, goFld, CTRL.CtrlID)) {
		sHeaderSpanId = "elh_" + gsTblVar + "_" + gsFldParm;
		if (goFld.FldAutoIncrement)
			ctl = "";
		else
			ctl = SYSTEMFUNCTIONS.FieldAdd();
		sForLabel = ew_UseForLabel(goFld) ? " for=\"" + gsFldVar + "\"" : "";
##-->
<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=goFld.FldName##--> ?>

<!--## if (bUseTabularFormForDesktop) { ##-->
	<tr id="r_<!--##=gsFldParm##-->">
		<td><span id="<!--##=sHeaderSpanId##-->"><!--##=SYSTEMFUNCTIONS.FieldCaption()##--></span></td>
		<td<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=ctl##--><?php echo $<!--##=gsFldObj##-->->CustomMsg ?></td>
	</tr>
<!--## } else { ##-->

	<div id="r_<!--##=gsFldParm##-->" class="form-group">
		<label id="<!--##=sHeaderSpanId##-->"<!--##=sForLabel##--> class="<!--##=ewBootstrapLabelClass##-->"><!--##=SYSTEMFUNCTIONS.FieldCaption()##--></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->"><div<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=ctl##--><?php echo $<!--##=gsFldObj##-->->CustomMsg ?></div></div>
	</div>

<!--## } ##-->

<?php } ?>
<!--##
	} else {
##-->
	<!--##=SYSTEMFUNCTIONS.FieldAdd()##-->
<!--##
	}
##-->
<!--##/session##-->


<?php
<!--##session phpevents##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Form_CustomValidate")##-->
<!--##/session##-->
?>
