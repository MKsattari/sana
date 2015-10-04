<?php
<!--##session report_excel_function##-->
<!--##
	if (bExportExcel) {
##-->

	// Export report to EXCEL
	function ExportReportExcel($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-excel' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xls');
		echo $html;
	}

<!--##
	};
##-->
<!--##/session##-->
?>