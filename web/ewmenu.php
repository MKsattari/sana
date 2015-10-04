<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, "mi_sana_location", $Language->MenuPhrase("1", "MenuText"), "sana_locationlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(2, "mi_sana_object", $Language->MenuPhrase("2", "MenuText"), "sana_objectlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(3, "mi_sana_person", $Language->MenuPhrase("3", "MenuText"), "sana_personlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, "mi_sana_project", $Language->MenuPhrase("4", "MenuText"), "sana_projectlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(5, "mi_sana_state", $Language->MenuPhrase("5", "MenuText"), "sana_statelist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(6, "mi_sana_user", $Language->MenuPhrase("6", "MenuText"), "sana_userlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
