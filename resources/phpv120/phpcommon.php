<!--##session phpcommon-config##-->
<!--##
	// Page object
	sPageObj = ew_PageObj();

	// Form object
	sFormName = ew_FormObj();

	// Handle grid object
	if (CTRL.CtrlID == "gridcls")
		sCtrlID = "grid";
	else
		sCtrlID = CTRL.CtrlID;

	// Control type
	switch (CTRL.CtrlType.toLowerCase()) {
		case "table":
			sCtrlType = "Table";
			break;
		case "report":
			sCtrlType = "Table";
			break;
		case "other":
			sCtrlType = "Other";
			break;
		default:
			sCtrlType = "Other";
			break;
	}

	// Common files
	sFnDefault = ew_GetFileNameByCtrlID("default");
	var sFnHomePage = PROJ.StartPage;
	if (sFnHomePage == "") sFnHomePage = PROJ.DefaultPage;
	if (sFnHomePage == "") sFnHomePage = sFnDefault;
	sFnLogin = ew_GetFileNameByCtrlID("login");
	sFnLogout = ew_GetFileNameByCtrlID("logout");
	sFnRegister = ew_GetFileNameByCtrlID("register");
	sFnForgotPwd = ew_GetFileNameByCtrlID("forgotpwd");
	sFnChangePwd = ew_GetFileNameByCtrlID("changepwd");
	if (DB.UseDynamicUserLevel && ew_IsNotEmpty(DB.UserLevelTbl) && ew_IsNotEmpty(DB.UserLevelIdFld)) {
		sFnUserPriv = ew_GetFileNameByCtrlID("userpriv");
		if (typeof(TABLE) != "undefined") WRKTABLE = TABLE; // Save table
		TABLE = DB.Tables(DB.UserLevelTbl);
		sUserLevelTblVar = TABLE.TblVar;
		sFnUserLevelTableList = ew_GetFileNameByCtrlID("list");
		WRKFIELD = TABLE.Fields(DB.UserLevelIdFld);
		sUserLevelIDFldParm = WRKFIELD.FldParm;
		sUrlUserPrivQuoted = "\"" + sFnUserPriv + "?" + sUserLevelIDFldParm + "=\" . $this->" + sUserLevelIDFldParm + "->CurrentValue";
		if (typeof(WRKTABLE) != "undefined") TABLE = WRKTABLE; // Restore table
	}
	
	// JavasSript popup message
	bUseJavaScriptMessage = PROJ.GetV("UseJavaScriptMessage");

	// Disable submit button
	bDisableButtonOnSubmit = PROJ.GetV("DisableButtonOnSubmit");

	// Language files
	sLanguageFiles = PROJ.LanguageFiles;
	sDefaultLanguageFile = PROJ.DefaultLanguageFile;
	if (sLanguageFiles == "") sLanguageFiles = "english.xml";
	if (sDefaultLanguageFile == "") sDefaultLanguageFile = "english.xml";
	arLanguageFile = sLanguageFiles.split(",");
	bMultiLanguage = PROJ.MultiLanguage;

	// CSS classes
	ewCSSDesktopTableClass = " class=\"table table-bordered table-striped ewDesktopTable\"";
	if (CTRL.CtrlID.toLowerCase() == "list" || CTRL.CtrlID.toLowerCase() == "grid" ||
		CTRL.CtrlID.toLowerCase() == "delete" ||
		CTRL.CtrlID.toLowerCase() == "userpriv" || CTRL.CtrlID.toLowerCase() == "master") {
		ewCSSTableClass = " class=\"table ewTable\"";
	} else if (CTRL.CtrlID.toLowerCase() == "preview") {
		ewCSSTableClass = " class=\"table ewTable ewPreviewTable\"";
	} else if (CTRL.CtrlID.toLowerCase() == "addopt") {
		ewCSSTableClass = " class=\"table ewTableAddOpt\"";
	} else if (CTRL.CtrlID.toLowerCase() == "add" || CTRL.CtrlID.toLowerCase() == "register" ||
		CTRL.CtrlID.toLowerCase() == "edit" || CTRL.CtrlID.toLowerCase() == "update" || CTRL.CtrlID.toLowerCase() == "search") {
		ewCSSTableClass = "";		
	} else {
		ewCSSTableClass = " class=\"table table-bordered table-striped ewViewTable\"";
	}
	if (CTRL.CtrlType.toLowerCase() == "table" || CTRL.CtrlType.toLowerCase() == "report")
		ewTableId = "tbl_" + TABLE.TblVar + CTRL.CtrlID;
	else
		ewTableId = "tbl_" + CTRL.CtrlID;
	ewCSSTableHeaderClass = " class=\"ewTableHeader\"";
	//ewCSSTableRowClass = " class=\"ewTableRow\"";
	ewCSSTableRowClass = "";
	ewCSSTableAltRowClass = " class=\"ewTableAltRow\"";
	ewCSSHighlightClass = " class=\"ewTableHighlightRow\"";
	ewCSSSelectClass = " class=\"ewTableSelectRow\"";
	ewCSSTableFooterClass = " class=\"ewTableFooter\"";
	// Report
	if (CTRL.CtrlType.toLowerCase() == "report") {
		ewCSSGroupField = " class=\"ewGroupField\"";
		ewCSSGroupName = " class=\"ewGroupName\"";
		ewCSSGroupHeader = " class=\"ewGroupHeader\"";
		ewCSSGroupSummary = " class=\"ewGroupSummary\"";
		ewCSSGroupAggregate = " class=\"ewGroupAggregate\"";
		ewCSSGrandSummary = " class=\"ewGrandSummary\"";
		ewCSSGroupIndent = " class=\"ewGroupIndent\"";
	}
	// Message
	ewCSSMsg = " class=\"ewMessage\"";
	// Bootstrap 3 classes
	ewBootstrapLeftColumnClass = "col-sm-2";
	ewBootstrapRightColumnClass = "col-sm-10";
	ewBootstrapOffsetClass = "col-sm-offset-2 col-sm-10";
	ewBootstrapLabelClass = ewBootstrapLeftColumnClass + " control-label ewLabel";
	ewBootstrapInputClass = "form-control ewControl";

	// Get mobile max width
	var sVisibleMobileClass = "";
	var sHiddenMobileClass = "";
	if (PROJ.GetV("UseResponsiveLayout")) {
		sVisibleMobileClass = "visible-xs";
		sHiddenMobileClass = "hidden-xs";
	}

	// Security
	bSecurityEnabled = !(PROJ.SecType == "None" || PROJ.SecType == "");
	bHardCodeAdmin = (PROJ.SecType == "Both" || PROJ.SecType == "Hard Code");
	var bUserTable = ew_HasUserTable();
	sSecTblVar = "";
	if (bUserTable) {
		SECTABLE = DB.Tables(PROJ.SecTbl);
		sSecTblVar = SECTABLE.TblVar;
	}
	bStaticUserLevel = (bUserTable && (!DB.UseDynamicUserLevel && ew_IsNotEmpty(DB.SecUserLevelFld)));
	bDynamicUserLevel = (bUserTable && (DB.UseDynamicUserLevel && ew_IsNotEmpty(DB.UserLevelTbl) && ew_IsNotEmpty(DB.SecUserLevelFld)));
	bUserLevel = (bStaticUserLevel || bDynamicUserLevel);
	bUserID = (bUserTable && ew_IsNotEmpty(DB.SecuUserIDFld));
	bParentUserID = (bUserID && ew_IsNotEmpty(DB.SecuParentUserIDFld));
	
	bUserProfile = (bUserTable && ew_IsNotEmpty(DB.SecUserProfileFld));
	bCheckConcurrentUser = (bUserProfile && PROJ.GetV("CheckConcurrentUser"));
	bCheckLoginRetry = (bUserProfile && PROJ.GetV("CheckLoginRetry"));
	bCheckPasswordExpiry = (bUserProfile && PROJ.GetV("CheckPasswordExpiry"));
	bResetConcurrentUser = (bCheckConcurrentUser && PROJ.GetV("ResetConcurrentUser"));
	bResetLoginRetry = (bCheckLoginRetry && PROJ.GetV("ResetLoginRetry"));
	bSetPasswordExpired = (bCheckPasswordExpiry && PROJ.GetV("SetPasswordExpired"));
	bResendRegisterEmail = (bUserTable && PROJ.SecRegisterEmail && ew_IsNotEmpty(PROJ.SecEmailFld) && PROJ.GetV("ResendRegisterEmail"));

	bCheckPasswordStrength = PROJ.GetV("CheckPasswordStrength");
	iMinPasswordStrength = PROJ.GetV("MinPasswordStrength");
	bGeneratePassword = PROJ.GetV("GeneratePassword");
	iGenPasswordLength = PROJ.GetV("GenPasswordLength");

	sSubmitButtonClass = "btn btn-primary ewButton";
	sResetButtonClass = "btn btn-default ewButton";
	sCancelButtonClass = sResetButtonClass;

	// Use place holder for textbox
	sUsePlaceHolder = PROJ.GetV("UsePlaceHolder");

	// Cutom file relative paths
	sRelativePath = "";
	sRelativePathPrefix = "";
	sAppRootRelativePath = "";

	bMultiPage = false;
	bShowMultiPageForDetails = false;

	bUseTabularFormForDesktop = PROJ.GetV("UseTabularFormForDesktop");
##-->
<!--##/session##-->


<!--##session phpcommon-directive##-->
<?php
<!--##=SYSTEMFUNCTIONS.PhpCgiPath()##-->
<!--##
	if (CTRL.CtrlID != "ewaddopt" &&
		CTRL.CtrlID != "ewlookup") {
##-->
if (session_id() == "") session_start(); // Initialize Session data
<!--##
	}
##-->
ob_start(); // Turn on output buffering
<!--## if (sRelativePath != "") { ##-->
$EW_RELATIVE_PATH = "<!--##=sRelativePath##-->";
<!--## } ##-->
?>
<!--##/session##-->

<!--##session breadcrumb##-->
<!--##=sExpStart##-->
<?php $Breadcrumb->Render(); ?>
<!--##=sExpEnd##-->
<!--##/session##-->

<!--##session language##-->
<!--##=sExpStart##-->
<?php echo $Language->SelectionForm(); ?>
<!--##=sExpEnd##-->
<!--##/session##-->

<!--##session phpcommon-message##-->
<?php
$<!--##=sPageObj##-->->ShowMessage();
?>
<!--##/session##-->

<!--##session header-message##-->
<?php $<!--##=sPageObj##-->->ShowPageHeader(); ?>
<!--##/session##-->

<!--##session footer-message##-->
<?php
$<!--##=sPageObj##-->->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<!--##/session##-->


<!--##session render-menu##-->
<?php
// Generate all menu items
$RootMenu->IsRoot = TRUE;
<!--##
	for (var i = 1; i <= MENULIST.Count(); i++) {

		if (MENULIST(i).MenuShow) {

			bMenuGroup = MENULIST(i).MenuGroup;

			if (MENULIST(i).MenuCustomUrl) {

				sUrl = MENULIST(i).MenuUrl.replace(new RegExp("\"", "g"), "&quot;");
				sMenuId = MENULIST(i).MenuId;
				sText = MENULIST(i).MenuText;
				sMenuName = sCustomMenuIdPrefix + ew_EncodeName(sText);
				iAnonymous = MENULIST(i).MenuAnonymous;
				bAnonymous = ((iAnonymous & 8) == 8);
				if (bAnonymous) {
					sTableSecChk = "TRUE";
				} else if (bSecurityEnabled) {
					sTableSecChk = "IsLoggedIn()";
				} else {
					sTableSecChk = "TRUE";
				}
##-->
<!--## if (bGenReportMenu) { ##-->
$RootMenu->AddMenuItem(<!--##=sMenuId##-->, $Language->MenuPhrase("<!--##=sMenuId##-->", "MenuText"), <!--##=ew_DoubleQuote(sUrl, 1)##-->, <!--##=MENULIST(i).MenuParentId##-->, <!--##=ew_DoubleQuote(MENULIST(i).MenuSource, 1)##-->, <!--##=sTableSecChk##-->, <!--##=ew_Val(bMenuGroup)##-->, TRUE);
<!--## } else { ##-->
$RootMenu->AddMenuItem(<!--##=sMenuId##-->, "<!--##=sMenuName##-->", $Language->MenuPhrase("<!--##=sMenuId##-->", "MenuText"), <!--##=ew_DoubleQuote(sUrl, 1)##-->, <!--##=MENULIST(i).MenuParentId##-->, <!--##=ew_DoubleQuote(MENULIST(i).MenuSource, 1)##-->, <!--##=sTableSecChk##-->, <!--##=ew_Val(bMenuGroup)##-->, TRUE);
<!--## } ##-->
<!--##
			} else {

				sMenuId = MENULIST(i).MenuId;
				sText = MENULIST(i).MenuText;
				//sMenuType = MENULIST(i).MenuType
				sMenuUrl = MENULIST(i).MenuUrl;
				
				if (MENULIST(i).MenuSource == "") {
				
					TABLE = DB.Tables(MENULIST(i).MenuUrl);
					sTblVar = TABLE.TblVar;
					sMenuName = sMenuIdPrefix + sTblVar;
					sTblName = TABLE.TblName;
					if (TABLE.TblType == "REPORT") {
					 	if (TABLE.TblReportType == "custom") {
							sFn = sTblName;
							if (PROJ.OutputNameLCase) sFn = sFn.toLowerCase();
							// Custom file output folder
							sFn = ew_OutputRelPath(TABLE.OutputFolder) + sFn;
						} else {
							sFn = ew_GetFileNameByCtrlID("report");
						}
					} else {
						sFn = ew_GetFileNameByCtrlID("list");
					}
					if (TABLE.TblIsDetail)
						sFn += "?cmd=resetall";

					// Table allow anonymous access
					bAnonymous = false; // Use table anonymous security

					// Dynamic user tables
					bDynamicUserTables = (bDynamicUserLevel &&
						(sTblName == DB.UserLevelTbl || sTblName == DB.UserLevelPrivTbl));

					if (TABLE.TblType == "REPORT" && TABLE.TblReportType == "custom" && TABLE.TblRptSrc != "")
						sTblName = TABLE.TblRptSrc;

					sQuotedTblName = "'" + PROJ.ProjID + SQuote(sTblName) + "'";

				} else {

					if (sMenuUrl.indexOf("|") > 0)
						sTblName = sMenuUrl.split("|")[0];
					else
						sTblName = sMenuUrl;
					sMenuName = sReportMenuIdPrefix + ew_EncodeName(sMenuUrl);
					
					sFn = MENULIST(i).MenuExtUrl;
					iAnonymous = MENULIST(i).MenuAnonymous;
					bAnonymous = ((iAnonymous & 8) == 8);
					bDynamicUserTables = false;

					sQuotedTblName = "'" + MENULIST(i).MenuSource + SQuote(sTblName) + "'";

				}

				if (bAnonymous) {
					sTableSecChk = "TRUE";
				} else if (bDynamicUserTables) {
					sTableSecChk = "(@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN";
				} else if (bUserLevel) {
					sTableSecChk = "AllowListMenu(" + sQuotedTblName + ")";
				} else if (bSecurityEnabled) {
					sTableSecChk = "IsLoggedIn()";
				} else {
					sTableSecChk = "TRUE";
				}

##-->
<!--## if (bGenReportMenu) { ##-->
$RootMenu->AddMenuItem(<!--##=sMenuId##-->, $Language->MenuPhrase("<!--##=sMenuId##-->", "MenuText"), <!--##=ew_DoubleQuote(sFn, 1)##-->, <!--##=MENULIST(i).MenuParentId##-->, <!--##=ew_DoubleQuote(MENULIST(i).MenuSource, 1)##-->, <!--##=sTableSecChk##-->, <!--##=ew_Val(bMenuGroup)##-->);
<!--## } else { ##-->
$RootMenu->AddMenuItem(<!--##=sMenuId##-->, "<!--##=sMenuName##-->", $Language->MenuPhrase("<!--##=sMenuId##-->", "MenuText"), <!--##=ew_DoubleQuote(sFn, 1)##-->, <!--##=MENULIST(i).MenuParentId##-->, <!--##=ew_DoubleQuote(MENULIST(i).MenuSource, 1)##-->, <!--##=sTableSecChk##-->, <!--##=ew_Val(bMenuGroup)##-->);
<!--## } ##-->
<!--##
			}
		}
	} // Menu
	
	if (bSecurityEnabled) {
		if (PROJ.SecChangePwdPage) {
##-->
<!--## if (bGenReportMenu) { ##-->
$RootMenu->AddMenuItem(-2, $Language->Phrase("ChangePwd"), <!--##=ew_DoubleQuote(sFnChangePwd, 1)##-->, -1, "", IsLoggedIn() && !IsSysAdmin());
<!--## } else { ##-->
$RootMenu->AddMenuItem(-2, "<!--##=sMenuIdPrefix##-->changepwd", $Language->Phrase("ChangePwd"), <!--##=ew_DoubleQuote(sFnChangePwd, 1)##-->, -1, "", IsLoggedIn() && !IsSysAdmin());
<!--## } ##-->
<!--##
		}
##-->
<!--## if (bGenReportMenu) { ##-->
$RootMenu->AddMenuItem(-1, $Language->Phrase("Logout"), <!--##=ew_DoubleQuote(sFnLogout, 1)##-->, -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, $Language->Phrase("Login"), <!--##=ew_DoubleQuote(sFnLogin, 1)##-->, -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("<!--##=sFnLogin##-->")) <> "<!--##=sFnLogin##-->");
<!--## } else { ##-->
$RootMenu->AddMenuItem(-1, "<!--##=sMenuIdPrefix##-->logout", $Language->Phrase("Logout"), <!--##=ew_DoubleQuote(sFnLogout, 1)##-->, -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "<!--##=sMenuIdPrefix##-->login", $Language->Phrase("Login"), <!--##=ew_DoubleQuote(sFnLogin, 1)##-->, -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("<!--##=sFnLogin##-->")) <> "<!--##=sFnLogin##-->");
<!--## } ##-->
<!--##
	}
##-->
$RootMenu->Render();
?>
<!--##/session##-->