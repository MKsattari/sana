<!--##session common_config##-->
<!--##

	// Export
	sProjName = PROJ.ProjName;
	sProjVar = PROJ.ProjVar;

	if (IsExport() || ew_IsNotEmpty(PROJ.AppRelatedProject)) {
		sTmplExpStart = "<?php if (@$gsExport == \"\") { ?>";
		sTmplExpEnd = "<?php } ?>";
		sTmplHtmlExpStart = "<?php if (@$gsExport == \"\" || @$gsExport == \"print\") { ?>";
		sTmplHtmlExpEnd = "<?php } ?>";
		sTmplPrintExpStart = "<?php if (@$gsExport == \"\" || @$gsExport == \"print\") { ?>";
	} else {
		sTmplExpStart = "";
		sTmplExpEnd = "";
		sTmplHtmlExpStart = "";
		sTmplHtmlExpEnd = "";
		sTmplPrintExpStart = "";
	}
	sTmplSkipStart = "<?php if (@!$gbSkipHeaderFooter) { ?>";
	sTmplSkipEnd = "<?php } ?>";
	
	// Configure options
	bUseAddOpt = UseAddOpt();
	bUseEmailExport = UseEmailExport(); // Export to Email
	bUseTooltip = UseTooltip(); // Use Tooltip
	bUseJSTemplate = true; // Use JS Template

	bDisableProjectStyles = PROJ.GetV("DisableProjectStyles");

	sMobileMenuNavbarClass = (PROJ.GetV("ThemeMobileMenuInverted") == "1") ? "navbar-inverse" : "navbar-default";
##-->
<!--##/session##-->

<!--##session header_top##-->
<?php
// Compatibility with PHP Report Maker
if (!isset($Language)) {
	include_once "<!--##=ew_GetFileNameByCtrlID("ewconfig")##-->";
	include_once "<!--##=ew_GetFileNameByCtrlID("ewshared")##-->";
	$Language = new cLanguage();
}
// Responsive layout
if (ew_IsResponsiveLayout()) {
	$gsHeaderRowClass = "<!--##=sHiddenMobileClass##--> ewHeaderRow";
	$gsMenuColumnClass = "<!--##=sHiddenMobileClass##--> ewMenuColumn";
	$gsSiteTitleClass = "<!--##=sHiddenMobileClass##--> ewSiteTitle";
} else {
	$gsHeaderRowClass = "ewHeaderRow";
	$gsMenuColumnClass = "ewMenuColumn";
	$gsSiteTitleClass = "ewSiteTitle";
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $Language->ProjectPhrase("BodyTitle") ?></title>
<!--##=SYSTEMFUNCTIONS.CharSet()##-->
<!--##=sTmplPrintExpStart##-->
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>bootstrap3/css/<?php echo ew_CssFile("bootstrap.css") ?>">
<!--## if (PROJ.GetV("ThemeUseBootstrapTheme") == "1") { ##-->
<!-- Optional theme -->
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>bootstrap3/css/<?php echo ew_CssFile("bootstrap-theme.css") ?>">
<!--## } ##-->
<!--##=sTmplExpEnd##-->

<!--##=sTmplPrintExpStart##-->
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?><!--##=ew_GetFileNameByCtrlID("jquery.fileupload.css")##-->">
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?><!--##=ew_GetFileNameByCtrlID("jquery.fileupload-ui.css")##-->">
<!--## if (PROJ.GetV("UseColorbox")) { ##-->
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>colorbox/colorbox.css">
<!--## } ##-->
<!--##=sTmplExpEnd##-->

<!--##=sTmplPrintExpStart##-->
<?php if (ew_IsResponsiveLayout()) { ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php } ?>
<!--## if (!bDisableProjectStyles) { ##-->
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?><?php echo ew_CssFile(EW_PROJECT_STYLESHEET_FILENAME) ?>">
<?php if (@$gsCustomExport == "pdf" && EW_PDF_STYLESHEET_FILENAME <> "") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?><?php echo EW_PDF_STYLESHEET_FILENAME ?>">
<?php } ?>
<!--## } ##-->
<!--##=SYSTEMFUNCTIONS.CSSFile()##-->

<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?><!--##=ew_FolderPath("_jquery")##-->/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?><!--##=ew_FolderPath("_jquery")##-->/jquery.storageapi.min.js"></script>

<!--## if (bCheckPasswordStrength) { ##-->
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?><!--##=ew_FolderPath("_jquery")##-->/pStrength.jquery.js"></script>
<!--## } ##-->
<!--## if (bGeneratePassword) { ##-->
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?><!--##=ew_FolderPath("_jquery")##-->/pGenerator.jquery.js"></script>
<!--## } ##-->

<!--##=sTmplExpEnd##-->

<!--##=sTmplPrintExpStart##-->
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>bootstrap3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?><!--##=ew_GetFileNameByCtrlID("typeahead.js")##-->"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?><!--##=ew_GetFileNameByCtrlID("jquery.ui.widget.js")##-->"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?><!--##=ew_GetFileNameByCtrlID("load-image.all.min.js")##-->"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?><!--##=ew_GetFileNameByCtrlID("jqueryfileupload.min.js")##-->"></script>
<!--## if (PROJ.GetV("UseColorbox")) { ##-->
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>colorbox/jquery.colorbox-min.js"></script>
<!--## } ##-->
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?><!--##=ew_GetFileNameByCtrlID("mobile-detect.js")##-->"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?><!--##=ew_GetFileNameByCtrlID("moment.js")##-->"></script>

<!--##/session##-->

<!--##session header_top_2##-->
<script type="text/javascript">
var EW_LANGUAGE_ID = "<?php echo $gsLanguage ?>";
var EW_DATE_SEPARATOR = "<!--##=(PROJ.DateSeparator || "/")##-->"; // Default date separator
var EW_DEFAULT_DATE_FORMAT = "<?php echo EW_DEFAULT_DATE_FORMAT ?>"; // Default date format
var EW_DECIMAL_POINT = "<?php echo $DEFAULT_DECIMAL_POINT ?>";
var EW_THOUSANDS_SEP = "<?php echo $DEFAULT_THOUSANDS_SEP ?>";
<!--## if (bCheckPasswordStrength) { ##-->
var EW_MIN_PASSWORD_STRENGTH = <!--##=iMinPasswordStrength##-->;
<!--## } ##-->
<!--## if (bGeneratePassword) { ##-->
var EW_GENERATE_PASSWORD_LENGTH = <!--##=iGenPasswordLength##-->;
var EW_GENERATE_PASSWORD_UPPERCASE = true;
var EW_GENERATE_PASSWORD_LOWERCASE = true;
var EW_GENERATE_PASSWORD_NUMBER = true;
var EW_GENERATE_PASSWORD_SPECIALCHARS = false;
<!--## } ##-->
var EW_SESSION_TIMEOUT = <?php echo (EW_SESSION_TIMEOUT > 0) ? ew_SessionTimeoutTime() : 0 ?>; // Session timeout time (seconds)
var EW_SESSION_TIMEOUT_COUNTDOWN = <?php echo EW_SESSION_TIMEOUT_COUNTDOWN ?>; // Count down time to session timeout (seconds)
var EW_SESSION_KEEP_ALIVE_INTERVAL = <?php echo EW_SESSION_KEEP_ALIVE_INTERVAL ?>; // Keep alive interval (seconds)
var EW_RELATIVE_PATH = "<?php echo $EW_RELATIVE_PATH ?>"; // Relative path
var EW_SESSION_URL = EW_RELATIVE_PATH + "<!--##=ew_GetFileNameByCtrlID("session")##-->"; // Session URL
var EW_IS_LOGGEDIN = <?php echo IsLoggedIn() ? "true" : "false" ?>; // Is logged in
var EW_IS_AUTOLOGIN = <?php echo IsAutoLogin() ? "true" : "false" ?>; // Is logged in with option "Auto login until I logout explicitly"
var EW_LOGOUT_URL = EW_RELATIVE_PATH + "<!--##=ew_GetFileNameByCtrlID("logout")##-->"; // Logout URL
var EW_LOOKUP_FILE_NAME = "<!--##=ew_GetFileNameByCtrlID("lookup")##-->"; // Lookup file name
var EW_AUTO_SUGGEST_MAX_ENTRIES = <?php echo EW_AUTO_SUGGEST_MAX_ENTRIES ?>; // Auto-Suggest max entries
<!--## if (bUseEmailExport) { ##-->
var EW_MAX_EMAIL_RECIPIENT = <?php echo EW_MAX_EMAIL_RECIPIENT ?>;
<!--## } ##-->
var EW_DISABLE_BUTTON_ON_SUBMIT = <!--##=ew_JsVal(bDisableButtonOnSubmit)##-->;
var EW_IMAGE_FOLDER = "<!--##=GetImageFolder()##-->"; // Image folder
var EW_UPLOAD_URL = "<?php echo EW_UPLOAD_URL ?>"; // Upload URL
var EW_UPLOAD_THUMBNAIL_WIDTH = <?php echo EW_UPLOAD_THUMBNAIL_WIDTH ?>; // Upload thumbnail width
var EW_UPLOAD_THUMBNAIL_HEIGHT = <?php echo EW_UPLOAD_THUMBNAIL_HEIGHT ?>; // Upload thumbnail height
var EW_MULTIPLE_UPLOAD_SEPARATOR = "<?php echo EW_MULTIPLE_UPLOAD_SEPARATOR ?>"; // Upload multiple separator
var EW_USE_COLORBOX = <?php echo (EW_USE_COLORBOX) ? "true" : "false" ?>;
var EW_USE_JAVASCRIPT_MESSAGE = <!--##=ew_JsVal(bUseJavaScriptMessage)##-->;
var EW_MOBILE_DETECT = new MobileDetect(window.navigator.userAgent);
var EW_IS_MOBILE = EW_MOBILE_DETECT.mobile() ? true : false;
var EW_PROJECT_STYLESHEET_FILENAME = "<?php echo EW_PROJECT_STYLESHEET_FILENAME ?>"; // Project style sheet
var EW_PDF_STYLESHEET_FILENAME = "<?php echo EW_PDF_STYLESHEET_FILENAME ?>"; // Pdf style sheet
var EW_TOKEN = "<?php echo @$gsToken ?>";
var EW_CSS_FLIP = <?php echo (EW_CSS_FLIP) ? "true" : "false" ?>;
var EW_CONFIRM_CANCEL = true;
</script>
<!--##=sTmplExpEnd##-->
<!--##=sTmplPrintExpStart##-->
<!--## if (bUseJSTemplate) { ##-->
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?><!--##=ew_GetFileNameByCtrlID("jsrender.min.js")##-->"></script>
<!--## } ##-->
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?><!--##=ew_GetFileNameByCtrlID("ew.js")##-->"></script>
<!--##=sTmplExpEnd##-->
<!--##=sTmplPrintExpStart##-->
<script type="text/javascript">
var ewVar = <?php echo json_encode($EW_CLIENT_VAR); ?>;
<?php echo $Language->ToJSON() ?>
</script>
<!--##/session##-->

<!--##session header_top_3##-->
<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Global","Page_Head")) { ##-->
<?php
<!--##~SYSTEMFUNCTIONS.GetServerScript("Global","Page_Head")##-->
?>
<!--## } ##-->
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?><!--##=ew_GetFileNameByCtrlID("userglobaljs")##-->"></script>
<!--##~SYSTEMFUNCTIONS.GetClientScript("Global","Client Script")##-->
<!--##=sTmplExpEnd##-->
<!--##=SYSTEMFUNCTIONS.FavIcon()##-->
</head>
<!--## if (PROJ.GetV("UseCssFlip")) { ##-->
<body dir="rtl">
<!--## } else { ##-->
<body>
<!--## } ##-->
<!--##=sTmplSkipStart##-->
<!--##=sTmplExpStart##-->
<div class="ewLayout">
	<!-- header (begin) --><!-- ** Note: Only licensed users are allowed to change the logo ** -->
	<div id="ewHeaderRow" class="<?php echo $gsHeaderRowClass ?>"><!--##=SYSTEMFUNCTIONS.HeaderLogo()##--></div>
<?php if (ew_IsResponsiveLayout()) { ?>
<nav id="ewMobileMenu" role="navigation" class="navbar <!--##=sMobileMenuNavbarClass##--> <!--##=sVisibleMobileClass##--> hidden-print">
	<div class="container-fluid"><!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button data-target="#ewMenu" data-toggle="collapse" class="navbar-toggle" type="button">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo (EW_MENUBAR_BRAND_HYPERLINK <> "") ? EW_MENUBAR_BRAND_HYPERLINK : "#" ?>"><?php echo (EW_MENUBAR_BRAND <> "") ? EW_MENUBAR_BRAND : $Language->ProjectPhrase("BodyTitle") ?></a>
		</div>
		<div id="ewMenu" class="collapse navbar-collapse" style="height: auto;"><!-- Begin Main Menu -->
<?php
	$RootMenu = new cMenu("MobileMenu");
	$RootMenu->MenuBarClassName = "";
	$RootMenu->MenuClassName = "nav navbar-nav";
	$RootMenu->SubMenuClassName = "dropdown-menu";
	$RootMenu->SubMenuDropdownImage = "";
	$RootMenu->SubMenuDropdownIconClassName = "icon-arrow-down";
	$RootMenu->MenuDividerClassName = "divider";
	$RootMenu->MenuItemClassName = "dropdown";
	$RootMenu->SubMenuItemClassName = "dropdown";
	$RootMenu->MenuActiveItemClassName = "active";
	$RootMenu->SubMenuActiveItemClassName = "active";
	$RootMenu->MenuRootGroupTitleAsSubMenu = TRUE;
	$RootMenu->MenuLinkDropdownClass = "ewDropdown";
	$RootMenu->MenuLinkClassName = "icon-arrow-right";
?>
<!--##=SYSTEMFUNCTIONS.IncludeFile("mobilemenu","")##-->
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
<?php } ?>
	<!-- header (end) -->
<!--##/session##-->

<!--##session menu##-->
	<!-- content (begin) -->
	<div id="ewContentTable" class="ewContentTable">
		<div id="ewContentRow">
			<div id="ewMenuColumn" class="<?php echo $gsMenuColumnClass ?>">

				<!-- left column (begin) -->
				<div class="ewMenu">
<!--##=SYSTEMFUNCTIONS.IncludeFile("menu","")##-->
				</div>
				<!-- left column (end) -->

			</div>
<!--##/session##-->

<!--##session header_bottom##-->
			<div id="ewContentColumn" class="ewContentColumn">
				<!-- right column (begin) -->
				<h4 class="<?php echo $gsSiteTitleClass ?>"><?php echo $Language->ProjectPhrase("BodyTitle") ?></h4>
<!--##=sTmplExpEnd##-->
<!--##=sTmplSkipEnd##-->
<!--##/session##-->
	
<!--##session footer##-->
<!--##=sTmplExpStart##-->
<!--##=sTmplSkipStart##-->
				<!-- right column (end) -->
				<?php if (isset($gTimer)) $gTimer->Stop() ?>

			</div>
		</div>
	</div>
	<!-- content (end) -->
	<!-- footer (begin) --><!-- ** Note: Only licensed users are allowed to remove or change the following copyright statement. ** -->
	<div id="ewFooterRow" class="ewFooterRow">	
		<div class="ewFooterText"><?php echo $Language->ProjectPhrase("FooterText") ?></div>
		<!-- Place other links, for example, disclaimer, here -->		
	</div>
	<!-- footer (end) -->	
</div>
<!--##=sTmplSkipEnd##-->
<!-- search dialog -->
<div id="ewSearchDialog" class="modal" role="dialog" aria-labelledby="ewSearchDialogTitle" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="ewSearchDialogTitle"></h4></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton"><?php echo $Language->Phrase("Search") ?></button><button type="button" class="btn btn-default ewButton" data-dismiss="modal"><?php echo $Language->Phrase("CancelBtn") ?></button></div></div></div></div>
<!--## if (bUseAddOpt) { ##-->
<!-- add option dialog -->
<div id="ewAddOptDialog" class="modal" role="dialog" aria-labelledby="ewAddOptDialogTitle" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="ewAddOptDialogTitle"></h4></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton"><?php echo $Language->Phrase("AddBtn") ?></button><button type="button" class="btn btn-default ewButton" data-dismiss="modal"><?php echo $Language->Phrase("CancelBtn") ?></button></div></div></div></div>
<!--## } ##-->
<!--## if (bUseEmailExport) { ##-->
<!-- email dialog -->
<div id="ewEmailDialog" class="modal" role="dialog" aria-labelledby="ewEmailDialogTitle" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="ewEmailDialogTitle"></h4></div>
<div class="modal-body">
<!--##=SYSTEMFUNCTIONS.IncludeFile("ewemail","other")##-->
</div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton"><?php echo $Language->Phrase("SendEmailBtn") ?></button><button type="button" class="btn btn-default ewButton" data-dismiss="modal"><?php echo $Language->Phrase("CancelBtn") ?></button></div></div></div></div>
<!--## } ##-->
<!-- message box -->
<div id="ewMsgBox" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton" data-dismiss="modal"><?php echo $Language->Phrase("MessageOK") ?></button></div></div></div></div>

<!-- prompt -->
<div id="ewPrompt" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton"><?php echo $Language->Phrase("MessageOK") ?></button><button type="button" class="btn btn-default ewButton" data-dismiss="modal"><?php echo $Language->Phrase("CancelBtn") ?></button></div></div></div></div>

<!-- session timer -->
<div id="ewTimer" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton" data-dismiss="modal"><?php echo $Language->Phrase("MessageOK") ?></button></div></div></div></div>

<!-- tooltip -->
<div id="ewTooltip"></div>
<!--##=sTmplExpEnd##-->
<!--##=sTmplExpStart##-->
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?><!--##=ew_GetFileNameByCtrlID("usereventjs")##-->"></script>
<!--##~SYSTEMFUNCTIONS.GetClientScript("Global","Startup Script")##-->
<!--##=sTmplExpEnd##-->
</body>
</html>
<!--##/session##-->
