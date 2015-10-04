<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "sana_personinfo.php" ?>
<?php include_once "sana_userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$sana_person_view = NULL; // Initialize page object first

class csana_person_view extends csana_person {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_person';

	// Page object name
	var $PageObjName = 'sana_person_view';

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

		// Table object (sana_person)
		if (!isset($GLOBALS["sana_person"]) || get_class($GLOBALS["sana_person"]) == "csana_person") {
			$GLOBALS["sana_person"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_person"];
		}
		$KeyUrl = "";
		if (@$_GET["personID"] <> "") {
			$this->RecKey["personID"] = $_GET["personID"];
			$KeyUrl .= "&amp;personID=" . urlencode($this->RecKey["personID"]);
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
			define("EW_TABLE_NAME", 'sana_person', TRUE);

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
		$this->personID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $sana_person;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($sana_person);
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
			if (@$_GET["personID"] <> "") {
				$this->personID->setQueryStringValue($_GET["personID"]);
				$this->RecKey["personID"] = $this->personID->QueryStringValue;
			} elseif (@$_POST["personID"] <> "") {
				$this->personID->setFormValue($_POST["personID"]);
				$this->RecKey["personID"] = $this->personID->FormValue;
			} else {
				$sReturnUrl = "sana_personlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "sana_personlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "sana_personlist.php"; // Not page request, return to list
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
		$this->personID->setDbValue($rs->fields('personID'));
		$this->personName->setDbValue($rs->fields('personName'));
		$this->lastName->setDbValue($rs->fields('lastName'));
		$this->nationalID->setDbValue($rs->fields('nationalID'));
		$this->nationalNumber->setDbValue($rs->fields('nationalNumber'));
		$this->fatherName->setDbValue($rs->fields('fatherName'));
		$this->gender->setDbValue($rs->fields('gender'));
		$this->country->setDbValue($rs->fields('country'));
		$this->province->setDbValue($rs->fields('province'));
		$this->county->setDbValue($rs->fields('county'));
		$this->district->setDbValue($rs->fields('district'));
		$this->city_ruralDistrict->setDbValue($rs->fields('city_ruralDistrict'));
		$this->region_village->setDbValue($rs->fields('region_village'));
		$this->address->setDbValue($rs->fields('address'));
		$this->convoy->setDbValue($rs->fields('convoy'));
		$this->convoyManager->setDbValue($rs->fields('convoyManager'));
		$this->followersName->setDbValue($rs->fields('followersName'));
		$this->status->setDbValue($rs->fields('status'));
		$this->isolatedLocation->setDbValue($rs->fields('isolatedLocation'));
		$this->birthDate->setDbValue($rs->fields('birthDate'));
		$this->ageRange->setDbValue($rs->fields('ageRange'));
		$this->dress1->setDbValue($rs->fields('dress1'));
		$this->dress2->setDbValue($rs->fields('dress2'));
		$this->signTags->setDbValue($rs->fields('signTags'));
		$this->phone->setDbValue($rs->fields('phone'));
		$this->mobilePhone->setDbValue($rs->fields('mobilePhone'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->temporaryResidence->setDbValue($rs->fields('temporaryResidence'));
		$this->visitsCount->setDbValue($rs->fields('visitsCount'));
		$this->picture->setDbValue($rs->fields('picture'));
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
		$this->personID->DbValue = $row['personID'];
		$this->personName->DbValue = $row['personName'];
		$this->lastName->DbValue = $row['lastName'];
		$this->nationalID->DbValue = $row['nationalID'];
		$this->nationalNumber->DbValue = $row['nationalNumber'];
		$this->fatherName->DbValue = $row['fatherName'];
		$this->gender->DbValue = $row['gender'];
		$this->country->DbValue = $row['country'];
		$this->province->DbValue = $row['province'];
		$this->county->DbValue = $row['county'];
		$this->district->DbValue = $row['district'];
		$this->city_ruralDistrict->DbValue = $row['city_ruralDistrict'];
		$this->region_village->DbValue = $row['region_village'];
		$this->address->DbValue = $row['address'];
		$this->convoy->DbValue = $row['convoy'];
		$this->convoyManager->DbValue = $row['convoyManager'];
		$this->followersName->DbValue = $row['followersName'];
		$this->status->DbValue = $row['status'];
		$this->isolatedLocation->DbValue = $row['isolatedLocation'];
		$this->birthDate->DbValue = $row['birthDate'];
		$this->ageRange->DbValue = $row['ageRange'];
		$this->dress1->DbValue = $row['dress1'];
		$this->dress2->DbValue = $row['dress2'];
		$this->signTags->DbValue = $row['signTags'];
		$this->phone->DbValue = $row['phone'];
		$this->mobilePhone->DbValue = $row['mobilePhone'];
		$this->_email->DbValue = $row['email'];
		$this->temporaryResidence->DbValue = $row['temporaryResidence'];
		$this->visitsCount->DbValue = $row['visitsCount'];
		$this->picture->DbValue = $row['picture'];
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
		// personID
		// personName
		// lastName
		// nationalID
		// nationalNumber
		// fatherName
		// gender
		// country
		// province
		// county
		// district
		// city_ruralDistrict
		// region_village
		// address
		// convoy
		// convoyManager
		// followersName
		// status
		// isolatedLocation
		// birthDate
		// ageRange
		// dress1
		// dress2
		// signTags
		// phone
		// mobilePhone
		// email
		// temporaryResidence
		// visitsCount
		// picture
		// registrationUser
		// registrationDateTime
		// registrationStation
		// isolatedDateTime
		// description

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// personID
		$this->personID->ViewValue = $this->personID->CurrentValue;
		$this->personID->ViewCustomAttributes = "";

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

		// gender
		$this->gender->ViewValue = $this->gender->CurrentValue;
		$this->gender->ViewCustomAttributes = "";

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

		// convoy
		$this->convoy->ViewValue = $this->convoy->CurrentValue;
		$this->convoy->ViewCustomAttributes = "";

		// convoyManager
		$this->convoyManager->ViewValue = $this->convoyManager->CurrentValue;
		$this->convoyManager->ViewCustomAttributes = "";

		// followersName
		$this->followersName->ViewValue = $this->followersName->CurrentValue;
		$this->followersName->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// isolatedLocation
		$this->isolatedLocation->ViewValue = $this->isolatedLocation->CurrentValue;
		$this->isolatedLocation->ViewCustomAttributes = "";

		// birthDate
		$this->birthDate->ViewValue = $this->birthDate->CurrentValue;
		$this->birthDate->ViewCustomAttributes = "";

		// ageRange
		$this->ageRange->ViewValue = $this->ageRange->CurrentValue;
		$this->ageRange->ViewCustomAttributes = "";

		// dress1
		$this->dress1->ViewValue = $this->dress1->CurrentValue;
		$this->dress1->ViewCustomAttributes = "";

		// dress2
		$this->dress2->ViewValue = $this->dress2->CurrentValue;
		$this->dress2->ViewCustomAttributes = "";

		// signTags
		$this->signTags->ViewValue = $this->signTags->CurrentValue;
		$this->signTags->ViewCustomAttributes = "";

		// phone
		$this->phone->ViewValue = $this->phone->CurrentValue;
		$this->phone->ViewCustomAttributes = "";

		// mobilePhone
		$this->mobilePhone->ViewValue = $this->mobilePhone->CurrentValue;
		$this->mobilePhone->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// temporaryResidence
		$this->temporaryResidence->ViewValue = $this->temporaryResidence->CurrentValue;
		$this->temporaryResidence->ViewCustomAttributes = "";

		// visitsCount
		$this->visitsCount->ViewValue = $this->visitsCount->CurrentValue;
		$this->visitsCount->ViewCustomAttributes = "";

		// picture
		$this->picture->ViewValue = $this->picture->CurrentValue;
		$this->picture->ViewCustomAttributes = "";

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

			// personID
			$this->personID->LinkCustomAttributes = "";
			$this->personID->HrefValue = "";
			$this->personID->TooltipValue = "";

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

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";
			$this->gender->TooltipValue = "";

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

			// convoy
			$this->convoy->LinkCustomAttributes = "";
			$this->convoy->HrefValue = "";
			$this->convoy->TooltipValue = "";

			// convoyManager
			$this->convoyManager->LinkCustomAttributes = "";
			$this->convoyManager->HrefValue = "";
			$this->convoyManager->TooltipValue = "";

			// followersName
			$this->followersName->LinkCustomAttributes = "";
			$this->followersName->HrefValue = "";
			$this->followersName->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// isolatedLocation
			$this->isolatedLocation->LinkCustomAttributes = "";
			$this->isolatedLocation->HrefValue = "";
			$this->isolatedLocation->TooltipValue = "";

			// birthDate
			$this->birthDate->LinkCustomAttributes = "";
			$this->birthDate->HrefValue = "";
			$this->birthDate->TooltipValue = "";

			// ageRange
			$this->ageRange->LinkCustomAttributes = "";
			$this->ageRange->HrefValue = "";
			$this->ageRange->TooltipValue = "";

			// dress1
			$this->dress1->LinkCustomAttributes = "";
			$this->dress1->HrefValue = "";
			$this->dress1->TooltipValue = "";

			// dress2
			$this->dress2->LinkCustomAttributes = "";
			$this->dress2->HrefValue = "";
			$this->dress2->TooltipValue = "";

			// signTags
			$this->signTags->LinkCustomAttributes = "";
			$this->signTags->HrefValue = "";
			$this->signTags->TooltipValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";
			$this->phone->TooltipValue = "";

			// mobilePhone
			$this->mobilePhone->LinkCustomAttributes = "";
			$this->mobilePhone->HrefValue = "";
			$this->mobilePhone->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// temporaryResidence
			$this->temporaryResidence->LinkCustomAttributes = "";
			$this->temporaryResidence->HrefValue = "";
			$this->temporaryResidence->TooltipValue = "";

			// visitsCount
			$this->visitsCount->LinkCustomAttributes = "";
			$this->visitsCount->HrefValue = "";
			$this->visitsCount->TooltipValue = "";

			// picture
			$this->picture->LinkCustomAttributes = "";
			$this->picture->HrefValue = "";
			$this->picture->TooltipValue = "";

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sana_personlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($sana_person_view)) $sana_person_view = new csana_person_view();

// Page init
$sana_person_view->Page_Init();

// Page main
$sana_person_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_person_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fsana_personview = new ew_Form("fsana_personview", "view");

// Form_CustomValidate event
fsana_personview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_personview.ValidateRequired = true;
<?php } else { ?>
fsana_personview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $sana_person_view->ExportOptions->Render("body") ?>
<?php
	foreach ($sana_person_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $sana_person_view->ShowPageHeader(); ?>
<?php
$sana_person_view->ShowMessage();
?>
<form name="fsana_personview" id="fsana_personview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_person_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_person_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_person">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($sana_person->personID->Visible) { // personID ?>
	<tr id="r_personID">
		<td><span id="elh_sana_person_personID"><?php echo $sana_person->personID->FldCaption() ?></span></td>
		<td data-name="personID"<?php echo $sana_person->personID->CellAttributes() ?>>
<span id="el_sana_person_personID">
<span<?php echo $sana_person->personID->ViewAttributes() ?>>
<?php echo $sana_person->personID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->personName->Visible) { // personName ?>
	<tr id="r_personName">
		<td><span id="elh_sana_person_personName"><?php echo $sana_person->personName->FldCaption() ?></span></td>
		<td data-name="personName"<?php echo $sana_person->personName->CellAttributes() ?>>
<span id="el_sana_person_personName">
<span<?php echo $sana_person->personName->ViewAttributes() ?>>
<?php echo $sana_person->personName->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->lastName->Visible) { // lastName ?>
	<tr id="r_lastName">
		<td><span id="elh_sana_person_lastName"><?php echo $sana_person->lastName->FldCaption() ?></span></td>
		<td data-name="lastName"<?php echo $sana_person->lastName->CellAttributes() ?>>
<span id="el_sana_person_lastName">
<span<?php echo $sana_person->lastName->ViewAttributes() ?>>
<?php echo $sana_person->lastName->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->nationalID->Visible) { // nationalID ?>
	<tr id="r_nationalID">
		<td><span id="elh_sana_person_nationalID"><?php echo $sana_person->nationalID->FldCaption() ?></span></td>
		<td data-name="nationalID"<?php echo $sana_person->nationalID->CellAttributes() ?>>
<span id="el_sana_person_nationalID">
<span<?php echo $sana_person->nationalID->ViewAttributes() ?>>
<?php echo $sana_person->nationalID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->nationalNumber->Visible) { // nationalNumber ?>
	<tr id="r_nationalNumber">
		<td><span id="elh_sana_person_nationalNumber"><?php echo $sana_person->nationalNumber->FldCaption() ?></span></td>
		<td data-name="nationalNumber"<?php echo $sana_person->nationalNumber->CellAttributes() ?>>
<span id="el_sana_person_nationalNumber">
<span<?php echo $sana_person->nationalNumber->ViewAttributes() ?>>
<?php echo $sana_person->nationalNumber->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->fatherName->Visible) { // fatherName ?>
	<tr id="r_fatherName">
		<td><span id="elh_sana_person_fatherName"><?php echo $sana_person->fatherName->FldCaption() ?></span></td>
		<td data-name="fatherName"<?php echo $sana_person->fatherName->CellAttributes() ?>>
<span id="el_sana_person_fatherName">
<span<?php echo $sana_person->fatherName->ViewAttributes() ?>>
<?php echo $sana_person->fatherName->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->gender->Visible) { // gender ?>
	<tr id="r_gender">
		<td><span id="elh_sana_person_gender"><?php echo $sana_person->gender->FldCaption() ?></span></td>
		<td data-name="gender"<?php echo $sana_person->gender->CellAttributes() ?>>
<span id="el_sana_person_gender">
<span<?php echo $sana_person->gender->ViewAttributes() ?>>
<?php echo $sana_person->gender->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->country->Visible) { // country ?>
	<tr id="r_country">
		<td><span id="elh_sana_person_country"><?php echo $sana_person->country->FldCaption() ?></span></td>
		<td data-name="country"<?php echo $sana_person->country->CellAttributes() ?>>
<span id="el_sana_person_country">
<span<?php echo $sana_person->country->ViewAttributes() ?>>
<?php echo $sana_person->country->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->province->Visible) { // province ?>
	<tr id="r_province">
		<td><span id="elh_sana_person_province"><?php echo $sana_person->province->FldCaption() ?></span></td>
		<td data-name="province"<?php echo $sana_person->province->CellAttributes() ?>>
<span id="el_sana_person_province">
<span<?php echo $sana_person->province->ViewAttributes() ?>>
<?php echo $sana_person->province->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->county->Visible) { // county ?>
	<tr id="r_county">
		<td><span id="elh_sana_person_county"><?php echo $sana_person->county->FldCaption() ?></span></td>
		<td data-name="county"<?php echo $sana_person->county->CellAttributes() ?>>
<span id="el_sana_person_county">
<span<?php echo $sana_person->county->ViewAttributes() ?>>
<?php echo $sana_person->county->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->district->Visible) { // district ?>
	<tr id="r_district">
		<td><span id="elh_sana_person_district"><?php echo $sana_person->district->FldCaption() ?></span></td>
		<td data-name="district"<?php echo $sana_person->district->CellAttributes() ?>>
<span id="el_sana_person_district">
<span<?php echo $sana_person->district->ViewAttributes() ?>>
<?php echo $sana_person->district->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->city_ruralDistrict->Visible) { // city_ruralDistrict ?>
	<tr id="r_city_ruralDistrict">
		<td><span id="elh_sana_person_city_ruralDistrict"><?php echo $sana_person->city_ruralDistrict->FldCaption() ?></span></td>
		<td data-name="city_ruralDistrict"<?php echo $sana_person->city_ruralDistrict->CellAttributes() ?>>
<span id="el_sana_person_city_ruralDistrict">
<span<?php echo $sana_person->city_ruralDistrict->ViewAttributes() ?>>
<?php echo $sana_person->city_ruralDistrict->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->region_village->Visible) { // region_village ?>
	<tr id="r_region_village">
		<td><span id="elh_sana_person_region_village"><?php echo $sana_person->region_village->FldCaption() ?></span></td>
		<td data-name="region_village"<?php echo $sana_person->region_village->CellAttributes() ?>>
<span id="el_sana_person_region_village">
<span<?php echo $sana_person->region_village->ViewAttributes() ?>>
<?php echo $sana_person->region_village->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->address->Visible) { // address ?>
	<tr id="r_address">
		<td><span id="elh_sana_person_address"><?php echo $sana_person->address->FldCaption() ?></span></td>
		<td data-name="address"<?php echo $sana_person->address->CellAttributes() ?>>
<span id="el_sana_person_address">
<span<?php echo $sana_person->address->ViewAttributes() ?>>
<?php echo $sana_person->address->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->convoy->Visible) { // convoy ?>
	<tr id="r_convoy">
		<td><span id="elh_sana_person_convoy"><?php echo $sana_person->convoy->FldCaption() ?></span></td>
		<td data-name="convoy"<?php echo $sana_person->convoy->CellAttributes() ?>>
<span id="el_sana_person_convoy">
<span<?php echo $sana_person->convoy->ViewAttributes() ?>>
<?php echo $sana_person->convoy->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->convoyManager->Visible) { // convoyManager ?>
	<tr id="r_convoyManager">
		<td><span id="elh_sana_person_convoyManager"><?php echo $sana_person->convoyManager->FldCaption() ?></span></td>
		<td data-name="convoyManager"<?php echo $sana_person->convoyManager->CellAttributes() ?>>
<span id="el_sana_person_convoyManager">
<span<?php echo $sana_person->convoyManager->ViewAttributes() ?>>
<?php echo $sana_person->convoyManager->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->followersName->Visible) { // followersName ?>
	<tr id="r_followersName">
		<td><span id="elh_sana_person_followersName"><?php echo $sana_person->followersName->FldCaption() ?></span></td>
		<td data-name="followersName"<?php echo $sana_person->followersName->CellAttributes() ?>>
<span id="el_sana_person_followersName">
<span<?php echo $sana_person->followersName->ViewAttributes() ?>>
<?php echo $sana_person->followersName->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_sana_person_status"><?php echo $sana_person->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $sana_person->status->CellAttributes() ?>>
<span id="el_sana_person_status">
<span<?php echo $sana_person->status->ViewAttributes() ?>>
<?php echo $sana_person->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->isolatedLocation->Visible) { // isolatedLocation ?>
	<tr id="r_isolatedLocation">
		<td><span id="elh_sana_person_isolatedLocation"><?php echo $sana_person->isolatedLocation->FldCaption() ?></span></td>
		<td data-name="isolatedLocation"<?php echo $sana_person->isolatedLocation->CellAttributes() ?>>
<span id="el_sana_person_isolatedLocation">
<span<?php echo $sana_person->isolatedLocation->ViewAttributes() ?>>
<?php echo $sana_person->isolatedLocation->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->birthDate->Visible) { // birthDate ?>
	<tr id="r_birthDate">
		<td><span id="elh_sana_person_birthDate"><?php echo $sana_person->birthDate->FldCaption() ?></span></td>
		<td data-name="birthDate"<?php echo $sana_person->birthDate->CellAttributes() ?>>
<span id="el_sana_person_birthDate">
<span<?php echo $sana_person->birthDate->ViewAttributes() ?>>
<?php echo $sana_person->birthDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->ageRange->Visible) { // ageRange ?>
	<tr id="r_ageRange">
		<td><span id="elh_sana_person_ageRange"><?php echo $sana_person->ageRange->FldCaption() ?></span></td>
		<td data-name="ageRange"<?php echo $sana_person->ageRange->CellAttributes() ?>>
<span id="el_sana_person_ageRange">
<span<?php echo $sana_person->ageRange->ViewAttributes() ?>>
<?php echo $sana_person->ageRange->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->dress1->Visible) { // dress1 ?>
	<tr id="r_dress1">
		<td><span id="elh_sana_person_dress1"><?php echo $sana_person->dress1->FldCaption() ?></span></td>
		<td data-name="dress1"<?php echo $sana_person->dress1->CellAttributes() ?>>
<span id="el_sana_person_dress1">
<span<?php echo $sana_person->dress1->ViewAttributes() ?>>
<?php echo $sana_person->dress1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->dress2->Visible) { // dress2 ?>
	<tr id="r_dress2">
		<td><span id="elh_sana_person_dress2"><?php echo $sana_person->dress2->FldCaption() ?></span></td>
		<td data-name="dress2"<?php echo $sana_person->dress2->CellAttributes() ?>>
<span id="el_sana_person_dress2">
<span<?php echo $sana_person->dress2->ViewAttributes() ?>>
<?php echo $sana_person->dress2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->signTags->Visible) { // signTags ?>
	<tr id="r_signTags">
		<td><span id="elh_sana_person_signTags"><?php echo $sana_person->signTags->FldCaption() ?></span></td>
		<td data-name="signTags"<?php echo $sana_person->signTags->CellAttributes() ?>>
<span id="el_sana_person_signTags">
<span<?php echo $sana_person->signTags->ViewAttributes() ?>>
<?php echo $sana_person->signTags->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->phone->Visible) { // phone ?>
	<tr id="r_phone">
		<td><span id="elh_sana_person_phone"><?php echo $sana_person->phone->FldCaption() ?></span></td>
		<td data-name="phone"<?php echo $sana_person->phone->CellAttributes() ?>>
<span id="el_sana_person_phone">
<span<?php echo $sana_person->phone->ViewAttributes() ?>>
<?php echo $sana_person->phone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->mobilePhone->Visible) { // mobilePhone ?>
	<tr id="r_mobilePhone">
		<td><span id="elh_sana_person_mobilePhone"><?php echo $sana_person->mobilePhone->FldCaption() ?></span></td>
		<td data-name="mobilePhone"<?php echo $sana_person->mobilePhone->CellAttributes() ?>>
<span id="el_sana_person_mobilePhone">
<span<?php echo $sana_person->mobilePhone->ViewAttributes() ?>>
<?php echo $sana_person->mobilePhone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_sana_person__email"><?php echo $sana_person->_email->FldCaption() ?></span></td>
		<td data-name="_email"<?php echo $sana_person->_email->CellAttributes() ?>>
<span id="el_sana_person__email">
<span<?php echo $sana_person->_email->ViewAttributes() ?>>
<?php echo $sana_person->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->temporaryResidence->Visible) { // temporaryResidence ?>
	<tr id="r_temporaryResidence">
		<td><span id="elh_sana_person_temporaryResidence"><?php echo $sana_person->temporaryResidence->FldCaption() ?></span></td>
		<td data-name="temporaryResidence"<?php echo $sana_person->temporaryResidence->CellAttributes() ?>>
<span id="el_sana_person_temporaryResidence">
<span<?php echo $sana_person->temporaryResidence->ViewAttributes() ?>>
<?php echo $sana_person->temporaryResidence->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->visitsCount->Visible) { // visitsCount ?>
	<tr id="r_visitsCount">
		<td><span id="elh_sana_person_visitsCount"><?php echo $sana_person->visitsCount->FldCaption() ?></span></td>
		<td data-name="visitsCount"<?php echo $sana_person->visitsCount->CellAttributes() ?>>
<span id="el_sana_person_visitsCount">
<span<?php echo $sana_person->visitsCount->ViewAttributes() ?>>
<?php echo $sana_person->visitsCount->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->picture->Visible) { // picture ?>
	<tr id="r_picture">
		<td><span id="elh_sana_person_picture"><?php echo $sana_person->picture->FldCaption() ?></span></td>
		<td data-name="picture"<?php echo $sana_person->picture->CellAttributes() ?>>
<span id="el_sana_person_picture">
<span<?php echo $sana_person->picture->ViewAttributes() ?>>
<?php echo $sana_person->picture->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->registrationUser->Visible) { // registrationUser ?>
	<tr id="r_registrationUser">
		<td><span id="elh_sana_person_registrationUser"><?php echo $sana_person->registrationUser->FldCaption() ?></span></td>
		<td data-name="registrationUser"<?php echo $sana_person->registrationUser->CellAttributes() ?>>
<span id="el_sana_person_registrationUser">
<span<?php echo $sana_person->registrationUser->ViewAttributes() ?>>
<?php echo $sana_person->registrationUser->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->registrationDateTime->Visible) { // registrationDateTime ?>
	<tr id="r_registrationDateTime">
		<td><span id="elh_sana_person_registrationDateTime"><?php echo $sana_person->registrationDateTime->FldCaption() ?></span></td>
		<td data-name="registrationDateTime"<?php echo $sana_person->registrationDateTime->CellAttributes() ?>>
<span id="el_sana_person_registrationDateTime">
<span<?php echo $sana_person->registrationDateTime->ViewAttributes() ?>>
<?php echo $sana_person->registrationDateTime->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->registrationStation->Visible) { // registrationStation ?>
	<tr id="r_registrationStation">
		<td><span id="elh_sana_person_registrationStation"><?php echo $sana_person->registrationStation->FldCaption() ?></span></td>
		<td data-name="registrationStation"<?php echo $sana_person->registrationStation->CellAttributes() ?>>
<span id="el_sana_person_registrationStation">
<span<?php echo $sana_person->registrationStation->ViewAttributes() ?>>
<?php echo $sana_person->registrationStation->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->isolatedDateTime->Visible) { // isolatedDateTime ?>
	<tr id="r_isolatedDateTime">
		<td><span id="elh_sana_person_isolatedDateTime"><?php echo $sana_person->isolatedDateTime->FldCaption() ?></span></td>
		<td data-name="isolatedDateTime"<?php echo $sana_person->isolatedDateTime->CellAttributes() ?>>
<span id="el_sana_person_isolatedDateTime">
<span<?php echo $sana_person->isolatedDateTime->ViewAttributes() ?>>
<?php echo $sana_person->isolatedDateTime->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sana_person->description->Visible) { // description ?>
	<tr id="r_description">
		<td><span id="elh_sana_person_description"><?php echo $sana_person->description->FldCaption() ?></span></td>
		<td data-name="description"<?php echo $sana_person->description->CellAttributes() ?>>
<span id="el_sana_person_description">
<span<?php echo $sana_person->description->ViewAttributes() ?>>
<?php echo $sana_person->description->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fsana_personview.Init();
</script>
<?php
$sana_person_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_person_view->Page_Terminate();
?>
