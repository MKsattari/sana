<!--##session phpconfig##-->
<!--##
	bBasicSearch = false;
	bExtendedBasicSearch = false;
	bAdvancedSearch = false;
	if (TABLE.TblBasicSearch) {
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (IsFldBasicSearch(goFld)) bBasicSearch = true;
			}
			if (bBasicSearch) break;
		}
	}
	if (TABLE.TblExtendedBasicSearch) {
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (IsFldExtendedSearch(goFld)) bExtendedBasicSearch = true;
			}
			if (bExtendedBasicSearch) break;
		}
	}
	if (TABLE.TblSearch) {
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (goFld.FldSearch) {
					bAdvancedSearch = true;
					break;
				}
			}
		}
	}

	// Basic search default value
	bHasBasicSearchDefault = (ew_IsNotEmpty(TABLE.TblBasicSearchDefault)) && TABLE.TblBasicSearch;

	// Advanced search default values
	bHasAdvancedSearchDefault = false;
	for (var i = 0, len = arAllFlds.length; i < len; i++) {
		if (GetFldObj(arAllFlds[i])) {
			if ((goFld.FldSearch || goFld.FldExtendedBasicSearch) && (ew_IsNotEmpty(goFld.FldSearchDefault) || ew_IsNotEmpty(goFld.FldSearchDefault2)) &&
				!(goFld.FldHtmlTag == "FILE" && ew_IsBinaryField(goFld))) {
				bHasAdvancedSearchDefault = true;
				break;
			}
		}
	} // AllField

	bHasSearchDefault = bHasBasicSearchDefault || bHasAdvancedSearchDefault;

	// Support selected row color
	if (iRecPerRow < 1) {
		//sListTableId = " id=\"" + sPageObj + "_main\"";
		sMouseClickMultiEvent = "ew_ClickMultiCheckbox(event);";
	} else {
		//sListTableId = "";
		sMouseClickMultiEvent = "";
	}

	// Set up Multi Column grid count
	var iMultiColumnCnt = 12;
	if (iRecPerRow > 0) {
		var ar = [1,2,3,4,6,12]; // Possible values of records per row
		for (var i = 1, len = ar.length; i < len; i++) {
			if (iRecPerRow < ar[i]) {
				iRecPerRow = ar[i-1];
				break;
			} else if (i == len-1) {
				iRecPerRow = ar[i];
				break;
			}
		}
		iMultiColumnCnt = (12/iRecPerRow);
	}
	var iEditRecPerRow = 1;
	var iMultiColumnEditCnt = 12;
##-->
<!--##/session##-->

<?php
<!--##session phpmain##-->

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;

<!--## if (CTRL.CtrlID == "gridcls") { ##-->
	var $ShowOtherOptions = FALSE;
<!--## } ##-->

	var $DisplayRecs = <!--##=iRecPerPage##-->;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
<!--## if (bTblCheckConcurrentUpdate) { ##-->
	var $HashValue; // Hash value
<!--## } ##-->

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
	var $DetailPages;

	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		<!--## if (bUseCustomTemplate) { ##-->
			$this->AllowAddDeleteRow = FALSE; // Do not allow add/delete row
		<!--## } ##-->

		<!--## if (iRecPerRow >= 1) { ##-->
		// Multi Column
		$this->RecPerRow = <!--##=iRecPerRow##-->;
		$this->MultiColumnCnt = <!--##=iMultiColumnCnt##-->;
		$this->MultiColumnEditCnt = <!--##=iMultiColumnEditCnt##-->;
		<!--## } ##-->

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);

		if ($this->IsPageRequest()) { // Validate request

		<!--## if (CTRL.CtrlID == "list") { ##-->
			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();
		<!--## } ##-->

		<!--## if (ew_IsNotEmpty(sRecPerPageList)) { ##-->
			// Set up records per page
			$this->SetUpDisplayRecs();
		<!--## } ##-->

			// Handle reset command
			$this->ResetCmd();

	<!--## if (CTRL.CtrlID == "list") { ##-->

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

		<!--## if (bInlineEdit || bGridEdit || bInlineAdd || bInlineCopy || bGridAdd) { ##-->
			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];
				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();
				
				<!--## if (bGridEdit) { ##-->
				// Switch to grid edit mode
				if ($this->CurrentAction == "gridedit")
					$this->GridEditMode();
				<!--## } ##-->
				
				<!--## if (bInlineEdit) { ##-->
				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();
				<!--## } ##-->
			
				<!--## if (bInlineAdd || bInlineCopy) { ##-->
				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();
				<!--## } ##-->
				
				<!--## if (bGridAdd) { ##-->
				// Switch to grid add mode
				if ($this->CurrentAction == "gridadd")
					$this->GridAddMode();
				<!--## } ##-->
			
			} else {
			
				if (@$_POST["a_list"] <> "") {
			
					$this->CurrentAction = $_POST["a_list"]; // Get action
			
				<!--## if (bGridEdit) { ##-->
					// Grid Update
					if (($this->CurrentAction == "gridupdate" || $this->CurrentAction == "gridoverwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridedit") {
						if ($this->ValidateGridForm()) {
							$bGridUpdate = $this->GridUpdate();
						} else {
							$bGridUpdate = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridUpdate) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
						}
					}
				<!--## } ##-->
			
				<!--## if (bInlineEdit) { ##-->
					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();
				<!--## } ##-->

				<!--## if (bInlineAdd || bInlineCopy) { ##-->
					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();
				<!--## } ##-->

				<!--## if (bGridAdd) { ##-->
					// Grid Insert
					if ($this->CurrentAction == "gridinsert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridadd") {
						if ($this->ValidateGridForm()) {
							$bGridInsert = $this->GridInsert();
						} else {
							$bGridInsert = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridInsert) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridadd"; // Stay in Grid Add mode
						}
					}
				<!--## } ##-->

				}

			}
		<!--## } ##-->

	<!--## } ##-->

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

	<!--## if (CTRL.CtrlID == "list") { ##-->
			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}
	<!--## } ##-->

	<!--## if (bGridAdd || bGridEdit) { ##-->
			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}
	<!--## } ##-->

	<!--## if (CTRL.CtrlID == "list" && (bAdvancedSearch || bExtendedBasicSearch || bBasicSearch)) { ##-->

			// Get default search criteria
		<!--## if (bBasicSearch) { ##-->
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
		<!--## } ##-->
		<!--## if (bExtendedBasicSearch || bAdvancedSearch) { ##-->
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));
		<!--## } ##-->

	<!--## if (CTRL.CtrlID == "list" && bBasicSearch) { ##-->
			// Get basic search values
			$this->LoadBasicSearchValues();
	<!--## } ##-->
		
	<!--## if (CTRL.CtrlID == "list" && (bAdvancedSearch || bExtendedBasicSearch)) { ##-->
			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values
	<!--## } ##-->

	<!--## if (CTRL.CtrlID == "list" && (bBasicSearch || bAdvancedSearch || bExtendedBasicSearch)) { ##-->
			// Restore filter list
			$this->RestoreFilterList();
	<!--## } ##-->

	<!--## if (CTRL.CtrlID == "list" && (bAdvancedSearch || bExtendedBasicSearch)) { ##-->
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);
	<!--## } ##-->

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

		<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Recordset_SearchValidated")) { ##-->
			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();
		<!--## } ##-->

	<!--## } ##-->

			// Set up sorting order
			$this->SetUpSortOrder();
		
	<!--## if (CTRL.CtrlID == "list" && bBasicSearch) { ##-->
			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
	<!--## } ##-->
		
	<!--## if (CTRL.CtrlID == "list" && (bAdvancedSearch || bExtendedBasicSearch)) { ##-->
			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
	<!--## } ##-->

		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = <!--##=iRecPerPage##-->; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();
		
	<!--## if (CTRL.CtrlID == "list" && (bBasicSearch || bExtendedBasicSearch || bAdvancedSearch)) { ##-->

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

		<!--## if (CTRL.CtrlID == "list" && bBasicSearch) { ##-->
			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		<!--## } ##-->

		<!--## if (CTRL.CtrlID == "list" && (bAdvancedSearch || bExtendedBasicSearch)) { ##-->
			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		<!--## } ##-->

		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Recordset_Searching")) { ##-->
		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);
		<!--## } ##-->
		
		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

	<!--## } ##-->
		
		// Build filter
		$sFilter = "";
		
	<!--## if (bUserTable) { ##-->
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
	<!--## } ##-->
		
	<!--##
		if (nMasterTableCount > 0) {
	##-->
		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
	<!--##
		}
	##-->

	<!--## if (bMasterTableHasUserIDFld) { ##-->
		// Add master User ID filter
		if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
	<!--##
		for (var i = 0, len = arMasterTables.length; i < len; i++) {
	##-->
			if ($this->getCurrentMasterTable() == "<!--##=arMasterTables[i].TblVar##-->")
				$this->DbMasterFilter = $this->AddMasterUserIDFilter($this->DbMasterFilter, "<!--##=arMasterTables[i].TblVar##-->"); // Add master User ID filter
	<!--##
		}
	##-->
		}
	<!--## } ##-->
		
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

	<!--## if (bShowBlankListPage) { ##-->
		if ($sFilter == "") {
			$sFilter = "0=101";
			$this->SearchWhere = $sFilter;
		}
	<!--## } ##-->
		
	<!--##
		if (nMasterTableCount > 0) {
			for (var i = 0, len = arMasterTables.length; i < len; i++) {
				var MasterDetail = goAllMasDets[arMasterTables[i].index];
				OLDTABLE = TABLE; // Save detail table
				DETAILTABLE = DB.Tables(TABLE.TblName);
				TABLE = DB.Tables(MasterDetail.MasterTable);
				if (TABLE.TblGen) {
					sMasterTblVar = TABLE.TblVar;
					sMasterListFn = ew_GetFileNameByCtrlID("list");
	##-->
		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "<!--##=sMasterTblVar##-->") {
			global $<!--##=sMasterTblVar##-->;
			$rsmaster = $<!--##=sMasterTblVar##-->->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
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
		
		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";
		
	<!--## if (bExportSelectedOnly && CTRL.CtrlID == "list") { ##-->
		// Export selected records
		if ($this->Export <> "")
			$this->CurrentFilter = $this->BuildExportSelectedFilter();
	<!--## } ##-->

	<!--## if ((CTRL.CtrlID == "list" && bListExport) && (bExportHtml || bExportEmail || bExportCsv || bExportWord || bExportExcel || bExportXml || bExportPDF)) { ##-->
		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}
	<!--## } ##-->

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

	<!--## if (CTRL.CtrlID == "list") { ##-->
		// Search options
		$this->SetupSearchOptions();
	<!--## } ##-->

	}

<!--##/session##-->
?>

<!--##session htmmaster##-->
<!--##
	if (nMasterTableCount > 0) {
##-->
<!--##=sMasterExpStart##-->
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
<?php
$gsMasterReturnUrl = "<!--##=sMasterListFn##-->";
if ($<!--##=sPageObj##-->->DbMasterFilter <> "" && $<!--##=gsTblVar##-->->getCurrentMasterTable() == "<!--##=sMasterTblVar##-->") {
	if ($<!--##=sPageObj##-->->MasterRecordExists) {
		if ($<!--##=gsTblVar##-->->getCurrentMasterTable() == $<!--##=gsTblVar##-->->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<!--##=SYSTEMFUNCTIONS.IncludeFile("master","mastertable")##-->
<?php
	}
}
?>
<!--##
			}
			TABLE = OLDTABLE; // Restore detail table
		} // MasterDetail
##-->
<!--##=sMasterExpEnd##-->
<!--##
	}
##-->
<!--##/session##-->


<!--##session htmheader##-->

<!--## if (CTRL.CtrlID == "list") { ##-->

<!--##=sExpStart##-->
<div class="ewToolbar">

<!--##include phpcommon.php/breadcrumb##-->

<?php if ($<!--##=sPageObj##-->->TotalRecs > 0 && $<!--##=sPageObj##-->->ExportOptions->Visible()) { ?>
<?php $<!--##=sPageObj##-->->ExportOptions->Render("body") ?>
<?php } ?>

<!--## if (bBasicSearch || bExtendedBasicSearch || bAdvancedSearch) { ##-->

<?php if ($<!--##=sPageObj##-->->SearchOptions->Visible()) { ?>
<?php $<!--##=sPageObj##-->->SearchOptions->Render("body") ?>
<?php } ?>

<?php if ($<!--##=sPageObj##-->->FilterOptions->Visible()) { ?>
<?php $<!--##=sPageObj##-->->FilterOptions->Render("body") ?>
<?php } ?>

<!--## } ##-->

<!--##include phpcommon.php/language##-->

<div class="clearfix"></div>
</div>
<!--##=sExpEnd##-->

<!--## } ##-->

<!--##/session##-->


<!--##session phploadrecordset##-->
<?php
<!--## if (bGridAdd) { ##-->

if ($<!--##=gsTblVar##-->->CurrentAction == "gridadd") {

	<!--## if (CTRL.CtrlID == "grid") { ##-->

	if ($<!--##=gsTblVar##-->->CurrentMode == "copy") {
		$bSelectLimit = $<!--##=sPageObj##-->->UseSelectLimit;
		if ($bSelectLimit) {
			$<!--##=sPageObj##-->->TotalRecs = $<!--##=gsTblVar##-->->SelectRecordCount();
			$<!--##=sPageObj##-->->Recordset = $<!--##=sPageObj##-->->LoadRecordset($<!--##=sPageObj##-->->StartRec-1, $<!--##=sPageObj##-->->DisplayRecs);
		} else {
			if ($<!--##=sPageObj##-->->Recordset = $<!--##=sPageObj##-->->LoadRecordset())
				$<!--##=sPageObj##-->->TotalRecs = $<!--##=sPageObj##-->->Recordset->RecordCount();
		}
		$<!--##=sPageObj##-->->StartRec = 1;
		$<!--##=sPageObj##-->->DisplayRecs = $<!--##=sPageObj##-->->TotalRecs;
	} else {
		$<!--##=gsTblVar##-->->CurrentFilter = "0=1";
		$<!--##=sPageObj##-->->StartRec = 1;
		$<!--##=sPageObj##-->->DisplayRecs = $<!--##=gsTblVar##-->->GridAddRowCount;
	}

	<!--## } else { ##-->

	$<!--##=gsTblVar##-->->CurrentFilter = "0=1";
	$<!--##=sPageObj##-->->StartRec = 1;
	$<!--##=sPageObj##-->->DisplayRecs = $<!--##=gsTblVar##-->->GridAddRowCount;

	<!--## } ##-->

	$<!--##=sPageObj##-->->TotalRecs = $<!--##=sPageObj##-->->DisplayRecs;
	$<!--##=sPageObj##-->->StopRec = $<!--##=sPageObj##-->->DisplayRecs;

} else {

<!--## } ##-->

	$bSelectLimit = $<!--##=sPageObj##-->->UseSelectLimit;
	if ($bSelectLimit) {
		if ($<!--##=sPageObj##-->->TotalRecs <= 0)
			$<!--##=sPageObj##-->->TotalRecs = $<!--##=gsTblVar##-->->SelectRecordCount();
	} else {
		if (!$<!--##=sPageObj##-->->Recordset && ($<!--##=sPageObj##-->->Recordset = $<!--##=sPageObj##-->->LoadRecordset()))
			$<!--##=sPageObj##-->->TotalRecs = $<!--##=sPageObj##-->->Recordset->RecordCount();
	}
	$<!--##=sPageObj##-->->StartRec = 1;
<!--## if (CTRL.CtrlID == "list") { ##-->
	if ($<!--##=sPageObj##-->->DisplayRecs <= 0 || ($<!--##=gsTblVar##-->->Export <> "" && $<!--##=gsTblVar##-->->ExportAll)) // Display all records
		$<!--##=sPageObj##-->->DisplayRecs = $<!--##=sPageObj##-->->TotalRecs;
	if (!($<!--##=gsTblVar##-->->Export <> "" && $<!--##=gsTblVar##-->->ExportAll))
		$<!--##=sPageObj##-->->SetUpStartRec(); // Set up start record position
<!--## } else { ##-->
	$<!--##=sPageObj##-->->DisplayRecs = $<!--##=sPageObj##-->->TotalRecs; // Display all records
<!--## } ##-->
	if ($bSelectLimit)
		$<!--##=sPageObj##-->->Recordset = $<!--##=sPageObj##-->->LoadRecordset($<!--##=sPageObj##-->->StartRec-1, $<!--##=sPageObj##-->->DisplayRecs);

	// Set no record found message
	if ($<!--##=gsTblVar##-->->CurrentAction == "" && $<!--##=sPageObj##-->->TotalRecs == 0) {
	<!--## if (bUserTable) { ##-->
		if (!$Security->CanList())
			$<!--##=sPageObj##-->->setWarningMessage($Language->Phrase("NoPermission"));
	<!--## } ##-->
		if ($<!--##=sPageObj##-->->SearchWhere == "0=101")
			$<!--##=sPageObj##-->->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$<!--##=sPageObj##-->->setWarningMessage($Language->Phrase("NoRecord"));
	}

	<!--## if (TABLE.TblAuditTrail && CTRL.CtrlID == "list" && (bBasicSearch || bExtendedBasicSearch || bAdvancedSearch)) { ##-->
	// Audit trail on search
	if ($<!--##=sPageObj##-->->AuditTrailOnSearch && $<!--##=sPageObj##-->->Command == "search" && !$<!--##=sPageObj##-->->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $<!--##=sPageObj##-->->getSessionWhere();
		$<!--##=sPageObj##-->->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
	<!--## } ##-->

<!--## if (bGridAdd) { ##-->
}
<!--## } ##-->

$<!--##=sPageObj##-->->RenderOtherOptions();

?>
<!--##/session##-->


<!--##session htmmain##-->

<!--##include phpcommon.php/header-message##-->
<!--##include phpcommon.php/phpcommon-message##-->

<?php if ($<!--##=sPageObj##-->->TotalRecs > 0 || $<!--##=gsTblVar##-->->CurrentAction <> "") { ?>
<!--## if (iRecPerRow < 1) { // Single Column ##-->
<div class="panel panel-default ewGrid">
<!--## } else { ##-->
<div class="ewMultiColumnGrid">
<!--## } ##-->

<!--## if (CTRL.CtrlID == "grid") { ##-->
<div id="<!--##=sFormName##-->" class="ewForm form-inline">
<!--## if (bTopPageLink) { ##-->
<?php if ($<!--##=sPageObj##-->->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($<!--##=sPageObj##-->->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<!--## } ##-->

<!--## } else { ##-->

<!--## if (bTopPageLink) { ##-->
<!--##=sExpStart##-->
<!--## if (iRecPerRow < 1) { // Single Column ##-->
<div class="panel-heading ewGridUpperPanel">
<!--## } else { ##-->
<div>
<!--## } ##-->
<?php if ($<!--##=gsTblVar##-->->CurrentAction <> "gridadd" && $<!--##=gsTblVar##-->->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<!--##include pager.php/pager##-->
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($<!--##=sPageObj##-->->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<!--##=sExpEnd##-->
<!--## } ##-->

<!--## if (iRecPerRow < 1) { // Single Column ##-->
<form name="<!--##=sFormName##-->" id="<!--##=sFormName##-->" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<!--## } else { ##-->
<form name="<!--##=sFormName##-->" id="<!--##=sFormName##-->" class="form-horizontal ewForm ewListForm ewMultiColumnForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<!--## } ##-->
<?php if ($<!--##=sPageObj##-->->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $<!--##=sPageObj##-->->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="<!--##=gsTblVar##-->">

<!--## if (bExportSelectedOnly) { ##-->
<input type="hidden" name="exporttype" id="exporttype" value="">
<!--## } ##-->

<!--##
	for (var i = 0, len = arMasterTables.length; i < len; i++) {
		var MasterDetail = goAllMasDets[arMasterTables[i].index];
		MASTERTABLE = DB.Tables(MasterDetail.MasterTable);
		if (MASTERTABLE.TblGen) {
			sMasterTblVar = MASTERTABLE.TblVar;
##-->
<?php if ($<!--##=gsTblVar##-->->getCurrentMasterTable() == "<!--##=sMasterTblVar##-->" && $<!--##=gsTblVar##-->->CurrentAction <> "") { ?>
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

<!--## } ##-->

<!--## if (iRecPerRow < 1) { // Single Column ##-->
<div id="gmp_<!--##=gsTblVar##-->" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<!--## } ##-->

<!--## if (CTRL.CtrlID != "grid") { ##-->
<!--## if (bInlineAdd || bInlineCopy) { ##-->
<?php if ($<!--##=sPageObj##-->->TotalRecs > 0 || $<!--##=gsTblVar##-->->CurrentAction == "add" || $<!--##=gsTblVar##-->->CurrentAction == "copy") { ?>
<!--## } else { ##-->
<?php if ($<!--##=sPageObj##-->->TotalRecs > 0) { ?>
<!--## } ##-->
<!--## } ##-->

<!--##
	var sMainTableStyle = "";
	if (bUseCustomTemplate) {
		sMainTableStyle = " style=\"display: none\"";
	}
##-->

<!--## if (iRecPerRow < 1) { // Single Column ##-->

<table id="<!--##=ewTableId##-->"<!--##=ewCSSTableClass##--><!--##=sMainTableStyle##-->>

<?php echo $<!--##=gsTblVar##-->->TableCustomInnerHtml ?>

<thead><!-- Table header -->
	<tr<!--##=ewCSSTableHeaderClass##-->>

<?php
// Header row
$<!--##=sPageObj##-->->RowType = EW_ROWTYPE_HEADER;

// Render list options
$<!--##=sPageObj##-->->RenderListOptions();

// Render list options (header, left)
<!--## if (bUseCustomTemplate) { ##-->
$<!--##=sPageObj##-->->ListOptions->Render("header", "", "", "<!--##=sCustomListOptionsHeader##-->", $<!--##=gsTblVar##-->->TableVar, "<!--##=sTemplateClass##-->");
<!--## } else { ##-->
$<!--##=sPageObj##-->->ListOptions->Render("header", "left");
<!--## } ##-->

?>

	<!--##
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				if (CTRL.CtrlID == "list" && bBasicSearch && goFld.FldBasicSearch &&
					(goFld.FldHtmlTag == "NO" || goFld.FldHtmlTag == "TEXT" || goFld.FldHtmlTag == "TEXTAREA" ||
					goFld.NativeDataType == 247 || goFld.NativeDataType == 248 ||
					(goFld.FldHtmlTag == "FILE" && !ew_IsBinaryField(goFld))))
					sSrchLegend = "<?php echo $Language->Phrase(\"SrchLegend\") ?>";
				else
					sSrchLegend = "";
				if (iSortType > 0)
					sJsSort = " class=\"ewPointer\" onclick=\"ew_Sort(event,'<?php echo $" + gsTblVar + "->SortUrl($" + gsFldObj + ") ?>'," + iSortType + ");\"";
				else
					sJsSort = "";
				var sScriptStart, sScriptEnd;
				if (bUseCustomTemplate) {
					sScriptStart = ew_CustomScriptTag(gsTblVar + "_" + gsFldParm, "header", sTemplateClass);
					sScriptEnd = "</script>";
				} else {
					sScriptStart = "";
					sScriptEnd = "";
				}
				sHeaderSpanId = "elh_" + gsTblVar + "_" + gsFldParm;
				sClassId = gsTblVar + "_" + gsFldParm;
	##-->
<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=gsFldName##--> ?>
	<?php if ($<!--##=gsTblVar##-->->SortUrl($<!--##=gsFldObj##-->) == "") { ?>
		<th data-name="<!--##=gsFldParm##-->"><div id="<!--##=sHeaderSpanId##-->" class="<!--##=sClassId##-->"><div class="ewTableHeaderCaption"<!--##=FieldTD_Header(FIELD)##-->><!--##=SYSTEMFUNCTIONS.FieldCaption()##--></div></div></th>
	<?php } else { ?>
		<th data-name="<!--##=gsFldParm##-->"><!--##=sScriptStart##--><div<!--##=sJsSort##-->><div id="<!--##=sHeaderSpanId##-->" class="<!--##=sClassId##-->">
			<div class="ewTableHeaderBtn"<!--##=FieldTD_Header(FIELD)##-->><span class="ewTableHeaderCaption"><?php echo $<!--##=gsFldObj##-->->FldCaption() ?><!--##=sSrchLegend##--></span><span class="ewTableHeaderSort"><?php if ($<!--##=gsFldObj##-->->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($<!--##=gsFldObj##-->->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div><!--##=sScriptEnd##--></th>
	<?php } ?>
<?php } ?>		
	<!--##
			}
		} // Field
	##-->

<!--## if (!bUseCustomTemplate) { ##-->
<?php
// Render list options (header, right)
$<!--##=sPageObj##-->->ListOptions->Render("header", "right");
?>
<!--## } ##-->

	</tr>
</thead>
<tbody>

<!--## } ##-->


<!--## if (CTRL.CtrlID == "list") { ##-->
<!--##
	if (bInlineAdd || bInlineCopy) {
		bGenTemplateLine = false;
##-->
<!--##include list-script-inline.php/inlineaddhtml##-->
<!--##
	}
##-->
<!--## } ##-->

<?php
<!--## if (CTRL.CtrlID == "list") { ##-->
if ($<!--##=gsTblVar##-->->ExportAll && $<!--##=gsTblVar##-->->Export <> "") {
	$<!--##=sPageObj##-->->StopRec = $<!--##=sPageObj##-->->TotalRecs;
} else {
	// Set the last record to display
	if ($<!--##=sPageObj##-->->TotalRecs > $<!--##=sPageObj##-->->StartRec + $<!--##=sPageObj##-->->DisplayRecs - 1)
		$<!--##=sPageObj##-->->StopRec = $<!--##=sPageObj##-->->StartRec + $<!--##=sPageObj##-->->DisplayRecs - 1;
	else
		$<!--##=sPageObj##-->->StopRec = $<!--##=sPageObj##-->->TotalRecs;
}
<!--## } else { ##-->
$<!--##=sPageObj##-->->StartRec = 1;
$<!--##=sPageObj##-->->StopRec = $<!--##=sPageObj##-->->TotalRecs; // Show all records
<!--## } ##-->

<!--## if (bInlineEdit || bGridEdit || bInlineAdd || bInlineCopy || bGridAdd) { ##-->
// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($<!--##=sPageObj##-->->FormKeyCountName) && ($<!--##=gsTblVar##-->->CurrentAction == "gridadd" || $<!--##=gsTblVar##-->->CurrentAction == "gridedit" || $<!--##=gsTblVar##-->->CurrentAction == "F")) {
		$<!--##=sPageObj##-->->KeyCount = $objForm->GetValue($<!--##=sPageObj##-->->FormKeyCountName);
		$<!--##=sPageObj##-->->StopRec = $<!--##=sPageObj##-->->StartRec + $<!--##=sPageObj##-->->KeyCount - 1;
	}
}
<!--## } ##-->

$<!--##=sPageObj##-->->RecCnt = $<!--##=sPageObj##-->->StartRec - 1;
if ($<!--##=sPageObj##-->->Recordset && !$<!--##=sPageObj##-->->Recordset->EOF) {
	$<!--##=sPageObj##-->->Recordset->MoveFirst();
	$bSelectLimit = $<!--##=sPageObj##-->->UseSelectLimit;
	if (!$bSelectLimit && $<!--##=sPageObj##-->->StartRec > 1)
		$<!--##=sPageObj##-->->Recordset->Move($<!--##=sPageObj##-->->StartRec - 1);
} elseif (!$<!--##=gsTblVar##-->->AllowAddDeleteRow && $<!--##=sPageObj##-->->StopRec == 0) {
	$<!--##=sPageObj##-->->StopRec = $<!--##=gsTblVar##-->->GridAddRowCount;
}
<!--## if (iRecPerRow < 1) { ##-->
// Initialize aggregate
$<!--##=gsTblVar##-->->RowType = EW_ROWTYPE_AGGREGATEINIT;
$<!--##=gsTblVar##-->->ResetAttrs();
$<!--##=sPageObj##-->->RenderRow();
<!--## } ##-->

<!--## if (bInlineEdit) { ##-->
$<!--##=sPageObj##-->->EditRowCnt = 0;
if ($<!--##=gsTblVar##-->->CurrentAction == "edit")
	$<!--##=sPageObj##-->->RowIndex = 1;
<!--## } ##-->

<!--## if (bGridAdd) { ##-->
if ($<!--##=gsTblVar##-->->CurrentAction == "gridadd")
	$<!--##=sPageObj##-->->RowIndex = 0;
<!--## } ##-->

<!--## if (bGridEdit) { ##-->
if ($<!--##=gsTblVar##-->->CurrentAction == "gridedit")
	$<!--##=sPageObj##-->->RowIndex = 0;
<!--## } ##-->

while ($<!--##=sPageObj##-->->RecCnt < $<!--##=sPageObj##-->->StopRec) {
	$<!--##=sPageObj##-->->RecCnt++;
	if (intval($<!--##=sPageObj##-->->RecCnt) >= intval($<!--##=sPageObj##-->->StartRec)) {
		$<!--##=sPageObj##-->->RowCnt++;

	<!--## if (bGridAdd || bGridEdit) { ##-->
		if ($<!--##=gsTblVar##-->->CurrentAction == "gridadd" || $<!--##=gsTblVar##-->->CurrentAction == "gridedit" || $<!--##=gsTblVar##-->->CurrentAction == "F") {
			$<!--##=sPageObj##-->->RowIndex++;
			$objForm->Index = $<!--##=sPageObj##-->->RowIndex;
			if ($objForm->HasValue($<!--##=sPageObj##-->->FormActionName))
				$<!--##=sPageObj##-->->RowAction = strval($objForm->GetValue($<!--##=sPageObj##-->->FormActionName));
			elseif ($<!--##=gsTblVar##-->->CurrentAction == "gridadd")
				$<!--##=sPageObj##-->->RowAction = "insert";
			else
				$<!--##=sPageObj##-->->RowAction = "";
		}
	<!--## } ##-->

		// Set up key count
		$<!--##=sPageObj##-->->KeyCount = $<!--##=sPageObj##-->->RowIndex;

		// Init row class and style
		$<!--##=gsTblVar##-->->ResetAttrs();
		$<!--##=gsTblVar##-->->CssClass = <!--##=ew_DoubleQuote(ewCSSTableRowClass, 1)##-->;

	<!--## if (CTRL.CtrlID == "grid") { ##-->

		if ($<!--##=gsTblVar##-->->CurrentAction == "gridadd") {

			if ($<!--##=gsTblVar##-->->CurrentMode == "copy") {
				$<!--##=sPageObj##-->->LoadRowValues($<!--##=sPageObj##-->->Recordset); // Load row values
				$<!--##=sPageObj##-->->SetRecordKey($<!--##=sPageObj##-->->RowOldKey, $<!--##=sPageObj##-->->Recordset); // Set old record key
			} else {
				$<!--##=sPageObj##-->->LoadDefaultValues(); // Load default values
				$<!--##=sPageObj##-->->RowOldKey = ""; // Clear old key value
			}

		} else {
			$<!--##=sPageObj##-->->LoadRowValues($<!--##=sPageObj##-->->Recordset); // Load row values
		}

	<!--## } else { ##-->

		if ($<!--##=gsTblVar##-->->CurrentAction == "gridadd") {

		<!--## if (gbTblListAdd || gbTblListEdit) { ##-->

			$<!--##=sPageObj##-->->LoadDefaultValues(); // Load default values

		<!--## } ##-->

		} else {
			$<!--##=sPageObj##-->->LoadRowValues($<!--##=sPageObj##-->->Recordset); // Load row values
		}

	<!--## } ##-->

		$<!--##=gsTblVar##-->->RowType = EW_ROWTYPE_VIEW; // Render view

<!--##
	if (bGridAdd) {
##-->

		if ($<!--##=gsTblVar##-->->CurrentAction == "gridadd") // Grid add
			$<!--##=gsTblVar##-->->RowType = EW_ROWTYPE_ADD; // Render add

		if ($<!--##=gsTblVar##-->->CurrentAction == "gridadd" && $<!--##=gsTblVar##-->->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$<!--##=sPageObj##-->->RestoreCurrentRowFormValues($<!--##=sPageObj##-->->RowIndex); // Restore form values

<!--##
	}
##-->

<!--##
	if (bInlineEdit || bGridEdit) {
##-->
	<!--## if (bInlineEdit) { ##-->
		if ($<!--##=gsTblVar##-->->CurrentAction == "edit") {
			if ($<!--##=sPageObj##-->->CheckInlineEditKey() && $<!--##=sPageObj##-->->EditRowCnt == 0) { // Inline edit
				$<!--##=gsTblVar##-->->RowType = EW_ROWTYPE_EDIT; // Render edit
	<!--## if (bTblCheckConcurrentUpdate) { ##-->
				if (!$<!--##=gsTblVar##-->->EventCancelled)
					$<!--##=sPageObj##-->->HashValue = $<!--##=sPageObj##-->->GetRowHash($<!--##=sPageObj##-->->Recordset); // Get hash value for record
	<!--## } ##-->
			}
		}
	<!--## } ##-->
	<!--## if (bGridEdit) { ##-->
		if ($<!--##=gsTblVar##-->->CurrentAction == "gridedit") { // Grid edit
			if ($<!--##=gsTblVar##-->->EventCancelled) {
				$<!--##=sPageObj##-->->RestoreCurrentRowFormValues($<!--##=sPageObj##-->->RowIndex); // Restore form values
			}
			if ($<!--##=sPageObj##-->->RowAction == "insert")
				$<!--##=gsTblVar##-->->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$<!--##=gsTblVar##-->->RowType = EW_ROWTYPE_EDIT; // Render edit
		<!--## if (bTblCheckConcurrentUpdate) { ##-->
			if (!$<!--##=gsTblVar##-->->EventCancelled)
				$<!--##=sPageObj##-->->HashValue = $<!--##=sPageObj##-->->GetRowHash($<!--##=sPageObj##-->->Recordset); // Get hash value for record
		<!--## } ##-->
		}
	<!--## } ##-->

<!--## if (bInlineEdit) { ##-->
		if ($<!--##=gsTblVar##-->->CurrentAction == "edit" && $<!--##=gsTblVar##-->->RowType == EW_ROWTYPE_EDIT && $<!--##=gsTblVar##-->->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$<!--##=sPageObj##-->->RestoreFormValues(); // Restore form values
		}
<!--## } ##-->
<!--## if (bGridEdit) { ##-->
		if ($<!--##=gsTblVar##-->->CurrentAction == "gridedit" && ($<!--##=gsTblVar##-->->RowType == EW_ROWTYPE_EDIT || $<!--##=gsTblVar##-->->RowType == EW_ROWTYPE_ADD) && $<!--##=gsTblVar##-->->EventCancelled) // Update failed
			$<!--##=sPageObj##-->->RestoreCurrentRowFormValues($<!--##=sPageObj##-->->RowIndex); // Restore form values
<!--## } ##-->

		if ($<!--##=gsTblVar##-->->RowType == EW_ROWTYPE_EDIT) // Edit row
			$<!--##=sPageObj##-->->EditRowCnt++;

<!--## if (CTRL.CtrlID == "grid") { ##-->
		if ($<!--##=gsTblVar##-->->CurrentAction == "F") // Confirm row
			$<!--##=sPageObj##-->->RestoreCurrentRowFormValues($<!--##=sPageObj##-->->RowIndex); // Restore form values
<!--## } ##-->

<!--##
	}
##-->

		// Set up row id / data-rowindex
		$<!--##=gsTblVar##-->->RowAttrs = array_merge($<!--##=gsTblVar##-->->RowAttrs, array('data-rowindex'=>$<!--##=sPageObj##-->->RowCnt, 'id'=>'r' . $<!--##=sPageObj##-->->RowCnt . '_<!--##=gsTblVar##-->', 'data-rowtype'=>$<!--##=gsTblVar##-->->RowType));

		// Render row
		$<!--##=sPageObj##-->->RenderRow();

		// Render list options
		$<!--##=sPageObj##-->->RenderListOptions();

<!--## if (bUseCustomTemplate) { ##-->
		// Save row and cell attributes
		$<!--##=sPageObj##-->->Attrs[$<!--##=sPageObj##-->->RowCnt] = array("row_attrs" => $<!--##=gsTblVar##-->->RowAttributes(), "cell_attrs" => array());
		foreach ($<!--##=sPageObj##-->->fields as $fld)
			$<!--##=sPageObj##-->->Attrs[$<!--##=sPageObj##-->->RowCnt]["cell_attrs"][substr($fld->FldVar, 2)] = $fld->CellAttributes();
<!--## } ##-->

<!--## if (CTRL.CtrlID == "grid" || (CTRL.CtrlID == "list" && (bGridAdd || bGridEdit))) { ##-->
		// Skip delete row / empty row for confirm page
		if ($<!--##=sPageObj##-->->RowAction <> "delete" && $<!--##=sPageObj##-->->RowAction <> "insertdelete" && !($<!--##=sPageObj##-->->RowAction == "insert" && $<!--##=gsTblVar##-->->CurrentAction == "F" && $<!--##=sPageObj##-->->EmptyRow())) {
<!--## } ##-->
?>

<!--## if (iRecPerRow < 1) { // Normal layout ##-->

	<tr<?php echo $<!--##=gsTblVar##-->->RowAttributes() ?>>

<?php
// Render list options (body, left)
<!--## if (bUseCustomTemplate) { ##-->
$<!--##=sPageObj##-->->ListOptions->Render("body", "", $<!--##=sPageObj##-->->RowCnt, "<!--##=sCustomListOptionsBody##-->", $<!--##=gsTblVar##-->->TableVar, "<!--##=sTemplateClass##-->");
<!--## } else { ##-->
$<!--##=sPageObj##-->->ListOptions->Render("body", "left", $<!--##=sPageObj##-->->RowCnt);
<!--## } ##-->
?>

	<!--##
	sNameTagVar = "$" + sPageObj + "->PageObjName . \"_row_\" . $" + sPageObj + "->RowCnt";
	sNameTag = "<a id=\"<?php echo " + sNameTagVar + " ?>\"></a>";
	sHiddenTags = "";
	// Generate primary key fields for inline edit/grid edit
	if (gbTblListEdit) {
		for (var i = 0; i < nKeyCount; i++) {
			if (!InArray(arFlds, arKeyFlds[i])) {
				if (GetFldObj(arKeyFlds[i])) {
					sHiddenTags += SYSTEMFUNCTIONS.FieldList();
				}
			}
		} // KeyField
	}
	##-->

	<!--##
		bWriteHiddenTags = true;
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				if (!bWriteHiddenTags) {
					sHiddenTags = "";
					sNameTag = "";
				}
	##-->
	<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=gsFldName##--> ?>
		<td data-name="<!--##=gsFldParm##-->"<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##~SYSTEMFUNCTIONS.FieldList()##--><!--##=sNameTag##--></td>
	<?php } ?>
<!--##=sHiddenTags##-->
	<!--##
				bWriteHiddenTags = false;
			}
		} // Field
	##-->
	
<!--## if (!bUseCustomTemplate) { ##-->
<?php
// Render list options (body, right)
$<!--##=sPageObj##-->->ListOptions->Render("body", "right", $<!--##=sPageObj##-->->RowCnt);
?>
<!--## } ##-->

	</tr>

<!--## } else { // Multi-Column layout ##-->

<?php echo $<!--##=sPageObj##-->->MultiColumnBeginGrid() ?>
<div class="<?php echo $<!--##=sPageObj##-->->MultiColumnClass ?>"<?php echo $<!--##=gsTblVar##-->->RowAttributes() ?>>
	<?php if ($<!--##=gsTblVar##-->->RowType == EW_ROWTYPE_VIEW) { // View record ?>
	<table class="table table-bordered table-striped">
	<?php } else { // Add/edit record ?>
	<div>
	<?php } ?>
	<!--##
		rowcnt = 0;
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				rowcnt += 1;
				if (CTRL.CtrlID == "list" && bBasicSearch && goFld.FldBasicSearch &&
					(goFld.FldHtmlTag == "NO" || goFld.FldHtmlTag == "TEXT" || goFld.FldHtmlTag == "TEXTAREA" ||
					goFld.NativeDataType == 247 || goFld.NativeDataType == 248 ||
					(goFld.FldHtmlTag == "FILE" && !ew_IsBinaryField(goFld))))
					sSrchLegend = "<?php echo $Language->Phrase(\"SrchLegend\") ?>";
				else
					sSrchLegend = "";
				if (iSortType > 0)
					sJsSort = " class=\"ewPointer\" onclick=\"ew_Sort(event,'<?php echo $" + gsTblVar + "->SortUrl($" + gsFldObj + ") ?>'," + iSortType + ");\"";
				else
					sJsSort = "";
				sClassId = gsTblVar + "_" + gsFldParm;
	##-->
	<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=gsFldName##--> ?>
		<?php if ($<!--##=gsTblVar##-->->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td<!--##=ewCSSTableHeaderClass##-->><span class="<!--##=sClassId##-->">
<?php if ($<!--##=gsTblVar##-->->Export <> "" || $<!--##=gsTblVar##-->->SortUrl($<!--##=gsFldObj##-->) == "") { ?>
				<div class="ewTableHeaderCaption"<!--##=FieldTD_Header(FIELD)##-->><!--##=SYSTEMFUNCTIONS.FieldCaption()##--></div>
<?php } else { ?>
				<div<!--##=sJsSort##-->>
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $<!--##=gsFldObj##-->->FldCaption() ?><!--##=sSrchLegend##--></span><span class="ewTableHeaderSort"><?php if ($<!--##=gsFldObj##-->->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($<!--##=gsFldObj##-->->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldList()##--></td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group <!--##=sClassId##-->">
			<label class="<!--##=ewBootstrapLabelClass##-->"><!--##=SYSTEMFUNCTIONS.FieldCaption()##--></label>
			<div class="<!--##=ewBootstrapRightColumnClass##-->"><div<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldList()##--></div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<!--##
			}
		} // Field
	##-->
	<?php if ($<!--##=gsTblVar##-->->RowType == EW_ROWTYPE_VIEW) { // View record ?>
	</table>
	<?php } else { // Add/edit record ?>
	</div>
	<?php } ?>

<div class="ewMultiColumnListOption">
<?php
// Render list options (body, bottom)
<!--## if (bUseCustomTemplate) { ##-->
$<!--##=sPageObj##-->->ListOptions->Render("body", "", $<!--##=sPageObj##-->->RowCnt, "<!--##=sCustomListOptionsBody##-->", $<!--##=gsTblVar##-->->TableVar, "<!--##=sTemplateClass##-->");
<!--## } else { ##-->
$<!--##=sPageObj##-->->ListOptions->Render("body", "", $<!--##=sPageObj##-->->RowCnt);
<!--## } ##-->
?>
</div>
<div class="clearfix"></div>

</div>

<!--## } ##-->

<!--##
	if (bGridAdd || bGridEdit || bInlineEdit) {
##-->
<?php if ($<!--##=gsTblVar##-->->RowType == EW_ROWTYPE_ADD || $<!--##=gsTblVar##-->->RowType == EW_ROWTYPE_EDIT) { ?>
<!--## if (bUseCustomTemplate) { ##-->
<script class="<!--##=sTemplateClass##-->_js" type="text/html">
<!--## } else { ##-->
<script type="text/javascript">
<!--## } ##-->
<!--##=sFormName##-->.UpdateOpts(<?php echo $<!--##=sPageObj##-->->RowIndex ?>);
</script>
<?php } ?>
<!--##
	}
##-->

<?php
	}

	<!--## if (CTRL.CtrlID == "grid" || (CTRL.CtrlID == "list" && (bGridAdd || bGridEdit))) { ##-->

	} // End delete row checking

	<!--## if (CTRL.CtrlID == "grid") { ##-->
	if ($<!--##=gsTblVar##-->->CurrentAction <> "gridadd" || $<!--##=gsTblVar##-->->CurrentMode == "copy")
		if (!$<!--##=sPageObj##-->->Recordset->EOF) $<!--##=sPageObj##-->->Recordset->MoveNext();
	<!--## } else { ##-->
	if ($<!--##=gsTblVar##-->->CurrentAction <> "gridadd")
		if (!$<!--##=sPageObj##-->->Recordset->EOF) $<!--##=sPageObj##-->->Recordset->MoveNext();
	<!--## } ##-->

	<!--## } else { ##-->

	if ($<!--##=gsTblVar##-->->CurrentAction <> "gridadd")
		$<!--##=sPageObj##-->->Recordset->MoveNext();

	<!--## } ##-->

}
?>

<!--## if (iRecPerRow < 1) { ##-->

<!--## if (!bUseCustomTemplate) { ##-->
<!--## if (CTRL.CtrlID == "grid" || (CTRL.CtrlID == "list" && (bGridAdd || bGridEdit))) { ##-->
<!--## bGenTemplateLine = true; ##-->
<!--##include list-script-inline.php/inlineaddhtml##-->
<!--## } ##-->
<!--## } ##-->

</tbody>
<!--## } ##-->

<!--## if (iRecPerRow >= 1) { // Multi-Column Layout ##-->
<?php echo $<!--##=sPageObj##-->->MultiColumnEndGrid() ?>
<!--## } ##-->

<!--## if (iRecPerRow < 1 && SYSTEMFUNCTIONS.IsAggregate() && CTRL.CtrlID == "list") { // Single Column Layout Aggregate Footer ##-->
<?php
// Render aggregate row
$<!--##=gsTblVar##-->->RowType = EW_ROWTYPE_AGGREGATE;
$<!--##=gsTblVar##-->->ResetAttrs();
$<!--##=sPageObj##-->->RenderRow();
?>
<?php if ($<!--##=sPageObj##-->->TotalRecs > 0 && ($<!--##=gsTblVar##-->->CurrentAction <> "gridadd" && $<!--##=gsTblVar##-->->CurrentAction <> "gridedit")) { ?>
<tfoot><!-- Table footer -->
	<tr<!--##=ewCSSTableFooterClass##-->>

<?php
// Render list options
$<!--##=sPageObj##-->->RenderListOptions();

// Render list options (footer, left)
<!--## if (bUseCustomTemplate) { ##-->
$<!--##=sPageObj##-->->ListOptions->Render("footer", "", "", "<!--##=sCustomListOptionsFooter##-->", $<!--##=gsTblVar##-->->TableVar, "<!--##=sTemplateClass##-->");
<!--## } else { ##-->
$<!--##=sPageObj##-->->ListOptions->Render("footer", "left");
<!--## } ##-->
?>

	<!--##
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				sFooterSpanId = "elf_" + gsTblVar + "_" + gsFldParm;
				sClassId = gsTblVar + "_" + gsFldParm;
	##-->
	<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=gsFldName##--> ?>
		<td data-name="<!--##=gsFldParm##-->"<!--##=FieldTD_Item(goFld)##-->><span id="<!--##=sFooterSpanId##-->" class="<!--##=sClassId##-->">
	<!--## if (ew_IsNotEmpty(goFld.FldAggregate)) { ##-->
		<!--##=SYSTEMFUNCTIONS.FieldAggregate()##-->
	<!--## } else { ##-->
		&nbsp;
	<!--## } ##-->
		</span></td>
	<?php } ?>
	<!--##
			}
		} // Field
	##-->

<!--## if (!bUseCustomTemplate) { ##-->
<?php
// Render list options (footer, right)
$<!--##=sPageObj##-->->ListOptions->Render("footer", "right");
?>
<!--## } ##-->

	</tr>
</tfoot>	
<?php } ?>
<!--## } ##-->

<!--## if (iRecPerRow < 1) { ##-->
</table>
<!--## } else { ##-->
<div class="clearfix"></div>
<!--## } ##-->

<!--## if (CTRL.CtrlID != "grid") { ##-->
<?php } ?>
<!--## } ##-->

<!--##
	if (bInlineAdd || bInlineCopy) {
##-->
<?php if ($<!--##=gsTblVar##-->->CurrentAction == "add" || $<!--##=gsTblVar##-->->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $<!--##=sPageObj##-->->FormKeyCountName ?>" id="<?php echo $<!--##=sPageObj##-->->FormKeyCountName ?>" value="<?php echo $<!--##=sPageObj##-->->KeyCount ?>">
<?php } ?>
<!--##
	}
##-->
<!--##
	if (bGridAdd) {
##-->
<!--## if (CTRL.CtrlID == "grid") { ##-->
<?php if ($<!--##=gsTblVar##-->->CurrentMode == "add" || $<!--##=gsTblVar##-->->CurrentMode == "copy") { ?>
<!--## } else { ##-->
<?php if ($<!--##=gsTblVar##-->->CurrentAction == "gridadd") { ?>
<!--## } ##-->
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $<!--##=sPageObj##-->->FormKeyCountName ?>" id="<?php echo $<!--##=sPageObj##-->->FormKeyCountName ?>" value="<?php echo $<!--##=sPageObj##-->->KeyCount ?>">
<?php echo $<!--##=sPageObj##-->->MultiSelectKey ?>
<?php } ?>
<!--##
	}
##-->
<!--##
	if (bInlineEdit) {
##-->
<?php if ($<!--##=gsTblVar##-->->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $<!--##=sPageObj##-->->FormKeyCountName ?>" id="<?php echo $<!--##=sPageObj##-->->FormKeyCountName ?>" value="<?php echo $<!--##=sPageObj##-->->KeyCount ?>">
<?php } ?>
<!--##
	}
##-->
<!--##
	if (bGridEdit) {
##-->
<!--## if (CTRL.CtrlID == "grid") { ##-->
<?php if ($<!--##=gsTblVar##-->->CurrentMode == "edit") { ?>
<!--## } else { ##-->
<?php if ($<!--##=gsTblVar##-->->CurrentAction == "gridedit") { ?>
<!--## } ##-->
	<!--## if (bTblCheckConcurrentUpdate) { ##-->
<?php if ($<!--##=gsTblVar##-->->UpdateConflict == "U") { // Record already updated by other user ?>
<input type="hidden" name="a_list" id="a_list" value="gridoverwrite">
<?php } else { ?>
	<!--## } ##-->
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
	<!--## if (bTblCheckConcurrentUpdate) { ##-->
<?php } ?>
	<!--## } ##-->
<input type="hidden" name="<?php echo $<!--##=sPageObj##-->->FormKeyCountName ?>" id="<?php echo $<!--##=sPageObj##-->->FormKeyCountName ?>" value="<?php echo $<!--##=sPageObj##-->->KeyCount ?>">
<?php echo $<!--##=sPageObj##-->->MultiSelectKey ?>
<?php } ?>
<!--##
	}
##-->

<!--## if (CTRL.CtrlID == "grid") { ##-->
<?php if ($<!--##=gsTblVar##-->->CurrentMode == "") { ?>
<!--## } else { ##-->
<?php if ($<!--##=gsTblVar##-->->CurrentAction == "") { ?>
<!--## } ##-->
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>

<!--## if (CTRL.CtrlID == "grid") { ##-->
<input type="hidden" name="detailpage" value="<!--##=sFormName##-->">
<!--## } ##-->

<!--## if (bUseCustomTemplate) { ##-->
<!--##~SYSTEMFUNCTIONS.GetCustomTemplate()##-->
<!--## } ##-->

<!--## if (iRecPerRow < 1) { // Single Column ##-->
</div>
<!--## } ##-->

<!--## if (CTRL.CtrlID == "list") { ##-->
</form>
<!--## } ##-->

<?php
// Close recordset
if ($<!--##=sPageObj##-->->Recordset)
	$<!--##=sPageObj##-->->Recordset->Close();
?>

<!--## if (CTRL.CtrlID == "grid") { ##-->

<!--## if (bBottomPageLink || !bTopPageLink) { ##-->
<?php if ($<!--##=sPageObj##-->->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($<!--##=sPageObj##-->->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<!--## } ##-->

</div>

<!--## } else { ##-->

<!--## if (bBottomPageLink || !bTopPageLink) { ##-->
<!--##=sExpStart##-->
<!--## if (iRecPerRow < 1) { // Single Column ##-->
<div class="panel-footer ewGridLowerPanel">
<!--## } else { ##-->
<div>
<!--## } ##-->
<!--## if (bBottomPageLink) { ##-->
<?php if ($<!--##=gsTblVar##-->->CurrentAction <> "gridadd" && $<!--##=gsTblVar##-->->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<!--##include pager.php/pager##-->
</form>
<?php } ?>
<!--## } ##-->
<div class="ewListOtherOptions">
<?php
	foreach ($<!--##=sPageObj##-->->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<!--##=sExpEnd##-->
<!--## } ##-->

<!--## } ##-->

</div>

<?php } ?>

<?php if ($<!--##=sPageObj##-->->TotalRecs == 0 && $<!--##=gsTblVar##-->->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($<!--##=sPageObj##-->->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>

<!--## if (bUseCustomTemplate) { ##-->
<!--##~SYSTEMFUNCTIONS.RenderCustomTemplate()##-->
<!--## } ##-->

<!--##=sExpStart##-->
<script type="text/javascript">
<!--## if (CTRL.CtrlID == "list" && (bBasicSearch || bExtendedBasicSearch || bAdvancedSearch)) { ##-->
<!--##=sFormNameSearch##-->.Init();
<!--##=sFormNameSearch##-->.FilterList = <?php echo $<!--##=sPageObj##-->->GetFilterList() ?>;
<!--## } ##-->
<!--##=sFormName##-->.Init();
</script>
<!--##=sExpEnd##-->

<!--##include phpcommon.php/footer-message##-->
<!--##/session##-->


<?php
<!--##session phpevents##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Form_CustomValidate")##-->
<!--## if (CTRL.CtrlID == "list") { ##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","ListOptions_Load")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","ListOptions_Rendered")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Row_CustomAction")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Page_Exporting")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Row_Export")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Page_Exported")##-->
<!--## } ##-->
<!--##/session##-->
?>