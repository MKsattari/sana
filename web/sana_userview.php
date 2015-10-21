<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "sana_userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$sana_user_view = NULL; // Initialize page object first

class csana_user_view extends csana_user {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_user';

	// Page object name
	var $PageObjName = 'sana_user_view';

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

		// Table object (sana_user)
		if (!isset($GLOBALS["sana_user"]) || get_class($GLOBALS["sana_user"]) == "csana_user") {
			$GLOBALS["sana_user"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_user"];
		}
		$KeyUrl = "";
		if (@$_GET["_userID"] <> "") {
			$this->RecKey["_userID"] = $_GET["_userID"];
			$KeyUrl .= "&amp;_userID=" . urlencode($this->RecKey["_userID"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sana_user', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("sana_userlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
			if (strval($Security->CurrentUserID()) == "") {
				$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
				$this->Page_Terminate(ew_GetUrl("sana_userlist.php"));
			}
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->_userID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $sana_user;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($sana_user);
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
			if (@$_GET["_userID"] <> "") {
				$this->_userID->setQueryStringValue($_GET["_userID"]);
				$this->RecKey["_userID"] = $this->_userID->QueryStringValue;
			} elseif (@$_POST["_userID"] <> "") {
				$this->_userID->setFormValue($_POST["_userID"]);
				$this->RecKey["_userID"] = $this->_userID->FormValue;
			} else {
				$sReturnUrl = "sana_userlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "sana_userlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "sana_userlist.php"; // Not page request, return to list
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
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit()&& $this->ShowOptionLink('edit'));

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd() && $this->ShowOptionLink('add'));

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete() && $this->ShowOptionLink('delete'));

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
		$this->_userID->setDbValue($rs->fields('userID'));
		$this->username->setDbValue($rs->fields('username'));
		$this->personName->setDbValue($rs->fields('personName'));
		$this->lastName->setDbValue($rs->fields('lastName'));
		$this->nationalID->setDbValue($rs->fields('nationalID'));
		$this->nationalNumber->setDbValue($rs->fields('nationalNumber'));
		$this->fatherName->setDbValue($rs->fields('fatherName'));
		$this->country->setDbValue($rs->fields('country'));
		$this->province->setDbValue($rs->fields('province'));
		$this->county->setDbValue($rs->fields('county'));
		$this->district->setDbValue($rs->fields('district'));
		$this->city_ruralDistrict->setDbValue($rs->fields('city_ruralDistrict'));
		$this->region_village->setDbValue($rs->fields('region_village'));
		$this->address->setDbValue($rs->fields('address'));
		$this->birthDate->setDbValue($rs->fields('birthDate'));
		$this->ageRange->setDbValue($rs->fields('ageRange'));
		$this->phone->setDbValue($rs->fields('phone'));
		$this->mobilePhone->setDbValue($rs->fields('mobilePhone'));
		$this->userPassword->setDbValue($rs->fields('userPassword'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->picture->Upload->DbValue = $rs->fields('picture');
		$this->picture->CurrentValue = $this->picture->Upload->DbValue;
		$this->registrationUser->setDbValue($rs->fields('registrationUser'));
		$this->registrationDateTime->setDbValue($rs->fields('registrationDateTime'));
		$this->stationID->setDbValue($rs->fields('stationID'));
		$this->isolatedDateTime->setDbValue($rs->fields('isolatedDateTime'));
		$this->acl->setDbValue($rs->fields('acl'));
		$this->description->setDbValue($rs->fields('description'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->_userID->DbValue = $row['userID'];
		$this->username->DbValue = $row['username'];
		$this->personName->DbValue = $row['personName'];
		$this->lastName->DbValue = $row['lastName'];
		$this->nationalID->DbValue = $row['nationalID'];
		$this->nationalNumber->DbValue = $row['nationalNumber'];
		$this->fatherName->DbValue = $row['fatherName'];
		$this->country->DbValue = $row['country'];
		$this->province->DbValue = $row['province'];
		$this->county->DbValue = $row['county'];
		$this->district->DbValue = $row['district'];
		$this->city_ruralDistrict->DbValue = $row['city_ruralDistrict'];
		$this->region_village->DbValue = $row['region_village'];
		$this->address->DbValue = $row['address'];
		$this->birthDate->DbValue = $row['birthDate'];
		$this->ageRange->DbValue = $row['ageRange'];
		$this->phone->DbValue = $row['phone'];
		$this->mobilePhone->DbValue = $row['mobilePhone'];
		$this->userPassword->DbValue = $row['userPassword'];
		$this->_email->DbValue = $row['email'];
		$this->picture->Upload->DbValue = $row['picture'];
		$this->registrationUser->DbValue = $row['registrationUser'];
		$this->registrationDateTime->DbValue = $row['registrationDateTime'];
		$this->stationID->DbValue = $row['stationID'];
		$this->isolatedDateTime->DbValue = $row['isolatedDateTime'];
		$this->acl->DbValue = $row['acl'];
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
		// userID
		// username
		// personName
		// lastName
		// nationalID
		// nationalNumber
		// fatherName
		// country
		// province
		// county
		// district
		// city_ruralDistrict
		// region_village
		// address
		// birthDate
		// ageRange
		// phone
		// mobilePhone
		// userPassword
		// email
		// picture
		// registrationUser
		// registrationDateTime
		// stationID
		// isolatedDateTime
		// acl
		// description

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// userID
		$this->_userID->ViewValue = $this->_userID->CurrentValue;
		$this->_userID->ViewCustomAttributes = "";

		// username
		$this->username->ViewValue = $this->username->CurrentValue;
		$this->username->ViewCustomAttributes = "";

		// personName
		$this->personName->ViewValue = $this->personName->CurrentValue;
		$this->personName->ViewCustomAttributes = "";

		// lastName
		$this->lastName->ViewValue = $this->lastName->CurrentValue;
		$this->lastName->ViewCustomAttributes = "";

		// nationalID
		$this->nationalID->ViewValue = $this->nationalID->CurrentValue;
		$this->nationalID->ViewCustomAttributes = "";

		// nationalNumber
		$this->nationalNumber->ViewValue = $this->nationalNumber->CurrentValue;
		$this->nationalNumber->ViewCustomAttributes = "";

		// fatherName
		$this->fatherName->ViewValue = $this->fatherName->CurrentValue;
		$this->fatherName->ViewCustomAttributes = "";

		// country
		$this->country->ViewValue = $this->country->CurrentValue;
		$this->country->ViewCustomAttributes = "";

		// province
		$this->province->ViewValue = $this->province->CurrentValue;
		$this->province->ViewCustomAttributes = "";

		// county
		$this->county->ViewValue = $this->county->CurrentValue;
		$this->county->ViewCustomAttributes = "";

		// district
		$this->district->ViewValue = $this->district->CurrentValue;
		$this->district->ViewCustomAttributes = "";

		// city_ruralDistrict
		$this->city_ruralDistrict->ViewValue = $this->city_ruralDistrict->CurrentValue;
		$this->city_ruralDistrict->ViewCustomAttributes = "";

		// region_village
		$this->region_village->ViewValue = $this->region_village->CurrentValue;
		$this->region_village->ViewCustomAttributes = "";

		// address
		$this->address->ViewValue = $this->address->CurrentValue;
		$this->address->ViewCustomAttributes = "";

		// birthDate
		$this->birthDate->ViewValue = $this->birthDate->CurrentValue;
		$this->birthDate->ViewCustomAttributes = "";

		// ageRange
		$this->ageRange->ViewValue = $this->ageRange->CurrentValue;
		$this->ageRange->ViewCustomAttributes = "";

		// phone
		$this->phone->ViewValue = $this->phone->CurrentValue;
		$this->phone->ViewCustomAttributes = "";

		// mobilePhone
		$this->mobilePhone->ViewValue = $this->mobilePhone->CurrentValue;
		$this->mobilePhone->ViewCustomAttributes = "";

		// userPassword
		$this->userPassword->ViewValue = $this->userPassword->CurrentValue;
		$this->userPassword->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// picture
		if (!ew_Empty($this->picture->Upload->DbValue)) {
			$this->picture->ViewValue = $this->picture->Upload->DbValue;
		} else {
			$this->picture->ViewValue = "";
		}
		$this->picture->ViewCustomAttributes = "";

		// registrationUser
		$this->registrationUser->ViewValue = $this->registrationUser->CurrentValue;
		$this->registrationUser->ViewCustomAttributes = "";

		// registrationDateTime
		$this->registrationDateTime->ViewValue = $this->registrationDateTime->CurrentValue;
		$this->registrationDateTime->ViewValue = ew_FormatDateTime($this->registrationDateTime->ViewValue, 5);
		$this->registrationDateTime->ViewCustomAttributes = "";

		// stationID
		$this->stationID->ViewValue = $this->stationID->CurrentValue;
		$this->stationID->ViewCustomAttributes = "";

		// isolatedDateTime
		$this->isolatedDateTime->ViewValue = $this->isolatedDateTime->CurrentValue;
		$this->isolatedDateTime->ViewValue = ew_FormatDateTime($this->isolatedDateTime->ViewValue, 5);
		$this->isolatedDateTime->ViewCustomAttributes = "";

		// acl
		if ($Security->CanAdmin()) { // System admin
		if (strval($this->acl->CurrentValue) <> "") {
			$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->acl->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->acl, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->acl->ViewValue = $this->acl->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->acl->ViewValue = $this->acl->CurrentValue;
			}
		} else {
			$this->acl->ViewValue = NULL;
		}
		} else {
			$this->acl->ViewValue = $Language->Phrase("PasswordMask");
		}
		$this->acl->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

			// userID
			$this->_userID->LinkCustomAttributes = "";
			$this->_userID->HrefValue = "";
			$this->_userID->TooltipValue = "";

			// username
			$this->username->LinkCustomAttributes = "";
			$this->username->HrefValue = "";
			$this->username->TooltipValue = "";

			// personName
			$this->personName->LinkCustomAttributes = "";
			$this->personName->HrefValue = "";
			$this->personName->TooltipValue = "";

			// lastName
			$this->lastName->LinkCustomAttributes = "";
			$this->lastName->HrefValue = "";
			$this->lastName->TooltipValue = "";

			// nationalID
			$this->nationalID->LinkCustomAttributes = "";
			$this->nationalID->HrefValue = "";
			$this->nationalID->TooltipValue = "";

			// nationalNumber
			$this->nationalNumber->LinkCustomAttributes = "";
			$this->nationalNumber->HrefValue = "";
			$this->nationalNumber->TooltipValue = "";

			// fatherName
			$this->fatherName->LinkCustomAttributes = "";
			$this->fatherName->HrefValue = "";
			$this->fatherName->TooltipValue = "";

			// country
			$this->country->LinkCustomAttributes = "";
			$this->country->HrefValue = "";
			$this->country->TooltipValue = "";

			// province
			$this->province->LinkCustomAttributes = "";
			$this->province->HrefValue = "";
			$this->province->TooltipValue = "";

			// county
			$this->county->LinkCustomAttributes = "";
			$this->county->HrefValue = "";
			$this->county->TooltipValue = "";

			// district
			$this->district->LinkCustomAttributes = "";
			$this->district->HrefValue = "";
			$this->district->TooltipValue = "";

			// city_ruralDistrict
			$this->city_ruralDistrict->LinkCustomAttributes = "";
			$this->city_ruralDistrict->HrefValue = "";
			$this->city_ruralDistrict->TooltipValue = "";

			// region_village
			$this->region_village->LinkCustomAttributes = "";
			$this->region_village->HrefValue = "";
			$this->region_village->TooltipValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";
			$this->address->TooltipValue = "";

			// birthDate
			$this->birthDate->LinkCustomAttributes = "";
			$this->birthDate->HrefValue = "";
			$this->birthDate->TooltipValue = "";

			// ageRange
			$this->ageRange->LinkCustomAttributes = "";
			$this->ageRange->HrefValue = "";
			$this->ageRange->TooltipValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";
			$this->phone->TooltipValue = "";

			// mobilePhone
			$this->mobilePhone->LinkCustomAttributes = "";
			$this->mobilePhone->HrefValue = "";
			$this->mobilePhone->TooltipValue = "";

			// userPassword
			$this->userPassword->LinkCustomAttributes = "";
			$this->userPassword->HrefValue = "";
			$this->userPassword->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// picture
			$this->picture->LinkCustomAttributes = "";
			$this->picture->HrefValue = "";
			$this->picture->HrefValue2 = $this->picture->UploadPath . $this->picture->Upload->DbValue;
			$this->picture->TooltipValue = "";

			// registrationUser
			$this->registrationUser->LinkCustomAttributes = "";
			$this->registrationUser->HrefValue = "";
			$this->registrationUser->TooltipValue = "";

			// registrationDateTime
			$this->registrationDateTime->LinkCustomAttributes = "";
			$this->registrationDateTime->HrefValue = "";
			$this->registrationDateTime->TooltipValue = "";

			// stationID
			$this->stationID->LinkCustomAttributes = "";
			$this->stationID->HrefValue = "";
			$this->stationID->TooltipValue = "";

			// isolatedDateTime
			$this->isolatedDateTime->LinkCustomAttributes = "";
			$this->isolatedDateTime->HrefValue = "";
			$this->isolatedDateTime->TooltipValue = "";

			// acl
			$this->acl->LinkCustomAttributes = "";
			$this->acl->HrefValue = "";
			$this->acl->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->_userID->CurrentValue);
		return TRUE;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sana_userlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($sana_user_view)) $sana_user_view = new csana_user_view();

// Page init
$sana_user_view->Page_Init();

// Page main
$sana_user_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_user_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fsana_userview = new ew_Form("fsana_userview", "view");

// Form_CustomValidate event
fsana_userview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_userview.ValidateRequired = true;
<?php } else { ?>
fsana_userview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsana_userview.Lists["x_acl"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $sana_user_view->ExportOptions->Render("body") ?>
<?php
	foreach ($sana_user_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $sana_user_view->ShowPageHeader(); ?>
<?php
$sana_user_view->ShowMessage();
?>
<form name="fsana_userview" id="fsana_userview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_user_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_user_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_user">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($sana_user->_userID->Visible) { // userID ?>
	<tr id="r__userID">
		<td><span id="elh_sana_user__userID"><?php echo $sana_user->_userID->FldCaption() ?></span></td>
		<td data-name="_userID"<?php echo $sana_user->_userID->CellAttributes() ?>>
<span id="el_sana_user__userID">
<span<?php echo $sana_user->_userID->ViewAttributes() ?>>
<?php echo $sana_user->_userID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->username->Visible) { // username ?>
	<tr id="r_username">
		<td><span id="elh_sana_user_username"><?php echo $sana_user->username->FldCaption() ?></span></td>
		<td data-name="username"<?php echo $sana_user->username->CellAttributes() ?>>
<span id="el_sana_user_username">
<span<?php echo $sana_user->username->ViewAttributes() ?>>
<?php echo $sana_user->username->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->personName->Visible) { // personName ?>
	<tr id="r_personName">
		<td><span id="elh_sana_user_personName"><?php echo $sana_user->personName->FldCaption() ?></span></td>
		<td data-name="personName"<?php echo $sana_user->personName->CellAttributes() ?>>
<span id="el_sana_user_personName">
<span<?php echo $sana_user->personName->ViewAttributes() ?>>
<?php echo $sana_user->personName->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->lastName->Visible) { // lastName ?>
	<tr id="r_lastName">
		<td><span id="elh_sana_user_lastName"><?php echo $sana_user->lastName->FldCaption() ?></span></td>
		<td data-name="lastName"<?php echo $sana_user->lastName->CellAttributes() ?>>
<span id="el_sana_user_lastName">
<span<?php echo $sana_user->lastName->ViewAttributes() ?>>
<?php echo $sana_user->lastName->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->nationalID->Visible) { // nationalID ?>
	<tr id="r_nationalID">
		<td><span id="elh_sana_user_nationalID"><?php echo $sana_user->nationalID->FldCaption() ?></span></td>
		<td data-name="nationalID"<?php echo $sana_user->nationalID->CellAttributes() ?>>
<span id="el_sana_user_nationalID">
<span<?php echo $sana_user->nationalID->ViewAttributes() ?>>
<?php echo $sana_user->nationalID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->nationalNumber->Visible) { // nationalNumber ?>
	<tr id="r_nationalNumber">
		<td><span id="elh_sana_user_nationalNumber"><?php echo $sana_user->nationalNumber->FldCaption() ?></span></td>
		<td data-name="nationalNumber"<?php echo $sana_user->nationalNumber->CellAttributes() ?>>
<span id="el_sana_user_nationalNumber">
<span<?php echo $sana_user->nationalNumber->ViewAttributes() ?>>
<?php echo $sana_user->nationalNumber->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->fatherName->Visible) { // fatherName ?>
	<tr id="r_fatherName">
		<td><span id="elh_sana_user_fatherName"><?php echo $sana_user->fatherName->FldCaption() ?></span></td>
		<td data-name="fatherName"<?php echo $sana_user->fatherName->CellAttributes() ?>>
<span id="el_sana_user_fatherName">
<span<?php echo $sana_user->fatherName->ViewAttributes() ?>>
<?php echo $sana_user->fatherName->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->country->Visible) { // country ?>
	<tr id="r_country">
		<td><span id="elh_sana_user_country"><?php echo $sana_user->country->FldCaption() ?></span></td>
		<td data-name="country"<?php echo $sana_user->country->CellAttributes() ?>>
<span id="el_sana_user_country">
<span<?php echo $sana_user->country->ViewAttributes() ?>>
<?php echo $sana_user->country->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->province->Visible) { // province ?>
	<tr id="r_province">
		<td><span id="elh_sana_user_province"><?php echo $sana_user->province->FldCaption() ?></span></td>
		<td data-name="province"<?php echo $sana_user->province->CellAttributes() ?>>
<span id="el_sana_user_province">
<span<?php echo $sana_user->province->ViewAttributes() ?>>
<?php echo $sana_user->province->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->county->Visible) { // county ?>
	<tr id="r_county">
		<td><span id="elh_sana_user_county"><?php echo $sana_user->county->FldCaption() ?></span></td>
		<td data-name="county"<?php echo $sana_user->county->CellAttributes() ?>>
<span id="el_sana_user_county">
<span<?php echo $sana_user->county->ViewAttributes() ?>>
<?php echo $sana_user->county->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->district->Visible) { // district ?>
	<tr id="r_district">
		<td><span id="elh_sana_user_district"><?php echo $sana_user->district->FldCaption() ?></span></td>
		<td data-name="district"<?php echo $sana_user->district->CellAttributes() ?>>
<span id="el_sana_user_district">
<span<?php echo $sana_user->district->ViewAttributes() ?>>
<?php echo $sana_user->district->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->city_ruralDistrict->Visible) { // city_ruralDistrict ?>
	<tr id="r_city_ruralDistrict">
		<td><span id="elh_sana_user_city_ruralDistrict"><?php echo $sana_user->city_ruralDistrict->FldCaption() ?></span></td>
		<td data-name="city_ruralDistrict"<?php echo $sana_user->city_ruralDistrict->CellAttributes() ?>>
<span id="el_sana_user_city_ruralDistrict">
<span<?php echo $sana_user->city_ruralDistrict->ViewAttributes() ?>>
<?php echo $sana_user->city_ruralDistrict->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->region_village->Visible) { // region_village ?>
	<tr id="r_region_village">
		<td><span id="elh_sana_user_region_village"><?php echo $sana_user->region_village->FldCaption() ?></span></td>
		<td data-name="region_village"<?php echo $sana_user->region_village->CellAttributes() ?>>
<span id="el_sana_user_region_village">
<span<?php echo $sana_user->region_village->ViewAttributes() ?>>
<?php echo $sana_user->region_village->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->address->Visible) { // address ?>
	<tr id="r_address">
		<td><span id="elh_sana_user_address"><?php echo $sana_user->address->FldCaption() ?></span></td>
		<td data-name="address"<?php echo $sana_user->address->CellAttributes() ?>>
<span id="el_sana_user_address">
<span<?php echo $sana_user->address->ViewAttributes() ?>>
<?php echo $sana_user->address->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->birthDate->Visible) { // birthDate ?>
	<tr id="r_birthDate">
		<td><span id="elh_sana_user_birthDate"><?php echo $sana_user->birthDate->FldCaption() ?></span></td>
		<td data-name="birthDate"<?php echo $sana_user->birthDate->CellAttributes() ?>>
<span id="el_sana_user_birthDate">
<span<?php echo $sana_user->birthDate->ViewAttributes() ?>>
<?php echo $sana_user->birthDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->ageRange->Visible) { // ageRange ?>
	<tr id="r_ageRange">
		<td><span id="elh_sana_user_ageRange"><?php echo $sana_user->ageRange->FldCaption() ?></span></td>
		<td data-name="ageRange"<?php echo $sana_user->ageRange->CellAttributes() ?>>
<span id="el_sana_user_ageRange">
<span<?php echo $sana_user->ageRange->ViewAttributes() ?>>
<?php echo $sana_user->ageRange->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->phone->Visible) { // phone ?>
	<tr id="r_phone">
		<td><span id="elh_sana_user_phone"><?php echo $sana_user->phone->FldCaption() ?></span></td>
		<td data-name="phone"<?php echo $sana_user->phone->CellAttributes() ?>>
<span id="el_sana_user_phone">
<span<?php echo $sana_user->phone->ViewAttributes() ?>>
<?php echo $sana_user->phone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->mobilePhone->Visible) { // mobilePhone ?>
	<tr id="r_mobilePhone">
		<td><span id="elh_sana_user_mobilePhone"><?php echo $sana_user->mobilePhone->FldCaption() ?></span></td>
		<td data-name="mobilePhone"<?php echo $sana_user->mobilePhone->CellAttributes() ?>>
<span id="el_sana_user_mobilePhone">
<span<?php echo $sana_user->mobilePhone->ViewAttributes() ?>>
<?php echo $sana_user->mobilePhone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->userPassword->Visible) { // userPassword ?>
	<tr id="r_userPassword">
		<td><span id="elh_sana_user_userPassword"><?php echo $sana_user->userPassword->FldCaption() ?></span></td>
		<td data-name="userPassword"<?php echo $sana_user->userPassword->CellAttributes() ?>>
<span id="el_sana_user_userPassword">
<span<?php echo $sana_user->userPassword->ViewAttributes() ?>>
<?php echo $sana_user->userPassword->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_sana_user__email"><?php echo $sana_user->_email->FldCaption() ?></span></td>
		<td data-name="_email"<?php echo $sana_user->_email->CellAttributes() ?>>
<span id="el_sana_user__email">
<span<?php echo $sana_user->_email->ViewAttributes() ?>>
<?php echo $sana_user->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->picture->Visible) { // picture ?>
	<tr id="r_picture">
		<td><span id="elh_sana_user_picture"><?php echo $sana_user->picture->FldCaption() ?></span></td>
		<td data-name="picture"<?php echo $sana_user->picture->CellAttributes() ?>>
<span id="el_sana_user_picture">
<span<?php echo $sana_user->picture->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($sana_user->picture, $sana_user->picture->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->registrationUser->Visible) { // registrationUser ?>
	<tr id="r_registrationUser">
		<td><span id="elh_sana_user_registrationUser"><?php echo $sana_user->registrationUser->FldCaption() ?></span></td>
		<td data-name="registrationUser"<?php echo $sana_user->registrationUser->CellAttributes() ?>>
<span id="el_sana_user_registrationUser">
<span<?php echo $sana_user->registrationUser->ViewAttributes() ?>>
<?php echo $sana_user->registrationUser->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->registrationDateTime->Visible) { // registrationDateTime ?>
	<tr id="r_registrationDateTime">
		<td><span id="elh_sana_user_registrationDateTime"><?php echo $sana_user->registrationDateTime->FldCaption() ?></span></td>
		<td data-name="registrationDateTime"<?php echo $sana_user->registrationDateTime->CellAttributes() ?>>
<span id="el_sana_user_registrationDateTime">
<span<?php echo $sana_user->registrationDateTime->ViewAttributes() ?>>
<?php echo $sana_user->registrationDateTime->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->stationID->Visible) { // stationID ?>
	<tr id="r_stationID">
		<td><span id="elh_sana_user_stationID"><?php echo $sana_user->stationID->FldCaption() ?></span></td>
		<td data-name="stationID"<?php echo $sana_user->stationID->CellAttributes() ?>>
<span id="el_sana_user_stationID">
<span<?php echo $sana_user->stationID->ViewAttributes() ?>>
<?php echo $sana_user->stationID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->isolatedDateTime->Visible) { // isolatedDateTime ?>
	<tr id="r_isolatedDateTime">
		<td><span id="elh_sana_user_isolatedDateTime"><?php echo $sana_user->isolatedDateTime->FldCaption() ?></span></td>
		<td data-name="isolatedDateTime"<?php echo $sana_user->isolatedDateTime->CellAttributes() ?>>
<span id="el_sana_user_isolatedDateTime">
<span<?php echo $sana_user->isolatedDateTime->ViewAttributes() ?>>
<?php echo $sana_user->isolatedDateTime->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->acl->Visible) { // acl ?>
	<tr id="r_acl">
		<td><span id="elh_sana_user_acl"><?php echo $sana_user->acl->FldCaption() ?></span></td>
		<td data-name="acl"<?php echo $sana_user->acl->CellAttributes() ?>>
<span id="el_sana_user_acl">
<span<?php echo $sana_user->acl->ViewAttributes() ?>>
<?php echo $sana_user->acl->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_user->description->Visible) { // description ?>
	<tr id="r_description">
		<td><span id="elh_sana_user_description"><?php echo $sana_user->description->FldCaption() ?></span></td>
		<td data-name="description"<?php echo $sana_user->description->CellAttributes() ?>>
<span id="el_sana_user_description">
<span<?php echo $sana_user->description->ViewAttributes() ?>>
<?php echo $sana_user->description->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fsana_userview.Init();
</script>
<?php
$sana_user_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_user_view->Page_Terminate();
?>
