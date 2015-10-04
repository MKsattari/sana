<?php
<!--##session phpmain##-->
	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		set_error_handler("ew_ErrorHandler");

		// Set up Breadcrumb
		//$this->SetupBreadcrumb(); // Not used

		// Process form if post back
		if ($objForm->GetValue("a_addopt") <> "") {

			$this->CurrentAction = $objForm->GetValue("a_addopt"); // Get form action

			$this->LoadFormValues(); // Load form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}

		} else { // Not post back
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {

			case "I": // Blank record, no action required
				break;
				
			case "A": // Add new record

				$this->SendEmail = TRUE; // Send email on add success

				if ($this->AddRow()) { // Add successful
					$row = array();
<!--##
		for (var i = 0, len = arAllFlds.length; i < len; i++) {
			if (GetFldObj(arAllFlds[i]) && !ew_IsBinaryField(goFld)) {
##-->
					$row["<!--##=gsFldVar##-->"] = $this-><!--##=gsFldParm##-->->DbValue;
<!--##
			}
		} // AllField
##-->
					if (!EW_DEBUG_ENABLED && ob_get_length())
						ob_end_clean();
					echo ew_ArrayToJson(array($row));
				} else {
					$this->ShowMessage();
				}
				$this->Page_Terminate();
				exit();
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add type
		$this->ResetAttrs();
		$this->RenderRow();

	}
<!--##/session##-->
?>


<!--##session htmtable##-->
<!--##
	ewAddOptBootstrapLeftColumnClass = "col-sm-3";
	ewAddOptBootstrapRightColumnClass = "col-sm-9";
	ewAddOptBootstrapLabelClass = ewAddOptBootstrapLeftColumnClass + " control-label ewLabel";
##-->

<!--##include phpcommon.php/phpcommon-message##-->

<form name="<!--##=sFormName##-->" id="<!--##=sFormName##-->" class="ewForm form-horizontal" action="<!--##=sFnAddOpt##-->" method="post">

<?php if ($<!--##=sPageObj##-->->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $<!--##=sPageObj##-->->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="<!--##=gsTblVar##-->">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">

<!--##
		for (var i = 0, len = arFlds.length; i < len; i++) {
			if (GetFldObj(arFlds[i])) {
				if (!goFld.FldAutoIncrement && ew_IsEmpty(goFld.FldAutoUpdateValue) && !(goFld.FldHtmlTag == "HIDDEN" && ew_IsNotEmpty(goFld.FldDefault))) {
					sHeaderSpanId = "elh_" + gsTblVar + "_" + gsFldParm;
					sForLabel = ew_UseForLabel(goFld) ? " for=\"" + gsFldVar + "\"" : "";
##-->
	<div class="form-group">
		<label class="<!--##=ewAddOptBootstrapLabelClass##-->"<!--##=sForLabel##-->><!--##=SYSTEMFUNCTIONS.FieldCaption()##--></label>
		<div class="<!--##=ewAddOptBootstrapRightColumnClass##-->"><!--##=SYSTEMFUNCTIONS.FieldAdd()##--></div>
	</div>
<!--##
				} else {
##-->
	<!--##=SYSTEMFUNCTIONS.FieldAdd()##-->
<!--##
				}
			}
		} // Field
##-->

</form>

<script type="text/javascript">
<!--##=sFormName##-->.Init();
<!--## if (bUseJavaScriptMessage) { ##-->
ew_ShowMessage();
<!--## } ##-->
</script>

<!--##/session##-->


<?php
<!--##session phpevents##-->
	// Custom validate event
	<!--##~SYSTEMFUNCTIONS.GetServerScript("Table","Form_CustomValidate")##-->
<!--##/session##-->
?>