<!-- ISADMIN -->
<!-- TEMPLATE -->
<div class="contentBox">
<?php
if (toSaferValue(@$_GET["applied"]) != "yes") {
	echo "<h1>Sind sie sich wirklich sicher?</h1><br>Hierdurch werden vergangene Gildenbank-Eintr&auml;ge gel&ouml;scht und die Punkte zusammengefasst.<br>Dies spart Speicherplatz und hilft dabei, die Datenbank schnell zu halten.";
	echo "<br><br><a href=\"?page=scripts/cleanupdb&applied=yes\">JA, ICH BIN MIR SICHER!</a>";
} else {
	mysql_query("TRUNCATE ".$databasename.".".$tableprefix."parsinghistory");
	mysql_query("INSERT INTO ".$databasename.".".$tableprefix."parsinghistory (timestamp) VALUES (NOW())");
	mysql_query("TRUNCATE ".$databasename.".".$tableprefix."gbphistory");
	$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."member ORDER BY gbp DESC");
	while ($row = @mysql_fetch_assoc($result)) {
		if ($row["gbp"] != 0) {
			if ($row["gbp"] > 0) {
				mysql_query("INSERT INTO ".$databasename.".".$tableprefix."gbphistory (type, name, points, info, timestamp) VALUES (1, '".$row["name"]."', ".$row["gbp"].", 'Datenbank-Bereinigung', NOW())");
			} else {
				mysql_query("INSERT INTO ".$databasename.".".$tableprefix."gbphistory (type, name, points, info, timestamp) VALUES (-1, '".$row["name"]."', ".-$row["gbp"].", 'Datenbank-Bereinigung', NOW())");
			}
		}
	}
	@mysql_free_result($result);
	echo "Bereinigung ausgef&uuml;hrt!";
}
?>
</div>
