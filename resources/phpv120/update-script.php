<?php
<!--##session phpmain##-->

	var $FormClassName = "form-horizontal ewForm ewUpdateForm";

	var $RecKeys;
	var $Disabled;

	var $Recordset;
	var $UpdateCount = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

	<!--## if (bUseTabularFormForDesktop) { ##-->
		$this->FormClassName = "ewForm ewUpdateForm";
		if (ew_IsMobile())
			$this->FormClassName = ew_Concat("form-horizontal", $this->FormClassName, " ");
	<!--## } ##-->

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		<!--##
			var sDisabled = "";
			if (bTblMultiUpdateConfirm) {
				sDisabled = "<?php echo $" + sPageObj + "->Disabled ?>";
			}
		##-->
		
		// Try to load keys from list form
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys

	<!--##
		if (bTableHasUserIDFld) {
	##-->

		// Check if valid user id
		$sql = $this->GetSQL($this->GetKeyFilter(), "");
		$conn = &$this->Connection();
		if ($this->Recordset = ew_LoadRecordset($sql, $conn)) {
			$res = TRUE;
			while (!$this->Recordset->EOF) {
				$this->LoadRowValues($this->Recordset);
				if (!$this->ShowOptionLink('<!--##=CTRL.CtrlID##-->')) {
					$sUserIdMsg = $Language->Phrase("NoEditPermission");
					$this->setFailureMessage($sUserIdMsg);
					$res = FALSE;
					break;
				}
				$this->Recordset->MoveNext();
			}
			$this->Recordset->Close();
			if (!$res) $this->Page_Terminate("<!--##=sFnList##-->"); // Return to list
		}

	<!--##
		}
	##-->

		if (@$_POST["a_update"] <> "") {
		
			// Get action
			$this->CurrentAction = $_POST["a_update"];

			$this->LoadFormValues(); // Get form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}

		} else {
			$this->LoadMultiUpdateValues(); // Load initial values to form
		}
		
		if (count($this->RecKeys) <= 0)
			$this->Page_Terminate("<!--##=sFnList##-->"); // No records selected, return to list
		
		switch ($this->CurrentAction) {
		
			case "U": // Update
		
				if ($this->UpdateRows()) { // Update Records based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				} else {
					$this->RestoreFormValues(); // Restore form values
				}
		
		}
		
		// Render row
		<!--## if (bTblMultiUpdateConfirm) { ##-->
		if ($this->CurrentAction == "F") { // Confirm page
			$this->RowType = EW_ROWTYPE_VIEW; // Render view
			$this->Disabled = " disabled=\"true\"";
		} else {
			$this->RowType = EW_ROWTYPE_EDIT; // Render edit
			$this->Disabled = "";
		}
		<!--## } else { ##-->
		$this->RowType = EW_ROWTYPE_EDIT; // Render edit
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

<form name="<!--##=sFormName##-->" id="<!--##=sFormName##-->" class="<?php echo $<!--##=sPageObj##-->->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">

<?php if ($<!--##=sPageObj##-->->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $<!--##=sPageObj##-->->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="<!--##=gsTblVar##-->">
<input type="hidden" name="a_update" id="a_update" value="U">
<!--## if (bTblMultiUpdateConfirm) { ##-->
<?php if ($<!--##=gsTblVar##-->->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } ?>
<!--## } ##-->

<!--## if (bUserTablePassword) { ##-->
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<!--## } ##-->

<?php foreach ($<!--##=sPageObj##-->->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>

<!--## if (bUseTabularFormForDesktop) { ##-->
<div class="ewDesktop">
	<table id="<!--##=ewTableId##-->"<!--##=ewCSSDesktopTableClass##-->>
	<thead class="hidden-xs">
	<tr>
		<th><!--##@UpdateValue##--><input type="checkbox" name="u" id="u" onclick="ew_SelectAll(this);"<!--##=sDisabled##--> /></th>
		<th><!--##@FieldName##--></th>
		<th><!--##@NewValue##--></th>
	</tr>
	</thead>
	<tbody>
<!--## } else { ##-->

<div id="<!--##=ewTableId##-->"<!--##=ewCSSTableClass##-->>
	<div class="form-group">
		<label class="<!--##=ewBootstrapLeftColumnClass##-->"><input type="checkbox" name="u" id="u" onclick="ew_SelectAll(this);"<!--##=sDisabled##-->> <!--##@UpdateSelectAll##--></label>
	</div>

<!--## } ##-->

<!--##
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				if (goFld.FldHtmlTag != "HIDDEN" && !goFld.FldIsPrimaryKey && !goFld.FldHtmlTagReadOnly) {
					sHeaderSpanId = "elh_" + gsTblVar + "_" + gsFldParm;
					sForLabel = ew_UseForLabel(goFld) ? " for=\"" + gsFldVar + "\"" : "";
##-->
<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=goFld.FldName##--> ?>

<!--## if (bUseTabularFormForDesktop) { ##-->
	<tr id="r_<!--##=gsFldParm##-->">
		<td class="ewCheckbox"<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldUpdate()##--></td>
		<td<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><span id="<!--##=sHeaderSpanId##-->"><label for="u_<!--##=gsFldParm##-->"><?php echo $<!--##=gsFldObj##-->->FldCaption() ?></label></span></td>
		<td<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldEdit()##--><?php echo $<!--##=gsFldObj##-->->CustomMsg ?></td>
	</tr>
<!--## } else { ##-->

	<div id="r_<!--##=gsFldParm##-->" class="form-group">
		<label<!--##=sForLabel##--> class="<!--##=ewBootstrapLeftColumnClass##--> control-label"><!--##=SYSTEMFUNCTIONS.FieldUpdate()##--> <?php echo $<!--##=gsFldObj##-->->FldCaption() ?></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->"><div<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldEdit()##--><?php echo $<!--##=gsFldObj##-->->CustomMsg ?></div></div>
	</div>

<!--## } ##-->

<?php } ?>
<!--##
				}
			}
		} // Field
##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
	</tbody>
	</table>
	<div class="ewDesktopButton">
<!--## } else { ##-->

	<div class="form-group">
		<div class="<!--##=ewBootstrapOffsetClass##-->">

<!--## } ##-->

<!--## if (bTblMultiUpdateConfirm) { ##-->
<?php if ($<!--##=gsTblVar##-->->CurrentAction <> "F") { // Confirm page ?>
<button class="<!--##=sSubmitButtonClass##-->" name="btnAction" id="btnAction" type="<!--##=sButtonType##-->"<!--##=sConfirmButtonSubmit##-->><?php echo $Language->Phrase("UpdateBtn") ?></button>
<button class="<!--##=sCancelButtonClass##-->" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $<!--##=sPageObj##-->->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="<!--##=sSubmitButtonClass##-->" name="btnAction" id="btnAction" type="<!--##=sButtonType##-->"<!--##=sButtonSubmit##-->><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="<!--##=sCancelButtonClass##-->" name="btnCancel" id="btnCancel" type="<!--##=sButtonType##-->"<!--##=sCancelButtonSubmit##-->><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
<!--## } else { ##-->
<button class="<!--##=sSubmitButtonClass##-->" name="btnAction" id="btnAction" type="<!--##=sButtonType##-->"<!--##=sButtonSubmit##-->><?php echo $Language->Phrase("UpdateBtn") ?></button>
<button class="<!--##=sCancelButtonClass##-->" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $<!--##=sPageObj##-->->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
<!--## } ##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
	</div>
<!--## } else { ##-->

		</div>
	</div>

<!--## } ##-->

</div>

</form>

<script type="text/javascript">
<!--##=sFormName##-->.Init();
</script>

<!--##include phpcommon.php/footer-message##-->
<!--##/session##-->


<?php
<!--##session phpfunction##-->
	// Load initial values to form if field values are identical in all selected records
	function LoadMultiUpdateValues() {

		$this->CurrentFilter = $this->GetKeyFilter();

		// Load recordset
		if ($this->Recordset = $this->LoadRecordset()) {
			$i = 1;
			while (!$this->Recordset->EOF) {
				if ($i == 1) {
		<!--##
				for (var i = 0; i < nFldCount; i++) {
					if (GetFldObj(arFlds[i])) {
						if (goFld.FldHtmlTag != "FILE") {
		##-->
					$this-><!--##=gsFldParm##-->->setDbValue($this->Recordset->fields('<!--##=SQuote(gsFldName)##-->'));
		<!--##
						}
					}
				} // Field
		##-->
				} else {
		<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
					if (goFld.FldHtmlTag != "FILE") {
		##-->
					if (!ew_CompareValue($this-><!--##=gsFldParm##-->->DbValue, $this->Recordset->fields('<!--##=SQuote(gsFldName)##-->')))
						$this-><!--##=gsFldParm##-->->CurrentValue = NULL;
		<!--##
					}
				}
			} // Field
		##-->
				}
				$i++;
				$this->Recordset->MoveNext();
			}
			$this->Recordset->Close();
		}
	}

	// Set up key value
	function SetupKeyValues($key) {

	<!--## if (nKeyCount > 1) { ##-->
		if (count($key) <> <!--##=nKeyCount##-->)
			return FALSE;
	<!--## } ##-->

	<!--##
		for (var i = 0; i < nKeyCount; i++) {
			if (GetFldObj(arKeyFlds[i])) {
				bNumericKey = (ew_GetFieldType(goFld.FldType) == 1);
	##-->
	<!--## if (nKeyCount > 1) { ##-->
		$sKeyFld = $key[<!--##=i##-->];
	<!--## } else { ##-->
		$sKeyFld = $key;
	<!--## } ##-->
	<!--## if (bNumericKey) { ##-->
		if (!is_numeric($sKeyFld))
			return FALSE;
	<!--## } ##-->
		$this-><!--##=gsFldParm##-->->CurrentValue = $sKeyFld;
	<!--##
			}
		} // KeyField
	##-->
		return TRUE;
	}
	
	// Update all selected rows
	function UpdateRows() {
		global $Language;
		
		$conn = &$this->Connection();
		$conn->BeginTrans();
	<!--## if (TABLE.TblAuditTrail) { ##-->
		if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateBegin")); // Batch update begin
	<!--## } ##-->

		// Get old recordset
		$this->CurrentFilter = $this->GetKeyFilter();
		$sSql = $this->SQL();
		$rsold = $conn->Execute($sSql);
		
		// Update all rows
		$sKey = "";
		foreach ($this->RecKeys as $key) {
			if ($this->SetupKeyValues($key)) {
	<!--## if (nKeyCount > 1) { ##-->
				$sThisKey = implode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
	<!--## } else { ##-->
				$sThisKey = $key;
	<!--## } ##-->
				$this->SendEmail = FALSE; // Do not send email on update success
				$this->UpdateCount += 1; // Update record count for records being updated
				$UpdateRows = $this->EditRow(); // Update this row
			} else {
				$UpdateRows = FALSE;
			}

			if (!$UpdateRows)
				break; // Update failed

			if ($sKey <> "") $sKey .= ", ";
			$sKey .= $sThisKey;

		}
		
		// Check if all rows updated
		if ($UpdateRows) {
			$conn->CommitTrans(); // Commit transaction
			
			// Get new recordset
			$rsnew = $conn->Execute($sSql);
			
		<!--## if (TABLE.TblAuditTrail) { ##-->
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateSuccess")); // Batch update success
		<!--## } ##-->
		<!--## if (TABLE.TblSendMailOnEdit) { ##-->
			$sTable = '<!--##=SQuote(TABLE.TblName)##-->';
			$sSubject = $sTable . " " . $Language->Phrase("RecordUpdated");
			$sAction = $Language->Phrase("ActionUpdatedMultiUpdate");
			
			$Email = new cEmail();
			$Email->Load(EW_EMAIL_NOTIFY_TEMPLATE);
			$Email->ReplaceSender(EW_SENDER_EMAIL); // Replace Sender
			$Email->ReplaceRecipient(EW_RECIPIENT_EMAIL); // Replace Recipient
			$Email->ReplaceSubject($sSubject); // Replace Subject
			$Email->ReplaceContent('<!--table-->', $sTable);
			$Email->ReplaceContent('<!--key-->', $sKey);
			$Email->ReplaceContent('<!--action-->', $sAction);

<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Email_Sending")) { ##-->
			$Args = array();
			$Args["rsold"] = $rsold->GetRows();
			$Args["rsnew"] = $rsnew->GetRows();
			$bEmailSent = FALSE;
			if ($this->Email_Sending($Email, $Args))
				$bEmailSent = $Email->Send();
<!--## } else { ##-->
			$bEmailSent = $Email->Send();
<!--## } ##-->

			// Send email failed
			if (!$bEmailSent)
				$this->setFailureMessage($Email->SendErrDescription);

		<!--## } ##-->
		} else {
			$conn->RollbackTrans(); // Rollback transaction
		<!--## if (TABLE.TblAuditTrail) { ##-->
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateRollback")); // Batch update rollback
		<!--## } ##-->
		}
		
		return $UpdateRows;
	
	}
<!--##/session##-->
?>

<?php
<!--##session phpevents##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Form_CustomValidate")##-->
<!--##/session##-->
?>