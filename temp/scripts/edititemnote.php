<!-- ISADMIN -->
<!-- TEMPLATE -->
<div class="contentBox">
<?php
$found = false;
$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."itemnotes");
while ($row = @mysql_fetch_assoc($result)) {
	if (toSaferValue(@$_POST["edititemnote_id"]) == $row["itemid"]) {
		$found = true;
		break;
	}
}
@mysql_free_result($result);
if ($found) {
	mysql_query("UPDATE ".$databasename.".".$tableprefix."itemnotes SET itemnote = '".toSaferValue(@$_POST["edititemnote_note"])."' WHERE itemid = ".toSaferValue(@$_POST["edititemnote_id"]));
	postErrOK(1, 600, "Der Eintrag wurde erfolgreich gespeichert!");
} else {
	mysql_query("INSERT INTO ".$databasename.".".$tableprefix."itemnotes (itemid, itemnote) VALUES (".toSaferValue(@$_POST["edititemnote_id"]).", '".toSaferValue(@$_POST["edititemnote_note"])."')");
	postErrOK(1, 600, "Der Eintrag wurde erfolgreich gespeichert!");
}
postRedirect(3, "index.php?page=itemnotes&filter=&sortindex=0&sortorder=asc");
?>
</div>
