<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "sana_stationinfo.php" ?>
<?php include_once "sana_userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$sana_station_add = NULL; // Initialize page object first

class csana_station_add extends csana_station {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_station';

	// Page object name
	var $PageObjName = 'sana_station_add';

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

		// Table object (sana_station)
		if (!isset($GLOBALS["sana_station"]) || get_class($GLOBALS["sana_station"]) == "csana_station") {
			$GLOBALS["sana_station"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_station"];
		}

		// Table object (sana_user)
		if (!isset($GLOBALS['sana_user'])) $GLOBALS['sana_user'] = new csana_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sana_station', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("sana_stationlist.php"));
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
		global $EW_EXPORT, $sana_station;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($sana_station);
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
			if (@$_GET["stationID"] != "") {
				$this->stationID->setQueryStringValue($_GET["stationID"]);
				$this->setKey("stationID", $this->stationID->CurrentValue); // Set up key
			} else {
				$this->setKey("stationID", ""); // Clear key
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
					$this->Page_Terminate("sana_stationlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "sana_stationlist.php")
						$sReturnUrl = $this->AddMasterUrl($this->GetListUrl()); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "sana_stationview.php")
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
		$this->stationName->CurrentValue = NULL;
		$this->stationName->OldValue = $this->stationName->CurrentValue;
		$this->projectID->CurrentValue = NULL;
		$this->projectID->OldValue = $this->projectID->CurrentValue;
		$this->description->CurrentValue = NULL;
		$this->description->OldValue = $this->description->CurrentValue;
		$this->address->CurrentValue = NULL;
		$this->address->OldValue = $this->address->CurrentValue;
		$this->GPS1->CurrentValue = NULL;
		$this->GPS1->OldValue = $this->GPS1->CurrentValue;
		$this->GPS2->CurrentValue = NULL;
		$this->GPS2->OldValue = $this->GPS2->CurrentValue;
		$this->GPS3->CurrentValue = NULL;
		$this->GPS3->OldValue = $this->GPS3->CurrentValue;
		$this->stationType->CurrentValue = NULL;
		$this->stationType->OldValue = $this->stationType->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->stationName->FldIsDetailKey) {
			$this->stationName->setFormValue($objForm->GetValue("x_stationName"));
		}
		if (!$this->projectID->FldIsDetailKey) {
			$this->projectID->setFormValue($objForm->GetValue("x_projectID"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->address->FldIsDetailKey) {
			$this->address->setFormValue($objForm->GetValue("x_address"));
		}
		if (!$this->GPS1->FldIsDetailKey) {
			$this->GPS1->setFormValue($objForm->GetValue("x_GPS1"));
		}
		if (!$this->GPS2->FldIsDetailKey) {
			$this->GPS2->setFormValue($objForm->GetValue("x_GPS2"));
		}
		if (!$this->GPS3->FldIsDetailKey) {
			$this->GPS3->setFormValue($objForm->GetValue("x_GPS3"));
		}
		if (!$this->stationType->FldIsDetailKey) {
			$this->stationType->setFormValue($objForm->GetValue("x_stationType"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->stationName->CurrentValue = $this->stationName->FormValue;
		$this->projectID->CurrentValue = $this->projectID->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->GPS1->CurrentValue = $this->GPS1->FormValue;
		$this->GPS2->CurrentValue = $this->GPS2->FormValue;
		$this->GPS3->CurrentValue = $this->GPS3->FormValue;
		$this->stationType->CurrentValue = $this->stationType->FormValue;
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
		$this->stationID->setDbValue($rs->fields('stationID'));
		$this->stationName->setDbValue($rs->fields('stationName'));
		$this->projectID->setDbValue($rs->fields('projectID'));
		$this->description->setDbValue($rs->fields('description'));
		$this->address->setDbValue($rs->fields('address'));
		$this->GPS1->setDbValue($rs->fields('GPS1'));
		$this->GPS2->setDbValue($rs->fields('GPS2'));
		$this->GPS3->setDbValue($rs->fields('GPS3'));
		$this->stationType->setDbValue($rs->fields('stationType'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->stationID->DbValue = $row['stationID'];
		$this->stationName->DbValue = $row['stationName'];
		$this->projectID->DbValue = $row['projectID'];
		$this->description->DbValue = $row['description'];
		$this->address->DbValue = $row['address'];
		$this->GPS1->DbValue = $row['GPS1'];
		$this->GPS2->DbValue = $row['GPS2'];
		$this->GPS3->DbValue = $row['GPS3'];
		$this->stationType->DbValue = $row['stationType'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("stationID")) <> "")
			$this->stationID->CurrentValue = $this->getKey("stationID"); // stationID
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
		// stationID
		// stationName
		// projectID
		// description
		// address
		// GPS1
		// GPS2
		// GPS3
		// stationType

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// stationID
		$this->stationID->ViewValue = $this->stationID->CurrentValue;
		$this->stationID->ViewCustomAttributes = "";

		// stationName
		$this->stationName->ViewValue = $this->stationName->CurrentValue;
		$this->stationName->ViewCustomAttributes = "";

		// projectID
		if (strval($this->projectID->CurrentValue) <> "") {
			$sFilterWrk = "`projectID`" . ew_SearchString("=", $this->projectID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `projectID`, `projectName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_project`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `projectID`, `projectName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_project`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `projectID`, `projectName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_project`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->projectID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->projectID->ViewValue = $this->projectID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->projectID->ViewValue = $this->projectID->CurrentValue;
			}
		} else {
			$this->projectID->ViewValue = NULL;
		}
		$this->projectID->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// address
		$this->address->ViewValue = $this->address->CurrentValue;
		$this->address->ViewCustomAttributes = "";

		// GPS1
		$this->GPS1->ViewValue = $this->GPS1->CurrentValue;
		$this->GPS1->ViewCustomAttributes = "";

		// GPS2
		$this->GPS2->ViewValue = $this->GPS2->CurrentValue;
		$this->GPS2->ViewCustomAttributes = "";

		// GPS3
		$this->GPS3->ViewValue = $this->GPS3->CurrentValue;
		$this->GPS3->ViewCustomAttributes = "";

		// stationType
		$this->stationType->ViewValue = $this->stationType->CurrentValue;
		$this->stationType->ViewCustomAttributes = "";

			// stationName
			$this->stationName->LinkCustomAttributes = "";
			$this->stationName->HrefValue = "";
			$this->stationName->TooltipValue = "";

			// projectID
			$this->projectID->LinkCustomAttributes = "";
			$this->projectID->HrefValue = "";
			$this->projectID->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";
			$this->address->TooltipValue = "";

			// GPS1
			$this->GPS1->LinkCustomAttributes = "";
			$this->GPS1->HrefValue = "";
			$this->GPS1->TooltipValue = "";

			// GPS2
			$this->GPS2->LinkCustomAttributes = "";
			$this->GPS2->HrefValue = "";
			$this->GPS2->TooltipValue = "";

			// GPS3
			$this->GPS3->LinkCustomAttributes = "";
			$this->GPS3->HrefValue = "";
			$this->GPS3->TooltipValue = "";

			// stationType
			$this->stationType->LinkCustomAttributes = "";
			$this->stationType->HrefValue = "";
			$this->stationType->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// stationName
			$this->stationName->EditAttrs["class"] = "form-control";
			$this->stationName->EditCustomAttributes = "";
			$this->stationName->EditValue = ew_HtmlEncode($this->stationName->CurrentValue);
			$this->stationName->PlaceHolder = ew_RemoveHtml($this->stationName->FldCaption());

			// projectID
			$this->projectID->EditAttrs["class"] = "form-control";
			$this->projectID->EditCustomAttributes = "";
			if (trim(strval($this->projectID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`projectID`" . ew_SearchString("=", $this->projectID->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `projectID`, `projectName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_project`";
					$sWhereWrk = "";
					break;
				case "fa":
					$sSqlWrk = "SELECT `projectID`, `projectName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_project`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `projectID`, `projectName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_project`";
					$sWhereWrk = "";
					break;
			}
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->projectID, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->projectID->EditValue = $arwrk;

			// description
			$this->description->EditAttrs["class"] = "form-control";
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);
			$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

			// address
			$this->address->EditAttrs["class"] = "form-control";
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = ew_HtmlEncode($this->address->CurrentValue);
			$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

			// GPS1
			$this->GPS1->EditAttrs["class"] = "form-control";
			$this->GPS1->EditCustomAttributes = "";
			$this->GPS1->EditValue = ew_HtmlEncode($this->GPS1->CurrentValue);
			$this->GPS1->PlaceHolder = ew_RemoveHtml($this->GPS1->FldCaption());

			// GPS2
			$this->GPS2->EditAttrs["class"] = "form-control";
			$this->GPS2->EditCustomAttributes = "";
			$this->GPS2->EditValue = ew_HtmlEncode($this->GPS2->CurrentValue);
			$this->GPS2->PlaceHolder = ew_RemoveHtml($this->GPS2->FldCaption());

			// GPS3
			$this->GPS3->EditAttrs["class"] = "form-control";
			$this->GPS3->EditCustomAttributes = "";
			$this->GPS3->EditValue = ew_HtmlEncode($this->GPS3->CurrentValue);
			$this->GPS3->PlaceHolder = ew_RemoveHtml($this->GPS3->FldCaption());

			// stationType
			$this->stationType->EditAttrs["class"] = "form-control";
			$this->stationType->EditCustomAttributes = "";
			$this->stationType->EditValue = ew_HtmlEncode($this->stationType->CurrentValue);
			$this->stationType->PlaceHolder = ew_RemoveHtml($this->stationType->FldCaption());

			// Add refer script
			// stationName

			$this->stationName->LinkCustomAttributes = "";
			$this->stationName->HrefValue = "";

			// projectID
			$this->projectID->LinkCustomAttributes = "";
			$this->projectID->HrefValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";

			// GPS1
			$this->GPS1->LinkCustomAttributes = "";
			$this->GPS1->HrefValue = "";

			// GPS2
			$this->GPS2->LinkCustomAttributes = "";
			$this->GPS2->HrefValue = "";

			// GPS3
			$this->GPS3->LinkCustomAttributes = "";
			$this->GPS3->HrefValue = "";

			// stationType
			$this->stationType->LinkCustomAttributes = "";
			$this->stationType->HrefValue = "";
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
		if (!$this->stationName->FldIsDetailKey && !is_null($this->stationName->FormValue) && $this->stationName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->stationName->FldCaption(), $this->stationName->ReqErrMsg));
		}
		if (!$this->GPS1->FldIsDetailKey && !is_null($this->GPS1->FormValue) && $this->GPS1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->GPS1->FldCaption(), $this->GPS1->ReqErrMsg));
		}
		if (!$this->GPS2->FldIsDetailKey && !is_null($this->GPS2->FormValue) && $this->GPS2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->GPS2->FldCaption(), $this->GPS2->ReqErrMsg));
		}
		if (!$this->GPS3->FldIsDetailKey && !is_null($this->GPS3->FormValue) && $this->GPS3->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->GPS3->FldCaption(), $this->GPS3->ReqErrMsg));
		}
		if (!$this->stationType->FldIsDetailKey && !is_null($this->stationType->FormValue) && $this->stationType->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->stationType->FldCaption(), $this->stationType->ReqErrMsg));
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

		// stationName
		$this->stationName->SetDbValueDef($rsnew, $this->stationName->CurrentValue, "", FALSE);

		// projectID
		$this->projectID->SetDbValueDef($rsnew, $this->projectID->CurrentValue, NULL, FALSE);

		// description
		$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, FALSE);

		// address
		$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, NULL, FALSE);

		// GPS1
		$this->GPS1->SetDbValueDef($rsnew, $this->GPS1->CurrentValue, "", FALSE);

		// GPS2
		$this->GPS2->SetDbValueDef($rsnew, $this->GPS2->CurrentValue, "", FALSE);

		// GPS3
		$this->GPS3->SetDbValueDef($rsnew, $this->GPS3->CurrentValue, "", FALSE);

		// stationType
		$this->stationType->SetDbValueDef($rsnew, $this->stationType->CurrentValue, "", FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->stationID->setDbValue($conn->Insert_ID());
				$rsnew['stationID'] = $this->stationID->DbValue;
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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sana_stationlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($sana_station_add)) $sana_station_add = new csana_station_add();

// Page init
$sana_station_add->Page_Init();

// Page main
$sana_station_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_station_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fsana_stationadd = new ew_Form("fsana_stationadd", "add");

// Validate form
fsana_stationadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_stationName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_station->stationName->FldCaption(), $sana_station->stationName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_GPS1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_station->GPS1->FldCaption(), $sana_station->GPS1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_GPS2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_station->GPS2->FldCaption(), $sana_station->GPS2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_GPS3");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_station->GPS3->FldCaption(), $sana_station->GPS3->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_stationType");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_station->stationType->FldCaption(), $sana_station->stationType->ReqErrMsg)) ?>");

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
fsana_stationadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_stationadd.ValidateRequired = true;
<?php } else { ?>
fsana_stationadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsana_stationadd.Lists["x_projectID"] = {"LinkField":"x_projectID","Ajax":true,"AutoFill":false,"DisplayFields":["x_projectName","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $sana_station_add->ShowPageHeader(); ?>
<?php
$sana_station_add->ShowMessage();
?>
<form name="fsana_stationadd" id="fsana_stationadd" class="<?php echo $sana_station_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_station_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_station_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_station">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($sana_station->stationName->Visible) { // stationName ?>
	<div id="r_stationName" class="form-group">
		<label id="elh_sana_station_stationName" for="x_stationName" class="col-sm-2 control-label ewLabel"><?php echo $sana_station->stationName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_station->stationName->CellAttributes() ?>>
<span id="el_sana_station_stationName">
<input type="text" data-table="sana_station" data-field="x_stationName" name="x_stationName" id="x_stationName" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_station->stationName->getPlaceHolder()) ?>" value="<?php echo $sana_station->stationName->EditValue ?>"<?php echo $sana_station->stationName->EditAttributes() ?>>
</span>
<?php echo $sana_station->stationName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_station->projectID->Visible) { // projectID ?>
	<div id="r_projectID" class="form-group">
		<label id="elh_sana_station_projectID" for="x_projectID" class="col-sm-2 control-label ewLabel"><?php echo $sana_station->projectID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_station->projectID->CellAttributes() ?>>
<span id="el_sana_station_projectID">
<select data-table="sana_station" data-field="x_projectID" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_station->projectID->DisplayValueSeparator) ? json_encode($sana_station->projectID->DisplayValueSeparator) : $sana_station->projectID->DisplayValueSeparator) ?>" id="x_projectID" name="x_projectID"<?php echo $sana_station->projectID->EditAttributes() ?>>
<?php
if (is_array($sana_station->projectID->EditValue)) {
	$arwrk = $sana_station->projectID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sana_station->projectID->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sana_station->projectID->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($sana_station->projectID->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($sana_station->projectID->CurrentValue) ?>" selected><?php echo $sana_station->projectID->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `projectID`, `projectName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_project`";
		$sWhereWrk = "";
		break;
	case "fa":
		$sSqlWrk = "SELECT `projectID`, `projectName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_project`";
		$sWhereWrk = "";
		break;
	default:
		$sSqlWrk = "SELECT `projectID`, `projectName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_project`";
		$sWhereWrk = "";
		break;
}
$sana_station->projectID->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sana_station->projectID->LookupFilters += array("f0" => "`projectID` = {filter_value}", "t0" => "20", "fn0" => "");
$sSqlWrk = "";
$sana_station->Lookup_Selecting($sana_station->projectID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sana_station->projectID->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_projectID" id="s_x_projectID" value="<?php echo $sana_station->projectID->LookupFilterQuery() ?>">
</span>
<?php echo $sana_station->projectID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_station->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label id="elh_sana_station_description" for="x_description" class="col-sm-2 control-label ewLabel"><?php echo $sana_station->description->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_station->description->CellAttributes() ?>>
<span id="el_sana_station_description">
<textarea data-table="sana_station" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($sana_station->description->getPlaceHolder()) ?>"<?php echo $sana_station->description->EditAttributes() ?>><?php echo $sana_station->description->EditValue ?></textarea>
</span>
<?php echo $sana_station->description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_station->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label id="elh_sana_station_address" for="x_address" class="col-sm-2 control-label ewLabel"><?php echo $sana_station->address->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sana_station->address->CellAttributes() ?>>
<span id="el_sana_station_address">
<input type="text" data-table="sana_station" data-field="x_address" name="x_address" id="x_address" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_station->address->getPlaceHolder()) ?>" value="<?php echo $sana_station->address->EditValue ?>"<?php echo $sana_station->address->EditAttributes() ?>>
</span>
<?php echo $sana_station->address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_station->GPS1->Visible) { // GPS1 ?>
	<div id="r_GPS1" class="form-group">
		<label id="elh_sana_station_GPS1" for="x_GPS1" class="col-sm-2 control-label ewLabel"><?php echo $sana_station->GPS1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_station->GPS1->CellAttributes() ?>>
<span id="el_sana_station_GPS1">
<input type="text" data-table="sana_station" data-field="x_GPS1" name="x_GPS1" id="x_GPS1" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($sana_station->GPS1->getPlaceHolder()) ?>" value="<?php echo $sana_station->GPS1->EditValue ?>"<?php echo $sana_station->GPS1->EditAttributes() ?>>
</span>
<?php echo $sana_station->GPS1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_station->GPS2->Visible) { // GPS2 ?>
	<div id="r_GPS2" class="form-group">
		<label id="elh_sana_station_GPS2" for="x_GPS2" class="col-sm-2 control-label ewLabel"><?php echo $sana_station->GPS2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_station->GPS2->CellAttributes() ?>>
<span id="el_sana_station_GPS2">
<input type="text" data-table="sana_station" data-field="x_GPS2" name="x_GPS2" id="x_GPS2" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($sana_station->GPS2->getPlaceHolder()) ?>" value="<?php echo $sana_station->GPS2->EditValue ?>"<?php echo $sana_station->GPS2->EditAttributes() ?>>
</span>
<?php echo $sana_station->GPS2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_station->GPS3->Visible) { // GPS3 ?>
	<div id="r_GPS3" class="form-group">
		<label id="elh_sana_station_GPS3" for="x_GPS3" class="col-sm-2 control-label ewLabel"><?php echo $sana_station->GPS3->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_station->GPS3->CellAttributes() ?>>
<span id="el_sana_station_GPS3">
<input type="text" data-table="sana_station" data-field="x_GPS3" name="x_GPS3" id="x_GPS3" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($sana_station->GPS3->getPlaceHolder()) ?>" value="<?php echo $sana_station->GPS3->EditValue ?>"<?php echo $sana_station->GPS3->EditAttributes() ?>>
</span>
<?php echo $sana_station->GPS3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_station->stationType->Visible) { // stationType ?>
	<div id="r_stationType" class="form-group">
		<label id="elh_sana_station_stationType" for="x_stationType" class="col-sm-2 control-label ewLabel"><?php echo $sana_station->stationType->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_station->stationType->CellAttributes() ?>>
<span id="el_sana_station_stationType">
<input type="text" data-table="sana_station" data-field="x_stationType" name="x_stationType" id="x_stationType" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_station->stationType->getPlaceHolder()) ?>" value="<?php echo $sana_station->stationType->EditValue ?>"<?php echo $sana_station->stationType->EditAttributes() ?>>
</span>
<?php echo $sana_station->stationType->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sana_station_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fsana_stationadd.Init();
</script>
<?php
$sana_station_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_station_add->Page_Terminate();
?>
