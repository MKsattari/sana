<?php

// Global variable for table object
$sana_message = NULL;

//
// Table class for sana_message
//
class csana_message extends cTable {
	var $messageID;
	var $personID;
	var $_userID;
	var $messageType;
	var $messageText;
	var $stationID;
	var $messageDateTime;
	var $registrationUser;
	var $registrationDateTime;
	var $registrationStation;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'sana_message';
		$this->TableName = 'sana_message';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`sana_message`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// messageID
		$this->messageID = new cField('sana_message', 'sana_message', 'x_messageID', 'messageID', '`messageID`', '`messageID`', 20, -1, FALSE, '`messageID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->messageID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['messageID'] = &$this->messageID;

		// personID
		$this->personID = new cField('sana_message', 'sana_message', 'x_personID', 'personID', '`personID`', '`personID`', 20, -1, FALSE, '`personID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->personID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['personID'] = &$this->personID;

		// userID
		$this->_userID = new cField('sana_message', 'sana_message', 'x__userID', 'userID', '`userID`', '`userID`', 20, -1, FALSE, '`userID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_userID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['userID'] = &$this->_userID;

		// messageType
		$this->messageType = new cField('sana_message', 'sana_message', 'x_messageType', 'messageType', '`messageType`', '`messageType`', 200, -1, FALSE, '`messageType`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['messageType'] = &$this->messageType;

		// messageText
		$this->messageText = new cField('sana_message', 'sana_message', 'x_messageText', 'messageText', '`messageText`', '`messageText`', 201, -1, FALSE, '`messageText`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['messageText'] = &$this->messageText;

		// stationID
		$this->stationID = new cField('sana_message', 'sana_message', 'x_stationID', 'stationID', '`stationID`', '`stationID`', 3, -1, FALSE, '`stationID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->stationID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['stationID'] = &$this->stationID;

		// messageDateTime
		$this->messageDateTime = new cField('sana_message', 'sana_message', 'x_messageDateTime', 'messageDateTime', '`messageDateTime`', 'DATE_FORMAT(`messageDateTime`, \'%Y/%m/%d\')', 135, 5, FALSE, '`messageDateTime`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->messageDateTime->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['messageDateTime'] = &$this->messageDateTime;

		// registrationUser
		$this->registrationUser = new cField('sana_message', 'sana_message', 'x_registrationUser', 'registrationUser', '`registrationUser`', '`registrationUser`', 3, -1, FALSE, '`registrationUser`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->registrationUser->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['registrationUser'] = &$this->registrationUser;

		// registrationDateTime
		$this->registrationDateTime = new cField('sana_message', 'sana_message', 'x_registrationDateTime', 'registrationDateTime', '`registrationDateTime`', 'DATE_FORMAT(`registrationDateTime`, \'%Y/%m/%d\')', 135, 5, FALSE, '`registrationDateTime`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->registrationDateTime->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['registrationDateTime'] = &$this->registrationDateTime;

		// registrationStation
		$this->registrationStation = new cField('sana_message', 'sana_message', 'x_registrationStation', 'registrationStation', '`registrationStation`', '`registrationStation`', 3, -1, FALSE, '`registrationStation`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->registrationStation->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['registrationStation'] = &$this->registrationStation;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "sana_person") {
			if ($this->personID->getSessionValue() <> "")
				$sMasterFilter .= "`personID`=" . ew_QuotedValue($this->personID->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "sana_person") {
			if ($this->personID->getSessionValue() <> "")
				$sDetailFilter .= "`personID`=" . ew_QuotedValue($this->personID->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_sana_person() {
		return "`personID`=@personID@";
	}

	// Detail filter
	function SqlDetailFilter_sana_person() {
		return "`personID`=@personID@";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`sana_message`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('messageID', $rs))
				ew_AddFilter($where, ew_QuotedName('messageID', $this->DBID) . '=' . ew_QuotedValue($rs['messageID'], $this->messageID->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`messageID` = @messageID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->messageID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@messageID@", ew_AdjustSql($this->messageID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "sana_messagelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "sana_messagelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("sana_messageview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("sana_messageview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "sana_messageadd.php?" . $this->UrlParm($parm);
		else
			$url = "sana_messageadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("sana_messageedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("sana_messageadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("sana_messagedelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		if ($this->getCurrentMasterTable() == "sana_person" && strpos($url, EW_TABLE_SHOW_MASTER . "=") === FALSE) {
			$url .= (strpos($url, "?") !== FALSE ? "&" : "?") . EW_TABLE_SHOW_MASTER . "=" . $this->getCurrentMasterTable();
			$url .= "&fk_personID=" . urlencode($this->personID->CurrentValue);
		}
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "messageID:" . ew_VarToJson($this->messageID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->messageID->CurrentValue)) {
			$sUrl .= "messageID=" . urlencode($this->messageID->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["messageID"]))
				$arKeys[] = ew_StripSlashes($_POST["messageID"]);
			elseif (isset($_GET["messageID"]))
				$arKeys[] = ew_StripSlashes($_GET["messageID"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->messageID->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
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

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// stationID
		$this->stationID->LinkCustomAttributes = "";
		$this->stationID->HrefValue = "";
		$this->stationID->TooltipValue = "";

		// messageDateTime
		$this->messageDateTime->LinkCustomAttributes = "";
		$this->messageDateTime->HrefValue = "";
		$this->messageDateTime->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// messageID
		$this->messageID->EditAttrs["class"] = "form-control";
		$this->messageID->EditCustomAttributes = "";
		$this->messageID->EditValue = $this->messageID->CurrentValue;
		$this->messageID->ViewCustomAttributes = "";

		// personID
		$this->personID->EditAttrs["class"] = "form-control";
		$this->personID->EditCustomAttributes = "";
		if ($this->personID->getSessionValue() <> "") {
			$this->personID->CurrentValue = $this->personID->getSessionValue();
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
		} else {
		$this->personID->EditValue = $this->personID->CurrentValue;
		$this->personID->PlaceHolder = ew_RemoveHtml($this->personID->FldCaption());
		}

		// userID
		$this->_userID->EditAttrs["class"] = "form-control";
		$this->_userID->EditCustomAttributes = "";
		$this->_userID->EditValue = $this->_userID->CurrentValue;
		$this->_userID->PlaceHolder = ew_RemoveHtml($this->_userID->FldCaption());

		// messageType
		$this->messageType->EditAttrs["class"] = "form-control";
		$this->messageType->EditCustomAttributes = "";
		$this->messageType->EditValue = $this->messageType->CurrentValue;
		$this->messageType->PlaceHolder = ew_RemoveHtml($this->messageType->FldCaption());

		// messageText
		$this->messageText->EditAttrs["class"] = "form-control";
		$this->messageText->EditCustomAttributes = "";
		$this->messageText->EditValue = $this->messageText->CurrentValue;
		$this->messageText->PlaceHolder = ew_RemoveHtml($this->messageText->FldCaption());

		// stationID
		$this->stationID->EditAttrs["class"] = "form-control";
		$this->stationID->EditCustomAttributes = "";
		$this->stationID->EditValue = $this->stationID->CurrentValue;
		$this->stationID->PlaceHolder = ew_RemoveHtml($this->stationID->FldCaption());

		// messageDateTime
		$this->messageDateTime->EditAttrs["class"] = "form-control";
		$this->messageDateTime->EditCustomAttributes = "";
		$this->messageDateTime->EditValue = ew_FormatDateTime($this->messageDateTime->CurrentValue, 5);
		$this->messageDateTime->PlaceHolder = ew_RemoveHtml($this->messageDateTime->FldCaption());

		// registrationUser
		$this->registrationUser->EditAttrs["class"] = "form-control";
		$this->registrationUser->EditCustomAttributes = "";
		$this->registrationUser->EditValue = $this->registrationUser->CurrentValue;
		$this->registrationUser->PlaceHolder = ew_RemoveHtml($this->registrationUser->FldCaption());

		// registrationDateTime
		$this->registrationDateTime->EditAttrs["class"] = "form-control";
		$this->registrationDateTime->EditCustomAttributes = "";
		$this->registrationDateTime->EditValue = ew_FormatDateTime($this->registrationDateTime->CurrentValue, 5);
		$this->registrationDateTime->PlaceHolder = ew_RemoveHtml($this->registrationDateTime->FldCaption());

		// registrationStation
		$this->registrationStation->EditAttrs["class"] = "form-control";
		$this->registrationStation->EditCustomAttributes = "";
		$this->registrationStation->EditValue = $this->registrationStation->CurrentValue;
		$this->registrationStation->PlaceHolder = ew_RemoveHtml($this->registrationStation->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->messageID->Exportable) $Doc->ExportCaption($this->messageID);
					if ($this->personID->Exportable) $Doc->ExportCaption($this->personID);
					if ($this->_userID->Exportable) $Doc->ExportCaption($this->_userID);
					if ($this->messageType->Exportable) $Doc->ExportCaption($this->messageType);
					if ($this->messageText->Exportable) $Doc->ExportCaption($this->messageText);
					if ($this->stationID->Exportable) $Doc->ExportCaption($this->stationID);
					if ($this->messageDateTime->Exportable) $Doc->ExportCaption($this->messageDateTime);
					if ($this->registrationUser->Exportable) $Doc->ExportCaption($this->registrationUser);
					if ($this->registrationDateTime->Exportable) $Doc->ExportCaption($this->registrationDateTime);
					if ($this->registrationStation->Exportable) $Doc->ExportCaption($this->registrationStation);
				} else {
					if ($this->messageID->Exportable) $Doc->ExportCaption($this->messageID);
					if ($this->personID->Exportable) $Doc->ExportCaption($this->personID);
					if ($this->_userID->Exportable) $Doc->ExportCaption($this->_userID);
					if ($this->messageType->Exportable) $Doc->ExportCaption($this->messageType);
					if ($this->stationID->Exportable) $Doc->ExportCaption($this->stationID);
					if ($this->messageDateTime->Exportable) $Doc->ExportCaption($this->messageDateTime);
					if ($this->registrationUser->Exportable) $Doc->ExportCaption($this->registrationUser);
					if ($this->registrationDateTime->Exportable) $Doc->ExportCaption($this->registrationDateTime);
					if ($this->registrationStation->Exportable) $Doc->ExportCaption($this->registrationStation);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->messageID->Exportable) $Doc->ExportField($this->messageID);
						if ($this->personID->Exportable) $Doc->ExportField($this->personID);
						if ($this->_userID->Exportable) $Doc->ExportField($this->_userID);
						if ($this->messageType->Exportable) $Doc->ExportField($this->messageType);
						if ($this->messageText->Exportable) $Doc->ExportField($this->messageText);
						if ($this->stationID->Exportable) $Doc->ExportField($this->stationID);
						if ($this->messageDateTime->Exportable) $Doc->ExportField($this->messageDateTime);
						if ($this->registrationUser->Exportable) $Doc->ExportField($this->registrationUser);
						if ($this->registrationDateTime->Exportable) $Doc->ExportField($this->registrationDateTime);
						if ($this->registrationStation->Exportable) $Doc->ExportField($this->registrationStation);
					} else {
						if ($this->messageID->Exportable) $Doc->ExportField($this->messageID);
						if ($this->personID->Exportable) $Doc->ExportField($this->personID);
						if ($this->_userID->Exportable) $Doc->ExportField($this->_userID);
						if ($this->messageType->Exportable) $Doc->ExportField($this->messageType);
						if ($this->stationID->Exportable) $Doc->ExportField($this->stationID);
						if ($this->messageDateTime->Exportable) $Doc->ExportField($this->messageDateTime);
						if ($this->registrationUser->Exportable) $Doc->ExportField($this->registrationUser);
						if ($this->registrationDateTime->Exportable) $Doc->ExportField($this->registrationDateTime);
						if ($this->registrationStation->Exportable) $Doc->ExportField($this->registrationStation);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
