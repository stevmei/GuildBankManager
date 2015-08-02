<?php
/***************************************************************************************
**                                                                                    **
**  GBM - GuildBankManager                                                            **
**  Author: Steven M.                                                                 **
**                                                                                    **
**  Datei: functions.php                                                              **
**                                                                                    **
**  Hier sind alle wichtigen Funktionen ausgelagert. Das Hinzufuegen und Verwenden    **
**  von eigenen Funktionen ist auf eigene Gefahr moeglich.                            **
**                                                                                    **
***************************************************************************************/

// Anzeigen einer Fehler-Meldung bzw. einer Erfolgreich-Meldung
//   type:   0=Fehler, 1=OK
//   width:  Breite der Meldung
//   text:   Anzuzeigender Text der Meldung
function postErrOK($type, $width, $text) {
	if ($type == 1) {
		echo "<div class=\"outerMargin\">\n<div class=\"simpleBoxOutline success\" style=\"width: ".$width."px\">\n<table>\n<colgroup>\n<col width=\"50px\">\n<col>\n</colgroup>\n<tr>\n<td style=\"text-align: center\"><img src=\"./images/dialog_ok.png\" alt=\"OK\" title=\"OK\"></td>\n<td>".$text."</td>\n</tr>\n</table>\n</div>\n</div>\n";
	} else {
		echo "<div class=\"outerMargin\">\n<div class=\"simpleBoxOutline error\" style=\"width: ".$width."px\">\n<table>\n<colgroup>\n<col width=\"50px\">\n<col>\n</colgroup>\n<tr>\n<td style=\"text-align: center\"><img src=\"./images/dialog_error.png\" alt=\"Fehler\" title=\"Fehler\"></td>\n<td>".$text."</td>\n</tr>\n</table>\n</div>\n</div>\n";
	}
}

// Absicherung gegen SQL-Injections, ...
//   value:  Abzusichernder String
function toSaferValue($value) {
	global $sqldb;
	$value = ltrim($value);
	// $value = htmlentities($value, ENT_QUOTES, "UTF-8");
	$order = array("\r\n", "\n\r", "\n", "\r");
	$replace = "<br>";
	$value = str_replace($order, $replace, $value);
	if ($sqldb != NULL) {
		$value = mysql_real_escape_string($value);
	}
	return $value;
}

// Anzeigen einer Weiterleitung
//   delay:    Verzoegerung der Weiterleitung in Sekunden
//   address:  Adresse, zu welcher weitergeleitet werden soll
function postRedirect($delay, $address) {
	echo "<div class=\"outerMargin\">\n<div class=\"simpleBoxOutline\" style=\"width: 600px\">\n<table>\n<colgroup>\n<col width=\"50px\">\n<col>\n</colgroup>\n<tr>\n<td style=\"text-align: center\"><img src=\"./images/load.gif\" alt=\"Lade...\" title=\"Lade...\"></td>\n<td>Sie werden in ".$delay." Sekunden weitergeleitet...<br>Wenn Sie nicht warten m&ouml;chten, klicken Sie bitte <a href=\"".$address."\">HIER</a></td>\n</tr>\n</table>\n<meta http-equiv=\"refresh\" content=\"".$delay."; URL=".$address."\" />\n</div>\n</div>\n";
}

// Umwandlung von Datetime (SQL) ins deutsche Format
//   stamp:  Der SQL-Datetime-Stamp als String
function mysqlDate($stamp) {
	$datec = strtotime($stamp);
	return date("d.m.Y H:i:s", $datec);
}

// Funktion zur Anmeldung eines Administrators
function adminLogin() {
	global $admin_passwd;
	global $databasename;
	global $tableprefix;
	if (toSaferValue(@$_POST["login_passwd"]) != $admin_passwd) {
		postErrOK(0, 600, "Es trat ein Fehler auf!");
		postRedirect(3, "index.php?page=home");
	} else {
		$newid = uniqid();
		mysql_query("INSERT INTO ".$databasename.".".$tableprefix."sessions (sessionhash, islogin, timestamp) VALUES ('".$newid."', 1, NOW())");
		$_SESSION["gbm_sessid"] = $newid;
		postErrOK(1, 600, "Sie haben sich erfolgreich angemeldet!");
		postRedirect(3, "index.php?page=home");
	}
}

// Funktion zur Abmeldung eines Administrators
function adminLogout() {
	global $databasename;
	global $tableprefix;
	if (isset($_SESSION["gbm_sessid"])) {
		mysql_query("UPDATE ".$databasename.".".$tableprefix."sessions SET islogin = 0 WHERE sessionhash = '".$_SESSION["gbm_sessid"]."'");
	}
	@session_destroy();
	postErrOK(1, 600, "Sie haben sich erfolgreich abgemeldet!");
	postRedirect(3, "index.php?page=home");
}

// Ueberpruefen, ob ein User als Administrator angemeldet ist
function isLogin() {
	global $databasename;
	global $tableprefix;
	if (isset($_SESSION["gbm_sessid"])) {
		$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."sessions WHERE sessionhash = '".$_SESSION["gbm_sessid"]."' LIMIT 1");
		while ($row = @mysql_fetch_assoc($result)) {
			if ($row["islogin"] == 1) {
				@mysql_free_result($result);
				return true;
			} else {
				@mysql_free_result($result);
				return false;
			}
		}
		@mysql_free_result($result);
	}
	return false;
}

// Prueft, ob ein Template oder ein Skript eine Anmeldung als Administrator benoetigt.
// Das jeweilige Template oder Skript muss innerhalb der ersten 5 Zeilen ein <!-- ISADMIN --> enthalten!
//   page:  Das zu ueberpruefende Template
function checkPermission($page) {
	$parsefile = @fopen("./temp/".$page.".php", "r");
	$linenum = 0;
	while ((!feof($parsefile)) && ($linenum < 5)) {
		$line = fgets($parsefile);
		if (strpos($line, "<!-- ISADMIN -->") !== false) {
			if (isLogin()) {
				return $page;
			} else {
				return "nopermission";
			}
		}
		$linenum = $linenum + 1;
	}
	return $page;
}

// Prueft, ob sich die Tabelle sessions in der Datenbank befindet.
//   page:  Das zu ueberpruefende Template
function checkDatabase($page) {
	global $databasename;
	global $tableprefix;
	if ($page == "scripts/firstinstall") {
		return $page;
	}
	$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."sessions LIMIT 1");
	if ($result === false) {
		@mysql_free_result($result);
		return "noinstallation";
	}
	@mysql_free_result($result);
	return $page;
}

// Gibt den Text aus, welcher im Header angezeigt werden soll
function getLoginState() {
	global $databasename;
	global $tableprefix;
	if (checkDatabase("test") != "test") {
		@mysql_free_result($result);
		return "Installations-Modus\n";
	}
	if (isset($_SESSION["gbm_sessid"])) {
		$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."sessions WHERE sessionhash = '".$_SESSION["gbm_sessid"]."' LIMIT 1");
		while ($row = @mysql_fetch_assoc($result)) {
			if ($row["islogin"] == 1) {
				@mysql_free_result($result);
				return "<a href=\"index.php?page=administration\">Administration</a> | <a href=\"index.php?page=scripts/logout\">Abmelden</a>\n";
			} else {
				@mysql_free_result($result);
				return "<a href=\"index.php?page=login\">Anmelden</a>\n";
			}
		}
		@mysql_free_result($result);
	}
	return "<a href=\"index.php?page=login\">Anmelden</a>\n";
}

// Verknuepft den Klassennamen mit dem entsprechenden Symbol/Icon
function getClassIcons() {
	$symbols = array();
	$symbols["Druide"] = "druide.gif";
	$symbols["Hexenmeister"] = "hexenmeister.gif";
	$symbols["Jäger"] = "jaeger.gif";
	$symbols["Krieger"] = "krieger.gif";
	$symbols["Magier"] = "magier.gif";
	$symbols["Paladin"] = "paladin.gif";
	$symbols["Priester"] = "priester.gif";
	$symbols["Schurke"] = "schurke.gif";
	$symbols["Schamane"] = "schamane.gif";
	$symbols["Druid"] = "druide.gif";
	$symbols["Warlock"] = "hexenmeister.gif";
	$symbols["Hunter"] = "jaeger.gif";
	$symbols["Warrior"] = "krieger.gif";
	$symbols["Mage"] = "magier.gif";
	$symbols["Paladin"] = "paladin.gif";
	$symbols["Priest"] = "priester.gif";
	$symbols["Rogue"] = "schurke.gif";
	$symbols["Shaman"] = "schamane.gif";
	return $symbols;
}
?>