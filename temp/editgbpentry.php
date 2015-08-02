<!-- ISADMIN -->
<!-- TEMPLATE -->
<div class="contentBox">
<?php
$tempid = toSaferValue(@$_GET["id"]);
$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."gbphistory WHERE historyid = ".$tempid);
$tempselin = "";
$tempselout = "";
while ($row = @mysql_fetch_assoc($result)) {
	if ($row["type"] == 1) {
		$tempselin = " selected=\"selected\"";
	} else {
		$tempselout = " selected=\"selected\"";
	}
	$tempname = $row["name"];
	$temppoints = $row["points"];
	$tempinfo = $row["info"];
}
@mysql_free_result($result);
?>
<div class="outerMargin">
<form name="editgbpentry_form" action="?page=scripts/editgbpentry" method="post" enctype="multipart/form-data">
<table class="myTable" width="600px">
<colgroup>
<col width="200px">
<col width="400px">
</colgroup>
<tr>
<th colspan="2">Bitte f&uuml;llen Sie die erforderlichen Daten aus:</th>
</tr>
<tr>
<td>Eintrag-Typ:</td>
<td>
<select class="myInput" name="editgbpentry_type" size="1">
<option<?php echo $tempselin;?>>Einlagern</option>
<option<?php echo $tempselout;?>>Auslagern</option>
</select>
</td>
</tr>
<tr class="myTableAlt">
<td>Mitglied:</td>
<td><input class="myInput" type="text" name="editgbpentry_name" value="<?php echo $tempname;?>"></td>
</tr>
<tr>
<td>Wert:</td>
<td><input class="myInput" type="text" name="editgbpentry_points" value="<?php echo $temppoints;?>"></td>
</tr>
<tr class="myTableAlt">
<td>Bemerkung:</td>
<td><input class="myInput" type="text" name="editgbpentry_info" value="<?php echo $tempinfo;?>"></td>
</tr>
<tr class="myTableFooter">
<td class="" colspan="2"><input class="myInput" type="hidden" name="editgbpentry_id" value="<?php echo $tempid;?>"><input type="submit" name="editgbpentry_submit" value="Daten absenden"></td>
</tr>
</table>
</form>
</div>
</div>