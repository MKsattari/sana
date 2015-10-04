<!--##session phpload##-->
<!--## if (CTRL.CtrlID == "grid") { ##-->
<!--## } else if (!PROJ.Cache || (CTRL.CtrlID == "preview" || CTRL.CtrlID == "blobview")) { ##-->
<!--## if (CTRL.CtrlID == "preview") sCharset = ", 'utf-8'"; else sCharset = ""; ##-->
<?php ew_Header(FALSE<!--##=sCharset##-->) ?>
<!--## } else { ##-->
<?php ew_Header(TRUE) ?>
<!--## } ##-->
<!--##=SYSTEMFUNCTIONS.ScriptTimeout()##-->
<?php
// Create page object
if (!isset($<!--##=sPageObj##-->)) $<!--##=sPageObj##--> = new c<!--##=sPageObj##-->();

// Page init
$<!--##=sPageObj##-->->Page_Init();

// Page main
$<!--##=sPageObj##-->->Page_Main();

<!--##
	if (CTRL.CtrlType.toLowerCase() == "table" || CTRL.CtrlType.toLowerCase() == "report" || CTRL.CtrlType.toLowerCase() == "other" && CTRL.CtrlID.toLowerCase() != "logout") {
		if (SYSTEMFUNCTIONS.ServerScriptExist("Global","Page_Rendering")) {
##-->
// Global Page Rendering event (in userfn*.php)
Page_Rendering();
<!--##
		}


		if (SYSTEMFUNCTIONS.ServerScriptExist(sCtrlType,"Page_Render") && CTRL.CtrlID != "custom") {
##-->
// Page Rendering event
$<!--##=sPageObj##-->->Page_Render();
<!--##
		}
	}
##-->
?>
<!--##/session##-->

<!--##session phpunload##-->
<?php
$<!--##=sPageObj##-->->Page_Terminate();
?>
<!--##/session##-->

<!--##session phppageclassbegin##-->
<!--##
	bExtendPageClass = (CTRL.CtrlType.toLowerCase() == "table" || CTRL.CtrlType.toLowerCase() == "report" && ew_IsNotEmpty(TABLE.TblRptSrc) || CTRL.CtrlType.toLowerCase() == "field" || (bUserTable && CTRL.CtrlID.toLowerCase() == "login") || CTRL.CtrlID.toLowerCase() == "register" || CTRL.CtrlID.toLowerCase() == "forgotpwd" || CTRL.CtrlID.toLowerCase() == "changepwd" || CTRL.CtrlID.toLowerCase() == "userpriv");

	// Field for report, use source table instead
	if (CTRL.CtrlType.toLowerCase() == "field" && TABLE.TblType == "REPORT") {
		TABLE = DB.Tables.Item(TABLE.TblRptSrc);
		gsTblVar = TABLE.TblVar;
		sTblObj = gsTblVar;
	}
##-->
<?php
//
// Page class
//
$<!--##=sPageObj##--> = NULL; // Initialize page object first
<!--## if (bExtendPageClass) { ##-->
class c<!--##=sPageObj##--> extends c<!--##=sTblObj##--> {
<!--## } else { ##-->
class c<!--##=sPageObj##--> {
<!--## } ##-->

	// Page ID
	var $PageID = '<!--##=sCtrlID##-->';

	// Project ID
	var $ProjectID = "<!--##=PROJ.ProjID##-->";

<!--## if (CTRL.CtrlType.toLowerCase() == "table" || CTRL.CtrlType.toLowerCase() == "report") { ##-->
	// Table name
	var $TableName = '<!--##=SQuote(TABLE.TblName)##-->';
<!--## } ##-->

	// Page object name
	var $PageObjName = '<!--##=sPageObj##-->';

<!--## if (CTRL.CtrlID == "gridcls" || CTRL.CtrlID == "list") { ##-->

	// Grid form hidden field names
	var $FormName = '<!--##=sFormName##-->';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

<!--## } ##-->

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
<!--## if (CTRL.CtrlType.toLowerCase() == "table") { ##-->
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
<!--## } ##-->
		return $PageUrl;
	}
	
<!--## if ((CTRL.CtrlType.toLowerCase() == "table" && (CTRL.CtrlID.toLowerCase() == "list" || CTRL.CtrlID.toLowerCase() == "view")) || CTRL.CtrlID.toLowerCase() == "report") { ##-->

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
	var $ExportExcelCustom = <!--##=ew_Val(bUseCustomTemplate)##-->;
	var $ExportWordCustom = <!--##=ew_Val(bUseCustomTemplate)##-->;
	var $ExportPdfCustom = <!--##=ew_Val(bUseCustomTemplate)##-->;
	var $ExportEmailCustom = <!--##=ew_Val(bUseCustomTemplate)##-->;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

<!--## } ##-->

<!--## if (CTRL.CtrlType.toLowerCase() == "table") { ##-->
<!--## if (TABLE.TblAuditTrail) { ##-->
    var $AuditTrailOnAdd = <!--##=ew_Val(bAuditTrailOnAdd)##-->;
    var $AuditTrailOnEdit = <!--##=ew_Val(bAuditTrailOnEdit)##-->;
    var $AuditTrailOnDelete = <!--##=ew_Val(bAuditTrailOnDelete)##-->;
    var $AuditTrailOnView = <!--##=ew_Val(bAuditTrailOnView)##-->;
    var $AuditTrailOnViewData = <!--##=ew_Val(bAuditTrailOnViewData)##-->;
    var $AuditTrailOnSearch = <!--##=ew_Val(bAuditTrailOnSearch)##-->;
<!--## } ##-->
<!--## } ##-->

<!--## if (CTRL.CtrlID != "blobview") { ##-->

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
		$hidden = <!--##=ew_Val(bUseJavaScriptMessage)##-->;
		$html = "";
		// Message
		$sMessage = $this->getMessage();
	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist(sCtrlType,"Message_Showing")) { ##-->
		$this->Message_Showing($sMessage, "");
	<!--## } ##-->
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}
		// Warning message
		$sWarningMessage = $this->getWarningMessage();
	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist(sCtrlType,"Message_Showing")) { ##-->
		$this->Message_Showing($sWarningMessage, "warning");
	<!--## } ##-->
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}
		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist(sCtrlType,"Message_Showing")) { ##-->
		$this->Message_Showing($sSuccessMessage, "success");
	<!--## } ##-->
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}
		// Failure message
		$sErrorMessage = $this->getFailureMessage();
	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist(sCtrlType,"Message_Showing")) { ##-->
		$this->Message_Showing($sErrorMessage, "failure");
	<!--## } ##-->
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}

<!--## } ##-->

<!--## if (CTRL.CtrlType.toLowerCase() == "table" || CTRL.CtrlID.toLowerCase() == "report" || CTRL.CtrlType.toLowerCase() == "other" && CTRL.CtrlID.toLowerCase() != "logout") { ##-->
	var $PageHeader;
	var $PageFooter;
	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist(sCtrlType,"Page_DataRendering")) { ##-->
		$this->Page_DataRendering($sHeader);
	<!--## } ##-->
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}
	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist(sCtrlType,"Page_DataRendered")) { ##-->
		$this->Page_DataRendered($sFooter);
	<!--## } ##-->
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
<!--## if (CTRL.CtrlType.toLowerCase() == "table") { ##-->
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
<!--## } else { ##-->
		return TRUE;
<!--## } ##-->
	}

<!--## } ##-->

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

	<!--## if (bUserTable) { ##-->
		global $UserTable, $UserTableConn;
	<!--## } ##-->

<!--## if (CTRL.CtrlID == "gridcls") { ##-->

		$this->FormActionName .= '_' . $this->FormName;
		$this->FormKeyName .= '_' . $this->FormName;
		$this->FormOldKeyName .= '_' . $this->FormName;
		$this->FormBlankRowName .= '_' . $this->FormName;
		$this->FormKeyCountName .= '_' . $this->FormName;

		$GLOBALS["Grid"] = &$this;

<!--## } else { ##-->

		$GLOBALS["Page"] = &$this;

<!--## } ##-->

	<!--## if (CTRL.CtrlID == "login") { ##-->
		$this->TokenTimeout = 48 * 60 * 60; // 48 hours for login
	<!--## } else { ##-->
		$this->TokenTimeout = ew_SessionTimeoutTime();
	<!--## } ##-->

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

<!--## if (bExtendPageClass) { ##-->
		// Parent constuctor
		parent::__construct();

	<!--## if (CTRL.CtrlID != "custom") { ##-->

		// Table object (<!--##=gsTblVar##-->)
		if (!isset($GLOBALS["<!--##=gsTblVar##-->"]) || get_class($GLOBALS["<!--##=gsTblVar##-->"]) == "c<!--##=gsTblVar##-->") {

			$GLOBALS["<!--##=gsTblVar##-->"] = &$this;

<!--## if (CTRL.CtrlID == "gridcls") { ##-->
//			$GLOBALS["MasterTable"] = &$GLOBALS["Table"];
//			if (!isset($GLOBALS["Table"])) $GLOBALS["Table"] = &$GLOBALS["<!--##=gsTblVar##-->"];
<!--## } else { ##-->
			$GLOBALS["Table"] = &$GLOBALS["<!--##=gsTblVar##-->"];
<!--## } ##-->

		}

	<!--## } ##-->

<!--## } ##-->

<!--## if (CTRL.CtrlType.toLowerCase() == "table" || CTRL.CtrlID.toLowerCase() == "report" || CTRL.CtrlType.toLowerCase() == "field") { ##-->

	<!--## if (CTRL.CtrlID.toLowerCase() == "list" || CTRL.CtrlID.toLowerCase() == "report") { ##-->

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
	<!--## if (CTRL.CtrlID.toLowerCase() == "list") { ##-->
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
	<!--## if (bDetailAdd && nDetailTableCount > 0) { ##-->
		$this->AddUrl = "<!--##=sFnAdd##-->?" . EW_TABLE_SHOW_DETAIL . "=";
	<!--## } else { ##-->
		$this->AddUrl = "<!--##=sFnAdd##-->";
	<!--## } ##-->
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "<!--##=sFnDelete##-->";
		$this->MultiUpdateUrl = "<!--##=sFnUpdate##-->";
	<!--## } ##-->

	<!--## } else if (CTRL.CtrlID.toLowerCase() == "view") { ##-->

		$KeyUrl = "";
		<!--##
			for (var i = 0; i < nKeyCount; i++) {
				if (GetFldObj(arKeyFlds[i])) {
		##-->
		if (@$_GET["<!--##=gsFldParm##-->"] <> "") {
			$this->RecKey["<!--##=gsFldParm##-->"] = $_GET["<!--##=gsFldParm##-->"];
			$KeyUrl .= "&amp;<!--##=gsFldParm##-->=" . urlencode($this->RecKey["<!--##=gsFldParm##-->"]);
		}
		<!--##
				}
			}
		##-->
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

	<!--## } ##-->

	<!--##
		// Initialize other table objects
		for (var tmpTblVar in dIncludeTable) {
	##-->
		// Table object (<!--##=SQuote(tmpTblVar)##-->)
		if (!isset($GLOBALS['<!--##=SQuote(tmpTblVar)##-->'])) $GLOBALS['<!--##=SQuote(tmpTblVar)##-->'] = new c<!--##=tmpTblVar##-->();
	<!--##
		}
	##-->

<!--## } else if (CTRL.CtrlID.toLowerCase() == "register" || CTRL.CtrlID.toLowerCase() == "changepwd" || CTRL.CtrlID.toLowerCase() == "forgotpwd" || CTRL.CtrlID.toLowerCase() == "userpriv" || ((CTRL.CtrlID.toLowerCase() == "login" || CTRL.CtrlID.toLowerCase() == "logout") && bUserTable)) { ##-->

	<!--## if (CTRL.CtrlID.toLowerCase() == "login" || CTRL.CtrlID.toLowerCase() == "forgotpwd" || CTRL.CtrlID.toLowerCase() == "changepwd" || CTRL.CtrlID.toLowerCase() == "userpriv") { ##-->

		if (!isset($GLOBALS["<!--##=gsTblVar##-->"])) $GLOBALS["<!--##=gsTblVar##-->"] = &$this;

	<!--## } else { ##-->

		if (!isset($GLOBALS["<!--##=gsTblVar##-->"])) $GLOBALS["<!--##=gsTblVar##-->"] = new c<!--##=gsTblVar##-->();

	<!--## } ##-->

	<!--##
		if (CTRL.CtrlID.toLowerCase() == "register") {
			// Initialize other table objects
			for (var tmpTblVar in dIncludeTable) {
	##-->
		// Table object (<!--##=SQuote(tmpTblVar)##-->)
		if (!isset($GLOBALS['<!--##=SQuote(tmpTblVar)##-->'])) $GLOBALS['<!--##=SQuote(tmpTblVar)##-->'] = new c<!--##=tmpTblVar##-->();
	<!--##
			}
		}
	##-->

<!--## } ##-->

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", '<!--##=sCtrlID##-->', TRUE);

<!--## if (CTRL.CtrlType.toLowerCase() == "table" || CTRL.CtrlType.toLowerCase() == "report" || CTRL.CtrlType.toLowerCase() == "field") { ##-->
		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", '<!--##=SQuote(TABLE.TblName)##-->', TRUE);
<!--## } ##-->

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
<!--## if (bExtendPageClass) { ##-->
		if (!isset($conn)) $conn = ew_Connect($this->DBID);
<!--## } else { ##-->
		if (!isset($conn)) $conn = ew_Connect();
<!--## } ##-->

	<!--## if (bUserTable) { ##-->
		// User table object (<!--##=sSecTblVar##-->)
		if (!isset($UserTable)) {
			$UserTable = new c<!--##=sSecTblVar##-->();
			$UserTableConn = Conn($UserTable->DBID);
		}
    <!--## } ##-->

<!--## if (CTRL.CtrlID.toLowerCase() == "list" || CTRL.CtrlID.toLowerCase() == "gridcls" || CTRL.CtrlID.toLowerCase() == "preview") { ##-->
		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;
<!--## } ##-->

<!--## if (CTRL.CtrlID.toLowerCase() == "list" || CTRL.CtrlID.toLowerCase() == "report" || CTRL.CtrlID.toLowerCase() == "view") { ##-->
		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";
<!--## } ##-->

<!--## if (CTRL.CtrlID.toLowerCase() == "gridcls") { ##-->
		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
<!--## } else if (CTRL.CtrlID.toLowerCase() == "list") { ##-->
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
<!--## } else if (CTRL.CtrlID.toLowerCase() == "view") { ##-->
		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
<!--## } else if (CTRL.CtrlID.toLowerCase() == "preview") { ##-->
		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
<!--## } ##-->

<!--## if (CTRL.CtrlID.toLowerCase() == "list") { ##-->
		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption <!--##=sFormNameSearch##-->";

		// List actions
		$this->ListActions = new cListActions();
<!--## } ##-->

	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		
<!--## if (bUserProfile) { ##-->
		// User profile
		$UserProfile = new cUserProfile();
<!--## } ##-->

<!--## if (bSecurityEnabled) { ##-->
		// Security
		$Security = new cAdvancedSecurity();
		<!--##~SYSTEMFUNCTIONS.Security()##-->
<!--## } ##-->

<!--## if (bCheckConcurrentUser && (CTRL.CtrlType == "table" || CTRL.CtrlID == "changepwd")) { ##-->
		// Update last accessed time
		if ($UserProfile->IsValidUser(CurrentUserName(), session_id())) {
		} else {
			echo $Language->Phrase("UserProfileCorrupted");
		}
<!--## } ##-->

<!--## if (CTRL.CtrlID == "add" || CTRL.CtrlID == "addopt" || CTRL.CtrlID == "register" || CTRL.CtrlID == "edit" || CTRL.CtrlID == "update" || CTRL.CtrlID == "search" || (CTRL.CtrlID == "list" && (bInlineEdit || bGridEdit || bInlineAdd || bInlineCopy || bGridAdd))) { ##-->
		// Create form object
		$objForm = new cFormObj();
<!--## } ##-->

<!--##
	sExpStart = "";
	sExpEnd = "";
	if (CTRL.CtrlType.toLowerCase() == "table" || CTRL.CtrlID.toLowerCase() == "report") {
		if ((CTRL.CtrlID == "list" && bListExport) ||
			(CTRL.CtrlID == "view" && bViewExport) ||
			CTRL.CtrlID == "report") {
			sExpStart = "<?php if ($" + gsTblVar + "->Export == \"\") { ?>";
			sExpEnd = "<?php } ?>";
##-->

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
	<!--## if (CTRL.CtrlType.toLowerCase() == "table") { ##-->
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
	<!--## } ##-->
		}
	
	<!--## if (CTRL.CtrlType.toLowerCase() == "table" || CTRL.CtrlID.toLowerCase() == "report") { ##-->
		$gsExportFile = $this->TableVar; // Get export file, used in header
	<!--## } ##-->

	<!--## if (CTRL.CtrlID == "view") { ##-->
	<!--##
		for (var i = 0; i < nKeyCount; i++) {
			if (GetFldObj(arKeyFlds[i])) {
	##-->
		if (@$_GET["<!--##=gsFldParm##-->"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["<!--##=gsFldParm##-->"]);
		}
	<!--##
			}
		}
	##-->
	<!--## } ##-->

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;

		$gsExport = $this->Export; // Get export parameter, used in header

	<!--## if (bUseCustomTemplate) { ##-->

		// Custom export (post back from ew_ApplyTemplate), export and terminate page
		if (@$_POST["customexport"] <> "") {
			$this->CustomExport = $_POST["customexport"];
			$this->Export = $this->CustomExport;
			$this->Page_Terminate();
			exit();
		}

	<!--## } ##-->

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";

<!--##
		}
	} else if (CTRL.CtrlID == "custompage") {
##-->
		if (@$_GET["export"] <> "")
			$gsExport = $_GET["export"]; // Get export parameter, used in header
<!--##
	}
##-->

<!--##
	if (bExtendPageClass && CTRL.CtrlID != "gridcls") {
##-->
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
<!--##
	}
##-->

<!--##
	if (CTRL.CtrlID == "list" || CTRL.CtrlID == "gridcls") {
##-->
		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

<!--##
	}

	if (CTRL.CtrlID == "list" || CTRL.CtrlID == "gridcls" || CTRL.CtrlID == "preview") {
##-->
		// Set up list options
		$this->SetupListOptions();
<!--##
	}

	if (((CTRL.CtrlID == "list" || CTRL.CtrlID == "report") && bListExport) ||
		(CTRL.CtrlID == "view" && bViewExport)) {
##-->
		// Setup export options
		$this->SetupExportOptions();
<!--##
	}
##-->

<!--## if (CTRL.CtrlSkipHeaderFooter) { ##-->
		global $gbOldSkipHeaderFooter, $gbSkipHeaderFooter;
		$gbOldSkipHeaderFooter = $gbSkipHeaderFooter;
		$gbSkipHeaderFooter = TRUE;
<!--## } ##-->

<!--##
	// Hide non-updatable fields for add/edit
	if (CTRL.CtrlType.toLowerCase() == "table" && !bUseCustomTemplate) {
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				if (goFld.FldIsPrimaryKey && goFld.FldAutoIncrement) {
##-->
		$this-><!--##=gsFldParm##-->->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
<!--##
				} else if (!IsFldUpdatable(goFld) || (ew_IsNotEmpty(goFld.FldAutoUpdateValue) && (CTRL.CtrlID == "list" || CTRL.CtrlID == "gridcls"))) {
##-->
		$this-><!--##=gsFldParm##-->->Visible = !$this->IsAddOrEdit();
<!--##
				}
			}
		} // Field
	}
##-->

<!--## if (bMultiPage) { ##-->
		// Set up multi page object
		$this->SetupMultiPages();
<!--## } ##-->

<!--## if (bShowMultiPageForDetails && (CTRL.CtrlID == "add" || CTRL.CtrlID == "edit" || CTRL.CtrlID == "view")) { ##-->
		// Set up detail page object
		$this->SetupDetailPages();
<!--## } ##-->

<!--##
	if (SYSTEMFUNCTIONS.ServerScriptExist("Global","Page_Loading")) {
##-->
		// Global Page Loading event (in userfn*.php)
		Page_Loading();
<!--##
		if (CTRL.CtrlID == "blobview") {
##-->
		ob_clean(); // Clear output
<!--##
		}
	}
##-->

<!--##
	if (CTRL.CtrlID != "custom" && CTRL.CtrlID != "blobview") {
		if (SYSTEMFUNCTIONS.ServerScriptExist(sCtrlType,"Page_Load")) {
##-->
		// Page Load event
		$this->Page_Load();
<!--##
		}
	}
##-->

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

<!--## if (CTRL.CtrlID == "list" || CTRL.CtrlID == "gridcls" || CTRL.CtrlID == "add" || CTRL.CtrlID == "addopt" || CTRL.CtrlID == "edit" || CTRL.CtrlID == "update" || CTRL.CtrlID == "register") { ##-->
		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
<!--##
	// Auto fill for detail tables
	WRKTABLE = TABLE; // Save table
	if (nDetailTableCount > 0) {
		for (var i = 0, len = arDetailTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arDetailTables[i].index];
			TABLE = DB.Tables(MasterDetail.DetailTable);
			if (TABLE.TblGen && TABLE.TblType != "REPORT") {
				sDetailTblVar = TABLE.TblVar;
				sDetailPageObj = ew_GetPageObjByCtrlId("grid");
				sDetailTblName = TABLE.TblName;
##-->
			// Process auto fill for detail table '<!--##=sDetailTblName##-->'
			if (@$_POST["grid"] == "f<!--##=sDetailTblVar##-->grid") {
				if (!isset($GLOBALS["<!--##=sDetailPageObj##-->"])) $GLOBALS["<!--##=sDetailPageObj##-->"] = new c<!--##=sDetailPageObj##-->;
				$GLOBALS["<!--##=sDetailPageObj##-->"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
<!--##
			}
		} // MasterDetail
	}
	TABLE = WRKTABLE; // Restore table
##-->

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
<!--## } ##-->

		// Create Token
		$this->CreateToken();

<!--##
	if (CTRL.CtrlID == "list" || CTRL.CtrlID == "gridcls" || CTRL.CtrlID == "preview") {
##-->

<!--## if ((CTRL.CtrlID == "list" || CTRL.CtrlID == "gridcls") && nMasterTableCount > 0) { ##-->
		// Set up master detail parameters
		$this->SetUpMasterParms();
<!--## } ##-->

		// Setup other options
		$this->SetupOtherOptions();

<!--## if (CTRL.CtrlID == "list") { ##-->

	<!--## if (bUserTable && TABLE.TblName == PROJ.SecTbl) { ##-->
		<!--## if (bResendRegisterEmail) { ##-->
		$this->ListActions->Add("resendregisteremail", $Language->Phrase("ResendRegisterEmailBtn"), IsAdmin(), EW_ACTION_AJAX, EW_ACTION_SINGLE);
		<!--## } ##-->
		<!--## if (bResetConcurrentUser) { ##-->
		$this->ListActions->Add("resetconcurrentuser", $Language->Phrase("ResetConcurrentUserBtn"), IsAdmin(), EW_ACTION_AJAX, EW_ACTION_SINGLE);
		<!--## } ##-->
		<!--## if (bResetLoginRetry) { ##-->
		$this->ListActions->Add("resetloginretry", $Language->Phrase("ResetLoginRetryBtn"), IsAdmin(), EW_ACTION_AJAX, EW_ACTION_SINGLE);
		<!--## } ##-->
		<!--## if (bSetPasswordExpired) { ##-->
		$this->ListActions->Add("setpasswordexpired", $Language->Phrase("SetPasswordExpiredBtn"), IsAdmin(), EW_ACTION_AJAX, EW_ACTION_SINGLE);
		<!--## } ##-->
	<!--## } ##-->

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

<!--## } ##-->

<!--##
	}
##-->

	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

	<!--## if (CTRL.CtrlSkipHeaderFooter) { ##-->
		global $gbOldSkipHeaderFooter, $gbSkipHeaderFooter;
		$gbSkipHeaderFooter = $gbOldSkipHeaderFooter;
	<!--## } ##-->

	<!--## if (CTRL.CtrlID != "gridcls") { ##-->

	<!--## if (bUseCustomTemplate) { ##-->
		if (@$_POST["customexport"] == "") {
	<!--## } ##-->

	<!--##
		if (CTRL.CtrlID != "custom" && CTRL.CtrlID != "blobview") {
			if (SYSTEMFUNCTIONS.ServerScriptExist(sCtrlType,"Page_Unload")) {
	##-->
		// Page Unload event
		$this->Page_Unload();
	<!--##
			}
		}
	##-->

	<!--##
		if (SYSTEMFUNCTIONS.ServerScriptExist("Global","Page_Unloaded")) {
	##-->
		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
	<!--##
		}
	##-->

	<!--## if (bUseCustomTemplate) { ##-->
		}
	<!--## } ##-->

	<!--## } ##-->

		// Export
	<!--## if (CTRL.CtrlType.toLowerCase() == "table") { ##-->
		global $EW_EXPORT, $<!--##=gsTblVar##-->;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
		<!--## if (bUseCustomTemplate) { ##-->
			if (is_array(@$_SESSION[EW_SESSION_TEMP_IMAGES])) // Restore temp images
				$gTmpImages = @$_SESSION[EW_SESSION_TEMP_IMAGES];
			if (@$_POST["data"] <> "")
				$sContent = $_POST["data"];
			$gsExportFile = @$_POST["filename"];
		<!--## } else { ##-->
				$sContent = ob_get_contents();
		<!--## } ##-->
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($<!--##=gsTblVar##-->);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
	<!--## } else if (CTRL.CtrlID.toLowerCase() == "report") { ##-->
		global $EW_EXPORT_REPORT;
		if ($this->Export <> "" && array_key_exists($this->Export, $EW_EXPORT_REPORT)) {
			$sContent = ob_get_contents();
			$fn = $EW_EXPORT_REPORT[$this->Export];
			$this->$fn($sContent);
		}
	<!--## } ##-->

	<!--## if (CTRL.CtrlType.toLowerCase() == "table" && bUseCustomTemplate) { ##-->

	if ($this->CustomExport <> "") { // Save temp images array for custom export
		if (is_array($gTmpImages))
			$_SESSION[EW_SESSION_TEMP_IMAGES] = $gTmpImages;
	}

	<!--## } ##-->

	<!--## if (CTRL.CtrlID.toLowerCase() == "gridcls" && nMasterTableCount > 0) { ##-->
//		$GLOBALS["Table"] = &$GLOBALS["MasterTable"];
		unset($GLOBALS["Grid"]);
		if ($url == "")
			return;
	<!--## } ##-->

	<!--## if (CTRL.CtrlID != "custom" && CTRL.CtrlID != "blobview" && SYSTEMFUNCTIONS.ServerScriptExist(sCtrlType,"Page_Redirecting")) { ##-->
		$this->Page_Redirecting($url);
	<!--## } ##-->

	<!--## if (CTRL.CtrlID != "gridcls") { ##-->
		 // Close connection
		ew_CloseConn();
	<!--## } ##-->

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}

<!--## if (!CTRL.CtrlSkipHeaderFooter) { ##-->
		exit();
<!--## } ##-->

	}
<!--##/session##-->
?>


<?php
<!--##session report_export_functions##-->

<!--##include report-html.php/report_html_function##-->
<!--##include report-word.php/report_word_function##-->
<!--##include report-excel.php/report_excel_function##-->

<!--##/session##-->
?>


<?php
<!--##session phpevents##-->
<!--## if (CTRL.CtrlID != "custom" && CTRL.CtrlID != "blobview") { ##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript(sCtrlType,"Page_Load")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript(sCtrlType,"Page_Unload")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript(sCtrlType,"Page_Redirecting")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript(sCtrlType,"Message_Showing")##-->
<!--## } ##-->

<!--## if (CTRL.CtrlType.toLowerCase() == "table" || CTRL.CtrlID.toLowerCase() == "report" || CTRL.CtrlType.toLowerCase() == "other" && CTRL.CtrlID.toLowerCase() != "logout") { ##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript(sCtrlType,"Page_Render")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript(sCtrlType,"Page_DataRendering")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript(sCtrlType,"Page_DataRendered")##-->
<!--## } ##-->

<!--##/session##-->
?>

<?php
<!--##session phppageclassend##-->
}
?>
<!--##/session##-->

<!--##session clientscript##-->
<!--##
	switch (CTRL.CtrlType.toLowerCase()) {
		case "table":
			sCtrlType = "Table";
			break;
		case "report":
			sCtrlType = "Table";
			break;
		case "other":
			sCtrlType = "Other";
			break;
		default:
			sCtrlType = "";
			break;
	}
##-->
<!--##~SYSTEMFUNCTIONS.GetClientScript(sCtrlType,"Client Script")##-->
<!--##/session##-->
<!--##session clientstartupscript##-->
<!--##~SYSTEMFUNCTIONS.GetClientScript(sCtrlType,"Startup Script")##-->
<!--##/session##-->