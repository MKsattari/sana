<!--##session infoconfig##-->
<!--##
	// Common table variables
	sTblName = TABLE.TblName;

	nFlds = arAllFlds.length;
	nGroups = 0;
	sGroupFldNames = "";
	sGroupFldSrcs = "";
	sGroupFlds = "";
	nOrders = 0;
	sOrdFlds = "";
	sDefaultOrderBy = "";
	sOrderFlds = "";

	bCustomView = false;
	if (TABLE.TblType == "CUSTOMVIEW") {
		bCustomView = true;
	} else if (TABLE.TblType == "REPORT") {
		if (ew_IsNotEmpty(TABLE.TblRptSrc)) {
			WRKTABLE = DB.Tables(TABLE.TblRptSrc);
			if (WRKTABLE.TblType == "CUSTOMVIEW")
				bCustomView = true;
		}
	}

	// Report Level SQL
	if (TABLE.TblType == "REPORT") {

		var SRCTABLE = null;

		if (ew_IsNotEmpty(TABLE.TblRptSrc))
			SRCTABLE = DB.Tables(TABLE.TblRptSrc);

		for (var i = 1; i <= nFlds; i++) {
			for (var j = 0; j < nFlds; j++) {
				goFld = goFlds[arAllFlds[j]];
				
				// Group By
				if (goFld.FldGroupBy == i) { // Matched Group By Sequence
				
					// GroupByFieldCount
					nGroups += 1; // Increment no. of groups
					if (nGroups != i) goFld.FldGroupBy = nGroups; // Adjust Group By Sequence
					
					// GroupByFieldNames
					if (ew_IsNotEmpty(sGroupFldNames)) sGroupFldNames += ",";
					sGroupFldNames += goFld.FldName;
					
					// GroupByFieldSources
					if (ew_IsNotEmpty(sGroupFldSrcs)) sGroupFldSrcs += ",";
					if (bCustomView && ew_IsNotEmpty(goFld.FldSourceName) && goFld.FldSourceName != goFld.FldName) {
						sGroupFldSrcs += goFld.FldSourceName;
						if (goFld.FldAlias) sGroupFldSrcs += " AS " + goFld.FldName;
					} else {
						sGroupFldSrcs += ew_QuotedName(goFld.FldName);
					}
					
					// GroupByFields
					if (ew_IsNotEmpty(sGroupFlds)) sGroupFlds += ",";
					sGroupFlds += ew_FieldSqlName(goFld);
					if (ew_IsNotEmpty(goFld.FldOrder)) sGroupFlds += " " + goFld.FldOrder;
										
				}
				
				// Order By					
				if (goFld.FldOrderBy == i) { // Matched Order By Sequence
								
					// OrderByFieldCount
					nOrders += 1; // Increment no. of orders
					if (nOrders != i) goFld.FldOrderBy = nOrders; // Adjust Order By Sequence
					
					// OrderByFieldSources
					if (ew_IsNotEmpty(sDefaultOrderBy)) sDefaultOrderBy += ",";
					if (bCustomView && ew_IsNotEmpty(goFld.FldSourceName) && goFld.FldSourceName != goFld.FldName)
						sDefaultOrderBy += goFld.FldSourceName;
					else
						sDefaultOrderBy += ew_QuotedName(goFld.FldName);
					sDefaultOrderBy += " " + goFld.FldOrder;
					
				}			
				
			} // Next j
		} // Next i

		if (SRCTABLE.TblType == "CUSTOMVIEW") {
			sLimitPart = ew_SQLPart(SRCTABLE.TblCustomSQL, "LIMIT").trim();
			sGroupByPart = ew_SQLPart(TABLE.TblCustomSQL, "GROUP BY").trim();
			sHavingPart = ew_SQLPart(TABLE.TblCustomSQL, "HAVING").trim();
			if (sLimitPart == "" && !(ew_IsNotEmpty(sGroupByPart) && ew_IsNotEmpty(sHavingPart))) { // Allow GROUP BY without HAVING
				sSelectPart = ew_SQLPart(SRCTABLE.TblCustomSQL, "SELECT");
				sFromPart =  ew_SQLPart(SRCTABLE.TblCustomSQL, "FROM");
				sWherePart = "\"" + ew_Quote2(ew_SQLPart(SRCTABLE.TblCustomSQL, "WHERE")) + "\"";
				sGroupByPart = ew_SQLPart(SRCTABLE.TblCustomSQL, "GROUP BY");
				sHavingPart = ew_SQLPart(SRCTABLE.TblCustomSQL, "HAVING");
				sOrderByPart = ew_SQLPart(SRCTABLE.TblCustomSQL, "ORDER BY");
				if (ew_IsNotEmpty(sOrderByPart)) {
					if (ew_IsNotEmpty(sDefaultOrderBy)) sDefaultOrderBy += ", ";
					sDefaultOrderBy += sOrderByPart;
				}
			} else {
				sSelectPart = "*";
				sFromPart = "(" + SRCTABLE.TblCustomSQL + ")";
				sWherePart = "";
				sGroupByPart = "";
				sHavingPart = "";
			}
		} else {
			sSelectPart = bTblDBOracle ? SqlTableName(TABLE) + ".*" : "*";
			sFromPart = SqlTableName(SRCTABLE);
			sWherePart = SRCTABLE.TblFilter.trim();
			sGroupByPart = "";
			sHavingPart = "";
			sLimitPart = "";
		}
		if (sWherePart == "") sWherePart = "\"\""; // Empty String

	// Table Level SQL
	} else {

		for (var i = 1; i <= nFlds; i++) {
			for (var j = 0; j < nFlds; j++) {
				goFld = goFlds[arAllFlds[j]];

				// Order By					
				if (goFld.FldOrderBy == i) { // Matched Order By Sequence

					// OrderByFieldCount
					nOrders += 1; // Increment no. of orders
					if (nOrders != i) goFld.FldOrderBy = nOrders; // Adjust Order By Sequence

					// OrderByFieldNames
					if (ew_IsNotEmpty(sOrderFlds)) sOrderFlds += ",";
					sOrderFlds += goFld.FldName;

					// OrderByFieldSources
					if (ew_IsNotEmpty(sDefaultOrderBy)) sDefaultOrderBy += ",";
					if (bCustomView && ew_IsNotEmpty(goFld.FldSourceName) && goFld.FldSourceName != goFld.FldName)
						sDefaultOrderBy += goFld.FldSourceName;
					else
						sDefaultOrderBy += ew_QuotedName(goFld.FldName);
					sDefaultOrderBy += " " + goFld.FldOrder;
					
				}

			} // Next j
		} // Next i

		if (TABLE.TblType == "CUSTOMVIEW") {
			sLimitPart = ew_SQLPart(TABLE.TblCustomSQL, "LIMIT").trim();
			sGroupByPart = ew_SQLPart(TABLE.TblCustomSQL, "GROUP BY").trim();
			sHavingPart = ew_SQLPart(TABLE.TblCustomSQL, "HAVING").trim();
			if (sLimitPart == "" && !(ew_IsNotEmpty(sGroupByPart) && ew_IsNotEmpty(sHavingPart))) { // Allow GROUP BY without HAVING
				sSelectPart = ew_SQLPart(TABLE.TblCustomSQL, "SELECT");
				sFromPart =  ew_SQLPart(TABLE.TblCustomSQL, "FROM");
				sWherePart = "\"" + ew_Quote2(ew_SQLPart(TABLE.TblCustomSQL, "WHERE")) + "\"";
				sGroupByPart = ew_SQLPart(TABLE.TblCustomSQL, "GROUP BY");
				sHavingPart = ew_SQLPart(TABLE.TblCustomSQL, "HAVING");
				sOrderByPart = ew_SQLPart(TABLE.TblCustomSQL, "ORDER BY");
				if (ew_IsNotEmpty(sOrderByPart)) {
					if (ew_IsNotEmpty(sDefaultOrderBy)) sDefaultOrderBy += ", ";
					sDefaultOrderBy += sOrderByPart;
				}
			} else {
				sSelectPart = "*";
				sFromPart = "(" + TABLE.TblCustomSQL + ") " + ew_QuotedName("EW_CV_" + TABLE.TblVar);
				sWherePart = "";
				sGroupByPart = "";
				sHavingPart = "";
			}
		//} else if (TABLE.TblType == "VIEW") {
		//	sSelectPart = "*";
		//	if (ew_IsNotEmpty(TABLE.TblSQL)) {
		//		sFromPart = ew_SQLPart(TABLE.TblSQL, "FROM");
		//		if (/\s/.test(sFromPart)) // Safe parsing, contains space => not single table
		//			sFromPart = SqlTableName(TABLE);
		//	} else {
		//		sFromPart = SqlTableName(TABLE);
		//	}
		//	sWherePart = "";
		//	sGroupByPart = "";
		//	sHavingPart = "";
		//	sLimitPart = "";
		} else {
			sSelectPart = bTblDBOracle ? SqlTableName(TABLE) + ".*" : "*";
			sFromPart = SqlTableName(TABLE);
			sWherePart = "";
			sGroupByPart = "";
			sHavingPart = "";
			sLimitPart = "";
		}
		if (sWherePart == "") sWherePart = "\"\""; // Empty String

	}

	// Set up custom field SQL
	var sSelectCustom = "";
	for (var i = 0; i < nFlds; i++) {
		goFld = goFlds[arAllFlds[i]];
		if (IsCustomFld(goFld)) {
			if (sSelectCustom != "") sSelectCustom += ", ";
			sSelectCustom += (goFld.FldSQL == "") ? "''" : ew_Quote(goFld.FldSQL);
			sSelectCustom += " AS " + ew_QuotedName(goFld.FldName);
		}
	}
	if (sSelectCustom != "") sSelectPart += ", " + sSelectCustom;

	sKeyFilter = "";
	for (var i = 0, len = arKeyFlds.length; i < len; i++) {
		if (GetFldObj(arKeyFlds[i])) {
			if (ew_IsNotEmpty(sKeyFilter)) sKeyFilter += " AND ";
			sKeyFilter += ew_Quote2(gsFld) + " = " + gsFldQuoteS + "@" + gsFldParm + "@" + gsFldQuoteE; // Quote by @...@
		}
	} // KeyField

	lPageBreakRecordCount = 0; // PDF page break count
	sPageOrientation = "portrait"; // PDF page orientation
	sPageSize = "a4"; // PDF page size

	sExcelPageOrientation = "\"\""; // PHPExcel page orientation
	sExcelPageSize = "\"\""; // PHPExcel page size

	lGridAddRowCount = PROJ.GetV("GridAddRowCount");
	if (lGridAddRowCount <= 0) lGridAddRowCount = 5;
##-->
<!--##/session##-->

<!--##session infoclass##-->
<?php
// Global variable for table object
$<!--##=gsTblVar##--> = NULL;

//
// Table class for <!--##=sTblName##-->
//
<!--## if (TABLE.TblType == "REPORT") { ##-->
class c<!--##=gsTblVar##--> extends cTableBase {
<!--## } else { ##-->
class c<!--##=gsTblVar##--> extends cTable {
<!--## } ##-->

<!--##
	for (var i = 0, len = arAllFlds.length; i < len; i++) {
		if (GetFldObj(arAllFlds[i])) {
##-->
	var $<!--##=gsFldParm##-->;
<!--##
		}
	} // AllField

	var sUpdateTable = sFromPart;
	if (TABLE.TblType == "VIEW" && ew_IsNotEmpty(TABLE.TblSQL)) {
		sUpdateTable = ew_SQLPart(TABLE.TblSQL, "FROM");
		if (/\s/.test(sUpdateTable) || /\sAS\s/i.test(ew_SQLPart(TABLE.TblSQL, "SELECT"))) // Safe parsing, FROM contains space => not single table, SELECT contains " AS " => alias
			sUpdateTable = sFromPart;
	}
##-->

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;
		
		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		$this->TableVar = '<!--##=gsTblVar##-->';
		$this->TableName = '<!--##=SQuote(sTblName)##-->';
		$this->TableType = '<!--##=TABLE.TblType##-->';

		// Update Table
		$this->UpdateTable = "<!--##=ew_Quote2(sUpdateTable)##-->";

	<!--## if (TABLE.TblType == "LINKTABLE") { ##-->
		$this->DBID = '<!--##=SQuote(TABLE.LinkDBID)##-->';
	<!--## } else { ##-->
		$this->DBID = 'DB';
	<!--## } ##-->

		$this->ExportAll = <!--##=ew_Val(bExportAll)##-->;
		$this->ExportPageBreakCount = <!--##=lPageBreakRecordCount##-->; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "<!--##=sPageOrientation##-->"; // Page orientation (PDF only)
		$this->ExportPageSize = "<!--##=sPageSize##-->"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = <!--##=sExcelPageOrientation##-->; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = <!--##=sExcelPageSize##-->; // Page size (PHPExcel only)

	<!--## if (TABLE.TblType != "REPORT") { ##-->
		$this->DetailAdd = <!--##=ew_Val(TABLE.TblDetailAdd)##-->; // Allow detail add
		$this->DetailEdit = <!--##=ew_Val(TABLE.TblDetailEdit)##-->; // Allow detail edit
		$this->DetailView = <!--##=ew_Val(TABLE.TblDetailView)##-->; // Allow detail view
		$this->ShowMultipleDetails = <!--##=ew_Val(TABLE.TblShowMultipleDetails && nDetailTableCount > 1)##-->; // Show multiple details
		$this->GridAddRowCount = <!--##=lGridAddRowCount##-->;
	<!--## } ##-->

	<!--## if (TABLE.TblType != "REPORT") { ##-->
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
	<!--## } ##-->

		$this->UserIDAllowSecurity = <!--##=TABLE.TblUserIDAllow##-->; // User ID Allow

	<!--## if (TABLE.TblType != "REPORT") { ##-->

		$this->BasicSearch = new cBasicSearch($this->TableVar);

<!--## if (ew_IsNotEmpty(TABLE.TblBasicSearchDefault)) { ##-->
		$this->BasicSearch->KeywordDefault = <!--##=TABLE.TblBasicSearchDefault##-->;
<!--## } ##-->

<!--## if (ew_IsNotEmpty(TABLE.TblBasicSearchTypeDefault)) { ##-->
		$this->BasicSearch->TypeDefault = "<!--##=ew_Quote(TABLE.TblBasicSearchTypeDefault)##-->";
<!--## } ##-->

	<!--## } ##-->

<!--##
	for (var i = 0, len = arAllFlds.length; i < len; i++) {
		if (GetFldObj(arAllFlds[i])) {
			lFldType = goFld.FldType;
			sFldExpression = ew_FieldSqlName(goFld);
			sFldBasicSearchExpression = ew_CastFieldForLike(TABLE, sFldExpression, lFldType);
			if (goFld.FldFmtType == "Date/Time")
				lFldDateTimeFormat = goFld.FldDtFormat;
			else
				lFldDateTimeFormat = "-1";
			bFldUpload = ew_Val(goFld.FldHtmlTag == "FILE");
			if (ew_IsFldVirtualLookup(goFld)) {
				sFldVirtualExpression = ew_QuotedName(ew_VirtualLookupFldName(goFld));
				FldIsVirtual = "TRUE";
				FldForceSelect = ew_Val(ew_IsFldForceSelect(goFld));
				FldVirtualSearch = ew_Val(!(goFld.FldHtmlTag == "CHECKBOX" || goFld.FldHtmlTag == "SELECT" && goFld.FldSelectMultiple) && goFld.FldVirtualLookupSearch || goFld.FldHtmlTag == "TEXT");
			} else {
				//sFldVirtualExpression = ew_QuotedName(gsFldName)
				sFldVirtualExpression = sFldExpression;
				FldIsVirtual = "FALSE";
				FldForceSelect = "FALSE";
				FldVirtualSearch = "FALSE";
			}
			sFldViewTag = goFld.FldViewTag;
			sFldHtmlTag = goFld.FldHtmlTag;
			sFldSrchOpr = goFld.FldSrchOpr;
			sFldSrchOpr2 = goFld.FldSrchOpr2;
			sFldSrchCond = "";
			if (sFldSrchOpr == "USER SELECT") sFldSrchOpr = "=";
			if (sFldSrchOpr2 == "USER SELECT") sFldSrchOpr2 = "=";
			if (sFldSrchOpr == "BETWEEN") {
				sFldSrchCond = "AND";
				sFldSrchOpr2 = "";
			} else if (ew_IsNotEmpty(sFldSrchOpr)) {
				sFldSrchCond = "AND";
			}
##-->
		// <!--##=gsFldName##-->
		$this-><!--##=gsFldParm##--> = new cField('<!--##=gsTblVar##-->', '<!--##=SQuote(gsTblName)##-->', '<!--##=gsFldVar##-->', '<!--##=SQuote(gsFldName)##-->', '<!--##=SQuote(sFldExpression)##-->', '<!--##=SQuote(sFldBasicSearchExpression)##-->', <!--##=lFldType##-->, <!--##=lFldDateTimeFormat##-->, <!--##=bFldUpload##-->, '<!--##=SQuote(sFldVirtualExpression)##-->', <!--##=FldIsVirtual##-->, <!--##=FldForceSelect##-->, <!--##=FldVirtualSearch##-->, '<!--##=SQuote(sFldViewTag)##-->', '<!--##=SQuote(sFldHtmlTag)##-->');
<!--## if (IsCustomFld(goFld)) { // Custom field ##-->
		$this-><!--##=gsFldParm##-->->FldIsCustom = TRUE; // Custom field
<!--## } ##-->
<!--## if (SYSTEMFUNCTIONS.IsBoolFld()) { ##-->
		$this-><!--##=gsFldParm##-->->FldDataType = EW_DATATYPE_BOOLEAN;
	<!--## if (ewTrue == "Y") { ##-->
		$this-><!--##=gsFldParm##-->->TrueValue = 'Y';
		$this-><!--##=gsFldParm##-->->FalseValue = 'N';
	<!--## } else if (ewTrue == "y") { ##-->
		$this-><!--##=gsFldParm##-->->TrueValue = 'y';
		$this-><!--##=gsFldParm##-->->FalseValue = 'n';
	<!--## } ##-->
<!--## } ##-->
<!--## if (goFld.FldIsLookup && goFld.FldSelectType != "Table") { ##-->
		$this-><!--##=gsFldParm##-->->OptionCount = <!--##=ew_FldTagValues(goFld).rtrim().split("\r\n").length##-->;
<!--## } ##-->
<!--##
		if (bTblDBOracle) {
			sFldTypeName = goFld.FldTypeName.toUpperCase();
			if (sFldTypeName == "BLOB" || sFldTypeName == "CLOB") {
##-->
		$this-><!--##=gsFldParm##-->->FldBlobType = "<!--##=sFldTypeName##-->";
<!--##
			}
		}
##-->
<!--## if (goFld.FldViewThumbnail) { ##-->
		$this-><!--##=gsFldParm##-->->ImageResize = TRUE;
<!--## } ##-->
<!--## if (ew_IsNotEmpty(goFld.UploadAllowedFileExt)) { ##-->
		$this-><!--##=gsFldParm##-->->UploadAllowedFileExt = "<!--##=goFld.UploadAllowedFileExt##-->";
<!--## } ##-->
<!--## if (ew_IsNotEmpty(goFld.UploadMaxFileSize) && isNumber(goFld.UploadMaxFileSize) && goFld.UploadMaxFileSize > 0) { ##-->
		$this-><!--##=gsFldParm##-->->UploadMaxFileSize = <!--##=goFld.UploadMaxFileSize##-->;
<!--## } ##-->
<!--## if (goFld.FldUploadMultiple && !ew_IsBinaryField(goFld)) { ##-->
		$this-><!--##=gsFldParm##-->->UploadMultiple = TRUE;
		$this-><!--##=gsFldParm##-->->Upload->UploadMultiple = TRUE;
	<!--## if (ew_IsNotEmpty(goFld.UploadMaxFileCount)) { ##-->
		$this-><!--##=gsFldParm##-->->UploadMaxFileCount = <!--##=goFld.UploadMaxFileCount##-->;
	<!--## } ##-->
<!--## } ##-->
<!--## if (goFld.FldUseDHtmlEditor) { ##-->
		$this-><!--##=gsFldParm##-->->TruncateMemoRemoveHtml = <!--##=ew_Val(goFld.FldUseDHtmlEditor)##-->;
<!--## } ##-->
<!--## if (ew_IsNotEmpty(goFld.FldValidate)) { ##-->
		$this-><!--##=gsFldParm##-->->FldDefaultErrMsg = <!--##=SYSTEMFUNCTIONS.PhpDefaultMsg()##-->;
<!--## } ##-->
<!--## if (ew_IsNotEmpty(goFld.FldSearchDefault)) { ##-->
		$this-><!--##=gsFldParm##-->->AdvancedSearch->SearchValueDefault = <!--##=goFld.FldSearchDefault##-->;
<!--## } ##-->
<!--## if (ew_IsNotEmpty(goFld.FldSearchDefault2)) { ##-->
		$this-><!--##=gsFldParm##-->->AdvancedSearch->SearchValue2Default = <!--##=goFld.FldSearchDefault2##-->;
<!--## } ##-->
<!--## if (ew_IsNotEmpty(goFld.FldSearchDefault) || ew_IsNotEmpty(goFld.FldSearchDefault2)) { ##-->
		$this-><!--##=gsFldParm##-->->AdvancedSearch->SearchOperatorDefault = "<!--##=sFldSrchOpr##-->";
		$this-><!--##=gsFldParm##-->->AdvancedSearch->SearchOperatorDefault2 = "<!--##=sFldSrchOpr2##-->";
		$this-><!--##=gsFldParm##-->->AdvancedSearch->SearchConditionDefault = "<!--##=sFldSrchCond##-->";
<!--## } ##-->
		$this->fields['<!--##=SQuote(gsFldName)##-->'] = &$this-><!--##=gsFldParm##-->;
<!--##
		}
	} // AllField
##-->
	}

<!--##
	if (TABLE.TblType != "REPORT") {
##-->

	<!--##
		if (iSortType == 1) {
	##-->
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
<!--## if (bTableVirtualLookup) { ##-->
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
<!--## } ##-->
		} else {
			$ofld->setSort("");
		}
	}
	<!--##
		} else if (iSortType == 2) {
	##-->
	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
<!--## if (bTableVirtualLookup) { ##-->
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			if ($ctrl) {
				$sOrderByList = $this->getSessionOrderByList();
				if (strpos($sOrderByList, $sSortFieldList . " " . $sLastSort) !== FALSE) {
					$sOrderByList = str_replace($sSortFieldList . " " . $sLastSort, $sSortFieldList . " " . $sThisSort, $sOrderByList);
				} else {
					if ($sOrderByList <> "") $sOrderByList .= ", ";
					$sOrderByList .= $sSortFieldList . " " . $sThisSort;
				}
				$this->setSessionOrderByList($sOrderByList); // Save to Session
			} else {
				$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
			}
<!--## } ##-->
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}
	<!--##
		}
	##-->

<!--## if (bTableVirtualLookup) { ##-->
	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}
	
	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}
<!--## } ##-->

<!--##
	}
##-->

<!--##
	if (nMasterTableCount > 0) {
##-->
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
	<!--##
		for (var i = 0, len = arMasterTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arMasterTables[i].index];
			MASTERTABLE = DB.Tables(MasterDetail.MasterTable);
			if (MASTERTABLE.TblGen) {
				sMasterTblVar = MASTERTABLE.TblVar;
	##-->
		if ($this->getCurrentMasterTable() == "<!--##=sMasterTblVar##-->") {
	<!--##
				for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
					FIELD = MASTERTABLE.Fields(MasterDetail.Rels[j].MasterField);
					sMasterFld = ew_FieldSqlNameBase(FIELD, MASTERTABLE);
					sMasterFldTypeName = GetFieldTypeName(FIELD.FldType);
					FIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
					sDetailFldParm = FIELD.FldParm;
					if (j >= 1)
						sCond = " AND ";
					else
						sCond = "";
	##-->
			if ($this-><!--##=sDetailFldParm##-->->getSessionValue() <> "")
				$sMasterFilter .= "<!--##=sCond##--><!--##=ew_Quote2(sMasterFld)##-->=" . ew_QuotedValue($this-><!--##=sDetailFldParm##-->->getSessionValue(), <!--##=sMasterFldTypeName##-->, "<!--##=ew_Quote(gsDbId)##-->");
			else
				return "";
	<!--##
				} // MasterDetailField
	##-->
		}
	<!--##
			}
		} // MasterDetail
		ew_SetDb(TABLE.TblName); // Reset current db
	##-->
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {
		// Detail filter
		$sDetailFilter = "";
	<!--##
		for (var i = 0, len = arMasterTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arMasterTables[i].index];
			MASTERTABLE = DB.Tables(MasterDetail.MasterTable);
			if (MASTERTABLE.TblGen) {
				sMasterTblVar = MASTERTABLE.TblVar;
	##-->
		if ($this->getCurrentMasterTable() == "<!--##=sMasterTblVar##-->") {
	<!--##
				for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
					FIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
					sDetailFld = ew_FieldSqlName(FIELD);
					sDetailFldTypeName = GetFieldTypeName(FIELD.FldType);
					sDetailFldParm = FIELD.FldParm;
					if (j >= 1)
						sCond = " AND ";
					else
						sCond = "";
	##-->
			if ($this-><!--##=sDetailFldParm##-->->getSessionValue() <> "")
				$sDetailFilter .= "<!--##=sCond##--><!--##=ew_Quote2(sDetailFld)##-->=" . ew_QuotedValue($this-><!--##=sDetailFldParm##-->->getSessionValue(), <!--##=sDetailFldTypeName##-->, "<!--##=ew_Quote(gsDbId)##-->");
			else
				return "";
	<!--##
				} // MasterDetailField
	##-->
		}
	<!--##
			}
		} // MasterDetail
	##-->
		return $sDetailFilter;
	}

	<!--##
		for (var i = 0, len = arMasterTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arMasterTables[i].index];
			MASTERTABLE = DB.Tables(MasterDetail.MasterTable);
			if (MASTERTABLE.TblGen) {
				sMasterTblVar = MASTERTABLE.TblVar;
				sMasterFilter = "";
				sDetailFilter = "";
				for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
					FIELD = MASTERTABLE.Fields(MasterDetail.Rels[j].MasterField);
					sMasterFld = ew_FieldSqlName(FIELD);
					sMasterFldParm = FIELD.FldParm;
					sMasterFldQuoteS = FIELD.FldQuoteS;
					sMasterFldQuoteE = FIELD.FldQuoteE;
					if (ew_IsNotEmpty(sMasterFilter)) sMasterFilter += " AND ";
					sMasterFilter += sMasterFld + "=" + sMasterFldQuoteS + "@" + sMasterFldParm + "@" + sMasterFldQuoteE;
					FIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
					sDetailFld = ew_FieldSqlName(FIELD);
					sDetailFldParm = FIELD.FldParm;
					sDetailFldQuoteS = FIELD.FldQuoteS;
					sDetailFldQuoteE = FIELD.FldQuoteE;
					if (ew_IsNotEmpty(sDetailFilter)) sDetailFilter += " AND ";
					sDetailFilter += sDetailFld + "=" + sDetailFldQuoteS + "@" + sDetailFldParm + "@" + sDetailFldQuoteE;
				} // MasterDetailField
	##-->
	// Master filter
	function SqlMasterFilter_<!--##=sMasterTblVar##-->() {
		return "<!--##=ew_Quote2(sMasterFilter)##-->";
	}
	// Detail filter
	function SqlDetailFilter_<!--##=sMasterTblVar##-->() {
		return "<!--##=ew_Quote2(sDetailFilter)##-->";
	}
	<!--##
			}
		} // MasterDetail
	##-->

<!--##
	}
##-->

<!--##
	if (nDetailTableCount > 0) {
##-->
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
	<!--##
		for (var i = 0, len = arDetailTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arDetailTables[i].index];
			DETAILTABLE = DB.Tables(MasterDetail.DetailTable);
			if (DETAILTABLE.TblGen && DETAILTABLE.TblType != "REPORT") {
				sDetailTblVar = DETAILTABLE.TblVar;
	##-->
		if ($this->getCurrentDetailTable() == "<!--##=sDetailTblVar##-->") {
			$sDetailUrl = $GLOBALS["<!--##=sDetailTblVar##-->"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
	<!--##
				for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
					MASTERFIELD = TABLE.Fields(MasterDetail.Rels[j].MasterField);
					sMasterFldParm = MASTERFIELD.FldParm;
					DETAILFIELD = DETAILTABLE.Fields(MasterDetail.Rels[j].DetailField);
					sDetailFldParm = DETAILFIELD.FldParm;
	##-->
			$sDetailUrl .= "&fk_<!--##=ew_Quote(sMasterFldParm)##-->=" . urlencode($this-><!--##=sMasterFldParm##-->->CurrentValue);
	<!--##
				} // MasterDetailField
	##-->
		}
	<!--##
			}
		} // MasterDetail
	##-->
		if ($sDetailUrl == "") {
			$sDetailUrl = "<!--##=sFnList##-->";
		}
		return $sDetailUrl;
	}
<!--##
	}
##-->

<!--##
	// Report SQL
	if (TABLE.TblType == "REPORT") {

		if (nGroups > 0) {

			// Report group level SQL: SELECT DISTINCT [Group-By FIELDS] FROM [TABLE/VIEW] ORDER BY [Group-By FIELDS]
			sGrpSelectPart = sGroupFldSrcs; // Use Group Fields
			sGrpOrderByPart = sGroupFlds;
##-->

	// Report group level SQL
	var $_SqlGroupSelect = "";
	function getSqlGroupSelect() { // Select
		return ($this->_SqlGroupSelect <> "") ? $this->_SqlGroupSelect : "SELECT DISTINCT <!--##=ew_Quote2(sGrpSelectPart)##--> FROM <!--##=ew_Quote2(sFromPart)##-->";
	}
	function SqlGroupSelect() { // For backward compatibility
    	return $this->getSqlGroupSelect();
	}
	function setSqlGroupSelect($v) {
    	$this->_SqlGroupSelect = $v;
	}
	
	var $_SqlGroupWhere = "";
	function getSqlGroupWhere() { // Where
		return ($this->_SqlGroupWhere <> "") ? $this->_SqlGroupWhere : <!--##=sWherePart##-->;
	}
	function SqlGroupWhere() { // For backward compatibility
    	return $this->getSqlGroupWhere();
	}
	function setSqlGroupWhere($v) {
    	$this->_SqlGroupWhere = $v;
	}
	
	var $_SqlGroupGroupBy = "";
	function getSqlGroupGroupBy() { // Group By
		return ($this->_SqlGroupGroupBy <> "") ? $this->_SqlGroupGroupBy : "<!--##=ew_Quote2(sGroupByPart)##-->";
	}
	function SqlGroupGroupBy() { // For backward compatibility
    	return $this->getSqlGroupGroupBy();
	}
	function setSqlGroupGroupBy($v) {
    	$this->_SqlGroupGroupBy = $v;
	}
	
	var $_SqlGroupHaving = "";
	function getSqlGroupHaving() { // Having
		return ($this->_SqlGroupHaving <> "") ? $this->_SqlGroupHaving : "<!--##=ew_Quote2(sHavingPart)##-->";
	}
	function SqlGroupHaving() { // For backward compatibility
    	return $this->getSqlGroupHaving();
	}
	function setSqlGroupHaving($v) {
    	$this->_SqlGroupHaving = $v;
	}
	
	var $_SqlGroupOrderBy = "";
	function getSqlGroupOrderBy() { // Order By
		return ($this->_SqlGroupOrderBy <> "") ? $this->_SqlGroupOrderBy : "<!--##=ew_Quote2(sGrpOrderByPart)##-->";
	}
	function SqlGroupOrderBy() { // For backward compatibility
    	return $this->getSqlGroupOrderBy();
	}
	function setSqlGroupOrderBy($v) {
    	$this->_SqlGroupOrderBy = $v;
	}

<!--##
		}

		// Report Detail Level SQL: SELECT * FROM [TABLE/VIEW] WHERE [Group-By FIELDS] = ... ORDER BY [Order-By FIELDS]
		sDtlOrderByPart = sDefaultOrderBy;
##-->

	// Report detail level SQL
	var $_SqlDetailSelect = "";
	function getSqlDetailSelect() { // Select
		return ($this->_SqlDetailSelect <> "") ? $this->_SqlDetailSelect : "SELECT <!--##=ew_Quote2(sSelectPart)##--> FROM <!--##=ew_Quote2(sFromPart)##-->";
	}
	function SqlDetailSelect() { // For backward compatibility
    	return $this->getSqlDetailSelect();
	}
	function setSqlDetailSelect($v) {
    	$this->_SqlDetailSelect = $v;
	}
	
	var $_SqlDetailWhere = "";
	function getSqlDetailWhere() { // Where
		return ($this->_SqlDetailWhere <> "") ? $this->_SqlDetailWhere : <!--##=sWherePart##-->;
	}
	function SqlDetailWhere() { // For backward compatibility
    	return $this->getSqlDetailWhere();
	}
	function setSqlDetailWhere($v) {
    	$this->_SqlDetailWhere = $v;
	}
	
	var $_SqlDetailGroupBy = "";
	function getSqlDetailGroupBy() { // Group By
		return ($this->_SqlDetailGroupBy <> "") ? $this->_SqlDetailGroupBy : "<!--##=ew_Quote2(sGroupByPart)##-->";
	}
	function SqlDetailGroupBy() { // For backward compatibility
    	return $this->getSqlDetailGroupBy();
	}
	function setSqlDetailGroupBy($v) {
    	$this->_SqlDetailGroupBy = $v;
	}
	
	var $_SqlDetailHaving = "";
	function getSqlDetailHaving() { // Having
		return ($this->_SqlDetailHaving <> "") ? $this->_SqlDetailHaving : "<!--##=ew_Quote2(sHavingPart)##-->";
	}
	function SqlDetailHaving() { // For backward compatibility
    	return $this->getSqlDetailHaving();
	}
	function setSqlDetailHaving($v) {
    	$this->_SqlDetailHaving = $v;
	}
	
	var $_SqlDetailOrderBy = "";
	function getSqlDetailOrderBy() { // Order By
		return ($this->_SqlDetailOrderBy <> "") ? $this->_SqlDetailOrderBy : "<!--##=ew_Quote2(sDtlOrderByPart)##-->";
	}
	function SqlDetailOrderBy() { // For backward compatibility
    	return $this->getSqlDetailOrderBy();
	}
	function setSqlDetailOrderBy($v) {
    	$this->_SqlDetailOrderBy = $v;
	}

<!--##
	// Table/View SQL
	} else {
##-->

	// Table level SQL
	var $_SqlFrom = "";
	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "<!--##=ew_Quote2(sFromPart)##-->";
	}
	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}
	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	
	var $_SqlSelect = "";
	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT <!--##=ew_Quote2(sSelectPart)##--> FROM " . $this->getSqlFrom();
	}
	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}
	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	
<!--## if (bTableVirtualLookup) { ##-->
	var $_SqlSelectList = "";
	function getSqlSelectList() { // Select for List page
		$select = "";
<!--## if (ew_LanguageCount > 1) { ##-->
		global $gsLanguage;
		switch ($gsLanguage) {
	<!--##
		for (var i = 1; i < ew_LanguageCount; i++) {
	##-->
		case "<!--##=ew_Language[i]##-->":
			$select = "SELECT * FROM (" .
				"SELECT <!--##=ew_Quote2(sSelectPart)##-->, <!--##=arVirtualFieldList[i]##--> FROM <!--##=ew_Quote2(sFromPart)##-->" .
				") <!--##=ew_Quote2(ew_QuotedName("EW_TMP_TABLE"))##-->";
			break;
<!--##
		}
##-->
		default:
			$select = "SELECT * FROM (" .
				"SELECT <!--##=ew_Quote2(sSelectPart)##-->, <!--##=arVirtualFieldList[0]##--> FROM <!--##=ew_Quote2(sFromPart)##-->" .
				") <!--##=ew_Quote2(ew_QuotedName("EW_TMP_TABLE"))##-->";
			break;
		}
<!--## } else { ##-->
		$select = "SELECT * FROM (" .
			"SELECT <!--##=ew_Quote2(sSelectPart)##-->, <!--##=arVirtualFieldList[0]##--> FROM <!--##=ew_Quote2(sFromPart)##-->" .
			") <!--##=ew_Quote2(ew_QuotedName("EW_TMP_TABLE"))##-->";
<!--## } ##-->
		return ($this->_SqlSelectList <> "") ? $this->_SqlSelectList : $select;
	}
	function SqlSelectList() { // For backward compatibility
    	return $this->getSqlSelectList();
	}
	function setSqlSelectList($v) {
    	$this->_SqlSelectList = $v;
	}
<!--## } ##-->

	var $_SqlWhere = "";
	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : <!--##=sWherePart##-->;
<!--##
	sTblDefaultFilter = TABLE.TblFilter.trim();
	if (sTblDefaultFilter == "") sTblDefaultFilter = "\"\"";
##-->
		$this->TableFilter = <!--##=sTblDefaultFilter##-->;
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
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "<!--##=ew_Quote2(sGroupByPart)##-->";
	}
	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}
	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	
	var $_SqlHaving = "";
	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "<!--##=ew_Quote2(sHavingPart)##-->";
	}
	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}
	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	
	var $_SqlOrderBy = "";
	function getSqlOrderBy() { // Order By
	<!--## if (bDynamicUserLevel && TABLE.TblName == DB.UserLevelTbl && sDefaultOrderBy == "") { ##-->
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : EW_USER_LEVEL_ID_FIELD;
	<!--## } else { ##-->
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "<!--##=ew_Quote2(sDefaultOrderBy)##-->";
	<!--## } ##-->
	}
	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}
	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

<!--## } ##-->

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		<!--## if (bTableHasUserIDFld || bMasterTableHasUserIDFld) { ##-->
		global $Security;
		// Add User ID filter
		if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
		<!--##
			if (bTableHasUserIDFld) {
		##-->
			$sFilter = $this->AddUserIDFilter($sFilter);
		<!--##
			} else if (bMasterTableHasUserIDFld) {
				for (var i = 0, len = arMasterTables.length; i < len; i++) {
		##-->
			if ($this->getCurrentMasterTable() == "<!--##=arMasterTables[i].TblVar##-->" || $this->getCurrentMasterTable() == "")
				$sFilter = $this->AddDetailUserIDFilter($sFilter, "<!--##=arMasterTables[i].TblVar##-->"); // Add detail User ID filter
		<!--##
				} // MasterTable
			}
		##-->
		}
		<!--## } ##-->
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {

	<!--## if (bTableHasUserIDFld) { ##-->
		$allow = $this->UserIDAllowSecurity;
	<!--## } else { ##-->
		$allow = EW_USER_ID_ALLOW;
	<!--## } ##-->

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
		<!--## if (PROJ.GetV("NoUserLevelCompat")) { // ##-->
				return (($allow & 32) == 32);
		<!--## } else { ##-->
				return (($allow & 8) == 8);
		<!--## } ##-->
			case "search":
		<!--## if (PROJ.GetV("NoUserLevelCompat")) { // ##-->
				return (($allow & 64) == 64);
		<!--## } else { ##-->
				return (($allow & 8) == 8);
		<!--## } ##-->
			default:
				return (($allow & 8) == 8);
		}

	}

<!--##
	// Report SQL
	if (TABLE.TblType == "REPORT") {

		if (nGroups > 0) {
##-->
	// Report group SQL
	function GroupSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->getSqlGroupSelect(), $this->getSqlGroupWhere(),
			 $this->getSqlGroupGroupBy(), $this->getSqlGroupHaving(),
			 $this->getSqlGroupOrderBy(), $sFilter, $sSort);
	}
<!--##
		}
##-->
	// Report detail SQL
	function DetailSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->getSqlDetailSelect(), $this->getSqlDetailWhere(),
			$this->getSqlDetailGroupBy(), $this->getSqlDetailHaving(),
			$this->getSqlDetailOrderBy(), $sFilter, $sSort);
	}
<!--##
	// Table/View SQL
	} else {
##-->
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
<!--## if (bTableVirtualLookup) { ##-->
		if ($this->UseVirtualFields()) {
			$sSort = $this->getSessionOrderByList();
			return ew_BuildSelectSql($this->getSqlSelectList(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		} else {
			$sSort = $this->getSessionOrderBy();
			return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		}
<!--## } else { ##-->
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
<!--## } ##-->
	}

	// Get ORDER BY clause
	function GetOrderBy() {
<!--## if (bTableVirtualLookup) { ##-->
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
<!--## } else { ##-->
		$sSort = $this->getSessionOrderBy();
<!--## } ##-->
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

<!--## if (bTableVirtualLookup) { ##-->
	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->getSessionWhere();
		$sOrderBy = $this->getSessionOrderByList();
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
<!--##
	bGenBasicSearchChk = true;
	for (var i = 0, len = arAllFlds.length; i < len; i++) {
		if (GetFldObj(arAllFlds[i])) {
			if (ew_IsFldVirtualLookup(goFld)) {
				if (IsFldBasicSearch(goFld) && bGenBasicSearchChk) {
##-->
		if ($this->BasicSearch->getKeyword() <> "")
			return TRUE;
<!--##
					bGenBasicSearchChk = false;
				}
				if (goFld.FldHtmlTag == "TEXT" || goFld.FldVirtualLookupSearch) {
##-->
		if ($<!--##=gsFldObj##-->->AdvancedSearch->SearchValue <> "" ||
			$<!--##=gsFldObj##-->->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $<!--##=gsFldObj##-->->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
<!--##
				}
##-->
		if (strpos($sOrderBy, " " . $<!--##=gsFldObj##-->->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
<!--##
			}
		}
	} // AllField
##-->
		return FALSE;
	}
<!--## } ##-->

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
		<!--## if (bUserTable && TABLE.TblName == PROJ.SecTbl) { ##-->
			if (EW_ENCRYPTED_PASSWORD && $name == '<!--##=SQuote(PROJ.SecPasswdFld)##-->')
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
		<!--## } ##-->
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
		<!--## if (bUserTable && TABLE.TblName == PROJ.SecTbl) { ##-->
			if (EW_ENCRYPTED_PASSWORD && $name == '<!--##=SQuote(PROJ.SecPasswdFld)##-->') {
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			}
		<!--## } ##-->
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
	<!--##
		if (nDetailTableCount > 0) {
			for (var i = 0, len = arDetailTables.length; i < len; i++) {
				var MasterDetail = goAllMasDets[arDetailTables[i].index];
				if (MasterDetail.CascadeUpdate) { // Cascade update
					OLDTABLE = TABLE; // Save master table
					MASTERTABLE = DB.Tables(TABLE.TblName);
					TABLE = DB.Tables(MasterDetail.DetailTable);
					if (TABLE.TblGen && TABLE.TblType != "REPORT") {
	##-->
		// Cascade Update detail table '<!--##=TABLE.TblName##-->'
		$bCascadeUpdate = FALSE;
		$rscascade = array();
	<!--##
						// Get detail key SQL
						var sDetailKeySQL = "";
						ew_SetDb(TABLE.TblName);
						for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
							MASTERFIELD = MASTERTABLE.Fields(MasterDetail.Rels[j].MasterField);
							sMasterFldName = MASTERFIELD.FldName;
							sMasterFldTypeName = GetFieldTypeName(MASTERFIELD.FldType);
							DETAILFIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
							sDetailFld = ew_FieldSqlName(DETAILFIELD);
							if (sDetailKeySQL != "") sDetailKeySQL += " . \" AND \" . ";
							sDetailKeySQL += "\"" + ew_Quote(sDetailFld) + " = \" . ew_QuotedValue($rsold['" + SQuote(sMasterFldName) + "'], " + sMasterFldTypeName + ", '" + SQuote(gsDbId) + "')";
						} // MasterDetailField
						for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
							MASTERFIELD = MASTERTABLE.Fields(MasterDetail.Rels[j].MasterField);
							sMasterFldName = MASTERFIELD.FldName;
							DETAILFIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
							sDetailFldName = DETAILFIELD.FldName;
							var sCheck = "isset($rs['" + SQuote(sMasterFldName) + "']) && $rsold['" + SQuote(sMasterFldName) + "'] <> $rs['" + SQuote(sMasterFldName) + "']";
							var sMasterKeyCheck = sCheck.replace('%f', SQuote(sMasterFldName));
	##-->
		if (!is_null($rsold) && (<!--##=sMasterKeyCheck##-->)) { // Update detail field '<!--##=sDetailFldName##-->'
			$bCascadeUpdate = TRUE;
			$rscascade['<!--##=SQuote(sDetailFldName)##-->'] = $rs['<!--##=SQuote(sMasterFldName)##-->']; 
		}
	<!--##
						} // MasterDetailField
	##-->
		if ($bCascadeUpdate) {
			if (!isset($GLOBALS["<!--##=TABLE.TblVar##-->"])) $GLOBALS["<!--##=TABLE.TblVar##-->"] = new c<!--##=TABLE.TblVar##-->();
			$rswrk = $GLOBALS["<!--##=TABLE.TblVar##-->"]->LoadRs(<!--##=sDetailKeySQL##-->); 
			while ($rswrk && !$rswrk->EOF) {
				$GLOBALS["<!--##=TABLE.TblVar##-->"]->Update($rscascade, <!--##=sDetailKeySQL##-->, $rswrk->fields);
				$rswrk->MoveNext();
			}
		}
	<!--##
					}
					TABLE = OLDTABLE; // Restore master table
				}
			}
		}
	##-->

		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
	<!--##
		for (var i = 0, len = arKeyFlds.length; i < len; i++) {
			if (GetFldObj(arKeyFlds[i])) {
	##-->
			if (array_key_exists('<!--##=SQuote(gsFldName)##-->', $rs))
				ew_AddFilter($where, ew_QuotedName('<!--##=SQuote(gsFldName)##-->', $this->DBID) . '=' . ew_QuotedValue($rs['<!--##=SQuote(gsFldName)##-->'], $this-><!--##=gsFldParm##-->->FldDataType, $this->DBID));
	<!--##
			}
		} // KeyField
	##-->
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
	<!--##
		if (nDetailTableCount > 0) {
			for (var i = 0, len = arDetailTables.length; i < len; i++) {
				var MasterDetail = goAllMasDets[arDetailTables[i].index];
				if (MasterDetail.CascadeDelete) { // Cascade delete
					OLDTABLE = TABLE; // Save master table
					MASTERTABLE = DB.Tables(TABLE.TblName);
					TABLE = DB.Tables(MasterDetail.DetailTable);
					if (TABLE.TblGen && TABLE.TblType != "REPORT") {
						// Get detail key SQL
						var sDetailKeySQL = "";
						ew_SetDb(TABLE.TblName);
						for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
							MASTERFIELD = MASTERTABLE.Fields(MasterDetail.Rels[j].MasterField);
							sMasterFldName = MASTERFIELD.FldName;
							sMasterFldTypeName = GetFieldTypeName(MASTERFIELD.FldType);
							DETAILFIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
							sDetailFld = ew_FieldSqlName(DETAILFIELD);
							if (sDetailKeySQL != "") sDetailKeySQL += " . \" AND \" . ";
							sDetailKeySQL += "\"" + ew_Quote(sDetailFld) + " = \" . ew_QuotedValue($rs['" + SQuote(sMasterFldName) + "'], " + sMasterFldTypeName + ", \"" + ew_Quote(gsDbId) + "\")";
						} // MasterDetailField
	##-->
		// Cascade delete detail table '<!--##=MasterDetail.DetailTable##-->'
		if (!isset($GLOBALS["<!--##=TABLE.TblVar##-->"])) $GLOBALS["<!--##=TABLE.TblVar##-->"] = new c<!--##=TABLE.TblVar##-->();
		$rscascade = $GLOBALS["<!--##=TABLE.TblVar##-->"]->LoadRs(<!--##=sDetailKeySQL##-->); 
		while ($rscascade && !$rscascade->EOF) {
			$GLOBALS["<!--##=TABLE.TblVar##-->"]->Delete($rscascade->fields);
			$rscascade->MoveNext();
		}
	<!--##
					}
					TABLE = OLDTABLE; // Restore master table
				}
			}
		}
	##-->

		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "<!--##=sKeyFilter##-->";
	}
	
	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
	<!--##
		for (var i = 0, len = arKeyFlds.length; i < len; i++) {
			if (GetFldObj(arKeyFlds[i])) {
				if (ew_GetFieldType(goFld.FldType) == 1) { // Numeric
	##-->
		if (!is_numeric($<!--##=gsFldObj##-->->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
	<!--##
				}
				if (ew_GetFieldType(goFld.FldType) == 2) { // Date
					sPrefix = "ew_UnFormatDateTime(";
					sSuffix = "," + goFld.FldDtFormat + ")";
				} else {
					sPrefix = "";
					sSuffix = "";
				}
	##-->
		$sKeyFilter = str_replace("@<!--##=gsFldParm##-->@", ew_AdjustSql(<!--##=sPrefix##-->$<!--##=gsFldObj##-->->CurrentValue<!--##=sSuffix##-->, $this->DBID), $sKeyFilter); // Replace key value
	<!--##
			}
		} // KeyField
	##-->
		return $sKeyFilter;
	}
<!--##
	}
##-->

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;
		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "<!--##=sFnLogin##-->") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "<!--##=sFnList##-->";
		}
	}
	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}
	
	// List URL
	function GetListUrl() {
		return "<!--##=sFnList##-->";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("<!--##=sFnView##-->", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("<!--##=sFnView##-->", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "<!--##=sFnAdd##-->?" . $this->UrlParm($parm);
		else
			$url = "<!--##=sFnAdd##-->";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
	<!--## if (bDetailEdit && nDetailTableCount > 0) { ##-->
		if ($parm <> "")
			$url = $this->KeyUrl("<!--##=sFnEdit##-->", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("<!--##=sFnEdit##-->", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	<!--## } else { ##-->
		$url = $this->KeyUrl("<!--##=sFnEdit##-->", $this->UrlParm($parm));
	<!--## } ##-->
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
	<!--## if (bDetailAdd && nDetailTableCount > 0) { ##-->
		if ($parm <> "")
			$url = $this->KeyUrl("<!--##=sFnAdd##-->", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("<!--##=sFnAdd##-->", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	<!--## } else { ##-->
		$url = $this->KeyUrl("<!--##=sFnAdd##-->", $this->UrlParm($parm));
	<!--## } ##-->
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("<!--##=sFnDelete##-->", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
	<!--##
		for (var i = 0, len = arMasterTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arMasterTables[i].index];
			MASTERTABLE = DB.Tables(MasterDetail.MasterTable);
			if (MASTERTABLE.TblGen) {
				sMasterTblVar = MASTERTABLE.TblVar;
	##-->
		if ($this->getCurrentMasterTable() == "<!--##=sMasterTblVar##-->" && strpos($url, EW_TABLE_SHOW_MASTER . "=") === FALSE) {
			$url .= (strpos($url, "?") !== FALSE ? "&" : "?") . EW_TABLE_SHOW_MASTER . "=" . $this->getCurrentMasterTable();
	<!--##
				for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
					MASTERFIELD = MASTERTABLE.Fields(MasterDetail.Rels[j].MasterField);
					sMasterFldParm = MASTERFIELD.FldParm;
					DETAILFIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
					sDetailFldParm = DETAILFIELD.FldParm;
	##-->
			$url .= "&fk_<!--##=ew_Quote(sMasterFldParm)##-->=" . urlencode($this-><!--##=sDetailFldParm##-->->CurrentValue);
	<!--##
				} // MasterDetailField
	##-->
		}
	<!--##
			}
		} // MasterDetail
	##-->
		return $url;
	}

	function KeyToJson() {
		$json = "";
<!--##
	for (var i = 0, len = arKeyFlds.length; i < len; i++) {
		if (GetFldObj(arKeyFlds[i])) {
			sPrefix = (i == 0) ? "" : ",";
			if (goFld.FldHtmlTag == "FILE" && !ew_IsBinaryField(goFld)) { // Upload to folder
##-->
		$json .= "<!--##=sPrefix##--><!--##=gsFldParm##-->:" . ew_VarToJson($this-><!--##=gsFldParm##-->->Upload->DbValue, "<!--##=GetFieldJsonTypeName(goFld.FldType)##-->", "'");
<!--##
			} else {
##-->
		$json .= "<!--##=sPrefix##--><!--##=gsFldParm##-->:" . ew_VarToJson($this-><!--##=gsFldParm##-->->CurrentValue, "<!--##=GetFieldJsonTypeName(goFld.FldType)##-->", "'");
<!--##
			}
		}
	} // KeyField
##-->
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
<!--##
	for (var i = 0, len = arKeyFlds.length; i < len; i++) {
		if (GetFldObj(arKeyFlds[i])) {
//			sFldKeyVal = gsFldParm + "->CurrentValue";
//			if (ew_GetFieldType(FIELD.FldType) != 1) {
//				sFldKeyVal = "urlencode(" + sFldKeyVal + ")";
//			}
			sConcat = "";
			if (i > 0) sConcat = "&";
			if (goFld.FldHtmlTag == "FILE" && !ew_IsBinaryField(goFld)) { // Upload to folder (P501)
##-->
		if (!ew_Empty($this-><!--##=gsFldParm##-->->Upload->DbValue)) {
			$sUrl .= "<!--##=sConcat##--><!--##=gsFldParm##-->=" . urlencode($this-><!--##=gsFldParm##-->->Upload->DbValue);
<!--##
			} else {
##-->
		if (!is_null($this-><!--##=gsFldParm##-->->CurrentValue)) {
			$sUrl .= "<!--##=sConcat##--><!--##=gsFldParm##-->=" . urlencode($this-><!--##=gsFldParm##-->->CurrentValue);
<!--##
			}
##-->
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
<!--##
		}
	} // KeyField
##-->
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
<!--## if (iSortType == 0) { ##-->
		return "";
<!--## } else { ##-->
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			<!--## if (bTblDBMySql || bTblDBPostgreSql) { ##-->
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
			<!--## } else { ##-->
			in_array($fld->FldType, array(141, 201, 203, 128, 204, 205))) { // Unsortable data type
			<!--## } ##-->
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
<!--## } ##-->
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
	<!--## if (nKeyCount > 1) { ##-->
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
	<!--## } ##-->
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
	<!--## if (nKeyCount > 1) { ##-->
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
	<!--## } ##-->
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
	<!--##
		for (var i = 0, len = arKeyFlds.length; i < len; i++) {
			if (GetFldObj(arKeyFlds[i])) {
	##-->
	<!--## if (nKeyCount > 1) { ##-->
			if ($isPost && isset($_POST["<!--##=gsFldParm##-->"]))
				$arKey[] = ew_StripSlashes($_POST["<!--##=gsFldParm##-->"]);
			elseif (isset($_GET["<!--##=gsFldParm##-->"]))
				$arKey[] = ew_StripSlashes($_GET["<!--##=gsFldParm##-->"]);
			else
				$arKeys = NULL; // Do not setup
	<!--## } else { ##-->
			if ($isPost && isset($_POST["<!--##=gsFldParm##-->"]))
				$arKeys[] = ew_StripSlashes($_POST["<!--##=gsFldParm##-->"]);
			elseif (isset($_GET["<!--##=gsFldParm##-->"]))
				$arKeys[] = ew_StripSlashes($_GET["<!--##=gsFldParm##-->"]);
			else
				$arKeys = NULL; // Do not setup
	<!--## } ##-->
	<!--##
			}
		} // KeyField
		if (nKeyCount > 1) {
	##-->
			if (is_array($arKeys)) $arKeys[] = $arKey;
	<!--##
		}
	##-->
			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}
		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
	<!--## if (nKeyCount > 1) { ##-->
				if (!is_array($key) || count($key) <> <!--##=nKeyCount##-->)
					continue; // Just skip so other keys will still work
	<!--## } ##-->
	<!--##
		for (var i = 0, len = arKeyFlds.length; i < len; i++) {
			if (GetFldObj(arKeyFlds[i])) {
				bNumericKey = (ew_GetFieldType(goFld.FldType) == 1);
				if (bNumericKey) {
	##-->
	<!--## if (nKeyCount > 1) { ##-->
				if (!is_numeric($key[<!--##=i##-->])) // <!--##=gsFldName##-->
	<!--## } else { ##-->
				if (!is_numeric($key))
	<!--## } ##-->
					continue;
	<!--##
				}
			}
		} // KeyField
	##-->
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
	<!--##
		for (var i = 0, len = arKeyFlds.length; i < len; i++) {
			if (GetFldObj(arKeyFlds[i])) {
				if (nKeyCount > 1)
					keystr = "$key[" + i + "]";
				else
					keystr = "$key";
	##-->
			$this-><!--##=gsFldParm##-->->CurrentValue = <!--##=keystr##-->;
	<!--##
			}
		} // KeyField
	##-->
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

	<!--## if (TABLE.TblType != "REPORT") { ##-->

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
	<!--##
		for (var i = 0, len = arAllFlds.length; i < len; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (ew_GetFieldType(goFld.FldType) == 4) { // Boolean Fields
	##-->
		$this-><!--##=gsFldParm##-->->setDbValue((ew_ConvertToBool($rs->fields('<!--##=SQuote(gsFldName)##-->'))) ? "1" : "0");
	<!--##
				} else if (goFld.FldHtmlTag == "FILE") {
	##-->
		$this-><!--##=gsFldParm##-->->Upload->DbValue = $rs->fields('<!--##=SQuote(gsFldName)##-->');
	<!--##
				} else {
	##-->
		$this-><!--##=gsFldParm##-->->setDbValue($rs->fields('<!--##=SQuote(gsFldName)##-->'));
	<!--##
				}
			}
		} // AllField
	##-->
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;
		
	<!--## if (TABLE.TblType != "REPORT" && SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_Rendering")) { ##-->
		// Call Row Rendering event
		$this->Row_Rendering();
	<!--## } ##-->
	
   // Common render codes
   <!--##
		for (var i = 0, len = arAllFlds.length; i < len; i++) {
			if (GetFldObj(arAllFlds[i])) {
   ##-->
		// <!--##=gsFldName##-->
		<!--##~SYSTEMFUNCTIONS.ScriptCommon()##-->
   <!--##
			}
		} // AllField
   ##-->

	<!--##
		for (var i = 0, len = arAllFlds.length; i < len; i++) {
			if (GetFldObj(arAllFlds[i])) {
	##-->
		// <!--##=gsFldName##-->
		<!--##~SYSTEMFUNCTIONS.ScriptView()##-->
	<!--##
			}
		} // AllField
	##-->

	<!--##
		for (var i = 0, len = arAllFlds.length; i < len; i++) {
			if (GetFldObj(arAllFlds[i])) {
	##-->
		// <!--##=gsFldName##-->
		<!--##~SYSTEMFUNCTIONS.ScriptViewRefer()##-->
	<!--##
			}
		} // AllField
	##-->
	
	<!--## if (TABLE.TblType != "REPORT" && SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_Rendered")) { ##-->
		// Call Row Rendered event
		$this->Row_Rendered();
	<!--## } ##-->

	}
	
	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;
		
	<!--## if (TABLE.TblType != "REPORT" && SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_Rendering")) { ##-->
		// Call Row Rendering event
		$this->Row_Rendering();
	<!--## } ##-->
	
	<!--##
		for (var i = 0, len = arAllFlds.length; i < len; i++) {
			if (GetFldObj(arAllFlds[i])) {
	##-->
		// <!--##=gsFldName##-->
		<!--##~SYSTEMFUNCTIONS.ScriptEdit()##-->
	<!--##
			}
		} // AllField
	##-->
	
	<!--## if (TABLE.TblType != "REPORT" && SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_Rendered")) { ##-->
		// Call Row Rendered event
		$this->Row_Rendered();
	<!--## } ##-->

	}

	// Aggregate list row values
	function AggregateListRowValues() {
	<!--##
		for (var i = 0, len = arAllFlds.length; i < len; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (goFld.FldAggregate == "COUNT" || goFld.FldAggregate == "AVERAGE") {
	##-->
			$<!--##=gsFldObj##-->->Count++; // Increment count
	<!--##
				}
				if (goFld.FldAggregate == "AVERAGE" || goFld.FldAggregate == "TOTAL") {
	##-->
			if (is_numeric($<!--##=gsFldObj##-->->CurrentValue))
				$<!--##=gsFldObj##-->->Total += $<!--##=gsFldObj##-->->CurrentValue; // Accumulate total
	<!--##
				}
			}
		} // AllField
	##-->
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

	<!--##
		for (var i = 0, len = arAllFlds.length; i < len; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (ew_IsNotEmpty(goFld.FldAggregate)) {
	##-->
			<!--##~SYSTEMFUNCTIONS.ScriptAggregate()##-->
	<!--##
				}
			}
		} //AllField
	##-->

	<!--## if (TABLE.TblType != "REPORT" && SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_Rendered")) { ##-->
		// Call Row Rendered event
		$this->Row_Rendered();
	<!--## } ##-->

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

	<!--##
		for (var i = 0, len = arAllFlds.length; i < len; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (goFld.FldView) {
	##-->
					if ($<!--##=gsFldObj##-->->Exportable) $Doc->ExportCaption($<!--##=gsFldObj##-->);
	<!--##
				}
			}
		} // AllField
	##-->

				} else {

	<!--##
		for (var i = 0, len = arAllFlds.length; i < len; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (goFld.FldExport) {
	##-->
					if ($<!--##=gsFldObj##-->->Exportable) $Doc->ExportCaption($<!--##=gsFldObj##-->);
	<!--##
				}
			}
		} // AllField
	##-->

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

		<!--## if (SYSTEMFUNCTIONS.IsAggregate()) { ##-->
				$this->AggregateListRowValues(); // Aggregate row values
		<!--## } ##-->

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();

				if (!$Doc->ExportCustom) {

					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled

					if ($ExportPageType == "view") {

	<!--##
		for (var i = 0, len = arAllFlds.length; i < len; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (goFld.FldView) {
	##-->
						if ($<!--##=gsFldObj##-->->Exportable) $Doc->ExportField($<!--##=gsFldObj##-->);
	<!--##
				}
			}
		} // AllField
	##-->

					} else {

	<!--##
		for (var i = 0, len = arAllFlds.length; i < len; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (goFld.FldExport) {
	##-->
						if ($<!--##=gsFldObj##-->->Exportable) $Doc->ExportField($<!--##=gsFldObj##-->);
	<!--##
				}
			}
		} // AllField
	##-->

					}

					$Doc->EndExportRow();
				}
			}

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist(sCtrlType,"Row_Export")) { ##-->
			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
	<!--## } ##-->

			$Recordset->MoveNext();
		}
		
		<!--## if (SYSTEMFUNCTIONS.IsAggregate()) { ##-->
		// Export aggregates (horizontal format only)
		if ($Doc->Horizontal) {
			$this->RowType = EW_ROWTYPE_AGGREGATE;
			$this->ResetAttrs();
			$this->AggregateListRow();
			if (!$Doc->ExportCustom) {
				$Doc->BeginExportRow(-1);
	<!--##
		for (var i = 0, len = arAllFlds.length; i < len; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (goFld.FldExport) {
	##-->
				$Doc->ExportAggregate($<!--##=gsFldObj##-->, '<!--##=SQuote(goFld.FldAggregate)##-->');
	<!--##
				}
			}
		} // AllField
	##-->
				$Doc->EndExportRow();
			}
		}
		<!--## } ##-->
		
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}

	}

	<!--## } ##-->

<!--##
	if (bTableHasUserIDFld) {
		if (GetFldObj(TABLE.TblUserIDFld)) {
			sTblUserIDFldName = gsFld;
		}
##-->

	<!--##
		if (TABLE.TblName == SECTABLE.TblName) {

			if (GetFldObj(DB.SecuUserIDFld)) {
				sFldDataType = GetFieldTypeName(goFld.FldType);
				sUserIDFldName = gsFld;
			}

			if (bParentUserID) {
				if (GetFldObj(DB.SecuParentUserIDFld)) {
					sParentUserIDFldName = gsFld;
				}
			}

			if (SECTABLE.TblType == "CUSTOMVIEW")
				sFromPart = ew_SQLPart(SECTABLE.TblCustomSQL, "FROM");
			else
				sFromPart = SqlTableName(SECTABLE);
	##-->

	// User ID filter
	function UserIDFilter($userid) {
		$sUserIDFilter = '<!--##=SQuote(sUserIDFldName)##--> = ' . ew_QuotedValue($userid, <!--##=sFldDataType##-->, EW_USER_TABLE_DBID);
	<!--## if (bParentUserID) { ##-->
		$sParentUserIDFilter = '<!--##=SQuote(sUserIDFldName)##--> IN (SELECT <!--##=SQuote(sUserIDFldName)##--> FROM ' . "<!--##=ew_Quote2(sFromPart)##-->" . ' WHERE <!--##=SQuote(sParentUserIDFldName)##--> = ' . ew_QuotedValue($userid, <!--##=sFldDataType##-->, EW_USER_TABLE_DBID) . ')';
		$sUserIDFilter = "($sUserIDFilter) OR ($sParentUserIDFilter)";
	<!--## } ##-->
		return $sUserIDFilter;
	}

	<!--##
		}
	##-->

	// Add User ID filter
	function AddUserIDFilter($sFilter) {
		global $Security;
		$sFilterWrk = "";
		$id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
		if (!$this->UserIDAllow($id) && !$Security->IsAdmin()) {
			$sFilterWrk = $Security->UserIDList();
			if ($sFilterWrk <> "")
				$sFilterWrk = '<!--##=SQuote(sTableUserIDFld)##--> IN (' . $sFilterWrk . ')';
		}

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","UserID_Filtering")) { ##-->
		// Call User ID Filtering event
		$this->UserID_Filtering($sFilterWrk);
	<!--## } ##-->

		ew_AddFilter($sFilter, $sFilterWrk);
		return $sFilter;
	}
	
	<!--##
		if (bParentUserID && PROJ.SecTbl == TABLE.TblName) { // User table with parent user id
	##-->

	// Add Parent User ID filter
	function AddParentUserIDFilter($sFilter, $userid) {
		global $Security;
		if (!$Security->IsAdmin()) {
			$result = $Security->ParentUserIDList($userid);
			if ($result <> "")
				$result = '<!--##=SQuote(sTableUserIDFld)##--> IN (' . $result . ')';
			ew_AddFilter($result, $sFilter);
			return $result;
		} else {
			return $sFilter;
		}
	}

	<!--##
		}
	##-->
	
	// User ID subquery
	function GetUserIDSubquery(&$fld, &$masterfld) {
		global $UserTableConn;
		$sWrk = "";
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM <!--##=ew_Quote2(sFromPart)##-->";
		$sFilter = $this->AddUserIDFilter("");
		if ($sFilter <> "") $sSql .= " WHERE " . $sFilter;

		// Use subquery
		if (EW_USE_SUBQUERY_FOR_MASTER_USER_ID) {
			$sWrk = $sSql;
		} else {
			// List all values
			if ($rs = $UserTableConn->Execute($sSql)) {
				while (!$rs->EOF) {
					if ($sWrk <> "") $sWrk .= ",";
					$sWrk .= ew_QuotedValue($rs->fields[0], $masterfld->FldDataType, EW_USER_TABLE_DBID);
					$rs->MoveNext();
				}
				$rs->Close();
			}
		}
		if ($sWrk <> "") {
			$sWrk = $fld->FldExpression . " IN (" . $sWrk . ")";
		}
		return $sWrk;
	}
<!--##
	}
##-->

<!--##
	if (bMasterTableHasUserIDFld) {
##-->

	// Add master User ID filter
	function AddMasterUserIDFilter($sFilter, $sCurrentMasterTable) {
		$sFilterWrk = $sFilter;
	<!--##
		for (var i = 0, len = arMasterTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arMasterTables[i].index];
			MASTERTABLE = DB.Tables(MasterDetail.MasterTable);
			if (MASTERTABLE.TblGen) {
				if (bUserID && ew_IsNotEmpty(MASTERTABLE.TblUserIDFld)) {
					sMasterTblVar = MASTERTABLE.TblVar;
					if (sMasterTblVar == TABLE.TblVar) { // Check if master = detail
	##-->
		if ($sCurrentMasterTable == "<!--##=sMasterTblVar##-->" && $this->getCurrentMasterTable() <> "") {
	<!--##
					} else {
	##-->
		if ($sCurrentMasterTable == "<!--##=sMasterTblVar##-->") {
	<!--##
					}
	##-->
			$sFilterWrk = $GLOBALS["<!--##=sMasterTblVar##-->"]->AddUserIDFilter($sFilterWrk);
		}
	<!--##
				}
			}
		} // MasterDetail
	##-->
		return $sFilterWrk;
	}

	// Add detail User ID filter
	function AddDetailUserIDFilter($sFilter, $sCurrentMasterTable) {
		$sFilterWrk = $sFilter;
	<!--##
		for (var i = 0, len = arMasterTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arMasterTables[i].index];
			MASTERTABLE = DB.Tables(MasterDetail.MasterTable);
			if (MASTERTABLE.TblGen) {
				if (bUserID && ew_IsNotEmpty(MASTERTABLE.TblUserIDFld)) {
					sMasterTblVar = MASTERTABLE.TblVar;
					if (sMasterTblVar == TABLE.TblVar) { // Check if master = detail
	##-->
		if ($sCurrentMasterTable == "<!--##=sMasterTblVar##-->" && $this->getCurrentMasterTable() <> "") {
	<!--##
					} else {
	##-->
		if ($sCurrentMasterTable == "<!--##=sMasterTblVar##-->") {
	<!--##
					}
					for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
						MASTERFIELD = MASTERTABLE.Fields(MasterDetail.Rels[j].MasterField);
						sMasterFldParm = MASTERFIELD.FldParm;
						DETAILFIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
						sDetailFldParm = DETAILFIELD.FldParm;
	##-->
			$mastertable = $GLOBALS["<!--##=sMasterTblVar##-->"];
			if (!$mastertable->UserIDAllow()) {
				$sSubqueryWrk = $mastertable->GetUserIDSubquery($this-><!--##=sDetailFldParm##-->, $mastertable-><!--##=sMasterFldParm##-->);
				ew_AddFilter($sFilterWrk, $sSubqueryWrk);
			}
	<!--##
					} // MasterDetailField
	##-->
		}
	<!--##
				}
			}
		} // MasterDetail
	##-->
		return $sFilterWrk;
	}
<!--##
	}
##-->

	<!--## if (TABLE.TblType != "REPORT") { ##-->
	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

	<!--##
		for (var i = 0, wrklen = arAllFlds.length; i < wrklen; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (goFld.FldAutoFill && ew_IsNotEmpty(goFld.FldAutoFillSourceFields) && ew_IsNotEmpty(goFld.FldAutoFillTargetFields) &&
					ew_IsNotEmpty(goFld.FldTagLnkTbl) && ew_IsNotEmpty(goFld.FldTagLnkFld)) {
					var LINKTABLE = DB.Tables.Item(goFld.FldTagLnkTbl);
					if (!LINKTABLE) continue;
					var sCtrlID = CTRL.CtrlID;
					var iFldCnt = -1;
					var arFields = [];
					var sFldVarList = "";
					var arSourceFlds = goFld.FldAutoFillSourceFields.split("||");
					var arTargetFlds = goFld.FldAutoFillTargetFields.split("||");
					for (var j = 0, cnt = arSourceFlds.length; j < cnt; j++) {
						iFldCnt += 1;
						ew_AddSelectField(arFields, LINKTABLE, arSourceFlds[j], ew_LanguageCount, "FIELD" + iFldCnt); // Fill field
					}
					// Build SQL
					var WRKFLD = LINKTABLE.Fields.Item(goFld.FldTagLnkFld);
					if (!WRKFLD) continue;
					var sFilterFld = ew_Quote(ew_FieldSqlNameBase(WRKFLD, LINKTABLE));
					var sFldTypeName = GetFieldTypeName(WRKFLD.FldType);
					var sFilter = "(" + sFilterFld + " = \" . ew_QuotedValue($val, " + sFldTypeName + ", $this->DBID) . \")";
	##-->
		if (preg_match('/^x(\d)*_<!--##=gsFldParm##-->$/', $id)) {
		<!--## if (LINKTABLE.LinkDBID == TABLE.LinkDBID) { ##-->
			$conn = &$this->Connection();
		<!--## } else { ##-->
			$conn = &Conn('<!--##=SQuote(LINKTABLE.LinkDBID)##-->');
		<!--## } ##-->
			<!--##~SYSTEMFUNCTIONS.LookupSql(false, arFields, sFilter, "", true, "", true)##-->
			if ($rs = ew_LoadRecordset($sSqlWrk, $conn)) {
				while ($rs && !$rs->EOF) {
					$ar = array();
	<!--##
					for (var j = 0, len = arTargetFlds.length; j < len; j++) {
						var TARGETFLD = goTblFlds.Fields[arTargetFlds[j]];
						sTargetFldParm = TARGETFLD.FldParm;
	##-->
					$this-><!--##=sTargetFldParm##-->->setDbValue($rs->fields[<!--##=j##-->]);
	<!--##
					}
	##-->
					$this->RowType == EW_ROWTYPE_EDIT;
					$this->RenderEditRow();
	<!--##
					var tmpFld = goFld; // Save field object
					for (var j = 0, len = arTargetFlds.length; j < len; j++) {
						goFld = goTblFlds.Fields[arTargetFlds[j]];
						sTargetFldParm = goFld.FldParm;
						if (SYSTEMFUNCTIONS.IsBoolFld()) {
	##-->
					$ar[] = ew_ConvertToBool($this-><!--##=sTargetFldParm##-->->CurrentValue) ? "<!--##=ewTrue##-->" : "<!--##=ewFalse##-->";
	<!--##
						} else if (ew_InArray(goFld.FldHtmlTag, ["SELECT", "RADIO", "CHECKBOX", "HIDDEN"]) > -1) {
	##-->
					$ar[] = $this-><!--##=sTargetFldParm##-->->CurrentValue;
	<!--##
						} else {
	##-->
					$ar[] = ($this-><!--##=sTargetFldParm##-->->AutoFillOriginalValue) ? $this-><!--##=sTargetFldParm##-->->CurrentValue : $this-><!--##=sTargetFldParm##-->->EditValue;
	<!--##
						}
					}
					goFld = tmpFld; // Restore field object
	##-->
					$rowcnt += 1;
					$rsarr[] = $ar;
					$rs->MoveNext();
				}
				$rs->Close();
			}
		}
	<!--##
				}
			}
		}
	##-->

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
	<!--## } ##-->

	<!--## if (bUserTable && TABLE.TblName == PROJ.SecTbl && PROJ.SecRegisterEmail && ew_IsNotEmpty(PROJ.SecEmailFld)) { ##-->
	// Send register email
	function SendRegisterEmail($row) {

	<!--## if (bUserProfile && bMultiLanguage) { ##-->
		// Get user language
		global $UserProfile;
		$sUsername = $row['<!--##=SQuote(PROJ.SecLoginIDFld)##-->'];
		$langid = $UserProfile->GetLanguageId($sUsername);
		$Email = $this->PrepareRegisterEmail($row, $langid);
	<!--## } else { ##-->
		$Email = $this->PrepareRegisterEmail($row);
	<!--## } ##-->

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Email_Sending")) { ##-->
		$Args = array();
		$Args["rs"] = $row;
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $Args)) // NOTE: use Email_Sending server event of user table
			$bEmailSent = $Email->Send();
	<!--## } else { ##-->
		$bEmailSent = $Email->Send();
	<!--## } ##-->
		return $bEmailSent;
	}

	// Prepare register email
	function PrepareRegisterEmail($row = NULL, $langid = "") {
		$Email = new cEmail;
		$Email->Load(EW_EMAIL_REGISTER_TEMPLATE, $langid);
	<!--##
		if (GetFldObj(PROJ.SecEmailFld)) {
			EMAILFIELD = FIELD;
			sEmailFldName = gsFldName;
			sEmailFldParm = gsFldParm;
			sEmailFldObj = "this->" + gsFldParm;
		}
	##-->
		$sReceiverEmail = ($row == NULL) ? $<!--##=sEmailFldObj##-->->CurrentValue : $row['<!--##=SQuote(sEmailFldName)##-->'];
		if ($sReceiverEmail == "") { // Send to recipient directly
			$sReceiverEmail = EW_RECIPIENT_EMAIL;
			$sBccEmail = "";
		} else { // Bcc recipient
			$sBccEmail = EW_RECIPIENT_EMAIL;
		}
		$Email->ReplaceSender(EW_SENDER_EMAIL); // Replace Sender
		$Email->ReplaceRecipient($sReceiverEmail); // Replace Recipient
		if ($sBccEmail <> "") $Email->AddBcc($sBccEmail); // Add Bcc
	<!--##
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (goFld.FldRegister) {
	##-->
		$Email->ReplaceContent('<!--FieldCaption_<!--##=SQuote(gsFldName)##-->-->', $this-><!--##=gsFldParm##-->->FldCaption());
		$Email->ReplaceContent('<!--<!--##=SQuote(gsFldName)##-->-->', ($row == NULL) ? strval($this-><!--##=gsFldParm##-->->FormValue) : $row['<!--##=SQuote(gsFldName)##-->']);
	<!--##
				}
			}
		} // Field

		if (PROJ.SecRegisterActivate) {
			if (GetFldObj(PROJ.SecLoginIDFld)) {
				LOGINIDFIELD = FIELD;
				sLoginIDFldName = gsFldName;
				sLoginIDFldParm = gsFldParm;
				sLoginIDFldObj = "this->" + gsFldParm;
			}
			if (GetFldObj(PROJ.SecPasswdFld)) {
				PASSWORDFIELD = FIELD;
				sPasswordFldName = gsFldName;
				sPasswordFldParm = gsFldParm;
				sPasswordFldObj = "this->" + gsFldParm;
			}
	##-->
		$sLoginID = ($row == NULL) ? $<!--##=sLoginIDFldObj##-->->CurrentValue : $row['<!--##=SQuote(sLoginIDFldName)##-->'];
		$sPassword = ($row == NULL) ? $<!--##=sPasswordFldObj##-->->CurrentValue : $row['<!--##=SQuote(sPasswordFldName)##-->'];
		$sActivateLink = ew_ConvertFullUrl("<!--##=sFnRegister##-->") . "?action=confirm";
		$sActivateLink .= "&email=" . $sReceiverEmail;
		$sToken = ew_Encrypt($sReceiverEmail) . "," . ew_Encrypt($sLoginID) . "," . ew_Encrypt($sPassword);
		$sActivateLink .= "&token=" . $sToken;
		$Email->ReplaceContent("<!--ActivateLink-->", $sActivateLink);
		$Email->Content = preg_replace('/<!--\s*register_activate_link[\s\S]*?-->/i', '', $Email->Content); // Remove comments
	<!--##
		} else {
	##-->
		$Email->Content = preg_replace('/<!--\s*register_activate_link_begin[\s\S]*?-->[\s\S]*?<!--\s*register_activate_link_end[\s\S]*?-->/i', '', $Email->Content); // Remove activate link block
	<!--##
		}
	##-->
		return $Email;
	}
	<!--## } ##-->

	<!--## if (TABLE.TblType != "REPORT") { ##-->
	// Table level events
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Recordset_Selecting")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Recordset_Selected")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Recordset_SearchValidated")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Recordset_Searching")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Row_Selecting")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Row_Selected")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Row_Inserting")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Row_Inserted")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Row_Updating")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Row_Updated")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Row_UpdateConflict")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Grid_Inserting")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Grid_Inserted")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Grid_Updating")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Grid_Updated")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Row_Deleting")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Row_Deleted")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Email_Sending")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Lookup_Selecting")##-->
	<!--## } ##-->

	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Row_Rendering")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Row_Rendered")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","UserID_Filtering")##-->

}
?>
<!--##/session##-->