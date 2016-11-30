<!-- ISADMIN -->
<!-- TEMPLATE -->
<div class="contentBox">
<?php
$redtext = "<span style=\"color: #FF0000\">";
$greentext = "<span style=\"color: #00FF00\">";
$endtext = "</span>";
echo "Starte SQL-Installation...";
// Start
$result = mysql_query("USE ".$databasename);
if ($result === false) {
	echo $redtext."<br><br>Die Datenbank ".$databasename." konnte nicht ausgew&auml;hlt werden!".$endtext;
} else {
	echo $greentext."<br><br>Die Datenbank ".$databasename." wurde erfolgreich ausgew&auml;hlt!".$endtext;
}
@mysql_free_result($result);
// Version 1.0 - Standardtabelle
$result = mysql_query("
CREATE TABLE IF NOT EXISTS ".$tableprefix."gbphistory (
historyid int(11) NOT NULL AUTO_INCREMENT,
type int(11) DEFAULT NULL,
name varchar(100) DEFAULT NULL,
points int(11) DEFAULT NULL,
info text DEFAULT NULL,
timestamp datetime DEFAULT NULL,
PRIMARY KEY (historyid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
");
if ($result === false) {
	echo $redtext."<br><br>Die Tabelle ".$tableprefix."gbphistory konnte nicht erstellt werden!".$endtext;
} else {
	echo $greentext."<br><br>Die Tabelle ".$tableprefix."gbphistory wurde erfolgreich erstellt!".$endtext;
}
@mysql_free_result($result);
// Version 1.0 - Standardtabelle
$result = mysql_query("
CREATE TABLE IF NOT EXISTS ".$tableprefix."guildbank (
entryid int(11) NOT NULL AUTO_INCREMENT,
itemname text DEFAULT NULL,
itemcount int(11) DEFAULT NULL,
bankchar varchar(100) DEFAULT NULL,
itemid int(11) DEFAULT NULL,
itemrare int(11) DEFAULT NULL,
PRIMARY KEY (entryid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
");
if ($result === false) {
	echo $redtext."<br><br>Die Tabelle ".$tableprefix."guildbank konnte nicht erstellt werden!".$endtext;
} else {
	echo $greentext."<br><br>Die Tabelle ".$tableprefix."guildbank wurde erfolgreich erstellt!".$endtext;
}
@mysql_free_result($result);
// Version 1.0 - Standardtabelle
$result = mysql_query("
CREATE TABLE IF NOT EXISTS ".$tableprefix."itempoints (
entryid int(11) NOT NULL AUTO_INCREMENT,
itemid int(11) DEFAULT NULL,
points int(11) DEFAULT NULL,
PRIMARY KEY (entryid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
");
if ($result === false) {
	echo $redtext."<br><br>Die Tabelle ".$tableprefix."itempoints konnte nicht erstellt werden!".$endtext;
} else {
	echo $greentext."<br><br>Die Tabelle ".$tableprefix."itempoints wurde erfolgreich erstellt!".$endtext;
}
@mysql_free_result($result);
// Version 1.0 - Standardtabelle
$result = mysql_query("
CREATE TABLE IF NOT EXISTS ".$tableprefix."member (
charid int(11) NOT NULL AUTO_INCREMENT,
name varchar(100) DEFAULT NULL,
level int(11) DEFAULT NULL,
class varchar(100) DEFAULT NULL,
gbp int(11) DEFAULT NULL,
PRIMARY KEY (charid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
");
if ($result === false) {
	echo $redtext."<br><br>Die Tabelle ".$tableprefix."member konnte nicht erstellt werden!".$endtext;
} else {
	echo $greentext."<br><br>Die Tabelle ".$tableprefix."member wurde erfolgreich erstellt!".$endtext;
}
@mysql_free_result($result);
// Version 1.0 - Standardtabelle
$result = mysql_query("
CREATE TABLE IF NOT EXISTS ".$tableprefix."parsinghistory (
historyid int(11) NOT NULL AUTO_INCREMENT,
timestamp timestamp NULL DEFAULT NULL,
PRIMARY KEY (historyid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
");
if ($result === false) {
	echo $redtext."<br><br>Die Tabelle ".$tableprefix."parsinghistory konnte nicht erstellt werden!".$endtext;
} else {
	echo $greentext."<br><br>Die Tabelle ".$tableprefix."parsinghistory wurde erfolgreich erstellt!".$endtext;
}
@mysql_free_result($result);
// Version 1.6 - Session-ID
$result = mysql_query("
CREATE TABLE IF NOT EXISTS ".$tableprefix."sessions (
sessionid int(11) NOT NULL AUTO_INCREMENT,
sessionhash varchar(100) DEFAULT NULL,
islogin tinyint(1) DEFAULT NULL,
timestamp datetime DEFAULT NULL,
PRIMARY KEY (sessionid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
");
if ($result === false) {
	echo $redtext."<br><br>Die Tabelle ".$tableprefix."sessions konnte nicht erstellt werden!".$endtext;
} else {
	echo $greentext."<br><br>Die Tabelle ".$tableprefix."sessions wurde erfolgreich erstellt!".$endtext;
}
@mysql_free_result($result);
// Version 3.5 - Versteckte Items
$result = mysql_query("
CREATE TABLE IF NOT EXISTS ".$tableprefix."hiddenitems (
entryid int(11) NOT NULL AUTO_INCREMENT,
itemid int(11) DEFAULT NULL,
PRIMARY KEY (entryid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
");
if ($result === false) {
	echo $redtext."<br><br>Die Tabelle ".$tableprefix."hiddenitems konnte nicht erstellt werden!".$endtext;
} else {
	echo $greentext."<br><br>Die Tabelle ".$tableprefix."hiddenitems wurde erfolgreich erstellt!".$endtext;
}
@mysql_free_result($result);
// Version 4.6 - Notizen
$result = mysql_query("
CREATE TABLE IF NOT EXISTS ".$tableprefix."itemnotes (
entryid int(11) NOT NULL AUTO_INCREMENT,
itemid int(11) DEFAULT NULL,
itemnote TEXT DEFAULT NULL,
PRIMARY KEY (entryid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
");
if ($result === false) {
	echo $redtext."<br><br>Die Tabelle ".$tableprefix."itemnotes konnte nicht erstellt werden!".$endtext;
} else {
	echo $greentext."<br><br>Die Tabelle ".$tableprefix."itemnotes wurde erfolgreich erstellt!".$endtext;
}
@mysql_free_result($result);
// Fertig
echo "<br><br>SQL-Installation abgeschlossen! Alles gr&uuml;n = ERFOLG!";
?>
</div>
