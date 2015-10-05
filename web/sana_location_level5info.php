<?php

// Global variable for table object
$sana_location_level5 = NULL;

//
// Table class for sana_location_level5
//
class csana_location_level5 extends cTable {
	var $locationLevel5ID;
	var $locationName;
	var $locationLevel4ID;
	var $locationLevel4Name;
	var $locationLevel1ID;
	var $locationLevel2ID;
	var $locationLevel3ID;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'sana_location_level5';
		$this->TableName = 'sana_location_level5';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`sana_location_level5`";
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

		// locationLevel5ID
		$this->locationLevel5ID = new cField('sana_location_level5', 'sana_location_level5', 'x_locationLevel5ID', 'locationLevel5ID', '`locationLevel5ID`', '`locationLevel5ID`', 3, -1, FALSE, '`locationLevel5ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->locationLevel5ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['locationLevel5ID'] = &$this->locationLevel5ID;

		// locationName
		$this->locationName = new cField('sana_location_level5', 'sana_location_level5', 'x_locationName', 'locationName', '`locationName`', '`locationName`', 200, -1, FALSE, '`locationName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['locationName'] = &$this->locationName;

		// locationLevel4ID
		$this->locationLevel4ID = new cField('sana_location_level5', 'sana_location_level5', 'x_locationLevel4ID', 'locationLevel4ID', '`locationLevel4ID`', '`locationLevel4ID`', 3, -1, FALSE, '`locationLevel4ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->locationLevel4ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['locationLevel4ID'] = &$this->locationLevel4ID;

		// locationLevel4Name
		$this->locationLevel4Name = new cField('sana_location_level5', 'sana_location_level5', 'x_locationLevel4Name', 'locationLevel4Name', '`locationLevel4Name`', '`locationLevel4Name`', 200, -1, FALSE, '`locationLevel4Name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['locationLevel4Name'] = &$this->locationLevel4Name;

		// locationLevel1ID
		$this->locationLevel1ID = new cField('sana_location_level5', 'sana_location_level5', 'x_locationLevel1ID', 'locationLevel1ID', '`locationLevel1ID`', '`locationLevel1ID`', 3, -1, FALSE, '`locationLevel1ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->locationLevel1ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['locationLevel1ID'] = &$this->locationLevel1ID;

		// locationLevel2ID
		$this->locationLevel2ID = new cField('sana_location_level5', 'sana_location_level5', 'x_locationLevel2ID', 'locationLevel2ID', '`locationLevel2ID`', '`locationLevel2ID`', 3, -1, FALSE, '`locationLevel2ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->locationLevel2ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['locationLevel2ID'] = &$this->locationLevel2ID;

		// locationLevel3ID
		$this->locationLevel3ID = new cField('sana_location_level5', 'sana_location_level5', 'x_locationLevel3ID', 'locationLevel3ID', '`locationLevel3ID`', '`locationLevel3ID`', 3, -1, FALSE, '`locationLevel3ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->locationLevel3ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['locationLevel3ID'] = &$this->locationLevel3ID;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`sana_location_level5`";
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
			if (array_key_exists('locationLevel5ID', $rs))
				ew_AddFilter($where, ew_QuotedName('locationLevel5ID', $this->DBID) . '=' . ew_QuotedValue($rs['locationLevel5ID'], $this->locationLevel5ID->FldDataType, $this->DBID));
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
		return "`locationLevel5ID` = @locationLevel5ID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->locationLevel5ID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@locationLevel5ID@", ew_AdjustSql($this->locationLevel5ID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "sana_location_level5list.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "sana_location_level5list.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("sana_location_level5view.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("sana_location_level5view.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "sana_location_level5add.php?" . $this->UrlParm($parm);
		else
			$url = "sana_location_level5add.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("sana_location_level5edit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("sana_location_level5add.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("sana_location_level5delete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "locationLevel5ID:" . ew_VarToJson($this->locationLevel5ID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->locationLevel5ID->CurrentValue)) {
			$sUrl .= "locationLevel5ID=" . urlencode($this->locationLevel5ID->CurrentValue);
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
			if ($isPost && isset($_POST["locationLevel5ID"]))
				$arKeys[] = ew_StripSlashes($_POST["locationLevel5ID"]);
			elseif (isset($_GET["locationLevel5ID"]))
				$arKeys[] = ew_StripSlashes($_GET["locationLevel5ID"]);
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
			$this->locationLevel5ID->CurrentValue = $key;
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
		$this->locationLevel5ID->setDbValue($rs->fields('locationLevel5ID'));
		$this->locationName->setDbValue($rs->fields('locationName'));
		$this->locationLevel4ID->setDbValue($rs->fields('locationLevel4ID'));
		$this->locationLevel4Name->setDbValue($rs->fields('locationLevel4Name'));
		$this->locationLevel1ID->setDbValue($rs->fields('locationLevel1ID'));
		$this->locationLevel2ID->setDbValue($rs->fields('locationLevel2ID'));
		$this->locationLevel3ID->setDbValue($rs->fields('locationLevel3ID'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// locationLevel5ID
		// locationName
		// locationLevel4ID
		// locationLevel4Name
		// locationLevel1ID
		// locationLevel2ID
		// locationLevel3ID
		// locationLevel5ID

		$this->locationLevel5ID->ViewValue = $this->locationLevel5ID->CurrentValue;
		$this->locationLevel5ID->ViewCustomAttributes = "";

		// locationName
		$this->locationName->ViewValue = $this->locationName->CurrentValue;
		$this->locationName->ViewCustomAttributes = "";

		// locationLevel4ID
		if (strval($this->locationLevel4ID->CurrentValue) <> "") {
			$sFilterWrk = "`locationLevel4ID`" . ew_SearchString("=", $this->locationLevel4ID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `locationLevel4ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level4`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `locationLevel4ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level4`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `locationLevel4ID`, `locationName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_location_level4`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->locationLevel4ID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->locationLevel4ID->ViewValue = $this->locationLevel4ID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->locationLevel4ID->ViewValue = $this->locationLevel4ID->CurrentValue;
			}
		} else {
			$this->locationLevel4ID->ViewValue = NULL;
		}
		$this->locationLevel4ID->ViewCustomAttributes = "";

		// locationLevel4Name
		$this->locationLevel4Name->ViewValue = $this->locationLevel4Name->CurrentValue;
		$this->locationLevel4Name->ViewCustomAttributes = "";

		// locationLevel1ID
		$this->locationLevel1ID->ViewValue = $this->locationLevel1ID->CurrentValue;
		$this->locationLevel1ID->ViewCustomAttributes = "";

		// locationLevel2ID
		$this->locationLevel2ID->ViewValue = $this->locationLevel2ID->CurrentValue;
		$this->locationLevel2ID->ViewCustomAttributes = "";

		// locationLevel3ID
		$this->locationLevel3ID->ViewValue = $this->locationLevel3ID->CurrentValue;
		$this->locationLevel3ID->ViewCustomAttributes = "";

		// locationLevel5ID
		$this->locationLevel5ID->LinkCustomAttributes = "";
		$this->locationLevel5ID->HrefValue = "";
		$this->locationLevel5ID->TooltipValue = "";

		// locationName
		$this->locationName->LinkCustomAttributes = "";
		$this->locationName->HrefValue = "";
		$this->locationName->TooltipValue = "";

		// locationLevel4ID
		$this->locationLevel4ID->LinkCustomAttributes = "";
		$this->locationLevel4ID->HrefValue = "";
		$this->locationLevel4ID->TooltipValue = "";

		// locationLevel4Name
		$this->locationLevel4Name->LinkCustomAttributes = "";
		$this->locationLevel4Name->HrefValue = "";
		$this->locationLevel4Name->TooltipValue = "";

		// locationLevel1ID
		$this->locationLevel1ID->LinkCustomAttributes = "";
		$this->locationLevel1ID->HrefValue = "";
		$this->locationLevel1ID->TooltipValue = "";

		// locationLevel2ID
		$this->locationLevel2ID->LinkCustomAttributes = "";
		$this->locationLevel2ID->HrefValue = "";
		$this->locationLevel2ID->TooltipValue = "";

		// locationLevel3ID
		$this->locationLevel3ID->LinkCustomAttributes = "";
		$this->locationLevel3ID->HrefValue = "";
		$this->locationLevel3ID->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// locationLevel5ID
		$this->locationLevel5ID->EditAttrs["class"] = "form-control";
		$this->locationLevel5ID->EditCustomAttributes = "";
		$this->locationLevel5ID->EditValue = $this->locationLevel5ID->CurrentValue;
		$this->locationLevel5ID->ViewCustomAttributes = "";

		// locationName
		$this->locationName->EditAttrs["class"] = "form-control";
		$this->locationName->EditCustomAttributes = "";
		$this->locationName->EditValue = $this->locationName->CurrentValue;
		$this->locationName->PlaceHolder = ew_RemoveHtml($this->locationName->FldCaption());

		// locationLevel4ID
		$this->locationLevel4ID->EditAttrs["class"] = "form-control";
		$this->locationLevel4ID->EditCustomAttributes = "";

		// locationLevel4Name
		$this->locationLevel4Name->EditAttrs["class"] = "form-control";
		$this->locationLevel4Name->EditCustomAttributes = "";
		$this->locationLevel4Name->EditValue = $this->locationLevel4Name->CurrentValue;
		$this->locationLevel4Name->PlaceHolder = ew_RemoveHtml($this->locationLevel4Name->FldCaption());

		// locationLevel1ID
		$this->locationLevel1ID->EditAttrs["class"] = "form-control";
		$this->locationLevel1ID->EditCustomAttributes = "";
		$this->locationLevel1ID->EditValue = $this->locationLevel1ID->CurrentValue;
		$this->locationLevel1ID->PlaceHolder = ew_RemoveHtml($this->locationLevel1ID->FldCaption());

		// locationLevel2ID
		$this->locationLevel2ID->EditAttrs["class"] = "form-control";
		$this->locationLevel2ID->EditCustomAttributes = "";
		$this->locationLevel2ID->EditValue = $this->locationLevel2ID->CurrentValue;
		$this->locationLevel2ID->PlaceHolder = ew_RemoveHtml($this->locationLevel2ID->FldCaption());

		// locationLevel3ID
		$this->locationLevel3ID->EditAttrs["class"] = "form-control";
		$this->locationLevel3ID->EditCustomAttributes = "";
		$this->locationLevel3ID->EditValue = $this->locationLevel3ID->CurrentValue;
		$this->locationLevel3ID->PlaceHolder = ew_RemoveHtml($this->locationLevel3ID->FldCaption());

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
					if ($this->locationLevel5ID->Exportable) $Doc->ExportCaption($this->locationLevel5ID);
					if ($this->locationName->Exportable) $Doc->ExportCaption($this->locationName);
					if ($this->locationLevel4ID->Exportable) $Doc->ExportCaption($this->locationLevel4ID);
					if ($this->locationLevel4Name->Exportable) $Doc->ExportCaption($this->locationLevel4Name);
					if ($this->locationLevel1ID->Exportable) $Doc->ExportCaption($this->locationLevel1ID);
					if ($this->locationLevel2ID->Exportable) $Doc->ExportCaption($this->locationLevel2ID);
					if ($this->locationLevel3ID->Exportable) $Doc->ExportCaption($this->locationLevel3ID);
				} else {
					if ($this->locationLevel5ID->Exportable) $Doc->ExportCaption($this->locationLevel5ID);
					if ($this->locationName->Exportable) $Doc->ExportCaption($this->locationName);
					if ($this->locationLevel4ID->Exportable) $Doc->ExportCaption($this->locationLevel4ID);
					if ($this->locationLevel4Name->Exportable) $Doc->ExportCaption($this->locationLevel4Name);
					if ($this->locationLevel1ID->Exportable) $Doc->ExportCaption($this->locationLevel1ID);
					if ($this->locationLevel2ID->Exportable) $Doc->ExportCaption($this->locationLevel2ID);
					if ($this->locationLevel3ID->Exportable) $Doc->ExportCaption($this->locationLevel3ID);
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
						if ($this->locationLevel5ID->Exportable) $Doc->ExportField($this->locationLevel5ID);
						if ($this->locationName->Exportable) $Doc->ExportField($this->locationName);
						if ($this->locationLevel4ID->Exportable) $Doc->ExportField($this->locationLevel4ID);
						if ($this->locationLevel4Name->Exportable) $Doc->ExportField($this->locationLevel4Name);
						if ($this->locationLevel1ID->Exportable) $Doc->ExportField($this->locationLevel1ID);
						if ($this->locationLevel2ID->Exportable) $Doc->ExportField($this->locationLevel2ID);
						if ($this->locationLevel3ID->Exportable) $Doc->ExportField($this->locationLevel3ID);
					} else {
						if ($this->locationLevel5ID->Exportable) $Doc->ExportField($this->locationLevel5ID);
						if ($this->locationName->Exportable) $Doc->ExportField($this->locationName);
						if ($this->locationLevel4ID->Exportable) $Doc->ExportField($this->locationLevel4ID);
						if ($this->locationLevel4Name->Exportable) $Doc->ExportField($this->locationLevel4Name);
						if ($this->locationLevel1ID->Exportable) $Doc->ExportField($this->locationLevel1ID);
						if ($this->locationLevel2ID->Exportable) $Doc->ExportField($this->locationLevel2ID);
						if ($this->locationLevel3ID->Exportable) $Doc->ExportField($this->locationLevel3ID);
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
