<?php

// personID
// personName
// lastName
// nationalID
// nationalNumber
// passportNumber
// fatherName
// gender
// locationLevel1
// locationLevel2
// mobilePhone
// picture

?>
<?php if ($sana_person->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $sana_person->TableCaption() ?></h4> -->
<table id="tbl_sana_personmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $sana_person->TableCustomInnerHtml ?>
	<tbody>
<?php if ($sana_person->personID->Visible) { // personID ?>
		<tr id="r_personID">
			<td><?php echo $sana_person->personID->FldCaption() ?></td>
			<td<?php echo $sana_person->personID->CellAttributes() ?>>
<span id="el_sana_person_personID">
<span<?php echo $sana_person->personID->ViewAttributes() ?>>
<?php echo $sana_person->personID->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sana_person->personName->Visible) { // personName ?>
		<tr id="r_personName">
			<td><?php echo $sana_person->personName->FldCaption() ?></td>
			<td<?php echo $sana_person->personName->CellAttributes() ?>>
<span id="el_sana_person_personName">
<span<?php echo $sana_person->personName->ViewAttributes() ?>>
<?php echo $sana_person->personName->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sana_person->lastName->Visible) { // lastName ?>
		<tr id="r_lastName">
			<td><?php echo $sana_person->lastName->FldCaption() ?></td>
			<td<?php echo $sana_person->lastName->CellAttributes() ?>>
<span id="el_sana_person_lastName">
<span<?php echo $sana_person->lastName->ViewAttributes() ?>>
<?php echo $sana_person->lastName->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sana_person->nationalID->Visible) { // nationalID ?>
		<tr id="r_nationalID">
			<td><?php echo $sana_person->nationalID->FldCaption() ?></td>
			<td<?php echo $sana_person->nationalID->CellAttributes() ?>>
<span id="el_sana_person_nationalID">
<span<?php echo $sana_person->nationalID->ViewAttributes() ?>>
<?php echo $sana_person->nationalID->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sana_person->nationalNumber->Visible) { // nationalNumber ?>
		<tr id="r_nationalNumber">
			<td><?php echo $sana_person->nationalNumber->FldCaption() ?></td>
			<td<?php echo $sana_person->nationalNumber->CellAttributes() ?>>
<span id="el_sana_person_nationalNumber">
<span<?php echo $sana_person->nationalNumber->ViewAttributes() ?>>
<?php echo $sana_person->nationalNumber->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sana_person->passportNumber->Visible) { // passportNumber ?>
		<tr id="r_passportNumber">
			<td><?php echo $sana_person->passportNumber->FldCaption() ?></td>
			<td<?php echo $sana_person->passportNumber->CellAttributes() ?>>
<span id="el_sana_person_passportNumber">
<span<?php echo $sana_person->passportNumber->ViewAttributes() ?>>
<?php echo $sana_person->passportNumber->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sana_person->fatherName->Visible) { // fatherName ?>
		<tr id="r_fatherName">
			<td><?php echo $sana_person->fatherName->FldCaption() ?></td>
			<td<?php echo $sana_person->fatherName->CellAttributes() ?>>
<span id="el_sana_person_fatherName">
<span<?php echo $sana_person->fatherName->ViewAttributes() ?>>
<?php echo $sana_person->fatherName->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sana_person->gender->Visible) { // gender ?>
		<tr id="r_gender">
			<td><?php echo $sana_person->gender->FldCaption() ?></td>
			<td<?php echo $sana_person->gender->CellAttributes() ?>>
<span id="el_sana_person_gender">
<span<?php echo $sana_person->gender->ViewAttributes() ?>>
<?php echo $sana_person->gender->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sana_person->locationLevel1->Visible) { // locationLevel1 ?>
		<tr id="r_locationLevel1">
			<td><?php echo $sana_person->locationLevel1->FldCaption() ?></td>
			<td<?php echo $sana_person->locationLevel1->CellAttributes() ?>>
<span id="el_sana_person_locationLevel1">
<span<?php echo $sana_person->locationLevel1->ViewAttributes() ?>>
<?php echo $sana_person->locationLevel1->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sana_person->locationLevel2->Visible) { // locationLevel2 ?>
		<tr id="r_locationLevel2">
			<td><?php echo $sana_person->locationLevel2->FldCaption() ?></td>
			<td<?php echo $sana_person->locationLevel2->CellAttributes() ?>>
<span id="el_sana_person_locationLevel2">
<span<?php echo $sana_person->locationLevel2->ViewAttributes() ?>>
<?php echo $sana_person->locationLevel2->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sana_person->mobilePhone->Visible) { // mobilePhone ?>
		<tr id="r_mobilePhone">
			<td><?php echo $sana_person->mobilePhone->FldCaption() ?></td>
			<td<?php echo $sana_person->mobilePhone->CellAttributes() ?>>
<span id="el_sana_person_mobilePhone">
<span<?php echo $sana_person->mobilePhone->ViewAttributes() ?>>
<?php echo $sana_person->mobilePhone->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sana_person->picture->Visible) { // picture ?>
		<tr id="r_picture">
			<td><?php echo $sana_person->picture->FldCaption() ?></td>
			<td<?php echo $sana_person->picture->CellAttributes() ?>>
<span id="el_sana_person_picture">
<span>
<?php echo ew_GetFileViewTag($sana_person->picture, $sana_person->picture->ListViewValue()) ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
