<?php include_once "sana_userinfo.php" ?>
<?php

// Create page object
if (!isset($sana_message_grid)) $sana_message_grid = new csana_message_grid();

// Page init
$sana_message_grid->Page_Init();

// Page main
$sana_message_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sana_message_grid->Page_Render();
?>
<?php if ($sana_message->Export == "") { ?>
<script type="text/javascript">

// Form object
var fsana_messagegrid = new ew_Form("fsana_messagegrid", "grid");
fsana_messagegrid.FormKeyCountName = '<?php echo $sana_message_grid->FormKeyCountName ?>';

// Validate form
fsana_messagegrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_personID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_message->personID->FldCaption(), $sana_message->personID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_personID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_message->personID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__userID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_message->_userID->FldCaption(), $sana_message->_userID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__userID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($sana_message->_userID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_messageType");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_message->messageType->FldCaption(), $sana_message->messageType->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_messageText");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sana_message->messageText->FldCaption(), $sana_message->messageText->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fsana_messagegrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "personID", false)) return false;
	if (ew_ValueChanged(fobj, infix, "_userID", false)) return false;
	if (ew_ValueChanged(fobj, infix, "messageType", false)) return false;
	if (ew_ValueChanged(fobj, infix, "messageText", false)) return false;
	return true;
}

// Form_CustomValidate event
fsana_messagegrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsana_messagegrid.ValidateRequired = true;
<?php } else { ?>
fsana_messagegrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsana_messagegrid.Lists["x_personID"] = {"LinkField":"x_personID","Ajax":true,"AutoFill":false,"DisplayFields":["x_personName","x_lastName","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fsana_messagegrid.Lists["x__userID"] = {"LinkField":"x__userID","Ajax":true,"AutoFill":false,"DisplayFields":["x_personName","x_lastName","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<?php } ?>
<?php
if ($sana_message->CurrentAction == "gridadd") {
	if ($sana_message->CurrentMode == "copy") {
		$bSelectLimit = $sana_message_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$sana_message_grid->TotalRecs = $sana_message->SelectRecordCount();
			$sana_message_grid->Recordset = $sana_message_grid->LoadRecordset($sana_message_grid->StartRec-1, $sana_message_grid->DisplayRecs);
		} else {
			if ($sana_message_grid->Recordset = $sana_message_grid->LoadRecordset())
				$sana_message_grid->TotalRecs = $sana_message_grid->Recordset->RecordCount();
		}
		$sana_message_grid->StartRec = 1;
		$sana_message_grid->DisplayRecs = $sana_message_grid->TotalRecs;
	} else {
		$sana_message->CurrentFilter = "0=1";
		$sana_message_grid->StartRec = 1;
		$sana_message_grid->DisplayRecs = $sana_message->GridAddRowCount;
	}
	$sana_message_grid->TotalRecs = $sana_message_grid->DisplayRecs;
	$sana_message_grid->StopRec = $sana_message_grid->DisplayRecs;
} else {
	$bSelectLimit = $sana_message_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($sana_message_grid->TotalRecs <= 0)
			$sana_message_grid->TotalRecs = $sana_message->SelectRecordCount();
	} else {
		if (!$sana_message_grid->Recordset && ($sana_message_grid->Recordset = $sana_message_grid->LoadRecordset()))
			$sana_message_grid->TotalRecs = $sana_message_grid->Recordset->RecordCount();
	}
	$sana_message_grid->StartRec = 1;
	$sana_message_grid->DisplayRecs = $sana_message_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$sana_message_grid->Recordset = $sana_message_grid->LoadRecordset($sana_message_grid->StartRec-1, $sana_message_grid->DisplayRecs);

	// Set no record found message
	if ($sana_message->CurrentAction == "" && $sana_message_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$sana_message_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($sana_message_grid->SearchWhere == "0=101")
			$sana_message_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$sana_message_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$sana_message_grid->RenderOtherOptions();
?>
<?php $sana_message_grid->ShowPageHeader(); ?>
<?php
$sana_message_grid->ShowMessage();
?>
<?php if ($sana_message_grid->TotalRecs > 0 || $sana_message->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div id="fsana_messagegrid" class="ewForm form-inline">
<div id="gmp_sana_message" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_sana_messagegrid" class="table ewTable">
<?php echo $sana_message->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$sana_message_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$sana_message_grid->RenderListOptions();

// Render list options (header, left)
$sana_message_grid->ListOptions->Render("header", "left");
?>
<?php if ($sana_message->messageID->Visible) { // messageID ?>
	<?php if ($sana_message->SortUrl($sana_message->messageID) == "") { ?>
		<th data-name="messageID"><div id="elh_sana_message_messageID" class="sana_message_messageID"><div class="ewTableHeaderCaption"><?php echo $sana_message->messageID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="messageID"><div><div id="elh_sana_message_messageID" class="sana_message_messageID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_message->messageID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sana_message->messageID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_message->messageID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_message->personID->Visible) { // personID ?>
	<?php if ($sana_message->SortUrl($sana_message->personID) == "") { ?>
		<th data-name="personID"><div id="elh_sana_message_personID" class="sana_message_personID"><div class="ewTableHeaderCaption"><?php echo $sana_message->personID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="personID"><div><div id="elh_sana_message_personID" class="sana_message_personID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_message->personID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sana_message->personID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_message->personID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_message->_userID->Visible) { // userID ?>
	<?php if ($sana_message->SortUrl($sana_message->_userID) == "") { ?>
		<th data-name="_userID"><div id="elh_sana_message__userID" class="sana_message__userID"><div class="ewTableHeaderCaption"><?php echo $sana_message->_userID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_userID"><div><div id="elh_sana_message__userID" class="sana_message__userID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_message->_userID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sana_message->_userID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_message->_userID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_message->messageType->Visible) { // messageType ?>
	<?php if ($sana_message->SortUrl($sana_message->messageType) == "") { ?>
		<th data-name="messageType"><div id="elh_sana_message_messageType" class="sana_message_messageType"><div class="ewTableHeaderCaption"><?php echo $sana_message->messageType->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="messageType"><div><div id="elh_sana_message_messageType" class="sana_message_messageType">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_message->messageType->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sana_message->messageType->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_message->messageType->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sana_message->messageText->Visible) { // messageText ?>
	<?php if ($sana_message->SortUrl($sana_message->messageText) == "") { ?>
		<th data-name="messageText"><div id="elh_sana_message_messageText" class="sana_message_messageText"><div class="ewTableHeaderCaption"><?php echo $sana_message->messageText->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="messageText"><div><div id="elh_sana_message_messageText" class="sana_message_messageText">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sana_message->messageText->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sana_message->messageText->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sana_message->messageText->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$sana_message_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$sana_message_grid->StartRec = 1;
$sana_message_grid->StopRec = $sana_message_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($sana_message_grid->FormKeyCountName) && ($sana_message->CurrentAction == "gridadd" || $sana_message->CurrentAction == "gridedit" || $sana_message->CurrentAction == "F")) {
		$sana_message_grid->KeyCount = $objForm->GetValue($sana_message_grid->FormKeyCountName);
		$sana_message_grid->StopRec = $sana_message_grid->StartRec + $sana_message_grid->KeyCount - 1;
	}
}
$sana_message_grid->RecCnt = $sana_message_grid->StartRec - 1;
if ($sana_message_grid->Recordset && !$sana_message_grid->Recordset->EOF) {
	$sana_message_grid->Recordset->MoveFirst();
	$bSelectLimit = $sana_message_grid->UseSelectLimit;
	if (!$bSelectLimit && $sana_message_grid->StartRec > 1)
		$sana_message_grid->Recordset->Move($sana_message_grid->StartRec - 1);
} elseif (!$sana_message->AllowAddDeleteRow && $sana_message_grid->StopRec == 0) {
	$sana_message_grid->StopRec = $sana_message->GridAddRowCount;
}

// Initialize aggregate
$sana_message->RowType = EW_ROWTYPE_AGGREGATEINIT;
$sana_message->ResetAttrs();
$sana_message_grid->RenderRow();
if ($sana_message->CurrentAction == "gridadd")
	$sana_message_grid->RowIndex = 0;
if ($sana_message->CurrentAction == "gridedit")
	$sana_message_grid->RowIndex = 0;
while ($sana_message_grid->RecCnt < $sana_message_grid->StopRec) {
	$sana_message_grid->RecCnt++;
	if (intval($sana_message_grid->RecCnt) >= intval($sana_message_grid->StartRec)) {
		$sana_message_grid->RowCnt++;
		if ($sana_message->CurrentAction == "gridadd" || $sana_message->CurrentAction == "gridedit" || $sana_message->CurrentAction == "F") {
			$sana_message_grid->RowIndex++;
			$objForm->Index = $sana_message_grid->RowIndex;
			if ($objForm->HasValue($sana_message_grid->FormActionName))
				$sana_message_grid->RowAction = strval($objForm->GetValue($sana_message_grid->FormActionName));
			elseif ($sana_message->CurrentAction == "gridadd")
				$sana_message_grid->RowAction = "insert";
			else
				$sana_message_grid->RowAction = "";
		}

		// Set up key count
		$sana_message_grid->KeyCount = $sana_message_grid->RowIndex;

		// Init row class and style
		$sana_message->ResetAttrs();
		$sana_message->CssClass = "";
		if ($sana_message->CurrentAction == "gridadd") {
			if ($sana_message->CurrentMode == "copy") {
				$sana_message_grid->LoadRowValues($sana_message_grid->Recordset); // Load row values
				$sana_message_grid->SetRecordKey($sana_message_grid->RowOldKey, $sana_message_grid->Recordset); // Set old record key
			} else {
				$sana_message_grid->LoadDefaultValues(); // Load default values
				$sana_message_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$sana_message_grid->LoadRowValues($sana_message_grid->Recordset); // Load row values
		}
		$sana_message->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($sana_message->CurrentAction == "gridadd") // Grid add
			$sana_message->RowType = EW_ROWTYPE_ADD; // Render add
		if ($sana_message->CurrentAction == "gridadd" && $sana_message->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$sana_message_grid->RestoreCurrentRowFormValues($sana_message_grid->RowIndex); // Restore form values
		if ($sana_message->CurrentAction == "gridedit") { // Grid edit
			if ($sana_message->EventCancelled) {
				$sana_message_grid->RestoreCurrentRowFormValues($sana_message_grid->RowIndex); // Restore form values
			}
			if ($sana_message_grid->RowAction == "insert")
				$sana_message->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$sana_message->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($sana_message->CurrentAction == "gridedit" && ($sana_message->RowType == EW_ROWTYPE_EDIT || $sana_message->RowType == EW_ROWTYPE_ADD) && $sana_message->EventCancelled) // Update failed
			$sana_message_grid->RestoreCurrentRowFormValues($sana_message_grid->RowIndex); // Restore form values
		if ($sana_message->RowType == EW_ROWTYPE_EDIT) // Edit row
			$sana_message_grid->EditRowCnt++;
		if ($sana_message->CurrentAction == "F") // Confirm row
			$sana_message_grid->RestoreCurrentRowFormValues($sana_message_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$sana_message->RowAttrs = array_merge($sana_message->RowAttrs, array('data-rowindex'=>$sana_message_grid->RowCnt, 'id'=>'r' . $sana_message_grid->RowCnt . '_sana_message', 'data-rowtype'=>$sana_message->RowType));

		// Render row
		$sana_message_grid->RenderRow();

		// Render list options
		$sana_message_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($sana_message_grid->RowAction <> "delete" && $sana_message_grid->RowAction <> "insertdelete" && !($sana_message_grid->RowAction == "insert" && $sana_message->CurrentAction == "F" && $sana_message_grid->EmptyRow())) {
?>
	<tr<?php echo $sana_message->RowAttributes() ?>>
<?php

// Render list options (body, left)
$sana_message_grid->ListOptions->Render("body", "left", $sana_message_grid->RowCnt);
?>
	<?php if ($sana_message->messageID->Visible) { // messageID ?>
		<td data-name="messageID"<?php echo $sana_message->messageID->CellAttributes() ?>>
<?php if ($sana_message->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="sana_message" data-field="x_messageID" name="o<?php echo $sana_message_grid->RowIndex ?>_messageID" id="o<?php echo $sana_message_grid->RowIndex ?>_messageID" value="<?php echo ew_HtmlEncode($sana_message->messageID->OldValue) ?>">
<?php } ?>
<?php if ($sana_message->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $sana_message_grid->RowCnt ?>_sana_message_messageID" class="form-group sana_message_messageID">
<span<?php echo $sana_message->messageID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sana_message->messageID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="sana_message" data-field="x_messageID" name="x<?php echo $sana_message_grid->RowIndex ?>_messageID" id="x<?php echo $sana_message_grid->RowIndex ?>_messageID" value="<?php echo ew_HtmlEncode($sana_message->messageID->CurrentValue) ?>">
<?php } ?>
<?php if ($sana_message->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $sana_message_grid->RowCnt ?>_sana_message_messageID" class="sana_message_messageID">
<span<?php echo $sana_message->messageID->ViewAttributes() ?>>
<?php echo $sana_message->messageID->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="sana_message" data-field="x_messageID" name="x<?php echo $sana_message_grid->RowIndex ?>_messageID" id="x<?php echo $sana_message_grid->RowIndex ?>_messageID" value="<?php echo ew_HtmlEncode($sana_message->messageID->FormValue) ?>">
<input type="hidden" data-table="sana_message" data-field="x_messageID" name="o<?php echo $sana_message_grid->RowIndex ?>_messageID" id="o<?php echo $sana_message_grid->RowIndex ?>_messageID" value="<?php echo ew_HtmlEncode($sana_message->messageID->OldValue) ?>">
<?php } ?>
<a id="<?php echo $sana_message_grid->PageObjName . "_row_" . $sana_message_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($sana_message->personID->Visible) { // personID ?>
		<td data-name="personID"<?php echo $sana_message->personID->CellAttributes() ?>>
<?php if ($sana_message->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($sana_message->personID->getSessionValue() <> "") { ?>
<span id="el<?php echo $sana_message_grid->RowCnt ?>_sana_message_personID" class="form-group sana_message_personID">
<span<?php echo $sana_message->personID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sana_message->personID->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $sana_message_grid->RowIndex ?>_personID" name="x<?php echo $sana_message_grid->RowIndex ?>_personID" value="<?php echo ew_HtmlEncode($sana_message->personID->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $sana_message_grid->RowCnt ?>_sana_message_personID" class="form-group sana_message_personID">
<?php
$wrkonchange = trim(" " . @$sana_message->personID->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$sana_message->personID->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $sana_message_grid->RowIndex ?>_personID" style="white-space: nowrap; z-index: <?php echo (9000 - $sana_message_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $sana_message_grid->RowIndex ?>_personID" id="sv_x<?php echo $sana_message_grid->RowIndex ?>_personID" value="<?php echo $sana_message->personID->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($sana_message->personID->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($sana_message->personID->getPlaceHolder()) ?>"<?php echo $sana_message->personID->EditAttributes() ?>>
</span>
<input type="hidden" data-table="sana_message" data-field="x_personID" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_message->personID->DisplayValueSeparator) ? json_encode($sana_message->personID->DisplayValueSeparator) : $sana_message->personID->DisplayValueSeparator) ?>" name="x<?php echo $sana_message_grid->RowIndex ?>_personID" id="x<?php echo $sana_message_grid->RowIndex ?>_personID" value="<?php echo ew_HtmlEncode($sana_message->personID->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_person`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->personID) . "',`lastName`) LIKE '{query_value}%'";
		break;
	case "fa":
		$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_person`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->personID) . "',`lastName`) LIKE '{query_value}%'";
		break;
	default:
		$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_person`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->personID) . "',`lastName`) LIKE '{query_value}%'";
		break;
}
$sana_message->Lookup_Selecting($sana_message->personID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $sana_message_grid->RowIndex ?>_personID" id="q_x<?php echo $sana_message_grid->RowIndex ?>_personID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fsana_messagegrid.CreateAutoSuggest({"id":"x<?php echo $sana_message_grid->RowIndex ?>_personID","forceSelect":false});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="sana_message" data-field="x_personID" name="o<?php echo $sana_message_grid->RowIndex ?>_personID" id="o<?php echo $sana_message_grid->RowIndex ?>_personID" value="<?php echo ew_HtmlEncode($sana_message->personID->OldValue) ?>">
<?php } ?>
<?php if ($sana_message->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($sana_message->personID->getSessionValue() <> "") { ?>
<span id="el<?php echo $sana_message_grid->RowCnt ?>_sana_message_personID" class="form-group sana_message_personID">
<span<?php echo $sana_message->personID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sana_message->personID->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $sana_message_grid->RowIndex ?>_personID" name="x<?php echo $sana_message_grid->RowIndex ?>_personID" value="<?php echo ew_HtmlEncode($sana_message->personID->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $sana_message_grid->RowCnt ?>_sana_message_personID" class="form-group sana_message_personID">
<?php
$wrkonchange = trim(" " . @$sana_message->personID->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$sana_message->personID->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $sana_message_grid->RowIndex ?>_personID" style="white-space: nowrap; z-index: <?php echo (9000 - $sana_message_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $sana_message_grid->RowIndex ?>_personID" id="sv_x<?php echo $sana_message_grid->RowIndex ?>_personID" value="<?php echo $sana_message->personID->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($sana_message->personID->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($sana_message->personID->getPlaceHolder()) ?>"<?php echo $sana_message->personID->EditAttributes() ?>>
</span>
<input type="hidden" data-table="sana_message" data-field="x_personID" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_message->personID->DisplayValueSeparator) ? json_encode($sana_message->personID->DisplayValueSeparator) : $sana_message->personID->DisplayValueSeparator) ?>" name="x<?php echo $sana_message_grid->RowIndex ?>_personID" id="x<?php echo $sana_message_grid->RowIndex ?>_personID" value="<?php echo ew_HtmlEncode($sana_message->personID->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_person`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->personID) . "',`lastName`) LIKE '{query_value}%'";
		break;
	case "fa":
		$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_person`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->personID) . "',`lastName`) LIKE '{query_value}%'";
		break;
	default:
		$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_person`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->personID) . "',`lastName`) LIKE '{query_value}%'";
		break;
}
$sana_message->Lookup_Selecting($sana_message->personID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $sana_message_grid->RowIndex ?>_personID" id="q_x<?php echo $sana_message_grid->RowIndex ?>_personID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fsana_messagegrid.CreateAutoSuggest({"id":"x<?php echo $sana_message_grid->RowIndex ?>_personID","forceSelect":false});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($sana_message->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $sana_message_grid->RowCnt ?>_sana_message_personID" class="sana_message_personID">
<span<?php echo $sana_message->personID->ViewAttributes() ?>>
<?php echo $sana_message->personID->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="sana_message" data-field="x_personID" name="x<?php echo $sana_message_grid->RowIndex ?>_personID" id="x<?php echo $sana_message_grid->RowIndex ?>_personID" value="<?php echo ew_HtmlEncode($sana_message->personID->FormValue) ?>">
<input type="hidden" data-table="sana_message" data-field="x_personID" name="o<?php echo $sana_message_grid->RowIndex ?>_personID" id="o<?php echo $sana_message_grid->RowIndex ?>_personID" value="<?php echo ew_HtmlEncode($sana_message->personID->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($sana_message->_userID->Visible) { // userID ?>
		<td data-name="_userID"<?php echo $sana_message->_userID->CellAttributes() ?>>
<?php if ($sana_message->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $sana_message_grid->RowCnt ?>_sana_message__userID" class="form-group sana_message__userID">
<?php
$wrkonchange = trim(" " . @$sana_message->_userID->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$sana_message->_userID->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $sana_message_grid->RowIndex ?>__userID" style="white-space: nowrap; z-index: <?php echo (9000 - $sana_message_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $sana_message_grid->RowIndex ?>__userID" id="sv_x<?php echo $sana_message_grid->RowIndex ?>__userID" value="<?php echo $sana_message->_userID->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($sana_message->_userID->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($sana_message->_userID->getPlaceHolder()) ?>"<?php echo $sana_message->_userID->EditAttributes() ?>>
</span>
<input type="hidden" data-table="sana_message" data-field="x__userID" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_message->_userID->DisplayValueSeparator) ? json_encode($sana_message->_userID->DisplayValueSeparator) : $sana_message->_userID->DisplayValueSeparator) ?>" name="x<?php echo $sana_message_grid->RowIndex ?>__userID" id="x<?php echo $sana_message_grid->RowIndex ?>__userID" value="<?php echo ew_HtmlEncode($sana_message->_userID->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_user`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->_userID) . "',`lastName`) LIKE '{query_value}%'";
		break;
	case "fa":
		$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_user`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->_userID) . "',`lastName`) LIKE '{query_value}%'";
		break;
	default:
		$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_user`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->_userID) . "',`lastName`) LIKE '{query_value}%'";
		break;
}
if (!$GLOBALS["sana_message"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["sana_user"]->AddUserIDFilter($sWhereWrk);
$sana_message->Lookup_Selecting($sana_message->_userID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $sana_message_grid->RowIndex ?>__userID" id="q_x<?php echo $sana_message_grid->RowIndex ?>__userID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fsana_messagegrid.CreateAutoSuggest({"id":"x<?php echo $sana_message_grid->RowIndex ?>__userID","forceSelect":false});
</script>
</span>
<input type="hidden" data-table="sana_message" data-field="x__userID" name="o<?php echo $sana_message_grid->RowIndex ?>__userID" id="o<?php echo $sana_message_grid->RowIndex ?>__userID" value="<?php echo ew_HtmlEncode($sana_message->_userID->OldValue) ?>">
<?php } ?>
<?php if ($sana_message->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $sana_message_grid->RowCnt ?>_sana_message__userID" class="form-group sana_message__userID">
<?php
$wrkonchange = trim(" " . @$sana_message->_userID->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$sana_message->_userID->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $sana_message_grid->RowIndex ?>__userID" style="white-space: nowrap; z-index: <?php echo (9000 - $sana_message_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $sana_message_grid->RowIndex ?>__userID" id="sv_x<?php echo $sana_message_grid->RowIndex ?>__userID" value="<?php echo $sana_message->_userID->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($sana_message->_userID->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($sana_message->_userID->getPlaceHolder()) ?>"<?php echo $sana_message->_userID->EditAttributes() ?>>
</span>
<input type="hidden" data-table="sana_message" data-field="x__userID" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_message->_userID->DisplayValueSeparator) ? json_encode($sana_message->_userID->DisplayValueSeparator) : $sana_message->_userID->DisplayValueSeparator) ?>" name="x<?php echo $sana_message_grid->RowIndex ?>__userID" id="x<?php echo $sana_message_grid->RowIndex ?>__userID" value="<?php echo ew_HtmlEncode($sana_message->_userID->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_user`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->_userID) . "',`lastName`) LIKE '{query_value}%'";
		break;
	case "fa":
		$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_user`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->_userID) . "',`lastName`) LIKE '{query_value}%'";
		break;
	default:
		$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_user`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->_userID) . "',`lastName`) LIKE '{query_value}%'";
		break;
}
if (!$GLOBALS["sana_message"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["sana_user"]->AddUserIDFilter($sWhereWrk);
$sana_message->Lookup_Selecting($sana_message->_userID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $sana_message_grid->RowIndex ?>__userID" id="q_x<?php echo $sana_message_grid->RowIndex ?>__userID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fsana_messagegrid.CreateAutoSuggest({"id":"x<?php echo $sana_message_grid->RowIndex ?>__userID","forceSelect":false});
</script>
</span>
<?php } ?>
<?php if ($sana_message->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $sana_message_grid->RowCnt ?>_sana_message__userID" class="sana_message__userID">
<span<?php echo $sana_message->_userID->ViewAttributes() ?>>
<?php echo $sana_message->_userID->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="sana_message" data-field="x__userID" name="x<?php echo $sana_message_grid->RowIndex ?>__userID" id="x<?php echo $sana_message_grid->RowIndex ?>__userID" value="<?php echo ew_HtmlEncode($sana_message->_userID->FormValue) ?>">
<input type="hidden" data-table="sana_message" data-field="x__userID" name="o<?php echo $sana_message_grid->RowIndex ?>__userID" id="o<?php echo $sana_message_grid->RowIndex ?>__userID" value="<?php echo ew_HtmlEncode($sana_message->_userID->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($sana_message->messageType->Visible) { // messageType ?>
		<td data-name="messageType"<?php echo $sana_message->messageType->CellAttributes() ?>>
<?php if ($sana_message->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $sana_message_grid->RowCnt ?>_sana_message_messageType" class="form-group sana_message_messageType">
<input type="text" data-table="sana_message" data-field="x_messageType" name="x<?php echo $sana_message_grid->RowIndex ?>_messageType" id="x<?php echo $sana_message_grid->RowIndex ?>_messageType" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_message->messageType->getPlaceHolder()) ?>" value="<?php echo $sana_message->messageType->EditValue ?>"<?php echo $sana_message->messageType->EditAttributes() ?>>
</span>
<input type="hidden" data-table="sana_message" data-field="x_messageType" name="o<?php echo $sana_message_grid->RowIndex ?>_messageType" id="o<?php echo $sana_message_grid->RowIndex ?>_messageType" value="<?php echo ew_HtmlEncode($sana_message->messageType->OldValue) ?>">
<?php } ?>
<?php if ($sana_message->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $sana_message_grid->RowCnt ?>_sana_message_messageType" class="form-group sana_message_messageType">
<input type="text" data-table="sana_message" data-field="x_messageType" name="x<?php echo $sana_message_grid->RowIndex ?>_messageType" id="x<?php echo $sana_message_grid->RowIndex ?>_messageType" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_message->messageType->getPlaceHolder()) ?>" value="<?php echo $sana_message->messageType->EditValue ?>"<?php echo $sana_message->messageType->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($sana_message->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $sana_message_grid->RowCnt ?>_sana_message_messageType" class="sana_message_messageType">
<span<?php echo $sana_message->messageType->ViewAttributes() ?>>
<?php echo $sana_message->messageType->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="sana_message" data-field="x_messageType" name="x<?php echo $sana_message_grid->RowIndex ?>_messageType" id="x<?php echo $sana_message_grid->RowIndex ?>_messageType" value="<?php echo ew_HtmlEncode($sana_message->messageType->FormValue) ?>">
<input type="hidden" data-table="sana_message" data-field="x_messageType" name="o<?php echo $sana_message_grid->RowIndex ?>_messageType" id="o<?php echo $sana_message_grid->RowIndex ?>_messageType" value="<?php echo ew_HtmlEncode($sana_message->messageType->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($sana_message->messageText->Visible) { // messageText ?>
		<td data-name="messageText"<?php echo $sana_message->messageText->CellAttributes() ?>>
<?php if ($sana_message->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $sana_message_grid->RowCnt ?>_sana_message_messageText" class="form-group sana_message_messageText">
<textarea data-table="sana_message" data-field="x_messageText" name="x<?php echo $sana_message_grid->RowIndex ?>_messageText" id="x<?php echo $sana_message_grid->RowIndex ?>_messageText" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($sana_message->messageText->getPlaceHolder()) ?>"<?php echo $sana_message->messageText->EditAttributes() ?>><?php echo $sana_message->messageText->EditValue ?></textarea>
</span>
<input type="hidden" data-table="sana_message" data-field="x_messageText" name="o<?php echo $sana_message_grid->RowIndex ?>_messageText" id="o<?php echo $sana_message_grid->RowIndex ?>_messageText" value="<?php echo ew_HtmlEncode($sana_message->messageText->OldValue) ?>">
<?php } ?>
<?php if ($sana_message->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $sana_message_grid->RowCnt ?>_sana_message_messageText" class="form-group sana_message_messageText">
<textarea data-table="sana_message" data-field="x_messageText" name="x<?php echo $sana_message_grid->RowIndex ?>_messageText" id="x<?php echo $sana_message_grid->RowIndex ?>_messageText" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($sana_message->messageText->getPlaceHolder()) ?>"<?php echo $sana_message->messageText->EditAttributes() ?>><?php echo $sana_message->messageText->EditValue ?></textarea>
</span>
<?php } ?>
<?php if ($sana_message->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $sana_message_grid->RowCnt ?>_sana_message_messageText" class="sana_message_messageText">
<span<?php echo $sana_message->messageText->ViewAttributes() ?>>
<?php echo $sana_message->messageText->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="sana_message" data-field="x_messageText" name="x<?php echo $sana_message_grid->RowIndex ?>_messageText" id="x<?php echo $sana_message_grid->RowIndex ?>_messageText" value="<?php echo ew_HtmlEncode($sana_message->messageText->FormValue) ?>">
<input type="hidden" data-table="sana_message" data-field="x_messageText" name="o<?php echo $sana_message_grid->RowIndex ?>_messageText" id="o<?php echo $sana_message_grid->RowIndex ?>_messageText" value="<?php echo ew_HtmlEncode($sana_message->messageText->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$sana_message_grid->ListOptions->Render("body", "right", $sana_message_grid->RowCnt);
?>
	</tr>
<?php if ($sana_message->RowType == EW_ROWTYPE_ADD || $sana_message->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fsana_messagegrid.UpdateOpts(<?php echo $sana_message_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($sana_message->CurrentAction <> "gridadd" || $sana_message->CurrentMode == "copy")
		if (!$sana_message_grid->Recordset->EOF) $sana_message_grid->Recordset->MoveNext();
}
?>
<?php
	if ($sana_message->CurrentMode == "add" || $sana_message->CurrentMode == "copy" || $sana_message->CurrentMode == "edit") {
		$sana_message_grid->RowIndex = '$rowindex$';
		$sana_message_grid->LoadDefaultValues();

		// Set row properties
		$sana_message->ResetAttrs();
		$sana_message->RowAttrs = array_merge($sana_message->RowAttrs, array('data-rowindex'=>$sana_message_grid->RowIndex, 'id'=>'r0_sana_message', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($sana_message->RowAttrs["class"], "ewTemplate");
		$sana_message->RowType = EW_ROWTYPE_ADD;

		// Render row
		$sana_message_grid->RenderRow();

		// Render list options
		$sana_message_grid->RenderListOptions();
		$sana_message_grid->StartRowCnt = 0;
?>
	<tr<?php echo $sana_message->RowAttributes() ?>>
<?php

// Render list options (body, left)
$sana_message_grid->ListOptions->Render("body", "left", $sana_message_grid->RowIndex);
?>
	<?php if ($sana_message->messageID->Visible) { // messageID ?>
		<td data-name="messageID">
<?php if ($sana_message->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_sana_message_messageID" class="form-group sana_message_messageID">
<span<?php echo $sana_message->messageID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sana_message->messageID->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="sana_message" data-field="x_messageID" name="x<?php echo $sana_message_grid->RowIndex ?>_messageID" id="x<?php echo $sana_message_grid->RowIndex ?>_messageID" value="<?php echo ew_HtmlEncode($sana_message->messageID->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="sana_message" data-field="x_messageID" name="o<?php echo $sana_message_grid->RowIndex ?>_messageID" id="o<?php echo $sana_message_grid->RowIndex ?>_messageID" value="<?php echo ew_HtmlEncode($sana_message->messageID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($sana_message->personID->Visible) { // personID ?>
		<td data-name="personID">
<?php if ($sana_message->CurrentAction <> "F") { ?>
<?php if ($sana_message->personID->getSessionValue() <> "") { ?>
<span id="el$rowindex$_sana_message_personID" class="form-group sana_message_personID">
<span<?php echo $sana_message->personID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sana_message->personID->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $sana_message_grid->RowIndex ?>_personID" name="x<?php echo $sana_message_grid->RowIndex ?>_personID" value="<?php echo ew_HtmlEncode($sana_message->personID->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_sana_message_personID" class="form-group sana_message_personID">
<?php
$wrkonchange = trim(" " . @$sana_message->personID->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$sana_message->personID->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $sana_message_grid->RowIndex ?>_personID" style="white-space: nowrap; z-index: <?php echo (9000 - $sana_message_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $sana_message_grid->RowIndex ?>_personID" id="sv_x<?php echo $sana_message_grid->RowIndex ?>_personID" value="<?php echo $sana_message->personID->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($sana_message->personID->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($sana_message->personID->getPlaceHolder()) ?>"<?php echo $sana_message->personID->EditAttributes() ?>>
</span>
<input type="hidden" data-table="sana_message" data-field="x_personID" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_message->personID->DisplayValueSeparator) ? json_encode($sana_message->personID->DisplayValueSeparator) : $sana_message->personID->DisplayValueSeparator) ?>" name="x<?php echo $sana_message_grid->RowIndex ?>_personID" id="x<?php echo $sana_message_grid->RowIndex ?>_personID" value="<?php echo ew_HtmlEncode($sana_message->personID->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_person`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->personID) . "',`lastName`) LIKE '{query_value}%'";
		break;
	case "fa":
		$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_person`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->personID) . "',`lastName`) LIKE '{query_value}%'";
		break;
	default:
		$sSqlWrk = "SELECT `personID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_person`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->personID) . "',`lastName`) LIKE '{query_value}%'";
		break;
}
$sana_message->Lookup_Selecting($sana_message->personID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $sana_message_grid->RowIndex ?>_personID" id="q_x<?php echo $sana_message_grid->RowIndex ?>_personID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fsana_messagegrid.CreateAutoSuggest({"id":"x<?php echo $sana_message_grid->RowIndex ?>_personID","forceSelect":false});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_sana_message_personID" class="form-group sana_message_personID">
<span<?php echo $sana_message->personID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sana_message->personID->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="sana_message" data-field="x_personID" name="x<?php echo $sana_message_grid->RowIndex ?>_personID" id="x<?php echo $sana_message_grid->RowIndex ?>_personID" value="<?php echo ew_HtmlEncode($sana_message->personID->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="sana_message" data-field="x_personID" name="o<?php echo $sana_message_grid->RowIndex ?>_personID" id="o<?php echo $sana_message_grid->RowIndex ?>_personID" value="<?php echo ew_HtmlEncode($sana_message->personID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($sana_message->_userID->Visible) { // userID ?>
		<td data-name="_userID">
<?php if ($sana_message->CurrentAction <> "F") { ?>
<span id="el$rowindex$_sana_message__userID" class="form-group sana_message__userID">
<?php
$wrkonchange = trim(" " . @$sana_message->_userID->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$sana_message->_userID->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $sana_message_grid->RowIndex ?>__userID" style="white-space: nowrap; z-index: <?php echo (9000 - $sana_message_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $sana_message_grid->RowIndex ?>__userID" id="sv_x<?php echo $sana_message_grid->RowIndex ?>__userID" value="<?php echo $sana_message->_userID->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($sana_message->_userID->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($sana_message->_userID->getPlaceHolder()) ?>"<?php echo $sana_message->_userID->EditAttributes() ?>>
</span>
<input type="hidden" data-table="sana_message" data-field="x__userID" data-value-separator="<?php echo ew_HtmlEncode(is_array($sana_message->_userID->DisplayValueSeparator) ? json_encode($sana_message->_userID->DisplayValueSeparator) : $sana_message->_userID->DisplayValueSeparator) ?>" name="x<?php echo $sana_message_grid->RowIndex ?>__userID" id="x<?php echo $sana_message_grid->RowIndex ?>__userID" value="<?php echo ew_HtmlEncode($sana_message->_userID->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
switch (@$gsLanguage) {
	case "en":
		$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_user`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->_userID) . "',`lastName`) LIKE '{query_value}%'";
		break;
	case "fa":
		$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_user`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->_userID) . "',`lastName`) LIKE '{query_value}%'";
		break;
	default:
		$sSqlWrk = "SELECT `userID`, `personName` AS `DispFld`, `lastName` AS `Disp2Fld` FROM `sana_user`";
		$sWhereWrk = "`personName` LIKE '{query_value}%' OR CONCAT(`personName`,'" . ew_ValueSeparator(1, $Page->_userID) . "',`lastName`) LIKE '{query_value}%'";
		break;
}
if (!$GLOBALS["sana_message"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["sana_user"]->AddUserIDFilter($sWhereWrk);
$sana_message->Lookup_Selecting($sana_message->_userID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $sana_message_grid->RowIndex ?>__userID" id="q_x<?php echo $sana_message_grid->RowIndex ?>__userID" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&d=">
<script type="text/javascript">
fsana_messagegrid.CreateAutoSuggest({"id":"x<?php echo $sana_message_grid->RowIndex ?>__userID","forceSelect":false});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_sana_message__userID" class="form-group sana_message__userID">
<span<?php echo $sana_message->_userID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sana_message->_userID->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="sana_message" data-field="x__userID" name="x<?php echo $sana_message_grid->RowIndex ?>__userID" id="x<?php echo $sana_message_grid->RowIndex ?>__userID" value="<?php echo ew_HtmlEncode($sana_message->_userID->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="sana_message" data-field="x__userID" name="o<?php echo $sana_message_grid->RowIndex ?>__userID" id="o<?php echo $sana_message_grid->RowIndex ?>__userID" value="<?php echo ew_HtmlEncode($sana_message->_userID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($sana_message->messageType->Visible) { // messageType ?>
		<td data-name="messageType">
<?php if ($sana_message->CurrentAction <> "F") { ?>
<span id="el$rowindex$_sana_message_messageType" class="form-group sana_message_messageType">
<input type="text" data-table="sana_message" data-field="x_messageType" name="x<?php echo $sana_message_grid->RowIndex ?>_messageType" id="x<?php echo $sana_message_grid->RowIndex ?>_messageType" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($sana_message->messageType->getPlaceHolder()) ?>" value="<?php echo $sana_message->messageType->EditValue ?>"<?php echo $sana_message->messageType->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_sana_message_messageType" class="form-group sana_message_messageType">
<span<?php echo $sana_message->messageType->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sana_message->messageType->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="sana_message" data-field="x_messageType" name="x<?php echo $sana_message_grid->RowIndex ?>_messageType" id="x<?php echo $sana_message_grid->RowIndex ?>_messageType" value="<?php echo ew_HtmlEncode($sana_message->messageType->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="sana_message" data-field="x_messageType" name="o<?php echo $sana_message_grid->RowIndex ?>_messageType" id="o<?php echo $sana_message_grid->RowIndex ?>_messageType" value="<?php echo ew_HtmlEncode($sana_message->messageType->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($sana_message->messageText->Visible) { // messageText ?>
		<td data-name="messageText">
<?php if ($sana_message->CurrentAction <> "F") { ?>
<span id="el$rowindex$_sana_message_messageText" class="form-group sana_message_messageText">
<textarea data-table="sana_message" data-field="x_messageText" name="x<?php echo $sana_message_grid->RowIndex ?>_messageText" id="x<?php echo $sana_message_grid->RowIndex ?>_messageText" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($sana_message->messageText->getPlaceHolder()) ?>"<?php echo $sana_message->messageText->EditAttributes() ?>><?php echo $sana_message->messageText->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el$rowindex$_sana_message_messageText" class="form-group sana_message_messageText">
<span<?php echo $sana_message->messageText->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sana_message->messageText->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="sana_message" data-field="x_messageText" name="x<?php echo $sana_message_grid->RowIndex ?>_messageText" id="x<?php echo $sana_message_grid->RowIndex ?>_messageText" value="<?php echo ew_HtmlEncode($sana_message->messageText->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="sana_message" data-field="x_messageText" name="o<?php echo $sana_message_grid->RowIndex ?>_messageText" id="o<?php echo $sana_message_grid->RowIndex ?>_messageText" value="<?php echo ew_HtmlEncode($sana_message->messageText->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$sana_message_grid->ListOptions->Render("body", "right", $sana_message_grid->RowCnt);
?>
<script type="text/javascript">
fsana_messagegrid.UpdateOpts(<?php echo $sana_message_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($sana_message->CurrentMode == "add" || $sana_message->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $sana_message_grid->FormKeyCountName ?>" id="<?php echo $sana_message_grid->FormKeyCountName ?>" value="<?php echo $sana_message_grid->KeyCount ?>">
<?php echo $sana_message_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($sana_message->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $sana_message_grid->FormKeyCountName ?>" id="<?php echo $sana_message_grid->FormKeyCountName ?>" value="<?php echo $sana_message_grid->KeyCount ?>">
<?php echo $sana_message_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($sana_message->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fsana_messagegrid">
</div>
<?php

// Close recordset
if ($sana_message_grid->Recordset)
	$sana_message_grid->Recordset->Close();
?>
<?php if ($sana_message_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($sana_message_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($sana_message_grid->TotalRecs == 0 && $sana_message->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($sana_message_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($sana_message->Export == "") { ?>
<script type="text/javascript">
fsana_messagegrid.Init();
</script>
<?php } ?>
<?php
$sana_message_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$sana_message_grid->Page_Terminate();
?>
