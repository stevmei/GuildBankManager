<?php
/***************************************************************************************
**                                                                                    **
**  GBM - GuildBankManager                                                            **
**  Author: Steven M.                                                                 **
**                                                                                    **
**  Datei: index.php                                                                  **
**                                                                                    **
**  Primaerer Skript-Ablauf wird hier geregelt. Es sollten keine Aenderungen mehr     **
**  vorgenommen werden. Nachtraegliche Skripte koennen jedoch noch eingebunden        **
**  werden.                                                                           **
**                                                                                    **
***************************************************************************************/

// Lade Konfigurationen, Datenbank-Funktionen und allgemeine Funktionen
include("./config.php");
include("./sql.php");
include("./classes.php");
include("./functions.php");
// Starten der PHP-Session, falls nicht bereits geschehen
@session_start();
// Stelle Verbindung zur Datenbank her
connect_DB();
// Informationen zum angeforderten Template
global $page;
$page = toSaferValue(@$_GET["page"]);
$page = str_replace("..", "", $page);
if (!file_exists("./temp/".$page.".php")) {
	$page = "home";
}
// Ueberpruefe auf erfolgreiche Installation
$page = checkDatabase($page);
// Ueberpruefen der Templates auf Berechtigungen
$page = checkPermission($page);
// Einbinden von Header, Content und Footer
include("./temp/head.php");
include("./temp/".$page.".php");
include("./temp/footer.php");
// Schliesse Verbindung zur Datenbank
close_DB();
?>