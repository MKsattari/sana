<?php
<!--##session pdfclass##-->
//
// Class for export to PDF
//
class cExportPdf extends cExportBase {

	var $PageOrientation = "portrait"; // To be setup after creating an instance
	var $PageSize = "a4"; // To be setup after creating an instance

	// Export
	function Export() {
		echo $this->Text;
	}

}
<!--##/session##-->
?>