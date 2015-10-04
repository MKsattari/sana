<?php
<!--##session phpmain##-->
<!--##
	var bHasUserSelect = false;
	for (var i = 0; i < nFldCount; i++) {
		if (GetFldObj(arFlds[i])) {
			if (goFld.FldSrchOpr == "USER SELECT" && ew_GetFieldType(goFld.FldType) != 4) {
				bHasUserSelect = true;
				break;
			}
		}
	} // Field

	ewBootstrapSearchLeftColumnClass = "col-sm-3";
	ewBootstrapSearchLabelClass = ewBootstrapSearchLeftColumnClass + " control-label ewLabel";
	ewBootstrapSearchRightColumnClass = "col-sm-9";
	ewBootstrapSearchOffsetClass = "col-sm-offset-3 col-sm-9";
##-->

	var $FormClassName = "form-horizontal ewForm ewSearchForm";
	var $IsModal = FALSE;
	var $SearchLabelClass = "<!--##=ewBootstrapSearchLabelClass##-->";
	var $SearchRightColumnClass = "<!--##=ewBootstrapSearchRightColumnClass##-->";

<!--## if (bMultiPage) { ##-->
	var $MultiPages; // Multi pages object
<!--## } ##-->

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;
		global $gbSkipHeaderFooter;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

	<!--## if (bUseTabularFormForDesktop) { ##-->
		$this->FormClassName = "ewForm ewSearchForm";
		if (ew_IsMobile() || $this->IsModal)
			$this->FormClassName = ew_Concat("form-horizontal", $this->FormClassName, " ");
	<!--## } ##-->

		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");

			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values

					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}

					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$sSrchStr = "<!--##=sFnList##-->" . "?" . $sSrchStr;
						if ($this->IsModal) {
							$row = array();
							$row["url"] = $sSrchStr;
							echo ew_ArrayToJson(array($row));
							$this->Page_Terminate();
							exit();
						} else {
							$this->Page_Terminate($sSrchStr); // Go to list page
						}
					}

			}

		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();

	}
<!--##/session##-->
?>

<!--##session htmtable##-->
<?php if (!$<!--##=sPageObj##-->->IsModal) { ?>
<div class="ewToolbar">
<!--##include phpcommon.php/breadcrumb##-->
<!--##include phpcommon.php/language##-->
<div class="clearfix"></div>
</div>
<?php } ?>

<!--##include phpcommon.php/header-message##-->
<!--##include phpcommon.php/phpcommon-message##-->

<form name="<!--##=sFormName##-->" id="<!--##=sFormName##-->" class="<?php echo $<!--##=sPageObj##-->->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">

<?php if ($<!--##=sPageObj##-->->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $<!--##=sPageObj##-->->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="<!--##=gsTblVar##-->">
<input type="hidden" name="a_search" id="a_search" value="S">

<?php if ($<!--##=sPageObj##-->->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>

<!--## if (bUseCustomTemplate) { ##-->
<!--##~SYSTEMFUNCTIONS.GetCustomTemplate()##-->
<!--## } ##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
<?php if (!ew_IsMobile() && !$<!--##=sPageObj##-->->IsModal) { ?>
<div class="ewDesktop">
<?php } ?>
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
<?php if (ew_IsMobile() || $<!--##=sPageObj##-->->IsModal) { ?>
<!--## } ##-->

<div<!--##=ewCSSTableClass##--><!--##=sPageZeroTableStyle##-->>

<!--## if (bUseTabularFormForDesktop) { ##-->
<?php } else { ?>
<div<!--##=sPageZeroTableStyle##-->>
<table id="<!--##=sTableId##-->"<!--##=ewCSSDesktopTableClass##-->>
<?php } ?>
<!--## } ##-->

<!--##
	for (var i = 0; i < nFldCount; i++) {
		if (GetFldObj(arFlds[i])) {
			if (glFldPageIndex == 0) {
##-->
<!--##include search-script.php/fieldlayout##-->
<!--##
			}
		}
	} // Field
##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
<?php if (ew_IsMobile() || $<!--##=sPageObj##-->->IsModal) { ?>
<!--## } ##-->

</div>

<!--## if (bUseTabularFormForDesktop) { ##-->
<?php } else { ?>
</table>
</div>
<?php } ?>
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
		if (!TABLE.TblMultiPageSearch || (TABLE.TblMultiPageSearch && String(pageidx) in dPages)) {
##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
<?php if (ew_IsMobile() || $<!--##=sPageObj##-->->IsModal) { ?>
<!--## } ##-->

<div<!--##=ewCSSTableClass##--><!--##=sPageTableStyle##-->>

<!--## if (bUseTabularFormForDesktop) { ##-->
<?php } else { ?>
<div>
<table id="<!--##=sTableId##-->"<!--##=ewCSSDesktopTableClass##--><!--##=sPageTableStyle##-->>
<?php } ?>
<!--## } ##-->

<!--##
	for (var i = 0; i < nFldCount; i++) {
		if (GetFldObj(arFlds[i])) {
			if (!TABLE.TblMultiPageSearch || (TABLE.TblMultiPageSearch && glFldPageIndex == pageidx)) {
##-->
<!--##include search-script.php/fieldlayout##-->
<!--##
			}
		}
	} // Field
##-->

<!--## if (bUseTabularFormForDesktop) { ##-->
<?php if (ew_IsMobile() || $<!--##=sPageObj##-->->IsModal) { ?>
<!--## } ##-->

</div>

<!--## if (bUseTabularFormForDesktop) { ##-->
<?php } else { ?>
</table>
</div>
<?php } ?>
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

<?php if (!$<!--##=sPageObj##-->->IsModal) { ?>

<!--## if (bUseTabularFormForDesktop) { ##-->
<?php if (ew_IsMobile()) { ?>
<!--## } ##-->

<div class="form-group">
	<div class="<!--##=ewBootstrapSearchOffsetClass##-->">

<!--## if (bUseTabularFormForDesktop) { ##-->
<?php } else { ?>
<div class="ewDesktopButton">
<?php } ?>
<!--## } ##-->
	
<button class="<!--##=sSubmitButtonClass##-->" name="btnAction" id="btnAction" type="<!--##=sButtonType##-->"<!--##=sButtonSubmit##-->><?php echo $Language->Phrase("Search") ?></button>
<button class="<!--##=sResetButtonClass##-->" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>

<!--## if (bUseTabularFormForDesktop) { ##-->
<?php if (ew_IsMobile()) { ?>
<!--## } ##-->

	</div>
</div>

<!--## if (bUseTabularFormForDesktop) { ##-->
<?php } else { ?>
</div>
<?php } ?>
<!--## } ##-->

<?php } ?>

<!--## if (bUseTabularFormForDesktop) { ##-->
<?php if (!ew_IsMobile() && !$<!--##=sPageObj##-->->IsModal) { ?>
</div>
<?php } ?>
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
	if (!(goFld.FldHtmlTag == "FILE" && ew_IsBinaryField(goFld))) {
		sHeaderSpanId = "elh_" + gsTblVar + "_" + gsFldParm;
		sSpanId = "el_" + gsTblVar + "_" + gsFldParm;
		sSpanId2 = "e2_" + gsTblVar + "_" + gsFldParm;
		sFldSrchOpr = goFld.FldSrchOpr;
		sFldSrchOpr2 = goFld.FldSrchOpr2;
		IsUserSelect = (sFldSrchOpr == "USER SELECT" && ew_GetFieldType(goFld.FldType) != 4);
		sInitStyle = "";
		if (IsUserSelect && sFldSrchOpr2 == "") sInitStyle = " style=\"display: none\"";
		if (bUseCustomTemplate) {
			sTemplateId = gsTblVar + "_" + gsFldParm;
			sSearchCaptionScript = ew_CustomScriptTag(sTemplateId, "searchcaption", sTemplateClass);
			sSearchOperatorScript = ew_CustomScriptTag(sTemplateId, "operator", sTemplateClass);
			sSearchFieldScript = ew_CustomScriptTag(sTemplateId, "value", sTemplateClass);
			sSearchConditionScript = ew_CustomScriptTag(sTemplateId, "condition", sTemplateClass);
			sSearchOperator2Script = ew_CustomScriptTag(sTemplateId, "operator2", sTemplateClass);
			sSearchField2Script = ew_CustomScriptTag(sTemplateId, "value2", sTemplateClass);
			sScriptEnd = "</script>";
		} else {
			sSearchCaptionScript = "";
			sSearchOperatorScript = "";
			sSearchFieldScript = "";
			sSearchConditionScript = "";
			sSearchOperator2Script = "";
			sSearchField2Script = "";
			sScriptEnd = "";
		}
		if (sFldSrchOpr == "USER SELECT") {
			sSearchOperatorStartTag = "<span class=\"ewSearchOperator\">";
			sSearchOperatorEndTag = "</span>";
		} else {
			sSearchOperatorStartTag = "<p class=\"form-control-static ewSearchOperator\">";
			sSearchOperatorEndTag = "</p>";
		}
		if (sFldSrchOpr2 == "USER SELECT") {
			sSearchOperator2StartTag = "<span class=\"ewSearchOperator btw0_" + gsFldParm +"\"" + sInitStyle + ">";
			sSearchOperator2EndTag = "</span>";
		} else {
			sSearchOperator2StartTag = "<p class=\"form-control-static ewSearchOperator btw0_" + gsFldParm +"\"" + sInitStyle + ">";
			sSearchOperator2EndTag = "</p>";
		}
		sForLabel = (ew_UseForLabel(goFld) || goFld.FldHtmlTag == "NO") ? " for=\"" + gsFldVar + "\"" : "";
##-->
<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=gsFldName##--> ?>

<!--## if (bUseTabularFormForDesktop) { ##-->
<?php if (ew_IsMobile() || $<!--##=sPageObj##-->->IsModal) { ?>
<!--## } ##-->

	<div id="r_<!--##=gsFldParm##-->" class="form-group">
		<label<!--##=sForLabel##--> class="<?php echo $<!--##=sPageObj##-->->SearchLabelClass ?>"><!--##=sSearchCaptionScript##--><span id="<!--##=sHeaderSpanId##-->"><?php echo $<!--##=gsFldObj##-->->FldCaption() ?></span><!--##=sScriptEnd##-->	
<!--## if (sFldSrchOpr != "USER SELECT") { ##-->
		<!--##=sSearchOperatorScript##--><!--##=sSearchOperatorStartTag##--><!--##=SYSTEMFUNCTIONS.FieldOperator()##--><!--##=sSearchOperatorEndTag##--><!--##=sScriptEnd##-->
<!--## } ##-->
		</label>
		<div class="<?php echo $<!--##=sPageObj##-->->SearchRightColumnClass ?>"><div<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>>
<!--## if (sFldSrchOpr == "USER SELECT") { ##-->
		<!--##=sSearchOperatorScript##--><!--##=sSearchOperatorStartTag##--><!--##=SYSTEMFUNCTIONS.FieldOperator()##--><!--##=sSearchOperatorEndTag##--><!--##=sScriptEnd##-->
<!--## } ##-->
			<!--##=sSearchFieldScript##--><span id="<!--##=sSpanId##-->"><!--##=SYSTEMFUNCTIONS.FieldSearch()##--></span><!--##=sScriptEnd##--><!--##=ew_JavaScript##-->
		<!--##=sSearchConditionScript##-->
		<!--## if (ew_IsNotEmpty(sFldSrchOpr2)) { ##-->
			<span class="ewSearchCond btw0_<!--##=gsFldParm##-->"<!--##=sInitStyle##-->><!--##=SYSTEMFUNCTIONS.FieldSearchCondition()##--></span>
		<!--## } ##-->
		<!--## if (sFldSrchOpr == "BETWEEN" || IsUserSelect) { ##-->
			<span class="ewSearchCond btw1_<!--##=gsFldParm##-->"<!--##=sInitStyle##-->>&nbsp;<!--##@AND##-->&nbsp;</span>
		<!--## } ##-->
		<!--##=sScriptEnd##-->
		<!--## if (ew_IsNotEmpty(sFldSrchOpr2)) { ##-->
			<!--##=sSearchOperator2Script##--><!--##=sSearchOperator2StartTag##--><!--##=SYSTEMFUNCTIONS.FieldOperator2()##--><!--##=sSearchOperator2EndTag##--><!--##=sScriptEnd##-->
		<!--## } ##-->			
		<!--## if (ew_IsNotEmpty(sFldSrchOpr2) || sFldSrchOpr == "BETWEEN" || IsUserSelect) { ##-->
			<!--##=sSearchField2Script##--><span id="<!--##=sSpanId2##-->"<!--## if (sFldSrchOpr2 == "") { ##--> class="btw1_<!--##=gsFldParm##-->"<!--## } ##--><!--##=sInitStyle##-->><!--##=SYSTEMFUNCTIONS.FieldSearch2()##--></span><!--##=sScriptEnd##--><!--##=ew_JavaScript##-->
		<!--## } ##-->
		</div></div>
	</div>

<!--## if (bUseTabularFormForDesktop) { ##-->
<?php } else { ?>
	<tr id="r_<!--##=gsFldParm##-->">
		<td><!--##=sSearchCaptionScript##--><span id="<!--##=sHeaderSpanId##-->"><?php echo $<!--##=gsFldObj##-->->FldCaption() ?></span><!--##=sScriptEnd##--></td>
		<td><!--##=sSearchOperatorScript##--><span class="ewSearchOperator"><!--##=SYSTEMFUNCTIONS.FieldOperator()##--></span><!--##=sScriptEnd##--></td>
		<td<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<!--##=sSearchFieldScript##--><span id="<!--##=sSpanId##-->"><!--##=SYSTEMFUNCTIONS.FieldSearch()##--></span><!--##=sScriptEnd##--><!--##=ew_JavaScript##-->
		<!--##=sSearchConditionScript##-->
			<!--## if (ew_IsNotEmpty(sFldSrchOpr2)) { ##-->
				<span class="ewSearchCond btw0_<!--##=gsFldParm##-->"<!--##=sInitStyle##-->><!--##=SYSTEMFUNCTIONS.FieldSearchCondition()##--></span>
			<!--## } ##-->
			<!--## if (sFldSrchOpr == "BETWEEN" || IsUserSelect) { ##-->
				<span class="ewSearchCond btw1_<!--##=gsFldParm##-->"<!--##=sInitStyle##-->>&nbsp;<!--##@AND##-->&nbsp;</span>
			<!--## } ##-->
		<!--##=sScriptEnd##-->
			<!--## if (ew_IsNotEmpty(sFldSrchOpr2)) { ##-->
				<!--##=sSearchOperator2Script##--><span class="ewSearchOperator btw0_<!--##=gsFldParm##-->"<!--##=sInitStyle##-->><!--##=SYSTEMFUNCTIONS.FieldOperator2()##--></span><!--##=sScriptEnd##-->
			<!--## } ##-->			
			<!--## if (ew_IsNotEmpty(sFldSrchOpr2) || sFldSrchOpr == "BETWEEN" || IsUserSelect) { ##-->
				<!--##=sSearchField2Script##--><span id="<!--##=sSpanId2##-->"<!--## if (sFldSrchOpr2 == "") { ##--> class="btw1_<!--##=gsFldParm##-->"<!--## } ##--><!--##=sInitStyle##-->><!--##=SYSTEMFUNCTIONS.FieldSearch2()##--></span><!--##=sScriptEnd##--><!--##=ew_JavaScript##-->
			<!--## } ##-->
			</div>
		</td>
	</tr>
<?php } ?>
<!--## } ##-->

<?php } ?>
<!--##
	}
##-->
<!--##/session##-->


<?php
<!--##session phpfunction##-->
	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
	<!--##
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				if (!(goFld.FldHtmlTag == "FILE" && ew_IsBinaryField(goFld))) {
					var bCheckbox = (SYSTEMFUNCTIONS.IsBoolFld() && goFld.FldHtmlTag == "CHECKBOX") || (goFld.FldSrchOpr == "IS NULL") || (goFld.FldSrchOpr == "IS NOT NULL") || (goFld.FldSrchOpr2 == "IS NULL") || (goFld.FldSrchOpr2 == "IS NOT NULL");
					if (bCheckbox) {
	##-->
		$this->BuildSearchUrl($sSrchUrl, $this-><!--##=gsFldParm##-->, TRUE); // <!--##=gsFldName##-->
	<!--##
					} else {
	##-->
		$this->BuildSearchUrl($sSrchUrl, $this-><!--##=gsFldParm##-->); // <!--##=gsFldName##-->
	<!--##
					}
				}
			}
		} // Field
	##-->
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

<!--##/session##-->
?>


<?php
<!--##session phpevents##-->
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Form_CustomValidate")##-->
<!--##/session##-->
?>