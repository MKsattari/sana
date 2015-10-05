<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "sana_location_level4info.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$sana_location_level4_add = NULL; // Initialize page object first

class csana_location_level4_add extends csana_location_level4 {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_location_level4';

	// Page object name
	var $PageObjName = 'sana_location_level4_add';

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
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (sana_location_level4)
		if (!isset($GLOBALS["sana_location_level4"]) || get_class($GLOBALS["sana_location_level4"]) == "csana_location_level4") {
			$GLOBALS["sana_location_level4"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_location_level4"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sana_location_level4', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

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
		global $EW_EXPORT, $sana_location_level4;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($sana_location_level4);
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
			if (@$_GET["locationLevel4ID"] != "") {
				$this->locationLevel4ID->setQueryStringValue($_GET["locationLevel4ID"]);
				$this->setKey("locationLevel4ID", $this->locationLevel4ID->CurrentValue); // Set up key
			} else {
				$this->setKey("locationLevel4ID", ""); // Clear key
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
					$this->Page_Terminate("sana_location_level4list.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "sana_location_level4list.php")
						$sReturnUrl = $this->AddMasterUrl($this->GetListUrl()); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "sana_location_level4view.php")
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
		$this->locationName->CurrentValue = NULL;
		$this->locationName->OldValue = $this->locationName->CurrentValue;
		$this->locationLevel3ID->CurrentValue = NULL;
		$this->locationLevel3ID->OldValue = $this->locationLevel3ID->CurrentValue;
		$this->locationLevel3Name->CurrentValue = NULL;
		$this->locationLevel3Name->OldValue = $this->locationLevel3Name->CurrentValue;
		$this->locationLevel1ID->CurrentValue = NULL;
		$this->locationLevel1ID->OldValue = $this->locationLevel1ID->CurrentValue;
		$this->locationLevel2ID->CurrentValue = NULL;
		$this->locationLevel2ID->OldValue = $this->locationLevel2ID->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->locationName->FldIsDetailKey) {
			$this->locationName->setFormValue($objForm->GetValue("x_locationName"));
		}
		if (!$this->locationLevel3ID->FldIsDetailKey) {
			$this->locationLevel3ID->setFormValue($objForm->GetValue("x_locationLevel3ID"));
		}
		if (!$this->locationLevel3Name->FldIsDetailKey) {
			$this->locationLevel3Name->setFormValue($objForm->GetValue("x_locationLevel3Name"));
		}
		if (!$this->locationLevel1ID->FldIsDetailKey) {
			$this->locationLevel1ID->setFormValue($objForm->GetValue("x_locationLevel1ID"));
		}
		if (!$this->locationLevel2ID->FldIsDetailKey) {
			$this->locationLevel2ID->setFormValue($objForm->GetValue("x_locationLevel2ID"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->locationName->CurrentValue = $this->locationName->FormValue;
		$this->locationLevel3ID->CurrentValue = $this->locationLevel3ID->FormValue;
		$this->locationLevel3Name->CurrentValue = $this->locationLevel3Name->FormValue;
		$this->locationLevel1ID->CurrentValue = $this->locationLevel1ID->FormValue;
		$this->locationLevel2ID->CurrentValue = $this->locationLevel2ID->FormValue;
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
		$this->locationLevel4ID->setDbValue($rs->fields('locationLevel4ID'));
		$this->locationName->setDbValue($rs->fields('locationName'));
		$this->locationLevel3ID->setDbValue($rs->fields('locationLevel3ID'));
		$this->locationLevel3Name->setDbValue($rs->fields('locationLevel3Name'));
		$this->locationLevel1ID->setDbValue($rs->fields('locationLevel1ID'));
		$this->locationLevel2ID->setDbValue($rs->fields('locationLevel2ID'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->locationLevel4ID->DbValue = $row['locationLevel4ID'];
		$this->locationName->DbValue = $row['locationName'];
		$this->locationLevel3ID->DbValue = $row['locationLevel3ID'];
		$this->locationLevel3Name->DbValue = $row['locationLevel3Name'];
		$this->locationLevel1ID->DbValue = $row['locationLevel1ID'];
		$this->locationLevel2ID->DbValue = $row['locationLevel2ID'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("locationLevel4ID")) <> "")
			$this->locationLevel4ID->CurrentValue = $this->getKey("locationLevel4ID"); // locationLevel4ID
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
		// locationLevel4ID
		// locationName
		// locationLevel3ID
		// locationLevel3Name
		// locationLevel1ID
		// locationLevel2ID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// locationLevel4ID
		$this->locationLevel4ID->ViewValue = $this->locationLevel4ID->CurrentValue;
		$this->locationLevel4ID->ViewCustomAttributes = "";

		// locationName
		$this->locationName->ViewValue = $this->locationName->CurrentValue;
		$this->locationName->ViewCustomAttributes = "";

		// locationLevel3ID
		if (strval($this->locationLevel3ID->CurrentValue) <> "") {
			$sFilterWrk = "`locationLevel3ID`" . ew_SearchString("=", $this->locationLevel3ID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `locationLevel3ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level3`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `locationLevel3ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level3`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `locationLevel3ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level3`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->locationLevel3ID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->locationLevel3ID->ViewValue = $this->locationLevel3ID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->locationLevel3ID->ViewValue = $this->locationLevel3ID->CurrentValue;
			}
		} else {
			$this->locationLevel3ID->ViewValue = NULL;
		}
		$this->locationLevel3ID->ViewCustomAttributes = "";

		// locationLevel3Name
		$this->locationLevel3Name->ViewValue = $this->locationLevel3Name->CurrentValue;
		$this->locationLevel3Name->ViewCustomAttributes = "";

		// locationLevel1ID
		$this->locationLevel1ID->ViewValue = $this->locationLevel1ID->CurrentValue;
		$this->locationLevel1ID->ViewCustomAttributes = "";

		// locationLevel2ID
		$this->locationLevel2ID->ViewValue = $this->locationLevel2ID->CurrentValue;
		$this->locationLevel2ID->ViewCustomAttributes = "";

			// locationName
			$this->locationName->LinkCustomAttributes = "";
			$this->locationName->HrefValue = "";
			$this->locationName->TooltipValue = "";

			// locationLevel3ID
			$this->locationLevel3ID->LinkCustomAttributes = "";
			$this->locationLevel3ID->HrefValue = "";
			$this->locationLevel3ID->TooltipValue = "";

			// locationLevel3Name
			$this->locationLevel3Name->LinkCustomAttributes = "";
			$this->locationLevel3Name->HrefValue = "";
			$this->locationLevel3Name->TooltipValue = "";

			// locationLevel1ID
			$this->locationLevel1ID->LinkCustomAttributes = "";
			$this->locationLevel1ID->HrefValue = "";
			$this->locationLevel1ID->TooltipValue = "";

			// locationLevel2ID
			$this->locationLevel2ID->LinkCustomAttributes = "";
			$this->locationLevel2ID->HrefValue = "";
			$this->locationLevel2ID->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// locationName
			$this->locationName->EditAttrs["class"] = "form-control";
			$this->locationName->EditCustomAttributes = "";
			$this->locationName->EditValue = ew_HtmlEncode($this->locationName->CurrentValue);
			$this->locationName->PlaceHolder = ew_RemoveHtml($this->locationName->FldCaption());

			// locationLevel3ID
			$this->locationLevel3ID->EditAttrs["class"] = "form-control";
			$this->locationLevel3ID->EditCustomAttributes = "";
			if (trim(strval($this->locationLevel3ID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`locationLevel3ID`" . ew_SearchString("=", $this->locationLevel3ID->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `locationLevel3ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level3`";
					$sWhereWrk = "";
					break;
				case "fa":
					$sSqlWrk = "SELECT `locationLevel3ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level3`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `locationLevel3ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level3`";
					$sWhereWrk = "";
					break;
			}
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->locationLevel3ID, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->locationLevel3ID->EditValue = $arwrk;

			// locationLevel3Name
			$this->locationLevel3Name->EditAttrs["class"] = "form-control";
			$this->locationLevel3Name->EditCustomAttributes = "";
			$this->locationLevel3Name->EditValue = ew_HtmlEncode($this->locationLevel3Name->CurrentValue);
			$this->locationLevel3Name->PlaceHolder = ew_RemoveHtml($this->locationLevel3Name->FldCaption());

			// locationLevel1ID
			$this->locationLevel1ID->EditAttrs["class"] = "form-control";
			$this->locationLevel1ID->EditCustomAttributes = "";
			$this->locationLevel1ID->EditValue = ew_HtmlEncode($this->locationLevel1ID->CurrentValue);
			$this->locationLevel1ID->PlaceHolder = ew_RemoveHtml($this->locationLevel1ID->FldCaption());

			// locationLevel2ID
			$this->locationLevel2ID->EditAttrs["class"] = "form-control";
			$this->locationLevel2ID->EditCustomAttributes = "";
			$this->locationLevel2ID->EditValue = ew_HtmlEncode($this->locationLevel2ID->CurrentValue);
			$this->locationLevel2ID->PlaceHolder = ew_RemoveHtml($this->locationLevel2ID->FldCaption());

			// Add refer script
			// locationName

			$this->locationName->LinkCustomAttributes = "";
			$this->locationName->HrefValue = "";

			// locationLevel3ID
			$this->locationLevel3ID->LinkCustomAttributes = "";
			$this->locationLevel3ID->HrefValue = "";

			// locationLevel3Name
			$this->locationLevel3Name->LinkCustomAttributes = "";
			$this->locationLevel3Name->HrefValue = "";

			// locationLevel1ID
			$this->locationLevel1ID->LinkCustomAttributes = "";
			$this->locationLevel1ID->HrefValue = "";

			// locationLevel2ID
			$this->locationLevel2ID->LinkCustomAttributes = "";
			$this->locationLevel2ID->HrefValue = "";
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
		if (!$this->locationName->FldIsDetailKey && !is_null($this->locationName->FormValue) && $this->locationName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->locationName->FldCaption(), $this->locationName->ReqErrMsg));
		}
		if (!$this->locationLevel3ID->FldIsDetailKey && !is_null($this->locationLevel3ID->FormValue) && $this->locationLevel3ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->locationLevel3ID->FldCaption(), $this->locationLevel3ID->ReqErrMsg));
		}
		if (!$this->locationLevel3Name->FldIsDetailKey && !is_null($this->locationLevel3Name->FormValue) && $this->locationLevel3Name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->locationLevel3Name->FldCaption(), $this->locationLevel3Name->ReqErrMsg));
		}
		if (!$this->locationLevel1ID->FldIsDetailKey && !is_null($this->locationLevel1ID->FormValue) && $this->locationLevel1ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->locationLevel1ID->FldCaption(), $this->locationLevel1ID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->locationLevel1ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->locationLevel1ID->FldErrMsg());
		}
		if (!$this->locationLevel2ID->FldIsDetailKey && !is_null($this->locationLevel2ID->FormValue) && $this->locationLevel2ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->locationLevel2ID->FldCaption(), $this->locationLevel2ID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->locationLevel2ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->locationLevel2ID->FldErrMsg());
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

		// locationName
		$this->locationName->SetDbValueDef($rsnew, $this->locationName->CurrentValue, "", FALSE);

		// locationLevel3ID
		$this->locationLevel3ID->SetDbValueDef($rsnew, $this->locationLevel3ID->CurrentValue, 0, FALSE);

		// locationLevel3Name
		$this->locationLevel3Name->SetDbValueDef($rsnew, $this->locationLevel3Name->CurrentValue, "", FALSE);

		// locationLevel1ID
		$this->locationLevel1ID->SetDbValueDef($rsnew, $this->locationLevel1ID->CurrentValue, 0, FALSE);

		// locationLevel2ID
		$this->locationLevel2ID->SetDbValueDef($rsnew, $this->locationLevel2ID->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->locationLevel4ID->setDbValue($conn->Insert_ID());
				$rsnew['locationLevel4ID'] = $this->locationLevel4ID->DbValue;
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sana_location_level4list.php"), "", $this->TableVar, TRUE);
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
if (!isset($sana_location_level4_add)) $sana_location_level4_add = new csana_location_level4_add();

// Page init
$sana_location_level4_add->Page_Init();

// Page main
$sana_location_level4_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_location_level4_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fsana_location_level4add = new ew_Form("fsana_location_level4add", "add");

// Validate form
fsana_location_level4add.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_locationName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_location_level4->locationName->FldCaption(), $sana_location_level4->locationName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_locationLevel3ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_location_level4->locationLevel3ID->FldCaption(), $sana_location_level4->locationLevel3ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_locationLevel3Name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_location_level4->locationLevel3Name->FldCaption(), $sana_location_level4->locationLevel3Name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_locationLevel1ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_location_level4->locationLevel1ID->FldCaption(), $sana_location_level4->locationLevel1ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_locationLevel1ID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_location_level4->locationLevel1ID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_locationLevel2ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_location_level4->locationLevel2ID->FldCaption(), $sana_location_level4->locationLevel2ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_locationLevel2ID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_location_level4->locationLevel2ID->FldErrMsg()) ?>");

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
fsana_location_level4add.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_location_level4add.ValidateRequired = true;
<?php } else { ?>
fsana_location_level4add.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsana_location_level4add.Lists["x_locationLevel3ID"] = {"LinkField":"x_locationLevel3ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_locationName","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $sana_location_level4_add->ShowPageHeader(); ?>
<?php
$sana_location_level4_add->ShowMessage();
?>
<form name="fsana_location_level4add" id="fsana_location_level4add" class="<?php echo $sana_location_level4_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_location_level4_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_location_level4_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_location_level4">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($sana_location_level4->locationName->Visible) { // locationName ?>
	<div id="r_locationName" class="form-group">
		<label id="elh_sana_location_level4_locationName" for="x_locationName" class="col-sm-2 control-label ewLabel"><?php echo $sana_location_level4->locationName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_location_level4->locationName->CellAttributes() ?>>
<span id="el_sana_location_level4_locationName">
<input type="text" data-table="sana_location_level4" data-field="x_locationName" name="x_locationName" id="x_locationName" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_location_level4->locationName->getPlaceHolder()) ?>" value="<?php echo $sana_location_level4->locationName->EditValue ?>"<?php echo $sana_location_level4->locationName->EditAttributes() ?>>
</span>
<?php echo $sana_location_level4->locationName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_location_level4->locationLevel3ID->Visible) { // locationLevel3ID ?>
	<div id="r_locationLevel3ID" class="form-group">
		<label id="elh_sana_location_level4_locationLevel3ID" for="x_locationLevel3ID" class="col-sm-2 control-label ewLabel"><?php echo $sana_location_level4->locationLevel3ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_location_level4->locationLevel3ID->CellAttributes() ?>>
<span id="el_sana_location_level4_locationLevel3ID">
<select data-table="sana_location_level4" data-field="x_locationLevel3ID" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_location_level4->locationLevel3ID->DisplayValueSeparator) ? json_encode($sana_location_level4->locationLevel3ID->DisplayValueSeparator) : $sana_location_level4->locationLevel3ID->DisplayValueSeparator) ?>" id="x_locationLevel3ID" name="x_locationLevel3ID"<?php echo $sana_location_level4->locationLevel3ID->EditAttributes() ?>>
<?php
if (is_array($sana_location_level4->locationLevel3ID->EditValue)) {
	$arwrk = $sana_location_level4->locationLevel3ID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sana_location_level4->locationLevel3ID->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sana_location_level4->locationLevel3ID->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($sana_location_level4->locationLevel3ID->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($sana_location_level4->locationLevel3ID->CurrentValue) ?>" selected><?php echo $sana_location_level4->locationLevel3ID->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `locationLevel3ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level3`";
		$sWhereWrk = "";
		break;
	case "fa":
		$sSqlWrk = "SELECT `locationLevel3ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level3`";
		$sWhereWrk = "";
		break;
	default:
		$sSqlWrk = "SELECT `locationLevel3ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level3`";
		$sWhereWrk = "";
		break;
}
$sana_location_level4->locationLevel3ID->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sana_location_level4->locationLevel3ID->LookupFilters += array("f0" => "`locationLevel3ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$sana_location_level4->Lookup_Selecting($sana_location_level4->locationLevel3ID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sana_location_level4->locationLevel3ID->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_locationLevel3ID" id="s_x_locationLevel3ID" value="<?php echo $sana_location_level4->locationLevel3ID->LookupFilterQuery() ?>">
</span>
<?php echo $sana_location_level4->locationLevel3ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_location_level4->locationLevel3Name->Visible) { // locationLevel3Name ?>
	<div id="r_locationLevel3Name" class="form-group">
		<label id="elh_sana_location_level4_locationLevel3Name" for="x_locationLevel3Name" class="col-sm-2 control-label ewLabel"><?php echo $sana_location_level4->locationLevel3Name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_location_level4->locationLevel3Name->CellAttributes() ?>>
<span id="el_sana_location_level4_locationLevel3Name">
<input type="text" data-table="sana_location_level4" data-field="x_locationLevel3Name" name="x_locationLevel3Name" id="x_locationLevel3Name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_location_level4->locationLevel3Name->getPlaceHolder()) ?>" value="<?php echo $sana_location_level4->locationLevel3Name->EditValue ?>"<?php echo $sana_location_level4->locationLevel3Name->EditAttributes() ?>>
</span>
<?php echo $sana_location_level4->locationLevel3Name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_location_level4->locationLevel1ID->Visible) { // locationLevel1ID ?>
	<div id="r_locationLevel1ID" class="form-group">
		<label id="elh_sana_location_level4_locationLevel1ID" for="x_locationLevel1ID" class="col-sm-2 control-label ewLabel"><?php echo $sana_location_level4->locationLevel1ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_location_level4->locationLevel1ID->CellAttributes() ?>>
<span id="el_sana_location_level4_locationLevel1ID">
<input type="text" data-table="sana_location_level4" data-field="x_locationLevel1ID" name="x_locationLevel1ID" id="x_locationLevel1ID" size="30" placeholder="<?php echo ew_HtmlEncode($sana_location_level4->locationLevel1ID->getPlaceHolder()) ?>" value="<?php echo $sana_location_level4->locationLevel1ID->EditValue ?>"<?php echo $sana_location_level4->locationLevel1ID->EditAttributes() ?>>
</span>
<?php echo $sana_location_level4->locationLevel1ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sana_location_level4->locationLevel2ID->Visible) { // locationLevel2ID ?>
	<div id="r_locationLevel2ID" class="form-group">
		<label id="elh_sana_location_level4_locationLevel2ID" for="x_locationLevel2ID" class="col-sm-2 control-label ewLabel"><?php echo $sana_location_level4->locationLevel2ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sana_location_level4->locationLevel2ID->CellAttributes() ?>>
<span id="el_sana_location_level4_locationLevel2ID">
<input type="text" data-table="sana_location_level4" data-field="x_locationLevel2ID" name="x_locationLevel2ID" id="x_locationLevel2ID" size="30" placeholder="<?php echo ew_HtmlEncode($sana_location_level4->locationLevel2ID->getPlaceHolder()) ?>" value="<?php echo $sana_location_level4->locationLevel2ID->EditValue ?>"<?php echo $sana_location_level4->locationLevel2ID->EditAttributes() ?>>
</span>
<?php echo $sana_location_level4->locationLevel2ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sana_location_level4_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fsana_location_level4add.Init();
</script>
<?php
$sana_location_level4_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_location_level4_add->Page_Terminate();
?>
