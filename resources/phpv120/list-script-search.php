<!--##session searchchkbegin##-->
<!--##
	// Show Option
	sSearchSecChkWrk = ew_SecurityCheck("Search", bSecurityEnabled, bUserTable);
	if (ew_IsNotEmpty(sSearchSecChkWrk)) {
		sSearchSecChkBegin = "<?php if (" + sSearchSecChkWrk + ") { ?>";
		sSearchSecChkEnd = "<?php } ?>";
	} else {
		sSearchSecChkBegin = "";
		sSearchSecChkEnd = "";
	}
##-->
<!--## if (bBasicSearch || bExtendedBasicSearch) { ##-->
<!--##=sSearchSecChkBegin##-->
<!--## } ##-->
<!--##/session##-->


<!--##session htmsearch##-->
<!--## if (bBasicSearch || bExtendedBasicSearch) { ##-->
<!--##
	var ColCnt = 0;
	var RowCnt = 0;
	var sSearchPanelClass = PROJ.GetV("SearchPanelCollapsed") ? "" : " in";
##-->
<?php if ($<!--##=gsTblVar##-->->Export == "" && $<!--##=gsTblVar##-->->CurrentAction == "") { ?>
<form name="<!--##=sFormNameSearch##-->" id="<!--##=sFormNameSearch##-->" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($<!--##=sPageObj##-->->SearchWhere <> "") ? " in" : "<!--##=sSearchPanelClass##-->"; ?>
<div id="<!--##=sFormNameSearch##-->_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="<!--##=gsTblVar##-->">
	<div class="ewBasicSearch">
	<!--## if (bExtendedBasicSearch) { ##-->
<?php
if ($gsSearchError == "")
	$<!--##=sPageObj##-->->LoadAdvancedSearch(); // Load advanced search

// Render for search
$<!--##=gsTblVar##-->->RowType = EW_ROWTYPE_SEARCH;

// Render row
$<!--##=gsTblVar##-->->ResetAttrs();
$<!--##=sPageObj##-->->RenderRow();
?>
	<!--##

		var sSearchRowStyle = "";
		if (bUseCustomTemplateSearch) {
			sScriptEnd = "</script>";
			sSearchRowStyle = " style=\"display: none\"";
		} else {
			sScriptEnd = "";
		}

		ColCnt = 0;
		RowCnt = 0;
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (IsFldExtendedSearch(goFld)) {
					ColCnt += 1;
					gsFldVar2 = "y_" + gsFldParm;
					sFldHtmlTag = goFld.FldHtmlTag;
					sFldSrchOpr = goFld.FldSrchOpr;
					sFldSrchOpr2 = goFld.FldSrchOpr2;
					IsUserSelect = (sFldSrchOpr == "USER SELECT" && ew_GetFieldType(goFld.FldType) != 4);
					sInitStyle = "";
					if (IsUserSelect && sFldSrchOpr2 == "") sInitStyle = " style=\"display: none\"";
					if (bUseCustomTemplateSearch) {
						sTemplateId = gsTblVar + "_" + gsFldParm;
						var sClassName = gsTblVar + "extendedsearch";
						sSearchCaptionScript = ew_CustomScriptTag(sTemplateId, "searchcaption", sClassName);
						sSearchOperatorScript = ew_CustomScriptTag(sTemplateId, "operator", sClassName);
						sSearchFieldScript = ew_CustomScriptTag(sTemplateId, "value", sClassName);
						sSearchConditionScript = ew_CustomScriptTag(sTemplateId, "condition", sClassName);
						sSearchOperator2Script = ew_CustomScriptTag(sTemplateId, "operator2", sClassName);
						sSearchField2Script = ew_CustomScriptTag(sTemplateId, "value2", sClassName);
					} else {
						sSearchCaptionScript = "";
						sSearchOperatorScript = "";
						sSearchFieldScript = "";
						sSearchConditionScript = "";
						sSearchOperator2Script = "";
						sSearchField2Script = "";
					}
					sForLabel = (ew_UseForLabel(goFld) || goFld.FldHtmlTag == "NO") ? " for=\"" + gsFldVar + "\"" : "";
	##-->
	<!--##
		if ((ColCnt-1) % iExtSearchFldPerRow == 0) {
			RowCnt += 1;
	##-->
<div id="xsr_<!--##=RowCnt##-->" class="ewRow"<!--##=sSearchRowStyle##-->>
	<!--## } ##-->
<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=gsFldName##--> ?>
	<div id="xsc_<!--##=gsFldParm##-->" class="ewCell form-group">
		<!--##=sSearchCaptionScript##--><label<!--##=sForLabel##--> class="ewSearchCaption ewLabel"><!--##=SYSTEMFUNCTIONS.FieldSearchCaption()##--></label><!--##=sScriptEnd##-->
		<!--##=sSearchOperatorScript##--><span class="ewSearchOperator"><!--##=SYSTEMFUNCTIONS.FieldOperator()##--></span><!--##=sScriptEnd##-->
		<!--##=sSearchFieldScript##--><span class="ewSearchField"><!--##=SYSTEMFUNCTIONS.FieldSearch()##--></span><!--##=sScriptEnd##--><!--##=ew_JavaScript##-->
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
		<!--##=sSearchField2Script##--><span class="ewSearchField<!--## if (sFldSrchOpr2 == "") { ##--> btw1_<!--##=gsFldParm##--><!--## } ##-->"<!--##=sInitStyle##-->><!--##=SYSTEMFUNCTIONS.FieldSearch2()##--></span><!--##=sScriptEnd##--><!--##=ew_JavaScript##-->
			<!--## } ##-->
	</div>
<?php } ?>
	<!--## if (ColCnt % iExtSearchFldPerRow == 0) { ##-->
</div>
	<!--## } ##-->
	<!--##
				}
			}
		} // AllField
	##-->
	<!--## if (ColCnt % iExtSearchFldPerRow != 0) { ##-->
</div>
	<!--## } ##-->
	<!--## } ##-->

<!--## if (bUseCustomTemplateSearch) { ##-->
<!--##~SYSTEMFUNCTIONS.GetCustomTemplateSearch()##-->
<!--## } ##-->

<div id="xsr_<!--##=RowCnt+1##-->" class="ewRow">
	<!--## if (bBasicSearch || bExtendedBasicSearch) { ##-->
		<!--## if (bBasicSearch) { ##-->
<!--##
	sPlaceHolder = (sUsePlaceHolder == "Caption") ? " placeholder=\"<?php echo ew_HtmlEncode($Language->Phrase(\"Search\")) ?>\"" : "";
##-->
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($<!--##=sPageObj##-->->BasicSearch->getKeyword()) ?>"<!--##=sPlaceHolder##-->>
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($<!--##=sPageObj##-->->BasicSearch->getType()) ?>">
		<!--## } ##-->
		<!--## if (bBasicSearch || bExtendedBasicSearch) { ##-->
		<!--## if (bBasicSearch) { ##-->
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $<!--##=sPageObj##-->->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($<!--##=sPageObj##-->->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($<!--##=sPageObj##-->->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($<!--##=sPageObj##-->->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($<!--##=sPageObj##-->->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
		<!--## } ##-->
	<button class="<!--##=sSubmitButtonClass##-->" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
		<!--## if (bBasicSearch) { ##-->
	</div>
	</div>
		<!--## } ##-->
		<!--## } ##-->
	<!--## } ##-->
</div>
	</div>
</div>

</form>

<!--## if (bUseCustomTemplateSearch) { ##-->
<!--##~SYSTEMFUNCTIONS.RenderCustomTemplateSearch()##-->
<!--## } ##-->

<?php } ?>
<!--## } ##-->
<!--##/session##-->


<!--##session searchchkend##-->
<!--## if (bBasicSearch || bExtendedBasicSearch) { ##-->
<!--##=sSearchSecChkEnd##-->
<!--## } ##-->

<!--##/session##-->
