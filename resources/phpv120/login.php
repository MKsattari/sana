<!--##session currenttable##-->
<!--##
	// Set security table current
	if (ew_IsNotEmpty(PROJ.SecTbl)) {
		TABLE = DB.Tables(PROJ.SecTbl);
		goFlds = goTblFlds.Fields;
		gsTblVar = TABLE.TblVar;
		sTblObj = gsTblVar;
	}
	
	sLoginOption = PROJ.LoginOption;
	if (sLoginOption == "") sLoginOption = "AUTO,USER,ASK";
	arLoginOption = sLoginOption.split(",");
	dLoginOption = {};
	lLoginOptionCount = 0;
	for (var i = 0; i < arLoginOption.length; i++) {
		sOption = arLoginOption[i].trim();
		if (sOption == "AUTO" || sOption == "USER" || sOption == "ASK") {
			if (!(sOption in dLoginOption)) {
				dLoginOption[sOption] = sOption;
				lLoginOptionCount += 1;
			}
		}
	}
##-->
<!--##/session##-->

<?php
<!--##session phpmain##-->

<!--##include captcha-script.php/phpcaptcha_var##-->

	var $Username;
	var $LoginType;

	//
	// Page main
	//
	function Page_Main() {
		global $Security, $Language, $UserProfile, $gsFormError;
		global $Breadcrumb;

		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb = new cBreadcrumb;
		$Breadcrumb->Add("<!--##=CTRL.CtrlID##-->", "LoginPage", $url, "", "", TRUE);

		$sPassword = "";
		$sLastUrl = $Security->LastUrl(); // Get last URL
		if ($sLastUrl == "")
			$sLastUrl = "<!--##=sFnDefault##-->";

		// If session expired, show session expired message
		if (@$_GET["expired"] == "1")
			$this->setFailureMessage($Language->Phrase("SessionExpired"));

		if (IsLoggingIn()) {

			$this->Username = @$_SESSION[EW_SESSION_USER_PROFILE_USER_NAME];
			$sPassword = @$_SESSION[EW_SESSION_USER_PROFILE_PASSWORD];
			$this->LoginType = @$_SESSION[EW_SESSION_USER_PROFILE_LOGIN_TYPE];
			$bValidPwd = $Security->ValidateUser($this->Username, $sPassword, FALSE);
			if ($bValidPwd) {
				$_SESSION[EW_SESSION_USER_PROFILE_USER_NAME] = "";
				$_SESSION[EW_SESSION_USER_PROFILE_PASSWORD] = "";
				$_SESSION[EW_SESSION_USER_PROFILE_LOGIN_TYPE] = "";
			}

		} else {

			if (!$Security->IsLoggedIn())
				$Security->AutoLogin();

	<!--## if (bUserLevel) { ##-->
			$Security->LoadUserLevel(); // Load user level
	<!--## } ##-->
	
			$this->Username = ""; // Initialize
			$encrypted = FALSE;
			if (isset($_POST["username"])) {
				$this->Username = ew_RemoveXSS(ew_StripSlashes($_POST["username"]));
				$sPassword = ew_RemoveXSS(ew_StripSlashes(@$_POST["password"]));
				$this->LoginType = strtolower(ew_RemoveXSS(@$_POST["type"]));
			} else if (EW_ALLOW_LOGIN_BY_URL && isset($_GET["username"])) {
				$this->Username = ew_RemoveXSS(ew_StripSlashes($_GET["username"]));
				$sPassword = ew_RemoveXSS(ew_StripSlashes(@$_GET["password"]));
				$this->LoginType = strtolower(ew_RemoveXSS(@$_GET["type"]));
				$encrypted = !empty($_GET["encrypted"]);
			}

			if ($this->Username <> "") {

				$bValidate = $this->ValidateForm($this->Username, $sPassword);
				if (!$bValidate)
					$this->setFailureMessage($gsFormError);

				$_SESSION[EW_SESSION_USER_LOGIN_TYPE] = $this->LoginType; // Save user login type

				$_SESSION[EW_SESSION_USER_PROFILE_USER_NAME] = $this->Username; // Save login user name
				$_SESSION[EW_SESSION_USER_PROFILE_LOGIN_TYPE] = $this->LoginType; // Save login type

<!--## if (bCheckLoginRetry) { ##-->
				// Max login attempt checking
				if ($UserProfile->ExceedLoginRetry($this->Username)) {
					$bValidate = FALSE;
					$this->setFailureMessage(str_replace("%t", EW_USER_PROFILE_RETRY_LOCKOUT, $Language->Phrase("ExceedMaxRetry")));
				}
<!--## } ##-->
			
			} else {
			
				if ($Security->IsLoggedIn()) {
					if ($this->getFailureMessage() == "")
						$this->Page_Terminate($sLastUrl); // Return to last accessed page
				}
				
				$bValidate = FALSE;
	
				// Restore settings
				if (@$_COOKIE[EW_PROJECT_NAME]['Checksum'] == strval(crc32(md5(EW_RANDOM_KEY))))
					$this->Username = ew_Decrypt(@$_COOKIE[EW_PROJECT_NAME]['Username']);
				if (@$_COOKIE[EW_PROJECT_NAME]['AutoLogin'] == "autologin") {
					$this->LoginType = "a";
				} elseif (@$_COOKIE[EW_PROJECT_NAME]['AutoLogin'] == "rememberusername") {
					$this->LoginType = "u";
				} else {
					$this->LoginType = "";
				}
			
			}
			
			$bValidPwd = FALSE;

			<!--##include captcha-script.php/phpcaptcha_php##-->

			if ($bValidate) {
			
			<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Other","User_LoggingIn")) { ##-->
				// Call Logging In event
				$bValidate = $this->User_LoggingIn($this->Username, $sPassword);
			<!--## } else { ##-->
				$bValidate = TRUE;
			<!--## } ##-->
			
				if ($bValidate) {
					$bValidPwd = $Security->ValidateUser($this->Username, $sPassword, FALSE, $encrypted); // Manual login
					if (!$bValidPwd) {
<!--## if (bCheckPasswordExpiry) { ##-->
						// Password expired, force change password
						if (IsPasswordExpired()) {
							$this->setFailureMessage($Language->Phrase("PasswordExpired"));
							$this->Page_Terminate("<!--##=sFnChangePwd##-->");
						}
<!--## } ##-->
						if ($this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("InvalidUidPwd")); // Invalid user id/password
<!--## if (bCheckPasswordExpiry) { ##-->
					// Password changed date not initialized, set as today
					} elseif ($UserProfile->EmptyPasswordChangedDate($this->Username)) {
						$UserProfile->SetValue(EW_USER_PROFILE_LAST_PASSWORD_CHANGED_DATE, ew_StdCurrentDate());
						$UserProfile->SaveProfileToDatabase($this->Username);
<!--## } ##-->
					}
				} else {
					if ($this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("LoginCancelled")); // Login cancelled
				}
			}
			
		}

		if ($bValidPwd) {
			// Write cookies
			if ($this->LoginType == "a") { // Auto login
				setcookie(EW_PROJECT_NAME . '[AutoLogin]',  "autologin", EW_COOKIE_EXPIRY_TIME); // Set autologin cookie
				setcookie(EW_PROJECT_NAME . '[Username]', ew_Encrypt($this->Username), EW_COOKIE_EXPIRY_TIME); // Set user name cookie
				setcookie(EW_PROJECT_NAME . '[Password]', ew_Encrypt($sPassword), EW_COOKIE_EXPIRY_TIME); // Set password cookie
				setcookie(EW_PROJECT_NAME . '[Checksum]', crc32(md5(EW_RANDOM_KEY)), EW_COOKIE_EXPIRY_TIME);
			} elseif ($this->LoginType == "u") { // Remember user name
				setcookie(EW_PROJECT_NAME . '[AutoLogin]', "rememberusername", EW_COOKIE_EXPIRY_TIME); // Set remember user name cookie
				setcookie(EW_PROJECT_NAME . '[Username]', ew_Encrypt($this->Username), EW_COOKIE_EXPIRY_TIME); // Set user name cookie
				setcookie(EW_PROJECT_NAME . '[Checksum]', crc32(md5(EW_RANDOM_KEY)), EW_COOKIE_EXPIRY_TIME);
			} else {
				setcookie(EW_PROJECT_NAME . '[AutoLogin]', "", EW_COOKIE_EXPIRY_TIME); // Clear auto login cookie
			}

		<!--## if (PROJ.SecLogInOutAuditTrail) { ##-->
			$this->WriteAuditTrailOnLogin($this->Username);
		<!--## } ##-->

		<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Other","User_LoggedIn")) { ##-->
			// Call loggedin event
			$this->User_LoggedIn($this->Username);
		<!--## } ##-->

			$this->Page_Terminate($sLastUrl); // Return to last accessed URL
			
		} elseif ($this->Username <> "" && $sPassword <> "") {

<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Other","User_LoginError")) { ##-->
			// Call user login error event
			$this->User_LoginError($this->Username, $sPassword);
<!--## } ##-->
		}

	}
<!--##/session##-->
?>

<!--##session login_htm##-->
<script type="text/javascript">

var <!--##=sFormName##--> = new ew_Form("<!--##=sFormName##-->");

// Validate function
<!--##=sFormName##-->.Validate = function()
{
	var fobj = this.Form;
	if (!this.ValidateRequired)
		return true; // Ignore validation
	if (!ew_HasValue(fobj.username))
		return this.OnError(fobj.username, ewLanguage.Phrase("EnterUid"));
	if (!ew_HasValue(fobj.password))
		return this.OnError(fobj.password, ewLanguage.Phrase("EnterPwd"));

<!--##include captcha-script.php/phpcaptcha_js##-->

<!--## if (SYSTEMFUNCTIONS.ClientScriptExist("Other","Form_CustomValidate")) { ##-->
	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj)) return false;
<!--## } ##-->
	return true;
}

<!--## if (SYSTEMFUNCTIONS.ClientScriptExist("Other","Form_CustomValidate")) { ##-->
// Form_CustomValidate function
<!--##=sFormName##-->.Form_CustomValidate = <!--##~SYSTEMFUNCTIONS.GetClientScript("Other","Form_CustomValidate")##-->
<!--## } ##-->

// Requires js validation
<?php if (EW_CLIENT_VALIDATE) { ?>
<!--##=sFormName##-->.ValidateRequired = true;
<?php } else { ?>
<!--##=sFormName##-->.ValidateRequired = false;
<?php } ?>

</script>

<div class="ewToolbar">
<!--##include phpcommon.php/breadcrumb##-->
<!--##include phpcommon.php/language##-->
<div class="clearfix"></div>
</div>

<!--##include phpcommon.php/header-message##-->
<!--##include phpcommon.php/phpcommon-message##-->

<form name="<!--##=sFormName##-->" id="<!--##=sFormName##-->" class="form-horizontal ewForm ewLoginForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($<!--##=sPageObj##-->->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $<!--##=sPageObj##-->->Token ?>">
<?php } ?>

<!--##
	sPlaceHolder = (sUsePlaceHolder == "Caption") ? " placeholder=\"<?php echo ew_HtmlEncode($Language->Phrase(\"Username\")) ?>\"" : "";
##-->
	<div class="form-group">
		<label class="<!--##=ewBootstrapLabelClass##-->" for="username"><!--##@Username##--></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->"><input type="text" name="username" id="username" class="<!--##=ewBootstrapInputClass##-->" value="<?php echo ew_HtmlEncode($<!--##=sPageObj##-->->Username) ?>"<!--##=sPlaceHolder##-->></div>
	</div>
<!--##
	sPlaceHolder = (sUsePlaceHolder == "Caption") ? " placeholder=\"<?php echo ew_HtmlEncode($Language->Phrase(\"Password\")) ?>\"" : "";
##-->
	<div class="form-group">
		<label class="<!--##=ewBootstrapLabelClass##-->" for="password"><!--##@Password##--></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->"><input type="password" name="password" id="password" class="<!--##=ewBootstrapInputClass##-->"<!--##=sPlaceHolder##-->></div>
	</div>

<!--## if (lLoginOptionCount > 1) { ##-->
	<div class="form-group">
		<div class="<!--##=ewBootstrapOffsetClass##-->">
			<a id="ewLoginOptions" class="collapsed" data-toggle="collapse" data-target="#<!--##=sFormName##-->_options"><?php echo $Language->Phrase("LoginOptions") ?> <span class="icon-arrow"></span></a>
			<div id="<!--##=sFormName##-->_options" class="collapse">
				<!--## if (String("AUTO") in dLoginOption) { ##-->
					<div class="radio ewRadio">
					<label for="type1"><input type="radio" name="type" id="type1" value="a"<?php if ($<!--##=sPageObj##-->->LoginType == "a") { ?> checked="checked"<?php } ?>><!--##@AutoLogin##--></label>
					</div>
				<!--## } ##-->
				<!--## if (String("USER") in dLoginOption) { ##-->
					<div class="radio ewRadio">
					<label for="type2"><input type="radio" name="type" id="type2" value="u"<?php if ($<!--##=sPageObj##-->->LoginType == "u") { ?>  checked="checked"<?php } ?>><!--##@SaveUserName##--></label>
					</div>
				<!--## } ##-->
				<!--## if (String("ASK") in dLoginOption) { ##-->
					<div class="radio ewRadio">
					<label for="type3"><input type="radio" name="type" id="type3" value=""<?php if ($<!--##=sPageObj##-->->LoginType == "") { ?> checked="checked"<?php } ?>><!--##@AlwaysAsk##--></label>
					</div>
				<!--## } ##-->
			</div>
		</div>
	</div>
<!--## } ##-->

<!--##include captcha-script.php/phpcaptcha_htm##-->

	<div class="form-group">
		<div class="<!--##=ewBootstrapOffsetClass##-->">
			<button class="<!--##=sSubmitButtonClass##-->" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("Login") ?></button>
		</div>
	</div>

<!--## if (lLoginOptionCount == 1) { ##-->
	<!--## if (String("AUTO") in dLoginOption) { ##-->
		<input type="hidden" name="type" value="a">
	<!--## } else if (String("USER") in dLoginOption) { ##-->
		<input type="hidden" name="type" value="u">
	<!--## } else if (String("ASK") in dLoginOption) { ##-->
		<input type="hidden" name="type" value="">
	<!--## } ##-->
<!--## } ##-->

</form>

<!--## if (bUserTable) { ##-->

<br>
<!--## if (PROJ.SecForgetPwdPage) { ##-->
<a class="ewLink ewLinkSeparator" href="<!--##=sFnForgotPwd##-->"><?php echo $Language->Phrase("ForgotPwd") ?></a>
<!--## } ##-->
<!--## if (PROJ.SecRegisterPage) { ##-->
<a class="ewLink ewLinkSeparator" href="<!--##=sFnRegister##-->"><?php echo $Language->Phrase("Register") ?></a>
<!--## } ##-->

<!--## } ##-->

<script type="text/javascript">
<!--##=sFormName##-->.Init();
</script>

<!--##include phpcommon.php/footer-message##-->
<!--##/session##-->

<?php
<!--##session phpfunction##-->

	//
	// Validate form
	//
	function ValidateForm($usr, $pwd) {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		if (trim($usr) == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterUid"));
		}

		if (trim($pwd) == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterPwd"));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Other","Form_CustomValidate")) { ##-->
		// Call Form Custom Validate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
	<!--## } ##-->
	
		return $ValidateForm;

	}

<!--## if (PROJ.SecLogInOutAuditTrail) { ##-->

	//
	// Write audit trail on login
	//
	function WriteAuditTrailOnLogin($usr) {
		global $Language;
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $Language->Phrase("AuditTrailLogin"), ew_CurrentUserIP(), "", "", "", "");
	}

<!--## } ##-->

<!--##/session##-->
?>

<?php
<!--##session phpevents##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Other","User_LoggingIn")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Other","User_LoggedIn")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Other","User_LoginError")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Other","Form_CustomValidate")##-->
<!--##/session##-->
?>