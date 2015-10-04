<?php
<!--##session audittrail##-->
<!--##
	bUseUserIDForAuditTrail = bUserID && PROJ.GetV("UseUserIDForAuditTrail");
	if (TABLE.TblAuditTrail) {
##-->

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = '<!--##=SQuote(TABLE.TblName)##-->';

		<!--## if (bUseUserIDForAuditTrail) { ##-->
		$usr = CurrentUserID();
		<!--## } else { ##-->
		$usr = CurrentUserName();
		<!--## } ##-->

		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}
	
	<!--##
		if (bAuditTrailOnAdd) {
	##-->
	
	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;

		if (!$this->AuditTrailOnAdd) return;

		$table = '<!--##=SQuote(TABLE.TblName)##-->';
	
		// Get key value
		$key = "";
		<!--##
			for (var i = 0, len = arKeyFlds.length; i < len; i++) {
				if (GetFldObj(arKeyFlds[i])) {
		##-->
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['<!--##=SQuote(gsFldName)##-->'];
		<!--##
				}
			} // KeyField
		##-->
	
		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();

		<!--## if (bUseUserIDForAuditTrail) { ##-->
		$usr = CurrentUserID();
		<!--## } else { ##-->
		$usr = CurrentUserName();
		<!--## } ##-->

		foreach (array_keys($rs) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$newvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				<!--## if (TABLE.TblName == PROJ.SecTbl) { ##-->
				if ($fldname == '<!--##=SQuote(PROJ.SecPasswdFld)##-->')
					$newvalue = $Language->Phrase("PasswordMask");
				<!--## } ##-->
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
	}
	
	<!--##
		}
	##-->
	
	<!--##
		if (bAuditTrailOnEdit) {
	##-->
	
	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;

		if (!$this->AuditTrailOnEdit) return;

		$table = '<!--##=SQuote(TABLE.TblName)##-->';

		// Get key value
		$key = "";
		<!--##
			for (var i = 0, len = arKeyFlds.length; i < len; i++) {
				if (GetFldObj(arKeyFlds[i])) {
		##-->
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['<!--##=SQuote(gsFldName)##-->'];
		<!--##
				}
			} // KeyField
		##-->
	
		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();

		<!--## if (bUseUserIDForAuditTrail) { ##-->
		$usr = CurrentUserID();
		<!--## } else { ##-->
		$usr = CurrentUserName();
		<!--## } ##-->

		foreach (array_keys($rsnew) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") { // Password Field
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					<!--## if (TABLE.TblName == PROJ.SecTbl) { ##-->
					if ($fldname == '<!--##=SQuote(PROJ.SecPasswdFld)##-->') {
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					}
					<!--## } ##-->
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
	}
	
	<!--##
		}
	##-->
	
	<!--##
		if (bAuditTrailOnDelete) {
	##-->
	
	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		global $Language;

		if (!$this->AuditTrailOnDelete) return;

		$table = '<!--##=SQuote(TABLE.TblName)##-->';
		
		// Get key value
		$key = "";
		<!--##
			for (var i = 0, len = arKeyFlds.length; i < len; i++) {
				if (GetFldObj(arKeyFlds[i])) {
		##-->
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['<!--##=SQuote(gsFldName)##-->'];
		<!--##
				}
			} // KeyField
		##-->
		
		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();

		<!--## if (bUseUserIDForAuditTrail) { ##-->
		$curUser = CurrentUserID();
		<!--## } else { ##-->
		$curUser = CurrentUserName();
		<!--## } ##-->

		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$oldvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				<!--## if (TABLE.TblName == PROJ.SecTbl) { ##-->
				if ($fldname == '<!--##=SQuote(PROJ.SecPasswdFld)##-->')
					$oldvalue = $Language->Phrase("PasswordMask");
				<!--## } ##-->
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
			}
		}
	}
	
	<!--##
		}
	##-->

	<!--##
		if (bAuditTrailOnView) {
	##-->
	
	// Write Audit Trail (view page)
	function WriteAuditTrailOnView(&$rs) {
		global $Language;

		if (!$this->AuditTrailOnView) return;

		$table = '<!--##=SQuote(TABLE.TblName)##-->';
	
		// Get key value
		$key = "";
		<!--##
			for (var i = 0, len = arKeyFlds.length; i < len; i++) {
				if (GetFldObj(arKeyFlds[i])) {
		##-->
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['<!--##=SQuote(gsFldName)##-->'];
		<!--##
				}
			} // KeyField
		##-->
	
		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();

		<!--## if (bUseUserIDForAuditTrail) { ##-->
		$usr = CurrentUserID();
		<!--## } else { ##-->
		$usr = CurrentUserName();
		<!--## } ##-->

		if ($this->AuditTrailOnViewData) { // Write all data
			foreach (array_keys($rs) as $fldname) {
				if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
						$oldvalue = $Language->Phrase("PasswordMask"); // Password Field
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
						if (EW_AUDIT_TRAIL_TO_DATABASE)
							$oldvalue = $rs[$fldname];
						else
							$oldvalue = "[MEMO]"; // Memo Field
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
						$oldvalue = "[XML]"; // XML Field
					} else {
						$oldvalue = $rs[$fldname];
					}
					<!--## if (TABLE.TblName == PROJ.SecTbl) { ##-->
					if ($fldname == '<!--##=SQuote(PROJ.SecPasswdFld)##-->')
						$oldvalue = $Language->Phrase("PasswordMask");
					<!--## } ##-->
					ew_WriteAuditTrail("log", $dt, $id, $usr, "V", $table, $fldname, $key, $oldvalue, "");
				}
			}
		} else { // Write record id only
			ew_WriteAuditTrail("log", $dt, $id, $usr, "V", $table, "", $key, "", "");
		}
	}
	
	<!--##
		}
	##-->

	<!--##
		if (bAuditTrailOnSearch) {
	##-->
	
	// Write Audit Trail (search)
	function WriteAuditTrailOnSearch($searchparm, $searchsql) {
		global $Language;

		if (!$this->AuditTrailOnSearch) return;

		$table = '<!--##=SQuote(TABLE.TblName)##-->';

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();

		<!--## if (bUseUserIDForAuditTrail) { ##-->
		$usr = CurrentUserID();
		<!--## } else { ##-->
		$usr = CurrentUserName();
		<!--## } ##-->

		ew_WriteAuditTrail("log", $dt, $id, $usr, "S", $table, "", "", $searchsql, $searchparm);

	}
	
	<!--##
		}
	##-->

<!--##
	}
##-->

<!--##
	if (TABLE.TblSendMailOnAdd) {
##-->
	
	<!--##
		if ((CTRL.CtrlID == "list" && (bInlineAdd || bInlineCopy || bGridAdd)) ||
			CTRL.CtrlID == "add" ||
			CTRL.CtrlID == "register") {
	##-->
	
	// Send email after add success
	function SendEmailOnAdd(&$rs) {
		global $Language;

		$sTable = '<!--##=SQuote(TABLE.TblName)##-->';
		$sSubject = $sTable . " " . $Language->Phrase("RecordInserted");
		$sAction = $Language->Phrase("ActionInserted");
	
		// Get key value
		$sKey = "";
		<!--##
			for (var i = 0, len = arKeyFlds.length; i < len; i++) {
				if (GetFldObj(arKeyFlds[i])) {
		##-->
		if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$sKey .= $rs['<!--##=SQuote(gsFldName)##-->'];
		<!--##
				}
			} // KeyField
		##-->
	
		$Email = new cEmail();
		$Email->Load(EW_EMAIL_NOTIFY_TEMPLATE);
		$Email->ReplaceSender(EW_SENDER_EMAIL); // Replace Sender
		$Email->ReplaceRecipient(EW_RECIPIENT_EMAIL); // Replace Recipient
		$Email->ReplaceSubject($sSubject); // Replace Subject
		$Email->ReplaceContent("<!--table-->", $sTable);
		$Email->ReplaceContent("<!--key-->", $sKey);
		$Email->ReplaceContent("<!--action-->", $sAction);
	
	<!--## if (SYSTEMFUNCTIONS.ServerScriptExist("Table","Email_Sending")) { ##-->
		$Args = array("rsnew" => $rs);
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $Args))
			$bEmailSent = $Email->Send();
	<!--## } else { ##-->
		$bEmailSent = $Email->Send();
	<!--## } ##-->
	
		// Send email failed
		if (!$bEmailSent)
			$this->setFailureMessage($Email->SendErrDescription);
	
	}
	
	<!--##
		}
	##-->
	
<!--##
	}
##-->

<!--##
	if (TABLE.TblSendMailOnEdit) {
##-->
	
	<!--##
		if ((CTRL.CtrlID == "list" && (bInlineEdit || bGridEdit)) ||
			CTRL.CtrlID == "edit" || CTRL.CtrlID == "update") {
	##-->
	
	// Send email after update success
	function SendEmailOnEdit(&$rsold, &$rsnew) {
		global $Language;

		$sTable = '<!--##=SQuote(TABLE.TblName)##-->';
		$sSubject = $sTable . " ". $Language->Phrase("RecordUpdated");
		$sAction = $Language->Phrase("ActionUpdated");
	
		// Get key value
		$sKey = "";
		<!--##
			for (var i = 0, len = arKeyFlds.length; i < len; i++) {
				if (GetFldObj(arKeyFlds[i])) {
		##-->
		if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$sKey .= $rsold['<!--##=SQuote(gsFldName)##-->'];
		<!--##
				}
			} // KeyField
		##-->
	
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
		$Args["rsold"] = &$rsold;
		$Args["rsnew"] = &$rsnew;
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $Args))
			$bEmailSent = $Email->Send();
	<!--## } else { ##-->
		$bEmailSent = $Email->Send();
	<!--## } ##-->
		
		// Send email failed
		if (!$bEmailSent)
			$this->setFailureMessage($Email->SendErrDescription);

	}
	
	<!--##
		}
	##-->
	
<!--##
	}
##-->

<!--##/session##-->
?>