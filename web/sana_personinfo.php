<?php

// Global variable for table object
$sana_person = NULL;

//
// Table class for sana_person
//
class csana_person extends cTable {
	var $personID;
	var $personName;
	var $lastName;
	var $nationalID;
	var $mobilePhone;
	var $nationalNumber;
	var $passportNumber;
	var $fatherName;
	var $gender;
	var $locationLevel1;
	var $locationLevel2;
	var $locationLevel3;
	var $locationLevel4;
	var $locationLevel5;
	var $locationLevel6;
	var $address;
	var $convoy;
	var $convoyManager;
	var $followersName;
	var $status;
	var $isolatedLocation;
	var $birthDate;
	var $ageRange;
	var $dress1;
	var $dress2;
	var $signTags;
	var $phone;
	var $_email;
	var $temporaryResidence;
	var $visitsCount;
	var $picture;
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
		$this->TableVar = 'sana_person';
		$this->TableName = 'sana_person';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`sana_person`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// personID
		$this->personID = new cField('sana_person', 'sana_person', 'x_personID', 'personID', '`personID`', '`personID`', 20, -1, FALSE, '`personID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->personID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['personID'] = &$this->personID;

		// personName
		$this->personName = new cField('sana_person', 'sana_person', 'x_personName', 'personName', '`personName`', '`personName`', 200, -1, FALSE, '`personName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['personName'] = &$this->personName;

		// lastName
		$this->lastName = new cField('sana_person', 'sana_person', 'x_lastName', 'lastName', '`lastName`', '`lastName`', 200, -1, FALSE, '`lastName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['lastName'] = &$this->lastName;

		// nationalID
		$this->nationalID = new cField('sana_person', 'sana_person', 'x_nationalID', 'nationalID', '`nationalID`', '`nationalID`', 200, -1, FALSE, '`nationalID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['nationalID'] = &$this->nationalID;

		// mobilePhone
		$this->mobilePhone = new cField('sana_person', 'sana_person', 'x_mobilePhone', 'mobilePhone', '`mobilePhone`', '`mobilePhone`', 200, -1, FALSE, '`mobilePhone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['mobilePhone'] = &$this->mobilePhone;

		// nationalNumber
		$this->nationalNumber = new cField('sana_person', 'sana_person', 'x_nationalNumber', 'nationalNumber', '`nationalNumber`', '`nationalNumber`', 200, -1, FALSE, '`nationalNumber`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['nationalNumber'] = &$this->nationalNumber;

		// passportNumber
		$this->passportNumber = new cField('sana_person', 'sana_person', 'x_passportNumber', 'passportNumber', '`passportNumber`', '`passportNumber`', 200, -1, FALSE, '`passportNumber`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['passportNumber'] = &$this->passportNumber;

		// fatherName
		$this->fatherName = new cField('sana_person', 'sana_person', 'x_fatherName', 'fatherName', '`fatherName`', '`fatherName`', 200, -1, FALSE, '`fatherName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['fatherName'] = &$this->fatherName;

		// gender
		$this->gender = new cField('sana_person', 'sana_person', 'x_gender', 'gender', '`gender`', '`gender`', 200, -1, FALSE, '`gender`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->fields['gender'] = &$this->gender;

		// locationLevel1
		$this->locationLevel1 = new cField('sana_person', 'sana_person', 'x_locationLevel1', 'locationLevel1', '`locationLevel1`', '`locationLevel1`', 200, -1, FALSE, '`locationLevel1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->fields['locationLevel1'] = &$this->locationLevel1;

		// locationLevel2
		$this->locationLevel2 = new cField('sana_person', 'sana_person', 'x_locationLevel2', 'locationLevel2', '`locationLevel2`', '`locationLevel2`', 200, -1, FALSE, '`locationLevel2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->fields['locationLevel2'] = &$this->locationLevel2;

		// locationLevel3
		$this->locationLevel3 = new cField('sana_person', 'sana_person', 'x_locationLevel3', 'locationLevel3', '`locationLevel3`', '`locationLevel3`', 200, -1, FALSE, '`locationLevel3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->fields['locationLevel3'] = &$this->locationLevel3;

		// locationLevel4
		$this->locationLevel4 = new cField('sana_person', 'sana_person', 'x_locationLevel4', 'locationLevel4', '`locationLevel4`', '`locationLevel4`', 200, -1, FALSE, '`locationLevel4`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['locationLevel4'] = &$this->locationLevel4;

		// locationLevel5
		$this->locationLevel5 = new cField('sana_person', 'sana_person', 'x_locationLevel5', 'locationLevel5', '`locationLevel5`', '`locationLevel5`', 200, -1, FALSE, '`locationLevel5`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['locationLevel5'] = &$this->locationLevel5;

		// locationLevel6
		$this->locationLevel6 = new cField('sana_person', 'sana_person', 'x_locationLevel6', 'locationLevel6', '`locationLevel6`', '`locationLevel6`', 200, -1, FALSE, '`locationLevel6`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['locationLevel6'] = &$this->locationLevel6;

		// address
		$this->address = new cField('sana_person', 'sana_person', 'x_address', 'address', '`address`', '`address`', 200, -1, FALSE, '`address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['address'] = &$this->address;

		// convoy
		$this->convoy = new cField('sana_person', 'sana_person', 'x_convoy', 'convoy', '`convoy`', '`convoy`', 200, -1, FALSE, '`convoy`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['convoy'] = &$this->convoy;

		// convoyManager
		$this->convoyManager = new cField('sana_person', 'sana_person', 'x_convoyManager', 'convoyManager', '`convoyManager`', '`convoyManager`', 200, -1, FALSE, '`convoyManager`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['convoyManager'] = &$this->convoyManager;

		// followersName
		$this->followersName = new cField('sana_person', 'sana_person', 'x_followersName', 'followersName', '`followersName`', '`followersName`', 200, -1, FALSE, '`followersName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['followersName'] = &$this->followersName;

		// status
		$this->status = new cField('sana_person', 'sana_person', 'x_status', 'status', '`status`', '`status`', 200, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->fields['status'] = &$this->status;

		// isolatedLocation
		$this->isolatedLocation = new cField('sana_person', 'sana_person', 'x_isolatedLocation', 'isolatedLocation', '`isolatedLocation`', '`isolatedLocation`', 200, -1, FALSE, '`isolatedLocation`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['isolatedLocation'] = &$this->isolatedLocation;

		// birthDate
		$this->birthDate = new cField('sana_person', 'sana_person', 'x_birthDate', 'birthDate', '`birthDate`', '`birthDate`', 3, -1, FALSE, '`birthDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->birthDate->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['birthDate'] = &$this->birthDate;

		// ageRange
		$this->ageRange = new cField('sana_person', 'sana_person', 'x_ageRange', 'ageRange', '`ageRange`', '`ageRange`', 200, -1, FALSE, '`ageRange`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->fields['ageRange'] = &$this->ageRange;

		// dress1
		$this->dress1 = new cField('sana_person', 'sana_person', 'x_dress1', 'dress1', '`dress1`', '`dress1`', 200, -1, FALSE, '`dress1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['dress1'] = &$this->dress1;

		// dress2
		$this->dress2 = new cField('sana_person', 'sana_person', 'x_dress2', 'dress2', '`dress2`', '`dress2`', 200, -1, FALSE, '`dress2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['dress2'] = &$this->dress2;

		// signTags
		$this->signTags = new cField('sana_person', 'sana_person', 'x_signTags', 'signTags', '`signTags`', '`signTags`', 200, -1, FALSE, '`signTags`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['signTags'] = &$this->signTags;

		// phone
		$this->phone = new cField('sana_person', 'sana_person', 'x_phone', 'phone', '`phone`', '`phone`', 200, -1, FALSE, '`phone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['phone'] = &$this->phone;

		// email
		$this->_email = new cField('sana_person', 'sana_person', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_email->FldDefaultErrMsg = $Language->Phrase("IncorrectEmail");
		$this->fields['email'] = &$this->_email;

		// temporaryResidence
		$this->temporaryResidence = new cField('sana_person', 'sana_person', 'x_temporaryResidence', 'temporaryResidence', '`temporaryResidence`', '`temporaryResidence`', 200, -1, FALSE, '`temporaryResidence`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['temporaryResidence'] = &$this->temporaryResidence;

		// visitsCount
		$this->visitsCount = new cField('sana_person', 'sana_person', 'x_visitsCount', 'visitsCount', '`visitsCount`', '`visitsCount`', 3, -1, FALSE, '`visitsCount`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->visitsCount->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['visitsCount'] = &$this->visitsCount;

		// picture
		$this->picture = new cField('sana_person', 'sana_person', 'x_picture', 'picture', '`picture`', '`picture`', 200, -1, TRUE, '`picture`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->fields['picture'] = &$this->picture;

		// registrationUser
		$this->registrationUser = new cField('sana_person', 'sana_person', 'x_registrationUser', 'registrationUser', '`registrationUser`', '`registrationUser`', 3, -1, FALSE, '`registrationUser`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'HIDDEN');
		$this->registrationUser->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['registrationUser'] = &$this->registrationUser;

		// registrationDateTime
		$this->registrationDateTime = new cField('sana_person', 'sana_person', 'x_registrationDateTime', 'registrationDateTime', '`registrationDateTime`', 'DATE_FORMAT(`registrationDateTime`, \'%Y/%m/%d\')', 135, 5, FALSE, '`registrationDateTime`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->registrationDateTime->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['registrationDateTime'] = &$this->registrationDateTime;

		// registrationStation
		$this->registrationStation = new cField('sana_person', 'sana_person', 'x_registrationStation', 'registrationStation', '`registrationStation`', '`registrationStation`', 3, -1, FALSE, '`registrationStation`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->registrationStation->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['registrationStation'] = &$this->registrationStation;

		// isolatedDateTime
		$this->isolatedDateTime = new cField('sana_person', 'sana_person', 'x_isolatedDateTime', 'isolatedDateTime', '`isolatedDateTime`', 'DATE_FORMAT(`isolatedDateTime`, \'%Y/%m/%d\')', 135, 5, FALSE, '`isolatedDateTime`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->isolatedDateTime->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['isolatedDateTime'] = &$this->isolatedDateTime;

		// description
		$this->description = new cField('sana_person', 'sana_person', 'x_description', 'description', '`description`', '`description`', 201, -1, FALSE, '`description`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
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

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "sana_message") {
			$sDetailUrl = $GLOBALS["sana_message"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_personID=" . urlencode($this->personID->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "sana_personlist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`sana_person`";
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
			if (array_key_exists('personID', $rs))
				ew_AddFilter($where, ew_QuotedName('personID', $this->DBID) . '=' . ew_QuotedValue($rs['personID'], $this->personID->FldDataType, $this->DBID));
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

		// Cascade delete detail table 'sana_message'
		if (!isset($GLOBALS["sana_message"])) $GLOBALS["sana_message"] = new csana_message();
		$rscascade = $GLOBALS["sana_message"]->LoadRs("`personID` = " . ew_QuotedValue($rs['personID'], EW_DATATYPE_NUMBER, "DB")); 
		while ($rscascade && !$rscascade->EOF) {
			$GLOBALS["sana_message"]->Delete($rscascade->fields);
			$rscascade->MoveNext();
		}
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`personID` = @personID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->personID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@personID@", ew_AdjustSql($this->personID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "sana_personlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "sana_personlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("sana_personview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("sana_personview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "sana_personadd.php?" . $this->UrlParm($parm);
		else
			$url = "sana_personadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("sana_personedit.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("sana_personedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("sana_personadd.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("sana_personadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("sana_persondelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "personID:" . ew_VarToJson($this->personID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->personID->CurrentValue)) {
			$sUrl .= "personID=" . urlencode($this->personID->CurrentValue);
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
			if ($isPost && isset($_POST["personID"]))
				$arKeys[] = ew_StripSlashes($_POST["personID"]);
			elseif (isset($_GET["personID"]))
				$arKeys[] = ew_StripSlashes($_GET["personID"]);
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
			$this->personID->CurrentValue = $key;
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
		$this->personID->setDbValue($rs->fields('personID'));
		$this->personName->setDbValue($rs->fields('personName'));
		$this->lastName->setDbValue($rs->fields('lastName'));
		$this->nationalID->setDbValue($rs->fields('nationalID'));
		$this->mobilePhone->setDbValue($rs->fields('mobilePhone'));
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
		$this->_email->setDbValue($rs->fields('email'));
		$this->temporaryResidence->setDbValue($rs->fields('temporaryResidence'));
		$this->visitsCount->setDbValue($rs->fields('visitsCount'));
		$this->picture->Upload->DbValue = $rs->fields('picture');
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
		// personID

		$this->personID->CellCssStyle = "white-space: nowrap;";

		// personName
		// lastName
		// nationalID
		// mobilePhone
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
		// email
		// temporaryResidence
		// visitsCount
		// picture
		// registrationUser
		// registrationDateTime
		// registrationStation
		// isolatedDateTime
		// description
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

		// mobilePhone
		$this->mobilePhone->ViewValue = $this->mobilePhone->CurrentValue;
		$this->mobilePhone->ViewCustomAttributes = "";

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
		if (strval($this->registrationStation->CurrentValue) <> "") {
			$sFilterWrk = "`stationID`" . ew_SearchString("=", $this->registrationStation->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `stationID`, `stationID` AS `DispFld`, `stationName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_station`";
				$sWhereWrk = "";
				break;
			case "fa":
				$sSqlWrk = "SELECT `stationID`, `stationID` AS `DispFld`, `stationName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_station`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `stationID`, `stationID` AS `DispFld`, `stationName` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sana_station`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->registrationStation, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->registrationStation->ViewValue = $this->registrationStation->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->registrationStation->ViewValue = $this->registrationStation->CurrentValue;
			}
		} else {
			$this->registrationStation->ViewValue = NULL;
		}
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

		// mobilePhone
		$this->mobilePhone->LinkCustomAttributes = "";
		$this->mobilePhone->HrefValue = "";
		$this->mobilePhone->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// personID
		$this->personID->EditAttrs["class"] = "form-control";
		$this->personID->EditCustomAttributes = "";
		$this->personID->EditValue = $this->personID->CurrentValue;
		$this->personID->ViewCustomAttributes = "";

		// personName
		$this->personName->EditAttrs["class"] = "form-control";
		$this->personName->EditCustomAttributes = "";
		$this->personName->EditValue = $this->personName->CurrentValue;
		$this->personName->PlaceHolder = ew_RemoveHtml($this->personName->FldCaption());

		// lastName
		$this->lastName->EditAttrs["class"] = "form-control";
		$this->lastName->EditCustomAttributes = "";
		$this->lastName->EditValue = $this->lastName->CurrentValue;
		$this->lastName->PlaceHolder = ew_RemoveHtml($this->lastName->FldCaption());

		// nationalID
		$this->nationalID->EditAttrs["class"] = "form-control";
		$this->nationalID->EditCustomAttributes = "";
		$this->nationalID->EditValue = $this->nationalID->CurrentValue;
		$this->nationalID->PlaceHolder = ew_RemoveHtml($this->nationalID->FldCaption());

		// mobilePhone
		$this->mobilePhone->EditAttrs["class"] = "form-control";
		$this->mobilePhone->EditCustomAttributes = "";
		$this->mobilePhone->EditValue = $this->mobilePhone->CurrentValue;
		$this->mobilePhone->PlaceHolder = ew_RemoveHtml($this->mobilePhone->FldCaption());

		// nationalNumber
		$this->nationalNumber->EditAttrs["class"] = "form-control";
		$this->nationalNumber->EditCustomAttributes = "";
		$this->nationalNumber->EditValue = $this->nationalNumber->CurrentValue;
		$this->nationalNumber->PlaceHolder = ew_RemoveHtml($this->nationalNumber->FldCaption());

		// passportNumber
		$this->passportNumber->EditAttrs["class"] = "form-control";
		$this->passportNumber->EditCustomAttributes = "";
		$this->passportNumber->EditValue = $this->passportNumber->CurrentValue;
		$this->passportNumber->PlaceHolder = ew_RemoveHtml($this->passportNumber->FldCaption());

		// fatherName
		$this->fatherName->EditAttrs["class"] = "form-control";
		$this->fatherName->EditCustomAttributes = "";
		$this->fatherName->EditValue = $this->fatherName->CurrentValue;
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

		// locationLevel3
		$this->locationLevel3->EditAttrs["class"] = "form-control";
		$this->locationLevel3->EditCustomAttributes = "";

		// locationLevel4
		$this->locationLevel4->EditAttrs["class"] = "form-control";
		$this->locationLevel4->EditCustomAttributes = "";
		$this->locationLevel4->EditValue = $this->locationLevel4->CurrentValue;
		$this->locationLevel4->PlaceHolder = ew_RemoveHtml($this->locationLevel4->FldCaption());

		// locationLevel5
		$this->locationLevel5->EditAttrs["class"] = "form-control";
		$this->locationLevel5->EditCustomAttributes = "";
		$this->locationLevel5->EditValue = $this->locationLevel5->CurrentValue;
		$this->locationLevel5->PlaceHolder = ew_RemoveHtml($this->locationLevel5->FldCaption());

		// locationLevel6
		$this->locationLevel6->EditAttrs["class"] = "form-control";
		$this->locationLevel6->EditCustomAttributes = "";
		$this->locationLevel6->EditValue = $this->locationLevel6->CurrentValue;
		$this->locationLevel6->PlaceHolder = ew_RemoveHtml($this->locationLevel6->FldCaption());

		// address
		$this->address->EditAttrs["class"] = "form-control";
		$this->address->EditCustomAttributes = "";
		$this->address->EditValue = $this->address->CurrentValue;
		$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

		// convoy
		$this->convoy->EditAttrs["class"] = "form-control";
		$this->convoy->EditCustomAttributes = "";
		$this->convoy->EditValue = $this->convoy->CurrentValue;
		$this->convoy->PlaceHolder = ew_RemoveHtml($this->convoy->FldCaption());

		// convoyManager
		$this->convoyManager->EditAttrs["class"] = "form-control";
		$this->convoyManager->EditCustomAttributes = "";
		$this->convoyManager->EditValue = $this->convoyManager->CurrentValue;
		$this->convoyManager->PlaceHolder = ew_RemoveHtml($this->convoyManager->FldCaption());

		// followersName
		$this->followersName->EditAttrs["class"] = "form-control";
		$this->followersName->EditCustomAttributes = "";
		$this->followersName->EditValue = $this->followersName->CurrentValue;
		$this->followersName->PlaceHolder = ew_RemoveHtml($this->followersName->FldCaption());

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";

		// isolatedLocation
		$this->isolatedLocation->EditAttrs["class"] = "form-control";
		$this->isolatedLocation->EditCustomAttributes = "";
		$this->isolatedLocation->EditValue = $this->isolatedLocation->CurrentValue;
		$this->isolatedLocation->PlaceHolder = ew_RemoveHtml($this->isolatedLocation->FldCaption());

		// birthDate
		$this->birthDate->EditAttrs["class"] = "form-control";
		$this->birthDate->EditCustomAttributes = "";
		$this->birthDate->EditValue = $this->birthDate->CurrentValue;
		$this->birthDate->PlaceHolder = ew_RemoveHtml($this->birthDate->FldCaption());

		// ageRange
		$this->ageRange->EditAttrs["class"] = "form-control";
		$this->ageRange->EditCustomAttributes = "";

		// dress1
		$this->dress1->EditAttrs["class"] = "form-control";
		$this->dress1->EditCustomAttributes = "";
		$this->dress1->EditValue = $this->dress1->CurrentValue;
		$this->dress1->PlaceHolder = ew_RemoveHtml($this->dress1->FldCaption());

		// dress2
		$this->dress2->EditAttrs["class"] = "form-control";
		$this->dress2->EditCustomAttributes = "";
		$this->dress2->EditValue = $this->dress2->CurrentValue;
		$this->dress2->PlaceHolder = ew_RemoveHtml($this->dress2->FldCaption());

		// signTags
		$this->signTags->EditAttrs["class"] = "form-control";
		$this->signTags->EditCustomAttributes = "";
		$this->signTags->EditValue = $this->signTags->CurrentValue;
		$this->signTags->PlaceHolder = ew_RemoveHtml($this->signTags->FldCaption());

		// phone
		$this->phone->EditAttrs["class"] = "form-control";
		$this->phone->EditCustomAttributes = "";
		$this->phone->EditValue = $this->phone->CurrentValue;
		$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = $this->_email->CurrentValue;
		$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

		// temporaryResidence
		$this->temporaryResidence->EditAttrs["class"] = "form-control";
		$this->temporaryResidence->EditCustomAttributes = "";
		$this->temporaryResidence->EditValue = $this->temporaryResidence->CurrentValue;
		$this->temporaryResidence->PlaceHolder = ew_RemoveHtml($this->temporaryResidence->FldCaption());

		// visitsCount
		$this->visitsCount->EditAttrs["class"] = "form-control";
		$this->visitsCount->EditCustomAttributes = "";
		$this->visitsCount->EditValue = $this->visitsCount->CurrentValue;
		$this->visitsCount->PlaceHolder = ew_RemoveHtml($this->visitsCount->FldCaption());

		// picture
		$this->picture->EditAttrs["class"] = "form-control";
		$this->picture->EditCustomAttributes = "";
		if (!ew_Empty($this->picture->Upload->DbValue)) {
			$this->picture->ImageWidth = 70;
			$this->picture->ImageHeight = 70;
			$this->picture->ImageAlt = $this->picture->FldAlt();
			$this->picture->EditValue = $this->picture->Upload->DbValue;
		} else {
			$this->picture->EditValue = "";
		}
		if (!ew_Empty($this->picture->CurrentValue))
			$this->picture->Upload->FileName = $this->picture->CurrentValue;

		// registrationUser
		// registrationDateTime
		// registrationStation
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
					if ($this->personName->Exportable) $Doc->ExportCaption($this->personName);
					if ($this->lastName->Exportable) $Doc->ExportCaption($this->lastName);
					if ($this->nationalID->Exportable) $Doc->ExportCaption($this->nationalID);
					if ($this->mobilePhone->Exportable) $Doc->ExportCaption($this->mobilePhone);
					if ($this->nationalNumber->Exportable) $Doc->ExportCaption($this->nationalNumber);
					if ($this->passportNumber->Exportable) $Doc->ExportCaption($this->passportNumber);
					if ($this->fatherName->Exportable) $Doc->ExportCaption($this->fatherName);
					if ($this->gender->Exportable) $Doc->ExportCaption($this->gender);
					if ($this->locationLevel1->Exportable) $Doc->ExportCaption($this->locationLevel1);
					if ($this->locationLevel2->Exportable) $Doc->ExportCaption($this->locationLevel2);
					if ($this->locationLevel3->Exportable) $Doc->ExportCaption($this->locationLevel3);
					if ($this->locationLevel4->Exportable) $Doc->ExportCaption($this->locationLevel4);
					if ($this->locationLevel5->Exportable) $Doc->ExportCaption($this->locationLevel5);
					if ($this->locationLevel6->Exportable) $Doc->ExportCaption($this->locationLevel6);
					if ($this->address->Exportable) $Doc->ExportCaption($this->address);
					if ($this->convoy->Exportable) $Doc->ExportCaption($this->convoy);
					if ($this->convoyManager->Exportable) $Doc->ExportCaption($this->convoyManager);
					if ($this->followersName->Exportable) $Doc->ExportCaption($this->followersName);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->isolatedLocation->Exportable) $Doc->ExportCaption($this->isolatedLocation);
					if ($this->birthDate->Exportable) $Doc->ExportCaption($this->birthDate);
					if ($this->ageRange->Exportable) $Doc->ExportCaption($this->ageRange);
					if ($this->dress1->Exportable) $Doc->ExportCaption($this->dress1);
					if ($this->dress2->Exportable) $Doc->ExportCaption($this->dress2);
					if ($this->signTags->Exportable) $Doc->ExportCaption($this->signTags);
					if ($this->phone->Exportable) $Doc->ExportCaption($this->phone);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->temporaryResidence->Exportable) $Doc->ExportCaption($this->temporaryResidence);
					if ($this->visitsCount->Exportable) $Doc->ExportCaption($this->visitsCount);
					if ($this->picture->Exportable) $Doc->ExportCaption($this->picture);
					if ($this->registrationUser->Exportable) $Doc->ExportCaption($this->registrationUser);
					if ($this->registrationDateTime->Exportable) $Doc->ExportCaption($this->registrationDateTime);
					if ($this->registrationStation->Exportable) $Doc->ExportCaption($this->registrationStation);
					if ($this->isolatedDateTime->Exportable) $Doc->ExportCaption($this->isolatedDateTime);
					if ($this->description->Exportable) $Doc->ExportCaption($this->description);
				} else {
					if ($this->personID->Exportable) $Doc->ExportCaption($this->personID);
					if ($this->personName->Exportable) $Doc->ExportCaption($this->personName);
					if ($this->lastName->Exportable) $Doc->ExportCaption($this->lastName);
					if ($this->nationalID->Exportable) $Doc->ExportCaption($this->nationalID);
					if ($this->mobilePhone->Exportable) $Doc->ExportCaption($this->mobilePhone);
					if ($this->nationalNumber->Exportable) $Doc->ExportCaption($this->nationalNumber);
					if ($this->passportNumber->Exportable) $Doc->ExportCaption($this->passportNumber);
					if ($this->fatherName->Exportable) $Doc->ExportCaption($this->fatherName);
					if ($this->gender->Exportable) $Doc->ExportCaption($this->gender);
					if ($this->locationLevel1->Exportable) $Doc->ExportCaption($this->locationLevel1);
					if ($this->locationLevel2->Exportable) $Doc->ExportCaption($this->locationLevel2);
					if ($this->locationLevel3->Exportable) $Doc->ExportCaption($this->locationLevel3);
					if ($this->locationLevel4->Exportable) $Doc->ExportCaption($this->locationLevel4);
					if ($this->locationLevel5->Exportable) $Doc->ExportCaption($this->locationLevel5);
					if ($this->locationLevel6->Exportable) $Doc->ExportCaption($this->locationLevel6);
					if ($this->address->Exportable) $Doc->ExportCaption($this->address);
					if ($this->convoy->Exportable) $Doc->ExportCaption($this->convoy);
					if ($this->convoyManager->Exportable) $Doc->ExportCaption($this->convoyManager);
					if ($this->followersName->Exportable) $Doc->ExportCaption($this->followersName);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->isolatedLocation->Exportable) $Doc->ExportCaption($this->isolatedLocation);
					if ($this->birthDate->Exportable) $Doc->ExportCaption($this->birthDate);
					if ($this->ageRange->Exportable) $Doc->ExportCaption($this->ageRange);
					if ($this->dress1->Exportable) $Doc->ExportCaption($this->dress1);
					if ($this->dress2->Exportable) $Doc->ExportCaption($this->dress2);
					if ($this->signTags->Exportable) $Doc->ExportCaption($this->signTags);
					if ($this->phone->Exportable) $Doc->ExportCaption($this->phone);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->temporaryResidence->Exportable) $Doc->ExportCaption($this->temporaryResidence);
					if ($this->visitsCount->Exportable) $Doc->ExportCaption($this->visitsCount);
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
						if ($this->personName->Exportable) $Doc->ExportField($this->personName);
						if ($this->lastName->Exportable) $Doc->ExportField($this->lastName);
						if ($this->nationalID->Exportable) $Doc->ExportField($this->nationalID);
						if ($this->mobilePhone->Exportable) $Doc->ExportField($this->mobilePhone);
						if ($this->nationalNumber->Exportable) $Doc->ExportField($this->nationalNumber);
						if ($this->passportNumber->Exportable) $Doc->ExportField($this->passportNumber);
						if ($this->fatherName->Exportable) $Doc->ExportField($this->fatherName);
						if ($this->gender->Exportable) $Doc->ExportField($this->gender);
						if ($this->locationLevel1->Exportable) $Doc->ExportField($this->locationLevel1);
						if ($this->locationLevel2->Exportable) $Doc->ExportField($this->locationLevel2);
						if ($this->locationLevel3->Exportable) $Doc->ExportField($this->locationLevel3);
						if ($this->locationLevel4->Exportable) $Doc->ExportField($this->locationLevel4);
						if ($this->locationLevel5->Exportable) $Doc->ExportField($this->locationLevel5);
						if ($this->locationLevel6->Exportable) $Doc->ExportField($this->locationLevel6);
						if ($this->address->Exportable) $Doc->ExportField($this->address);
						if ($this->convoy->Exportable) $Doc->ExportField($this->convoy);
						if ($this->convoyManager->Exportable) $Doc->ExportField($this->convoyManager);
						if ($this->followersName->Exportable) $Doc->ExportField($this->followersName);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->isolatedLocation->Exportable) $Doc->ExportField($this->isolatedLocation);
						if ($this->birthDate->Exportable) $Doc->ExportField($this->birthDate);
						if ($this->ageRange->Exportable) $Doc->ExportField($this->ageRange);
						if ($this->dress1->Exportable) $Doc->ExportField($this->dress1);
						if ($this->dress2->Exportable) $Doc->ExportField($this->dress2);
						if ($this->signTags->Exportable) $Doc->ExportField($this->signTags);
						if ($this->phone->Exportable) $Doc->ExportField($this->phone);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->temporaryResidence->Exportable) $Doc->ExportField($this->temporaryResidence);
						if ($this->visitsCount->Exportable) $Doc->ExportField($this->visitsCount);
						if ($this->picture->Exportable) $Doc->ExportField($this->picture);
						if ($this->registrationUser->Exportable) $Doc->ExportField($this->registrationUser);
						if ($this->registrationDateTime->Exportable) $Doc->ExportField($this->registrationDateTime);
						if ($this->registrationStation->Exportable) $Doc->ExportField($this->registrationStation);
						if ($this->isolatedDateTime->Exportable) $Doc->ExportField($this->isolatedDateTime);
						if ($this->description->Exportable) $Doc->ExportField($this->description);
					} else {
						if ($this->personID->Exportable) $Doc->ExportField($this->personID);
						if ($this->personName->Exportable) $Doc->ExportField($this->personName);
						if ($this->lastName->Exportable) $Doc->ExportField($this->lastName);
						if ($this->nationalID->Exportable) $Doc->ExportField($this->nationalID);
						if ($this->mobilePhone->Exportable) $Doc->ExportField($this->mobilePhone);
						if ($this->nationalNumber->Exportable) $Doc->ExportField($this->nationalNumber);
						if ($this->passportNumber->Exportable) $Doc->ExportField($this->passportNumber);
						if ($this->fatherName->Exportable) $Doc->ExportField($this->fatherName);
						if ($this->gender->Exportable) $Doc->ExportField($this->gender);
						if ($this->locationLevel1->Exportable) $Doc->ExportField($this->locationLevel1);
						if ($this->locationLevel2->Exportable) $Doc->ExportField($this->locationLevel2);
						if ($this->locationLevel3->Exportable) $Doc->ExportField($this->locationLevel3);
						if ($this->locationLevel4->Exportable) $Doc->ExportField($this->locationLevel4);
						if ($this->locationLevel5->Exportable) $Doc->ExportField($this->locationLevel5);
						if ($this->locationLevel6->Exportable) $Doc->ExportField($this->locationLevel6);
						if ($this->address->Exportable) $Doc->ExportField($this->address);
						if ($this->convoy->Exportable) $Doc->ExportField($this->convoy);
						if ($this->convoyManager->Exportable) $Doc->ExportField($this->convoyManager);
						if ($this->followersName->Exportable) $Doc->ExportField($this->followersName);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->isolatedLocation->Exportable) $Doc->ExportField($this->isolatedLocation);
						if ($this->birthDate->Exportable) $Doc->ExportField($this->birthDate);
						if ($this->ageRange->Exportable) $Doc->ExportField($this->ageRange);
						if ($this->dress1->Exportable) $Doc->ExportField($this->dress1);
						if ($this->dress2->Exportable) $Doc->ExportField($this->dress2);
						if ($this->signTags->Exportable) $Doc->ExportField($this->signTags);
						if ($this->phone->Exportable) $Doc->ExportField($this->phone);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->temporaryResidence->Exportable) $Doc->ExportField($this->temporaryResidence);
						if ($this->visitsCount->Exportable) $Doc->ExportField($this->visitsCount);
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
