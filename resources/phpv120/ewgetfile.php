<?php
<!--##session phpmain##-->

	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {

		// Get key
		<!--##
			ORIFIELD = FIELD;
			for (var i = 0, len = arKeyFlds.length; i < len; i++) {
				if (GetFldObj(arKeyFlds[i])) {
		##-->
		if (@$_GET["<!--##=gsFldParm##-->"] <> "") {
			$this-><!--##=gsFldParm##-->->setQueryStringValue($_GET["<!--##=gsFldParm##-->"]);
		} else {
			$this->Page_Terminate(); // Exit
			exit();
		}
		<!--##
				}
			} // KeyField
			FIELD = ORIFIELD;
			goFld = goTblFlds.Fields[FIELD.FldName];
		##-->
		
		$objBinary = new cUpload('<!--##=TABLE.TblVar##-->', '<!--##=FIELD.FldVar##-->');
		
		<!--##
			thumbnailwidth = FIELD.FldTagImgWidth; // Default width
			thumbnailheight = FIELD.FldTagImgHeight; // Default height
			if (thumbnailwidth <= 0 && thumbnailheight <= 0) {
				thumbnailwidth = "EW_THUMBNAIL_DEFAULT_WIDTH";
			 	thumbnailheight = "EW_THUMBNAIL_DEFAULT_HEIGHT";
			}
		##-->
		// Show thumbnail
		$bShowThumbnail = (@$_GET["showthumbnail"] == "1");
		
		if (@$_GET["thumbnailwidth"] == "" && @$_GET["thumbnailheight"] == "") {
			$iThumbnailWidth = <!--##=thumbnailwidth##-->; // Set default width
			$iThumbnailHeight = <!--##=thumbnailheight##-->; // Set default height
		} else {
			if (@$_GET["thumbnailwidth"] <> "") {
				$iThumbnailWidth = $_GET["thumbnailwidth"];
				if (!is_numeric($iThumbnailWidth) || $iThumbnailWidth < 0) $iThumbnailWidth = 0;
			}
			if (@$_GET["thumbnailheight"] <> "") {
				$iThumbnailHeight = $_GET["thumbnailheight"];
				if (!is_numeric($iThumbnailHeight) || $iThumbnailHeight < 0) $iThumbnailHeight = 0;
			}
		}
		
		<!--## if (ew_IsNotEmpty(TABLE.TblKey)) { ##-->
		
		$sFilter = $this->KeyFilter();
		
		// Set up filter (SQL WHERE clause) and get return SQL
		// SQL constructor in <!--##=gsTblVar##--> class, <!--##=gsTblVar##-->info.php
		
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		if ($this->Recordset = $conn->Execute($sSql)) {

			if (!$this->Recordset->EOF) {
				if (ob_get_length())
					ob_end_clean();

				$objBinary->Value = $this->Recordset->fields('<!--##=SQuote(FIELD.FldName)##-->');
				
				<!--## if (!bTblDBMySql) { ##-->
				if (is_array($objBinary->Value) || is_object($objBinary->Value)) // Byte array
					$objBinary->Value = ew_BytesToStr($objBinary->Value);
				<!--## } else { ##-->
				$objBinary->Value = $objBinary->Value;
				<!--## } ##-->
				
				if ($bShowThumbnail) {
					ew_ResizeBinary($objBinary->Value, $iThumbnailWidth, $iThumbnailHeight);
				}
				
				$data = $objBinary->Value;

				<!--##
					var sFileName = ew_IsNotEmpty(FIELD.FileNameFld) ? ", $this->Recordset->fields('" + SQuote(FIELD.FileNameFld) + "')" : "";
					if (ew_IsNotEmpty(FIELD.FileTypeFld)) {
				##-->
				if (trim(strval($this->Recordset->fields('<!--##=SQuote(FIELD.FileTypeFld)##-->'))) <> "") {
					header("Content-type: ". $this->Recordset->fields('<!--##=SQuote(FIELD.FileTypeFld)##-->'));
				} else {
					if (strpos(ew_ServerVar("HTTP_USER_AGENT"), "MSIE") === FALSE)
					 header("Content-type: " . ew_ContentType(substr($data, 0, 11)<!--##=sFileName##-->));
				}
				<!--##
					} else {
				##-->
				if (strpos(ew_ServerVar("HTTP_USER_AGENT"), "MSIE") === FALSE)
					header("Content-type: " . ew_ContentType(substr($data, 0, 11)<!--##=sFileName##-->));
				<!--##
					}
				##-->

				<!--##
					if (ew_IsNotEmpty(FIELD.FileNameFld)) {
				##-->
				if (trim(strval($this->Recordset->fields('<!--##=SQuote(FIELD.FileNameFld)##-->'))) <> "") {
					header("Content-Disposition: attachment; filename=\"" . $this->Recordset->fields('<!--##=SQuote(FIELD.FileNameFld)##-->') . "\"");
				}
				<!--##
					}
				##-->

				if (substr($data, 0, 2) == "PK" && strpos($data, "[Content_Types].xml") > 0 &&
					strpos($data, "_rels") > 0 && strpos($data, "docProps") > 0) { // Fix Office 2007 documents
					if (substr($data, -4) <> "\0\0\0\0")
						$data .= "\0\0\0\0";
				}
				
				echo $data;
			
			}
			
			$this->Recordset->Close();
		}
		<!--## } ##-->
	}
<!--##/session##-->
?>