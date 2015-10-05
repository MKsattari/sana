<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "sana_objectinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$sana_object_list = NULL; // Initialize page object first

class csana_object_list extends csana_object {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_object';

	// Page object name
	var $PageObjName = 'sana_object_list';

	// Grid form hidden field names
	var $FormName = 'fsana_objectlist';
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

		// Table object (sana_object)
		if (!isset($GLOBALS["sana_object"]) || get_class($GLOBALS["sana_object"]) == "csana_object") {
			$GLOBALS["sana_object"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_object"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "sana_objectadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "sana_objectdelete.php";
		$this->MultiUpdateUrl = "sana_objectupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sana_object', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fsana_objectlistsrch";

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
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

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
			$this->objectID->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->objectID->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->objectID->AdvancedSearch->ToJSON(), ","); // Field objectID
		$sFilterList = ew_Concat($sFilterList, $this->objectName->AdvancedSearch->ToJSON(), ","); // Field objectName
		$sFilterList = ew_Concat($sFilterList, $this->ownerID->AdvancedSearch->ToJSON(), ","); // Field ownerID
		$sFilterList = ew_Concat($sFilterList, $this->ownerName->AdvancedSearch->ToJSON(), ","); // Field ownerName
		$sFilterList = ew_Concat($sFilterList, $this->lastName->AdvancedSearch->ToJSON(), ","); // Field lastName
		$sFilterList = ew_Concat($sFilterList, $this->mobilePhone->AdvancedSearch->ToJSON(), ","); // Field mobilePhone
		$sFilterList = ew_Concat($sFilterList, $this->color->AdvancedSearch->ToJSON(), ","); // Field color
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJSON(), ","); // Field status
		$sFilterList = ew_Concat($sFilterList, $this->content->AdvancedSearch->ToJSON(), ","); // Field content
		$sFilterList = ew_Concat($sFilterList, $this->financialValue->AdvancedSearch->ToJSON(), ","); // Field financialValue
		$sFilterList = ew_Concat($sFilterList, $this->registrationUser->AdvancedSearch->ToJSON(), ","); // Field registrationUser
		$sFilterList = ew_Concat($sFilterList, $this->registrationDateTime->AdvancedSearch->ToJSON(), ","); // Field registrationDateTime
		$sFilterList = ew_Concat($sFilterList, $this->registrationStation->AdvancedSearch->ToJSON(), ","); // Field registrationStation
		$sFilterList = ew_Concat($sFilterList, $this->isolatedDateTime->AdvancedSearch->ToJSON(), ","); // Field isolatedDateTime
		$sFilterList = ew_Concat($sFilterList, $this->description->AdvancedSearch->ToJSON(), ","); // Field description
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

		// Field objectID
		$this->objectID->AdvancedSearch->SearchValue = @$filter["x_objectID"];
		$this->objectID->AdvancedSearch->SearchOperator = @$filter["z_objectID"];
		$this->objectID->AdvancedSearch->SearchCondition = @$filter["v_objectID"];
		$this->objectID->AdvancedSearch->SearchValue2 = @$filter["y_objectID"];
		$this->objectID->AdvancedSearch->SearchOperator2 = @$filter["w_objectID"];
		$this->objectID->AdvancedSearch->Save();

		// Field objectName
		$this->objectName->AdvancedSearch->SearchValue = @$filter["x_objectName"];
		$this->objectName->AdvancedSearch->SearchOperator = @$filter["z_objectName"];
		$this->objectName->AdvancedSearch->SearchCondition = @$filter["v_objectName"];
		$this->objectName->AdvancedSearch->SearchValue2 = @$filter["y_objectName"];
		$this->objectName->AdvancedSearch->SearchOperator2 = @$filter["w_objectName"];
		$this->objectName->AdvancedSearch->Save();

		// Field ownerID
		$this->ownerID->AdvancedSearch->SearchValue = @$filter["x_ownerID"];
		$this->ownerID->AdvancedSearch->SearchOperator = @$filter["z_ownerID"];
		$this->ownerID->AdvancedSearch->SearchCondition = @$filter["v_ownerID"];
		$this->ownerID->AdvancedSearch->SearchValue2 = @$filter["y_ownerID"];
		$this->ownerID->AdvancedSearch->SearchOperator2 = @$filter["w_ownerID"];
		$this->ownerID->AdvancedSearch->Save();

		// Field ownerName
		$this->ownerName->AdvancedSearch->SearchValue = @$filter["x_ownerName"];
		$this->ownerName->AdvancedSearch->SearchOperator = @$filter["z_ownerName"];
		$this->ownerName->AdvancedSearch->SearchCondition = @$filter["v_ownerName"];
		$this->ownerName->AdvancedSearch->SearchValue2 = @$filter["y_ownerName"];
		$this->ownerName->AdvancedSearch->SearchOperator2 = @$filter["w_ownerName"];
		$this->ownerName->AdvancedSearch->Save();

		// Field lastName
		$this->lastName->AdvancedSearch->SearchValue = @$filter["x_lastName"];
		$this->lastName->AdvancedSearch->SearchOperator = @$filter["z_lastName"];
		$this->lastName->AdvancedSearch->SearchCondition = @$filter["v_lastName"];
		$this->lastName->AdvancedSearch->SearchValue2 = @$filter["y_lastName"];
		$this->lastName->AdvancedSearch->SearchOperator2 = @$filter["w_lastName"];
		$this->lastName->AdvancedSearch->Save();

		// Field mobilePhone
		$this->mobilePhone->AdvancedSearch->SearchValue = @$filter["x_mobilePhone"];
		$this->mobilePhone->AdvancedSearch->SearchOperator = @$filter["z_mobilePhone"];
		$this->mobilePhone->AdvancedSearch->SearchCondition = @$filter["v_mobilePhone"];
		$this->mobilePhone->AdvancedSearch->SearchValue2 = @$filter["y_mobilePhone"];
		$this->mobilePhone->AdvancedSearch->SearchOperator2 = @$filter["w_mobilePhone"];
		$this->mobilePhone->AdvancedSearch->Save();

		// Field color
		$this->color->AdvancedSearch->SearchValue = @$filter["x_color"];
		$this->color->AdvancedSearch->SearchOperator = @$filter["z_color"];
		$this->color->AdvancedSearch->SearchCondition = @$filter["v_color"];
		$this->color->AdvancedSearch->SearchValue2 = @$filter["y_color"];
		$this->color->AdvancedSearch->SearchOperator2 = @$filter["w_color"];
		$this->color->AdvancedSearch->Save();

		// Field status
		$this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
		$this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
		$this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
		$this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
		$this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
		$this->status->AdvancedSearch->Save();

		// Field content
		$this->content->AdvancedSearch->SearchValue = @$filter["x_content"];
		$this->content->AdvancedSearch->SearchOperator = @$filter["z_content"];
		$this->content->AdvancedSearch->SearchCondition = @$filter["v_content"];
		$this->content->AdvancedSearch->SearchValue2 = @$filter["y_content"];
		$this->content->AdvancedSearch->SearchOperator2 = @$filter["w_content"];
		$this->content->AdvancedSearch->Save();

		// Field financialValue
		$this->financialValue->AdvancedSearch->SearchValue = @$filter["x_financialValue"];
		$this->financialValue->AdvancedSearch->SearchOperator = @$filter["z_financialValue"];
		$this->financialValue->AdvancedSearch->SearchCondition = @$filter["v_financialValue"];
		$this->financialValue->AdvancedSearch->SearchValue2 = @$filter["y_financialValue"];
		$this->financialValue->AdvancedSearch->SearchOperator2 = @$filter["w_financialValue"];
		$this->financialValue->AdvancedSearch->Save();

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

		// Field isolatedDateTime
		$this->isolatedDateTime->AdvancedSearch->SearchValue = @$filter["x_isolatedDateTime"];
		$this->isolatedDateTime->AdvancedSearch->SearchOperator = @$filter["z_isolatedDateTime"];
		$this->isolatedDateTime->AdvancedSearch->SearchCondition = @$filter["v_isolatedDateTime"];
		$this->isolatedDateTime->AdvancedSearch->SearchValue2 = @$filter["y_isolatedDateTime"];
		$this->isolatedDateTime->AdvancedSearch->SearchOperator2 = @$filter["w_isolatedDateTime"];
		$this->isolatedDateTime->AdvancedSearch->Save();

		// Field description
		$this->description->AdvancedSearch->SearchValue = @$filter["x_description"];
		$this->description->AdvancedSearch->SearchOperator = @$filter["z_description"];
		$this->description->AdvancedSearch->SearchCondition = @$filter["v_description"];
		$this->description->AdvancedSearch->SearchValue2 = @$filter["y_description"];
		$this->description->AdvancedSearch->SearchOperator2 = @$filter["w_description"];
		$this->description->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->objectName, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->ownerName, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->lastName, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mobilePhone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->color, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->status, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->content, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->financialValue, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->description, $arKeywords, $type);
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
			$this->UpdateSort($this->objectID); // objectID
			$this->UpdateSort($this->objectName); // objectName
			$this->UpdateSort($this->ownerID); // ownerID
			$this->UpdateSort($this->ownerName); // ownerName
			$this->UpdateSort($this->lastName); // lastName
			$this->UpdateSort($this->mobilePhone); // mobilePhone
			$this->UpdateSort($this->color); // color
			$this->UpdateSort($this->status); // status
			$this->UpdateSort($this->content); // content
			$this->UpdateSort($this->financialValue); // financialValue
			$this->UpdateSort($this->registrationUser); // registrationUser
			$this->UpdateSort($this->registrationDateTime); // registrationDateTime
			$this->UpdateSort($this->registrationStation); // registrationStation
			$this->UpdateSort($this->isolatedDateTime); // isolatedDateTime
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

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->objectID->setSort("");
				$this->objectName->setSort("");
				$this->ownerID->setSort("");
				$this->ownerName->setSort("");
				$this->lastName->setSort("");
				$this->mobilePhone->setSort("");
				$this->color->setSort("");
				$this->status->setSort("");
				$this->content->setSort("");
				$this->financialValue->setSort("");
				$this->registrationUser->setSort("");
				$this->registrationDateTime->setSort("");
				$this->registrationStation->setSort("");
				$this->isolatedDateTime->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->objectID->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fsana_objectlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fsana_objectlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fsana_objectlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fsana_objectlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("objectID")) <> "")
			$this->objectID->CurrentValue = $this->getKey("objectID"); // objectID
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
if (!isset($sana_object_list)) $sana_object_list = new csana_object_list();

// Page init
$sana_object_list->Page_Init();

// Page main
$sana_object_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_object_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fsana_objectlist = new ew_Form("fsana_objectlist", "list");
fsana_objectlist.FormKeyCountName = '<?php echo $sana_object_list->FormKeyCountName ?>';

// Form_CustomValidate event
fsana_objectlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_objectlist.ValidateRequired = true;
<?php } else { ?>
fsana_objectlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fsana_objectlistsrch = new ew_Form("fsana_objectlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($sana_object_list->TotalRecs > 0 && $sana_object_list->ExportOptions->Visible()) { ?>
<?php $sana_object_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($sana_object_list->SearchOptions->Visible()) { ?>
<?php $sana_object_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($sana_object_list->FilterOptions->Visible()) { ?>
<?php $sana_object_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $sana_object_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($sana_object_list->TotalRecs <= 0)
			$sana_object_list->TotalRecs = $sana_object->SelectRecordCount();
	} else {
		if (!$sana_object_list->Recordset && ($sana_object_list->Recordset = $sana_object_list->LoadRecordset()))
			$sana_object_list->TotalRecs = $sana_object_list->Recordset->RecordCount();
	}
	$sana_object_list->StartRec = 1;
	if ($sana_object_list->DisplayRecs <= 0 || ($sana_object->Export <> "" && $sana_object->ExportAll)) // Display all records
		$sana_object_list->DisplayRecs = $sana_object_list->TotalRecs;
	if (!($sana_object->Export <> "" && $sana_object->ExportAll))
		$sana_object_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$sana_object_list->Recordset = $sana_object_list->LoadRecordset($sana_object_list->StartRec-1, $sana_object_list->DisplayRecs);

	// Set no record found message
	if ($sana_object->CurrentAction == "" && $sana_object_list->TotalRecs == 0) {
		if ($sana_object_list->SearchWhere == "0=101")
			$sana_object_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$sana_object_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$sana_object_list->RenderOtherOptions();
?>
<?php if ($sana_object->Export == "" && $sana_object->CurrentAction == "") { ?>
<form name="fsana_objectlistsrch" id="fsana_objectlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($sana_object_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fsana_objectlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="sana_object">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($sana_object_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($sana_object_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $sana_object_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($sana_object_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($sana_object_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($sana_object_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($sana_object_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $sana_object_list->ShowPageHeader(); ?>
<?php
$sana_object_list->ShowMessage();
?>
<?php if ($sana_object_list->TotalRecs > 0 || $sana_object->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fsana_objectlist" id="fsana_objectlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_object_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_object_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_object">
<div id="gmp_sana_object" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($sana_object_list->TotalRecs > 0) { ?>
<table id="tbl_sana_objectlist" class="table ewTable">
<?php echo $sana_object->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$sana_object_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$sana_object_list->RenderListOptions();

// Render list options (header, left)
$sana_object_list->ListOptions->Render("header", "left");
?>
<?php if ($sana_object->objectID->Visible) { // objectID ?>
	<?php if ($sana_object->SortUrl($sana_object->objectID) == "") { ?>
		<th data-name="objectID"><div id="elh_sana_object_objectID" class="sana_object_objectID"><div class="ewTableHeaderCaption"><?php echo $sana_object->objectID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="objectID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_object->SortUrl($sana_object->objectID) ?>',1);"><div id="elh_sana_object_objectID" class="sana_object_objectID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_object->objectID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sana_object->objectID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_object->objectID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_object->objectName->Visible) { // objectName ?>
	<?php if ($sana_object->SortUrl($sana_object->objectName) == "") { ?>
		<th data-name="objectName"><div id="elh_sana_object_objectName" class="sana_object_objectName"><div class="ewTableHeaderCaption"><?php echo $sana_object->objectName->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="objectName"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_object->SortUrl($sana_object->objectName) ?>',1);"><div id="elh_sana_object_objectName" class="sana_object_objectName">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_object->objectName->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_object->objectName->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_object->objectName->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_object->ownerID->Visible) { // ownerID ?>
	<?php if ($sana_object->SortUrl($sana_object->ownerID) == "") { ?>
		<th data-name="ownerID"><div id="elh_sana_object_ownerID" class="sana_object_ownerID"><div class="ewTableHeaderCaption"><?php echo $sana_object->ownerID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ownerID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_object->SortUrl($sana_object->ownerID) ?>',1);"><div id="elh_sana_object_ownerID" class="sana_object_ownerID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_object->ownerID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sana_object->ownerID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_object->ownerID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_object->ownerName->Visible) { // ownerName ?>
	<?php if ($sana_object->SortUrl($sana_object->ownerName) == "") { ?>
		<th data-name="ownerName"><div id="elh_sana_object_ownerName" class="sana_object_ownerName"><div class="ewTableHeaderCaption"><?php echo $sana_object->ownerName->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ownerName"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_object->SortUrl($sana_object->ownerName) ?>',1);"><div id="elh_sana_object_ownerName" class="sana_object_ownerName">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_object->ownerName->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_object->ownerName->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_object->ownerName->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_object->lastName->Visible) { // lastName ?>
	<?php if ($sana_object->SortUrl($sana_object->lastName) == "") { ?>
		<th data-name="lastName"><div id="elh_sana_object_lastName" class="sana_object_lastName"><div class="ewTableHeaderCaption"><?php echo $sana_object->lastName->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="lastName"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_object->SortUrl($sana_object->lastName) ?>',1);"><div id="elh_sana_object_lastName" class="sana_object_lastName">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_object->lastName->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_object->lastName->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_object->lastName->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_object->mobilePhone->Visible) { // mobilePhone ?>
	<?php if ($sana_object->SortUrl($sana_object->mobilePhone) == "") { ?>
		<th data-name="mobilePhone"><div id="elh_sana_object_mobilePhone" class="sana_object_mobilePhone"><div class="ewTableHeaderCaption"><?php echo $sana_object->mobilePhone->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mobilePhone"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_object->SortUrl($sana_object->mobilePhone) ?>',1);"><div id="elh_sana_object_mobilePhone" class="sana_object_mobilePhone">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_object->mobilePhone->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_object->mobilePhone->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_object->mobilePhone->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_object->color->Visible) { // color ?>
	<?php if ($sana_object->SortUrl($sana_object->color) == "") { ?>
		<th data-name="color"><div id="elh_sana_object_color" class="sana_object_color"><div class="ewTableHeaderCaption"><?php echo $sana_object->color->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="color"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_object->SortUrl($sana_object->color) ?>',1);"><div id="elh_sana_object_color" class="sana_object_color">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_object->color->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_object->color->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_object->color->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_object->status->Visible) { // status ?>
	<?php if ($sana_object->SortUrl($sana_object->status) == "") { ?>
		<th data-name="status"><div id="elh_sana_object_status" class="sana_object_status"><div class="ewTableHeaderCaption"><?php echo $sana_object->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_object->SortUrl($sana_object->status) ?>',1);"><div id="elh_sana_object_status" class="sana_object_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_object->status->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_object->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_object->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_object->content->Visible) { // content ?>
	<?php if ($sana_object->SortUrl($sana_object->content) == "") { ?>
		<th data-name="content"><div id="elh_sana_object_content" class="sana_object_content"><div class="ewTableHeaderCaption"><?php echo $sana_object->content->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="content"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_object->SortUrl($sana_object->content) ?>',1);"><div id="elh_sana_object_content" class="sana_object_content">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_object->content->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_object->content->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_object->content->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_object->financialValue->Visible) { // financialValue ?>
	<?php if ($sana_object->SortUrl($sana_object->financialValue) == "") { ?>
		<th data-name="financialValue"><div id="elh_sana_object_financialValue" class="sana_object_financialValue"><div class="ewTableHeaderCaption"><?php echo $sana_object->financialValue->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="financialValue"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_object->SortUrl($sana_object->financialValue) ?>',1);"><div id="elh_sana_object_financialValue" class="sana_object_financialValue">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_object->financialValue->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_object->financialValue->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_object->financialValue->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_object->registrationUser->Visible) { // registrationUser ?>
	<?php if ($sana_object->SortUrl($sana_object->registrationUser) == "") { ?>
		<th data-name="registrationUser"><div id="elh_sana_object_registrationUser" class="sana_object_registrationUser"><div class="ewTableHeaderCaption"><?php echo $sana_object->registrationUser->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="registrationUser"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_object->SortUrl($sana_object->registrationUser) ?>',1);"><div id="elh_sana_object_registrationUser" class="sana_object_registrationUser">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_object->registrationUser->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sana_object->registrationUser->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_object->registrationUser->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_object->registrationDateTime->Visible) { // registrationDateTime ?>
	<?php if ($sana_object->SortUrl($sana_object->registrationDateTime) == "") { ?>
		<th data-name="registrationDateTime"><div id="elh_sana_object_registrationDateTime" class="sana_object_registrationDateTime"><div class="ewTableHeaderCaption"><?php echo $sana_object->registrationDateTime->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="registrationDateTime"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_object->SortUrl($sana_object->registrationDateTime) ?>',1);"><div id="elh_sana_object_registrationDateTime" class="sana_object_registrationDateTime">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_object->registrationDateTime->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sana_object->registrationDateTime->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_object->registrationDateTime->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_object->registrationStation->Visible) { // registrationStation ?>
	<?php if ($sana_object->SortUrl($sana_object->registrationStation) == "") { ?>
		<th data-name="registrationStation"><div id="elh_sana_object_registrationStation" class="sana_object_registrationStation"><div class="ewTableHeaderCaption"><?php echo $sana_object->registrationStation->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="registrationStation"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_object->SortUrl($sana_object->registrationStation) ?>',1);"><div id="elh_sana_object_registrationStation" class="sana_object_registrationStation">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_object->registrationStation->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sana_object->registrationStation->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_object->registrationStation->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_object->isolatedDateTime->Visible) { // isolatedDateTime ?>
	<?php if ($sana_object->SortUrl($sana_object->isolatedDateTime) == "") { ?>
		<th data-name="isolatedDateTime"><div id="elh_sana_object_isolatedDateTime" class="sana_object_isolatedDateTime"><div class="ewTableHeaderCaption"><?php echo $sana_object->isolatedDateTime->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="isolatedDateTime"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_object->SortUrl($sana_object->isolatedDateTime) ?>',1);"><div id="elh_sana_object_isolatedDateTime" class="sana_object_isolatedDateTime">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_object->isolatedDateTime->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sana_object->isolatedDateTime->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_object->isolatedDateTime->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$sana_object_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($sana_object->ExportAll && $sana_object->Export <> "") {
	$sana_object_list->StopRec = $sana_object_list->TotalRecs;
} else {

	// Set the last record to display
	if ($sana_object_list->TotalRecs > $sana_object_list->StartRec + $sana_object_list->DisplayRecs - 1)
		$sana_object_list->StopRec = $sana_object_list->StartRec + $sana_object_list->DisplayRecs - 1;
	else
		$sana_object_list->StopRec = $sana_object_list->TotalRecs;
}
$sana_object_list->RecCnt = $sana_object_list->StartRec - 1;
if ($sana_object_list->Recordset && !$sana_object_list->Recordset->EOF) {
	$sana_object_list->Recordset->MoveFirst();
	$bSelectLimit = $sana_object_list->UseSelectLimit;
	if (!$bSelectLimit && $sana_object_list->StartRec > 1)
		$sana_object_list->Recordset->Move($sana_object_list->StartRec - 1);
} elseif (!$sana_object->AllowAddDeleteRow && $sana_object_list->StopRec == 0) {
	$sana_object_list->StopRec = $sana_object->GridAddRowCount;
}

// Initialize aggregate
$sana_object->RowType = EW_ROWTYPE_AGGREGATEINIT;
$sana_object->ResetAttrs();
$sana_object_list->RenderRow();
while ($sana_object_list->RecCnt < $sana_object_list->StopRec) {
	$sana_object_list->RecCnt++;
	if (intval($sana_object_list->RecCnt) >= intval($sana_object_list->StartRec)) {
		$sana_object_list->RowCnt++;

		// Set up key count
		$sana_object_list->KeyCount = $sana_object_list->RowIndex;

		// Init row class and style
		$sana_object->ResetAttrs();
		$sana_object->CssClass = "";
		if ($sana_object->CurrentAction == "gridadd") {
		} else {
			$sana_object_list->LoadRowValues($sana_object_list->Recordset); // Load row values
		}
		$sana_object->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$sana_object->RowAttrs = array_merge($sana_object->RowAttrs, array('data-rowindex'=>$sana_object_list->RowCnt, 'id'=>'r' . $sana_object_list->RowCnt . '_sana_object', 'data-rowtype'=>$sana_object->RowType));

		// Render row
		$sana_object_list->RenderRow();

		// Render list options
		$sana_object_list->RenderListOptions();
?>
	<tr<?php echo $sana_object->RowAttributes() ?>>
<?php

// Render list options (body, left)
$sana_object_list->ListOptions->Render("body", "left", $sana_object_list->RowCnt);
?>
	<?php if ($sana_object->objectID->Visible) { // objectID ?>
		<td data-name="objectID"<?php echo $sana_object->objectID->CellAttributes() ?>>
<span id="el<?php echo $sana_object_list->RowCnt ?>_sana_object_objectID" class="sana_object_objectID">
<span<?php echo $sana_object->objectID->ViewAttributes() ?>>
<?php echo $sana_object->objectID->ListViewValue() ?></span>
</span>
<a id="<?php echo $sana_object_list->PageObjName . "_row_" . $sana_object_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($sana_object->objectName->Visible) { // objectName ?>
		<td data-name="objectName"<?php echo $sana_object->objectName->CellAttributes() ?>>
<span id="el<?php echo $sana_object_list->RowCnt ?>_sana_object_objectName" class="sana_object_objectName">
<span<?php echo $sana_object->objectName->ViewAttributes() ?>>
<?php echo $sana_object->objectName->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_object->ownerID->Visible) { // ownerID ?>
		<td data-name="ownerID"<?php echo $sana_object->ownerID->CellAttributes() ?>>
<span id="el<?php echo $sana_object_list->RowCnt ?>_sana_object_ownerID" class="sana_object_ownerID">
<span<?php echo $sana_object->ownerID->ViewAttributes() ?>>
<?php echo $sana_object->ownerID->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_object->ownerName->Visible) { // ownerName ?>
		<td data-name="ownerName"<?php echo $sana_object->ownerName->CellAttributes() ?>>
<span id="el<?php echo $sana_object_list->RowCnt ?>_sana_object_ownerName" class="sana_object_ownerName">
<span<?php echo $sana_object->ownerName->ViewAttributes() ?>>
<?php echo $sana_object->ownerName->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_object->lastName->Visible) { // lastName ?>
		<td data-name="lastName"<?php echo $sana_object->lastName->CellAttributes() ?>>
<span id="el<?php echo $sana_object_list->RowCnt ?>_sana_object_lastName" class="sana_object_lastName">
<span<?php echo $sana_object->lastName->ViewAttributes() ?>>
<?php echo $sana_object->lastName->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_object->mobilePhone->Visible) { // mobilePhone ?>
		<td data-name="mobilePhone"<?php echo $sana_object->mobilePhone->CellAttributes() ?>>
<span id="el<?php echo $sana_object_list->RowCnt ?>_sana_object_mobilePhone" class="sana_object_mobilePhone">
<span<?php echo $sana_object->mobilePhone->ViewAttributes() ?>>
<?php echo $sana_object->mobilePhone->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_object->color->Visible) { // color ?>
		<td data-name="color"<?php echo $sana_object->color->CellAttributes() ?>>
<span id="el<?php echo $sana_object_list->RowCnt ?>_sana_object_color" class="sana_object_color">
<span<?php echo $sana_object->color->ViewAttributes() ?>>
<?php echo $sana_object->color->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_object->status->Visible) { // status ?>
		<td data-name="status"<?php echo $sana_object->status->CellAttributes() ?>>
<span id="el<?php echo $sana_object_list->RowCnt ?>_sana_object_status" class="sana_object_status">
<span<?php echo $sana_object->status->ViewAttributes() ?>>
<?php echo $sana_object->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_object->content->Visible) { // content ?>
		<td data-name="content"<?php echo $sana_object->content->CellAttributes() ?>>
<span id="el<?php echo $sana_object_list->RowCnt ?>_sana_object_content" class="sana_object_content">
<span<?php echo $sana_object->content->ViewAttributes() ?>>
<?php echo $sana_object->content->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_object->financialValue->Visible) { // financialValue ?>
		<td data-name="financialValue"<?php echo $sana_object->financialValue->CellAttributes() ?>>
<span id="el<?php echo $sana_object_list->RowCnt ?>_sana_object_financialValue" class="sana_object_financialValue">
<span<?php echo $sana_object->financialValue->ViewAttributes() ?>>
<?php echo $sana_object->financialValue->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_object->registrationUser->Visible) { // registrationUser ?>
		<td data-name="registrationUser"<?php echo $sana_object->registrationUser->CellAttributes() ?>>
<span id="el<?php echo $sana_object_list->RowCnt ?>_sana_object_registrationUser" class="sana_object_registrationUser">
<span<?php echo $sana_object->registrationUser->ViewAttributes() ?>>
<?php echo $sana_object->registrationUser->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_object->registrationDateTime->Visible) { // registrationDateTime ?>
		<td data-name="registrationDateTime"<?php echo $sana_object->registrationDateTime->CellAttributes() ?>>
<span id="el<?php echo $sana_object_list->RowCnt ?>_sana_object_registrationDateTime" class="sana_object_registrationDateTime">
<span<?php echo $sana_object->registrationDateTime->ViewAttributes() ?>>
<?php echo $sana_object->registrationDateTime->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_object->registrationStation->Visible) { // registrationStation ?>
		<td data-name="registrationStation"<?php echo $sana_object->registrationStation->CellAttributes() ?>>
<span id="el<?php echo $sana_object_list->RowCnt ?>_sana_object_registrationStation" class="sana_object_registrationStation">
<span<?php echo $sana_object->registrationStation->ViewAttributes() ?>>
<?php echo $sana_object->registrationStation->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_object->isolatedDateTime->Visible) { // isolatedDateTime ?>
		<td data-name="isolatedDateTime"<?php echo $sana_object->isolatedDateTime->CellAttributes() ?>>
<span id="el<?php echo $sana_object_list->RowCnt ?>_sana_object_isolatedDateTime" class="sana_object_isolatedDateTime">
<span<?php echo $sana_object->isolatedDateTime->ViewAttributes() ?>>
<?php echo $sana_object->isolatedDateTime->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$sana_object_list->ListOptions->Render("body", "right", $sana_object_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($sana_object->CurrentAction <> "gridadd")
		$sana_object_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($sana_object->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($sana_object_list->Recordset)
	$sana_object_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($sana_object->CurrentAction <> "gridadd" && $sana_object->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($sana_object_list->Pager)) $sana_object_list->Pager = new cNumericPager($sana_object_list->StartRec, $sana_object_list->DisplayRecs, $sana_object_list->TotalRecs, $sana_object_list->RecRange) ?>
<?php if ($sana_object_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($sana_object_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $sana_object_list->PageUrl() ?>start=<?php echo $sana_object_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($sana_object_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $sana_object_list->PageUrl() ?>start=<?php echo $sana_object_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($sana_object_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $sana_object_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($sana_object_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $sana_object_list->PageUrl() ?>start=<?php echo $sana_object_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($sana_object_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $sana_object_list->PageUrl() ?>start=<?php echo $sana_object_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $sana_object_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $sana_object_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $sana_object_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($sana_object_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($sana_object_list->TotalRecs == 0 && $sana_object->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($sana_object_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fsana_objectlistsrch.Init();
fsana_objectlistsrch.FilterList = <?php echo $sana_object_list->GetFilterList() ?>;
fsana_objectlist.Init();
</script>
<?php
$sana_object_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_object_list->Page_Terminate();
?>
