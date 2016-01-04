<!-- ISADMIN -->
<!-- TEMPLATE -->
<div class="contentBox">
<?php
$type = 1;
if (toSaferValue(@$_POST["editgbpentry_type"]) == "Auslagern") {
	$type = -1;
}
$found = false;
$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."member");
while ($row = @mysql_fetch_assoc($result)) {
	if (toSaferValue(@$_POST["editgbpentry_name"]) == $row["name"]) {
		$found = true;
		
		break;
	}
}
@mysql_free_result($result);
$oldpoints = 0;
$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."gbphistory WHERE historyid = ".toSaferValue(@$_POST["editgbpentry_id"]));
while ($row = @mysql_fetch_assoc($result)) {
	$oldpoints = $row["type"]*$row["points"];
}
@mysql_free_result($result);
if ($found) {
	mysql_query("UPDATE ".$databasename.".".$tableprefix."gbphistory SET type = ".$type.", name = '".toSaferValue(@$_POST["editgbpentry_name"])."', points = ".toSaferValue(@$_POST["editgbpentry_points"]).", info = '".toSaferValue(@$_POST["editgbpentry_info"])."' WHERE historyid = ".toSaferValue(@$_POST["editgbpentry_id"]));
	if ($type == 1) {
		mysql_query("UPDATE ".$databasename.".".$tableprefix."member SET gbp = gbp + ".toSaferValue(@$_POST["editgbpentry_points"])." - ".$oldpoints." WHERE name = '".toSaferValue(@$_POST["editgbpentry_name"])."'");
	} else {
		mysql_query("UPDATE ".$databasename.".".$tableprefix."member SET gbp = gbp - ".toSaferValue(@$_POST["editgbpentry_points"])." - ".$oldpoints." WHERE name = '".toSaferValue(@$_POST["editgbpentry_name"])."'");
	}
	postErrOK(1, 600, "Der Eintrag wurde erfolgreich gespeichert!");
} else {
	postErrOK(0, 600, "Dieses Mitglied ist nicht in der Datenbank eingetragen!");
}
postRedirect(3, "index.php?page=gbphistoryadmin&name=&filter=&sortindex=0&sortorder=desc");
?>
</div>
