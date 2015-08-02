<!-- ISADMIN -->
<!-- TEMPLATE -->
<div class="contentBox">
<?php
$type = 1;
if (toSaferValue(@$_POST["addgbpentry_type"]) == "Auslagern") {
	$type = -1;
}
$found = false;
$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."member");
while ($row = @mysql_fetch_assoc($result)) {
	if (toSaferValue(@$_POST["addgbpentry_name"]) == $row["name"]) {
		$found = true;
		break;
	}
}
@mysql_free_result($result);
if ($found) {
	mysql_query("INSERT INTO ".$databasename.".".$tableprefix."gbphistory (type, name, points, info, timestamp) VALUES (".$type.", '".toSaferValue(@$_POST["addgbpentry_name"])."', ".toSaferValue(@$_POST["addgbpentry_points"]).", '".toSaferValue(@$_POST["addgbpentry_info"])."', NOW())");
	if ($type == 1) {
		mysql_query("UPDATE ".$databasename.".".$tableprefix."member SET gbp = gbp + ".toSaferValue(@$_POST["addgbpentry_points"])." WHERE name = '".toSaferValue(@$_POST["addgbpentry_name"])."'");
	} else {
		mysql_query("UPDATE ".$databasename.".".$tableprefix."member SET gbp = gbp - ".toSaferValue(@$_POST["addgbpentry_points"])." WHERE name = '".toSaferValue(@$_POST["addgbpentry_name"])."'");
	}
	postErrOK(1, 600, "Der Eintrag wurde erfolgreich gespeichert!");
} else {
	postErrOK(0, 600, "Dieses Mitglied ist nicht in der Datenbank eingetragen!");
}
postRedirect(3, "index.php?page=addgbpentry");
?>
</div>