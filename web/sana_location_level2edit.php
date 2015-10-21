<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "sana_location_level2info.php" ?>
<?php include_once "sana_userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$sana_location_level2_edit = NULL; // Initialize page object first

class csana_location_level2_edit extends csana_location_level2 {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_location_level2';

	// Page object name
	var $PageObjName = 'sana_location_level2_edit';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<br><div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (sana_location_level2)
		if (!isset($GLOBALS["sana_location_level2"]) || get_class($GLOBALS["sana_location_level2"]) == "csana_location_level2") {
			$GLOBALS["sana_location_level2"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_location_level2"];
		}

		// Table object (sana_user)
		if (!isset($GLOBALS['sana_user'])) $GLOBALS['sana_user'] = new csana_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sana_location_level2', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (sana_user)
		if (!isset($UserTable)) {
			$UserTable = new csana_user();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("sana_location_level2list.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->locationLevel2ID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $sana_location_level2;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($sana_location_level2);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["locationLevel2ID"] <> "") {
			$this->locationLevel2ID->setQueryStringValue($_GET["locationLevel2ID"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->locationLevel2ID->CurrentValue == "")
			$this->Page_Terminate("sana_location_level2list.php"); // Invalid key, return to list

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
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("sana_location_level2list.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "sana_location_level2list.php")
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
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->locationLevel2ID->FldIsDetailKey)
			$this->locationLevel2ID->setFormValue($objForm->GetValue("x_locationLevel2ID"));
		if (!$this->locationName->FldIsDetailKey) {
			$this->locationName->setFormValue($objForm->GetValue("x_locationName"));
		}
		if (!$this->locationLevel1ID->FldIsDetailKey) {
			$this->locationLevel1ID->setFormValue($objForm->GetValue("x_locationLevel1ID"));
		}
		if (!$this->locationLevel1Name->FldIsDetailKey) {
			$this->locationLevel1Name->setFormValue($objForm->GetValue("x_locationLevel1Name"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->locationLevel2ID->CurrentValue = $this->locationLevel2ID->FormValue;
		$this->locationName->CurrentValue = $this->locationName->FormValue;
		$this->locationLevel1ID->CurrentValue = $this->locationLevel1ID->FormValue;
		$this->locationLevel1Name->CurrentValue = $this->locationLevel1Name->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->locationLevel2ID->setDbValue($rs->fields('locationLevel2ID'));
		$this->locationName->setDbValue($rs->fields('locationName'));
		$this->locationLevel1ID->setDbValue($rs->fields('locationLevel1ID'));
		$this->locationLevel1Name->setDbValue($rs->fields('locationLevel1Name'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->locationLevel2ID->DbValue = $row['locationLevel2ID'];
		$this->locationName->DbValue = $row['locationName'];
		$this->locationLevel1ID->DbValue = $row['locationLevel1ID'];
		$this->locationLevel1Name->DbValue = $row['locationLevel1Name'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// locationLevel2ID
		// locationName
		// locationLevel1ID
		// locationLevel1Name

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// locationLevel2ID
		$this->locationLevel2ID->ViewValue = $this->locationLevel2ID->CurrentValue;
		$this->locationLevel2ID->ViewCustomAttributes = "";

		// locationName
		$this->locationName->ViewValue = $this->locationName->CurrentValue;
		$this->locationName->ViewCustomAttributes = "";

		// locationLevel1ID
		if (strval($this->locationLevel1ID->CurrentValue) <> "") {
			$sFilterWrk = "`locationLevel1ID`" . ew_SearchString("=", $this->locationLevel1ID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `locationLevel1ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level1`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `locationLevel1ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level1`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `locationLevel1ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level1`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->locationLevel1ID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->locationLevel1ID->ViewValue = $this->locationLevel1ID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->locationLevel1ID->ViewValue = $this->locationLevel1ID->CurrentValue;
			}
		} else {
			$this->locationLevel1ID->ViewValue = NULL;
		}
		$this->locationLevel1ID->ViewCustomAttributes = "";

		// locationLevel1Name
		$this->locationLevel1Name->ViewValue = $this->locationLevel1Name->CurrentValue;
		$this->locationLevel1Name->ViewCustomAttributes = "";

			// locationLevel2ID
			$this->locationLevel2ID->LinkCustomAttributes = "";
			$this->locationLevel2ID->HrefValue = "";
			$this->locationLevel2ID->TooltipValue = "";

			// locationName
			$this->locationName->LinkCustomAttributes = "";
			$this->locationName->HrefValue = "";
			$this->locationName->TooltipValue = "";

			// locationLevel1ID
			$this->locationLevel1ID->LinkCustomAttributes = "";
			$this->locationLevel1ID->HrefValue = "";
			$this->locationLevel1ID->TooltipValue = "";

			// locationLevel1Name
			$this->locationLevel1Name->LinkCustomAttributes = "";
			$this->locationLevel1Name->HrefValue = "";
			$this->locationLevel1Name->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// locationLevel2ID
			$this->locationLevel2ID->EditAttrs["class"] = "form-control";
			$this->locationLevel2ID->EditCustomAttributes = "";
			$this->locationLevel2ID->EditValue = $this->locationLevel2ID->CurrentValue;
			$this->locationLevel2ID->ViewCustomAttributes = "";

			// locationName
			$this->locationName->EditAttrs["class"] = "form-control";
			$this->locationName->EditCustomAttributes = "";
			$this->locationName->EditValue = ew_HtmlEncode($this->locationName->CurrentValue);
			$this->locationName->PlaceHolder = ew_RemoveHtml($this->locationName->FldCaption());

			// locationLevel1ID
			$this->locationLevel1ID->EditAttrs["class"] = "form-control";
			$this->locationLevel1ID->EditCustomAttributes = "";
			if (trim(strval($this->locationLevel1ID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`locationLevel1ID`" . ew_SearchString("=", $this->locationLevel1ID->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `locationLevel1ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level1`";
					$sWhereWrk = "";
					break;
				case "fa":
					$sSqlWrk = "SELECT `locationLevel1ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level1`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `locationLevel1ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level1`";
					$sWhereWrk = "";
					break;
			}
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->locationLevel1ID, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->locationLevel1ID->EditValue = $arwrk;

			// locationLevel1Name
			$this->locationLevel1Name->EditAttrs["class"] = "form-control";
			$this->locationLevel1Name->EditCustomAttributes = "";

			// Edit refer script
			// locationLevel2ID

			$this->locationLevel2ID->LinkCustomAttributes = "";
			$this->locationLevel2ID->HrefValue = "";

			// locationName
			$this->locationName->LinkCustomAttributes = "";
			$this->locationName->HrefValue = "";

			// locationLevel1ID
			$this->locationLevel1ID->LinkCustomAttributes = "";
			$this->locationLevel1ID->HrefValue = "";

			// locationLevel1Name
			$this->locationLevel1Name->LinkCustomAttributes = "";
			$this->locationLevel1Name->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->locationName->FldIsDetailKey && !is_null($this->locationName->FormValue) && $this->locationName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->locationName->FldCaption(), $this->locationName->ReqErrMsg));
		}
		if (!$this->locationLevel1ID->FldIsDetailKey && !is_null($this->locationLevel1ID->FormValue) && $this->locationLevel1ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->locationLevel1ID->FldCaption(), $this->locationLevel1ID->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// locationName
			$this->locationName->SetDbValueDef($rsnew, $this->locationName->CurrentValue, "", $this->locationName->ReadOnly);

			// locationLevel1ID
			$this->locationLevel1ID->SetDbValueDef($rsnew, $this->locationLevel1ID->CurrentValue, 0, $this->locationLevel1ID->ReadOnly);

			// locationLevel1Name
			$this->locationLevel1Name->SetDbValueDef($rsnew, $this->locationLevel1Name->CurrentValue, "", $this->locationLevel1Name->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sana_location_level2list.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($sana_location_level2_edit)) $sana_location_level2_edit = new csana_location_level2_edit();

// Page init
$sana_location_level2_edit->Page_Init();

// Page main
$sana_location_level2_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_location_level2_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fsana_location_level2edit = new ew_Form("fsana_location_level2edit", "edit");

// Validate form
fsana_location_level2edit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_locationName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_location_level2->locationName->FldCaption(), $sana_location_level2->locationName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_locationLevel1ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_location_level2->locationLevel1ID->FldCaption(), $sana_location_level2->locationLevel1ID->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fsana_location_level2edit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_location_level2edit.ValidateRequired = true;
<?php } else { ?>
fsana_location_level2edit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsana_location_level2edit.Lists["x_locationLevel1ID"] = {"LinkField":"x_locationLevel1ID","Ajax":true,"AutoFill":true,"DisplayFields":["x_locationName","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $sana_location_level2_edit->ShowPageHeader(); ?>
<?php
$sana_location_level2_edit->ShowMessage();
?>
<form name="fsana_location_level2edit" id="fsana_location_level2edit" class="<?php echo $sana_location_level2_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_location_level2_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_location_level2_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_location_level2">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($sana_location_level2->locationLevel2ID->Visible) { // locationLevel2ID ?>
	<div id="r_locationLevel2ID" class="form-group">
		<label id="elh_sana_location_level2_locationLevel2ID" class="col-sm-2 control-label ewLabel"><?php echo $sana_location_level2->locationLevel2ID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_location_level2->locationLevel2ID->CellAttributes() ?>>
<span id="el_sana_location_level2_locationLevel2ID">
<span<?php echo $sana_location_level2->locationLevel2ID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sana_location_level2->locationLevel2ID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="sana_location_level2" data-field="x_locationLevel2ID" name="x_locationLevel2ID" id="x_locationLevel2ID" value="<?php echo ew_HtmlEncode($sana_location_level2->locationLevel2ID->CurrentValue) ?>">
<?php echo $sana_location_level2->locationLevel2ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_location_level2->locationName->Visible) { // locationName ?>
	<div id="r_locationName" class="form-group">
		<label id="elh_sana_location_level2_locationName" for="x_locationName" class="col-sm-2 control-label ewLabel"><?php echo $sana_location_level2->locationName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_location_level2->locationName->CellAttributes() ?>>
<span id="el_sana_location_level2_locationName">
<input type="text" data-table="sana_location_level2" data-field="x_locationName" name="x_locationName" id="x_locationName" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_location_level2->locationName->getPlaceHolder()) ?>" value="<?php echo $sana_location_level2->locationName->EditValue ?>"<?php echo $sana_location_level2->locationName->EditAttributes() ?>>
</span>
<?php echo $sana_location_level2->locationName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_location_level2->locationLevel1ID->Visible) { // locationLevel1ID ?>
	<div id="r_locationLevel1ID" class="form-group">
		<label id="elh_sana_location_level2_locationLevel1ID" for="x_locationLevel1ID" class="col-sm-2 control-label ewLabel"><?php echo $sana_location_level2->locationLevel1ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_location_level2->locationLevel1ID->CellAttributes() ?>>
<span id="el_sana_location_level2_locationLevel1ID">
<?php $sana_location_level2->locationLevel1ID->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$sana_location_level2->locationLevel1ID->EditAttrs["onchange"]; ?>
<select data-table="sana_location_level2" data-field="x_locationLevel1ID" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_location_level2->locationLevel1ID->DisplayValueSeparator) ? json_encode($sana_location_level2->locationLevel1ID->DisplayValueSeparator) : $sana_location_level2->locationLevel1ID->DisplayValueSeparator) ?>" id="x_locationLevel1ID" name="x_locationLevel1ID"<?php echo $sana_location_level2->locationLevel1ID->EditAttributes() ?>>
<?php
if (is_array($sana_location_level2->locationLevel1ID->EditValue)) {
	$arwrk = $sana_location_level2->locationLevel1ID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sana_location_level2->locationLevel1ID->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sana_location_level2->locationLevel1ID->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($sana_location_level2->locationLevel1ID->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($sana_location_level2->locationLevel1ID->CurrentValue) ?>" selected><?php echo $sana_location_level2->locationLevel1ID->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `locationLevel1ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level1`";
		$sWhereWrk = "";
		break;
	case "fa":
		$sSqlWrk = "SELECT `locationLevel1ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level1`";
		$sWhereWrk = "";
		break;
	default:
		$sSqlWrk = "SELECT `locationLevel1ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level1`";
		$sWhereWrk = "";
		break;
}
$sana_location_level2->locationLevel1ID->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sana_location_level2->locationLevel1ID->LookupFilters += array("f0" => "`locationLevel1ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$sana_location_level2->Lookup_Selecting($sana_location_level2->locationLevel1ID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sana_location_level2->locationLevel1ID->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_locationLevel1ID" id="s_x_locationLevel1ID" value="<?php echo $sana_location_level2->locationLevel1ID->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x_locationLevel1ID" id="ln_x_locationLevel1ID" value="x_locationLevel1Name">
</span>
<?php echo $sana_location_level2->locationLevel1ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<span id="el_sana_location_level2_locationLevel1Name">
<input type="hidden" data-table="sana_location_level2" data-field="x_locationLevel1Name" name="x_locationLevel1Name" id="x_locationLevel1Name" value="<?php echo ew_HtmlEncode($sana_location_level2->locationLevel1Name->CurrentValue) ?>">
</span>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sana_location_level2_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fsana_location_level2edit.Init();
</script>
<?php
$sana_location_level2_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_location_level2_edit->Page_Terminate();
?>
