<?php

// Global variable for table object
$sana_object = NULL;

//
// Table class for sana_object
//
class csana_object extends cTable {
	var $objectID;
	var $objectName;
	var $ownerID;
	var $ownerName;
	var $lastName;
	var $mobilePhone;
	var $color;
	var $status;
	var $content;
	var $financialValue;
	var $registrationUser;
	var $registrationDateTime;
	var $registrationStation;
	var $isolatedDateTime;
	var $description;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'sana_object';
		$this->TableName = 'sana_object';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`sana_object`";
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

		// objectID
		$this->objectID = new cField('sana_object', 'sana_object', 'x_objectID', 'objectID', '`objectID`', '`objectID`', 20, -1, FALSE, '`objectID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->objectID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['objectID'] = &$this->objectID;

		// objectName
		$this->objectName = new cField('sana_object', 'sana_object', 'x_objectName', 'objectName', '`objectName`', '`objectName`', 200, -1, FALSE, '`objectName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['objectName'] = &$this->objectName;

		// ownerID
		$this->ownerID = new cField('sana_object', 'sana_object', 'x_ownerID', 'ownerID', '`ownerID`', '`ownerID`', 3, -1, FALSE, '`ownerID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ownerID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ownerID'] = &$this->ownerID;

		// ownerName
		$this->ownerName = new cField('sana_object', 'sana_object', 'x_ownerName', 'ownerName', '`ownerName`', '`ownerName`', 200, -1, FALSE, '`ownerName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['ownerName'] = &$this->ownerName;

		// lastName
		$this->lastName = new cField('sana_object', 'sana_object', 'x_lastName', 'lastName', '`lastName`', '`lastName`', 200, -1, FALSE, '`lastName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['lastName'] = &$this->lastName;

		// mobilePhone
		$this->mobilePhone = new cField('sana_object', 'sana_object', 'x_mobilePhone', 'mobilePhone', '`mobilePhone`', '`mobilePhone`', 200, -1, FALSE, '`mobilePhone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['mobilePhone'] = &$this->mobilePhone;

		// color
		$this->color = new cField('sana_object', 'sana_object', 'x_color', 'color', '`color`', '`color`', 200, -1, FALSE, '`color`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['color'] = &$this->color;

		// status
		$this->status = new cField('sana_object', 'sana_object', 'x_status', 'status', '`status`', '`status`', 200, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['status'] = &$this->status;

		// content
		$this->content = new cField('sana_object', 'sana_object', 'x_content', 'content', '`content`', '`content`', 200, -1, FALSE, '`content`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['content'] = &$this->content;

		// financialValue
		$this->financialValue = new cField('sana_object', 'sana_object', 'x_financialValue', 'financialValue', '`financialValue`', '`financialValue`', 200, -1, FALSE, '`financialValue`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['financialValue'] = &$this->financialValue;

		// registrationUser
		$this->registrationUser = new cField('sana_object', 'sana_object', 'x_registrationUser', 'registrationUser', '`registrationUser`', '`registrationUser`', 3, -1, FALSE, '`registrationUser`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->registrationUser->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['registrationUser'] = &$this->registrationUser;

		// registrationDateTime
		$this->registrationDateTime = new cField('sana_object', 'sana_object', 'x_registrationDateTime', 'registrationDateTime', '`registrationDateTime`', 'DATE_FORMAT(`registrationDateTime`, \'%Y/%m/%d\')', 135, 5, FALSE, '`registrationDateTime`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->registrationDateTime->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['registrationDateTime'] = &$this->registrationDateTime;

		// registrationStation
		$this->registrationStation = new cField('sana_object', 'sana_object', 'x_registrationStation', 'registrationStation', '`registrationStation`', '`registrationStation`', 3, -1, FALSE, '`registrationStation`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->registrationStation->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['registrationStation'] = &$this->registrationStation;

		// isolatedDateTime
		$this->isolatedDateTime = new cField('sana_object', 'sana_object', 'x_isolatedDateTime', 'isolatedDateTime', '`isolatedDateTime`', 'DATE_FORMAT(`isolatedDateTime`, \'%Y/%m/%d\')', 135, 5, FALSE, '`isolatedDateTime`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->isolatedDateTime->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['isolatedDateTime'] = &$this->isolatedDateTime;

		// description
		$this->description = new cField('sana_object', 'sana_object', 'x_description', 'description', '`description`', '`description`', 201, -1, FALSE, '`description`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['description'] = &$this->description;
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

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`sana_object`";
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
			if (array_key_exists('objectID', $rs))
				ew_AddFilter($where, ew_QuotedName('objectID', $this->DBID) . '=' . ew_QuotedValue($rs['objectID'], $this->objectID->FldDataType, $this->DBID));
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
		return "`objectID` = @objectID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->objectID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@objectID@", ew_AdjustSql($this->objectID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "sana_objectlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "sana_objectlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("sana_objectview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("sana_objectview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "sana_objectadd.php?" . $this->UrlParm($parm);
		else
			$url = "sana_objectadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("sana_objectedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("sana_objectadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("sana_objectdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "objectID:" . ew_VarToJson($this->objectID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->objectID->CurrentValue)) {
			$sUrl .= "objectID=" . urlencode($this->objectID->CurrentValue);
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
			if ($isPost && isset($_POST["objectID"]))
				$arKeys[] = ew_StripSlashes($_POST["objectID"]);
			elseif (isset($_GET["objectID"]))
				$arKeys[] = ew_StripSlashes($_GET["objectID"]);
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
			$this->objectID->CurrentValue = $key;
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

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

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

		// description
		$this->description->LinkCustomAttributes = "";
		$this->description->HrefValue = "";
		$this->description->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// objectID
		$this->objectID->EditAttrs["class"] = "form-control";
		$this->objectID->EditCustomAttributes = "";
		$this->objectID->EditValue = $this->objectID->CurrentValue;
		$this->objectID->ViewCustomAttributes = "";

		// objectName
		$this->objectName->EditAttrs["class"] = "form-control";
		$this->objectName->EditCustomAttributes = "";
		$this->objectName->EditValue = $this->objectName->CurrentValue;
		$this->objectName->PlaceHolder = ew_RemoveHtml($this->objectName->FldCaption());

		// ownerID
		$this->ownerID->EditAttrs["class"] = "form-control";
		$this->ownerID->EditCustomAttributes = "";
		$this->ownerID->EditValue = $this->ownerID->CurrentValue;
		$this->ownerID->PlaceHolder = ew_RemoveHtml($this->ownerID->FldCaption());

		// ownerName
		$this->ownerName->EditAttrs["class"] = "form-control";
		$this->ownerName->EditCustomAttributes = "";
		$this->ownerName->EditValue = $this->ownerName->CurrentValue;
		$this->ownerName->PlaceHolder = ew_RemoveHtml($this->ownerName->FldCaption());

		// lastName
		$this->lastName->EditAttrs["class"] = "form-control";
		$this->lastName->EditCustomAttributes = "";
		$this->lastName->EditValue = $this->lastName->CurrentValue;
		$this->lastName->PlaceHolder = ew_RemoveHtml($this->lastName->FldCaption());

		// mobilePhone
		$this->mobilePhone->EditAttrs["class"] = "form-control";
		$this->mobilePhone->EditCustomAttributes = "";
		$this->mobilePhone->EditValue = $this->mobilePhone->CurrentValue;
		$this->mobilePhone->PlaceHolder = ew_RemoveHtml($this->mobilePhone->FldCaption());

		// color
		$this->color->EditAttrs["class"] = "form-control";
		$this->color->EditCustomAttributes = "";
		$this->color->EditValue = $this->color->CurrentValue;
		$this->color->PlaceHolder = ew_RemoveHtml($this->color->FldCaption());

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->CurrentValue;
		$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

		// content
		$this->content->EditAttrs["class"] = "form-control";
		$this->content->EditCustomAttributes = "";
		$this->content->EditValue = $this->content->CurrentValue;
		$this->content->PlaceHolder = ew_RemoveHtml($this->content->FldCaption());

		// financialValue
		$this->financialValue->EditAttrs["class"] = "form-control";
		$this->financialValue->EditCustomAttributes = "";
		$this->financialValue->EditValue = $this->financialValue->CurrentValue;
		$this->financialValue->PlaceHolder = ew_RemoveHtml($this->financialValue->FldCaption());

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

		// isolatedDateTime
		$this->isolatedDateTime->EditAttrs["class"] = "form-control";
		$this->isolatedDateTime->EditCustomAttributes = "";
		$this->isolatedDateTime->EditValue = ew_FormatDateTime($this->isolatedDateTime->CurrentValue, 5);
		$this->isolatedDateTime->PlaceHolder = ew_RemoveHtml($this->isolatedDateTime->FldCaption());

		// description
		$this->description->EditAttrs["class"] = "form-control";
		$this->description->EditCustomAttributes = "";
		$this->description->EditValue = $this->description->CurrentValue;
		$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

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
					if ($this->objectID->Exportable) $Doc->ExportCaption($this->objectID);
					if ($this->objectName->Exportable) $Doc->ExportCaption($this->objectName);
					if ($this->ownerID->Exportable) $Doc->ExportCaption($this->ownerID);
					if ($this->ownerName->Exportable) $Doc->ExportCaption($this->ownerName);
					if ($this->lastName->Exportable) $Doc->ExportCaption($this->lastName);
					if ($this->mobilePhone->Exportable) $Doc->ExportCaption($this->mobilePhone);
					if ($this->color->Exportable) $Doc->ExportCaption($this->color);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->content->Exportable) $Doc->ExportCaption($this->content);
					if ($this->financialValue->Exportable) $Doc->ExportCaption($this->financialValue);
					if ($this->registrationUser->Exportable) $Doc->ExportCaption($this->registrationUser);
					if ($this->registrationDateTime->Exportable) $Doc->ExportCaption($this->registrationDateTime);
					if ($this->registrationStation->Exportable) $Doc->ExportCaption($this->registrationStation);
					if ($this->isolatedDateTime->Exportable) $Doc->ExportCaption($this->isolatedDateTime);
					if ($this->description->Exportable) $Doc->ExportCaption($this->description);
				} else {
					if ($this->objectID->Exportable) $Doc->ExportCaption($this->objectID);
					if ($this->objectName->Exportable) $Doc->ExportCaption($this->objectName);
					if ($this->ownerID->Exportable) $Doc->ExportCaption($this->ownerID);
					if ($this->ownerName->Exportable) $Doc->ExportCaption($this->ownerName);
					if ($this->lastName->Exportable) $Doc->ExportCaption($this->lastName);
					if ($this->mobilePhone->Exportable) $Doc->ExportCaption($this->mobilePhone);
					if ($this->color->Exportable) $Doc->ExportCaption($this->color);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->content->Exportable) $Doc->ExportCaption($this->content);
					if ($this->financialValue->Exportable) $Doc->ExportCaption($this->financialValue);
					if ($this->registrationUser->Exportable) $Doc->ExportCaption($this->registrationUser);
					if ($this->registrationDateTime->Exportable) $Doc->ExportCaption($this->registrationDateTime);
					if ($this->registrationStation->Exportable) $Doc->ExportCaption($this->registrationStation);
					if ($this->isolatedDateTime->Exportable) $Doc->ExportCaption($this->isolatedDateTime);
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
						if ($this->objectID->Exportable) $Doc->ExportField($this->objectID);
						if ($this->objectName->Exportable) $Doc->ExportField($this->objectName);
						if ($this->ownerID->Exportable) $Doc->ExportField($this->ownerID);
						if ($this->ownerName->Exportable) $Doc->ExportField($this->ownerName);
						if ($this->lastName->Exportable) $Doc->ExportField($this->lastName);
						if ($this->mobilePhone->Exportable) $Doc->ExportField($this->mobilePhone);
						if ($this->color->Exportable) $Doc->ExportField($this->color);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->content->Exportable) $Doc->ExportField($this->content);
						if ($this->financialValue->Exportable) $Doc->ExportField($this->financialValue);
						if ($this->registrationUser->Exportable) $Doc->ExportField($this->registrationUser);
						if ($this->registrationDateTime->Exportable) $Doc->ExportField($this->registrationDateTime);
						if ($this->registrationStation->Exportable) $Doc->ExportField($this->registrationStation);
						if ($this->isolatedDateTime->Exportable) $Doc->ExportField($this->isolatedDateTime);
						if ($this->description->Exportable) $Doc->ExportField($this->description);
					} else {
						if ($this->objectID->Exportable) $Doc->ExportField($this->objectID);
						if ($this->objectName->Exportable) $Doc->ExportField($this->objectName);
						if ($this->ownerID->Exportable) $Doc->ExportField($this->ownerID);
						if ($this->ownerName->Exportable) $Doc->ExportField($this->ownerName);
						if ($this->lastName->Exportable) $Doc->ExportField($this->lastName);
						if ($this->mobilePhone->Exportable) $Doc->ExportField($this->mobilePhone);
						if ($this->color->Exportable) $Doc->ExportField($this->color);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->content->Exportable) $Doc->ExportField($this->content);
						if ($this->financialValue->Exportable) $Doc->ExportField($this->financialValue);
						if ($this->registrationUser->Exportable) $Doc->ExportField($this->registrationUser);
						if ($this->registrationDateTime->Exportable) $Doc->ExportField($this->registrationDateTime);
						if ($this->registrationStation->Exportable) $Doc->ExportField($this->registrationStation);
						if ($this->isolatedDateTime->Exportable) $Doc->ExportField($this->isolatedDateTime);
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
