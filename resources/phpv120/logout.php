<!--##session currenttable##-->
<!--##
	// Set security table current
	if (ew_IsNotEmpty(PROJ.SecTbl)) {
		TABLE = DB.Tables(PROJ.SecTbl);
		gsTblVar = TABLE.TblVar;
	}
##-->
<!--##/session##-->
<?php
<!--##session phpmain##-->
	//
	// Page main
	//
	function Page_Main() {
		global $Security, $Language, $UserProfile;

		$bValidate = TRUE;
		
		$sUsername = $Security->CurrentUserName();
		
		<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Other","User_LoggingOut")) { ##-->
		// Call User LoggingOut event
		$bValidate = $this->User_LoggingOut($sUsername);
		<!--## } else { ##-->
		$bValidate = TRUE;
		<!--## } ##-->
		
		if (!$bValidate) {
		
			$sLastUrl = $Security->LastUrl();
			if ($sLastUrl == "")
				$sLastUrl = "<!--##=sFnDefault##-->";
			$this->Page_Terminate($sLastUrl); // Go to last accessed URL
		
		} else {
		
			if (@$_COOKIE[EW_PROJECT_NAME]['AutoLogin'] == "") // Not autologin
				setcookie(EW_PROJECT_NAME . '[Username]', ""); // Clear user name cookie
		
			setcookie(EW_PROJECT_NAME . '[Password]', ""); // Clear password cookie
			setcookie(EW_PROJECT_NAME . '[LastUrl]', ""); // Clear last URL
		
<!--## if (bCheckConcurrentUser) { ##-->

			// Clear Session ID
			$UserProfile->RemoveUser($sUsername, session_id());

<!--## } ##-->

			<!--## if (PROJ.SecLogInOutAuditTrail) { ##-->
			$this->WriteAuditTrailOnLogout($sUsername);
			<!--## } ##-->

			<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Other","User_LoggedOut")) { ##-->
			// Call User LoggedOut event
			$this->User_LoggedOut($sUsername);
			<!--## } ##-->

			// Clean upload temp folder
			ew_CleanUploadTempPaths(session_id());

			// Unset all of the Session variables
			$_SESSION = array();
			
			// Delete the Session cookie and kill the Session
			if (isset($_COOKIE[session_name()]))
				setcookie(session_name(), '', time()-42000, '/');
			
			// Finally, destroy the Session
			@session_destroy();

			// If session expired, show expired message
			if (@$_GET["expired"] == "1")
				$this->Page_Terminate("<!--##=sFnLogin##-->?expired=1"); // Go to login page
			else
				$this->Page_Terminate("<!--##=sFnLogin##-->"); // Go to login page
		
		}
	}

<!--## if (PROJ.SecLogInOutAuditTrail) { ##-->

	//
	// Write audit trail on logout
	//
	function WriteAuditTrailOnLogout($usr) {
		global $Language;
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $Language->Phrase("AuditTrailLogout"), ew_CurrentUserIP(), "", "", "", "");
	}

<!--## } ##-->

<!--##/session##-->
?>

<?php
<!--##session phpevents##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Other","User_LoggingOut")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Other","User_LoggedOut")##-->
<!--##/session##-->
?>