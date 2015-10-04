<!--##session exportemail_htm##-->
<form id="ewEmailForm" class="form-horizontal ewForm ewEmailForm" action="<?php echo ew_CurrentPage() ?>">
<?php if ($Page->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $Page->Token ?>">
<?php } ?>
<input type="hidden" name="export" id="export" value="email">
	<div class="form-group">
		<label class="<!--##=ewBootstrapLabelClass##-->" for="sender"><!--##@EmailFormSender##--></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->"><input type="text" class="<!--##=ewBootstrapInputClass##-->" name="sender" id="sender"></div>
	</div>
	<div class="form-group">
		<label class="<!--##=ewBootstrapLabelClass##-->" for="recipient"><!--##@EmailFormRecipient##--></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->"><input type="text" class="<!--##=ewBootstrapInputClass##-->" name="recipient" id="recipient"></div>
	</div>
	<div class="form-group">
		<label class="<!--##=ewBootstrapLabelClass##-->" for="cc"><!--##@EmailFormCc##--></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->"><input type="text" class="<!--##=ewBootstrapInputClass##-->" name="cc" id="cc"></div>
	</div>
	<div class="form-group">
		<label class="<!--##=ewBootstrapLabelClass##-->" for="bcc"><!--##@EmailFormBcc##--></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->"><input type="text" class="<!--##=ewBootstrapInputClass##-->" name="bcc" id="bcc"></div>
	</div>
	<div class="form-group">
		<label class="<!--##=ewBootstrapLabelClass##-->" for="subject"><!--##@EmailFormSubject##--></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->"><input type="text" class="<!--##=ewBootstrapInputClass##-->" name="subject" id="subject"></div>
	</div>
	<div class="form-group">
		<label class="<!--##=ewBootstrapLabelClass##-->" for="message"><!--##@EmailFormMessage##--></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->"><textarea class="<!--##=ewBootstrapInputClass##-->" rows="6" name="message" id="message"></textarea></div>
	</div>
<!--
	<div class="form-group">
		<label class="<!--##=ewBootstrapLabelClass##-->"><!--##@EmailFormContentType##--></label>
		<div class="<!--##=ewBootstrapRightColumnClass##-->">
		<label class="radio-inline ewRadio" style="white-space: nowrap;"><input type="radio" name="contenttype" value="html" checked="checked"><!--##@EmailFormContentTypeHtml##--></label>
		<label class="radio-inline ewRadio" style="white-space: nowrap;"><input type="radio" name="contenttype" value="url"><!--##@EmailFormContentTypeUrl##--></label>
		</div>
	</div>
-->
	<input type="hidden" name="contenttype" value="html">
</form>
<!--##/session##-->
