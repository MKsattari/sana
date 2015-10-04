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

$sana_person_delete = NULL; // Initialize page object first

class csana_person_delete extends csana_person {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_person';

	// Page object name
	var $PageObjName = 'sana_person_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("sana_personlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in sana_person class, sana_personinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['personID'];
				$this->LoadDbValues($row);
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sana_personlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($sana_person_delete)) $sana_person_delete = new csana_person_delete();

// Page init
$sana_person_delete->Page_Init();

// Page main
$sana_person_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_person_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fsana_persondelete = new ew_Form("fsana_persondelete", "delete");

// Form_CustomValidate event
fsana_persondelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_persondelete.ValidateRequired = true;
<?php } else { ?>
fsana_persondelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($sana_person_delete->Recordset = $sana_person_delete->LoadRecordset())
	$sana_person_deleteTotalRecs = $sana_person_delete->Recordset->RecordCount(); // Get record count
if ($sana_person_deleteTotalRecs <= 0) { // No record found, exit
	if ($sana_person_delete->Recordset)
		$sana_person_delete->Recordset->Close();
	$sana_person_delete->Page_Terminate("sana_personlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $sana_person_delete->ShowPageHeader(); ?>
<?php
$sana_person_delete->ShowMessage();
?>
<form name="fsana_persondelete" id="fsana_persondelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_person_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_person_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_person">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($sana_person_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $sana_person->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($sana_person->personID->Visible) { // personID ?>
		<th><span id="elh_sana_person_personID" class="sana_person_personID"><?php echo $sana_person->personID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->personName->Visible) { // personName ?>
		<th><span id="elh_sana_person_personName" class="sana_person_personName"><?php echo $sana_person->personName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->lastName->Visible) { // lastName ?>
		<th><span id="elh_sana_person_lastName" class="sana_person_lastName"><?php echo $sana_person->lastName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->nationalID->Visible) { // nationalID ?>
		<th><span id="elh_sana_person_nationalID" class="sana_person_nationalID"><?php echo $sana_person->nationalID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->nationalNumber->Visible) { // nationalNumber ?>
		<th><span id="elh_sana_person_nationalNumber" class="sana_person_nationalNumber"><?php echo $sana_person->nationalNumber->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->fatherName->Visible) { // fatherName ?>
		<th><span id="elh_sana_person_fatherName" class="sana_person_fatherName"><?php echo $sana_person->fatherName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->gender->Visible) { // gender ?>
		<th><span id="elh_sana_person_gender" class="sana_person_gender"><?php echo $sana_person->gender->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->country->Visible) { // country ?>
		<th><span id="elh_sana_person_country" class="sana_person_country"><?php echo $sana_person->country->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->province->Visible) { // province ?>
		<th><span id="elh_sana_person_province" class="sana_person_province"><?php echo $sana_person->province->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->county->Visible) { // county ?>
		<th><span id="elh_sana_person_county" class="sana_person_county"><?php echo $sana_person->county->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->district->Visible) { // district ?>
		<th><span id="elh_sana_person_district" class="sana_person_district"><?php echo $sana_person->district->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->city_ruralDistrict->Visible) { // city_ruralDistrict ?>
		<th><span id="elh_sana_person_city_ruralDistrict" class="sana_person_city_ruralDistrict"><?php echo $sana_person->city_ruralDistrict->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->region_village->Visible) { // region_village ?>
		<th><span id="elh_sana_person_region_village" class="sana_person_region_village"><?php echo $sana_person->region_village->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->address->Visible) { // address ?>
		<th><span id="elh_sana_person_address" class="sana_person_address"><?php echo $sana_person->address->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->convoy->Visible) { // convoy ?>
		<th><span id="elh_sana_person_convoy" class="sana_person_convoy"><?php echo $sana_person->convoy->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->convoyManager->Visible) { // convoyManager ?>
		<th><span id="elh_sana_person_convoyManager" class="sana_person_convoyManager"><?php echo $sana_person->convoyManager->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->followersName->Visible) { // followersName ?>
		<th><span id="elh_sana_person_followersName" class="sana_person_followersName"><?php echo $sana_person->followersName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->status->Visible) { // status ?>
		<th><span id="elh_sana_person_status" class="sana_person_status"><?php echo $sana_person->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->isolatedLocation->Visible) { // isolatedLocation ?>
		<th><span id="elh_sana_person_isolatedLocation" class="sana_person_isolatedLocation"><?php echo $sana_person->isolatedLocation->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->birthDate->Visible) { // birthDate ?>
		<th><span id="elh_sana_person_birthDate" class="sana_person_birthDate"><?php echo $sana_person->birthDate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->ageRange->Visible) { // ageRange ?>
		<th><span id="elh_sana_person_ageRange" class="sana_person_ageRange"><?php echo $sana_person->ageRange->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->dress1->Visible) { // dress1 ?>
		<th><span id="elh_sana_person_dress1" class="sana_person_dress1"><?php echo $sana_person->dress1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->dress2->Visible) { // dress2 ?>
		<th><span id="elh_sana_person_dress2" class="sana_person_dress2"><?php echo $sana_person->dress2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->signTags->Visible) { // signTags ?>
		<th><span id="elh_sana_person_signTags" class="sana_person_signTags"><?php echo $sana_person->signTags->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->phone->Visible) { // phone ?>
		<th><span id="elh_sana_person_phone" class="sana_person_phone"><?php echo $sana_person->phone->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->mobilePhone->Visible) { // mobilePhone ?>
		<th><span id="elh_sana_person_mobilePhone" class="sana_person_mobilePhone"><?php echo $sana_person->mobilePhone->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->_email->Visible) { // email ?>
		<th><span id="elh_sana_person__email" class="sana_person__email"><?php echo $sana_person->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->temporaryResidence->Visible) { // temporaryResidence ?>
		<th><span id="elh_sana_person_temporaryResidence" class="sana_person_temporaryResidence"><?php echo $sana_person->temporaryResidence->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->visitsCount->Visible) { // visitsCount ?>
		<th><span id="elh_sana_person_visitsCount" class="sana_person_visitsCount"><?php echo $sana_person->visitsCount->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->picture->Visible) { // picture ?>
		<th><span id="elh_sana_person_picture" class="sana_person_picture"><?php echo $sana_person->picture->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->registrationUser->Visible) { // registrationUser ?>
		<th><span id="elh_sana_person_registrationUser" class="sana_person_registrationUser"><?php echo $sana_person->registrationUser->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->registrationDateTime->Visible) { // registrationDateTime ?>
		<th><span id="elh_sana_person_registrationDateTime" class="sana_person_registrationDateTime"><?php echo $sana_person->registrationDateTime->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->registrationStation->Visible) { // registrationStation ?>
		<th><span id="elh_sana_person_registrationStation" class="sana_person_registrationStation"><?php echo $sana_person->registrationStation->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->isolatedDateTime->Visible) { // isolatedDateTime ?>
		<th><span id="elh_sana_person_isolatedDateTime" class="sana_person_isolatedDateTime"><?php echo $sana_person->isolatedDateTime->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$sana_person_delete->RecCnt = 0;
$i = 0;
while (!$sana_person_delete->Recordset->EOF) {
	$sana_person_delete->RecCnt++;
	$sana_person_delete->RowCnt++;

	// Set row properties
	$sana_person->ResetAttrs();
	$sana_person->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$sana_person_delete->LoadRowValues($sana_person_delete->Recordset);

	// Render row
	$sana_person_delete->RenderRow();
?>
	<tr<?php echo $sana_person->RowAttributes() ?>>
<?php if ($sana_person->personID->Visible) { // personID ?>
		<td<?php echo $sana_person->personID->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_personID" class="sana_person_personID">
<span<?php echo $sana_person->personID->ViewAttributes() ?>>
<?php echo $sana_person->personID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->personName->Visible) { // personName ?>
		<td<?php echo $sana_person->personName->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_personName" class="sana_person_personName">
<span<?php echo $sana_person->personName->ViewAttributes() ?>>
<?php echo $sana_person->personName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->lastName->Visible) { // lastName ?>
		<td<?php echo $sana_person->lastName->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_lastName" class="sana_person_lastName">
<span<?php echo $sana_person->lastName->ViewAttributes() ?>>
<?php echo $sana_person->lastName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->nationalID->Visible) { // nationalID ?>
		<td<?php echo $sana_person->nationalID->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_nationalID" class="sana_person_nationalID">
<span<?php echo $sana_person->nationalID->ViewAttributes() ?>>
<?php echo $sana_person->nationalID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->nationalNumber->Visible) { // nationalNumber ?>
		<td<?php echo $sana_person->nationalNumber->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_nationalNumber" class="sana_person_nationalNumber">
<span<?php echo $sana_person->nationalNumber->ViewAttributes() ?>>
<?php echo $sana_person->nationalNumber->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->fatherName->Visible) { // fatherName ?>
		<td<?php echo $sana_person->fatherName->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_fatherName" class="sana_person_fatherName">
<span<?php echo $sana_person->fatherName->ViewAttributes() ?>>
<?php echo $sana_person->fatherName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->gender->Visible) { // gender ?>
		<td<?php echo $sana_person->gender->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_gender" class="sana_person_gender">
<span<?php echo $sana_person->gender->ViewAttributes() ?>>
<?php echo $sana_person->gender->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->country->Visible) { // country ?>
		<td<?php echo $sana_person->country->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_country" class="sana_person_country">
<span<?php echo $sana_person->country->ViewAttributes() ?>>
<?php echo $sana_person->country->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->province->Visible) { // province ?>
		<td<?php echo $sana_person->province->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_province" class="sana_person_province">
<span<?php echo $sana_person->province->ViewAttributes() ?>>
<?php echo $sana_person->province->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->county->Visible) { // county ?>
		<td<?php echo $sana_person->county->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_county" class="sana_person_county">
<span<?php echo $sana_person->county->ViewAttributes() ?>>
<?php echo $sana_person->county->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->district->Visible) { // district ?>
		<td<?php echo $sana_person->district->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_district" class="sana_person_district">
<span<?php echo $sana_person->district->ViewAttributes() ?>>
<?php echo $sana_person->district->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->city_ruralDistrict->Visible) { // city_ruralDistrict ?>
		<td<?php echo $sana_person->city_ruralDistrict->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_city_ruralDistrict" class="sana_person_city_ruralDistrict">
<span<?php echo $sana_person->city_ruralDistrict->ViewAttributes() ?>>
<?php echo $sana_person->city_ruralDistrict->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->region_village->Visible) { // region_village ?>
		<td<?php echo $sana_person->region_village->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_region_village" class="sana_person_region_village">
<span<?php echo $sana_person->region_village->ViewAttributes() ?>>
<?php echo $sana_person->region_village->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->address->Visible) { // address ?>
		<td<?php echo $sana_person->address->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_address" class="sana_person_address">
<span<?php echo $sana_person->address->ViewAttributes() ?>>
<?php echo $sana_person->address->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->convoy->Visible) { // convoy ?>
		<td<?php echo $sana_person->convoy->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_convoy" class="sana_person_convoy">
<span<?php echo $sana_person->convoy->ViewAttributes() ?>>
<?php echo $sana_person->convoy->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->convoyManager->Visible) { // convoyManager ?>
		<td<?php echo $sana_person->convoyManager->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_convoyManager" class="sana_person_convoyManager">
<span<?php echo $sana_person->convoyManager->ViewAttributes() ?>>
<?php echo $sana_person->convoyManager->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->followersName->Visible) { // followersName ?>
		<td<?php echo $sana_person->followersName->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_followersName" class="sana_person_followersName">
<span<?php echo $sana_person->followersName->ViewAttributes() ?>>
<?php echo $sana_person->followersName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->status->Visible) { // status ?>
		<td<?php echo $sana_person->status->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_status" class="sana_person_status">
<span<?php echo $sana_person->status->ViewAttributes() ?>>
<?php echo $sana_person->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->isolatedLocation->Visible) { // isolatedLocation ?>
		<td<?php echo $sana_person->isolatedLocation->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_isolatedLocation" class="sana_person_isolatedLocation">
<span<?php echo $sana_person->isolatedLocation->ViewAttributes() ?>>
<?php echo $sana_person->isolatedLocation->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->birthDate->Visible) { // birthDate ?>
		<td<?php echo $sana_person->birthDate->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_birthDate" class="sana_person_birthDate">
<span<?php echo $sana_person->birthDate->ViewAttributes() ?>>
<?php echo $sana_person->birthDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->ageRange->Visible) { // ageRange ?>
		<td<?php echo $sana_person->ageRange->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_ageRange" class="sana_person_ageRange">
<span<?php echo $sana_person->ageRange->ViewAttributes() ?>>
<?php echo $sana_person->ageRange->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->dress1->Visible) { // dress1 ?>
		<td<?php echo $sana_person->dress1->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_dress1" class="sana_person_dress1">
<span<?php echo $sana_person->dress1->ViewAttributes() ?>>
<?php echo $sana_person->dress1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->dress2->Visible) { // dress2 ?>
		<td<?php echo $sana_person->dress2->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_dress2" class="sana_person_dress2">
<span<?php echo $sana_person->dress2->ViewAttributes() ?>>
<?php echo $sana_person->dress2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->signTags->Visible) { // signTags ?>
		<td<?php echo $sana_person->signTags->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_signTags" class="sana_person_signTags">
<span<?php echo $sana_person->signTags->ViewAttributes() ?>>
<?php echo $sana_person->signTags->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->phone->Visible) { // phone ?>
		<td<?php echo $sana_person->phone->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_phone" class="sana_person_phone">
<span<?php echo $sana_person->phone->ViewAttributes() ?>>
<?php echo $sana_person->phone->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->mobilePhone->Visible) { // mobilePhone ?>
		<td<?php echo $sana_person->mobilePhone->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_mobilePhone" class="sana_person_mobilePhone">
<span<?php echo $sana_person->mobilePhone->ViewAttributes() ?>>
<?php echo $sana_person->mobilePhone->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->_email->Visible) { // email ?>
		<td<?php echo $sana_person->_email->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person__email" class="sana_person__email">
<span<?php echo $sana_person->_email->ViewAttributes() ?>>
<?php echo $sana_person->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->temporaryResidence->Visible) { // temporaryResidence ?>
		<td<?php echo $sana_person->temporaryResidence->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_temporaryResidence" class="sana_person_temporaryResidence">
<span<?php echo $sana_person->temporaryResidence->ViewAttributes() ?>>
<?php echo $sana_person->temporaryResidence->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->visitsCount->Visible) { // visitsCount ?>
		<td<?php echo $sana_person->visitsCount->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_visitsCount" class="sana_person_visitsCount">
<span<?php echo $sana_person->visitsCount->ViewAttributes() ?>>
<?php echo $sana_person->visitsCount->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->picture->Visible) { // picture ?>
		<td<?php echo $sana_person->picture->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_picture" class="sana_person_picture">
<span<?php echo $sana_person->picture->ViewAttributes() ?>>
<?php echo $sana_person->picture->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->registrationUser->Visible) { // registrationUser ?>
		<td<?php echo $sana_person->registrationUser->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_registrationUser" class="sana_person_registrationUser">
<span<?php echo $sana_person->registrationUser->ViewAttributes() ?>>
<?php echo $sana_person->registrationUser->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->registrationDateTime->Visible) { // registrationDateTime ?>
		<td<?php echo $sana_person->registrationDateTime->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_registrationDateTime" class="sana_person_registrationDateTime">
<span<?php echo $sana_person->registrationDateTime->ViewAttributes() ?>>
<?php echo $sana_person->registrationDateTime->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->registrationStation->Visible) { // registrationStation ?>
		<td<?php echo $sana_person->registrationStation->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_registrationStation" class="sana_person_registrationStation">
<span<?php echo $sana_person->registrationStation->ViewAttributes() ?>>
<?php echo $sana_person->registrationStation->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->isolatedDateTime->Visible) { // isolatedDateTime ?>
		<td<?php echo $sana_person->isolatedDateTime->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_isolatedDateTime" class="sana_person_isolatedDateTime">
<span<?php echo $sana_person->isolatedDateTime->ViewAttributes() ?>>
<?php echo $sana_person->isolatedDateTime->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$sana_person_delete->Recordset->MoveNext();
}
$sana_person_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sana_person_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fsana_persondelete.Init();
</script>
<?php
$sana_person_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_person_delete->Page_Terminate();
?>
