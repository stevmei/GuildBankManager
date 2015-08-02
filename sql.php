<?php
/***************************************************************************************
**                                                                                    **
**  GBM - GuildBankManager                                                            **
**  Author: Steven M.                                                                 **
**                                                                                    **
**  Datei: sql.php                                                                    **
**                                                                                    **
**  Hier sind die Funktionen zum Datenbank-Verbingungsaufbau sowie -abbau             **
**  gespeichert. Bitte keine Aenderungen vornehmen!                                   **
**                                                                                    **
***************************************************************************************/

// Initialisierung der Datenbank mit NULL
$sqldb = NULL;

// Verbindungsaufbau
function connect_DB() {
	global $sqldb;
	global $dbhost;
	global $dbuser;
	global $dbpasswd;
	$sqldb = mysql_connect($dbhost, $dbuser, $dbpasswd);
	if ($sqldb === false) {
		echo "<div><span style=\"color: #FF0000\">FEHLER: ES KONNTE KEINE VERBINDUNG ZUR SQL-DATENBANK HERGESTELLT WERDEN! Bitte &uuml;berpr&uuml;fen Sie die Einstellungen der config.php!</span></div>";
		exit();
	}
	mysql_set_charset("utf8", $sqldb);
	mysql_query("SET NAMES utf8");
}

// Verbingungsabbau
function close_DB() {
	global $sqldb;
	mysql_close($sqldb);
}
?>