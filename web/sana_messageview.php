<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "sana_messageinfo.php" ?>
<?php include_once "sana_personinfo.php" ?>
<?php include_once "sana_userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$sana_message_view = NULL; // Initialize page object first

class csana_message_view extends csana_message {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_message';

	// Page object name
	var $PageObjName = 'sana_message_view';

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

		// Table object (sana_message)
		if (!isset($GLOBALS["sana_message"]) || get_class($GLOBALS["sana_message"]) == "csana_message") {
			$GLOBALS["sana_message"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_message"];
		}
		$KeyUrl = "";
		if (@$_GET["messageID"] <> "") {
			$this->RecKey["messageID"] = $_GET["messageID"];
			$KeyUrl .= "&amp;messageID=" . urlencode($this->RecKey["messageID"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (sana_person)
		if (!isset($GLOBALS['sana_person'])) $GLOBALS['sana_person'] = new csana_person();

		// Table object (sana_user)
		if (!isset($GLOBALS['sana_user'])) $GLOBALS['sana_user'] = new csana_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sana_message', TRUE);

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
		if (!$Security->CanView()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("sana_messagelist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->messageID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $sana_message;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($sana_message);
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

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["messageID"] <> "") {
				$this->messageID->setQueryStringValue($_GET["messageID"]);
				$this->RecKey["messageID"] = $this->messageID->QueryStringValue;
			} elseif (@$_POST["messageID"] <> "") {
				$this->messageID->setFormValue($_POST["messageID"]);
				$this->RecKey["messageID"] = $this->messageID->FormValue;
			} else {
				$sReturnUrl = "sana_messagelist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "sana_messagelist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "sana_messagelist.php"; // Not page request, return to list
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
		$this->messageID->setDbValue($rs->fields('messageID'));
		$this->personID->setDbValue($rs->fields('personID'));
		$this->_userID->setDbValue($rs->fields('userID'));
		$this->messageType->setDbValue($rs->fields('messageType'));
		$this->messageText->setDbValue($rs->fields('messageText'));
		$this->stationID->setDbValue($rs->fields('stationID'));
		$this->messageDateTime->setDbValue($rs->fields('messageDateTime'));
		$this->registrationUser->setDbValue($rs->fields('registrationUser'));
		$this->registrationDateTime->setDbValue($rs->fields('registrationDateTime'));
		$this->registrationStation->setDbValue($rs->fields('registrationStation'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->messageID->DbValue = $row['messageID'];
		$this->personID->DbValue = $row['personID'];
		$this->_userID->DbValue = $row['userID'];
		$this->messageType->DbValue = $row['messageType'];
		$this->messageText->DbValue = $row['messageText'];
		$this->stationID->DbValue = $row['stationID'];
		$this->messageDateTime->DbValue = $row['messageDateTime'];
		$this->registrationUser->DbValue = $row['registrationUser'];
		$this->registrationDateTime->DbValue = $row['registrationDateTime'];
		$this->registrationStation->DbValue = $row['registrationStation'];
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
		// messageID
		// personID
		// userID
		// messageType
		// messageText
		// stationID
		// messageDateTime
		// registrationUser
		// registrationDateTime
		// registrationStation

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// messageID
		$this->messageID->ViewValue = $this->messageID->CurrentValue;
		$this->messageID->ViewCustomAttributes = "";

		// personID
		$this->personID->ViewValue = $this->personID->CurrentValue;
		if (strval($this->personID->CurrentValue) <> "") {
			$sFilterWrk = "`personID`" . ew_SearchString("=", $this->personID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_person`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_person`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_person`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->personID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->personID->ViewValue = $this->personID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->personID->ViewValue = $this->personID->CurrentValue;
			}
		} else {
			$this->personID->ViewValue = NULL;
		}
		$this->personID->ViewCustomAttributes = "";

		// userID
		$this->_userID->ViewValue = $this->_userID->CurrentValue;
		if (strval($this->_userID->CurrentValue) <> "") {
			$sFilterWrk = "`userID`" . ew_SearchString("=", $this->_userID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_user`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_user`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_user`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->_userID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->_userID->ViewValue = $this->_userID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->_userID->ViewValue = $this->_userID->CurrentValue;
			}
		} else {
			$this->_userID->ViewValue = NULL;
		}
		$this->_userID->ViewCustomAttributes = "";

		// messageType
		$this->messageType->ViewValue = $this->messageType->CurrentValue;
		$this->messageType->ViewCustomAttributes = "";

		// messageText
		$this->messageText->ViewValue = $this->messageText->CurrentValue;
		$this->messageText->ViewCustomAttributes = "";

		// stationID
		$this->stationID->ViewValue = $this->stationID->CurrentValue;
		if (strval($this->stationID->CurrentValue) <> "") {
			$sFilterWrk = "`stationID`" . ew_SearchString("=", $this->stationID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `stationID`, `stationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_station`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `stationID`, `stationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_station`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `stationID`, `stationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_station`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->stationID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->stationID->ViewValue = $this->stationID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->stationID->ViewValue = $this->stationID->CurrentValue;
			}
		} else {
			$this->stationID->ViewValue = NULL;
		}
		$this->stationID->ViewCustomAttributes = "";

		// messageDateTime
		$this->messageDateTime->ViewValue = $this->messageDateTime->CurrentValue;
		$this->messageDateTime->ViewValue = ew_FormatDateTime($this->messageDateTime->ViewValue, 5);
		$this->messageDateTime->ViewCustomAttributes = "";

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

			// messageID
			$this->messageID->LinkCustomAttributes = "";
			$this->messageID->HrefValue = "";
			$this->messageID->TooltipValue = "";

			// personID
			$this->personID->LinkCustomAttributes = "";
			$this->personID->HrefValue = "";
			$this->personID->TooltipValue = "";

			// userID
			$this->_userID->LinkCustomAttributes = "";
			$this->_userID->HrefValue = "";
			$this->_userID->TooltipValue = "";

			// messageType
			$this->messageType->LinkCustomAttributes = "";
			$this->messageType->HrefValue = "";
			$this->messageType->TooltipValue = "";

			// messageText
			$this->messageText->LinkCustomAttributes = "";
			$this->messageText->HrefValue = "";
			$this->messageText->TooltipValue = "";

			// stationID
			$this->stationID->LinkCustomAttributes = "";
			$this->stationID->HrefValue = "";
			$this->stationID->TooltipValue = "";

			// messageDateTime
			$this->messageDateTime->LinkCustomAttributes = "";
			$this->messageDateTime->HrefValue = "";
			$this->messageDateTime->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "sana_person") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_personID"] <> "") {
					$GLOBALS["sana_person"]->personID->setQueryStringValue($_GET["fk_personID"]);
					$this->personID->setQueryStringValue($GLOBALS["sana_person"]->personID->QueryStringValue);
					$this->personID->setSessionValue($this->personID->QueryStringValue);
					if (!is_numeric($GLOBALS["sana_person"]->personID->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "sana_person") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_personID"] <> "") {
					$GLOBALS["sana_person"]->personID->setFormValue($_POST["fk_personID"]);
					$this->personID->setFormValue($GLOBALS["sana_person"]->personID->FormValue);
					$this->personID->setSessionValue($this->personID->FormValue);
					if (!is_numeric($GLOBALS["sana_person"]->personID->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);
			$this->setSessionWhere($this->GetDetailFilter());

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "sana_person") {
				if ($this->personID->CurrentValue == "") $this->personID->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sana_messagelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($sana_message_view)) $sana_message_view = new csana_message_view();

// Page init
$sana_message_view->Page_Init();

// Page main
$sana_message_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_message_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fsana_messageview = new ew_Form("fsana_messageview", "view");

// Form_CustomValidate event
fsana_messageview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_messageview.ValidateRequired = true;
<?php } else { ?>
fsana_messageview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsana_messageview.Lists["x_personID"] = {"LinkField":"x_personID","Ajax":true,"AutoFill":false,"DisplayFields":["x_personName","x_lastName","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fsana_messageview.Lists["x__userID"] = {"LinkField":"x__userID","Ajax":true,"AutoFill":false,"DisplayFields":["x_personName","x_lastName","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fsana_messageview.Lists["x_stationID"] = {"LinkField":"x_stationID","Ajax":true,"AutoFill":false,"DisplayFields":["x_stationName","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $sana_message_view->ExportOptions->Render("body") ?>
<?php
	foreach ($sana_message_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $sana_message_view->ShowPageHeader(); ?>
<?php
$sana_message_view->ShowMessage();
?>
<form name="fsana_messageview" id="fsana_messageview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_message_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_message_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_message">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($sana_message->messageID->Visible) { // messageID ?>
	<tr id="r_messageID">
		<td><span id="elh_sana_message_messageID"><?php echo $sana_message->messageID->FldCaption() ?></span></td>
		<td data-name="messageID"<?php echo $sana_message->messageID->CellAttributes() ?>>
<span id="el_sana_message_messageID">
<span<?php echo $sana_message->messageID->ViewAttributes() ?>>
<?php echo $sana_message->messageID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_message->personID->Visible) { // personID ?>
	<tr id="r_personID">
		<td><span id="elh_sana_message_personID"><?php echo $sana_message->personID->FldCaption() ?></span></td>
		<td data-name="personID"<?php echo $sana_message->personID->CellAttributes() ?>>
<span id="el_sana_message_personID">
<span<?php echo $sana_message->personID->ViewAttributes() ?>>
<?php echo $sana_message->personID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_message->_userID->Visible) { // userID ?>
	<tr id="r__userID">
		<td><span id="elh_sana_message__userID"><?php echo $sana_message->_userID->FldCaption() ?></span></td>
		<td data-name="_userID"<?php echo $sana_message->_userID->CellAttributes() ?>>
<span id="el_sana_message__userID">
<span<?php echo $sana_message->_userID->ViewAttributes() ?>>
<?php echo $sana_message->_userID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_message->messageType->Visible) { // messageType ?>
	<tr id="r_messageType">
		<td><span id="elh_sana_message_messageType"><?php echo $sana_message->messageType->FldCaption() ?></span></td>
		<td data-name="messageType"<?php echo $sana_message->messageType->CellAttributes() ?>>
<span id="el_sana_message_messageType">
<span<?php echo $sana_message->messageType->ViewAttributes() ?>>
<?php echo $sana_message->messageType->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_message->messageText->Visible) { // messageText ?>
	<tr id="r_messageText">
		<td><span id="elh_sana_message_messageText"><?php echo $sana_message->messageText->FldCaption() ?></span></td>
		<td data-name="messageText"<?php echo $sana_message->messageText->CellAttributes() ?>>
<span id="el_sana_message_messageText">
<span<?php echo $sana_message->messageText->ViewAttributes() ?>>
<?php echo $sana_message->messageText->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_message->stationID->Visible) { // stationID ?>
	<tr id="r_stationID">
		<td><span id="elh_sana_message_stationID"><?php echo $sana_message->stationID->FldCaption() ?></span></td>
		<td data-name="stationID"<?php echo $sana_message->stationID->CellAttributes() ?>>
<span id="el_sana_message_stationID">
<span<?php echo $sana_message->stationID->ViewAttributes() ?>>
<?php echo $sana_message->stationID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_message->messageDateTime->Visible) { // messageDateTime ?>
	<tr id="r_messageDateTime">
		<td><span id="elh_sana_message_messageDateTime"><?php echo $sana_message->messageDateTime->FldCaption() ?></span></td>
		<td data-name="messageDateTime"<?php echo $sana_message->messageDateTime->CellAttributes() ?>>
<span id="el_sana_message_messageDateTime">
<span<?php echo $sana_message->messageDateTime->ViewAttributes() ?>>
<?php echo $sana_message->messageDateTime->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_message->registrationUser->Visible) { // registrationUser ?>
	<tr id="r_registrationUser">
		<td><span id="elh_sana_message_registrationUser"><?php echo $sana_message->registrationUser->FldCaption() ?></span></td>
		<td data-name="registrationUser"<?php echo $sana_message->registrationUser->CellAttributes() ?>>
<span id="el_sana_message_registrationUser">
<span<?php echo $sana_message->registrationUser->ViewAttributes() ?>>
<?php echo $sana_message->registrationUser->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_message->registrationDateTime->Visible) { // registrationDateTime ?>
	<tr id="r_registrationDateTime">
		<td><span id="elh_sana_message_registrationDateTime"><?php echo $sana_message->registrationDateTime->FldCaption() ?></span></td>
		<td data-name="registrationDateTime"<?php echo $sana_message->registrationDateTime->CellAttributes() ?>>
<span id="el_sana_message_registrationDateTime">
<span<?php echo $sana_message->registrationDateTime->ViewAttributes() ?>>
<?php echo $sana_message->registrationDateTime->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_message->registrationStation->Visible) { // registrationStation ?>
	<tr id="r_registrationStation">
		<td><span id="elh_sana_message_registrationStation"><?php echo $sana_message->registrationStation->FldCaption() ?></span></td>
		<td data-name="registrationStation"<?php echo $sana_message->registrationStation->CellAttributes() ?>>
<span id="el_sana_message_registrationStation">
<span<?php echo $sana_message->registrationStation->ViewAttributes() ?>>
<?php echo $sana_message->registrationStation->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fsana_messageview.Init();
</script>
<?php
$sana_message_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_message_view->Page_Terminate();
?>
