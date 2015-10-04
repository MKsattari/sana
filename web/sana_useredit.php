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

$sana_user_edit = NULL; // Initialize page object first

class csana_user_edit extends csana_user {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_user';

	// Page object name
	var $PageObjName = 'sana_user_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->IsLoggedIn()) $this->Page_Terminate(ew_GetUrl("login.php"));
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["_userID"] <> "") {
			$this->_userID->setQueryStringValue($_GET["_userID"]);
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
		if ($this->_userID->CurrentValue == "")
			$this->Page_Terminate("sana_userlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("sana_userlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "sana_userlist.php")
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
		if (!$this->_userID->FldIsDetailKey)
			$this->_userID->setFormValue($objForm->GetValue("x__userID"));
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
		if (!$this->fatherName->FldIsDetailKey) {
			$this->fatherName->setFormValue($objForm->GetValue("x_fatherName"));
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
		if (!$this->birthDate->FldIsDetailKey) {
			$this->birthDate->setFormValue($objForm->GetValue("x_birthDate"));
		}
		if (!$this->ageRange->FldIsDetailKey) {
			$this->ageRange->setFormValue($objForm->GetValue("x_ageRange"));
		}
		if (!$this->phone->FldIsDetailKey) {
			$this->phone->setFormValue($objForm->GetValue("x_phone"));
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
		$this->LoadRow();
		$this->_userID->CurrentValue = $this->_userID->FormValue;
		$this->username->CurrentValue = $this->username->FormValue;
		$this->personName->CurrentValue = $this->personName->FormValue;
		$this->lastName->CurrentValue = $this->lastName->FormValue;
		$this->nationalID->CurrentValue = $this->nationalID->FormValue;
		$this->nationalNumber->CurrentValue = $this->nationalNumber->FormValue;
		$this->fatherName->CurrentValue = $this->fatherName->FormValue;
		$this->country->CurrentValue = $this->country->FormValue;
		$this->province->CurrentValue = $this->province->FormValue;
		$this->county->CurrentValue = $this->county->FormValue;
		$this->district->CurrentValue = $this->district->FormValue;
		$this->city_ruralDistrict->CurrentValue = $this->city_ruralDistrict->FormValue;
		$this->region_village->CurrentValue = $this->region_village->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->birthDate->CurrentValue = $this->birthDate->FormValue;
		$this->ageRange->CurrentValue = $this->ageRange->FormValue;
		$this->phone->CurrentValue = $this->phone->FormValue;
		$this->mobilePhone->CurrentValue = $this->mobilePhone->FormValue;
		$this->userPassword->CurrentValue = $this->userPassword->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->picture->CurrentValue = $this->picture->FormValue;
		$this->registrationUser->CurrentValue = $this->registrationUser->FormValue;
		$this->registrationDateTime->CurrentValue = $this->registrationDateTime->FormValue;
		$this->registrationDateTime->CurrentValue = ew_UnFormatDateTime($this->registrationDateTime->CurrentValue, 5);
		$this->registrationStation->CurrentValue = $this->registrationStation->FormValue;
		$this->isolatedDateTime->CurrentValue = $this->isolatedDateTime->FormValue;
		$this->isolatedDateTime->CurrentValue = ew_UnFormatDateTime($this->isolatedDateTime->CurrentValue, 5);
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
			$res = $this->ShowOptionLink('edit');
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
		$this->picture->setDbValue($rs->fields('picture'));
		$this->registrationUser->setDbValue($rs->fields('registrationUser'));
		$this->registrationDateTime->setDbValue($rs->fields('registrationDateTime'));
		$this->registrationStation->setDbValue($rs->fields('registrationStation'));
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
		$this->picture->DbValue = $row['picture'];
		$this->registrationUser->DbValue = $row['registrationUser'];
		$this->registrationDateTime->DbValue = $row['registrationDateTime'];
		$this->registrationStation->DbValue = $row['registrationStation'];
		$this->isolatedDateTime->DbValue = $row['isolatedDateTime'];
		$this->acl->DbValue = $row['acl'];
		$this->description->DbValue = $row['description'];
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
		// registrationStation
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

		// acl
		$this->acl->ViewValue = $this->acl->CurrentValue;
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

			// acl
			$this->acl->LinkCustomAttributes = "";
			$this->acl->HrefValue = "";
			$this->acl->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// userID
			$this->_userID->EditAttrs["class"] = "form-control";
			$this->_userID->EditCustomAttributes = "";
			$this->_userID->EditValue = $this->_userID->CurrentValue;
			$this->_userID->ViewCustomAttributes = "";

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

			// fatherName
			$this->fatherName->EditAttrs["class"] = "form-control";
			$this->fatherName->EditCustomAttributes = "";
			$this->fatherName->EditValue = ew_HtmlEncode($this->fatherName->CurrentValue);
			$this->fatherName->PlaceHolder = ew_RemoveHtml($this->fatherName->FldCaption());

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

			// acl
			$this->acl->EditAttrs["class"] = "form-control";
			$this->acl->EditCustomAttributes = "";
			$this->acl->EditValue = ew_HtmlEncode($this->acl->CurrentValue);
			$this->acl->PlaceHolder = ew_RemoveHtml($this->acl->FldCaption());

			// description
			$this->description->EditAttrs["class"] = "form-control";
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);
			$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

			// Edit refer script
			// userID

			$this->_userID->LinkCustomAttributes = "";
			$this->_userID->HrefValue = "";

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

			// fatherName
			$this->fatherName->LinkCustomAttributes = "";
			$this->fatherName->HrefValue = "";

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

			// birthDate
			$this->birthDate->LinkCustomAttributes = "";
			$this->birthDate->HrefValue = "";

			// ageRange
			$this->ageRange->LinkCustomAttributes = "";
			$this->ageRange->HrefValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";

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
		if (!$this->fatherName->FldIsDetailKey && !is_null($this->fatherName->FormValue) && $this->fatherName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fatherName->FldCaption(), $this->fatherName->ReqErrMsg));
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

			// username
			$this->username->SetDbValueDef($rsnew, $this->username->CurrentValue, "", $this->username->ReadOnly);

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

			// birthDate
			$this->birthDate->SetDbValueDef($rsnew, $this->birthDate->CurrentValue, NULL, $this->birthDate->ReadOnly);

			// ageRange
			$this->ageRange->SetDbValueDef($rsnew, $this->ageRange->CurrentValue, NULL, $this->ageRange->ReadOnly);

			// phone
			$this->phone->SetDbValueDef($rsnew, $this->phone->CurrentValue, NULL, $this->phone->ReadOnly);

			// mobilePhone
			$this->mobilePhone->SetDbValueDef($rsnew, $this->mobilePhone->CurrentValue, NULL, $this->mobilePhone->ReadOnly);

			// userPassword
			$this->userPassword->SetDbValueDef($rsnew, $this->userPassword->CurrentValue, NULL, $this->userPassword->ReadOnly || (EW_ENCRYPTED_PASSWORD && $rs->fields('userPassword') == $this->userPassword->CurrentValue));

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, $this->_email->ReadOnly);

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

			// acl
			$this->acl->SetDbValueDef($rsnew, $this->acl->CurrentValue, NULL, $this->acl->ReadOnly);

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
if (!isset($sana_user_edit)) $sana_user_edit = new csana_user_edit();

// Page init
$sana_user_edit->Page_Init();

// Page main
$sana_user_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_user_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fsana_useredit = new ew_Form("fsana_useredit", "edit");

// Validate form
fsana_useredit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_fatherName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_user->fatherName->FldCaption(), $sana_user->fatherName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_country");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_user->country->FldCaption(), $sana_user->country->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_province");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_user->province->FldCaption(), $sana_user->province->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_birthDate");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_user->birthDate->FldErrMsg()) ?>");
			if ($(fobj.x_userPassword).hasClass("ewPasswordStrength") && !$(fobj.x_userPassword).data("validated"))
				return this.OnError(fobj.x_userPassword, ewLanguage.Phrase("PasswordTooSimple"));
			elm = this.GetElements("x" + infix + "_registrationUser");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_user->registrationUser->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_registrationDateTime");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_user->registrationDateTime->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_registrationStation");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_user->registrationStation->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_isolatedDateTime");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_user->isolatedDateTime->FldErrMsg()) ?>");

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
fsana_useredit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_useredit.ValidateRequired = true;
<?php } else { ?>
fsana_useredit.ValidateRequired = false; 
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
<?php $sana_user_edit->ShowPageHeader(); ?>
<?php
$sana_user_edit->ShowMessage();
?>
<form name="fsana_useredit" id="fsana_useredit" class="<?php echo $sana_user_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_user_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_user_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_user">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<div>
<?php if ($sana_user->_userID->Visible) { // userID ?>
	<div id="r__userID" class="form-group">
		<label id="elh_sana_user__userID" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->_userID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->_userID->CellAttributes() ?>>
<span id="el_sana_user__userID">
<span<?php echo $sana_user->_userID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sana_user->_userID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="sana_user" data-field="x__userID" name="x__userID" id="x__userID" value="<?php echo ew_HtmlEncode($sana_user->_userID->CurrentValue) ?>">
<?php echo $sana_user->_userID->CustomMsg ?></div></div>
	</div>
<?php } ?>
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
		<label id="elh_sana_user_nationalNumber" for="x_nationalNumber" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->nationalNumber->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->nationalNumber->CellAttributes() ?>>
<span id="el_sana_user_nationalNumber">
<input type="text" data-table="sana_user" data-field="x_nationalNumber" name="x_nationalNumber" id="x_nationalNumber" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($sana_user->nationalNumber->getPlaceHolder()) ?>" value="<?php echo $sana_user->nationalNumber->EditValue ?>"<?php echo $sana_user->nationalNumber->EditAttributes() ?>>
</span>
<?php echo $sana_user->nationalNumber->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->fatherName->Visible) { // fatherName ?>
	<div id="r_fatherName" class="form-group">
		<label id="elh_sana_user_fatherName" for="x_fatherName" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->fatherName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->fatherName->CellAttributes() ?>>
<span id="el_sana_user_fatherName">
<input type="text" data-table="sana_user" data-field="x_fatherName" name="x_fatherName" id="x_fatherName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_user->fatherName->getPlaceHolder()) ?>" value="<?php echo $sana_user->fatherName->EditValue ?>"<?php echo $sana_user->fatherName->EditAttributes() ?>>
</span>
<?php echo $sana_user->fatherName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->country->Visible) { // country ?>
	<div id="r_country" class="form-group">
		<label id="elh_sana_user_country" for="x_country" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->country->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->country->CellAttributes() ?>>
<span id="el_sana_user_country">
<input type="text" data-table="sana_user" data-field="x_country" name="x_country" id="x_country" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_user->country->getPlaceHolder()) ?>" value="<?php echo $sana_user->country->EditValue ?>"<?php echo $sana_user->country->EditAttributes() ?>>
</span>
<?php echo $sana_user->country->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->province->Visible) { // province ?>
	<div id="r_province" class="form-group">
		<label id="elh_sana_user_province" for="x_province" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->province->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->province->CellAttributes() ?>>
<span id="el_sana_user_province">
<input type="text" data-table="sana_user" data-field="x_province" name="x_province" id="x_province" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_user->province->getPlaceHolder()) ?>" value="<?php echo $sana_user->province->EditValue ?>"<?php echo $sana_user->province->EditAttributes() ?>>
</span>
<?php echo $sana_user->province->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->county->Visible) { // county ?>
	<div id="r_county" class="form-group">
		<label id="elh_sana_user_county" for="x_county" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->county->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->county->CellAttributes() ?>>
<span id="el_sana_user_county">
<input type="text" data-table="sana_user" data-field="x_county" name="x_county" id="x_county" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_user->county->getPlaceHolder()) ?>" value="<?php echo $sana_user->county->EditValue ?>"<?php echo $sana_user->county->EditAttributes() ?>>
</span>
<?php echo $sana_user->county->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->district->Visible) { // district ?>
	<div id="r_district" class="form-group">
		<label id="elh_sana_user_district" for="x_district" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->district->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->district->CellAttributes() ?>>
<span id="el_sana_user_district">
<input type="text" data-table="sana_user" data-field="x_district" name="x_district" id="x_district" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_user->district->getPlaceHolder()) ?>" value="<?php echo $sana_user->district->EditValue ?>"<?php echo $sana_user->district->EditAttributes() ?>>
</span>
<?php echo $sana_user->district->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->city_ruralDistrict->Visible) { // city_ruralDistrict ?>
	<div id="r_city_ruralDistrict" class="form-group">
		<label id="elh_sana_user_city_ruralDistrict" for="x_city_ruralDistrict" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->city_ruralDistrict->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->city_ruralDistrict->CellAttributes() ?>>
<span id="el_sana_user_city_ruralDistrict">
<input type="text" data-table="sana_user" data-field="x_city_ruralDistrict" name="x_city_ruralDistrict" id="x_city_ruralDistrict" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_user->city_ruralDistrict->getPlaceHolder()) ?>" value="<?php echo $sana_user->city_ruralDistrict->EditValue ?>"<?php echo $sana_user->city_ruralDistrict->EditAttributes() ?>>
</span>
<?php echo $sana_user->city_ruralDistrict->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->region_village->Visible) { // region_village ?>
	<div id="r_region_village" class="form-group">
		<label id="elh_sana_user_region_village" for="x_region_village" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->region_village->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->region_village->CellAttributes() ?>>
<span id="el_sana_user_region_village">
<input type="text" data-table="sana_user" data-field="x_region_village" name="x_region_village" id="x_region_village" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_user->region_village->getPlaceHolder()) ?>" value="<?php echo $sana_user->region_village->EditValue ?>"<?php echo $sana_user->region_village->EditAttributes() ?>>
</span>
<?php echo $sana_user->region_village->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label id="elh_sana_user_address" for="x_address" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->address->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->address->CellAttributes() ?>>
<span id="el_sana_user_address">
<input type="text" data-table="sana_user" data-field="x_address" name="x_address" id="x_address" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_user->address->getPlaceHolder()) ?>" value="<?php echo $sana_user->address->EditValue ?>"<?php echo $sana_user->address->EditAttributes() ?>>
</span>
<?php echo $sana_user->address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->birthDate->Visible) { // birthDate ?>
	<div id="r_birthDate" class="form-group">
		<label id="elh_sana_user_birthDate" for="x_birthDate" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->birthDate->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->birthDate->CellAttributes() ?>>
<span id="el_sana_user_birthDate">
<input type="text" data-table="sana_user" data-field="x_birthDate" name="x_birthDate" id="x_birthDate" size="30" placeholder="<?php echo ew_HtmlEncode($sana_user->birthDate->getPlaceHolder()) ?>" value="<?php echo $sana_user->birthDate->EditValue ?>"<?php echo $sana_user->birthDate->EditAttributes() ?>>
</span>
<?php echo $sana_user->birthDate->CustomMsg ?></div></div>
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
<?php if ($sana_user->phone->Visible) { // phone ?>
	<div id="r_phone" class="form-group">
		<label id="elh_sana_user_phone" for="x_phone" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->phone->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->phone->CellAttributes() ?>>
<span id="el_sana_user_phone">
<input type="text" data-table="sana_user" data-field="x_phone" name="x_phone" id="x_phone" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_user->phone->getPlaceHolder()) ?>" value="<?php echo $sana_user->phone->EditValue ?>"<?php echo $sana_user->phone->EditAttributes() ?>>
</span>
<?php echo $sana_user->phone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->mobilePhone->Visible) { // mobilePhone ?>
	<div id="r_mobilePhone" class="form-group">
		<label id="elh_sana_user_mobilePhone" for="x_mobilePhone" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->mobilePhone->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->mobilePhone->CellAttributes() ?>>
<span id="el_sana_user_mobilePhone">
<input type="text" data-table="sana_user" data-field="x_mobilePhone" name="x_mobilePhone" id="x_mobilePhone" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($sana_user->mobilePhone->getPlaceHolder()) ?>" value="<?php echo $sana_user->mobilePhone->EditValue ?>"<?php echo $sana_user->mobilePhone->EditAttributes() ?>>
</span>
<?php echo $sana_user->mobilePhone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->userPassword->Visible) { // userPassword ?>
	<div id="r_userPassword" class="form-group">
		<label id="elh_sana_user_userPassword" for="x_userPassword" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->userPassword->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->userPassword->CellAttributes() ?>>
<span id="el_sana_user_userPassword">
<div class="input-group" id="ig_x_userPassword">
<input type="text" data-password-strength="pst_x_userPassword" data-password-generated="pgt_x_userPassword" data-table="sana_user" data-field="x_userPassword" name="x_userPassword" id="x_userPassword" value="<?php echo $sana_user->userPassword->EditValue ?>" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_user->userPassword->getPlaceHolder()) ?>"<?php echo $sana_user->userPassword->EditAttributes() ?>>
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
		<label id="elh_sana_user_picture" for="x_picture" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->picture->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->picture->CellAttributes() ?>>
<span id="el_sana_user_picture">
<input type="text" data-table="sana_user" data-field="x_picture" name="x_picture" id="x_picture" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_user->picture->getPlaceHolder()) ?>" value="<?php echo $sana_user->picture->EditValue ?>"<?php echo $sana_user->picture->EditAttributes() ?>>
</span>
<?php echo $sana_user->picture->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->registrationUser->Visible) { // registrationUser ?>
	<div id="r_registrationUser" class="form-group">
		<label id="elh_sana_user_registrationUser" for="x_registrationUser" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->registrationUser->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->registrationUser->CellAttributes() ?>>
<span id="el_sana_user_registrationUser">
<input type="text" data-table="sana_user" data-field="x_registrationUser" name="x_registrationUser" id="x_registrationUser" size="30" placeholder="<?php echo ew_HtmlEncode($sana_user->registrationUser->getPlaceHolder()) ?>" value="<?php echo $sana_user->registrationUser->EditValue ?>"<?php echo $sana_user->registrationUser->EditAttributes() ?>>
</span>
<?php echo $sana_user->registrationUser->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->registrationDateTime->Visible) { // registrationDateTime ?>
	<div id="r_registrationDateTime" class="form-group">
		<label id="elh_sana_user_registrationDateTime" for="x_registrationDateTime" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->registrationDateTime->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->registrationDateTime->CellAttributes() ?>>
<span id="el_sana_user_registrationDateTime">
<input type="text" data-table="sana_user" data-field="x_registrationDateTime" data-format="5" name="x_registrationDateTime" id="x_registrationDateTime" placeholder="<?php echo ew_HtmlEncode($sana_user->registrationDateTime->getPlaceHolder()) ?>" value="<?php echo $sana_user->registrationDateTime->EditValue ?>"<?php echo $sana_user->registrationDateTime->EditAttributes() ?>>
</span>
<?php echo $sana_user->registrationDateTime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->registrationStation->Visible) { // registrationStation ?>
	<div id="r_registrationStation" class="form-group">
		<label id="elh_sana_user_registrationStation" for="x_registrationStation" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->registrationStation->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->registrationStation->CellAttributes() ?>>
<span id="el_sana_user_registrationStation">
<input type="text" data-table="sana_user" data-field="x_registrationStation" name="x_registrationStation" id="x_registrationStation" size="30" placeholder="<?php echo ew_HtmlEncode($sana_user->registrationStation->getPlaceHolder()) ?>" value="<?php echo $sana_user->registrationStation->EditValue ?>"<?php echo $sana_user->registrationStation->EditAttributes() ?>>
</span>
<?php echo $sana_user->registrationStation->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->isolatedDateTime->Visible) { // isolatedDateTime ?>
	<div id="r_isolatedDateTime" class="form-group">
		<label id="elh_sana_user_isolatedDateTime" for="x_isolatedDateTime" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->isolatedDateTime->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->isolatedDateTime->CellAttributes() ?>>
<span id="el_sana_user_isolatedDateTime">
<input type="text" data-table="sana_user" data-field="x_isolatedDateTime" data-format="5" name="x_isolatedDateTime" id="x_isolatedDateTime" placeholder="<?php echo ew_HtmlEncode($sana_user->isolatedDateTime->getPlaceHolder()) ?>" value="<?php echo $sana_user->isolatedDateTime->EditValue ?>"<?php echo $sana_user->isolatedDateTime->EditAttributes() ?>>
</span>
<?php echo $sana_user->isolatedDateTime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_user->acl->Visible) { // acl ?>
	<div id="r_acl" class="form-group">
		<label id="elh_sana_user_acl" for="x_acl" class="col-sm-2 control-label ewLabel"><?php echo $sana_user->acl->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_user->acl->CellAttributes() ?>>
<span id="el_sana_user_acl">
<input type="text" data-table="sana_user" data-field="x_acl" name="x_acl" id="x_acl" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_user->acl->getPlaceHolder()) ?>" value="<?php echo $sana_user->acl->EditValue ?>"<?php echo $sana_user->acl->EditAttributes() ?>>
</span>
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
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sana_user_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fsana_useredit.Init();
</script>
<?php
$sana_user_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_user_edit->Page_Terminate();
?>
