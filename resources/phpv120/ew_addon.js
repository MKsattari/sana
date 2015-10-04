<!--##session js_local_begin##-->
<script type="text/javascript">

<!--##
	// Submit form
	sId = CTRL.CtrlID.toLowerCase();
##-->

// Form object
<!--## if (sId == "grid") { ##-->
var <!--##=sFormName##--> = new ew_Form("<!--##=sFormName##-->", "<!--##=sId##-->");
<!--## } else { ##-->
var CurrentPageID = EW_PAGE_ID = "<!--##=sId##-->";
	<!--## if (sId == "search") { ##-->
<?php if ($<!--##=sPageObj##-->->IsModal) { ?>
var CurrentAdvancedSearchForm = <!--##=sFormName##--> = new ew_Form("<!--##=sFormName##-->", "<!--##=sId##-->");
<?php } else { ?>
var CurrentForm = <!--##=sFormName##--> = new ew_Form("<!--##=sFormName##-->", "<!--##=sId##-->");
<?php } ?>
	<!--## } else { ##-->
var CurrentForm = <!--##=sFormName##--> = new ew_Form("<!--##=sFormName##-->", "<!--##=sId##-->");
	<!--## } ##-->
<!--## } ##-->

<!--## if (sId == "list" || sId == "grid") { ##-->
<!--##=sFormName##-->.FormKeyCountName = '<?php echo $<!--##=sPageObj##-->->FormKeyCountName ?>';
<!--## } ##-->

<!--##
	if (((bInlineEdit || bInlineAdd || bInlineCopy || bGridEdit || bGridAdd) && sId == "list") ||
	sId == "grid" || sId == "add" || sId == "edit" || sId == "update" || sId == "register" || sId == "addopt") {
##-->
// Validate form
<!--##=sFormName##-->.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	
	<!--## if (sId == "update") { ##-->
	if (!ew_UpdateSelected(fobj)) {
		ew_Alert(ewLanguage.Phrase("NoFieldSelected"));
		return false;
	}
	<!--## } ##-->
	
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";

	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
	<!--##
		if ((sId == "list" && bGridAdd) || sId == "grid") {
	##-->
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
	<!--##
		}

		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {

				// Required Field
				if (IsRequiredField(goFld)) {
	##-->
			<!--##~SYSTEMFUNCTIONS.JsReqValidator()##-->
	<!--##
				}

				// Text validation
				if (IsValidateText(goFld)) {
	##-->
			<!--##~SYSTEMFUNCTIONS.JsValidator()##-->
	<!--##
				}

			}
		} // Field

		if (TABLE.TblName == DB.UserLevelTbl && bDynamicUserLevel) {
			sUserLevelIDFldVar = goTblFlds.Fields[DB.UserLevelIdFld].FldVar;
			sUserLevelNameFldVar = goTblFlds.Fields[DB.UserLevelNameFld].FldVar;
	##-->
			var elId = fobj.elements["x" + infix + "<!--##=sUserLevelIDFldVar.substr(1)##-->"];
			var elName = fobj.elements["x" + infix + "<!--##=sUserLevelNameFldVar.substr(1)##-->"];
			if (elId && elName) {
				elId.value = $.trim(elId.value);
				elName.value = $.trim(elName.value);
				if (elId && !ew_CheckInteger(elId.value))
					return this.OnError(elId, ewLanguage.Phrase("UserLevelIDInteger"));
				var level = parseInt(elId.value, 10);
				if (level == 0 && !ew_SameText(elName.value, "Default")) {
					return this.OnError(elName, ewLanguage.Phrase("UserLevelDefaultName"));
				} else if (level == -1 && !ew_SameText(elName.value, "Administrator")) {
					return this.OnError(elName, ewLanguage.Phrase("UserLevelAdministratorName"));
				} else if (level == -2 && !ew_SameText(elName.value, "Anonymous")) {
					return this.OnError(elName, ewLanguage.Phrase("UserLevelAnonymousName"));
				} else if (level < -2) {
					return this.OnError(elId, ewLanguage.Phrase("UserLevelIDIncorrect"));
				} else if (level > 0 && ew_InArray(elName.value.toLowerCase(), ["anonymous", "administrator", "default"]) > -1) {
					return this.OnError(elName, ewLanguage.Phrase("UserLevelNameIncorrect"));
				}
			}
	<!--##
		}

		if (SYSTEMFUNCTIONS.ClientScriptExist(sCtrlType, "Form_CustomValidate")) {
	##-->
			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	<!--##
		}

		if ((sId == "list" && bGridAdd) || sId == "grid") {
	##-->
		} // End Grid Add checking
	<!--##
		}
	##--> 
	}

	<!--## if (sId == "add" || sId == "edit" || sId == "register") { ##-->
<!--##include captcha-script.php/phpcaptcha_js##-->
	<!--## } ##-->

	<!--##
		if (sId == "list" && bGridAdd) {
	##-->
	if (gridinsert && addcnt == 0) { // No row added
		ew_Alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	<!--##
		}
	##-->

	<!--##
		if (sId == "add" || sId == "edit") {
	##-->
	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	<!--##
		}
	##-->

	return true;
}

<!--##
	}
##-->

<!--##
	if ((sId == "list" && bGridAdd) || sId == "grid") {
##-->
// Check empty row
<!--##=sFormName##-->.EmptyRow = function(infix) {
	var fobj = this.Form;
	<!--##
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				if (!goFld.FldAutoIncrement && ew_IsEmpty(goFld.FldAutoUpdateValue)) {
					var sFieldType = (SYSTEMFUNCTIONS.IsBoolFld()) ? "true" : "false";
	##-->
	if (ew_ValueChanged(fobj, infix, "<!--##=ew_AddSquareBrackets(gsFldParm, goFld)##-->", <!--##=sFieldType##-->)) return false;
	<!--##				
				}
			}
		}
	##-->
	return true;
}
<!--##
	}
##-->

<!--## if (SYSTEMFUNCTIONS.ClientScriptExist(sCtrlType, "Form_CustomValidate")) { ##-->
// Form_CustomValidate event
<!--##=sFormName##-->.Form_CustomValidate = <!--##~SYSTEMFUNCTIONS.GetClientScript(sCtrlType, "Form_CustomValidate")##-->
<!--## } ##-->

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
<!--##=sFormName##-->.ValidateRequired = true;
<?php } else { ?>
<!--##=sFormName##-->.ValidateRequired = false; 
<?php } ?>

<!--##
	if (bMultiPage) {
##-->
// Multi-Page
<!--##=sFormName##-->.MultiPage = new ew_MultiPage("<!--##=sFormName##-->");
<!--##
	}
##-->

// Dynamic selection lists
<!--##
	for (var i = 0; i < nFldCount; i++) {
		if (GetFldObj(arFlds[i])) {
			if (goFld.FldIsLookup) {
##-->
<!--##=sFormName##-->.Lists["<!--##=ew_AddSquareBrackets(gsFldVar, goFld)##-->"] = <!--##=SYSTEMFUNCTIONS.SelectionList(sId)##-->;
<!--## if (goFld.FldSelectType != "Table") { // User Values ##-->
<!--##=sFormName##-->.Lists["<!--##=ew_AddSquareBrackets(gsFldVar, goFld)##-->"].Options = <?php echo json_encode($<!--##=gsFldObj##-->->Options()) ?>;
<!--## } ##-->
<!--##
			}
		}
	}
##-->

// Form object for search
<!--## if ((bBasicSearch || bExtendedBasicSearch || bAdvancedSearch) && sId == "list") { ##-->
var CurrentSearchForm = <!--##=sFormNameSearch##--> = new ew_Form("<!--##=sFormNameSearch##-->");
<!--## } ##-->

<!--## if (sId == "search" || (bExtendedBasicSearch && sId == "list")) { ##-->

// Validate function for search
<!--##=sFormNameSearch##-->.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	<!--##
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (IsValidateSearch(goFld)) {
	##-->
	<!--##~SYSTEMFUNCTIONS.JsValidator()##-->
	<!--##
				}
			}
		}

		if (SYSTEMFUNCTIONS.ClientScriptExist(sCtrlType, "Form_CustomValidate")) {
	##-->
	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	<!--##
		}
	##-->
	return true;
}
<!--## } ##-->

<!--##
	if (bExtendedBasicSearch && sId == "list") {
##-->

<!--## if (SYSTEMFUNCTIONS.ClientScriptExist(sCtrlType, "Form_CustomValidate")) { ##-->
// Form_CustomValidate event
<!--##=sFormNameSearch##-->.Form_CustomValidate = <!--##~SYSTEMFUNCTIONS.GetClientScript(sCtrlType, "Form_CustomValidate")##-->
<!--## } ##-->

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
<!--##=sFormNameSearch##-->.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
<!--##=sFormNameSearch##-->.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
<!--##
	for (var i = 0; i < nAllFldCount; i++) {
		if (GetFldObj(arAllFlds[i]) && IsFldExtendedSearch(goFld)) {
			if (goFld.FldIsLookup) {
##-->
<!--##=sFormNameSearch##-->.Lists["<!--##=ew_AddSquareBrackets(gsFldVar, goFld)##-->"] = <!--##=SYSTEMFUNCTIONS.SelectionList("extbs")##-->;
<!--## if (goFld.FldSelectType != "Table") { // User Values ##-->
<!--##=sFormNameSearch##-->.Lists["<!--##=ew_AddSquareBrackets(gsFldVar, goFld)##-->"].Options = <?php echo json_encode($<!--##=gsFldObj##-->->Options()) ?>;
<!--## } ##-->
<!--##
			}
		}
	}
##-->

<!--##
	}
##-->

<!--## if ((bBasicSearch || bExtendedBasicSearch || bAdvancedSearch) && sId == "list" && PROJ.GetV("SearchPanelCollapsed") && !bShowBlankListPage) { ##-->
// Init search panel as collapsed
if (<!--##=sFormNameSearch##-->) <!--##=sFormNameSearch##-->.InitSearchPanel = true;
<!--## } ##-->

<!--##/session##-->


<!--##session js_local_end##-->
</script>
<!--##/session##-->
