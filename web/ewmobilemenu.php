<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(15, "mmi_sana_station", $Language->MenuPhrase("15", "MenuText"), "sana_stationlist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(3, "mmi_sana_person", $Language->MenuPhrase("3", "MenuText"), "sana_personlist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(2, "mmi_sana_object", $Language->MenuPhrase("2", "MenuText"), "sana_objectlist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(6, "mmi_sana_user", $Language->MenuPhrase("6", "MenuText"), "sana_userlist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(5, "mmi_sana_state", $Language->MenuPhrase("5", "MenuText"), "sana_statelist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(4, "mmi_sana_project", $Language->MenuPhrase("4", "MenuText"), "sana_projectlist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(9, "mmi_sana_location_level1", $Language->MenuPhrase("9", "MenuText"), "sana_location_level1list.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(10, "mmi_sana_location_level2", $Language->MenuPhrase("10", "MenuText"), "sana_location_level2list.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(11, "mmi_sana_location_level3", $Language->MenuPhrase("11", "MenuText"), "sana_location_level3list.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(12, "mmi_sana_location_level4", $Language->MenuPhrase("12", "MenuText"), "sana_location_level4list.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(13, "mmi_sana_location_level5", $Language->MenuPhrase("13", "MenuText"), "sana_location_level5list.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(14, "mmi_sana_location_level6", $Language->MenuPhrase("14", "MenuText"), "sana_location_level6list.php", -1, "", TRUE, FALSE);
$RootMenu->Render();
?>
<!-- End Main Menu -->
