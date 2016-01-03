<!-- TEMPLATE -->
<div class="contentBox">
<?php
// PASSWORT-SCHUTZ
if (!isAllowed()) {
	echo "</div>";
	return;
}
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
// FILTER
$onlychar = toSaferValue(@$_GET["filter"]);
$onlycharwhere = "";
if ($onlychar != "") {
	$onlycharwhere = " WHERE bankchar = '".$onlychar."'";
}
// TABELLE HEADER
$tb_header = new MyTableHeader;
$tb_header->setTitle(array("Menge", "Itemname", "Bankchar", "Punkte*"));
$tb_header->setCenter(array(true, false, false, true));
$tb_header->setWidth(array(100, 400, 200, 100));
$tb_header->setSortindex($sortindex);
$tb_header->setSortorder($sortorder);
$tb_header->setExtrasort(true, 1);
$tb_header->setFilter($onlychar);
// TABELLE DATA
$tb_table = new MyTable;
$tb_table->setHeader($tb_header);
$tb_table->setTemppage(toSaferValue(@$_GET["page"]));
$tb_table->setExtrasort(true, 1);
// HIDDEN ITEMS
$result = mysql_query("SELECT *FROM ".$databasename.".".$tableprefix."hiddenitems");
$counter = 0;
$hiddenitems = array();
while ($row = @mysql_fetch_assoc($result)) {
	$hiddenitems[$counter] = $row["itemid"];
	$counter = $counter + 1;
}
// INVENTAR
$result = mysql_query("SELECT *, ".$databasename.".".$tableprefix."guildbank.itemid AS use_itemid FROM ".$databasename.".".$tableprefix."guildbank LEFT JOIN ".$databasename.".".$tableprefix."itempoints ON ".$databasename.".".$tableprefix."guildbank.itemid = ".$databasename.".".$tableprefix."itempoints.itemid".$onlycharwhere);
$counter = 0;
$inventar = array();
while ($row = @mysql_fetch_assoc($result)) {
	$foundinv = false;
	for ($a = 0; $a < count($inventar); $a++) {
		if ($inventar[$a]["id"] == $row["use_itemid"]) {
			$foundinv = true;
			$inventar[$a]["count"] = $inventar[$a]["count"] + $row["itemcount"];
			if (array_key_exists($row["bankchar"], $inventar[$a]["bank"])) {
				$inventar[$a]["bank_count"][$row["bankchar"]] = $inventar[$a]["bank_count"][$row["bankchar"]] + $row["itemcount"];
			} else {
				$inventar[$a]["bank_count"][$row["bankchar"]] = $row["itemcount"];
			}
			if ($row["use_itemid"] == 999999) {
				$rest = $inventar[$counter]["bank_count"][$row["bankchar"]];
				$gold = floor($rest / 10000);
				$rest = $rest - ($gold * 10000);
				$silber = floor($rest / 100);
				$kupfer = $rest - ($silber * 100);
				$inventar[$a]["bank"][$row["bankchar"]] = "<a href=\"?page=guildbank&filter=".$row["bankchar"]."\">".$row["bankchar"]."</a> (".$gold."g ".$silber."s ".$kupfer."k)";
			} else {
				$inventar[$a]["bank"][$row["bankchar"]] = "<a href=\"?page=guildbank&filter=".$row["bankchar"]."\">".$row["bankchar"]."</a> (".$inventar[$a]["bank_count"][$row["bankchar"]].")";
			}
		}
	}
	if ((!$foundinv) && (!in_array($row["use_itemid"], $hiddenitems))) {
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
		$inventar[$counter]["bank"] = array();
		$inventar[$counter]["bank_count"] = array();
		if ($row["bankchar"] == NULL) {
			$inventar[$counter]["bank"]["Keiner"] = "Keiner";
			$inventar[$counter]["bank_count"]["Keiner"] = $row["itemcount"];
		} else {
			if ($row["use_itemid"] == 999999) {
				$rest = $row["itemcount"];
				$gold = floor($rest / 10000);
				$rest = $rest - ($gold * 10000);
				$silber = floor($rest / 100);
				$kupfer = $rest - ($silber * 100);
				$inventar[$counter]["bank"][$row["bankchar"]] = "<a href=\"?page=guildbank&filter=".$row["bankchar"]."\">".$row["bankchar"]."</a> (".$gold."g ".$silber."s ".$kupfer."k)";
			} else {
				$inventar[$counter]["bank"][$row["bankchar"]] = "<a href=\"?page=guildbank&filter=".$row["bankchar"]."\">".$row["bankchar"]."</a> (".$row["itemcount"].")";
			}
			$inventar[$counter]["bank_count"][$row["bankchar"]] = $row["itemcount"];
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
	$invstring = "";
	$invstringbr = "";
	$keys = array_keys($inventar[$a]["bank"]);
    foreach($keys as $key){
		$invstring = $invstring.$invstringbr.$inventar[$a]["bank"][$key];
		$invstringbr = "<br>";
	}
	$tb_table->addRow(array($inventar[$a]["count"], $inventar[$a]["name"], $invstring, $inventar[$a]["punkte"]));
	if ($inventar[$a]["id"] == 999999) {
		$rest = $inventar[$a]["count"];
		$gold = floor($rest / 10000);
		$rest = $rest - ($gold * 10000);
		$silber = floor($rest / 100);
		$kupfer = $rest - ($silber * 100);
		$tb_table->addHtmlrow(array("1", "<div class=\"rare".$inventar[$a]["rare"]."\">".$gold." <img src=\"images/money_gold.gif\" alt=\"Gold\" title=\"Gold\"> ".$silber." <img src=\"images/money_silver.gif\" alt=\"Silber\" title=\"Silber\"> ".$kupfer." <img src=\"images/money_copper.gif\" alt=\"Kupfer\" title=\"Kupfer\"></div>", $invstring, $inventar[$a]["punkte"]));
	} else {
		$tb_table->addHtmlrow(array($inventar[$a]["count"], "<div class=\"rare".$inventar[$a]["rare"]."\"><a href=\"http://datenbank.welli-it.de/?item=".$inventar[$a]["id"]."\" target=\"_BLANK\">".$inventar[$a]["name"]."</a></div>", $invstring, $inventar[$a]["punkte"]));
	}
	$tb_table->addExtraKey(array($inventar[$a]["rare"]));
}
// TABELLE SORT AND PRINT
$tb_table->sortTable();
$tb_table->printTable();
?>
<br>
*Hierbei handelt es sich um durchschnittliche Werte. 0 bedeutet, dass keine Punkte hinterlegt sind!
</div>
</div>
