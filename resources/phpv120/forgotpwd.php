<!--##session currenttable##-->
<!--##
	// Set security table current and email field
	if (ew_IsNotEmpty(PROJ.SecTbl)) {
		TABLE = DB.Tables(PROJ.SecTbl);
		gsTblVar = TABLE.TblVar;
		goFlds = goTblFlds.Fields;
		if (ew_IsNotEmpty(PROJ.SecEmailFld)) {
			if (GetFldObj(PROJ.SecEmailFld)) {
				sEmailFld = gsFld;
				sEmailFldType = GetFieldTypeName(goFld.FldType);
				if (goFld.FldTagSize > 0) {
					sEmailFldTagSize = " size=\"" + goFld.FldTagSize + "\"";
				} else {
					sEmailFldTagSize = "";
				}
				if (goFld.FldTagMaxLength > 0) {
					sEmailFldTagMaxLength = " maxlength=\"" + goFld.FldTagMaxLength + "\"";
				} else {
					sEmailFldTagMaxLength = "";
				}
			}
		}
	}
##-->
<!--##/session##-->

<?php
<!--##session phpmain##-->

<!--##include captcha-script.php/phpcaptcha_var##-->

	var $Email = "";
	var $Action = "";
	var $ActivateCode = "";

	//
	// Page main
	//
	function Page_Main() {
		global $UserTableConn, $Language, $gsFormError;
		global $Breadcrumb;

		$Breadcrumb = new cBreadcrumb;
		$Breadcrumb->Add("<!--##=CTRL.CtrlID##-->", "RequestPwdPage", ew_CurrentUrl(), "", "", TRUE);

		$bPostBack = ew_IsHttpPost();
		$bValidEmail = FALSE;

		if ($bPostBack) {

			// Setup variables
			$this->Email = $_POST["email"];
			$bValidEmail = $this->ValidateForm($this->Email);

			if ($bValidEmail) {
				if (EW_ENCRYPTED_PASSWORD)
					$this->Action = "reset"; // Prompt user to change password
				else
					$this->Action = "confirm"; // Send password directly if not MD5
				$this->ActivateCode = ew_Encrypt($this->Email);
			} else {
				$this->setFailureMessage($gsFormError);
			}

		// Handle email activation
		} elseif (@$_GET["action"] <> "") {
			$this->Action = $_GET["action"];
			$this->Email = @$_GET["email"];
			$this->ActivateCode = @$_GET["code"];
			if ($this->Email <> ew_Decrypt($this->ActivateCode) || strtolower($this->Action) <> "confirm" && strtolower($this->Action) <> "reset") { // Email activation
				if ($this->getFailureMessage() == "")
					$this->setFailureMessage($Language->Phrase("ActivateFailed")); // Set activate failed message
				$this->Page_Terminate("<!--##=sFnLogin##-->"); // Go to login page
			}
			if (strtolower($this->Action) == "reset")
				$this->Action = "resetpassword";
		}

		<!--##include captcha-script.php/phpcaptcha_php##-->

		if ($this->Action <> "") {

			$bEmailSent = FALSE;

			// Set up filter (SQL WHERE clause) and get Return SQL
			// SQL constructor in <!--##=gsTblVar##--> class, <!--##=gsTblVar##-->info.php
			$sFilter = str_replace("%e", ew_AdjustSql($this->Email, EW_USER_TABLE_DBID), EW_USER_EMAIL_FILTER);
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();

			if ($RsUser = $UserTableConn->Execute($sSql)) {
				if (!$RsUser->EOF) {
					$rsold = $RsUser->fields;
					$bValidEmail = TRUE;
	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Other","User_RecoverPassword")) { ##-->
					// Call User Recover Password event
					$bValidEmail = $this->User_RecoverPassword($rsold);
	<!--## } ##-->
					if ($bValidEmail) {
						$sUserName = $rsold['<!--##=SQuote(PROJ.SecLoginIDFld)##-->'];
						$sPassword = $rsold['<!--##=SQuote(PROJ.SecPasswdFld)##-->'];
					}
				} else {
					$bValidEmail = FALSE;
					$this->setFailureMessage($Language->Phrase("InvalidEmail"));
				}

				$RsUser->Close();

				if ($bValidEmail) {
					if (strtolower($this->Action) == "resetpassword") { // Reset password
						$_SESSION[EW_SESSION_USER_PROFILE_USER_NAME] = $sUserName; // Save login user name
						$_SESSION[EW_SESSION_STATUS] = "passwordreset";
						$this->Page_Terminate("<!--##=sFnChangePwd##-->");
					} else {
						$Email = new cEmail();
						if (strtolower($this->Action) == "confirm") {
							$Email->Load(EW_EMAIL_FORGOTPWD_TEMPLATE);
							$Email->ReplaceContent('<!--$Password-->', $sPassword);
						} else {
							$Email->Load(EW_EMAIL_RESETPWD_TEMPLATE);
							$sActivateLink = ew_FullUrl() . "?action=reset";
							$sActivateLink .= "&email=" . $this->Email;
							$sActivateLink .= "&code=" . $this->ActivateCode;
							$Email->ReplaceContent('<!--$ActivateLink-->', $sActivateLink);
						}
						$Email->ReplaceSender(EW_SENDER_EMAIL); // Replace Sender
						$Email->ReplaceRecipient($this->Email); // Replace Recipient
						$Email->ReplaceContent('<!--$UserName-->', $sUserName);
		<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Other","Email_Sending")) { ##-->
						$Args = array();
						if (EW_ENCRYPTED_PASSWORD && strtolower($this->Action) == "confirm") $Args["rs"] = &$rsnew;
						if ($this->Email_Sending($Email, $Args))
							$bEmailSent = $Email->Send();
		<!--## } else { ##-->
						$bEmailSent = $Email->Send();
		<!--## } ##-->
					}
				}
	
			}

			if ($bEmailSent) {
				if ($this->getSuccessMessage() == "")
					if (strtolower($this->Action) == "confirm")
						$this->setSuccessMessage($Language->Phrase("PwdEmailSent")); // Set up success message
					else
						$this->setSuccessMessage($Language->Phrase("ResetPwdEmailSent")); // Set up success message
				$this->Page_Terminate("<!--##=sFnLogin##-->"); // Return to login page
			} elseif ($bValidEmail) {
				$this->setFailureMessage($Email->SendErrDescription); // Set up error message
			}

		}

	}
<!--##/session##-->
?>


<!--##session forgotpwd_htm##-->
<script type="text/javascript">

var <!--##=sFormName##--> = new ew_Form("<!--##=sFormName##-->");

// Extend page with Validate function
<!--##=sFormName##-->.Validate = function()
{
	var fobj = this.Form;
	if (!this.ValidateRequired)
		return true; // Ignore validation
	if  (!ew_HasValue(fobj.email))
		return this.OnError(fobj.email, ewLanguage.Phrase("EnterValidEmail"));
	if  (!ew_CheckEmail(fobj.email.value))
		return this.OnError(fobj.email, ewLanguage.Phrase("EnterValidEmail"));

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

<form name="<!--##=sFormName##-->" id="<!--##=sFormName##-->" class="form-horizontal ewForm ewForgotpwdForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($<!--##=sPageObj##-->->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $<!--##=sPageObj##-->->Token ?>">
<?php } ?>

<!--##
	sPlaceHolder = (sUsePlaceHolder == "Caption") ? " placeholder=\"<?php echo ew_HtmlEncode($Language->Phrase(\"UserEmail\")) ?>\"" : "";
##-->
	<div class="form-group">
		<label class="<!--##=ewBootstrapLabelClass##-->" for="email"><!--##@UserEmail##--></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->"><input type="text" name="email" id="email" class="<!--##=ewBootstrapInputClass##-->" value="<?php ew_HtmlEncode($<!--##=sPageObj##-->->Email) ?>"<!--##=sEmailFldTagSize##--><!--##=sEmailFldTagMaxLength##--><!--##=sPlaceHolder##-->></div>
	</div>

<!--##include captcha-script.php/phpcaptcha_htm##-->

	<div class="form-group">
		<div class="<!--##=ewBootstrapOffsetClass##-->">
			<button class="<!--##=sSubmitButtonClass##-->" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SendPwd") ?></button>
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

	//
	// Validate form
	//

	function ValidateForm($email) {
		global $gsFormError, $Language;
		
		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		if ($email == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterValidEmail"));
		}

		if (!ew_CheckEmail($email)) {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterValidEmail"));
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

<!--##/session##-->
?>


<?php
<!--##session phpevents##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Other","Email_Sending")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Other","Form_CustomValidate")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Other","User_RecoverPassword")##-->
<!--##/session##-->
?>