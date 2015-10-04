<!--##session ewdb##-->
<?php
if (!isset($EW_RELATIVE_PATH)) $EW_RELATIVE_PATH = "";
if (!isset($EW_ERROR_FN)) $EW_ERROR_FN = "ew_ErrorFn";
if (!defined("EW_USE_ADODB")) define("EW_USE_ADODB", <!--##=ew_Val(UseADOdb())##-->, TRUE); // Use ADOdb
if (!defined("EW_USE_MYSQLI")) define("EW_USE_MYSQLI", extension_loaded("mysqli"), TRUE); // Use MySQLi
?>
<!--##=SYSTEMFUNCTIONS.IncludeFile("_adodb","")##-->
<?php
/**
 * PHPMaker 12 database helper class
 */

if (!function_exists('DbHelper')) {
	function &DbHelper($dbid = "") {
<!--##
	sDbVar = DB.DBVar;
	var dbcnt = 0;
	for (var i = 1, len = PROJ.LINKDBS.Count(); i <= len; i++) {
		var db = PROJ.LINKDBS.Seq(i);
		var dbid = db.DBID;
		var sDbName = db.DBName;
		var sLinkDbVar = db.DBVar;
		dbcnt += 1;
		var sCond = (dbcnt == 1) ? "if" : "elseif";
##-->
		<!--##=sCond##--> ($dbid == "<!--##=dbid##-->" || $dbid === <!--##=i##-->) // <!--##=dbid##-->
			$dbclass = "c<!--##=sLinkDbVar##-->_db";
<!--##
	}
	if (dbcnt > 0) {
##-->
		else // DB
			$dbclass = "c<!--##=sDbVar##-->_db";
<!--##
	} else { // No link database
##-->
		$dbclass = "c<!--##=sDbVar##-->_db";
<!--##
	}
##-->
		$dbhelper = new $dbclass();
		return $dbhelper;
	}
}

<!--##
	sRelativePathPrefix = "$EW_RELATIVE_PATH . ";

	// Get database list
	var dbs = [];
	dbs[dbs.length] = "DB";
	for (var i = 1, len = PROJ.LINKDBS.Count(); i <= len; i++) {
		var db = PROJ.LINKDBS.Seq(i);
		var dbid = db.DBID;
		dbs[dbs.length] = dbid;
	}

	// Generate all database helpers
	for (var i = 0, len = dbs.length; i < len; i++) {

		var dbid = dbs[i];
		if (dbid == "DB")
			var db = DB;
		else
			var db = PROJ.LINKDBS(dbid);

		// Database parameters
		sDbVar = db.DBVar;
		var sDbType = ew_DbType(dbid);
		var IsDBMsAccess = (sDbType == "ACCESS");
		var IsDBMsSql = (sDbType == "MSSQL");
		var IsDBMySql = (sDbType == "MYSQL");
		var IsDBPostgreSql = (sDbType == "POSTGRESQL");
		var IsDBOracle = (sDbType == "ORACLE");
##-->

class c<!--##=sDbVar##-->_db extends cDbHelper {

	// Database connection info
<!--## if (IsDBMySql || IsDBPostgreSql) { ##-->
	var $Host = '<!--##=db.DBHOST##-->';
	var $Port = <!--##=db.DBPort##-->;
	var $Username = '<!--##=SQuote(db.DBUID)##-->';
	var $Password = '<!--##=SQuote(db.DBPwd)##-->';
	var $DbName = '<!--##=SQuote(db.DBName)##-->';
<!--## } else if (IsDBOracle) { ##-->
	var $Host = FALSE; // Not used
	var $Port = FALSE; // Not used
	var $Username = '<!--##=SQuote(db.DBUID)##-->';
	var $Password = '<!--##=SQuote(db.DBPwd)##-->';
	var $DbName = '<!--##=SQuote(GetOracleServiceName(db.DBConnstr))##-->';
	var $Schema = '<!--##=SQuote(db.DBName)##-->';
<!--## } ##-->

	// ADODB (Access/SQL Server)
	var $CodePage = <!--##=PROJ.CodePage##-->; // Code page

	// Database
	var $StartQuote = "<!--##=ew_Quote(db.DBQuoteS)##-->";
	var $EndQuote = "<!--##=ew_Quote(db.DBQuoteE)##-->";

<!--## if (IsDBMySql) { ##-->
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
	var $MySqlCharset = "<!--##=sEncoding##-->";
<!--## } ##-->

	// Connect to database
	function &Connect($info = NULL) {

	<!--## if (IsDBMsSql || IsDBMsAccess) { ##-->
		if (!(strtolower(substr(PHP_OS, 0, 3)) === 'win')) // Non Windows platform
			die("Microsoft Access or SQL Server is supported on Windows server only.");
	<!--## } ##-->

		$GLOBALS["ADODB_FETCH_MODE"] = ADODB_FETCH_BOTH;
		$GLOBALS["ADODB_COUNTRECS"] = FALSE;

	<!--## if (IsDBMySql) { ##-->
		if (EW_USE_ADODB) {
			if (EW_USE_MYSQLI)
				$conn = ADONewConnection('mysqli');
			else
				$conn = ADONewConnection('mysqlt');
		} else {
			$conn = new mysqlt_driver_ADOConnection();
		}
	<!--## } else if (IsDBPostgreSql) { ##-->
		$conn = ADONewConnection('postgres7');
	<!--## } else if (IsDBMsSql) { ##-->
		$conn = ADONewConnection('ado_mssql');
	<!--## } else if (IsDBMsAccess) { ##-->
		$conn = ADONewConnection('ado_access');
	<!--## } else if (IsDBOracle) { ##-->
		$conn = ADONewConnection('oci805');
		$conn->NLS_DATE_FORMAT = 'RRRR-MM-DD HH24:MI:SS';
	<!--## } ##-->
		$conn->debug = $this->Debug;
		$conn->debug_echo = FALSE;

		if (!$info) {
	<!--## if (IsDBMySql || IsDBPostgreSql || IsDBOracle) { ##-->
			$info = array("host" => $this->Host, "port" => $this->Port,
			"user" => $this->Username, "pass" => $this->Password, "db" => $this->DbName);
		<!--## if (IsDBOracle) { ##-->
			<!--## if (PROJ.GetV("OracleCharset") != "") { ##-->
			$info["charset"] = "<!--##=PROJ.GetV("OracleCharset")##-->";
			<!--## } else { ##-->
			$info["charset"] = $conn->charSet;
			<!--## } ##-->
			$info["schema"] = $this->Schema;
		<!--## } ##-->
	<!--## } else { ##-->
			$info = <!--##=SYSTEMFUNCTIONS.ConnectionString(db)##-->; // ADO connection
	<!--## } ##-->
		}

	<!--## if (IsDBMySql || IsDBPostgreSql || IsDBOracle) { ##-->
		$conn->port = intval($info["port"]);
	<!--## } ##-->
	<!--## if (IsDBOracle) { ##-->
		$conn->charSet = $info["charset"];
	<!--## } ##-->
		if ($this->Debug)
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
	<!--## if (IsDBMySql || IsDBPostgreSql || IsDBOracle) { ##-->
		$conn->Connect($info["host"], $info["user"], $info["pass"], $info["db"]);
	<!--## } ##-->
	<!--## if (IsDBMySql) { ##-->
		if ($this->MySqlCharset <> "")
			$conn->Execute("SET NAMES '" . $this->MySqlCharset . "'");
	<!--## } ##-->
	<!--## if (IsDBMsAccess || IsDBMsSql) { ##-->
		if ($this->CodePage > 0)
			$conn->charPage = $this->CodePage;
		<!--## if (IsDBMsAccess) { ##-->
		$relpath = @$info["relpath"];
		$dbname = @$info["dbname"];
		$provider = @$info["provider"];
		$password = @$info["password"];
		if ($relpath == "")
			$datasource = realpath($GLOBALS["EW_RELATIVE_PATH"] . $dbname);
		elseif (substr($relpath, 0, 1) == ".") // Relative path starting with "." or ".." (relative to app root)
			$datasource = ew_ServerMapPath($relpath . $dbname);
		elseif (substr($relpath, 0, 2) == "\\\\" || strpos($relpath, ":") !== FALSE) // Physical path
			$datasource = $relpath . $dbname;
		else // Relative to app root
			$datasource = ew_AppRoot() . str_replace("/", "\\", $relpath) . $dbname;
		if ($password <> "")
			$connstr = $provider . ";Data Source=" . $datasource . ";Jet OLEDB:Database Password=" . $password . ";";
		elseif (strtoupper(substr($dbname, -6)) == ".ACCDB") // AccDb
			$connstr = $provider . ";Data Source=" . $datasource . ";Persist Security Info=False;";
		else
			$connstr = $provider . ";Data Source=" . $datasource . ";";
		$conn->Connect($connstr, FALSE, FALSE);
		<!--## } else { ##-->
		$connstr = @$info["connectionstring"];
		$conn->Connect($connstr, FALSE, FALSE);
		<!--## } ##-->
	<!--## } ##-->
	<!--## if (IsDBMsSql && PROJ.DefaultDateFormat > 0) { ##-->
		// Set date format
		$conn->Execute("SET DATEFORMAT ymd");
	<!--## } ##-->
	<!--## if (IsDBOracle) { ##-->
		// Set schema
		$conn->Execute("ALTER SESSION SET CURRENT_SCHEMA = ". $this->QuotedName($info["schema"]));
		$conn->Execute("ALTER SESSION SET NLS_TIMESTAMP_FORMAT = 'yyyy-mm-dd hh24:mi:ss'");
		$conn->Execute("ALTER SESSION SET NLS_TIMESTAMP_TZ_FORMAT = 'yyyy-mm-dd hh24:mi:ss'");
		<!--## if (PROJ.GetV("OracleCompare") != "") { ##-->
		$conn->Execute("ALTER SESSION SET NLS_COMP = <!--##=PROJ.GetV("OracleCompare")##-->");
		<!--## } ##-->
		<!--## if (PROJ.GetV("OracleSort") != "") { ##-->
		$conn->Execute("ALTER SESSION SET NLS_SORT = <!--##=PROJ.GetV("OracleSort")##-->");
		<!--## } ##-->
	<!--## } ##-->
		$conn->raiseErrorFn = '';

		return $conn;
	}

}

<!--##
	}
##-->

// Abstract database helper class
abstract class cDbHelper {

	// Debug
	var $Debug = FALSE;

	// Language
	var $Language;

	// Connection
	var $Connection;

	// Constructor
	function __construct($langfolder = "", $langid = "", $info = NULL) {
		$args = func_get_args();
		if (count($args) == 1 && is_array($args[0])) { // $info only
			$langfolder = "";
			$langid = "";
			$info = $args[0];
		}
		// Debug
		if (defined("EW_DEBUG_ENABLED"))
			$this->Debug = EW_DEBUG_ENABLED;
		// Open connection
		if (!isset($this->Connection)) $this->Connection = $this->Connect($info);
		// Set up language object
		if ($langfolder <> "")
			$this->Language = new cLanguage($langfolder, $langid);
		elseif (isset($GLOBALS["Language"]))
			$this->Language = &$GLOBALS["Language"];
	}

	// Quote name
	private function QuotedName($Name) {
		$Name = str_replace($this->EndQuote, $this->EndQuote . $this->EndQuote, $Name);
		return $this->StartQuote . $Name . $this->EndQuote;
	}

	// Executes the query, and returns the row(s) as JSON
	function ExecuteJson($SQL, $FirstOnly = TRUE) {
		$rs = $this->LoadRecordset($SQL);
		if ($rs && !$rs->EOF && $rs->FieldCount() > 0) {
			$res = ($FirstOnly) ? array($rs->fields) : $rs->GetRows();
			$rs->Close();
			return json_encode($res);
		}
		return "false";
	}

	// Execute UPDATE, INSERT, or DELETE statements
	function Execute($SQL, $fn = NULL) {
		$conn = &$this->Connection;
		if ($this->Debug)
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($SQL);
		$conn->raiseErrorFn = '';
		if (is_callable($fn) && $rs) {
			while (!$rs->EOF) {
				$fn($rs->fields);
				$rs->MoveNext();
			}
			$rs->MoveFirst();
		}
		return $rs;
	}

	// Executes the query, and returns the first column of the first row
	function ExecuteScalar($SQL) {
		$res = FALSE;
		$rs = $this->LoadRecordset($SQL);
		if ($rs && !$rs->EOF && $rs->FieldCount() > 0) {
			$res = $rs->fields[0];
			$rs->Close();
		}
		return $res;
	}

	// Executes the query, and returns the first row
	function ExecuteRow($SQL) {
		$res = FALSE;
		$rs = $this->LoadRecordset($SQL);
		if ($rs && !$rs->EOF) {
			$res = $rs->fields;
			$rs->Close();
		}
		return $res;
	}

	// Load recordset
	function &LoadRecordset($SQL) {
		$conn = &$this->Connection;
		if ($this->Debug)
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($SQL);
		$conn->raiseErrorFn = '';
		return $rs;
	}

	// Table CSS class name
	var $TableClass = "table table-bordered table-striped ewDbTable";

	// Get result in HTML table
	// options: fieldcaption(bool|array), horizontal(bool), tablename(string|array), tableclass(string)
	function ExecuteHtml($SQL, $options = NULL) {
		$ar = is_array($options) ? $options : array();
		$horizontal = (array_key_exists("horizontal", $ar) && $ar["horizontal"]);
		$rs = $this->LoadRecordset($SQL);
		if (!$rs || $rs->EOF || $rs->FieldCount() < 1)
			return "";
		$html = "";
		$class = (array_key_exists("tableclass", $ar) && $ar["tableclass"]) ? $ar["tableclass"] : $this->TableClass;
		if ($rs->RecordCount() > 1 || $horizontal) { // Horizontal table
			$cnt = $rs->FieldCount();
			$html = "<table class=\"" . $class . "\">";
			$html .= "<thead><tr>";
			$row = &$rs->fields;
			foreach ($row as $key => $value) {
				if (!is_numeric($key))
					$html .= "<th>" . $this->GetFieldCaption($key, $ar) . "</th>";
			}
			$html .= "</tr></thead>";
			$html .= "<tbody>";
			$rowcnt = 0;
			while (!$rs->EOF) {
				$html .= "<tr>";
				$row = &$rs->fields;
				foreach ($row as $key => $value) {
					if (!is_numeric($key))
						$html .= "<td>" . $value . "</td>";
				}
				$html .= "</tr>";
				$rs->MoveNext();
			}
			$html .= "</tbody></table>";
		} else { // Single row, vertical table
			$html = "<table class=\"" . $class . "\"><tbody>";
			$row = &$rs->fields;
			foreach ($row as $key => $value) {
				if (!is_numeric($key)) {
					$html .= "<tr>";
					$html .= "<td>" . $this->GetFieldCaption($key, $ar) . "</td>";
					$html .= "<td>" . $value . "</td></tr>";
				}
			}
			$html .= "</tbody></table>";
		}
		return $html;
	}

	function GetFieldCaption($key, $ar) {
		$caption = "";
		if (!is_array($ar))
			return $key;
		$tablename = @$ar["tablename"];
		$usecaption = (array_key_exists("fieldcaption", $ar) && $ar["fieldcaption"]);
		if ($usecaption) {
			if (is_array($ar["fieldcaption"])) {
				$caption = @$ar["fieldcaption"][$key];
			} elseif (isset($this->Language)) {
				if (is_array($tablename)) {
					foreach ($tablename as $tbl) {
						$caption = @$this->Language->FieldPhrase($tbl, $key, "FldCaption");
						if ($caption <> "")
							break;
					}
				} elseif ($tablename <> "") {
					$caption = @$this->Language->FieldPhrase($tablename, $key, "FldCaption");
				}
			}
		}
		return ($caption <> "") ? $caption : $key;
	}

}

// Connection/Query error handler
if (!function_exists("ew_ErrorFn")) {
	// Connection/Query error handler
	function ew_ErrorFn($DbType, $ErrorType, $ErrorNo, $ErrorMsg, $Param1, $Param2, $Object) {
		if ($ErrorType == 'CONNECT') {
			if ($DbType == "ado_access" || $DbType == "ado_mssql") {
				$msg = "Failed to connect to database. Error: " . $ErrorMsg;
			} else {
				$msg = "Failed to connect to $Param2 at $Param1. Error: " . $ErrorMsg;
			}
		} elseif ($ErrorType == 'EXECUTE') {
			if (defined("EW_DEBUG_ENABLED") && EW_DEBUG_ENABLED) {
				$msg = "Failed to execute SQL: $Param1. Error: " . $ErrorMsg;
			} else {
				$msg = "Failed to execute SQL. Error: " . $ErrorMsg;
			}
		}
		if (function_exists("ew_AddMessage") && defined("EW_SESSION_FAILURE_MESSAGE"))
			ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $msg);
		else
			echo "<div class=\"alert alert-danger ewError\">" . $msg . "</div>";
	}
}
?>
<!--##/session##-->
