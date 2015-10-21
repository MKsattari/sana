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

$sana_message_add = NULL; // Initialize page object first

class csana_message_add extends csana_message {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_message';

	// Page object name
	var $PageObjName = 'sana_message_add';

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

		// Table object (sana_message)
		if (!isset($GLOBALS["sana_message"]) || get_class($GLOBALS["sana_message"]) == "csana_message") {
			$GLOBALS["sana_message"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_message"];
		}

		// Table object (sana_person)
		if (!isset($GLOBALS['sana_person'])) $GLOBALS['sana_person'] = new csana_person();

		// Table object (sana_user)
		if (!isset($GLOBALS['sana_user'])) $GLOBALS['sana_user'] = new csana_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["messageID"] != "") {
				$this->messageID->setQueryStringValue($_GET["messageID"]);
				$this->setKey("messageID", $this->messageID->CurrentValue); // Set up key
			} else {
				$this->setKey("messageID", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

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
					$this->Page_Terminate("sana_messagelist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "sana_messagelist.php")
						$sReturnUrl = $this->AddMasterUrl($this->GetListUrl()); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "sana_messageview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->personID->CurrentValue = NULL;
		$this->personID->OldValue = $this->personID->CurrentValue;
		$this->_userID->CurrentValue = NULL;
		$this->_userID->OldValue = $this->_userID->CurrentValue;
		$this->messageType->CurrentValue = NULL;
		$this->messageType->OldValue = $this->messageType->CurrentValue;
		$this->messageText->CurrentValue = NULL;
		$this->messageText->OldValue = $this->messageText->CurrentValue;
		$this->stationID->CurrentValue = NULL;
		$this->stationID->OldValue = $this->stationID->CurrentValue;
		$this->messageDateTime->CurrentValue = NULL;
		$this->messageDateTime->OldValue = $this->messageDateTime->CurrentValue;
		$this->registrationUser->CurrentValue = NULL;
		$this->registrationUser->OldValue = $this->registrationUser->CurrentValue;
		$this->registrationDateTime->CurrentValue = NULL;
		$this->registrationDateTime->OldValue = $this->registrationDateTime->CurrentValue;
		$this->registrationStation->CurrentValue = NULL;
		$this->registrationStation->OldValue = $this->registrationStation->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->personID->FldIsDetailKey) {
			$this->personID->setFormValue($objForm->GetValue("x_personID"));
		}
		if (!$this->_userID->FldIsDetailKey) {
			$this->_userID->setFormValue($objForm->GetValue("x__userID"));
		}
		if (!$this->messageType->FldIsDetailKey) {
			$this->messageType->setFormValue($objForm->GetValue("x_messageType"));
		}
		if (!$this->messageText->FldIsDetailKey) {
			$this->messageText->setFormValue($objForm->GetValue("x_messageText"));
		}
		if (!$this->stationID->FldIsDetailKey) {
			$this->stationID->setFormValue($objForm->GetValue("x_stationID"));
		}
		if (!$this->messageDateTime->FldIsDetailKey) {
			$this->messageDateTime->setFormValue($objForm->GetValue("x_messageDateTime"));
			$this->messageDateTime->CurrentValue = ew_UnFormatDateTime($this->messageDateTime->CurrentValue, 5);
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
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->personID->CurrentValue = $this->personID->FormValue;
		$this->_userID->CurrentValue = $this->_userID->FormValue;
		$this->messageType->CurrentValue = $this->messageType->FormValue;
		$this->messageText->CurrentValue = $this->messageText->FormValue;
		$this->stationID->CurrentValue = $this->stationID->FormValue;
		$this->messageDateTime->CurrentValue = $this->messageDateTime->FormValue;
		$this->messageDateTime->CurrentValue = ew_UnFormatDateTime($this->messageDateTime->CurrentValue, 5);
		$this->registrationUser->CurrentValue = $this->registrationUser->FormValue;
		$this->registrationDateTime->CurrentValue = $this->registrationDateTime->FormValue;
		$this->registrationDateTime->CurrentValue = ew_UnFormatDateTime($this->registrationDateTime->CurrentValue, 5);
		$this->registrationStation->CurrentValue = $this->registrationStation->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("messageID")) <> "")
			$this->messageID->CurrentValue = $this->getKey("messageID"); // messageID
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// personID
			$this->personID->EditAttrs["class"] = "form-control";
			$this->personID->EditCustomAttributes = "";
			if ($this->personID->getSessionValue() <> "") {
				$this->personID->CurrentValue = $this->personID->getSessionValue();
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
			} else {
			$this->personID->EditValue = ew_HtmlEncode($this->personID->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->personID->EditValue = $this->personID->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->personID->EditValue = ew_HtmlEncode($this->personID->CurrentValue);
				}
			} else {
				$this->personID->EditValue = NULL;
			}
			$this->personID->PlaceHolder = ew_RemoveHtml($this->personID->FldCaption());
			}

			// userID
			$this->_userID->EditAttrs["class"] = "form-control";
			$this->_userID->EditCustomAttributes = "";
			$this->_userID->EditValue = ew_HtmlEncode($this->_userID->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->_userID->EditValue = $this->_userID->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->_userID->EditValue = ew_HtmlEncode($this->_userID->CurrentValue);
				}
			} else {
				$this->_userID->EditValue = NULL;
			}
			$this->_userID->PlaceHolder = ew_RemoveHtml($this->_userID->FldCaption());

			// messageType
			$this->messageType->EditAttrs["class"] = "form-control";
			$this->messageType->EditCustomAttributes = "";
			$this->messageType->EditValue = ew_HtmlEncode($this->messageType->CurrentValue);
			$this->messageType->PlaceHolder = ew_RemoveHtml($this->messageType->FldCaption());

			// messageText
			$this->messageText->EditAttrs["class"] = "form-control";
			$this->messageText->EditCustomAttributes = "";
			$this->messageText->EditValue = ew_HtmlEncode($this->messageText->CurrentValue);
			$this->messageText->PlaceHolder = ew_RemoveHtml($this->messageText->FldCaption());

			// stationID
			$this->stationID->EditAttrs["class"] = "form-control";
			$this->stationID->EditCustomAttributes = "";
			$this->stationID->EditValue = ew_HtmlEncode($this->stationID->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->stationID->EditValue = $this->stationID->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->stationID->EditValue = ew_HtmlEncode($this->stationID->CurrentValue);
				}
			} else {
				$this->stationID->EditValue = NULL;
			}
			$this->stationID->PlaceHolder = ew_RemoveHtml($this->stationID->FldCaption());

			// messageDateTime
			$this->messageDateTime->EditAttrs["class"] = "form-control";
			$this->messageDateTime->EditCustomAttributes = "";
			$this->messageDateTime->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->messageDateTime->CurrentValue, 5));
			$this->messageDateTime->PlaceHolder = ew_RemoveHtml($this->messageDateTime->FldCaption());

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

			// Add refer script
			// personID

			$this->personID->LinkCustomAttributes = "";
			$this->personID->HrefValue = "";

			// userID
			$this->_userID->LinkCustomAttributes = "";
			$this->_userID->HrefValue = "";

			// messageType
			$this->messageType->LinkCustomAttributes = "";
			$this->messageType->HrefValue = "";

			// messageText
			$this->messageText->LinkCustomAttributes = "";
			$this->messageText->HrefValue = "";

			// stationID
			$this->stationID->LinkCustomAttributes = "";
			$this->stationID->HrefValue = "";

			// messageDateTime
			$this->messageDateTime->LinkCustomAttributes = "";
			$this->messageDateTime->HrefValue = "";

			// registrationUser
			$this->registrationUser->LinkCustomAttributes = "";
			$this->registrationUser->HrefValue = "";

			// registrationDateTime
			$this->registrationDateTime->LinkCustomAttributes = "";
			$this->registrationDateTime->HrefValue = "";

			// registrationStation
			$this->registrationStation->LinkCustomAttributes = "";
			$this->registrationStation->HrefValue = "";
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
		if (!$this->personID->FldIsDetailKey && !is_null($this->personID->FormValue) && $this->personID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->personID->FldCaption(), $this->personID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->personID->FormValue)) {
			ew_AddMessage($gsFormError, $this->personID->FldErrMsg());
		}
		if (!$this->_userID->FldIsDetailKey && !is_null($this->_userID->FormValue) && $this->_userID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_userID->FldCaption(), $this->_userID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->_userID->FormValue)) {
			ew_AddMessage($gsFormError, $this->_userID->FldErrMsg());
		}
		if (!$this->messageType->FldIsDetailKey && !is_null($this->messageType->FormValue) && $this->messageType->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->messageType->FldCaption(), $this->messageType->ReqErrMsg));
		}
		if (!$this->messageText->FldIsDetailKey && !is_null($this->messageText->FormValue) && $this->messageText->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->messageText->FldCaption(), $this->messageText->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->stationID->FormValue)) {
			ew_AddMessage($gsFormError, $this->stationID->FldErrMsg());
		}
		if (!ew_CheckDate($this->messageDateTime->FormValue)) {
			ew_AddMessage($gsFormError, $this->messageDateTime->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;

		// Check referential integrity for master table 'sana_person'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_sana_person();
		if (strval($this->personID->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@personID@", ew_AdjustSql($this->personID->CurrentValue, "DB"), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["sana_person"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "sana_person", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// personID
		$this->personID->SetDbValueDef($rsnew, $this->personID->CurrentValue, 0, FALSE);

		// userID
		$this->_userID->SetDbValueDef($rsnew, $this->_userID->CurrentValue, 0, FALSE);

		// messageType
		$this->messageType->SetDbValueDef($rsnew, $this->messageType->CurrentValue, "", FALSE);

		// messageText
		$this->messageText->SetDbValueDef($rsnew, $this->messageText->CurrentValue, "", FALSE);

		// stationID
		$this->stationID->SetDbValueDef($rsnew, $this->stationID->CurrentValue, NULL, FALSE);

		// messageDateTime
		$this->messageDateTime->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->messageDateTime->CurrentValue, 5), NULL, FALSE);

		// registrationUser
		$this->registrationUser->SetDbValueDef($rsnew, $this->registrationUser->CurrentValue, NULL, FALSE);

		// registrationDateTime
		$this->registrationDateTime->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->registrationDateTime->CurrentValue, 5), NULL, FALSE);

		// registrationStation
		$this->registrationStation->SetDbValueDef($rsnew, $this->registrationStation->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->messageID->setDbValue($conn->Insert_ID());
				$rsnew['messageID'] = $this->messageID->DbValue;
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($sana_message_add)) $sana_message_add = new csana_message_add();

// Page init
$sana_message_add->Page_Init();

// Page main
$sana_message_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_message_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fsana_messageadd = new ew_Form("fsana_messageadd", "add");

// Validate form
fsana_messageadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_personID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_message->personID->FldCaption(), $sana_message->personID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_personID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_message->personID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__userID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_message->_userID->FldCaption(), $sana_message->_userID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__userID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_message->_userID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_messageType");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_message->messageType->FldCaption(), $sana_message->messageType->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_messageText");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_message->messageText->FldCaption(), $sana_message->messageText->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_stationID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_message->stationID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_messageDateTime");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_message->messageDateTime->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_registrationUser");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_message->registrationUser->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_registrationDateTime");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_message->registrationDateTime->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_registrationStation");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_message->registrationStation->FldErrMsg()) ?>");

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
fsana_messageadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_messageadd.ValidateRequired = true;
<?php } else { ?>
fsana_messageadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsana_messageadd.Lists["x_personID"] = {"LinkField":"x_personID","Ajax":true,"AutoFill":false,"DisplayFields":["x_personName","x_lastName","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fsana_messageadd.Lists["x__userID"] = {"LinkField":"x__userID","Ajax":true,"AutoFill":false,"DisplayFields":["x_personName","x_lastName","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fsana_messageadd.Lists["x_stationID"] = {"LinkField":"x_stationID","Ajax":true,"AutoFill":false,"DisplayFields":["x_stationName","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $sana_message_add->ShowPageHeader(); ?>
<?php
$sana_message_add->ShowMessage();
?>
<form name="fsana_messageadd" id="fsana_messageadd" class="<?php echo $sana_message_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_message_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_message_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_message">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($sana_message->getCurrentMasterTable() == "sana_person") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="sana_person">
<input type="hidden" name="fk_personID" value="<?php echo $sana_message->personID->getSessionValue() ?>">
<?php } ?>
<div>
<?php if ($sana_message->personID->Visible) { // personID ?>
	<div id="r_personID" class="form-group">
		<label id="elh_sana_message_personID" class="col-sm-2 control-label ewLabel"><?php echo $sana_message->personID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_message->personID->CellAttributes() ?>>
<?php if ($sana_message->personID->getSessionValue() <> "") { ?>
<span id="el_sana_message_personID">
<span<?php echo $sana_message->personID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sana_message->personID->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_personID" name="x_personID" value="<?php echo ew_HtmlEncode($sana_message->personID->CurrentValue) ?>">
<?php } else { ?>
<span id="el_sana_message_personID">
<?php
$wrkonchange = trim(" " . @$sana_message->personID->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$sana_message->personID->EditAttrs["onchange"] = "";
?>
<span id="as_x_personID" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_personID" id="sv_x_personID" value="<?php echo $sana_message->personID->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($sana_message->personID->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($sana_message->personID->getPlaceHolder()) ?>"<?php echo $sana_message->personID->EditAttributes() ?>>
</span>
<input type="hidden" data-table="sana_message" data-field="x_personID" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_message->personID->DisplayValueSeparator) ? json_encode($sana_message->personID->DisplayValueSeparator) : $sana_message->personID->DisplayValueSeparator) ?>" name="x_personID" id="x_personID" value="<?php echo ew_HtmlEncode($sana_message->personID->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_person`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->personID) . "',`lastName`) LIKE '{query_value}%'";
		break;
	case "fa":
		$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_person`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->personID) . "',`lastName`) LIKE '{query_value}%'";
		break;
	default:
		$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_person`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->personID) . "',`lastName`) LIKE '{query_value}%'";
		break;
}
$sana_message->Lookup_Selecting($sana_message->personID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_personID" id="q_x_personID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fsana_messageadd.CreateAutoSuggest({"id":"x_personID","forceSelect":false});
</script>
</span>
<?php } ?>
<?php echo $sana_message->personID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_message->_userID->Visible) { // userID ?>
	<div id="r__userID" class="form-group">
		<label id="elh_sana_message__userID" class="col-sm-2 control-label ewLabel"><?php echo $sana_message->_userID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_message->_userID->CellAttributes() ?>>
<span id="el_sana_message__userID">
<?php
$wrkonchange = trim(" " . @$sana_message->_userID->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$sana_message->_userID->EditAttrs["onchange"] = "";
?>
<span id="as_x__userID" style="white-space: nowrap; z-index: 8970">
	<input type="text" name="sv_x__userID" id="sv_x__userID" value="<?php echo $sana_message->_userID->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($sana_message->_userID->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($sana_message->_userID->getPlaceHolder()) ?>"<?php echo $sana_message->_userID->EditAttributes() ?>>
</span>
<input type="hidden" data-table="sana_message" data-field="x__userID" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_message->_userID->DisplayValueSeparator) ? json_encode($sana_message->_userID->DisplayValueSeparator) : $sana_message->_userID->DisplayValueSeparator) ?>" name="x__userID" id="x__userID" value="<?php echo ew_HtmlEncode($sana_message->_userID->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_user`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->_userID) . "',`lastName`) LIKE '{query_value}%'";
		break;
	case "fa":
		$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_user`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->_userID) . "',`lastName`) LIKE '{query_value}%'";
		break;
	default:
		$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_user`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->_userID) . "',`lastName`) LIKE '{query_value}%'";
		break;
}
if (!$GLOBALS["sana_message"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["sana_user"]->AddUserIDFilter($sWhereWrk);
$sana_message->Lookup_Selecting($sana_message->_userID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x__userID" id="q_x__userID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fsana_messageadd.CreateAutoSuggest({"id":"x__userID","forceSelect":false});
</script>
</span>
<?php echo $sana_message->_userID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_message->messageType->Visible) { // messageType ?>
	<div id="r_messageType" class="form-group">
		<label id="elh_sana_message_messageType" for="x_messageType" class="col-sm-2 control-label ewLabel"><?php echo $sana_message->messageType->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_message->messageType->CellAttributes() ?>>
<span id="el_sana_message_messageType">
<input type="text" data-table="sana_message" data-field="x_messageType" name="x_messageType" id="x_messageType" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_message->messageType->getPlaceHolder()) ?>" value="<?php echo $sana_message->messageType->EditValue ?>"<?php echo $sana_message->messageType->EditAttributes() ?>>
</span>
<?php echo $sana_message->messageType->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_message->messageText->Visible) { // messageText ?>
	<div id="r_messageText" class="form-group">
		<label id="elh_sana_message_messageText" for="x_messageText" class="col-sm-2 control-label ewLabel"><?php echo $sana_message->messageText->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_message->messageText->CellAttributes() ?>>
<span id="el_sana_message_messageText">
<textarea data-table="sana_message" data-field="x_messageText" name="x_messageText" id="x_messageText" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($sana_message->messageText->getPlaceHolder()) ?>"<?php echo $sana_message->messageText->EditAttributes() ?>><?php echo $sana_message->messageText->EditValue ?></textarea>
</span>
<?php echo $sana_message->messageText->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_message->stationID->Visible) { // stationID ?>
	<div id="r_stationID" class="form-group">
		<label id="elh_sana_message_stationID" class="col-sm-2 control-label ewLabel"><?php echo $sana_message->stationID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_message->stationID->CellAttributes() ?>>
<span id="el_sana_message_stationID">
<?php
$wrkonchange = trim(" " . @$sana_message->stationID->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$sana_message->stationID->EditAttrs["onchange"] = "";
?>
<span id="as_x_stationID" style="white-space: nowrap; z-index: 8940">
	<input type="text" name="sv_x_stationID" id="sv_x_stationID" value="<?php echo $sana_message->stationID->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($sana_message->stationID->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($sana_message->stationID->getPlaceHolder()) ?>"<?php echo $sana_message->stationID->EditAttributes() ?>>
</span>
<input type="hidden" data-table="sana_message" data-field="x_stationID" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_message->stationID->DisplayValueSeparator) ? json_encode($sana_message->stationID->DisplayValueSeparator) : $sana_message->stationID->DisplayValueSeparator) ?>" name="x_stationID" id="x_stationID" value="<?php echo ew_HtmlEncode($sana_message->stationID->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `stationID`, `stationName` AS `DispFld` FROM `sana_station`";
		$sWhereWrk = "`stationName` LIKE '{query_value}%'";
		break;
	case "fa":
		$sSqlWrk = "SELECT `stationID`, `stationName` AS `DispFld` FROM `sana_station`";
		$sWhereWrk = "`stationName` LIKE '{query_value}%'";
		break;
	default:
		$sSqlWrk = "SELECT `stationID`, `stationName` AS `DispFld` FROM `sana_station`";
		$sWhereWrk = "`stationName` LIKE '{query_value}%'";
		break;
}
$sana_message->Lookup_Selecting($sana_message->stationID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_stationID" id="q_x_stationID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fsana_messageadd.CreateAutoSuggest({"id":"x_stationID","forceSelect":false});
</script>
</span>
<?php echo $sana_message->stationID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_message->messageDateTime->Visible) { // messageDateTime ?>
	<div id="r_messageDateTime" class="form-group">
		<label id="elh_sana_message_messageDateTime" for="x_messageDateTime" class="col-sm-2 control-label ewLabel"><?php echo $sana_message->messageDateTime->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_message->messageDateTime->CellAttributes() ?>>
<span id="el_sana_message_messageDateTime">
<input type="text" data-table="sana_message" data-field="x_messageDateTime" data-format="5" name="x_messageDateTime" id="x_messageDateTime" placeholder="<?php echo ew_HtmlEncode($sana_message->messageDateTime->getPlaceHolder()) ?>" value="<?php echo $sana_message->messageDateTime->EditValue ?>"<?php echo $sana_message->messageDateTime->EditAttributes() ?>>
</span>
<?php echo $sana_message->messageDateTime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_message->registrationUser->Visible) { // registrationUser ?>
	<div id="r_registrationUser" class="form-group">
		<label id="elh_sana_message_registrationUser" for="x_registrationUser" class="col-sm-2 control-label ewLabel"><?php echo $sana_message->registrationUser->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_message->registrationUser->CellAttributes() ?>>
<span id="el_sana_message_registrationUser">
<input type="text" data-table="sana_message" data-field="x_registrationUser" name="x_registrationUser" id="x_registrationUser" size="30" placeholder="<?php echo ew_HtmlEncode($sana_message->registrationUser->getPlaceHolder()) ?>" value="<?php echo $sana_message->registrationUser->EditValue ?>"<?php echo $sana_message->registrationUser->EditAttributes() ?>>
</span>
<?php echo $sana_message->registrationUser->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_message->registrationDateTime->Visible) { // registrationDateTime ?>
	<div id="r_registrationDateTime" class="form-group">
		<label id="elh_sana_message_registrationDateTime" for="x_registrationDateTime" class="col-sm-2 control-label ewLabel"><?php echo $sana_message->registrationDateTime->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_message->registrationDateTime->CellAttributes() ?>>
<span id="el_sana_message_registrationDateTime">
<input type="text" data-table="sana_message" data-field="x_registrationDateTime" data-format="5" name="x_registrationDateTime" id="x_registrationDateTime" placeholder="<?php echo ew_HtmlEncode($sana_message->registrationDateTime->getPlaceHolder()) ?>" value="<?php echo $sana_message->registrationDateTime->EditValue ?>"<?php echo $sana_message->registrationDateTime->EditAttributes() ?>>
</span>
<?php echo $sana_message->registrationDateTime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_message->registrationStation->Visible) { // registrationStation ?>
	<div id="r_registrationStation" class="form-group">
		<label id="elh_sana_message_registrationStation" for="x_registrationStation" class="col-sm-2 control-label ewLabel"><?php echo $sana_message->registrationStation->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_message->registrationStation->CellAttributes() ?>>
<span id="el_sana_message_registrationStation">
<input type="text" data-table="sana_message" data-field="x_registrationStation" name="x_registrationStation" id="x_registrationStation" size="30" placeholder="<?php echo ew_HtmlEncode($sana_message->registrationStation->getPlaceHolder()) ?>" value="<?php echo $sana_message->registrationStation->EditValue ?>"<?php echo $sana_message->registrationStation->EditAttributes() ?>>
</span>
<?php echo $sana_message->registrationStation->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sana_message_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fsana_messageadd.Init();
</script>
<?php
$sana_message_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_message_add->Page_Terminate();
?>
