<!--##session currenttable##-->
<!--##
	// Set security table current
	if (ew_IsNotEmpty(PROJ.SecTbl)) {
		TABLE = DB.Tables(PROJ.SecTbl);
		goFlds = goTblFlds.Fields;
		gsTblVar = TABLE.TblVar;
	}
	if (GetFldObj(PROJ.SecLoginIDFld)) {
		LOGINIDFIELD = FIELD;
		sLoginIDFld = gsFld;
		sLoginIDFldName = gsFldName;
		sLoginIDFldVar = gsFldVar;
		sLoginIDFldParm = gsFldParm;
		sLoginIDFldObj = "this->" + gsFldParm;
	}
	if (GetFldObj(PROJ.SecPasswdFld)) {
		PASSWORDFIELD = FIELD;
		sPasswordFld = gsFld;
		sPasswordFldName = gsFldName;
		sPasswordFldVar = gsFldVar;
		sPasswordFldParm = gsFldParm;
		sPasswordFldObj = "this->" + gsFldParm;
	}
	if (PROJ.SecRegisterEmail && ew_IsNotEmpty(PROJ.SecEmailFld)) {
		if (GetFldObj(PROJ.SecEmailFld)) {
			EMAILFIELD = FIELD;
			sEmailFld = gsFld;
			sEmailFldName = gsFldName;
			sEmailFldVar = gsFldVar;
			sEmailFldParm = gsFldParm;
			sEmailFldObj = "this->" + gsFldParm;
		}
	}
	var sActivateFldName = "";
	if (PROJ.SecRegisterActivate && ew_IsNotEmpty(PROJ.SecRegisterActivateFld)) {
		if (GetFldObj(PROJ.SecRegisterActivateFld)) {
			sActivateFldName = gsFldName;
		}
	}
##-->
<!--##/session##-->

<?php
<!--##session phpmain##-->

	var $FormClassName = "form-horizontal ewForm ewRegisterForm";

<!--## if (bMultiPage) { ##-->
	var $MultiPages; // Multi pages object
<!--## } ##-->

<!--##include captcha-script.php/phpcaptcha_var##-->

	//
	// Page main
	//
	function Page_Main() {
		global $UserTableConn, $Security, $Language, $gsLanguage, $gsFormError, $objForm;
		global $Breadcrumb;

	<!--## if (bUseTabularFormForDesktop) { ##-->
		$this->FormClassName = "ewForm ewRegisterForm";
		if (ew_IsMobile())
			$this->FormClassName = ew_Concat("form-horizontal", $this->FormClassName, " ");
	<!--## } ##-->

		// Set up Breadcrumb
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("<!--##=CTRL.CtrlID##-->", "RegisterPage", $url, "", "", TRUE);

		$bUserExists = FALSE;
		
		if (@$_POST["a_register"] <> "") {
		
			// Get action
			$this->CurrentAction = $_POST["a_register"];

			$this->LoadFormValues(); // Get form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
			
		} else {
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}
		
		<!--##include captcha-script.php/phpcaptcha_php##-->
		
		<!--##
			if (PROJ.SecRegisterActivate && ew_IsNotEmpty(PROJ.SecRegisterActivateFld)) {
		##-->
		// Handle email activation
		if (@$_GET["action"] <> "") {
			$sAction = $_GET["action"];
			$sEmail = @$_GET["email"];
			$sCode = @$_GET["token"];
			@list($sApprovalCode, $sUsr, $sPwd) = explode(",", $sCode, 3);
			$sApprovalCode = ew_Decrypt($sApprovalCode);
			$sUsr = ew_Decrypt($sUsr);
			$sPwd = ew_Decrypt($sPwd);
			if ($sEmail == $sApprovalCode) {
				if (strtolower($sAction) == "confirm") { // Email activation
					if ($this->ActivateEmail($sEmail)) { // Activate this email
						if ($this->getSuccessMessage() == "")
							$this->setSuccessMessage($Language->Phrase("ActivateAccount")); // Set up message acount activated
	<!--## if (PROJ.SecRegisterAutoLogin && ew_IsNotEmpty(sLoginIDFldObj) && ew_IsNotEmpty(sPasswordFldObj)) { ##-->
						// Auto login user from cookie values
						//$sUsr = ew_Decrypt(@$_COOKIE[EW_PROJECT_NAME]['Username']);
						//$sPwd = ew_Decrypt(@$_COOKIE[EW_PROJECT_NAME]['Password']);
						if ($Security->ValidateUser($sUsr, $sPwd, TRUE))
							$this->Page_Terminate(<!--##=sRegisterReturnPage##-->); // Go to return page
	<!--## } else { ##-->
						$this->Page_Terminate("<!--##=sFnLogin##-->"); // Go to login page
	<!--## } ##-->
					}
				}
			}
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("ActivateFailed")); // Set activate failed message
			$this->Page_Terminate("<!--##=sFnLogin##-->"); // Go to login page
		}
		<!--##
			}
		##-->
		
		switch ($this->CurrentAction) {
		
			case "I": // Blank record, no action required
				break;
		
			case "A": // Add
		
		<!--## if (!LOGINIDFIELD.FldAutoIncrement) { ##-->
				// Check for duplicate User ID
				$sFilter = str_replace("%u", ew_AdjustSql($<!--##=sLoginIDFldObj##-->->CurrentValue, EW_USER_TABLE_DBID), EW_USER_NAME_FILTER);
				// Set up filter (SQL WHERE clause) and get return SQL
				// SQL constructor in <!--##=gsTblVar##--> class, <!--##=gsTblVar##-->info.php
				$this->CurrentFilter = $sFilter;
				$sUserSql = $this->SQL();
		
				if ($rs = $UserTableConn->Execute($sUserSql)) {
					if (!$rs->EOF) {
						$bUserExists = TRUE;
						$this->RestoreFormValues(); // Restore form values
						$this->setFailureMessage($Language->Phrase("UserExists")); // Set user exist message
					}
					$rs->Close();
				}
		<!--## } else { ##-->
				$bUserExists = FALSE;
		<!--## } ##-->
		
				if (!$bUserExists) {
					$this->SendEmail = TRUE; // Send email on add success
					if ($this->AddRow()) { // Add record
		<!--##
			if (PROJ.SecRegisterEmail && ew_IsNotEmpty(PROJ.SecEmailFld)) {
		##-->
		
						$Email = $this->PrepareRegisterEmail();
						// Get new recordset
						$this->CurrentFilter = $this->KeyFilter();
						$sSql = $this->SQL();
						$rsnew = $UserTableConn->Execute($sSql);
						$row = $rsnew->fields;
	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Other","Email_Sending")) { ##-->
						$Args = array();
						$Args["rs"] = $row;
						$bEmailSent = FALSE;
						if ($this->Email_Sending($Email, $Args))
							$bEmailSent = $Email->Send();
	<!--## } else { ##-->
						$bEmailSent = $Email->Send();
	<!--## } ##-->
						// Send email failed
						if (!$bEmailSent)
							$this->setFailureMessage($Email->SendErrDescription);

	<!--## if (bUserProfile && bMultiLanguage) { ##-->
						// Save user language
						global $UserProfile;
						$sUsername = $row['<!--##=SQuote(PROJ.SecLoginIDFld)##-->'];
						$UserProfile->SetLanguageId($sUsername, $gsLanguage);
	<!--## } ##-->

		<!--##
			}
		##-->

		<!--## if (PROJ.SecRegisterActivate) { ##-->
						if ($this->getSuccessMessage() == "")
							$this->setSuccessMessage($Language->Phrase("RegisterSuccessActivate")); // Activate success
		<!--## } else { ##-->
						if ($this->getSuccessMessage() == "")
							$this->setSuccessMessage($Language->Phrase("RegisterSuccess")); // Register success
			<!--## if (PROJ.SecRegisterAutoLogin && ew_IsNotEmpty(sLoginIDFldObj) && ew_IsNotEmpty(sPasswordFldObj)) { ##-->
						// Auto login user
						if ($Security->ValidateUser($<!--##=sLoginIDFldObj##-->->CurrentValue, $<!--##=sPasswordFldObj##-->->FormValue, TRUE)) {
							// Nothing to do
						}
			<!--## } ##-->
		<!--## } ##-->
		
						$this->Page_Terminate(<!--##=sRegisterReturnPage##-->); // Return
					} else {
						$this->RestoreFormValues(); // Restore form values
					}
				}
		
		}
		
		// Render row
		<!--## if (bRegisterConfirm) { ##-->
		if ($this->CurrentAction == "F") { // Confirm page
			$this->RowType = EW_ROWTYPE_VIEW; // Render view
		} else {
			$this->RowType = EW_ROWTYPE_ADD; // Render add
		}
		<!--## } else { ##-->
		$this->RowType = EW_ROWTYPE_ADD; // Render add
		<!--## } ##-->
		$this->ResetAttrs();
		$this->RenderRow();
	}
<!--##/session##-->
?>

<!--##session htmtable##-->
<div class="ewToolbar">
<!--##include phpcommon.php/breadcrumb##-->
<!--##include phpcommon.php/language##-->
<div class="clearfix"></div>
</div>

<!--##include phpcommon.php/header-message##-->
<!--##include phpcommon.php/phpcommon-message##-->

<form name="<!--##=sFormName##-->" id="<!--##=sFormName##-->" class="<?php echo $<!--##=sPageObj##-->->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">

<?php if ($<!--##=sPageObj##-->->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $<!--##=sPageObj##-->->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="<!--##=gsTblVar##-->">
<input type="hidden" name="a_register" id="a_register" value="A">
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<!--## if (bRegisterConfirm) { ##-->
<?php if ($<!--##=gsTblVar##-->->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } ?>
<!--## } ##-->

<!--## if (bUseCustomTemplate) { ##-->
<!--##~SYSTEMFUNCTIONS.GetCustomTemplate()##-->
<!--## } ##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
<div class="ewDesktop">
<!--## } ##-->

<!--##
	var sMainTableStyle = "";
	var sPageTableStyle = "";
	var sPageZeroTableStyle = "";
	if (bUseCustomTemplate) {
		sPageZeroTableStyle = " style=\"display: none\"";
		if (!bUseCustomMultiPageTemplate)
			sMainTableStyle = " style=\"display: none\"";
		sPageTableStyle = " style=\"display: none\"";
	}

	// Field with page index = 0
	if (bMultiPage && bMultiPageZero) {
		var sTableId = ewTableId + "0";
##-->
<?php if ($<!--##=sPageObj##-->->MultiPages->Items[0]->Visible) { ?>

<!--## if (bUseTabularFormForDesktop) { ##-->
<div<!--##=sPageZeroTableStyle##-->>
<table id="<!--##=sTableId##-->"<!--##=ewCSSDesktopTableClass##-->>
<!--## } else { ##-->

<div<!--##=ewCSSTableClass##--><!--##=sPageZeroTableStyle##-->>

<!--## } ##-->

<!--##
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				if (glFldPageIndex == 0) {
##-->
<!--##include register.php/fieldlayout##-->
<!--##
				}
			}
		} // Field
##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
</table>
</div>
<!--## } else { ##-->

</div>

<!--## } ##-->

<?php } ?>
<!--##
	}
##-->

<!--##
	// Multi page
	if (bMultiPage) {
##-->
<div class="ewMultiPage"<!--##=sMainTableStyle##-->>
<!--## if (bUseAccordionForMultiPage) { ##-->
<div class="panel-group" id="<!--##=sPageObj##-->">
<!--## } else { ##-->
<div class="tabbable" id="<!--##=sPageObj##-->">
	<ul class="nav<?php echo $<!--##=sPageObj##-->->MultiPages->NavStyle() ?>">
<!--##
		for (var i = 1; i <= nPage; i++) {
			if (String(i) in dPages) {
##-->
		<li<?php echo $<!--##=sPageObj##-->->MultiPages->TabStyle("<!--##=i##-->") ?>><a href="#tab_<!--##=gsTblVar##--><!--##=i##-->" data-toggle="tab"><?php echo $<!--##=gsTblVar##-->->PageCaption(<!--##=i##-->) ?></a></li>
<!--##
			} else {
##-->
		<li style="display: none"><a href="#tab_<!--##=gsTblVar##--><!--##=i##-->" data-toggle="tab"></a></li>
<!--##
			}
		} // Page
##-->
	</ul>
	<div class="tab-content">
<!--## } ##-->
<!--##
	}
##-->

<!--##
	for (var pageidx = 1; pageidx <= nPage; pageidx++) {
		var sTableId = ewTableId + ((bMultiPage) ? pageidx : "");
		if (bMultiPage) {
##-->
<!--## if (bUseAccordionForMultiPage) { ##-->
	<div class="panel panel-default<?php echo $<!--##=sPageObj##-->->MultiPages->PageStyle("<!--##=pageidx##-->") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#<!--##=sPageObj##-->" href="#tab_<!--##=gsTblVar##--><!--##=pageidx##-->"><?php echo $<!--##=gsTblVar##-->->PageCaption(<!--##=pageidx##-->) ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $<!--##=sPageObj##-->->MultiPages->PageStyle("<!--##=pageidx##-->") ?>" id="tab_<!--##=gsTblVar##--><!--##=pageidx##-->">
			<div class="panel-body">
<!--## } else { ##-->
		<div class="tab-pane<?php echo $<!--##=sPageObj##-->->MultiPages->PageStyle("<!--##=pageidx##-->") ?>" id="tab_<!--##=gsTblVar##--><!--##=pageidx##-->">
<!--## } ##-->
<!--##
		}
		if (!PROJ.RegisterMultiPage || (PROJ.RegisterMultiPage && String(pageidx) in dPages)) {
##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
<div>
<table id="<!--##=sTableId##-->"<!--##=ewCSSDesktopTableClass##--><!--##=sPageTableStyle##-->>
<!--## } else { ##-->

<div<!--##=ewCSSTableClass##--><!--##=sPageTableStyle##-->>

<!--## } ##-->

<!--##
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				if (!PROJ.RegisterMultiPage || (PROJ.RegisterMultiPage && glFldPageIndex == pageidx)) {
##-->
<!--##include register.php/fieldlayout##-->
<!--##
				}
			}
		} // Field
##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
</table>
</div>
<!--## } else { ##-->

</div>

<!--## } ##-->

<!--## if (bMultiPage && bUseCustomMultiPageTemplate) { ##-->
<!--##~SYSTEMFUNCTIONS.GetCustomTemplatePage(pageidx)##-->
<!--## } ##-->

<!--##
		}
		if (bMultiPage) {
##-->
<!--## if (bUseAccordionForMultiPage) { ##-->
			</div>
		</div>
	</div>
<!--## } else { ##-->
		</div>
<!--## } ##-->
<!--##
		}
	} // Page
##-->

<!--##
	// Multi page
	if (bMultiPage) {
##-->
<!--## if (bUseAccordionForMultiPage) { ##-->
</div>
<!--## } else { ##-->
	</div>
</div>
<!--## } ##-->
</div>
<!--##
	}
##-->

<!--##include captcha-script.php/phpcaptcha_htm##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
<div class="ewDesktopButton">
<!--## } else { ##-->

<div class="form-group">
	<div class="<!--##=ewBootstrapOffsetClass##-->">

<!--## } ##-->

<!--## if (bRegisterConfirm) { ##-->
<?php if ($<!--##=gsTblVar##-->->CurrentAction <> "F") { // Confirm page ?>
<button class="<!--##=sSubmitButtonClass##-->" name="btnAction" id="btnAction" type="<!--##=sButtonType##-->"<!--##=sConfirmButtonSubmit##-->><?php echo $Language->Phrase("RegisterBtn") ?></button>
<?php } else { ?>
<button class="<!--##=sSubmitButtonClass##-->" name="btnAction" id="btnAction" type="<!--##=sButtonType##-->"<!--##=sButtonSubmit##-->><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="<!--##=sCancelButtonClass##-->" name="btnCancel" id="btnCancel" type="<!--##=sButtonType##-->"<!--##=sCancelButtonSubmit##-->><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
<!--## } else { ##-->
<button class="<!--##=sSubmitButtonClass##-->" name="btnAction" id="btnAction" type="<!--##=sButtonType##-->"<!--##=sButtonSubmit##-->><?php echo $Language->Phrase("RegisterBtn") ?></button>
<!--## } ##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
</div>
<!--## } else { ##-->

	</div>
</div>

<!--## } ##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
</div>
<!--## } ##-->

</form>

<!--## if (bUseCustomTemplate) { ##-->
<!--##~SYSTEMFUNCTIONS.RenderCustomTemplate()##-->
<!--## } ##-->

<script type="text/javascript">
<!--##=sFormName##-->.Init();
</script>

<!--##include phpcommon.php/footer-message##-->
<!--##/session##-->


<!--##session fieldlayout##-->
<!--##
	if (!goFld.FldAutoIncrement && ew_IsEmpty(goFld.FldAutoUpdateValue)) {
		// Skip User Level Field, Activate Field and Hidden Field
		if (goFld.FldName != DB.SecUserLevelFld &&
			goFld.FldName != sActivateFldName &&
			!(goFld.FldHtmlTag == "HIDDEN" && ew_IsNotEmpty(goFld.FldDefault))) {
			sHeaderSpanId = "elh_" + gsTblVar + "_" + gsFldParm;
			sForLabel = ew_UseForLabel(goFld) ? " for=\"" + gsFldVar + "\"" : "";
##-->
<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=gsFldName##--> ?>

<!--## if (bUseTabularFormForDesktop) { ##-->
	<tr id="r_<!--##=gsFldParm##-->">
		<td><span id="<!--##=sHeaderSpanId##-->"><!--##=SYSTEMFUNCTIONS.FieldCaption()##--></span></td>
		<td<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldAdd()##--><?php echo $<!--##=gsFldObj##-->->CustomMsg ?></td>
	</tr>
<!--## } else { ##-->

	<div id="r_<!--##=gsFldParm##-->" class="form-group">
		<label id="<!--##=sHeaderSpanId##-->"<!--##=sForLabel##--> class="<!--##=ewBootstrapLabelClass##-->"><!--##=SYSTEMFUNCTIONS.FieldCaption()##--></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->"><div<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldAdd()##--><?php echo $<!--##=gsFldObj##-->->CustomMsg ?></div></div>
	</div>

<!--## } ##-->

<?php } ?>
<!--##
			if (goFld.FldName == PROJ.SecPasswdFld) { // Generate Confirm Password Field
				sTmpFldVar = goFld.FldVar; // Save Field Variable Name
				gsFldVar = "c_" + gsFldParm; // New Variable as c_FldVar
				goFld.FldVar = gsFldVar;
				sHeaderSpanId = "elh_c_" + gsTblVar + "_" + gsFldParm;
##-->
<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=gsFldName##--> ?>

<!--## if (bUseTabularFormForDesktop) { ##-->
	<tr id="r_c_<!--##=gsFldParm##-->">
		<td><span id="<!--##=sHeaderSpanId##-->"><!--##=SYSTEMFUNCTIONS.FieldCaption()##--></span></td>
		<td<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldAdd()##--></td>
	</tr>
<!--## } else { ##-->

	<div id="r_c_<!--##=gsFldParm##-->" class="form-group">
		<label id="<!--##=sHeaderSpanId##-->" for="<!--##=gsFldVar##-->" class="<!--##=ewBootstrapLabelClass##-->"><!--##=SYSTEMFUNCTIONS.FieldCaption()##--></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->"><div<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldAdd()##--></div></div>
	</div>

<!--## } ##-->

<?php } ?>
<!--##
				goFld.FldVar = sTmpFldVar; // Restore Field Variable Name
			}
		// Hidden field
		} else if (goFld.FldHtmlTag == "HIDDEN" && ew_IsNotEmpty(goFld.FldDefault)) {
##-->
	<!--##=SYSTEMFUNCTIONS.FieldAdd()##-->
<!--##
		}
	}
##-->
<!--##/session##-->


<?php
<!--##session phpfunction##-->
<!--##
	if (PROJ.SecRegisterActivate && ew_IsNotEmpty(PROJ.SecRegisterActivateFld)) {
		if (GetFldObj(PROJ.SecRegisterActivateFld)) {
			sActivateFld = gsFld;
			sActivateFldName = gsFldName;
			sActivateFldVar = gsFldVar;
			sActivateFldParm = gsFldParm;
			sActivateFldQuoteS = gsFldQuoteS;
			sActivateFldQuoteE = gsFldQuoteE;
			sActivateFldValue = ActivateFieldValue(SECTABLE, FIELD);
			if (ew_IsNotEmpty(sActivateFldQuoteS)) {
				sActivateFldValue = ew_DoubleQuote(sActivateFldValue, 1);
			}
		}
##-->

	// Activate account based on email
	function ActivateEmail($email) {
		global $UserTableConn, $Language;
		$sFilter = str_replace("%e", ew_AdjustSql($email, EW_USER_TABLE_DBID), EW_USER_EMAIL_FILTER);
		$sSql = $this->GetSQL($sFilter, "");
		$UserTableConn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $UserTableConn->Execute($sSql);
		$UserTableConn->raiseErrorFn = '';
		if (!$rs)
			return FALSE;
		if (!$rs->EOF) {
			$rsnew = $rs->fields;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
			$rsact = array('<!--##=SQuote(sActivateFldName)##-->' => <!--##=sActivateFldValue##-->); // Auto register
			$this->CurrentFilter = $sFilter;
			$res = $this->Update($rsact);
			<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Other","User_Activated")) { ##-->
			if ($res) { // Call User Activated event
				$rsnew['<!--##=SQuote(sActivateFldName)##-->'] = <!--##=sActivateFldValue##-->;
				$this->User_Activated($rsnew);
			}
			<!--## } ##-->
			return $res;
		} else {
			$this->setFailureMessage($Language->Phrase("NoRecord"));
			$rs->Close();
			return FALSE;
		}
	}
<!--##
	}
##-->
<!--##/session##-->
?>


<?php
<!--##session phpevents##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Other","Email_Sending")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Other","Form_CustomValidate")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Other","User_Registered")##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Other","User_Activated")##-->
<!--##/session##-->
?>