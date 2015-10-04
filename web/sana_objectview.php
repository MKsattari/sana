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

$sana_object_view = NULL; // Initialize page object first

class csana_object_view extends csana_object {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_object';

	// Page object name
	var $PageObjName = 'sana_object_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Table object (sana_object)
		if (!isset($GLOBALS["sana_object"]) || get_class($GLOBALS["sana_object"]) == "csana_object") {
			$GLOBALS["sana_object"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_object"];
		}
		$KeyUrl = "";
		if (@$_GET["objectID"] <> "") {
			$this->RecKey["objectID"] = $_GET["objectID"];
			$KeyUrl .= "&amp;objectID=" . urlencode($this->RecKey["objectID"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (sana_user)
		if (!isset($GLOBALS['sana_user'])) $GLOBALS['sana_user'] = new csana_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["objectID"] <> "") {
				$this->objectID->setQueryStringValue($_GET["objectID"]);
				$this->RecKey["objectID"] = $this->objectID->QueryStringValue;
			} elseif (@$_POST["objectID"] <> "") {
				$this->objectID->setFormValue($_POST["objectID"]);
				$this->RecKey["objectID"] = $this->objectID->FormValue;
			} else {
				$sReturnUrl = "sana_objectlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "sana_objectlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "sana_objectlist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sana_objectlist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($sana_object_view)) $sana_object_view = new csana_object_view();

// Page init
$sana_object_view->Page_Init();

// Page main
$sana_object_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_object_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fsana_objectview = new ew_Form("fsana_objectview", "view");

// Form_CustomValidate event
fsana_objectview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_objectview.ValidateRequired = true;
<?php } else { ?>
fsana_objectview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $sana_object_view->ExportOptions->Render("body") ?>
<?php
	foreach ($sana_object_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $sana_object_view->ShowPageHeader(); ?>
<?php
$sana_object_view->ShowMessage();
?>
<form name="fsana_objectview" id="fsana_objectview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_object_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_object_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_object">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($sana_object->objectID->Visible) { // objectID ?>
	<tr id="r_objectID">
		<td><span id="elh_sana_object_objectID"><?php echo $sana_object->objectID->FldCaption() ?></span></td>
		<td data-name="objectID"<?php echo $sana_object->objectID->CellAttributes() ?>>
<span id="el_sana_object_objectID">
<span<?php echo $sana_object->objectID->ViewAttributes() ?>>
<?php echo $sana_object->objectID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_object->objectName->Visible) { // objectName ?>
	<tr id="r_objectName">
		<td><span id="elh_sana_object_objectName"><?php echo $sana_object->objectName->FldCaption() ?></span></td>
		<td data-name="objectName"<?php echo $sana_object->objectName->CellAttributes() ?>>
<span id="el_sana_object_objectName">
<span<?php echo $sana_object->objectName->ViewAttributes() ?>>
<?php echo $sana_object->objectName->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_object->ownerID->Visible) { // ownerID ?>
	<tr id="r_ownerID">
		<td><span id="elh_sana_object_ownerID"><?php echo $sana_object->ownerID->FldCaption() ?></span></td>
		<td data-name="ownerID"<?php echo $sana_object->ownerID->CellAttributes() ?>>
<span id="el_sana_object_ownerID">
<span<?php echo $sana_object->ownerID->ViewAttributes() ?>>
<?php echo $sana_object->ownerID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_object->ownerName->Visible) { // ownerName ?>
	<tr id="r_ownerName">
		<td><span id="elh_sana_object_ownerName"><?php echo $sana_object->ownerName->FldCaption() ?></span></td>
		<td data-name="ownerName"<?php echo $sana_object->ownerName->CellAttributes() ?>>
<span id="el_sana_object_ownerName">
<span<?php echo $sana_object->ownerName->ViewAttributes() ?>>
<?php echo $sana_object->ownerName->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_object->lastName->Visible) { // lastName ?>
	<tr id="r_lastName">
		<td><span id="elh_sana_object_lastName"><?php echo $sana_object->lastName->FldCaption() ?></span></td>
		<td data-name="lastName"<?php echo $sana_object->lastName->CellAttributes() ?>>
<span id="el_sana_object_lastName">
<span<?php echo $sana_object->lastName->ViewAttributes() ?>>
<?php echo $sana_object->lastName->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_object->mobilePhone->Visible) { // mobilePhone ?>
	<tr id="r_mobilePhone">
		<td><span id="elh_sana_object_mobilePhone"><?php echo $sana_object->mobilePhone->FldCaption() ?></span></td>
		<td data-name="mobilePhone"<?php echo $sana_object->mobilePhone->CellAttributes() ?>>
<span id="el_sana_object_mobilePhone">
<span<?php echo $sana_object->mobilePhone->ViewAttributes() ?>>
<?php echo $sana_object->mobilePhone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_object->color->Visible) { // color ?>
	<tr id="r_color">
		<td><span id="elh_sana_object_color"><?php echo $sana_object->color->FldCaption() ?></span></td>
		<td data-name="color"<?php echo $sana_object->color->CellAttributes() ?>>
<span id="el_sana_object_color">
<span<?php echo $sana_object->color->ViewAttributes() ?>>
<?php echo $sana_object->color->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_object->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_sana_object_status"><?php echo $sana_object->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $sana_object->status->CellAttributes() ?>>
<span id="el_sana_object_status">
<span<?php echo $sana_object->status->ViewAttributes() ?>>
<?php echo $sana_object->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_object->content->Visible) { // content ?>
	<tr id="r_content">
		<td><span id="elh_sana_object_content"><?php echo $sana_object->content->FldCaption() ?></span></td>
		<td data-name="content"<?php echo $sana_object->content->CellAttributes() ?>>
<span id="el_sana_object_content">
<span<?php echo $sana_object->content->ViewAttributes() ?>>
<?php echo $sana_object->content->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_object->financialValue->Visible) { // financialValue ?>
	<tr id="r_financialValue">
		<td><span id="elh_sana_object_financialValue"><?php echo $sana_object->financialValue->FldCaption() ?></span></td>
		<td data-name="financialValue"<?php echo $sana_object->financialValue->CellAttributes() ?>>
<span id="el_sana_object_financialValue">
<span<?php echo $sana_object->financialValue->ViewAttributes() ?>>
<?php echo $sana_object->financialValue->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_object->registrationUser->Visible) { // registrationUser ?>
	<tr id="r_registrationUser">
		<td><span id="elh_sana_object_registrationUser"><?php echo $sana_object->registrationUser->FldCaption() ?></span></td>
		<td data-name="registrationUser"<?php echo $sana_object->registrationUser->CellAttributes() ?>>
<span id="el_sana_object_registrationUser">
<span<?php echo $sana_object->registrationUser->ViewAttributes() ?>>
<?php echo $sana_object->registrationUser->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_object->registrationDateTime->Visible) { // registrationDateTime ?>
	<tr id="r_registrationDateTime">
		<td><span id="elh_sana_object_registrationDateTime"><?php echo $sana_object->registrationDateTime->FldCaption() ?></span></td>
		<td data-name="registrationDateTime"<?php echo $sana_object->registrationDateTime->CellAttributes() ?>>
<span id="el_sana_object_registrationDateTime">
<span<?php echo $sana_object->registrationDateTime->ViewAttributes() ?>>
<?php echo $sana_object->registrationDateTime->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_object->registrationStation->Visible) { // registrationStation ?>
	<tr id="r_registrationStation">
		<td><span id="elh_sana_object_registrationStation"><?php echo $sana_object->registrationStation->FldCaption() ?></span></td>
		<td data-name="registrationStation"<?php echo $sana_object->registrationStation->CellAttributes() ?>>
<span id="el_sana_object_registrationStation">
<span<?php echo $sana_object->registrationStation->ViewAttributes() ?>>
<?php echo $sana_object->registrationStation->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_object->isolatedDateTime->Visible) { // isolatedDateTime ?>
	<tr id="r_isolatedDateTime">
		<td><span id="elh_sana_object_isolatedDateTime"><?php echo $sana_object->isolatedDateTime->FldCaption() ?></span></td>
		<td data-name="isolatedDateTime"<?php echo $sana_object->isolatedDateTime->CellAttributes() ?>>
<span id="el_sana_object_isolatedDateTime">
<span<?php echo $sana_object->isolatedDateTime->ViewAttributes() ?>>
<?php echo $sana_object->isolatedDateTime->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_object->description->Visible) { // description ?>
	<tr id="r_description">
		<td><span id="elh_sana_object_description"><?php echo $sana_object->description->FldCaption() ?></span></td>
		<td data-name="description"<?php echo $sana_object->description->CellAttributes() ?>>
<span id="el_sana_object_description">
<span<?php echo $sana_object->description->ViewAttributes() ?>>
<?php echo $sana_object->description->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fsana_objectview.Init();
</script>
<?php
$sana_object_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_object_view->Page_Terminate();
?>
