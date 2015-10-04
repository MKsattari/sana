<?php
<!--##session sharefunction##-->
<!--##
	if (bHasFileField &&
		(CTRL.CtrlID == "list" && (gbTblListAdd || gbTblListEdit)) ||
		 CTRL.CtrlID == "gridcls" ||
		 CTRL.CtrlID == "add" || CTRL.CtrlID == "addopt" ||
		 CTRL.CtrlID == "edit" ||
		 CTRL.CtrlID == "update" ||
		 CTRL.CtrlID == "register") { // Upload Field Exists
##-->
	
	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data

		<!--##
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				if (goFld.FldHtmlTag == "FILE") { // Upload field
					sUpdateFldVar = "u_" + gsFldParm;
		##-->

		$this-><!--##=gsFldParm##-->->Upload->Index = $objForm->Index;
		$this-><!--##=gsFldParm##-->->Upload->UploadFile();

		<!--##
			if (ew_IsNotEmpty(goFld.FileNameFld)) {
				WRKFLD = TABLE.Fields(goFld.FileNameFld);
				sFileNameFldParm = WRKFLD.FldParm;
		##-->
		$this-><!--##=sFileNameFldParm##-->->CurrentValue = $this-><!--##=gsFldParm##-->->Upload->FileName;
		<!--##
			}
			if (ew_IsNotEmpty(goFld.FileTypeFld)) {
				WRKFLD = TABLE.Fields(goFld.FileTypeFld);
				sFileTypeFldParm = WRKFLD.FldParm;
		##-->
		$this-><!--##=sFileTypeFldParm##-->->CurrentValue = $this-><!--##=gsFldParm##-->->Upload->ContentType;
		<!--##
			}
			if (ew_IsNotEmpty(goFld.FileSizeFld)) {
				WRKFLD = TABLE.Fields(goFld.FileSizeFld);
				sFileSizeFldParm = WRKFLD.FldParm;
		##-->
		$this-><!--##=sFileSizeFldParm##-->->CurrentValue = $this-><!--##=gsFldParm##-->->Upload->FileSize;
		<!--##
			}
			if (ew_IsNotEmpty(goFld.ImageWidthFld) && ew_IsNotEmpty(goFld.ImageHeightFld)) {
				WRKFLD = TABLE.Fields(goFld.ImageWidthFld);
				sImageWidthFldParm = WRKFLD.FldParm;
		##-->
		$this-><!--##=sImageWidthFldParm##-->->CurrentValue = $this-><!--##=gsFldParm##-->->Upload->ImageWidth;
		<!--##
				WRKFLD = TABLE.Fields(goFld.ImageHeightFld);
				sImageHeightFldParm = WRKFLD.FldParm;
		##-->
		$this-><!--##=sImageHeightFldParm##-->->CurrentValue = $this-><!--##=gsFldParm##-->->Upload->ImageHeight;
		<!--##
			}
		##-->

		<!--## if (CTRL.CtrlID == "update") { ##-->
		$this-><!--##=gsFldParm##-->->MultiUpdate = $objForm->GetValue("<!--##=sUpdateFldVar##-->");
		<!--## } ##-->
	
		<!--##
				}
			}
		}
		##-->
	
	}
<!--##
	}
##-->
<!--##
	if ((CTRL.CtrlID == "list" && (gbTblListAdd || gbTblListEdit)) ||
		CTRL.CtrlID == "gridcls" ||
		CTRL.CtrlID == "add" || CTRL.CtrlID == "addopt" ||
		CTRL.CtrlID == "register") {
##-->

	// Load default values
	function LoadDefaultValues() {
		<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
		##-->
		<!--##~SYSTEMFUNCTIONS.ScriptEditDefaultValue()##-->
		<!--##
					if (goFld.FldHtmlTag == "FILE") {
		##-->
	<!--## if (CTRL.CtrlID == "gridcls") { ##-->
		$this-><!--##=gsFldParm##-->->Upload->Index = $this->RowIndex;
	<!--## } ##-->
		<!--##
					}
				}
			}
		##-->
	}
	
<!--##
	}
##-->

<!--##
	if (CTRL.CtrlID == "list" && bBasicSearch) {
##-->
	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}
<!--##
	}
##-->


<!--##
	if ((CTRL.CtrlID == "list" && (bAdvancedSearch || bExtendedBasicSearch)) ||
		CTRL.CtrlID == "search") {
##-->

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;
		// Load search values
	<!--##
		if (CTRL.CtrlID == "list") {
			sGetValue = "@$_GET[";
			sGetValue2 = "]";
		} else {
			sGetValue = "$objForm->GetValue(";
			sGetValue2 = ")";
		}

		for (i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				//if (IsFldExtendedSearch(FIELD) || IsFldAdvancedSearch(FIELD)) {
				if (IsFldExtendedSearch(goFld) || goFld.FldSearch) {

					if (!(goFld.FldHtmlTag == "FILE" && ew_IsBinaryField(goFld))) {
						//sFieldValue = gsFldObj + "->AdvancedSearch->SearchValue";
						//sFieldOpr = gsFldObj + "->AdvancedSearch->SearchOperator";
						//sFldOpr = "z_" + gsFldParm;
	##-->
		// <!--##=gsFldName##-->
		$this-><!--##=gsFldParm##-->->AdvancedSearch->SearchValue = ew_StripSlashes(<!--##=sGetValue##-->"x_<!--##=gsFldParm##-->"<!--##=sGetValue2##-->);
	<!--## if (CTRL.CtrlID == "list") { ##-->
		if ($this-><!--##=gsFldParm##-->->AdvancedSearch->SearchValue <> "") $this->Command = "search";
	<!--## } ##-->
		$this-><!--##=gsFldParm##-->->AdvancedSearch->SearchOperator = <!--##=sGetValue##-->"z_<!--##=gsFldParm##-->"<!--##=sGetValue2##-->;
	<!--##
						//if (ew_GetFieldType(goFld.FldType) == 2) {
							//iFldDtFormat = goFld.FldDtFormat;
							// Skip unformatting datetime
						//}
						
						IsUserSelect = (goFld.FldSrchOpr == "USER SELECT" && ew_GetFieldType(goFld.FldType) != 4);

						if (goFld.FldSrchOpr == "BETWEEN" || IsUserSelect || ew_IsNotEmpty(goFld.FldSrchOpr2)) {
							//gsFldVar2 = "y_" + gsFldParm;
							//sFieldValue = gsFldObj + "->AdvancedSearch->SearchValue2";
	##-->
		$this-><!--##=gsFldParm##-->->AdvancedSearch->SearchCondition = <!--##=sGetValue##-->"v_<!--##=gsFldParm##-->"<!--##=sGetValue2##-->;
		$this-><!--##=gsFldParm##-->->AdvancedSearch->SearchValue2 = ew_StripSlashes(<!--##=sGetValue##-->"y_<!--##=gsFldParm##-->"<!--##=sGetValue2##-->);
	<!--## if (CTRL.CtrlID == "list") { ##-->
		if ($this-><!--##=gsFldParm##-->->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
	<!--## } ##-->
		$this-><!--##=gsFldParm##-->->AdvancedSearch->SearchOperator2 = <!--##=sGetValue##-->"w_<!--##=gsFldParm##-->"<!--##=sGetValue2##-->;
	<!--##
							//if (ew_GetFieldType(goFld.FldType) == 2) {
								//iFldDtFormat = goFld.FldDtFormat;
								// Skip unformatting datetime
							//}
						}

						if ((goFld.FldHtmlTag == "SELECT" && goFld.FldSelectMultiple) ||
							goFld.FldHtmlTag == "CHECKBOX") {
	##-->
		if (is_array($this-><!--##=gsFldParm##-->->AdvancedSearch->SearchValue)) $this-><!--##=gsFldParm##-->->AdvancedSearch->SearchValue = implode(",", $this-><!--##=gsFldParm##-->->AdvancedSearch->SearchValue);
		if (is_array($this-><!--##=gsFldParm##-->->AdvancedSearch->SearchValue2)) $this-><!--##=gsFldParm##-->->AdvancedSearch->SearchValue2 = implode(",", $this-><!--##=gsFldParm##-->->AdvancedSearch->SearchValue2);
	<!--##
						}

					}

				}
			}
		}
	##-->

	}

<!--##
	}
##-->

<!--##
	if ((CTRL.CtrlID == "list" && (gbTblListAdd || gbTblListEdit)) ||
		CTRL.CtrlID == "gridcls" ||
		CTRL.CtrlID == "add" || CTRL.CtrlID == "addopt" ||
		CTRL.CtrlID == "edit" ||
		CTRL.CtrlID == "update" ||
		CTRL.CtrlID == "register") {
##-->
	// Load form values
	function LoadFormValues() {
		// Load from form
		global $objForm;
	
	<!--## if (CTRL.CtrlID == "gridcls") { ##-->
		$objForm->FormName = $this->FormName;
	<!--## } ##-->

	<!--## if (bHasFileField) { ##-->
		$this->GetUploadFiles(); // Get upload files
	<!--## } ##-->

		<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
					if (goFld.FldHtmlTag != "FILE") {

						// Handle autoincrement fields
						if (goFld.FldAutoIncrement) {

							if (CTRL.CtrlID == "add" || CTRL.CtrlID == "addopt" || CTRL.CtrlID == "register") {
								// Skip
							} else if (CTRL.CtrlID == "gridcls" || CTRL.CtrlID == "list") {
		##-->
		if (!$this-><!--##=gsFldParm##-->->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this-><!--##=gsFldParm##-->->setFormValue($objForm->GetValue("<!--##=gsFldVar##-->"));
		<!--##
							} else {
		##-->
		if (!$this-><!--##=gsFldParm##-->->FldIsDetailKey)
			$this-><!--##=gsFldParm##-->->setFormValue($objForm->GetValue("<!--##=gsFldVar##-->"));
		<!--##
							}

						} else {
		##-->
		if (!$this-><!--##=gsFldParm##-->->FldIsDetailKey) {
		<!--##
							if (CTRL.CtrlID == "addopt") {
		##-->
			$this-><!--##=gsFldParm##-->->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("<!--##=gsFldVar##-->")));
		<!--##
							} else {
		##-->
			$this-><!--##=gsFldParm##-->->setFormValue($objForm->GetValue("<!--##=gsFldVar##-->"));
		<!--##
							}
							if (ew_GetFieldType(goFld.FldType) == 2) {
								iFldDtFormat = goFld.FldDtFormat;
		##-->
			$this-><!--##=gsFldParm##-->->CurrentValue = ew_UnFormatDateTime($this-><!--##=gsFldParm##-->->CurrentValue, <!--##=iFldDtFormat##-->);
		<!--##
							}
		##-->
		}
		<!--##
							if (CTRL.CtrlID == "update") {
								sUpdateFldVar = "u_" + gsFldParm;
	##-->
		$this-><!--##=gsFldParm##-->->MultiUpdate = $objForm->GetValue("<!--##=sUpdateFldVar##-->");
	<!--##
							}

							if (CTRL.CtrlID == "register" && goFld.FldName == PROJ.SecPasswdFld) {
								sCPwdFldVar = "c_" + gsFldParm;
	##-->
		$this-><!--##=gsFldParm##-->->ConfirmValue = $objForm->GetValue("<!--##=sCPwdFldVar##-->");
	<!--##
							}
	
							if ((CTRL.CtrlID == "list" && bGridAdd) || CTRL.CtrlID == "gridcls") {
								sOldFldVar = "o_" + gsFldParm;
		##-->
		$this-><!--##=gsFldParm##-->->setOldValue($objForm->GetValue("<!--##=sOldFldVar##-->"));
	<!--##
							}
						}
					}
				}
			}
	##-->

	<!--##
		// Load hidden primary key fields
		for (var i = 0; i < nKeyCount; i++) {
			if (!InArray(arFlds, arKeyFlds[i])) {
				if (GetFldObj(arKeyFlds[i])) {

					// Handle autoincrement fields
					if (goFld.FldAutoIncrement) {

						if (CTRL.CtrlID == "add" || CTRL.CtrlID == "addopt" || CTRL.CtrlID == "register") {
							// Skip
						} else if (CTRL.CtrlID == "gridcls" || CTRL.CtrlID == "list") {
		##-->
		if (!$this-><!--##=gsFldParm##-->->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this-><!--##=gsFldParm##-->->setFormValue($objForm->GetValue("<!--##=gsFldVar##-->"));
		<!--##
						} else {
		##-->
		if (!$this-><!--##=gsFldParm##-->->FldIsDetailKey)
			$this-><!--##=gsFldParm##-->->setFormValue($objForm->GetValue("<!--##=gsFldVar##-->"));
		<!--##
						}

					} else {
						if (CTRL.CtrlID == "addopt") {
	##-->
		if (!$this-><!--##=gsFldParm##-->->FldIsDetailKey)
			$this-><!--##=gsFldParm##-->->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("<!--##=gsFldVar##-->")));
	<!--##
						} else {
	##-->
		if (!$this-><!--##=gsFldParm##-->->FldIsDetailKey)
			$this-><!--##=gsFldParm##-->->setFormValue($objForm->GetValue("<!--##=gsFldVar##-->"));
	<!--##
						}
					}
				}
			}
		}
	##-->

<!--## if (bTblCheckConcurrentUpdate && ((CTRL.CtrlID == "edit") || (CTRL.CtrlID == "list" && gbTblListEdit) || (CTRL.CtrlID == "gridcls"))) { ##-->
		if ($this->CurrentAction <> "overwrite")
			$this->HashValue = $objForm->GetValue("k_hash");
<!--## } ##-->
	}
	
	// Restore form values
	function RestoreFormValues() {
		global $objForm;

	<!--## if (CTRL.CtrlID == "add") { // Load old row to get the file upload fields ##-->
		$this->LoadOldRecord();
	<!--## } else if (CTRL.CtrlID == "edit") { // Load old row to get the file upload fields ##-->
		$this->LoadRow();
	<!--## } ##-->

	<!--##
		// Restore hidden primary key fields
		for (var i = 0; i < nKeyCount; i++) {
			if (!InArray(arFlds, arKeyFlds[i])) {
				if (GetFldObj(arKeyFlds[i])) {

					// Handle autoincrement fields
					if (goFld.FldAutoIncrement) {
						if (CTRL.CtrlID == "add" || CTRL.CtrlID == "addopt" || CTRL.CtrlID == "register") {
							// Skip
						} else if (CTRL.CtrlID == "gridcls" || CTRL.CtrlID == "list") {
		##-->
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this-><!--##=gsFldParm##-->->CurrentValue = $this-><!--##=gsFldParm##-->->FormValue;
		<!--##
						} else {
		##-->
		$this-><!--##=gsFldParm##-->->CurrentValue = $this-><!--##=gsFldParm##-->->FormValue;
		<!--##
						}

					} else {

						if (CTRL.CtrlID == "addopt") {
	##-->
		$this-><!--##=gsFldParm##-->->CurrentValue = ew_ConvertToUtf8($this-><!--##=gsFldParm##-->->FormValue);
	<!--##
						} else {
	##-->
		$this-><!--##=gsFldParm##-->->CurrentValue = $this-><!--##=gsFldParm##-->->FormValue;
	<!--##
						}
					}
				}
			}
		}
	##-->

	<!--##
		for (i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				if (goFld.FldHtmlTag == "FILE") {
				} else if (!SYSTEMFUNCTIONS.IsFileRelatedField(goFld.FldName)) {

					// Handle autoincrement fields
					if (goFld.FldAutoIncrement) {
						if (CTRL.CtrlID == "add" || CTRL.CtrlID == "addopt" || CTRL.CtrlID == "register") {
							// Skip
						} else if (CTRL.CtrlID == "gridcls" || CTRL.CtrlID == "list") {
		##-->
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this-><!--##=gsFldParm##-->->CurrentValue = $this-><!--##=gsFldParm##-->->FormValue;
		<!--##
						} else {
		##-->
		$this-><!--##=gsFldParm##-->->CurrentValue = $this-><!--##=gsFldParm##-->->FormValue;
		<!--##
						}

					} else {

						if (CTRL.CtrlID == "addopt") {
	##-->
		$this-><!--##=gsFldParm##-->->CurrentValue = ew_ConvertToUtf8($this-><!--##=gsFldParm##-->->FormValue);
	<!--##
						} else {
	##-->
		$this-><!--##=gsFldParm##-->->CurrentValue = $this-><!--##=gsFldParm##-->->FormValue;
	<!--##
						}
						if (ew_GetFieldType(goFld.FldType) == 2) {
							iFldDtFormat = goFld.FldDtFormat;
	##-->
		$this-><!--##=gsFldParm##-->->CurrentValue = ew_UnFormatDateTime($this-><!--##=gsFldParm##-->->CurrentValue, <!--##=iFldDtFormat##-->);
	<!--##
						}
					}
				}
			}
		} // Field
	##-->

	<!--## if (bTblCheckConcurrentUpdate && ((CTRL.CtrlID == "edit") || (CTRL.CtrlID == "list" && gbTblListEdit) || (CTRL.CtrlID == "gridcls"))) { ##-->
		if ($this->CurrentAction <> "overwrite")
			$this->HashValue = $objForm->GetValue("k_hash");
	<!--## } ##-->

	<!--## if ((CTRL.CtrlID == "add" && bDetailAdd && nDetailTableCount > 0 && bTblAddConfirm) || (CTRL.CtrlID == "edit" && bDetailEdit && nDetailTableCount > 0 && bTblEditConfirm)) { ##-->
		$this->ResetDetailParms();
	<!--## } ##-->

	}

<!--##
	}
##-->

<!--##
	if ((CTRL.CtrlID == "list") ||
		(CTRL.CtrlID == "gridcls") ||
		(CTRL.CtrlID == "view" && bDetailViewPaging) ||
		(CTRL.CtrlID == "view" && bViewExport && (bExportHtml || bExportEmail || bExportCsv || bExportWord || bExportExcel || bExportXml || bExportPDF)) ||
		(CTRL.CtrlID == "edit" && bDetailEditPaging) ||
		(CTRL.CtrlID == "delete") ||
		(CTRL.CtrlID == "update")) {
##-->

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
	
		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
<!--## if (bTableVirtualLookup) { ##-->
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
<!--## } else { ##-->
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
<!--## } ##-->
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Recordset_Selected")) { ##-->
		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		<!--## } ##-->
	
		return $rs;
	
	}
<!--##
	}
##-->

<!--##
	if (CTRL.CtrlID == "list" ||
		CTRL.CtrlID == "gridcls" ||
		CTRL.CtrlID == "view" ||
		CTRL.CtrlID == "edit" || CTRL.CtrlID == "update" ||
		CTRL.CtrlID == "add" || CTRL.CtrlID == "addopt" || CTRL.CtrlID == "register" ||
		CTRL.CtrlID == "delete") {
##-->
	
	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;

		$sFilter = $this->KeyFilter();

		<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_Selecting")) { ##-->
		// Call Row Selecting event
		$this->Row_Selecting($sFilter);
		<!--## } ##-->
	
		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			
	<!--## if (bTblCheckConcurrentUpdate && ((CTRL.CtrlID == "edit") || (CTRL.CtrlID == "list" && gbTblListEdit) || (CTRL.CtrlID == "gridcls"))) { ##-->
			if (!$this->EventCancelled)
				$this->HashValue = $this->GetRowHash($rs); // Get hash value for record
	<!--## } ##-->
	
			$rs->Close();
		}

	<!--##
		if (bTableHasUserIDFld) {
			if ((CTRL.CtrlID == "add") ||
				(CTRL.CtrlID == "edit")) {
	##-->

		// Check if valid user id
		if ($res) {
			$res = $this->ShowOptionLink('<!--##=CTRL.CtrlID##-->');
			if (!$res) {
				$sUserIdMsg = $Language->Phrase("NoPermission");
				$this->setFailureMessage($sUserIdMsg);
			}
		}

	<!--##
			}
		}
	##-->

		return $res;
	}
	
	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_Selected")) { ##-->
		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
	<!--## } ##-->

	<!--## if (TABLE.TblAuditTrail && CTRL.CtrlID == "view") { ##-->
		if ($this->AuditTrailOnView) $this->WriteAuditTrailOnView($row);
	<!--## } ##-->

	<!--##
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				sFld = "$rs->fields('" + SQuote(gsFldName) + "')";
				if (goFld.FldHtmlTag == "FILE") {
	##-->
		$this-><!--##=gsFldParm##-->->Upload->DbValue = <!--##=sFld##-->;
	<!--## if (!ew_IsBinaryField(goFld)) { ##-->
		$this-><!--##=gsFldParm##-->->CurrentValue = $this-><!--##=gsFldParm##-->->Upload->DbValue;
	<!--## } else { ##-->
		if (is_array($this-><!--##=gsFldParm##-->->Upload->DbValue) || is_object($this-><!--##=gsFldParm##-->->Upload->DbValue)) // Byte array
			$this-><!--##=gsFldParm##-->->Upload->DbValue = ew_BytesToStr($this-><!--##=gsFldParm##-->->Upload->DbValue);
	<!--## } ##-->
	<!--## if (CTRL.CtrlID == "gridcls") { ##-->
		$this-><!--##=gsFldParm##-->->Upload->Index = $this->RowIndex;
	<!--## } ##-->
	<!--##
				} else {
	##-->
		$this-><!--##=gsFldParm##-->->setDbValue(<!--##=GetFldVal(TABLE, sFld, goFld.FldType)##-->);
	<!--##
					if (bDynamicUserLevel && TABLE.TblName == DB.UserLevelTbl && goFld.FldName == DB.UserLevelIdFld) { // User Level field
	##-->
		if (is_null($this-><!--##=gsFldParm##-->->CurrentValue)) {
			$this-><!--##=gsFldParm##-->->CurrentValue = 0;
		} else {
			$this-><!--##=gsFldParm##-->->CurrentValue = intval($this-><!--##=gsFldParm##-->->CurrentValue);
		}
	<!--##
					}
					if (ew_IsFldVirtualLookup(goFld)) {
						sVirtualFldName = ew_VirtualLookupFldName(goFld);
	##-->
		if (array_key_exists('<!--##=sVirtualFldName##-->', $rs->fields)) {
			$this-><!--##=gsFldParm##-->->VirtualValue = $rs->fields('<!--##=sVirtualFldName##-->'); // Set up virtual field value
		} else {
			$this-><!--##=gsFldParm##-->->VirtualValue = ""; // Clear value
		}
	<!--##
					}
				}
			}
		}
	##-->

	<!--##
		if (bDetailShowCount && (CTRL.CtrlID == "list" || CTRL.CtrlID == "view")) {
			for (var i = 0, len = arDetailTables.length; i < len; i++) {
				var MasterDetail = goAllMasDets[arDetailTables[i].index];
				OLDTABLE = TABLE; // Save current table
				MASTERTABLE = TABLE;
				TABLE = DB.Tables(MasterDetail.DetailTable);
				if (nDetailTableCount > 0 && TABLE.TblGen && TABLE.TblType != "REPORT") {
					sDetailPageObj = ew_GetPageObjByCtrlId("grid");
					sDetailTblVar = TABLE.TblVar;
	##-->
		if (!isset($GLOBALS["<!--##=sDetailPageObj##-->"])) $GLOBALS["<!--##=sDetailPageObj##-->"] = new c<!--##=sDetailPageObj##-->;
		$sDetailFilter = $GLOBALS["<!--##=sDetailTblVar##-->"]->SqlDetailFilter_<!--##=gsTblVar##-->();
	<!--##
					ew_SetDb(TABLE.TblName);
					for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
						MASTERFIELD = MASTERTABLE.Fields(MasterDetail.Rels[j].MasterField);
						sMasterFldParm = MASTERFIELD.FldParm;
						DETAILFIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
						sDetailFldParm = DETAILFIELD.FldParm;
	##-->
		$sDetailFilter = str_replace("@<!--##=sDetailFldParm##-->@", ew_AdjustSql($this-><!--##=sMasterFldParm##-->->DbValue, "<!--##=ew_Quote(gsDbId)##-->"), $sDetailFilter);
	<!--##
					} // MasterDetailField
	##-->
		$GLOBALS["<!--##=sDetailTblVar##-->"]->setCurrentMasterTable("<!--##=gsTblVar##-->");
		$sDetailFilter = $GLOBALS["<!--##=sDetailTblVar##-->"]->ApplyUserIDFilters($sDetailFilter);
		$this-><!--##=sDetailTblVar##-->_Count = $GLOBALS["<!--##=sDetailTblVar##-->"]->LoadRecordCount($sDetailFilter);
	<!--##
				}
				TABLE = OLDTABLE; // Restore current table
			} // MasterDetail
			ew_SetDb(TABLE.TblName); // Restore
		}
	##-->

	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
	<!--##
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				sFld = "$row['" + SQuote(gsFldName) + "']";
				if (goFld.FldHtmlTag == "FILE") {
	##-->
		$this-><!--##=gsFldParm##-->->Upload->DbValue = <!--##=sFld##-->;
	<!--##
				} else {
	##-->
		$this-><!--##=gsFldParm##-->->DbValue = <!--##=GetFldVal(TABLE, sFld, goFld.FldType)##-->;
	<!--##
				}
			}
		}
	##-->
	}

<!--##
	}
##-->

<!--##
	if (CTRL.CtrlID == "list" ||
		CTRL.CtrlID == "gridcls" ||
		CTRL.CtrlID == "add") {
##-->
	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;

	<!--## if (CTRL.CtrlID == "gridcls") { ##-->

	<!--## if (nKeyCount > 1) { ##-->
		$arKeys[] = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $this->RowOldKey);
	<!--## } else { ##-->
		$arKeys[] = $this->RowOldKey;
	<!--## } ##-->
		$cnt = count($arKeys);
		if ($cnt >= <!--##=nKeyCount##-->) {
	<!--##
		for (var i = 0; i < nKeyCount; i++) {
			if (GetFldObj(arKeyFlds[i])) {
	##-->
			if (strval($arKeys[<!--##=i##-->]) <> "")
				$this-><!--##=gsFldParm##-->->CurrentValue = strval($arKeys[<!--##=i##-->]); // <!--##=gsFldName##-->
			else
				$bValidKey = FALSE;
	<!--##
			}
		}
	##-->
		} else {
			$bValidKey = FALSE;
		}

	<!--## } else { ##-->

	<!--##
		for (var i = 0; i < nKeyCount; i++) {
			if (GetFldObj(arKeyFlds[i])) {
	##-->
		if (strval($this->getKey("<!--##=gsFldParm##-->")) <> "")
			$this-><!--##=gsFldParm##-->->CurrentValue = $this->getKey("<!--##=gsFldParm##-->"); // <!--##=gsFldName##-->
		else
			$bValidKey = FALSE;
	<!--##
			}
		}
	##-->

	<!--## } ##-->

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;

	}
<!--##
	}
##-->

<!--##
	if (CTRL.CtrlID == "list" ||
		CTRL.CtrlID == "gridcls" ||
		CTRL.CtrlID == "report" ||
		CTRL.CtrlID == "view" ||
		CTRL.CtrlID == "edit" ||
		CTRL.CtrlID == "update" ||
		CTRL.CtrlID == "add" || CTRL.CtrlID == "addopt" ||
		CTRL.CtrlID == "register" ||
		CTRL.CtrlID == "delete" ||
		CTRL.CtrlID == "search") {
##-->

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
	<!--## if (CTRL.CtrlID.toLowerCase() == "view") { ##-->
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();
	<!--## } else if (CTRL.CtrlID.toLowerCase() == "list") { ##-->
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
	<!--## } ##-->

	<!--##
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				if (ew_IsFldFloatFormat(goFld)) { // Check if adSingle/adDouble/adNumeric/adCurrency
	##-->
		// Convert decimal values if posted back
		if ($this-><!--##=gsFldParm##-->->FormValue == $this-><!--##=gsFldParm##-->->CurrentValue && is_numeric(ew_StrToFloat($this-><!--##=gsFldParm##-->->CurrentValue)))
			$this-><!--##=gsFldParm##-->->CurrentValue = ew_StrToFloat($this-><!--##=gsFldParm##-->->CurrentValue);
	<!--##
				}
			}
		}
	##-->

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_Rendering")) { ##-->	
		// Call Row_Rendering event
		$this->Row_Rendering();
	<!--## } ##-->

		// Common render codes for all row types
	<!--##
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
	##-->
		// <!--##=gsFldName##-->
		<!--##~SYSTEMFUNCTIONS.ScriptCommon()##-->
	<!--##
			}
		}
	##-->
		
	<!--##
		if (CTRL.CtrlID == "list" && SYSTEMFUNCTIONS.IsAggregate()) {
	##-->
		// Accumulate aggregate value
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT && $this->RowType <> EW_ROWTYPE_AGGREGATE) {
	<!--##
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (goFld.FldAggregate == "COUNT" || goFld.FldAggregate == "AVERAGE") {
	##-->
			$this-><!--##=gsFldParm##-->->Count++; // Increment count
	<!--##
				}
				if (goFld.FldAggregate == "AVERAGE" || goFld.FldAggregate == "TOTAL") {
	##-->
			if (is_numeric($this-><!--##=gsFldParm##-->->CurrentValue))
				$this-><!--##=gsFldParm##-->->Total += $this-><!--##=gsFldParm##-->->CurrentValue; // Accumulate total
	<!--##
				}
			}
		}
	##-->
		}
	<!--##
		}
	##-->

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

	<!--##
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (InArray(arFlds, arAllFlds[i]) || goFld.FldExport) {
	##-->
		// <!--##=gsFldName##-->
		<!--##~SYSTEMFUNCTIONS.ScriptView()##-->
	<!--##
				}
			}
		}
	##-->

	<!--##
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
	##-->
			// <!--##=gsFldName##-->
			<!--##~SYSTEMFUNCTIONS.ScriptViewRefer()##-->
	<!--##
			}
		}
	##-->

	<!--##
		if (CTRL.CtrlID == "add" || CTRL.CtrlID == "addopt" || CTRL.CtrlID == "register" || (CTRL.CtrlID == "list" && (gbTblListAdd || gbTblListEdit)) || CTRL.CtrlID == "gridcls") {
	##-->

		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

	<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
	##-->
			// <!--##=gsFldName##-->
			<!--##~SYSTEMFUNCTIONS.ScriptAdd()##-->
	<!--##
				}
			}
	##-->

			// Add refer script
	<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
	##-->
			// <!--##=gsFldName##-->
			<!--##~SYSTEMFUNCTIONS.ScriptAddRefer()##-->
	<!--##
				}
			}
	##-->

	<!--##
		}
	##-->
	
	<!--##
		if (CTRL.CtrlID == "edit" || CTRL.CtrlID == "update" || (CTRL.CtrlID == "list" && gbTblListEdit) || CTRL.CtrlID == "gridcls") {
	##-->

		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

	<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
	##-->
			// <!--##=gsFldName##-->
			<!--##~SYSTEMFUNCTIONS.ScriptEdit()##-->
	<!--##
				}
			}
	##-->

			// Edit refer script
	<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
	##-->
			// <!--##=gsFldName##-->
			<!--##~SYSTEMFUNCTIONS.ScriptEditRefer()##-->
	<!--##
				}
			}
	##-->
		
	<!--##
		}
	##-->

	<!--##
		if (CTRL.CtrlID == "search" || (CTRL.CtrlID == "list" && bExtendedBasicSearch)) {
	##-->

		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

	<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
	##-->
			// <!--##=gsFldName##-->
			<!--##~SYSTEMFUNCTIONS.ScriptSearch()##-->
	<!--##
					IsUserSelect = (goFld.FldSrchOpr == "USER SELECT" && ew_GetFieldType(goFld.FldType) != 4);
					if (goFld.FldSrchOpr == "BETWEEN" || IsUserSelect || ew_IsNotEmpty(goFld.FldSrchOpr2)) {
	##-->
			<!--##~SYSTEMFUNCTIONS.ScriptSearch2()##-->
	<!--##
					}
				}
			}
	##-->
		
	<!--##
		}
	##-->

	<!--##
		if (CTRL.CtrlID == "list" && SYSTEMFUNCTIONS.IsAggregate()) {
	##-->

		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATEINIT) { // Initialize aggregate row

	<!--##
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (goFld.FldAggregate == "COUNT" || goFld.FldAggregate == "AVERAGE") {
	##-->
			$this-><!--##=gsFldParm##-->->Count = 0; // Initialize count
	<!--##
				}
				if (goFld.FldAggregate == "AVERAGE" || goFld.FldAggregate == "TOTAL") {
	##-->
			$this-><!--##=gsFldParm##-->->Total = 0; // Initialize total
	<!--##
				}
			}
		}
	##-->

		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATE) { // Aggregate row

	<!--##
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (ew_IsNotEmpty(goFld.FldAggregate)) {
	##-->
			<!--##~SYSTEMFUNCTIONS.ScriptAggregate()##-->
	<!--##
				}
			}
		}
	##-->

	<!--##
		}
	##-->
	
		}

	<!--##
		if (CTRL.CtrlID == "add" || CTRL.CtrlID == "addopt" || CTRL.CtrlID == "register" ||
			CTRL.CtrlID == "edit" || CTRL.CtrlID == "update" ||
			CTRL.CtrlID == "search" ||
			(CTRL.CtrlID == "list" && (gbTblListAdd || gbTblListEdit || bExtendedBasicSearch)) ||
			CTRL.CtrlID == "gridcls") {
	##-->
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}
	<!--##
		}
	##-->

		<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_Rendered")) { ##-->
		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
		<!--## } ##-->
	
	}

<!--##
	}
##-->

<!--##
	if ((CTRL.CtrlID == "list" && (bAdvancedSearch || bExtendedBasicSearch)) ||
		CTRL.CtrlID == "search") {
##-->

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

	<!--##
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (IsValidateSearch(goFld)) {
					if (IsValidateServer(goFld)) { // Skip RegExp And Custom validation if server validate not enabled
	##-->
		<!--##~SYSTEMFUNCTIONS.PhpSearchValidator()##-->
	<!--##
					}
				}
			}
		} // Field
	##-->

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Form_CustomValidate")) { ##-->
		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
	<!--## } ##-->
	
		return $ValidateSearch;

	}

<!--##
	}
##-->

<!--##
	if ((CTRL.CtrlID == "list" && (gbTblListAdd || gbTblListEdit)) ||
		CTRL.CtrlID == "gridcls" ||
		CTRL.CtrlID == "add" || CTRL.CtrlID == "addopt" ||
		CTRL.CtrlID == "edit" ||
		CTRL.CtrlID == "update" ||
		CTRL.CtrlID == "register") {
##-->

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;
		
	<!--## if (CTRL.CtrlID != "gridcls") { ##-->
		// Initialize form error message
		$gsFormError = "";
	<!--## } ##-->

	<!--##
		if (CTRL.CtrlID == "update") {
	##-->
		$lUpdateCnt = 0;
	<!--##
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
	##-->
		if ($this-><!--##=gsFldParm##-->->MultiUpdate == "1") $lUpdateCnt++;
	<!--##
			}
		} // Field
	##-->
		if ($lUpdateCnt == 0) {
			$gsFormError = $Language->Phrase("NoFieldSelected");
			return FALSE;
		}
	<!--##
		}
	##-->

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");

	<!--##
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				// Required Field
				if (IsRequiredField(goFld) && !(SYSTEMFUNCTIONS.IsBoolFld(goFld) && goFld.FldHtmlTag == "CHECKBOX")) {
	##-->
		<!--##~SYSTEMFUNCTIONS.PhpReqValidator()##-->
	<!--##
				}

				// Text validation
				if (IsValidateText(goFld)) {
					if (IsValidateServer(goFld)) { // Skip RegExp And Custom validation if server validate not enabled
	##-->
		<!--##~SYSTEMFUNCTIONS.PhpValidator()##-->
	<!--##
					}
				}
			}
		} // Field
	##-->

	<!--##
		if (((CTRL.CtrlID == "add" && bDetailAdd) || (CTRL.CtrlID == "edit" && bDetailEdit)) && nDetailTableCount > 0) {
			if (CTRL.CtrlID == "add")
				sDetailPrp = "DetailAdd";
			else if (CTRL.CtrlID = "edit")
				sDetailPrp = "DetailEdit";
	##-->
		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
	<!--##
			for (var i = 0, len = arDetailTables.length; i < len; i++) {
				var MasterDetail = goAllMasDets[arDetailTables[i].index];
				OLDTABLE = TABLE; // Save master table
				MASTERTABLE = DB.Tables(TABLE.TblName);
				TABLE = DB.Tables(MasterDetail.DetailTable);
				if (TABLE.TblGen && TABLE.TblType != "REPORT") {
					sDetailTblVar = TABLE.TblVar;
					sDetailPageObj = ew_GetPageObjByCtrlId("grid");
	##-->
		if (in_array("<!--##=sDetailTblVar##-->", $DetailTblVar) && $GLOBALS["<!--##=sDetailTblVar##-->"]-><!--##=sDetailPrp##-->) {
			if (!isset($GLOBALS["<!--##=sDetailPageObj##-->"])) $GLOBALS["<!--##=sDetailPageObj##-->"] = new c<!--##=sDetailPageObj##-->(); // get detail page object
			$GLOBALS["<!--##=sDetailPageObj##-->"]->ValidateGridForm();
		}
	<!--##
				}
				TABLE = OLDTABLE; // Restore master table
			} // MasterDetail
		}
	##-->

		// Return validate result
		$ValidateForm = ($gsFormError == "");

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Form_CustomValidate")) { ##-->
		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
	<!--## } ##-->
	
		return $ValidateForm;

	}

<!--##
	}
##-->

<!--##
	if (CTRL.CtrlID == "delete" ||
		CTRL.CtrlID == "gridcls" ||
		(CTRL.CtrlID == "list" && (bGridAdd || bGridEdit))) {
##-->

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;

		<!--## if (bUserTable) { ##-->
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		<!--## } ##-->

		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();

		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';

		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		//} else {
		//	$this->LoadRowValues($rs); // Load row values
		}
		$rows = ($rs) ? $rs->GetRows() : array();

	<!--##
		if (nDetailTableCount > 0) {
			for (var i = 0, len = arDetailTables.length; i < len; i++) {
				var MasterDetail = goAllMasDets[arDetailTables[i].index];
				if (MasterDetail.EnforceReferentialIntegrity && !MasterDetail.CascadeDelete) { // Enforce referential integrity but not Cascade delete
					OLDTABLE = TABLE; // Save master table
					MASTERTABLE = DB.Tables(TABLE.TblName);
					TABLE = DB.Tables(MasterDetail.DetailTable);
	##-->
		// Check if records exist for detail table '<!--##=TABLE.TblName##-->'
		if (!isset($GLOBALS["<!--##=TABLE.TblVar##-->"])) $GLOBALS["<!--##=TABLE.TblVar##-->"] = new c<!--##=TABLE.TblVar##-->();
	<!--##
					if (TABLE.TblGen && TABLE.TblType != "REPORT") {
						// Get detail key SQL
						var sDetailKeySQL = "";
						ew_SetDb(TABLE.TblName);
						for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
							MASTERFIELD = MASTERTABLE.Fields(MasterDetail.Rels[j].MasterField);
							sMasterFldName = MASTERFIELD.FldName;
							sMasterFldTypeName = GetFieldTypeName(MASTERFIELD.FldType);
							DETAILFIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
							sDetailFld = ew_FieldSqlName(DETAILFIELD);
							if (sDetailKeySQL != "") sDetailKeySQL += " . \" AND \" . ";
							sDetailKeySQL += "\"" + ew_Quote(sDetailFld) + " = \" . ew_QuotedValue($row['" + SQuote(sMasterFldName) + "'], " + sMasterFldTypeName + ", '" + SQuote(gsDbId) + "')";
						} // MasterDetailField
	##-->
		foreach ($rows as $row) {
			$rsdetail = $GLOBALS["<!--##=TABLE.TblVar##-->"]->LoadRs(<!--##=sDetailKeySQL##-->);
			if ($rsdetail && !$rsdetail->EOF) {
				$sRelatedRecordMsg = str_replace("%t", "<!--##=ew_Quote(TABLE.TblName)##-->", $Language->Phrase("RelatedRecordExists"));
				$this->setFailureMessage($sRelatedRecordMsg);
				return FALSE;
			}
		}
	<!--##
					}
					TABLE = OLDTABLE; // Restore master table
				}
			}
			ew_SetDb(TABLE.TblName); // Restore
		}
	##-->

		<!--## if (CTRL.CtrlID == "delete") { ##-->
		$conn->BeginTrans();
		<!--## } ##-->

		<!--## if (bAuditTrailOnDelete) { ##-->
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin
		<!--## } ##-->
	
		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_Deleting")) { ##-->
		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		<!--## } ##-->
	
		if ($DeleteRows) {
	
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
			<!--##
				for (var i = 0; i < nKeyCount; i++) {
					if (GetFldObj(arKeyFlds[i])) {
			##-->
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['<!--##=SQuote(gsFldName)##-->'];
			<!--##
					}
				}
			##-->
		<!--##
			if (PROJ.DeleteUploadedFile) {
		##-->
				$this->LoadDbValues($row);
		<!--##
				for (var i = 0; i < nAllFldCount; i++) {
					if (GetFldObj(arAllFlds[i])) {
						if (goFld.FldHtmlTag == "FILE" && !ew_IsBinaryField(goFld)) {
		##-->
		<!--## if (ew_IsNotEmpty(goFld.FldUploadPath)) { ##-->
				$this-><!--##=gsFldParm##-->->OldUploadPath = <!--##=goFld.FldUploadPath##-->;
		<!--## } ##-->
		<!--## if (!goFld.FldUploadMultiple) { // Not multiple upload ##-->
				@unlink(ew_UploadPathEx(TRUE, $this-><!--##=gsFldParm##-->->OldUploadPath) . $row['<!--##=SQuote(gsFldName)##-->']);
		<!--## } else { ##-->
				$OldFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $row['<!--##=SQuote(gsFldName)##-->']);
				$FileCount = count($OldFiles);
				for ($i = 0; $i < $FileCount; $i++) {
					@unlink(ew_UploadPathEx(TRUE, $this-><!--##=gsFldParm##-->->OldUploadPath) . $OldFiles[$i]);
				}
		<!--## } ##-->
		<!--##
						}
					}
				} // Field
			}
	
			if (bDynamicUserLevel && TABLE.TblName == DB.UserLevelTbl) {
				if (GetFldObj(DB.UserLevelIdFld)) {
		##-->
				$<!--##=gsFldVar##--> = $row['<!--##=SQuote(DB.UserLevelIdFld)##-->']; // Get User Level id
		<!--##
				}
			}
		##-->
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				
				if ($DeleteRows === FALSE)
					break;
				
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
				
		<!--##
			if (bDynamicUserLevel && TABLE.TblName == DB.UserLevelTbl) {
				if (GetFldObj(DB.UserLevelIdFld)) {
		##-->
				if (!is_null($<!--##=gsFldVar##-->)) {
					$conn->Execute("DELETE FROM " . EW_USER_LEVEL_PRIV_TABLE . " WHERE " . EW_USER_LEVEL_PRIV_USER_LEVEL_ID_FIELD . " = " . $<!--##=gsFldVar##-->); // Delete user rights as well
				}
		<!--##
				}
			}
		##-->
			}
	
		} else {
			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {
				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
	
		if ($DeleteRows) {

		<!--## if (CTRL.CtrlID == "delete") { ##-->
			$conn->CommitTrans(); // Commit the changes
		<!--## } ##-->

		<!--## if (bAuditTrailOnDelete) { ##-->
			if ($DeleteRows) {
				foreach ($rsold as $row)
					$this->WriteAuditTrailOnDelete($row);
			}
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		<!--## } ##-->

		<!--## if (CTRL.CtrlID == "delete") { ##-->
		<!--## if (TABLE.TblSendMailOnDelete) { ##-->
			$sTable = '<!--##=SQuote(TABLE.TblName)##-->';
			$sSubject = $sTable . " " . $Language->Phrase("RecordDeleted");
			$sAction = $Language->Phrase("ActionDeleted");
			$Email = new cEmail();
			$Email->Load(EW_EMAIL_NOTIFY_TEMPLATE);
			$Email->ReplaceSender(EW_SENDER_EMAIL); // Replace Sender
			$Email->ReplaceRecipient(EW_RECIPIENT_EMAIL); // Replace Recipient
			$Email->ReplaceSubject($sSubject); // Replace Subject
			$Email->ReplaceContent("<!--table-->", $sTable);
			$Email->ReplaceContent("<!--key-->", $sKey);
			$Email->ReplaceContent("<!--action-->", $sAction);
<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Email_Sending")) { ##-->
			$Args = array();
			$Args["rs"] = &$rsold;
			$bEmailSent = FALSE;
			if ($this->Email_Sending($Email, $Args))
				$bEmailSent = $Email->Send();
<!--## } else { ##-->
			$bEmailSent = $Email->Send();
<!--## } ##-->
			if (!$bEmailSent)
				$this->setFailureMessage($Email->SendErrDescription);
		<!--## } ##-->
		<!--## } ##-->

		} else {
		<!--## if (CTRL.CtrlID == "delete") { ##-->
			$conn->RollbackTrans(); // Rollback changes

		<!--## if (TABLE.TblAuditTrail) { ##-->
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
		<!--## } ##-->
		<!--## } ##-->
		}
	
		<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_Deleted")) { ##-->
		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		<!--## } ##-->
	
		return $DeleteRows;
	}

<!--##
	}
##-->


<!--##
	if ((CTRL.CtrlID == "list" && (bInlineEdit || bGridEdit)) ||
		CTRL.CtrlID == "gridcls" ||
		CTRL.CtrlID == "edit" ||
		CTRL.CtrlID == "update") {
##-->

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;

		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();

		<!--##
			for (var i = 0; i < nAllFldCount; i++) {
				if (GetFldObj(arAllFlds[i])) {
					if ((goFld.FldUniqueIdx || goFld.FldCheckDuplicate) &&
						!(goFld.FldIsPrimaryKey || goFld.FldAutoIncrement || goFld.FldHtmlTag == "FILE")) {
						if (IsFldList(goFld) || IsFldEdit(goFld) || IsFldUpdate(goFld)) {
		##-->
		if ($this-><!--##=gsFldParm##-->->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(<!--##=ew_Quote(gsFld)##--> = <!--##=goFld.FldQuoteS##-->" . ew_AdjustSql($this-><!--##=gsFldParm##-->->CurrentValue, $this->DBID) . "<!--##=goFld.FldQuoteE##-->)";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this-><!--##=gsFldParm##-->->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this-><!--##=gsFldParm##-->->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
		<!--##
						}
					}
				}
			} // Field
		##-->

		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
	
		if ($rs === FALSE)
			return FALSE;
	
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

	<!--## if (CTRL.CtrlID == "edit" && bDetailEdit && nDetailTableCount > 0) { ##-->
			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();
	<!--## } ##-->

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
	<!--##
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (goFld.FldHtmlTag == "FILE" && !ew_IsBinaryField(goFld)) {
	##-->
	<!--## if (ew_IsNotEmpty(goFld.FldUploadPath)) { ##-->
			$this-><!--##=gsFldParm##-->->OldUploadPath = <!--##=goFld.FldUploadPath##-->;
			$this-><!--##=gsFldParm##-->->UploadPath = $this-><!--##=gsFldParm##-->->OldUploadPath;
	<!--## } ##-->
	<!--##
				}
			}
		} // Field
	##-->

			$rsnew = array();
	
		<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
					if (goFld.FldGenerate && !goFld.FldHtmlTagReadOnly && IsFldUpdatable(goFld)) {
		##-->
			// <!--##=gsFldName##-->
		<!--##
						sFldTypeName = goFld.FldTypeName.toUpperCase();
						if (!(bTblDBOracle && (sFldTypeName == "BLOB" || sFldTypeName == "CLOB"))) {
		##-->
			<!--##~SYSTEMFUNCTIONS.ScriptUpdate()##-->
		<!--##
						}
					}
				}
			} // Field
		##-->
		
		<!--## if (bTblCheckConcurrentUpdate && (CTRL.CtrlID == "edit" || (CTRL.CtrlID == "list" && gbTblListEdit) || CTRL.CtrlID == "gridcls")) { ##-->
			// Check hash value
			$bRowHasConflict = ($this->GetRowHash($rs) <> $this->HashValue);
		<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_UpdateConflict")) { ##-->
			// Call Row Update Conflict event
			if ($bRowHasConflict)
				$bRowHasConflict = $this->Row_UpdateConflict($rsold, $rsnew);
		<!--## } ##-->
			if ($bRowHasConflict) {
				$this->setFailureMessage($Language->Phrase("RecordChangedByOtherUser"));
				$this->UpdateConflict = "U";
				$rs->Close();
				return FALSE; // Update Failed
			}
		<!--## } ##-->

	<!--##
		if (nMasterTableCount > 0) {
			for (var i = 0, len = arMasterTables.length; i < len; i++) {
				var MasterDetail = goAllMasDets[arMasterTables[i].index];
				if (MasterDetail.EnforceReferentialIntegrity) { // Enforce referential integrity
					OLDTABLE = TABLE; // Save detail table
					DETAILTABLE = DB.Tables(TABLE.TblName);
					TABLE = DB.Tables(MasterDetail.MasterTable);
					if (TABLE.TblGen && TABLE.TblType != "REPORT") {
						sMasterTblVar = TABLE.TblVar;
	##-->
			// Check referential integrity for master table '<!--##=TABLE.TblName##-->'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_<!--##=sMasterTblVar##-->();
	<!--##
						for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
							MASTERFIELD = TABLE.Fields(MasterDetail.Rels[j].MasterField);
							sMasterFldParm = MASTERFIELD.FldParm;
							DETAILFIELD = DETAILTABLE.Fields(MasterDetail.Rels[j].DetailField);
							sDetailFldName = DETAILFIELD.FldName;
							sDetailFldParm = DETAILFIELD.FldParm;
	##-->
			$KeyValue = isset($rsnew['<!--##=SQuote(sDetailFldName)##-->']) ? $rsnew['<!--##=SQuote(sDetailFldName)##-->'] : $rsold['<!--##=SQuote(sDetailFldName)##-->'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@<!--##=sMasterFldParm##-->@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
	<!--##
						} // MasterDetailField
	##-->
			if ($bValidMasterRecord) {
				$rsmaster = $GLOBALS["<!--##=sMasterTblVar##-->"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "<!--##=ew_Quote(TABLE.TblName)##-->", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}
	<!--##
					}
					TABLE = OLDTABLE; // Restore detail table
				}
			}
		}
	##-->

		<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
					if (goFld.FldHtmlTag == "FILE" && !goFld.FldHtmlTagReadOnly) {
		##-->
			<!--##~SYSTEMFUNCTIONS.ScriptUpdateFileData()##-->
		<!--##
					}
				}
			} // Field
		##-->

		<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_Updating")) { ##-->
			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
		<!--## } else { ##-->
			$bUpdateRow = TRUE;
		<!--## } ##-->
	
			if ($bUpdateRow) {
	
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';

				if ($EditRow) {
		<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
					if (goFld.FldHtmlTag == "FILE" && !goFld.FldHtmlTagReadOnly) {
		##-->
					<!--##~SYSTEMFUNCTIONS.ScriptUpdateFile()##-->
		<!--##
					}
				}
			} // Field
		##-->
				}

		<!--##
			if (bTblDBOracle) {
		##-->
				// Update Oracle BLOB/CLOB fields
		<!--##
				for (var i = 0; i < nFldCount; i++) {
					if (GetFldObj(arFlds[i])) {
						sFldTypeName = goFld.FldTypeName.toUpperCase();
						if (sFldTypeName == "BLOB") {
		##-->
				if (!$this-><!--##=gsFldParm##-->->Upload->KeepFile)
					$conn->UpdateBlob($this->getSqlFrom(), $this-><!--##=gsFldParm##-->->FldExpression, $this-><!--##=gsFldParm##-->->Upload->Value, $this->KeyFilter(), $this-><!--##=gsFldParm##-->->FldBlobType);
		<!--##
						} else if (sFldTypeName == "CLOB") {
		##-->
				if (!$this-><!--##=gsFldParm##-->->Upload->KeepFile)
					$conn->UpdateBlob($this->getSqlFrom(), $this-><!--##=gsFldParm##-->->FldExpression, $this-><!--##=gsFldParm##-->->CurrentValue, $this->KeyFilter(), $this-><!--##=gsFldParm##-->->FldBlobType);
		<!--##
						}
					}
				} // Field
			}
		##-->

		<!--##
			if (CTRL.CtrlID == "edit" && bDetailEdit && nDetailTableCount > 0) {
		##-->
				// Update detail records
				if ($EditRow) {
					$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		<!--##
				for (var i = 0, len = arDetailTables.length; i < len; i++) {
					var MasterDetail = goAllMasDets[arDetailTables[i].index];
					OLDTABLE = TABLE; // Save master table
					MASTERTABLE = DB.Tables(TABLE.TblName);
					TABLE = DB.Tables(MasterDetail.DetailTable);
					if (TABLE.TblGen && TABLE.TblType != "REPORT") {
						sDetailTblVar = TABLE.TblVar;
						sDetailPageObj = ew_GetPageObjByCtrlId("grid");
		##-->
					if (in_array("<!--##=sDetailTblVar##-->", $DetailTblVar) && $GLOBALS["<!--##=sDetailTblVar##-->"]->DetailEdit) {
						if (!isset($GLOBALS["<!--##=sDetailPageObj##-->"])) $GLOBALS["<!--##=sDetailPageObj##-->"] = new c<!--##=sDetailPageObj##-->(); // Get detail page object
						$EditRow = $GLOBALS["<!--##=sDetailPageObj##-->"]->GridUpdate();
					}
		<!--##
					}
					TABLE = OLDTABLE; // Restore master table
				} // MasterDetail
		##-->
				}
		<!--##
			}
		##-->

		<!--## if (CTRL.CtrlID == "edit" && bDetailEdit && nDetailTableCount > 0) { ##-->
				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
				}
		<!--## } ##-->

			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {
					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}
	

		<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_Updated")) { ##-->
		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		<!--## } ##-->
	
		<!--## if (TABLE.TblAuditTrail || TABLE.TblSendMailOnEdit) { ##-->
		if ($EditRow) {
		<!--## if (bAuditTrailOnEdit) { ##-->
			$this->WriteAuditTrailOnEdit($rsold, $rsnew);
		<!--## } ##-->
		<!--## if (TABLE.TblSendMailOnEdit && ((CTRL.CtrlID == "list" && (bInlineEdit || bGridEdit)) || CTRL.CtrlID == "edit" || CTRL.CtrlID == "update")) { ##-->
			if ($this->SendEmail)
				$this->SendEmailOnEdit($rsold, $rsnew);
		<!--## } ##-->
		}
		<!--## } ##-->
	
		$rs->Close();

		<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
					if (goFld.FldHtmlTag == "FILE" && !goFld.FldHtmlTagReadOnly) {
		##-->
		// <!--##=gsFldName##-->
		ew_CleanUploadTempPath($this-><!--##=gsFldParm##-->, $this-><!--##=gsFldParm##-->->Upload->Index);
		<!--##
					}
				}
			} // Field
		##-->
	
		return $EditRow;
	
	}

<!--##
	}
##-->

<!--##
	if (bTblCheckConcurrentUpdate && (CTRL.CtrlID == "edit" || (CTRL.CtrlID == "list" && gbTblListEdit) || CTRL.CtrlID == "gridcls")) {
##-->

	// Load row hash
	function LoadRowHash() {

		$sFilter = $this->KeyFilter();

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$RsRow = $conn->Execute($sSql);
		$this->HashValue = ($RsRow && !$RsRow->EOF) ? $this->GetRowHash($RsRow) : ""; // Get hash value for record
		$RsRow->Close();
	}

	// Get Row Hash
	function GetRowHash(&$rs) {
		if (!$rs)
			return "";
		$sHash = "";
	<!--##
		for (var i = 0; i < nFldCount; i++) {
			if (GetFldObj(arFlds[i])) {
				if (goFld.FldGenerate && !goFld.FldHtmlTagReadOnly && IsFldUpdatable(goFld)) {
	##-->
		$sHash .= ew_GetFldHash($rs->fields('<!--##=SQuote(gsFldName)##-->')); // <!--##=gsFldName##-->
	<!--##
				}
			}
		} // Field
	##-->
		return md5($sHash);
	}
<!--##
	}
##-->

<!--##
	if ((CTRL.CtrlID == "list" && (gbTblListAdd || gbTblListEdit)) ||
		CTRL.CtrlID == "gridcls" ||
		CTRL.CtrlID == "add" || CTRL.CtrlID == "addopt" ||
		CTRL.CtrlID == "register") {
##-->

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;

	<!--##
		if (bDynamicUserLevel && TABLE.TblName == DB.UserLevelTbl) {
			if (GetFldObj(DB.UserLevelIdFld)) {
				sUserLevelIDFldVar = gsFldVar;
				sUserLevelIDFldParm = gsFldParm;
				sUserLevelIDFldCV = "this->" + gsFldParm + "->CurrentValue";
			}
			if (GetFldObj(DB.UserLevelNameFld)) {
				sUserLevelNameFldVar = gsFldVar;
				sUserLevelNameFldParm = gsFldParm;
				sUserLevelNameFldCV = "this->" + gsFldParm + "->CurrentValue";
			}
	##-->
		if (trim(strval($<!--##=sUserLevelIDFldCV##-->)) == "") {
			$this->setFailureMessage($Language->Phrase("MissingUserLevelID"));
		} elseif (trim($<!--##=sUserLevelNameFldCV##-->) == "") {
			$this->setFailureMessage($Language->Phrase("MissingUserLevelName"));
		} elseif (!is_numeric($<!--##=sUserLevelIDFldCV##-->)) {
			$this->setFailureMessage($Language->Phrase("UserLevelIDInteger"));
		} elseif (intval($<!--##=sUserLevelIDFldCV##-->) < -2) {
			$this->setFailureMessage($Language->Phrase("UserLevelIDIncorrect"));
		} elseif (intval($<!--##=sUserLevelIDFldCV##-->) == 0 && !ew_SameText($<!--##=sUserLevelNameFldCV##-->, "Default")) {
			$this->setFailureMessage($Language->Phrase("UserLevelDefaultName"));
		} elseif (intval($<!--##=sUserLevelIDFldCV##-->) == -1 && !ew_SameText($<!--##=sUserLevelNameFldCV##-->, "Administrator")) {
			$this->setFailureMessage($Language->Phrase("UserLevelAdministratorName"));
		} elseif (intval($<!--##=sUserLevelIDFldCV##-->) == -2 && !ew_SameText($<!--##=sUserLevelNameFldCV##-->, "Anonymous")) {
			$this->setFailureMessage($Language->Phrase("UserLevelAnonymousName"));
		} elseif (intval($<!--##=sUserLevelIDFldCV##-->) > 0 && in_array(strtolower(trim($<!--##=sUserLevelNameFldCV##-->)), array("anonymous", "administrator", "default"))) {
			$this->setFailureMessage($Language->Phrase("UserLevelNameIncorrect"));
		}
	
		if ($this->getFailureMessage() <> "")
			return FALSE;

	<!--##
		}
	##-->
	
	<!--##
		if (bTableHasUserIDFld) {
			if (InArray(arFlds, TABLE.TblUserIDFld)) {
				if (GetFldObj(TABLE.TblUserIDFld)) {
					if (ew_IsEmpty(goFld.FldAutoUpdateValue)) {
	##-->
		// Check if valid User ID
		$bValidUser = FALSE;
		if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
			$bValidUser = $Security->IsValidUserID($this-><!--##=gsFldParm##-->->CurrentValue);
			if (!$bValidUser) {
				$sUserIdMsg = str_replace("%c", CurrentUserID(), $Language->Phrase("UnAuthorizedUserID"));
				$sUserIdMsg = str_replace("%u", $this-><!--##=gsFldParm##-->->CurrentValue, $sUserIdMsg);
				$this->setFailureMessage($sUserIdMsg);
				return FALSE;
			}
		}
	<!--##
					}
				}
			}
		}
	##-->

	<!--##
		if (bParentUserID && PROJ.SecTbl == TABLE.TblName) {
			if (InArray(arFlds, DB.SecuParentUserIDFld)) {
				if (GetFldObj(DB.SecuParentUserIDFld)) {
					if (ew_IsEmpty(goFld.FldAutoUpdateValue)) {
	##-->
		// Check if valid parent user id
		$bValidParentUser = FALSE;
		if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
			$bValidParentUser = $Security->IsValidUserID($this-><!--##=gsFldParm##-->->CurrentValue);
			if (!$bValidParentUser) {
				$sParentUserIdMsg = str_replace("%c", CurrentUserID(), $Language->Phrase("UnAuthorizedParentUserID"));
				$sParentUserIdMsg = str_replace("%p", $this-><!--##=gsFldParm##-->->CurrentValue, $sParentUserIdMsg);
				$this->setFailureMessage($sParentUserIdMsg);
				return FALSE;
			}
		}
	<!--##
					}
				}
			}
		}
	##-->
	
	<!--##
		if (bMasterTableHasUserIDFld) {
	##-->
		// Check if valid key values for master user
		if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
	<!--##
			if (nMasterTableCount > 0) {
	##-->
	<!--##
				for (var i = 0, len = arMasterTables.length; i < len; i++) {
					var MasterDetail = goAllMasDets[arMasterTables[i].index];
					OLDTABLE = TABLE; // Save detail table
					DETAILTABLE = DB.Tables(TABLE.TblName);
					TABLE = DB.Tables(MasterDetail.MasterTable);
					if (TABLE.TblGen) {
						sMasterTblVar = TABLE.TblVar;
						sMasterUserIDFldName = TABLE.TblUserIDFld;
	##-->
			$sMasterFilter = $this->SqlMasterFilter_<!--##=sMasterTblVar##-->();
	<!--##
						ew_SetDb(TABLE.TblName);
						for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
							MASTERFIELD = TABLE.Fields(MasterDetail.Rels[j].MasterField);
							sMasterFldParm = MASTERFIELD.FldParm;
							DETAILFIELD = DETAILTABLE.Fields(MasterDetail.Rels[j].DetailField);
							sDetailFldParm = DETAILFIELD.FldParm;
	##-->
			if (strval($this-><!--##=sDetailFldParm##-->->CurrentValue) <> "") {
				$sMasterFilter = str_replace("@<!--##=sMasterFldParm##-->@", ew_AdjustSql($this-><!--##=sDetailFldParm##-->->CurrentValue, "<!--##=ew_Quote(gsDbId)##-->"), $sMasterFilter);
			} else {
				$sMasterFilter = "";
			}
	<!--##
						} // MasterDetailField
	##-->
			if ($sMasterFilter <> "") {
				$rsmaster = $GLOBALS["<!--##=sMasterTblVar##-->"]->LoadRs($sMasterFilter);
				$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
				$bValidMasterKey = TRUE;
				if ($this->MasterRecordExists) {
					$bValidMasterKey = $Security->IsValidUserID($rsmaster->fields['<!--##=SQuote(sMasterUserIDFldName)##-->']);
				} elseif ($this->getCurrentMasterTable() == "<!--##=sMasterTblVar##-->") {
					$bValidMasterKey = FALSE;
				}
				if (!$bValidMasterKey) {
					$sMasterUserIdMsg = str_replace("%c", CurrentUserID(), $Language->Phrase("UnAuthorizedMasterUserID"));
					$sMasterUserIdMsg = str_replace("%f", $sMasterFilter, $sMasterUserIdMsg);
					$this->setFailureMessage($sMasterUserIdMsg);
					return FALSE;
				}
				if ($rsmaster) $rsmaster->Close();
			}
	<!--##
					}
					TABLE = OLDTABLE; // Restore detail table
				} // MasterDetail
				ew_SetDb(TABLE.TblName);
			}
	##-->
		}
	<!--##
		}
	##-->

	<!--## if (CTRL.CtrlID == "gridcls") { ##-->
		// Set up foreign key field value from Session
	<!--##
		for (var i = 0, len = arMasterTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arMasterTables[i].index];
			MASTERTABLE = DB.Tables(MasterDetail.MasterTable);
			if (MASTERTABLE.TblGen) {
				sMasterTblVar = MASTERTABLE.TblVar;
	##-->
			if ($this->getCurrentMasterTable() == "<!--##=sMasterTblVar##-->") {
	<!--##
				for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
					DETAILFIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
					sDetailFldParm = DETAILFIELD.FldParm;
	##-->
				$this-><!--##=sDetailFldParm##-->->CurrentValue = $this-><!--##=sDetailFldParm##-->->getSessionValue();
	<!--##
				} // MasterDetailField
	##-->
			}
	<!--##
			}
		} // MasterDetail
	##-->
	<!--## } ##-->

	<!--##
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if ((goFld.FldUniqueIdx || goFld.FldCheckDuplicate) &&
					!(goFld.FldAutoIncrement || goFld.FldHtmlTag == "FILE") &&
					((nKeyCount > 1 && !goFld.FldIsPrimaryKey) || (nKeyCount <= 1))) {
					if (IsFldList(goFld) || IsFldAdd(goFld) || IsFldAddOpt(goFld) || IsFldRegister(goFld)) {
	##-->
		if ($this-><!--##=gsFldParm##-->->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(<!--##=ew_Quote(gsFldName)##--> = <!--##=gsFldQuoteS##-->" . ew_AdjustSql($this-><!--##=gsFldParm##-->->CurrentValue, $this->DBID) . "<!--##=gsFldQuoteE##-->)";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this-><!--##=gsFldParm##-->->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this-><!--##=gsFldParm##-->->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
	<!--##
					}
				}
			}
		} // Field
	##-->

	<!--##
		if (nMasterTableCount > 0) {
			for (var i = 0, len = arMasterTables.length; i < len; i++) {
				var MasterDetail = goAllMasDets[arMasterTables[i].index];
				if (MasterDetail.EnforceReferentialIntegrity) { // Enforce referential integrity
					OLDTABLE = TABLE; // Save detail table
					DETAILTABLE = DB.Tables(TABLE.TblName);
					TABLE = DB.Tables(MasterDetail.MasterTable);
					if (TABLE.TblGen && TABLE.TblType != "REPORT") {
						sMasterTblVar = TABLE.TblVar;
	##-->
		// Check referential integrity for master table '<!--##=TABLE.TblName##-->'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_<!--##=sMasterTblVar##-->();
	<!--##
						ew_SetDb(TABLE.TblName);
						for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
							MASTERFIELD = TABLE.Fields(MasterDetail.Rels[j].MasterField);
							sMasterFldParm = MASTERFIELD.FldParm;
							DETAILFIELD = DETAILTABLE.Fields(MasterDetail.Rels[j].DetailField);
							sDetailFldName = DETAILFIELD.FldName;
							sDetailFldParm = DETAILFIELD.FldParm;
	##-->
	<!--##
		if (!InArray(arFlds, sDetailFldName)) {
	##-->
		if ($this-><!--##=sDetailFldParm##-->->getSessionValue() <> "") {
			$sMasterFilter = str_replace("@<!--##=sMasterFldParm##-->@", ew_AdjustSql($this-><!--##=sDetailFldParm##-->->getSessionValue(), "<!--##=ew_Quote(gsDbId)##-->"), $sMasterFilter);
	<!--##
		} else {
	##-->
		if (strval($this-><!--##=sDetailFldParm##-->->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@<!--##=sMasterFldParm##-->@", ew_AdjustSql($this-><!--##=sDetailFldParm##-->->CurrentValue, "<!--##=ew_Quote(gsDbId)##-->"), $sMasterFilter);
	<!--##
		}
	##-->
		} else {
			$bValidMasterRecord = FALSE;
		}
	<!--##
						} // MasterDetailField
	##-->
		if ($bValidMasterRecord) {
			$rsmaster = $GLOBALS["<!--##=sMasterTblVar##-->"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "<!--##=ew_Quote(TABLE.TblName)##-->", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}
	<!--##
					}
					TABLE = OLDTABLE; // Restore detail table
				}
			}
			ew_SetDb(TABLE.TblName); // Restore
		}
	##-->

		$conn = &$this->Connection();
	<!--## if (CTRL.CtrlID == "add" && bDetailAdd && nDetailTableCount > 0) { ##-->
		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();
	<!--## } ##-->

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
	<!--##
		for (var i = 0; i < nAllFldCount; i++) {
			if (GetFldObj(arAllFlds[i])) {
				if (goFld.FldHtmlTag == "FILE" && !ew_IsBinaryField(goFld)) {
	##-->
	<!--## if (ew_IsNotEmpty(goFld.FldUploadPath)) { ##-->
			$this-><!--##=gsFldParm##-->->OldUploadPath = <!--##=goFld.FldUploadPath##-->;
			$this-><!--##=gsFldParm##-->->UploadPath = $this-><!--##=gsFldParm##-->->OldUploadPath;
	<!--## } ##-->
	<!--##
				}
			}
		} // Field
	##-->
		}

		$rsnew = array();

	<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
					if (IsFldUpdatable(goFld)) {
	##-->
		// <!--##=gsFldName##-->
	<!--##
						// User Level field in register page
						if (CTRL.CtrlID == "register" && goFld.FldName == DB.SecUserLevelFld) {
							if (ew_IsNotEmpty(goFld.FldDefault) && !isNaN(goFld.FldDefault)) {
								iUserLevel = goFld.FldDefault;
							} else {
								iUserLevel = 0;
							}
	##-->
		$rsnew['<!--##=SQuote(goFld.FldName)##-->'] = <!--##=iUserLevel##-->; // Set default User Level
	<!--##
						// Normal field
						} else {
							sFldTypeName = goFld.FldTypeName.toUpperCase();
							if (!(bTblDBOracle && (sFldTypeName == "BLOB" || sFldTypeName == "CLOB"))) {
	##-->
		<!--##~SYSTEMFUNCTIONS.ScriptInsert()##-->
	<!--##
							}
						}
					}
				}
			} // Field
	##-->

	<!--##
		// Generate detail key / parent user id / user id field update if not selected
		for (i = 0; i < nAllFldCount; i++) {
			if (!InArray(arFlds, arAllFlds[i])) {
				if (GetFldObj(arAllFlds[i])) {
					if (ew_IsDetailKeyFld(TABLE, goFld) ||
						(bParentUserID && PROJ.SecTbl == TABLE.TblName && goFld.FldName == DB.SecuParentUserIDFld) ||
						(bTableHasUserIDFld && goFld.FldName == TABLE.TblUserIDFld)) {
	##-->
		// <!--##=gsFldName##-->
		<!--##~SYSTEMFUNCTIONS.ScriptUpdateSpecial()##-->
	<!--##
					}
				}
			}
		} // Field
	##-->

	<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
					if (goFld.FldHtmlTag == "FILE") {
	##-->
		<!--##~SYSTEMFUNCTIONS.ScriptUpdateFileData()##-->
	<!--##
					}
				}
			} // Field
	##-->

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_Inserting")) { ##-->
		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
	<!--## } else { ##-->
		$bInsertRow = TRUE;
	<!--## } ##-->

	<!--##
		// Get number of non-autoincrement key fields
		nKey = 0;
		nAutoIncKey = 0;
		for (var i = 0; i < nKeyCount; i++) {
			if (GetFldObj(arKeyFlds[i])) {
				if (!goFld.FldAutoIncrement) {
	##-->
		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['<!--##=SQuote(goFld.FldName)##-->']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}
	<!--##
					nKey += 1;
				} else {
					nAutoIncKey += 1;
				}
			}
		} // Field

		if (nAutoIncKey == 0 && nKey > 0) {
	##-->
		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
	<!--##
		}
	##-->

		if ($bInsertRow) {

			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';

			if ($AddRow) {

	<!--##
			for (var i = 0; i < nAllFldCount; i++) {
				if (GetFldObj(arAllFlds[i])) {
					if (goFld.FldAutoIncrement) {
	##-->
				// Get insert id if necessary
	<!--##
						var sFldDbDefault = goFld.FldDbDefault;
						if (bTblDBPostgreSql && /^nextval\(/i.test(sFldDbDefault)) {
							sCurrval = sFldDbDefault.replace(/nextval\(/gi, "currval(");
						} else if (bTblDBOracle && /\.NEXTVAL$/i.test(sFldDbDefault)) {
							sCurrval = sFldDbDefault.replace(/\.NEXTVAL/gi, ".CURRVAL");
						}
	##-->
		<!--## if (bTblDBPostgreSql && /^nextval\(/i.test(sFldDbDefault)) { ##-->
				$this-><!--##=gsFldParm##-->->setDbValue($conn->GetOne("SELECT <!--##=ew_Quote(sCurrval)##-->"));
		<!--## } else if (bTblDBOracle && /\.NEXTVAL$/i.test(sFldDbDefault)) { ##-->
				$this-><!--##=gsFldParm##-->->setDbValue($conn->GetOne("SELECT <!--##=ew_Quote(sCurrval)##--> FROM dual"));
		<!--## } else { ##-->
				$this-><!--##=gsFldParm##-->->setDbValue($conn->Insert_ID());
		<!--## } ##-->
				$rsnew['<!--##=SQuote(goFld.FldName)##-->'] = $this-><!--##=gsFldParm##-->->DbValue;
	<!--##
					}
				}
			} // Field
	##-->

	<!--##
			if (bTblDBOracle) {
	##-->
			// Update Oracle BLOB fields
	<!--##
				for (var i = 0; i < nFldCount; i++) {
					if (GetFldObj(arFlds[i])) {
						sFldTypeName = goFld.FldTypeName.toUpperCase();
						if (sFldTypeName == "BLOB") {
	##-->
				$conn->UpdateBlob($this->getSqlFrom(), $this-><!--##=gsFldParm##-->->FldExpression, $this-><!--##=gsFldParm##-->->Upload->Value, $this->KeyFilter(), $this-><!--##=gsFldParm##-->->FldBlobType);
	<!--##
						} else if (sFldTypeName == "CLOB") {
	##-->
				$conn->UpdateBlob($this->getSqlFrom(), $this-><!--##=gsFldParm##-->->FldExpression, $this-><!--##=gsFldParm##-->->CurrentValue, $this->KeyFilter(), $this-><!--##=gsFldParm##-->->FldBlobType);
	<!--##
						}
					}
				} // Field
			}
	##-->

	<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
					if (goFld.FldHtmlTag == "FILE") {
	##-->
				<!--##~SYSTEMFUNCTIONS.ScriptUpdateFile()##-->
	<!--##
					}
				}
			} // Field
	##-->

			}

		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {
				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

	<!--##
			if (CTRL.CtrlID == "add" && bDetailAdd && nDetailTableCount > 0) {
	##-->
		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
	<!--##
				for (var i = 0, len = arDetailTables.length; i < len; i++) {
					var MasterDetail = goAllMasDets[arDetailTables[i].index];
					OLDTABLE = TABLE; // Save master table
					MASTERTABLE = DB.Tables(TABLE.TblName);
					TABLE = DB.Tables(MasterDetail.DetailTable);
					if (TABLE.TblGen && TABLE.TblType != "REPORT") {
						sDetailTblVar = TABLE.TblVar;
						sDetailPageObj = ew_GetPageObjByCtrlId("grid");
	##-->
			if (in_array("<!--##=sDetailTblVar##-->", $DetailTblVar) && $GLOBALS["<!--##=sDetailTblVar##-->"]->DetailAdd) {
	<!--##
						for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
							MASTERFIELD = MASTERTABLE.Fields(MasterDetail.Rels[j].MasterField);
							sMasterFldParm = MASTERFIELD.FldParm;
							DETAILFIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
							sDetailFldParm = DETAILFIELD.FldParm;
	##-->
				$GLOBALS["<!--##=sDetailTblVar##-->"]-><!--##=sDetailFldParm##-->->setSessionValue($this-><!--##=sMasterFldParm##-->->CurrentValue); // Set master key
	<!--##
						} // MasterDetailField
	##-->
				if (!isset($GLOBALS["<!--##=sDetailPageObj##-->"])) $GLOBALS["<!--##=sDetailPageObj##-->"] = new c<!--##=sDetailPageObj##-->(); // Get detail page object
				$AddRow = $GLOBALS["<!--##=sDetailPageObj##-->"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["<!--##=sDetailTblVar##-->"]-><!--##=sDetailFldParm##-->->setSessionValue(""); // Clear master key if insert failed
			}
	<!--##
					}
					TABLE = OLDTABLE; // Restore master table
				} // MasterDetail
	##-->
		}
	<!--##
			}
	##-->

	<!--## if (CTRL.CtrlID == "add" && bDetailAdd && nDetailTableCount > 0) { ##-->
		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
	<!--## } ##-->

		if ($AddRow) {

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Row_Inserted")) { ##-->
			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
	<!--## } ##-->

	<!--## if (bAuditTrailOnAdd) { ##-->
			$this->WriteAuditTrailOnAdd($rsnew);
	<!--## } ##-->

	<!--## if (TABLE.TblSendMailOnAdd && (CTRL.CtrlID == "list" && (bInlineAdd || bInlineCopy || bGridAdd) || CTRL.CtrlID == "add" || CTRL.CtrlID == "register")) { ##-->
			if ($this->SendEmail)
				$this->SendEmailOnAdd($rsnew);
	<!--## } ##-->

	<!--## if (CTRL.CtrlID == "register" && SYSTEMFUNCTIONS.ServerScriptExist("Other","User_Registered")) { ##-->
			// Call User Registered event
			$this->User_Registered($rsnew);
	<!--## } ##-->

		}

	<!--##
			for (var i = 0; i < nFldCount; i++) {
				if (GetFldObj(arFlds[i])) {
					if (goFld.FldHtmlTag == "FILE") {
	##-->
		// <!--##=gsFldName##-->
		ew_CleanUploadTempPath($this-><!--##=gsFldParm##-->, $this-><!--##=gsFldParm##-->->Upload->Index);
	<!--##
					}
				}
			} // Field
	##-->

	<!--##
			if (bDynamicUserLevel && TABLE.TblName == DB.UserLevelTbl) {
				if (GetFldObj(DB.UserLevelIdFld)) {
	##-->
		// Add User Level priv
		if ($this->Priv > 0) {
			$UserLevelList = array();
			$UserLevelPrivList = array();
			$TableList = array();
			$GLOBALS["Security"]->LoadUserLevelFromConfigFile($UserLevelList, $UserLevelPrivList, $TableList, TRUE);
			$TableNameCount = count($TableList);
			for ($i = 0; $i < $TableNameCount; $i++) {
				$sSql = "INSERT INTO " . EW_USER_LEVEL_PRIV_TABLE . " (" .
					EW_USER_LEVEL_PRIV_TABLE_NAME_FIELD . ", " .
					EW_USER_LEVEL_PRIV_USER_LEVEL_ID_FIELD . ", " .
					EW_USER_LEVEL_PRIV_PRIV_FIELD . ") VALUES ('" .
					ew_AdjustSql($TableList[$i][4] . $TableList[$i][0], EW_USER_LEVEL_PRIV_DBID) .
					"', " . $this-><!--##=gsFldParm##-->->CurrentValue . ", " . $this->Priv . ")";
				$conn->Execute($sSql);
			}
		}
	<!--##
				}
			}
	##-->
		return $AddRow;
	}

<!--##
	}
##-->

<!--##
	if ((CTRL.CtrlID == "list" && (bExtendedBasicSearch || bAdvancedSearch)) ||
		(CTRL.CtrlID == "search")) {
##-->

	// Load advanced search
	function LoadAdvancedSearch() {
	<!--##
			for (var i = 0; i < nAllFldCount; i++) {
				if (GetFldObj(arAllFlds[i])) {
					//if (IsFldExtendedSearch(goFld) || IsFldAdvancedSearch(goFld)) {
					if (IsFldExtendedSearch(goFld) || goFld.FldSearch) {
						if (!(goFld.FldHtmlTag == "FILE" && ew_IsBinaryField(goFld))) {
							//sFldOpr = "z_" + gsFldParm;
							//gsFldVar2 = "y_" + gsFldParm;
							//sFldOpr2 = "w_" + gsFldParm;
							//sFldCond = "v_" + gsFldParm;
							//sFldSrchOpr = goFld.FldSrchOpr;
							//sFldSrchOpr2 = goFld.FldSrchOpr2;
							//IsUserSelect = (goFld.FldSrchOpr == "USER SELECT" && ew_GetFieldType(goFld.FldType) != 4);
	##-->
		$this-><!--##=gsFldParm##-->->AdvancedSearch->Load();
	<!--##
						}
					}
				}
			} // Field
	##-->
	}

<!--##
	}
##-->

<!--##
	if (bExportSelectedOnly && CTRL.CtrlID == "list") {
##-->

	// Build export filter for selected records
	function BuildExportSelectedFilter() {
		global $Language;
		$sWrkFilter = "";
		if ($this->Export <> "") {
			$sWrkFilter = $this->GetKeyFilter();
		}
		return $sWrkFilter;
	}
<!--##
	}
##-->

<!--##
	if (((CTRL.CtrlID == "list" || CTRL.CtrlID == "report") && bListExport) ||
		(CTRL.CtrlID == "view" && bViewExport)) {
##-->

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = <!--##=sExportPrintUrl##--> . <!--##=sPrinterFriendlyCaption##--> . "<!--##=sExportEndTag##-->";
		$item->Visible = <!--##=ew_Val(bPrinterFriendly)##-->;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
	<!--## if (bUseCustomTemplate) { ##-->
		if ($this->ExportExcelCustom)
			$item->Body = <!--##=sCustomExportExcelUrl##--> . <!--##=sExportToExcelCaption##--> . "<!--##=sExportEndTag##-->";
		else
			$item->Body = <!--##=sExportExcelUrl##--> . <!--##=sExportToExcelCaption##--> . "<!--##=sExportEndTag##-->";
	<!--## } else { ##-->
		$item->Body = <!--##=sExportExcelUrl##--> . <!--##=sExportToExcelCaption##--> . "<!--##=sExportEndTag##-->";
	<!--## } ##-->
		$item->Visible = <!--##=ew_Val(bExportExcel)##-->;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
	<!--## if (bUseCustomTemplate) { ##-->
		if ($this->ExportWordCustom)
			$item->Body = <!--##=sCustomExportWordUrl##--> . <!--##=sExportToWordCaption##--> . "<!--##=sExportEndTag##-->";
		else
			$item->Body = <!--##=sExportWordUrl##--> . <!--##=sExportToWordCaption##--> . "<!--##=sExportEndTag##-->";
	<!--## } else { ##-->
		$item->Body = <!--##=sExportWordUrl##--> . <!--##=sExportToWordCaption##--> . "<!--##=sExportEndTag##-->";
	<!--## } ##-->
		$item->Visible = <!--##=ew_Val(bExportWord)##-->;

<!--## if (CTRL.CtrlID == "list" || CTRL.CtrlID == "view") { ##-->

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = <!--##=sExportHtmlUrl##--> . <!--##=sExportToHtmlCaption##--> . "<!--##=sExportEndTag##-->";
		$item->Visible = <!--##=ew_Val(bExportHtml)##-->;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = <!--##=sExportXmlUrl##--> . <!--##=sExportToXmlCaption##--> . "<!--##=sExportEndTag##-->";
		$item->Visible = <!--##=ew_Val(bExportXml)##-->;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = <!--##=sExportCsvUrl##--> . <!--##=sExportToCsvCaption##--> . "<!--##=sExportEndTag##-->";
		$item->Visible = <!--##=ew_Val(bExportCsv)##-->;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
	<!--## if (bUseCustomTemplate) { ##-->
		if ($this->ExportPdfCustom)
			$item->Body = <!--##=sCustomExportPdfUrl##--> . <!--##=sExportToPdfCaption##--> . "<!--##=sExportEndTag##-->";
		else
			$item->Body = <!--##=sExportPdfUrl##--> . <!--##=sExportToPdfCaption##--> . "<!--##=sExportEndTag##-->";
	<!--## } else { ##-->
		$item->Body = <!--##=sExportPdfUrl##--> . <!--##=sExportToPdfCaption##--> . "<!--##=sExportEndTag##-->";
	<!--## } ##-->
		$item->Visible = <!--##=ew_Val(bExportPDF)##-->;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
	<!--## if (bUseCustomTemplate) { ##-->
		$url = $this->ExportEmailCustom ? ",url:'" . $this->PageUrl() . "export=email&amp;custom=1'" : "";
	<!--## } else { ##-->
		$url = "";
	<!--## } ##-->
	<!--## if (CTRL.CtrlID == "list") { ##-->
		$item->Body = "<button id=\"emf_<!--##=gsTblVar##-->\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_<!--##=gsTblVar##-->',hdr:ewLanguage.Phrase('ExportToEmailText'),f:<!--##=sJsFormName##-->,sel:<!--##=ew_JsVal(bExportSelectedOnly)##-->" . $url . "});\">" . <!--##=sExportToEmailCaption##--> . "</button>";
	<!--## } else if (CTRL.CtrlID == "view") { ##-->
		$item->Body = "<button id=\"emf_<!--##=gsTblVar##-->\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_<!--##=gsTblVar##-->',hdr:ewLanguage.Phrase('ExportToEmailText'),f:<!--##=sJsFormName##-->,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . <!--##=sExportToEmailCaption##--> . "</button>";
	<!--## } ##-->
		$item->Visible = <!--##=ew_Val(bExportEmail)##-->;

<!--## } ##-->

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = <!--##=ew_Val(bUseDropDownForExport)##-->;
<!--## if (PROJ.GetV("UseDropdownForMobile")) { ##-->
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
<!--## } ##-->
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

	<!--## if (CTRL.CtrlID == "view" || CTRL.CtrlID == "report") { ##-->
		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	<!--## } ##-->

	}

<!--##
	}
##-->

<!--##
	if (bExportHtml || bExportEmail || bExportCsv || bExportWord || bExportExcel || bExportXml || bExportPDF) {
		if ((CTRL.CtrlID == "list" && bListExport) || (CTRL.CtrlID == "view" && bViewExport)) {
			
			if (CTRL.CtrlID == "view") {
				sExportStyle = "v"; // Vertical
				sExportPageType = "view";
			} else {
				sExportStyle = "h"; // Horizontal
				sExportPageType = "";
			}
##-->

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");

		<!--## if (CTRL.CtrlID == "list") { ##-->
		$bSelectLimit = $this->UseSelectLimit;
		<!--## } else { ##-->
		$bSelectLimit = FALSE;
		<!--## } ##-->
		
		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}

		$this->StartRec = 1;

		<!--## if (CTRL.CtrlID == "list") { ##-->

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position
			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);

		<!--## } else { ##-->

		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}

		<!--## } ##-->

		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}

		$this->ExportDoc = ew_ExportDocument($this, "<!--##=sExportStyle##-->");
		$Doc = &$this->ExportDoc;

		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {
			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;
		}

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist(sCtrlType,"Page_Exporting")) { ##-->
		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
	<!--## } ##-->

		$ParentTable = "";
	<!--##
			if (CTRL.CtrlID == "list" && nMasterTableCount > 0) {
				for (var i = 0, len = arMasterTables.length; i < len; i++) {
					var MasterDetail = goAllMasDets[arMasterTables[i].index];
					OLDTABLE = TABLE; // Save detail table
					DETAILTABLE = DB.Tables(TABLE.TblName);
					TABLE = DB.Tables(MasterDetail.MasterTable);
					if (TABLE.TblGen) {
						sMasterTblVar = TABLE.TblVar;
	##-->
		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "<!--##=sMasterTblVar##-->") {
			global $<!--##=sMasterTblVar##-->;
			if (!isset($<!--##=sMasterTblVar##-->)) $<!--##=sMasterTblVar##--> = new c<!--##=sMasterTblVar##-->;
			$rsmaster = $<!--##=sMasterTblVar##-->->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
		<!--## if (bShowVerticalMasterRecord) { ##-->
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
		<!--## } ##-->
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$<!--##=sMasterTblVar##-->;
					$<!--##=sMasterTblVar##-->->ExportDocument($Doc, $rsmaster, 1, 1);
					$Doc->ExportEmptyRow();
					$Doc->Table = &$this;
				}
		<!--## if (bShowVerticalMasterRecord) { ##-->
				$Doc->SetStyle($ExportStyle); // Restore
		<!--## } ##-->
				$rsmaster->Close();
			}
		}
	<!--##
					}
					TABLE = OLDTABLE; // Restore detail table
				} // MasterDetail
			}
	##-->

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist(sCtrlType,"Page_DataRendering")) { ##-->
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
	<!--## } ##-->

		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "<!--##=sExportPageType##-->");

	<!--##
			if (CTRL.CtrlID == "view" && nDetailTableCount > 0) {
				for (var i = 0, len = arDetailTables.length; i < len; i++) {
					var MasterDetail = goAllMasDets[arDetailTables[i].index];
					OLDTABLE = TABLE; // Save table
					TABLE = DB.Tables(MasterDetail.DetailTable);
					if (TABLE.TblGen) {
						sDetailTblVar = TABLE.TblVar;
	##-->
		// Export detail records (<!--##=sDetailTblVar##-->)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("<!--##=sDetailTblVar##-->", explode(",", $this->getCurrentDetailTable()))) {
			global $<!--##=sDetailTblVar##-->;
			if (!isset($<!--##=sDetailTblVar##-->)) $<!--##=sDetailTblVar##--> = new c<!--##=sDetailTblVar##-->;
			$rsdetail = $<!--##=sDetailTblVar##-->->LoadRs($<!--##=sDetailTblVar##-->->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$<!--##=sDetailTblVar##-->->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}
	<!--##
					}
					TABLE = OLDTABLE; // Restore table
				} // MasterDetail
			}
	##-->

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist(sCtrlType,"Page_DataRendered")) { ##-->
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;
	<!--## } ##-->

		// Close recordset
		$rs->Close();

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist(sCtrlType,"Page_Exported")) { ##-->
		// Call Page Exported server event
		$this->Page_Exported();
	<!--## } ##-->
	
		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();
		
		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();
	
		// Output data
	<!--## if (bExportEmail) { ##-->
		if ($this->Export == "email") {
			echo $this->ExportEmail($Doc->Text);
		} else {
			$Doc->Export();
		}
	<!--## } else { ##-->
		$Doc->Export();
	<!--## } ##-->

	}

<!--##
		}
	}
##-->

<!--##
	if (bExportEmail) {
		if ((CTRL.CtrlID == "list" && bListExport) ||
			(CTRL.CtrlID == "view" && bViewExport)) {
##-->

	// Export email
	function ExportEmail($EmailContent) {
		global $gTmpImages, $Language;
		$sSender = @$_POST["sender"];
		$sRecipient = @$_POST["recipient"];
		$sCc = @$_POST["cc"];
		$sBcc = @$_POST["bcc"];
		$sContentType = @$_POST["contenttype"];
		
		// Subject
		$sSubject = ew_StripSlashes(@$_POST["subject"]);
		$sEmailSubject = $sSubject;
		
		// Message
		$sContent = ew_StripSlashes(@$_POST["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterSenderEmail") . "</p>";
		}
		if (!ew_CheckEmail($sSender)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperSenderEmail") . "</p>";
		}

		// Check recipient
		if (!ew_CheckEmailList($sRecipient, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperRecipientEmail") . "</p>";
		}

		// Check cc
		if (!ew_CheckEmailList($sCc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperCcEmail") . "</p>";
		}

		// Check bcc
		if (!ew_CheckEmailList($sBcc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperBccEmail") . "</p>";
		}

		// Check email sent count
		if (!isset($_SESSION[EW_EXPORT_EMAIL_COUNTER]))
			$_SESSION[EW_EXPORT_EMAIL_COUNTER] = 0;
		if (intval($_SESSION[EW_EXPORT_EMAIL_COUNTER]) > EW_MAX_EMAIL_SENT_COUNT) {
			return "<p class=\"text-danger\">" . $Language->Phrase("ExceedMaxEmailExport") . "</p>";
		}

		// Send email
		$Email = new cEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		if ($sEmailMessage <> "") {
			$sEmailMessage = ew_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		if ($sContentType == "url") {
			$sUrl = ew_ConvertFullUrl(ew_CurrentPage() . "?" . $this->ExportQueryString());
			$sEmailMessage .= $sUrl; // Send URL only
		} else {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
			$sEmailMessage .= ew_CleanEmailContent($EmailContent); // Send HTML
		}
		$Email->Content = $sEmailMessage; // Content

	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Email_Sending")) { ##-->
		$EventArgs = array();
		if ($this->Recordset) {
			$this->RecCnt = $this->StartRec - 1;
			$this->Recordset->MoveFirst();
			if ($this->StartRec > 1)
				$this->Recordset->Move($this->StartRec - 1);
			$EventArgs["rs"] = &$this->Recordset;
		}
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();
	<!--## } else { ##-->
		$bEmailSent = $Email->Send();
	<!--## } ##-->

		// Check email sent status
		if ($bEmailSent) {
			// Update email sent count
			$_SESSION[EW_EXPORT_EMAIL_COUNTER]++;

			// Sent email success
			return "<p class=\"text-success\">" . $Language->Phrase("SendEmailSuccess") . "</p>"; // Set up success message

		} else {
			// Sent email failure
			return "<p class=\"text-danger\">" . $Email->SendErrDescription . "</p>";
		}

	}

	// Export QueryString
	function ExportQueryString() {

		// Initialize
		$sQry = "export=html";

	<!--## if (CTRL.CtrlID == "list") { ##-->

		<!--## if (bExportSelectedOnly) { ##-->

		if (isset($_GET["key_m"])) {
			$nKeys = count($_GET["key_m"]);
			foreach ($_GET["key_m"] as $key)
				$sQry .= "&key_m[]=" . $key;
		}

		<!--## } else { ##-->

		// Build QueryString for search
		<!--## if (bBasicSearch) { ##-->
		if ($this->BasicSearch->getKeyword() <> "") {
			$sQry .= "&" . EW_TABLE_BASIC_SEARCH . "=" . urlencode($this->BasicSearch->getKeyword()) . "&" . EW_TABLE_BASIC_SEARCH_TYPE . "=" . urlencode($this->BasicSearch->getType());
		}
		<!--## } ##-->
		<!--## if (bExtendedBasicSearch || bAdvancedSearch) { ##-->
		<!--##
			for (var i = 0; i < nAllFldCount; i++) {
				if (GetFldObj(arAllFlds[i])) {
					if ((goFld.FldSearch || goFld.FldExtendedBasicSearch) &&
						goFld.FldHtmlTag != "FILE") {
		##-->
		$this->AddSearchQueryString($sQry, $this-><!--##=gsFldParm##-->); // <!--##=gsFldName##-->
		<!--##
					}
				}
			} // Field
		##-->
		<!--## } ##-->

		// Build QueryString for pager
		$sQry .= "&" . EW_TABLE_REC_PER_PAGE . "=" . urlencode($this->getRecordsPerPage()) . "&" . EW_TABLE_START_REC . "=" . urlencode($this->getStartRecordNumber());

		<!--## } ##-->

	<!--## } else { ##-->

		// Add record key QueryString
		$sQry .= "&" . substr($this->KeyUrl("", ""), 1);

	<!--## } ##-->

		return $sQry;

	}

	<!--## if (CTRL.CtrlID == "list") { ##-->

	// Add search QueryString
	function AddSearchQueryString(&$Qry, &$Fld) {
		$FldSearchValue = $Fld->AdvancedSearch->getValue("x");
		$FldParm = substr($Fld->FldVar,2);
		if (strval($FldSearchValue) <> "") {
			$Qry .= "&x_" . $FldParm . "=" . urlencode($FldSearchValue) .
				"&z_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("z"));
		}
		$FldSearchValue2 = $Fld->AdvancedSearch->getValue("y");
		if (strval($FldSearchValue2) <> "") {
			$Qry .= "&v_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("v")) .
				"&y_" . $FldParm . "=" . urlencode($FldSearchValue2) .
				"&w_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("w"));
		}
	}

	<!--## } ##-->

<!--##
		}
	} // ExportEmail
##-->

<!--##
	if (bTableHasUserIDFld) {
		if ((CTRL.CtrlID == "list") ||
			(CTRL.CtrlID == "gridcls") ||
			(CTRL.CtrlID == "view") ||
			(CTRL.CtrlID == "add") ||
			(CTRL.CtrlID == "edit") || (CTRL.CtrlID == "update") ||
			(CTRL.CtrlID == "delete")) {
##-->

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this-><!--##=sTableUserIDFldParm##-->->CurrentValue);
		return TRUE;
	}

<!--##
		}
	}
##-->

<!--##
	if (nMasterTableCount > 0) {
		if (((CTRL.CtrlID == "list" || CTRL.CtrlID == "gridcls" || CTRL.CtrlID == "add" || CTRL.CtrlID == "edit" || CTRL.CtrlID == "view" || CTRL.CtrlID == "delete") && TABLE.TblType != "REPORT") ||
			(CTRL.CtrlID == "report" && TABLE.TblType == "REPORT")) {
##-->
	
	// Set up master/detail based on QueryString
	function SetUpMasterParms() {

	<!--## if (CTRL.CtrlID == "gridcls") { ##-->

		// Hide foreign keys
		$sMasterTblVar = $this->getCurrentMasterTable();
	<!--##
		// Build master/detail information
		for (var i = 0, len = arMasterTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arMasterTables[i].index];
			MASTERTABLE = DB.Tables(MasterDetail.MasterTable);
			if (MASTERTABLE.TblGen) {
				sMasterTblVar = MASTERTABLE.TblVar;
	##-->
		if ($sMasterTblVar == "<!--##=sMasterTblVar##-->") {
	<!--##
				for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
					DETAILFIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
					sDetailFldParm = DETAILFIELD.FldParm;
	##-->
			$this-><!--##=sDetailFldParm##-->->Visible = FALSE;
			if ($GLOBALS["<!--##=sMasterTblVar##-->"]->EventCancelled) $this->EventCancelled = TRUE;
	<!--##
				} // MasterDetailField
	##-->
		}
	<!--##
			}
		} // MasterDetail
	##-->

	<!--## } else { ##-->

		$bValidMaster = FALSE;
		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
		<!--##
			// Build master/detail information
			for (var i = 0, len = arMasterTables.length; i < len; i++) {
				var MasterDetail = goAllMasDets[arMasterTables[i].index];
				MASTERTABLE = DB.Tables(MasterDetail.MasterTable);
				if (MASTERTABLE.TblGen) {
					sMasterTblVar = MASTERTABLE.TblVar;
		##-->
			if ($sMasterTblVar == "<!--##=sMasterTblVar##-->") {
				$bValidMaster = TRUE;
		<!--##
					for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
						MASTERFIELD = MASTERTABLE.Fields(MasterDetail.Rels[j].MasterField);
						sMasterFldParm = MASTERFIELD.FldParm;
						DETAILFIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
						sDetailFldParm = DETAILFIELD.FldParm;
		##-->
				if (@$_GET["fk_<!--##=sMasterFldParm##-->"] <> "") {
					$GLOBALS["<!--##=sMasterTblVar##-->"]-><!--##=sMasterFldParm##-->->setQueryStringValue($_GET["fk_<!--##=sMasterFldParm##-->"]);
					$this-><!--##=sDetailFldParm##-->->setQueryStringValue($GLOBALS["<!--##=sMasterTblVar##-->"]-><!--##=sMasterFldParm##-->->QueryStringValue);
					$this-><!--##=sDetailFldParm##-->->setSessionValue($this-><!--##=sDetailFldParm##-->->QueryStringValue);
				<!--## if (ew_GetFieldType(MASTERFIELD.FldType) == 1) { ##-->
					if (!is_numeric($GLOBALS["<!--##=sMasterTblVar##-->"]-><!--##=sMasterFldParm##-->->QueryStringValue)) $bValidMaster = FALSE;
				<!--## } ##-->
				} else {
					$bValidMaster = FALSE;
				}
		<!--##
					} // MasterDetailField
		##-->
			}
		<!--##
				}
			} // MasterDetail
		##-->

		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
		<!--##
			// Build master/detail information
			for (var i = 0, len = arMasterTables.length; i < len; i++) {
				var MasterDetail = goAllMasDets[arMasterTables[i].index];
				MASTERTABLE = DB.Tables(MasterDetail.MasterTable);
				if (MASTERTABLE.TblGen) {
					sMasterTblVar = MASTERTABLE.TblVar;
		##-->
			if ($sMasterTblVar == "<!--##=sMasterTblVar##-->") {
				$bValidMaster = TRUE;
		<!--##
					for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
						MASTERFIELD = MASTERTABLE.Fields(MasterDetail.Rels[j].MasterField);
						sMasterFldParm = MASTERFIELD.FldParm;
						DETAILFIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
						sDetailFldParm = DETAILFIELD.FldParm;
		##-->
				if (@$_POST["fk_<!--##=sMasterFldParm##-->"] <> "") {
					$GLOBALS["<!--##=sMasterTblVar##-->"]-><!--##=sMasterFldParm##-->->setFormValue($_POST["fk_<!--##=sMasterFldParm##-->"]);
					$this-><!--##=sDetailFldParm##-->->setFormValue($GLOBALS["<!--##=sMasterTblVar##-->"]-><!--##=sMasterFldParm##-->->FormValue);
					$this-><!--##=sDetailFldParm##-->->setSessionValue($this-><!--##=sDetailFldParm##-->->FormValue);
				<!--## if (ew_GetFieldType(MASTERFIELD.FldType) == 1) { ##-->
					if (!is_numeric($GLOBALS["<!--##=sMasterTblVar##-->"]-><!--##=sMasterFldParm##-->->FormValue)) $bValidMaster = FALSE;
				<!--## } ##-->
				} else {
					$bValidMaster = FALSE;
				}
		<!--##
					} // MasterDetailField
		##-->
			}
		<!--##
				}
			} // MasterDetail
		##-->

		}

		if ($bValidMaster) {

		<!--## if (CTRL.CtrlID == "list") { ##-->
			// Update URL
			$this->AddUrl = $this->AddMasterUrl($this->AddUrl);
			$this->InlineAddUrl = $this->AddMasterUrl($this->InlineAddUrl);
			$this->GridAddUrl = $this->AddMasterUrl($this->GridAddUrl);
			$this->GridEditUrl = $this->AddMasterUrl($this->GridEditUrl);
		<!--## } ##-->

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);
		<!--## if (CTRL.CtrlID == "view" || CTRL.CtrlID == "edit") { ##-->
			$this->setSessionWhere($this->GetDetailFilter());
		<!--## } ##-->
		<!--##
			if (TABLE.TblType != "REPORT") {
		##-->
			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		<!--##
			}
		##-->
	
			// Clear previous master key from Session
		<!--##
			for (var i = 0, len = arMasterTables.length; i < len; i++) {
				var MasterDetail = goAllMasDets[arMasterTables[i].index];
				MASTERTABLE = DB.Tables(MasterDetail.MasterTable);
				if (MASTERTABLE.TblGen) {
					sMasterTblVar = MASTERTABLE.TblVar;
		##-->
			if ($sMasterTblVar <> "<!--##=sMasterTblVar##-->") {
		<!--##
					for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
						MASTERFIELD = MASTERTABLE.Fields(MasterDetail.Rels[j].MasterField);
						sMasterFldParm = MASTERFIELD.FldParm;
						DETAILFIELD = TABLE.Fields(MasterDetail.Rels[j].DetailField);
						sDetailFldParm = DETAILFIELD.FldParm;
		##-->
				if ($this-><!--##=sDetailFldParm##-->->CurrentValue == "") $this-><!--##=sDetailFldParm##-->->setSessionValue("");
		<!--##
					} // MasterDetailField
		##-->
			}
		<!--##
				}
			} // MasterDetail
		##-->
	
		}

	<!--## } ##-->

		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter

	}
<!--##
		}
	}
##-->

<!--##
	if (nDetailTableCount > 0) {
		if (((CTRL.CtrlID == "view" && bDetailView) ||
			(CTRL.CtrlID == "add" && bDetailAdd) ||
			(CTRL.CtrlID == "edit" && bDetailEdit)) &&
			TABLE.TblType != "REPORT") {
			if (CTRL.CtrlID == "view")
				sDetailPrp = "DetailView";
			else if (CTRL.CtrlID == "edit")
				sDetailPrp = "DetailEdit";
			else if (CTRL.CtrlID == "add")
				sDetailPrp = "DetailAdd";
##-->

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {
		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
		<!--##
			// Build master/detail information
			for (var i = 0, len = arDetailTables.length; i < len; i++) {
				var MasterDetail = goAllMasDets[arDetailTables[i].index];
				DETAILTABLE = DB.Tables(MasterDetail.DetailTable);
				if (DETAILTABLE.TblGen && DETAILTABLE.TblType != "REPORT") {
					sDetailTblVar = DETAILTABLE.TblVar;
					OLDTABLE = TABLE; // Save table
					TABLE = DETAILTABLE;
					sDetailPageObj = ew_GetPageObjByCtrlId("grid");
					TABLE = OLDTABLE; // Restore table
		##-->
			if (in_array("<!--##=sDetailTblVar##-->", $DetailTblVar)) {
				if (!isset($GLOBALS["<!--##=sDetailPageObj##-->"]))
					$GLOBALS["<!--##=sDetailPageObj##-->"] = new c<!--##=sDetailPageObj##-->;
				if ($GLOBALS["<!--##=sDetailPageObj##-->"]-><!--##=sDetailPrp##-->) {
		<!--## if (CTRL.CtrlID == "add") { ##-->
					if ($this->CopyRecord)
						$GLOBALS["<!--##=sDetailPageObj##-->"]->CurrentMode = "copy";
					else
						$GLOBALS["<!--##=sDetailPageObj##-->"]->CurrentMode = "add";
			<!--## if (bTblAddConfirm) { ##-->
					if ($this->CurrentAction == "F")
						$GLOBALS["<!--##=sDetailPageObj##-->"]->CurrentAction = "F";
					else
						$GLOBALS["<!--##=sDetailPageObj##-->"]->CurrentAction = "gridadd";
					if ($this->CurrentAction == "X")
						$GLOBALS["<!--##=sDetailPageObj##-->"]->EventCancelled = TRUE;
			<!--## } else { ##-->
					$GLOBALS["<!--##=sDetailPageObj##-->"]->CurrentAction = "gridadd";
			<!--## } ##-->
		<!--## } else if (CTRL.CtrlID == "edit") { ##-->
					$GLOBALS["<!--##=sDetailPageObj##-->"]->CurrentMode = "edit";
			<!--## if (bTblEditConfirm) { ##-->
					if ($this->CurrentAction == "F")
						$GLOBALS["<!--##=sDetailPageObj##-->"]->CurrentAction = "F";
					else
						$GLOBALS["<!--##=sDetailPageObj##-->"]->CurrentAction = "gridedit";
					if ($this->CurrentAction == "X")
						$GLOBALS["<!--##=sDetailPageObj##-->"]->EventCancelled = TRUE;
			<!--## } else { ##-->
					$GLOBALS["<!--##=sDetailPageObj##-->"]->CurrentAction = "gridedit";
			<!--## } ##-->
		<!--## } else { ##-->
					$GLOBALS["<!--##=sDetailPageObj##-->"]->CurrentMode = "<!--##=CTRL.CtrlID##-->";
		<!--## } ##-->
					// Save current master table to detail table
					$GLOBALS["<!--##=sDetailPageObj##-->"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["<!--##=sDetailPageObj##-->"]->setStartRecordNumber(1);
		<!--##
					for (var j = 0, cnt = MasterDetail.Rels.length; j < cnt; j++) {
						MASTERFIELD = TABLE.Fields(MasterDetail.Rels[j].MasterField);
						sMasterFldParm = MASTERFIELD.FldParm;
						DETAILFIELD = DETAILTABLE.Fields(MasterDetail.Rels[j].DetailField);
						sDetailFldParm = DETAILFIELD.FldParm;
		##-->
					$GLOBALS["<!--##=sDetailPageObj##-->"]-><!--##=sDetailFldParm##-->->FldIsDetailKey = TRUE;
					$GLOBALS["<!--##=sDetailPageObj##-->"]-><!--##=sDetailFldParm##-->->CurrentValue = $this-><!--##=sMasterFldParm##-->->CurrentValue;
					$GLOBALS["<!--##=sDetailPageObj##-->"]-><!--##=sDetailFldParm##-->->setSessionValue($GLOBALS["<!--##=sDetailPageObj##-->"]-><!--##=sDetailFldParm##-->->CurrentValue);
		<!--##
					} // MasterDetailField
		##-->
				}
			}
		<!--##
				}
			} // MasterDetail
		##-->
		}
	}

	<!--## if ((CTRL.CtrlID == "add" && bTblAddConfirm) || (CTRL.CtrlID == "edit" && bTblEditConfirm)) { ##-->

	// Reset detail parms
	function ResetDetailParms() {
		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
		<!--##
			// Build master/detail information
			for (var i = 0, len = arDetailTables.length; i < len; i++) {
				var MasterDetail = goAllMasDets[arDetailTables[i].index];
				DETAILTABLE = DB.Tables(MasterDetail.DetailTable);
				if (DETAILTABLE.TblGen && DETAILTABLE.TblType != "REPORT") {
					sDetailTblVar = DETAILTABLE.TblVar;
					OLDTABLE = TABLE; // Save table
					TABLE = DETAILTABLE;
					sDetailPageObj = ew_GetPageObjByCtrlId("grid");
					TABLE = OLDTABLE; // Restore table
		##-->
			if (in_array("<!--##=sDetailTblVar##-->", $DetailTblVar)) {
				if (!isset($GLOBALS["<!--##=sDetailPageObj##-->"]))
					$GLOBALS["<!--##=sDetailPageObj##-->"] = new c<!--##=sDetailPageObj##-->;
				if ($GLOBALS["<!--##=sDetailPageObj##-->"]-><!--##=sDetailPrp##-->) {
		<!--## if (CTRL.CtrlID == "add") { ##-->
					$GLOBALS["<!--##=sDetailPageObj##-->"]->CurrentAction = "gridadd";
		<!--## } else if (CTRL.CtrlID == "edit") { ##-->
					$GLOBALS["<!--##=sDetailPageObj##-->"]->CurrentAction = "gridedit";
		<!--## } ##-->
				}
			}
		<!--##
				}
			} // MasterDetail
		##-->
		}
	}

	<!--## } ##-->

<!--##
		}
	}
##-->

<!--## if (CTRL.CtrlID != "gridcls") { ##-->
	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
	<!--## if (CTRL.CtrlType.toLowerCase() == "table" || CTRL.CtrlType.toLowerCase() == "report") { ##-->
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		<!--## if (CTRL.CtrlID == "list" || CTRL.CtrlID == "report") { ##-->
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("<!--##=CTRL.CtrlID##-->", $this->TableVar, $url, "", $this->TableVar, TRUE);
		<!--## } else { ##-->
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("<!--##=sFnList##-->"), "", $this->TableVar, TRUE);
			<!--## if (CTRL.CtrlID == "add") { ##-->
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
			<!--## } else { ##-->
		$PageId = "<!--##=CTRL.CtrlID##-->";
			<!--## } ##-->
		$Breadcrumb->Add("<!--##=CTRL.CtrlID##-->", $PageId, $url);
		<!--## } ##-->
	<!--## } ##-->
	}
<!--## } ##-->

<!--##
	if (bMultiPage) {
##-->
	// Set up multi pages
	function SetupMultiPages() {
		$pages = new cSubPages();
	<!--## if (sMultiPageType == "tabs" || sMultiPageType == "pills") { ##-->
		$pages->Style = "<!--##=sMultiPageType##-->";
	<!--## } ##-->
	<!--##
		for (var i = 0; i <= nPage; i++) {
	##-->
		$pages->Add(<!--##=i##-->);
	<!--##
		} // Page
	##-->
		$this->MultiPages = $pages;
	}
<!--##
	}
##-->

<!--##
	if (bShowMultiPageForDetails && (CTRL.CtrlID == "add" || CTRL.CtrlID == "edit" || CTRL.CtrlID == "view")) {
##-->
	// Set up detail pages
	function SetupDetailPages() {
		$pages = new cSubPages();
	<!--## if (sMultiPageType == "tabs" || sMultiPageType == "pills") { ##-->
		$pages->Style = "<!--##=sMultiPageType##-->";
	<!--## } ##-->
	<!--##
		for (var i = 0, len = arDetailTables.length; i < len; i++) {
			var MasterDetail = goAllMasDets[arDetailTables[i].index];
			OLDTABLE = TABLE; // Save master table
			MASTERTABLE = DB.Tables(TABLE.TblName);
			TABLE = DB.Tables(MasterDetail.DetailTable);
			if (TABLE.TblGen && TABLE.TblType != "REPORT") {
				sDetailTblVar = TABLE.TblVar;
	##-->
		$pages->Add('<!--##=SQuote(sDetailTblVar)##-->');
	<!--##
			}
			TABLE = OLDTABLE; // Restore master table
		}
	##-->
		$this->DetailPages = $pages;
	}
<!--##
	}
##-->

<!--##/session##-->
?>