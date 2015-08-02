<!-- ISADMIN -->
<!-- TEMPLATE -->
<div class="contentBox">
<?php
$found = false;
$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."itempoints");
while ($row = @mysql_fetch_assoc($result)) {
	if (toSaferValue(@$_POST["additempoints_id"]) == $row["itemid"]) {
		$found = true;
		break;
	}
}
@mysql_free_result($result);
if ($found) {
	mysql_query("UPDATE ".$databasename.".".$tableprefix."itempoints SET points = ".toSaferValue(@$_POST["additempoints_points"])." WHERE itemid = ".toSaferValue(@$_POST["additempoints_id"]));
	postErrOK(1, 600, "Der Eintrag wurde erfolgreich gespeichert!");
} else {
	mysql_query("INSERT INTO ".$databasename.".".$tableprefix."itempoints (itemid, points) VALUES (".toSaferValue(@$_POST["additempoints_id"]).", ".toSaferValue(@$_POST["additempoints_points"]).")");
	postErrOK(1, 600, "Der Eintrag wurde erfolgreich gespeichert!");
}
postRedirect(3, "index.php?page=itempoints");
?>
</div>