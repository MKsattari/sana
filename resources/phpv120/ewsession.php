<!--##session session_script##-->
<?php
ew_Header(FALSE);
$session = new cewsession;
$session->Page_Main();

//
// Page class for session
//
class cewsession {

	// Page ID
	var $PageID = "session";

	// Project ID
	var $ProjectID = "<!--##=PROJ.ProjID##-->";

	// Page object name
	var $PageObjName = "session";

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		return ew_CurrentPage() . "?";
	}
	
	// Main
	// - Uncomment ** for database connectivity / Page_Loading / Page_Unloaded server event
	function Page_Main() {
		global $conn;
		$GLOBALS["Page"] = &$this;

		//**$conn = ew_Connect();

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Global","Page_Loading")) { ##-->
		// Global Page Loading event (in userfn*.php)
		//**Page_Loading();
	<!--## } ##-->

		if (ob_get_length())
			ob_end_clean();

		$time = time();
		$_SESSION["EW_LAST_REFRESH_TIME"] = $time;
		echo ew_Encrypt($time);

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Global","Page_Unloaded")) { ##-->
		// Global Page Unloaded event (in userfn*.php)
		//**Page_Unloaded();
	<!--## } ##-->

		 // Close connection
		//**ew_CloseConn();

	}
}
?>
<!--##/session##-->