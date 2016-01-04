<!-- ISADMIN -->
<!-- TEMPLATE -->
<div class="contentBox">
<?php
// HINWEIS
$name = toSaferValue(@$_GET["name"]);
if ($name == "") {
	echo "Hinweis: Es werden nur die letzten 50 Eintr&auml;ge angezeigt!\n";
}
?>
<div class="outerMargin">
<?php
// SORT
$sortindex = toSaferValue(@$_GET["sortindex"]);
if ($sortindex == "") {
	$sortindex = 0;
}
$sortorder = toSaferValue(@$_GET["sortorder"]);
if ($sortorder == "") {
	$sortorder = SORT_DESC;
} else {
	if ($sortorder == "desc") {
		$sortorder = SORT_DESC;
	} else {
		$sortorder = SORT_ASC;
	}
}
// TABELLE HEADER
$tb_header = new MyTableHeader;
$tb_header->setTitle(array("Zeit", "Name", "Typ", "Punkte", "Bemerkung", "ADMIN"));
$tb_header->setCenter(array(false, false, false, true, false));
$tb_header->setWidth(array(150, 150, 100, 100, 300, 100));
$tb_header->setSortindex($sortindex);
$tb_header->setSortorder($sortorder);
// TABELLE DATA
$tb_table = new MyTable;
$tb_table->setHeader($tb_header);
$tb_table->setTemppage(toSaferValue(@$_GET["page"])."&name=".toSaferValue(@$_GET["name"]));
// HISTORY
if ($name == "") {
	$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."gbphistory ORDER BY timestamp DESC LIMIT 50");
} else {
	$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."gbphistory WHERE name = '".$name."' ORDER BY timestamp DESC");
}
$types = array();
$types[1] = "<img src=\"./images/list_add.png\" alt=\"Einlagern\" title=\"Einlagern\"> Einlagern";
$types[-1] = "<img src=\"./images/list_remove.png\" alt=\"Auslagern\" title=\"Auslagern\"> Auslagern";
while ($row = @mysql_fetch_assoc($result)) {
	$tb_table->addRow(array($row["timestamp"], $row["name"], $row["type"], $row["points"], $row["info"], "[link]"));
	$tb_table->addHtmlrow(array(mysqlDate($row["timestamp"]), "<a href=\"?page=gbphistoryadmin&name=".$row["name"]."\">".$row["name"]."</a>", $types[$row["type"]], $row["points"], $row["info"], "<a href=\"index.php?page=editgbpentry&id=".$row["historyid"]."\">&auml;ndern</a>"));
}
@mysql_free_result($result);
// TABELLE SORT AND PRINT
$tb_table->sortTable();
$tb_table->printTable();
?>
</table>
</div>
</div>
