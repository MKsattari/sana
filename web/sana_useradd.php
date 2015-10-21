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

$sana_user_add = NULL; // Initialize page object first

class csana_user_add extends csana_user {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_user';

	// Page object name
	var $PageObjName = 'sana_user_add';

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

		// Table object (sana_user)
		if (!isset($GLOBALS["sana_user"]) || get_class($GLOBALS["sana_user"]) == "csana_user") {
			$GLOBALS["sana_user"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_user"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

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

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["_userID"] != "") {
				$this->_userID->setQueryStringValue($_GET["_userID"]);
				$this->setKey("_userID", $this->_userID->CurrentValue); // Set up key
			} else {
				$this->setKey("_userID", ""); // Clear key
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
					$this->Page_Terminate("sana_userlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "sana_userlist.php")
						$sReturnUrl = $this->AddMasterUrl($this->GetListUrl()); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "sana_userview.php")
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
		$this->picture->Upload->Index = $objForm->Index;
		$this->picture->Upload->UploadFile();
		$this->picture->CurrentValue = $this->picture->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->username->CurrentValue = NULL;
		$this->username->OldValue = $this->username->CurrentValue;
		$this->personName->CurrentValue = NULL;
		$this->personName->OldValue = $this->personName->CurrentValue;
		$this->lastName->CurrentValue = NULL;
		$this->lastName->OldValue = $this->lastName->CurrentValue;
		$this->nationalID->CurrentValue = NULL;
		$this->nationalID->OldValue = $this->nationalID->CurrentValue;
		$this->nationalNumber->CurrentValue = NULL;
		$this->nationalNumber->OldValue = $this->nationalNumber->CurrentValue;
		$this->ageRange->CurrentValue = NULL;
		$this->ageRange->OldValue = $this->ageRange->CurrentValue;
		$this->mobilePhone->CurrentValue = NULL;
		$this->mobilePhone->OldValue = $this->mobilePhone->CurrentValue;
		$this->userPassword->CurrentValue = NULL;
		$this->userPassword->OldValue = $this->userPassword->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->picture->Upload->DbValue = NULL;
		$this->picture->OldValue = $this->picture->Upload->DbValue;
		$this->picture->CurrentValue = NULL; // Clear file related field
		$this->registrationUser->CurrentValue = NULL;
		$this->registrationUser->OldValue = $this->registrationUser->CurrentValue;
		$this->registrationDateTime->CurrentValue = NULL;
		$this->registrationDateTime->OldValue = $this->registrationDateTime->CurrentValue;
		$this->stationID->CurrentValue = NULL;
		$this->stationID->OldValue = $this->stationID->CurrentValue;
		$this->acl->CurrentValue = NULL;
		$this->acl->OldValue = $this->acl->CurrentValue;
		$this->description->CurrentValue = NULL;
		$this->description->OldValue = $this->description->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->username->FldIsDetailKey) {
			$this->username->setFormValue($objForm->GetValue("x_username"));
		}
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
		if (!$this->ageRange->FldIsDetailKey) {
			$this->ageRange->setFormValue($objForm->GetValue("x_ageRange"));
		}
		if (!$this->mobilePhone->FldIsDetailKey) {
			$this->mobilePhone->setFormValue($objForm->GetValue("x_mobilePhone"));
		}
		if (!$this->userPassword->FldIsDetailKey) {
			$this->userPassword->setFormValue($objForm->GetValue("x_userPassword"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->registrationUser->FldIsDetailKey) {
			$this->registrationUser->setFormValue($objForm->GetValue("x_registrationUser"));
		}
		if (!$this->registrationDateTime->FldIsDetailKey) {
			$this->registrationDateTime->setFormValue($objForm->GetValue("x_registrationDateTime"));
			$this->registrationDateTime->CurrentValue = ew_UnFormatDateTime($this->registrationDateTime->CurrentValue, 5);
		}
		if (!$this->stationID->FldIsDetailKey) {
			$this->stationID->setFormValue($objForm->GetValue("x_stationID"));
		}
		if (!$this->acl->FldIsDetailKey) {
			$this->acl->setFormValue($objForm->GetValue("x_acl"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->username->CurrentValue = $this->username->FormValue;
		$this->personName->CurrentValue = $this->personName->FormValue;
		$this->lastName->CurrentValue = $this->lastName->FormValue;
		$this->nationalID->CurrentValue = $this->nationalID->FormValue;
		$this->nationalNumber->CurrentValue = $this->nationalNumber->FormValue;
		$this->ageRange->CurrentValue = $this->ageRange->FormValue;
		$this->mobilePhone->CurrentValue = $this->mobilePhone->FormValue;
		$this->userPassword->CurrentValue = $this->userPassword->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->registrationUser->CurrentValue = $this->registrationUser->FormValue;
		$this->registrationDateTime->CurrentValue = $this->registrationDateTime->FormValue;
		$this->registrationDateTime->CurrentValue = ew_UnFormatDateTime($this->registrationDateTime->CurrentValue, 5);
		$this->stationID->CurrentValue = $this->stationID->FormValue;
		$this->acl->CurrentValue = $this->acl->FormValue;
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

		// Check if valid user id
		if ($res) {
			$res = $this->ShowOptionLink('add');
			if (!$res) {
				$sUserIdMsg = $Language->Phrase("NoPermission");
				$this->setFailureMessage($sUserIdMsg);
			}
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("_userID")) <> "")
			$this->_userID->CurrentValue = $this->getKey("_userID"); // userID
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
		$this->userPassword->ViewValue = $Language->Phrase("PasswordMask");
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

			// ageRange
			$this->ageRange->LinkCustomAttributes = "";
			$this->ageRange->HrefValue = "";
			$this->ageRange->TooltipValue = "";

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

			// acl
			$this->acl->LinkCustomAttributes = "";
			$this->acl->HrefValue = "";
			$this->acl->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// username
			$this->username->EditAttrs["class"] = "form-control";
			$this->username->EditCustomAttributes = "";
			$this->username->EditValue = ew_HtmlEncode($this->username->CurrentValue);
			$this->username->PlaceHolder = ew_RemoveHtml($this->username->FldCaption());

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

			// ageRange
			$this->ageRange->EditAttrs["class"] = "form-control";
			$this->ageRange->EditCustomAttributes = "";
			$this->ageRange->EditValue = ew_HtmlEncode($this->ageRange->CurrentValue);
			$this->ageRange->PlaceHolder = ew_RemoveHtml($this->ageRange->FldCaption());

			// mobilePhone
			$this->mobilePhone->EditAttrs["class"] = "form-control";
			$this->mobilePhone->EditCustomAttributes = "";
			$this->mobilePhone->EditValue = ew_HtmlEncode($this->mobilePhone->CurrentValue);
			$this->mobilePhone->PlaceHolder = ew_RemoveHtml($this->mobilePhone->FldCaption());

			// userPassword
			$this->userPassword->EditAttrs["class"] = "form-control ewPasswordStrength";
			$this->userPassword->EditCustomAttributes = "";
			$this->userPassword->EditValue = ew_HtmlEncode($this->userPassword->CurrentValue);
			$this->userPassword->PlaceHolder = ew_RemoveHtml($this->userPassword->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// picture
			$this->picture->EditAttrs["class"] = "form-control";
			$this->picture->EditCustomAttributes = "";
			if (!ew_Empty($this->picture->Upload->DbValue)) {
				$this->picture->EditValue = $this->picture->Upload->DbValue;
			} else {
				$this->picture->EditValue = "";
			}
			if (!ew_Empty($this->picture->CurrentValue))
				$this->picture->Upload->FileName = $this->picture->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->picture);

			// registrationUser
			// registrationDateTime
			// stationID
			// acl

			$this->acl->EditAttrs["class"] = "form-control";
			$this->acl->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->acl->EditValue = $Language->Phrase("PasswordMask");
			} else {
			if (trim(strval($this->acl->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->acl->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
					$sWhereWrk = "";
					break;
				case "fa":
					$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
					$sWhereWrk = "";
					break;
			}
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->acl, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->acl->EditValue = $arwrk;
			}

			// description
			$this->description->EditAttrs["class"] = "form-control";
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);
			$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

			// Add refer script
			// username

			$this->username->LinkCustomAttributes = "";
			$this->username->HrefValue = "";

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

			// ageRange
			$this->ageRange->LinkCustomAttributes = "";
			$this->ageRange->HrefValue = "";

			// mobilePhone
			$this->mobilePhone->LinkCustomAttributes = "";
			$this->mobilePhone->HrefValue = "";

			// userPassword
			$this->userPassword->LinkCustomAttributes = "";
			$this->userPassword->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// picture
			$this->picture->LinkCustomAttributes = "";
			$this->picture->HrefValue = "";
			$this->picture->HrefValue2 = $this->picture->UploadPath . $this->picture->Upload->DbValue;

			// registrationUser
			$this->registrationUser->LinkCustomAttributes = "";
			$this->registrationUser->HrefValue = "";

			// registrationDateTime
			$this->registrationDateTime->LinkCustomAttributes = "";
			$this->registrationDateTime->HrefValue = "";

			// stationID
			$this->stationID->LinkCustomAttributes = "";
			$this->stationID->HrefValue = "";

			// acl
			$this->acl->LinkCustomAttributes = "";
			$this->acl->HrefValue = "";

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
		if (!$this->username->FldIsDetailKey && !is_null($this->username->FormValue) && $this->username->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->username->FldCaption(), $this->username->ReqErrMsg));
		}
		if (!$this->personName->FldIsDetailKey && !is_null($this->personName->FormValue) && $this->personName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->personName->FldCaption(), $this->personName->ReqErrMsg));
		}
		if (!$this->lastName->FldIsDetailKey && !is_null($this->lastName->FormValue) && $this->lastName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lastName->FldCaption(), $this->lastName->ReqErrMsg));
		}
		if (!$this->nationalNumber->FldIsDetailKey && !is_null($this->nationalNumber->FormValue) && $this->nationalNumber->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nationalNumber->FldCaption(), $this->nationalNumber->ReqErrMsg));
		}
		if (!$this->mobilePhone->FldIsDetailKey && !is_null($this->mobilePhone->FormValue) && $this->mobilePhone->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->mobilePhone->FldCaption(), $this->mobilePhone->ReqErrMsg));
		}
		if (!$this->userPassword->FldIsDetailKey && !is_null($this->userPassword->FormValue) && $this->userPassword->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->userPassword->FldCaption(), $this->userPassword->ReqErrMsg));
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
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// username
		$this->username->SetDbValueDef($rsnew, $this->username->CurrentValue, "", FALSE);

		// personName
		$this->personName->SetDbValueDef($rsnew, $this->personName->CurrentValue, "", FALSE);

		// lastName
		$this->lastName->SetDbValueDef($rsnew, $this->lastName->CurrentValue, "", FALSE);

		// nationalID
		$this->nationalID->SetDbValueDef($rsnew, $this->nationalID->CurrentValue, NULL, FALSE);

		// nationalNumber
		$this->nationalNumber->SetDbValueDef($rsnew, $this->nationalNumber->CurrentValue, NULL, FALSE);

		// ageRange
		$this->ageRange->SetDbValueDef($rsnew, $this->ageRange->CurrentValue, NULL, FALSE);

		// mobilePhone
		$this->mobilePhone->SetDbValueDef($rsnew, $this->mobilePhone->CurrentValue, NULL, FALSE);

		// userPassword
		$this->userPassword->SetDbValueDef($rsnew, $this->userPassword->CurrentValue, NULL, FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, FALSE);

		// picture
		if ($this->picture->Visible && !$this->picture->Upload->KeepFile) {
			$this->picture->Upload->DbValue = ""; // No need to delete old file
			if ($this->picture->Upload->FileName == "") {
				$rsnew['picture'] = NULL;
			} else {
				$rsnew['picture'] = $this->picture->Upload->FileName;
			}
		}

		// registrationUser
		$this->registrationUser->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['registrationUser'] = &$this->registrationUser->DbValue;

		// registrationDateTime
		$this->registrationDateTime->SetDbValueDef($rsnew, ew_CurrentDate(), NULL);
		$rsnew['registrationDateTime'] = &$this->registrationDateTime->DbValue;

		// stationID
		$this->stationID->SetDbValueDef($rsnew, CurrentParentUserID(), NULL);
		$rsnew['stationID'] = &$this->stationID->DbValue;

		// acl
		if ($Security->CanAdmin()) { // System admin
		$this->acl->SetDbValueDef($rsnew, $this->acl->CurrentValue, NULL, FALSE);
		}

		// description
		$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, FALSE);

		// userID
		if ($this->picture->Visible && !$this->picture->Upload->KeepFile) {
			if (!ew_Empty($this->picture->Upload->Value)) {
				if ($this->picture->Upload->FileName == $this->picture->Upload->DbValue) { // Overwrite if same file name
					$this->picture->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['picture'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->picture->UploadPath), $rsnew['picture']); // Get new file name
				}
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->_userID->setDbValue($conn->Insert_ID());
				$rsnew['userID'] = $this->_userID->DbValue;
				if ($this->picture->Visible && !$this->picture->Upload->KeepFile) {
					if (!ew_Empty($this->picture->Upload->Value)) {
						$this->picture->Upload->SaveToFile($this->picture->UploadPath, $rsnew['picture'], TRUE);
					}
					if ($this->picture->Upload->DbValue <> "")
						@unlink(ew_UploadPathEx(TRUE, $this->picture->OldUploadPath) . $this->picture->Upload->DbValue);
				}
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

		// picture
		ew_CleanUploadTempPath($this->picture, $this->picture->Upload->Index);
		return $AddRow;
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
if (!isset($sana_user_add)) $sana_user_add = new csana_user_add();

// Page init
$sana_user_add->Page_Init();

// Page main
$sana_user_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_user_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fsana_useradd = new ew_Form("fsana_useradd", "add");

// Validate form
fsana_useradd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_username");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_user->username->FldCaption(), $sana_user->username->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_personName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_user->personName->FldCaption(), $sana_user->personName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lastName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_user->lastName->FldCaption(), $sana_user->lastName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nationalNumber");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_user->nationalNumber->FldCaption(), $sana_user->nationalNumber->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_mobilePhone");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_user->mobilePhone->FldCaption(), $sana_user->mobilePhone->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_userPassword");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_user->userPassword->FldCaption(), $sana_user->userPassword->ReqErrMsg)) ?>");
			if ($(fobj.x_userPassword).hasClass("ewPasswordStrength") && !$(fobj.x_userPassword).data("validated"))
				return this.OnError(fobj.x_userPassword, ewLanguage.Phrase("PasswordTooSimple"));

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
fsana_useradd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_useradd.ValidateRequired = true;
<?php } else { ?>
fsana_useradd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsana_useradd.Lists["x_acl"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $sana_user_add->ShowPageHeader(); ?>
<?php
$sana_user_add->ShowMessage();
?>
<form name="fsana_useradd" id="fsana_useradd" class="<?php echo $sana_user_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_user_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_user_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_user">
<input type="hidden" name="a_add" id="a_add" value="A">
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<div>
<?php if ($sana_user->username->Visible) { // username ?>
	<div id="r_username" class="form-group">
		<label id="elh_sana_user_username" for="x_username" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->username->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->username->CellAttributes() ?>>
<span id="el_sana_user_username">
<input type="text" data-table="sana_user" data-field="x_username" name="x_username" id="x_username" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_user->username->getPlaceHolder()) ?>" value="<?php echo $sana_user->username->EditValue ?>"<?php echo $sana_user->username->EditAttributes() ?>>
</span>
<?php echo $sana_user->username->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->personName->Visible) { // personName ?>
	<div id="r_personName" class="form-group">
		<label id="elh_sana_user_personName" for="x_personName" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->personName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->personName->CellAttributes() ?>>
<span id="el_sana_user_personName">
<input type="text" data-table="sana_user" data-field="x_personName" name="x_personName" id="x_personName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_user->personName->getPlaceHolder()) ?>" value="<?php echo $sana_user->personName->EditValue ?>"<?php echo $sana_user->personName->EditAttributes() ?>>
</span>
<?php echo $sana_user->personName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->lastName->Visible) { // lastName ?>
	<div id="r_lastName" class="form-group">
		<label id="elh_sana_user_lastName" for="x_lastName" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->lastName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->lastName->CellAttributes() ?>>
<span id="el_sana_user_lastName">
<input type="text" data-table="sana_user" data-field="x_lastName" name="x_lastName" id="x_lastName" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($sana_user->lastName->getPlaceHolder()) ?>" value="<?php echo $sana_user->lastName->EditValue ?>"<?php echo $sana_user->lastName->EditAttributes() ?>>
</span>
<?php echo $sana_user->lastName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->nationalID->Visible) { // nationalID ?>
	<div id="r_nationalID" class="form-group">
		<label id="elh_sana_user_nationalID" for="x_nationalID" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->nationalID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->nationalID->CellAttributes() ?>>
<span id="el_sana_user_nationalID">
<input type="text" data-table="sana_user" data-field="x_nationalID" name="x_nationalID" id="x_nationalID" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($sana_user->nationalID->getPlaceHolder()) ?>" value="<?php echo $sana_user->nationalID->EditValue ?>"<?php echo $sana_user->nationalID->EditAttributes() ?>>
</span>
<?php echo $sana_user->nationalID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->nationalNumber->Visible) { // nationalNumber ?>
	<div id="r_nationalNumber" class="form-group">
		<label id="elh_sana_user_nationalNumber" for="x_nationalNumber" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->nationalNumber->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->nationalNumber->CellAttributes() ?>>
<span id="el_sana_user_nationalNumber">
<input type="text" data-table="sana_user" data-field="x_nationalNumber" name="x_nationalNumber" id="x_nationalNumber" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($sana_user->nationalNumber->getPlaceHolder()) ?>" value="<?php echo $sana_user->nationalNumber->EditValue ?>"<?php echo $sana_user->nationalNumber->EditAttributes() ?>>
</span>
<?php echo $sana_user->nationalNumber->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->ageRange->Visible) { // ageRange ?>
	<div id="r_ageRange" class="form-group">
		<label id="elh_sana_user_ageRange" for="x_ageRange" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->ageRange->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->ageRange->CellAttributes() ?>>
<span id="el_sana_user_ageRange">
<input type="text" data-table="sana_user" data-field="x_ageRange" name="x_ageRange" id="x_ageRange" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($sana_user->ageRange->getPlaceHolder()) ?>" value="<?php echo $sana_user->ageRange->EditValue ?>"<?php echo $sana_user->ageRange->EditAttributes() ?>>
</span>
<?php echo $sana_user->ageRange->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->mobilePhone->Visible) { // mobilePhone ?>
	<div id="r_mobilePhone" class="form-group">
		<label id="elh_sana_user_mobilePhone" for="x_mobilePhone" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->mobilePhone->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->mobilePhone->CellAttributes() ?>>
<span id="el_sana_user_mobilePhone">
<input type="text" data-table="sana_user" data-field="x_mobilePhone" name="x_mobilePhone" id="x_mobilePhone" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($sana_user->mobilePhone->getPlaceHolder()) ?>" value="<?php echo $sana_user->mobilePhone->EditValue ?>"<?php echo $sana_user->mobilePhone->EditAttributes() ?>>
</span>
<?php echo $sana_user->mobilePhone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->userPassword->Visible) { // userPassword ?>
	<div id="r_userPassword" class="form-group">
		<label id="elh_sana_user_userPassword" for="x_userPassword" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->userPassword->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->userPassword->CellAttributes() ?>>
<span id="el_sana_user_userPassword">
<div class="input-group" id="ig_x_userPassword">
<input type="password" data-password-strength="pst_x_userPassword" data-password-generated="pgt_x_userPassword" data-table="sana_user" data-field="x_userPassword" name="x_userPassword" id="x_userPassword" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_user->userPassword->getPlaceHolder()) ?>"<?php echo $sana_user->userPassword->EditAttributes() ?>>
<span class="input-group-btn">
	<button type="button" class="btn btn-default ewPasswordGenerator" title="<?php echo ew_HtmlTitle($Language->Phrase("GeneratePassword")) ?>" data-password-field="x_userPassword" data-password-confirm="c_userPassword" data-password-strength="pst_x_userPassword" data-password-generated="pgt_x_userPassword"><?php echo $Language->Phrase("GeneratePassword") ?></button>
</span>
</div>
<span class="help-block" id="pgt_x_userPassword" style="display: none;"></span>
<div class="progress ewPasswordStrengthBar" id="pst_x_userPassword" style="display: none;">
	<div class="progress-bar" role="progressbar"></div>
</div>
</span>
<?php echo $sana_user->userPassword->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_sana_user__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->_email->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->_email->CellAttributes() ?>>
<span id="el_sana_user__email">
<input type="text" data-table="sana_user" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($sana_user->_email->getPlaceHolder()) ?>" value="<?php echo $sana_user->_email->EditValue ?>"<?php echo $sana_user->_email->EditAttributes() ?>>
</span>
<?php echo $sana_user->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->picture->Visible) { // picture ?>
	<div id="r_picture" class="form-group">
		<label id="elh_sana_user_picture" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->picture->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->picture->CellAttributes() ?>>
<span id="el_sana_user_picture">
<div id="fd_x_picture">
<span title="<?php echo $sana_user->picture->FldTitle() ? $sana_user->picture->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($sana_user->picture->ReadOnly || $sana_user->picture->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="sana_user" data-field="x_picture" name="x_picture" id="x_picture"<?php echo $sana_user->picture->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_picture" id= "fn_x_picture" value="<?php echo $sana_user->picture->Upload->FileName ?>">
<input type="hidden" name="fa_x_picture" id= "fa_x_picture" value="0">
<input type="hidden" name="fs_x_picture" id= "fs_x_picture" value="255">
<input type="hidden" name="fx_x_picture" id= "fx_x_picture" value="<?php echo $sana_user->picture->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_picture" id= "fm_x_picture" value="<?php echo $sana_user->picture->UploadMaxFileSize ?>">
</div>
<table id="ft_x_picture" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $sana_user->picture->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->acl->Visible) { // acl ?>
	<div id="r_acl" class="form-group">
		<label id="elh_sana_user_acl" for="x_acl" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->acl->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->acl->CellAttributes() ?>>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<span id="el_sana_user_acl">
<p class="form-control-static"><?php echo $sana_user->acl->EditValue ?></p>
</span>
<?php } else { ?>
<span id="el_sana_user_acl">
<select data-table="sana_user" data-field="x_acl" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_user->acl->DisplayValueSeparator) ? json_encode($sana_user->acl->DisplayValueSeparator) : $sana_user->acl->DisplayValueSeparator) ?>" id="x_acl" name="x_acl"<?php echo $sana_user->acl->EditAttributes() ?>>
<?php
if (is_array($sana_user->acl->EditValue)) {
	$arwrk = $sana_user->acl->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sana_user->acl->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sana_user->acl->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($sana_user->acl->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($sana_user->acl->CurrentValue) ?>" selected><?php echo $sana_user->acl->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
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
$sana_user->acl->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sana_user->acl->LookupFilters += array("f0" => "`userlevelid` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$sana_user->Lookup_Selecting($sana_user->acl, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sana_user->acl->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_acl" id="s_x_acl" value="<?php echo $sana_user->acl->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $sana_user->acl->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label id="elh_sana_user_description" for="x_description" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->description->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->description->CellAttributes() ?>>
<span id="el_sana_user_description">
<textarea data-table="sana_user" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($sana_user->description->getPlaceHolder()) ?>"<?php echo $sana_user->description->EditAttributes() ?>><?php echo $sana_user->description->EditValue ?></textarea>
</span>
<?php echo $sana_user->description->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sana_user_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fsana_useradd.Init();
</script>
<?php
$sana_user_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_user_add->Page_Terminate();
?>
