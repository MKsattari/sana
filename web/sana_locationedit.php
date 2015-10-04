<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "sana_locationinfo.php" ?>
<?php include_once "sana_userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$sana_location_edit = NULL; // Initialize page object first

class csana_location_edit extends csana_location {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_location';

	// Page object name
	var $PageObjName = 'sana_location_edit';

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
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
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

		// Table object (sana_location)
		if (!isset($GLOBALS["sana_location"]) || get_class($GLOBALS["sana_location"]) == "csana_location") {
			$GLOBALS["sana_location"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_location"];
		}

		// Table object (sana_user)
		if (!isset($GLOBALS['sana_user'])) $GLOBALS['sana_user'] = new csana_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sana_location', TRUE);

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
		if (!$Security->IsLoggedIn()) $this->Page_Terminate(ew_GetUrl("login.php"));
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->locationID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $sana_location;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($sana_location);
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
		if (@$_GET["locationID"] <> "") {
			$this->locationID->setQueryStringValue($_GET["locationID"]);
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
		if ($this->locationID->CurrentValue == "")
			$this->Page_Terminate("sana_locationlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("sana_locationlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "sana_locationlist.php")
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
		if (!$this->locationID->FldIsDetailKey)
			$this->locationID->setFormValue($objForm->GetValue("x_locationID"));
		if (!$this->locationClass->FldIsDetailKey) {
			$this->locationClass->setFormValue($objForm->GetValue("x_locationClass"));
		}
		if (!$this->locationName->FldIsDetailKey) {
			$this->locationName->setFormValue($objForm->GetValue("x_locationName"));
		}
		if (!$this->parentID->FldIsDetailKey) {
			$this->parentID->setFormValue($objForm->GetValue("x_parentID"));
		}
		if (!$this->hierarchyIDPath->FldIsDetailKey) {
			$this->hierarchyIDPath->setFormValue($objForm->GetValue("x_hierarchyIDPath"));
		}
		if (!$this->hierarchyPath->FldIsDetailKey) {
			$this->hierarchyPath->setFormValue($objForm->GetValue("x_hierarchyPath"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->locationID->CurrentValue = $this->locationID->FormValue;
		$this->locationClass->CurrentValue = $this->locationClass->FormValue;
		$this->locationName->CurrentValue = $this->locationName->FormValue;
		$this->parentID->CurrentValue = $this->parentID->FormValue;
		$this->hierarchyIDPath->CurrentValue = $this->hierarchyIDPath->FormValue;
		$this->hierarchyPath->CurrentValue = $this->hierarchyPath->FormValue;
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
		$this->locationID->setDbValue($rs->fields('locationID'));
		$this->locationClass->setDbValue($rs->fields('locationClass'));
		$this->locationName->setDbValue($rs->fields('locationName'));
		$this->parentID->setDbValue($rs->fields('parentID'));
		$this->hierarchyIDPath->setDbValue($rs->fields('hierarchyIDPath'));
		$this->hierarchyPath->setDbValue($rs->fields('hierarchyPath'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->locationID->DbValue = $row['locationID'];
		$this->locationClass->DbValue = $row['locationClass'];
		$this->locationName->DbValue = $row['locationName'];
		$this->parentID->DbValue = $row['parentID'];
		$this->hierarchyIDPath->DbValue = $row['hierarchyIDPath'];
		$this->hierarchyPath->DbValue = $row['hierarchyPath'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// locationID
		// locationClass
		// locationName
		// parentID
		// hierarchyIDPath
		// hierarchyPath

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// locationID
		$this->locationID->ViewValue = $this->locationID->CurrentValue;
		$this->locationID->ViewCustomAttributes = "";

		// locationClass
		$this->locationClass->ViewValue = $this->locationClass->CurrentValue;
		$this->locationClass->ViewCustomAttributes = "";

		// locationName
		$this->locationName->ViewValue = $this->locationName->CurrentValue;
		$this->locationName->ViewCustomAttributes = "";

		// parentID
		$this->parentID->ViewValue = $this->parentID->CurrentValue;
		$this->parentID->ViewCustomAttributes = "";

		// hierarchyIDPath
		$this->hierarchyIDPath->ViewValue = $this->hierarchyIDPath->CurrentValue;
		$this->hierarchyIDPath->ViewCustomAttributes = "";

		// hierarchyPath
		$this->hierarchyPath->ViewValue = $this->hierarchyPath->CurrentValue;
		$this->hierarchyPath->ViewCustomAttributes = "";

			// locationID
			$this->locationID->LinkCustomAttributes = "";
			$this->locationID->HrefValue = "";
			$this->locationID->TooltipValue = "";

			// locationClass
			$this->locationClass->LinkCustomAttributes = "";
			$this->locationClass->HrefValue = "";
			$this->locationClass->TooltipValue = "";

			// locationName
			$this->locationName->LinkCustomAttributes = "";
			$this->locationName->HrefValue = "";
			$this->locationName->TooltipValue = "";

			// parentID
			$this->parentID->LinkCustomAttributes = "";
			$this->parentID->HrefValue = "";
			$this->parentID->TooltipValue = "";

			// hierarchyIDPath
			$this->hierarchyIDPath->LinkCustomAttributes = "";
			$this->hierarchyIDPath->HrefValue = "";
			$this->hierarchyIDPath->TooltipValue = "";

			// hierarchyPath
			$this->hierarchyPath->LinkCustomAttributes = "";
			$this->hierarchyPath->HrefValue = "";
			$this->hierarchyPath->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// locationID
			$this->locationID->EditAttrs["class"] = "form-control";
			$this->locationID->EditCustomAttributes = "";
			$this->locationID->EditValue = $this->locationID->CurrentValue;
			$this->locationID->ViewCustomAttributes = "";

			// locationClass
			$this->locationClass->EditAttrs["class"] = "form-control";
			$this->locationClass->EditCustomAttributes = "";
			$this->locationClass->EditValue = ew_HtmlEncode($this->locationClass->CurrentValue);
			$this->locationClass->PlaceHolder = ew_RemoveHtml($this->locationClass->FldCaption());

			// locationName
			$this->locationName->EditAttrs["class"] = "form-control";
			$this->locationName->EditCustomAttributes = "";
			$this->locationName->EditValue = ew_HtmlEncode($this->locationName->CurrentValue);
			$this->locationName->PlaceHolder = ew_RemoveHtml($this->locationName->FldCaption());

			// parentID
			$this->parentID->EditAttrs["class"] = "form-control";
			$this->parentID->EditCustomAttributes = "";
			$this->parentID->EditValue = ew_HtmlEncode($this->parentID->CurrentValue);
			$this->parentID->PlaceHolder = ew_RemoveHtml($this->parentID->FldCaption());

			// hierarchyIDPath
			$this->hierarchyIDPath->EditAttrs["class"] = "form-control";
			$this->hierarchyIDPath->EditCustomAttributes = "";
			$this->hierarchyIDPath->EditValue = ew_HtmlEncode($this->hierarchyIDPath->CurrentValue);
			$this->hierarchyIDPath->PlaceHolder = ew_RemoveHtml($this->hierarchyIDPath->FldCaption());

			// hierarchyPath
			$this->hierarchyPath->EditAttrs["class"] = "form-control";
			$this->hierarchyPath->EditCustomAttributes = "";
			$this->hierarchyPath->EditValue = ew_HtmlEncode($this->hierarchyPath->CurrentValue);
			$this->hierarchyPath->PlaceHolder = ew_RemoveHtml($this->hierarchyPath->FldCaption());

			// Edit refer script
			// locationID

			$this->locationID->LinkCustomAttributes = "";
			$this->locationID->HrefValue = "";

			// locationClass
			$this->locationClass->LinkCustomAttributes = "";
			$this->locationClass->HrefValue = "";

			// locationName
			$this->locationName->LinkCustomAttributes = "";
			$this->locationName->HrefValue = "";

			// parentID
			$this->parentID->LinkCustomAttributes = "";
			$this->parentID->HrefValue = "";

			// hierarchyIDPath
			$this->hierarchyIDPath->LinkCustomAttributes = "";
			$this->hierarchyIDPath->HrefValue = "";

			// hierarchyPath
			$this->hierarchyPath->LinkCustomAttributes = "";
			$this->hierarchyPath->HrefValue = "";
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
		if (!$this->locationClass->FldIsDetailKey && !is_null($this->locationClass->FormValue) && $this->locationClass->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->locationClass->FldCaption(), $this->locationClass->ReqErrMsg));
		}
		if (!$this->locationName->FldIsDetailKey && !is_null($this->locationName->FormValue) && $this->locationName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->locationName->FldCaption(), $this->locationName->ReqErrMsg));
		}
		if (!$this->parentID->FldIsDetailKey && !is_null($this->parentID->FormValue) && $this->parentID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->parentID->FldCaption(), $this->parentID->ReqErrMsg));
		}
		if (!$this->hierarchyIDPath->FldIsDetailKey && !is_null($this->hierarchyIDPath->FormValue) && $this->hierarchyIDPath->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hierarchyIDPath->FldCaption(), $this->hierarchyIDPath->ReqErrMsg));
		}
		if (!$this->hierarchyPath->FldIsDetailKey && !is_null($this->hierarchyPath->FormValue) && $this->hierarchyPath->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hierarchyPath->FldCaption(), $this->hierarchyPath->ReqErrMsg));
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

			// locationClass
			$this->locationClass->SetDbValueDef($rsnew, $this->locationClass->CurrentValue, "", $this->locationClass->ReadOnly);

			// locationName
			$this->locationName->SetDbValueDef($rsnew, $this->locationName->CurrentValue, "", $this->locationName->ReadOnly);

			// parentID
			$this->parentID->SetDbValueDef($rsnew, $this->parentID->CurrentValue, "", $this->parentID->ReadOnly);

			// hierarchyIDPath
			$this->hierarchyIDPath->SetDbValueDef($rsnew, $this->hierarchyIDPath->CurrentValue, "", $this->hierarchyIDPath->ReadOnly);

			// hierarchyPath
			$this->hierarchyPath->SetDbValueDef($rsnew, $this->hierarchyPath->CurrentValue, "", $this->hierarchyPath->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sana_locationlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($sana_location_edit)) $sana_location_edit = new csana_location_edit();

// Page init
$sana_location_edit->Page_Init();

// Page main
$sana_location_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_location_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fsana_locationedit = new ew_Form("fsana_locationedit", "edit");

// Validate form
fsana_locationedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_locationClass");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_location->locationClass->FldCaption(), $sana_location->locationClass->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_locationName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_location->locationName->FldCaption(), $sana_location->locationName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_parentID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_location->parentID->FldCaption(), $sana_location->parentID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hierarchyIDPath");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_location->hierarchyIDPath->FldCaption(), $sana_location->hierarchyIDPath->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hierarchyPath");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_location->hierarchyPath->FldCaption(), $sana_location->hierarchyPath->ReqErrMsg)) ?>");

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
fsana_locationedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_locationedit.ValidateRequired = true;
<?php } else { ?>
fsana_locationedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
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
<?php $sana_location_edit->ShowPageHeader(); ?>
<?php
$sana_location_edit->ShowMessage();
?>
<form name="fsana_locationedit" id="fsana_locationedit" class="<?php echo $sana_location_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_location_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_location_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_location">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($sana_location->locationID->Visible) { // locationID ?>
	<div id="r_locationID" class="form-group">
		<label id="elh_sana_location_locationID" class="col-sm-2 control-label ewLabel"><?php echo $sana_location->locationID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_location->locationID->CellAttributes() ?>>
<span id="el_sana_location_locationID">
<span<?php echo $sana_location->locationID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sana_location->locationID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="sana_location" data-field="x_locationID" name="x_locationID" id="x_locationID" value="<?php echo ew_HtmlEncode($sana_location->locationID->CurrentValue) ?>">
<?php echo $sana_location->locationID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_location->locationClass->Visible) { // locationClass ?>
	<div id="r_locationClass" class="form-group">
		<label id="elh_sana_location_locationClass" for="x_locationClass" class="col-sm-2 control-label ewLabel"><?php echo $sana_location->locationClass->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_location->locationClass->CellAttributes() ?>>
<span id="el_sana_location_locationClass">
<input type="text" data-table="sana_location" data-field="x_locationClass" name="x_locationClass" id="x_locationClass" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_location->locationClass->getPlaceHolder()) ?>" value="<?php echo $sana_location->locationClass->EditValue ?>"<?php echo $sana_location->locationClass->EditAttributes() ?>>
</span>
<?php echo $sana_location->locationClass->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_location->locationName->Visible) { // locationName ?>
	<div id="r_locationName" class="form-group">
		<label id="elh_sana_location_locationName" for="x_locationName" class="col-sm-2 control-label ewLabel"><?php echo $sana_location->locationName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_location->locationName->CellAttributes() ?>>
<span id="el_sana_location_locationName">
<input type="text" data-table="sana_location" data-field="x_locationName" name="x_locationName" id="x_locationName" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_location->locationName->getPlaceHolder()) ?>" value="<?php echo $sana_location->locationName->EditValue ?>"<?php echo $sana_location->locationName->EditAttributes() ?>>
</span>
<?php echo $sana_location->locationName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_location->parentID->Visible) { // parentID ?>
	<div id="r_parentID" class="form-group">
		<label id="elh_sana_location_parentID" for="x_parentID" class="col-sm-2 control-label ewLabel"><?php echo $sana_location->parentID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_location->parentID->CellAttributes() ?>>
<span id="el_sana_location_parentID">
<input type="text" data-table="sana_location" data-field="x_parentID" name="x_parentID" id="x_parentID" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_location->parentID->getPlaceHolder()) ?>" value="<?php echo $sana_location->parentID->EditValue ?>"<?php echo $sana_location->parentID->EditAttributes() ?>>
</span>
<?php echo $sana_location->parentID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_location->hierarchyIDPath->Visible) { // hierarchyIDPath ?>
	<div id="r_hierarchyIDPath" class="form-group">
		<label id="elh_sana_location_hierarchyIDPath" for="x_hierarchyIDPath" class="col-sm-2 control-label ewLabel"><?php echo $sana_location->hierarchyIDPath->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_location->hierarchyIDPath->CellAttributes() ?>>
<span id="el_sana_location_hierarchyIDPath">
<input type="text" data-table="sana_location" data-field="x_hierarchyIDPath" name="x_hierarchyIDPath" id="x_hierarchyIDPath" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_location->hierarchyIDPath->getPlaceHolder()) ?>" value="<?php echo $sana_location->hierarchyIDPath->EditValue ?>"<?php echo $sana_location->hierarchyIDPath->EditAttributes() ?>>
</span>
<?php echo $sana_location->hierarchyIDPath->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_location->hierarchyPath->Visible) { // hierarchyPath ?>
	<div id="r_hierarchyPath" class="form-group">
		<label id="elh_sana_location_hierarchyPath" for="x_hierarchyPath" class="col-sm-2 control-label ewLabel"><?php echo $sana_location->hierarchyPath->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_location->hierarchyPath->CellAttributes() ?>>
<span id="el_sana_location_hierarchyPath">
<input type="text" data-table="sana_location" data-field="x_hierarchyPath" name="x_hierarchyPath" id="x_hierarchyPath" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_location->hierarchyPath->getPlaceHolder()) ?>" value="<?php echo $sana_location->hierarchyPath->EditValue ?>"<?php echo $sana_location->hierarchyPath->EditAttributes() ?>>
</span>
<?php echo $sana_location->hierarchyPath->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sana_location_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fsana_locationedit.Init();
</script>
<?php
$sana_location_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_location_edit->Page_Terminate();
?>