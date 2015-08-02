<!-- TEMPLATE -->
<div class="contentBox">
<?php
// HINWEIS
$stand = "Kein Eintrag";
$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."parsinghistory ORDER BY timestamp DESC LIMIT 1");
while ($row = @mysql_fetch_assoc($result)) {
	$stand = mysqlDate($row["timestamp"]);
}
@mysql_free_result($result);
echo "Aktueller Stand: ".$stand."\n";
?>
<div class="outerMargin">
<?php
// SORT
$sortindex = toSaferValue(@$_GET["sortindex"]);
if ($sortindex == "") {
	$sortindex = 1;
}
$sortorder = toSaferValue(@$_GET["sortorder"]);
if ($sortorder == "") {
	$sortorder = SORT_ASC;
} else {
	if ($sortorder == "desc") {
		$sortorder = SORT_DESC;
	} else {
		$sortorder = SORT_ASC;
	}
}
// TABELLE HEADER
$tb_header = new MyTableHeader;
$tb_header->setTitle(array("Menge", "Itemname", "Bankchar", "Punkte*"));
$tb_header->setCenter(array(true, false, false, true));
$tb_header->setWidth(array(100, 450, 150, 100));
$tb_header->setSortindex($sortindex);
$tb_header->setSortorder($sortorder);
// TABELLE DATA
$tb_table = new MyTable;
$tb_table->setHeader($tb_header);
$tb_table->setTemppage(toSaferValue(@$_GET["page"]));
// INVENTAR
$result = mysql_query("SELECT *, ".$databasename.".".$tableprefix."guildbank.itemid AS use_itemid FROM ".$databasename.".".$tableprefix."guildbank LEFT JOIN ".$databasename.".".$tableprefix."itempoints ON ".$databasename.".".$tableprefix."guildbank.itemid = ".$databasename.".".$tableprefix."itempoints.itemid");
$counter = 0;
$inventar = array();
while ($row = @mysql_fetch_assoc($result)) {
	$foundinv = false;
	for ($a = 0; $a < count($inventar); $a++) {
		if ($inventar[$a]["id"] == $row["use_itemid"]) {
			$foundinv = true;
			$inventar[$a]["count"] = $inventar[$a]["count"] + $row["itemcount"];
			$pos = strpos($inventar[$a]["bank"], $row["bankchar"]);
			if($pos === false) {
				$inventar[$a]["bank"] = $inventar[$a]["bank"].", ".$row["bankchar"];
			}
		}
	}
	if (!$foundinv) {
		$inventar[$counter] = array();
		$inventar[$counter]["id"] = $row["use_itemid"];
		if ($row["itemname"] == NULL) {
			$inventar[$counter]["name"] = "ID: ".$row["use_itemid"];
		} else {
			$inventar[$counter]["name"] = $row["itemname"];
		}
		if ($row["itemrare"] == NULL) {
			$inventar[$counter]["rare"] = 0;
		} else {
			$inventar[$counter]["rare"] = $row["itemrare"];
		}
		if ($row["itemcount"] == NULL) {
			$inventar[$counter]["count"] = 0;
		} else {
			$inventar[$counter]["count"] = $row["itemcount"];
		}
		if ($row["bankchar"] == NULL) {
			$inventar[$counter]["bank"] = "Keiner";
		} else {
			$inventar[$counter]["bank"] = $row["bankchar"];
		}
		if ($row["points"] == NULL) {
			$inventar[$counter]["punkte"] = 0;
		} else {
			$inventar[$counter]["punkte"] = $row["points"];
		}
		$counter = $counter + 1;
	}
}
@mysql_free_result($result);
for ($a = 0; $a < count($inventar); $a++) {
	$tb_table->addRow(array($inventar[$a]["count"], $inventar[$a]["name"], $inventar[$a]["bank"], $inventar[$a]["punkte"]));
	$tb_table->addHtmlrow(array($inventar[$a]["count"], "<div class=\"rare".$inventar[$a]["rare"]."\"><a href=\"http://datenbank.welli-it.de/?item=".$inventar[$a]["id"]."\" target=\"_BLANK\">".$inventar[$a]["name"]."</a></div>", $inventar[$a]["bank"], $inventar[$a]["punkte"]));
}
// TABELLE SORT AND PRINT
$tb_table->sortTable();
$tb_table->printTable();
?>
</table>
<br>
*Hierbei handelt es sich um durchschnittliche Werte. 0 bedeutet, dass keine Punkte hinterlegt sind!
</div>
</div>