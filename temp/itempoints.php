<!-- ISADMIN -->
<!-- TEMPLATE -->
<div class="contentBox">
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
$tb_header->setTitle(array("Itemname", "Punkte", "ADMIN"));
$tb_header->setCenter(array(false, true, true));
$tb_header->setWidth(array(400, 100, 100));
$tb_header->setSortindex($sortindex);
$tb_header->setSortorder($sortorder);
$tb_header->setExtrasort(true, 0);
// TABELLE DATA
$tb_table = new MyTable;
$tb_table->setHeader($tb_header);
$tb_table->setTemppage(toSaferValue(@$_GET["page"]));
$tb_table->setExtrasort(true, 0);
// INVENTAR
$result = mysql_query("SELECT *, ".$databasename.".".$tableprefix."guildbank.itemid AS use_itemid FROM ".$databasename.".".$tableprefix."guildbank LEFT JOIN ".$databasename.".".$tableprefix."itempoints ON ".$databasename.".".$tableprefix."guildbank.itemid = ".$databasename.".".$tableprefix."itempoints.itemid UNION SELECT *, ".$databasename.".".$tableprefix."itempoints.itemid AS use_itemid FROM ".$databasename.".".$tableprefix."guildbank RIGHT JOIN ".$databasename.".".$tableprefix."itempoints ON ".$databasename.".".$tableprefix."guildbank.itemid = ".$databasename.".".$tableprefix."itempoints.itemid WHERE ".$databasename.".".$tableprefix."guildbank.itemid IS NULL");
$counter = 0;
$inventar = array();
while ($row = @mysql_fetch_assoc($result)) {
	$foundinv = false;
	for ($a = 0; $a < count($inventar); $a++) {
		if ($inventar[$a]["id"] == $row["use_itemid"]) {
			$foundinv = true;
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
	$tb_table->addRow(array($inventar[$a]["name"], $inventar[$a]["punkte"], "[link]"));
	$tb_table->addHtmlrow(array("<div class=\"rare".$inventar[$a]["rare"]."\"><a href=\"http://datenbank.welli-it.de/?item=".$inventar[$a]["id"]."\" target=\"_BLANK\">".$inventar[$a]["name"]."</a></div>", $inventar[$a]["punkte"], "<a href=\"index.php?page=edititempoints&id=".$inventar[$a]["id"]."\">&auml;ndern</a>"));
	$tb_table->addExtraKey(array($inventar[$a]["rare"]));
}
// TABELLE SORT AND PRINT
$tb_table->sortTable();
$tb_table->printTable();
?>
</table>
</div>
</div>
