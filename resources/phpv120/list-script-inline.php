<!--##session inlineaddhtml##-->
<?php

<!--## if (bGenTemplateLine) { ##-->

	<!--## if (CTRL.CtrlID == "grid") { ##-->
	if ($<!--##=gsTblVar##-->->CurrentMode == "add" || $<!--##=gsTblVar##-->->CurrentMode == "copy" || $<!--##=gsTblVar##-->->CurrentMode == "edit") {
	<!--## } else { ##-->
	if ($<!--##=gsTblVar##-->->CurrentAction == "gridadd" || $<!--##=gsTblVar##-->->CurrentAction == "gridedit") {
	<!--## } ##-->

		$<!--##=sPageObj##-->->RowIndex = '$rowindex$';
		$<!--##=sPageObj##-->->LoadDefaultValues();

		// Set row properties
		$<!--##=gsTblVar##-->->ResetAttrs();

		$<!--##=gsTblVar##-->->RowAttrs = array_merge($<!--##=gsTblVar##-->->RowAttrs, array('data-rowindex'=>$<!--##=sPageObj##-->->RowIndex, 'id'=>'r0_<!--##=gsTblVar##-->', 'data-rowtype'=>EW_ROWTYPE_ADD));

		ew_AppendClass($<!--##=gsTblVar##-->->RowAttrs["class"], "ewTemplate");

<!--## } else { ##-->

	if ($<!--##=gsTblVar##-->->CurrentAction == "add" || $<!--##=gsTblVar##-->->CurrentAction == "copy") {

		$<!--##=sPageObj##-->->RowIndex = 0;
		$<!--##=sPageObj##-->->KeyCount = $<!--##=sPageObj##-->->RowIndex;

<!--## if (bInlineCopy) { ##-->
		if ($<!--##=gsTblVar##-->->CurrentAction == "copy" && !$<!--##=sPageObj##-->->LoadRow())
				$<!--##=gsTblVar##-->->CurrentAction = "add";
<!--## } ##-->

		if ($<!--##=gsTblVar##-->->CurrentAction == "add")
			$<!--##=sPageObj##-->->LoadDefaultValues();

		if ($<!--##=gsTblVar##-->->EventCancelled) // Insert failed
			$<!--##=sPageObj##-->->RestoreFormValues(); // Restore form values

		// Set row properties
		$<!--##=gsTblVar##-->->ResetAttrs();

		$<!--##=gsTblVar##-->->RowAttrs = array_merge($<!--##=gsTblVar##-->->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_<!--##=gsTblVar##-->', 'data-rowtype'=>EW_ROWTYPE_ADD));

<!--## } ##-->

		$<!--##=gsTblVar##-->->RowType = EW_ROWTYPE_ADD;

		// Render row
		$<!--##=sPageObj##-->->RenderRow();
		
		// Render list options
		$<!--##=sPageObj##-->->RenderListOptions();
		$<!--##=sPageObj##-->->StartRowCnt = 0;
?>

<!--## if (iRecPerRow < 1) { // Normal layout ##-->

	<tr<?php echo $<!--##=gsTblVar##-->->RowAttributes() ?>>

<!--## var rowidx = "$" + sPageObj + "->" + ((bGenTemplateLine) ? "RowIndex" : "RowCnt"); ##-->
<?php
// Render list options (body, left)
<!--## if (bUseCustomTemplate) { ##-->
$<!--##=sPageObj##-->->ListOptions->Render("body", "", <!--##=rowidx##-->, "<!--##=sCustomListOptionsBody##-->", $<!--##=gsTblVar##-->->TableVar, "<!--##=sTemplateClass##-->");
<!--## } else { ##-->
$<!--##=sPageObj##-->->ListOptions->Render("body", "left", <!--##=rowidx##-->);
<!--## } ##-->
?>

	<!--##
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				ctl = SYSTEMFUNCTIONS.FieldAdd();
	##-->
	<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=gsFldName##--> ?>
		<td data-name="<!--##=gsFldParm##-->"<!--##=FieldTD_Item(goFld)##-->><!--##=ctl##--></td>
	<?php } ?>
	<!--##
			}
		} // Field
	##-->

<!--## if (!bUseCustomTemplate) { ##-->
<?php
// Render list options (body, right)
$<!--##=sPageObj##-->->ListOptions->Render("body", "right", $<!--##=sPageObj##-->->RowCnt);
?>
<!--## } ##-->

<!--## if (bUseCustomTemplate) { ##-->
<script class="<!--##=sTemplateClass##-->_js" type="text/html">
<!--## } else { ##-->
<script type="text/javascript">
<!--## } ##-->
<!--##=sFormName##-->.UpdateOpts(<?php echo $<!--##=sPageObj##-->->RowIndex ?>);
</script>

	</tr>

<!--## } else { // Multi-Column ##-->

<?php $<!--##=sPageObj##-->->ColCnt = 0 ?>
<div class="row ewMultiColumnRow">
<div class="<?php echo $<!--##=sPageObj##-->->MultiColumnEditClass ?>"<?php echo $<!--##=gsTblVar##-->->RowAttributes() ?>>
	<div>

	<!--##
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				if (goFld.FldAutoIncrement)
					ctl = "";
				else
					ctl = SYSTEMFUNCTIONS.FieldAdd();
				sClassId = gsTblVar + "_" + gsFldParm;
	##-->
	<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=gsFldName##--> ?>
		<div class="form-group <!--##=sClassId##-->">
			<label class="<!--##=ewBootstrapLabelClass##-->"><!--##=SYSTEMFUNCTIONS.FieldCaption()##--></label>
			<div class="<!--##=ewBootstrapRightColumnClass##-->"><div<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=ctl##--></div></div>
		</div>
	<?php } ?>
	<!--##
			}
		} // Field
	##-->
	</div>

<div class="ewMultiColumnListOption">
<!--## var rowidx = "$" + sPageObj + "->" + ((bGenTemplateLine) ? "RowIndex" : "RowCnt"); ##-->
<?php
// Render list options (body, bottom)
<!--## if (bUseCustomTemplate) { ##-->
$<!--##=sPageObj##-->->ListOptions->Render("body", "", <!--##=rowidx##-->, "<!--##=sCustomListOptionsBody##-->", $<!--##=gsTblVar##-->->TableVar, "<!--##=sTemplateClass##-->");
<!--## } else { ##-->
$<!--##=sPageObj##-->->ListOptions->Render("body", "bottom", <!--##=rowidx##-->);
<!--## } ##-->
?>
</div>
<div class="clearfix"></div>

<!--## if (bUseCustomTemplate) { ##-->
<script class="<!--##=sTemplateClass##-->_js" type="text/html">
<!--## } else { ##-->
<script type="text/javascript">
<!--## } ##-->
<!--##=sFormName##-->.UpdateOpts(<?php echo $<!--##=sPageObj##-->->RowIndex ?>);
</script>

</div>
</div>

<!--## } ##-->

<?php
}
?>
<!--##/session##-->