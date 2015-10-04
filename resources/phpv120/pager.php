<!--##session pager##-->
<!--##
	switch (iPagerStyle) {
		case 1: // Pager Style 1
##-->
<?php if (!isset($<!--##=sPageObj##-->->Pager)) $<!--##=sPageObj##-->->Pager = new cNumericPager($<!--##=sPageObj##-->->StartRec, $<!--##=sPageObj##-->->DisplayRecs, $<!--##=sPageObj##-->->TotalRecs, $<!--##=sPageObj##-->->RecRange) ?>
<?php if ($<!--##=sPageObj##-->->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($<!--##=sPageObj##-->->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $<!--##=sPageObj##-->->PageUrl() ?>start=<?php echo $<!--##=sPageObj##-->->Pager->FirstButton->Start ?>"><!--##@PagerFirst##--></a></li>
	<?php } ?>
	<?php if ($<!--##=sPageObj##-->->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $<!--##=sPageObj##-->->PageUrl() ?>start=<?php echo $<!--##=sPageObj##-->->Pager->PrevButton->Start ?>"><!--##@PagerPrevious##--></a></li>
	<?php } ?>
	<?php foreach ($<!--##=sPageObj##-->->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $<!--##=sPageObj##-->->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($<!--##=sPageObj##-->->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $<!--##=sPageObj##-->->PageUrl() ?>start=<?php echo $<!--##=sPageObj##-->->Pager->NextButton->Start ?>"><!--##@PagerNext##--></a></li>
	<?php } ?>
	<?php if ($<!--##=sPageObj##-->->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $<!--##=sPageObj##-->->PageUrl() ?>start=<?php echo $<!--##=sPageObj##-->->Pager->LastButton->Start ?>"><!--##@PagerLast##--></a></li>
	<?php } ?>
</ul></div>
</div>
	<!--## if (CTRL.CtrlID.toLowerCase() == "list") { ##-->
<div class="ewPager ewRec">
	<span><!--##@Record##-->&nbsp;<?php echo $<!--##=sPageObj##-->->Pager->FromIndex ?>&nbsp;<!--##@To##-->&nbsp;<?php echo $<!--##=sPageObj##-->->Pager->ToIndex ?>&nbsp;<!--##@Of##-->&nbsp;<?php echo $<!--##=sPageObj##-->->Pager->RecordCount ?></span>
</div>
	<!--## } ##-->
<?php } ?>
<!--##
		break;
		case 2: // Pager Style 2
##-->
<?php if (!isset($<!--##=sPageObj##-->->Pager)) $<!--##=sPageObj##-->->Pager = new cPrevNextPager($<!--##=sPageObj##-->->StartRec, $<!--##=sPageObj##-->->DisplayRecs, $<!--##=sPageObj##-->->TotalRecs) ?>
<?php if ($<!--##=sPageObj##-->->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><!--##@Page##-->&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($<!--##=sPageObj##-->->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $<!--##=sPageObj##-->->PageUrl() ?>start=<?php echo $<!--##=sPageObj##-->->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($<!--##=sPageObj##-->->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $<!--##=sPageObj##-->->PageUrl() ?>start=<?php echo $<!--##=sPageObj##-->->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $<!--##=sPageObj##-->->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($<!--##=sPageObj##-->->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $<!--##=sPageObj##-->->PageUrl() ?>start=<?php echo $<!--##=sPageObj##-->->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($<!--##=sPageObj##-->->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $<!--##=sPageObj##-->->PageUrl() ?>start=<?php echo $<!--##=sPageObj##-->->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<!--##@of##-->&nbsp;<?php echo $<!--##=sPageObj##-->->Pager->PageCount ?></span>
</div>
	<!--## if (CTRL.CtrlID.toLowerCase() == "list") { ##-->
<div class="ewPager ewRec">
	<span><!--##@Record##-->&nbsp;<?php echo $<!--##=sPageObj##-->->Pager->FromIndex ?>&nbsp;<!--##@To##-->&nbsp;<?php echo $<!--##=sPageObj##-->->Pager->ToIndex ?>&nbsp;<!--##@Of##-->&nbsp;<?php echo $<!--##=sPageObj##-->->Pager->RecordCount ?></span>
</div>
	<!--## } ##-->
<?php } ?>
<!--##
		break;
	}
##-->
<!--##
	if (ew_IsNotEmpty(sRecPerPageList) && CTRL.CtrlID.toLowerCase() == "list") {
		var arrRecPerPage = sRecPerPageList.split(",");
##-->

<?php if ($<!--##=sPageObj##-->->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="<!--##=gsTblVar##-->">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">

	<!--##
		for (i = 0; i < arrRecPerPage.length; i++) {
			thisDisplayRecs = arrRecPerPage[i];
			if (parseInt(thisDisplayRecs) > 0) {
				thisValue = parseInt(thisDisplayRecs);
	##-->
<option value="<!--##=thisDisplayRecs##-->"<?php if ($<!--##=sPageObj##-->->DisplayRecs == <!--##=thisValue##-->) { ?> selected="selected"<?php } ?>><!--##=thisDisplayRecs##--></option>
	<!--##
			} else {
	##-->
<option value="ALL"<?php if ($<!--##=gsTblVar##-->->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><!--##@AllRecords##--></option>
	<!--##
			}
		}
	##-->
</select>
</div>
<?php } ?>
<!--##
	}
##-->
<!--##/session##-->

<?php
<!--##session pagerfunction##-->

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		
		$this->StartRec = $this->getStartRecordNumber();
		
		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

<!--##/session##-->
?>