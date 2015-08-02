<!-- TEMPLATE -->
<div class="contentBox">
<?php
// HINWEIS
echo "Hinweis: M&ouml;glicherweise werden nicht alle R&auml;nge angezeigt!\n";
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
$tb_header->setTitle(array("Platz", "Name", "Level", "Klasse", "Gildenbank-Konto", "Diese Woche (KW)"));
$tb_header->setCenter(array(true, false, true, false, true, true));
$tb_header->setWidth(array(50, 150, 100, 200, 150, 150));
$tb_header->setSortindex($sortindex);
$tb_header->setSortorder($sortorder);
// TABELLE DATA
$tb_table = new MyTable;
$tb_table->setHeader($tb_header);
$tb_table->setTemppage(toSaferValue(@$_GET["page"]));
// WOCHEN-PUNKTE
$monday = strtotime('last monday', strtotime('tomorrow'));
$sqlstring = date("Y-m-d H:i:s", $monday);
$weekpoints = array();
$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."gbphistory WHERE type = 1 AND timestamp >= '".$sqlstring."'");
while ($row = @mysql_fetch_assoc($result)) {
	if (!array_key_exists($row["name"], $weekpoints)) {
		$weekpoints[$row["name"]] = $row["points"];
	} else {
		$weekpoints[$row["name"]] = $weekpoints[$row["name"]] + $row["points"];
	}
}
@mysql_free_result($result);
// MITGLIEDER
$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."member ORDER BY gbp DESC");
$counter = 1;
$symbols = getClassIcons();
while ($row = @mysql_fetch_assoc($result)) {
	if (!array_key_exists($row["name"], $weekpoints)) {
		$weekpoints[$row["name"]] = 0;
	}
	$tb_table->addRow(array($counter, $row["name"], $row["level"], $row["class"], $row["gbp"], $weekpoints[$row["name"]]));
	$tb_table->addHtmlrow(array($counter, "<a href=\"?page=gbphistory&name=".$row["name"]."\">".$row["name"]."</a>", $row["level"], "<img src=\"./images/classes/".$symbols[$row["class"]]."\" alt=\"".$row["class"]."\" title=\"".$row["class"]."\"> ".$row["class"], "<a href=\"?page=gbphistory&name=".$row["name"]."\">".$row["gbp"]."</a>", "<a href=\"?page=gbphistory&name=".$row["name"]."\">".$weekpoints[$row["name"]]."</a>"));
	$counter++;
}
@mysql_free_result($result);
// TABELLE SORT AND PRINT
$tb_table->sortTable();
$tb_table->printTable();
?>
</div>
</div>