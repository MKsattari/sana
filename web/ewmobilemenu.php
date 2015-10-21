<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(30, "mmci_home_page", $Language->MenuPhrase("30", "MenuText"), "http://www.044004.ir/sana/web2", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(3, "mmi_sana_person", $Language->MenuPhrase("3", "MenuText"), "sana_personlist.php", -1, "", AllowListMenu('{07091A10-D58A-4784-942B-0E21010F5DFC}sana_person'), FALSE);
$RootMenu->AddMenuItem(16, "mmi_sana_message", $Language->MenuPhrase("16", "MenuText"), "sana_messagelist.php?cmd=resetall", -1, "", AllowListMenu('{07091A10-D58A-4784-942B-0E21010F5DFC}sana_message'), FALSE);
$RootMenu->AddMenuItem(31, "mmi_sana_sms", $Language->MenuPhrase("31", "MenuText"), "sana_smslist.php", -1, "", AllowListMenu('{07091A10-D58A-4784-942B-0E21010F5DFC}sana_sms'), FALSE);
$RootMenu->AddMenuItem(6, "mmi_sana_user", $Language->MenuPhrase("6", "MenuText"), "sana_userlist.php", -1, "", AllowListMenu('{07091A10-D58A-4784-942B-0E21010F5DFC}sana_user'), FALSE);
$RootMenu->AddMenuItem(15, "mmi_sana_station", $Language->MenuPhrase("15", "MenuText"), "sana_stationlist.php", -1, "", AllowListMenu('{07091A10-D58A-4784-942B-0E21010F5DFC}sana_station'), FALSE);
$RootMenu->AddMenuItem(5, "mmi_sana_state", $Language->MenuPhrase("5", "MenuText"), "sana_statelist.php", -1, "", AllowListMenu('{07091A10-D58A-4784-942B-0E21010F5DFC}sana_state'), FALSE);
$RootMenu->AddMenuItem(4, "mmi_sana_project", $Language->MenuPhrase("4", "MenuText"), "sana_projectlist.php", -1, "", AllowListMenu('{07091A10-D58A-4784-942B-0E21010F5DFC}sana_project'), FALSE);
$RootMenu->AddMenuItem(9, "mmi_sana_location_level1", $Language->MenuPhrase("9", "MenuText"), "sana_location_level1list.php", -1, "", AllowListMenu('{07091A10-D58A-4784-942B-0E21010F5DFC}sana_location_level1'), FALSE);
$RootMenu->AddMenuItem(10, "mmi_sana_location_level2", $Language->MenuPhrase("10", "MenuText"), "sana_location_level2list.php", -1, "", AllowListMenu('{07091A10-D58A-4784-942B-0E21010F5DFC}sana_location_level2'), FALSE);
$RootMenu->AddMenuItem(11, "mmi_sana_location_level3", $Language->MenuPhrase("11", "MenuText"), "sana_location_level3list.php", -1, "", AllowListMenu('{07091A10-D58A-4784-942B-0E21010F5DFC}sana_location_level3'), FALSE);
$RootMenu->AddMenuItem(-2, "mmi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
