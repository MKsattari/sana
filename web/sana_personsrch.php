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

$sana_person_search = NULL; // Initialize page object first

class csana_person_search extends csana_person {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{07091A10-D58A-4784-942B-0E21010F5DFC}";

	// Table name
	var $TableName = 'sana_person';

	// Page object name
	var $PageObjName = 'sana_person_search';

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

		// Table object (sana_person)
		if (!isset($GLOBALS["sana_person"]) || get_class($GLOBALS["sana_person"]) == "csana_person") {
			$GLOBALS["sana_person"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sana_person"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sana_person', TRUE);

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
	var $FormClassName = "form-horizontal ewForm ewSearchForm";
	var $IsModal = FALSE;
	var $SearchLabelClass = "col-sm-3 control-label ewLabel";
	var $SearchRightColumnClass = "col-sm-9";

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;
		global $gbSkipHeaderFooter;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$sSrchStr = "sana_personlist.php" . "?" . $sSrchStr;
						if ($this->IsModal) {
							$row = array();
							$row["url"] = $sSrchStr;
							echo ew_ArrayToJson(array($row));
							$this->Page_Terminate();
							exit();
						} else {
							$this->Page_Terminate($sSrchStr); // Go to list page
						}
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->personID); // personID
		$this->BuildSearchUrl($sSrchUrl, $this->personName); // personName
		$this->BuildSearchUrl($sSrchUrl, $this->lastName); // lastName
		$this->BuildSearchUrl($sSrchUrl, $this->nationalID); // nationalID
		$this->BuildSearchUrl($sSrchUrl, $this->nationalNumber); // nationalNumber
		$this->BuildSearchUrl($sSrchUrl, $this->passportNumber); // passportNumber
		$this->BuildSearchUrl($sSrchUrl, $this->fatherName); // fatherName
		$this->BuildSearchUrl($sSrchUrl, $this->gender); // gender
		$this->BuildSearchUrl($sSrchUrl, $this->locationLevel1); // locationLevel1
		$this->BuildSearchUrl($sSrchUrl, $this->locationLevel2); // locationLevel2
		$this->BuildSearchUrl($sSrchUrl, $this->locationLevel3); // locationLevel3
		$this->BuildSearchUrl($sSrchUrl, $this->locationLevel4); // locationLevel4
		$this->BuildSearchUrl($sSrchUrl, $this->locationLevel5); // locationLevel5
		$this->BuildSearchUrl($sSrchUrl, $this->locationLevel6); // locationLevel6
		$this->BuildSearchUrl($sSrchUrl, $this->address); // address
		$this->BuildSearchUrl($sSrchUrl, $this->convoy); // convoy
		$this->BuildSearchUrl($sSrchUrl, $this->convoyManager); // convoyManager
		$this->BuildSearchUrl($sSrchUrl, $this->followersName); // followersName
		$this->BuildSearchUrl($sSrchUrl, $this->status); // status
		$this->BuildSearchUrl($sSrchUrl, $this->isolatedLocation); // isolatedLocation
		$this->BuildSearchUrl($sSrchUrl, $this->birthDate); // birthDate
		$this->BuildSearchUrl($sSrchUrl, $this->ageRange); // ageRange
		$this->BuildSearchUrl($sSrchUrl, $this->dress1); // dress1
		$this->BuildSearchUrl($sSrchUrl, $this->dress2); // dress2
		$this->BuildSearchUrl($sSrchUrl, $this->signTags); // signTags
		$this->BuildSearchUrl($sSrchUrl, $this->phone); // phone
		$this->BuildSearchUrl($sSrchUrl, $this->mobilePhone); // mobilePhone
		$this->BuildSearchUrl($sSrchUrl, $this->_email); // email
		$this->BuildSearchUrl($sSrchUrl, $this->temporaryResidence); // temporaryResidence
		$this->BuildSearchUrl($sSrchUrl, $this->visitsCount); // visitsCount
		$this->BuildSearchUrl($sSrchUrl, $this->picture); // picture
		$this->BuildSearchUrl($sSrchUrl, $this->registrationUser); // registrationUser
		$this->BuildSearchUrl($sSrchUrl, $this->registrationDateTime); // registrationDateTime
		$this->BuildSearchUrl($sSrchUrl, $this->registrationStation); // registrationStation
		$this->BuildSearchUrl($sSrchUrl, $this->isolatedDateTime); // isolatedDateTime
		$this->BuildSearchUrl($sSrchUrl, $this->description); // description
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// personID

		$this->personID->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_personID"));
		$this->personID->AdvancedSearch->SearchOperator = $objForm->GetValue("z_personID");

		// personName
		$this->personName->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_personName"));
		$this->personName->AdvancedSearch->SearchOperator = $objForm->GetValue("z_personName");

		// lastName
		$this->lastName->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_lastName"));
		$this->lastName->AdvancedSearch->SearchOperator = $objForm->GetValue("z_lastName");

		// nationalID
		$this->nationalID->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nationalID"));
		$this->nationalID->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nationalID");

		// nationalNumber
		$this->nationalNumber->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nationalNumber"));
		$this->nationalNumber->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nationalNumber");

		// passportNumber
		$this->passportNumber->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_passportNumber"));
		$this->passportNumber->AdvancedSearch->SearchOperator = $objForm->GetValue("z_passportNumber");

		// fatherName
		$this->fatherName->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_fatherName"));
		$this->fatherName->AdvancedSearch->SearchOperator = $objForm->GetValue("z_fatherName");

		// gender
		$this->gender->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_gender"));
		$this->gender->AdvancedSearch->SearchOperator = $objForm->GetValue("z_gender");

		// locationLevel1
		$this->locationLevel1->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_locationLevel1"));
		$this->locationLevel1->AdvancedSearch->SearchOperator = $objForm->GetValue("z_locationLevel1");

		// locationLevel2
		$this->locationLevel2->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_locationLevel2"));
		$this->locationLevel2->AdvancedSearch->SearchOperator = $objForm->GetValue("z_locationLevel2");

		// locationLevel3
		$this->locationLevel3->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_locationLevel3"));
		$this->locationLevel3->AdvancedSearch->SearchOperator = $objForm->GetValue("z_locationLevel3");

		// locationLevel4
		$this->locationLevel4->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_locationLevel4"));
		$this->locationLevel4->AdvancedSearch->SearchOperator = $objForm->GetValue("z_locationLevel4");

		// locationLevel5
		$this->locationLevel5->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_locationLevel5"));
		$this->locationLevel5->AdvancedSearch->SearchOperator = $objForm->GetValue("z_locationLevel5");

		// locationLevel6
		$this->locationLevel6->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_locationLevel6"));
		$this->locationLevel6->AdvancedSearch->SearchOperator = $objForm->GetValue("z_locationLevel6");

		// address
		$this->address->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_address"));
		$this->address->AdvancedSearch->SearchOperator = $objForm->GetValue("z_address");

		// convoy
		$this->convoy->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_convoy"));
		$this->convoy->AdvancedSearch->SearchOperator = $objForm->GetValue("z_convoy");

		// convoyManager
		$this->convoyManager->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_convoyManager"));
		$this->convoyManager->AdvancedSearch->SearchOperator = $objForm->GetValue("z_convoyManager");

		// followersName
		$this->followersName->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_followersName"));
		$this->followersName->AdvancedSearch->SearchOperator = $objForm->GetValue("z_followersName");

		// status
		$this->status->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_status"));
		$this->status->AdvancedSearch->SearchOperator = $objForm->GetValue("z_status");

		// isolatedLocation
		$this->isolatedLocation->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_isolatedLocation"));
		$this->isolatedLocation->AdvancedSearch->SearchOperator = $objForm->GetValue("z_isolatedLocation");

		// birthDate
		$this->birthDate->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_birthDate"));
		$this->birthDate->AdvancedSearch->SearchOperator = $objForm->GetValue("z_birthDate");

		// ageRange
		$this->ageRange->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_ageRange"));
		$this->ageRange->AdvancedSearch->SearchOperator = $objForm->GetValue("z_ageRange");

		// dress1
		$this->dress1->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_dress1"));
		$this->dress1->AdvancedSearch->SearchOperator = $objForm->GetValue("z_dress1");

		// dress2
		$this->dress2->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_dress2"));
		$this->dress2->AdvancedSearch->SearchOperator = $objForm->GetValue("z_dress2");

		// signTags
		$this->signTags->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_signTags"));
		$this->signTags->AdvancedSearch->SearchOperator = $objForm->GetValue("z_signTags");

		// phone
		$this->phone->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_phone"));
		$this->phone->AdvancedSearch->SearchOperator = $objForm->GetValue("z_phone");

		// mobilePhone
		$this->mobilePhone->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_mobilePhone"));
		$this->mobilePhone->AdvancedSearch->SearchOperator = $objForm->GetValue("z_mobilePhone");

		// email
		$this->_email->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x__email"));
		$this->_email->AdvancedSearch->SearchOperator = $objForm->GetValue("z__email");

		// temporaryResidence
		$this->temporaryResidence->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_temporaryResidence"));
		$this->temporaryResidence->AdvancedSearch->SearchOperator = $objForm->GetValue("z_temporaryResidence");

		// visitsCount
		$this->visitsCount->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_visitsCount"));
		$this->visitsCount->AdvancedSearch->SearchOperator = $objForm->GetValue("z_visitsCount");

		// picture
		$this->picture->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_picture"));
		$this->picture->AdvancedSearch->SearchOperator = $objForm->GetValue("z_picture");

		// registrationUser
		$this->registrationUser->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_registrationUser"));
		$this->registrationUser->AdvancedSearch->SearchOperator = $objForm->GetValue("z_registrationUser");

		// registrationDateTime
		$this->registrationDateTime->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_registrationDateTime"));
		$this->registrationDateTime->AdvancedSearch->SearchOperator = $objForm->GetValue("z_registrationDateTime");

		// registrationStation
		$this->registrationStation->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_registrationStation"));
		$this->registrationStation->AdvancedSearch->SearchOperator = $objForm->GetValue("z_registrationStation");

		// isolatedDateTime
		$this->isolatedDateTime->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_isolatedDateTime"));
		$this->isolatedDateTime->AdvancedSearch->SearchOperator = $objForm->GetValue("z_isolatedDateTime");

		// description
		$this->description->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_description"));
		$this->description->AdvancedSearch->SearchOperator = $objForm->GetValue("z_description");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
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

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

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

			// locationLevel3
			$this->locationLevel3->LinkCustomAttributes = "";
			$this->locationLevel3->HrefValue = "";
			$this->locationLevel3->TooltipValue = "";

			// locationLevel4
			$this->locationLevel4->LinkCustomAttributes = "";
			$this->locationLevel4->HrefValue = "";
			$this->locationLevel4->TooltipValue = "";

			// locationLevel5
			$this->locationLevel5->LinkCustomAttributes = "";
			$this->locationLevel5->HrefValue = "";
			$this->locationLevel5->TooltipValue = "";

			// locationLevel6
			$this->locationLevel6->LinkCustomAttributes = "";
			$this->locationLevel6->HrefValue = "";
			$this->locationLevel6->TooltipValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";
			$this->address->TooltipValue = "";

			// convoy
			$this->convoy->LinkCustomAttributes = "";
			$this->convoy->HrefValue = "";
			$this->convoy->TooltipValue = "";

			// convoyManager
			$this->convoyManager->LinkCustomAttributes = "";
			$this->convoyManager->HrefValue = "";
			$this->convoyManager->TooltipValue = "";

			// followersName
			$this->followersName->LinkCustomAttributes = "";
			$this->followersName->HrefValue = "";
			$this->followersName->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// isolatedLocation
			$this->isolatedLocation->LinkCustomAttributes = "";
			$this->isolatedLocation->HrefValue = "";
			$this->isolatedLocation->TooltipValue = "";

			// birthDate
			$this->birthDate->LinkCustomAttributes = "";
			$this->birthDate->HrefValue = "";
			$this->birthDate->TooltipValue = "";

			// ageRange
			$this->ageRange->LinkCustomAttributes = "";
			$this->ageRange->HrefValue = "";
			$this->ageRange->TooltipValue = "";

			// dress1
			$this->dress1->LinkCustomAttributes = "";
			$this->dress1->HrefValue = "";
			$this->dress1->TooltipValue = "";

			// dress2
			$this->dress2->LinkCustomAttributes = "";
			$this->dress2->HrefValue = "";
			$this->dress2->TooltipValue = "";

			// signTags
			$this->signTags->LinkCustomAttributes = "";
			$this->signTags->HrefValue = "";
			$this->signTags->TooltipValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";
			$this->phone->TooltipValue = "";

			// mobilePhone
			$this->mobilePhone->LinkCustomAttributes = "";
			$this->mobilePhone->HrefValue = "";
			$this->mobilePhone->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// temporaryResidence
			$this->temporaryResidence->LinkCustomAttributes = "";
			$this->temporaryResidence->HrefValue = "";
			$this->temporaryResidence->TooltipValue = "";

			// visitsCount
			$this->visitsCount->LinkCustomAttributes = "";
			$this->visitsCount->HrefValue = "";
			$this->visitsCount->TooltipValue = "";

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
				$this->picture->LinkAttrs["data-rel"] = "sana_person_x_picture";

				//$this->picture->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
				//$this->picture->LinkAttrs["data-placement"] = "bottom";
				//$this->picture->LinkAttrs["data-container"] = "body";

				$this->picture->LinkAttrs["class"] = "ewLightbox img-thumbnail";
			}

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

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";
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
			if (trim(strval($this->gender->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`stateName`" . ew_SearchString("=", $this->gender->AdvancedSearch->SearchValue, EW_DATATYPE_STRING, "");
			}
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
				case "fa":
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
			}
			$lookuptblfilter = "`stateLanguage` = '" . CurrentLanguageID() . "' AND `stateClass` = 'gender'";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->gender, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->gender->EditValue = $arwrk;

			// locationLevel1
			$this->locationLevel1->EditAttrs["class"] = "form-control";
			$this->locationLevel1->EditCustomAttributes = "";
			if (trim(strval($this->locationLevel1->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`locationName`" . ew_SearchString("=", $this->locationLevel1->AdvancedSearch->SearchValue, EW_DATATYPE_STRING, "");
			}
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level1`";
					$sWhereWrk = "";
					break;
				case "fa":
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level1`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level1`";
					$sWhereWrk = "";
					break;
			}
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->locationLevel1, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->locationLevel1->EditValue = $arwrk;

			// locationLevel2
			$this->locationLevel2->EditAttrs["class"] = "form-control";
			$this->locationLevel2->EditCustomAttributes = "";
			if (trim(strval($this->locationLevel2->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`locationName`" . ew_SearchString("=", $this->locationLevel2->AdvancedSearch->SearchValue, EW_DATATYPE_STRING, "");
			}
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `locationLevel1Name` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level2`";
					$sWhereWrk = "";
					break;
				case "fa":
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `locationLevel1Name` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level2`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `locationLevel1Name` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level2`";
					$sWhereWrk = "";
					break;
			}
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->locationLevel2, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->locationLevel2->EditValue = $arwrk;

			// locationLevel3
			$this->locationLevel3->EditAttrs["class"] = "form-control";
			$this->locationLevel3->EditCustomAttributes = "";
			if (trim(strval($this->locationLevel3->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`locationName`" . ew_SearchString("=", $this->locationLevel3->AdvancedSearch->SearchValue, EW_DATATYPE_STRING, "");
			}
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `locationLevel2Name` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level3`";
					$sWhereWrk = "";
					break;
				case "fa":
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `locationLevel2Name` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level3`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `locationLevel2Name` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_location_level3`";
					$sWhereWrk = "";
					break;
			}
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->locationLevel3, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->locationLevel3->EditValue = $arwrk;

			// locationLevel4
			$this->locationLevel4->EditAttrs["class"] = "form-control";
			$this->locationLevel4->EditCustomAttributes = "";
			$this->locationLevel4->EditValue = ew_HtmlEncode($this->locationLevel4->AdvancedSearch->SearchValue);
			$this->locationLevel4->PlaceHolder = ew_RemoveHtml($this->locationLevel4->FldCaption());

			// locationLevel5
			$this->locationLevel5->EditAttrs["class"] = "form-control";
			$this->locationLevel5->EditCustomAttributes = "";
			$this->locationLevel5->EditValue = ew_HtmlEncode($this->locationLevel5->AdvancedSearch->SearchValue);
			$this->locationLevel5->PlaceHolder = ew_RemoveHtml($this->locationLevel5->FldCaption());

			// locationLevel6
			$this->locationLevel6->EditAttrs["class"] = "form-control";
			$this->locationLevel6->EditCustomAttributes = "";
			$this->locationLevel6->EditValue = ew_HtmlEncode($this->locationLevel6->AdvancedSearch->SearchValue);
			$this->locationLevel6->PlaceHolder = ew_RemoveHtml($this->locationLevel6->FldCaption());

			// address
			$this->address->EditAttrs["class"] = "form-control";
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = ew_HtmlEncode($this->address->AdvancedSearch->SearchValue);
			$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

			// convoy
			$this->convoy->EditAttrs["class"] = "form-control";
			$this->convoy->EditCustomAttributes = "";
			$this->convoy->EditValue = ew_HtmlEncode($this->convoy->AdvancedSearch->SearchValue);
			$this->convoy->PlaceHolder = ew_RemoveHtml($this->convoy->FldCaption());

			// convoyManager
			$this->convoyManager->EditAttrs["class"] = "form-control";
			$this->convoyManager->EditCustomAttributes = "";
			$this->convoyManager->EditValue = ew_HtmlEncode($this->convoyManager->AdvancedSearch->SearchValue);
			$this->convoyManager->PlaceHolder = ew_RemoveHtml($this->convoyManager->FldCaption());

			// followersName
			$this->followersName->EditAttrs["class"] = "form-control";
			$this->followersName->EditCustomAttributes = "";
			$this->followersName->EditValue = ew_HtmlEncode($this->followersName->AdvancedSearch->SearchValue);
			$this->followersName->PlaceHolder = ew_RemoveHtml($this->followersName->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			if (trim(strval($this->status->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`stateName`" . ew_SearchString("=", $this->status->AdvancedSearch->SearchValue, EW_DATATYPE_STRING, "");
			}
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
				case "fa":
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
			}
			$lookuptblfilter = "`stateLanguage` = '" . CurrentLanguageID() . "' AND `stateClass` = 'person'";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->status->EditValue = $arwrk;

			// isolatedLocation
			$this->isolatedLocation->EditAttrs["class"] = "form-control";
			$this->isolatedLocation->EditCustomAttributes = "";
			$this->isolatedLocation->EditValue = ew_HtmlEncode($this->isolatedLocation->AdvancedSearch->SearchValue);
			$this->isolatedLocation->PlaceHolder = ew_RemoveHtml($this->isolatedLocation->FldCaption());

			// birthDate
			$this->birthDate->EditAttrs["class"] = "form-control";
			$this->birthDate->EditCustomAttributes = "";
			$this->birthDate->EditValue = ew_HtmlEncode($this->birthDate->AdvancedSearch->SearchValue);
			$this->birthDate->PlaceHolder = ew_RemoveHtml($this->birthDate->FldCaption());

			// ageRange
			$this->ageRange->EditAttrs["class"] = "form-control";
			$this->ageRange->EditCustomAttributes = "";
			if (trim(strval($this->ageRange->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`stateName`" . ew_SearchString("=", $this->ageRange->AdvancedSearch->SearchValue, EW_DATATYPE_STRING, "");
			}
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
				case "fa":
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `stateName`, `stateName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sana_state`";
					$sWhereWrk = "";
					break;
			}
			$lookuptblfilter = " `stateClass` = 'age' ";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->ageRange, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->ageRange->EditValue = $arwrk;

			// dress1
			$this->dress1->EditAttrs["class"] = "form-control";
			$this->dress1->EditCustomAttributes = "";
			$this->dress1->EditValue = ew_HtmlEncode($this->dress1->AdvancedSearch->SearchValue);
			$this->dress1->PlaceHolder = ew_RemoveHtml($this->dress1->FldCaption());

			// dress2
			$this->dress2->EditAttrs["class"] = "form-control";
			$this->dress2->EditCustomAttributes = "";
			$this->dress2->EditValue = ew_HtmlEncode($this->dress2->AdvancedSearch->SearchValue);
			$this->dress2->PlaceHolder = ew_RemoveHtml($this->dress2->FldCaption());

			// signTags
			$this->signTags->EditAttrs["class"] = "form-control";
			$this->signTags->EditCustomAttributes = "";
			$this->signTags->EditValue = ew_HtmlEncode($this->signTags->AdvancedSearch->SearchValue);
			$this->signTags->PlaceHolder = ew_RemoveHtml($this->signTags->FldCaption());

			// phone
			$this->phone->EditAttrs["class"] = "form-control";
			$this->phone->EditCustomAttributes = "";
			$this->phone->EditValue = ew_HtmlEncode($this->phone->AdvancedSearch->SearchValue);
			$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

			// mobilePhone
			$this->mobilePhone->EditAttrs["class"] = "form-control";
			$this->mobilePhone->EditCustomAttributes = "";
			$this->mobilePhone->EditValue = ew_HtmlEncode($this->mobilePhone->AdvancedSearch->SearchValue);
			$this->mobilePhone->PlaceHolder = ew_RemoveHtml($this->mobilePhone->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->AdvancedSearch->SearchValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// temporaryResidence
			$this->temporaryResidence->EditAttrs["class"] = "form-control";
			$this->temporaryResidence->EditCustomAttributes = "";
			$this->temporaryResidence->EditValue = ew_HtmlEncode($this->temporaryResidence->AdvancedSearch->SearchValue);
			$this->temporaryResidence->PlaceHolder = ew_RemoveHtml($this->temporaryResidence->FldCaption());

			// visitsCount
			$this->visitsCount->EditAttrs["class"] = "form-control";
			$this->visitsCount->EditCustomAttributes = "";
			$this->visitsCount->EditValue = ew_HtmlEncode($this->visitsCount->AdvancedSearch->SearchValue);
			$this->visitsCount->PlaceHolder = ew_RemoveHtml($this->visitsCount->FldCaption());

			// picture
			$this->picture->EditAttrs["class"] = "form-control";
			$this->picture->EditCustomAttributes = "";
			$this->picture->EditValue = ew_HtmlEncode($this->picture->AdvancedSearch->SearchValue);
			$this->picture->PlaceHolder = ew_RemoveHtml($this->picture->FldCaption());

			// registrationUser
			$this->registrationUser->EditAttrs["class"] = "form-control";
			$this->registrationUser->EditCustomAttributes = "";
			$this->registrationUser->EditValue = ew_HtmlEncode($this->registrationUser->AdvancedSearch->SearchValue);
			$this->registrationUser->PlaceHolder = ew_RemoveHtml($this->registrationUser->FldCaption());

			// registrationDateTime
			$this->registrationDateTime->EditAttrs["class"] = "form-control";
			$this->registrationDateTime->EditCustomAttributes = "";
			$this->registrationDateTime->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->registrationDateTime->AdvancedSearch->SearchValue, 5), 5));
			$this->registrationDateTime->PlaceHolder = ew_RemoveHtml($this->registrationDateTime->FldCaption());

			// registrationStation
			$this->registrationStation->EditAttrs["class"] = "form-control";
			$this->registrationStation->EditCustomAttributes = "";
			$this->registrationStation->EditValue = ew_HtmlEncode($this->registrationStation->AdvancedSearch->SearchValue);
			$this->registrationStation->PlaceHolder = ew_RemoveHtml($this->registrationStation->FldCaption());

			// isolatedDateTime
			$this->isolatedDateTime->EditAttrs["class"] = "form-control";
			$this->isolatedDateTime->EditCustomAttributes = "";
			$this->isolatedDateTime->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->isolatedDateTime->AdvancedSearch->SearchValue, 5), 5));
			$this->isolatedDateTime->PlaceHolder = ew_RemoveHtml($this->isolatedDateTime->FldCaption());

			// description
			$this->description->EditAttrs["class"] = "form-control";
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->AdvancedSearch->SearchValue);
			$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());
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
		if (!ew_CheckInteger($this->birthDate->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->birthDate->FldErrMsg());
		}
		if (!ew_CheckInteger($this->visitsCount->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->visitsCount->FldErrMsg());
		}
		if (!ew_CheckDate($this->registrationDateTime->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->registrationDateTime->FldErrMsg());
		}
		if (!ew_CheckInteger($this->registrationStation->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->registrationStation->FldErrMsg());
		}
		if (!ew_CheckDate($this->isolatedDateTime->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->isolatedDateTime->FldErrMsg());
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("sana_personlist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
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
if (!isset($sana_person_search)) $sana_person_search = new csana_person_search();

// Page init
$sana_person_search->Page_Init();

// Page main
$sana_person_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_person_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($sana_person_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fsana_personsearch = new ew_Form("fsana_personsearch", "search");
<?php } else { ?>
var CurrentForm = fsana_personsearch = new ew_Form("fsana_personsearch", "search");
<?php } ?>

// Form_CustomValidate event
fsana_personsearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_personsearch.ValidateRequired = true;
<?php } else { ?>
fsana_personsearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsana_personsearch.Lists["x_gender"] = {"LinkField":"x_stateName","Ajax":true,"AutoFill":false,"DisplayFields":["x_stateName","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fsana_personsearch.Lists["x_locationLevel1"] = {"LinkField":"x_locationName","Ajax":true,"AutoFill":false,"DisplayFields":["x_locationName","","",""],"ParentFields":[],"ChildFields":["x_locationLevel2"],"FilterFields":[],"Options":[],"Template":""};
fsana_personsearch.Lists["x_locationLevel2"] = {"LinkField":"x_locationName","Ajax":true,"AutoFill":false,"DisplayFields":["x_locationName","","",""],"ParentFields":["x_locationLevel1"],"ChildFields":["x_locationLevel3"],"FilterFields":["x_locationLevel1Name"],"Options":[],"Template":""};
fsana_personsearch.Lists["x_locationLevel3"] = {"LinkField":"x_locationName","Ajax":true,"AutoFill":false,"DisplayFields":["x_locationName","","",""],"ParentFields":["x_locationLevel2"],"ChildFields":[],"FilterFields":["x_locationLevel2Name"],"Options":[],"Template":""};
fsana_personsearch.Lists["x_status"] = {"LinkField":"x_stateName","Ajax":true,"AutoFill":false,"DisplayFields":["x_stateName","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fsana_personsearch.Lists["x_ageRange"] = {"LinkField":"x_stateName","Ajax":true,"AutoFill":false,"DisplayFields":["x_stateName","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
// Validate function for search

fsana_personsearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_personID");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($sana_person->personID->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_birthDate");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($sana_person->birthDate->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_visitsCount");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($sana_person->visitsCount->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_registrationDateTime");
	if (elm && !ew_CheckDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($sana_person->registrationDateTime->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_registrationStation");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($sana_person->registrationStation->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_isolatedDateTime");
	if (elm && !ew_CheckDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($sana_person->isolatedDateTime->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$sana_person_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $sana_person_search->ShowPageHeader(); ?>
<?php
$sana_person_search->ShowMessage();
?>
<form name="fsana_personsearch" id="fsana_personsearch" class="<?php echo $sana_person_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sana_person_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sana_person_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sana_person">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($sana_person_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($sana_person->personID->Visible) { // personID ?>
	<div id="r_personID" class="form-group">
		<label for="x_personID" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_personID"><?php echo $sana_person->personID->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_personID" id="z_personID" value="="></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->personID->CellAttributes() ?>>
			<span id="el_sana_person_personID">
<input type="text" data-table="sana_person" data-field="x_personID" name="x_personID" id="x_personID" placeholder="<?php echo ew_HtmlEncode($sana_person->personID->getPlaceHolder()) ?>" value="<?php echo $sana_person->personID->EditValue ?>"<?php echo $sana_person->personID->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->personName->Visible) { // personName ?>
	<div id="r_personName" class="form-group">
		<label for="x_personName" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_personName"><?php echo $sana_person->personName->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_personName" id="z_personName" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->personName->CellAttributes() ?>>
			<span id="el_sana_person_personName">
<input type="text" data-table="sana_person" data-field="x_personName" name="x_personName" id="x_personName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->personName->getPlaceHolder()) ?>" value="<?php echo $sana_person->personName->EditValue ?>"<?php echo $sana_person->personName->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->lastName->Visible) { // lastName ?>
	<div id="r_lastName" class="form-group">
		<label for="x_lastName" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_lastName"><?php echo $sana_person->lastName->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_lastName" id="z_lastName" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->lastName->CellAttributes() ?>>
			<span id="el_sana_person_lastName">
<input type="text" data-table="sana_person" data-field="x_lastName" name="x_lastName" id="x_lastName" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($sana_person->lastName->getPlaceHolder()) ?>" value="<?php echo $sana_person->lastName->EditValue ?>"<?php echo $sana_person->lastName->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->nationalID->Visible) { // nationalID ?>
	<div id="r_nationalID" class="form-group">
		<label for="x_nationalID" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_nationalID"><?php echo $sana_person->nationalID->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nationalID" id="z_nationalID" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->nationalID->CellAttributes() ?>>
			<span id="el_sana_person_nationalID">
<input type="text" data-table="sana_person" data-field="x_nationalID" name="x_nationalID" id="x_nationalID" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($sana_person->nationalID->getPlaceHolder()) ?>" value="<?php echo $sana_person->nationalID->EditValue ?>"<?php echo $sana_person->nationalID->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->nationalNumber->Visible) { // nationalNumber ?>
	<div id="r_nationalNumber" class="form-group">
		<label for="x_nationalNumber" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_nationalNumber"><?php echo $sana_person->nationalNumber->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nationalNumber" id="z_nationalNumber" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->nationalNumber->CellAttributes() ?>>
			<span id="el_sana_person_nationalNumber">
<input type="text" data-table="sana_person" data-field="x_nationalNumber" name="x_nationalNumber" id="x_nationalNumber" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($sana_person->nationalNumber->getPlaceHolder()) ?>" value="<?php echo $sana_person->nationalNumber->EditValue ?>"<?php echo $sana_person->nationalNumber->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->passportNumber->Visible) { // passportNumber ?>
	<div id="r_passportNumber" class="form-group">
		<label for="x_passportNumber" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_passportNumber"><?php echo $sana_person->passportNumber->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_passportNumber" id="z_passportNumber" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->passportNumber->CellAttributes() ?>>
			<span id="el_sana_person_passportNumber">
<input type="text" data-table="sana_person" data-field="x_passportNumber" name="x_passportNumber" id="x_passportNumber" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($sana_person->passportNumber->getPlaceHolder()) ?>" value="<?php echo $sana_person->passportNumber->EditValue ?>"<?php echo $sana_person->passportNumber->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->fatherName->Visible) { // fatherName ?>
	<div id="r_fatherName" class="form-group">
		<label for="x_fatherName" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_fatherName"><?php echo $sana_person->fatherName->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_fatherName" id="z_fatherName" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->fatherName->CellAttributes() ?>>
			<span id="el_sana_person_fatherName">
<input type="text" data-table="sana_person" data-field="x_fatherName" name="x_fatherName" id="x_fatherName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->fatherName->getPlaceHolder()) ?>" value="<?php echo $sana_person->fatherName->EditValue ?>"<?php echo $sana_person->fatherName->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->gender->Visible) { // gender ?>
	<div id="r_gender" class="form-group">
		<label for="x_gender" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_gender"><?php echo $sana_person->gender->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_gender" id="z_gender" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->gender->CellAttributes() ?>>
			<span id="el_sana_person_gender">
<select data-table="sana_person" data-field="x_gender" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_person->gender->DisplayValueSeparator) ? json_encode($sana_person->gender->DisplayValueSeparator) : $sana_person->gender->DisplayValueSeparator) ?>" id="x_gender" name="x_gender"<?php echo $sana_person->gender->EditAttributes() ?>>
<?php
if (is_array($sana_person->gender->EditValue)) {
	$arwrk = $sana_person->gender->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sana_person->gender->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sana_person->gender->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($sana_person->gender->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($sana_person->gender->CurrentValue) ?>" selected><?php echo $sana_person->gender->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
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
$sana_person->gender->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sana_person->gender->LookupFilters += array("f0" => "`stateName` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$sana_person->Lookup_Selecting($sana_person->gender, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sana_person->gender->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_gender" id="s_x_gender" value="<?php echo $sana_person->gender->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->locationLevel1->Visible) { // locationLevel1 ?>
	<div id="r_locationLevel1" class="form-group">
		<label for="x_locationLevel1" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_locationLevel1"><?php echo $sana_person->locationLevel1->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_locationLevel1" id="z_locationLevel1" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->locationLevel1->CellAttributes() ?>>
			<span id="el_sana_person_locationLevel1">
<?php $sana_person->locationLevel1->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$sana_person->locationLevel1->EditAttrs["onchange"]; ?>
<select data-table="sana_person" data-field="x_locationLevel1" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_person->locationLevel1->DisplayValueSeparator) ? json_encode($sana_person->locationLevel1->DisplayValueSeparator) : $sana_person->locationLevel1->DisplayValueSeparator) ?>" id="x_locationLevel1" name="x_locationLevel1"<?php echo $sana_person->locationLevel1->EditAttributes() ?>>
<?php
if (is_array($sana_person->locationLevel1->EditValue)) {
	$arwrk = $sana_person->locationLevel1->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sana_person->locationLevel1->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sana_person->locationLevel1->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($sana_person->locationLevel1->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($sana_person->locationLevel1->CurrentValue) ?>" selected><?php echo $sana_person->locationLevel1->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
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
$sana_person->locationLevel1->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sana_person->locationLevel1->LookupFilters += array("f0" => "`locationName` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$sana_person->Lookup_Selecting($sana_person->locationLevel1, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sana_person->locationLevel1->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_locationLevel1" id="s_x_locationLevel1" value="<?php echo $sana_person->locationLevel1->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->locationLevel2->Visible) { // locationLevel2 ?>
	<div id="r_locationLevel2" class="form-group">
		<label for="x_locationLevel2" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_locationLevel2"><?php echo $sana_person->locationLevel2->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_locationLevel2" id="z_locationLevel2" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->locationLevel2->CellAttributes() ?>>
			<span id="el_sana_person_locationLevel2">
<?php $sana_person->locationLevel2->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$sana_person->locationLevel2->EditAttrs["onchange"]; ?>
<select data-table="sana_person" data-field="x_locationLevel2" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_person->locationLevel2->DisplayValueSeparator) ? json_encode($sana_person->locationLevel2->DisplayValueSeparator) : $sana_person->locationLevel2->DisplayValueSeparator) ?>" id="x_locationLevel2" name="x_locationLevel2"<?php echo $sana_person->locationLevel2->EditAttributes() ?>>
<?php
if (is_array($sana_person->locationLevel2->EditValue)) {
	$arwrk = $sana_person->locationLevel2->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sana_person->locationLevel2->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sana_person->locationLevel2->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($sana_person->locationLevel2->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($sana_person->locationLevel2->CurrentValue) ?>" selected><?php echo $sana_person->locationLevel2->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level2`";
		$sWhereWrk = "{filter}";
		break;
	case "fa":
		$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level2`";
		$sWhereWrk = "{filter}";
		break;
	default:
		$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level2`";
		$sWhereWrk = "{filter}";
		break;
}
$sana_person->locationLevel2->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sana_person->locationLevel2->LookupFilters += array("f0" => "`locationName` = {filter_value}", "t0" => "200", "fn0" => "");
$sana_person->locationLevel2->LookupFilters += array("f1" => "`locationLevel1Name` IN ({filter_value})", "t1" => "200", "fn1" => "");
$sSqlWrk = "";
$sana_person->Lookup_Selecting($sana_person->locationLevel2, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sana_person->locationLevel2->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_locationLevel2" id="s_x_locationLevel2" value="<?php echo $sana_person->locationLevel2->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->locationLevel3->Visible) { // locationLevel3 ?>
	<div id="r_locationLevel3" class="form-group">
		<label for="x_locationLevel3" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_locationLevel3"><?php echo $sana_person->locationLevel3->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_locationLevel3" id="z_locationLevel3" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->locationLevel3->CellAttributes() ?>>
			<span id="el_sana_person_locationLevel3">
<select data-table="sana_person" data-field="x_locationLevel3" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_person->locationLevel3->DisplayValueSeparator) ? json_encode($sana_person->locationLevel3->DisplayValueSeparator) : $sana_person->locationLevel3->DisplayValueSeparator) ?>" id="x_locationLevel3" name="x_locationLevel3"<?php echo $sana_person->locationLevel3->EditAttributes() ?>>
<?php
if (is_array($sana_person->locationLevel3->EditValue)) {
	$arwrk = $sana_person->locationLevel3->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sana_person->locationLevel3->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sana_person->locationLevel3->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($sana_person->locationLevel3->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($sana_person->locationLevel3->CurrentValue) ?>" selected><?php echo $sana_person->locationLevel3->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level3`";
		$sWhereWrk = "{filter}";
		break;
	case "fa":
		$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level3`";
		$sWhereWrk = "{filter}";
		break;
	default:
		$sSqlWrk = "SELECT `locationName`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level3`";
		$sWhereWrk = "{filter}";
		break;
}
$sana_person->locationLevel3->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sana_person->locationLevel3->LookupFilters += array("f0" => "`locationName` = {filter_value}", "t0" => "200", "fn0" => "");
$sana_person->locationLevel3->LookupFilters += array("f1" => "`locationLevel2Name` IN ({filter_value})", "t1" => "200", "fn1" => "");
$sSqlWrk = "";
$sana_person->Lookup_Selecting($sana_person->locationLevel3, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sana_person->locationLevel3->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_locationLevel3" id="s_x_locationLevel3" value="<?php echo $sana_person->locationLevel3->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->locationLevel4->Visible) { // locationLevel4 ?>
	<div id="r_locationLevel4" class="form-group">
		<label for="x_locationLevel4" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_locationLevel4"><?php echo $sana_person->locationLevel4->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_locationLevel4" id="z_locationLevel4" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->locationLevel4->CellAttributes() ?>>
			<span id="el_sana_person_locationLevel4">
<input type="text" data-table="sana_person" data-field="x_locationLevel4" name="x_locationLevel4" id="x_locationLevel4" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->locationLevel4->getPlaceHolder()) ?>" value="<?php echo $sana_person->locationLevel4->EditValue ?>"<?php echo $sana_person->locationLevel4->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->locationLevel5->Visible) { // locationLevel5 ?>
	<div id="r_locationLevel5" class="form-group">
		<label for="x_locationLevel5" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_locationLevel5"><?php echo $sana_person->locationLevel5->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_locationLevel5" id="z_locationLevel5" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->locationLevel5->CellAttributes() ?>>
			<span id="el_sana_person_locationLevel5">
<input type="text" data-table="sana_person" data-field="x_locationLevel5" name="x_locationLevel5" id="x_locationLevel5" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->locationLevel5->getPlaceHolder()) ?>" value="<?php echo $sana_person->locationLevel5->EditValue ?>"<?php echo $sana_person->locationLevel5->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->locationLevel6->Visible) { // locationLevel6 ?>
	<div id="r_locationLevel6" class="form-group">
		<label for="x_locationLevel6" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_locationLevel6"><?php echo $sana_person->locationLevel6->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_locationLevel6" id="z_locationLevel6" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->locationLevel6->CellAttributes() ?>>
			<span id="el_sana_person_locationLevel6">
<input type="text" data-table="sana_person" data-field="x_locationLevel6" name="x_locationLevel6" id="x_locationLevel6" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->locationLevel6->getPlaceHolder()) ?>" value="<?php echo $sana_person->locationLevel6->EditValue ?>"<?php echo $sana_person->locationLevel6->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label for="x_address" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_address"><?php echo $sana_person->address->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_address" id="z_address" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->address->CellAttributes() ?>>
			<span id="el_sana_person_address">
<input type="text" data-table="sana_person" data-field="x_address" name="x_address" id="x_address" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->address->getPlaceHolder()) ?>" value="<?php echo $sana_person->address->EditValue ?>"<?php echo $sana_person->address->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->convoy->Visible) { // convoy ?>
	<div id="r_convoy" class="form-group">
		<label for="x_convoy" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_convoy"><?php echo $sana_person->convoy->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_convoy" id="z_convoy" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->convoy->CellAttributes() ?>>
			<span id="el_sana_person_convoy">
<input type="text" data-table="sana_person" data-field="x_convoy" name="x_convoy" id="x_convoy" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->convoy->getPlaceHolder()) ?>" value="<?php echo $sana_person->convoy->EditValue ?>"<?php echo $sana_person->convoy->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->convoyManager->Visible) { // convoyManager ?>
	<div id="r_convoyManager" class="form-group">
		<label for="x_convoyManager" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_convoyManager"><?php echo $sana_person->convoyManager->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_convoyManager" id="z_convoyManager" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->convoyManager->CellAttributes() ?>>
			<span id="el_sana_person_convoyManager">
<input type="text" data-table="sana_person" data-field="x_convoyManager" name="x_convoyManager" id="x_convoyManager" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->convoyManager->getPlaceHolder()) ?>" value="<?php echo $sana_person->convoyManager->EditValue ?>"<?php echo $sana_person->convoyManager->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->followersName->Visible) { // followersName ?>
	<div id="r_followersName" class="form-group">
		<label for="x_followersName" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_followersName"><?php echo $sana_person->followersName->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_followersName" id="z_followersName" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->followersName->CellAttributes() ?>>
			<span id="el_sana_person_followersName">
<input type="text" data-table="sana_person" data-field="x_followersName" name="x_followersName" id="x_followersName" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->followersName->getPlaceHolder()) ?>" value="<?php echo $sana_person->followersName->EditValue ?>"<?php echo $sana_person->followersName->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label for="x_status" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_status"><?php echo $sana_person->status->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_status" id="z_status" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->status->CellAttributes() ?>>
			<span id="el_sana_person_status">
<select data-table="sana_person" data-field="x_status" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_person->status->DisplayValueSeparator) ? json_encode($sana_person->status->DisplayValueSeparator) : $sana_person->status->DisplayValueSeparator) ?>" id="x_status" name="x_status"<?php echo $sana_person->status->EditAttributes() ?>>
<?php
if (is_array($sana_person->status->EditValue)) {
	$arwrk = $sana_person->status->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sana_person->status->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sana_person->status->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($sana_person->status->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($sana_person->status->CurrentValue) ?>" selected><?php echo $sana_person->status->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
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
$sana_person->status->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sana_person->status->LookupFilters += array("f0" => "`stateName` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$sana_person->Lookup_Selecting($sana_person->status, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sana_person->status->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_status" id="s_x_status" value="<?php echo $sana_person->status->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->isolatedLocation->Visible) { // isolatedLocation ?>
	<div id="r_isolatedLocation" class="form-group">
		<label for="x_isolatedLocation" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_isolatedLocation"><?php echo $sana_person->isolatedLocation->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_isolatedLocation" id="z_isolatedLocation" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->isolatedLocation->CellAttributes() ?>>
			<span id="el_sana_person_isolatedLocation">
<input type="text" data-table="sana_person" data-field="x_isolatedLocation" name="x_isolatedLocation" id="x_isolatedLocation" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->isolatedLocation->getPlaceHolder()) ?>" value="<?php echo $sana_person->isolatedLocation->EditValue ?>"<?php echo $sana_person->isolatedLocation->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->birthDate->Visible) { // birthDate ?>
	<div id="r_birthDate" class="form-group">
		<label for="x_birthDate" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_birthDate"><?php echo $sana_person->birthDate->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_birthDate" id="z_birthDate" value="="></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->birthDate->CellAttributes() ?>>
			<span id="el_sana_person_birthDate">
<input type="text" data-table="sana_person" data-field="x_birthDate" name="x_birthDate" id="x_birthDate" size="30" placeholder="<?php echo ew_HtmlEncode($sana_person->birthDate->getPlaceHolder()) ?>" value="<?php echo $sana_person->birthDate->EditValue ?>"<?php echo $sana_person->birthDate->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->ageRange->Visible) { // ageRange ?>
	<div id="r_ageRange" class="form-group">
		<label for="x_ageRange" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_ageRange"><?php echo $sana_person->ageRange->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ageRange" id="z_ageRange" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->ageRange->CellAttributes() ?>>
			<span id="el_sana_person_ageRange">
<select data-table="sana_person" data-field="x_ageRange" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_person->ageRange->DisplayValueSeparator) ? json_encode($sana_person->ageRange->DisplayValueSeparator) : $sana_person->ageRange->DisplayValueSeparator) ?>" id="x_ageRange" name="x_ageRange"<?php echo $sana_person->ageRange->EditAttributes() ?>>
<?php
if (is_array($sana_person->ageRange->EditValue)) {
	$arwrk = $sana_person->ageRange->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sana_person->ageRange->AdvancedSearch->SearchValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sana_person->ageRange->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($sana_person->ageRange->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($sana_person->ageRange->CurrentValue) ?>" selected><?php echo $sana_person->ageRange->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
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
$sana_person->ageRange->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sana_person->ageRange->LookupFilters += array("f0" => "`stateName` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$sana_person->Lookup_Selecting($sana_person->ageRange, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sana_person->ageRange->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_ageRange" id="s_x_ageRange" value="<?php echo $sana_person->ageRange->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->dress1->Visible) { // dress1 ?>
	<div id="r_dress1" class="form-group">
		<label for="x_dress1" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_dress1"><?php echo $sana_person->dress1->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_dress1" id="z_dress1" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->dress1->CellAttributes() ?>>
			<span id="el_sana_person_dress1">
<input type="text" data-table="sana_person" data-field="x_dress1" name="x_dress1" id="x_dress1" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->dress1->getPlaceHolder()) ?>" value="<?php echo $sana_person->dress1->EditValue ?>"<?php echo $sana_person->dress1->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->dress2->Visible) { // dress2 ?>
	<div id="r_dress2" class="form-group">
		<label for="x_dress2" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_dress2"><?php echo $sana_person->dress2->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_dress2" id="z_dress2" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->dress2->CellAttributes() ?>>
			<span id="el_sana_person_dress2">
<input type="text" data-table="sana_person" data-field="x_dress2" name="x_dress2" id="x_dress2" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->dress2->getPlaceHolder()) ?>" value="<?php echo $sana_person->dress2->EditValue ?>"<?php echo $sana_person->dress2->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->signTags->Visible) { // signTags ?>
	<div id="r_signTags" class="form-group">
		<label for="x_signTags" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_signTags"><?php echo $sana_person->signTags->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_signTags" id="z_signTags" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->signTags->CellAttributes() ?>>
			<span id="el_sana_person_signTags">
<input type="text" data-table="sana_person" data-field="x_signTags" name="x_signTags" id="x_signTags" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->signTags->getPlaceHolder()) ?>" value="<?php echo $sana_person->signTags->EditValue ?>"<?php echo $sana_person->signTags->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->phone->Visible) { // phone ?>
	<div id="r_phone" class="form-group">
		<label for="x_phone" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_phone"><?php echo $sana_person->phone->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_phone" id="z_phone" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->phone->CellAttributes() ?>>
			<span id="el_sana_person_phone">
<input type="text" data-table="sana_person" data-field="x_phone" name="x_phone" id="x_phone" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_person->phone->getPlaceHolder()) ?>" value="<?php echo $sana_person->phone->EditValue ?>"<?php echo $sana_person->phone->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->mobilePhone->Visible) { // mobilePhone ?>
	<div id="r_mobilePhone" class="form-group">
		<label for="x_mobilePhone" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_mobilePhone"><?php echo $sana_person->mobilePhone->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_mobilePhone" id="z_mobilePhone" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->mobilePhone->CellAttributes() ?>>
			<span id="el_sana_person_mobilePhone">
<input type="text" data-table="sana_person" data-field="x_mobilePhone" name="x_mobilePhone" id="x_mobilePhone" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($sana_person->mobilePhone->getPlaceHolder()) ?>" value="<?php echo $sana_person->mobilePhone->EditValue ?>"<?php echo $sana_person->mobilePhone->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label for="x__email" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person__email"><?php echo $sana_person->_email->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z__email" id="z__email" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->_email->CellAttributes() ?>>
			<span id="el_sana_person__email">
<input type="text" data-table="sana_person" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($sana_person->_email->getPlaceHolder()) ?>" value="<?php echo $sana_person->_email->EditValue ?>"<?php echo $sana_person->_email->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->temporaryResidence->Visible) { // temporaryResidence ?>
	<div id="r_temporaryResidence" class="form-group">
		<label for="x_temporaryResidence" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_temporaryResidence"><?php echo $sana_person->temporaryResidence->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_temporaryResidence" id="z_temporaryResidence" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->temporaryResidence->CellAttributes() ?>>
			<span id="el_sana_person_temporaryResidence">
<input type="text" data-table="sana_person" data-field="x_temporaryResidence" name="x_temporaryResidence" id="x_temporaryResidence" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->temporaryResidence->getPlaceHolder()) ?>" value="<?php echo $sana_person->temporaryResidence->EditValue ?>"<?php echo $sana_person->temporaryResidence->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->visitsCount->Visible) { // visitsCount ?>
	<div id="r_visitsCount" class="form-group">
		<label for="x_visitsCount" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_visitsCount"><?php echo $sana_person->visitsCount->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_visitsCount" id="z_visitsCount" value="="></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->visitsCount->CellAttributes() ?>>
			<span id="el_sana_person_visitsCount">
<input type="text" data-table="sana_person" data-field="x_visitsCount" name="x_visitsCount" id="x_visitsCount" size="30" placeholder="<?php echo ew_HtmlEncode($sana_person->visitsCount->getPlaceHolder()) ?>" value="<?php echo $sana_person->visitsCount->EditValue ?>"<?php echo $sana_person->visitsCount->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->picture->Visible) { // picture ?>
	<div id="r_picture" class="form-group">
		<label class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_picture"><?php echo $sana_person->picture->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_picture" id="z_picture" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->picture->CellAttributes() ?>>
			<span id="el_sana_person_picture">
<input type="text" data-table="sana_person" data-field="x_picture" name="x_picture" id="x_picture" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($sana_person->picture->getPlaceHolder()) ?>" value="<?php echo $sana_person->picture->EditValue ?>"<?php echo $sana_person->picture->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->registrationUser->Visible) { // registrationUser ?>
	<div id="r_registrationUser" class="form-group">
		<label class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_registrationUser"><?php echo $sana_person->registrationUser->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_registrationUser" id="z_registrationUser" value="="></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->registrationUser->CellAttributes() ?>>
			<span id="el_sana_person_registrationUser">
<input type="text" data-table="sana_person" data-field="x_registrationUser" name="x_registrationUser" id="x_registrationUser" size="30" placeholder="<?php echo ew_HtmlEncode($sana_person->registrationUser->getPlaceHolder()) ?>" value="<?php echo $sana_person->registrationUser->EditValue ?>"<?php echo $sana_person->registrationUser->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->registrationDateTime->Visible) { // registrationDateTime ?>
	<div id="r_registrationDateTime" class="form-group">
		<label for="x_registrationDateTime" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_registrationDateTime"><?php echo $sana_person->registrationDateTime->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_registrationDateTime" id="z_registrationDateTime" value="="></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->registrationDateTime->CellAttributes() ?>>
			<span id="el_sana_person_registrationDateTime">
<input type="text" data-table="sana_person" data-field="x_registrationDateTime" data-format="5" name="x_registrationDateTime" id="x_registrationDateTime" placeholder="<?php echo ew_HtmlEncode($sana_person->registrationDateTime->getPlaceHolder()) ?>" value="<?php echo $sana_person->registrationDateTime->EditValue ?>"<?php echo $sana_person->registrationDateTime->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->registrationStation->Visible) { // registrationStation ?>
	<div id="r_registrationStation" class="form-group">
		<label for="x_registrationStation" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_registrationStation"><?php echo $sana_person->registrationStation->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_registrationStation" id="z_registrationStation" value="="></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->registrationStation->CellAttributes() ?>>
			<span id="el_sana_person_registrationStation">
<input type="text" data-table="sana_person" data-field="x_registrationStation" name="x_registrationStation" id="x_registrationStation" size="30" placeholder="<?php echo ew_HtmlEncode($sana_person->registrationStation->getPlaceHolder()) ?>" value="<?php echo $sana_person->registrationStation->EditValue ?>"<?php echo $sana_person->registrationStation->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->isolatedDateTime->Visible) { // isolatedDateTime ?>
	<div id="r_isolatedDateTime" class="form-group">
		<label for="x_isolatedDateTime" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_isolatedDateTime"><?php echo $sana_person->isolatedDateTime->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_isolatedDateTime" id="z_isolatedDateTime" value="="></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->isolatedDateTime->CellAttributes() ?>>
			<span id="el_sana_person_isolatedDateTime">
<input type="text" data-table="sana_person" data-field="x_isolatedDateTime" data-format="5" name="x_isolatedDateTime" id="x_isolatedDateTime" placeholder="<?php echo ew_HtmlEncode($sana_person->isolatedDateTime->getPlaceHolder()) ?>" value="<?php echo $sana_person->isolatedDateTime->EditValue ?>"<?php echo $sana_person->isolatedDateTime->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($sana_person->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label for="x_description" class="<?php echo $sana_person_search->SearchLabelClass ?>"><span id="elh_sana_person_description"><?php echo $sana_person->description->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_description" id="z_description" value="LIKE"></p>
		</label>
		<div class="<?php echo $sana_person_search->SearchRightColumnClass ?>"><div<?php echo $sana_person->description->CellAttributes() ?>>
			<span id="el_sana_person_description">
<input type="text" data-table="sana_person" data-field="x_description" name="x_description" id="x_description" size="35" placeholder="<?php echo ew_HtmlEncode($sana_person->description->getPlaceHolder()) ?>" value="<?php echo $sana_person->description->EditValue ?>"<?php echo $sana_person->description->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$sana_person_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fsana_personsearch.Init();
</script>
<?php
$sana_person_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sana_person_search->Page_Terminate();
?>
