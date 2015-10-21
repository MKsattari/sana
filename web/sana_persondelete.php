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
		if (!$Security->CanDelete()) {
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// personID

		$this->personID->CellCssStyle = "white-space: nowrap;";

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

			// mobilePhone
			$this->mobilePhone->LinkCustomAttributes = "";
			$this->mobilePhone->HrefValue = "";
			$this->mobilePhone->TooltipValue = "";

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

			// locationLevel1
			$this->locationLevel1->LinkCustomAttributes = "";
			$this->locationLevel1->HrefValue = "";
			$this->locationLevel1->TooltipValue = "";

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

			// registrationStation
			$this->registrationStation->LinkCustomAttributes = "";
			$this->registrationStation->HrefValue = "";
			$this->registrationStation->TooltipValue = "";
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
				@unlink(ew_UploadPathEx(TRUE, $this->picture->OldUploadPath) . $row['picture']);
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
fsana_persondelete.Lists["x_gender"] = {"LinkField":"x_stateName","Ajax":true,"AutoFill":false,"DisplayFields":["x_stateName","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fsana_persondelete.Lists["x_locationLevel1"] = {"LinkField":"x_locationName","Ajax":true,"AutoFill":false,"DisplayFields":["x_locationName","","",""],"ParentFields":[],"ChildFields":["x_locationLevel2"],"FilterFields":[],"Options":[],"Template":""};
fsana_persondelete.Lists["x_registrationStation"] = {"LinkField":"x_stationID","Ajax":true,"AutoFill":false,"DisplayFields":["x_stationID","x_stationName","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php if ($sana_person->mobilePhone->Visible) { // mobilePhone ?>
		<th><span id="elh_sana_person_mobilePhone" class="sana_person_mobilePhone"><?php echo $sana_person->mobilePhone->FldCaption() ?></span></th>
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
<?php if ($sana_person->locationLevel1->Visible) { // locationLevel1 ?>
		<th><span id="elh_sana_person_locationLevel1" class="sana_person_locationLevel1"><?php echo $sana_person->locationLevel1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->picture->Visible) { // picture ?>
		<th><span id="elh_sana_person_picture" class="sana_person_picture"><?php echo $sana_person->picture->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_person->registrationStation->Visible) { // registrationStation ?>
		<th><span id="elh_sana_person_registrationStation" class="sana_person_registrationStation"><?php echo $sana_person->registrationStation->FldCaption() ?></span></th>
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
<?php if ($sana_person->mobilePhone->Visible) { // mobilePhone ?>
		<td<?php echo $sana_person->mobilePhone->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_mobilePhone" class="sana_person_mobilePhone">
<span<?php echo $sana_person->mobilePhone->ViewAttributes() ?>>
<?php echo $sana_person->mobilePhone->ListViewValue() ?></span>
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
<?php if ($sana_person->locationLevel1->Visible) { // locationLevel1 ?>
		<td<?php echo $sana_person->locationLevel1->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_locationLevel1" class="sana_person_locationLevel1">
<span<?php echo $sana_person->locationLevel1->ViewAttributes() ?>>
<?php echo $sana_person->locationLevel1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_person->picture->Visible) { // picture ?>
		<td<?php echo $sana_person->picture->CellAttributes() ?>>
<span id="el<?php echo $sana_person_delete->RowCnt ?>_sana_person_picture" class="sana_person_picture">
<span>
<?php echo ew_GetFileViewTag($sana_person->picture, $sana_person->picture->ListViewValue()) ?>
</span>
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
