<!--##session currenttable##-->
<!--##
	if (DB.UseDynamicUserLevel && ew_IsNotEmpty(DB.UserLevelTbl)) {
		TABLE = DB.Tables(DB.UserLevelTbl);
		gsTblVar = TABLE.TblVar;
	}
##-->
<!--##/session##-->


<!--##session phpconfig##-->
<!--##
	if (GetFldObj(DB.UserLevelIdFld)) {
		sUserLevelIDFldVar = gsFldVar;
		sUserLevelIDFldParm = gsFldParm;
	}

	// Support selected row color
	if (iRecPerRow < 1) {
		sListTableId = " id=\"" + sPageObj + "_main\"";
	} else {
		sListTableId = "";
	}
##-->
<!--##/session##-->

<?php
<!--##session phpmain##-->

	var $TempPriv;
	var $Disabled;
	var $Privileges;
	var $TableNameCount;
	var $ReportLanguage;

	var $UserLevelList = array();
	var $UserLevelPrivList = array();
	var $TableList = array();

	//
	// Page main
	//
	function Page_Main() {
		global $Security, $Language;
		global $EW_RELATED_LANGUAGE_FOLDER;
		global $Breadcrumb;

		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb = new cBreadcrumb;
		$Breadcrumb->Add("list", "<!--##=sUserLevelTblVar##-->", "<!--##=sFnUserLevelTableList##-->", "", "<!--##=sUserLevelTblVar##-->");
		$Breadcrumb->Add("<!--##=CTRL.CtrlID##-->", "UserLevelPermission", $url);

		// Try to load PHP Report Maker language file
		// Note: The langauge IDs must be the same in both projects
		$Security->LoadUserLevelFromConfigFile($this->UserLevelList, $this->UserLevelPrivList, $this->TableList, TRUE);
		if ($EW_RELATED_LANGUAGE_FOLDER <> "")
			$this->ReportLanguage = new cLanguage($EW_RELATED_LANGUAGE_FOLDER);

		$this->TableNameCount = count($this->TableList);

		$this->Privileges = &ew_InitArray($this->TableNameCount, 0);

		// Get action
		if (@$_POST["a_edit"] == "") {
			$this->CurrentAction = "I"; // Display with input box
			// Load key from QueryString
			if (@$_GET["<!--##=sUserLevelIDFldParm##-->"] <> "") {
				$this-><!--##=sUserLevelIDFldParm##-->->setQueryStringValue($_GET["<!--##=sUserLevelIDFldParm##-->"]);
			} else {
				$this->Page_Terminate("<!--##=sFnUserLevelTableList##-->"); // Return to list
			}
			if ($this-><!--##=sUserLevelIDFldParm##-->->QueryStringValue == "-1") {
				$this->Disabled = " disabled=\"disabled\"";
			} else {
				$this->Disabled = "";
			}
		} else {
			$this->CurrentAction = $_POST["a_edit"];
			// Get fields from form
			$this-><!--##=sUserLevelIDFldParm##-->->setFormValue($_POST["<!--##=sUserLevelIDFldVar##-->"]);
			for ($i = 0; $i < $this->TableNameCount; $i++) {
				if (defined("EW_USER_LEVEL_COMPAT")) {
					$this->Privileges[$i] = intval(@$_POST["Add_" . $i]) + 
						intval(@$_POST["Delete_" . $i]) + intval(@$_POST["Edit_" . $i]) +
						intval(@$_POST["List_" . $i]);
				} else {
					$this->Privileges[$i] = intval(@$_POST["Add_" . $i]) +
						intval(@$_POST["Delete_" . $i]) + intval(@$_POST["Edit_" . $i]) +
						intval(@$_POST["List_" . $i]) + intval(@$_POST["View_" . $i]) +
						intval(@$_POST["Search_" . $i]);
				}
			}
		}
		
		switch ($this->CurrentAction) {
			case "I": // Display
				if (!$Security->SetUpUserLevelEx()) // Get all User Level info
					$this->Page_Terminate("<!--##=sFnUserLevelTableList##-->"); // Return to list
				break;
			case "U": // Update
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
					// Alternatively, comment out the following line to go back to this page
					$this->Page_Terminate("<!--##=sFnUserLevelTableList##-->"); // Return to list
				}
		}
	}
<!--##/session##-->
?>

<!--##session htmtable##-->
<div class="ewToolbar">
<!--##include phpcommon.php/breadcrumb##-->
<!--##include phpcommon.php/language##-->
<div class="clearfix"></div>
</div>

<p><!--##@UserLevel##--><?php echo $Security->GetUserLevelName($<!--##=gsTblVar##-->-><!--##=sUserLevelIDFldParm##-->->CurrentValue) ?>(<?php echo $<!--##=gsTblVar##-->-><!--##=sUserLevelIDFldParm##-->->CurrentValue ?>)</p>

<!--##include phpcommon.php/phpcommon-message##-->

<script type="text/javascript">
var <!--##=sFormName##--> = new ew_Form("<!--##=sFormName##-->");
</script>

<form name="<!--##=sFormName##-->" id="<!--##=sFormName##-->" class="form-inline ewForm ewUserprivForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($<!--##=sPageObj##-->->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $<!--##=sPageObj##-->->Token ?>">
<?php } ?>
<div class="ewDesktop">
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<input type="hidden" name="t" value="<!--##=gsTblVar##-->">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<!-- hidden tag for User Level ID -->
<input type="hidden" name="<!--##=sUserLevelIDFldVar##-->" id="<!--##=sUserLevelIDFldVar##-->" value="<?php echo $<!--##=gsTblVar##-->-><!--##=sUserLevelIDFldParm##-->->CurrentValue ?>">
<table id="<!--##=ewTableId##-->"<!--##=ewCSSTableClass##-->>
	<thead>
	<tr<!--##=ewCSSTableHeaderClass##-->>
		<th><!--##@TableOrView##--></th>
		<th><!--##@PermissionAddCopy##--> <input type="checkbox" class="ewPriv" name="Add" id="Add" onclick="ew_SelectAll(this);"<?php echo $<!--##=sPageObj##-->->Disabled ?>></th>
		<th><!--##@PermissionDelete##--> <input type="checkbox" class="ewPriv" name="Delete" id="Delete" onclick="ew_SelectAll(this);"<?php echo $<!--##=sPageObj##-->->Disabled ?>></th>
		<th><!--##@PermissionEdit##--> <input type="checkbox" class="ewPriv" name="Edit" id="Edit" onclick="ew_SelectAll(this);"<?php echo $<!--##=sPageObj##-->->Disabled ?>></th>
<?php if (defined("EW_USER_LEVEL_COMPAT")) { ?>
		<th><!--##@PermissionListSearchView##--> <input type="checkbox" class="ewPriv" name="List" id="List" onclick="ew_SelectAll(this);"<?php echo $<!--##=sPageObj##-->->Disabled ?>></th>
<?php } else { ?>
		<th><!--##@PermissionList##--> <input type="checkbox" class="ewPriv" name="List" id="List" onclick="ew_SelectAll(this);"<?php echo $<!--##=sPageObj##-->->Disabled ?>></th>
		<th><!--##@PermissionView##--> <input type="checkbox" class="ewPriv" name="View" id="View" onclick="ew_SelectAll(this);"<?php echo $<!--##=sPageObj##-->->Disabled ?>></th>
		<th><!--##@PermissionSearch##--> <input type="checkbox" class="ewPriv" name="Search" id="Search" onclick="ew_SelectAll(this);"<?php echo $<!--##=sPageObj##-->->Disabled ?>></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
for ($i = 0; $i < $<!--##=sPageObj##-->->TableNameCount; $i++) {
	$<!--##=sPageObj##-->->TempPriv = $Security->GetUserLevelPrivEx($<!--##=sPageObj##-->->TableList[$i][4] . $<!--##=sPageObj##-->->TableList[$i][0], $<!--##=gsTblVar##-->-><!--##=sUserLevelIDFldParm##-->->CurrentValue);

		// Set row properties
		$<!--##=gsTblVar##-->->ResetAttrs();
?>
	<tr<?php echo $<!--##=gsTblVar##-->->RowAttributes() ?>>
		<td><?php echo $<!--##=sPageObj##-->->GetTableCaption($i) ?></td>
		<td class="ewCheckbox"><input type="checkbox" class="ewPriv" name="Add_<?php echo $i ?>" id="Add_<?php echo $i ?>" value="1"<?php if (($<!--##=sPageObj##-->->TempPriv & EW_ALLOW_ADD) == EW_ALLOW_ADD) { ?> checked="checked"<?php } ?><?php echo $<!--##=sPageObj##-->->Disabled ?>></td>
		<td class="ewCheckbox"><input type="checkbox" class="ewPriv" name="Delete_<?php echo $i ?>" id="Delete_<?php echo $i ?>" value="2"<?php if (($<!--##=sPageObj##-->->TempPriv & EW_ALLOW_DELETE) == EW_ALLOW_DELETE) { ?> checked="checked"<?php } ?><?php echo $<!--##=sPageObj##-->->Disabled ?>></td>
		<td class="ewCheckbox"><input type="checkbox" class="ewPriv" name="Edit_<?php echo $i ?>" id="Edit_<?php echo $i ?>" value="4"<?php if (($<!--##=sPageObj##-->->TempPriv & EW_ALLOW_EDIT) == EW_ALLOW_EDIT) { ?> checked="checked"<?php } ?><?php echo $<!--##=sPageObj##-->->Disabled ?>></td>
<?php if (defined("EW_USER_LEVEL_COMPAT")) { ?>
		<td class="ewCheckbox"><input type="checkbox" class="ewPriv" name="List_<?php echo $i ?>" id="List_<?php echo $i ?>" value="8"<?php if (($<!--##=sPageObj##-->->TempPriv & EW_ALLOW_LIST) == EW_ALLOW_LIST) { ?> checked="checked"<?php } ?><?php echo $<!--##=sPageObj##-->->Disabled ?>></td>
<?php } else { ?>
		<td class="ewCheckbox"><input type="checkbox" class="ewPriv" name="List_<?php echo $i ?>" id="List_<?php echo $i ?>" value="8"<?php if (($<!--##=sPageObj##-->->TempPriv & EW_ALLOW_LIST) == EW_ALLOW_LIST) { ?> checked="checked"<?php } ?><?php echo $<!--##=sPageObj##-->->Disabled ?>></td>
		<td class="ewCheckbox"><input type="checkbox" class="ewPriv" name="View_<?php echo $i ?>" id="View_<?php echo $i ?>" value="32"<?php if (($<!--##=sPageObj##-->->TempPriv & EW_ALLOW_VIEW) == EW_ALLOW_VIEW) { ?> checked="checked"<?php } ?><?php echo $<!--##=sPageObj##-->->Disabled ?>></td>
		<td class="ewCheckbox"><input type="checkbox" class="ewPriv" name="Search_<?php echo $i ?>" id="Search_<?php echo $i ?>" value="64"<?php if (($<!--##=sPageObj##-->->TempPriv & EW_ALLOW_SEARCH) == EW_ALLOW_SEARCH) { ?> checked="checked"<?php } ?><?php echo $<!--##=sPageObj##-->->Disabled ?>></td>
<?php } ?>
	</tr>
<?php } ?>
	</tbody>
</table>
</div>
</div>
<div class="ewDesktopButton">
<button class="<!--##=sSubmitButtonClass##-->" name="btnsubmit" id="btnsubmit" type="submit"<?php echo $<!--##=sPageObj##-->->Disabled ?>><?php echo $Language->Phrase("Update") ?></button>
<button class="<!--##=sCancelButtonClass##-->" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $<!--##=sPageObj##-->->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</div>
</form>

<script type="text/javascript">
<!--##=sFormName##-->.Init();
</script>

<!--##/session##-->


<?php
<!--##session phpfunction##-->

	// Update privileges
	function EditRow() {
		$c = &Conn(EW_USER_LEVEL_PRIV_DBID);
		for ($i = 0; $i < $this->TableNameCount; $i++) {
			$Sql = "SELECT * FROM " . EW_USER_LEVEL_PRIV_TABLE . " WHERE " . 
				EW_USER_LEVEL_PRIV_TABLE_NAME_FIELD . " = '" . ew_AdjustSql($this->TableList[$i][4] . $this->TableList[$i][0], EW_USER_LEVEL_PRIV_DBID) . "' AND " .
				EW_USER_LEVEL_PRIV_USER_LEVEL_ID_FIELD . " = " . $this-><!--##=sUserLevelIDFldParm##-->->CurrentValue;
			$rs = $c->Execute($Sql);
			if ($rs && !$rs->EOF) {
				$Sql = "UPDATE " . EW_USER_LEVEL_PRIV_TABLE . " SET " . EW_USER_LEVEL_PRIV_PRIV_FIELD . " = " . $this->Privileges[$i] . " WHERE " .
					EW_USER_LEVEL_PRIV_TABLE_NAME_FIELD . " = '" . ew_AdjustSql($this->TableList[$i][4] . $this->TableList[$i][0], EW_USER_LEVEL_PRIV_DBID) . "' AND " .
					EW_USER_LEVEL_PRIV_USER_LEVEL_ID_FIELD . " = " . $this-><!--##=sUserLevelIDFldParm##-->->CurrentValue;
				$c->Execute($Sql);
			} else {
				$Sql = "INSERT INTO " . EW_USER_LEVEL_PRIV_TABLE . " (" . EW_USER_LEVEL_PRIV_TABLE_NAME_FIELD . ", " . EW_USER_LEVEL_PRIV_USER_LEVEL_ID_FIELD . ", " . EW_USER_LEVEL_PRIV_PRIV_FIELD . ") VALUES ('" . ew_AdjustSql($this->TableList[$i][4] . $this->TableList[$i][0], EW_USER_LEVEL_PRIV_DBID) . "', " . $this-><!--##=sUserLevelIDFldParm##-->->CurrentValue . ", " . $this->Privileges[$i] . ")";
				$c->Execute($Sql);
			}
			if ($rs)
				$rs->Close();
		}
		return TRUE;
	}
	
	// Get table caption
	function GetTableCaption($i) {
		global $Language, $EW_RELATED_PROJECT_ID;
		$caption = "";
		if ($i < $this->TableNameCount) {
			$report = ($this->TableList[$i][4] == $EW_RELATED_PROJECT_ID);
			$other = (!$report && $this->TableList[$i][4] <> CurrentProjectID());
			if (!$report && !$other)
				$caption = $Language->TablePhrase($this->TableList[$i][1], "TblCaption");
            if ($report && is_object($this->ReportLanguage))
				$caption = $this->ReportLanguage->TablePhrase($this->TableList[$i][1], "TblCaption");
			if ($caption == "")
				$caption = $this->TableList[$i][2];
			if ($caption == "") {
				$caption = $this->TableList[$i][0];
				$caption = preg_replace('/^\{\w{8}-\w{4}-\w{4}-\w{4}-\w{12}\}/', '', $caption); // Remove project id
			}
			if ($report)
				$caption .= "<span class=\"ewUserprivProject\"> (" . $Language->Phrase("Report") . ")</span>";
			if ($other) {
				if ($this->TableList[$i][5] <> "") {
					$pathinfo = pathinfo($this->TableList[$i][5]);
					$ext = $pathinfo['extension'];
					$project = basename($this->TableList[$i][5], "." . $ext);
				} else {
					$project = $this->TableList[$i][4];
				}
				//$project = $this->TableList[$i][4]; // ** Uncomment to use project id
				$caption .= "<span class=\"ewUserprivProject\"> (" . $project . ")</span>";
			}
		}
		return $caption;
	}

<!--##/session##-->
?>