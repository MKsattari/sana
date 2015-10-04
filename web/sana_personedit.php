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

$sana_person_edit = NULL; // Initialize page object first

class csana_person_edit extends csana_person {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_person';

	// Page object name
	var $PageObjName = 'sana_person_edit';

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

		// Table object (sana_person)
		if (!isset($GLOBALS["sana_person"]) || get_class($GLOBALS["sana_person"]) == "csana_person") {
			$GLOBALS["sana_person"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_person"];
		}

		// Table object (sana_user)
		if (!isset($GLOBALS['sana_user'])) $GLOBALS['sana_user'] = new csana_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["personID"] <> "") {
			$this->personID->setQueryStringValue($_GET["personID"]);
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
		if ($this->personID->CurrentValue == "")
			$this->Page_Terminate("sana_personlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("sana_personlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "sana_personlist.php")
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
		if (!$this->personID->FldIsDetailKey)
			$this->personID->setFormValue($objForm->GetValue("x_personID"));
		if (!$this->personName->FldIsDetailKey) {
			$this->personName->setFormValue($objForm->GetValue("x_personName"));
		}
		if (!$this->lastName->FldIsDetailKey) {
			$this->lastName->setFormValue($objForm->GetValue("x_lastName"));
		}
		if (!$this->nationalID->FldIsDetailKey) {
			$this->nationalID->setFormValue($objForm->GetValue("x_nationalID"));
		}
		if (!$this->nationalNumber->FldIsDetailKey) {
			$this->nationalNumber->setFormValue($objForm->GetValue("x_nationalNumber"));
		}
		if (!$this->fatherName->FldIsDetailKey) {
			$this->fatherName->setFormValue($objForm->GetValue("x_fatherName"));
		}
		if (!$this->gender->FldIsDetailKey) {
			$this->gender->setFormValue($objForm->GetValue("x_gender"));
		}
		if (!$this->country->FldIsDetailKey) {
			$this->country->setFormValue($objForm->GetValue("x_country"));
		}
		if (!$this->province->FldIsDetailKey) {
			$this->province->setFormValue($objForm->GetValue("x_province"));
		}
		if (!$this->county->FldIsDetailKey) {
			$this->county->setFormValue($objForm->GetValue("x_county"));
		}
		if (!$this->district->FldIsDetailKey) {
			$this->district->setFormValue($objForm->GetValue("x_district"));
		}
		if (!$this->city_ruralDistrict->FldIsDetailKey) {
			$this->city_ruralDistrict->setFormValue($objForm->GetValue("x_city_ruralDistrict"));
		}
		if (!$this->region_village->FldIsDetailKey) {
			$this->region_village->setFormValue($objForm->GetValue("x_region_village"));
		}
		if (!$this->address->FldIsDetailKey) {
			$this->address->setFormValue($objForm->GetValue("x_address"));
		}
		if (!$this->convoy->FldIsDetailKey) {
			$this->convoy->setFormValue($objForm->GetValue("x_convoy"));
		}
		if (!$this->convoyManager->FldIsDetailKey) {
			$this->convoyManager->setFormValue($objForm->GetValue("x_convoyManager"));
		}
		if (!$this->followersName->FldIsDetailKey) {
			$this->followersName->setFormValue($objForm->GetValue("x_followersName"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->isolatedLocation->FldIsDetailKey) {
			$this->isolatedLocation->setFormValue($objForm->GetValue("x_isolatedLocation"));
		}
		if (!$this->birthDate->FldIsDetailKey) {
			$this->birthDate->setFormValue($objForm->GetValue("x_birthDate"));
		}
		if (!$this->ageRange->FldIsDetailKey) {
			$this->ageRange->setFormValue($objForm->GetValue("x_ageRange"));
		}
		if (!$this->dress1->FldIsDetailKey) {
			$this->dress1->setFormValue($objForm->GetValue("x_dress1"));
		}
		if (!$this->dress2->FldIsDetailKey) {
			$this->dress2->setFormValue($objForm->GetValue("x_dress2"));
		}
		if (!$this->signTags->FldIsDetailKey) {
			$this->signTags->setFormValue($objForm->GetValue("x_signTags"));
		}
		if (!$this->phone->FldIsDetailKey) {
			$this->phone->setFormValue($objForm->GetValue("x_phone"));
		}
		if (!$this->mobilePhone->FldIsDetailKey) {
			$this->mobilePhone->setFormValue($objForm->GetValue("x_mobilePhone"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->temporaryResidence->FldIsDetailKey) {
			$this->temporaryResidence->setFormValue($objForm->GetValue("x_temporaryResidence"));
		}
		if (!$this->visitsCount->FldIsDetailKey) {
			$this->visitsCount->setFormValue($objForm->GetValue("x_visitsCount"));
		}
		if (!$this->picture->FldIsDetailKey) {
			$this->picture->setFormValue($objForm->GetValue("x_picture"));
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
		$this->personID->CurrentValue = $this->personID->FormValue;
		$this->personName->CurrentValue = $this->personName->FormValue;
		$this->lastName->CurrentValue = $this->lastName->FormValue;
		$this->nationalID->CurrentValue = $this->nationalID->FormValue;
		$this->nationalNumber->CurrentValue = $this->nationalNumber->FormValue;
		$this->fatherName->CurrentValue = $this->fatherName->FormValue;
		$this->gender->CurrentValue = $this->gender->FormValue;
		$this->country->CurrentValue = $this->country->FormValue;
		$this->province->CurrentValue = $this->province->FormValue;
		$this->county->CurrentValue = $this->county->FormValue;
		$this->district->CurrentValue = $this->district->FormValue;
		$this->city_ruralDistrict->CurrentValue = $this->city_ruralDistrict->FormValue;
		$this->region_village->CurrentValue = $this->region_village->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->convoy->CurrentValue = $this->convoy->FormValue;
		$this->convoyManager->CurrentValue = $this->convoyManager->FormValue;
		$this->followersName->CurrentValue = $this->followersName->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->isolatedLocation->CurrentValue = $this->isolatedLocation->FormValue;
		$this->birthDate->CurrentValue = $this->birthDate->FormValue;
		$this->ageRange->CurrentValue = $this->ageRange->FormValue;
		$this->dress1->CurrentValue = $this->dress1->FormValue;
		$this->dress2->CurrentValue = $this->dress2->FormValue;
		$this->signTags->CurrentValue = $this->signTags->FormValue;
		$this->phone->CurrentValue = $this->phone->FormValue;
		$this->mobilePhone->CurrentValue = $this->mobilePhone->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->temporaryResidence->CurrentValue = $this->temporaryResidence->FormValue;
		$this->visitsCount->CurrentValue = $this->visitsCount->FormValue;
		$this->picture->CurrentValue = $this->picture->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// personID
			$this->personID->EditAttrs["class"] = "form-control";
			$this->personID->EditCustomAttributes = "";
			$this->personID->EditValue = $this->personID->CurrentValue;
			$this->personID->ViewCustomAttributes = "";

			// personName
			$this->personName->EditAttrs["class"] = "form-control";
			$this->personName->EditCustomAttributes = "";
			$this->personName->EditValue = ew_HtmlEncode($this->personName->CurrentValue);
			$this->personName->PlaceHolder = ew_RemoveHtml($this->personName->FldCaption());

			// lastName
			$this->lastName->EditAttrs["class"] = "form-control";
			$this->lastName->EditCustomAttributes = "";
			$this->lastName->EditValue = ew_HtmlEncode($this->lastName->CurrentValue);
			$this->lastName->PlaceHolder = ew_RemoveHtml($this->lastName->FldCaption());

			// nationalID
			$this->nationalID->EditAttrs["class"] = "form-control";
			$this->nationalID->EditCustomAttributes = "";
			$this->nationalID->EditValue = ew_HtmlEncode($this->nationalID->CurrentValue);
			$this->nationalID->PlaceHolder = ew_RemoveHtml($this->nationalID->FldCaption());

			// nationalNumber
			$this->nationalNumber->EditAttrs["class"] = "form-control";
			$this->nationalNumber->EditCustomAttributes = "";
			$this->nationalNumber->EditValue = ew_HtmlEncode($this->nationalNumber->CurrentValue);
			$this->nationalNumber->PlaceHolder = ew_RemoveHtml($this->nationalNumber->FldCaption());

			// fatherName
			$this->fatherName->EditAttrs["class"] = "form-control";
			$this->fatherName->EditCustomAttributes = "";
			$this->fatherName->EditValue = ew_HtmlEncode($this->fatherName->CurrentValue);
			$this->fatherName->PlaceHolder = ew_RemoveHtml($this->fatherName->FldCaption());

			// gender
			$this->gender->EditAttrs["class"] = "form-control";
			$this->gender->EditCustomAttributes = "";
			$this->gender->EditValue = ew_HtmlEncode($this->gender->CurrentValue);
			$this->gender->PlaceHolder = ew_RemoveHtml($this->gender->FldCaption());

			// country
			$this->country->EditAttrs["class"] = "form-control";
			$this->country->EditCustomAttributes = "";
			$this->country->EditValue = ew_HtmlEncode($this->country->CurrentValue);
			$this->country->PlaceHolder = ew_RemoveHtml($this->country->FldCaption());

			// province
			$this->province->EditAttrs["class"] = "form-control";
			$this->province->EditCustomAttributes = "";
			$this->province->EditValue = ew_HtmlEncode($this->province->CurrentValue);
			$this->province->PlaceHolder = ew_RemoveHtml($this->province->FldCaption());

			// county
			$this->county->EditAttrs["class"] = "form-control";
			$this->county->EditCustomAttributes = "";
			$this->county->EditValue = ew_HtmlEncode($this->county->CurrentValue);
			$this->county->PlaceHolder = ew_RemoveHtml($this->county->FldCaption());

			// district
			$this->district->EditAttrs["class"] = "form-control";
			$this->district->EditCustomAttributes = "";
			$this->district->EditValue = ew_HtmlEncode($this->district->CurrentValue);
			$this->district->PlaceHolder = ew_RemoveHtml($this->district->FldCaption());

			// city_ruralDistrict
			$this->city_ruralDistrict->EditAttrs["class"] = "form-control";
			$this->city_ruralDistrict->EditCustomAttributes = "";
			$this->city_ruralDistrict->EditValue = ew_HtmlEncode($this->city_ruralDistrict->CurrentValue);
			$this->city_ruralDistrict->PlaceHolder = ew_RemoveHtml($this->city_ruralDistrict->FldCaption());

			// region_village
			$this->region_village->EditAttrs["class"] = "form-control";
			$this->region_village->EditCustomAttributes = "";
			$this->region_village->EditValue = ew_HtmlEncode($this->region_village->CurrentValue);
			$this->region_village->PlaceHolder = ew_RemoveHtml($this->region_village->FldCaption());

			// address
			$this->address->EditAttrs["class"] = "form-control";
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = ew_HtmlEncode($this->address->CurrentValue);
			$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

			// convoy
			$this->convoy->EditAttrs["class"] = "form-control";
			$this->convoy->EditCustomAttributes = "";
			$this->convoy->EditValue = ew_HtmlEncode($this->convoy->CurrentValue);
			$this->convoy->PlaceHolder = ew_RemoveHtml($this->convoy->FldCaption());

			// convoyManager
			$this->convoyManager->EditAttrs["class"] = "form-control";
			$this->convoyManager->EditCustomAttributes = "";
			$this->convoyManager->EditValue = ew_HtmlEncode($this->convoyManager->CurrentValue);
			$this->convoyManager->PlaceHolder = ew_RemoveHtml($this->convoyManager->FldCaption());

			// followersName
			$this->followersName->EditAttrs["class"] = "form-control";
			$this->followersName->EditCustomAttributes = "";
			$this->followersName->EditValue = ew_HtmlEncode($this->followersName->CurrentValue);
			$this->followersName->PlaceHolder = ew_RemoveHtml($this->followersName->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// isolatedLocation
			$this->isolatedLocation->EditAttrs["class"] = "form-control";
			$this->isolatedLocation->EditCustomAttributes = "";
			$this->isolatedLocation->EditValue = ew_HtmlEncode($this->isolatedLocation->CurrentValue);
			$this->isolatedLocation->PlaceHolder = ew_RemoveHtml($this->isolatedLocation->FldCaption());

			// birthDate
			$this->birthDate->EditAttrs["class"] = "form-control";
			$this->birthDate->EditCustomAttributes = "";
			$this->birthDate->EditValue = ew_HtmlEncode($this->birthDate->CurrentValue);
			$this->birthDate->PlaceHolder = ew_RemoveHtml($this->birthDate->FldCaption());

			// ageRange
			$this->ageRange->EditAttrs["class"] = "form-control";
			$this->ageRange->EditCustomAttributes = "";
			$this->ageRange->EditValue = ew_HtmlEncode($this->ageRange->CurrentValue);
			$this->ageRange->PlaceHolder = ew_RemoveHtml($this->ageRange->FldCaption());

			// dress1
			$this->dress1->EditAttrs["class"] = "form-control";
			$this->dress1->EditCustomAttributes = "";
			$this->dress1->EditValue = ew_HtmlEncode($this->dress1->CurrentValue);
			$this->dress1->PlaceHolder = ew_RemoveHtml($this->dress1->FldCaption());

			// dress2
			$this->dress2->EditAttrs["class"] = "form-control";
			$this->dress2->EditCustomAttributes = "";
			$this->dress2->EditValue = ew_HtmlEncode($this->dress2->CurrentValue);
			$this->dress2->PlaceHolder = ew_RemoveHtml($this->dress2->FldCaption());

			// signTags
			$this->signTags->EditAttrs["class"] = "form-control";
			$this->signTags->EditCustomAttributes = "";
			$this->signTags->EditValue = ew_HtmlEncode($this->signTags->CurrentValue);
			$this->signTags->PlaceHolder = ew_RemoveHtml($this->signTags->FldCaption());

			// phone
			$this->phone->EditAttrs["class"] = "form-control";
			$this->phone->EditCustomAttributes = "";
			$this->phone->EditValue = ew_HtmlEncode($this->phone->CurrentValue);
			$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

			// mobilePhone
			$this->mobilePhone->EditAttrs["class"] = "form-control";
			$this->mobilePhone->EditCustomAttributes = "";
			$this->mobilePhone->EditValue = ew_HtmlEncode($this->mobilePhone->CurrentValue);
			$this->mobilePhone->PlaceHolder = ew_RemoveHtml($this->mobilePhone->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// temporaryResidence
			$this->temporaryResidence->EditAttrs["class"] = "form-control";
			$this->temporaryResidence->EditCustomAttributes = "";
			$this->temporaryResidence->EditValue = ew_HtmlEncode($this->temporaryResidence->CurrentValue);
			$this->temporaryResidence->PlaceHolder = ew_RemoveHtml($this->temporaryResidence->FldCaption());

			// visitsCount
			$this->visitsCount->EditAttrs["class"] = "form-control";
			$this->visitsCount->EditCustomAttributes = "";
			$this->visitsCount->EditValue = ew_HtmlEncode($this->visitsCount->CurrentValue);
			$this->visitsCount->PlaceHolder = ew_RemoveHtml($this->visitsCount->FldCaption());

			// picture
			$this->picture->EditAttrs["class"] = "form-control";
			$this->picture->EditCustomAttributes = "";
			$this->picture->EditValue = ew_HtmlEncode($this->picture->CurrentValue);
			$this->picture->PlaceHolder = ew_RemoveHtml($this->picture->FldCaption());

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
			// personID

			$this->personID->LinkCustomAttributes = "";
			$this->personID->HrefValue = "";

			// personName
			$this->personName->LinkCustomAttributes = "";
			$this->personName->HrefValue = "";

			// lastName
			$this->lastName->LinkCustomAttributes = "";
			$this->lastName->HrefValue = "";

			// nationalID
			$this->nationalID->LinkCustomAttributes = "";
			$this->nationalID->HrefValue = "";

			// nationalNumber
			$this->nationalNumber->LinkCustomAttributes = "";
			$this->nationalNumber->HrefValue = "";

			// fatherName
			$this->fatherName->LinkCustomAttributes = "";
			$this->fatherName->HrefValue = "";

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";

			// country
			$this->country->LinkCustomAttributes = "";
			$this->country->HrefValue = "";

			// province
			$this->province->LinkCustomAttributes = "";
			$this->province->HrefValue = "";

			// county
			$this->county->LinkCustomAttributes = "";
			$this->county->HrefValue = "";

			// district
			$this->district->LinkCustomAttributes = "";
			$this->district->HrefValue = "";

			// city_ruralDistrict
			$this->city_ruralDistrict->LinkCustomAttributes = "";
			$this->city_ruralDistrict->HrefValue = "";

			// region_village
			$this->region_village->LinkCustomAttributes = "";
			$this->region_village->HrefValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";

			// convoy
			$this->convoy->LinkCustomAttributes = "";
			$this->convoy->HrefValue = "";

			// convoyManager
			$this->convoyManager->LinkCustomAttributes = "";
			$this->convoyManager->HrefValue = "";

			// followersName
			$this->followersName->LinkCustomAttributes = "";
			$this->followersName->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// isolatedLocation
			$this->isolatedLocation->LinkCustomAttributes = "";
			$this->isolatedLocation->HrefValue = "";

			// birthDate
			$this->birthDate->LinkCustomAttributes = "";
			$this->birthDate->HrefValue = "";

			// ageRange
			$this->ageRange->LinkCustomAttributes = "";
			$this->ageRange->HrefValue = "";

			// dress1
			$this->dress1->LinkCustomAttributes = "";
			$this->dress1->HrefValue = "";

			// dress2
			$this->dress2->LinkCustomAttributes = "";
			$this->dress2->HrefValue = "";

			// signTags
			$this->signTags->LinkCustomAttributes = "";
			$this->signTags->HrefValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";

			// mobilePhone
			$this->mobilePhone->LinkCustomAttributes = "";
			$this->mobilePhone->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// temporaryResidence
			$this->temporaryResidence->LinkCustomAttributes = "";
			$this->temporaryResidence->HrefValue = "";

			// visitsCount
			$this->visitsCount->LinkCustomAttributes = "";
			$this->visitsCount->HrefValue = "";

			// picture
			$this->picture->LinkCustomAttributes = "";
			$this->picture->HrefValue = "";

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
		if (!$this->personName->FldIsDetailKey && !is_null($this->personName->FormValue) && $this->personName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->personName->FldCaption(), $this->personName->ReqErrMsg));
		}
		if (!$this->lastName->FldIsDetailKey && !is_null($this->lastName->FormValue) && $this->lastName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lastName->FldCaption(), $this->lastName->ReqErrMsg));
		}
		if (!$this->fatherName->FldIsDetailKey && !is_null($this->fatherName->FormValue) && $this->fatherName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fatherName->FldCaption(), $this->fatherName->ReqErrMsg));
		}
		if (!$this->gender->FldIsDetailKey && !is_null($this->gender->FormValue) && $this->gender->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->gender->FldCaption(), $this->gender->ReqErrMsg));
		}
		if (!$this->country->FldIsDetailKey && !is_null($this->country->FormValue) && $this->country->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->country->FldCaption(), $this->country->ReqErrMsg));
		}
		if (!$this->province->FldIsDetailKey && !is_null($this->province->FormValue) && $this->province->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->province->FldCaption(), $this->province->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->birthDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->birthDate->FldErrMsg());
		}
		if (!ew_CheckInteger($this->visitsCount->FormValue)) {
			ew_AddMessage($gsFormError, $this->visitsCount->FldErrMsg());
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

			// personName
			$this->personName->SetDbValueDef($rsnew, $this->personName->CurrentValue, "", $this->personName->ReadOnly);

			// lastName
			$this->lastName->SetDbValueDef($rsnew, $this->lastName->CurrentValue, "", $this->lastName->ReadOnly);

			// nationalID
			$this->nationalID->SetDbValueDef($rsnew, $this->nationalID->CurrentValue, NULL, $this->nationalID->ReadOnly);

			// nationalNumber
			$this->nationalNumber->SetDbValueDef($rsnew, $this->nationalNumber->CurrentValue, NULL, $this->nationalNumber->ReadOnly);

			// fatherName
			$this->fatherName->SetDbValueDef($rsnew, $this->fatherName->CurrentValue, "", $this->fatherName->ReadOnly);

			// gender
			$this->gender->SetDbValueDef($rsnew, $this->gender->CurrentValue, "", $this->gender->ReadOnly);

			// country
			$this->country->SetDbValueDef($rsnew, $this->country->CurrentValue, "", $this->country->ReadOnly);

			// province
			$this->province->SetDbValueDef($rsnew, $this->province->CurrentValue, "", $this->province->ReadOnly);

			// county
			$this->county->SetDbValueDef($rsnew, $this->county->CurrentValue, NULL, $this->county->ReadOnly);

			// district
			$this->district->SetDbValueDef($rsnew, $this->district->CurrentValue, NULL, $this->district->ReadOnly);

			// city_ruralDistrict
			$this->city_ruralDistrict->SetDbValueDef($rsnew, $this->city_ruralDistrict->CurrentValue, NULL, $this->city_ruralDistrict->ReadOnly);

			// region_village
			$this->region_village->SetDbValueDef($rsnew, $this->region_village->CurrentValue, NULL, $this->region_village->ReadOnly);

			// address
			$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, NULL, $this->address->ReadOnly);

			// convoy
			$this->convoy->SetDbValueDef($rsnew, $this->convoy->CurrentValue, NULL, $this->convoy->ReadOnly);

			// convoyManager
			$this->convoyManager->SetDbValueDef($rsnew, $this->convoyManager->CurrentValue, NULL, $this->convoyManager->ReadOnly);

			// followersName
			$this->followersName->SetDbValueDef($rsnew, $this->followersName->CurrentValue, NULL, $this->followersName->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, $this->status->ReadOnly);

			// isolatedLocation
			$this->isolatedLocation->SetDbValueDef($rsnew, $this->isolatedLocation->CurrentValue, NULL, $this->isolatedLocation->ReadOnly);

			// birthDate
			$this->birthDate->SetDbValueDef($rsnew, $this->birthDate->CurrentValue, NULL, $this->birthDate->ReadOnly);

			// ageRange
			$this->ageRange->SetDbValueDef($rsnew, $this->ageRange->CurrentValue, NULL, $this->ageRange->ReadOnly);

			// dress1
			$this->dress1->SetDbValueDef($rsnew, $this->dress1->CurrentValue, NULL, $this->dress1->ReadOnly);

			// dress2
			$this->dress2->SetDbValueDef($rsnew, $this->dress2->CurrentValue, NULL, $this->dress2->ReadOnly);

			// signTags
			$this->signTags->SetDbValueDef($rsnew, $this->signTags->CurrentValue, NULL, $this->signTags->ReadOnly);

			// phone
			$this->phone->SetDbValueDef($rsnew, $this->phone->CurrentValue, NULL, $this->phone->ReadOnly);

			// mobilePhone
			$this->mobilePhone->SetDbValueDef($rsnew, $this->mobilePhone->CurrentValue, NULL, $this->mobilePhone->ReadOnly);

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, $this->_email->ReadOnly);

			// temporaryResidence
			$this->temporaryResidence->SetDbValueDef($rsnew, $this->temporaryResidence->CurrentValue, NULL, $this->temporaryResidence->ReadOnly);

			// visitsCount
			$this->visitsCount->SetDbValueDef($rsnew, $this->visitsCount->CurrentValue, NULL, $this->visitsCount->ReadOnly);

			// picture
			$this->picture->SetDbValueDef($rsnew, $this->picture->CurrentValue, NULL, $this->picture->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sana_personlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($sana_person_edit)) $sana_person_edit = new csana_person_edit();

// Page init
$sana_person_edit->Page_Init();

// Page main
$sana_person_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_person_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fsana_personedit = new ew_Form("fsana_personedit", "edit");

// Validate form
fsana_personedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_personName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_person->personName->FldCaption(), $sana_person->personName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lastName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_person->lastName->FldCaption(), $sana_person->lastName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fatherName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_person->fatherName->FldCaption(), $sana_person->fatherName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_gender");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_person->gender->FldCaption(), $sana_person->gender->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_country");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_person->country->FldCaption(), $sana_person->country->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_province");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_person->province->FldCaption(), $sana_person->province->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_birthDate");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_person->birthDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_visitsCount");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_person->visitsCount->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_registrationUser");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_person->registrationUser->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_registrationDateTime");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_person->registrationDateTime->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_registrationStation");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_person->registrationStation->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_isolatedDateTime");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_person->isolatedDateTime->FldErrMsg()) ?>");

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
fsana_personedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_personedit.ValidateRequired = true;
<?php } else { ?>
fsana_personedit.ValidateRequired = false; 
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
<?php $sana_person_edit->ShowPageHeader(); ?>
<?php
$sana_person_edit->ShowMessage();
?>
<form name="fsana_personedit" id="fsana_personedit" class="<?php echo $sana_person_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_person_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_person_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_person">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($sana_person->personID->Visible) { // personID ?>
	<div id="r_personID" class="form-group">
		<label id="elh_sana_person_personID" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->personID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->personID->CellAttributes() ?>>
<span id="el_sana_person_personID">
<span<?php echo $sana_person->personID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sana_person->personID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="sana_person" data-field="x_personID" name="x_personID" id="x_personID" value="<?php echo ew_HtmlEncode($sana_person->personID->CurrentValue) ?>">
<?php echo $sana_person->personID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->personName->Visible) { // personName ?>
	<div id="r_personName" class="form-group">
		<label id="elh_sana_person_personName" for="x_personName" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->personName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->personName->CellAttributes() ?>>
<span id="el_sana_person_personName">
<input type="text" data-table="sana_person" data-field="x_personName" name="x_personName" id="x_personName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->personName->getPlaceHolder()) ?>" value="<?php echo $sana_person->personName->EditValue ?>"<?php echo $sana_person->personName->EditAttributes() ?>>
</span>
<?php echo $sana_person->personName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->lastName->Visible) { // lastName ?>
	<div id="r_lastName" class="form-group">
		<label id="elh_sana_person_lastName" for="x_lastName" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->lastName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->lastName->CellAttributes() ?>>
<span id="el_sana_person_lastName">
<input type="text" data-table="sana_person" data-field="x_lastName" name="x_lastName" id="x_lastName" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($sana_person->lastName->getPlaceHolder()) ?>" value="<?php echo $sana_person->lastName->EditValue ?>"<?php echo $sana_person->lastName->EditAttributes() ?>>
</span>
<?php echo $sana_person->lastName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->nationalID->Visible) { // nationalID ?>
	<div id="r_nationalID" class="form-group">
		<label id="elh_sana_person_nationalID" for="x_nationalID" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->nationalID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->nationalID->CellAttributes() ?>>
<span id="el_sana_person_nationalID">
<input type="text" data-table="sana_person" data-field="x_nationalID" name="x_nationalID" id="x_nationalID" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($sana_person->nationalID->getPlaceHolder()) ?>" value="<?php echo $sana_person->nationalID->EditValue ?>"<?php echo $sana_person->nationalID->EditAttributes() ?>>
</span>
<?php echo $sana_person->nationalID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->nationalNumber->Visible) { // nationalNumber ?>
	<div id="r_nationalNumber" class="form-group">
		<label id="elh_sana_person_nationalNumber" for="x_nationalNumber" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->nationalNumber->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->nationalNumber->CellAttributes() ?>>
<span id="el_sana_person_nationalNumber">
<input type="text" data-table="sana_person" data-field="x_nationalNumber" name="x_nationalNumber" id="x_nationalNumber" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($sana_person->nationalNumber->getPlaceHolder()) ?>" value="<?php echo $sana_person->nationalNumber->EditValue ?>"<?php echo $sana_person->nationalNumber->EditAttributes() ?>>
</span>
<?php echo $sana_person->nationalNumber->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->fatherName->Visible) { // fatherName ?>
	<div id="r_fatherName" class="form-group">
		<label id="elh_sana_person_fatherName" for="x_fatherName" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->fatherName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->fatherName->CellAttributes() ?>>
<span id="el_sana_person_fatherName">
<input type="text" data-table="sana_person" data-field="x_fatherName" name="x_fatherName" id="x_fatherName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->fatherName->getPlaceHolder()) ?>" value="<?php echo $sana_person->fatherName->EditValue ?>"<?php echo $sana_person->fatherName->EditAttributes() ?>>
</span>
<?php echo $sana_person->fatherName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->gender->Visible) { // gender ?>
	<div id="r_gender" class="form-group">
		<label id="elh_sana_person_gender" for="x_gender" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->gender->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->gender->CellAttributes() ?>>
<span id="el_sana_person_gender">
<input type="text" data-table="sana_person" data-field="x_gender" name="x_gender" id="x_gender" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($sana_person->gender->getPlaceHolder()) ?>" value="<?php echo $sana_person->gender->EditValue ?>"<?php echo $sana_person->gender->EditAttributes() ?>>
</span>
<?php echo $sana_person->gender->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->country->Visible) { // country ?>
	<div id="r_country" class="form-group">
		<label id="elh_sana_person_country" for="x_country" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->country->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->country->CellAttributes() ?>>
<span id="el_sana_person_country">
<input type="text" data-table="sana_person" data-field="x_country" name="x_country" id="x_country" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->country->getPlaceHolder()) ?>" value="<?php echo $sana_person->country->EditValue ?>"<?php echo $sana_person->country->EditAttributes() ?>>
</span>
<?php echo $sana_person->country->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->province->Visible) { // province ?>
	<div id="r_province" class="form-group">
		<label id="elh_sana_person_province" for="x_province" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->province->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->province->CellAttributes() ?>>
<span id="el_sana_person_province">
<input type="text" data-table="sana_person" data-field="x_province" name="x_province" id="x_province" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->province->getPlaceHolder()) ?>" value="<?php echo $sana_person->province->EditValue ?>"<?php echo $sana_person->province->EditAttributes() ?>>
</span>
<?php echo $sana_person->province->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->county->Visible) { // county ?>
	<div id="r_county" class="form-group">
		<label id="elh_sana_person_county" for="x_county" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->county->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->county->CellAttributes() ?>>
<span id="el_sana_person_county">
<input type="text" data-table="sana_person" data-field="x_county" name="x_county" id="x_county" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->county->getPlaceHolder()) ?>" value="<?php echo $sana_person->county->EditValue ?>"<?php echo $sana_person->county->EditAttributes() ?>>
</span>
<?php echo $sana_person->county->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->district->Visible) { // district ?>
	<div id="r_district" class="form-group">
		<label id="elh_sana_person_district" for="x_district" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->district->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->district->CellAttributes() ?>>
<span id="el_sana_person_district">
<input type="text" data-table="sana_person" data-field="x_district" name="x_district" id="x_district" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->district->getPlaceHolder()) ?>" value="<?php echo $sana_person->district->EditValue ?>"<?php echo $sana_person->district->EditAttributes() ?>>
</span>
<?php echo $sana_person->district->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->city_ruralDistrict->Visible) { // city_ruralDistrict ?>
	<div id="r_city_ruralDistrict" class="form-group">
		<label id="elh_sana_person_city_ruralDistrict" for="x_city_ruralDistrict" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->city_ruralDistrict->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->city_ruralDistrict->CellAttributes() ?>>
<span id="el_sana_person_city_ruralDistrict">
<input type="text" data-table="sana_person" data-field="x_city_ruralDistrict" name="x_city_ruralDistrict" id="x_city_ruralDistrict" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->city_ruralDistrict->getPlaceHolder()) ?>" value="<?php echo $sana_person->city_ruralDistrict->EditValue ?>"<?php echo $sana_person->city_ruralDistrict->EditAttributes() ?>>
</span>
<?php echo $sana_person->city_ruralDistrict->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->region_village->Visible) { // region_village ?>
	<div id="r_region_village" class="form-group">
		<label id="elh_sana_person_region_village" for="x_region_village" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->region_village->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->region_village->CellAttributes() ?>>
<span id="el_sana_person_region_village">
<input type="text" data-table="sana_person" data-field="x_region_village" name="x_region_village" id="x_region_village" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->region_village->getPlaceHolder()) ?>" value="<?php echo $sana_person->region_village->EditValue ?>"<?php echo $sana_person->region_village->EditAttributes() ?>>
</span>
<?php echo $sana_person->region_village->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label id="elh_sana_person_address" for="x_address" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->address->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->address->CellAttributes() ?>>
<span id="el_sana_person_address">
<input type="text" data-table="sana_person" data-field="x_address" name="x_address" id="x_address" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->address->getPlaceHolder()) ?>" value="<?php echo $sana_person->address->EditValue ?>"<?php echo $sana_person->address->EditAttributes() ?>>
</span>
<?php echo $sana_person->address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->convoy->Visible) { // convoy ?>
	<div id="r_convoy" class="form-group">
		<label id="elh_sana_person_convoy" for="x_convoy" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->convoy->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->convoy->CellAttributes() ?>>
<span id="el_sana_person_convoy">
<input type="text" data-table="sana_person" data-field="x_convoy" name="x_convoy" id="x_convoy" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->convoy->getPlaceHolder()) ?>" value="<?php echo $sana_person->convoy->EditValue ?>"<?php echo $sana_person->convoy->EditAttributes() ?>>
</span>
<?php echo $sana_person->convoy->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->convoyManager->Visible) { // convoyManager ?>
	<div id="r_convoyManager" class="form-group">
		<label id="elh_sana_person_convoyManager" for="x_convoyManager" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->convoyManager->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->convoyManager->CellAttributes() ?>>
<span id="el_sana_person_convoyManager">
<input type="text" data-table="sana_person" data-field="x_convoyManager" name="x_convoyManager" id="x_convoyManager" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->convoyManager->getPlaceHolder()) ?>" value="<?php echo $sana_person->convoyManager->EditValue ?>"<?php echo $sana_person->convoyManager->EditAttributes() ?>>
</span>
<?php echo $sana_person->convoyManager->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->followersName->Visible) { // followersName ?>
	<div id="r_followersName" class="form-group">
		<label id="elh_sana_person_followersName" for="x_followersName" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->followersName->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->followersName->CellAttributes() ?>>
<span id="el_sana_person_followersName">
<input type="text" data-table="sana_person" data-field="x_followersName" name="x_followersName" id="x_followersName" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->followersName->getPlaceHolder()) ?>" value="<?php echo $sana_person->followersName->EditValue ?>"<?php echo $sana_person->followersName->EditAttributes() ?>>
</span>
<?php echo $sana_person->followersName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_sana_person_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->status->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->status->CellAttributes() ?>>
<span id="el_sana_person_status">
<input type="text" data-table="sana_person" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->status->getPlaceHolder()) ?>" value="<?php echo $sana_person->status->EditValue ?>"<?php echo $sana_person->status->EditAttributes() ?>>
</span>
<?php echo $sana_person->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->isolatedLocation->Visible) { // isolatedLocation ?>
	<div id="r_isolatedLocation" class="form-group">
		<label id="elh_sana_person_isolatedLocation" for="x_isolatedLocation" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->isolatedLocation->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->isolatedLocation->CellAttributes() ?>>
<span id="el_sana_person_isolatedLocation">
<input type="text" data-table="sana_person" data-field="x_isolatedLocation" name="x_isolatedLocation" id="x_isolatedLocation" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->isolatedLocation->getPlaceHolder()) ?>" value="<?php echo $sana_person->isolatedLocation->EditValue ?>"<?php echo $sana_person->isolatedLocation->EditAttributes() ?>>
</span>
<?php echo $sana_person->isolatedLocation->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->birthDate->Visible) { // birthDate ?>
	<div id="r_birthDate" class="form-group">
		<label id="elh_sana_person_birthDate" for="x_birthDate" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->birthDate->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->birthDate->CellAttributes() ?>>
<span id="el_sana_person_birthDate">
<input type="text" data-table="sana_person" data-field="x_birthDate" name="x_birthDate" id="x_birthDate" size="30" placeholder="<?php echo ew_HtmlEncode($sana_person->birthDate->getPlaceHolder()) ?>" value="<?php echo $sana_person->birthDate->EditValue ?>"<?php echo $sana_person->birthDate->EditAttributes() ?>>
</span>
<?php echo $sana_person->birthDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->ageRange->Visible) { // ageRange ?>
	<div id="r_ageRange" class="form-group">
		<label id="elh_sana_person_ageRange" for="x_ageRange" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->ageRange->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->ageRange->CellAttributes() ?>>
<span id="el_sana_person_ageRange">
<input type="text" data-table="sana_person" data-field="x_ageRange" name="x_ageRange" id="x_ageRange" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($sana_person->ageRange->getPlaceHolder()) ?>" value="<?php echo $sana_person->ageRange->EditValue ?>"<?php echo $sana_person->ageRange->EditAttributes() ?>>
</span>
<?php echo $sana_person->ageRange->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->dress1->Visible) { // dress1 ?>
	<div id="r_dress1" class="form-group">
		<label id="elh_sana_person_dress1" for="x_dress1" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->dress1->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->dress1->CellAttributes() ?>>
<span id="el_sana_person_dress1">
<input type="text" data-table="sana_person" data-field="x_dress1" name="x_dress1" id="x_dress1" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->dress1->getPlaceHolder()) ?>" value="<?php echo $sana_person->dress1->EditValue ?>"<?php echo $sana_person->dress1->EditAttributes() ?>>
</span>
<?php echo $sana_person->dress1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->dress2->Visible) { // dress2 ?>
	<div id="r_dress2" class="form-group">
		<label id="elh_sana_person_dress2" for="x_dress2" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->dress2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->dress2->CellAttributes() ?>>
<span id="el_sana_person_dress2">
<input type="text" data-table="sana_person" data-field="x_dress2" name="x_dress2" id="x_dress2" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->dress2->getPlaceHolder()) ?>" value="<?php echo $sana_person->dress2->EditValue ?>"<?php echo $sana_person->dress2->EditAttributes() ?>>
</span>
<?php echo $sana_person->dress2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->signTags->Visible) { // signTags ?>
	<div id="r_signTags" class="form-group">
		<label id="elh_sana_person_signTags" for="x_signTags" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->signTags->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->signTags->CellAttributes() ?>>
<span id="el_sana_person_signTags">
<input type="text" data-table="sana_person" data-field="x_signTags" name="x_signTags" id="x_signTags" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->signTags->getPlaceHolder()) ?>" value="<?php echo $sana_person->signTags->EditValue ?>"<?php echo $sana_person->signTags->EditAttributes() ?>>
</span>
<?php echo $sana_person->signTags->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->phone->Visible) { // phone ?>
	<div id="r_phone" class="form-group">
		<label id="elh_sana_person_phone" for="x_phone" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->phone->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->phone->CellAttributes() ?>>
<span id="el_sana_person_phone">
<input type="text" data-table="sana_person" data-field="x_phone" name="x_phone" id="x_phone" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->phone->getPlaceHolder()) ?>" value="<?php echo $sana_person->phone->EditValue ?>"<?php echo $sana_person->phone->EditAttributes() ?>>
</span>
<?php echo $sana_person->phone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->mobilePhone->Visible) { // mobilePhone ?>
	<div id="r_mobilePhone" class="form-group">
		<label id="elh_sana_person_mobilePhone" for="x_mobilePhone" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->mobilePhone->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->mobilePhone->CellAttributes() ?>>
<span id="el_sana_person_mobilePhone">
<input type="text" data-table="sana_person" data-field="x_mobilePhone" name="x_mobilePhone" id="x_mobilePhone" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($sana_person->mobilePhone->getPlaceHolder()) ?>" value="<?php echo $sana_person->mobilePhone->EditValue ?>"<?php echo $sana_person->mobilePhone->EditAttributes() ?>>
</span>
<?php echo $sana_person->mobilePhone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_sana_person__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->_email->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->_email->CellAttributes() ?>>
<span id="el_sana_person__email">
<input type="text" data-table="sana_person" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($sana_person->_email->getPlaceHolder()) ?>" value="<?php echo $sana_person->_email->EditValue ?>"<?php echo $sana_person->_email->EditAttributes() ?>>
</span>
<?php echo $sana_person->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->temporaryResidence->Visible) { // temporaryResidence ?>
	<div id="r_temporaryResidence" class="form-group">
		<label id="elh_sana_person_temporaryResidence" for="x_temporaryResidence" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->temporaryResidence->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->temporaryResidence->CellAttributes() ?>>
<span id="el_sana_person_temporaryResidence">
<input type="text" data-table="sana_person" data-field="x_temporaryResidence" name="x_temporaryResidence" id="x_temporaryResidence" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->temporaryResidence->getPlaceHolder()) ?>" value="<?php echo $sana_person->temporaryResidence->EditValue ?>"<?php echo $sana_person->temporaryResidence->EditAttributes() ?>>
</span>
<?php echo $sana_person->temporaryResidence->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->visitsCount->Visible) { // visitsCount ?>
	<div id="r_visitsCount" class="form-group">
		<label id="elh_sana_person_visitsCount" for="x_visitsCount" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->visitsCount->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->visitsCount->CellAttributes() ?>>
<span id="el_sana_person_visitsCount">
<input type="text" data-table="sana_person" data-field="x_visitsCount" name="x_visitsCount" id="x_visitsCount" size="30" placeholder="<?php echo ew_HtmlEncode($sana_person->visitsCount->getPlaceHolder()) ?>" value="<?php echo $sana_person->visitsCount->EditValue ?>"<?php echo $sana_person->visitsCount->EditAttributes() ?>>
</span>
<?php echo $sana_person->visitsCount->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->picture->Visible) { // picture ?>
	<div id="r_picture" class="form-group">
		<label id="elh_sana_person_picture" for="x_picture" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->picture->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->picture->CellAttributes() ?>>
<span id="el_sana_person_picture">
<input type="text" data-table="sana_person" data-field="x_picture" name="x_picture" id="x_picture" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->picture->getPlaceHolder()) ?>" value="<?php echo $sana_person->picture->EditValue ?>"<?php echo $sana_person->picture->EditAttributes() ?>>
</span>
<?php echo $sana_person->picture->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->registrationUser->Visible) { // registrationUser ?>
	<div id="r_registrationUser" class="form-group">
		<label id="elh_sana_person_registrationUser" for="x_registrationUser" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->registrationUser->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->registrationUser->CellAttributes() ?>>
<span id="el_sana_person_registrationUser">
<input type="text" data-table="sana_person" data-field="x_registrationUser" name="x_registrationUser" id="x_registrationUser" size="30" placeholder="<?php echo ew_HtmlEncode($sana_person->registrationUser->getPlaceHolder()) ?>" value="<?php echo $sana_person->registrationUser->EditValue ?>"<?php echo $sana_person->registrationUser->EditAttributes() ?>>
</span>
<?php echo $sana_person->registrationUser->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->registrationDateTime->Visible) { // registrationDateTime ?>
	<div id="r_registrationDateTime" class="form-group">
		<label id="elh_sana_person_registrationDateTime" for="x_registrationDateTime" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->registrationDateTime->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->registrationDateTime->CellAttributes() ?>>
<span id="el_sana_person_registrationDateTime">
<input type="text" data-table="sana_person" data-field="x_registrationDateTime" data-format="5" name="x_registrationDateTime" id="x_registrationDateTime" placeholder="<?php echo ew_HtmlEncode($sana_person->registrationDateTime->getPlaceHolder()) ?>" value="<?php echo $sana_person->registrationDateTime->EditValue ?>"<?php echo $sana_person->registrationDateTime->EditAttributes() ?>>
</span>
<?php echo $sana_person->registrationDateTime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->registrationStation->Visible) { // registrationStation ?>
	<div id="r_registrationStation" class="form-group">
		<label id="elh_sana_person_registrationStation" for="x_registrationStation" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->registrationStation->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->registrationStation->CellAttributes() ?>>
<span id="el_sana_person_registrationStation">
<input type="text" data-table="sana_person" data-field="x_registrationStation" name="x_registrationStation" id="x_registrationStation" size="30" placeholder="<?php echo ew_HtmlEncode($sana_person->registrationStation->getPlaceHolder()) ?>" value="<?php echo $sana_person->registrationStation->EditValue ?>"<?php echo $sana_person->registrationStation->EditAttributes() ?>>
</span>
<?php echo $sana_person->registrationStation->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->isolatedDateTime->Visible) { // isolatedDateTime ?>
	<div id="r_isolatedDateTime" class="form-group">
		<label id="elh_sana_person_isolatedDateTime" for="x_isolatedDateTime" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->isolatedDateTime->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->isolatedDateTime->CellAttributes() ?>>
<span id="el_sana_person_isolatedDateTime">
<input type="text" data-table="sana_person" data-field="x_isolatedDateTime" data-format="5" name="x_isolatedDateTime" id="x_isolatedDateTime" placeholder="<?php echo ew_HtmlEncode($sana_person->isolatedDateTime->getPlaceHolder()) ?>" value="<?php echo $sana_person->isolatedDateTime->EditValue ?>"<?php echo $sana_person->isolatedDateTime->EditAttributes() ?>>
</span>
<?php echo $sana_person->isolatedDateTime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label id="elh_sana_person_description" for="x_description" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->description->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->description->CellAttributes() ?>>
<span id="el_sana_person_description">
<textarea data-table="sana_person" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($sana_person->description->getPlaceHolder()) ?>"<?php echo $sana_person->description->EditAttributes() ?>><?php echo $sana_person->description->EditValue ?></textarea>
</span>
<?php echo $sana_person->description->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sana_person_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fsana_personedit.Init();
</script>
<?php
$sana_person_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_person_edit->Page_Terminate();
?>
