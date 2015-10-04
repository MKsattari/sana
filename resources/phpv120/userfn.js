<!--##session global##-->
// Global user functions
<!--##~SYSTEMFUNCTIONS.GetClientScript("Global","Global Code")##-->
<!--##/session##-->


<!--##session event##-->
// Field event handlers
(function($) {
<!--##
	for (var i = 1, len = DB.Tables.Count(); i <= len; i++) {
		TABLE = DB.Tables.Seq(i);
		var sTblVar = TABLE.TblVar;
		if (TABLE.TblGen) {
			ew_LoadCurrentFields(); // Load current fields
			var flds = goTblFlds.AllFields;
			for (var j = 0, len2 = flds.length; j < len2; j++) {
				goFld = goTblFlds.Fields[flds[j]];
				var sFldName = goFld.FldName;
				var sFldVar = goFld.FldVar;
				if (SYSTEMFUNCTIONS.ClientScriptExist("Field","Events")) {
##-->
	// Table '<!--##=sTblVar##-->' Field '<!--##=sFldName##-->'
	$('[data-table=<!--##=sTblVar##-->][data-field=<!--##=sFldVar##-->]').on(
		<!--##~SYSTEMFUNCTIONS.GetClientScript("Field","Events")##-->
	);
<!--##
				}
			}
		}
	} // Table
##-->
})(jQuery);

<!--##/session##-->