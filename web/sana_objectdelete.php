<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "sana_objectinfo.php" ?>
<?php include_once "sana_userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$sana_object_delete = NULL; // Initialize page object first

class csana_object_delete extends csana_object {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_object';

	// Page object name
	var $PageObjName = 'sana_object_delete';

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

		// Table object (sana_object)
		if (!isset($GLOBALS["sana_object"]) || get_class($GLOBALS["sana_object"]) == "csana_object") {
			$GLOBALS["sana_object"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_object"];
		}

		// Table object (sana_user)
		if (!isset($GLOBALS['sana_user'])) $GLOBALS['sana_user'] = new csana_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sana_object', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("sana_objectlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->objectID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $sana_object;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($sana_object);
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
			$this->Page_Terminate("sana_objectlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in sana_object class, sana_objectinfo.php

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
		$this->objectID->setDbValue($rs->fields('objectID'));
		$this->objectName->setDbValue($rs->fields('objectName'));
		$this->ownerID->setDbValue($rs->fields('ownerID'));
		$this->ownerName->setDbValue($rs->fields('ownerName'));
		$this->lastName->setDbValue($rs->fields('lastName'));
		$this->mobilePhone->setDbValue($rs->fields('mobilePhone'));
		$this->color->setDbValue($rs->fields('color'));
		$this->status->setDbValue($rs->fields('status'));
		$this->content->setDbValue($rs->fields('content'));
		$this->financialValue->setDbValue($rs->fields('financialValue'));
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
		$this->objectID->DbValue = $row['objectID'];
		$this->objectName->DbValue = $row['objectName'];
		$this->ownerID->DbValue = $row['ownerID'];
		$this->ownerName->DbValue = $row['ownerName'];
		$this->lastName->DbValue = $row['lastName'];
		$this->mobilePhone->DbValue = $row['mobilePhone'];
		$this->color->DbValue = $row['color'];
		$this->status->DbValue = $row['status'];
		$this->content->DbValue = $row['content'];
		$this->financialValue->DbValue = $row['financialValue'];
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
		// objectID
		// objectName
		// ownerID
		// ownerName
		// lastName
		// mobilePhone
		// color
		// status
		// content
		// financialValue
		// registrationUser
		// registrationDateTime
		// registrationStation
		// isolatedDateTime
		// description

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// objectID
		$this->objectID->ViewValue = $this->objectID->CurrentValue;
		$this->objectID->ViewCustomAttributes = "";

		// objectName
		$this->objectName->ViewValue = $this->objectName->CurrentValue;
		$this->objectName->ViewCustomAttributes = "";

		// ownerID
		$this->ownerID->ViewValue = $this->ownerID->CurrentValue;
		$this->ownerID->ViewCustomAttributes = "";

		// ownerName
		$this->ownerName->ViewValue = $this->ownerName->CurrentValue;
		$this->ownerName->ViewCustomAttributes = "";

		// lastName
		$this->lastName->ViewValue = $this->lastName->CurrentValue;
		$this->lastName->ViewCustomAttributes = "";

		// mobilePhone
		$this->mobilePhone->ViewValue = $this->mobilePhone->CurrentValue;
		$this->mobilePhone->ViewCustomAttributes = "";

		// color
		$this->color->ViewValue = $this->color->CurrentValue;
		$this->color->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// content
		$this->content->ViewValue = $this->content->CurrentValue;
		$this->content->ViewCustomAttributes = "";

		// financialValue
		$this->financialValue->ViewValue = $this->financialValue->CurrentValue;
		$this->financialValue->ViewCustomAttributes = "";

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

			// objectID
			$this->objectID->LinkCustomAttributes = "";
			$this->objectID->HrefValue = "";
			$this->objectID->TooltipValue = "";

			// objectName
			$this->objectName->LinkCustomAttributes = "";
			$this->objectName->HrefValue = "";
			$this->objectName->TooltipValue = "";

			// ownerID
			$this->ownerID->LinkCustomAttributes = "";
			$this->ownerID->HrefValue = "";
			$this->ownerID->TooltipValue = "";

			// ownerName
			$this->ownerName->LinkCustomAttributes = "";
			$this->ownerName->HrefValue = "";
			$this->ownerName->TooltipValue = "";

			// lastName
			$this->lastName->LinkCustomAttributes = "";
			$this->lastName->HrefValue = "";
			$this->lastName->TooltipValue = "";

			// mobilePhone
			$this->mobilePhone->LinkCustomAttributes = "";
			$this->mobilePhone->HrefValue = "";
			$this->mobilePhone->TooltipValue = "";

			// color
			$this->color->LinkCustomAttributes = "";
			$this->color->HrefValue = "";
			$this->color->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// content
			$this->content->LinkCustomAttributes = "";
			$this->content->HrefValue = "";
			$this->content->TooltipValue = "";

			// financialValue
			$this->financialValue->LinkCustomAttributes = "";
			$this->financialValue->HrefValue = "";
			$this->financialValue->TooltipValue = "";

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
				$sThisKey .= $row['objectID'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sana_objectlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($sana_object_delete)) $sana_object_delete = new csana_object_delete();

// Page init
$sana_object_delete->Page_Init();

// Page main
$sana_object_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_object_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fsana_objectdelete = new ew_Form("fsana_objectdelete", "delete");

// Form_CustomValidate event
fsana_objectdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_objectdelete.ValidateRequired = true;
<?php } else { ?>
fsana_objectdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($sana_object_delete->Recordset = $sana_object_delete->LoadRecordset())
	$sana_object_deleteTotalRecs = $sana_object_delete->Recordset->RecordCount(); // Get record count
if ($sana_object_deleteTotalRecs <= 0) { // No record found, exit
	if ($sana_object_delete->Recordset)
		$sana_object_delete->Recordset->Close();
	$sana_object_delete->Page_Terminate("sana_objectlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $sana_object_delete->ShowPageHeader(); ?>
<?php
$sana_object_delete->ShowMessage();
?>
<form name="fsana_objectdelete" id="fsana_objectdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_object_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_object_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_object">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($sana_object_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $sana_object->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($sana_object->objectID->Visible) { // objectID ?>
		<th><span id="elh_sana_object_objectID" class="sana_object_objectID"><?php echo $sana_object->objectID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_object->objectName->Visible) { // objectName ?>
		<th><span id="elh_sana_object_objectName" class="sana_object_objectName"><?php echo $sana_object->objectName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_object->ownerID->Visible) { // ownerID ?>
		<th><span id="elh_sana_object_ownerID" class="sana_object_ownerID"><?php echo $sana_object->ownerID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_object->ownerName->Visible) { // ownerName ?>
		<th><span id="elh_sana_object_ownerName" class="sana_object_ownerName"><?php echo $sana_object->ownerName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_object->lastName->Visible) { // lastName ?>
		<th><span id="elh_sana_object_lastName" class="sana_object_lastName"><?php echo $sana_object->lastName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_object->mobilePhone->Visible) { // mobilePhone ?>
		<th><span id="elh_sana_object_mobilePhone" class="sana_object_mobilePhone"><?php echo $sana_object->mobilePhone->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_object->color->Visible) { // color ?>
		<th><span id="elh_sana_object_color" class="sana_object_color"><?php echo $sana_object->color->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_object->status->Visible) { // status ?>
		<th><span id="elh_sana_object_status" class="sana_object_status"><?php echo $sana_object->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_object->content->Visible) { // content ?>
		<th><span id="elh_sana_object_content" class="sana_object_content"><?php echo $sana_object->content->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_object->financialValue->Visible) { // financialValue ?>
		<th><span id="elh_sana_object_financialValue" class="sana_object_financialValue"><?php echo $sana_object->financialValue->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_object->registrationUser->Visible) { // registrationUser ?>
		<th><span id="elh_sana_object_registrationUser" class="sana_object_registrationUser"><?php echo $sana_object->registrationUser->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_object->registrationDateTime->Visible) { // registrationDateTime ?>
		<th><span id="elh_sana_object_registrationDateTime" class="sana_object_registrationDateTime"><?php echo $sana_object->registrationDateTime->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_object->registrationStation->Visible) { // registrationStation ?>
		<th><span id="elh_sana_object_registrationStation" class="sana_object_registrationStation"><?php echo $sana_object->registrationStation->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_object->isolatedDateTime->Visible) { // isolatedDateTime ?>
		<th><span id="elh_sana_object_isolatedDateTime" class="sana_object_isolatedDateTime"><?php echo $sana_object->isolatedDateTime->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$sana_object_delete->RecCnt = 0;
$i = 0;
while (!$sana_object_delete->Recordset->EOF) {
	$sana_object_delete->RecCnt++;
	$sana_object_delete->RowCnt++;

	// Set row properties
	$sana_object->ResetAttrs();
	$sana_object->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$sana_object_delete->LoadRowValues($sana_object_delete->Recordset);

	// Render row
	$sana_object_delete->RenderRow();
?>
	<tr<?php echo $sana_object->RowAttributes() ?>>
<?php if ($sana_object->objectID->Visible) { // objectID ?>
		<td<?php echo $sana_object->objectID->CellAttributes() ?>>
<span id="el<?php echo $sana_object_delete->RowCnt ?>_sana_object_objectID" class="sana_object_objectID">
<span<?php echo $sana_object->objectID->ViewAttributes() ?>>
<?php echo $sana_object->objectID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_object->objectName->Visible) { // objectName ?>
		<td<?php echo $sana_object->objectName->CellAttributes() ?>>
<span id="el<?php echo $sana_object_delete->RowCnt ?>_sana_object_objectName" class="sana_object_objectName">
<span<?php echo $sana_object->objectName->ViewAttributes() ?>>
<?php echo $sana_object->objectName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_object->ownerID->Visible) { // ownerID ?>
		<td<?php echo $sana_object->ownerID->CellAttributes() ?>>
<span id="el<?php echo $sana_object_delete->RowCnt ?>_sana_object_ownerID" class="sana_object_ownerID">
<span<?php echo $sana_object->ownerID->ViewAttributes() ?>>
<?php echo $sana_object->ownerID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_object->ownerName->Visible) { // ownerName ?>
		<td<?php echo $sana_object->ownerName->CellAttributes() ?>>
<span id="el<?php echo $sana_object_delete->RowCnt ?>_sana_object_ownerName" class="sana_object_ownerName">
<span<?php echo $sana_object->ownerName->ViewAttributes() ?>>
<?php echo $sana_object->ownerName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_object->lastName->Visible) { // lastName ?>
		<td<?php echo $sana_object->lastName->CellAttributes() ?>>
<span id="el<?php echo $sana_object_delete->RowCnt ?>_sana_object_lastName" class="sana_object_lastName">
<span<?php echo $sana_object->lastName->ViewAttributes() ?>>
<?php echo $sana_object->lastName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_object->mobilePhone->Visible) { // mobilePhone ?>
		<td<?php echo $sana_object->mobilePhone->CellAttributes() ?>>
<span id="el<?php echo $sana_object_delete->RowCnt ?>_sana_object_mobilePhone" class="sana_object_mobilePhone">
<span<?php echo $sana_object->mobilePhone->ViewAttributes() ?>>
<?php echo $sana_object->mobilePhone->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_object->color->Visible) { // color ?>
		<td<?php echo $sana_object->color->CellAttributes() ?>>
<span id="el<?php echo $sana_object_delete->RowCnt ?>_sana_object_color" class="sana_object_color">
<span<?php echo $sana_object->color->ViewAttributes() ?>>
<?php echo $sana_object->color->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_object->status->Visible) { // status ?>
		<td<?php echo $sana_object->status->CellAttributes() ?>>
<span id="el<?php echo $sana_object_delete->RowCnt ?>_sana_object_status" class="sana_object_status">
<span<?php echo $sana_object->status->ViewAttributes() ?>>
<?php echo $sana_object->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_object->content->Visible) { // content ?>
		<td<?php echo $sana_object->content->CellAttributes() ?>>
<span id="el<?php echo $sana_object_delete->RowCnt ?>_sana_object_content" class="sana_object_content">
<span<?php echo $sana_object->content->ViewAttributes() ?>>
<?php echo $sana_object->content->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_object->financialValue->Visible) { // financialValue ?>
		<td<?php echo $sana_object->financialValue->CellAttributes() ?>>
<span id="el<?php echo $sana_object_delete->RowCnt ?>_sana_object_financialValue" class="sana_object_financialValue">
<span<?php echo $sana_object->financialValue->ViewAttributes() ?>>
<?php echo $sana_object->financialValue->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_object->registrationUser->Visible) { // registrationUser ?>
		<td<?php echo $sana_object->registrationUser->CellAttributes() ?>>
<span id="el<?php echo $sana_object_delete->RowCnt ?>_sana_object_registrationUser" class="sana_object_registrationUser">
<span<?php echo $sana_object->registrationUser->ViewAttributes() ?>>
<?php echo $sana_object->registrationUser->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_object->registrationDateTime->Visible) { // registrationDateTime ?>
		<td<?php echo $sana_object->registrationDateTime->CellAttributes() ?>>
<span id="el<?php echo $sana_object_delete->RowCnt ?>_sana_object_registrationDateTime" class="sana_object_registrationDateTime">
<span<?php echo $sana_object->registrationDateTime->ViewAttributes() ?>>
<?php echo $sana_object->registrationDateTime->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_object->registrationStation->Visible) { // registrationStation ?>
		<td<?php echo $sana_object->registrationStation->CellAttributes() ?>>
<span id="el<?php echo $sana_object_delete->RowCnt ?>_sana_object_registrationStation" class="sana_object_registrationStation">
<span<?php echo $sana_object->registrationStation->ViewAttributes() ?>>
<?php echo $sana_object->registrationStation->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_object->isolatedDateTime->Visible) { // isolatedDateTime ?>
		<td<?php echo $sana_object->isolatedDateTime->CellAttributes() ?>>
<span id="el<?php echo $sana_object_delete->RowCnt ?>_sana_object_isolatedDateTime" class="sana_object_isolatedDateTime">
<span<?php echo $sana_object->isolatedDateTime->ViewAttributes() ?>>
<?php echo $sana_object->isolatedDateTime->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$sana_object_delete->Recordset->MoveNext();
}
$sana_object_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sana_object_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fsana_objectdelete.Init();
</script>
<?php
$sana_object_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_object_delete->Page_Terminate();
?>
