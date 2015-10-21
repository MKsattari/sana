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

$sana_message_list = NULL; // Initialize page object first

class csana_message_list extends csana_message {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_message';

	// Page object name
	var $PageObjName = 'sana_message_list';

	// Grid form hidden field names
	var $FormName = 'fsana_messagelist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "sana_messageadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "sana_messagedelete.php";
		$this->MultiUpdateUrl = "sana_messageupdate.php";

		// Table object (sana_person)
		if (!isset($GLOBALS['sana_person'])) $GLOBALS['sana_person'] = new csana_person();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sana_message', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fsana_messagelistsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
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

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 50;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore filter list
			$this->RestoreFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 50; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "sana_person") {
			global $sana_person;
			$rsmaster = $sana_person->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("sana_personlist.php"); // Return to master page
			} else {
				$sana_person->LoadListRowValues($rsmaster);
				$sana_person->RowType = EW_ROWTYPE_MASTER; // Master row
				$sana_person->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->messageID->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->messageID->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->messageID->AdvancedSearch->ToJSON(), ","); // Field messageID
		$sFilterList = ew_Concat($sFilterList, $this->personID->AdvancedSearch->ToJSON(), ","); // Field personID
		$sFilterList = ew_Concat($sFilterList, $this->_userID->AdvancedSearch->ToJSON(), ","); // Field userID
		$sFilterList = ew_Concat($sFilterList, $this->messageType->AdvancedSearch->ToJSON(), ","); // Field messageType
		$sFilterList = ew_Concat($sFilterList, $this->messageText->AdvancedSearch->ToJSON(), ","); // Field messageText
		$sFilterList = ew_Concat($sFilterList, $this->stationID->AdvancedSearch->ToJSON(), ","); // Field stationID
		$sFilterList = ew_Concat($sFilterList, $this->messageDateTime->AdvancedSearch->ToJSON(), ","); // Field messageDateTime
		$sFilterList = ew_Concat($sFilterList, $this->registrationUser->AdvancedSearch->ToJSON(), ","); // Field registrationUser
		$sFilterList = ew_Concat($sFilterList, $this->registrationDateTime->AdvancedSearch->ToJSON(), ","); // Field registrationDateTime
		$sFilterList = ew_Concat($sFilterList, $this->registrationStation->AdvancedSearch->ToJSON(), ","); // Field registrationStation
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}

		// Return filter list in json
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field messageID
		$this->messageID->AdvancedSearch->SearchValue = @$filter["x_messageID"];
		$this->messageID->AdvancedSearch->SearchOperator = @$filter["z_messageID"];
		$this->messageID->AdvancedSearch->SearchCondition = @$filter["v_messageID"];
		$this->messageID->AdvancedSearch->SearchValue2 = @$filter["y_messageID"];
		$this->messageID->AdvancedSearch->SearchOperator2 = @$filter["w_messageID"];
		$this->messageID->AdvancedSearch->Save();

		// Field personID
		$this->personID->AdvancedSearch->SearchValue = @$filter["x_personID"];
		$this->personID->AdvancedSearch->SearchOperator = @$filter["z_personID"];
		$this->personID->AdvancedSearch->SearchCondition = @$filter["v_personID"];
		$this->personID->AdvancedSearch->SearchValue2 = @$filter["y_personID"];
		$this->personID->AdvancedSearch->SearchOperator2 = @$filter["w_personID"];
		$this->personID->AdvancedSearch->Save();

		// Field userID
		$this->_userID->AdvancedSearch->SearchValue = @$filter["x__userID"];
		$this->_userID->AdvancedSearch->SearchOperator = @$filter["z__userID"];
		$this->_userID->AdvancedSearch->SearchCondition = @$filter["v__userID"];
		$this->_userID->AdvancedSearch->SearchValue2 = @$filter["y__userID"];
		$this->_userID->AdvancedSearch->SearchOperator2 = @$filter["w__userID"];
		$this->_userID->AdvancedSearch->Save();

		// Field messageType
		$this->messageType->AdvancedSearch->SearchValue = @$filter["x_messageType"];
		$this->messageType->AdvancedSearch->SearchOperator = @$filter["z_messageType"];
		$this->messageType->AdvancedSearch->SearchCondition = @$filter["v_messageType"];
		$this->messageType->AdvancedSearch->SearchValue2 = @$filter["y_messageType"];
		$this->messageType->AdvancedSearch->SearchOperator2 = @$filter["w_messageType"];
		$this->messageType->AdvancedSearch->Save();

		// Field messageText
		$this->messageText->AdvancedSearch->SearchValue = @$filter["x_messageText"];
		$this->messageText->AdvancedSearch->SearchOperator = @$filter["z_messageText"];
		$this->messageText->AdvancedSearch->SearchCondition = @$filter["v_messageText"];
		$this->messageText->AdvancedSearch->SearchValue2 = @$filter["y_messageText"];
		$this->messageText->AdvancedSearch->SearchOperator2 = @$filter["w_messageText"];
		$this->messageText->AdvancedSearch->Save();

		// Field stationID
		$this->stationID->AdvancedSearch->SearchValue = @$filter["x_stationID"];
		$this->stationID->AdvancedSearch->SearchOperator = @$filter["z_stationID"];
		$this->stationID->AdvancedSearch->SearchCondition = @$filter["v_stationID"];
		$this->stationID->AdvancedSearch->SearchValue2 = @$filter["y_stationID"];
		$this->stationID->AdvancedSearch->SearchOperator2 = @$filter["w_stationID"];
		$this->stationID->AdvancedSearch->Save();

		// Field messageDateTime
		$this->messageDateTime->AdvancedSearch->SearchValue = @$filter["x_messageDateTime"];
		$this->messageDateTime->AdvancedSearch->SearchOperator = @$filter["z_messageDateTime"];
		$this->messageDateTime->AdvancedSearch->SearchCondition = @$filter["v_messageDateTime"];
		$this->messageDateTime->AdvancedSearch->SearchValue2 = @$filter["y_messageDateTime"];
		$this->messageDateTime->AdvancedSearch->SearchOperator2 = @$filter["w_messageDateTime"];
		$this->messageDateTime->AdvancedSearch->Save();

		// Field registrationUser
		$this->registrationUser->AdvancedSearch->SearchValue = @$filter["x_registrationUser"];
		$this->registrationUser->AdvancedSearch->SearchOperator = @$filter["z_registrationUser"];
		$this->registrationUser->AdvancedSearch->SearchCondition = @$filter["v_registrationUser"];
		$this->registrationUser->AdvancedSearch->SearchValue2 = @$filter["y_registrationUser"];
		$this->registrationUser->AdvancedSearch->SearchOperator2 = @$filter["w_registrationUser"];
		$this->registrationUser->AdvancedSearch->Save();

		// Field registrationDateTime
		$this->registrationDateTime->AdvancedSearch->SearchValue = @$filter["x_registrationDateTime"];
		$this->registrationDateTime->AdvancedSearch->SearchOperator = @$filter["z_registrationDateTime"];
		$this->registrationDateTime->AdvancedSearch->SearchCondition = @$filter["v_registrationDateTime"];
		$this->registrationDateTime->AdvancedSearch->SearchValue2 = @$filter["y_registrationDateTime"];
		$this->registrationDateTime->AdvancedSearch->SearchOperator2 = @$filter["w_registrationDateTime"];
		$this->registrationDateTime->AdvancedSearch->Save();

		// Field registrationStation
		$this->registrationStation->AdvancedSearch->SearchValue = @$filter["x_registrationStation"];
		$this->registrationStation->AdvancedSearch->SearchOperator = @$filter["z_registrationStation"];
		$this->registrationStation->AdvancedSearch->SearchCondition = @$filter["v_registrationStation"];
		$this->registrationStation->AdvancedSearch->SearchValue2 = @$filter["y_registrationStation"];
		$this->registrationStation->AdvancedSearch->SearchOperator2 = @$filter["w_registrationStation"];
		$this->registrationStation->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->messageType, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->messageText, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual && $Fld->FldVirtualSearch) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->messageID); // messageID
			$this->UpdateSort($this->personID); // personID
			$this->UpdateSort($this->_userID); // userID
			$this->UpdateSort($this->messageType); // messageType
			$this->UpdateSort($this->messageText); // messageText
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->personID->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->messageID->setSort("");
				$this->personID->setSort("");
				$this->_userID->setSort("");
				$this->messageType->setSort("");
				$this->messageText->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt) {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->messageID->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "");
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fsana_messagelistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fsana_messagelistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fsana_messagelist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fsana_messagelistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("messageID")) <> "")
			$this->messageID->CurrentValue = $this->getKey("messageID"); // messageID
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

			// Update URL
			$this->AddUrl = $this->AddMasterUrl($this->AddUrl);
			$this->InlineAddUrl = $this->AddMasterUrl($this->InlineAddUrl);
			$this->GridAddUrl = $this->AddMasterUrl($this->GridAddUrl);
			$this->GridEditUrl = $this->AddMasterUrl($this->GridEditUrl);

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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($sana_message_list)) $sana_message_list = new csana_message_list();

// Page init
$sana_message_list->Page_Init();

// Page main
$sana_message_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_message_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fsana_messagelist = new ew_Form("fsana_messagelist", "list");
fsana_messagelist.FormKeyCountName = '<?php echo $sana_message_list->FormKeyCountName ?>';

// Form_CustomValidate event
fsana_messagelist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_messagelist.ValidateRequired = true;
<?php } else { ?>
fsana_messagelist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsana_messagelist.Lists["x_personID"] = {"LinkField":"x_personID","Ajax":true,"AutoFill":false,"DisplayFields":["x_personName","x_lastName","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fsana_messagelist.Lists["x__userID"] = {"LinkField":"x__userID","Ajax":true,"AutoFill":false,"DisplayFields":["x_personName","x_lastName","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
var CurrentSearchForm = fsana_messagelistsrch = new ew_Form("fsana_messagelistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($sana_message_list->TotalRecs > 0 && $sana_message_list->ExportOptions->Visible()) { ?>
<?php $sana_message_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($sana_message_list->SearchOptions->Visible()) { ?>
<?php $sana_message_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($sana_message_list->FilterOptions->Visible()) { ?>
<?php $sana_message_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php if (($sana_message->Export == "") || (EW_EXPORT_MASTER_RECORD && $sana_message->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "sana_personlist.php";
if ($sana_message_list->DbMasterFilter <> "" && $sana_message->getCurrentMasterTable() == "sana_person") {
	if ($sana_message_list->MasterRecordExists) {
		if ($sana_message->getCurrentMasterTable() == $sana_message->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php include_once "sana_personmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = $sana_message_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($sana_message_list->TotalRecs <= 0)
			$sana_message_list->TotalRecs = $sana_message->SelectRecordCount();
	} else {
		if (!$sana_message_list->Recordset && ($sana_message_list->Recordset = $sana_message_list->LoadRecordset()))
			$sana_message_list->TotalRecs = $sana_message_list->Recordset->RecordCount();
	}
	$sana_message_list->StartRec = 1;
	if ($sana_message_list->DisplayRecs <= 0 || ($sana_message->Export <> "" && $sana_message->ExportAll)) // Display all records
		$sana_message_list->DisplayRecs = $sana_message_list->TotalRecs;
	if (!($sana_message->Export <> "" && $sana_message->ExportAll))
		$sana_message_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$sana_message_list->Recordset = $sana_message_list->LoadRecordset($sana_message_list->StartRec-1, $sana_message_list->DisplayRecs);

	// Set no record found message
	if ($sana_message->CurrentAction == "" && $sana_message_list->TotalRecs == 0) {
		if ($sana_message_list->SearchWhere == "0=101")
			$sana_message_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$sana_message_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$sana_message_list->RenderOtherOptions();
?>
<?php if ($sana_message->Export == "" && $sana_message->CurrentAction == "") { ?>
<form name="fsana_messagelistsrch" id="fsana_messagelistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($sana_message_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fsana_messagelistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="sana_message">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($sana_message_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($sana_message_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $sana_message_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($sana_message_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($sana_message_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($sana_message_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($sana_message_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $sana_message_list->ShowPageHeader(); ?>
<?php
$sana_message_list->ShowMessage();
?>
<?php if ($sana_message_list->TotalRecs > 0 || $sana_message->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fsana_messagelist" id="fsana_messagelist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_message_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_message_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_message">
<?php if ($sana_message->getCurrentMasterTable() == "sana_person" && $sana_message->CurrentAction <> "") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="sana_person">
<input type="hidden" name="fk_personID" value="<?php echo $sana_message->personID->getSessionValue() ?>">
<?php } ?>
<div id="gmp_sana_message" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($sana_message_list->TotalRecs > 0) { ?>
<table id="tbl_sana_messagelist" class="table ewTable">
<?php echo $sana_message->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$sana_message_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$sana_message_list->RenderListOptions();

// Render list options (header, left)
$sana_message_list->ListOptions->Render("header", "left");
?>
<?php if ($sana_message->messageID->Visible) { // messageID ?>
	<?php if ($sana_message->SortUrl($sana_message->messageID) == "") { ?>
		<th data-name="messageID"><div id="elh_sana_message_messageID" class="sana_message_messageID"><div class="ewTableHeaderCaption"><?php echo $sana_message->messageID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="messageID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_message->SortUrl($sana_message->messageID) ?>',1);"><div id="elh_sana_message_messageID" class="sana_message_messageID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_message->messageID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sana_message->messageID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_message->messageID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_message->personID->Visible) { // personID ?>
	<?php if ($sana_message->SortUrl($sana_message->personID) == "") { ?>
		<th data-name="personID"><div id="elh_sana_message_personID" class="sana_message_personID"><div class="ewTableHeaderCaption"><?php echo $sana_message->personID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="personID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_message->SortUrl($sana_message->personID) ?>',1);"><div id="elh_sana_message_personID" class="sana_message_personID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_message->personID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sana_message->personID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_message->personID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_message->_userID->Visible) { // userID ?>
	<?php if ($sana_message->SortUrl($sana_message->_userID) == "") { ?>
		<th data-name="_userID"><div id="elh_sana_message__userID" class="sana_message__userID"><div class="ewTableHeaderCaption"><?php echo $sana_message->_userID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_userID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_message->SortUrl($sana_message->_userID) ?>',1);"><div id="elh_sana_message__userID" class="sana_message__userID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_message->_userID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sana_message->_userID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_message->_userID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_message->messageType->Visible) { // messageType ?>
	<?php if ($sana_message->SortUrl($sana_message->messageType) == "") { ?>
		<th data-name="messageType"><div id="elh_sana_message_messageType" class="sana_message_messageType"><div class="ewTableHeaderCaption"><?php echo $sana_message->messageType->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="messageType"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_message->SortUrl($sana_message->messageType) ?>',1);"><div id="elh_sana_message_messageType" class="sana_message_messageType">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_message->messageType->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_message->messageType->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_message->messageType->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_message->messageText->Visible) { // messageText ?>
	<?php if ($sana_message->SortUrl($sana_message->messageText) == "") { ?>
		<th data-name="messageText"><div id="elh_sana_message_messageText" class="sana_message_messageText"><div class="ewTableHeaderCaption"><?php echo $sana_message->messageText->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="messageText"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_message->SortUrl($sana_message->messageText) ?>',1);"><div id="elh_sana_message_messageText" class="sana_message_messageText">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_message->messageText->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_message->messageText->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_message->messageText->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$sana_message_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($sana_message->ExportAll && $sana_message->Export <> "") {
	$sana_message_list->StopRec = $sana_message_list->TotalRecs;
} else {

	// Set the last record to display
	if ($sana_message_list->TotalRecs > $sana_message_list->StartRec + $sana_message_list->DisplayRecs - 1)
		$sana_message_list->StopRec = $sana_message_list->StartRec + $sana_message_list->DisplayRecs - 1;
	else
		$sana_message_list->StopRec = $sana_message_list->TotalRecs;
}
$sana_message_list->RecCnt = $sana_message_list->StartRec - 1;
if ($sana_message_list->Recordset && !$sana_message_list->Recordset->EOF) {
	$sana_message_list->Recordset->MoveFirst();
	$bSelectLimit = $sana_message_list->UseSelectLimit;
	if (!$bSelectLimit && $sana_message_list->StartRec > 1)
		$sana_message_list->Recordset->Move($sana_message_list->StartRec - 1);
} elseif (!$sana_message->AllowAddDeleteRow && $sana_message_list->StopRec == 0) {
	$sana_message_list->StopRec = $sana_message->GridAddRowCount;
}

// Initialize aggregate
$sana_message->RowType = EW_ROWTYPE_AGGREGATEINIT;
$sana_message->ResetAttrs();
$sana_message_list->RenderRow();
while ($sana_message_list->RecCnt < $sana_message_list->StopRec) {
	$sana_message_list->RecCnt++;
	if (intval($sana_message_list->RecCnt) >= intval($sana_message_list->StartRec)) {
		$sana_message_list->RowCnt++;

		// Set up key count
		$sana_message_list->KeyCount = $sana_message_list->RowIndex;

		// Init row class and style
		$sana_message->ResetAttrs();
		$sana_message->CssClass = "";
		if ($sana_message->CurrentAction == "gridadd") {
		} else {
			$sana_message_list->LoadRowValues($sana_message_list->Recordset); // Load row values
		}
		$sana_message->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$sana_message->RowAttrs = array_merge($sana_message->RowAttrs, array('data-rowindex'=>$sana_message_list->RowCnt, 'id'=>'r' . $sana_message_list->RowCnt . '_sana_message', 'data-rowtype'=>$sana_message->RowType));

		// Render row
		$sana_message_list->RenderRow();

		// Render list options
		$sana_message_list->RenderListOptions();
?>
	<tr<?php echo $sana_message->RowAttributes() ?>>
<?php

// Render list options (body, left)
$sana_message_list->ListOptions->Render("body", "left", $sana_message_list->RowCnt);
?>
	<?php if ($sana_message->messageID->Visible) { // messageID ?>
		<td data-name="messageID"<?php echo $sana_message->messageID->CellAttributes() ?>>
<span id="el<?php echo $sana_message_list->RowCnt ?>_sana_message_messageID" class="sana_message_messageID">
<span<?php echo $sana_message->messageID->ViewAttributes() ?>>
<?php echo $sana_message->messageID->ListViewValue() ?></span>
</span>
<a id="<?php echo $sana_message_list->PageObjName . "_row_" . $sana_message_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($sana_message->personID->Visible) { // personID ?>
		<td data-name="personID"<?php echo $sana_message->personID->CellAttributes() ?>>
<span id="el<?php echo $sana_message_list->RowCnt ?>_sana_message_personID" class="sana_message_personID">
<span<?php echo $sana_message->personID->ViewAttributes() ?>>
<?php echo $sana_message->personID->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_message->_userID->Visible) { // userID ?>
		<td data-name="_userID"<?php echo $sana_message->_userID->CellAttributes() ?>>
<span id="el<?php echo $sana_message_list->RowCnt ?>_sana_message__userID" class="sana_message__userID">
<span<?php echo $sana_message->_userID->ViewAttributes() ?>>
<?php echo $sana_message->_userID->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_message->messageType->Visible) { // messageType ?>
		<td data-name="messageType"<?php echo $sana_message->messageType->CellAttributes() ?>>
<span id="el<?php echo $sana_message_list->RowCnt ?>_sana_message_messageType" class="sana_message_messageType">
<span<?php echo $sana_message->messageType->ViewAttributes() ?>>
<?php echo $sana_message->messageType->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_message->messageText->Visible) { // messageText ?>
		<td data-name="messageText"<?php echo $sana_message->messageText->CellAttributes() ?>>
<span id="el<?php echo $sana_message_list->RowCnt ?>_sana_message_messageText" class="sana_message_messageText">
<span<?php echo $sana_message->messageText->ViewAttributes() ?>>
<?php echo $sana_message->messageText->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$sana_message_list->ListOptions->Render("body", "right", $sana_message_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($sana_message->CurrentAction <> "gridadd")
		$sana_message_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($sana_message->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($sana_message_list->Recordset)
	$sana_message_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($sana_message->CurrentAction <> "gridadd" && $sana_message->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($sana_message_list->Pager)) $sana_message_list->Pager = new cNumericPager($sana_message_list->StartRec, $sana_message_list->DisplayRecs, $sana_message_list->TotalRecs, $sana_message_list->RecRange) ?>
<?php if ($sana_message_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($sana_message_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $sana_message_list->PageUrl() ?>start=<?php echo $sana_message_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($sana_message_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $sana_message_list->PageUrl() ?>start=<?php echo $sana_message_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($sana_message_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $sana_message_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($sana_message_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $sana_message_list->PageUrl() ?>start=<?php echo $sana_message_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($sana_message_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $sana_message_list->PageUrl() ?>start=<?php echo $sana_message_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $sana_message_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $sana_message_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $sana_message_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($sana_message_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($sana_message_list->TotalRecs == 0 && $sana_message->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($sana_message_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fsana_messagelistsrch.Init();
fsana_messagelistsrch.FilterList = <?php echo $sana_message_list->GetFilterList() ?>;
fsana_messagelist.Init();
</script>
<?php
$sana_message_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_message_list->Page_Terminate();
?>
