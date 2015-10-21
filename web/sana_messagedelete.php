<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "sana_messageinfo.php" ?>
<?php include_once "sana_personinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$sana_message_delete = NULL; // Initialize page object first

class csana_message_delete extends csana_message {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_message';

	// Page object name
	var $PageObjName = 'sana_message_delete';

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

		// Table object (sana_message)
		if (!isset($GLOBALS["sana_message"]) || get_class($GLOBALS["sana_message"]) == "csana_message") {
			$GLOBALS["sana_message"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_message"];
		}

		// Table object (sana_person)
		if (!isset($GLOBALS['sana_person'])) $GLOBALS['sana_person'] = new csana_person();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sana_message', TRUE);

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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->messageID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $sana_message;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($sana_message);
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

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("sana_messagelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in sana_message class, sana_messageinfo.php

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
		$this->messageID->setDbValue($rs->fields('messageID'));
		$this->personID->setDbValue($rs->fields('personID'));
		$this->_userID->setDbValue($rs->fields('userID'));
		$this->messageType->setDbValue($rs->fields('messageType'));
		$this->messageText->setDbValue($rs->fields('messageText'));
		$this->stationID->setDbValue($rs->fields('stationID'));
		$this->messageDateTime->setDbValue($rs->fields('messageDateTime'));
		$this->registrationUser->setDbValue($rs->fields('registrationUser'));
		$this->registrationDateTime->setDbValue($rs->fields('registrationDateTime'));
		$this->registrationStation->setDbValue($rs->fields('registrationStation'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->messageID->DbValue = $row['messageID'];
		$this->personID->DbValue = $row['personID'];
		$this->_userID->DbValue = $row['userID'];
		$this->messageType->DbValue = $row['messageType'];
		$this->messageText->DbValue = $row['messageText'];
		$this->stationID->DbValue = $row['stationID'];
		$this->messageDateTime->DbValue = $row['messageDateTime'];
		$this->registrationUser->DbValue = $row['registrationUser'];
		$this->registrationDateTime->DbValue = $row['registrationDateTime'];
		$this->registrationStation->DbValue = $row['registrationStation'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// messageID
		// personID
		// userID
		// messageType
		// messageText
		// stationID
		// messageDateTime
		// registrationUser
		// registrationDateTime
		// registrationStation

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// messageID
		$this->messageID->ViewValue = $this->messageID->CurrentValue;
		$this->messageID->ViewCustomAttributes = "";

		// personID
		$this->personID->ViewValue = $this->personID->CurrentValue;
		if (strval($this->personID->CurrentValue) <> "") {
			$sFilterWrk = "`personID`" . ew_SearchString("=", $this->personID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_person`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_person`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_person`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->personID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->personID->ViewValue = $this->personID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->personID->ViewValue = $this->personID->CurrentValue;
			}
		} else {
			$this->personID->ViewValue = NULL;
		}
		$this->personID->ViewCustomAttributes = "";

		// userID
		$this->_userID->ViewValue = $this->_userID->CurrentValue;
		if (strval($this->_userID->CurrentValue) <> "") {
			$sFilterWrk = "`userID`" . ew_SearchString("=", $this->_userID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_user`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_user`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_user`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->_userID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->_userID->ViewValue = $this->_userID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->_userID->ViewValue = $this->_userID->CurrentValue;
			}
		} else {
			$this->_userID->ViewValue = NULL;
		}
		$this->_userID->ViewCustomAttributes = "";

		// messageType
		$this->messageType->ViewValue = $this->messageType->CurrentValue;
		$this->messageType->ViewCustomAttributes = "";

		// messageText
		$this->messageText->ViewValue = $this->messageText->CurrentValue;
		$this->messageText->ViewCustomAttributes = "";

		// stationID
		$this->stationID->ViewValue = $this->stationID->CurrentValue;
		if (strval($this->stationID->CurrentValue) <> "") {
			$sFilterWrk = "`stationID`" . ew_SearchString("=", $this->stationID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `stationID`, `stationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_station`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `stationID`, `stationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_station`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `stationID`, `stationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_station`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->stationID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->stationID->ViewValue = $this->stationID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->stationID->ViewValue = $this->stationID->CurrentValue;
			}
		} else {
			$this->stationID->ViewValue = NULL;
		}
		$this->stationID->ViewCustomAttributes = "";

		// messageDateTime
		$this->messageDateTime->ViewValue = $this->messageDateTime->CurrentValue;
		$this->messageDateTime->ViewValue = ew_FormatDateTime($this->messageDateTime->ViewValue, 5);
		$this->messageDateTime->ViewCustomAttributes = "";

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

			// messageID
			$this->messageID->LinkCustomAttributes = "";
			$this->messageID->HrefValue = "";
			$this->messageID->TooltipValue = "";

			// personID
			$this->personID->LinkCustomAttributes = "";
			$this->personID->HrefValue = "";
			$this->personID->TooltipValue = "";

			// userID
			$this->_userID->LinkCustomAttributes = "";
			$this->_userID->HrefValue = "";
			$this->_userID->TooltipValue = "";

			// messageType
			$this->messageType->LinkCustomAttributes = "";
			$this->messageType->HrefValue = "";
			$this->messageType->TooltipValue = "";

			// messageText
			$this->messageText->LinkCustomAttributes = "";
			$this->messageText->HrefValue = "";
			$this->messageText->TooltipValue = "";
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
				$sThisKey .= $row['messageID'];
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

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "sana_person") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_personID"] <> "") {
					$GLOBALS["sana_person"]->personID->setQueryStringValue($_GET["fk_personID"]);
					$this->personID->setQueryStringValue($GLOBALS["sana_person"]->personID->QueryStringValue);
					$this->personID->setSessionValue($this->personID->QueryStringValue);
					if (!is_numeric($GLOBALS["sana_person"]->personID->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "sana_person") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_personID"] <> "") {
					$GLOBALS["sana_person"]->personID->setFormValue($_POST["fk_personID"]);
					$this->personID->setFormValue($GLOBALS["sana_person"]->personID->FormValue);
					$this->personID->setSessionValue($this->personID->FormValue);
					if (!is_numeric($GLOBALS["sana_person"]->personID->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "sana_person") {
				if ($this->personID->CurrentValue == "") $this->personID->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sana_messagelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($sana_message_delete)) $sana_message_delete = new csana_message_delete();

// Page init
$sana_message_delete->Page_Init();

// Page main
$sana_message_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_message_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fsana_messagedelete = new ew_Form("fsana_messagedelete", "delete");

// Form_CustomValidate event
fsana_messagedelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_messagedelete.ValidateRequired = true;
<?php } else { ?>
fsana_messagedelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsana_messagedelete.Lists["x_personID"] = {"LinkField":"x_personID","Ajax":true,"AutoFill":false,"DisplayFields":["x_personName","x_lastName","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fsana_messagedelete.Lists["x__userID"] = {"LinkField":"x__userID","Ajax":true,"AutoFill":false,"DisplayFields":["x_personName","x_lastName","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($sana_message_delete->Recordset = $sana_message_delete->LoadRecordset())
	$sana_message_deleteTotalRecs = $sana_message_delete->Recordset->RecordCount(); // Get record count
if ($sana_message_deleteTotalRecs <= 0) { // No record found, exit
	if ($sana_message_delete->Recordset)
		$sana_message_delete->Recordset->Close();
	$sana_message_delete->Page_Terminate("sana_messagelist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $sana_message_delete->ShowPageHeader(); ?>
<?php
$sana_message_delete->ShowMessage();
?>
<form name="fsana_messagedelete" id="fsana_messagedelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_message_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_message_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_message">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($sana_message_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $sana_message->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($sana_message->messageID->Visible) { // messageID ?>
		<th><span id="elh_sana_message_messageID" class="sana_message_messageID"><?php echo $sana_message->messageID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_message->personID->Visible) { // personID ?>
		<th><span id="elh_sana_message_personID" class="sana_message_personID"><?php echo $sana_message->personID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_message->_userID->Visible) { // userID ?>
		<th><span id="elh_sana_message__userID" class="sana_message__userID"><?php echo $sana_message->_userID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_message->messageType->Visible) { // messageType ?>
		<th><span id="elh_sana_message_messageType" class="sana_message_messageType"><?php echo $sana_message->messageType->FldCaption() ?></span></th>
<?php } ?>
<?php if ($sana_message->messageText->Visible) { // messageText ?>
		<th><span id="elh_sana_message_messageText" class="sana_message_messageText"><?php echo $sana_message->messageText->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$sana_message_delete->RecCnt = 0;
$i = 0;
while (!$sana_message_delete->Recordset->EOF) {
	$sana_message_delete->RecCnt++;
	$sana_message_delete->RowCnt++;

	// Set row properties
	$sana_message->ResetAttrs();
	$sana_message->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$sana_message_delete->LoadRowValues($sana_message_delete->Recordset);

	// Render row
	$sana_message_delete->RenderRow();
?>
	<tr<?php echo $sana_message->RowAttributes() ?>>
<?php if ($sana_message->messageID->Visible) { // messageID ?>
		<td<?php echo $sana_message->messageID->CellAttributes() ?>>
<span id="el<?php echo $sana_message_delete->RowCnt ?>_sana_message_messageID" class="sana_message_messageID">
<span<?php echo $sana_message->messageID->ViewAttributes() ?>>
<?php echo $sana_message->messageID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_message->personID->Visible) { // personID ?>
		<td<?php echo $sana_message->personID->CellAttributes() ?>>
<span id="el<?php echo $sana_message_delete->RowCnt ?>_sana_message_personID" class="sana_message_personID">
<span<?php echo $sana_message->personID->ViewAttributes() ?>>
<?php echo $sana_message->personID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_message->_userID->Visible) { // userID ?>
		<td<?php echo $sana_message->_userID->CellAttributes() ?>>
<span id="el<?php echo $sana_message_delete->RowCnt ?>_sana_message__userID" class="sana_message__userID">
<span<?php echo $sana_message->_userID->ViewAttributes() ?>>
<?php echo $sana_message->_userID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_message->messageType->Visible) { // messageType ?>
		<td<?php echo $sana_message->messageType->CellAttributes() ?>>
<span id="el<?php echo $sana_message_delete->RowCnt ?>_sana_message_messageType" class="sana_message_messageType">
<span<?php echo $sana_message->messageType->ViewAttributes() ?>>
<?php echo $sana_message->messageType->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($sana_message->messageText->Visible) { // messageText ?>
		<td<?php echo $sana_message->messageText->CellAttributes() ?>>
<span id="el<?php echo $sana_message_delete->RowCnt ?>_sana_message_messageText" class="sana_message_messageText">
<span<?php echo $sana_message->messageText->ViewAttributes() ?>>
<?php echo $sana_message->messageText->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$sana_message_delete->Recordset->MoveNext();
}
$sana_message_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sana_message_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fsana_messagedelete.Init();
</script>
<?php
$sana_message_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_message_delete->Page_Terminate();
?>
