<!-- ISADMIN -->
<!-- TEMPLATE -->
<div class="contentBox">
<?php
if (!file_exists("./".toSaferValue(@$_POST["parselua_file"]))) {
	postErrOK(0, 600, "Es trat ein Fehler auf!");
	postRedirect(3, "index.php?page=parselua");
} else {
	mysql_query("TRUNCATE TABLE ".$databasename.".".$tableprefix."member");
	echo "<div class=\"simpleBoxOutline\" style=\"width: 600px; text-align: left\">\n";
	echo "<b>Beginne Parsing...</b>\n";
	$parsefile = fopen("./".toSaferValue(@$_POST["parselua_file"]), "r");
	$hereweare = 0;
	$bankcount = 0;
	$tobank = "";
	$matches = NULL;
	while (!feof($parsefile)) {
		$line = fgets($parsefile);
		$line = trim($line);
		if (substr($line, 0, 16) == "gbm_guildmembers") {
			$hereweare = 1;
		}
		if (substr($line, 0, 11) == "gbm_excepts") {
			$hereweare = 2;
		}
		if (substr($line, 0, 8) == "gbm_bank") {
			$hereweare = 3;
		}
		$newbank = false;
		if (preg_match("@\[\"[A-Za-z]*\"\]@", $line, $matches) == 1) {
			$hereweare = 3;
			$bankcount = $bankcount + 1;
			$tobank = substr($matches[0], 2, strlen($matches[0])-4);
			$newbank = true;
			echo "<br>".$bankcount.". Bank gefunden: ".$tobank;
			mysql_query("DELETE FROM ".$databasename.".".$tableprefix."guildbank WHERE bankchar = '".$tobank."'");
		}
		if ((substr($line, 0, 1) == "[") && !($newbank)) {
			switch ($hereweare) {
				case 1:
					$line = substr(strstr($line, "\""), 1);
					$line = strstr($line, "\"", true);
					$parsed = explode("%", $line);
					echo "<br>Spieler in der Datei gefunden: ".$parsed[0]." - Level ".$parsed[1]." ".$parsed[2]."\n";
					mysql_query("INSERT INTO ".$databasename.".".$tableprefix."member (name, level, class, gbp) VALUES ('".$parsed[0]."', ".$parsed[1].", '".$parsed[2]."', 0)");
					break;
				default:
					break;
			}
			if (($hereweare == 3) && !($newbank)) {
				$line = substr(strstr($line, "\""), 1);
				$line = strstr($line, "\"", true);
				$parsed = explode("%", $line);
				echo "<br>Inventar-Eintrag in der Datei gefunden: ".$parsed[0]."x ".$parsed[3]." (ID: ".$parsed[1].")\n";
				mysql_query("INSERT INTO ".$databasename.".".$tableprefix."guildbank (itemname, itemcount, bankchar, itemid, itemrare) VALUES ('".$parsed[3]."', ".$parsed[0].", '".$tobank."', ".$parsed[1].", ".$parsed[2].")");
			}
		}
	}
	echo "<br><b>Parsing beendet!</b>\n";
	fclose($parsefile);
	echo "<br><b>Beginne DB-Aktualisierung...</b>\n";
	$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."gbphistory");
	while ($row = @mysql_fetch_assoc($result)) {
		if ($row["type"] == "1") {
			mysql_query("UPDATE ".$databasename.".".$tableprefix."member SET gbp = gbp + ".$row["points"]." WHERE name = '".$row["name"]."'");
		} else {
			mysql_query("UPDATE ".$databasename.".".$tableprefix."member SET gbp = gbp - ".$row["points"]." WHERE name = '".$row["name"]."'");
		}
	}
	@mysql_free_result($result);
	echo "<br><b>DB-Aktualisierung beendet!</b>\n";
	echo "</div>\n";
	mysql_query("INSERT INTO ".$databasename.".".$tableprefix."parsinghistory (timestamp) VALUES (NOW())");
}
?>
</div>