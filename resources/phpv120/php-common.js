/*
 *** -----------------------------------------
 ***  IMPORTANT - DO NOT CHANGE
 ***
 *
 ***********************
 * Common variables
 */

// Global system variable: bDBMsAccess, bDBMsSql, bDBMySql, bDBPostgreSql

var dMasterDetail = {};

// Table level variables - set up in phpcommon-table.php
var gsTblVar;
var gsTblName;
var gbTblListAdd;
var gbTblListEdit;

// Field level variables
var goFlds;
var goFld;
var glFldPageIndex;
var gsFld;
var gsFldQuoteS, gsFldQuoteE;
var gsFldName;
var gsFldVar;
var gsFldParm;
var gsFldObj;

// Field arrays
var arKeyFlds;
var arFlds;
var arAllFlds;

// User level prefix for PHP Report Maker
var pfxUserLevel = ReadReg("HKCU\\Software\\PHPReportMaker\\6.0\\Settings\\General\\UserLevelTableNamePrefix");
if (pfxUserLevel == "") pfxUserLevel = ReadReg("HKCU\\Software\\PHPReportMaker\\5.0\\Settings\\General\\UserLevelTableNamePrefix");
if (pfxUserLevel == "") pfxUserLevel = "||PHPReportMaker||";

/**
 ***********************
 * Commonly used functions
 */


// Read registry
function ReadReg(RegPath) {
	try {
		var obj = new ActiveXObject("WScript.Shell");
		return obj.RegRead(RegPath);
	} catch(e) {
		return "";
	}
}

// Convert Data
function ConvertData(v, t) {
	try {
		switch (t.toLowerCase()) {
			case "boolean": if (v) return 1; else return 0;
			case "integer": return int(v);
			case "long": return int(v);
			case "single": return float(v);
			case "double": return double(v);
			default: return v;
		}
	} catch(e) {
		return v;
	}
}

// Get field Json type name
function GetFieldJsonTypeName(FldTyp) {
	switch (FldTyp) {
		//Case adBigInt, adInteger, adSmallInt, adTinyInt, adSingle, adDouble, adNumeric, adCurrency, adUnsignedTinyInt, adUnsignedSmallInt, adUnsignedInt, adUnsignedBigInt, 139
		case 20:
		case 3:
		case 2:
		case 16:
		case 4:
		case 5:
		case 131:
		case 6:
		case 17:
		case 18:
		case 19:
		case 21:
		case 139:
			return "number"; // Number
		//Case adDate, adDBDate, adDBTimeStamp, 146
		case 7:
		case 133:
		case 135:
		case 146:
			return "string"; // Treate date as string
		//Case adDBTime, 145
		case 134:
		case 145:
			return "string"; // Treat time as string
		//Case adLongVarChar, adLongVarWChar, adChar, adWChar, adVarChar, adVarWChar, 141
		case 201:
		case 203:
		case 129:
		case 130:
		case 200:
		case 202:
		case 141:
			return "string"; // String
		//Case adBoolean
		case 11:
			return "boolean"; // Boolean
		//Case adGUID
		case 72:
			return "string"; // Treat GUID as string
		//Case adVarBinary, adLongVarBinary
		case 204:
		case 205:
			return "string"; // Treat binary as string
		default:
			return "string"; // Default as string
	}
}

function GetFieldTypeName(FldTyp) {
	switch (FldTyp) {
		//Case adBigInt, adInteger, adSmallInt, adTinyInt, adSingle, adDouble, adNumeric, adCurrency, adUnsignedTinyInt, adUnsignedSmallInt, adUnsignedInt, adUnsignedBigInt, 139
		case 20:
		case 3:
		case 2:
		case 16:
		case 4:
		case 5:
		case 131:
		case 6:
		case 17:
		case 18:
		case 19:
		case 21:
		case 139:
			return "EW_DATATYPE_NUMBER";
		//Case adDate, adDBDate, adDBTimeStamp, 146
		case 7:
		case 133:
		case 135:
		case 146:
			return "EW_DATATYPE_DATE";
		//Case adDBTime, 145
		case 134:
		case 145:
			return "EW_DATATYPE_TIME";
		//Case adLongVarChar, adLongVarWChar
		case 201:
		case 203:
			return "EW_DATATYPE_MEMO";
		//Case adChar, adWChar, adVarChar, adVarWChar, 141
		case 129:
		case 130:
		case 200:
		case 202:
		case 141:
			return "EW_DATATYPE_STRING";

		//Case adBoolean
		case 11:
			return "EW_DATATYPE_BOOLEAN";
		//Case adGUID
		case 72:
			return "EW_DATATYPE_GUID";
		//Case adVarBinary, adLongVarBinary
		case 204:
		case 205:
			return "EW_DATATYPE_BLOB";
		default:
			return "EW_DATATYPE_OTHER";
	}
}

function GetReturnPage(p) {
	if (p.substr(0,1) == "_") {
		switch (p.substr(1).toLowerCase()) {
			case "view":
				return "$this->GetViewUrl()";
			case "edit":
				return "$this->GetEditUrl()";
			case "add":
				return "$this->GetAddUrl()";
			case "copy":
				return "$this->GetCopyUrl()";
			case "delete":
				return "$this->GetDeleteUrl()";
			default:
				var wrk = ew_GetFileNameByCtrlID(p.substr(1));
				return ew_DoubleQuote(wrk, 1);
		}
	} else {
		return p;
	}
}

// Set field name in array
function SetFldObj(ar, idx) {
	ar[idx] = gsFldParm; // FIELD.FldVar
}

// Get field object
function GetFldObj(fldname) {
	FIELD = TABLE.Fields(fldname);
	goFld = goFlds[fldname];
	if (goFld) {
		gsFldParm = goFld.FldParm;
		gsFldVar = goFld.FldVar;
		gsFldName = goFld.FldName;
		glFldPageIndex = goFld.FldPageIndex;
		gsFld = ew_FieldSqlName(goFld);
		gsFldQuoteS = goFld.FldQuoteS;
		gsFldQuoteE = goFld.FldQuoteE;
	} else {
		gsFldParm = FIELD.FldParm;
		gsFldVar = FIELD.FldVar;
		gsFldName = FIELD.FldName;
		glFldPageIndex = FIELD.FldPageIndex;
		gsFld = ew_FieldSqlName(FIELD);
		gsFldQuoteS = FIELD.FldQuoteS;
		gsFldQuoteE = FIELD.FldQuoteE;
	}
	if (CTRL.CtrlID == "info")
		gsFldObj = "this->" + gsFldParm;
	else
		gsFldObj = gsTblVar + "->" + gsFldParm;
	return true;
}

function GetFldVal(tbl, fld, fldtype) {
	var dbid = ew_GetDbId(tbl.TblName);
	if (ew_DbType(dbid) == "MYSQL") { // MySQL
		return fld;
	} else {
		if (ew_GetFieldType(fldtype) == 4) {
			return "((ew_ConvertToBool(" + fld + ")) ? \"1\" : \"0\")";
		//else if (fldtype == 18 || fldtype == 19 || fldtype == 131 || fldtype == 139) {
			//return "ew_Conv(" + fld + ", " + fldtype + ")";
		} else {
			return fld;
		}
	}
}

function UseAddOpt() {
	for (var i = 0, len = goTbls.length; i < len; i++) {
		var TMPTABLE = goTbls[i];
		if (TMPTABLE.TblAddOpt)
			return true;
	}
	return false;
}

function UseEmailExport() {
	for (var i = 0, len = goTbls.length; i < len; i++) {
		var TMPTABLE = goTbls[i];
		if (TMPTABLE.TblGen && ((!TMPTABLE.TblUseGlobal && TMPTABLE.TblExportEmail) || PROJ.ExportEmail))
			return true;
	}
	return false;
}

// Return if Export is required
function IsExport() {
	for (var i = 0, len = goTbls.length; i < len; i++) {
		var WRKTABLE = goTbls[i];
		var bTblGen = WRKTABLE.TblGen;
		if (bTblGen) {
			var bUseGlobal = WRKTABLE.TblUseGlobal;
			var bExport = (bUseGlobal) ? PROJ.PrinterFriendly : WRKTABLE.TblPrinterFriendly;
			if (bExport) return true;
			bExport = (bUseGlobal) ? PROJ.ExportHtml : WRKTABLE.TblExportHtml;
			if (bExport) return true;
			bExport = (bUseGlobal) ? PROJ.ExportWord : WRKTABLE.TblExportWord;
			if (bExport) return true;
			bExport = (bUseGlobal) ? PROJ.ExportExcel : WRKTABLE.TblExportExcel;
			if (bExport) return true;
			bExport = (bUseGlobal) ? PROJ.ExportXml : WRKTABLE.TblExportXml;
			if (bExport) return true;
			bExport = (bUseGlobal) ? PROJ.ExportCsv : WRKTABLE.TblExportCsv;
			if (bExport) return true;
			bExport = (bUseGlobal) ? PROJ.ExportEmail : WRKTABLE.TblExportEmail; // P7
			if (bExport) return true;
			bExport = (bUseGlobal) ? PROJ.ExportPDF : WRKTABLE.TblExportPDF; // P8
			if (bExport) return true;
		}
	}
	return false;
}

function UseTooltip() {
	return true;
}

function IsMasterDetail(master, detail) {
	for (var i = 0, len = goAllMasDets.length; i < len; i++) {
		var MasterDetail = goAllMasDets[i];
		if (MasterDetail.DetailTable == detail && MasterDetail.MasterTable == master)
			return true;
	}
	return false;
}

function IsRelated(master, detail) {
	dMasterDetail[master + "->" + detail] = "";
	for (var i = 0, len = goAllMasDets.length; i < len; i++) {
		var MasterDetail = goAllMasDets[i];
		if (MasterDetail.DetailTable == detail) {
			if (MasterDetail.MasterTable == master) { // Check if master table
				return true;
			} else if (!(master + "->" + MasterDetail.MasterTable in dMasterDetail)) {
				if (IsRelated(master, MasterDetail.MasterTable)) // Check if master table of master table
					return true;
			}
		}
	}
	return false;
}

function IsRequiredField(f) {
	var isRequired;
	var bValidateNotNull = PROJ.GetV("ValidateNotNull");
	isRequired = (((f.FldReq && ew_IsEmpty(f.FldDbDefault) && bValidateNotNull) || f.FldRequired) && ew_IsEmpty(f.FldAutoUpdateValue) && !f.FldAutoIncrement) &&
		(f.FldHtmlTag != "NO" && f.FldHtmlTag != "HIDDEN") &&
		(IsFldList(f) || IsFldAdd(f) || IsFldAddOpt(f) || IsFldRegister(f) ||
		(IsFldEdit(f) && !f.FldHtmlTagReadOnly) ||
		(IsFldUpdate(f) && !f.FldHtmlTagReadOnly && !f.FldIsPrimaryKey))
	// Set register page username/password/email field as required
	if (!isRequired && IsFldRegister(f)) {
		if (f.FldName == PROJ.SecPasswdFld || f.FldName == PROJ.SecLoginIDFld || (PROJ.SecRegisterEmail && f.FldName == PROJ.SecEmailFld))
			isRequired = true;
	}
	return isRequired;
}

function IsValidateText(f) {
	return (ew_IsEmpty(f.FldAutoUpdateValue)) &&
		((f.FldHtmlTag == "TEXT" || f.FldHtmlTag == "PASSWORD") &&
		(!ew_IsFldVirtualLookup(f)) &&
		((IsFldList(f) && !f.FldHtmlTagReadOnly) || IsFldAdd(f) || IsFldAddOpt(f) || IsFldRegister(f) ||
		(IsFldEdit(f) && !f.FldHtmlTagReadOnly) ||
		(IsFldUpdate(f) && !f.FldHtmlTagReadOnly)));
}

function IsValidateFile(f) {
	return (f.FldHtmlTag == "FILE") &&
		((IsFldList(f) && !f.FldHtmlTagReadOnly) || IsFldAdd(f) || IsFldAddOpt(f) || IsFldRegister(f) ||
		(IsFldEdit(f) && !f.FldHtmlTagReadOnly) ||
		(IsFldUpdate(f) && !f.FldHtmlTagReadOnly));
}

function IsValidateSearch(f) {
	return (f.FldHtmlTag == "TEXT" || f.FldHtmlTag == "NO") &&
		(!ew_IsFldVirtualLookup(f)) &&
		(IsFldExtendedSearch(f) || IsFldAdvancedSearch(f)) &&
		(f.FldValidate != "EMAIL");
}

function IsValidateServer(f) {
	if (PROJ.ServerValidate)
		return true;
	else
		return (f.FldValidate == "PASSWORD" || f.FldValidate == "EMAIL" || f.FldValidate == "DATE" || f.FldValidate == "EURODATE" || f.FldValidate == "USDATE" || f.FldValidate == "TIME" || f.FldValidate == "FLOAT" || f.FldValidate == "RANGE" || f.FldValidate == "INTEGER" || f.FldValidate == "USPHONE" || f.FldValidate == "USZIP" || f.FldValidate == "CREDITCARD" || f.FldValidate == "USSSN" || f.FldValidate == "GUID");
}

function IsFldEditCtl(f) {
	return (IsFldList(f) && (gbTblListAdd || gbTblListEdit)) ||
		IsFldEdit(f) || IsFldUpdate(f) || IsFldAdd(f) || IsFldRegister(f);
}

function IsFldList(f) {
	return (f.FldList && (CTRL.CtrlID == "list" || CTRL.CtrlID == "master" || CTRL.CtrlID == "info" || CTRL.CtrlID == "grid" || CTRL.CtrlID == "gridcls" || CTRL.CtrlID == "preview"));
}

function IsFldReport(f) {
	return (f.FldList && CTRL.CtrlID == "report");
}

function IsFldBasicSearch(f) {
	return (f.FldList && f.FldBasicSearch);
}

function IsFldExtendedSearch(f) {
	return (f.FldList && f.FldExtendedBasicSearch);
}

function IsFldAdvancedSearch(f) {
	return (f.FldSearch && CTRL.CtrlID == "search");
}

function IsFldUpdatable(f) {
	return (((f.FldAttribute & 4) == 4) || ((f.FldAttribute & 8) == 8) || f.FldIsCustom);
}

function IsFldView(f) {
	return (f.FldView && CTRL.CtrlID == "view");
}

function IsFldAdd(f) {
	return (f.FldAdd && CTRL.CtrlID == "add");
}

function IsFldAddOpt(f) {
	return (f.FldAddOpt && CTRL.CtrlID == "addopt");
}

function IsFldEdit(f) {
	return (f.FldEdit && CTRL.CtrlID == "edit");
}

function IsFldDelete(f) {
	return (f.FldList && CTRL.CtrlID == "delete");
}

function IsFldUpdate(f) {
	return (f.FldMultiUpdate && CTRL.CtrlID == "update");
}

function IsFldRegister(f) {
	return (f.FldRegister && CTRL.CtrlID == "register");
}

function IsCustomFld(f) {
	return f.FldIsCustom;
}

function InArray(ar, v) {
	return ew_InArray(v, ar) > -1;
}

function ActivateFieldValue(t, f) {
	var val;
	var dbid = ew_GetDbId(t.TblName);
	switch (ew_GetFieldType(f.FldType)) {
	case 4: // Boolean
		val = "1";
		if (ew_DbType(dbid) == "ACCESS") // Access
			val = "True";
		break;
	case 1: // Numeric
		val = 1;
		break;
	default:
		if (f.NativeDataType == 247) { // ENUM
			if (ew_HasTagValue(f, "Y")) // Assume ENUM(Y,N)
				val = "Y";
			else
				val = "1";
		} else {
			val = "Y";
		}
	}
	return val;
}

function FieldTD_Header(f) {
	var bFldColumnWrap, sFldColumnWidth;
	var sStyle;
	bFldColumnWrap = f.FldColumnWrap;
	sFldColumnWidth = f.FldColumnWidth;
	sStyle = "";
	if (ew_IsNotEmpty(sFldColumnWidth)) {
		sStyle += "width: " + sFldColumnWidth;
		if (!isNaN(sFldColumnWidth)) sStyle += "px";
		sStyle += ";";
	}
	if (!bFldColumnWrap) {
		if (ew_IsNotEmpty(sStyle)) sStyle += " ";
		sStyle += "white-space: nowrap;";
	}
	if (ew_IsNotEmpty(sStyle)) sStyle = " style=\"" + sStyle + "\"";
	return sStyle;
}

function FieldTD_Item(f) {
	return ""; // Set up in RenderRow / RenderListRow
}

function SqlTableName(t) {
	var name;
	if (t.TblType == "LINKTABLE")
		name = ew_QuotedName(t.LinkTableName);
	else
		name = ew_QuotedName(t.TblName);
	if (ew_IsNotEmpty(t.TblSchema))
		name = ew_QuotedName(t.TblSchema) + "." + name;
	return name;
}

function BuildCond(cond,opr,newcond) {
	if (ew_IsNotEmpty(newcond)) {
		if (ew_IsNotEmpty(cond)) {
			var wrkcond = cond + " " + opr + " ";
			wrkcond += newcond;
			if (opr.toLowerCase() == "||") wrkcond = "(" + wrkcond + ")";
			return wrkcond;
		} else {
			return newcond;
		}
	} else {
		return cond;
	}
}

function GetProjCssFileName() {
	var fileName = PROJ.ProjVar + ".css";
	if (PROJ.OutputNameLCase)
		fileName = fileName.toLowerCase();
	if (ew_IsNotEmpty(ew_FolderPath("_css")))
		fileName = ew_FolderPath("_css") + "/" + fileName;
	return fileName;
}

function GetImageFolder() {
	if (ew_IsNotEmpty(ew_FolderPath("_images")))
		return ew_FolderPath("_images") + "/";
	else
		return "";
}

function CharsetToIconvEncoding(Charset) {
	switch (Charset.toLowerCase()) {
		case "iso-8859-6": return "ISO-8859-6";
		case "x-mac-arabic": return "MacArabic";
		case "windows-1256": return "CP1256";
		case "iso-8859-4": return "ISO-8859-4";
		case "windows-1257": return "CP1257";
		case "ibm852": return "CP852";
		case "iso-8859-2": return "ISO-8859-2";
		case "x-mac-ce": return "MacCentralEurope";
		case "windows-1250": return "CP1250";
		case "gb2312": return "GBK";
		case "hz-gb-2312": return "GBK";
		case "big5": return "BIG5";
		case "cp866": return "CP866";
		case "iso-8859-5": return "ISO-8859-5";
		case "koi8-r": return "KOI8-R";
		case "koi8-u": return "KOI8-U";
		case "x-mac-cyrillic": return "MacCyrillic";
		case "windows-1251": return "CP1251";
		case "iso-8859-7": return "ISO-8859-7";
		case "x-mac-greek": return "MacGreek";
		case "windows-1253": return "CP1253";
		case "iso-8859-8-i": return "ISO-8859-8";
		case "iso-8859-8": return "ISO-8859-8";
		case "x-mac-hebrew": return "MacHebrew";
		case "windows-1255": return "CP1255";
		case "x-mac-icelandic": return "MacIceland";
		case "euc-jp": return "EUC-JP";
		case "iso-2022-jp": return "ISO-2022-JP";
		case "shift_jis": return "SHIFT_JIS";
		case "euc-kr": return "EUC-KR";
		case "iso-2022-kr": return "ISO-2022-KR";
		case "Johab": return "JOHAB";
		case "iso-8859-3": return "ISO-8859-3";
		case "iso-8859-15": return "ISO-8859-15";
		case "windows-874": return "CP874";
		case "ibm857": return "CP857";
		case "iso-8859-9": return "ISO-8859-9";
		case "x-mac-turkish": return "MacTurkish";
		case "windows-1254": return "CP1254";
		case "utf-16": return "UTF-16";
		case "utf-8": return "UTF-8";
		case "windows-1258": return "CP1258";
		case "ibm850": return "CP850";
		case "iso-8859-1": return "ISO-8859-1";
		case "macintosh": return "Macintosh";
		case "windows-1252": return "CP1252";
		// Add your encodings here
		default: return "";
	}
}

function CharsetToMySqlCharset(Charset) {
	switch (Charset.toLowerCase()) {
		//case "iso-8859-6": return "";
		//case "x-mac-arabic": return "";
		case "windows-1256": return "cp1256";
		//case "iso-8859-4": return "";
		case "windows-1257": return "cp1257";
		case "ibm852": return "cp852";
		case "iso-8859-2": return "latin2";
		case "x-mac-ce": return "macce";
		case "windows-1250": return "cp1250";
		case "gb2312": return "gb2312";
		//case "hz-gb-2312": return "";
		case "big5": return "big5";
		case "cp866": return "cp866";
		//case "iso-8859-5": return "";
		case "koi8-r": return "koi8r";
		case "koi8-u": return "koi8u";
		//case "x-mac-cyrillic": return "";
		case "windows-1251": return "cp1251";
		case "iso-8859-7": return "greek";
		//case "x-mac-greek": return "";
		//case "windows-1253": return "";
		case "iso-8859-8-i": return "hebrew";
		case "iso-8859-8": return "hebrew";
		//case "x-mac-hebrew": return "";
		//case "windows-1255": return "";
		case "x-mac-icelandic": return "";
		case "euc-jp": return "ujis";
		//case "iso-2022-jp": return "";
		case "shift_jis": return "sjis";
		case "euc-kr": return "euckr";
		//case "iso-2022-kr": return "";
		//case "Johab": return "";
		//case "iso-8859-3": return "";
		//case "iso-8859-15": return "";
		//case "windows-874": return "";
		//case "ibm857": return "";
		case "iso-8859-9": return "latin5";
		//case "x-mac-turkish": return "";
		//case "windows-1254": return "";
		case "utf-16": return "ucs2";
		case "utf-8": return "utf8";
		//case "windows-1258": return "";
		case "ibm850": return "cp850";
		case "iso-8859-1": return "latin1";
		case "macintosh": return "macroman";
		case "windows-1252": return "latin1";
		// Add your encodings here
		default: return "";
	}
}

function IsMsAccess() {
	return bDBMsAccess;
}

function IsMsSQL() {
	return bDBMsSql;
}

function IsMySQL() {
	return bDBMySql;
}

function IsPostgreSQL() {
	return bDBPostgreSql;
}

function IsOracle() {
	return bDBOracle;
}

// Get Oracle service name from connection string
function GetOracleServiceName(ConnStr) {
	sName = "";
	var wrkstr = ConnStr.toUpperCase();
	var p1 = wrkstr.indexOf("DATA SOURCE=");
	if (p1 > 0) {
		p1 += 12; // Skip "Data Source=";
		p2 = ConnStr.indexOf(";", p1);
		if (p2 > p1)
			sName = ConnStr.substr(p1, p2-p1);
		else
			sName = ConnStr.substr(p1);
	}
	return sName;
}

function UseADOdb() {
	return bUseADOdb;
}

function CompatOldReport() {
	return ew_IsNotEmpty(PROJ.AppRelatedProject) && (PROJ.AppCompatVersion == "" || PROJ.AppCompatVersion <= 7);
}

function GenChangePwd() {
	return (PROJ.SecChangePwdPage || PROJ.MD5Password) && (PROJ.SecType == "Use Table" || PROJ.SecType == "Both");
}

/*
 ***
 ***  IMPORTANT - DO NOT CHANGE
 *** -----------------------------------------
 */