<!--##session ewconfig##-->
<!--##
// Project Name
sProjVar = PROJ.ProjVar;

// Database location
if (bDBMsAccess)
	iCursorLocation = 2; // Use adUseServer
else
	iCursorLocation = 3; // Use adUseClient

// Date Separator
sDateSeparator = PROJ.DateSeparator;
if (sDateSeparator == "") sDateSeparator = "/";

// Smtp Server and Port
sSmtpServer = PROJ.SmtpServer;
if (sSmtpServer == "") sSmtpServer = "localhost";
iSmtpServerPort = PROJ.SmtpServerPort;
if (iSmtpServerPort <= 0) iSmtpServerPort = 25;

// Random key
sRandomKey = ew_RandomKey();

// Default Date Format
var DefaultDateFormat;
switch (PROJ.DefaultDateFormat) {
	case 5:
	case 9:
	case 12:
	case 15:
	DefaultDateFormat = "yyyy" + sDateSeparator + "mm" + sDateSeparator + "dd"; break;
	case 6:
	case 10:
	case 13:
	case 16:
	DefaultDateFormat = "mm" + sDateSeparator + "dd" + sDateSeparator + "yyyy"; break;
	case 7:
	case 11:
	case 14:
	case 17:
	DefaultDateFormat = "dd" + sDateSeparator + "mm" + sDateSeparator + "yyyy"; break;
}

var sFilter = "";

var iUploadThumbnailWidth = PROJ.GetV("UploadThumbnailWidth");
if (!isNumber(iUploadThumbnailWidth) || iUploadThumbnailWidth < 0) iUploadThumbnailWidth = 0;
var iUploadThumbnailHeight = PROJ.GetV("UploadThumbnailHeight");
if (!isNumber(iUploadThumbnailHeight) || iUploadThumbnailHeight < 0) iUploadThumbnailHeight = 0;
if (iUploadThumbnailWidth == 0 && iUploadThumbnailHeight == 0) iUploadThumbnailWidth = 200;

var sUploadUrl = ew_GetFileNameByCtrlID("ewupload");

var bReduceImageOnly = PROJ.GetV("ReduceImageOnly");
var bKeepAspectRatio = PROJ.GetV("AlwaysKeepAspectRatio");
##-->
<?php
/**
 * PHPMaker 12 configuration file
 */

// Relative path
if (!isset($EW_RELATIVE_PATH)) $EW_RELATIVE_PATH = "";

// Show SQL for debug
define("EW_DEBUG_ENABLED", <!--##=ew_Val(PROJ.GetV("Debug"))##-->, TRUE); // TRUE to debug
if (EW_DEBUG_ENABLED) {
	@ini_set("display_errors", "1"); // Display errors
	error_reporting(E_ALL ^ E_NOTICE); // Report all errors except E_NOTICE
}

// General
define("EW_IS_WINDOWS", (strtolower(substr(PHP_OS, 0, 3)) === 'win'), TRUE); // Is Windows OS
define("EW_IS_PHP5", (phpversion() >= "5.3.0"), TRUE); // Is PHP 5.3 or later
if (!EW_IS_PHP5) die("This script requires PHP 5.3 or later. You are running " . phpversion() . ".");
define("EW_PATH_DELIMITER", ((EW_IS_WINDOWS) ? "\\" : "/"), TRUE); // Physical path delimiter
$EW_ROOT_RELATIVE_PATH = "<!--##=PROJ.RootRelativePath##-->"; // Relative path of app root
define("EW_DEFAULT_DATE_FORMAT", "<!--##=DefaultDateFormat##-->", TRUE); // Default date format
define("EW_DEFAULT_DATE_FORMAT_ID", "<!--##=PROJ.DefaultDateFormat##-->", TRUE); // Default date format
define("EW_DATE_SEPARATOR", "<!--##=sDateSeparator##-->", TRUE); // Date separator
define("EW_UNFORMAT_YEAR", 50, TRUE); // Unformat year
define("EW_PROJECT_NAME", "<!--##=sProjVar##-->", TRUE); // Project name
define("EW_CONFIG_FILE_FOLDER", EW_PROJECT_NAME . "<!--##=ew_FolderPath("_security")##-->", TRUE); // Config file name
define("EW_PROJECT_ID", "<!--##=PROJ.ProjID##-->", TRUE); // Project ID (GUID)
$EW_RELATED_PROJECT_ID = "";
$EW_RELATED_LANGUAGE_FOLDER = "";
define("EW_RANDOM_KEY", '<!--##=sRandomKey##-->', TRUE); // Random key for encryption
define("EW_PROJECT_STYLESHEET_FILENAME", "<!--##=GetProjCssFileName()##-->", TRUE); // Project stylesheet file name
define("EW_CHARSET", "<!--##=PROJ.Charset##-->", TRUE); // Project charset
define("EW_EMAIL_CHARSET", EW_CHARSET, TRUE); // Email charset
define("EW_EMAIL_KEYWORD_SEPARATOR", "<!--##=PROJ.GetV("EmailKeywordSeparator")##-->", TRUE); // Email keyword separator
$EW_COMPOSITE_KEY_SEPARATOR = "<!--##=PROJ.GetV("CompositeKeySeparator")##-->"; // Composite key separator
define("EW_HIGHLIGHT_COMPARE", TRUE, TRUE); // Highlight compare mode, TRUE(case-insensitive)|FALSE(case-sensitive)
if (!function_exists('xml_parser_create') && !class_exists("DOMDocument")) die("This script requires PHP XML Parser or DOM.");
define('EW_USE_DOM_XML', ((!function_exists('xml_parser_create') && class_exists("DOMDocument")) || <!--##=ew_Val(PROJ.GetV("UseDomXml"))##-->), TRUE);
if (!isset($ADODB_OUTP)) $ADODB_OUTP = 'ew_SetDebugMsg';
define("EW_FONT_SIZE", <!--##=parseInt(PROJ.BodySize) || 14##-->, TRUE);
define("EW_TMP_IMAGE_FONT", "DejaVuSans", TRUE); // Font for temp files

// Set up font path
$EW_FONT_PATH = realpath('./<!--##=ew_FolderPath("_font")##-->');

// Database connection info
define("EW_USE_ADODB", <!--##=ew_Val(UseADOdb())##-->, TRUE); // Use ADOdb
if (!defined("EW_USE_MYSQLI"))
	define('EW_USE_MYSQLI', extension_loaded("mysqli"), TRUE); // Use MySQLi
$EW_CONN["DB"] = <!--##=SYSTEMFUNCTIONS.DatabaseConnection(DB)##-->;
$EW_CONN[0] = &$EW_CONN["DB"];
<!--##
	for (var i = 1, len = PROJ.LINKDBS.Count(); i <= len; i++) {
		var db = PROJ.LINKDBS.Seq(i);
		var dbid = db.DBID;
##-->
$EW_CONN["<!--##=dbid##-->"] = <!--##=SYSTEMFUNCTIONS.DatabaseConnection(db)##-->;
$EW_CONN[<!--##=i##-->] = &$EW_CONN["<!--##=dbid##-->"];
<!--##
	}
##-->

// Set up database error function
$EW_ERROR_FN = 'ew_ErrorFn';

// ADODB (Access/SQL Server)
define("EW_CODEPAGE", <!--##=PROJ.CodePage##-->, TRUE); // Code page

<!--##
	// Get encoding from project charset (iconv assumed)
	sEncoding = "";
	if (ew_IsNotEmpty(PROJ.GetV("Encoding"))) sEncoding = PROJ.GetV("Encoding");
	if (sEncoding == "") sEncoding = CharsetToIconvEncoding(PROJ.Charset);
##-->
/**
 * Character encoding
 * Note: If you use non English languages, you need to set character encoding
 * for some features. Make sure either iconv functions or multibyte string
 * functions are enabled and your encoding is supported. See PHP manual for
 * details.
 */
define("EW_ENCODING", "<!--##=sEncoding##-->", TRUE); // Character encoding
define("EW_IS_DOUBLE_BYTE", in_array(EW_ENCODING, array("GBK", "BIG5", "SHIFT_JIS")), TRUE); // Double-byte character encoding
define("EW_FILE_SYSTEM_ENCODING", "<!--##=PROJ.GetV("FileSystemEncoding")##-->", TRUE); // File system encoding

// Database
define("EW_IS_MSACCESS", <!--##=ew_Val(bDBMsAccess)##-->, TRUE); // Access
define("EW_IS_MSSQL", <!--##=ew_Val(bDBMsSql)##-->, TRUE); // SQL Server
define("EW_IS_MYSQL", <!--##=ew_Val(bDBMySql)##-->, TRUE); // MySQL
define("EW_IS_POSTGRESQL", <!--##=ew_Val(bDBPostgreSql)##-->, TRUE); // PostgreSQL
define("EW_IS_ORACLE", <!--##=ew_Val(bDBOracle)##-->, TRUE); // Oracle

if (!EW_IS_WINDOWS && (EW_IS_MSACCESS || EW_IS_MSSQL))
	die("Microsoft Access or SQL Server is supported on Windows server only.");

define("EW_DB_QUOTE_START", "<!--##=ew_Quote(DB.DBQuoteS)##-->", TRUE);
define("EW_DB_QUOTE_END", "<!--##=ew_Quote(DB.DBQuoteE)##-->", TRUE);

/**
 * MySQL charset (for SET NAMES statement, not used by default)
 * Note: Read http://dev.mysql.com/doc/refman/5.0/en/charset-connection.html
 * before using this setting.
 */
<!--##
	// Get MySQL charset from project charset
	sEncoding = "";
	if (ew_IsNotEmpty(PROJ.GetV("MySQLCharset"))) sEncoding = PROJ.GetV("MySQLCharset");
	if (sEncoding == "") sEncoding = CharsetToMySqlCharset(PROJ.Charset);
##-->
define("EW_MYSQL_CHARSET", "<!--##=sEncoding##-->", TRUE);

/**
 * Password (MD5 and case-sensitivity)
 * Note: If you enable MD5 password, make sure that the passwords in your
 * user table are stored as MD5 hash (32-character hexadecimal number) of the
 * clear text password. If you also use case-insensitive password, convert the
 * clear text passwords to lower case first before calculating MD5 hash.
 * Otherwise, existing users will not be able to login. MD5 hash is
 * irreversible, password will be reset during password recovery.
 */
define("EW_ENCRYPTED_PASSWORD", <!--##=ew_Val(PROJ.MD5Password)##-->, TRUE); // Use encrypted password
define("EW_CASE_SENSITIVE_PASSWORD", <!--##=ew_Val(PROJ.CaseSensitivePassword)##-->, TRUE); // Case-sensitive password

/**
 * Remove XSS
 * Note: If you want to allow these keywords, remove them from the following EW_XSS_ARRAY at your own risks.
*/
define("EW_REMOVE_XSS", <!--##=ew_Val(PROJ.GetV("RemoveXSS"))##-->, TRUE);
$EW_XSS_ARRAY = array('javascript', 'vbscript', 'expression', '<applet', '<meta', '<xml', '<blink', '<link', '<style', '<script', '<embed', '<object', '<iframe', '<frame', '<frameset', '<ilayer', '<layer', '<bgsound', '<title', '<base',
'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

// Check Token
define("EW_CHECK_TOKEN", <!--##=ew_Val(PROJ.GetV("CheckPostToken"))##-->, TRUE); // Check post token

// Session timeout time
define("EW_SESSION_TIMEOUT", <!--##=PROJ.GetV("SessTimeOut")##-->, TRUE); // Session timeout time (minutes)

// Session keep alive interval
define("EW_SESSION_KEEP_ALIVE_INTERVAL", <!--##=PROJ.GetV("SessionKeepAliveInterval")##-->, TRUE); // Session keep alive interval (seconds)
define("EW_SESSION_TIMEOUT_COUNTDOWN", <!--##=PROJ.GetV("SessionTimeoutCountdown")##-->, TRUE); // Session timeout count down interval (seconds)

// Session names
define("EW_SESSION_STATUS", EW_PROJECT_NAME . "_status", TRUE); // Login status
define("EW_SESSION_USER_NAME", EW_SESSION_STATUS . "_UserName", TRUE); // User name
define("EW_SESSION_USER_LOGIN_TYPE", EW_SESSION_STATUS . "_UserLoginType", TRUE); // User login type
define("EW_SESSION_USER_ID", EW_SESSION_STATUS . "_UserID", TRUE); // User ID
define("EW_SESSION_USER_PROFILE", EW_SESSION_STATUS . "_UserProfile", TRUE); // User profile
define("EW_SESSION_USER_PROFILE_USER_NAME", EW_SESSION_USER_PROFILE . "_UserName", TRUE);
define("EW_SESSION_USER_PROFILE_PASSWORD", EW_SESSION_USER_PROFILE . "_Password", TRUE);
define("EW_SESSION_USER_PROFILE_LOGIN_TYPE", EW_SESSION_USER_PROFILE . "_LoginType", TRUE);
define("EW_SESSION_USER_LEVEL_ID", EW_SESSION_STATUS . "_UserLevel", TRUE); // User Level ID
define("EW_SESSION_USER_LEVEL_LIST", EW_SESSION_STATUS . "_UserLevelList", TRUE); // User Level List
define("EW_SESSION_USER_LEVEL_LIST_LOADED", EW_SESSION_STATUS . "_UserLevelListLoaded", TRUE); // User Level List Loaded
@define("EW_SESSION_USER_LEVEL", EW_SESSION_STATUS . "_UserLevelValue", TRUE); // User Level
define("EW_SESSION_PARENT_USER_ID", EW_SESSION_STATUS . "_ParentUserID", TRUE); // Parent User ID
define("EW_SESSION_SYS_ADMIN", EW_PROJECT_NAME . "_SysAdmin", TRUE); // System admin
define("EW_SESSION_PROJECT_ID", EW_PROJECT_NAME . "_ProjectID", TRUE); // User Level project ID
define("EW_SESSION_AR_USER_LEVEL", EW_PROJECT_NAME . "_arUserLevel", TRUE); // User Level array
define("EW_SESSION_AR_USER_LEVEL_PRIV", EW_PROJECT_NAME . "_arUserLevelPriv", TRUE); // User Level privilege array
define("EW_SESSION_USER_LEVEL_MSG", EW_PROJECT_NAME . "_UserLevelMessage", TRUE); // User Level Message
define("EW_SESSION_SECURITY", EW_PROJECT_NAME . "_Security", TRUE); // Security array
define("EW_SESSION_MESSAGE", EW_PROJECT_NAME . "_Message", TRUE); // System message
define("EW_SESSION_FAILURE_MESSAGE", EW_PROJECT_NAME . "_Failure_Message", TRUE); // System error message
define("EW_SESSION_SUCCESS_MESSAGE", EW_PROJECT_NAME . "_Success_Message", TRUE); // System message
define("EW_SESSION_WARNING_MESSAGE", EW_PROJECT_NAME . "_Warning_Message", TRUE); // Warning message
define("EW_SESSION_INLINE_MODE", EW_PROJECT_NAME . "_InlineMode", TRUE); // Inline mode
define("EW_SESSION_BREADCRUMB", EW_PROJECT_NAME . "_Breadcrumb", TRUE); // Breadcrumb
define("EW_SESSION_TEMP_IMAGES", EW_PROJECT_NAME . "_TempImages", TRUE); // Temp images

<!--##
	sLanguageFolder = ew_FolderPath("_language");
	if (ew_IsNotEmpty(sLanguageFolder)) sLanguageFolder += "/";
	sLanguageFiles = PROJ.LanguageFiles;
	sDefaultLanguageFile = PROJ.DefaultLanguageFile;
	if (sLanguageFiles == "") sLanguageFiles = "english.xml";
	if (sDefaultLanguageFile == "") sDefaultLanguageFile = "english.xml";
	bMultiLanguage = PROJ.MultiLanguage;
	if (bMultiLanguage)
		arLanguageFile = sLanguageFiles.split(",");
	else
		arLanguageFile = sDefaultLanguageFile.split(",");
##-->
// Language settings
define("EW_LANGUAGE_FOLDER", $EW_RELATIVE_PATH . "<!--##=sLanguageFolder##-->", TRUE);
$EW_LANGUAGE_FILE = array();
<!--##
	for (var i = 0; i < arLanguageFile.length; i++) {
		sFile = ew_Dequote(arLanguageFile[i]);
		sLanguageId = LANGUAGE.GetFileId(sFile);
		if (i == 0 || sFile == sDefaultLanguageFile)
			sDefaultLanguageId = sLanguageId;
##-->
$EW_LANGUAGE_FILE[] = array("<!--##=ew_Quote(sLanguageId)##-->", "", "<!--##=ew_Quote(sFile)##-->");
<!--##
	} // Language
##-->
define("EW_LANGUAGE_DEFAULT_ID", "<!--##=sDefaultLanguageId##-->", TRUE);
define("EW_SESSION_LANGUAGE_ID", EW_PROJECT_NAME . "_LanguageId", TRUE); // Language ID

// Page Token
define("EW_TOKEN_NAME", "token", TRUE); // DO NOT CHANGE!
define("EW_SESSION_TOKEN", EW_PROJECT_NAME . "_Token", TRUE);

// Data types
define("EW_DATATYPE_NUMBER", 1, TRUE);
define("EW_DATATYPE_DATE", 2, TRUE);
define("EW_DATATYPE_STRING", 3, TRUE);
define("EW_DATATYPE_BOOLEAN", 4, TRUE);
define("EW_DATATYPE_MEMO", 5, TRUE);
define("EW_DATATYPE_BLOB", 6, TRUE);
define("EW_DATATYPE_TIME", 7, TRUE);
define("EW_DATATYPE_GUID", 8, TRUE);
define("EW_DATATYPE_XML", 9, TRUE);
define("EW_DATATYPE_OTHER", 10, TRUE);

// Row types
define("EW_ROWTYPE_HEADER", 0, TRUE); // Row type header
define("EW_ROWTYPE_VIEW", 1, TRUE); // Row type view
define("EW_ROWTYPE_ADD", 2, TRUE); // Row type add
define("EW_ROWTYPE_EDIT", 3, TRUE); // Row type edit
define("EW_ROWTYPE_SEARCH", 4, TRUE); // Row type search
define("EW_ROWTYPE_MASTER", 5, TRUE); // Row type master record
define("EW_ROWTYPE_AGGREGATEINIT", 6, TRUE); // Row type aggregate init
define("EW_ROWTYPE_AGGREGATE", 7, TRUE); // Row type aggregate

// List actions
define("EW_ACTION_POSTBACK", "P", TRUE); // Post back
define("EW_ACTION_AJAX", "A", TRUE); // Ajax
define("EW_ACTION_MULTIPLE", "M", TRUE); // Multiple records
define("EW_ACTION_SINGLE", "S", TRUE); // Single record

// Table parameters
define("EW_TABLE_PREFIX", "<!--##=pfxUserLevel##-->", TRUE);
define("EW_TABLE_REC_PER_PAGE", "recperpage", TRUE); // Records per page
define("EW_TABLE_START_REC", "start", TRUE); // Start record
define("EW_TABLE_PAGE_NO", "pageno", TRUE); // Page number
define("EW_TABLE_BASIC_SEARCH", "psearch", TRUE); // Basic search keyword
define("EW_TABLE_BASIC_SEARCH_TYPE","psearchtype", TRUE); // Basic search type
define("EW_TABLE_ADVANCED_SEARCH", "advsrch", TRUE); // Advanced search
define("EW_TABLE_SEARCH_WHERE", "searchwhere", TRUE); // Search where clause
define("EW_TABLE_WHERE", "where", TRUE); // Table where
define("EW_TABLE_WHERE_LIST", "where_list", TRUE); // Table where (list page)
define("EW_TABLE_ORDER_BY", "orderby", TRUE); // Table order by
define("EW_TABLE_ORDER_BY_LIST", "orderby_list", TRUE); // Table order by (list page)
define("EW_TABLE_SORT", "sort", TRUE); // Table sort
define("EW_TABLE_KEY", "key", TRUE); // Table key
define("EW_TABLE_SHOW_MASTER", "showmaster", TRUE); // Table show master
define("EW_TABLE_SHOW_DETAIL", "showdetail", TRUE); // Table show detail
define("EW_TABLE_MASTER_TABLE", "mastertable", TRUE); // Master table
define("EW_TABLE_DETAIL_TABLE", "detailtable", TRUE); // Detail table
define("EW_TABLE_RETURN_URL", "return", TRUE); // Return URL
define("EW_TABLE_EXPORT_RETURN_URL", "exportreturn", TRUE); // Export return URL
define("EW_TABLE_GRID_ADD_ROW_COUNT", "gridaddcnt", TRUE); // Grid add row count

// Audit Trail
define("EW_AUDIT_TRAIL_TO_DATABASE", <!--##=ew_Val(PROJ.GetV("AuditTrailToDB"))##-->, TRUE); // Write audit trail to DB
<!--##
	var sAuditTrailTable = PROJ.GetV("AuditTrailTable");
	var sAuditTrailTableVar = sAuditTrailTable;
	var sAuditTrailDBID = "DB";
	if (DB.Tables.TableExist(sAuditTrailTable)) {
		ew_SetDb(sAuditTrailTable);
		var AUDITTRAILTABLE = DB.Tables(sAuditTrailTable);
		sAuditTrailTable = SqlTableName(AUDITTRAILTABLE);
		sAuditTrailTableVar = AUDITTRAILTABLE.TblVar;
		sAuditTrailDBID = gsDbId;
	}
##-->
define("EW_AUDIT_TRAIL_DBID", "<!--##=ew_Quote(sAuditTrailDBID)##-->", TRUE); // Audit trail DBID
define("EW_AUDIT_TRAIL_TABLE_NAME", "<!--##=ew_Quote2(sAuditTrailTable)##-->", TRUE); // Audit trail table name
define("EW_AUDIT_TRAIL_TABLE_VAR", "<!--##=ew_Quote2(sAuditTrailTableVar)##-->", TRUE); // Audit trail table var
define("EW_AUDIT_TRAIL_FIELD_NAME_DATETIME", "<!--##=ew_Quote2(PROJ.GetV("AuditTrailFieldDateTime"))##-->", TRUE); // Audit trail DateTime field name
define("EW_AUDIT_TRAIL_FIELD_NAME_SCRIPT", "<!--##=ew_Quote2(PROJ.GetV("AuditTrailFieldScript"))##-->", TRUE); // Audit trail Script field name
define("EW_AUDIT_TRAIL_FIELD_NAME_USER", "<!--##=ew_Quote2(PROJ.GetV("AuditTrailFieldUser"))##-->", TRUE); // Audit trail User field name
define("EW_AUDIT_TRAIL_FIELD_NAME_ACTION", "<!--##=ew_Quote2(PROJ.GetV("AuditTrailFieldAction"))##-->", TRUE); // Audit trail Action field name
define("EW_AUDIT_TRAIL_FIELD_NAME_TABLE", "<!--##=ew_Quote2(PROJ.GetV("AuditTrailFieldTable"))##-->", TRUE); // Audit trail Table field name
define("EW_AUDIT_TRAIL_FIELD_NAME_FIELD", "<!--##=ew_Quote2(PROJ.GetV("AuditTrailFieldField"))##-->", TRUE); // Audit trail Field field name
define("EW_AUDIT_TRAIL_FIELD_NAME_KEYVALUE", "<!--##=ew_Quote2(PROJ.GetV("AuditTrailFieldKeyValue"))##-->", TRUE); // Audit trail Key Value field name
define("EW_AUDIT_TRAIL_FIELD_NAME_OLDVALUE", "<!--##=ew_Quote2(PROJ.GetV("AuditTrailFieldOldValue"))##-->", TRUE); // Audit trail Old Value field name
define("EW_AUDIT_TRAIL_FIELD_NAME_NEWVALUE", "<!--##=ew_Quote2(PROJ.GetV("AuditTrailFieldNewValue"))##-->", TRUE); // Audit trail New Value field name

// Security
define("EW_ADMIN_USER_NAME", "<!--##=ew_Quote2(PROJ.SecLoginID)##-->", TRUE); // Administrator user name
define("EW_ADMIN_PASSWORD", "<!--##=ew_Quote2(PROJ.SecPasswd)##-->", TRUE); // Administrator password
define("EW_USE_CUSTOM_LOGIN", TRUE, TRUE); // Use custom login
define("EW_ALLOW_LOGIN_BY_URL", <!--##=ew_Val(PROJ.GetV("AllowLoginByUrl"))##-->, TRUE); // Allow login by URL
define("EW_ALLOW_LOGIN_BY_SESSION", <!--##=ew_Val(PROJ.GetV("AllowLoginBySession"))##-->, TRUE); // Allow login by session variables
define("EW_PHPASS_ITERATION_COUNT_LOG2", "[10,8]", TRUE); // Note: Use JSON array syntax

<!--## if (bDynamicUserLevel) { ##-->
<!--##
	var sUserLevelTbl = DB.UserLevelTbl;
	var sUserLevelDBID = "DB";
	if (DB.Tables.TableExist(sUserLevelTbl)) {
		ew_SetDb(sUserLevelTbl);
		sUserLevelTbl = SqlTableName(DB.Tables(sUserLevelTbl));
		sUserLevelDBID = gsDbId;
	}
##-->
// Dynamic User Level settings
// User level definition table/field names
@define("EW_USER_LEVEL_DBID", "<!--##=ew_Quote(sUserLevelDBID)##-->", TRUE);
@define("EW_USER_LEVEL_TABLE", "<!--##=ew_Quote2(sUserLevelTbl)##-->", TRUE);
@define("EW_USER_LEVEL_ID_FIELD", "<!--##=ew_Quote2(ew_QuotedName(DB.UserLevelIdFld))##-->", TRUE);
@define("EW_USER_LEVEL_NAME_FIELD", "<!--##=ew_Quote2(ew_QuotedName(DB.UserLevelNameFld))##-->", TRUE);

<!--##
	var sUserLevelPrivTbl = DB.UserLevelPrivTbl;
	var sUserLevelPrivDBID = "DB";
	if (DB.Tables.TableExist(sUserLevelPrivTbl)) {
		ew_SetDb(sUserLevelPrivTbl);
		sUserLevelPrivTbl = SqlTableName(DB.Tables(sUserLevelPrivTbl));
		sUserLevelPrivDBID = gsDbId;
	}
##-->
// User Level privileges table/field names
@define("EW_USER_LEVEL_PRIV_DBID", "<!--##=ew_Quote(sUserLevelPrivDBID)##-->", TRUE);
@define("EW_USER_LEVEL_PRIV_TABLE", "<!--##=ew_Quote2(sUserLevelPrivTbl)##-->", TRUE);
@define("EW_USER_LEVEL_PRIV_TABLE_NAME_FIELD", "<!--##=ew_Quote2(ew_QuotedName(DB.UserLevelPrivTblNameFld))##-->", TRUE);
@define("EW_USER_LEVEL_PRIV_TABLE_NAME_FIELD_2", "<!--##=ew_Quote2(DB.UserLevelPrivTblNameFld)##-->", TRUE);
@define("EW_USER_LEVEL_PRIV_TABLE_NAME_FIELD_SIZE", 255, TRUE);
@define("EW_USER_LEVEL_PRIV_USER_LEVEL_ID_FIELD", "<!--##=ew_Quote2(ew_QuotedName(DB.UserLevelPrivUserLevelFld))##-->", TRUE);
@define("EW_USER_LEVEL_PRIV_PRIV_FIELD", "<!--##=ew_Quote2(ew_QuotedName(DB.UserLevelPrivPrivFld))##-->", TRUE);
<!--## } ##-->

// User level constants
<!--## if (!PROJ.GetV("NoUserLevelCompat")) { ##-->
define("EW_USER_LEVEL_COMPAT", TRUE, TRUE); // Use old User Level values. Comment out to use new User Level values (separate values for View/Search).
<!--## } ##-->
define("EW_ALLOW_ADD", 1, TRUE); // Add
define("EW_ALLOW_DELETE", 2, TRUE); // Delete
define("EW_ALLOW_EDIT", 4, TRUE); // Edit
@define("EW_ALLOW_LIST", 8, TRUE); // List
if (defined("EW_USER_LEVEL_COMPAT")) {
	define("EW_ALLOW_VIEW", 8, TRUE); // View
	define("EW_ALLOW_SEARCH", 8, TRUE); // Search
} else {
	define("EW_ALLOW_VIEW", 32, TRUE); // View
	define("EW_ALLOW_SEARCH", 64, TRUE); // Search
}
@define("EW_ALLOW_REPORT", 8, TRUE); // Report
@define("EW_ALLOW_ADMIN", 16, TRUE); // Admin

// Hierarchical User ID
@define("EW_USER_ID_IS_HIERARCHICAL", <!--##=ew_Val(PROJ.GetV("HierarchicalUserID"))##-->, TRUE); // Change to FALSE to show one level only

// Use subquery for master/detail
define("EW_USE_SUBQUERY_FOR_MASTER_USER_ID", <!--##=ew_Val(PROJ.GetV("MasterUserIDUseSubquery"))##-->, TRUE);

<!--##
	var defaultUserIDAllow = 8 + 32 + 64;
##-->
define("EW_USER_ID_ALLOW", <!--##=defaultUserIDAllow##-->, TRUE);

// User table filters
<!--##
if (bUserTable) {
	ew_SetDb(SECTABLE.TblName);
	var sUserTableDBID = gsDbId;
	if (SECTABLE.TblType == "CUSTOMVIEW")
		sUserTable = ew_SQLPart(SECTABLE.TblCustomSQL, "FROM");
	else
		sUserTable = SqlTableName(SECTABLE);
	if (ew_IsNotEmpty(PROJ.SecLoginIDFld)) {
		FIELD = SECTABLE.Fields(PROJ.SecLoginIDFld);
		sFld = ew_FieldSqlName(FIELD);
		sFldQuoteS = FIELD.FldQuoteS;
		sFldQuoteE = FIELD.FldQuoteE;
		sFilter = "(" + sFld + " = " + sFldQuoteS + "%u" + sFldQuoteE + ")";
	} else {
		sFilter = "";
	}
##-->
define("EW_USER_TABLE_DBID", "<!--##=ew_Quote(sUserTableDBID)##-->", TRUE);
define("EW_USER_TABLE", "<!--##=ew_Quote2(sUserTable)##-->", TRUE);
define("EW_USER_NAME_FILTER", "<!--##=ew_Quote2(sFilter)##-->", TRUE);
<!--##
	if (ew_IsNotEmpty(DB.SecuUserIDFld)) {
		FIELD = SECTABLE.Fields(DB.SecuUserIDFld);
		sFld = ew_FieldSqlName(FIELD);
		sFldQuoteS = FIELD.FldQuoteS;
		sFldQuoteE = FIELD.FldQuoteE;
		sFilter = "(" + sFld + " = " + sFldQuoteS + "%u" + sFldQuoteE + ")";
	} else {
		sFilter = "";
	}
##-->
define("EW_USER_ID_FILTER", "<!--##=ew_Quote2(sFilter)##-->", TRUE);
<!--##
	if (ew_IsNotEmpty(PROJ.SecEmailFld)) {
		FIELD = SECTABLE.Fields(PROJ.SecEmailFld);
		sFld = ew_FieldSqlName(FIELD);
		sFldQuoteS = FIELD.FldQuoteS;
		sFldQuoteE = FIELD.FldQuoteE;
		sFilter = "(" + sFld + " = " + sFldQuoteS + "%e" + sFldQuoteE + ")";
	} else {
		sFilter = "";
	}
##-->
define("EW_USER_EMAIL_FILTER", "<!--##=ew_Quote2(sFilter)##-->", TRUE);
<!--##
	if (PROJ.SecRegisterActivate && ew_IsNotEmpty(PROJ.SecRegisterActivateFld)) {
		FIELD = SECTABLE.Fields(PROJ.SecRegisterActivateFld);
		sFld = ew_FieldSqlName(FIELD);
		sFldQuoteS = FIELD.FldQuoteS;
		sFldQuoteE = FIELD.FldQuoteE;
		sFldValue = ActivateFieldValue(SECTABLE, FIELD);
		sFilter = "(" + sFld + " = " + sFldQuoteS + sFldValue + sFldQuoteE + ")";
	} else {
		sFilter = "";
	}
##-->
define("EW_USER_ACTIVATE_FILTER", "<!--##=ew_Quote2(sFilter)##-->", TRUE);
<!--##
	if (ew_IsNotEmpty(DB.SecUserProfileFld)) {
		FIELD = SECTABLE.Fields(DB.SecUserProfileFld);
		sProfileFld = FIELD.FldName;
##-->
define("EW_USER_PROFILE_FIELD_NAME", "<!--##=ew_Quote2(sProfileFld)##-->", TRUE);
<!--##
	}
}
##-->

// User Profile Constants
define("EW_USER_PROFILE_KEY_SEPARATOR", "<!--##=PROJ.GetV("UserProfileKeySeparator")##-->", TRUE);
define("EW_USER_PROFILE_FIELD_SEPARATOR", "<!--##=PROJ.GetV("UserProfileFieldSeparator")##-->", TRUE);
define("EW_USER_PROFILE_SESSION_ID", "SessionID", TRUE);
define("EW_USER_PROFILE_LAST_ACCESSED_DATE_TIME", "LastAccessedDateTime", TRUE);
define("EW_USER_PROFILE_CONCURRENT_SESSION_COUNT", <!--##=PROJ.GetV("ConcurrentUserSessionCount")##-->, TRUE); // Maximum sessions allowed
define("EW_USER_PROFILE_SESSION_TIMEOUT", <!--##=PROJ.GetV("UserProfileSessionTimeout")##-->, TRUE);
define("EW_USER_PROFILE_LOGIN_RETRY_COUNT", "LoginRetryCount", TRUE);
define("EW_USER_PROFILE_LAST_BAD_LOGIN_DATE_TIME", "LastBadLoginDateTime", TRUE);
define("EW_USER_PROFILE_MAX_RETRY", <!--##=PROJ.GetV("UserProfileMaxRetry")##-->, TRUE);
define("EW_USER_PROFILE_RETRY_LOCKOUT", <!--##=PROJ.GetV("UserProfileRetryLockout")##-->, TRUE);
define("EW_USER_PROFILE_LAST_PASSWORD_CHANGED_DATE", "LastPasswordChangedDate", TRUE);
define("EW_USER_PROFILE_PASSWORD_EXPIRE", <!--##=PROJ.GetV("UserProfilePasswordExpire")##-->, TRUE);
define("EW_USER_PROFILE_LANGUAGE_ID", "LanguageId", TRUE);

// Email
define("EW_SMTP_SERVER", "<!--##=sSmtpServer##-->", TRUE); // SMTP server
define("EW_SMTP_SERVER_PORT", <!--##=iSmtpServerPort##-->, TRUE); // SMTP server port
define("EW_SMTP_SECURE_OPTION", "<!--##=PROJ.SmtpSecureOption.toLowerCase()##-->", TRUE);
define("EW_SMTP_SERVER_USERNAME", "<!--##=ew_Quote2(PROJ.SMTPServerUsername)##-->", TRUE); // SMTP server user name
define("EW_SMTP_SERVER_PASSWORD", "<!--##=ew_Quote2(PROJ.SMTPServerPassword)##-->", TRUE); // SMTP server password
define("EW_SENDER_EMAIL", "<!--##=PROJ.SecSenderEmail##-->", TRUE); // Sender email address
define("EW_RECIPIENT_EMAIL", "<!--##=PROJ.RecipientEmail##-->", TRUE); // Recipient email address
define("EW_MAX_EMAIL_RECIPIENT", <!--##=PROJ.GetV("MaxEmailRecipient")##-->, TRUE);
define("EW_MAX_EMAIL_SENT_COUNT", <!--##=PROJ.GetV("MaxEmailSentCount")##-->, TRUE);
define("EW_EXPORT_EMAIL_COUNTER", EW_SESSION_STATUS . "_EmailCounter", TRUE);

define("EW_EMAIL_CHANGEPWD_TEMPLATE", "changepwd.html", TRUE);
define("EW_EMAIL_FORGOTPWD_TEMPLATE", "forgotpwd.html", TRUE);
define("EW_EMAIL_NOTIFY_TEMPLATE", "notify.html", TRUE);
define("EW_EMAIL_REGISTER_TEMPLATE", "register.html", TRUE);
define("EW_EMAIL_RESETPWD_TEMPLATE", "resetpwd.html", TRUE);
define("EW_EMAIL_TEMPLATE_PATH", "<!--##=ew_FolderPath("_html")##-->", TRUE); // Template path

// File upload
define("EW_UPLOAD_TEMP_PATH", "", TRUE); // Upload temp path (absolute)
define("EW_UPLOAD_DEST_PATH", "<!--##=PROJ.UploadPath##-->", TRUE); // Upload destination path (relative to app root)
define("EW_UPLOAD_URL", "<!--##=sUploadUrl##-->", TRUE); // Upload URL
define("EW_UPLOAD_TEMP_FOLDER_PREFIX", "temp__", TRUE); // Upload temp folders prefix
define("EW_UPLOAD_TEMP_FOLDER_TIME_LIMIT", 1440, TRUE); // Upload temp folder time limit (minutes)
define("EW_UPLOAD_THUMBNAIL_FOLDER", "thumbnail", TRUE); // Temporary thumbnail folder
define("EW_UPLOAD_THUMBNAIL_WIDTH", <!--##=iUploadThumbnailWidth##-->, TRUE); // Temporary thumbnail max width
define("EW_UPLOAD_THUMBNAIL_HEIGHT", <!--##=iUploadThumbnailHeight##-->, TRUE); // Temporary thumbnail max height
define("EW_UPLOAD_ALLOWED_FILE_EXT", "<!--##=PROJ.UploadAllowedFileExt##-->", TRUE); // Allowed file extensions
define("EW_IMAGE_ALLOWED_FILE_EXT", "gif,jpg,png,bmp", TRUE); // Allowed file extensions for images
define("EW_DOWNLOAD_ALLOWED_FILE_EXT", "pdf,xls,doc,xlsx,docx", TRUE); // Allowed file extensions for download (non-image)
define("EW_ENCRYPT_FILE_PATH", <!--##=ew_Val(PROJ.GetV("EncryptFilePath"))##-->, TRUE); // Encrypt file path
define("EW_MAX_FILE_SIZE", <!--##=DB.MaxUploadSize##-->, TRUE); // Max file size
define("EW_MAX_FILE_COUNT", 0, TRUE); // Max file count
define("EW_THUMBNAIL_DEFAULT_WIDTH", <!--##=PROJ.GetV("ThumbnailDefaultWidth")##-->, TRUE); // Thumbnail default width
define("EW_THUMBNAIL_DEFAULT_HEIGHT", <!--##=PROJ.GetV("ThumbnailDefaultHeight")##-->, TRUE); // Thumbnail default height
define("EW_THUMBNAIL_DEFAULT_QUALITY", 100, TRUE); // Thumbnail default qualtity (JPEG)
define("EW_UPLOADED_FILE_MODE", 0666, TRUE); // Uploaded file mode
define("EW_UPLOAD_TMP_PATH", "", TRUE); // User upload temp path (relative to app root) e.g. "tmp/"
define("EW_UPLOAD_CONVERT_ACCENTED_CHARS", FALSE, TRUE); // Convert accented chars in upload file name
define("EW_USE_COLORBOX", <!--##=ew_Val(PROJ.GetV("UseColorbox"))##-->, TRUE); // Use Colorbox
define("EW_MULTIPLE_UPLOAD_SEPARATOR", "<!--##=PROJ.GetV("UploadMultipleSeparator")##-->", TRUE); // Multiple upload separator

// Image resize
$EW_THUMBNAIL_CLASS = "cThumbnail";
define("EW_REDUCE_IMAGE_ONLY", <!--##=ew_Val(bReduceImageOnly)##-->, TRUE);
define("EW_KEEP_ASPECT_RATIO", <!--##=ew_Val(bKeepAspectRatio)##-->, TRUE);
$EW_RESIZE_OPTIONS = array("keepAspectRatio" => EW_KEEP_ASPECT_RATIO, "resizeUp" => !EW_REDUCE_IMAGE_ONLY, "jpegQuality" => EW_THUMBNAIL_DEFAULT_QUALITY);

// Audit trail
define("EW_AUDIT_TRAIL_PATH", "<!--##=ew_Quote(PROJ.AuditTrailPath)##-->", TRUE); // Audit trail path (relative to app root)

// Export records
define("EW_EXPORT_ALL", TRUE, TRUE); // Export all records
define("EW_EXPORT_ALL_TIME_LIMIT", <!--##=PROJ.GetV("ExportAllTimeLimit")##-->, TRUE); // Export all records time limit
define("EW_XML_ENCODING", "utf-8", TRUE); // Encoding for Export to XML
define("EW_EXPORT_ORIGINAL_VALUE", <!--##=ew_Val(PROJ.GetV("ExportOriginalValues"))##-->, TRUE);
define("EW_EXPORT_FIELD_CAPTION", <!--##=ew_Val(PROJ.GetV("ExportFieldCaption"))##-->, TRUE); // TRUE to export field caption
define("EW_EXPORT_CSS_STYLES", <!--##=ew_Val(PROJ.GetV("ExportCssStyles"))##-->, TRUE); // TRUE to export CSS styles
define("EW_EXPORT_MASTER_RECORD", <!--##=ew_Val(PROJ.GetV("ExportMasterRecord"))##-->, TRUE); // TRUE to export master record
define("EW_EXPORT_MASTER_RECORD_FOR_CSV", <!--##=ew_Val(PROJ.GetV("ExportMasterRecordForCsv"))##-->, TRUE); // TRUE to export master record for CSV
define("EW_EXPORT_DETAIL_RECORDS", <!--##=ew_Val(PROJ.GetV("ExportDetailRecords"))##-->, TRUE); // TRUE to export detail records
define("EW_EXPORT_DETAIL_RECORDS_FOR_CSV", <!--##=ew_Val(PROJ.GetV("ExportDetailRecordsForCsv"))##-->, TRUE); // TRUE to export detail records for CSV
$EW_EXPORT = array(
	"email" => "cExportEmail",
	"html" => "cExportHtml",
	"word" => "cExportWord",
	"excel" => "cExportExcel",
	"pdf" => "cExportPdf",
	"csv" => "cExportCsv",
	"xml" => "cExportXml"
);

// Export records for reports
$EW_EXPORT_REPORT = array(
	"print" => "ExportReportHtml",
	"html" => "ExportReportHtml",
	"word" => "ExportReportWord",
	"excel" => "ExportReportExcel"
);

// MIME types
$EW_MIME_TYPES = array(
	"pdf"	=>	"application/pdf",
	"exe"	=>	"application/octet-stream",
	"zip"	=>	"application/zip",
	"doc"	=>	"application/msword",
	"docx"	=>	"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
	"xls"	=>	"application/vnd.ms-excel",
	"xlsx"	=>	"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
	"ppt"	=>	"application/vnd.ms-powerpoint",
	"pptx"	=>	"application/vnd.openxmlformats-officedocument.presentationml.presentation",
	"gif"	=>	"image/gif",
	"png"	=>	"image/png",
	"jpeg"	=>	"image/jpeg",
	"jpg"	=>	"image/jpeg",
	"mp3"	=>	"audio/mpeg",
	"wav"	=>	"audio/x-wav",
	"mpeg"	=>	"video/mpeg",
	"mpg"	=>	"video/mpeg",
	"mpe"	=>	"video/mpeg",
	"mov"	=>	"video/quicktime",
	"avi"	=>	"video/x-msvideo",
	"3gp"	=>	"video/3gpp",
	"css"	=>	"text/css",
	"js"	=>	"application/javascript",
	"htm"	=>	"text/html",
	"html"	=>	"text/html"
);

// Boolean html attributes
$EW_BOOLEAN_HTML_ATTRIBUTES = array("checked", "compact", "declare", "defer", "disabled", "ismap", "multiple", "nohref", "noresize", "noshade", "nowrap", "readonly", "selected");

// Use token in URL (reserved, not used, do NOT change!)
define("EW_USE_TOKEN_IN_URL", FALSE, TRUE);

// Use ILIKE for PostgreSql
define("EW_USE_ILIKE_FOR_POSTGRESQL", <!--##=ew_Val(PROJ.GetV("PostgreSqlUseIlike"))##-->, TRUE);

// Use collation for MySQL
define("EW_LIKE_COLLATION_FOR_MYSQL", "<!--##=ew_Quote(PROJ.GetV("MySqlLikeCollation"))##-->", TRUE);

// Use collation for MsSQL
define("EW_LIKE_COLLATION_FOR_MSSQL", "<!--##=ew_Quote(PROJ.GetV("MsSqlLikeCollation"))##-->", TRUE);

// Null / Not Null values
define("EW_NULL_VALUE", "##null##", TRUE);
define("EW_NOT_NULL_VALUE", "##notnull##", TRUE);

/**
 * Search multi value option
 * 1 - no multi value
 * 2 - AND all multi values
 * 3 - OR all multi values
*/
define("EW_SEARCH_MULTI_VALUE_OPTION", <!--##=PROJ.GetV("SearchMultiValueOption")##-->, TRUE);

// Quick search
define("EW_BASIC_SEARCH_IGNORE_PATTERN", "/[\?,\.\^\*\(\)\[\]\\\"]/", TRUE); // Ignore special characters
define("EW_BASIC_SEARCH_ANY_FIELDS", <!--##=ew_Val(PROJ.GetV("BasicSearchAnyFields"))##-->, TRUE); // Search "All keywords" in any selected fields

// Validate option
define("EW_CLIENT_VALIDATE", <!--##=ew_Val(PROJ.ClientValidate)##-->, TRUE);
define("EW_SERVER_VALIDATE", <!--##=ew_Val(PROJ.ServerValidate)##-->, TRUE);

// Blob field byte count for hash value calculation
define("EW_BLOB_FIELD_BYTE_COUNT", <!--##=PROJ.GetV("BlobFieldByteCount")##-->, TRUE);

// Auto suggest max entries
define("EW_AUTO_SUGGEST_MAX_ENTRIES", <!--##=PROJ.GetV("AutoSuggestMaxEntries")##-->, TRUE);

// Auto fill original value
define("EW_AUTO_FILL_ORIGINAL_VALUE", <!--##=PROJ.GetV("AutoFillOriginalValue")##-->, TRUE);

// Checkbox and radio button groups
define("EW_ITEM_TEMPLATE_CLASSNAME", "ewTemplate", TRUE);
define("EW_ITEM_TABLE_CLASSNAME", "ewItemTable", TRUE);

// Use responsive layout
$EW_USE_RESPONSIVE_LAYOUT = <!--##=ew_Val(PROJ.GetV("UseResponsiveLayout"))##-->;

// Use css flip
define("EW_CSS_FLIP", <!--##=ew_Val(PROJ.GetV("UseCssFlip"))##-->, TRUE);

// Time zone
$DEFAULT_TIME_ZONE = "<!--##=PROJ.GetV("DefaultTimeZone")##-->";

/**
 * Numeric and monetary formatting options
 * Note: DO NOT CHANGE THE FOLLOWING $DEFAULT_* VARIABLES!
 * If you want to use custom settings, customize the language file,
 * set "use_system_locale" to "0" to override localeconv and customize the
 * phrases under the <locale> node for ew_FormatCurrency/Number/Percent functions
 * Also read http://www.php.net/localeconv for description of the constants
*/
$DEFAULT_LOCALE = json_decode('{"decimal_point":".","thousands_sep":"","int_curr_symbol":"$","currency_symbol":"$","mon_decimal_point":".","mon_thousands_sep":"","positive_sign":"","negative_sign":"-","int_frac_digits":2,"frac_digits":2,"p_cs_precedes":1,"p_sep_by_space":0,"n_cs_precedes":1,"n_sep_by_space":0,"p_sign_posn":1,"n_sign_posn":1}', TRUE); 
$DEFAULT_DECIMAL_POINT = &$DEFAULT_LOCALE["decimal_point"];
$DEFAULT_THOUSANDS_SEP = &$DEFAULT_LOCALE["thousands_sep"];
$DEFAULT_CURRENCY_SYMBOL = &$DEFAULT_LOCALE["currency_symbol"];
$DEFAULT_MON_DECIMAL_POINT = &$DEFAULT_LOCALE["mon_decimal_point"];
$DEFAULT_MON_THOUSANDS_SEP = &$DEFAULT_LOCALE["mon_thousands_sep"];
$DEFAULT_POSITIVE_SIGN = &$DEFAULT_LOCALE["positive_sign"];
$DEFAULT_NEGATIVE_SIGN = &$DEFAULT_LOCALE["negative_sign"];
$DEFAULT_FRAC_DIGITS = &$DEFAULT_LOCALE["frac_digits"];
$DEFAULT_P_CS_PRECEDES = &$DEFAULT_LOCALE["p_cs_precedes"];
$DEFAULT_P_SEP_BY_SPACE = &$DEFAULT_LOCALE["p_sep_by_space"];
$DEFAULT_N_CS_PRECEDES = &$DEFAULT_LOCALE["n_cs_precedes"];
$DEFAULT_N_SEP_BY_SPACE = &$DEFAULT_LOCALE["n_sep_by_space"];
$DEFAULT_P_SIGN_POSN = &$DEFAULT_LOCALE["p_sign_posn"];
$DEFAULT_N_SIGN_POSN = &$DEFAULT_LOCALE["n_sign_posn"];
<!--## if (!bMultiLanguage && PROJ.SetLocale && ew_IsNotEmpty(PROJ.Locale)) { ##-->
define("EW_DEFAULT_LOCALE", '<!--##=SQuote(PROJ.Locale)##-->', TRUE);
if (!json_decode(EW_DEFAULT_LOCALE)) // String, not JSON
	@setlocale(LC_ALL, EW_DEFAULT_LOCALE);
<!--## } ##-->

// Cookies
define("EW_COOKIE_EXPIRY_TIME", time() + 365*24*60*60, TRUE); // Change cookie expiry time here

<!--## if (!bMultiLanguage) { ##-->
/**
 * Time zone
 * Read http://www.php.net/date_default_timezone_set for details
 * and http://www.php.net/timezones for supported time zones
 */
// Set up time zone for non-multi-language site
if (function_exists("date_default_timezone_set"))
	date_default_timezone_set($DEFAULT_TIME_ZONE);
<!--## } ##-->

// Client variables
$EW_CLIENT_VAR = array();

//
// Global variables
//
if (!isset($conn)) {

	// Common objects
	$conn = NULL; // Connection
	$Page = NULL; // Page
	$UserTable = NULL; // User table
	$UserTableConn = NULL; // User table connection
	$Table = NULL; // Main table
	$Grid = NULL; // Grid page object
	$Language = NULL; // Language
	$Security = NULL; // Security
	$UserProfile = NULL; // User profile
	$objForm = NULL; // Form
	
	// Current language
	$gsLanguage = "";
	
	// Token
	$gsToken = "";

	// Used by ValidateForm/ValidateSearch
	$gsFormError = ""; // Form error message
	$gsSearchError = ""; // Search form error message
	
	// Used by *master.php
	$gsMasterReturnUrl = "";
	
	// Used by header.php, export checking
	$gsExport = "";
	$gsExportFile = "";
	$gsCustomExport = "";
	
	// Used by header.php/footer.php, skip header/footer checking
	$gbSkipHeaderFooter = FALSE;
	$gbOldSkipHeaderFooter = $gbSkipHeaderFooter;

	// Email error message
	$gsEmailErrDesc = "";
	
	// Debug message
	$gsDebugMsg = "";
	
	// Debug timer
	$gTimer = NULL;

	// Keep temp images name for PDF export for delete
	$gTmpImages = array();
}

// Mobile detect
$MobileDetect = NULL;

// Breadcrumb
$Breadcrumb = NULL;
?>
<!--##/session##-->

<!--##session ewconfigmenu##-->
<?php
// Menu
define("EW_MENUBAR_ID", "RootMenu", TRUE);
define("EW_MENUBAR_BRAND", "", TRUE);
define("EW_MENUBAR_BRAND_HYPERLINK", "", TRUE);
define("EW_MENUBAR_CLASSNAME", "", TRUE);
//define("EW_MENU_CLASSNAME", "nav nav-list", TRUE);
define("EW_MENU_CLASSNAME", "dropdown-menu", TRUE);
define("EW_SUBMENU_CLASSNAME", "dropdown-menu", TRUE);
define("EW_SUBMENU_DROPDOWN_IMAGE", "", TRUE);
define("EW_SUBMENU_DROPDOWN_ICON_CLASSNAME", "", TRUE);
define("EW_MENU_DIVIDER_CLASSNAME", "divider", TRUE);
define("EW_MENU_ITEM_CLASSNAME", "dropdown-submenu", TRUE);
define("EW_SUBMENU_ITEM_CLASSNAME", "dropdown-submenu", TRUE);
define("EW_MENU_ACTIVE_ITEM_CLASS", "active", TRUE);
define("EW_SUBMENU_ACTIVE_ITEM_CLASS", "active", TRUE);
define("EW_MENU_ROOT_GROUP_TITLE_AS_SUBMENU", FALSE, TRUE);
define("EW_SHOW_RIGHT_MENU", FALSE, TRUE);
?>
<!--##/session##-->

<!--##session ewconfigpdf##-->
<?php
define("EW_PDF_STYLESHEET_FILENAME", "", TRUE); // Export PDF CSS styles
?>
<!--##/session##-->