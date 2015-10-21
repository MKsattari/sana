<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "sana_location_level3info.php" ?>
<?php include_once "sana_userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$sana_location_level3_delete = NULL; // Initialize page object first

class csana_location_level3_delete extends csana_location_level3 {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_location_level3';

	// Page object name
	var $PageObjName = 'sana_location_level3_delete';

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

		// Table object (sana_location_level3)
		if (!isset($GLOBALS["sana_location_level3"]) || get_class($GLOBALS["sana_location_level3"]) == "csana_location_level3") {
			$GLOBALS["sana_location_level3"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_location_level3"];
		}

		// Table object (sana_user)
		if (!isset($GLOBALS['sana_user'])) $GLOBALS['sana_user'] = new csana_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sana_location_level3', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("sana_location_level3list.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->locationLevel3ID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $sana_location_level3;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($sana_location_level3);
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
			$this->Page_Terminate("sana_location_level3list.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in sana_location_level3 class, sana_location_level3info.php

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
		$this->locationLevel3ID->setDbValue($rs->fields('locationLevel3ID'));
		$this->locationName->setDbValue($rs->fields('locationName'));
		$this->locationLevel1ID->setDbValue($rs->fields('locationLevel1ID'));
		$this->locationLevel2ID->setDbValue($rs->fields('locationLevel2ID'));
		$this->locationLevel2Name->setDbValue($rs->fields('locationLevel2Name'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->locationLevel3ID->DbValue = $row['locationLevel3ID'];
		$this->locationName->DbValue = $row['locationName'];
		$this->locationLevel1ID->DbValue = $row['locationLevel1ID'];
		$this->locationLevel2ID->DbValue = $row['locationLevel2ID'];
		$this->locationLevel2Name->DbValue = $row['locationLevel2Name'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// locationLevel3ID
		// locationName
		// locationLevel1ID
		// locationLevel2ID
		// locationLevel2Name

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// locationLevel3ID
		$this->locationLevel3ID->ViewValue = $this->locationLevel3ID->CurrentValue;
		$this->locationLevel3ID->ViewCustomAttributes = "";

		// locationName
		$this->locationName->ViewValue = $this->locationName->CurrentValue;
		$this->locationName->ViewCustomAttributes = "";

		// locationLevel1ID
		if (strval($this->locationLevel1ID->CurrentValue) <> "") {
			$sFilterWrk = "`locationLevel1ID`" . ew_SearchString("=", $this->locationLevel1ID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `locationLevel1ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level1`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `locationLevel1ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level1`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `locationLevel1ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level1`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->locationLevel1ID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->locationLevel1ID->ViewValue = $this->locationLevel1ID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->locationLevel1ID->ViewValue = $this->locationLevel1ID->CurrentValue;
			}
		} else {
			$this->locationLevel1ID->ViewValue = NULL;
		}
		$this->locationLevel1ID->ViewCustomAttributes = "";

		// locationLevel2ID
		if (strval($this->locationLevel2ID->CurrentValue) <> "") {
			$sFilterWrk = "`locationLevel2ID`" . ew_SearchString("=", $this->locationLevel2ID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `locationLevel2ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level2`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `locationLevel2ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level2`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `locationLevel2ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level2`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->locationLevel2ID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->locationLevel2ID->ViewValue = $this->locationLevel2ID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->locationLevel2ID->ViewValue = $this->locationLevel2ID->CurrentValue;
			}
		} else {
			$this->locationLevel2ID->ViewValue = NULL;
		}
		$this->locationLevel2ID->ViewCustomAttributes = "";

		// locationLevel2Name
		$this->locationLevel2Name->ViewValue = $this->locationLevel2Name->CurrentValue;
		$this->locationLevel2Name->ViewCustomAttributes = "";

			// locationLevel3ID
			$this->locationLevel3ID->LinkCustomAttributes = "";
			$this->locationLevel3ID->HrefValue = "";
			$this->locationLevel3ID->TooltipValue = "";

			// locationName
			$this->locationName->LinkCustomAttributes = "";
			$this->locationName->HrefValue = "";
			$this->locationName->TooltipValue = "";

			// locationLevel1ID
			$this->locationLevel1ID->LinkCustomAttributes = "";
			$this->locationLevel1ID->HrefValue = "";
			$this->locationLevel1ID->TooltipValue = "";

			// locationLevel2ID
			$this->locationLevel2ID->LinkCustomAttributes = "";
			$this->locationLevel2ID->HrefValue = "";
			$this->locationLevel2ID->TooltipValue = "";

			// locationLevel2Name
			$this->locationLevel2Name->LinkCustomAttributes = "";
			$this->locationLevel2Name->HrefValue = "";
			$this->locationLevel2Name->TooltipValue = "";
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
				$sThisKey .= $row['locationLevel3ID'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sana_location_level3list.php"), "", $this->TableVar, TRUE);
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
if (!isset($sana_location_level3_delete)) $sana_location_level3_delete = new csana_location_level3_delete();

// Page init
$sana_location_level3_delete->Page_Init();

// Page main
$sana_location_level3_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_location_level3_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fsana_location_level3delete = new ew_Form("fsana_location_level3delete", "delete");

// Form_CustomValidate event
fsana_location_level3delete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_location_level3delete.ValidateRequired = true;
<?php } else { ?>
fsana_location_level3delete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsana_location_level3delete.Lists["x_locationLevel1ID"] = {"LinkField":"x_locationLevel1ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_locationName","","",""],"ParentFields":[],"ChildFields":["x_locationLevel2ID"],"FilterFields":[],"Options":[],"Template":""};
fsana_location_level3delete.Lists["x_locationLevel2ID"] = {"LinkField":"x_locationLevel2ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_locationName","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($sana_location_level3_delete->Recordset = $sana_location_level3_delete->LoadRecordset())
	$sana_location_level3_deleteTotalRecs = $sana_location_level3_delete->Recordset->RecordCount(); // Get record count
if ($sana_location_level3_deleteTotalRecs <= 0) { // No record found, exit
	if ($sana_location_level3_delete->Recordset)
		$sana_location_level3_delete->Recordset->Close();
	$sana_location_level3_delete->Page_Terminate("sana_location_level3list.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $sana_location_level3_delete->ShowPageHeader(); ?>
<?php
$sana_location_level3_delete->ShowMessage();
?>
<form name="fsana_location_level3delete" id="fsana_location_level3delete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_location_level3_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_location_level3_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_location_level3">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($sana_location_level3_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $sana_location_level3->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($sana_location_level3->locationLevel3ID->Visible) { // locationLevel3ID ?>
		<th><span id="elh_sana_location_level3_locationLevel3ID" class="sana_location_level3_locationLevel3ID"><?php echo $sana_location_level3->locationLevel3ID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_location_level3->locationName->Visible) { // locationName ?>
		<th><span id="elh_sana_location_level3_locationName" class="sana_location_level3_locationName"><?php echo $sana_location_level3->locationName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_location_level3->locationLevel1ID->Visible) { // locationLevel1ID ?>
		<th><span id="elh_sana_location_level3_locationLevel1ID" class="sana_location_level3_locationLevel1ID"><?php echo $sana_location_level3->locationLevel1ID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_location_level3->locationLevel2ID->Visible) { // locationLevel2ID ?>
		<th><span id="elh_sana_location_level3_locationLevel2ID" class="sana_location_level3_locationLevel2ID"><?php echo $sana_location_level3->locationLevel2ID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_location_level3->locationLevel2Name->Visible) { // locationLevel2Name ?>
		<th><span id="elh_sana_location_level3_locationLevel2Name" class="sana_location_level3_locationLevel2Name"><?php echo $sana_location_level3->locationLevel2Name->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$sana_location_level3_delete->RecCnt = 0;
$i = 0;
while (!$sana_location_level3_delete->Recordset->EOF) {
	$sana_location_level3_delete->RecCnt++;
	$sana_location_level3_delete->RowCnt++;

	// Set row properties
	$sana_location_level3->ResetAttrs();
	$sana_location_level3->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$sana_location_level3_delete->LoadRowValues($sana_location_level3_delete->Recordset);

	// Render row
	$sana_location_level3_delete->RenderRow();
?>
	<tr<?php echo $sana_location_level3->RowAttributes() ?>>
<?php if ($sana_location_level3->locationLevel3ID->Visible) { // locationLevel3ID ?>
		<td<?php echo $sana_location_level3->locationLevel3ID->CellAttributes() ?>>
<span id="el<?php echo $sana_location_level3_delete->RowCnt ?>_sana_location_level3_locationLevel3ID" class="sana_location_level3_locationLevel3ID">
<span<?php echo $sana_location_level3->locationLevel3ID->ViewAttributes() ?>>
<?php echo $sana_location_level3->locationLevel3ID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_location_level3->locationName->Visible) { // locationName ?>
		<td<?php echo $sana_location_level3->locationName->CellAttributes() ?>>
<span id="el<?php echo $sana_location_level3_delete->RowCnt ?>_sana_location_level3_locationName" class="sana_location_level3_locationName">
<span<?php echo $sana_location_level3->locationName->ViewAttributes() ?>>
<?php echo $sana_location_level3->locationName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_location_level3->locationLevel1ID->Visible) { // locationLevel1ID ?>
		<td<?php echo $sana_location_level3->locationLevel1ID->CellAttributes() ?>>
<span id="el<?php echo $sana_location_level3_delete->RowCnt ?>_sana_location_level3_locationLevel1ID" class="sana_location_level3_locationLevel1ID">
<span<?php echo $sana_location_level3->locationLevel1ID->ViewAttributes() ?>>
<?php echo $sana_location_level3->locationLevel1ID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_location_level3->locationLevel2ID->Visible) { // locationLevel2ID ?>
		<td<?php echo $sana_location_level3->locationLevel2ID->CellAttributes() ?>>
<span id="el<?php echo $sana_location_level3_delete->RowCnt ?>_sana_location_level3_locationLevel2ID" class="sana_location_level3_locationLevel2ID">
<span<?php echo $sana_location_level3->locationLevel2ID->ViewAttributes() ?>>
<?php echo $sana_location_level3->locationLevel2ID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_location_level3->locationLevel2Name->Visible) { // locationLevel2Name ?>
		<td<?php echo $sana_location_level3->locationLevel2Name->CellAttributes() ?>>
<span id="el<?php echo $sana_location_level3_delete->RowCnt ?>_sana_location_level3_locationLevel2Name" class="sana_location_level3_locationLevel2Name">
<span<?php echo $sana_location_level3->locationLevel2Name->ViewAttributes() ?>>
<?php echo $sana_location_level3->locationLevel2Name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$sana_location_level3_delete->Recordset->MoveNext();
}
$sana_location_level3_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sana_location_level3_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fsana_location_level3delete.Init();
</script>
<?php
$sana_location_level3_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_location_level3_delete->Page_Terminate();
?>
