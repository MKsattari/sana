<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "sana_personinfo.php" ?>
<?php include_once "sana_userinfo.php" ?>
<?php include_once "sana_messagegridcls.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$sana_person_add = NULL; // Initialize page object first

class csana_person_add extends csana_person {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_person';

	// Page object name
	var $PageObjName = 'sana_person_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("sana_personlist.php"));
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

		// Set up multi page object
		$this->SetupMultiPages();

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

			// Process auto fill for detail table 'sana_message'
			if (@$_POST["grid"] == "fsana_messagegrid") {
				if (!isset($GLOBALS["sana_message_grid"])) $GLOBALS["sana_message_grid"] = new csana_message_grid;
				$GLOBALS["sana_message_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;
	var $MultiPages; // Multi pages object

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
			if (@$_GET["personID"] != "") {
				$this->personID->setQueryStringValue($_GET["personID"]);
				$this->setKey("personID", $this->personID->CurrentValue); // Set up key
			} else {
				$this->setKey("personID", ""); // Clear key
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

		// Set up detail parameters
		$this->SetUpDetailParms();

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
					$this->Page_Terminate("sana_personlist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "sana_personlist.php")
						$sReturnUrl = $this->AddMasterUrl($this->GetListUrl()); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "sana_personview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		$this->personName->CurrentValue = NULL;
		$this->personName->OldValue = $this->personName->CurrentValue;
		$this->lastName->CurrentValue = NULL;
		$this->lastName->OldValue = $this->lastName->CurrentValue;
		$this->nationalID->CurrentValue = NULL;
		$this->nationalID->OldValue = $this->nationalID->CurrentValue;
		$this->mobilePhone->CurrentValue = NULL;
		$this->mobilePhone->OldValue = $this->mobilePhone->CurrentValue;
		$this->nationalNumber->CurrentValue = NULL;
		$this->nationalNumber->OldValue = $this->nationalNumber->CurrentValue;
		$this->passportNumber->CurrentValue = NULL;
		$this->passportNumber->OldValue = $this->passportNumber->CurrentValue;
		$this->fatherName->CurrentValue = NULL;
		$this->fatherName->OldValue = $this->fatherName->CurrentValue;
		$this->gender->CurrentValue = NULL;
		$this->gender->OldValue = $this->gender->CurrentValue;
		$this->locationLevel1->CurrentValue = NULL;
		$this->locationLevel1->OldValue = $this->locationLevel1->CurrentValue;
		$this->locationLevel2->CurrentValue = NULL;
		$this->locationLevel2->OldValue = $this->locationLevel2->CurrentValue;
		$this->locationLevel3->CurrentValue = NULL;
		$this->locationLevel3->OldValue = $this->locationLevel3->CurrentValue;
		$this->locationLevel4->CurrentValue = NULL;
		$this->locationLevel4->OldValue = $this->locationLevel4->CurrentValue;
		$this->locationLevel5->CurrentValue = NULL;
		$this->locationLevel5->OldValue = $this->locationLevel5->CurrentValue;
		$this->locationLevel6->CurrentValue = NULL;
		$this->locationLevel6->OldValue = $this->locationLevel6->CurrentValue;
		$this->address->CurrentValue = NULL;
		$this->address->OldValue = $this->address->CurrentValue;
		$this->convoy->CurrentValue = NULL;
		$this->convoy->OldValue = $this->convoy->CurrentValue;
		$this->convoyManager->CurrentValue = NULL;
		$this->convoyManager->OldValue = $this->convoyManager->CurrentValue;
		$this->followersName->CurrentValue = NULL;
		$this->followersName->OldValue = $this->followersName->CurrentValue;
		$this->status->CurrentValue = NULL;
		$this->status->OldValue = $this->status->CurrentValue;
		$this->isolatedLocation->CurrentValue = NULL;
		$this->isolatedLocation->OldValue = $this->isolatedLocation->CurrentValue;
		$this->birthDate->CurrentValue = NULL;
		$this->birthDate->OldValue = $this->birthDate->CurrentValue;
		$this->ageRange->CurrentValue = NULL;
		$this->ageRange->OldValue = $this->ageRange->CurrentValue;
		$this->dress1->CurrentValue = NULL;
		$this->dress1->OldValue = $this->dress1->CurrentValue;
		$this->dress2->CurrentValue = NULL;
		$this->dress2->OldValue = $this->dress2->CurrentValue;
		$this->signTags->CurrentValue = NULL;
		$this->signTags->OldValue = $this->signTags->CurrentValue;
		$this->phone->CurrentValue = NULL;
		$this->phone->OldValue = $this->phone->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->temporaryResidence->CurrentValue = NULL;
		$this->temporaryResidence->OldValue = $this->temporaryResidence->CurrentValue;
		$this->visitsCount->CurrentValue = NULL;
		$this->visitsCount->OldValue = $this->visitsCount->CurrentValue;
		$this->picture->Upload->DbValue = NULL;
		$this->picture->OldValue = $this->picture->Upload->DbValue;
		$this->picture->CurrentValue = NULL; // Clear file related field
		$this->registrationUser->CurrentValue = NULL;
		$this->registrationUser->OldValue = $this->registrationUser->CurrentValue;
		$this->registrationDateTime->CurrentValue = NULL;
		$this->registrationDateTime->OldValue = $this->registrationDateTime->CurrentValue;
		$this->registrationStation->CurrentValue = NULL;
		$this->registrationStation->OldValue = $this->registrationStation->CurrentValue;
		$this->isolatedDateTime->CurrentValue = NULL;
		$this->isolatedDateTime->OldValue = $this->isolatedDateTime->CurrentValue;
		$this->description->CurrentValue = NULL;
		$this->description->OldValue = $this->description->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->personName->FldIsDetailKey) {
			$this->personName->setFormValue($objForm->GetValue("x_personName"));
		}
		if (!$this->lastName->FldIsDetailKey) {
			$this->lastName->setFormValue($objForm->GetValue("x_lastName"));
		}
		if (!$this->nationalID->FldIsDetailKey) {
			$this->nationalID->setFormValue($objForm->GetValue("x_nationalID"));
		}
		if (!$this->mobilePhone->FldIsDetailKey) {
			$this->mobilePhone->setFormValue($objForm->GetValue("x_mobilePhone"));
		}
		if (!$this->nationalNumber->FldIsDetailKey) {
			$this->nationalNumber->setFormValue($objForm->GetValue("x_nationalNumber"));
		}
		if (!$this->passportNumber->FldIsDetailKey) {
			$this->passportNumber->setFormValue($objForm->GetValue("x_passportNumber"));
		}
		if (!$this->fatherName->FldIsDetailKey) {
			$this->fatherName->setFormValue($objForm->GetValue("x_fatherName"));
		}
		if (!$this->gender->FldIsDetailKey) {
			$this->gender->setFormValue($objForm->GetValue("x_gender"));
		}
		if (!$this->locationLevel1->FldIsDetailKey) {
			$this->locationLevel1->setFormValue($objForm->GetValue("x_locationLevel1"));
		}
		if (!$this->locationLevel2->FldIsDetailKey) {
			$this->locationLevel2->setFormValue($objForm->GetValue("x_locationLevel2"));
		}
		if (!$this->locationLevel3->FldIsDetailKey) {
			$this->locationLevel3->setFormValue($objForm->GetValue("x_locationLevel3"));
		}
		if (!$this->locationLevel4->FldIsDetailKey) {
			$this->locationLevel4->setFormValue($objForm->GetValue("x_locationLevel4"));
		}
		if (!$this->locationLevel5->FldIsDetailKey) {
			$this->locationLevel5->setFormValue($objForm->GetValue("x_locationLevel5"));
		}
		if (!$this->locationLevel6->FldIsDetailKey) {
			$this->locationLevel6->setFormValue($objForm->GetValue("x_locationLevel6"));
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
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->temporaryResidence->FldIsDetailKey) {
			$this->temporaryResidence->setFormValue($objForm->GetValue("x_temporaryResidence"));
		}
		if (!$this->visitsCount->FldIsDetailKey) {
			$this->visitsCount->setFormValue($objForm->GetValue("x_visitsCount"));
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
		$this->LoadOldRecord();
		$this->personName->CurrentValue = $this->personName->FormValue;
		$this->lastName->CurrentValue = $this->lastName->FormValue;
		$this->nationalID->CurrentValue = $this->nationalID->FormValue;
		$this->mobilePhone->CurrentValue = $this->mobilePhone->FormValue;
		$this->nationalNumber->CurrentValue = $this->nationalNumber->FormValue;
		$this->passportNumber->CurrentValue = $this->passportNumber->FormValue;
		$this->fatherName->CurrentValue = $this->fatherName->FormValue;
		$this->gender->CurrentValue = $this->gender->FormValue;
		$this->locationLevel1->CurrentValue = $this->locationLevel1->FormValue;
		$this->locationLevel2->CurrentValue = $this->locationLevel2->FormValue;
		$this->locationLevel3->CurrentValue = $this->locationLevel3->FormValue;
		$this->locationLevel4->CurrentValue = $this->locationLevel4->FormValue;
		$this->locationLevel5->CurrentValue = $this->locationLevel5->FormValue;
		$this->locationLevel6->CurrentValue = $this->locationLevel6->FormValue;
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
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->temporaryResidence->CurrentValue = $this->temporaryResidence->FormValue;
		$this->visitsCount->CurrentValue = $this->visitsCount->FormValue;
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
		$this->mobilePhone->setDbValue($rs->fields('mobilePhone'));
		$this->nationalNumber->setDbValue($rs->fields('nationalNumber'));
		$this->passportNumber->setDbValue($rs->fields('passportNumber'));
		$this->fatherName->setDbValue($rs->fields('fatherName'));
		$this->gender->setDbValue($rs->fields('gender'));
		$this->locationLevel1->setDbValue($rs->fields('locationLevel1'));
		$this->locationLevel2->setDbValue($rs->fields('locationLevel2'));
		$this->locationLevel3->setDbValue($rs->fields('locationLevel3'));
		$this->locationLevel4->setDbValue($rs->fields('locationLevel4'));
		$this->locationLevel5->setDbValue($rs->fields('locationLevel5'));
		$this->locationLevel6->setDbValue($rs->fields('locationLevel6'));
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
		$this->_email->setDbValue($rs->fields('email'));
		$this->temporaryResidence->setDbValue($rs->fields('temporaryResidence'));
		$this->visitsCount->setDbValue($rs->fields('visitsCount'));
		$this->picture->Upload->DbValue = $rs->fields('picture');
		$this->picture->CurrentValue = $this->picture->Upload->DbValue;
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
		$this->mobilePhone->DbValue = $row['mobilePhone'];
		$this->nationalNumber->DbValue = $row['nationalNumber'];
		$this->passportNumber->DbValue = $row['passportNumber'];
		$this->fatherName->DbValue = $row['fatherName'];
		$this->gender->DbValue = $row['gender'];
		$this->locationLevel1->DbValue = $row['locationLevel1'];
		$this->locationLevel2->DbValue = $row['locationLevel2'];
		$this->locationLevel3->DbValue = $row['locationLevel3'];
		$this->locationLevel4->DbValue = $row['locationLevel4'];
		$this->locationLevel5->DbValue = $row['locationLevel5'];
		$this->locationLevel6->DbValue = $row['locationLevel6'];
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
		$this->_email->DbValue = $row['email'];
		$this->temporaryResidence->DbValue = $row['temporaryResidence'];
		$this->visitsCount->DbValue = $row['visitsCount'];
		$this->picture->Upload->DbValue = $row['picture'];
		$this->registrationUser->DbValue = $row['registrationUser'];
		$this->registrationDateTime->DbValue = $row['registrationDateTime'];
		$this->registrationStation->DbValue = $row['registrationStation'];
		$this->isolatedDateTime->DbValue = $row['isolatedDateTime'];
		$this->description->DbValue = $row['description'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("personID")) <> "")
			$this->personID->CurrentValue = $this->getKey("personID"); // personID
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
		// personID
		// personName
		// lastName
		// nationalID
		// mobilePhone
		// nationalNumber
		// passportNumber
		// fatherName
		// gender
		// locationLevel1
		// locationLevel2
		// locationLevel3
		// locationLevel4
		// locationLevel5
		// locationLevel6
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

		// mobilePhone
		$this->mobilePhone->ViewValue = $this->mobilePhone->CurrentValue;
		$this->mobilePhone->ViewCustomAttributes = "";

		// nationalNumber
		$this->nationalNumber->ViewValue = $this->nationalNumber->CurrentValue;
		$this->nationalNumber->ViewCustomAttributes = "";

		// passportNumber
		$this->passportNumber->ViewValue = $this->passportNumber->CurrentValue;
		$this->passportNumber->ViewCustomAttributes = "";

		// fatherName
		$this->fatherName->ViewValue = $this->fatherName->CurrentValue;
		$this->fatherName->ViewCustomAttributes = "";

		// gender
		if (strval($this->gender->CurrentValue) <> "") {
			$sFilterWrk = "`stateName`" . ew_SearchString("=", $this->gender->CurrentValue, EW_DATATYPE_STRING, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
				$sWhereWrk = "";
				break;
		}
		$lookuptblfilter = "`stateLanguage` = '" . CurrentLanguageID() . "' AND `stateClass` = 'gender'";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->gender, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->gender->ViewValue = $this->gender->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->gender->ViewValue = $this->gender->CurrentValue;
			}
		} else {
			$this->gender->ViewValue = NULL;
		}
		$this->gender->ViewCustomAttributes = "";

		// locationLevel1
		if (strval($this->locationLevel1->CurrentValue) <> "") {
			$sFilterWrk = "`locationName`" . ew_SearchString("=", $this->locationLevel1->CurrentValue, EW_DATATYPE_STRING, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level1`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level1`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level1`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->locationLevel1, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->locationLevel1->ViewValue = $this->locationLevel1->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->locationLevel1->ViewValue = $this->locationLevel1->CurrentValue;
			}
		} else {
			$this->locationLevel1->ViewValue = NULL;
		}
		$this->locationLevel1->ViewCustomAttributes = "";

		// locationLevel2
		if (strval($this->locationLevel2->CurrentValue) <> "") {
			$sFilterWrk = "`locationName`" . ew_SearchString("=", $this->locationLevel2->CurrentValue, EW_DATATYPE_STRING, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level2`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level2`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level2`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->locationLevel2, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->locationLevel2->ViewValue = $this->locationLevel2->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->locationLevel2->ViewValue = $this->locationLevel2->CurrentValue;
			}
		} else {
			$this->locationLevel2->ViewValue = NULL;
		}
		$this->locationLevel2->ViewCustomAttributes = "";

		// locationLevel3
		if (strval($this->locationLevel3->CurrentValue) <> "") {
			$sFilterWrk = "`locationName`" . ew_SearchString("=", $this->locationLevel3->CurrentValue, EW_DATATYPE_STRING, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level3`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level3`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level3`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->locationLevel3, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->locationLevel3->ViewValue = $this->locationLevel3->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->locationLevel3->ViewValue = $this->locationLevel3->CurrentValue;
			}
		} else {
			$this->locationLevel3->ViewValue = NULL;
		}
		$this->locationLevel3->ViewCustomAttributes = "";

		// locationLevel4
		$this->locationLevel4->ViewValue = $this->locationLevel4->CurrentValue;
		$this->locationLevel4->ViewCustomAttributes = "";

		// locationLevel5
		$this->locationLevel5->ViewValue = $this->locationLevel5->CurrentValue;
		$this->locationLevel5->ViewCustomAttributes = "";

		// locationLevel6
		$this->locationLevel6->ViewValue = $this->locationLevel6->CurrentValue;
		$this->locationLevel6->ViewCustomAttributes = "";

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
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`stateName`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_STRING, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
				$sWhereWrk = "";
				break;
		}
		$lookuptblfilter = "`stateLanguage` = '" . CurrentLanguageID() . "' AND `stateClass` = 'person'";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->status->ViewValue = $this->status->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->status->ViewValue = $this->status->CurrentValue;
			}
		} else {
			$this->status->ViewValue = NULL;
		}
		$this->status->ViewCustomAttributes = "";

		// isolatedLocation
		$this->isolatedLocation->ViewValue = $this->isolatedLocation->CurrentValue;
		$this->isolatedLocation->ViewCustomAttributes = "";

		// birthDate
		$this->birthDate->ViewValue = $this->birthDate->CurrentValue;
		$this->birthDate->ViewCustomAttributes = "";

		// ageRange
		if (strval($this->ageRange->CurrentValue) <> "") {
			$sFilterWrk = "`stateName`" . ew_SearchString("=", $this->ageRange->CurrentValue, EW_DATATYPE_STRING, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
				$sWhereWrk = "";
				break;
		}
		$lookuptblfilter = " `stateClass` = 'age' ";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->ageRange, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->ageRange->ViewValue = $this->ageRange->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->ageRange->ViewValue = $this->ageRange->CurrentValue;
			}
		} else {
			$this->ageRange->ViewValue = NULL;
		}
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
		if (!ew_Empty($this->picture->Upload->DbValue)) {
			$this->picture->ImageWidth = 70;
			$this->picture->ImageHeight = 70;
			$this->picture->ImageAlt = $this->picture->FldAlt();
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

		// registrationStation
		if (strval($this->registrationStation->CurrentValue) <> "") {
			$sFilterWrk = "`stationID`" . ew_SearchString("=", $this->registrationStation->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `stationID`, `stationID` AS `DispFld`, `stationName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_station`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `stationID`, `stationID` AS `DispFld`, `stationName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_station`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `stationID`, `stationID` AS `DispFld`, `stationName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_station`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->registrationStation, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->registrationStation->ViewValue = $this->registrationStation->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->registrationStation->ViewValue = $this->registrationStation->CurrentValue;
			}
		} else {
			$this->registrationStation->ViewValue = NULL;
		}
		$this->registrationStation->ViewCustomAttributes = "";

		// isolatedDateTime
		$this->isolatedDateTime->ViewValue = $this->isolatedDateTime->CurrentValue;
		$this->isolatedDateTime->ViewValue = ew_FormatDateTime($this->isolatedDateTime->ViewValue, 5);
		$this->isolatedDateTime->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

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

			// mobilePhone
			$this->mobilePhone->LinkCustomAttributes = "";
			$this->mobilePhone->HrefValue = "";
			$this->mobilePhone->TooltipValue = "";

			// nationalNumber
			$this->nationalNumber->LinkCustomAttributes = "";
			$this->nationalNumber->HrefValue = "";
			$this->nationalNumber->TooltipValue = "";

			// passportNumber
			$this->passportNumber->LinkCustomAttributes = "";
			$this->passportNumber->HrefValue = "";
			$this->passportNumber->TooltipValue = "";

			// fatherName
			$this->fatherName->LinkCustomAttributes = "";
			$this->fatherName->HrefValue = "";
			$this->fatherName->TooltipValue = "";

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";
			$this->gender->TooltipValue = "";

			// locationLevel1
			$this->locationLevel1->LinkCustomAttributes = "";
			$this->locationLevel1->HrefValue = "";
			$this->locationLevel1->TooltipValue = "";

			// locationLevel2
			$this->locationLevel2->LinkCustomAttributes = "";
			$this->locationLevel2->HrefValue = "";
			$this->locationLevel2->TooltipValue = "";

			// locationLevel3
			$this->locationLevel3->LinkCustomAttributes = "";
			$this->locationLevel3->HrefValue = "";
			$this->locationLevel3->TooltipValue = "";

			// locationLevel4
			$this->locationLevel4->LinkCustomAttributes = "";
			$this->locationLevel4->HrefValue = "";
			$this->locationLevel4->TooltipValue = "";

			// locationLevel5
			$this->locationLevel5->LinkCustomAttributes = "";
			$this->locationLevel5->HrefValue = "";
			$this->locationLevel5->TooltipValue = "";

			// locationLevel6
			$this->locationLevel6->LinkCustomAttributes = "";
			$this->locationLevel6->HrefValue = "";
			$this->locationLevel6->TooltipValue = "";

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
			if (!ew_Empty($this->picture->Upload->DbValue)) {
				$this->picture->HrefValue = ew_GetFileUploadUrl($this->picture, $this->picture->Upload->DbValue); // Add prefix/suffix
				$this->picture->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->picture->HrefValue = ew_ConvertFullUrl($this->picture->HrefValue);
			} else {
				$this->picture->HrefValue = "";
			}
			$this->picture->HrefValue2 = $this->picture->UploadPath . $this->picture->Upload->DbValue;
			$this->picture->TooltipValue = "";
			if ($this->picture->UseColorbox) {
				$this->picture->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->picture->LinkAttrs["data-rel"] = "sana_person_x_picture";

				//$this->picture->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
				//$this->picture->LinkAttrs["data-placement"] = "bottom";
				//$this->picture->LinkAttrs["data-container"] = "body";

				$this->picture->LinkAttrs["class"] = "ewLightbox img-thumbnail";
			}

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// mobilePhone
			$this->mobilePhone->EditAttrs["class"] = "form-control";
			$this->mobilePhone->EditCustomAttributes = "";
			$this->mobilePhone->EditValue = ew_HtmlEncode($this->mobilePhone->CurrentValue);
			$this->mobilePhone->PlaceHolder = ew_RemoveHtml($this->mobilePhone->FldCaption());

			// nationalNumber
			$this->nationalNumber->EditAttrs["class"] = "form-control";
			$this->nationalNumber->EditCustomAttributes = "";
			$this->nationalNumber->EditValue = ew_HtmlEncode($this->nationalNumber->CurrentValue);
			$this->nationalNumber->PlaceHolder = ew_RemoveHtml($this->nationalNumber->FldCaption());

			// passportNumber
			$this->passportNumber->EditAttrs["class"] = "form-control";
			$this->passportNumber->EditCustomAttributes = "";
			$this->passportNumber->EditValue = ew_HtmlEncode($this->passportNumber->CurrentValue);
			$this->passportNumber->PlaceHolder = ew_RemoveHtml($this->passportNumber->FldCaption());

			// fatherName
			$this->fatherName->EditAttrs["class"] = "form-control";
			$this->fatherName->EditCustomAttributes = "";
			$this->fatherName->EditValue = ew_HtmlEncode($this->fatherName->CurrentValue);
			$this->fatherName->PlaceHolder = ew_RemoveHtml($this->fatherName->FldCaption());

			// gender
			$this->gender->EditAttrs["class"] = "form-control";
			$this->gender->EditCustomAttributes = "";
			if (trim(strval($this->gender->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`stateName`" . ew_SearchString("=", $this->gender->CurrentValue, EW_DATATYPE_STRING, "");
			}
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
				case "fa":
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
			}
			$lookuptblfilter = "`stateLanguage` = '" . CurrentLanguageID() . "' AND `stateClass` = 'gender'";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->gender, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->gender->EditValue = $arwrk;

			// locationLevel1
			$this->locationLevel1->EditAttrs["class"] = "form-control";
			$this->locationLevel1->EditCustomAttributes = "";
			if (trim(strval($this->locationLevel1->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`locationName`" . ew_SearchString("=", $this->locationLevel1->CurrentValue, EW_DATATYPE_STRING, "");
			}
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level1`";
					$sWhereWrk = "";
					break;
				case "fa":
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level1`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level1`";
					$sWhereWrk = "";
					break;
			}
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->locationLevel1, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->locationLevel1->EditValue = $arwrk;

			// locationLevel2
			$this->locationLevel2->EditAttrs["class"] = "form-control";
			$this->locationLevel2->EditCustomAttributes = "";
			if (trim(strval($this->locationLevel2->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`locationName`" . ew_SearchString("=", $this->locationLevel2->CurrentValue, EW_DATATYPE_STRING, "");
			}
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `locationLevel1Name` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level2`";
					$sWhereWrk = "";
					break;
				case "fa":
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `locationLevel1Name` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level2`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `locationLevel1Name` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level2`";
					$sWhereWrk = "";
					break;
			}
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->locationLevel2, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->locationLevel2->EditValue = $arwrk;

			// locationLevel3
			$this->locationLevel3->EditAttrs["class"] = "form-control";
			$this->locationLevel3->EditCustomAttributes = "";
			if (trim(strval($this->locationLevel3->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`locationName`" . ew_SearchString("=", $this->locationLevel3->CurrentValue, EW_DATATYPE_STRING, "");
			}
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `locationLevel2Name` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level3`";
					$sWhereWrk = "";
					break;
				case "fa":
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `locationLevel2Name` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level3`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `locationLevel2Name` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level3`";
					$sWhereWrk = "";
					break;
			}
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->locationLevel3, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->locationLevel3->EditValue = $arwrk;

			// locationLevel4
			$this->locationLevel4->EditAttrs["class"] = "form-control";
			$this->locationLevel4->EditCustomAttributes = "";
			$this->locationLevel4->EditValue = ew_HtmlEncode($this->locationLevel4->CurrentValue);
			$this->locationLevel4->PlaceHolder = ew_RemoveHtml($this->locationLevel4->FldCaption());

			// locationLevel5
			$this->locationLevel5->EditAttrs["class"] = "form-control";
			$this->locationLevel5->EditCustomAttributes = "";
			$this->locationLevel5->EditValue = ew_HtmlEncode($this->locationLevel5->CurrentValue);
			$this->locationLevel5->PlaceHolder = ew_RemoveHtml($this->locationLevel5->FldCaption());

			// locationLevel6
			$this->locationLevel6->EditAttrs["class"] = "form-control";
			$this->locationLevel6->EditCustomAttributes = "";
			$this->locationLevel6->EditValue = ew_HtmlEncode($this->locationLevel6->CurrentValue);
			$this->locationLevel6->PlaceHolder = ew_RemoveHtml($this->locationLevel6->FldCaption());

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
			if (trim(strval($this->status->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`stateName`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_STRING, "");
			}
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
				case "fa":
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
			}
			$lookuptblfilter = "`stateLanguage` = '" . CurrentLanguageID() . "' AND `stateClass` = 'person'";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->status->EditValue = $arwrk;

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
			if (trim(strval($this->ageRange->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`stateName`" . ew_SearchString("=", $this->ageRange->CurrentValue, EW_DATATYPE_STRING, "");
			}
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
				case "fa":
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
			}
			$lookuptblfilter = " `stateClass` = 'age' ";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->ageRange, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->ageRange->EditValue = $arwrk;

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
			if (!ew_Empty($this->picture->Upload->DbValue)) {
				$this->picture->ImageWidth = 70;
				$this->picture->ImageHeight = 70;
				$this->picture->ImageAlt = $this->picture->FldAlt();
				$this->picture->EditValue = $this->picture->Upload->DbValue;
			} else {
				$this->picture->EditValue = "";
			}
			if (!ew_Empty($this->picture->CurrentValue))
				$this->picture->Upload->FileName = $this->picture->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->picture);

			// registrationUser
			// registrationDateTime
			// registrationStation
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

			// Add refer script
			// personName

			$this->personName->LinkCustomAttributes = "";
			$this->personName->HrefValue = "";

			// lastName
			$this->lastName->LinkCustomAttributes = "";
			$this->lastName->HrefValue = "";

			// nationalID
			$this->nationalID->LinkCustomAttributes = "";
			$this->nationalID->HrefValue = "";

			// mobilePhone
			$this->mobilePhone->LinkCustomAttributes = "";
			$this->mobilePhone->HrefValue = "";

			// nationalNumber
			$this->nationalNumber->LinkCustomAttributes = "";
			$this->nationalNumber->HrefValue = "";

			// passportNumber
			$this->passportNumber->LinkCustomAttributes = "";
			$this->passportNumber->HrefValue = "";

			// fatherName
			$this->fatherName->LinkCustomAttributes = "";
			$this->fatherName->HrefValue = "";

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";

			// locationLevel1
			$this->locationLevel1->LinkCustomAttributes = "";
			$this->locationLevel1->HrefValue = "";

			// locationLevel2
			$this->locationLevel2->LinkCustomAttributes = "";
			$this->locationLevel2->HrefValue = "";

			// locationLevel3
			$this->locationLevel3->LinkCustomAttributes = "";
			$this->locationLevel3->HrefValue = "";

			// locationLevel4
			$this->locationLevel4->LinkCustomAttributes = "";
			$this->locationLevel4->HrefValue = "";

			// locationLevel5
			$this->locationLevel5->LinkCustomAttributes = "";
			$this->locationLevel5->HrefValue = "";

			// locationLevel6
			$this->locationLevel6->LinkCustomAttributes = "";
			$this->locationLevel6->HrefValue = "";

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
			if (!ew_Empty($this->picture->Upload->DbValue)) {
				$this->picture->HrefValue = ew_GetFileUploadUrl($this->picture, $this->picture->Upload->DbValue); // Add prefix/suffix
				$this->picture->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->picture->HrefValue = ew_ConvertFullUrl($this->picture->HrefValue);
			} else {
				$this->picture->HrefValue = "";
			}
			$this->picture->HrefValue2 = $this->picture->UploadPath . $this->picture->Upload->DbValue;

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
		if (!$this->nationalID->FldIsDetailKey && !is_null($this->nationalID->FormValue) && $this->nationalID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nationalID->FldCaption(), $this->nationalID->ReqErrMsg));
		}
		if (!$this->mobilePhone->FldIsDetailKey && !is_null($this->mobilePhone->FormValue) && $this->mobilePhone->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->mobilePhone->FldCaption(), $this->mobilePhone->ReqErrMsg));
		}
		if (!$this->nationalNumber->FldIsDetailKey && !is_null($this->nationalNumber->FormValue) && $this->nationalNumber->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nationalNumber->FldCaption(), $this->nationalNumber->ReqErrMsg));
		}
		if (!$this->gender->FldIsDetailKey && !is_null($this->gender->FormValue) && $this->gender->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->gender->FldCaption(), $this->gender->ReqErrMsg));
		}
		if (!$this->locationLevel1->FldIsDetailKey && !is_null($this->locationLevel1->FormValue) && $this->locationLevel1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->locationLevel1->FldCaption(), $this->locationLevel1->ReqErrMsg));
		}
		if (!$this->locationLevel2->FldIsDetailKey && !is_null($this->locationLevel2->FormValue) && $this->locationLevel2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->locationLevel2->FldCaption(), $this->locationLevel2->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->birthDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->birthDate->FldErrMsg());
		}
		if (!ew_CheckEmail($this->_email->FormValue)) {
			ew_AddMessage($gsFormError, $this->_email->FldErrMsg());
		}
		if (!ew_CheckInteger($this->visitsCount->FormValue)) {
			ew_AddMessage($gsFormError, $this->visitsCount->FldErrMsg());
		}
		if (!ew_CheckDate($this->isolatedDateTime->FormValue)) {
			ew_AddMessage($gsFormError, $this->isolatedDateTime->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("sana_message", $DetailTblVar) && $GLOBALS["sana_message"]->DetailAdd) {
			if (!isset($GLOBALS["sana_message_grid"])) $GLOBALS["sana_message_grid"] = new csana_message_grid(); // get detail page object
			$GLOBALS["sana_message_grid"]->ValidateGridForm();
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

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// personName
		$this->personName->SetDbValueDef($rsnew, $this->personName->CurrentValue, "", FALSE);

		// lastName
		$this->lastName->SetDbValueDef($rsnew, $this->lastName->CurrentValue, "", FALSE);

		// nationalID
		$this->nationalID->SetDbValueDef($rsnew, $this->nationalID->CurrentValue, NULL, FALSE);

		// mobilePhone
		$this->mobilePhone->SetDbValueDef($rsnew, $this->mobilePhone->CurrentValue, NULL, FALSE);

		// nationalNumber
		$this->nationalNumber->SetDbValueDef($rsnew, $this->nationalNumber->CurrentValue, NULL, FALSE);

		// passportNumber
		$this->passportNumber->SetDbValueDef($rsnew, $this->passportNumber->CurrentValue, NULL, FALSE);

		// fatherName
		$this->fatherName->SetDbValueDef($rsnew, $this->fatherName->CurrentValue, NULL, FALSE);

		// gender
		$this->gender->SetDbValueDef($rsnew, $this->gender->CurrentValue, "", FALSE);

		// locationLevel1
		$this->locationLevel1->SetDbValueDef($rsnew, $this->locationLevel1->CurrentValue, "", FALSE);

		// locationLevel2
		$this->locationLevel2->SetDbValueDef($rsnew, $this->locationLevel2->CurrentValue, "", FALSE);

		// locationLevel3
		$this->locationLevel3->SetDbValueDef($rsnew, $this->locationLevel3->CurrentValue, NULL, FALSE);

		// locationLevel4
		$this->locationLevel4->SetDbValueDef($rsnew, $this->locationLevel4->CurrentValue, NULL, FALSE);

		// locationLevel5
		$this->locationLevel5->SetDbValueDef($rsnew, $this->locationLevel5->CurrentValue, NULL, FALSE);

		// locationLevel6
		$this->locationLevel6->SetDbValueDef($rsnew, $this->locationLevel6->CurrentValue, NULL, FALSE);

		// address
		$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, NULL, FALSE);

		// convoy
		$this->convoy->SetDbValueDef($rsnew, $this->convoy->CurrentValue, NULL, FALSE);

		// convoyManager
		$this->convoyManager->SetDbValueDef($rsnew, $this->convoyManager->CurrentValue, NULL, FALSE);

		// followersName
		$this->followersName->SetDbValueDef($rsnew, $this->followersName->CurrentValue, NULL, FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, FALSE);

		// isolatedLocation
		$this->isolatedLocation->SetDbValueDef($rsnew, $this->isolatedLocation->CurrentValue, NULL, FALSE);

		// birthDate
		$this->birthDate->SetDbValueDef($rsnew, $this->birthDate->CurrentValue, NULL, FALSE);

		// ageRange
		$this->ageRange->SetDbValueDef($rsnew, $this->ageRange->CurrentValue, NULL, FALSE);

		// dress1
		$this->dress1->SetDbValueDef($rsnew, $this->dress1->CurrentValue, NULL, FALSE);

		// dress2
		$this->dress2->SetDbValueDef($rsnew, $this->dress2->CurrentValue, NULL, FALSE);

		// signTags
		$this->signTags->SetDbValueDef($rsnew, $this->signTags->CurrentValue, NULL, FALSE);

		// phone
		$this->phone->SetDbValueDef($rsnew, $this->phone->CurrentValue, NULL, FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, FALSE);

		// temporaryResidence
		$this->temporaryResidence->SetDbValueDef($rsnew, $this->temporaryResidence->CurrentValue, NULL, FALSE);

		// visitsCount
		$this->visitsCount->SetDbValueDef($rsnew, $this->visitsCount->CurrentValue, NULL, FALSE);

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

		// registrationStation
		$this->registrationStation->SetDbValueDef($rsnew, CurrentParentUserID(), NULL);
		$rsnew['registrationStation'] = &$this->registrationStation->DbValue;

		// isolatedDateTime
		$this->isolatedDateTime->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->isolatedDateTime->CurrentValue, 5), NULL, FALSE);

		// description
		$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, FALSE);
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
				$this->personID->setDbValue($conn->Insert_ID());
				$rsnew['personID'] = $this->personID->DbValue;
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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("sana_message", $DetailTblVar) && $GLOBALS["sana_message"]->DetailAdd) {
				$GLOBALS["sana_message"]->personID->setSessionValue($this->personID->CurrentValue); // Set master key
				if (!isset($GLOBALS["sana_message_grid"])) $GLOBALS["sana_message_grid"] = new csana_message_grid(); // Get detail page object
				$AddRow = $GLOBALS["sana_message_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["sana_message"]->personID->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
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

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("sana_message", $DetailTblVar)) {
				if (!isset($GLOBALS["sana_message_grid"]))
					$GLOBALS["sana_message_grid"] = new csana_message_grid;
				if ($GLOBALS["sana_message_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["sana_message_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["sana_message_grid"]->CurrentMode = "add";
					$GLOBALS["sana_message_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["sana_message_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["sana_message_grid"]->setStartRecordNumber(1);
					$GLOBALS["sana_message_grid"]->personID->FldIsDetailKey = TRUE;
					$GLOBALS["sana_message_grid"]->personID->CurrentValue = $this->personID->CurrentValue;
					$GLOBALS["sana_message_grid"]->personID->setSessionValue($GLOBALS["sana_message_grid"]->personID->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sana_personlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Set up multi pages
	function SetupMultiPages() {
		$pages = new cSubPages();
		$pages->Add(0);
		$pages->Add(1);
		$pages->Add(2);
		$pages->Add(3);
		$pages->Add(4);
		$this->MultiPages = $pages;
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
if (!isset($sana_person_add)) $sana_person_add = new csana_person_add();

// Page init
$sana_person_add->Page_Init();

// Page main
$sana_person_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_person_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fsana_personadd = new ew_Form("fsana_personadd", "add");

// Validate form
fsana_personadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nationalID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_person->nationalID->FldCaption(), $sana_person->nationalID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_mobilePhone");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_person->mobilePhone->FldCaption(), $sana_person->mobilePhone->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nationalNumber");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_person->nationalNumber->FldCaption(), $sana_person->nationalNumber->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_gender");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_person->gender->FldCaption(), $sana_person->gender->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_locationLevel1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_person->locationLevel1->FldCaption(), $sana_person->locationLevel1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_locationLevel2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_person->locationLevel2->FldCaption(), $sana_person->locationLevel2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_birthDate");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_person->birthDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_person->_email->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_visitsCount");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_person->visitsCount->FldErrMsg()) ?>");
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
fsana_personadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_personadd.ValidateRequired = true;
<?php } else { ?>
fsana_personadd.ValidateRequired = false; 
<?php } ?>

// Multi-Page
fsana_personadd.MultiPage = new ew_MultiPage("fsana_personadd");

// Dynamic selection lists
fsana_personadd.Lists["x_gender"] = {"LinkField":"x_stateName","Ajax":true,"AutoFill":false,"DisplayFields":["x_stateName","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fsana_personadd.Lists["x_locationLevel1"] = {"LinkField":"x_locationName","Ajax":true,"AutoFill":false,"DisplayFields":["x_locationName","","",""],"ParentFields":[],"ChildFields":["x_locationLevel2"],"FilterFields":[],"Options":[],"Template":""};
fsana_personadd.Lists["x_locationLevel2"] = {"LinkField":"x_locationName","Ajax":true,"AutoFill":false,"DisplayFields":["x_locationName","","",""],"ParentFields":["x_locationLevel1"],"ChildFields":["x_locationLevel3"],"FilterFields":["x_locationLevel1Name"],"Options":[],"Template":""};
fsana_personadd.Lists["x_locationLevel3"] = {"LinkField":"x_locationName","Ajax":true,"AutoFill":false,"DisplayFields":["x_locationName","","",""],"ParentFields":["x_locationLevel2"],"ChildFields":[],"FilterFields":["x_locationLevel2Name"],"Options":[],"Template":""};
fsana_personadd.Lists["x_status"] = {"LinkField":"x_stateName","Ajax":true,"AutoFill":false,"DisplayFields":["x_stateName","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fsana_personadd.Lists["x_ageRange"] = {"LinkField":"x_stateName","Ajax":true,"AutoFill":false,"DisplayFields":["x_stateName","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fsana_personadd.Lists["x_registrationStation"] = {"LinkField":"x_stationID","Ajax":true,"AutoFill":false,"DisplayFields":["x_stationID","x_stationName","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $sana_person_add->ShowPageHeader(); ?>
<?php
$sana_person_add->ShowMessage();
?>
<form name="fsana_personadd" id="fsana_personadd" class="<?php echo $sana_person_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_person_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_person_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_person">
<input type="hidden" name="a_add" id="a_add" value="A">
<div class="ewMultiPage">
<div class="panel-group" id="sana_person_add">
	<div class="panel panel-default<?php echo $sana_person_add->MultiPages->PageStyle("1") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#sana_person_add" href="#tab_sana_person1"><?php echo $sana_person->PageCaption(1) ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $sana_person_add->MultiPages->PageStyle("1") ?>" id="tab_sana_person1">
			<div class="panel-body">
<div>
<?php if ($sana_person->personName->Visible) { // personName ?>
	<div id="r_personName" class="form-group">
		<label id="elh_sana_person_personName" for="x_personName" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->personName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->personName->CellAttributes() ?>>
<span id="el_sana_person_personName">
<input type="text" data-table="sana_person" data-field="x_personName" data-page="1" name="x_personName" id="x_personName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->personName->getPlaceHolder()) ?>" value="<?php echo $sana_person->personName->EditValue ?>"<?php echo $sana_person->personName->EditAttributes() ?>>
</span>
<?php echo $sana_person->personName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->lastName->Visible) { // lastName ?>
	<div id="r_lastName" class="form-group">
		<label id="elh_sana_person_lastName" for="x_lastName" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->lastName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->lastName->CellAttributes() ?>>
<span id="el_sana_person_lastName">
<input type="text" data-table="sana_person" data-field="x_lastName" data-page="1" name="x_lastName" id="x_lastName" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($sana_person->lastName->getPlaceHolder()) ?>" value="<?php echo $sana_person->lastName->EditValue ?>"<?php echo $sana_person->lastName->EditAttributes() ?>>
</span>
<?php echo $sana_person->lastName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->nationalID->Visible) { // nationalID ?>
	<div id="r_nationalID" class="form-group">
		<label id="elh_sana_person_nationalID" for="x_nationalID" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->nationalID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->nationalID->CellAttributes() ?>>
<span id="el_sana_person_nationalID">
<input type="text" data-table="sana_person" data-field="x_nationalID" data-page="1" name="x_nationalID" id="x_nationalID" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($sana_person->nationalID->getPlaceHolder()) ?>" value="<?php echo $sana_person->nationalID->EditValue ?>"<?php echo $sana_person->nationalID->EditAttributes() ?>>
</span>
<?php echo $sana_person->nationalID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->mobilePhone->Visible) { // mobilePhone ?>
	<div id="r_mobilePhone" class="form-group">
		<label id="elh_sana_person_mobilePhone" for="x_mobilePhone" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->mobilePhone->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->mobilePhone->CellAttributes() ?>>
<span id="el_sana_person_mobilePhone">
<input type="text" data-table="sana_person" data-field="x_mobilePhone" data-page="1" name="x_mobilePhone" id="x_mobilePhone" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($sana_person->mobilePhone->getPlaceHolder()) ?>" value="<?php echo $sana_person->mobilePhone->EditValue ?>"<?php echo $sana_person->mobilePhone->EditAttributes() ?>>
</span>
<?php echo $sana_person->mobilePhone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->nationalNumber->Visible) { // nationalNumber ?>
	<div id="r_nationalNumber" class="form-group">
		<label id="elh_sana_person_nationalNumber" for="x_nationalNumber" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->nationalNumber->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->nationalNumber->CellAttributes() ?>>
<span id="el_sana_person_nationalNumber">
<input type="text" data-table="sana_person" data-field="x_nationalNumber" data-page="1" name="x_nationalNumber" id="x_nationalNumber" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($sana_person->nationalNumber->getPlaceHolder()) ?>" value="<?php echo $sana_person->nationalNumber->EditValue ?>"<?php echo $sana_person->nationalNumber->EditAttributes() ?>>
</span>
<?php echo $sana_person->nationalNumber->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->passportNumber->Visible) { // passportNumber ?>
	<div id="r_passportNumber" class="form-group">
		<label id="elh_sana_person_passportNumber" for="x_passportNumber" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->passportNumber->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->passportNumber->CellAttributes() ?>>
<span id="el_sana_person_passportNumber">
<input type="text" data-table="sana_person" data-field="x_passportNumber" data-page="1" name="x_passportNumber" id="x_passportNumber" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($sana_person->passportNumber->getPlaceHolder()) ?>" value="<?php echo $sana_person->passportNumber->EditValue ?>"<?php echo $sana_person->passportNumber->EditAttributes() ?>>
</span>
<?php echo $sana_person->passportNumber->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->fatherName->Visible) { // fatherName ?>
	<div id="r_fatherName" class="form-group">
		<label id="elh_sana_person_fatherName" for="x_fatherName" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->fatherName->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->fatherName->CellAttributes() ?>>
<span id="el_sana_person_fatherName">
<input type="text" data-table="sana_person" data-field="x_fatherName" data-page="1" name="x_fatherName" id="x_fatherName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->fatherName->getPlaceHolder()) ?>" value="<?php echo $sana_person->fatherName->EditValue ?>"<?php echo $sana_person->fatherName->EditAttributes() ?>>
</span>
<?php echo $sana_person->fatherName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->gender->Visible) { // gender ?>
	<div id="r_gender" class="form-group">
		<label id="elh_sana_person_gender" for="x_gender" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->gender->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->gender->CellAttributes() ?>>
<span id="el_sana_person_gender">
<select data-table="sana_person" data-field="x_gender" data-page="1" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_person->gender->DisplayValueSeparator) ? json_encode($sana_person->gender->DisplayValueSeparator) : $sana_person->gender->DisplayValueSeparator) ?>" id="x_gender" name="x_gender"<?php echo $sana_person->gender->EditAttributes() ?>>
<?php
if (is_array($sana_person->gender->EditValue)) {
	$arwrk = $sana_person->gender->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sana_person->gender->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sana_person->gender->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($sana_person->gender->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($sana_person->gender->CurrentValue) ?>" selected><?php echo $sana_person->gender->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
		$sWhereWrk = "";
		break;
	case "fa":
		$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
		$sWhereWrk = "";
		break;
	default:
		$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
		$sWhereWrk = "";
		break;
}
$lookuptblfilter = "`stateLanguage` = '" . CurrentLanguageID() . "' AND `stateClass` = 'gender'";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$sana_person->gender->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sana_person->gender->LookupFilters += array("f0" => "`stateName` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$sana_person->Lookup_Selecting($sana_person->gender, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sana_person->gender->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_gender" id="s_x_gender" value="<?php echo $sana_person->gender->LookupFilterQuery() ?>">
</span>
<?php echo $sana_person->gender->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->ageRange->Visible) { // ageRange ?>
	<div id="r_ageRange" class="form-group">
		<label id="elh_sana_person_ageRange" for="x_ageRange" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->ageRange->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->ageRange->CellAttributes() ?>>
<span id="el_sana_person_ageRange">
<select data-table="sana_person" data-field="x_ageRange" data-page="1" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_person->ageRange->DisplayValueSeparator) ? json_encode($sana_person->ageRange->DisplayValueSeparator) : $sana_person->ageRange->DisplayValueSeparator) ?>" id="x_ageRange" name="x_ageRange"<?php echo $sana_person->ageRange->EditAttributes() ?>>
<?php
if (is_array($sana_person->ageRange->EditValue)) {
	$arwrk = $sana_person->ageRange->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sana_person->ageRange->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sana_person->ageRange->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($sana_person->ageRange->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($sana_person->ageRange->CurrentValue) ?>" selected><?php echo $sana_person->ageRange->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
		$sWhereWrk = "";
		break;
	case "fa":
		$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
		$sWhereWrk = "";
		break;
	default:
		$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
		$sWhereWrk = "";
		break;
}
$lookuptblfilter = " `stateClass` = 'age' ";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$sana_person->ageRange->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sana_person->ageRange->LookupFilters += array("f0" => "`stateName` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$sana_person->Lookup_Selecting($sana_person->ageRange, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sana_person->ageRange->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_ageRange" id="s_x_ageRange" value="<?php echo $sana_person->ageRange->LookupFilterQuery() ?>">
</span>
<?php echo $sana_person->ageRange->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default<?php echo $sana_person_add->MultiPages->PageStyle("2") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#sana_person_add" href="#tab_sana_person2"><?php echo $sana_person->PageCaption(2) ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $sana_person_add->MultiPages->PageStyle("2") ?>" id="tab_sana_person2">
			<div class="panel-body">
<div>
<?php if ($sana_person->locationLevel1->Visible) { // locationLevel1 ?>
	<div id="r_locationLevel1" class="form-group">
		<label id="elh_sana_person_locationLevel1" for="x_locationLevel1" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->locationLevel1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->locationLevel1->CellAttributes() ?>>
<span id="el_sana_person_locationLevel1">
<?php $sana_person->locationLevel1->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$sana_person->locationLevel1->EditAttrs["onchange"]; ?>
<select data-table="sana_person" data-field="x_locationLevel1" data-page="2" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_person->locationLevel1->DisplayValueSeparator) ? json_encode($sana_person->locationLevel1->DisplayValueSeparator) : $sana_person->locationLevel1->DisplayValueSeparator) ?>" id="x_locationLevel1" name="x_locationLevel1"<?php echo $sana_person->locationLevel1->EditAttributes() ?>>
<?php
if (is_array($sana_person->locationLevel1->EditValue)) {
	$arwrk = $sana_person->locationLevel1->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sana_person->locationLevel1->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sana_person->locationLevel1->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($sana_person->locationLevel1->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($sana_person->locationLevel1->CurrentValue) ?>" selected><?php echo $sana_person->locationLevel1->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level1`";
		$sWhereWrk = "";
		break;
	case "fa":
		$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level1`";
		$sWhereWrk = "";
		break;
	default:
		$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level1`";
		$sWhereWrk = "";
		break;
}
$sana_person->locationLevel1->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sana_person->locationLevel1->LookupFilters += array("f0" => "`locationName` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$sana_person->Lookup_Selecting($sana_person->locationLevel1, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sana_person->locationLevel1->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_locationLevel1" id="s_x_locationLevel1" value="<?php echo $sana_person->locationLevel1->LookupFilterQuery() ?>">
</span>
<?php echo $sana_person->locationLevel1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->locationLevel2->Visible) { // locationLevel2 ?>
	<div id="r_locationLevel2" class="form-group">
		<label id="elh_sana_person_locationLevel2" for="x_locationLevel2" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->locationLevel2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->locationLevel2->CellAttributes() ?>>
<span id="el_sana_person_locationLevel2">
<?php $sana_person->locationLevel2->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$sana_person->locationLevel2->EditAttrs["onchange"]; ?>
<select data-table="sana_person" data-field="x_locationLevel2" data-page="2" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_person->locationLevel2->DisplayValueSeparator) ? json_encode($sana_person->locationLevel2->DisplayValueSeparator) : $sana_person->locationLevel2->DisplayValueSeparator) ?>" id="x_locationLevel2" name="x_locationLevel2"<?php echo $sana_person->locationLevel2->EditAttributes() ?>>
<?php
if (is_array($sana_person->locationLevel2->EditValue)) {
	$arwrk = $sana_person->locationLevel2->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sana_person->locationLevel2->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sana_person->locationLevel2->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($sana_person->locationLevel2->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($sana_person->locationLevel2->CurrentValue) ?>" selected><?php echo $sana_person->locationLevel2->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level2`";
		$sWhereWrk = "{filter}";
		break;
	case "fa":
		$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level2`";
		$sWhereWrk = "{filter}";
		break;
	default:
		$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level2`";
		$sWhereWrk = "{filter}";
		break;
}
$sana_person->locationLevel2->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sana_person->locationLevel2->LookupFilters += array("f0" => "`locationName` = {filter_value}", "t0" => "200", "fn0" => "");
$sana_person->locationLevel2->LookupFilters += array("f1" => "`locationLevel1Name` IN ({filter_value})", "t1" => "200", "fn1" => "");
$sSqlWrk = "";
$sana_person->Lookup_Selecting($sana_person->locationLevel2, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sana_person->locationLevel2->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_locationLevel2" id="s_x_locationLevel2" value="<?php echo $sana_person->locationLevel2->LookupFilterQuery() ?>">
</span>
<?php echo $sana_person->locationLevel2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->locationLevel3->Visible) { // locationLevel3 ?>
	<div id="r_locationLevel3" class="form-group">
		<label id="elh_sana_person_locationLevel3" for="x_locationLevel3" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->locationLevel3->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->locationLevel3->CellAttributes() ?>>
<span id="el_sana_person_locationLevel3">
<select data-table="sana_person" data-field="x_locationLevel3" data-page="2" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_person->locationLevel3->DisplayValueSeparator) ? json_encode($sana_person->locationLevel3->DisplayValueSeparator) : $sana_person->locationLevel3->DisplayValueSeparator) ?>" id="x_locationLevel3" name="x_locationLevel3"<?php echo $sana_person->locationLevel3->EditAttributes() ?>>
<?php
if (is_array($sana_person->locationLevel3->EditValue)) {
	$arwrk = $sana_person->locationLevel3->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sana_person->locationLevel3->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sana_person->locationLevel3->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($sana_person->locationLevel3->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($sana_person->locationLevel3->CurrentValue) ?>" selected><?php echo $sana_person->locationLevel3->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level3`";
		$sWhereWrk = "{filter}";
		break;
	case "fa":
		$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level3`";
		$sWhereWrk = "{filter}";
		break;
	default:
		$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level3`";
		$sWhereWrk = "{filter}";
		break;
}
$sana_person->locationLevel3->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sana_person->locationLevel3->LookupFilters += array("f0" => "`locationName` = {filter_value}", "t0" => "200", "fn0" => "");
$sana_person->locationLevel3->LookupFilters += array("f1" => "`locationLevel2Name` IN ({filter_value})", "t1" => "200", "fn1" => "");
$sSqlWrk = "";
$sana_person->Lookup_Selecting($sana_person->locationLevel3, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sana_person->locationLevel3->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_locationLevel3" id="s_x_locationLevel3" value="<?php echo $sana_person->locationLevel3->LookupFilterQuery() ?>">
</span>
<?php echo $sana_person->locationLevel3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->locationLevel4->Visible) { // locationLevel4 ?>
	<div id="r_locationLevel4" class="form-group">
		<label id="elh_sana_person_locationLevel4" for="x_locationLevel4" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->locationLevel4->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->locationLevel4->CellAttributes() ?>>
<span id="el_sana_person_locationLevel4">
<input type="text" data-table="sana_person" data-field="x_locationLevel4" data-page="2" name="x_locationLevel4" id="x_locationLevel4" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->locationLevel4->getPlaceHolder()) ?>" value="<?php echo $sana_person->locationLevel4->EditValue ?>"<?php echo $sana_person->locationLevel4->EditAttributes() ?>>
</span>
<?php echo $sana_person->locationLevel4->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->locationLevel5->Visible) { // locationLevel5 ?>
	<div id="r_locationLevel5" class="form-group">
		<label id="elh_sana_person_locationLevel5" for="x_locationLevel5" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->locationLevel5->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->locationLevel5->CellAttributes() ?>>
<span id="el_sana_person_locationLevel5">
<input type="text" data-table="sana_person" data-field="x_locationLevel5" data-page="2" name="x_locationLevel5" id="x_locationLevel5" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->locationLevel5->getPlaceHolder()) ?>" value="<?php echo $sana_person->locationLevel5->EditValue ?>"<?php echo $sana_person->locationLevel5->EditAttributes() ?>>
</span>
<?php echo $sana_person->locationLevel5->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->locationLevel6->Visible) { // locationLevel6 ?>
	<div id="r_locationLevel6" class="form-group">
		<label id="elh_sana_person_locationLevel6" for="x_locationLevel6" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->locationLevel6->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->locationLevel6->CellAttributes() ?>>
<span id="el_sana_person_locationLevel6">
<input type="text" data-table="sana_person" data-field="x_locationLevel6" data-page="2" name="x_locationLevel6" id="x_locationLevel6" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->locationLevel6->getPlaceHolder()) ?>" value="<?php echo $sana_person->locationLevel6->EditValue ?>"<?php echo $sana_person->locationLevel6->EditAttributes() ?>>
</span>
<?php echo $sana_person->locationLevel6->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label id="elh_sana_person_address" for="x_address" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->address->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->address->CellAttributes() ?>>
<span id="el_sana_person_address">
<input type="text" data-table="sana_person" data-field="x_address" data-page="2" name="x_address" id="x_address" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->address->getPlaceHolder()) ?>" value="<?php echo $sana_person->address->EditValue ?>"<?php echo $sana_person->address->EditAttributes() ?>>
</span>
<?php echo $sana_person->address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->temporaryResidence->Visible) { // temporaryResidence ?>
	<div id="r_temporaryResidence" class="form-group">
		<label id="elh_sana_person_temporaryResidence" for="x_temporaryResidence" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->temporaryResidence->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->temporaryResidence->CellAttributes() ?>>
<span id="el_sana_person_temporaryResidence">
<textarea data-table="sana_person" data-field="x_temporaryResidence" data-page="2" name="x_temporaryResidence" id="x_temporaryResidence" cols="35" rows="5" placeholder="<?php echo ew_HtmlEncode($sana_person->temporaryResidence->getPlaceHolder()) ?>"<?php echo $sana_person->temporaryResidence->EditAttributes() ?>><?php echo $sana_person->temporaryResidence->EditValue ?></textarea>
</span>
<?php echo $sana_person->temporaryResidence->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default<?php echo $sana_person_add->MultiPages->PageStyle("3") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#sana_person_add" href="#tab_sana_person3"><?php echo $sana_person->PageCaption(3) ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $sana_person_add->MultiPages->PageStyle("3") ?>" id="tab_sana_person3">
			<div class="panel-body">
<div>
<?php if ($sana_person->convoy->Visible) { // convoy ?>
	<div id="r_convoy" class="form-group">
		<label id="elh_sana_person_convoy" for="x_convoy" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->convoy->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->convoy->CellAttributes() ?>>
<span id="el_sana_person_convoy">
<input type="text" data-table="sana_person" data-field="x_convoy" data-page="3" name="x_convoy" id="x_convoy" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->convoy->getPlaceHolder()) ?>" value="<?php echo $sana_person->convoy->EditValue ?>"<?php echo $sana_person->convoy->EditAttributes() ?>>
</span>
<?php echo $sana_person->convoy->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->convoyManager->Visible) { // convoyManager ?>
	<div id="r_convoyManager" class="form-group">
		<label id="elh_sana_person_convoyManager" for="x_convoyManager" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->convoyManager->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->convoyManager->CellAttributes() ?>>
<span id="el_sana_person_convoyManager">
<input type="text" data-table="sana_person" data-field="x_convoyManager" data-page="3" name="x_convoyManager" id="x_convoyManager" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->convoyManager->getPlaceHolder()) ?>" value="<?php echo $sana_person->convoyManager->EditValue ?>"<?php echo $sana_person->convoyManager->EditAttributes() ?>>
</span>
<?php echo $sana_person->convoyManager->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->followersName->Visible) { // followersName ?>
	<div id="r_followersName" class="form-group">
		<label id="elh_sana_person_followersName" for="x_followersName" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->followersName->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->followersName->CellAttributes() ?>>
<span id="el_sana_person_followersName">
<textarea data-table="sana_person" data-field="x_followersName" data-page="3" name="x_followersName" id="x_followersName" cols="35" rows="3" placeholder="<?php echo ew_HtmlEncode($sana_person->followersName->getPlaceHolder()) ?>"<?php echo $sana_person->followersName->EditAttributes() ?>><?php echo $sana_person->followersName->EditValue ?></textarea>
</span>
<?php echo $sana_person->followersName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->isolatedLocation->Visible) { // isolatedLocation ?>
	<div id="r_isolatedLocation" class="form-group">
		<label id="elh_sana_person_isolatedLocation" for="x_isolatedLocation" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->isolatedLocation->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->isolatedLocation->CellAttributes() ?>>
<span id="el_sana_person_isolatedLocation">
<input type="text" data-table="sana_person" data-field="x_isolatedLocation" data-page="3" name="x_isolatedLocation" id="x_isolatedLocation" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->isolatedLocation->getPlaceHolder()) ?>" value="<?php echo $sana_person->isolatedLocation->EditValue ?>"<?php echo $sana_person->isolatedLocation->EditAttributes() ?>>
</span>
<?php echo $sana_person->isolatedLocation->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->birthDate->Visible) { // birthDate ?>
	<div id="r_birthDate" class="form-group">
		<label id="elh_sana_person_birthDate" for="x_birthDate" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->birthDate->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->birthDate->CellAttributes() ?>>
<span id="el_sana_person_birthDate">
<input type="text" data-table="sana_person" data-field="x_birthDate" data-page="3" name="x_birthDate" id="x_birthDate" size="30" placeholder="<?php echo ew_HtmlEncode($sana_person->birthDate->getPlaceHolder()) ?>" value="<?php echo $sana_person->birthDate->EditValue ?>"<?php echo $sana_person->birthDate->EditAttributes() ?>>
</span>
<?php echo $sana_person->birthDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->dress1->Visible) { // dress1 ?>
	<div id="r_dress1" class="form-group">
		<label id="elh_sana_person_dress1" for="x_dress1" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->dress1->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->dress1->CellAttributes() ?>>
<span id="el_sana_person_dress1">
<input type="text" data-table="sana_person" data-field="x_dress1" data-page="3" name="x_dress1" id="x_dress1" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->dress1->getPlaceHolder()) ?>" value="<?php echo $sana_person->dress1->EditValue ?>"<?php echo $sana_person->dress1->EditAttributes() ?>>
</span>
<?php echo $sana_person->dress1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->dress2->Visible) { // dress2 ?>
	<div id="r_dress2" class="form-group">
		<label id="elh_sana_person_dress2" for="x_dress2" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->dress2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->dress2->CellAttributes() ?>>
<span id="el_sana_person_dress2">
<input type="text" data-table="sana_person" data-field="x_dress2" data-page="3" name="x_dress2" id="x_dress2" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->dress2->getPlaceHolder()) ?>" value="<?php echo $sana_person->dress2->EditValue ?>"<?php echo $sana_person->dress2->EditAttributes() ?>>
</span>
<?php echo $sana_person->dress2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->signTags->Visible) { // signTags ?>
	<div id="r_signTags" class="form-group">
		<label id="elh_sana_person_signTags" for="x_signTags" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->signTags->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->signTags->CellAttributes() ?>>
<span id="el_sana_person_signTags">
<input type="text" data-table="sana_person" data-field="x_signTags" data-page="3" name="x_signTags" id="x_signTags" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->signTags->getPlaceHolder()) ?>" value="<?php echo $sana_person->signTags->EditValue ?>"<?php echo $sana_person->signTags->EditAttributes() ?>>
</span>
<?php echo $sana_person->signTags->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->phone->Visible) { // phone ?>
	<div id="r_phone" class="form-group">
		<label id="elh_sana_person_phone" for="x_phone" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->phone->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->phone->CellAttributes() ?>>
<span id="el_sana_person_phone">
<textarea data-table="sana_person" data-field="x_phone" data-page="3" name="x_phone" id="x_phone" cols="35" rows="3" placeholder="<?php echo ew_HtmlEncode($sana_person->phone->getPlaceHolder()) ?>"<?php echo $sana_person->phone->EditAttributes() ?>><?php echo $sana_person->phone->EditValue ?></textarea>
</span>
<?php echo $sana_person->phone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_sana_person__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->_email->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->_email->CellAttributes() ?>>
<span id="el_sana_person__email">
<input type="text" data-table="sana_person" data-field="x__email" data-page="3" name="x__email" id="x__email" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($sana_person->_email->getPlaceHolder()) ?>" value="<?php echo $sana_person->_email->EditValue ?>"<?php echo $sana_person->_email->EditAttributes() ?>>
</span>
<?php echo $sana_person->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->picture->Visible) { // picture ?>
	<div id="r_picture" class="form-group">
		<label id="elh_sana_person_picture" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->picture->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->picture->CellAttributes() ?>>
<span id="el_sana_person_picture">
<div id="fd_x_picture">
<span title="<?php echo $sana_person->picture->FldTitle() ? $sana_person->picture->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($sana_person->picture->ReadOnly || $sana_person->picture->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="sana_person" data-field="x_picture" data-page="3" name="x_picture" id="x_picture"<?php echo $sana_person->picture->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_picture" id= "fn_x_picture" value="<?php echo $sana_person->picture->Upload->FileName ?>">
<input type="hidden" name="fa_x_picture" id= "fa_x_picture" value="0">
<input type="hidden" name="fs_x_picture" id= "fs_x_picture" value="255">
<input type="hidden" name="fx_x_picture" id= "fx_x_picture" value="<?php echo $sana_person->picture->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_picture" id= "fm_x_picture" value="<?php echo $sana_person->picture->UploadMaxFileSize ?>">
</div>
<table id="ft_x_picture" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $sana_person->picture->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->isolatedDateTime->Visible) { // isolatedDateTime ?>
	<div id="r_isolatedDateTime" class="form-group">
		<label id="elh_sana_person_isolatedDateTime" for="x_isolatedDateTime" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->isolatedDateTime->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->isolatedDateTime->CellAttributes() ?>>
<span id="el_sana_person_isolatedDateTime">
<input type="text" data-table="sana_person" data-field="x_isolatedDateTime" data-page="3" data-format="5" name="x_isolatedDateTime" id="x_isolatedDateTime" placeholder="<?php echo ew_HtmlEncode($sana_person->isolatedDateTime->getPlaceHolder()) ?>" value="<?php echo $sana_person->isolatedDateTime->EditValue ?>"<?php echo $sana_person->isolatedDateTime->EditAttributes() ?>>
</span>
<?php echo $sana_person->isolatedDateTime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label id="elh_sana_person_description" for="x_description" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->description->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->description->CellAttributes() ?>>
<span id="el_sana_person_description">
<textarea data-table="sana_person" data-field="x_description" data-page="3" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($sana_person->description->getPlaceHolder()) ?>"<?php echo $sana_person->description->EditAttributes() ?>><?php echo $sana_person->description->EditValue ?></textarea>
</span>
<?php echo $sana_person->description->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default<?php echo $sana_person_add->MultiPages->PageStyle("4") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#sana_person_add" href="#tab_sana_person4"><?php echo $sana_person->PageCaption(4) ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $sana_person_add->MultiPages->PageStyle("4") ?>" id="tab_sana_person4">
			<div class="panel-body">
<div>
<?php if ($sana_person->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_sana_person_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->status->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->status->CellAttributes() ?>>
<span id="el_sana_person_status">
<select data-table="sana_person" data-field="x_status" data-page="4" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_person->status->DisplayValueSeparator) ? json_encode($sana_person->status->DisplayValueSeparator) : $sana_person->status->DisplayValueSeparator) ?>" id="x_status" name="x_status"<?php echo $sana_person->status->EditAttributes() ?>>
<?php
if (is_array($sana_person->status->EditValue)) {
	$arwrk = $sana_person->status->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sana_person->status->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sana_person->status->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($sana_person->status->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($sana_person->status->CurrentValue) ?>" selected><?php echo $sana_person->status->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
		$sWhereWrk = "";
		break;
	case "fa":
		$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
		$sWhereWrk = "";
		break;
	default:
		$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_state`";
		$sWhereWrk = "";
		break;
}
$lookuptblfilter = "`stateLanguage` = '" . CurrentLanguageID() . "' AND `stateClass` = 'person'";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
$sana_person->status->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sana_person->status->LookupFilters += array("f0" => "`stateName` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$sana_person->Lookup_Selecting($sana_person->status, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sana_person->status->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_status" id="s_x_status" value="<?php echo $sana_person->status->LookupFilterQuery() ?>">
</span>
<?php echo $sana_person->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_person->visitsCount->Visible) { // visitsCount ?>
	<div id="r_visitsCount" class="form-group">
		<label id="elh_sana_person_visitsCount" for="x_visitsCount" class="col-sm-2 control-label ewLabel"><?php echo $sana_person->visitsCount->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_person->visitsCount->CellAttributes() ?>>
<span id="el_sana_person_visitsCount">
<input type="text" data-table="sana_person" data-field="x_visitsCount" data-page="4" name="x_visitsCount" id="x_visitsCount" size="30" placeholder="<?php echo ew_HtmlEncode($sana_person->visitsCount->getPlaceHolder()) ?>" value="<?php echo $sana_person->visitsCount->EditValue ?>"<?php echo $sana_person->visitsCount->EditAttributes() ?>>
</span>
<?php echo $sana_person->visitsCount->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php
	if (in_array("sana_message", explode(",", $sana_person->getCurrentDetailTable())) && $sana_message->DetailAdd) {
?>
<?php if ($sana_person->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("sana_message", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "sana_messagegrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sana_person_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fsana_personadd.Init();
</script>
<?php
$sana_person_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_person_add->Page_Terminate();
?>
