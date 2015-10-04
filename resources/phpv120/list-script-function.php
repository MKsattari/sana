<?php
<!--##session listfunction##-->
	<!--##
		if (ew_IsNotEmpty(sRecPerPageList)) {
	##-->
	// Set up number of records displayed per page
	function SetUpDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = <!--##=iRecPerPage##-->; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session
			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}
	<!--##
		}
	##-->
	
	<!--##
		if (bInlineEdit || bGridEdit || bInlineAdd || bInlineCopy || bGridAdd) {
	##-->
	//  Exit inline mode
	function ClearInlineMode() {
		<!--## if (bInlineEdit) { ##-->
		<!--##
			for (var i = 0; i < nKeyCount; i++) {
				if (GetFldObj(arKeyFlds[i])) {
		##-->
		$this->setKey("<!--##=gsFldParm##-->", ""); // Clear inline edit key
		<!--##
				}
			} // KeyField
		##-->
		<!--## } ##-->
		<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
					if (ew_IsFldFloatFormat(goFld)) { // Check if adSingle/adDouble/adNumeric/adCurrency
		##-->
		$this-><!--##=gsFldParm##-->->FormValue = ""; // Clear form value
		<!--##
					}
				}
			}
		##-->
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}
	<!--##
		}
	##-->

	<!--##
		if (bGridAdd) {
	##-->
	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}
	<!--##
		}
	##-->
	
	<!--##
		if (bGridEdit) {
	##-->
	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}
	<!--##
		}
	##-->
	
	<!--##
		if (bInlineEdit) {
	##-->
	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		<!--## if (bUserTable) { ##-->
		if (!$Security->CanEdit())
			$this->Page_Terminate("<!--##=sFnLogin##-->"); // Go to login page
		<!--## } ##-->
		$bInlineEdit = TRUE;
		<!--##
			for (var i = 0; i < nKeyCount; i++) {
				if (GetFldObj(arKeyFlds[i])) {
		##-->
		if (@$_GET["<!--##=gsFldParm##-->"] <> "") {
			$this-><!--##=gsFldParm##-->->setQueryStringValue($_GET["<!--##=gsFldParm##-->"]);
		} else {
			$bInlineEdit = FALSE;
		}
		<!--##
				}
			} // KeyField
		##-->
		if ($bInlineEdit) {
			if ($this->LoadRow()) {

		<!--##
			if (bTableHasUserIDFld) {
		##-->

				// Check if valid user id
				if (!$this->ShowOptionLink('edit')) {
					$sUserIdMsg = $Language->Phrase("NoEditPermission");
					$this->setFailureMessage($sUserIdMsg);
					$this->ClearInlineMode(); // Clear inline edit mode
					return;
				}

		<!--##
			}
		##-->

		<!--##
			for (var i = 0; i < nKeyCount; i++) {
				if (GetFldObj(arKeyFlds[i])) {
		##-->
				$this->setKey("<!--##=gsFldParm##-->", $this-><!--##=gsFldParm##-->->CurrentValue); // Set up inline edit key
		<!--##
				}
			} // KeyField
		##-->
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}
	
	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1; 

		$this->LoadFormValues(); // Get form values
		
		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {	
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
<!--## if (bTblCheckConcurrentUpdate) { ##-->
			// Overwrite record, just reload hash value
			if ($this->CurrentAction == "overwrite")
				$this->LoadRowHash();
<!--## } ##-->
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
	
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	
	}
	
	// Check Inline Edit key
	function CheckInlineEditKey() {
		//CheckInlineEditKey = True
		<!--##
			for (var i = 0; i < nKeyCount; i++) {
				if (GetFldObj(arKeyFlds[i])) {
		##-->
		if (strval($this->getKey("<!--##=gsFldParm##-->")) <> strval($this-><!--##=gsFldParm##-->->CurrentValue))
			return FALSE;
		<!--##
				}
			} // KeyField
		##-->
		return TRUE;
	}
	<!--##
		}
	##-->
	
	<!--##
		if (bInlineAdd || bInlineCopy) {
	##-->
	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		<!--## if (bUserTable) { ##-->
		if (!$Security->CanAdd())
			$this->Page_Terminate("<!--##=sFnLogin##-->"); // Return to login page
		<!--## } ##-->

		<!--## if (bInlineCopy) { ##-->
		if ($this->CurrentAction == "copy") {
		<!--##
			for (var i = 0; i < nKeyCount; i++) {
				if (GetFldObj(arKeyFlds[i])) {
		##-->
			if (@$_GET["<!--##=gsFldParm##-->"] <> "") {
				$this-><!--##=gsFldParm##-->->setQueryStringValue($_GET["<!--##=gsFldParm##-->"]);
				$this->setKey("<!--##=gsFldParm##-->", $this-><!--##=gsFldParm##-->->CurrentValue); // Set up key
			} else {
				$this->setKey("<!--##=gsFldParm##-->", ""); // Clear key
				$this->CurrentAction = "add";
			}
		<!--##
				}
			} // KeyField
		##-->
		}

		<!--##
			if (bTableHasUserIDFld) {
		##-->

		// Check if valid user id
		if ($this->LoadRow() && !$this->ShowOptionLink('add')) {
			$sUserIdMsg = $Language->Phrase("NoAddPermission");
			$this->setFailureMessage($sUserIdMsg);
			$this->ClearInlineMode(); // Clear inline edit mode
			return;
		}

		<!--##
			}
		##-->

		<!--## } else { ##-->
		$this->CurrentAction = "add";
		<!--## } ##-->
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}
	
	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;

		$this->LoadOldRecord(); // Load old recordset

		$objForm->Index = 0;

		$this->LoadFormValues(); // Get form values
		
		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
	
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
		}
	
	}
	<!--##
		}
	##-->
	
	<!--##
		if (bGridEdit) {
	##-->
	// Perform update to grid
	function GridUpdate() {
		global $Language, $objForm, $gsFormError;

		$bGridUpdate = TRUE;
	
		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		if ($this->CurrentFilter == "")
			$this->CurrentFilter = "0=1";
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}
	
	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Grid_Updating")) { ##-->
		// Call Grid Updating event
		if (!$this->Grid_Updating($rsold)) {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("GridEditCancelled")); // Set grid edit cancelled message
			return FALSE;
		}
	<!--## } ##-->

	<!--## if (CTRL.CtrlID == "list") { ##-->
		// Begin transaction
		$conn->BeginTrans();
	<!--## } ##-->

	<!--## if (TABLE.TblAuditTrail) { ##-->
		if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateBegin")); // Batch update begin
	<!--## } ##-->
		
		$sKey = "";
	
		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows

				$this->LoadFormValues(); // Get form values

				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {
					// No action required

				// Validate form and insert/update/delete record
				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {

						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {

	<!--## if (bTblCheckConcurrentUpdate) { ##-->
								// Overwrite record, just reload hash value
								if ($this->CurrentAction == "gridoverwrite")
									$this->LoadRowHash();
	<!--## } ##-->

								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row

							}
						} // End update
					}

				}
	
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
	
			}
		}
	
		if ($bGridUpdate) {

	<!--## if (CTRL.CtrlID == "list") { ##-->
			$conn->CommitTrans(); // Commit transaction
	<!--## } ##-->
			
			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}
			
	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Grid_Updated")) { ##-->
			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
	<!--## } ##-->

	<!--## if (TABLE.TblAuditTrail) { ##-->
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateSuccess")); // Batch update success
	<!--## } ##-->

	<!--## if (CTRL.CtrlID == "list") { ##-->
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
	<!--## } ##-->

			$this->ClearInlineMode(); // Clear inline edit mode
	
	<!--## if (TABLE.TblSendMailOnEdit) { ##-->
			// Send notify email
			$sTable = '<!--##=SQuote(TABLE.TblName)##-->';
			$sSubject = $sTable . " " . $Language->Phrase("RecordUpdated");
			$sAction = $Language->Phrase("ActionUpdatedGridEdit");
			
			$Email = new cEmail();
			$Email->Load(EW_EMAIL_NOTIFY_TEMPLATE);
			$Email->ReplaceSender(EW_SENDER_EMAIL); // Replace Sender
			$Email->ReplaceRecipient(EW_RECIPIENT_EMAIL); // Replace Recipient
			$Email->ReplaceSubject($sSubject); // Replace Subject
			$Email->ReplaceContent("<!--table-->", $sTable);
			$Email->ReplaceContent("<!--key-->", $sKey);
			$Email->ReplaceContent("<!--action-->", $sAction);

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Email_Sending")) { ##-->
			$Args = array();
			$Args["rsold"] = &$rsold;
			$Args["rsnew"] = &$rsnew;
			$bEmailSent = FALSE;
			if ($this->Email_Sending($Email, $Args))
				$bEmailSent = $Email->Send();
	<!--## } else { ##-->
			$bEmailSent = $Email->Send();
	<!--## } ##-->

			// Set up error message
			if (!$bEmailSent)
				$this->setFailureMessage($Email->SendErrDescription);

	<!--## } ##-->
	
		} else {

	<!--## if (CTRL.CtrlID == "list") { ##-->
			$conn->RollbackTrans(); // Rollback transaction
	<!--## } ##-->

	<!--## if (TABLE.TblAuditTrail) { ##-->
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateRollback")); // Batch update rollback
	<!--## } ##-->
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
		}

		return $bGridUpdate;

	}

<!--##
	}
##-->

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;

		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));

		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();

				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));

		}

		return $sWrkFilter;

	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= <!--##=nKeyCount##-->) {
		<!--##
			for (var i = 0; i < nKeyCount; i++) {
				if (GetFldObj(arKeyFlds[i])) {
		##-->
			$this-><!--##=gsFldParm##-->->setFormValue($arrKeyFlds[<!--##=i##-->]);
		<!--##
					if (ew_GetFieldType(goFld.FldType) == 1) {
		##-->
			if (!is_numeric($this-><!--##=gsFldParm##-->->FormValue))
				return FALSE;
		<!--##
					}
				}
			} // KeyField
		##-->
		}
		return TRUE;
	}
	
<!--##
	if (bGridAdd) {
##-->

	// Perform Grid Add
	function GridInsert() {
		global $Language, $objForm, $gsFormError;

		$rowindex = 1;
		$bGridInsert = FALSE;
		$conn = &$this->Connection();

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Grid_Inserting")) { ##-->
		// Call Grid Inserting event
		if (!$this->Grid_Inserting()) {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("GridAddCancelled")); // Set grid add cancelled message
			}
			return FALSE;
		}
	<!--## } ##-->

	<!--## if (CTRL.CtrlID == "list") { ##-->
		// Begin transaction
		$conn->BeginTrans();
	<!--## } ##-->

		// Init key filter
		$sWrkFilter = "";

		$addcnt = 0;

	<!--## if (TABLE.TblAuditTrail) { ##-->
		if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertBegin")); // Batch insert begin
	<!--## } ##-->

		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;

			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip

	<!--## if (CTRL.CtrlID == "gridcls") { ##-->
			if ($rowaction == "insert") {
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
				$this->LoadOldRecord(); // Load old recordset
			}
	<!--## } ##-->

			$this->LoadFormValues(); // Get form values

			if (!$this->EmptyRow()) {

				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}

				if ($bGridInsert) {

	<!--##
		for (var i = 0; i < nKeyCount; i++) {
			if (GetFldObj(arKeyFlds[i])) {
	##-->
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this-><!--##=gsFldParm##-->->CurrentValue;
	<!--##
			}
		} // KeyField
	##-->

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;

				} else {
					break;
				}
			}

		}

		if ($addcnt == 0) { // No record inserted
	<!--## if (CTRL.CtrlID == "list") { ##-->
			$this->setFailureMessage($Language->Phrase("NoAddRecord"));
			$bGridInsert = FALSE;
	<!--## } else { ##-->
			$this->ClearInlineMode(); // Clear grid add mode and return
			return TRUE;
	<!--## } ##-->
		}

		if ($bGridInsert) {

	<!--## if (CTRL.CtrlID == "list") { ##-->
			$conn->CommitTrans(); // Commit transaction
	<!--## } ##-->

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Grid_Inserted")) { ##-->
			// Call Grid_Inserted event
			$this->Grid_Inserted($rsnew);
	<!--## } ##-->

	<!--## if (TABLE.TblAuditTrail) { ##-->
			if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertSuccess")); // Batch insert success
	<!--## } ##-->

	<!--## if (CTRL.CtrlID == "list") { ##-->
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("InsertSuccess")); // Set up insert success message
	<!--## } ##-->

			$this->ClearInlineMode(); // Clear grid add mode

	<!--## if (TABLE.TblSendMailOnAdd) { ##-->
			// Send notify email
			$sTable = '<!--##=SQuote(TABLE.TblName)##-->';
			$sSubject = $sTable . " " . $Language->Phrase("RecordInserted");
			$sAction = $Language->Phrase("ActionInsertedGridAdd");

			$Email = new cEmail();
			$Email->Load(EW_EMAIL_NOTIFY_TEMPLATE);
			$Email->ReplaceSender(EW_SENDER_EMAIL); // Replace Sender
			$Email->ReplaceRecipient(EW_RECIPIENT_EMAIL); // Replace Recipient
			$Email->ReplaceSubject($sSubject); // Replace Subject
			$Email->ReplaceContent("<!--table-->", $sTable);
			$Email->ReplaceContent("<!--key-->", $sKey);
			$Email->ReplaceContent("<!--action-->", $sAction);

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Email_Sending")) { ##-->
			$Args = array();
			$Args["rsnew"] = &$rsnew;
			$bEmailSent = FALSE;
			if ($this->Email_Sending($Email, $Args))
				$bEmailSent = $Email->Send();
	<!--## } else { ##-->
			$bEmailSent = $Email->Send();
	<!--## } ##-->

			if (!$bEmailSent)
				$this->setFailureMessage($Email->SendErrDescription);

	<!--## } ##-->

		} else {

	<!--## if (CTRL.CtrlID == "list") { ##-->
			$conn->RollbackTrans(); // Rollback transaction
	<!--## } ##-->

	<!--## if (TABLE.TblAuditTrail) { ##-->
			if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertRollback")); // Batch insert rollback
	<!--## } ##-->

			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
		}

		return $bGridInsert;

	}

<!--##
	}
##-->

<!--##
	if (bGridAdd || bGridEdit) {
##-->

	// Check if empty row
	function EmptyRow() {
		global $objForm;
	<!--##
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				sOldFldVar = "o_" + gsFldParm;
				// Skip AutoIncrement fields, AutoUpdate fields and date fields with default value
				if (!goFld.FldAutoIncrement && ew_IsEmpty(goFld.FldAutoUpdateValue)) {
					if (goFld.FldHtmlTag == "FILE") { // P6
	##-->
		if (!ew_Empty($this-><!--##=gsFldParm##-->->Upload->Value))
			return FALSE;
	<!--##
					} else if (SYSTEMFUNCTIONS.IsBoolFld()) {
	##-->
		if ($objForm->HasValue("<!--##=gsFldVar##-->") && $objForm->HasValue("<!--##=sOldFldVar##-->") && ew_ConvertToBool($this-><!--##=gsFldParm##-->->CurrentValue) <> ew_ConvertToBool($this-><!--##=gsFldParm##-->->OldValue))
			return FALSE;
	<!--##
					} else {
	##-->
		if ($objForm->HasValue("<!--##=gsFldVar##-->") && $objForm->HasValue("<!--##=sOldFldVar##-->") && $this-><!--##=gsFldParm##-->->CurrentValue <> $this-><!--##=gsFldParm##-->->OldValue)
			return FALSE;
	<!--##
					}
				}
			}
		} // Field
	##-->
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;
		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;

			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {

				$this->LoadFormValues(); // Get form values

				if ($rowaction == "insert" && $this->EmptyRow()) {
					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Get all form values of the grid
	function GetGridFormValues() {
		global $objForm;
		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;
		$rows = array();

		// Loop through all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;

			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {

				$this->LoadFormValues(); // Get form values

				if ($rowaction == "insert" && $this->EmptyRow()) {
					// Ignore
				} else {
					$rows[] = $this->GetFieldValues("FormValue"); // Return row as array
				}
			}
		}
		return $rows; // Return as array of array
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values

	}

<!--##
	}
##-->
	
<!--##
	if (CTRL.CtrlID == "list" && (bBasicSearch || bExtendedBasicSearch || bAdvancedSearch)) {
##-->

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";

		<!--##
			for (var i = 0; i < nAllFldCount; i++) {
				if (GetFldObj(arAllFlds[i])) {
					if ((goFld.FldSearch || goFld.FldExtendedBasicSearch) &&
						!(goFld.FldHtmlTag == "FILE" && ew_IsBinaryField(goFld))) {
		##-->
		$sFilterList = ew_Concat($sFilterList, $this-><!--##=gsFldParm##-->->AdvancedSearch->ToJSON(), ","); // Field <!--##=gsFldName##-->
		<!--##
					}
				}
			} // AllField
		##-->

		<!--## if (bBasicSearch) { ##-->

		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}

		<!--## } ##-->

		// Return filter list in json
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;

		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		<!--##
			for (var i = 0; i < nAllFldCount; i++) {
				if (GetFldObj(arAllFlds[i])) {
					if ((goFld.FldSearch || goFld.FldExtendedBasicSearch) &&
						!(goFld.FldHtmlTag == "FILE" && ew_IsBinaryField(goFld))) {
		##-->
		// Field <!--##=gsFldName##-->
		$this-><!--##=gsFldParm##-->->AdvancedSearch->SearchValue = @$filter["x_<!--##=gsFldParm##-->"];
		$this-><!--##=gsFldParm##-->->AdvancedSearch->SearchOperator = @$filter["z_<!--##=gsFldParm##-->"];
		$this-><!--##=gsFldParm##-->->AdvancedSearch->SearchCondition = @$filter["v_<!--##=gsFldParm##-->"];
		$this-><!--##=gsFldParm##-->->AdvancedSearch->SearchValue2 = @$filter["y_<!--##=gsFldParm##-->"];
		$this-><!--##=gsFldParm##-->->AdvancedSearch->SearchOperator2 = @$filter["w_<!--##=gsFldParm##-->"];
		$this-><!--##=gsFldParm##-->->AdvancedSearch->Save();
		<!--##
					}
				}
			} // AllField
		##-->

		<!--## if (bBasicSearch) { ##-->

		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);

		<!--## } ##-->

	}

<!--##
	}
##-->

	<!--##
		if (CTRL.CtrlID == "list" && (bExtendedBasicSearch || bAdvancedSearch)) {
	##-->
	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
	
		<!--##
			if (bUserTable) {
		##-->
		if (!$Security->CanSearch()) return "";
		<!--##
			}
		##-->
	
		<!--##
			for (var i = 0; i < nAllFldCount; i++) {
				if (GetFldObj(arAllFlds[i])) {
					if ((goFld.FldSearch || goFld.FldExtendedBasicSearch) &&
						!(goFld.FldHtmlTag == "FILE" && ew_IsBinaryField(goFld))) {
						// Multi-Select field
						if ((goFld.FldHtmlTag == "CHECKBOX" && !SYSTEMFUNCTIONS.IsBoolFld() && ew_GetFieldType(goFld.FldType) == 3) ||
							(goFld.FldHtmlTag == "SELECT" && goFld.FldSelectMultiple))
							bMultiSelect = "TRUE";
						else
							bMultiSelect = "FALSE";
		##-->
		$this->BuildSearchSql($sWhere, $this-><!--##=gsFldParm##-->, $Default, <!--##=bMultiSelect##-->); // <!--##=gsFldName##-->
		<!--##
					}
				}
			} // AllField
		##-->
	
		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
		<!--##
			for (var i = 0; i < nAllFldCount; i++) {
				if (GetFldObj(arAllFlds[i])) {
					if ((goFld.FldSearch || goFld.FldExtendedBasicSearch) &&
						!(goFld.FldHtmlTag == "FILE" && ew_IsBinaryField(goFld))) {
		##-->
			$this-><!--##=gsFldParm##-->->AdvancedSearch->Save(); // <!--##=gsFldName##-->
		<!--##
					}
				}
			} // AllField
		##-->
		}
		
		return $sWhere;
	
	}
	
	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";
		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";

		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;

		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	<!--##
		}
	##-->

	<!--##
		if (CTRL.CtrlID == "list" && bBasicSearch) {
	##-->

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";

		<!--##
			for (var i = 0; i < nAllFldCount; i++) {
				if (GetFldObj(arAllFlds[i])) {
					if (goFld.FldBasicSearch) {
		##-->
		$this->BuildBasicSearchSQL($sWhere, $this-><!--##=gsFldParm##-->, $arKeywords, $type);
		<!--##
					}
				}
			} // AllField
		##-->

		return $sWhere;
	}
	
	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual && $Fld->FldVirtualSearch) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}


	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
	
		<!--##
			if (bUserTable) {
		##-->
		if (!$Security->CanSearch()) return "";
		<!--##
			}
		##-->
		
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();
				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}
				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));
				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}

		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
	
		return $sSearchStr;
	}
	<!--##
		}
	##-->
	
	<!--##
		if (CTRL.CtrlID == "list" && (bExtendedBasicSearch || bAdvancedSearch || bBasicSearch)) {
	##-->

	// Check if search parm exists
	function CheckSearchParms() {

		<!--##
			if (CTRL.CtrlID == "list" && bBasicSearch) {
		##-->
		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		<!--##
			}
		##-->

		<!--##
			if (CTRL.CtrlID == "list" && (bExtendedBasicSearch || bAdvancedSearch)) {
				for (var i = 0; i < nAllFldCount; i++) {
					if (GetFldObj(arAllFlds[i])) {
						if ((goFld.FldSearch || goFld.FldExtendedBasicSearch) &&
							!(goFld.FldHtmlTag == "FILE" && ew_IsBinaryField(goFld))) {
							sFldObj = "this->" + gsFldParm;
		##-->
		if ($<!--##=sFldObj##-->->AdvancedSearch->IssetSession())
			return TRUE;
		<!--##
						}
					}
				} // AllField
			}
		##-->

		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {
		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

	<!--##
		if (CTRL.CtrlID == "list" && bBasicSearch) {
	##-->
		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	<!--##
		}
	##-->
	
	<!--##
		if (CTRL.CtrlID == "list" && (bExtendedBasicSearch || bAdvancedSearch)) {
	##-->
		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	<!--##
		}
	##-->
	
	}
	
	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
	<!--##
		gencnt = 0;
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if ((goFld.FldSearch || goFld.FldExtendedBasicSearch) && (ew_IsNotEmpty(goFld.FldSearchDefault) || ew_IsNotEmpty(goFld.FldSearchDefault2)) &&
					!(goFld.FldHtmlTag == "FILE" && ew_IsBinaryField(goFld))) {
	##-->
		$this-><!--##=gsFldParm##-->->AdvancedSearch->LoadDefault();
	<!--##
					gencnt += 1;
				}
			}
		} // AllField
		if (gencnt > 0) {
	##-->
		return TRUE;
	<!--##
		} else {
	##-->
		return FALSE;
	<!--##
		}
	##-->
	}
	
	<!--##
		}
	##-->
	
	<!--##
		if (CTRL.CtrlID == "list" && bBasicSearch) {
	##-->
	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}
	<!--##
		}
	##-->
	
	<!--##
		if (CTRL.CtrlID == "list" && (bExtendedBasicSearch || bAdvancedSearch)) {
	##-->
	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		<!--##
			for (var i = 0; i < nAllFldCount; i++) {
				if (GetFldObj(arAllFlds[i])) {
					if ((goFld.FldSearch || goFld.FldExtendedBasicSearch) &&
						!(goFld.FldHtmlTag == "FILE" && ew_IsBinaryField(goFld))) {
						sFldObj = "this->" + gsFldParm;
						//sFldOpr = "z_" + gsFldParm;
						//gsFldVar2 = "y_" + gsFldParm;
						//sFldOpr2 = "w_" + gsFldParm;
						//sFldCond = "v_" + gsFldParm;
						//sFldSrchOpr = goFld.FldSrchOpr;
						//sFldSrchOpr2 = goFld.FldSrchOpr2;
						//IsUserSelect = (goFld.FldSrchOpr == "USER SELECT" && ew_GetFieldType(goFld.FldType) != 4);
		##-->
		$<!--##=sFldObj##-->->AdvancedSearch->UnsetSession();
		<!--##
					}
				}
			} // AllField
		##-->
	
	}
	<!--##
		}
	##-->
	
	<!--##
		if (CTRL.CtrlID == "list" && (bExtendedBasicSearch || bAdvancedSearch || bBasicSearch)) {
	##-->
	// Restore all search parameters
	function RestoreSearchParms() {

		$this->RestoreSearch = TRUE;

	<!--## if (CTRL.CtrlID == "list" && bBasicSearch) { ##-->
		// Restore basic search values
		$this->BasicSearch->Load();
	<!--## } ##-->

	<!--## if (CTRL.CtrlID == "list" && (bExtendedBasicSearch || bAdvancedSearch)) { ##-->
		// Restore advanced search values
	<!--##
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if ((goFld.FldSearch || goFld.FldExtendedBasicSearch) &&
					!(goFld.FldHtmlTag == "FILE" && ew_IsBinaryField(goFld))) {
	##-->
		$this-><!--##=gsFldParm##-->->AdvancedSearch->Load();
	<!--##
				}
			}
		} // AllField
	##-->
	<!--## } ##-->

	}

	<!--##
		}
	##-->
	
	// Set up sort parameters
	function SetUpSortOrder() {

		<!--## if (iSortType == 2) { ##-->
		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");
		<!--## } ##-->
	
		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
		<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
					if (!ew_IsBinaryField(goFld)) {
						if (iSortType == 1) { // Single column Sort
		##-->
			$this->UpdateSort($this-><!--##=gsFldParm##-->); // <!--##=gsFldName##-->
		<!--##
						} else if (iSortType == 2) { // Multi Column Sort
		##-->
			$this->UpdateSort($this-><!--##=gsFldParm##-->, $bCtrl); // <!--##=gsFldName##-->
		<!--##
						}
					}
				}
			} // Field
		##-->
			$this->setStartRecordNumber(1); // Reset start position
		}
	}
	
	
	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
		<!--##
			sDefaultOrderBy = SYSTEMFUNCTIONS.OrderByFieldSources(); // List of Order By Field Sources
			if (TABLE.TblType == "CUSTOMVIEW") {
				sOrderByPart = ew_SQLPart(TABLE.TblCustomSQL, "ORDER BY");
				if (ew_IsNotEmpty(sOrderByPart)) {
					if (ew_IsNotEmpty(sDefaultOrderBy)) sDefaultOrderBy += ", ";
					sDefaultOrderBy += sOrderByPart;
				}
			}
			if (ew_IsNotEmpty(sDefaultOrderBy)) {
				arOrderFlds = sDefaultOrderBy.split(",");
				for (i = 0; i < arOrderFlds.length; i++) {
					sOrderFld = arOrderFlds[i].trim();
					if (sOrderFld.substr(sOrderFld.length-4).toUpperCase() == " ASC") {
						sSort = "ASC";
						sOrderFld = sOrderFld.substr(0,sOrderFld.length-3).trim();
					} else if (sOrderFld.substr(sOrderFld.length-5).toUpperCase() == " DESC") {
						sSort = "DESC";
						sOrderFld = sOrderFld.substr(0,sOrderFld.length-4).trim();
					} else {
						sSort = "ASC";
					}
					for (j = 0; j < nFldCount; j++) {
						if (GetFldObj(arFlds[j])) {
							if (!ew_IsBinaryField(goFld) && gsFld == sOrderFld) {
		##-->
				$this-><!--##=gsFldParm##-->->setSort("<!--##=sSort##-->");
		<!--##
								break;
							}
						}
					} // Field
				} // OrderField
			}
		##-->
			}
		}
	}
	
	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

	<!--##
		if (CTRL.CtrlID == "list" && (bExtendedBasicSearch || bAdvancedSearch || bBasicSearch)) {
	##-->
			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();
	<!--##
		}
	##-->
	
	<!--##
		if (nMasterTableCount > 0) {
	##-->
			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
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
	<!--##
		}
	##-->
	
			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
<!--## if (bTableVirtualLookup) { ##-->
				$this->setSessionOrderByList($sOrderBy);
<!--## } ##-->
	<!--##
		if (iSortType > 0) {
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
					if (!ew_IsBinaryField(goFld)) {
	##-->
				$this-><!--##=gsFldParm##-->->setSort("");
	<!--##
					}
				}
			} // Field
		}
	##-->
			}
	
			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

		}

	}

<!--##
	// Set up view link visibility
	sViewVisible = ew_SecurityCheck("View", bSecurityEnabled, bUserTable);
	if (sViewVisible == "") sViewVisible = "TRUE";

	// Set up edit link visibility
	sEditVisible = ew_SecurityCheck("Edit", bSecurityEnabled, bUserTable);
	if (sEditVisible == "") sEditVisible = "TRUE";

	// Set up copy link visibility
	sCopyVisible = ew_SecurityCheck("Add", bSecurityEnabled, bUserTable);
	if (sCopyVisible == "") sCopyVisible = "TRUE";

	// Set up delete link visibility
	sDeleteVisible = ew_SecurityCheck("Delete", bSecurityEnabled, bUserTable);
	if (sDeleteVisible == "") sDeleteVisible = "TRUE";
	if (bInlineDelete) {
		sDeleteConfirm = " onclick=\"return ew_ConfirmDelete(this);\"";
	} else {
		sDeleteConfirm = "";
	}

	// Set up user permission visibility
	sUserPermissionVisible = "$Security->IsAdmin()";

	// Set up multi checkbox visibility
	var sCheckboxVisible = "FALSE";
	if (bExportSelectedOnly) {
		sCheckboxVisible = "TRUE";
	} else {
		if (TABLE.TblDelete && bMultiDelete)
			sCheckboxVisible = (bSecurityEnabled) ? ew_SecurityCheck("Delete", bSecurityEnabled, bUserTable) : "TRUE";
		if (bMultiUpdate) {
			sWrkVisible = (bSecurityEnabled) ? ew_SecurityCheck("Edit", bSecurityEnabled, bUserTable) : "TRUE";
			sCheckboxVisible = (sCheckboxVisible == "FALSE") ? sWrkVisible : BuildCond(sCheckboxVisible, "||", sWrkVisible);
		}
	}
	if (sCheckboxVisible == "") sCheckboxVisible = "TRUE";
##-->

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

<!--## if (CTRL.CtrlID == "gridcls" || (CTRL.CtrlID == "list" && (bGridAdd || bGridEdit))) { ##-->

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = <!--##=ew_Val(bLinkOnLeft)##-->;
			$item->Visible = FALSE; // Default hidden
		}

<!--## } ##-->

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = <!--##=ew_Val(bLinkOnLeft)##-->;
		$item->Visible = FALSE;

<!--## if (CTRL.CtrlID == "list") { ##-->

<!--## if (TABLE.TblView) { ##-->
		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = <!--##=sViewVisible##-->;
		$item->OnLeft = <!--##=ew_Val(bLinkOnLeft)##-->;
<!--## } ##-->

<!--## if (TABLE.TblEdit || bInlineEdit) { ##-->
		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = <!--##=sEditVisible##-->;
		$item->OnLeft = <!--##=ew_Val(bLinkOnLeft)##-->;
<!--## } ##-->

<!--## if ((TABLE.TblCopy && TABLE.TblAdd) || bInlineCopy || bInlineAdd) { ##-->
<!--##
	if (!TABLE.TblCopy && !bInlineCopy) {
		if (sCopyVisible == "TRUE")
			sCopyVisible = "";
		else
			sCopyVisible = sCopyVisible + " && ";
		sCopyVisible += "($this->CurrentAction == \"add\")";
	}
##-->
		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = <!--##=sCopyVisible##-->;
		$item->OnLeft = <!--##=ew_Val(bLinkOnLeft)##-->;
<!--## } ##-->

<!--## if (TABLE.TblDelete && !bMultiDelete) { ##-->
		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = <!--##=sDeleteVisible##-->;
		$item->OnLeft = <!--##=ew_Val(bLinkOnLeft)##-->;
<!--## } ##-->

<!--##
	// Detail links
	WRKTABLE = TABLE; // Save table
	if (nDetailTableCount > 0) {
		for (var i = 0, len = arDetailTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arDetailTables[i].index];
			TABLE = DB.Tables(MasterDetail.DetailTable);
			if (TABLE.TblGen) {
				sDetailTblVar = TABLE.TblVar;
				sDetailPageObj = ew_GetPageObjByCtrlId("grid");
				sDetailTblName = TABLE.TblName;
				sDetailVisible = ew_SecurityCheck("Detail", bSecurityEnabled, bUserTable);
				if (sDetailVisible == "") sDetailVisible = "TRUE";
				sDetailPrefix = (TABLE.TblType == "REPORT") ? "detailreport" : "detail";
##-->
		// "<!--##=sDetailPrefix##-->_<!--##=sDetailTblVar##-->"
		$item = &$this->ListOptions->Add("<!--##=sDetailPrefix##-->_<!--##=sDetailTblVar##-->");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = <!--##=sDetailVisible##--> && !$this->ShowMultipleDetails;
		$item->OnLeft = <!--##=ew_Val(bLinkOnLeft)##-->;
		$item->ShowInButtonGroup = FALSE;
	<!--## if (TABLE.TblType != "REPORT") { ##-->
		if (!isset($GLOBALS["<!--##=sDetailPageObj##-->"])) $GLOBALS["<!--##=sDetailPageObj##-->"] = new c<!--##=sDetailPageObj##-->;
	<!--## } ##-->
<!--##
			}
		} // MasterDetail
##-->
		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssStyle = "white-space: nowrap;";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = <!--##=ew_Val(bLinkOnLeft)##-->;
			$item->ShowInButtonGroup = FALSE;
		}

		// Set up detail pages
		$pages = new cSubPages();
	<!--##
		for (var i = 0, len = arDetailTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arDetailTables[i].index];
			DETAILTABLE = DB.Tables(MasterDetail.DetailTable);
			if (DETAILTABLE.TblGen && DETAILTABLE.TblType != "REPORT") {
				var sDetailTblVar = DETAILTABLE.TblVar;
	##-->
		$pages->Add("<!--##=ew_Quote(sDetailTblVar)##-->");
	<!--##
			}
		}
	##-->
		$this->DetailPages = $pages;

<!--##
	}
	TABLE = WRKTABLE; // Resume table
##-->

<!--## if (bDynamicUserLevel && TABLE.TblName == DB.UserLevelTbl) { ##-->
		// "userpermission"
		$item = &$this->ListOptions->Add("userpermission");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = <!--##=sUserPermissionVisible##-->;
		$item->OnLeft = <!--##=ew_Val(bLinkOnLeft)##-->;
		$item->ButtonGroupName = "userpermission"; // Use own group
<!--## } ##-->

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = <!--##=ew_Val(bLinkOnLeft)##-->;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = <!--##=sCheckboxVisible##-->;
		$item->OnLeft = <!--##=ew_Val(bLinkOnLeft)##-->;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
	<!--## if (bLinkOnLeft) { ##-->
		$item->MoveTo(0);
	<!--## } ##-->
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

<!--## } ##-->

<!--## if (TABLE.TblDisplayRowCount) { ##-->
		// "sequence"
		$item = &$this->ListOptions->Add("sequence");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = TRUE; // Always on left
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;
<!--## } ##-->

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = <!--##=ew_Val(bUseDropDownForListOptions)##-->;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
<!--## if (CTRL.CtrlID == "list") { ##-->
		$this->ListOptions->UseButtonGroup = <!--##=ew_Val(bUseButtonsForLinks)##-->;
<!--## } else { ##-->
		$this->ListOptions->UseButtonGroup = FALSE;
<!--## } ##-->
<!--## if (PROJ.GetV("UseDropdownForMobile")) { ##-->
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
<!--## } ##-->
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

<!--## if (CTRL.CtrlID == "list" && SYSTEMFUNCTIONS.ServerScriptExist("Table","ListOptions_Load")) { ##-->
		// Call ListOptions_Load event
		$this->ListOptions_Load();
<!--## } ##-->

<!--## if (CTRL.CtrlID == "list") { ##-->
		$this->SetupListOptionsExt();
<!--## } ##-->

		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();

	}

<!--##
	// Set up view link visibility
	sViewVisible = ew_SecurityCheck("View", bSecurityEnabled, bUserTable);
	if (bTableHasUserIDFld) {
		sViewVisible = BuildCond(sViewVisible, "&&", "$this->ShowOptionLink('view')");
	}
	sMasterViewVisible = sViewVisible;
	if (sViewVisible == "") sViewVisible = "TRUE";

	// Set up edit link visibility
	sEditVisible = ew_SecurityCheck("Edit", bSecurityEnabled, bUserTable);
	if (bTableHasUserIDFld) {
		sEditVisible = BuildCond(sEditVisible, "&&", "$this->ShowOptionLink('edit')");
	}
	sMasterEditVisible = sEditVisible;
	//sEditVisible = BuildCond(sEditVisible, "&&", "$oListOpt->Visible");
	if (sEditVisible == "") sEditVisible = "TRUE";

	// Set up copy link visibility
	sCopyVisible = ew_SecurityCheck("Add", bSecurityEnabled, bUserTable);
	if (bTableHasUserIDFld) {
		sCopyVisible = BuildCond(sCopyVisible, "&&", "$this->ShowOptionLink('add')");
	}
	sMasterCopyVisible = sCopyVisible;
	//sCopyVisible = BuildCond(sCopyVisible, "&&", "$oListOpt->Visible");
	if (sCopyVisible == "") sCopyVisible = "TRUE";

	// Set up delete link visibility
	sDeleteVisible = ew_SecurityCheck("Delete", bSecurityEnabled, bUserTable);
	if (bTableHasUserIDFld) {
		sDeleteVisible = BuildCond(sDeleteVisible, "&&", "$this->ShowOptionLink('delete')");
	}
	//sDeleteVisible = BuildCond(sDeleteVisible, "&&", "$oListOpt->Visible");
	if (sDeleteVisible == "") sDeleteVisible = "TRUE";

	// Set up user permission visibility
	sUserPermissionVisible = "$Security->IsAdmin()";

	// Set up multi checkbox visibility
	if (bExportSelectedOnly) {
		sCheckboxVisible = "";
	} else {
		sCheckboxVisible = "";
		if (TABLE.TblDelete && bMultiDelete) {
			sWrkVisible = ew_SecurityCheck("Delete", bSecurityEnabled, bUserTable);
			if (bTableHasUserIDFld)
				sWrkVisible = BuildCond(sWrkVisible, "&&", "$this->ShowOptionLink('delete')");
			sCheckboxVisible = BuildCond(sCheckboxVisible, "||", sWrkVisible);
		}
		if (bMultiUpdate) {
			sWrkVisible = ew_SecurityCheck("Edit", bSecurityEnabled, bUserTable);
			if (bTableHasUserIDFld)
                sWrkVisible = BuildCond(sWrkVisible, "&&", "$this->ShowOptionLink('edit')");
            sCheckboxVisible = BuildCond(sCheckboxVisible, "||", sWrkVisible);
        }
	}
	//sCheckboxVisible = BuildCond(sCheckboxVisible, "&&", "$oListOpt->Visible");
	if (sCheckboxVisible == "") sCheckboxVisible = "FALSE";
##-->
	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;

		$this->ListOptions->LoadDefault();

<!--## if ((CTRL.CtrlID == "gridcls") || (CTRL.CtrlID == "list" && (bInlineEdit || bGridEdit || bInlineAdd || bInlineCopy || bGridAdd))) { ##-->

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";

	<!--## if (CTRL.CtrlID == "gridcls") { ##-->
			if ($objForm->HasValue($this->FormOldKeyName))
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
			if ($this->RowOldKey <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $OldKeyName . "\" id=\"" . $OldKeyName . "\" value=\"" . ew_HtmlEncode($this->RowOldKey) . "\">";
	<!--## } ##-->

			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";

		}

<!--## } ##-->

<!--## if (CTRL.CtrlID == "gridcls" || (CTRL.CtrlID == "list" && (bGridAdd || bGridEdit))) { ##-->

		// "delete"
		if ($this->AllowAddDeleteRow) {
	<!--## if (CTRL.CtrlID == "list") { ##-->
			if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
	<!--## } else { ##-->
			if ($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") {
	<!--## } ##-->
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
	<!--##
		if (!TABLE.TblDelete || bUserTable) {
			if (!TABLE.TblDelete)
				sCond = "";
			else
				sCond = "!$Security->CanDelete() && ";
	##-->
				if (<!--##=sCond##-->is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle(<!--##=sDeleteLinkCaption##-->) . "\" data-caption=\"" . ew_HtmlTitle(<!--##=sDeleteLinkCaption##-->) . "\" onclick=\"return ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . <!--##=sDeleteLinkCaption##--> . "</a>";
				}
	<!--## } else { ##-->
				$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle(<!--##=sDeleteLinkCaption##-->) . "\" data-caption=\"" . ew_HtmlTitle(<!--##=sDeleteLinkCaption##-->) . "\" onclick=\"return ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . <!--##=sDeleteLinkCaption##--> . "</a>";
	<!--## } ##-->
			}
		}

<!--## } ##-->

<!--## if (TABLE.TblDisplayRowCount) { ##-->
		// "sequence"
		$oListOpt = &$this->ListOptions->Items["sequence"];
		$oListOpt->Body = ew_FormatSeqNo($this->RecCnt);
<!--## } ##-->

<!--## if (CTRL.CtrlID == "list") { ##-->

<!--## if (bInlineAdd || bInlineCopy) { ##-->
		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink ewInlineInsert\" title=\"" . ew_HtmlTitle(<!--##=sInsertLinkCaption##-->) . "\" data-caption=\"" . ew_HtmlTitle(<!--##=sInsertLinkCaption##-->) . "\" href=\"\" onclick=\"<!--##=sListFormSubmit##-->\">" . <!--##=sInsertLinkCaption##--> . "</a>&nbsp;" .
				"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle(<!--##=sCancelLinkCaption##-->) . "\" data-caption=\"" . ew_HtmlTitle(<!--##=sCancelLinkCaption##-->) . "\" href=\"" . $cancelurl . "\">" . <!--##=sCancelLinkCaption##--> . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}
<!--## } ##-->

<!--## if (bInlineEdit) { ##-->
		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
	<!--## if (bTblCheckConcurrentUpdate) { ##-->
			if ($this->UpdateConflict == "U") {
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink ewInlineReload\" title=\"" . ew_HtmlTitle(<!--##=sReloadLinkCaption##-->) . "\" data-caption=\"" . ew_HtmlTitle(<!--##=sReloadLinkCaption##-->) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" .
					<!--##=sReloadLinkCaption##--> . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineOverwrite\" title=\"" . ew_HtmlTitle(<!--##=sOverwriteLinkCaption##-->) . "\" data-caption=\"" . ew_HtmlTitle(<!--##=sOverwriteLinkCaption##-->) . "\" href=\"\" onclick=\"<!--##=sListFormInlineSubmit##-->\">" . <!--##=sOverwriteLinkCaption##--> . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle(<!--##=sConflictCancelLinkCaption##-->) . "\" data-caption=\"" . ew_HtmlTitle(<!--##=sConflictCancelLinkCaption##-->) . "\" href=\"" . $cancelurl . "\">" . <!--##=sConflictCancelLinkCaption##--> . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"overwrite\"></div>";
			} else {
	<!--## } ##-->
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink ewInlineUpdate\" title=\"" . ew_HtmlTitle(<!--##=sUpdateLinkCaption##-->) . "\" data-caption=\"" . ew_HtmlTitle(<!--##=sUpdateLinkCaption##-->) . "\" href=\"\" onclick=\"<!--##=sListFormInlineSubmit##-->\">" . <!--##=sUpdateLinkCaption##--> . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle(<!--##=sCancelLinkCaption##-->) . "\" data-caption=\"" . ew_HtmlTitle(<!--##=sCancelLinkCaption##-->) . "\" href=\"" . $cancelurl . "\">" . <!--##=sCancelLinkCaption##--> . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
	<!--## if (bTblCheckConcurrentUpdate) { ##-->
			}
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_hash\" id=\"k" . $this->RowIndex . "_hash\" value=\"" . $this->HashValue . "\">";
	<!--## } ##-->
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode(<!--##=sMultiSelectKey##-->) . "\">";
			return;
		}
<!--## } ##-->

<!--## if (TABLE.TblView) { ##-->
		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if (<!--##=sViewVisible##-->)
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle(<!--##=sViewLinkCaption##-->) . "\" data-caption=\"" . ew_HtmlTitle(<!--##=sViewLinkCaption##-->) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . <!--##=sViewLinkCaption##--> . "</a>";
		else
			$oListOpt->Body = "";
<!--## } ##-->

<!--## if (TABLE.TblEdit || bInlineEdit) { ##-->
		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if (<!--##=sEditVisible##-->) {
	<!--## if (TABLE.TblEdit) { ##-->
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle(<!--##=sEditLinkCaption##-->) . "\" data-caption=\"" . ew_HtmlTitle(<!--##=sEditLinkCaption##-->) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . <!--##=sEditLinkCaption##--> . "</a>";
	<!--## } ##-->
	<!--## if (bInlineEdit) { ##-->
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" title=\"" . ew_HtmlTitle(<!--##=sInlineEditLinkCaption##-->) . "\" data-caption=\"" . ew_HtmlTitle(<!--##=sInlineEditLinkCaption##-->) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" . <!--##=sInlineEditLinkCaption##--> . "</a>";
	<!--## } ##-->
		} else {
			$oListOpt->Body = "";
		}
<!--## } ##-->

<!--## if ((TABLE.TblCopy && TABLE.TblAdd) || (bInlineCopy && bInlineAdd)) { ##-->
		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (<!--##=sCopyVisible##-->) {
	<!--## if (TABLE.TblCopy && TABLE.TblAdd) { ##-->
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . ew_HtmlTitle(<!--##=sCopyLinkCaption##-->) . "\" data-caption=\"" . ew_HtmlTitle(<!--##=sCopyLinkCaption##-->) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . <!--##=sCopyLinkCaption##--> . "</a>";
	<!--## } ##-->
	<!--## if (bInlineCopy && bInlineAdd) { ##-->
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineCopy\" title=\"" . ew_HtmlTitle(<!--##=sInlineCopyLinkCaption##-->) . "\" data-caption=\"" . ew_HtmlTitle(<!--##=sInlineCopyLinkCaption##-->) . "\" href=\"" . ew_HtmlEncode($this->InlineCopyUrl) . "\">" . <!--##=sInlineCopyLinkCaption##--> . "</a>";
	<!--## } ##-->
		} else {
			$oListOpt->Body = "";
		}
<!--## } ##-->

<!--## if (TABLE.TblDelete && !bMultiDelete) { ##-->
		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if (<!--##=sDeleteVisible##-->)
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "<!--##=ew_Quote(sDeleteConfirm)##-->" . " title=\"" . ew_HtmlTitle(<!--##=sDeleteLinkCaption##-->) . "\" data-caption=\"" . ew_HtmlTitle(<!--##=sDeleteLinkCaption##-->) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . <!--##=sDeleteLinkCaption##--> . "</a>";
		else
			$oListOpt->Body = "";
<!--## } ##-->

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt) {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

<!--##
	// Detail links
	if (nDetailTableCount > 0) {
##-->
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
				sDetailVisible = ew_SecurityCheck("Detail", bSecurityEnabled, bUserTable);
				if (bTableHasUserIDFld) {
					if (ew_IsNotEmpty(sDetailVisible)) sDetailVisible += " && ";
					sDetailVisible += "$this->ShowOptionLink()";
				}
				if (sDetailVisible == "") sDetailVisible = "TRUE";
				if (TABLE.TblType == "REPORT")
					sDetailUrl = ew_GetFileNameByCtrlID("report"); // Detail report url
				else
					sDetailUrl = ew_GetFileNameByCtrlID("list"); // Detail list url
				sDetailUrl += "?\" . EW_TABLE_SHOW_MASTER . \"=" + gsTblVar;
				sQry = "";
				for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
					FIELD = MASTERTABLE.Fields(MasterDetail.Rels[j].MasterField);
					sFldParm = FIELD.FldParm;
					sFldObj = "this->" + sFldParm;
					sQry += "&fk_" + sFldParm + "=";
					cv = sFldObj + "->CurrentValue";
					if (ew_GetFieldType(FIELD.FldType) == 2)
						sQry += "\" . urlencode(ew_UnFormatDateTime($" + cv + ", 0)) . \"";
					else
						sQry += "\" . urlencode(strval($" + cv + ")) . \"";
				} // MasterDetailField
				sDetailUrl += sQry;
				if (CTRL.CtrlID == "view")
					sDetailLink = sViewPageDetailLinkCaption;
				else
					sDetailLink = sDetailLinkCaption;

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
		$oListOpt = &$this->ListOptions->Items["<!--##=sDetailPrefix##-->_<!--##=sDetailTblVar##-->"];
		if (<!--##=sDetailVisible##-->) {
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
			if ($GLOBALS["<!--##=sDetailPageObj##-->"]->DetailView<!--##=sDetailViewVisible##-->) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle(<!--##=sMasterDetailViewLinkCaption##-->) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=<!--##=sDetailTblVar##-->")) . "\">" . ew_HtmlImageAndText(<!--##=sMasterDetailViewLinkCaption##-->) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "<!--##=sDetailTblVar##-->";
			}
		<!--## } ##-->
		<!--## if (MASTERTABLE.TblEdit && bDetailEdit) { ##-->
			if ($GLOBALS["<!--##=sDetailPageObj##-->"]->DetailEdit<!--##=sDetailEditVisible##-->) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle(<!--##=sMasterDetailEditLinkCaption##-->) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=<!--##=sDetailTblVar##-->")) . "\">" . ew_HtmlImageAndText(<!--##=sMasterDetailEditLinkCaption##-->) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "<!--##=sDetailTblVar##-->";
			}
		<!--## } ##-->
		<!--## if (bDetailCopy) { ##-->
			if ($GLOBALS["<!--##=sDetailPageObj##-->"]->DetailAdd<!--##=sDetailCopyVisible##-->) {
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
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
<!--##
			}
			TABLE = OLDTABLE; // Restore table
		} // MasterDetail
##-->
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
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}
<!--##
	}
##-->

<!--##
	if (bDynamicUserLevel && TABLE.TblName == DB.UserLevelTbl) {
		if (GetFldObj(DB.UserLevelIdFld))
			sUserLvlIDFld = "this->" + gsFldParm + "->CurrentValue";
##-->
		// "userpermission"
		$oListOpt = &$this->ListOptions->Items["userpermission"];
		if ($<!--##=sUserLvlIDFld##--> < 0 && $<!--##=sUserLvlIDFld##--> <> -2) {
			$oListOpt->Body = "-";
		} else {
			$oListOpt->Body = "<a class=\"ewRowLink ewUserPermission\" title=\"" . ew_HtmlTitle($Language->Phrase("Permission")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("Permission")) . "\" href=\"" . ew_HtmlEncode(<!--##=sUrlUserPrivQuoted##-->) . "\">" . $Language->Phrase("Permission") . "</a>";
		}
<!--##
	}
##-->

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
<!--##
	if (ew_IsNotEmpty(sMultiSelectKey)) {
		if (ew_IsNotEmpty(sMouseClickMultiEvent))
			sMultiClick = " onclick='" + sMouseClickMultiEvent + "'";
		else
			sMultiClick = "";
##-->
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode(<!--##=sMultiSelectKey##-->) . "\"<!--##=sMultiClick##-->>";
<!--##
	}
##-->

<!--## } ##-->

<!--## if (bGridEdit) { ##-->
	<!--## if (CTRL.CtrlID == "gridcls") { ##-->
		if ($this->CurrentMode == "edit" && is_numeric($this->RowIndex)) {
	<!--## } else { ##-->
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
	<!--## } ##-->
	<!--## if (ew_IsNotEmpty(sMultiSelectKey)) { ##-->
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . <!--##=sMultiSelectKey##--> . "\">";
	<!--## } ##-->
	<!--## if (bTblCheckConcurrentUpdate) { ##-->
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_hash\" id=\"k" . $this->RowIndex . "_hash\" value=\"" . $this->HashValue . "\">";
	<!--## } ##-->
		}
<!--## } ##-->

		$this->RenderListOptionsExt();

<!--## if (CTRL.CtrlID == "list" && SYSTEMFUNCTIONS.ServerScriptExist("Table","ListOptions_Rendered")) { ##-->
		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
<!--## } ##-->

	}

<!--## if (CTRL.CtrlID == "gridcls") { ##-->
	// Set record key
	function SetRecordKey(&$key, $rs) {

		$key = "";
	<!--##
		for (var i = 0; i < nKeyCount; i++) {
			if (GetFldObj(arKeyFlds[i])) {
	##-->
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs->fields('<!--##=SQuote(gsFldName)##-->');
	<!--##
			}
		} // KeyField
	##-->

	}
<!--## } ##-->

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;

<!--## if (CTRL.CtrlID == "gridcls") { ##-->

		$option = &$this->OtherOptions["addedit"];
		$option->UseDropDownButton = FALSE;
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$option->UseButtonGroup = TRUE;
		$option->ButtonClass = "btn-sm"; // Class for button group
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

<!--## } else if (CTRL.CtrlID == "list") { ##-->

		$options = &$this->OtherOptions;

<!--##
	if (TABLE.TblAdd || bInlineAdd || bGridAdd) {
		sAddSecChkWrk = ew_SecurityCheck("Add", bSecurityEnabled, bUserTable);
		if (ew_IsNotEmpty(sAddSecChkWrk))
			sAddSecChkWrk = " && " + sAddSecChkWrk;
##-->
		$option = $options["addedit"];

	<!--## if (TABLE.TblAdd) { ##-->
		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> ""<!--##=sAddSecChkWrk##-->);
	<!--## } ##-->
	<!--## if (bInlineAdd) { ##-->
		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> ""<!--##=sAddSecChkWrk##-->);
	<!--## } ##-->
	<!--## if (bGridAdd) { ##-->
		$item = &$option->Add("gridadd");
		$item->Body = "<a class=\"ewAddEdit ewGridAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" href=\"" . ew_HtmlEncode($this->GridAddUrl) . "\">" . $Language->Phrase("GridAddLink") . "</a>";
		$item->Visible = ($this->GridAddUrl <> ""<!--##=sAddSecChkWrk##-->);
	<!--## } ##-->
	<!--## if (TABLE.TblAdd && bDetailAdd) { ##-->
		$option = $options["detail"];
		$DetailTableLink = "";
	<!--##
		for (var i = 0, len = arDetailTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arDetailTables[i].index];
			OLDTABLE = TABLE; // Save master table
			MASTERTABLE = DB.Tables(TABLE.TblName);
			TABLE = DB.Tables(MasterDetail.DetailTable);
			if (nDetailTableCount > 0 && TABLE.TblGen && TABLE.TblType != "REPORT") {
				sDetailTblVar = TABLE.TblVar;

				// Set up add link visibility
				sDetailAddVisible = ew_SecurityCheck("DetailAdd", bSecurityEnabled, bUserTable);
				if (ew_IsNotEmpty(sDetailAddVisible)) sDetailAddVisible = " && " + sDetailAddVisible;
	##-->
		$item = &$option->Add("detailadd_<!--##=sDetailTblVar##-->");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["<!--##=sDetailTblVar##-->"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=<!--##=sDetailTblVar##-->") . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["<!--##=sDetailTblVar##-->"]->DetailAdd<!--##=sDetailAddVisible##--><!--##=sAddSecChkWrk##-->);
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "<!--##=sDetailTblVar##-->";
		}
	<!--##
			}
			TABLE = OLDTABLE; // Restore table
		} // MasterDetail
	##-->
		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink) . "\">" . $Language->Phrase("AddMasterDetailLink") . "</a>";
			$item->Visible = ($DetailTableLink <> ""<!--##=sAddSecChkWrk##-->);
			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detailadd_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}
	<!--## } ##-->
<!--##
	}

	if (bGridEdit) {
		sEditSecChkWrk = ew_SecurityCheck("Edit", bSecurityEnabled, bUserTable);
		if (ew_IsNotEmpty(sEditSecChkWrk))
			sEditSecChkWrk = " && " + sEditSecChkWrk;
##-->
		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> ""<!--##=sEditSecChkWrk##-->);
<!--##
	}
##-->

		$option = $options["action"];
<!--##
	if (TABLE.TblDelete && bMultiDelete) {
		if (bInlineDelete)
			sDeleteJs = "ew_SubmitAction(event,{f:" + sJsFormName + ",url:'\" . $this->MultiDeleteUrl . \"',msg:ewLanguage.Phrase('DeleteConfirmMsg')});";
		else
			sDeleteJs = "ew_SubmitAction(event,{f:" + sJsFormName + ",url:'\" . $this->MultiDeleteUrl . \"'});";
		sDeleteSecChkWrk = ew_SecurityCheck("Delete", bSecurityEnabled, bUserTable);
		if (ew_IsEmpty(sDeleteSecChkWrk))
			sDeleteSecChkWrk = "TRUE";
##-->
		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"<!--##=sDeleteJs##-->return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = (<!--##=sDeleteSecChkWrk##-->);
<!--##
	}

	if (bMultiUpdate) {
		sUpdateJs = "ew_SubmitAction(event,{f:" + sJsFormName + ",url:'\" . $this->MultiUpdateUrl . \"'});";
		sUpdateSecChkWrk = ew_SecurityCheck("Edit", bSecurityEnabled, bUserTable);
		if (ew_IsEmpty(sUpdateSecChkWrk))
			sUpdateSecChkWrk = "TRUE";
##-->
		// Add multi update
		$item = &$option->Add("multiupdate");
		$item->Body = "<a class=\"ewAction ewMultiUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateSelectedLink")) . "\" href=\"\" onclick=\"<!--##=sUpdateJs##-->return false;\">" . $Language->Phrase("UpdateSelectedLink") . "</a>";
		$item->Visible = (<!--##=sUpdateSecChkWrk##-->);
<!--##
	}
##-->

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = <!--##=ew_Val(bUseDropDownForAction)##-->;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"<!--##=sFormNameSearch##-->\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = <!--##=ew_Val(bBasicSearch || bExtendedBasicSearch || bAdvancedSearch)##-->;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"<!--##=sFormNameSearch##-->\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = <!--##=ew_Val(bBasicSearch || bExtendedBasicSearch || bAdvancedSearch)##-->;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

<!--## } ##-->

	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;

		$options = &$this->OtherOptions;

<!--## if (CTRL.CtrlID == "gridcls") { ##-->

<!--##
	if (!TABLE.TblAdd) {
		sAddSecChkWrk = "FALSE";
	} else {
		sAddSecChkWrk = ew_SecurityCheck("Add", bSecurityEnabled, bUserTable);
		if (ew_IsEmpty(sAddSecChkWrk))
			sAddSecChkWrk = "TRUE";
	}
##-->
<!--## if (iRecPerRow < 1) { // Single Column ##-->
		if (($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") && $this->CurrentAction != "F") { // Check add/copy/edit mode
			if ($this->AllowAddDeleteRow) {
				$option = &$options["addedit"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
				$item = &$option->Add("addblankrow");
				$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
				$item->Visible = <!--##=sAddSecChkWrk##-->;
				$this->ShowOtherOptions = $item->Visible;
			}
		}
<!--## } ##-->

<!--## } else if (CTRL.CtrlID == "list") { ##-->

<!--##
	if (bGridAdd || bGridEdit) {
##-->
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "gridedit") { // Not grid add/edit mode
<!--##
	}
##-->

			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:<!--##=sJsFormName##-->}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}

<!--##
	if (bGridAdd || bGridEdit) {
		if (!TABLE.TblAdd) {
			sAddSecChkWrk = "FALSE";
		} else {
			sAddSecChkWrk = ew_SecurityCheck("Add", bSecurityEnabled, bUserTable);
			if (ew_IsEmpty(sAddSecChkWrk))
				sAddSecChkWrk = "TRUE";
		}
##-->
		} else { // Grid add/edit mode

			// Hide all options first
			foreach ($options as &$option)
				$option->HideAllOptions();

<!--## if (bGridAdd) { ##-->
			if ($this->CurrentAction == "gridadd") {
	<!--## if (iRecPerRow < 1) { // Single Column ##-->
				if ($this->AllowAddDeleteRow) {
					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = <!--##=sAddSecChkWrk##-->;
				}
	<!--## } ##-->
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
				// Add grid insert
				$item = &$option->Add("gridinsert");
				$item->Body = "<a class=\"ewAction ewGridInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" href=\"\" onclick=\"<!--##=sListFormGridSubmit##-->\">" . $Language->Phrase("GridInsertLink") . "</a>";
				// Add grid cancel
				$item = &$option->Add("gridcancel");
				$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
<!--## } ##-->

<!--## if (bGridEdit) { ##-->
			if ($this->CurrentAction == "gridedit") {
	<!--## if (iRecPerRow < 1) { // Single Column ##-->
				if ($this->AllowAddDeleteRow) {
					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = <!--##=sAddSecChkWrk##-->;
				}
	<!--## } ##-->
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
	<!--## if (bTblCheckConcurrentUpdate) { ##-->
				if ($this->UpdateConflict == "U") { // Record already updated by other user
					$item = &$option->Add("reload");
					$item->Body = "<a class=\"ewAction ewGridReload\" title=\"" . ew_HtmlTitle($Language->Phrase("ReloadLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ReloadLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl)  . "\">" . $Language->Phrase("ReloadLink") . "</a>";
					$item = &$option->Add("overwrite");
					$item->Body = "<a class=\"ewAction ewGridOverwrite\" title=\"" . ew_HtmlTitle($Language->Phrase("OverwriteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("OverwriteLink")) . "\" href=\"\" onclick=\"<!--##=sListFormGridSubmit##-->\">" . $Language->Phrase("OverwriteLink") . "</a>";
					$item = &$option->Add("cancel");
					$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
					$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("ConflictCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ConflictCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("ConflictCancelLink") . "</a>";
				} else {
	<!--## } ##-->
					$item = &$option->Add("gridsave");
					$item->Body = "<a class=\"ewAction ewGridSave\" title=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" href=\"\" onclick=\"<!--##=sListFormGridSubmit##-->\">" . $Language->Phrase("GridSaveLink") . "</a>";
					$item = &$option->Add("gridcancel");
					$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
					$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
	<!--## if (bTblCheckConcurrentUpdate) { ##-->
				}
	<!--## } ##-->
			}
<!--## } ##-->

		}

<!--##
	}
##-->

<!--## } ##-->

	}

<!--## if (CTRL.CtrlID == "list") { ##-->

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
	<!--## if (bUserProfile && TABLE.TblName == PROJ.SecTbl) { ##-->
		global $UserProfile;
	<!--## } ##-->
		$userlist = "";
		$user = "";

		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}

			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;

	<!--## if (bUserTable && TABLE.TblName == PROJ.SecTbl) { ##-->

					$user = $row['<!--##=SQuote(PROJ.SecLoginIDFld)##-->'];
					if ($userlist <> "") $userlist .= ",";
					$userlist .= $user;
					if ($UserAction == "resendregisteremail")
		<!--## if (bResendRegisterEmail) { ##-->
						$Processed = $this->SendRegisterEmail($row);
		<!--## } else { ##-->
						$Processed = FALSE;
		<!--## } ##-->
					elseif ($UserAction == "resetconcurrentuser")
		<!--## if (bResetConcurrentUser) { ##-->
						$Processed = $UserProfile->ResetConcurrentUser($user);
		<!--## } else { ##-->
						$Processed = FALSE;
		<!--## } ##-->
					elseif ($UserAction == "resetloginretry")
		<!--## if (bResetLoginRetry) { ##-->
						$Processed = $UserProfile->ResetLoginRetry($user);
		<!--## } else { ##-->
						$Processed = FALSE;
		<!--## } ##-->
					elseif ($UserAction == "setpasswordexpired")
		<!--## if (bSetPasswordExpired) { ##-->
						$Processed = $UserProfile->SetPasswordExpired($user);
		<!--## } else { ##-->
						$Processed = FALSE;
		<!--## } ##-->
					else

		<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_CustomAction")) { ##-->
						$Processed = $this->Row_CustomAction($UserAction, $row);
		<!--## } else { ##-->
						$Processed = TRUE;
		<!--## } ##-->

	<!--## } else { ##-->

		<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_CustomAction")) { ##-->
					$Processed = $this->Row_CustomAction($UserAction, $row);
		<!--## } else { ##-->
					$Processed = TRUE;
		<!--## } ##-->

	<!--## } ##-->

					if (!$Processed) break;
					$rs->MoveNext();
				}

				if ($Processed) {

					$conn->CommitTrans(); // Commit the changes

	<!--## if (bUserTable && TABLE.TblName == PROJ.SecTbl) { ##-->

		<!--## if (bResendRegisterEmail) { ##-->
					if ($UserAction == "resendregisteremail")
						$this->setSuccessMessage(str_replace('%u', $userlist, $Language->Phrase("ResendRegisterEmailSuccess")));
		<!--## } ##-->

		<!--## if (bResetConcurrentUser) { ##-->
					if ($UserAction == "resetconcurrentuser")
						$this->setSuccessMessage(str_replace('%u', $userlist, $Language->Phrase("ResetConcurrentUserSuccess")));
		<!--## } ##-->

		<!--## if (bResetLoginRetry) { ##-->
					if ($UserAction == "resetloginretry")
						$this->setSuccessMessage(str_replace('%u', $userlist, $Language->Phrase("ResetLoginRetrySuccess")));
		<!--## } ##-->

		<!--## if (bSetPasswordExpired) { ##-->
					if ($UserAction == "setpasswordexpired")
						$this->setSuccessMessage(str_replace('%u', $userlist, $Language->Phrase("SetPasswordExpiredSuccess")));
		<!--## } ##-->

	<!--## } ##-->

					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message

				} else {

					$conn->RollbackTrans(); // Rollback changes

	<!--## if (bUserTable && TABLE.TblName == PROJ.SecTbl) { ##-->

		<!--## if (bResendRegisterEmail) { ##-->
					if ($UserAction == "resendregisteremail")
						$this->setFailureMessage(str_replace('%u', $userlist, $Language->Phrase("ResendRegisterEmailFailure")));
		<!--## } ##-->

		<!--## if (bResetConcurrentUser) { ##-->
					if ($UserAction == "resetconcurrentuser")
						$this->setFailureMessage(str_replace('%u', $user, $Language->Phrase("ResetConcurrentUserFailure")));
		<!--## } ##-->

		<!--## if (bResetLoginRetry) { ##-->
					if ($UserAction == "resetloginretry")
						$this->setFailureMessage(str_replace('%u', $user, $Language->Phrase("ResetLoginRetryFailure")));
		<!--## } ##-->

		<!--## if (bSetPasswordExpired) { ##-->
					if ($UserAction == "setpasswordexpired")
						$this->setFailureMessage(str_replace('%u', $user, $Language->Phrase("SetPasswordExpiredFailure")));
		<!--## } ##-->

	<!--## } ##-->

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {
						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}

				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action

			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

<!--## } ##-->

<!--## if (CTRL.CtrlID == "list") { ##-->

	<!--## if (iRecPerRow >= 1) { // Multi-Column Layout ##-->

	// Begin grid
	function MultiColumnBeginGrid() {
		$div = "";
		// Get correct grid count
		if (in_array($this->CurrentAction, array("gridadd", "gridedit"))) { // Grid add/edit
			$cnt = $this->MultiColumnEditCnt;
		} elseif ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline edit row
			$cnt = $this->MultiColumnEditCnt;
		} else {
			$cnt = $this->MultiColumnCnt;
		}
		$this->GridCnt += $cnt;
		$this->ColCnt += 1;
		$this->MultiColumnClass = "col-sm-" . $cnt;
		// Close previous div
		if ($this->GridCnt > 12) {
			$this->GridCnt = $cnt;
			$this->ColCnt = 1;
			$div .= "</div>";
		}
		// Begin new div
		if ($this->ColCnt == 1) {
			$div .= "<div class=\"row ewMultiColumnRow\">";
		}
		return $div;
	}

	// End grid
	function MultiColumnEndGrid() {
		$div = "";
		// Close previous div
		if ($this->GridCnt > 0) {
			$div = "</div>";
		}
		return $div;
	}

	<!--## } ##-->


	// Set up search options
	function SetupSearchOptions() {
		global $Language;

		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

<!--## if (bBasicSearch || bExtendedBasicSearch) { ##-->

<!--##
	var sSearchToggleClass = PROJ.GetV("SearchPanelCollapsed") ? "" : " active";
##-->
		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : "<!--##=sSearchToggleClass##-->";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"<!--##=sFormNameSearch##-->\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

<!--## } ##-->

<!--## if (bBasicSearch || bExtendedBasicSearch || bAdvancedSearch) { ##-->

<!--##
	if (bShowBlankListPage || bHasSearchDefault) {
		sResetBtn = "$Language->Phrase(\"ResetSearchBtn\")";
		sResetPhrase = "$Language->Phrase(\"ResetSearch\")";
	} else {
		sResetBtn = "$Language->Phrase(\"ShowAllBtn\")";
		sResetPhrase = "$Language->Phrase(\"ShowAll\")";
	}
##-->

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . <!--##=sResetPhrase##--> . "\" data-caption=\"" . <!--##=sResetPhrase##--> . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . <!--##=sResetBtn##--> . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

<!--## } ##-->

<!--## if (bAdvancedSearch) { ##-->

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
	<!--## if (bUseModalSearch) { ##-->
		if (ew_IsMobile())
			$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"<!--##=sFnSearch##-->\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		else
			$item->Body = "<button type=\"button\" class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" onclick=\"ew_SearchDialogShow({lnk:this,url:'<!--##=sFnSearch##-->'});\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
	<!--## } else { ##-->
		$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"<!--##=sFnSearch##-->\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
	<!--## } ##-->
		$item->Visible = TRUE;

<!--## } ##-->

<!--## if ((bBasicSearch || bExtendedBasicSearch || bAdvancedSearch) && TABLE.TblSearchHighlight) { ##-->

		// Search highlight button
		$item = &$this->SearchOptions->Add("searchhighlight");
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewHighlight active\" title=\"" . $Language->Phrase("Highlight") . "\" data-caption=\"" . $Language->Phrase("Highlight") . "\" data-toggle=\"button\" data-form=\"<!--##=sFormNameSearch##-->\" data-name=\"" . $this->HighlightName() . "\">" . $Language->Phrase("HighlightBtn") . "</button>";
		$item->Visible = ($this->SearchWhere <> "" && $this->TotalRecs > 0);

<!--## } ##-->

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
	<!--## if (bUserTable) { ##-->
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	<!--## } ##-->

	}
<!--## } ##-->

<!--##/session##-->
?>

<?php
<!--##session setuplistoptionsext-start##-->
	function SetupListOptionsExt() {
		global $Security, $Language;
<!--##/session##-->

<!--##session setuplistoptionsext-end##-->
	}
<!--##/session##-->

<!--##session renderlistoptionsext-start##-->
	function RenderListOptionsExt() {
		global $Security, $Language;
<!--##/session##-->

<!--##session renderlistoptionsext-end##-->
	}
<!--##/session##-->
?>