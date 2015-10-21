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

$sana_user_delete = NULL; // Initialize page object first

class csana_user_delete extends csana_user {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_user';

	// Page object name
	var $PageObjName = 'sana_user_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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
			$this->Page_Terminate("sana_userlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in sana_user class, sana_userinfo.php

		$this->CurrentFilter = $sFilter;

		// Check if valid user id
		$conn = &$this->Connection();
		$sql = $this->GetSQL($this->CurrentFilter, "");
		if ($this->Recordset = ew_LoadRecordset($sql, $conn)) {
			$res = TRUE;
			while (!$this->Recordset->EOF) {
				$this->LoadRowValues($this->Recordset);
				if (!$this->ShowOptionLink('delete')) {
					$sUserIdMsg = $Language->Phrase("NoDeletePermission");
					$this->setFailureMessage($sUserIdMsg);
					$res = FALSE;
					break;
				}
				$this->Recordset->MoveNext();
			}
			$this->Recordset->Close();
			if (!$res) $this->Page_Terminate("sana_userlist.php"); // Return to list
		}

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

			// mobilePhone
			$this->mobilePhone->LinkCustomAttributes = "";
			$this->mobilePhone->HrefValue = "";
			$this->mobilePhone->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// picture
			$this->picture->LinkCustomAttributes = "";
			$this->picture->HrefValue = "";
			$this->picture->HrefValue2 = $this->picture->UploadPath . $this->picture->Upload->DbValue;
			$this->picture->TooltipValue = "";
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
				$sThisKey .= $row['userID'];
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
if (!isset($sana_user_delete)) $sana_user_delete = new csana_user_delete();

// Page init
$sana_user_delete->Page_Init();

// Page main
$sana_user_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_user_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fsana_userdelete = new ew_Form("fsana_userdelete", "delete");

// Form_CustomValidate event
fsana_userdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_userdelete.ValidateRequired = true;
<?php } else { ?>
fsana_userdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($sana_user_delete->Recordset = $sana_user_delete->LoadRecordset())
	$sana_user_deleteTotalRecs = $sana_user_delete->Recordset->RecordCount(); // Get record count
if ($sana_user_deleteTotalRecs <= 0) { // No record found, exit
	if ($sana_user_delete->Recordset)
		$sana_user_delete->Recordset->Close();
	$sana_user_delete->Page_Terminate("sana_userlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $sana_user_delete->ShowPageHeader(); ?>
<?php
$sana_user_delete->ShowMessage();
?>
<form name="fsana_userdelete" id="fsana_userdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_user_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_user_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_user">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($sana_user_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $sana_user->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($sana_user->_userID->Visible) { // userID ?>
		<th><span id="elh_sana_user__userID" class="sana_user__userID"><?php echo $sana_user->_userID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_user->username->Visible) { // username ?>
		<th><span id="elh_sana_user_username" class="sana_user_username"><?php echo $sana_user->username->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_user->personName->Visible) { // personName ?>
		<th><span id="elh_sana_user_personName" class="sana_user_personName"><?php echo $sana_user->personName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_user->lastName->Visible) { // lastName ?>
		<th><span id="elh_sana_user_lastName" class="sana_user_lastName"><?php echo $sana_user->lastName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_user->nationalID->Visible) { // nationalID ?>
		<th><span id="elh_sana_user_nationalID" class="sana_user_nationalID"><?php echo $sana_user->nationalID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_user->mobilePhone->Visible) { // mobilePhone ?>
		<th><span id="elh_sana_user_mobilePhone" class="sana_user_mobilePhone"><?php echo $sana_user->mobilePhone->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_user->_email->Visible) { // email ?>
		<th><span id="elh_sana_user__email" class="sana_user__email"><?php echo $sana_user->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_user->picture->Visible) { // picture ?>
		<th><span id="elh_sana_user_picture" class="sana_user_picture"><?php echo $sana_user->picture->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$sana_user_delete->RecCnt = 0;
$i = 0;
while (!$sana_user_delete->Recordset->EOF) {
	$sana_user_delete->RecCnt++;
	$sana_user_delete->RowCnt++;

	// Set row properties
	$sana_user->ResetAttrs();
	$sana_user->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$sana_user_delete->LoadRowValues($sana_user_delete->Recordset);

	// Render row
	$sana_user_delete->RenderRow();
?>
	<tr<?php echo $sana_user->RowAttributes() ?>>
<?php if ($sana_user->_userID->Visible) { // userID ?>
		<td<?php echo $sana_user->_userID->CellAttributes() ?>>
<span id="el<?php echo $sana_user_delete->RowCnt ?>_sana_user__userID" class="sana_user__userID">
<span<?php echo $sana_user->_userID->ViewAttributes() ?>>
<?php echo $sana_user->_userID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_user->username->Visible) { // username ?>
		<td<?php echo $sana_user->username->CellAttributes() ?>>
<span id="el<?php echo $sana_user_delete->RowCnt ?>_sana_user_username" class="sana_user_username">
<span<?php echo $sana_user->username->ViewAttributes() ?>>
<?php echo $sana_user->username->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_user->personName->Visible) { // personName ?>
		<td<?php echo $sana_user->personName->CellAttributes() ?>>
<span id="el<?php echo $sana_user_delete->RowCnt ?>_sana_user_personName" class="sana_user_personName">
<span<?php echo $sana_user->personName->ViewAttributes() ?>>
<?php echo $sana_user->personName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_user->lastName->Visible) { // lastName ?>
		<td<?php echo $sana_user->lastName->CellAttributes() ?>>
<span id="el<?php echo $sana_user_delete->RowCnt ?>_sana_user_lastName" class="sana_user_lastName">
<span<?php echo $sana_user->lastName->ViewAttributes() ?>>
<?php echo $sana_user->lastName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_user->nationalID->Visible) { // nationalID ?>
		<td<?php echo $sana_user->nationalID->CellAttributes() ?>>
<span id="el<?php echo $sana_user_delete->RowCnt ?>_sana_user_nationalID" class="sana_user_nationalID">
<span<?php echo $sana_user->nationalID->ViewAttributes() ?>>
<?php echo $sana_user->nationalID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_user->mobilePhone->Visible) { // mobilePhone ?>
		<td<?php echo $sana_user->mobilePhone->CellAttributes() ?>>
<span id="el<?php echo $sana_user_delete->RowCnt ?>_sana_user_mobilePhone" class="sana_user_mobilePhone">
<span<?php echo $sana_user->mobilePhone->ViewAttributes() ?>>
<?php echo $sana_user->mobilePhone->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_user->_email->Visible) { // email ?>
		<td<?php echo $sana_user->_email->CellAttributes() ?>>
<span id="el<?php echo $sana_user_delete->RowCnt ?>_sana_user__email" class="sana_user__email">
<span<?php echo $sana_user->_email->ViewAttributes() ?>>
<?php echo $sana_user->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_user->picture->Visible) { // picture ?>
		<td<?php echo $sana_user->picture->CellAttributes() ?>>
<span id="el<?php echo $sana_user_delete->RowCnt ?>_sana_user_picture" class="sana_user_picture">
<span<?php echo $sana_user->picture->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($sana_user->picture, $sana_user->picture->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$sana_user_delete->Recordset->MoveNext();
}
$sana_user_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sana_user_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fsana_userdelete.Init();
</script>
<?php
$sana_user_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_user_delete->Page_Terminate();
?>
