<?php
<!--##session phpmain##-->

	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;

	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

	<!--## if (nMasterTableCount > 0) { ##-->
		// Set up master/detail parameters
		$this->SetUpMasterParms();
	<!--## } ##-->

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("<!--##=sFnList##-->"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in <!--##=gsTblVar##--> class, <!--##=gsTblVar##-->info.php
		$this->CurrentFilter = $sFilter;

	<!--##
		if (bTableHasUserIDFld) {
	##-->

		// Check if valid user id
		$conn = &$this->Connection();
		$sql = $this->GetSQL($this->CurrentFilter, "");
		if ($this->Recordset = ew_LoadRecordset($sql, $conn)) {
			$res = TRUE;
			while (!$this->Recordset->EOF) {
				$this->LoadRowValues($this->Recordset);
				if (!$this->ShowOptionLink('<!--##=CTRL.CtrlID##-->')) {
					$sUserIdMsg = $Language->Phrase("NoDeletePermission");
					$this->setFailureMessage($sUserIdMsg);
					$res = FALSE;
					break;
				}
				$this->Recordset->MoveNext();
			}
			$this->Recordset->Close();
			if (!$res) $this->Page_Terminate("<!--##=sFnList##-->"); // Return to list
		}

	<!--##
		}
	##-->

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
		<!--## if (bInlineDelete) { ##-->
			$this->CurrentAction = "D"; // Delete record directly
		<!--## } else { ##-->
			$this->CurrentAction = "I"; // Display record
		<!--## } ##-->
		}
		
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}

	}
<!--##/session##-->
?>

<!--##session htmtable##-->
<?php
// Load records for display
if ($<!--##=sPageObj##-->->Recordset = $<!--##=sPageObj##-->->LoadRecordset())
	$<!--##=sPageObj##-->TotalRecs = $<!--##=sPageObj##-->->Recordset->RecordCount(); // Get record count
if ($<!--##=sPageObj##-->TotalRecs <= 0) { // No record found, exit
	if ($<!--##=sPageObj##-->->Recordset)
		$<!--##=sPageObj##-->->Recordset->Close();
	$<!--##=sPageObj##-->->Page_Terminate("<!--##=sFnList##-->"); // Return to list
}
?>

<div class="ewToolbar">
<!--##include phpcommon.php/breadcrumb##-->
<!--##include phpcommon.php/language##-->
<div class="clearfix"></div>
</div>

<!--##include phpcommon.php/header-message##-->
<!--##include phpcommon.php/phpcommon-message##-->

<form name="<!--##=sFormName##-->" id="<!--##=sFormName##-->" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">

<?php if ($<!--##=sPageObj##-->->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $<!--##=sPageObj##-->->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="<!--##=gsTblVar##-->">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($<!--##=sPageObj##-->->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>

<!--##
	var sMainTableStyle = "";
	if (bUseCustomTemplate) {
		sMainTableStyle = " style=\"display: none\"";
	}
##-->

<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table<!--##=ewCSSTableClass##-->>
<?php echo $<!--##=gsTblVar##-->->TableCustomInnerHtml ?>
	<thead>
	<tr<!--##=ewCSSTableHeaderClass##-->>

<!--##
	for (var i = 0, len = arFlds.length; i < len; i++) {
		if (GetFldObj(arFlds[i])) {
			sHeaderSpanId = "elh_" + gsTblVar + "_" + gsFldParm;
			sClassId = gsTblVar + "_" + gsFldParm;
##-->
<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=gsFldName##--> ?>
		<th><span id="<!--##=sHeaderSpanId##-->" class="<!--##=sClassId##-->"><!--##=SYSTEMFUNCTIONS.FieldCaption()##--></span></th>
<?php } ?>
<!--##
		}
	} // Field
##-->
	</tr>
	</thead>
	<tbody>
<?php
$<!--##=sPageObj##-->->RecCnt = 0;
$i = 0;
while (!$<!--##=sPageObj##-->->Recordset->EOF) {

	$<!--##=sPageObj##-->->RecCnt++;
	$<!--##=sPageObj##-->->RowCnt++;

	// Set row properties
	$<!--##=gsTblVar##-->->ResetAttrs();
	$<!--##=gsTblVar##-->->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$<!--##=sPageObj##-->->LoadRowValues($<!--##=sPageObj##-->->Recordset);

	// Render row
	$<!--##=sPageObj##-->->RenderRow();
?>

	<tr<?php echo $<!--##=gsTblVar##-->->RowAttributes() ?>>
<!--##
	for (var i = 0, len = arFlds.length; i < len; i++) {
		if (GetFldObj(arFlds[i])) {
##-->
<?php if ($<!--##=gsFldObj##-->->Visible) { // <!--##=gsFldName##--> ?>
		<td<?php echo $<!--##=gsFldObj##-->->CellAttributes() ?>><!--##=SYSTEMFUNCTIONS.FieldView()##--></td>
<?php } ?>
<!--##
		}
	} // Field
##-->
	</tr>

<?php
	$<!--##=sPageObj##-->->Recordset->MoveNext();
}

$<!--##=sPageObj##-->->Recordset->Close();
?>

</tbody>
</table>
</div>
</div>

<!--## if (bUseCustomTemplate) { ##-->
<!--##~SYSTEMFUNCTIONS.GetCustomTemplate()##-->
<!--## } ##-->

<div>
<button class="<!--##=sSubmitButtonClass##-->" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="<!--##=sCancelButtonClass##-->" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $<!--##=sPageObj##-->->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>

</form>

<!--## if (bUseCustomTemplate) { ##-->
<!--##~SYSTEMFUNCTIONS.RenderCustomTemplate()##-->
<!--## } ##-->

<script type="text/javascript">
<!--##=sFormName##-->.Init();
</script>

<!--##include phpcommon.php/footer-message##-->
<!--##/session##-->


<?php
<!--##session phpfunction##-->
<!--##/session##-->
?>