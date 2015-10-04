<?php
<!--##session report_word_function##-->
<!--##
	if (bExportWord) {
##-->

	// Export report to WORD
	function ExportReportWord($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-word' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.doc');
		echo $html;
	}

<!--##
	};
##-->
<!--##/session##-->
?>