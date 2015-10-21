<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "sana_objectinfo.php" ?>
<?php include_once "sana_userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$sana_object_edit = NULL; // Initialize page object first

class csana_object_edit extends csana_object {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_object';

	// Page object name
	var $PageObjName = 'sana_object_edit';

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

		// Table object (sana_object)
		if (!isset($GLOBALS["sana_object"]) || get_class($GLOBALS["sana_object"]) == "csana_object") {
			$GLOBALS["sana_object"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_object"];
		}

		// Table object (sana_user)
		if (!isset($GLOBALS['sana_user'])) $GLOBALS['sana_user'] = new csana_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sana_object', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("sana_objectlist.php"));
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
		$this->objectID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $sana_object;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($sana_object);
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
		if (@$_GET["objectID"] <> "") {
			$this->objectID->setQueryStringValue($_GET["objectID"]);
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
		if ($this->objectID->CurrentValue == "")
			$this->Page_Terminate("sana_objectlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("sana_objectlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "sana_objectlist.php")
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
		if (!$this->objectID->FldIsDetailKey)
			$this->objectID->setFormValue($objForm->GetValue("x_objectID"));
		if (!$this->objectName->FldIsDetailKey) {
			$this->objectName->setFormValue($objForm->GetValue("x_objectName"));
		}
		if (!$this->ownerID->FldIsDetailKey) {
			$this->ownerID->setFormValue($objForm->GetValue("x_ownerID"));
		}
		if (!$this->ownerName->FldIsDetailKey) {
			$this->ownerName->setFormValue($objForm->GetValue("x_ownerName"));
		}
		if (!$this->lastName->FldIsDetailKey) {
			$this->lastName->setFormValue($objForm->GetValue("x_lastName"));
		}
		if (!$this->mobilePhone->FldIsDetailKey) {
			$this->mobilePhone->setFormValue($objForm->GetValue("x_mobilePhone"));
		}
		if (!$this->color->FldIsDetailKey) {
			$this->color->setFormValue($objForm->GetValue("x_color"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->content->FldIsDetailKey) {
			$this->content->setFormValue($objForm->GetValue("x_content"));
		}
		if (!$this->financialValue->FldIsDetailKey) {
			$this->financialValue->setFormValue($objForm->GetValue("x_financialValue"));
		}
		if (!$this->registrationUser->FldIsDetailKey) {
			$this->registrationUser->setFormValue($objForm->GetValue("x_registrationUser"));
		}
		if (!$this->registrationDateTime->FldIsDetailKey) {
			$this->registrationDateTime->setFormValue($objForm->GetValue("x_registrationDateTime"));
			$this->registrationDateTime->CurrentValue = ew_UnFormatDateTime($this->registrationDateTime->CurrentValue, 5);
		}
		if (!$this->registrationStation->FldIsDetailKey) {
			$this->registrationStation->setFormValue($objForm->GetValue("x_registrationStation"));
		}
		if (!$this->isolatedDateTime->FldIsDetailKey) {
			$this->isolatedDateTime->setFormValue($objForm->GetValue("x_isolatedDateTime"));
			$this->isolatedDateTime->CurrentValue = ew_UnFormatDateTime($this->isolatedDateTime->CurrentValue, 5);
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->objectID->CurrentValue = $this->objectID->FormValue;
		$this->objectName->CurrentValue = $this->objectName->FormValue;
		$this->ownerID->CurrentValue = $this->ownerID->FormValue;
		$this->ownerName->CurrentValue = $this->ownerName->FormValue;
		$this->lastName->CurrentValue = $this->lastName->FormValue;
		$this->mobilePhone->CurrentValue = $this->mobilePhone->FormValue;
		$this->color->CurrentValue = $this->color->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->content->CurrentValue = $this->content->FormValue;
		$this->financialValue->CurrentValue = $this->financialValue->FormValue;
		$this->registrationUser->CurrentValue = $this->registrationUser->FormValue;
		$this->registrationDateTime->CurrentValue = $this->registrationDateTime->FormValue;
		$this->registrationDateTime->CurrentValue = ew_UnFormatDateTime($this->registrationDateTime->CurrentValue, 5);
		$this->registrationStation->CurrentValue = $this->registrationStation->FormValue;
		$this->isolatedDateTime->CurrentValue = $this->isolatedDateTime->FormValue;
		$this->isolatedDateTime->CurrentValue = ew_UnFormatDateTime($this->isolatedDateTime->CurrentValue, 5);
		$this->description->CurrentValue = $this->description->FormValue;
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
		$this->objectID->setDbValue($rs->fields('objectID'));
		$this->objectName->setDbValue($rs->fields('objectName'));
		$this->ownerID->setDbValue($rs->fields('ownerID'));
		$this->ownerName->setDbValue($rs->fields('ownerName'));
		$this->lastName->setDbValue($rs->fields('lastName'));
		$this->mobilePhone->setDbValue($rs->fields('mobilePhone'));
		$this->color->setDbValue($rs->fields('color'));
		$this->status->setDbValue($rs->fields('status'));
		$this->content->setDbValue($rs->fields('content'));
		$this->financialValue->setDbValue($rs->fields('financialValue'));
		$this->registrationUser->setDbValue($rs->fields('registrationUser'));
		$this->registrationDateTime->setDbValue($rs->fields('registrationDateTime'));
		$this->registrationStation->setDbValue($rs->fields('registrationStation'));
		$this->isolatedDateTime->setDbValue($rs->fields('isolatedDateTime'));
		$this->description->setDbValue($rs->fields('description'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->objectID->DbValue = $row['objectID'];
		$this->objectName->DbValue = $row['objectName'];
		$this->ownerID->DbValue = $row['ownerID'];
		$this->ownerName->DbValue = $row['ownerName'];
		$this->lastName->DbValue = $row['lastName'];
		$this->mobilePhone->DbValue = $row['mobilePhone'];
		$this->color->DbValue = $row['color'];
		$this->status->DbValue = $row['status'];
		$this->content->DbValue = $row['content'];
		$this->financialValue->DbValue = $row['financialValue'];
		$this->registrationUser->DbValue = $row['registrationUser'];
		$this->registrationDateTime->DbValue = $row['registrationDateTime'];
		$this->registrationStation->DbValue = $row['registrationStation'];
		$this->isolatedDateTime->DbValue = $row['isolatedDateTime'];
		$this->description->DbValue = $row['description'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// objectID
		// objectName
		// ownerID
		// ownerName
		// lastName
		// mobilePhone
		// color
		// status
		// content
		// financialValue
		// registrationUser
		// registrationDateTime
		// registrationStation
		// isolatedDateTime
		// description

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// objectID
		$this->objectID->ViewValue = $this->objectID->CurrentValue;
		$this->objectID->ViewCustomAttributes = "";

		// objectName
		$this->objectName->ViewValue = $this->objectName->CurrentValue;
		$this->objectName->ViewCustomAttributes = "";

		// ownerID
		$this->ownerID->ViewValue = $this->ownerID->CurrentValue;
		$this->ownerID->ViewCustomAttributes = "";

		// ownerName
		$this->ownerName->ViewValue = $this->ownerName->CurrentValue;
		$this->ownerName->ViewCustomAttributes = "";

		// lastName
		$this->lastName->ViewValue = $this->lastName->CurrentValue;
		$this->lastName->ViewCustomAttributes = "";

		// mobilePhone
		$this->mobilePhone->ViewValue = $this->mobilePhone->CurrentValue;
		$this->mobilePhone->ViewCustomAttributes = "";

		// color
		$this->color->ViewValue = $this->color->CurrentValue;
		$this->color->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// content
		$this->content->ViewValue = $this->content->CurrentValue;
		$this->content->ViewCustomAttributes = "";

		// financialValue
		$this->financialValue->ViewValue = $this->financialValue->CurrentValue;
		$this->financialValue->ViewCustomAttributes = "";

		// registrationUser
		$this->registrationUser->ViewValue = $this->registrationUser->CurrentValue;
		$this->registrationUser->ViewCustomAttributes = "";

		// registrationDateTime
		$this->registrationDateTime->ViewValue = $this->registrationDateTime->CurrentValue;
		$this->registrationDateTime->ViewValue = ew_FormatDateTime($this->registrationDateTime->ViewValue, 5);
		$this->registrationDateTime->ViewCustomAttributes = "";

		// registrationStation
		$this->registrationStation->ViewValue = $this->registrationStation->CurrentValue;
		$this->registrationStation->ViewCustomAttributes = "";

		// isolatedDateTime
		$this->isolatedDateTime->ViewValue = $this->isolatedDateTime->CurrentValue;
		$this->isolatedDateTime->ViewValue = ew_FormatDateTime($this->isolatedDateTime->ViewValue, 5);
		$this->isolatedDateTime->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

			// objectID
			$this->objectID->LinkCustomAttributes = "";
			$this->objectID->HrefValue = "";
			$this->objectID->TooltipValue = "";

			// objectName
			$this->objectName->LinkCustomAttributes = "";
			$this->objectName->HrefValue = "";
			$this->objectName->TooltipValue = "";

			// ownerID
			$this->ownerID->LinkCustomAttributes = "";
			$this->ownerID->HrefValue = "";
			$this->ownerID->TooltipValue = "";

			// ownerName
			$this->ownerName->LinkCustomAttributes = "";
			$this->ownerName->HrefValue = "";
			$this->ownerName->TooltipValue = "";

			// lastName
			$this->lastName->LinkCustomAttributes = "";
			$this->lastName->HrefValue = "";
			$this->lastName->TooltipValue = "";

			// mobilePhone
			$this->mobilePhone->LinkCustomAttributes = "";
			$this->mobilePhone->HrefValue = "";
			$this->mobilePhone->TooltipValue = "";

			// color
			$this->color->LinkCustomAttributes = "";
			$this->color->HrefValue = "";
			$this->color->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// content
			$this->content->LinkCustomAttributes = "";
			$this->content->HrefValue = "";
			$this->content->TooltipValue = "";

			// financialValue
			$this->financialValue->LinkCustomAttributes = "";
			$this->financialValue->HrefValue = "";
			$this->financialValue->TooltipValue = "";

			// registrationUser
			$this->registrationUser->LinkCustomAttributes = "";
			$this->registrationUser->HrefValue = "";
			$this->registrationUser->TooltipValue = "";

			// registrationDateTime
			$this->registrationDateTime->LinkCustomAttributes = "";
			$this->registrationDateTime->HrefValue = "";
			$this->registrationDateTime->TooltipValue = "";

			// registrationStation
			$this->registrationStation->LinkCustomAttributes = "";
			$this->registrationStation->HrefValue = "";
			$this->registrationStation->TooltipValue = "";

			// isolatedDateTime
			$this->isolatedDateTime->LinkCustomAttributes = "";
			$this->isolatedDateTime->HrefValue = "";
			$this->isolatedDateTime->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// objectID
			$this->objectID->EditAttrs["class"] = "form-control";
			$this->objectID->EditCustomAttributes = "";
			$this->objectID->EditValue = $this->objectID->CurrentValue;
			$this->objectID->ViewCustomAttributes = "";

			// objectName
			$this->objectName->EditAttrs["class"] = "form-control";
			$this->objectName->EditCustomAttributes = "";
			$this->objectName->EditValue = ew_HtmlEncode($this->objectName->CurrentValue);
			$this->objectName->PlaceHolder = ew_RemoveHtml($this->objectName->FldCaption());

			// ownerID
			$this->ownerID->EditAttrs["class"] = "form-control";
			$this->ownerID->EditCustomAttributes = "";
			$this->ownerID->EditValue = ew_HtmlEncode($this->ownerID->CurrentValue);
			$this->ownerID->PlaceHolder = ew_RemoveHtml($this->ownerID->FldCaption());

			// ownerName
			$this->ownerName->EditAttrs["class"] = "form-control";
			$this->ownerName->EditCustomAttributes = "";
			$this->ownerName->EditValue = ew_HtmlEncode($this->ownerName->CurrentValue);
			$this->ownerName->PlaceHolder = ew_RemoveHtml($this->ownerName->FldCaption());

			// lastName
			$this->lastName->EditAttrs["class"] = "form-control";
			$this->lastName->EditCustomAttributes = "";
			$this->lastName->EditValue = ew_HtmlEncode($this->lastName->CurrentValue);
			$this->lastName->PlaceHolder = ew_RemoveHtml($this->lastName->FldCaption());

			// mobilePhone
			$this->mobilePhone->EditAttrs["class"] = "form-control";
			$this->mobilePhone->EditCustomAttributes = "";
			$this->mobilePhone->EditValue = ew_HtmlEncode($this->mobilePhone->CurrentValue);
			$this->mobilePhone->PlaceHolder = ew_RemoveHtml($this->mobilePhone->FldCaption());

			// color
			$this->color->EditAttrs["class"] = "form-control";
			$this->color->EditCustomAttributes = "";
			$this->color->EditValue = ew_HtmlEncode($this->color->CurrentValue);
			$this->color->PlaceHolder = ew_RemoveHtml($this->color->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// content
			$this->content->EditAttrs["class"] = "form-control";
			$this->content->EditCustomAttributes = "";
			$this->content->EditValue = ew_HtmlEncode($this->content->CurrentValue);
			$this->content->PlaceHolder = ew_RemoveHtml($this->content->FldCaption());

			// financialValue
			$this->financialValue->EditAttrs["class"] = "form-control";
			$this->financialValue->EditCustomAttributes = "";
			$this->financialValue->EditValue = ew_HtmlEncode($this->financialValue->CurrentValue);
			$this->financialValue->PlaceHolder = ew_RemoveHtml($this->financialValue->FldCaption());

			// registrationUser
			$this->registrationUser->EditAttrs["class"] = "form-control";
			$this->registrationUser->EditCustomAttributes = "";
			$this->registrationUser->EditValue = ew_HtmlEncode($this->registrationUser->CurrentValue);
			$this->registrationUser->PlaceHolder = ew_RemoveHtml($this->registrationUser->FldCaption());

			// registrationDateTime
			$this->registrationDateTime->EditAttrs["class"] = "form-control";
			$this->registrationDateTime->EditCustomAttributes = "";
			$this->registrationDateTime->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->registrationDateTime->CurrentValue, 5));
			$this->registrationDateTime->PlaceHolder = ew_RemoveHtml($this->registrationDateTime->FldCaption());

			// registrationStation
			$this->registrationStation->EditAttrs["class"] = "form-control";
			$this->registrationStation->EditCustomAttributes = "";
			$this->registrationStation->EditValue = ew_HtmlEncode($this->registrationStation->CurrentValue);
			$this->registrationStation->PlaceHolder = ew_RemoveHtml($this->registrationStation->FldCaption());

			// isolatedDateTime
			$this->isolatedDateTime->EditAttrs["class"] = "form-control";
			$this->isolatedDateTime->EditCustomAttributes = "";
			$this->isolatedDateTime->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->isolatedDateTime->CurrentValue, 5));
			$this->isolatedDateTime->PlaceHolder = ew_RemoveHtml($this->isolatedDateTime->FldCaption());

			// description
			$this->description->EditAttrs["class"] = "form-control";
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);
			$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

			// Edit refer script
			// objectID

			$this->objectID->LinkCustomAttributes = "";
			$this->objectID->HrefValue = "";

			// objectName
			$this->objectName->LinkCustomAttributes = "";
			$this->objectName->HrefValue = "";

			// ownerID
			$this->ownerID->LinkCustomAttributes = "";
			$this->ownerID->HrefValue = "";

			// ownerName
			$this->ownerName->LinkCustomAttributes = "";
			$this->ownerName->HrefValue = "";

			// lastName
			$this->lastName->LinkCustomAttributes = "";
			$this->lastName->HrefValue = "";

			// mobilePhone
			$this->mobilePhone->LinkCustomAttributes = "";
			$this->mobilePhone->HrefValue = "";

			// color
			$this->color->LinkCustomAttributes = "";
			$this->color->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// content
			$this->content->LinkCustomAttributes = "";
			$this->content->HrefValue = "";

			// financialValue
			$this->financialValue->LinkCustomAttributes = "";
			$this->financialValue->HrefValue = "";

			// registrationUser
			$this->registrationUser->LinkCustomAttributes = "";
			$this->registrationUser->HrefValue = "";

			// registrationDateTime
			$this->registrationDateTime->LinkCustomAttributes = "";
			$this->registrationDateTime->HrefValue = "";

			// registrationStation
			$this->registrationStation->LinkCustomAttributes = "";
			$this->registrationStation->HrefValue = "";

			// isolatedDateTime
			$this->isolatedDateTime->LinkCustomAttributes = "";
			$this->isolatedDateTime->HrefValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
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
		if (!$this->objectName->FldIsDetailKey && !is_null($this->objectName->FormValue) && $this->objectName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->objectName->FldCaption(), $this->objectName->ReqErrMsg));
		}
		if (!$this->ownerID->FldIsDetailKey && !is_null($this->ownerID->FormValue) && $this->ownerID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ownerID->FldCaption(), $this->ownerID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->ownerID->FormValue)) {
			ew_AddMessage($gsFormError, $this->ownerID->FldErrMsg());
		}
		if (!$this->ownerName->FldIsDetailKey && !is_null($this->ownerName->FormValue) && $this->ownerName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ownerName->FldCaption(), $this->ownerName->ReqErrMsg));
		}
		if (!$this->lastName->FldIsDetailKey && !is_null($this->lastName->FormValue) && $this->lastName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lastName->FldCaption(), $this->lastName->ReqErrMsg));
		}
		if (!$this->color->FldIsDetailKey && !is_null($this->color->FormValue) && $this->color->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->color->FldCaption(), $this->color->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->registrationUser->FormValue)) {
			ew_AddMessage($gsFormError, $this->registrationUser->FldErrMsg());
		}
		if (!ew_CheckDate($this->registrationDateTime->FormValue)) {
			ew_AddMessage($gsFormError, $this->registrationDateTime->FldErrMsg());
		}
		if (!ew_CheckInteger($this->registrationStation->FormValue)) {
			ew_AddMessage($gsFormError, $this->registrationStation->FldErrMsg());
		}
		if (!ew_CheckDate($this->isolatedDateTime->FormValue)) {
			ew_AddMessage($gsFormError, $this->isolatedDateTime->FldErrMsg());
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

			// objectName
			$this->objectName->SetDbValueDef($rsnew, $this->objectName->CurrentValue, "", $this->objectName->ReadOnly);

			// ownerID
			$this->ownerID->SetDbValueDef($rsnew, $this->ownerID->CurrentValue, 0, $this->ownerID->ReadOnly);

			// ownerName
			$this->ownerName->SetDbValueDef($rsnew, $this->ownerName->CurrentValue, "", $this->ownerName->ReadOnly);

			// lastName
			$this->lastName->SetDbValueDef($rsnew, $this->lastName->CurrentValue, "", $this->lastName->ReadOnly);

			// mobilePhone
			$this->mobilePhone->SetDbValueDef($rsnew, $this->mobilePhone->CurrentValue, NULL, $this->mobilePhone->ReadOnly);

			// color
			$this->color->SetDbValueDef($rsnew, $this->color->CurrentValue, "", $this->color->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, $this->status->ReadOnly);

			// content
			$this->content->SetDbValueDef($rsnew, $this->content->CurrentValue, NULL, $this->content->ReadOnly);

			// financialValue
			$this->financialValue->SetDbValueDef($rsnew, $this->financialValue->CurrentValue, NULL, $this->financialValue->ReadOnly);

			// registrationUser
			$this->registrationUser->SetDbValueDef($rsnew, $this->registrationUser->CurrentValue, NULL, $this->registrationUser->ReadOnly);

			// registrationDateTime
			$this->registrationDateTime->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->registrationDateTime->CurrentValue, 5), NULL, $this->registrationDateTime->ReadOnly);

			// registrationStation
			$this->registrationStation->SetDbValueDef($rsnew, $this->registrationStation->CurrentValue, NULL, $this->registrationStation->ReadOnly);

			// isolatedDateTime
			$this->isolatedDateTime->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->isolatedDateTime->CurrentValue, 5), NULL, $this->isolatedDateTime->ReadOnly);

			// description
			$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, $this->description->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sana_objectlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($sana_object_edit)) $sana_object_edit = new csana_object_edit();

// Page init
$sana_object_edit->Page_Init();

// Page main
$sana_object_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_object_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fsana_objectedit = new ew_Form("fsana_objectedit", "edit");

// Validate form
fsana_objectedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_objectName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_object->objectName->FldCaption(), $sana_object->objectName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ownerID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_object->ownerID->FldCaption(), $sana_object->ownerID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ownerID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_object->ownerID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ownerName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_object->ownerName->FldCaption(), $sana_object->ownerName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lastName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_object->lastName->FldCaption(), $sana_object->lastName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_color");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_object->color->FldCaption(), $sana_object->color->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_registrationUser");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_object->registrationUser->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_registrationDateTime");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_object->registrationDateTime->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_registrationStation");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_object->registrationStation->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_isolatedDateTime");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_object->isolatedDateTime->FldErrMsg()) ?>");

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
fsana_objectedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_objectedit.ValidateRequired = true;
<?php } else { ?>
fsana_objectedit.ValidateRequired = false; 
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
<?php $sana_object_edit->ShowPageHeader(); ?>
<?php
$sana_object_edit->ShowMessage();
?>
<form name="fsana_objectedit" id="fsana_objectedit" class="<?php echo $sana_object_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_object_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_object_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_object">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($sana_object->objectID->Visible) { // objectID ?>
	<div id="r_objectID" class="form-group">
		<label id="elh_sana_object_objectID" class="col-sm-2 control-label ewLabel"><?php echo $sana_object->objectID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_object->objectID->CellAttributes() ?>>
<span id="el_sana_object_objectID">
<span<?php echo $sana_object->objectID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sana_object->objectID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="sana_object" data-field="x_objectID" name="x_objectID" id="x_objectID" value="<?php echo ew_HtmlEncode($sana_object->objectID->CurrentValue) ?>">
<?php echo $sana_object->objectID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_object->objectName->Visible) { // objectName ?>
	<div id="r_objectName" class="form-group">
		<label id="elh_sana_object_objectName" for="x_objectName" class="col-sm-2 control-label ewLabel"><?php echo $sana_object->objectName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_object->objectName->CellAttributes() ?>>
<span id="el_sana_object_objectName">
<input type="text" data-table="sana_object" data-field="x_objectName" name="x_objectName" id="x_objectName" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_object->objectName->getPlaceHolder()) ?>" value="<?php echo $sana_object->objectName->EditValue ?>"<?php echo $sana_object->objectName->EditAttributes() ?>>
</span>
<?php echo $sana_object->objectName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_object->ownerID->Visible) { // ownerID ?>
	<div id="r_ownerID" class="form-group">
		<label id="elh_sana_object_ownerID" for="x_ownerID" class="col-sm-2 control-label ewLabel"><?php echo $sana_object->ownerID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_object->ownerID->CellAttributes() ?>>
<span id="el_sana_object_ownerID">
<input type="text" data-table="sana_object" data-field="x_ownerID" name="x_ownerID" id="x_ownerID" size="30" placeholder="<?php echo ew_HtmlEncode($sana_object->ownerID->getPlaceHolder()) ?>" value="<?php echo $sana_object->ownerID->EditValue ?>"<?php echo $sana_object->ownerID->EditAttributes() ?>>
</span>
<?php echo $sana_object->ownerID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_object->ownerName->Visible) { // ownerName ?>
	<div id="r_ownerName" class="form-group">
		<label id="elh_sana_object_ownerName" for="x_ownerName" class="col-sm-2 control-label ewLabel"><?php echo $sana_object->ownerName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_object->ownerName->CellAttributes() ?>>
<span id="el_sana_object_ownerName">
<input type="text" data-table="sana_object" data-field="x_ownerName" name="x_ownerName" id="x_ownerName" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_object->ownerName->getPlaceHolder()) ?>" value="<?php echo $sana_object->ownerName->EditValue ?>"<?php echo $sana_object->ownerName->EditAttributes() ?>>
</span>
<?php echo $sana_object->ownerName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_object->lastName->Visible) { // lastName ?>
	<div id="r_lastName" class="form-group">
		<label id="elh_sana_object_lastName" for="x_lastName" class="col-sm-2 control-label ewLabel"><?php echo $sana_object->lastName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_object->lastName->CellAttributes() ?>>
<span id="el_sana_object_lastName">
<input type="text" data-table="sana_object" data-field="x_lastName" name="x_lastName" id="x_lastName" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_object->lastName->getPlaceHolder()) ?>" value="<?php echo $sana_object->lastName->EditValue ?>"<?php echo $sana_object->lastName->EditAttributes() ?>>
</span>
<?php echo $sana_object->lastName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_object->mobilePhone->Visible) { // mobilePhone ?>
	<div id="r_mobilePhone" class="form-group">
		<label id="elh_sana_object_mobilePhone" for="x_mobilePhone" class="col-sm-2 control-label ewLabel"><?php echo $sana_object->mobilePhone->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_object->mobilePhone->CellAttributes() ?>>
<span id="el_sana_object_mobilePhone">
<input type="text" data-table="sana_object" data-field="x_mobilePhone" name="x_mobilePhone" id="x_mobilePhone" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($sana_object->mobilePhone->getPlaceHolder()) ?>" value="<?php echo $sana_object->mobilePhone->EditValue ?>"<?php echo $sana_object->mobilePhone->EditAttributes() ?>>
</span>
<?php echo $sana_object->mobilePhone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_object->color->Visible) { // color ?>
	<div id="r_color" class="form-group">
		<label id="elh_sana_object_color" for="x_color" class="col-sm-2 control-label ewLabel"><?php echo $sana_object->color->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_object->color->CellAttributes() ?>>
<span id="el_sana_object_color">
<input type="text" data-table="sana_object" data-field="x_color" name="x_color" id="x_color" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($sana_object->color->getPlaceHolder()) ?>" value="<?php echo $sana_object->color->EditValue ?>"<?php echo $sana_object->color->EditAttributes() ?>>
</span>
<?php echo $sana_object->color->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_object->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_sana_object_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $sana_object->status->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_object->status->CellAttributes() ?>>
<span id="el_sana_object_status">
<input type="text" data-table="sana_object" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_object->status->getPlaceHolder()) ?>" value="<?php echo $sana_object->status->EditValue ?>"<?php echo $sana_object->status->EditAttributes() ?>>
</span>
<?php echo $sana_object->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_object->content->Visible) { // content ?>
	<div id="r_content" class="form-group">
		<label id="elh_sana_object_content" for="x_content" class="col-sm-2 control-label ewLabel"><?php echo $sana_object->content->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_object->content->CellAttributes() ?>>
<span id="el_sana_object_content">
<input type="text" data-table="sana_object" data-field="x_content" name="x_content" id="x_content" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_object->content->getPlaceHolder()) ?>" value="<?php echo $sana_object->content->EditValue ?>"<?php echo $sana_object->content->EditAttributes() ?>>
</span>
<?php echo $sana_object->content->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_object->financialValue->Visible) { // financialValue ?>
	<div id="r_financialValue" class="form-group">
		<label id="elh_sana_object_financialValue" for="x_financialValue" class="col-sm-2 control-label ewLabel"><?php echo $sana_object->financialValue->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_object->financialValue->CellAttributes() ?>>
<span id="el_sana_object_financialValue">
<input type="text" data-table="sana_object" data-field="x_financialValue" name="x_financialValue" id="x_financialValue" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_object->financialValue->getPlaceHolder()) ?>" value="<?php echo $sana_object->financialValue->EditValue ?>"<?php echo $sana_object->financialValue->EditAttributes() ?>>
</span>
<?php echo $sana_object->financialValue->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_object->registrationUser->Visible) { // registrationUser ?>
	<div id="r_registrationUser" class="form-group">
		<label id="elh_sana_object_registrationUser" for="x_registrationUser" class="col-sm-2 control-label ewLabel"><?php echo $sana_object->registrationUser->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_object->registrationUser->CellAttributes() ?>>
<span id="el_sana_object_registrationUser">
<input type="text" data-table="sana_object" data-field="x_registrationUser" name="x_registrationUser" id="x_registrationUser" size="30" placeholder="<?php echo ew_HtmlEncode($sana_object->registrationUser->getPlaceHolder()) ?>" value="<?php echo $sana_object->registrationUser->EditValue ?>"<?php echo $sana_object->registrationUser->EditAttributes() ?>>
</span>
<?php echo $sana_object->registrationUser->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_object->registrationDateTime->Visible) { // registrationDateTime ?>
	<div id="r_registrationDateTime" class="form-group">
		<label id="elh_sana_object_registrationDateTime" for="x_registrationDateTime" class="col-sm-2 control-label ewLabel"><?php echo $sana_object->registrationDateTime->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_object->registrationDateTime->CellAttributes() ?>>
<span id="el_sana_object_registrationDateTime">
<input type="text" data-table="sana_object" data-field="x_registrationDateTime" data-format="5" name="x_registrationDateTime" id="x_registrationDateTime" placeholder="<?php echo ew_HtmlEncode($sana_object->registrationDateTime->getPlaceHolder()) ?>" value="<?php echo $sana_object->registrationDateTime->EditValue ?>"<?php echo $sana_object->registrationDateTime->EditAttributes() ?>>
</span>
<?php echo $sana_object->registrationDateTime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_object->registrationStation->Visible) { // registrationStation ?>
	<div id="r_registrationStation" class="form-group">
		<label id="elh_sana_object_registrationStation" for="x_registrationStation" class="col-sm-2 control-label ewLabel"><?php echo $sana_object->registrationStation->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_object->registrationStation->CellAttributes() ?>>
<span id="el_sana_object_registrationStation">
<input type="text" data-table="sana_object" data-field="x_registrationStation" name="x_registrationStation" id="x_registrationStation" size="30" placeholder="<?php echo ew_HtmlEncode($sana_object->registrationStation->getPlaceHolder()) ?>" value="<?php echo $sana_object->registrationStation->EditValue ?>"<?php echo $sana_object->registrationStation->EditAttributes() ?>>
</span>
<?php echo $sana_object->registrationStation->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_object->isolatedDateTime->Visible) { // isolatedDateTime ?>
	<div id="r_isolatedDateTime" class="form-group">
		<label id="elh_sana_object_isolatedDateTime" for="x_isolatedDateTime" class="col-sm-2 control-label ewLabel"><?php echo $sana_object->isolatedDateTime->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_object->isolatedDateTime->CellAttributes() ?>>
<span id="el_sana_object_isolatedDateTime">
<input type="text" data-table="sana_object" data-field="x_isolatedDateTime" data-format="5" name="x_isolatedDateTime" id="x_isolatedDateTime" placeholder="<?php echo ew_HtmlEncode($sana_object->isolatedDateTime->getPlaceHolder()) ?>" value="<?php echo $sana_object->isolatedDateTime->EditValue ?>"<?php echo $sana_object->isolatedDateTime->EditAttributes() ?>>
</span>
<?php echo $sana_object->isolatedDateTime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_object->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label id="elh_sana_object_description" for="x_description" class="col-sm-2 control-label ewLabel"><?php echo $sana_object->description->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_object->description->CellAttributes() ?>>
<span id="el_sana_object_description">
<textarea data-table="sana_object" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($sana_object->description->getPlaceHolder()) ?>"<?php echo $sana_object->description->EditAttributes() ?>><?php echo $sana_object->description->EditValue ?></textarea>
</span>
<?php echo $sana_object->description->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sana_object_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fsana_objectedit.Init();
</script>
<?php
$sana_object_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_object_edit->Page_Terminate();
?>
