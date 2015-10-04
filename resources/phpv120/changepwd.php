<!--##session currenttable##-->
<!--##
	// Set security table current
	if (ew_IsNotEmpty(PROJ.SecTbl)) {
		TABLE = DB.Tables(PROJ.SecTbl);
		goFlds = goTblFlds.Fields;
		gsTblVar = TABLE.TblVar;
	}
##-->
<!--##/session##-->


<?php
<!--##session phpmain##-->
<!--##
	if (GetFldObj(PROJ.SecLoginIDFld)) {
		// Nothing to do
	}
##-->

<!--##include captcha-script.php/phpcaptcha_var##-->

	var $OldPassword = "";
	var $NewPassword = "";
	var $ConfirmedPassword = "";

	// 
	// Page main
	//
	function Page_Main() {
		global $UserTableConn, $Language, $Security, $gsFormError;
		global $Breadcrumb;

		$Breadcrumb = new cBreadcrumb;
		$Breadcrumb->Add("<!--##=CTRL.CtrlID##-->", "ChangePwdPage", ew_CurrentUrl(), "", "", TRUE);

		$bPostBack = ew_IsHttpPost();
		$bValidate = TRUE;
		if ($bPostBack) {
			$this->OldPassword = ew_StripSlashes(@$_POST["opwd"]);
			$this->NewPassword = ew_StripSlashes(@$_POST["npwd"]);
			$this->ConfirmedPassword = ew_StripSlashes(@$_POST["cpwd"]);
			$bValidate = $this->ValidateForm($this->OldPassword, $this->NewPassword, $this->ConfirmedPassword);
			if (!$bValidate) {
				$this->setFailureMessage($gsFormError);
			}
		}

		<!--##include captcha-script.php/phpcaptcha_php##-->

		$bPwdUpdated = FALSE;

		if ($bPostBack && $bValidate) {

			// Setup variables
			$sUsername = $Security->CurrentUserName();
			if (IsPasswordReset())
				$sUsername = $_SESSION[EW_SESSION_USER_PROFILE_USER_NAME];
<!--## if (bCheckPasswordExpiry) { ##-->
			if (IsPasswordExpired())
				$sUsername = $_SESSION[EW_SESSION_USER_PROFILE_USER_NAME];
<!--## } ##-->

			$sFilter = str_replace("%u", ew_AdjustSql($sUsername, EW_USER_TABLE_DBID), EW_USER_NAME_FILTER);

			// Set up filter (Sql Where Clause) and get Return SQL
			// SQL constructor in <!--##=gsTblVar##--> class, <!--##=gsTblVar##-->info.php
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();

			if ($rs = $UserTableConn->Execute($sSql)) {
				if (!$rs->EOF) {

					$rsold = $rs->fields;
					if (IsPasswordReset() || ew_ComparePassword($rsold['<!--##=SQuote(PROJ.SecPasswdFld)##-->'], $this->OldPassword)) {
						$bValidPwd = TRUE;
		<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Other","User_ChangePassword")) { ##-->
						if (!IsPasswordReset())
							$bValidPwd = $this->User_ChangePassword($rsold, $sUsername, $this->OldPassword, $this->NewPassword);
		<!--## } ##-->
						if ($bValidPwd) {
							$rsnew = array('<!--##=SQuote(PROJ.SecPasswdFld)##-->' => $this->NewPassword); // Change Password
		<!--## if (PROJ.SecChangeEmail) { ##-->
							$sEmail = $rsold['<!--##=SQuote(PROJ.SecEmailFld)##-->'];
		<!--## } ##-->
							$rs->Close();
							$UserTableConn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
							$bValidPwd = $this->Update($rsnew);
							$UserTableConn->raiseErrorFn = '';
							if ($bValidPwd)
								$bPwdUpdated = TRUE;
						} else {
							$this->setFailureMessage($Language->Phrase("InvalidNewPassword"));
							$rs->Close();
						}

					} else {
						$this->setFailureMessage($Language->Phrase("InvalidPassword"));
					}
				} else {
					$rs->Close();
				}

			}

		}

		if ($bPwdUpdated) {
<!--## if (PROJ.SecChangeEmail) { ##-->
			if (@$sEmail <> "") {
				// Load Email Content
				$Email = new cEmail();
				$Email->Load(EW_EMAIL_CHANGEPWD_TEMPLATE);
				$Email->ReplaceSender(EW_SENDER_EMAIL); // Replace Sender
				$Email->ReplaceRecipient($sEmail); // Replace Recipient
				$Email->ReplaceContent('<!--$Password-->', $this->NewPassword);
	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Other","Email_Sending")) { ##-->
				$Args = array();
				$Args["rs"] = &$rsnew;
				$bEmailSent = FALSE;
				if ($this->Email_Sending($Email, $Args))
					$bEmailSent = $Email->Send();
	<!--## } else { ##-->
				$bEmailSent = $Email->Send();
	<!--## } ##-->
				// Send email failed
				if (!$bEmailSent)
					$this->setFailureMessage($Email->SendErrDescription);
			}
<!--## } ##-->

			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("PasswordChanged")); // Set up success message

			if (IsPasswordReset()) {
				$_SESSION[EW_SESSION_STATUS] = "";
				$_SESSION[EW_SESSION_USER_PROFILE_USER_NAME] = "";
			}

<!--## if (bCheckPasswordExpiry) { ##-->
			// Update user profile and login again
			global $UserProfile;
			$UserProfile->LoadProfileFromDatabase($sUsername);
			$UserProfile->SetValue(EW_USER_PROFILE_LAST_PASSWORD_CHANGED_DATE, ew_StdCurrentDate());
			$UserProfile->SaveProfileToDatabase($sUsername);

			if (IsPasswordExpired()) {
				$_SESSION[EW_SESSION_USER_PROFILE_PASSWORD] = $this->NewPassword;
				$_SESSION[EW_SESSION_STATUS] = "loggingin";
				$this->Page_Terminate("<!--##=sFnLogin##-->"); // Continue login process
			}
<!--## } ##-->
			
			$this->Page_Terminate("<!--##=sFnDefault##-->"); // Exit page and clean up

		}
	}
<!--##/session##-->
?>
<!--##session changepwd_htm##-->
<script type="text/javascript">
var <!--##=sFormName##--> = new ew_Form("<!--##=sFormName##-->");

// Extend form with Validate function
<!--##=sFormName##-->.Validate = function() {
	var $ = jQuery, fobj = this.Form, $npwd = $(fobj.npwd);
	if (!this.ValidateRequired)
		return true; // Ignore validation
<?php if (!IsPasswordReset()) { ?>
	if (!ew_HasValue(fobj.opwd))
		return this.OnError(fobj.opwd, ewLanguage.Phrase("EnterOldPassword"));
<?php } ?>
	if (!ew_HasValue(fobj.npwd))
		return this.OnError(fobj.npwd, ewLanguage.Phrase("EnterNewPassword"));

<!--## if (bCheckPasswordStrength) { ##-->
	if ($npwd.hasClass("ewPasswordStrength") && !$npwd.data("validated"))
		return this.OnError(fobj.npwd, ewLanguage.Phrase("PasswordTooSimple"));
<!--## } ##-->

	if (fobj.npwd.value != fobj.cpwd.value)
		return this.OnError(fobj.cpwd, ewLanguage.Phrase("MismatchPassword"));

<!--##include captcha-script.php/phpcaptcha_js##-->

<!--## if (SYSTEMFUNCTIONS.ClientScriptExist("Other","Form_CustomValidate")) { ##-->
	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj)) return false;
<!--## } ##-->
	return true;
}

<!--## if (SYSTEMFUNCTIONS.ClientScriptExist("Other","Form_CustomValidate")) { ##-->
// Extend form with Form_CustomValidate function
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

<form name="<!--##=sFormName##-->" id="<!--##=sFormName##-->" class="form-horizontal ewForm ewChangepwdForm" action="<?php echo ew_CurrentPage() ?>" method="post">

<?php if ($<!--##=sPageObj##-->->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $<!--##=sPageObj##-->->Token ?>">
<?php } ?>

<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">

<!--##
	sPlaceHolder = (sUsePlaceHolder == "Caption") ? " placeholder=\"<?php echo ew_HtmlEncode($Language->Phrase(\"OldPassword\")) ?>\"" : "";
##-->
<?php if (!IsPasswordReset()) { ?>
	<div class="form-group">
		<label class="<!--##=ewBootstrapLabelClass##-->" for="opwd"><!--##@OldPassword##--></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->"><input type="password" name="opwd" id="opwd" class="<!--##=ewBootstrapInputClass##-->"<!--##=sPlaceHolder##-->></div>
	</div>
<?php } ?>

<!--##
	sPlaceHolder = (sUsePlaceHolder == "Caption") ? " placeholder=\"<?php echo ew_HtmlEncode($Language->Phrase(\"NewPassword\")) ?>\"" : "";
	var sPasswordStrengthId = "";
	var sPasswordStrength = "";
	var sGeneratePasswordId = "";
	var sGeneratePassword = "";
	var sPasswordInputClass = ewBootstrapInputClass;
	if (bCheckPasswordStrength) {
		sPasswordStrengthId = "pst_npwd";
		sPasswordStrength = " data-password-strength=\"" + sPasswordStrengthId + "\"";
		sPasswordInputClass += " ewPasswordStrength";
	}
	if (bGeneratePassword) {
		sGeneratePasswordId = "pgt_npwd";
		sGeneratePassword = " data-password-generated=\"" + sGeneratePasswordId + "\"";
	}
##-->
	<div class="form-group">
		<label class="<!--##=ewBootstrapLabelClass##-->" for="npwd"><!--##@NewPassword##--></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->">
<!--## if (bGeneratePassword) { ##-->
		<div class="input-group" id="ignpwd">
<!--## } ##-->
		<input type="password"<!--##=sPasswordStrength##--><!--##=sGeneratePassword##--> name="npwd" id="npwd" class="<!--##=sPasswordInputClass##-->"<!--##=sPlaceHolder##-->>
<!--## if (bGeneratePassword) { ##-->
		<span class="input-group-btn">
			<button type="button" class="btn btn-default ewPasswordGenerator" title="<?php echo ew_HtmlTitle($Language->Phrase("GeneratePassword")) ?>" data-password-field="npwd" data-password-confirm="cpwd"<!--##=sPasswordStrength##--><!--##=sGeneratePassword##-->><?php echo $Language->Phrase("GeneratePassword") ?></button>
		</span>
		</div>
		<span class="help-block" id="<!--##=sGeneratePasswordId##-->" style="display: none;"></span>
<!--## } ##-->
<!--## if (bCheckPasswordStrength) { ##-->
		<div class="progress ewPasswordStrengthBar" id="<!--##=sPasswordStrengthId##-->" style="display: none;">
			<div class="progress-bar" role="progressbar"></div>
		</div>
<!--## } ##-->
		</div>
	</div>

<!--##
	sPlaceHolder = (sUsePlaceHolder == "Caption") ? " placeholder=\"<?php echo ew_HtmlEncode($Language->Phrase(\"ConfirmPassword\")) ?>\"" : "";
##-->
	<div class="form-group">
		<label class="<!--##=ewBootstrapLabelClass##-->" for="cpwd"><!--##@ConfirmPassword##--></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->">
		<input type="password" name="cpwd" id="cpwd" class="<!--##=ewBootstrapInputClass##-->"<!--##=sPlaceHolder##-->>
		</div>
	</div>

<!--##include captcha-script.php/phpcaptcha_htm##-->

	<div class="form-group">
		<div class="<!--##=ewBootstrapOffsetClass##-->">
			<button class="<!--##=sSubmitButtonClass##-->" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("ChangePwdBtn") ?></button>
		</div>
	</div>

</form>

<script type="text/javascript">
<!--##=sFormName##-->.Init();
</script>

<!--##include phpcommon.php/footer-message##-->
<!--##/session##-->

<?php
<!--##session phpfunction##-->

	// Validate form
	function ValidateForm($opwd, $npwd, $cpwd) {
		global $Language, $gsFormError;
	
		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
			
		// Initialize form error message
		$gsFormError = "";
		
		if (!IsPasswordReset() && $opwd == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterOldPassword"));
		}
		if ($npwd == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterNewPassword"));
		}
		if ($npwd <> $cpwd) {
			ew_AddMessage($gsFormError, $Language->Phrase("MismatchPassword"));
		}

		// Return validate result
		$valid = ($gsFormError == "");

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Other","Form_CustomValidate")) { ##-->
		// Call Form CustomValidate event
		$sFormCustomError = "";
		$valid = $valid && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
	<!--## } ##-->
	
		return $valid;

	}

<!--##/session##-->
?>


<?php
<!--##session phpevents##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Other","Email_Sending")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Other","Form_CustomValidate")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Other","User_ChangePassword")##-->
<!--##/session##-->
?>
