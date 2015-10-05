<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "sana_personinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$sana_person_list = NULL; // Initialize page object first

class csana_person_list extends csana_person {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_person';

	// Page object name
	var $PageObjName = 'sana_person_list';

	// Grid form hidden field names
	var $FormName = 'fsana_personlist';
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

		// Table object (sana_person)
		if (!isset($GLOBALS["sana_person"]) || get_class($GLOBALS["sana_person"]) == "csana_person") {
			$GLOBALS["sana_person"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_person"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "sana_personadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "sana_persondelete.php";
		$this->MultiUpdateUrl = "sana_personupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sana_person', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fsana_personlistsrch";

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
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Restore filter list
			$this->RestoreFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

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

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
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

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
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
			$this->personID->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->personID->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->personID->AdvancedSearch->ToJSON(), ","); // Field personID
		$sFilterList = ew_Concat($sFilterList, $this->personName->AdvancedSearch->ToJSON(), ","); // Field personName
		$sFilterList = ew_Concat($sFilterList, $this->lastName->AdvancedSearch->ToJSON(), ","); // Field lastName
		$sFilterList = ew_Concat($sFilterList, $this->nationalID->AdvancedSearch->ToJSON(), ","); // Field nationalID
		$sFilterList = ew_Concat($sFilterList, $this->nationalNumber->AdvancedSearch->ToJSON(), ","); // Field nationalNumber
		$sFilterList = ew_Concat($sFilterList, $this->passportNumber->AdvancedSearch->ToJSON(), ","); // Field passportNumber
		$sFilterList = ew_Concat($sFilterList, $this->fatherName->AdvancedSearch->ToJSON(), ","); // Field fatherName
		$sFilterList = ew_Concat($sFilterList, $this->gender->AdvancedSearch->ToJSON(), ","); // Field gender
		$sFilterList = ew_Concat($sFilterList, $this->locationLevel1->AdvancedSearch->ToJSON(), ","); // Field locationLevel1
		$sFilterList = ew_Concat($sFilterList, $this->locationLevel2->AdvancedSearch->ToJSON(), ","); // Field locationLevel2
		$sFilterList = ew_Concat($sFilterList, $this->locationLevel3->AdvancedSearch->ToJSON(), ","); // Field locationLevel3
		$sFilterList = ew_Concat($sFilterList, $this->locationLevel4->AdvancedSearch->ToJSON(), ","); // Field locationLevel4
		$sFilterList = ew_Concat($sFilterList, $this->locationLevel5->AdvancedSearch->ToJSON(), ","); // Field locationLevel5
		$sFilterList = ew_Concat($sFilterList, $this->locationLevel6->AdvancedSearch->ToJSON(), ","); // Field locationLevel6
		$sFilterList = ew_Concat($sFilterList, $this->address->AdvancedSearch->ToJSON(), ","); // Field address
		$sFilterList = ew_Concat($sFilterList, $this->convoy->AdvancedSearch->ToJSON(), ","); // Field convoy
		$sFilterList = ew_Concat($sFilterList, $this->convoyManager->AdvancedSearch->ToJSON(), ","); // Field convoyManager
		$sFilterList = ew_Concat($sFilterList, $this->followersName->AdvancedSearch->ToJSON(), ","); // Field followersName
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJSON(), ","); // Field status
		$sFilterList = ew_Concat($sFilterList, $this->isolatedLocation->AdvancedSearch->ToJSON(), ","); // Field isolatedLocation
		$sFilterList = ew_Concat($sFilterList, $this->birthDate->AdvancedSearch->ToJSON(), ","); // Field birthDate
		$sFilterList = ew_Concat($sFilterList, $this->ageRange->AdvancedSearch->ToJSON(), ","); // Field ageRange
		$sFilterList = ew_Concat($sFilterList, $this->dress1->AdvancedSearch->ToJSON(), ","); // Field dress1
		$sFilterList = ew_Concat($sFilterList, $this->dress2->AdvancedSearch->ToJSON(), ","); // Field dress2
		$sFilterList = ew_Concat($sFilterList, $this->signTags->AdvancedSearch->ToJSON(), ","); // Field signTags
		$sFilterList = ew_Concat($sFilterList, $this->phone->AdvancedSearch->ToJSON(), ","); // Field phone
		$sFilterList = ew_Concat($sFilterList, $this->mobilePhone->AdvancedSearch->ToJSON(), ","); // Field mobilePhone
		$sFilterList = ew_Concat($sFilterList, $this->_email->AdvancedSearch->ToJSON(), ","); // Field email
		$sFilterList = ew_Concat($sFilterList, $this->temporaryResidence->AdvancedSearch->ToJSON(), ","); // Field temporaryResidence
		$sFilterList = ew_Concat($sFilterList, $this->visitsCount->AdvancedSearch->ToJSON(), ","); // Field visitsCount
		$sFilterList = ew_Concat($sFilterList, $this->picture->AdvancedSearch->ToJSON(), ","); // Field picture
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

		// Field personID
		$this->personID->AdvancedSearch->SearchValue = @$filter["x_personID"];
		$this->personID->AdvancedSearch->SearchOperator = @$filter["z_personID"];
		$this->personID->AdvancedSearch->SearchCondition = @$filter["v_personID"];
		$this->personID->AdvancedSearch->SearchValue2 = @$filter["y_personID"];
		$this->personID->AdvancedSearch->SearchOperator2 = @$filter["w_personID"];
		$this->personID->AdvancedSearch->Save();

		// Field personName
		$this->personName->AdvancedSearch->SearchValue = @$filter["x_personName"];
		$this->personName->AdvancedSearch->SearchOperator = @$filter["z_personName"];
		$this->personName->AdvancedSearch->SearchCondition = @$filter["v_personName"];
		$this->personName->AdvancedSearch->SearchValue2 = @$filter["y_personName"];
		$this->personName->AdvancedSearch->SearchOperator2 = @$filter["w_personName"];
		$this->personName->AdvancedSearch->Save();

		// Field lastName
		$this->lastName->AdvancedSearch->SearchValue = @$filter["x_lastName"];
		$this->lastName->AdvancedSearch->SearchOperator = @$filter["z_lastName"];
		$this->lastName->AdvancedSearch->SearchCondition = @$filter["v_lastName"];
		$this->lastName->AdvancedSearch->SearchValue2 = @$filter["y_lastName"];
		$this->lastName->AdvancedSearch->SearchOperator2 = @$filter["w_lastName"];
		$this->lastName->AdvancedSearch->Save();

		// Field nationalID
		$this->nationalID->AdvancedSearch->SearchValue = @$filter["x_nationalID"];
		$this->nationalID->AdvancedSearch->SearchOperator = @$filter["z_nationalID"];
		$this->nationalID->AdvancedSearch->SearchCondition = @$filter["v_nationalID"];
		$this->nationalID->AdvancedSearch->SearchValue2 = @$filter["y_nationalID"];
		$this->nationalID->AdvancedSearch->SearchOperator2 = @$filter["w_nationalID"];
		$this->nationalID->AdvancedSearch->Save();

		// Field nationalNumber
		$this->nationalNumber->AdvancedSearch->SearchValue = @$filter["x_nationalNumber"];
		$this->nationalNumber->AdvancedSearch->SearchOperator = @$filter["z_nationalNumber"];
		$this->nationalNumber->AdvancedSearch->SearchCondition = @$filter["v_nationalNumber"];
		$this->nationalNumber->AdvancedSearch->SearchValue2 = @$filter["y_nationalNumber"];
		$this->nationalNumber->AdvancedSearch->SearchOperator2 = @$filter["w_nationalNumber"];
		$this->nationalNumber->AdvancedSearch->Save();

		// Field passportNumber
		$this->passportNumber->AdvancedSearch->SearchValue = @$filter["x_passportNumber"];
		$this->passportNumber->AdvancedSearch->SearchOperator = @$filter["z_passportNumber"];
		$this->passportNumber->AdvancedSearch->SearchCondition = @$filter["v_passportNumber"];
		$this->passportNumber->AdvancedSearch->SearchValue2 = @$filter["y_passportNumber"];
		$this->passportNumber->AdvancedSearch->SearchOperator2 = @$filter["w_passportNumber"];
		$this->passportNumber->AdvancedSearch->Save();

		// Field fatherName
		$this->fatherName->AdvancedSearch->SearchValue = @$filter["x_fatherName"];
		$this->fatherName->AdvancedSearch->SearchOperator = @$filter["z_fatherName"];
		$this->fatherName->AdvancedSearch->SearchCondition = @$filter["v_fatherName"];
		$this->fatherName->AdvancedSearch->SearchValue2 = @$filter["y_fatherName"];
		$this->fatherName->AdvancedSearch->SearchOperator2 = @$filter["w_fatherName"];
		$this->fatherName->AdvancedSearch->Save();

		// Field gender
		$this->gender->AdvancedSearch->SearchValue = @$filter["x_gender"];
		$this->gender->AdvancedSearch->SearchOperator = @$filter["z_gender"];
		$this->gender->AdvancedSearch->SearchCondition = @$filter["v_gender"];
		$this->gender->AdvancedSearch->SearchValue2 = @$filter["y_gender"];
		$this->gender->AdvancedSearch->SearchOperator2 = @$filter["w_gender"];
		$this->gender->AdvancedSearch->Save();

		// Field locationLevel1
		$this->locationLevel1->AdvancedSearch->SearchValue = @$filter["x_locationLevel1"];
		$this->locationLevel1->AdvancedSearch->SearchOperator = @$filter["z_locationLevel1"];
		$this->locationLevel1->AdvancedSearch->SearchCondition = @$filter["v_locationLevel1"];
		$this->locationLevel1->AdvancedSearch->SearchValue2 = @$filter["y_locationLevel1"];
		$this->locationLevel1->AdvancedSearch->SearchOperator2 = @$filter["w_locationLevel1"];
		$this->locationLevel1->AdvancedSearch->Save();

		// Field locationLevel2
		$this->locationLevel2->AdvancedSearch->SearchValue = @$filter["x_locationLevel2"];
		$this->locationLevel2->AdvancedSearch->SearchOperator = @$filter["z_locationLevel2"];
		$this->locationLevel2->AdvancedSearch->SearchCondition = @$filter["v_locationLevel2"];
		$this->locationLevel2->AdvancedSearch->SearchValue2 = @$filter["y_locationLevel2"];
		$this->locationLevel2->AdvancedSearch->SearchOperator2 = @$filter["w_locationLevel2"];
		$this->locationLevel2->AdvancedSearch->Save();

		// Field locationLevel3
		$this->locationLevel3->AdvancedSearch->SearchValue = @$filter["x_locationLevel3"];
		$this->locationLevel3->AdvancedSearch->SearchOperator = @$filter["z_locationLevel3"];
		$this->locationLevel3->AdvancedSearch->SearchCondition = @$filter["v_locationLevel3"];
		$this->locationLevel3->AdvancedSearch->SearchValue2 = @$filter["y_locationLevel3"];
		$this->locationLevel3->AdvancedSearch->SearchOperator2 = @$filter["w_locationLevel3"];
		$this->locationLevel3->AdvancedSearch->Save();

		// Field locationLevel4
		$this->locationLevel4->AdvancedSearch->SearchValue = @$filter["x_locationLevel4"];
		$this->locationLevel4->AdvancedSearch->SearchOperator = @$filter["z_locationLevel4"];
		$this->locationLevel4->AdvancedSearch->SearchCondition = @$filter["v_locationLevel4"];
		$this->locationLevel4->AdvancedSearch->SearchValue2 = @$filter["y_locationLevel4"];
		$this->locationLevel4->AdvancedSearch->SearchOperator2 = @$filter["w_locationLevel4"];
		$this->locationLevel4->AdvancedSearch->Save();

		// Field locationLevel5
		$this->locationLevel5->AdvancedSearch->SearchValue = @$filter["x_locationLevel5"];
		$this->locationLevel5->AdvancedSearch->SearchOperator = @$filter["z_locationLevel5"];
		$this->locationLevel5->AdvancedSearch->SearchCondition = @$filter["v_locationLevel5"];
		$this->locationLevel5->AdvancedSearch->SearchValue2 = @$filter["y_locationLevel5"];
		$this->locationLevel5->AdvancedSearch->SearchOperator2 = @$filter["w_locationLevel5"];
		$this->locationLevel5->AdvancedSearch->Save();

		// Field locationLevel6
		$this->locationLevel6->AdvancedSearch->SearchValue = @$filter["x_locationLevel6"];
		$this->locationLevel6->AdvancedSearch->SearchOperator = @$filter["z_locationLevel6"];
		$this->locationLevel6->AdvancedSearch->SearchCondition = @$filter["v_locationLevel6"];
		$this->locationLevel6->AdvancedSearch->SearchValue2 = @$filter["y_locationLevel6"];
		$this->locationLevel6->AdvancedSearch->SearchOperator2 = @$filter["w_locationLevel6"];
		$this->locationLevel6->AdvancedSearch->Save();

		// Field address
		$this->address->AdvancedSearch->SearchValue = @$filter["x_address"];
		$this->address->AdvancedSearch->SearchOperator = @$filter["z_address"];
		$this->address->AdvancedSearch->SearchCondition = @$filter["v_address"];
		$this->address->AdvancedSearch->SearchValue2 = @$filter["y_address"];
		$this->address->AdvancedSearch->SearchOperator2 = @$filter["w_address"];
		$this->address->AdvancedSearch->Save();

		// Field convoy
		$this->convoy->AdvancedSearch->SearchValue = @$filter["x_convoy"];
		$this->convoy->AdvancedSearch->SearchOperator = @$filter["z_convoy"];
		$this->convoy->AdvancedSearch->SearchCondition = @$filter["v_convoy"];
		$this->convoy->AdvancedSearch->SearchValue2 = @$filter["y_convoy"];
		$this->convoy->AdvancedSearch->SearchOperator2 = @$filter["w_convoy"];
		$this->convoy->AdvancedSearch->Save();

		// Field convoyManager
		$this->convoyManager->AdvancedSearch->SearchValue = @$filter["x_convoyManager"];
		$this->convoyManager->AdvancedSearch->SearchOperator = @$filter["z_convoyManager"];
		$this->convoyManager->AdvancedSearch->SearchCondition = @$filter["v_convoyManager"];
		$this->convoyManager->AdvancedSearch->SearchValue2 = @$filter["y_convoyManager"];
		$this->convoyManager->AdvancedSearch->SearchOperator2 = @$filter["w_convoyManager"];
		$this->convoyManager->AdvancedSearch->Save();

		// Field followersName
		$this->followersName->AdvancedSearch->SearchValue = @$filter["x_followersName"];
		$this->followersName->AdvancedSearch->SearchOperator = @$filter["z_followersName"];
		$this->followersName->AdvancedSearch->SearchCondition = @$filter["v_followersName"];
		$this->followersName->AdvancedSearch->SearchValue2 = @$filter["y_followersName"];
		$this->followersName->AdvancedSearch->SearchOperator2 = @$filter["w_followersName"];
		$this->followersName->AdvancedSearch->Save();

		// Field status
		$this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
		$this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
		$this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
		$this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
		$this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
		$this->status->AdvancedSearch->Save();

		// Field isolatedLocation
		$this->isolatedLocation->AdvancedSearch->SearchValue = @$filter["x_isolatedLocation"];
		$this->isolatedLocation->AdvancedSearch->SearchOperator = @$filter["z_isolatedLocation"];
		$this->isolatedLocation->AdvancedSearch->SearchCondition = @$filter["v_isolatedLocation"];
		$this->isolatedLocation->AdvancedSearch->SearchValue2 = @$filter["y_isolatedLocation"];
		$this->isolatedLocation->AdvancedSearch->SearchOperator2 = @$filter["w_isolatedLocation"];
		$this->isolatedLocation->AdvancedSearch->Save();

		// Field birthDate
		$this->birthDate->AdvancedSearch->SearchValue = @$filter["x_birthDate"];
		$this->birthDate->AdvancedSearch->SearchOperator = @$filter["z_birthDate"];
		$this->birthDate->AdvancedSearch->SearchCondition = @$filter["v_birthDate"];
		$this->birthDate->AdvancedSearch->SearchValue2 = @$filter["y_birthDate"];
		$this->birthDate->AdvancedSearch->SearchOperator2 = @$filter["w_birthDate"];
		$this->birthDate->AdvancedSearch->Save();

		// Field ageRange
		$this->ageRange->AdvancedSearch->SearchValue = @$filter["x_ageRange"];
		$this->ageRange->AdvancedSearch->SearchOperator = @$filter["z_ageRange"];
		$this->ageRange->AdvancedSearch->SearchCondition = @$filter["v_ageRange"];
		$this->ageRange->AdvancedSearch->SearchValue2 = @$filter["y_ageRange"];
		$this->ageRange->AdvancedSearch->SearchOperator2 = @$filter["w_ageRange"];
		$this->ageRange->AdvancedSearch->Save();

		// Field dress1
		$this->dress1->AdvancedSearch->SearchValue = @$filter["x_dress1"];
		$this->dress1->AdvancedSearch->SearchOperator = @$filter["z_dress1"];
		$this->dress1->AdvancedSearch->SearchCondition = @$filter["v_dress1"];
		$this->dress1->AdvancedSearch->SearchValue2 = @$filter["y_dress1"];
		$this->dress1->AdvancedSearch->SearchOperator2 = @$filter["w_dress1"];
		$this->dress1->AdvancedSearch->Save();

		// Field dress2
		$this->dress2->AdvancedSearch->SearchValue = @$filter["x_dress2"];
		$this->dress2->AdvancedSearch->SearchOperator = @$filter["z_dress2"];
		$this->dress2->AdvancedSearch->SearchCondition = @$filter["v_dress2"];
		$this->dress2->AdvancedSearch->SearchValue2 = @$filter["y_dress2"];
		$this->dress2->AdvancedSearch->SearchOperator2 = @$filter["w_dress2"];
		$this->dress2->AdvancedSearch->Save();

		// Field signTags
		$this->signTags->AdvancedSearch->SearchValue = @$filter["x_signTags"];
		$this->signTags->AdvancedSearch->SearchOperator = @$filter["z_signTags"];
		$this->signTags->AdvancedSearch->SearchCondition = @$filter["v_signTags"];
		$this->signTags->AdvancedSearch->SearchValue2 = @$filter["y_signTags"];
		$this->signTags->AdvancedSearch->SearchOperator2 = @$filter["w_signTags"];
		$this->signTags->AdvancedSearch->Save();

		// Field phone
		$this->phone->AdvancedSearch->SearchValue = @$filter["x_phone"];
		$this->phone->AdvancedSearch->SearchOperator = @$filter["z_phone"];
		$this->phone->AdvancedSearch->SearchCondition = @$filter["v_phone"];
		$this->phone->AdvancedSearch->SearchValue2 = @$filter["y_phone"];
		$this->phone->AdvancedSearch->SearchOperator2 = @$filter["w_phone"];
		$this->phone->AdvancedSearch->Save();

		// Field mobilePhone
		$this->mobilePhone->AdvancedSearch->SearchValue = @$filter["x_mobilePhone"];
		$this->mobilePhone->AdvancedSearch->SearchOperator = @$filter["z_mobilePhone"];
		$this->mobilePhone->AdvancedSearch->SearchCondition = @$filter["v_mobilePhone"];
		$this->mobilePhone->AdvancedSearch->SearchValue2 = @$filter["y_mobilePhone"];
		$this->mobilePhone->AdvancedSearch->SearchOperator2 = @$filter["w_mobilePhone"];
		$this->mobilePhone->AdvancedSearch->Save();

		// Field email
		$this->_email->AdvancedSearch->SearchValue = @$filter["x__email"];
		$this->_email->AdvancedSearch->SearchOperator = @$filter["z__email"];
		$this->_email->AdvancedSearch->SearchCondition = @$filter["v__email"];
		$this->_email->AdvancedSearch->SearchValue2 = @$filter["y__email"];
		$this->_email->AdvancedSearch->SearchOperator2 = @$filter["w__email"];
		$this->_email->AdvancedSearch->Save();

		// Field temporaryResidence
		$this->temporaryResidence->AdvancedSearch->SearchValue = @$filter["x_temporaryResidence"];
		$this->temporaryResidence->AdvancedSearch->SearchOperator = @$filter["z_temporaryResidence"];
		$this->temporaryResidence->AdvancedSearch->SearchCondition = @$filter["v_temporaryResidence"];
		$this->temporaryResidence->AdvancedSearch->SearchValue2 = @$filter["y_temporaryResidence"];
		$this->temporaryResidence->AdvancedSearch->SearchOperator2 = @$filter["w_temporaryResidence"];
		$this->temporaryResidence->AdvancedSearch->Save();

		// Field visitsCount
		$this->visitsCount->AdvancedSearch->SearchValue = @$filter["x_visitsCount"];
		$this->visitsCount->AdvancedSearch->SearchOperator = @$filter["z_visitsCount"];
		$this->visitsCount->AdvancedSearch->SearchCondition = @$filter["v_visitsCount"];
		$this->visitsCount->AdvancedSearch->SearchValue2 = @$filter["y_visitsCount"];
		$this->visitsCount->AdvancedSearch->SearchOperator2 = @$filter["w_visitsCount"];
		$this->visitsCount->AdvancedSearch->Save();

		// Field picture
		$this->picture->AdvancedSearch->SearchValue = @$filter["x_picture"];
		$this->picture->AdvancedSearch->SearchOperator = @$filter["z_picture"];
		$this->picture->AdvancedSearch->SearchCondition = @$filter["v_picture"];
		$this->picture->AdvancedSearch->SearchValue2 = @$filter["y_picture"];
		$this->picture->AdvancedSearch->SearchOperator2 = @$filter["w_picture"];
		$this->picture->AdvancedSearch->Save();

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

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		$this->BuildSearchSql($sWhere, $this->personID, $Default, FALSE); // personID
		$this->BuildSearchSql($sWhere, $this->personName, $Default, FALSE); // personName
		$this->BuildSearchSql($sWhere, $this->lastName, $Default, FALSE); // lastName
		$this->BuildSearchSql($sWhere, $this->nationalID, $Default, FALSE); // nationalID
		$this->BuildSearchSql($sWhere, $this->nationalNumber, $Default, FALSE); // nationalNumber
		$this->BuildSearchSql($sWhere, $this->passportNumber, $Default, FALSE); // passportNumber
		$this->BuildSearchSql($sWhere, $this->fatherName, $Default, FALSE); // fatherName
		$this->BuildSearchSql($sWhere, $this->gender, $Default, FALSE); // gender
		$this->BuildSearchSql($sWhere, $this->locationLevel1, $Default, FALSE); // locationLevel1
		$this->BuildSearchSql($sWhere, $this->locationLevel2, $Default, FALSE); // locationLevel2
		$this->BuildSearchSql($sWhere, $this->locationLevel3, $Default, FALSE); // locationLevel3
		$this->BuildSearchSql($sWhere, $this->locationLevel4, $Default, FALSE); // locationLevel4
		$this->BuildSearchSql($sWhere, $this->locationLevel5, $Default, FALSE); // locationLevel5
		$this->BuildSearchSql($sWhere, $this->locationLevel6, $Default, FALSE); // locationLevel6
		$this->BuildSearchSql($sWhere, $this->address, $Default, FALSE); // address
		$this->BuildSearchSql($sWhere, $this->convoy, $Default, FALSE); // convoy
		$this->BuildSearchSql($sWhere, $this->convoyManager, $Default, FALSE); // convoyManager
		$this->BuildSearchSql($sWhere, $this->followersName, $Default, FALSE); // followersName
		$this->BuildSearchSql($sWhere, $this->status, $Default, FALSE); // status
		$this->BuildSearchSql($sWhere, $this->isolatedLocation, $Default, FALSE); // isolatedLocation
		$this->BuildSearchSql($sWhere, $this->birthDate, $Default, FALSE); // birthDate
		$this->BuildSearchSql($sWhere, $this->ageRange, $Default, FALSE); // ageRange
		$this->BuildSearchSql($sWhere, $this->dress1, $Default, FALSE); // dress1
		$this->BuildSearchSql($sWhere, $this->dress2, $Default, FALSE); // dress2
		$this->BuildSearchSql($sWhere, $this->signTags, $Default, FALSE); // signTags
		$this->BuildSearchSql($sWhere, $this->phone, $Default, FALSE); // phone
		$this->BuildSearchSql($sWhere, $this->mobilePhone, $Default, FALSE); // mobilePhone
		$this->BuildSearchSql($sWhere, $this->_email, $Default, FALSE); // email
		$this->BuildSearchSql($sWhere, $this->temporaryResidence, $Default, FALSE); // temporaryResidence
		$this->BuildSearchSql($sWhere, $this->visitsCount, $Default, FALSE); // visitsCount
		$this->BuildSearchSql($sWhere, $this->picture, $Default, FALSE); // picture
		$this->BuildSearchSql($sWhere, $this->registrationUser, $Default, FALSE); // registrationUser
		$this->BuildSearchSql($sWhere, $this->registrationDateTime, $Default, FALSE); // registrationDateTime
		$this->BuildSearchSql($sWhere, $this->registrationStation, $Default, FALSE); // registrationStation
		$this->BuildSearchSql($sWhere, $this->isolatedDateTime, $Default, FALSE); // isolatedDateTime
		$this->BuildSearchSql($sWhere, $this->description, $Default, FALSE); // description

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->personID->AdvancedSearch->Save(); // personID
			$this->personName->AdvancedSearch->Save(); // personName
			$this->lastName->AdvancedSearch->Save(); // lastName
			$this->nationalID->AdvancedSearch->Save(); // nationalID
			$this->nationalNumber->AdvancedSearch->Save(); // nationalNumber
			$this->passportNumber->AdvancedSearch->Save(); // passportNumber
			$this->fatherName->AdvancedSearch->Save(); // fatherName
			$this->gender->AdvancedSearch->Save(); // gender
			$this->locationLevel1->AdvancedSearch->Save(); // locationLevel1
			$this->locationLevel2->AdvancedSearch->Save(); // locationLevel2
			$this->locationLevel3->AdvancedSearch->Save(); // locationLevel3
			$this->locationLevel4->AdvancedSearch->Save(); // locationLevel4
			$this->locationLevel5->AdvancedSearch->Save(); // locationLevel5
			$this->locationLevel6->AdvancedSearch->Save(); // locationLevel6
			$this->address->AdvancedSearch->Save(); // address
			$this->convoy->AdvancedSearch->Save(); // convoy
			$this->convoyManager->AdvancedSearch->Save(); // convoyManager
			$this->followersName->AdvancedSearch->Save(); // followersName
			$this->status->AdvancedSearch->Save(); // status
			$this->isolatedLocation->AdvancedSearch->Save(); // isolatedLocation
			$this->birthDate->AdvancedSearch->Save(); // birthDate
			$this->ageRange->AdvancedSearch->Save(); // ageRange
			$this->dress1->AdvancedSearch->Save(); // dress1
			$this->dress2->AdvancedSearch->Save(); // dress2
			$this->signTags->AdvancedSearch->Save(); // signTags
			$this->phone->AdvancedSearch->Save(); // phone
			$this->mobilePhone->AdvancedSearch->Save(); // mobilePhone
			$this->_email->AdvancedSearch->Save(); // email
			$this->temporaryResidence->AdvancedSearch->Save(); // temporaryResidence
			$this->visitsCount->AdvancedSearch->Save(); // visitsCount
			$this->picture->AdvancedSearch->Save(); // picture
			$this->registrationUser->AdvancedSearch->Save(); // registrationUser
			$this->registrationDateTime->AdvancedSearch->Save(); // registrationDateTime
			$this->registrationStation->AdvancedSearch->Save(); // registrationStation
			$this->isolatedDateTime->AdvancedSearch->Save(); // isolatedDateTime
			$this->description->AdvancedSearch->Save(); // description
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->personID, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->personName, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->lastName, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nationalID, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nationalNumber, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->passportNumber, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->fatherName, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->gender, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->locationLevel1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->locationLevel2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->locationLevel3, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->locationLevel4, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->locationLevel5, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->locationLevel6, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->convoy, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->convoyManager, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->followersName, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->status, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->isolatedLocation, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->ageRange, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->dress1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->dress2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->signTags, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->phone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mobilePhone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->temporaryResidence, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->picture, $arKeywords, $type);
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
		if ($this->personID->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->personName->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->lastName->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nationalID->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nationalNumber->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->passportNumber->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fatherName->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->gender->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->locationLevel1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->locationLevel2->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->locationLevel3->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->locationLevel4->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->locationLevel5->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->locationLevel6->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->address->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->convoy->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->convoyManager->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->followersName->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->isolatedLocation->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->birthDate->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ageRange->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dress1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dress2->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->signTags->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->phone->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mobilePhone->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_email->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->temporaryResidence->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->visitsCount->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->picture->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->registrationUser->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->registrationDateTime->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->registrationStation->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->isolatedDateTime->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->description->AdvancedSearch->IssetSession())
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

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->personID->AdvancedSearch->UnsetSession();
		$this->personName->AdvancedSearch->UnsetSession();
		$this->lastName->AdvancedSearch->UnsetSession();
		$this->nationalID->AdvancedSearch->UnsetSession();
		$this->nationalNumber->AdvancedSearch->UnsetSession();
		$this->passportNumber->AdvancedSearch->UnsetSession();
		$this->fatherName->AdvancedSearch->UnsetSession();
		$this->gender->AdvancedSearch->UnsetSession();
		$this->locationLevel1->AdvancedSearch->UnsetSession();
		$this->locationLevel2->AdvancedSearch->UnsetSession();
		$this->locationLevel3->AdvancedSearch->UnsetSession();
		$this->locationLevel4->AdvancedSearch->UnsetSession();
		$this->locationLevel5->AdvancedSearch->UnsetSession();
		$this->locationLevel6->AdvancedSearch->UnsetSession();
		$this->address->AdvancedSearch->UnsetSession();
		$this->convoy->AdvancedSearch->UnsetSession();
		$this->convoyManager->AdvancedSearch->UnsetSession();
		$this->followersName->AdvancedSearch->UnsetSession();
		$this->status->AdvancedSearch->UnsetSession();
		$this->isolatedLocation->AdvancedSearch->UnsetSession();
		$this->birthDate->AdvancedSearch->UnsetSession();
		$this->ageRange->AdvancedSearch->UnsetSession();
		$this->dress1->AdvancedSearch->UnsetSession();
		$this->dress2->AdvancedSearch->UnsetSession();
		$this->signTags->AdvancedSearch->UnsetSession();
		$this->phone->AdvancedSearch->UnsetSession();
		$this->mobilePhone->AdvancedSearch->UnsetSession();
		$this->_email->AdvancedSearch->UnsetSession();
		$this->temporaryResidence->AdvancedSearch->UnsetSession();
		$this->visitsCount->AdvancedSearch->UnsetSession();
		$this->picture->AdvancedSearch->UnsetSession();
		$this->registrationUser->AdvancedSearch->UnsetSession();
		$this->registrationDateTime->AdvancedSearch->UnsetSession();
		$this->registrationStation->AdvancedSearch->UnsetSession();
		$this->isolatedDateTime->AdvancedSearch->UnsetSession();
		$this->description->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->personID->AdvancedSearch->Load();
		$this->personName->AdvancedSearch->Load();
		$this->lastName->AdvancedSearch->Load();
		$this->nationalID->AdvancedSearch->Load();
		$this->nationalNumber->AdvancedSearch->Load();
		$this->passportNumber->AdvancedSearch->Load();
		$this->fatherName->AdvancedSearch->Load();
		$this->gender->AdvancedSearch->Load();
		$this->locationLevel1->AdvancedSearch->Load();
		$this->locationLevel2->AdvancedSearch->Load();
		$this->locationLevel3->AdvancedSearch->Load();
		$this->locationLevel4->AdvancedSearch->Load();
		$this->locationLevel5->AdvancedSearch->Load();
		$this->locationLevel6->AdvancedSearch->Load();
		$this->address->AdvancedSearch->Load();
		$this->convoy->AdvancedSearch->Load();
		$this->convoyManager->AdvancedSearch->Load();
		$this->followersName->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->isolatedLocation->AdvancedSearch->Load();
		$this->birthDate->AdvancedSearch->Load();
		$this->ageRange->AdvancedSearch->Load();
		$this->dress1->AdvancedSearch->Load();
		$this->dress2->AdvancedSearch->Load();
		$this->signTags->AdvancedSearch->Load();
		$this->phone->AdvancedSearch->Load();
		$this->mobilePhone->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->temporaryResidence->AdvancedSearch->Load();
		$this->visitsCount->AdvancedSearch->Load();
		$this->picture->AdvancedSearch->Load();
		$this->registrationUser->AdvancedSearch->Load();
		$this->registrationDateTime->AdvancedSearch->Load();
		$this->registrationStation->AdvancedSearch->Load();
		$this->isolatedDateTime->AdvancedSearch->Load();
		$this->description->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->personID); // personID
			$this->UpdateSort($this->personName); // personName
			$this->UpdateSort($this->lastName); // lastName
			$this->UpdateSort($this->nationalID); // nationalID
			$this->UpdateSort($this->nationalNumber); // nationalNumber
			$this->UpdateSort($this->passportNumber); // passportNumber
			$this->UpdateSort($this->fatherName); // fatherName
			$this->UpdateSort($this->gender); // gender
			$this->UpdateSort($this->locationLevel1); // locationLevel1
			$this->UpdateSort($this->locationLevel2); // locationLevel2
			$this->UpdateSort($this->mobilePhone); // mobilePhone
			$this->UpdateSort($this->picture); // picture
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
				$this->personID->setSort("");
				$this->personName->setSort("");
				$this->lastName->setSort("");
				$this->nationalID->setSort("");
				$this->nationalNumber->setSort("");
				$this->passportNumber->setSort("");
				$this->fatherName->setSort("");
				$this->gender->setSort("");
				$this->locationLevel1->setSort("");
				$this->locationLevel2->setSort("");
				$this->mobilePhone->setSort("");
				$this->picture->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->personID->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fsana_personlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fsana_personlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fsana_personlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fsana_personlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"sana_personsrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		$item->Visible = TRUE;

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

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// personID

		$this->personID->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_personID"]);
		if ($this->personID->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->personID->AdvancedSearch->SearchOperator = @$_GET["z_personID"];

		// personName
		$this->personName->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_personName"]);
		if ($this->personName->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->personName->AdvancedSearch->SearchOperator = @$_GET["z_personName"];

		// lastName
		$this->lastName->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_lastName"]);
		if ($this->lastName->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->lastName->AdvancedSearch->SearchOperator = @$_GET["z_lastName"];

		// nationalID
		$this->nationalID->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nationalID"]);
		if ($this->nationalID->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nationalID->AdvancedSearch->SearchOperator = @$_GET["z_nationalID"];

		// nationalNumber
		$this->nationalNumber->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nationalNumber"]);
		if ($this->nationalNumber->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nationalNumber->AdvancedSearch->SearchOperator = @$_GET["z_nationalNumber"];

		// passportNumber
		$this->passportNumber->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_passportNumber"]);
		if ($this->passportNumber->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->passportNumber->AdvancedSearch->SearchOperator = @$_GET["z_passportNumber"];

		// fatherName
		$this->fatherName->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fatherName"]);
		if ($this->fatherName->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fatherName->AdvancedSearch->SearchOperator = @$_GET["z_fatherName"];

		// gender
		$this->gender->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_gender"]);
		if ($this->gender->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->gender->AdvancedSearch->SearchOperator = @$_GET["z_gender"];

		// locationLevel1
		$this->locationLevel1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_locationLevel1"]);
		if ($this->locationLevel1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->locationLevel1->AdvancedSearch->SearchOperator = @$_GET["z_locationLevel1"];

		// locationLevel2
		$this->locationLevel2->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_locationLevel2"]);
		if ($this->locationLevel2->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->locationLevel2->AdvancedSearch->SearchOperator = @$_GET["z_locationLevel2"];

		// locationLevel3
		$this->locationLevel3->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_locationLevel3"]);
		if ($this->locationLevel3->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->locationLevel3->AdvancedSearch->SearchOperator = @$_GET["z_locationLevel3"];

		// locationLevel4
		$this->locationLevel4->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_locationLevel4"]);
		if ($this->locationLevel4->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->locationLevel4->AdvancedSearch->SearchOperator = @$_GET["z_locationLevel4"];

		// locationLevel5
		$this->locationLevel5->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_locationLevel5"]);
		if ($this->locationLevel5->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->locationLevel5->AdvancedSearch->SearchOperator = @$_GET["z_locationLevel5"];

		// locationLevel6
		$this->locationLevel6->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_locationLevel6"]);
		if ($this->locationLevel6->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->locationLevel6->AdvancedSearch->SearchOperator = @$_GET["z_locationLevel6"];

		// address
		$this->address->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_address"]);
		if ($this->address->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->address->AdvancedSearch->SearchOperator = @$_GET["z_address"];

		// convoy
		$this->convoy->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_convoy"]);
		if ($this->convoy->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->convoy->AdvancedSearch->SearchOperator = @$_GET["z_convoy"];

		// convoyManager
		$this->convoyManager->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_convoyManager"]);
		if ($this->convoyManager->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->convoyManager->AdvancedSearch->SearchOperator = @$_GET["z_convoyManager"];

		// followersName
		$this->followersName->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_followersName"]);
		if ($this->followersName->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->followersName->AdvancedSearch->SearchOperator = @$_GET["z_followersName"];

		// status
		$this->status->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_status"]);
		if ($this->status->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->status->AdvancedSearch->SearchOperator = @$_GET["z_status"];

		// isolatedLocation
		$this->isolatedLocation->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_isolatedLocation"]);
		if ($this->isolatedLocation->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->isolatedLocation->AdvancedSearch->SearchOperator = @$_GET["z_isolatedLocation"];

		// birthDate
		$this->birthDate->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_birthDate"]);
		if ($this->birthDate->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->birthDate->AdvancedSearch->SearchOperator = @$_GET["z_birthDate"];

		// ageRange
		$this->ageRange->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ageRange"]);
		if ($this->ageRange->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ageRange->AdvancedSearch->SearchOperator = @$_GET["z_ageRange"];

		// dress1
		$this->dress1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dress1"]);
		if ($this->dress1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dress1->AdvancedSearch->SearchOperator = @$_GET["z_dress1"];

		// dress2
		$this->dress2->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dress2"]);
		if ($this->dress2->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dress2->AdvancedSearch->SearchOperator = @$_GET["z_dress2"];

		// signTags
		$this->signTags->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_signTags"]);
		if ($this->signTags->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->signTags->AdvancedSearch->SearchOperator = @$_GET["z_signTags"];

		// phone
		$this->phone->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_phone"]);
		if ($this->phone->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->phone->AdvancedSearch->SearchOperator = @$_GET["z_phone"];

		// mobilePhone
		$this->mobilePhone->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_mobilePhone"]);
		if ($this->mobilePhone->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->mobilePhone->AdvancedSearch->SearchOperator = @$_GET["z_mobilePhone"];

		// email
		$this->_email->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__email"]);
		if ($this->_email->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_email->AdvancedSearch->SearchOperator = @$_GET["z__email"];

		// temporaryResidence
		$this->temporaryResidence->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_temporaryResidence"]);
		if ($this->temporaryResidence->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->temporaryResidence->AdvancedSearch->SearchOperator = @$_GET["z_temporaryResidence"];

		// visitsCount
		$this->visitsCount->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_visitsCount"]);
		if ($this->visitsCount->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->visitsCount->AdvancedSearch->SearchOperator = @$_GET["z_visitsCount"];

		// picture
		$this->picture->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_picture"]);
		if ($this->picture->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->picture->AdvancedSearch->SearchOperator = @$_GET["z_picture"];

		// registrationUser
		$this->registrationUser->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_registrationUser"]);
		if ($this->registrationUser->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->registrationUser->AdvancedSearch->SearchOperator = @$_GET["z_registrationUser"];

		// registrationDateTime
		$this->registrationDateTime->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_registrationDateTime"]);
		if ($this->registrationDateTime->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->registrationDateTime->AdvancedSearch->SearchOperator = @$_GET["z_registrationDateTime"];

		// registrationStation
		$this->registrationStation->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_registrationStation"]);
		if ($this->registrationStation->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->registrationStation->AdvancedSearch->SearchOperator = @$_GET["z_registrationStation"];

		// isolatedDateTime
		$this->isolatedDateTime->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_isolatedDateTime"]);
		if ($this->isolatedDateTime->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->isolatedDateTime->AdvancedSearch->SearchOperator = @$_GET["z_isolatedDateTime"];

		// description
		$this->description->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_description"]);
		if ($this->description->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->description->AdvancedSearch->SearchOperator = @$_GET["z_description"];
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
		$this->mobilePhone->setDbValue($rs->fields('mobilePhone'));
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
		$this->mobilePhone->DbValue = $row['mobilePhone'];
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("personID")) <> "")
			$this->personID->CurrentValue = $this->getKey("personID"); // personID
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
		// personID
		// personName
		// lastName
		// nationalID
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
		// mobilePhone
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

		// mobilePhone
		$this->mobilePhone->ViewValue = $this->mobilePhone->CurrentValue;
		$this->mobilePhone->ViewCustomAttributes = "";

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
		$this->registrationStation->ViewValue = $this->registrationStation->CurrentValue;
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

			// nationalNumber
			$this->nationalNumber->LinkCustomAttributes = "";
			$this->nationalNumber->HrefValue = "";
			$this->nationalNumber->TooltipValue = "";

			// passportNumber
			$this->passportNumber->LinkCustomAttributes = "";
			$this->passportNumber->HrefValue = "";
			$this->passportNumber->TooltipValue = "";

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

			// locationLevel2
			$this->locationLevel2->LinkCustomAttributes = "";
			$this->locationLevel2->HrefValue = "";
			$this->locationLevel2->TooltipValue = "";

			// mobilePhone
			$this->mobilePhone->LinkCustomAttributes = "";
			$this->mobilePhone->HrefValue = "";
			$this->mobilePhone->TooltipValue = "";

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
				$this->picture->LinkAttrs["data-rel"] = "sana_person_x" . $this->RowCnt . "_picture";

				//$this->picture->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
				//$this->picture->LinkAttrs["data-placement"] = "bottom";
				//$this->picture->LinkAttrs["data-container"] = "body";

				$this->picture->LinkAttrs["class"] = "ewLightbox img-thumbnail";
			}
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// personID
			$this->personID->EditAttrs["class"] = "form-control";
			$this->personID->EditCustomAttributes = "";
			$this->personID->EditValue = ew_HtmlEncode($this->personID->AdvancedSearch->SearchValue);
			$this->personID->PlaceHolder = ew_RemoveHtml($this->personID->FldCaption());

			// personName
			$this->personName->EditAttrs["class"] = "form-control";
			$this->personName->EditCustomAttributes = "";
			$this->personName->EditValue = ew_HtmlEncode($this->personName->AdvancedSearch->SearchValue);
			$this->personName->PlaceHolder = ew_RemoveHtml($this->personName->FldCaption());

			// lastName
			$this->lastName->EditAttrs["class"] = "form-control";
			$this->lastName->EditCustomAttributes = "";
			$this->lastName->EditValue = ew_HtmlEncode($this->lastName->AdvancedSearch->SearchValue);
			$this->lastName->PlaceHolder = ew_RemoveHtml($this->lastName->FldCaption());

			// nationalID
			$this->nationalID->EditAttrs["class"] = "form-control";
			$this->nationalID->EditCustomAttributes = "";
			$this->nationalID->EditValue = ew_HtmlEncode($this->nationalID->AdvancedSearch->SearchValue);
			$this->nationalID->PlaceHolder = ew_RemoveHtml($this->nationalID->FldCaption());

			// nationalNumber
			$this->nationalNumber->EditAttrs["class"] = "form-control";
			$this->nationalNumber->EditCustomAttributes = "";
			$this->nationalNumber->EditValue = ew_HtmlEncode($this->nationalNumber->AdvancedSearch->SearchValue);
			$this->nationalNumber->PlaceHolder = ew_RemoveHtml($this->nationalNumber->FldCaption());

			// passportNumber
			$this->passportNumber->EditAttrs["class"] = "form-control";
			$this->passportNumber->EditCustomAttributes = "";
			$this->passportNumber->EditValue = ew_HtmlEncode($this->passportNumber->AdvancedSearch->SearchValue);
			$this->passportNumber->PlaceHolder = ew_RemoveHtml($this->passportNumber->FldCaption());

			// fatherName
			$this->fatherName->EditAttrs["class"] = "form-control";
			$this->fatherName->EditCustomAttributes = "";
			$this->fatherName->EditValue = ew_HtmlEncode($this->fatherName->AdvancedSearch->SearchValue);
			$this->fatherName->PlaceHolder = ew_RemoveHtml($this->fatherName->FldCaption());

			// gender
			$this->gender->EditAttrs["class"] = "form-control";
			$this->gender->EditCustomAttributes = "";

			// locationLevel1
			$this->locationLevel1->EditAttrs["class"] = "form-control";
			$this->locationLevel1->EditCustomAttributes = "";

			// locationLevel2
			$this->locationLevel2->EditAttrs["class"] = "form-control";
			$this->locationLevel2->EditCustomAttributes = "";

			// mobilePhone
			$this->mobilePhone->EditAttrs["class"] = "form-control";
			$this->mobilePhone->EditCustomAttributes = "";
			$this->mobilePhone->EditValue = ew_HtmlEncode($this->mobilePhone->AdvancedSearch->SearchValue);
			$this->mobilePhone->PlaceHolder = ew_RemoveHtml($this->mobilePhone->FldCaption());

			// picture
			$this->picture->EditAttrs["class"] = "form-control";
			$this->picture->EditCustomAttributes = "";
			$this->picture->EditValue = ew_HtmlEncode($this->picture->AdvancedSearch->SearchValue);
			$this->picture->PlaceHolder = ew_RemoveHtml($this->picture->FldCaption());
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckInteger($this->personID->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->personID->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->personID->AdvancedSearch->Load();
		$this->personName->AdvancedSearch->Load();
		$this->lastName->AdvancedSearch->Load();
		$this->nationalID->AdvancedSearch->Load();
		$this->nationalNumber->AdvancedSearch->Load();
		$this->passportNumber->AdvancedSearch->Load();
		$this->fatherName->AdvancedSearch->Load();
		$this->gender->AdvancedSearch->Load();
		$this->locationLevel1->AdvancedSearch->Load();
		$this->locationLevel2->AdvancedSearch->Load();
		$this->locationLevel3->AdvancedSearch->Load();
		$this->locationLevel4->AdvancedSearch->Load();
		$this->locationLevel5->AdvancedSearch->Load();
		$this->locationLevel6->AdvancedSearch->Load();
		$this->address->AdvancedSearch->Load();
		$this->convoy->AdvancedSearch->Load();
		$this->convoyManager->AdvancedSearch->Load();
		$this->followersName->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->isolatedLocation->AdvancedSearch->Load();
		$this->birthDate->AdvancedSearch->Load();
		$this->ageRange->AdvancedSearch->Load();
		$this->dress1->AdvancedSearch->Load();
		$this->dress2->AdvancedSearch->Load();
		$this->signTags->AdvancedSearch->Load();
		$this->phone->AdvancedSearch->Load();
		$this->mobilePhone->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->temporaryResidence->AdvancedSearch->Load();
		$this->visitsCount->AdvancedSearch->Load();
		$this->picture->AdvancedSearch->Load();
		$this->registrationUser->AdvancedSearch->Load();
		$this->registrationDateTime->AdvancedSearch->Load();
		$this->registrationStation->AdvancedSearch->Load();
		$this->isolatedDateTime->AdvancedSearch->Load();
		$this->description->AdvancedSearch->Load();
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
if (!isset($sana_person_list)) $sana_person_list = new csana_person_list();

// Page init
$sana_person_list->Page_Init();

// Page main
$sana_person_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_person_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fsana_personlist = new ew_Form("fsana_personlist", "list");
fsana_personlist.FormKeyCountName = '<?php echo $sana_person_list->FormKeyCountName ?>';

// Form_CustomValidate event
fsana_personlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_personlist.ValidateRequired = true;
<?php } else { ?>
fsana_personlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsana_personlist.Lists["x_gender"] = {"LinkField":"x_stateName","Ajax":true,"AutoFill":false,"DisplayFields":["x_stateName","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fsana_personlist.Lists["x_locationLevel1"] = {"LinkField":"x_locationName","Ajax":true,"AutoFill":false,"DisplayFields":["x_locationName","","",""],"ParentFields":[],"ChildFields":["x_locationLevel2"],"FilterFields":[],"Options":[],"Template":""};
fsana_personlist.Lists["x_locationLevel2"] = {"LinkField":"x_locationName","Ajax":true,"AutoFill":false,"DisplayFields":["x_locationName","","",""],"ParentFields":[],"ChildFields":["x_locationLevel3"],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
var CurrentSearchForm = fsana_personlistsrch = new ew_Form("fsana_personlistsrch");

// Validate function for search
fsana_personlistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_personID");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($sana_person->personID->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fsana_personlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_personlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fsana_personlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($sana_person_list->TotalRecs > 0 && $sana_person_list->ExportOptions->Visible()) { ?>
<?php $sana_person_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($sana_person_list->SearchOptions->Visible()) { ?>
<?php $sana_person_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($sana_person_list->FilterOptions->Visible()) { ?>
<?php $sana_person_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $sana_person_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($sana_person_list->TotalRecs <= 0)
			$sana_person_list->TotalRecs = $sana_person->SelectRecordCount();
	} else {
		if (!$sana_person_list->Recordset && ($sana_person_list->Recordset = $sana_person_list->LoadRecordset()))
			$sana_person_list->TotalRecs = $sana_person_list->Recordset->RecordCount();
	}
	$sana_person_list->StartRec = 1;
	if ($sana_person_list->DisplayRecs <= 0 || ($sana_person->Export <> "" && $sana_person->ExportAll)) // Display all records
		$sana_person_list->DisplayRecs = $sana_person_list->TotalRecs;
	if (!($sana_person->Export <> "" && $sana_person->ExportAll))
		$sana_person_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$sana_person_list->Recordset = $sana_person_list->LoadRecordset($sana_person_list->StartRec-1, $sana_person_list->DisplayRecs);

	// Set no record found message
	if ($sana_person->CurrentAction == "" && $sana_person_list->TotalRecs == 0) {
		if ($sana_person_list->SearchWhere == "0=101")
			$sana_person_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$sana_person_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$sana_person_list->RenderOtherOptions();
?>
<?php if ($sana_person->Export == "" && $sana_person->CurrentAction == "") { ?>
<form name="fsana_personlistsrch" id="fsana_personlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($sana_person_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fsana_personlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="sana_person">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$sana_person_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$sana_person->RowType = EW_ROWTYPE_SEARCH;

// Render row
$sana_person->ResetAttrs();
$sana_person_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($sana_person->personID->Visible) { // personID ?>
	<div id="xsc_personID" class="ewCell form-group">
		<label for="x_personID" class="ewSearchCaption ewLabel"><?php echo $sana_person->personID->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_personID" id="z_personID" value="="></span>
		<span class="ewSearchField">
<input type="text" data-table="sana_person" data-field="x_personID" name="x_personID" id="x_personID" placeholder="<?php echo ew_HtmlEncode($sana_person->personID->getPlaceHolder()) ?>" value="<?php echo $sana_person->personID->EditValue ?>"<?php echo $sana_person->personID->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($sana_person->personName->Visible) { // personName ?>
	<div id="xsc_personName" class="ewCell form-group">
		<label for="x_personName" class="ewSearchCaption ewLabel"><?php echo $sana_person->personName->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_personName" id="z_personName" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="sana_person" data-field="x_personName" name="x_personName" id="x_personName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->personName->getPlaceHolder()) ?>" value="<?php echo $sana_person->personName->EditValue ?>"<?php echo $sana_person->personName->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($sana_person->lastName->Visible) { // lastName ?>
	<div id="xsc_lastName" class="ewCell form-group">
		<label for="x_lastName" class="ewSearchCaption ewLabel"><?php echo $sana_person->lastName->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_lastName" id="z_lastName" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="sana_person" data-field="x_lastName" name="x_lastName" id="x_lastName" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($sana_person->lastName->getPlaceHolder()) ?>" value="<?php echo $sana_person->lastName->EditValue ?>"<?php echo $sana_person->lastName->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($sana_person->mobilePhone->Visible) { // mobilePhone ?>
	<div id="xsc_mobilePhone" class="ewCell form-group">
		<label for="x_mobilePhone" class="ewSearchCaption ewLabel"><?php echo $sana_person->mobilePhone->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_mobilePhone" id="z_mobilePhone" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="sana_person" data-field="x_mobilePhone" name="x_mobilePhone" id="x_mobilePhone" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($sana_person->mobilePhone->getPlaceHolder()) ?>" value="<?php echo $sana_person->mobilePhone->EditValue ?>"<?php echo $sana_person->mobilePhone->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($sana_person_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($sana_person_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $sana_person_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($sana_person_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($sana_person_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($sana_person_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($sana_person_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $sana_person_list->ShowPageHeader(); ?>
<?php
$sana_person_list->ShowMessage();
?>
<?php if ($sana_person_list->TotalRecs > 0 || $sana_person->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fsana_personlist" id="fsana_personlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_person_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_person_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_person">
<div id="gmp_sana_person" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($sana_person_list->TotalRecs > 0) { ?>
<table id="tbl_sana_personlist" class="table ewTable">
<?php echo $sana_person->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$sana_person_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$sana_person_list->RenderListOptions();

// Render list options (header, left)
$sana_person_list->ListOptions->Render("header", "left");
?>
<?php if ($sana_person->personID->Visible) { // personID ?>
	<?php if ($sana_person->SortUrl($sana_person->personID) == "") { ?>
		<th data-name="personID"><div id="elh_sana_person_personID" class="sana_person_personID"><div class="ewTableHeaderCaption"><?php echo $sana_person->personID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="personID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_person->SortUrl($sana_person->personID) ?>',1);"><div id="elh_sana_person_personID" class="sana_person_personID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_person->personID->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_person->personID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_person->personID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_person->personName->Visible) { // personName ?>
	<?php if ($sana_person->SortUrl($sana_person->personName) == "") { ?>
		<th data-name="personName"><div id="elh_sana_person_personName" class="sana_person_personName"><div class="ewTableHeaderCaption"><?php echo $sana_person->personName->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="personName"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_person->SortUrl($sana_person->personName) ?>',1);"><div id="elh_sana_person_personName" class="sana_person_personName">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_person->personName->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_person->personName->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_person->personName->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_person->lastName->Visible) { // lastName ?>
	<?php if ($sana_person->SortUrl($sana_person->lastName) == "") { ?>
		<th data-name="lastName"><div id="elh_sana_person_lastName" class="sana_person_lastName"><div class="ewTableHeaderCaption"><?php echo $sana_person->lastName->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="lastName"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_person->SortUrl($sana_person->lastName) ?>',1);"><div id="elh_sana_person_lastName" class="sana_person_lastName">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_person->lastName->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_person->lastName->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_person->lastName->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_person->nationalID->Visible) { // nationalID ?>
	<?php if ($sana_person->SortUrl($sana_person->nationalID) == "") { ?>
		<th data-name="nationalID"><div id="elh_sana_person_nationalID" class="sana_person_nationalID"><div class="ewTableHeaderCaption"><?php echo $sana_person->nationalID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nationalID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_person->SortUrl($sana_person->nationalID) ?>',1);"><div id="elh_sana_person_nationalID" class="sana_person_nationalID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_person->nationalID->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_person->nationalID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_person->nationalID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_person->nationalNumber->Visible) { // nationalNumber ?>
	<?php if ($sana_person->SortUrl($sana_person->nationalNumber) == "") { ?>
		<th data-name="nationalNumber"><div id="elh_sana_person_nationalNumber" class="sana_person_nationalNumber"><div class="ewTableHeaderCaption"><?php echo $sana_person->nationalNumber->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nationalNumber"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_person->SortUrl($sana_person->nationalNumber) ?>',1);"><div id="elh_sana_person_nationalNumber" class="sana_person_nationalNumber">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_person->nationalNumber->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_person->nationalNumber->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_person->nationalNumber->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_person->passportNumber->Visible) { // passportNumber ?>
	<?php if ($sana_person->SortUrl($sana_person->passportNumber) == "") { ?>
		<th data-name="passportNumber"><div id="elh_sana_person_passportNumber" class="sana_person_passportNumber"><div class="ewTableHeaderCaption"><?php echo $sana_person->passportNumber->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="passportNumber"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_person->SortUrl($sana_person->passportNumber) ?>',1);"><div id="elh_sana_person_passportNumber" class="sana_person_passportNumber">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_person->passportNumber->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_person->passportNumber->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_person->passportNumber->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_person->fatherName->Visible) { // fatherName ?>
	<?php if ($sana_person->SortUrl($sana_person->fatherName) == "") { ?>
		<th data-name="fatherName"><div id="elh_sana_person_fatherName" class="sana_person_fatherName"><div class="ewTableHeaderCaption"><?php echo $sana_person->fatherName->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fatherName"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_person->SortUrl($sana_person->fatherName) ?>',1);"><div id="elh_sana_person_fatherName" class="sana_person_fatherName">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_person->fatherName->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_person->fatherName->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_person->fatherName->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_person->gender->Visible) { // gender ?>
	<?php if ($sana_person->SortUrl($sana_person->gender) == "") { ?>
		<th data-name="gender"><div id="elh_sana_person_gender" class="sana_person_gender"><div class="ewTableHeaderCaption"><?php echo $sana_person->gender->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="gender"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_person->SortUrl($sana_person->gender) ?>',1);"><div id="elh_sana_person_gender" class="sana_person_gender">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_person->gender->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sana_person->gender->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_person->gender->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_person->locationLevel1->Visible) { // locationLevel1 ?>
	<?php if ($sana_person->SortUrl($sana_person->locationLevel1) == "") { ?>
		<th data-name="locationLevel1"><div id="elh_sana_person_locationLevel1" class="sana_person_locationLevel1"><div class="ewTableHeaderCaption"><?php echo $sana_person->locationLevel1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="locationLevel1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_person->SortUrl($sana_person->locationLevel1) ?>',1);"><div id="elh_sana_person_locationLevel1" class="sana_person_locationLevel1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_person->locationLevel1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sana_person->locationLevel1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_person->locationLevel1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_person->locationLevel2->Visible) { // locationLevel2 ?>
	<?php if ($sana_person->SortUrl($sana_person->locationLevel2) == "") { ?>
		<th data-name="locationLevel2"><div id="elh_sana_person_locationLevel2" class="sana_person_locationLevel2"><div class="ewTableHeaderCaption"><?php echo $sana_person->locationLevel2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="locationLevel2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_person->SortUrl($sana_person->locationLevel2) ?>',1);"><div id="elh_sana_person_locationLevel2" class="sana_person_locationLevel2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_person->locationLevel2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sana_person->locationLevel2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_person->locationLevel2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_person->mobilePhone->Visible) { // mobilePhone ?>
	<?php if ($sana_person->SortUrl($sana_person->mobilePhone) == "") { ?>
		<th data-name="mobilePhone"><div id="elh_sana_person_mobilePhone" class="sana_person_mobilePhone"><div class="ewTableHeaderCaption"><?php echo $sana_person->mobilePhone->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mobilePhone"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_person->SortUrl($sana_person->mobilePhone) ?>',1);"><div id="elh_sana_person_mobilePhone" class="sana_person_mobilePhone">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_person->mobilePhone->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_person->mobilePhone->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_person->mobilePhone->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_person->picture->Visible) { // picture ?>
	<?php if ($sana_person->SortUrl($sana_person->picture) == "") { ?>
		<th data-name="picture"><div id="elh_sana_person_picture" class="sana_person_picture"><div class="ewTableHeaderCaption"><?php echo $sana_person->picture->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="picture"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $sana_person->SortUrl($sana_person->picture) ?>',1);"><div id="elh_sana_person_picture" class="sana_person_picture">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_person->picture->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($sana_person->picture->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_person->picture->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$sana_person_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($sana_person->ExportAll && $sana_person->Export <> "") {
	$sana_person_list->StopRec = $sana_person_list->TotalRecs;
} else {

	// Set the last record to display
	if ($sana_person_list->TotalRecs > $sana_person_list->StartRec + $sana_person_list->DisplayRecs - 1)
		$sana_person_list->StopRec = $sana_person_list->StartRec + $sana_person_list->DisplayRecs - 1;
	else
		$sana_person_list->StopRec = $sana_person_list->TotalRecs;
}
$sana_person_list->RecCnt = $sana_person_list->StartRec - 1;
if ($sana_person_list->Recordset && !$sana_person_list->Recordset->EOF) {
	$sana_person_list->Recordset->MoveFirst();
	$bSelectLimit = $sana_person_list->UseSelectLimit;
	if (!$bSelectLimit && $sana_person_list->StartRec > 1)
		$sana_person_list->Recordset->Move($sana_person_list->StartRec - 1);
} elseif (!$sana_person->AllowAddDeleteRow && $sana_person_list->StopRec == 0) {
	$sana_person_list->StopRec = $sana_person->GridAddRowCount;
}

// Initialize aggregate
$sana_person->RowType = EW_ROWTYPE_AGGREGATEINIT;
$sana_person->ResetAttrs();
$sana_person_list->RenderRow();
while ($sana_person_list->RecCnt < $sana_person_list->StopRec) {
	$sana_person_list->RecCnt++;
	if (intval($sana_person_list->RecCnt) >= intval($sana_person_list->StartRec)) {
		$sana_person_list->RowCnt++;

		// Set up key count
		$sana_person_list->KeyCount = $sana_person_list->RowIndex;

		// Init row class and style
		$sana_person->ResetAttrs();
		$sana_person->CssClass = "";
		if ($sana_person->CurrentAction == "gridadd") {
		} else {
			$sana_person_list->LoadRowValues($sana_person_list->Recordset); // Load row values
		}
		$sana_person->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$sana_person->RowAttrs = array_merge($sana_person->RowAttrs, array('data-rowindex'=>$sana_person_list->RowCnt, 'id'=>'r' . $sana_person_list->RowCnt . '_sana_person', 'data-rowtype'=>$sana_person->RowType));

		// Render row
		$sana_person_list->RenderRow();

		// Render list options
		$sana_person_list->RenderListOptions();
?>
	<tr<?php echo $sana_person->RowAttributes() ?>>
<?php

// Render list options (body, left)
$sana_person_list->ListOptions->Render("body", "left", $sana_person_list->RowCnt);
?>
	<?php if ($sana_person->personID->Visible) { // personID ?>
		<td data-name="personID"<?php echo $sana_person->personID->CellAttributes() ?>>
<span id="el<?php echo $sana_person_list->RowCnt ?>_sana_person_personID" class="sana_person_personID">
<span<?php echo $sana_person->personID->ViewAttributes() ?>>
<?php echo $sana_person->personID->ListViewValue() ?></span>
</span>
<a id="<?php echo $sana_person_list->PageObjName . "_row_" . $sana_person_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($sana_person->personName->Visible) { // personName ?>
		<td data-name="personName"<?php echo $sana_person->personName->CellAttributes() ?>>
<span id="el<?php echo $sana_person_list->RowCnt ?>_sana_person_personName" class="sana_person_personName">
<span<?php echo $sana_person->personName->ViewAttributes() ?>>
<?php echo $sana_person->personName->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_person->lastName->Visible) { // lastName ?>
		<td data-name="lastName"<?php echo $sana_person->lastName->CellAttributes() ?>>
<span id="el<?php echo $sana_person_list->RowCnt ?>_sana_person_lastName" class="sana_person_lastName">
<span<?php echo $sana_person->lastName->ViewAttributes() ?>>
<?php echo $sana_person->lastName->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_person->nationalID->Visible) { // nationalID ?>
		<td data-name="nationalID"<?php echo $sana_person->nationalID->CellAttributes() ?>>
<span id="el<?php echo $sana_person_list->RowCnt ?>_sana_person_nationalID" class="sana_person_nationalID">
<span<?php echo $sana_person->nationalID->ViewAttributes() ?>>
<?php echo $sana_person->nationalID->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_person->nationalNumber->Visible) { // nationalNumber ?>
		<td data-name="nationalNumber"<?php echo $sana_person->nationalNumber->CellAttributes() ?>>
<span id="el<?php echo $sana_person_list->RowCnt ?>_sana_person_nationalNumber" class="sana_person_nationalNumber">
<span<?php echo $sana_person->nationalNumber->ViewAttributes() ?>>
<?php echo $sana_person->nationalNumber->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_person->passportNumber->Visible) { // passportNumber ?>
		<td data-name="passportNumber"<?php echo $sana_person->passportNumber->CellAttributes() ?>>
<span id="el<?php echo $sana_person_list->RowCnt ?>_sana_person_passportNumber" class="sana_person_passportNumber">
<span<?php echo $sana_person->passportNumber->ViewAttributes() ?>>
<?php echo $sana_person->passportNumber->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_person->fatherName->Visible) { // fatherName ?>
		<td data-name="fatherName"<?php echo $sana_person->fatherName->CellAttributes() ?>>
<span id="el<?php echo $sana_person_list->RowCnt ?>_sana_person_fatherName" class="sana_person_fatherName">
<span<?php echo $sana_person->fatherName->ViewAttributes() ?>>
<?php echo $sana_person->fatherName->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_person->gender->Visible) { // gender ?>
		<td data-name="gender"<?php echo $sana_person->gender->CellAttributes() ?>>
<span id="el<?php echo $sana_person_list->RowCnt ?>_sana_person_gender" class="sana_person_gender">
<span<?php echo $sana_person->gender->ViewAttributes() ?>>
<?php echo $sana_person->gender->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_person->locationLevel1->Visible) { // locationLevel1 ?>
		<td data-name="locationLevel1"<?php echo $sana_person->locationLevel1->CellAttributes() ?>>
<span id="el<?php echo $sana_person_list->RowCnt ?>_sana_person_locationLevel1" class="sana_person_locationLevel1">
<span<?php echo $sana_person->locationLevel1->ViewAttributes() ?>>
<?php echo $sana_person->locationLevel1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_person->locationLevel2->Visible) { // locationLevel2 ?>
		<td data-name="locationLevel2"<?php echo $sana_person->locationLevel2->CellAttributes() ?>>
<span id="el<?php echo $sana_person_list->RowCnt ?>_sana_person_locationLevel2" class="sana_person_locationLevel2">
<span<?php echo $sana_person->locationLevel2->ViewAttributes() ?>>
<?php echo $sana_person->locationLevel2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_person->mobilePhone->Visible) { // mobilePhone ?>
		<td data-name="mobilePhone"<?php echo $sana_person->mobilePhone->CellAttributes() ?>>
<span id="el<?php echo $sana_person_list->RowCnt ?>_sana_person_mobilePhone" class="sana_person_mobilePhone">
<span<?php echo $sana_person->mobilePhone->ViewAttributes() ?>>
<?php echo $sana_person->mobilePhone->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($sana_person->picture->Visible) { // picture ?>
		<td data-name="picture"<?php echo $sana_person->picture->CellAttributes() ?>>
<span id="el<?php echo $sana_person_list->RowCnt ?>_sana_person_picture" class="sana_person_picture">
<span>
<?php echo ew_GetFileViewTag($sana_person->picture, $sana_person->picture->ListViewValue()) ?>
</span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$sana_person_list->ListOptions->Render("body", "right", $sana_person_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($sana_person->CurrentAction <> "gridadd")
		$sana_person_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($sana_person->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($sana_person_list->Recordset)
	$sana_person_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($sana_person->CurrentAction <> "gridadd" && $sana_person->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($sana_person_list->Pager)) $sana_person_list->Pager = new cNumericPager($sana_person_list->StartRec, $sana_person_list->DisplayRecs, $sana_person_list->TotalRecs, $sana_person_list->RecRange) ?>
<?php if ($sana_person_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($sana_person_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $sana_person_list->PageUrl() ?>start=<?php echo $sana_person_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($sana_person_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $sana_person_list->PageUrl() ?>start=<?php echo $sana_person_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($sana_person_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $sana_person_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($sana_person_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $sana_person_list->PageUrl() ?>start=<?php echo $sana_person_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($sana_person_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $sana_person_list->PageUrl() ?>start=<?php echo $sana_person_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $sana_person_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $sana_person_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $sana_person_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($sana_person_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($sana_person_list->TotalRecs == 0 && $sana_person->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($sana_person_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fsana_personlistsrch.Init();
fsana_personlistsrch.FilterList = <?php echo $sana_person_list->GetFilterList() ?>;
fsana_personlist.Init();
</script>
<?php
$sana_person_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_person_list->Page_Terminate();
?>
