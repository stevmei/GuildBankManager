<!-- ISADMIN -->
<!-- TEMPLATE -->
<div class="contentBox">
<?php
$found = false;
$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."hiddenitems");
while ($row = @mysql_fetch_assoc($result)) {
	if (toSaferValue(@$_GET["id"]) == $row["itemid"]) {
		$found = true;
		break;
	}
}
@mysql_free_result($result);
if ($found) {
	mysql_query("DELETE FROM ".$databasename.".".$tableprefix."hiddenitems WHERE itemid = ".toSaferValue(@$_GET["id"]));
	postErrOK(1, 600, "Der Eintrag wurde erfolgreich gespeichert!");
} else {
	mysql_query("INSERT INTO ".$databasename.".".$tableprefix."hiddenitems (itemid) VALUES (".toSaferValue(@$_GET["id"]).")");
	postErrOK(1, 600, "Der Eintrag wurde erfolgreich gespeichert!");
}
postRedirect(3, "index.php?page=itemvisibility&filter=&sortindex=0&sortorder=asc");
?>
</div>
