<!--##session masterhtm##-->
<!--##
	var sMainTableStyle = "";
	if (bUseCustomTemplate) {
		sMainTableStyle = " style=\"display: none\"";
	}
##-->
<?php
<!--##
	for (var j = 0; j < nFldCount; j++) {
		if (GetFldObj(arFlds[j])) {
##-->
// <!--##=gsFldName##-->
<!--##~SYSTEMFUNCTIONS.ScriptCommon()##-->
<!--##
		}
	} // Field
##-->
?>
<?php if ($<!--##=gsTblVar##-->->Visible) { ?>

<!-- <h4 class="ewMasterCaption"><?php echo $<!--##=gsTblVar##-->->TableCaption() ?></h4> -->

<!--## if (bShowVerticalMasterRecord) { ##-->

<table id="<!--##=ewTableId##-->" class="table table-bordered table-striped ewViewTable"<!--##=sMainTableStyle##-->>

<?php echo $<!--##=gsTblVar##-->->TableCustomInnerHtml ?>

	<tbody>
		<!--##
			for (var j = 0; j < nFldCount; j++) {
				if (GetFldObj(arFlds[j])) {
		##-->
<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=gsFldName##--> ?>
		<tr id="r_<!--##=gsFldParm##-->">
			<td><!--##=SYSTEMFUNCTIONS.FieldCaption()##--></td>
			<td<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldView()##--></td>
		</tr>
<?php } ?>
		<!--##
				}
			} // Field
		##-->
	</tbody>
</table>

<!--## } else { ##-->

<div id="t_<!--##=gsTblVar##-->" class="<?php if (ew_IsResponsiveLayout()) echo "table-responsive "; ?>ewGrid"<!--##=sMainTableStyle##-->>
<table id="<!--##=ewTableId##-->"<!--##=ewCSSTableClass##-->>

<?php echo $<!--##=gsTblVar##-->->TableCustomInnerHtml ?>

	<thead>
		<tr>
		<!--##
			for (var j = 0; j < nFldCount; j++) {
				if (GetFldObj(arFlds[j])) {
		##-->
<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=gsFldName##--> ?>
			<th<!--##=ewCSSTableHeaderClass##-->><!--##=SYSTEMFUNCTIONS.FieldCaption()##--></th>
<?php } ?>
		<!--##
				}
			} // Field
		##-->
		</tr>
	</thead>
	<tbody>
		<tr>
		<!--##
			for (var j = 0; j < nFldCount; j++) {
				if (GetFldObj(arFlds[j])) {
		##-->
<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=gsFldName##--> ?>
			<td<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldView()##--></td>
<?php } ?>
		<!--##
				}
			} // Field
		##-->
		</tr>
	</tbody>
</table>
</div>

<!--## } ##-->

<!--## if (bUseCustomTemplate) { ##-->
<!--##~SYSTEMFUNCTIONS.GetCustomTemplate()##-->
<!--## } ##-->

<!--## if (bUseCustomTemplate) { ##-->
<!--##~SYSTEMFUNCTIONS.RenderCustomTemplate()##-->
<!--## } ##-->

<?php } ?>
<!--##/session##-->
