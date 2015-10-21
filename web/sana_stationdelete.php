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

$sana_station_delete = NULL; // Initialize page object first

class csana_station_delete extends csana_station {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_station';

	// Page object name
	var $PageObjName = 'sana_station_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->stationID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("sana_stationlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in sana_station class, sana_stationinfo.php

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

			// stationID
			$this->stationID->LinkCustomAttributes = "";
			$this->stationID->HrefValue = "";
			$this->stationID->TooltipValue = "";

			// stationName
			$this->stationName->LinkCustomAttributes = "";
			$this->stationName->HrefValue = "";
			$this->stationName->TooltipValue = "";

			// projectID
			$this->projectID->LinkCustomAttributes = "";
			$this->projectID->HrefValue = "";
			$this->projectID->TooltipValue = "";

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
				$sThisKey .= $row['stationID'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sana_stationlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($sana_station_delete)) $sana_station_delete = new csana_station_delete();

// Page init
$sana_station_delete->Page_Init();

// Page main
$sana_station_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_station_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fsana_stationdelete = new ew_Form("fsana_stationdelete", "delete");

// Form_CustomValidate event
fsana_stationdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_stationdelete.ValidateRequired = true;
<?php } else { ?>
fsana_stationdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsana_stationdelete.Lists["x_projectID"] = {"LinkField":"x_projectID","Ajax":true,"AutoFill":false,"DisplayFields":["x_projectName","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($sana_station_delete->Recordset = $sana_station_delete->LoadRecordset())
	$sana_station_deleteTotalRecs = $sana_station_delete->Recordset->RecordCount(); // Get record count
if ($sana_station_deleteTotalRecs <= 0) { // No record found, exit
	if ($sana_station_delete->Recordset)
		$sana_station_delete->Recordset->Close();
	$sana_station_delete->Page_Terminate("sana_stationlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $sana_station_delete->ShowPageHeader(); ?>
<?php
$sana_station_delete->ShowMessage();
?>
<form name="fsana_stationdelete" id="fsana_stationdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_station_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_station_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_station">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($sana_station_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $sana_station->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($sana_station->stationID->Visible) { // stationID ?>
		<th><span id="elh_sana_station_stationID" class="sana_station_stationID"><?php echo $sana_station->stationID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_station->stationName->Visible) { // stationName ?>
		<th><span id="elh_sana_station_stationName" class="sana_station_stationName"><?php echo $sana_station->stationName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_station->projectID->Visible) { // projectID ?>
		<th><span id="elh_sana_station_projectID" class="sana_station_projectID"><?php echo $sana_station->projectID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_station->address->Visible) { // address ?>
		<th><span id="elh_sana_station_address" class="sana_station_address"><?php echo $sana_station->address->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_station->GPS1->Visible) { // GPS1 ?>
		<th><span id="elh_sana_station_GPS1" class="sana_station_GPS1"><?php echo $sana_station->GPS1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_station->GPS2->Visible) { // GPS2 ?>
		<th><span id="elh_sana_station_GPS2" class="sana_station_GPS2"><?php echo $sana_station->GPS2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_station->GPS3->Visible) { // GPS3 ?>
		<th><span id="elh_sana_station_GPS3" class="sana_station_GPS3"><?php echo $sana_station->GPS3->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_station->stationType->Visible) { // stationType ?>
		<th><span id="elh_sana_station_stationType" class="sana_station_stationType"><?php echo $sana_station->stationType->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$sana_station_delete->RecCnt = 0;
$i = 0;
while (!$sana_station_delete->Recordset->EOF) {
	$sana_station_delete->RecCnt++;
	$sana_station_delete->RowCnt++;

	// Set row properties
	$sana_station->ResetAttrs();
	$sana_station->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$sana_station_delete->LoadRowValues($sana_station_delete->Recordset);

	// Render row
	$sana_station_delete->RenderRow();
?>
	<tr<?php echo $sana_station->RowAttributes() ?>>
<?php if ($sana_station->stationID->Visible) { // stationID ?>
		<td<?php echo $sana_station->stationID->CellAttributes() ?>>
<span id="el<?php echo $sana_station_delete->RowCnt ?>_sana_station_stationID" class="sana_station_stationID">
<span<?php echo $sana_station->stationID->ViewAttributes() ?>>
<?php echo $sana_station->stationID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_station->stationName->Visible) { // stationName ?>
		<td<?php echo $sana_station->stationName->CellAttributes() ?>>
<span id="el<?php echo $sana_station_delete->RowCnt ?>_sana_station_stationName" class="sana_station_stationName">
<span<?php echo $sana_station->stationName->ViewAttributes() ?>>
<?php echo $sana_station->stationName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_station->projectID->Visible) { // projectID ?>
		<td<?php echo $sana_station->projectID->CellAttributes() ?>>
<span id="el<?php echo $sana_station_delete->RowCnt ?>_sana_station_projectID" class="sana_station_projectID">
<span<?php echo $sana_station->projectID->ViewAttributes() ?>>
<?php echo $sana_station->projectID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_station->address->Visible) { // address ?>
		<td<?php echo $sana_station->address->CellAttributes() ?>>
<span id="el<?php echo $sana_station_delete->RowCnt ?>_sana_station_address" class="sana_station_address">
<span<?php echo $sana_station->address->ViewAttributes() ?>>
<?php echo $sana_station->address->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_station->GPS1->Visible) { // GPS1 ?>
		<td<?php echo $sana_station->GPS1->CellAttributes() ?>>
<span id="el<?php echo $sana_station_delete->RowCnt ?>_sana_station_GPS1" class="sana_station_GPS1">
<span<?php echo $sana_station->GPS1->ViewAttributes() ?>>
<?php echo $sana_station->GPS1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_station->GPS2->Visible) { // GPS2 ?>
		<td<?php echo $sana_station->GPS2->CellAttributes() ?>>
<span id="el<?php echo $sana_station_delete->RowCnt ?>_sana_station_GPS2" class="sana_station_GPS2">
<span<?php echo $sana_station->GPS2->ViewAttributes() ?>>
<?php echo $sana_station->GPS2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_station->GPS3->Visible) { // GPS3 ?>
		<td<?php echo $sana_station->GPS3->CellAttributes() ?>>
<span id="el<?php echo $sana_station_delete->RowCnt ?>_sana_station_GPS3" class="sana_station_GPS3">
<span<?php echo $sana_station->GPS3->ViewAttributes() ?>>
<?php echo $sana_station->GPS3->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_station->stationType->Visible) { // stationType ?>
		<td<?php echo $sana_station->stationType->CellAttributes() ?>>
<span id="el<?php echo $sana_station_delete->RowCnt ?>_sana_station_stationType" class="sana_station_stationType">
<span<?php echo $sana_station->stationType->ViewAttributes() ?>>
<?php echo $sana_station->stationType->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$sana_station_delete->Recordset->MoveNext();
}
$sana_station_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sana_station_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fsana_stationdelete.Init();
</script>
<?php
$sana_station_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_station_delete->Page_Terminate();
?>
